<?php
namespace addons\swiftfin\jobs;

use addons\swiftfin\helpers\RouteHelper;
use addons\swiftfin\helpers\SwiftfinHelper;
use addons\swiftfin\models\containers\swift\SwaPackage;
use addons\swiftfin\models\containers\swift\SwtContainer;
use addons\swiftfin\models\SwiftFinType;
use addons\swiftfin\SwiftfinModule;
use common\base\RegularJob;
use common\document\Document;
use common\helpers\FileHelper;
use common\models\ImportError;
use Exception;
use Yii;

class ImportJob extends RegularJob
{
    const DOCUMENTS_PER_JOB = 100;

    /**
     * Максимальная длина читаемой порции документов
     * @var integer
     */
    public $maxDocuments;

    public $deleteSource = false;

    private $_jobResource;

    private $_settings;

    public function setUp()
    {
        parent::setUp();

        $this->maxDocuments = isset($this->args['maxDocuments']) ? $this->args['maxDocuments'] : static::DOCUMENTS_PER_JOB;
        $this->deleteSource = isset($this->args['deleteSource']) ? $this->args['deleteSource'] : false;

        $this->_jobResource = Yii::$app->registry->getImportResource(SwiftfinModule::SERVICE_ID, 'job');
    }

    public function perform()
    {
        $this->_settings = Yii::$app->settings->get('swiftfin:swiftfin');

        $this->log('Importing SwiftFin data', false, 'regular-jobs');
        $this->processSwift();
        $this->processXml();
    }

    protected function processSwift()
    {
        $this->processResource(SwiftfinModule::RESOURCE_IMPORT_SWIFT, Document::ORIGIN_FILE);
    }

    protected function processXml()
    {
        $this->processResource(SwiftfinModule::RESOURCE_IMPORT_XML, Document::ORIGIN_XMLFILE);
    }

    /**
     * Process file
     *
     * @param string $resource Document Resourse
     * @param string $origin   Document origin
     */
    protected function processResource($resource, $origin)
    {
        $importResource = Yii::$app->registry->getImportResource(SwiftfinModule::SERVICE_ID, $resource);

        $filesCount = count($importResource->contents);
        if ($filesCount) {
            $this->log("Found {$filesCount} files in {$resource} resource");
        }

        $count = 0;
        foreach ($importResource->contents as $filePath) {
            $this->processFile($filePath, $origin);

            $count++;

            if ($count > $this->maxDocuments) {
                break;
            }
        }
    }

    /**
     * Process file
     *
     * @param string $filePath File path
     * @param string $origin   File origin
     * @return boolean
     */
    protected function processFile($filePath, $origin)
    {
        if (($realFilePath = realpath($filePath))) {
            $filePath = $realFilePath;
        }
        if (!file_exists($filePath) || !is_readable($filePath)) {
            $this->log("File {$filePath} doesn't exist or not readable");

            // Запись в журнал ошибок импорта
            ImportError::createError([
                'type' => ImportError::TYPE_SWIFTFIN,
                'filename' => FileHelper::mb_basename($filePath),
                'errorDescriptionData' => [
                    'text' => 'File doesn\'t exist or not readable'
                ]
            ]);

            return false;
        }

        $fileType = SwiftfinHelper::determineFileFormat($filePath);

        switch ($fileType) {
            case SwiftfinHelper::FILE_FORMAT_UNKNOWN:
                $this->moveToInvalid($filePath);

                // Запись в журнал ошибок импорта
                ImportError::createError([
                    'type' => ImportError::TYPE_SWIFTFIN,
                    'filename' => FileHelper::mb_basename($filePath),
                    'errorDescriptionData' => [
                        'text' => 'Failed to get document type'
                    ]
                ]);

                break;
            case SwiftfinHelper::FILE_FORMAT_SWIFT:
                $this->processSwiftFile($filePath, $origin);

                break;
            case SwiftfinHelper::FILE_FORMAT_SWIFT_PACKAGE:
                $this->processSwaFile($filePath, $origin);

                break;
            default:
                $this->registerFile($filePath, $origin);
        }
    }

    /**
     * Register file
     *
     * @param string $filePath Document file path
     * @param string $origin   Document origin
     * @throws Exception
     */
    protected function registerFile($filePath, $origin)
    {
        $jobFilePath = $this->_jobResource->getPath() . '/' . FileHelper::mb_basename($filePath);
        rename($filePath, $jobFilePath);

        Yii::$app->resque->enqueue(
            'common\jobs\StateJob',
            [
                'stateClass' => 'addons\swiftfin\states\out\SwiftOutState',
                'params' => serialize([
                    'filePath' => $jobFilePath,
                    'origin' => $origin
                ])
            ],
            true,
            \common\components\Resque::OUTGOING_QUEUE
        );
    }

    /**
     * Process swift file
     *
     * @param string $filePath Swift file path
     * @param string $origin   Swift origin
     * @throws Exception
     */
    protected function processSwiftFile($filePath, $origin)
    {
        try {
            $swt = $this->getSwtContainer($filePath);
            if ($swt === false) {
                // Запись в журнал ошибок импорта
                ImportError::createError([
                    'type' => ImportError::TYPE_SWIFTFIN,
                    'filename' => FileHelper::mb_basename($filePath),
                    'errorDescriptionData' => [
                        'text' => 'Bad swift file'
                    ]
                ]);

                throw new Exception("Bad swift file {$filePath}");
            }

            // Проверка на дублирование референса операции
            $operationReference = $swt->getOperationReference();
            $sender = $swt->sender;

            $referenceDuplicate = SwiftfinHelper::checkOperationReferenceExisted($operationReference, $sender);

            if ($referenceDuplicate) {
                // Запись в журнал ошибок импорта
                ImportError::createError([
                    'type'                  => ImportError::TYPE_SWIFTFIN,
                    'filename'              => FileHelper::mb_basename($filePath),
                    'errorDescriptionData'  => [
                        'text'   => 'Reference {id} is already used in an another operation',
                        'params' => [
                            'id' => $operationReference
                        ]
                    ],
                    'documentType'          => $this->getDocumentType($swt),
                    'documentNumber'        => $operationReference,
                    'documentCurrency'      => $swt->getCurrency(),
                    'senderTerminalAddress' => $sender,
                ]);

                throw new Exception("Reference {$operationReference} is already used in an another operation on {$sender}");
            }

            if ($this->_settings->validateOnImport) {

                if (!$swt->validate()) {
                    $this->log('Swift file validation failed: ' . $swt->getReadableErrors(), true);

                    // Запись в журнал ошибок импорта
                    ImportError::createError([
                        'type'                  => ImportError::TYPE_SWIFTFIN,
                        'filename'              => FileHelper::mb_basename($filePath),
                        'errorDescriptionData'  => [
                            'text' => 'Swift file validation failed: ' . $swt->getReadableErrors()
                        ],
                        'documentType'          => $this->getDocumentType($swt),
                        'documentNumber'        => $operationReference,
                        'documentCurrency'      => $swt->getCurrency(),
                        'senderTerminalAddress' => $sender,
                    ]);

                    // Создание nak-документа
                    $content = $swt->getRawContents();
                    SwiftfinHelper::createNak($content, $sender);

                    throw new Exception("Swift file validation failed {$filePath}");
                }

                // Применяем перловый валидатор. Для этого создаем модель MtUniversalDocument
                // и наполняем ее из swt-контейнера
                $mt = SwiftfinModule::getInstance()->mtDispatcher->instantiateMt(
                    $swt->getcontentType(),
                    ['owner' => $swt]
                );

                $mt->setBody($swt->getContent());

                // сначала костыльно проверяем на наличие некорректных символов, т.к. модель сама делает транслитерацию

                $parsed = $mt->parseForTags();

                $testChars = "ABVGDEoJZIiKLMNOPRSTUFHCcQqxYXeuaj0123456789()?+npd,/\\-.:bsvzrmf\n\r' ";
                $testCharsReplace = str_repeat('-', strlen($testChars));

                foreach($parsed as $tag) {
                    $tagName = $tag['name'];
                    $tagValue = $tag['value'];
                    if ($tagValue) {
                        $length = strlen($tagValue);
                        $result = strtr($tagValue, $testChars, $testCharsReplace);
                        if ($result !== str_repeat('-', $length)) {
                            $mt->addError('body', 'Tag ' . $tagName . ' contains invalid characters'
                                //. str_replace('-', '', $result)
                            );
                        }
                    }
                }

                // перловый валидатор срабатывает здесь
                if ($mt->hasErrors() || !$mt->validate()) {

                    $this->log('Swift file validation failed: ' . $mt->getReadableErrors(), true);

                    // Запись в журнал ошибок импорта
                    ImportError::createError([
                        'type'                  => ImportError::TYPE_SWIFTFIN,
                        'filename'              => FileHelper::mb_basename($filePath),
                        'errorDescriptionData'  => [
                            'text' => 'Swift file validation failed: ' . $mt->getReadableErrors()
                        ],
                        'documentType'          => $this->getDocumentType($swt),
                        'documentNumber'        => $operationReference,
                        'documentCurrency'      => $swt->getCurrency(),
                        'senderTerminalAddress' => $sender,
                    ]);

                    // Создание nak-документа
                    $content = $swt->getRawContents();
                    SwiftfinHelper::createNak($content, $sender);

                    throw new Exception("Swift file validation failed {$filePath}");
                }
            }

            if (RouteHelper::isRouteEnabled($swt)) {
                $this->routeSwiftFile($filePath);
            } else {
                $this->registerFile($filePath, $origin);
            }
        } catch (Exception $ex) {
            $this->log($ex->getMessage());
            $this->moveToInvalid($filePath);
        }
    }

    /**
     * Process swa package file
     *
     * @param string $filePath Swa package path
     * @param string $origin   Swa origin
     * @throws Exception
     */
    protected function processSwaFile($filePath, $origin)
    {
        try {
            $swa = new SwaPackage();
            $swa->loadData(file_get_contents($filePath));

            $swtDocuments = $swa->swtDocuments;
            if (!$swtDocuments) {

                // Запись в журнал ошибок импорта
                ImportError::createError([
                    'type' => ImportError::TYPE_SWIFTFIN,
                    'filename' => FileHelper::mb_basename($filePath),
                    'errorDescriptionData' => [
                        'text' => 'Empty swa package'
                    ]
                ]);

                throw new Exception("Empty swa package");
            }

            $resourceTemp = \Yii::$app->registry->getTempResource(SwiftfinModule::SERVICE_ID);

            foreach ($swtDocuments as $swtDocument) {
                $fileInfo = $resourceTemp->putData($swtDocument->getRawContents(), 'swt');
                if ($fileInfo === false) {

                    // Запись в журнал ошибок импорта
                    ImportError::createError([
                        'type' => ImportError::TYPE_SWIFTFIN,
                        'filename' => FileHelper::mb_basename($filePath),
                        'errorDescriptionData' => [
                            'text' => 'Cannot write temp file'
                        ]
                    ]);

                    throw new Exception("Cannot write SWT file to {$resourceTemp->getPath()}");
                }
                $swtDocument->sourceFile = $fileInfo['path'];
                $this->processSwiftFile($swtDocument->sourceFile, $origin);
            }

            unlink($filePath);
        } catch (Exception $ex) {
            $this->log($ex->getMessage());
            $this->moveToInvalid($filePath);
        }
    }

    /**
     * Route swift document
     *
     * @param string $filePath Swift file path
     * @return int
     */
    protected function routeSwiftFile($filePath)
    {
        try {
            $path = RouteHelper::getRoutePath($filePath);
            if (!rename($filePath, $path)) {
                throw new Exception("Error routing file {$filePath} to {$path}");
            }

            $this->log("File {$filePath} was routed to {$path}");
        } catch (Exception $ex) {
            $this->log($ex->getMessage());
            $this->moveToInvalid($filePath);
        }
    }

    /**
     * Get swt container from file or string
     *
     * @param string $data File path or file content
     * @return boolean|SwtContainer Return swt container or false
     */
    protected function getSwtContainer($data)
    {
        try {
            if (is_readable($data)) {
                // Необходимо привести все концы строк к виду \r\n,
                // так как перл-валидатор следует строгим правилам валидации

                $data = str_replace("\r\n", "\n", file_get_contents($data));
                $data = str_replace("\n", "\r\n", $data);
            }

            $swtContainer = new SwtContainer();
            $swtContainer->loadData($data);

            return $swtContainer;
        } catch (Exception $ex) {
            $this->log($ex->getMessage());

            return false;
        }
    }

    /**
     * Move file to invalid
     *
     * @param string $filePath File path
     */
    protected function moveToInvalid($filePath)
    {
        $errorResource = Yii::$app->registry->getImportResource(SwiftfinModule::SERVICE_ID, 'error');

        try {
            if ($errorResource->putFile($filePath)) {
                $this->log("Error importing file {$filePath}: moved to {$errorResource->getPath()}");
            }
        } catch(Exception $ex) {
            $this->log("Error importing file {$filePath}: could not move");
            $this->log($ex->getMessage());
        }

        if (is_file($filePath)) {
            unlink($filePath);
        }
    }

    private function getDocumentType(SwtContainer $container): ?string
    {
        try {
            $typeModel = SwiftFinType::createFromData($container->rawContents);
            return $typeModel ? $typeModel->getType() : null;
        } catch (\Throwable $exception) {
            $this->log("Failed to get document type, caused by: $exception");
            return null;
        }
    }
}