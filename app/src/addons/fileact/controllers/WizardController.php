<?php

namespace addons\fileact\controllers;

use addons\fileact\FileActModule;
use addons\fileact\helpers\FileActHelper;
use addons\fileact\models\FileActType;
use addons\fileact\models\form\WizardForm;
use common\base\BaseServiceController;
use common\components\TerminalId;
use common\document\Document;
use common\document\DocumentPermission;
use common\helpers\DocumentHelper;
use common\helpers\Uuid;
use common\models\Terminal;
use common\modules\transport\helpers\DocumentTransportHelper;
use Exception;
use Yii;
use yii\filters\AccessControl;
use yii\web\ServerErrorHttpException;
use yii\web\UploadedFile;

class WizardController extends BaseServiceController
{
    public function behaviors() {
		return [
			'access' => [
				'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => [DocumentPermission::CREATE],
                        'roleParams' => [
                            'serviceId' => FileActModule::SERVICE_ID,
                        ],
                    ],
                ],
			],
		];
	}

	public function actionIndex()
	{
		/** @var WizardForm $form */
		$form = $this->getCachedDocument();

		if (!$form) {
			$form = new WizardForm();
		} else {
			// пока что тупо удалим все файлы, бывшие на шаге 2
			$form->clearFiles();
			$this->cacheDocument($form);
			/*
			 * @todo Обратное преобразование получателя, если мы вернулись на шаг 1
			 * (если модель уже существует)
			 * Необходимо для правильной инициализации Select2 для выбора получателя
			 */
			if (($extracted = TerminalId::extract($form->recipient))) {
				$recipient = $extracted->participantId;
				$form->recipient = $recipient;
				$form->terminalCode = $extracted->terminalCode;
			}
		}

		$form->sender = Yii::$app->terminals->defaultTerminal->terminalId;
		$form->uuid = Uuid::generate();

		if ($form->load(Yii::$app->request->post())) {
			$recipient = TerminalId::extract($form->recipient);
			/**
			 * Если указанный адрес получателя является адресом участника, то
			 * учитываем код терминала для формирования финального адреса
			 */
			if ($recipient->getType() === TerminalId::TYPE_PARTICIPANT) {
                $recipient->terminalCode = strlen($form->terminalCode) == 1
                                            ? $form->terminalCode
                                            : TerminalId::DEFAULT_TERMINAL_CODE;
			}
			$form->recipient = (string)$recipient;
			$this->cacheDocument($form);

			return $this->redirect(['step2']);
		}

        $errors = $form->errors;

		// Далее отрисовываем первый шаг визарда
		return $this->render('index', [
			'model'       => $form,
			'currentStep' => 1,
			'errors'      => !empty($errors) ? $errors : false
		]);
	}

	public function actionStep2()
	{
		$form = $this->getCachedDocument();

		// Если в кэше нет документа, возвращаемся на первый шаг визарда
		if (empty($form)) {
			return $this->redirect(['index']);
		}

        $form->clearErrors();

		// Если были загружены файлы
		if (Yii::$app->request->isPost) {
			try {

				$fileXml = UploadedFile::getInstance($form, 'fileXml');
				$fileBin = UploadedFile::getInstance($form, 'fileBin');

                // сохранять загруженные файлы будем во временный каталог
                // до того как будет сформирован окончательный документ
                // т.к. эти файлы могут быть 100 раз отброшены в ходе сессии,
                // нет смысла забивать ими окончательный storage
                $resourceTemp = Yii::$app->registry->getTempResource($this->module->getServiceId());

                if (!empty($fileBin)) {
                    $form->removeFile('bin');
                    $fileInfo = $resourceTemp->putFile($fileBin->tempName, $fileBin->name);
                    $form->addFile('bin', $fileBin->name, $fileInfo['path']);
                }

                if (!empty($fileXml)) {
                    $form->removeFile('xml');
                    $fileInfo = $resourceTemp->putFile($fileXml->tempName, $fileXml->name);
                    $form->addFile('xml', $fileXml->name, $fileInfo['path']);
                }

                // Нужно проверить, является ли загруженный файл валидным PDU
                // Проверка осуществляется в следующих случаях:
                // 1. Загружен новый xml-файл
                // 2. Загружен бин-файл при наличии уже загруженного xml-файла

                if (!empty($fileXml) || (!empty($fileBin) && $form->getFile('xml'))) {
                    $formFileXml = $form->getFile('xml');
                    $formFileBin = $form->getFile('bin');

                    if (!$this->checkPDU($form, $formFileXml, $formFileBin)) {
                        $form->removeFile('xml');
                        //Yii::$app->session->setFlash('error', Yii::t('doc', 'Invalid wizard data'));
                    }
                }
			} catch(Exception $ex) {
				throw new ServerErrorHttpException($ex->getMessage() . ' in ' . $ex->getFile() . ':' . $ex->getLine(), 500, $ex);
			}

			$this->cacheDocument($form);
		}

		// Далее будет отрисован 2-й шаг визарда в нужном режиме отображения
		return $this->render('index', [
			'model'       => $form,
			'currentStep' => 2,
		]);
	}

	public function actionStep3()
	{
		$form = $this->getCachedDocument();

        // Если в кэше нет документа, возвращаемся на первый шаг визарда
        if (!$form) {
            Yii::$app->session->setFlash('error', Yii::t('doc', 'No wizard data available'));

            return $this->redirect(['index']);
        }
        // Если документ не валидируется или не загружены файлы, то идем на второй шаг
        if (!$form->validate() || !$form->hasFiles()) {
            Yii::$app->session->setFlash('error', Yii::t('doc', 'Invalid wizard data'));
            $this->cacheDocument($form);

            return $this->redirect(['step2']);
        }

        $document = $this->createFileActDocument($form);
        if (!$document) {
            // какая-то хрень произошла с файлами, начинай с шага 2
            Yii::$app->session->setFlash('error', Yii::t('doc', '{type} document creation failed', ['type' => 'FileAct']));

            return $this->redirect(['step2']);
        }

        $this->clearCachedDocument();

        return $this->redirect(['/fileact/default/view', 'id' => $document->id]);
	}

    /**
     * Проверяет загруженный PDU на валидность, сравнивая с данными формы и с Bin-файлом
     * @param type $form
     * @param type $fileXml
     * @param type $fileBin
     * @return boolean
     */

    protected function checkPDU($form, $fileXml, $fileBin)
    {
        $model = new FileActType();

        if (!$model->loadHeader($fileXml['savedPath'])) {
            Yii::$app->session->setFlash('error', Yii::t('doc', Yii::t('doc', 'The loaded XML file is not a valid PDU')));

            return false;
        }

        if ($fileBin && $model->pduAttributes['file'] !== $fileBin['fileName']) {
            $form->addError('fileBin', Yii::t('doc', 'The name of the uploaded file does not match the name of Bin file specified in XML file'));
            Yii::$app->session->setFlash('error', Yii::t('doc', 'The name of the uploaded file does not match the name of Bin file specified in XML file'));

            return false;
        }

        if ($model->pduAttributes['sender'] !== $form->sender) {
            $form->addError('fileXml', Yii::t('doc', 'The sender in the loaded XML file does not match the first step'));
            Yii::$app->session->setFlash('error', Yii::t('doc', 'The sender in the loaded XML file does not match the first step'));

            return false;
        }

        if ($model->pduAttributes['receiver'] !== $form->recipient) {
            $form->addError('fileXml', Yii::t('doc', 'The recipient in the loaded XML file does not match the first step'));
            Yii::$app->session->setFlash('error', Yii::t('doc', 'The recipient in the loaded XML file does not match the first step'));

            return false;
        }

        return true;
    }

	/**
	 * Формирует FileAct + CyberXml документы из данных кешированной формы
	 * @param type $form
	 * @return object
	 */
	protected function createFileActDocument(&$form)
	{
        $fileList = $form->getFiles();
        $uuid = false;

        if (!isset($fileList['xml'])) {
            $uuid = Uuid::generate();
            $pdu = FileActHelper::generatePDU(
                $uuid, $form->sender, $form->recipient, $fileList['bin']['fileName']
            )->asXML();

            $pduFileName = $uuid . '.xml';
            $resourceTemp = Yii::$app->registry->getTempResource($this->module->getServiceId());
            $fileList['xml']['fileName'] = $pduFileName;
            $fileInfo = $resourceTemp->putData(FileActHelper::generateBinHeader($pdu) . $pdu, $pduFileName);
            $fileList['xml']['savedPath'] = $fileInfo['path'];
        }

        $model = $this->module->saveFileAct(
            $fileList['xml']['savedPath'], $fileList['bin']['savedPath']
        );

        unlink($fileList['xml']['savedPath']);
        unlink($fileList['bin']['savedPath']);

        if (!$model || $model->hasErrors()) {
            return false;
        }

        // Находим объект терминала по его наименованию
        $terminal = Terminal::findOne(['terminalId' => $form->sender]);

        if (empty($terminal)) {
            $terminal = Yii::$app->terminals->defaultTerminal;
        }

        $documentContext = DocumentHelper::createDocumentContext(
                $model,
                [
                    'uuid' => $uuid !== false ? $uuid : $model->uuid,
                    'type' => $model->getType(),
                    'origin' => Document::ORIGIN_WEB,
                    'sender' => $model->sender,
                    'receiver' => $model->recipient,
                    'terminalId' => $terminal->id
                ],
                [
                    'zipStoredFileId' => $model->zipStoredFileId,
                    'binStoredFileId' => $model->binStoredFileId,
                    'pduStoredFileId' => $model->pduStoredFileId,
                    'binFileName'     => $model->binFileName,
                    'senderReference' => $model->senderReference
                ],
                $terminal->terminalId
        );

        if ($documentContext) {
            $document = $documentContext['document'];
            DocumentTransportHelper::processDocument($document, true);

            return $document;
        }

        return false;

	}

	/**
	 * @param WizardForm $doc
	 */
	protected function cacheDocument($doc)
	{
		Yii::$app->cache->set('fileact/wizard/doc-' . Yii::$app->session->id, $doc);
	}

	/**
	 * @return WizardForm
	 */
	protected function getCachedDocument()
	{
		$doc = Yii::$app->cache->get('fileact/wizard/doc-' . Yii::$app->session->id);
		if (!($doc instanceof WizardForm)) {
			return null;
		}

		return $doc;
	}

	protected function clearCachedDocument()
	{
		Yii::$app->cache->delete('fileact/wizard/doc-' . Yii::$app->session->id);
	}

}