<?php

namespace addons\VTB\helpers;

use addons\edm\models\BaseVTBDocument\BaseVTBDocumentType;
use addons\edm\models\PaymentStatusReport\PaymentStatusReportType;
use addons\edm\models\VTBRegisterCur\VTBRegisterCurType;
use addons\edm\models\VTBRegisterRu\VTBRegisterRuType;
use common\document\Document;
use common\helpers\DocumentHelper;
use common\models\cyberxml\CyberXmlDocument;

class VTBModuleHelper
{
    public static function sendPaymentStatusReport(Document $paymentDocument, $groupStatusCode, $transactionStatusCode, $statusDescription, $statusComment)
    {
        /** @var CyberXmlDocument $paymentCyxDocument */
        $paymentCyxDocument = CyberXmlDocument::read($paymentDocument->actualStoredFileId);

        /** @var BaseVTBDocumentType $paymentDocumentTypeModel */
        $paymentDocumentTypeModel = $paymentCyxDocument->content->getTypeModel();
        $vtbDocument = $paymentDocumentTypeModel->document;

        $reportTypeModel = new PaymentStatusReportType([
            'refDocId'          => $paymentCyxDocument->docId,
            'refDocDate'        => $paymentCyxDocument->docDate,
            'refSenderId'       => $paymentCyxDocument->senderId,
            'registerId'        => 'noref',
            'registerDate'      => $paymentCyxDocument->docDate,
            'statusCode'        => $groupStatusCode,
            'statusDescription' => $statusDescription,
            'statusComment'     => $statusComment,
            'paymentCount'      => 1,
            'paymentSum'        => property_exists($vtbDocument, 'AMOUNT') ? $vtbDocument->AMOUNT : null,
        ]);

        // Когда статус нужно отправить на реестр платежных поручений
        if ($paymentDocument->uuidReference) {
            $register = Document::findOne(
                [
                    'uuid' => $paymentDocument->uuidReference,
                    'type' => [VTBRegisterRuType::TYPE, VTBRegisterCurType::TYPE]
                ]
            );

            if ($register) {
                $reportTypeModel->refDocId = $register->uuidRemote;
                $reportTypeModel->statusDescription = '';
                $reportTypeModel->statusComment = '';
            }
        }

        if (property_exists($vtbDocument, 'DOCUMENTNUMBER')) {
            $reportTypeModel->transactionStatus = [
                $vtbDocument->DOCUMENTNUMBER => [
                    'statusCode'        => $transactionStatusCode,
                    'statusDescription' => $statusDescription,
                    'statusReason'      => $statusComment,
                ]
            ];
        }

        return DocumentHelper::createAndSendDocument(
            $reportTypeModel,
            $paymentCyxDocument->receiverId,
            $paymentCyxDocument->senderId,
            $paymentDocument->uuidRemote
        );
    }

}
