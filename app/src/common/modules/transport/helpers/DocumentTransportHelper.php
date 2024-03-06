<?php

namespace common\modules\transport\helpers;

use addons\edm\models\BankLetter\BankLetterDocumentExt;
use addons\edm\models\ConfirmingDocumentInformation\ConfirmingDocumentInformationExt;
use addons\edm\models\ForeignCurrencyControl\ForeignCurrencyOperationInformationExt;
use addons\edm\models\ForeignCurrencyOperation\ForeignCurrencyOperationDocumentExt;
use addons\edm\models\PaymentRegister\PaymentRegisterType;
use addons\edm\models\SBBOLPayDocRu\SBBOLPayDocRuType;
use addons\edm\models\StatementRequest\StatementRequestExt;
use addons\edm\models\VTBCancellationRequest\VTBCancellationRequestExt;
use addons\edm\models\VTBContractRequest\VTBContractRequestExt;
use addons\finzip\models\FinZipType;
use addons\ISO20022\models\Auth024Type;
use addons\ISO20022\models\Auth026Type;
use common\components\Resque;
use common\document\Document;
use common\helpers\DocumentHelper;
use common\models\cyberxml\CyberXmlDocument;
use common\modules\transport\models\CFTAckType;
use common\modules\transport\models\CFTChkAckType;
use common\modules\transport\models\CFTStatusReportType;
use common\modules\transport\models\StatusReportType;
use common\modules\transport\states\out\CFTAckOutState;
use common\modules\transport\states\out\CFTResendOutState;
use common\states\StateRunner;
use Exception;
use Yii;

/**
 * Класс-хелпер
 * Содержит функции для формирования служебных сообщений типа CFTAck и CFTStatusReport
 */
class DocumentTransportHelper
{
    /**
     * Функция формирует ответный Ack для CyberXmlDocument
     * @param CyberXmlDocument $cyxDoc
     */
    public static function ack(CyberXmlDocument $cyxDoc, $remoteDocId)
    {
        // Ack отсылается не для всех типов документов
        if (!$cyxDoc->isAckable()) {
            return true;
        }

        $state = new CFTAckOutState([
            'refDocId' => $remoteDocId,
            'refSenderId' => $cyxDoc->senderId,
            'receiverId' => $cyxDoc->receiverId,
            'origin' => Document::ORIGIN_WEB
        ]);

        /**
         * Ack не ставится в очередь отправки, а выполняется напрямую,
         * чтобы максимально сократить время отправки
         * и предотвратить получение запросов ChkAck от процессинга
         */
        return StateRunner::run($state);
    }

    /**
     * Функция формирует ответный StatusReport для CyberXmlDocument
     * @param Document $document
     * @param array $errorInfo Информация о состянии сообщения для отчета, должна
     * содержать атрибуты 'statusCode', 'errorCode', 'errorDescription'.
     */
    public static function statusReport(Document $document, $errorInfo)
    {
        // StatusReport отсылается не для всех типов документов
        if ($document->isReport()) {
            return true;
        }

//        $state = new CFTStatusReportOutState([
//            'origin' => Document::ORIGIN_WEB,
//            'refDocId' => $remoteDocId,
//            'senderId' => $cyxDoc->senderId,
//            'receiverId' => $cyxDoc->receiverId,
//            'statusCode' => $errorInfo['statusCode'],
//            'errorCode' => $errorInfo['errorCode'],
//            'errorDescription' => $errorInfo['errorDescription'],
//        ]);
//
//        return StateRunner::run($state);

        return Yii::$app->resque->enqueue(
                        'common\jobs\StateJob',
                        [
                            'stateClass' => '\common\modules\transport\states\out\CFTStatusReportOutState',
                            'params' => serialize([
                                'origin' => Document::ORIGIN_WEB,
                                'refDocId' => $document->uuidRemote,
                                'senderId' => $document->sender,
                                'receiverId' => $document->receiver,
                                'statusCode' => $errorInfo['statusCode'],
                                'errorCode' => $errorInfo['errorCode'],
                                'errorDescription' => $errorInfo['errorDescription'],
                            ])
                        ],
                        true,
                        Resque::OUTGOING_QUEUE
        );
    }

    public static function resend(CFTChkAckType $chkTypeModel)
    {
        $state = new CFTResendOutState([
            'refDocId' => $chkTypeModel->refDocId,
            'refSenderId' => $chkTypeModel->refSenderId,
            'receiverId' => $chkTypeModel->recipient
        ]);

        return StateRunner::run($state);

//        Yii::$app->resque->enqueue(
//            'common\jobs\StateJob',
//            [
//                'stateClass' => '\common\modules\transport\states\out\CFTResendOutState',
//                'params' => serialize([
//                    'refDocId' => $chkTypeModel->refDocId,
//                    'refSenderId' => $chkTypeModel->refSenderId,
//                ])
//            ],
//            true,
//            \common\components\Resque::SERVICE_QUEUE
//        );
    }

    public static function createSendingState(Document $document)
    {
        return Yii::$app->resque->enqueue(
            'common\jobs\StateJob',
            [
                'stateClass' => 'common\states\out\SendingState',
                'params' => serialize([
                    'documentId' => $document->id,
                ])
            ],
            true,
            Resque::OUTGOING_QUEUE
        );
    }

    /**
     * Функция отрабатывает действия по входящему репорту.
     * @param Document $report Сообщение, являющееся входящим StatusReport'ом
     * @return Document Обработанный документ или null
     */
    public static function processStatusReport(CFTStatusReportType $report)
    {
        $referencedDocumentId = $report->refDocId;

        // Ищем сообщение, на которое ссылается StatusReport
        $targetDocument = Document::findOne(['uuid' => $referencedDocumentId]);

        if (is_null($targetDocument)) {
            /**
             * @todo Журналировать ситуацию
             */
            return;
        }

        // Изменяем статус согласно StatusReport
        // Смена состояния по StatusReport возможна только из состояния "отправлено"
        // или "доставлено" или "доставляется" или "недоставлено" или "в обработке сервиса"
        // или "ожидается доставка вложения"
        if (in_array($targetDocument->status, [
                Document::STATUS_SENT,
                Document::STATUS_DELIVERING,
                Document::STATUS_UNDELIVERED,
                Document::STATUS_SERVICE_PROCESSING,
                Document::STATUS_ATTACHMENT_UNDELIVERED,
                Document::STATUS_DELIVERED
            ])
        ) {
            $info = '';
            if ($report->errorCode != 0) {
                $info = Yii::t('app/error', 'An error has occurred {errorDescription} (status code:{statusCode}, error code: {errorCode})', [
                            'statusCode' => $report->statusCode,
                            'errorCode' => $report->errorCode,
                            'errorDescription' => $report->errorDescription
                ]);
            }

            switch ($report->statusCode) {
                case 'RJCT':
                    $status = Document::STATUS_REJECTED;
                    break;
                case 'ACDC':
                    $status = Document::STATUS_DELIVERED;
                    break;
                case 'ATDE':
                    $status = Document::STATUS_ATTACHMENT_UNDELIVERED;
                    break;
            }

            $targetDocument->updateStatus($status, $info);
        }
    }

    /**
     * Функция отрабатывает действия по входящему репорту бизнес-статусов
     * @param Document $report Сообщение, являющееся входящим StatusReport'ом
     * @return Document|void Обработанный документ или null
     */
    public static function processBusinessStatusReport(StatusReportType $report, $attempt = 1, $token = null)
    {
        $referencedDocumentId = $report->refDocId;

        // Ищем сообщение, на которое ссылается StatusReport
        $targetDocument = Document::findOne(['uuid' => $referencedDocumentId]);

        if (is_null($targetDocument)) {
            return;
        }

        // Меняем бизнес-статус ext-модели связанного документа
        $extModel = $targetDocument->extModel;

        // Если есть ext-модель
        if ($extModel) {
            static::logReceivingStatusReport($report, $targetDocument);

            if ($targetDocument->type == PaymentRegisterType::TYPE || $targetDocument->type === SBBOLPayDocRuType::TYPE) {

                $extModel->businessStatus = $report->statusCode;
                $extModel->businessStatusDescription = '';
                $extModel->businessStatusComment = '';

                if (!empty($report->errorCode)) {
                    $extModel->businessStatusComment = $report->errorDescription;
                }

                $extModel->save();
            } else if ($targetDocument->type == FinZipType::TYPE) {
                $extModel->businessStatus = $report->statusCode;
                $extModel->businessStatusDescription = "";

                if ($report->errorCode != 0) {
                    $extModel->businessStatusDescription = $report->errorDescription;
                }

                $extModel->save();
            } else if (
                $extModel instanceof VTBCancellationRequestExt
                || $extModel instanceof BankLetterDocumentExt
                || $extModel instanceof StatementRequestExt
                || $extModel instanceof ForeignCurrencyOperationDocumentExt
                || $extModel instanceof ConfirmingDocumentInformationExt
                || $extModel instanceof ForeignCurrencyOperationInformationExt
                || $extModel instanceof VTBContractRequestExt
            ) {
                $extModel->businessStatus = $report->statusCode;

                if ($report->errorCode) {
                    $extModel->businessStatusDescription = $report->errorDescription;
                }

                if ($extModel->hasAttribute('dateProcessing')) {
                    if (empty($extModel->dateProcessing)) {
                        $extModel->dateProcessing = date('Y-m-d H:i:s');
                    }
                }

                if ($extModel->hasAttribute('dateDue')) {
                    // заполнение даты исполнения
                    if ($report->statusCode === 'ACSC') {
                        $extModel->dateDue = date('Y-m-d H:i:s');
                    } elseif ($report->statusCode === 'RJCT') {
                        // если документ отклонен, очищаем дату исполнения
                        $extModel->dateDue = null;
                    }
                }

                $extModel->save(false);
            } else if (
                $targetDocument->type === Auth026Type::TYPE
                || $targetDocument->type === Auth024Type::TYPE
            ) {
                $extModel->statusCode = $report->statusCode;
                $extModel->errorCode = $report->errorCode;
                $extModel->errorDescription = $report->errorDescription;

                $extModel->save();
            } else {
                Yii::info('Unsupported document type, business status is not saved');
            }
        }
    }

    private static function logReceivingStatusReport(StatusReportType $report, Document $targetDocument)
    {
        Yii::info("Received StatusReport for {$targetDocument->type} {$targetDocument->id}, {$targetDocument->uuid}");

        Yii::$app->monitoring->log(
            'document:documentBusinessStatusChange', 'document', $targetDocument->id, [
                'businessStatus' => $report->statusCode,
                'documentType' => $targetDocument->type,
                'reportType' => StatusReportType::TYPE
            ]
        );
    }

    /**
     * Функция отрабатывает действия по входящему Ack'у.
     * @param Document $ack Сообщение, являющееся входящим StatusReport'ом
     * @return Document Обработанный документ или null
     */
    public static function processAck(CFTAckType $ack)
    {
        $refDocId = $ack->refDocId;
        // Ищем сообщение, на которое ссылается Ack
        $targetDocument = Document::findOne(['uuid' => $refDocId]);
        if (is_null($targetDocument)) {
            /**
             * @todo Журналировать ситуацию
             */
            return;
        }

        // Изменяем статус согласно Ack
        // Смена состояния на "доставляется" возможна только из состояния "отправлено"
        if ($targetDocument->status == Document::STATUS_SENT) {
            $targetDocument->updateStatus(Document::STATUS_DELIVERING);
        }
    }

    public static function processChkAck(CFTChkAckType $chkAck)
    {
        $refDocId = $chkAck->refDocId;
        // Ищем сообщение, на которое ссылается ChkAck
        $document = Document::findOne(['uuidRemote' => $refDocId]);

        if (is_null($document)) {
            return static::resend($chkAck);
        }

        /**
         * Если у документа нет ActualStoredFileId, значит ему не требовалась расшифровка и его тело хранится
         * в encryptedStoredFileId.
         * @todo в таких случаях нужно encrypted переносить в actual на каком-то шаге обработки документа
         */
        $cyxDoc = CyberXmlDocument::read($document->actualStoredFileId ?: $document->encryptedStoredFileId);

        return static::ack($cyxDoc, $refDocId);
    }

    /**
     * Метод реализует транспортную логику для новосозданного документа
     *
     * Если документу требуется подписание, то он переводится в статус для подписания
     * и выполняется экстракция данных для подписания
     *
     * Если подписание не требуется, документ сразу отправляется
     *
     * Параметр $immediate отвечает за то, чтобы выполнять экстракцию данных немедленно
     * или запускать джоб. В случаях, когда немедленная экстракция не нужна,
     * предпочтительно делать это в джобе
     *
     * @param Document $document
     * @param type $immediate
     */
    public static function processDocument(Document $document, $immediate = false)
    {
        if ($document->status == Document::STATUS_ACCEPTED) {
            static::createSendingState($document);
        } else if (
            $document->signaturesRequired > 0
            && $document->direction == Document::DIRECTION_OUT
            && $document->status != Document::STATUS_PENDING
            && empty($document->getSignData())
        ) {
            /**
             * Если потребуется подписание, то выполним ExtractSignDataJob
             */
            $document->status = Document::STATUS_PENDING;
            $document->save(false, ['status']);

            if ($immediate) {
                static::extractSignData($document);
            } else {
            	Yii::$app->resque->enqueue('common\jobs\ExtractSignDataJob', ['id' => $document->id]);
            }
        }
    }

    public static function extractSignData(Document $document, CyberXmlDocument $cyxDoc = null)
    {
        try {
            if (!$cyxDoc) {
                if ($document->isEncrypted) {
                    Yii::$app->terminals->setCurrentTerminalId($document->sender);
                    $data = Yii::$app->storage->decryptStoredFile($document->actualStoredFileId);
                    $cyxDoc = new CyberXmlDocument();
                    $cyxDoc->loadXml($data);
                } else {
                    $cyxDoc = CyberXmlDocument::read($document->actualStoredFileId);
                }
            }

            $document->setSignData($cyxDoc->extractSignData());

            if ($document->status == Document::STATUS_PENDING) {
                // Если документ в статусе PENDING, то значит это был документ, требующий подписания
                $document->status = Document::STATUS_FORSIGNING;
            }

            if ($document->save(false, ['status'])) {
                Yii::info($document->type . ' ' . $document->id . ' signature data extracted');
            } else {
                $document->updateStatus(
                    Document::STATUS_CREATING_ERROR,
                    $document->type . ' ' . $document->id . ' failed to extract signature data'
                );
            }
        } catch (Exception $ex) {
            \Yii::info($ex->getMessage());
        }
    }

    public static function sendStatusReport(Document $document, $statusCode, $errorDescription, $errorCode)
    {
        /** @var CyberXmlDocument $cyxDocument */
        $cyxDocument = CyberXmlDocument::read($document->actualStoredFileId);

        $reportTypeModel = new StatusReportType([
            'refDocId' => $document->uuidRemote,
            'statusCode' => $statusCode,
            'errorDescription' => $errorDescription,
            'errorCode' => $errorCode
        ]);

        return DocumentHelper::createAndSendDocument(
            $reportTypeModel,
            $cyxDocument->receiverId,
            $cyxDocument->senderId,
            $document->uuidRemote
        );
    }

}