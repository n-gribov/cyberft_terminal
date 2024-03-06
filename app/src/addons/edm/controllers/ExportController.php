<?php

namespace addons\edm\controllers;

use addons\edm\models\ConfirmingDocumentInformation\ConfirmingDocumentInformationExt;
use addons\edm\models\ContractRegistrationRequest\ContractRegistrationRequestExt;
use addons\edm\models\ForeignCurrencyControl\ForeignCurrencyOperationInformationExt;
use addons\edm\models\PaymentOrder\PaymentOrderType;
use addons\edm\models\PaymentRegister\PaymentRegisterPaymentOrder;
use addons\edm\models\Statement\StatementType;
use addons\edm\models\Statement\StatementTypeConverter;
use common\base\BaseServiceController;
use common\components\storage\StoredFile;
use common\document\Document;
use common\models\cyberxml\CyberXmlDocument;
use Exception;
use Yii;
use yii\filters\AccessControl;

class ExportController extends BaseServiceController
{
	const EXPORT_JOB_CLASS = '\addons\edm\jobs\EdmExportJob';

	/**
	 * @var array $supportedTypes Supported types
	 */
	private $_supportedTypes = [
		'excel' => ['name' => 'excel', 'ext' => 'xlsx'],
		'1c' => ['name' => '1c', 'ext' => 'txt'],
        'pdf' => ['name' => 'pdf', 'ext' => 'pdf']
	];

	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
					[
						// Accessible for authorized users only
						'allow' => true,
						'roles' => ['@'],
					],
				],
			],
		];
	}

	public function actionIndex()
	{
		return $this->redirect(['/edm/documents']);
	}


    public function actionExportStatement($exportType, $id, $mode = null)
    {
        $document = $this->findModel($id);

        try {
            $typeModel = $this->getTypeModel($document);
            $statementTypeModel = StatementTypeConverter::convertFrom($typeModel);

            $fileName = "Statement";

            if ($mode) {
                $descriptor = $mode . ":" . 'transactions';
                $fileName .= "_{$mode}";
            } else {
                $descriptor = $mode;
            }

            $fileName .= "_{$statementTypeModel->statementAccountNumber}";
            $fileName .= "_{$statementTypeModel->statementPeriodStart}_{$statementTypeModel->statementPeriodEnd}";
            $fileName = str_replace('\\', '', $fileName);

            return $this->export($document, $statementTypeModel, $fileName, $exportType, $descriptor);
        } catch (Exception $ex) {
            Yii::$app->session->setFlash('error', $ex->getMessage());
            return $this->render('exportError');
        }
    }

    public function actionExportPaymentorder($exportType, $id)
    {
        $document = $this->findModel($id);

        try {
            $typeModel = $this->getTypeModel($document);
            $fileName	 = "PaymentOrder-{$typeModel->number}";

            return $this->export($document, $typeModel, $fileName, $exportType);
        } catch (Exception $ex) {
            Yii::$app->session->setFlash('error', $ex->getMessage());

            return $this->render('exportError');
        }
    }

    public function actionExportFcc($id, $fccType)
    {
        try {
            $document = Yii::$app->terminalAccess->findModel(Document::className(), $id);

            // Получение typeModel
            if ($fccType == 'fci') {
                $model = ForeignCurrencyOperationInformationExt::findOne(['documentId' => $document->id]);
                $fileName = "ForeignCurrencyOperationInformation-{$model->number}";
                $descriptor = 'ForeignCurrencyOperationInformation:' . $model->number;
            } else if ($fccType == 'cdi') {
                $model = ConfirmingDocumentInformationExt::findOne(['documentId' => $document->id]);
                $fileName = "ConfirmingDocumentInformation-{$model->number}";
                $descriptor = 'ConfirmingDocumentInformation:' . $model->number;
            } else if ($fccType == 'crr') {
                $model = ContractRegistrationRequestExt::findOne(['documentId' => $document->id]);
                $fileName = "ContractRegistrationRequest-{$model->number}";
                $descriptor = 'ContractRegistrationRequest:' . $model->number;
            }

            return $this->export($document, $model, $fileName, 'excel', $descriptor);
        } catch (Exception $ex) {
            Yii::$app->session->setFlash('error', $ex->getMessage());

            return $this->render('exportError');
        }
    }

    public function actionExportPaymentregisterPaymentorder($exportType, $id)
    {
        $model = Yii::$app->terminalAccess->findModel(PaymentRegisterPaymentOrder::className(), $id);

        try {
            $typeModel = (new PaymentOrderType())->loadFromString($model->body);
            $fileName	 = "PaymentOrder-{$typeModel->number}-{$typeModel->payerCheckingAccount}";

            $descriptor = 'PaymentRegisterPaymentOrder:' . $typeModel->payerCheckingAccount;

            return $this->export($model, $typeModel, $fileName, $exportType, $descriptor);
        } catch (Exception $ex) {
            Yii::$app->session->setFlash('error', $ex->getMessage());
            return $this->render('exportError');
        }
    }

    public function actionExportStatementPaymentorder($exportType, $id, $num)
    {
        $document = $this->findModel($id);

        try {
            $typeModel = $this->getTypeModel($document);
            $statementTypeModel = StatementTypeConverter::convertFrom($typeModel);

            $paymentOrder = $statementTypeModel->getPaymentOrder($num);

  			$fileName = str_replace('\\', '',
                    "Statement-{$statementTypeModel->statementNumber}-{$statementTypeModel->statementAccountNumber}-{$paymentOrder->number}"
            );

            $descriptor = 'StatementPaymentOrder:' . $num;

            return $this->export($document, $statementTypeModel, $fileName, $exportType, $descriptor);
        } catch (Exception $ex) {
            Yii::$app->errorHandler->logException($ex);
            Yii::$app->session->setFlash('error', $ex->getMessage());
            return $this->render('exportError');
        }
    }

    private function getTypeModel($document)
    {
        if (!$document->actualStoredFileId) {
            throw new Exception(Yii::t('edm', 'Document is not ready'));
        }

        $cyx = CyberXmlDocument::read($document->getValidStoredFileId());
        $typeModel = $cyx->content->getTypeModel();

        return $typeModel;
    }

	private function export($document, $typeModel, $fileName, $exportType, $descriptor = null)
	{
        try {
       		$type = $this->getSupportedType($exportType);
        	if ($type === false) {
            	throw new Exception(Yii::t('edm', 'Wrong export type'));
            }

            $storedFile = StoredFile::findOne([
                'serviceId' => $this->module->serviceId,
                'entity' => $typeModel->type,
                'entityId' => $document->id,
                'fileType' => $exportType,
                'descriptor' => $descriptor
            ]);

            if (!empty($storedFile)) {
                if ($storedFile->status === StoredFile::STATUS_READY) {

                    // Для документов валютного контроля удаляем существующий файл экспорта
                    if (in_array($typeModel->type, [
                        'ConfirmingDocumentInformation',
                        'ContractRegistrationRequest',
                        'ForeignCurrencyOperationInformation'
                    ])) {
                        try {
                            unlink($storedFile->getRealPath());
                        } catch(Exception $e) {

                        }

                        $storedFile->delete();
                    } else {
                        /** File found and processed */
                        return $this->redirect(['/storage/download', 'id' => $storedFile->id]);
                    }

                } else if ($storedFile->status === StoredFile::STATUS_PROCESSING) {
                    /** File found and processing */
                    return $this->redirect(['/storage/status', 'id' => $storedFile->id]);
                } else {
                    return $this->render('exportError');
                }
            }

            // Для выписок своя логика формирования наименования файла
            if ($typeModel->type != StatementType::TYPE) {
                $fileName .= '-' . date('d.m.y');
            }

            $fileName .= '.' . $type['ext'];

      		$storedFile = $this->module->storeDataExport('1');

            $storedFile->entity       = $typeModel->type;
            $storedFile->entityId	  = $document->id;
            $storedFile->fileType	  = $type['name'];
            $storedFile->status		  = StoredFile::STATUS_PROCESSING;
            $storedFile->contextValue = ['fileName' => $fileName];
            $storedFile->descriptor   = $descriptor;

            if (!$storedFile->save()) {
                throw new Exception('Stored file save error');
            }

            Yii::$app->resque->enqueue(static::EXPORT_JOB_CLASS,
			[
                'id' => $document->id,
                'exportId' => $storedFile->id,
                'type' => $type['name'],
                'descriptor' => $descriptor,
            ]);

            return $this->redirect(['/storage/status', 'id' => $storedFile->id]);
        } catch(Exception $ex) {
            Yii::$app->session->setFlash('error', $ex->getMessage());
            return $this->render('exportError');
        }
	}

	private function getSupportedType($type)
	{
		if (isset($this->_supportedTypes[$type])) {
			return $this->_supportedTypes[$type];
		}

		return FALSE;
	}

    protected function findModel($id)
    {
        return Yii::$app->terminalAccess->findModel(Document::className(), $id);
    }

}