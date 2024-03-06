<?php
namespace addons\swiftfin\models;

use addons\swiftfin\helpers\SwiftfinHelper;
use common\base\BaseType;
use addons\swiftfin\models\containers\swift\SwtContainer;
use addons\swiftfin\models\containers\swift\SwaPackage;
use common\components\TerminalId;
use common\models\cyberxml\CyberXmlDocument;
use Yii;

class SwiftFinType extends BaseType
{
    private $_source; // SwtContainer or SwaPackage
    private $_sourceFormat;
    private $_sourceData;
    private $_swtDocuments;

    public $date;
    public $sender;
    public $recipient;
    public $currency;
    public $sum;
    public $nestedItemsCount;
    public $terminalCode;
    public $contentType;
    public $documentId;
    public $pathSource;
    public $rawText;
    public $operationReference;
    public $valueDate;

    public $safeContent;

    public function init()
    {
        parent::init();

        // @todo Необходимо разобраться с формированием этого значения
        $this->nestedItemsCount = 1;
    }

    public static function createFromFile($filePath)
    {
        $data = file_get_contents($filePath);

        return static::createFromData($data, $filePath);
    }

    /**
     * Возвращает экземпляр модели с заполненными данными
     * @param $data
     * @return bool|SwiftFinType
     */
    public static function createFromData($data, $pathSource = null)
    {
        $dataFormat = SwiftfinHelper::determineStringFormat($data);

        if (SwiftfinHelper::FILE_FORMAT_CYBERXML == $dataFormat){
            $cyx = new CyberXmlDocument();
            $cyx->loadXml($data);
            $contentModel = $cyx->getContent();

            if ($contentModel instanceof SwiftfinCyberXmlContent) {
                return $contentModel->getTypeModel();
            }
        }

        if (in_array($dataFormat, [SwiftfinHelper::FILE_FORMAT_SWIFT, SwiftfinHelper::FILE_FORMAT_SWIFT_PACKAGE])) {
            $model = new SwiftFinType();
            $model->sourceFormat = $dataFormat;

            /*
             * Православный вариант. Формат данных - SWT.
             */
            if (SwiftfinHelper::FILE_FORMAT_SWIFT === $dataFormat) {
                if ($pathSource) {
                    $model->pathSource = $pathSource;
                }

                $model->_source = new SwtContainer();
                $model->_source->loadData($data);

                $model->date = $model->_source->getDate();
                $model->sender = $model->_source->getSender();
                $model->recipient = $model->_source->getRecipient();
                $model->currency = $model->_source->getCurrency();
                $model->sum = $model->_source->getSum();
                $model->operationReference = $model->_source->getOperationReference();
                $model->rawText = $model->_source->getRawText();
                $model->terminalCode = $model->_source->terminalCode;
                $model->contentType = $model->_source->contentType;
                $model->valueDate = $model->_source->getValueDate();
            }

            /*
             * Формат данных SWA.
             * Наполняем модель суммой, валютой и свифтовкой.
             * Отдельные SWT можно получить из $_swtDocuments (их можно опять же скормить тайп модели)
             */
            if (SwiftfinHelper::FILE_FORMAT_SWIFT_PACKAGE === $dataFormat) {
                if ($pathSource) {
                    $model->pathSource = $pathSource;
                }

                $model->_source = new SwaPackage();
                $model->_source->loadData($data);

                $model->currency = $model->_source->getCurrency();
                $model->sum = $model->_source->getSum();
                $model->rawText = $model->_source->getRawText();
                $model->contentType = $model->swtDocuments[0]->contentType;
                $model->sender = $model->swtDocuments[0]->getSender();
                $model->recipient = $model->swtDocuments[0]->getRecipient();
            }

            return $model;
        }

        return false;
    }

    public function getType()
    {
        //return 'MT' . preg_replace('/[^0-9]/', '', $this->contentType);
        return 'MT' . $this->contentType;
    }

    public function getModelDataAsString()
    {
        return $this->rawText;
    }

    public function getSource()
    {
        return $this->_source;
    }

    public function getSourceData()
    {
        if ($this->_source && !$this->_sourceData) {
            if (SwiftfinHelper::FILE_FORMAT_SWIFT === $this->sourceFormat) {
                $this->_sourceData = $this->source->pack();
            } else if (SwiftfinHelper::FILE_FORMAT_SWIFT_PACKAGE === $this->sourceFormat) {
                $this->_sourceData = $this->source->export();
            }
        }

        return $this->_sourceData;
    }

    public function getSourceFormat()
    {
        return $this->_sourceFormat;
    }

    public function setSourceFormat($value)
    {
        $this->_sourceFormat = $value;
    }

    public function getSwtDocuments()
    {
        if ($this->source
            && SwiftfinHelper::FILE_FORMAT_SWIFT_PACKAGE === $this->sourceFormat
            && !$this->_swtDocuments) {
            $this->_swtDocuments = $this->source->swtDocuments;
        }

        return $this->_swtDocuments;
    }

    public function getSearchType()
    {
        return 'swiftfin';
    }

    /**
     * Метод возвращает поля для поиска в ElasticSearch
     * @return array|bool
     */
    public function getSearchFields()
    {
        return [
            'sender' => $this->sender,
            'receiver' => $this->recipient,
            'body' => $this->getModelDataAsString()
        ];
    }

    public function validateRecipient()
    {
        return in_array(TerminalId::extract($this->recipient)->getParticipantId(),
            array_keys(Yii::$app->getModule('certManager')->getParticipantsList()));
    }

    public function validateSender($sessionLess = false)
    {
        if ($sessionLess) {
            return !empty(Yii::$app->exchange->findTerminalData($this->sender));
        }

        return $this->sender === Yii::$app->exchange->defaultTerminalId;
    }

    /**
     * Обнаружен баг: при постановке в Resque в качестве параметра сериализованной строки,
     * которая содержит байт b7 (Swiftfin с бинарной контрольной суммой в начале файла),
     * Resque валится с ошибкой "Could not find job class"
     * поэтому бинарные данные обезвреживаются перед сериализацией
     *
     * @return array
     */

    public function __sleep()
    {
        $this->safeContent = base64_encode(serialize($this->_source));
        $this->rawText = null;

        return [
            'date',
            'valueDate',
            'sender',
            'recipient',
            'currency',
            'sum',
            'operationReference',
            'terminalCode',
            'contentType',
            'nestedItemsCount',
            'terminalCode',
            'documentId',
            'pathSource',
            'safeContent',
        ];
    }

    public function __wakeup()
    {
        $this->_source = unserialize(base64_decode($this->safeContent));
        $this->rawText = $this->source->getRawText();
        $this->safeContent = null;
    }

}