<?php

namespace addons\edm\jobs;

use addons\edm\jobs\ExportJob\ExportResult;
use addons\edm\jobs\ExportJob\StatementTo1cExport;
use addons\edm\jobs\ExportJob\StatementToIsoExport;
use addons\edm\models\ConfirmingDocumentInformation\ConfirmingDocumentInformationExt;
use addons\edm\models\ForeignCurrencyControl\ForeignCurrencyOperationInformationExt;
use addons\edm\models\ForeignCurrencyOperation\ForeignCurrencyOperationDocumentExt;
use addons\edm\models\PaymentOrder\PaymentOrderType;
use addons\edm\models\PaymentRegister\PaymentRegisterDocumentExt;
use addons\edm\models\PaymentRegister\PaymentRegisterPaymentOrder;
use addons\edm\models\PaymentRegister\PaymentRegisterType;
use addons\edm\models\PaymentStatusReport\PaymentStatusReportType;
use addons\edm\models\RaiffeisenStatement\RaiffeisenStatementType;
use addons\edm\models\Sbbol2Statement\Sbbol2StatementType;
use addons\edm\models\SBBOLStatement\SBBOLStatementType;
use addons\edm\models\Statement\StatementType;
use addons\edm\models\Statement\StatementTypeConverter;
use addons\edm\models\VTBCancellationRequest\VTBCancellationRequestExt;
use addons\edm\models\VTBContractRequest\VTBContractRequestExt;
use addons\edm\models\VTBRegisterCur\VTBRegisterCurType;
use addons\edm\models\VTBStatementRu\VTBStatementRuType;
use addons\ISO20022\models\Camt052Type;
use addons\ISO20022\models\Camt053Type;
use addons\ISO20022\models\Camt054Type;
use common\base\DocumentJob;
use common\document\Document;
use common\modules\api\ApiModule;
use common\modules\monitor\models\MonitorLogAR;
use Resque_Job_DontPerform;
use Yii;
use yii\helpers\ArrayHelper;

class ExportJob extends DocumentJob
{
    public function setUp()
    {
        $this->_logCategory = 'EDM';
        $this->_module = Yii::$app->getModule('edm');
        parent::setUp();
    }

    public function perform()
    {
        // Это задание может поддерживать несколько типов документов. Нужно по имеющемуся типу определить,
        // в какой ресурс его надо экспортировать. Для этого в пропертостях модуля у каждого типа хранится
        // ид ресурса для экспорта
        $docType = $this->_cyxDocument->docType;
        $docProps = $this->_module->config->docTypes;

        if (!isset($docProps[$docType]['resources']['export'])) {
            $this->log('Export resource for type ' . $docType . ' is not defined', true);
            throw new Resque_Job_DontPerform('Module config error');
        }

        $result = ExportResult::notRequired();

        $typeModel = $this->_cyxDocument->getContent()->getTypeModel();

        if ($docType == StatementType::TYPE
            || $docType == VTBStatementRuType::TYPE
            || $docType == SBBOLStatementType::TYPE
            || $docType == Sbbol2StatementType::TYPE
            || $docType == RaiffeisenStatementType::TYPE
            || $docType == Camt052Type::TYPE
            || $docType == Camt053Type::TYPE
            || $docType == Camt054Type::TYPE
        ) {
            $statementTypeModel = StatementTypeConverter::convertFrom($typeModel);
            $this->updatePaymentOrders($statementTypeModel);
            $result = $this->exportStatement($statementTypeModel);
        } else if ($docType == PaymentStatusReportType::TYPE) {
            $status = $typeModel->statusCode;
            $statusDescription = $typeModel->statusDescription;
            $statusComment = $typeModel->statusComment;

            $document = Document::findOne(['uuid' => $typeModel->refDocId]);

            if (!empty($document)) {
                /**
                 * Изоляция CYB-3472, см. коммент
                 * "При регистрации PaymentStatusReport для SwiftFin, получаем ошибку"
                 */
                $extModel = $document->extModel;

                if (!$extModel) {
                    $this->log(
                        'Cannot find extModel for document id=' . $document->id,
                        true
                    );
                } else if (
                    $extModel instanceof PaymentRegisterDocumentExt
                    && in_array('registerId', $extModel->attributes())
                ) {
                    if ($typeModel->transactionStatus) {
                        $this->updateTransactionStatus($document, $typeModel);
                    } else {
                        // Иначе обновляем статус сразу всем документам
                        $updatedCount = PaymentRegisterPaymentOrder::updateBusinessStatusesByRegister(
                            $document->id,
                            $status,
                            $statusDescription,
                            $statusComment
                        );

                        $this->log("Updated $updatedCount PaymentOrders from PaymentRegister id={$document->id} with status=$status");
                    }

                    $this->setPaymentRegisterStatus($document, $extModel, [
                        'status' => $status,
                        'description' => $statusDescription,
                        'comment' => $statusComment
                    ]);

                } else if ($document->type == VTBRegisterCurType::TYPE) {
                    $this->updateTransactionStatusRegisterCur($document, $typeModel);

                    $this->setPaymentRegisterStatus($document, $extModel, [
                        'status' => $status,
                        'description' => $statusDescription,
                        'comment' => $statusComment
                    ]);

                } else if (
                    $extModel instanceof VTBCancellationRequestExt
                    || $extModel instanceof ForeignCurrencyOperationDocumentExt
                    || $extModel instanceof ConfirmingDocumentInformationExt
                    || $extModel instanceof ForeignCurrencyOperationInformationExt
                    || $extModel instanceof VTBContractRequestExt
                ) {
                    $extModel->businessStatus = $status;
                    $extModel->businessStatusComment = $statusComment;
                    $extModel->businessStatusDescription = $statusDescription;

                    if ($extModel->hasAttribute('dateProcessing')) {
                        if (empty($extModel->dateProcessing)) {
                            $extModel->dateProcessing = date('Y-m-d H:i:s');
                        }
                    }

                    if ($extModel->hasAttribute('dateDue')) {
                        // заполнение даты исполнения
                        if ($status === 'ACSC') {
                            $extModel->dateDue = date('Y-m-d H:i:s');
                        } else if ($status === 'RJCT') {
                            // если документ отклонен, очищаем дату исполнения
                            $extModel->dateDue = null;
                        }
                    }

                    // Сохранить модель в БД
                    $extModel->save();

                    Yii::info("Receive PaymentStatusReport for {$document->type} {$document->uuid}");

                    // Зарегистрировать событие изменения бизнес-статуса документа в модуле мониторинга
                    Yii::$app->monitoring->log(
                        'document:documentBusinessStatusChange', 'document', $document->id, [
                            'businessStatus' => $status,
                            'documentType' => $document->type,
                            'reportType' => PaymentStatusReportType::TYPE
                        ]
                    );
                }
                $result = ExportResult::exported();
            } else {
                $result = ExportResult::failed();
                $this->log(
                    'Cannot find Document uuid=' . $typeModel->refDocId . ' from PaymentStatusReport.refDocId',
                    true
                );
            }
        }

        if ($result->isFailed()) {
            $this->log("Error exporting document ID {$this->_documentId}", true);
            $this->_document->updateStatus(Document::STATUS_NOT_EXPORTED, 'Export');
        } else if ($result->isExported()) {
            // PaymentStatusReportType через API не экспортируем
            if ($docType != PaymentStatusReportType::TYPE) {
                ApiModule::addToExportQueueIfRequired($this->_document->uuidRemote, $result->getFilePath(), $this->_document->receiver);
            }
            $this->_document->updateStatus(Document::STATUS_EXPORTED, 'Export');
        }
    }

    private function updateTransactionStatus($registerDocument, $typeModel)
    {
        foreach ($typeModel->transactionStatus as $docNumber => $item) {
            $paymentOrder = PaymentRegisterPaymentOrder::findOne([
                'registerId' => $registerDocument->id,
                'number' => $docNumber,
            ]);

            if ($paymentOrder) {
                $isUpdated = $paymentOrder->updateBusinessStatus(
                    $item['statusCode'],
                    $item['statusDescription'],
                    $item['statusReason']
                );

                if ($isUpdated) {
                    $this->log('Updated PaymentOrder #' . $docNumber . ' from PaymentRegister id='
                        . $registerDocument->id . ' with status=' . $item['statusCode']);
                    // Зарегистрировать событие изменения бизнес-статуса документа в модуле мониторинга
                    Yii::$app->monitoring->log(
                        'document:documentBusinessStatusChange', 'document', $paymentOrder->id, [
                            'businessStatus' => $item['statusCode'],
                            'documentType' => PaymentOrderType::TYPE,
                            'reportType' => PaymentStatusReportType::TYPE,
                            'terminalId' => $paymentOrder->terminalId
                        ]
                    );
                }
            }
        }
    }

    private function updatePaymentOrders(StatementType $statement)
    {
        $count = count($statement->getTransactions());

        for ($num = 0; $num < $count; $num++) {
            $transaction = $statement->getPaymentOrder($num);

            $paymentOrder = PaymentRegisterPaymentOrder::findOne([
                'sum' => $transaction->sum,
                'date' => Yii::$app->formatter->asDatetime($transaction->date, 'php:Y-m-d'),
                'number' => $transaction->number,
            ]);

            if ($paymentOrder) {
                $paymentOrder->dateProcessing = $transaction->dateProcessing;
                $paymentOrder->dateDue = $transaction->dateDue;

                if (!empty($transaction->dateDue)) {
                    $paymentOrder->businessStatus = 'ACSC';
                } else if (!empty($transaction->dateProcessing)) {
                    $paymentOrder->businessStatus = 'ACSP';
                }

                // Если модель успешно сохранена в БД
                if ($paymentOrder->save()) {
                    $this->log('Payment order number ' . $paymentOrder->number . ' updated from Statement number ' . $statement->statementNumber);
                } else {
                    $this->log('Payment order number ' . $paymentOrder->number . ' not updated from Statement number ' . $statement->statementNumber
                        . ': ' . print_r($paymentOrder->errors, true),
                        true);
                }
            }
        }
    }

    private function updateTransactionStatusRegisterCur($registerDocument, $typeModel)
    {
        foreach ($typeModel->transactionStatus as $docNumber => $item) {
            $paymentOrder = ForeignCurrencyOperationDocumentExt::findOne(
                [
                    'documentId' => $registerDocument->id,
                    'numberDocument' => $docNumber,
                ]
            );

            if ($paymentOrder) {
                $paymentOrder->businessStatus = $item['statusCode'];
                $paymentOrder->save(false);
            }
        }
    }

    private function setPaymentRegisterStatus($document, $extModel, $statusData)
    {
        if ($document->type == VTBRegisterCurType::TYPE) {
            $paymentOrdersStatuses = ForeignCurrencyOperationDocumentExt::find()->select('businessStatus')
                ->where(['documentId' => $document->id])
                ->asArray()->all();
        } else {
            $paymentOrdersStatuses = PaymentRegisterPaymentOrder::find()->select('businessStatus')
                ->where(['registerId' => $document->id])
                ->asArray()->all();
        }
        $statuses = ArrayHelper::getColumn($paymentOrdersStatuses, 'businessStatus');
        $statusesQty = count($statuses);

        $paymentRegisterExt = PaymentRegisterDocumentExt::class;

        if ($statusesQty == 0) {
            return false;
        } else if ($statusesQty == 1) {
            $status = $statuses[0];
        } else {
            $checkSameStatus = array_unique($statuses);
            $checkSameStatusQty = count($checkSameStatus);

            if ($checkSameStatusQty == 1) {
                $status = $checkSameStatus[0];
            } else if (in_array($paymentRegisterExt::STATUS_REJECTED, $statuses)) {
                $status = $paymentRegisterExt::STATUS_PARTIALLY_REJECTED;
            } else if (in_array($paymentRegisterExt::STATUS_PROCESSED, $statuses)) {
                $status = $paymentRegisterExt::STATUS_PARTIALLY;
            } else if (in_array($paymentRegisterExt::STATUS_ACCEPTED, $statuses)) {
                $status = $paymentRegisterExt::STATUS_PARTIALLY_ACCEPTED;
            } else if (in_array($paymentRegisterExt::STATUS_PENDING, $statuses)) {
                $status = $paymentRegisterExt::STATUS_PARTIALLY_PENDING;
            } else {
                $status = $statusData['status'];
            }
        }

        if ($status != $extModel->businessStatus) {
            $extModel->businessStatus = $status;
            $extModel->businessStatusDescription = $statusData['description'];
            $extModel->businessStatusComment = $statusData['comment'];
            $extModel->save(false, ['businessStatus', 'businessStatusDescription', 'businessStatusComment']);

            if ($status == 'RJCT' && $document->type != VTBRegisterCurType::TYPE) {
                // Зарегистрировать событие отказа в обработке документа в модуле мониторинга
                Yii::$app->monitoring->log('edm:PaymentStatusError', 'PaymentRegister', $document->id,
                    [
                        'status' => 'RJCT',
                        'statusDescription' => 'Payment Register processing rejected',
                        'registerId' => $document->id,
                        'initiatorType' => MonitorLogAR::INITIATOR_TYPE_SYSTEM,
                        'terminalId' => $document->terminalId
                    ]
                );
            } else {
                // Зарегистрировать событие изменения бизнес-статуса документа в модуле мониторинга
                Yii::$app->monitoring->log(
                    'document:documentBusinessStatusChange', 'document', $document->id, [
                        'businessStatus' => $status,
                        'documentType' => PaymentRegisterType::TYPE,
                        'reportType' => PaymentStatusReportType::TYPE,
                        'terminalId' => $document->terminalId
                    ]
                );
            }
        }
    }

    private function exportStatement(StatementType $statementTypeModel): ExportResult
    {
        $logCallback = function ($message, $isWarning) {
            $this->log($message, $isWarning);
        };

        try {
            $exportTo1CResult = StatementTo1cExport::run(
                $this->_document,
                $this->_cyxDocument,
                $statementTypeModel,
                $logCallback
            );
        } catch (\Exception $exception) {
            $this->log("Failed to export statement to 1C, caused by: $exception", true);
            $exportTo1CResult = ExportResult::failed();
        }

        try {
            $exportToISOResult = StatementToIsoExport::run(
                $this->_document,
                $this->_cyxDocument,
                $statementTypeModel,
                $logCallback
            );
        } catch (\Exception $exception) {
            $this->log("Failed to export statement to ISO, caused by: $exception", true);
            $exportToISOResult = ExportResult::failed();
        }

        if ($exportTo1CResult->isExported() || $exportToISOResult->isExported()) {
            return ExportResult::exported($exportTo1CResult->getFilePath() ?? $exportToISOResult->getFilePath());
        } else if ($exportTo1CResult->isFailed() || $exportToISOResult->isFailed()) {
            return ExportResult::failed();
        } else {
            return ExportResult::notRequired();
        }
    }
}
