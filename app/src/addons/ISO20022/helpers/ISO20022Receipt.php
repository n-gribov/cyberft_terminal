<?php


namespace addons\ISO20022\helpers;

use common\models\cyberxml\CyberXmlDocument;
use Exception;
use Yii;
use yii\base\DynamicModel;

class ISO20022Receipt
{
    private $cyxDocument;
    private $documentFileName;
    private $documentExportDate;

    public function __construct(CyberXmlDocument $cyxDocument, $documentFileName, \DateTime $documentExportDate)
    {
        $this->cyxDocument = $cyxDocument;
        $this->documentFileName = $documentFileName;
        $this->documentExportDate = $documentExportDate;
    }

    public function export($receiptsDirPath)
    {
        $receiptBody = $this->create();
        $filePath = $this->getReceiptExportFilePath($receiptsDirPath);
        $bytesWritten = file_put_contents($filePath, $receiptBody);
        if ($bytesWritten === false) {
            throw new Exception("file_put_contents() has failed to save to file $filePath");
        }

        return $filePath;
    }

    public function create()
    {
        $receiptDate = date('c');
        $exportDate = $this->documentExportDate->format('c');
        $typeModel = $this->cyxDocument->getContent()->getTypeModel();

        $receipt = <<<RECEIPT
{$receiptDate}           ISO20022 CyberFT
_______________________________________________________________________________
    ---------------------  Instance Type and Transmission ---------------------
       Notification (Information) of Original received from CyberFT
       Priority      : Normal
       Message Output Reference  : {$this->documentFileName}
    ------------------------------ Message Header -----------------------------
       CyberFT Output            : iso20022

       Requestor DN              : {$typeModel->sender}
       Responder DN              : {$typeModel->receiver}

       SWIFT  Request Reference  : {$typeModel->msgId}
    ------------------------------ Interventions ------------------------------
       Creation Time : {$exportDate}
       Application   : CyberFT
    ------------------------------- Signatories -------------------------------
RECEIPT;

        $receipt .= PHP_EOL . $this->createSignersBlock();

        return $receipt;
    }

    private function getReceiptExportFilePath($receiptsDirPath)
    {
        $senderId = $this->cyxDocument->senderId;
        $receiverId = $this->cyxDocument->receiverId;
        $messageId = $this->cyxDocument->getContent()->getTypeModel()->msgId;

        return "$receiptsDirPath/receipt_iso20022_{$senderId}_{$receiverId}_{$messageId}.prt";
    }

    private function createSignersBlock()
    {
        $signers = $this->getSigners();

        return array_reduce(
            $signers,
            function ($carry, $signer) {
                $name = str_pad($signer->name, 26, ' ', STR_PAD_RIGHT);
                return "$carry       {$name}: {$signer->fingerprint}" . PHP_EOL;
            },
            ''
        );
    }

    private function getSigners()
    {
        $mySignVerifier = Yii::$app->xmlsec;
        $certManager = Yii::$app->getModule('certManager');

        $allSignatures = $mySignVerifier->locateAllSignatures($this->cyxDocument->getDom());

        $signers = [];
        foreach ($allSignatures as $signatureClass => $signaturesList) {
            for ($signatureCnt = 0; $signatureCnt < $signaturesList->length; $signatureCnt++) {
                $signature = $signaturesList->item($signatureCnt);
                $fingerprint = $mySignVerifier->getFingerprint($signature);
                $cert = $certManager->getCertificateByAddress($this->cyxDocument->senderId, $fingerprint);
                if (!empty($cert)) {
                    $signers[] = new DynamicModel([
                        'name' => $cert->fullName,
                        'fingerprint' => $fingerprint
                    ]);
                }
            }
        }

        return $signers;
    }
}
