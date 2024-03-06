<?php

namespace addons\edm\jobs;

use addons\edm\helpers\Converter;
use addons\edm\helpers\PdfStatement;
use addons\edm\models\ConfirmingDocumentInformation\ConfirmingDocumentInformationExt;
use addons\edm\models\ContractRegistrationRequest\ContractRegistrationRequestExt;
use addons\edm\models\ForeignCurrencyControl\ForeignCurrencyOperationInformationExt;
use addons\edm\models\PaymentOrder\PaymentOrderType;
use addons\edm\models\PaymentRegister\PaymentRegisterPaymentOrder;
use addons\edm\models\RaiffeisenStatement\RaiffeisenStatementType;
use addons\edm\models\SBBOLStatement\SBBOLStatementType;
use addons\edm\models\Statement\StatementType;
use addons\edm\models\Statement\StatementTypeConverter;
use addons\edm\models\VTBStatementRu\VTBStatementRuType;
use addons\ISO20022\models\Camt052Type;
use addons\ISO20022\models\Camt053Type;
use addons\ISO20022\models\Camt054Type;
use common\base\Job;
use common\components\storage\StoredFile;
use common\document\Document;
use common\models\cyberxml\CyberXmlDocument;
use common\modules\certManager\models\Cert;
use ErrorException;
use Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Resque_Job_DontPerform;
use Yii;
use function iconv;

/**
 * Export EDM document to 1C or Excel format
 */
class EdmExportJob extends Job
{
	const RESOURCE_ID = 'export';

	private $_id;
	private $_exportFileId;
	private $_type;
	private $_module;
	private $_storedFile;
	private $_descriptor = null;

	public function setUp()
	{
		parent::setUp();

        $this->_module = Yii::$app->addon->getModule('edm');

        if (empty($this->_module)) {
            $this->log('EDM module not found');
            throw new Resque_Job_DontPerform('EDM module not found');
        }

        if (!$this->checkArgs()) {
            throw new Resque_Job_DontPerform("Bad arguments");
        }

        $this->_storedFile = StoredFile::findOne($this->_exportFileId);
        if (is_null($this->_storedFile)) {
            $this->log("Cannot find stored file with ID[{$this->_exportFileId}]");
            throw new Resque_Job_DontPerform("Cannot find stored file with ID[{$this->_exportFileId}]");
        }
	}

    public function perform()
    {
	    try {
	        $this->export();
        } catch (\Exception $exception) {
	        $this->log("Export has failed, caused by: $exception", true);
            $this->_storedFile->status = StoredFile::STATUS_PROCESSING_ERROR;
            $this->_storedFile->save();
        }
    }

	private function export()
	{
        $descriptorParts = explode(':', $this->_descriptor);
        $descriptorEntity = $descriptorParts[0];
        $signatures = [];

        if ($descriptorEntity == 'PaymentRegisterPaymentOrder') {
            $model = PaymentRegisterPaymentOrder::findOne(['id' => $this->_id]);
            $typeModel = (new PaymentOrderType())->loadFromString($model->body);
        } elseif ($descriptorEntity == 'ForeignCurrencyOperationInformation') {
            $model = Document::findOne($this->_id);
            $typeModel = ForeignCurrencyOperationInformationExt::findOne(['documentId' => $model->id]);
        } elseif ($descriptorEntity == 'ConfirmingDocumentInformation') {
            $model = Document::findOne($this->_id);
            $signatures = $model->getSignatures(Document::SIGNATURES_TYPEMODEL, Cert::ROLE_SIGNER);
            $typeModel = ConfirmingDocumentInformationExt::findOne(['documentId' => $model->id]);
        } elseif ($descriptorEntity == 'ContractRegistrationRequest') {
            $model = Document::findOne($this->_id);
            $typeModel = ContractRegistrationRequestExt::findOne(['documentId' => $model->id]);
        } else {
			$model = Document::findOne(['id' => $this->_id]);
            $typeModel = CyberXmlDocument::getTypeModel($model->actualStoredFileId);
            if ($descriptorEntity == 'StatementPaymentOrder') {
                $statementTypeModel = StatementTypeConverter::convertFrom($typeModel);
                $typeModel = $statementTypeModel->getPaymentOrder($descriptorParts[1]);
            }
        }

		switch ($this->_type) {
			case 'excel':
				$result	 = $this->exportExcel($typeModel, $signatures);
				break;
			case '1c':
                switch ($descriptorEntity) {
                    case 'debit':
                        $result	 = $this->export1C($typeModel, 'debit');
                        break;
                    case 'credit':
                        $result	 = $this->export1C($typeModel, 'credit');
                        break;
                    default:
                        $result	 = $this->export1C($typeModel);
                        break;
                }
				break;
            case 'pdf':
                $result = $this->exportStatementToPdf($model, $descriptorEntity);
                break;
            default:
                $result = false;
        }

		$newStatus = ($result === false) ? StoredFile::STATUS_PROCESSING_ERROR : StoredFile::STATUS_READY;

		$this->_storedFile->status = $newStatus;
		$this->_storedFile->save();

		if ($result !== false) {
			$this->log("EDM document [{$this->_id}] was exported to format [{$this->_type}]");
		} else {
			$this->log("EDM document [{$this->_id}] export error", true);
		}
	}

	private function exportExcel($typeModel, $signatures)
	{
		try {
			if ($typeModel->type == PaymentOrderType::TYPE) {

                $typeModel->unsetParticipantsNames();

                if ($typeModel->isRequirement()) {
                    $xlsView = $this->_module->basePath . '/xlsViews/paymentrequirement.xls';
                } else {
                    $xlsView = $this->_module->basePath . '/xlsViews/paymentorder.xls';
                }

                $dataExcel = Converter::paymentOrderToXls($typeModel, $xlsView);
            } else if ($typeModel->type == 'ForeignCurrencyOperationInformation') {
                $xlsView = $this->_module->basePath . '/xlsViews/foreigncurrencyoperationinformation.xls';
                $dataExcel = Converter::foreignCurrencyOperationInformationToXls($typeModel, $xlsView);
            } else if ($typeModel->type == 'ConfirmingDocumentInformation') {
                $xlsView = $this->_module->basePath . '/xlsViews/confirmingdocumentinformation.xls';
                $dataExcel = Converter::confirmingDocumentInformationToXls($typeModel, $xlsView, $signatures);
            } else if ($typeModel->type == 'ContractRegistrationRequest') {
                if ($typeModel->passportType == ContractRegistrationRequestExt::PASSPORT_TYPE_TRADE) {
                    $xlsView = $this->_module->basePath . '/xlsViews/contractregistrationrequesttrade.xls';
                    $dataExcel = Converter::contractRegistrationRequestTradeToXls($typeModel, $xlsView);
                } else if ($typeModel->passportType == ContractRegistrationRequestExt::PASSPORT_TYPE_LOAN) {
                    $xlsView = $this->_module->basePath . '/xlsViews/contractregistrationrequestloan.xls';
                    $dataExcel = Converter::contractRegistrationRequestLoanToXls($typeModel, $xlsView);
                }
			} elseif (in_array($typeModel->type, [
                    StatementType::TYPE, 
                    VTBStatementRuType::TYPE, 
                    SBBOLStatementType::TYPE, 
                    RaiffeisenStatementType::TYPE, 
                    Camt052Type::TYPE, 
                    Camt053Type::TYPE, 
                    Camt054Type::TYPE
                ])) {
                $statementTypeModel = StatementTypeConverter::convertFrom($typeModel);
				$xlsView = $this->_module->basePath . '/xlsViews/statement.xls';
				$dataExcel = Converter::statementToXls($statementTypeModel, $xlsView);
			} else {
			    throw new Exception("Unsupported document type: {$typeModel->type}");
            }

			$writer = IOFactory::createWriter($dataExcel, 'Xlsx');

			ob_start();
			$writer->save('php://output');
			$data = ob_get_clean();

			return $this->updateExportFile($data);
		} catch (ErrorException $ex) {
			$this->log('Error while exporting EDM document to excel format: ' . $ex->getMessage());
			Yii::warning($ex->getMessage());

            return false;
		}
	}

    private function export1C($typeModel = null, $mode = 'all')
	{
		try {
            if ($typeModel->type == PaymentOrderType::TYPE) {
                $typeModel->beforeExport1c();
				$data = iconv('UTF-8', 'cp1251', $typeModel->getModelDataAsString());
			} else {
                $statementTypeModel = StatementTypeConverter::convertFrom($typeModel);

                // Режим вывода операций - только по дебету, только кредиту, все
                if ($mode == 'debit') {
                    $data = Converter::statementTo1C($statementTypeModel, 'debit');
                } elseif ($mode == 'credit') {
                    $data = Converter::statementTo1C($statementTypeModel, 'credit');
                } else {
                    $data = Converter::statementTo1C($statementTypeModel);
                }
			}

            return $this->updateExportFile($data);
		} catch (ErrorException $ex) {
			$this->log('Error while exporting EDM document to 1C format: ' . $ex->getMessage());
			Yii::warning($ex->getMessage());

            return false;
		}
	}

    private function exportStatementToPdf(Document $document, ?string $mode)
    {
        try {
            $pdfContent = $this->renderStatementToPdf($document, $mode);
            return $this->updateExportFile($pdfContent);
        } catch (Exception $exception) {
            $this->log('Error while exporting EDM document to PDF format: ' . $exception->getMessage());
            Yii::warning($exception->getMessage());
            return false;
        }
    }

    private function renderStatementToPdf(Document $document, ?string $mode): string
    {
        $pdfStatement = new PdfStatement($document);
        switch ($mode) {
            case 'summary':
                return $pdfStatement->renderSummary();
            case 'orders':
                return $pdfStatement->renderOrders();
            default:
                return $pdfStatement->renderAll();
        }
    }

	private function updateExportFile($data)
	{
		return Yii::$app->storage->updateData($this->_storedFile, $data);
	}

	private function checkArgs()
	{
		if (!isset($this->args['id'])) {
			$this->log('EDM document ID must be set', true);

			return false;
		}

        $this->_id = $this->args['id'];

		if (!isset($this->args['exportId'])) {
			$this->log('Export file path must be set', true);

			return false;
		}

        $this->_exportFileId = $this->args['exportId'];

		if (!isset($this->args['type'])) {
			$this->log('Type must be set', true);

			return false;
		}

        $this->_type = $this->args['type'];

        if (isset($this->args['descriptor'])) {
			$this->_descriptor = $this->args['descriptor'];
		}

		return true;
	}
}