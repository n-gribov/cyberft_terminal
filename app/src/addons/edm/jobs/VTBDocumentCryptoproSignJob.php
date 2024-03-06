<?php

namespace addons\edm\jobs;

use addons\edm\models\BaseVTBDocument\BaseVTBDocumentType;
use addons\edm\models\CryptoproSigningRequest;
use addons\edm\models\VTBRegisterCur\VTBRegisterCurType;
use addons\edm\models\VTBRegisterRu\VTBRegisterRuType;
use common\base\Job;
use common\document\Document;
use common\helpers\vtb\VTBSignHelper;
use common\jobs\ExtractSignDataJob;
use common\models\CryptoproKey;
use common\models\CryptoproKeyBeneficiary;
use common\models\cyberxml\CyberXmlDocument;
use common\modules\certManager\components\ssl\X509FileModel;
use common\modules\transport\helpers\DocumentTransportHelper;
use Exception;
use Resque_Job_DontPerform;
use Yii;

class VTBDocumentCryptoproSignJob extends Job
{
    /** @var Document */
    private $document;

    /** @var CryptoproSigningRequest */
    private $signingRequest;

    public function setUp()
    {
        parent::setUp();

        if (!isset($this->args['id'])) {
            $this->log('Document id must be set');

            throw new Resque_Job_DontPerform();
        }

        $documentId = $this->args['id'];
        $this->document = Document::findOne($documentId);
        if ($this->document === null) {
            $this->log("Document $documentId is not found");

            throw new Resque_Job_DontPerform();
        }

        $this->signingRequest = CryptoproSigningRequest::findOneByDocument($documentId);
        if ($this->signingRequest === null) {
            $this->log("Cryptopro signing request for document $documentId is not found");

            throw new Resque_Job_DontPerform();
        }
    }

	public function perform()
	{
        $success = true;

        $keys = CryptoproKey::findByTerminalId($this->document->terminalId);
	
	$sender = $this->document->sender;
	$receiver = $this->document->receiver;
	
	$validKeys = [];
	// Фильтруем ключи в зависимости от указанного в документе получателя (CYB-4581)
	foreach ($keys as $cryptoProSignKey) {
		$cryptoProBeneficiaries = CryptoproKeyBeneficiary::findAll(['keyId' => $cryptoProSignKey->id]);
		if (count($cryptoProBeneficiaries) == 0) {
			$validKeys[] = $cryptoProSignKey;
			continue;
		}
		$cryptoProBeneficiaries = CryptoproKeyBeneficiary::findAll(
			[
			    'keyId' => $cryptoProSignKey->id,
			    'terminalId' => $receiver
			]);
		if (count($cryptoProBeneficiaries) != 0) {
			$validKeys[] = $cryptoProSignKey;
		}
	}
	
        if (!empty($validKeys)) {
            $success = $this->signWithKeys($validKeys);
        } else {
            $this->log("Cryptopro keys for terminal {$this->document->terminalId} are not found");
            $success = false;
        }
	
        if (!$success) {
            Yii::$app->monitoring->log('document:CryptoProSigningError', 'document', $this->document->id);
            $this->signingRequest->status = CryptoproSigningRequest::STATUS_SIGNING_ERROR;
            $this->signingRequest->save();
            $this->document->updateStatus(Document::STATUS_PROCESSING_ERROR);
        } else {
            $this->signingRequest->status = CryptoproSigningRequest::STATUS_SIGNED;
            $this->signingRequest->save();
            $this->log("{$this->document->type} {$this->document->id} is signed with cryptopro keys");

            Yii::$app->resque->enqueue(ExtractSignDataJob::class, ['id' => $this->document->id]);
            Yii::$app->addon->getModule('edm')->processDocument($this->document);
            if ($this->document->status == Document::STATUS_ACCEPTED) {
                DocumentTransportHelper::createSendingState($this->document);
            }
        }
	}

    private function signWithKeys($keys)
    {
        foreach ($keys as $key) {
            $isSigned = $this->signWithKey($key);

            if (!$isSigned) {
                $this->log("{$this->document->type} {$this->document->id} failed to sign with cryptopro key {$key->id}");
                return false;
            }
        }

        return true;
    }

    private function signWithKey(CryptoproKey $key)
    {
        try {
            if ($this->document->type == VTBRegisterRuType::TYPE ||
                $this->document->type == VTBRegisterCurType::TYPE) {
                $cyx = CyberXmlDocument::read($this->document->getValidStoredFileId());
                $typeModel = $cyx->getContent()->getTypeModel();

                foreach($typeModel->paymentOrders as $vtbPayDoc) {
                    $signature = VTBSignHelper::sign(
                        $vtbPayDoc->document,
                        $vtbPayDoc->documentVersion,
                        VTBDocumentCryptoproSignJob::getKeyPassword($key),
                        VTBDocumentCryptoproSignJob::getKeyCommonName($key)
                    );

                    if ($signature === false) {
                        return false;
                    }

                    $vtbPayDoc->injectSignature($signature, $key->certData);
                }

                $cyx->getContent()->markDirty();
                $fileInfo = $cyx->getStoredFile()->updateData($cyx->saveXML());

                if ($fileInfo === null) {
                    $this->log('Failed to update stored');
                    return false;
                }

                return true;
            } else {
                $typeModel = CyberXmlDocument::getTypeModel($this->document->getValidStoredFileId());
		
                $signature = VTBSignHelper::sign(
                    $typeModel->document,
                    $typeModel->documentVersion,
                    static::getKeyPassword($key),
                    static::getKeyCommonName($key)
                );

                if ($signature === false) {
                    return false;
                }

                return $this->storeSignature($signature, $key);
            }

        } catch (Exception $exception) {
            $this->log("Document signature failed, caused by: $exception", true);

            return false;
        }
    }

    public static function getKeyCommonName(CryptoproKey $key)
    {
        $x509Info = X509FileModel::loadData($key->certData);
        return $x509Info->subject['CN'];
    }

    public static function getKeyPassword(CryptoproKey $key)
    {
        $passwordKey = getenv('COOKIE_VALIDATION_KEY');
        return Yii::$app->security->decryptByKey(base64_decode($key->password), $passwordKey);
    }

    private function storeSignature($signature, CryptoproKey $key)
    {
        $cyx = CyberXmlDocument::read($this->document->getValidStoredFileId());
        /** @var BaseVTBDocumentType $typeModel */
        $typeModel = $cyx->getContent()->getTypeModel();

        $isInjected = $typeModel->injectSignature($signature, $key->certData);
        if (!$isInjected) {
            $this->log('Failed to inject signature into type model');
            return false;
        }

        $cyx->getContent()->markDirty();
        $fileInfo = $cyx->getStoredFile()->updateData($cyx->saveXML());
        if ($fileInfo === null) {
            $this->log('Failed to update stored');
            return false;
        }

        return true;
    }

}
