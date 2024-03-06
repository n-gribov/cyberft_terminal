<?php

namespace addons\edm\states\out;

use addons\edm\models\CurrencyPaymentRegister\CurrencyPaymentRegisterDocumentExt;
use addons\edm\models\ForeignCurrencyOperation\ForeignCurrencyOperationDocumentExt;
use addons\edm\models\ForeignCurrencyOperation\ForeignCurrencyOperationFactory;
use addons\edm\models\Pain001Fcy\Pain001FcyType;
use addons\ISO20022\helpers\ISO20022Helper;
use addons\ISO20022\models\Pain001Type;
use common\document\Document;
use common\modules\transport\helpers\DocumentTransportHelper;
use Yii;

class Pain001FcyPrepareStep extends BasePain001PrepareStep
{
    public function run()
    {
        /** @var Pain001FcyType $typeModel */
        $typeModel = $this->state->model;
        ISO20022Helper::addMissingHeadersToPain001($typeModel);

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $organization = $this->getSenderOrganization($typeModel);
            $typeModel->injectMissingDebtorAttributes(
                $organization->nameLatin ?: $organization->name,
                $organization->getFullAddressLatin() ?: $organization->getFullAddress()
            );

            $this->validateXml($typeModel);

            $extModelAttributes = $this->createExtModelAttributes($typeModel);
            $this->ensureDocumentIsNotDuplicate($extModelAttributes);
            $document = $this->createDocument($typeModel, $extModelAttributes);
            $this->createPayments($document, $typeModel);
            DocumentTransportHelper::processDocument($document, true);
            $transaction->commit();
        } catch (\Exception $exception) {
            $transaction->rollBack();
            $this->handleException($exception);
            return false;
        }

        // Предотвращаем запуск DocumentOutState
        $this->state->stop();

        return true;
    }

    protected function createExtModelAttributes(Pain001Type $typeModel): array
    {
        /** @var \SimpleXMLElement $xml */
        $xml = $typeModel->getRawXml();
        $date = (string)@$xml->CstmrCdtTrfInitn->GrpHdr->CreDtTm ?: null;
        $paymentElements = $xml->xpath("/*[local-name()='Document']/*[local-name()='CstmrCdtTrfInitn']/*[local-name()='PmtInf']/*[local-name()='CdtTrfTxInf']");

        return [
            'date'          => $date ? date('Y-m-d', strtotime($date)) : null,
            'debitAccount'  => (string)@$xml->CstmrCdtTrfInitn->PmtInf->DbtrAcct->Id->Othr->Id ?: null,
            'uuid'          => $typeModel->msgId,
            'paymentsCount' => count($paymentElements),
        ];
    }

    private function ensureDocumentIsNotDuplicate(array $extModelAttributes): void
    {
        $uuid = $extModelAttributes['uuid'] ?? null;
        if (empty($uuid)) {
            throw new \Exception('Got empty document uuid');
        }

        $extTableName = CurrencyPaymentRegisterDocumentExt::tableName();
        $documentExists = Document::find()
            ->innerJoin(
                "$extTableName ext",
                'ext.documentId = document.id'
            )
            ->where([
                'and',
                ['=', 'document.direction', Document::DIRECTION_OUT],
                ['not in', 'document.status', [Document::STATUS_REJECTED, Document::STATUS_DELETED]],
                ['=', 'ext.uuid', $uuid],
                [
                    'or',
                    ['!=', 'ext.businessStatus', 'RJCT'],
                    ['ext.businessStatus' => null],
                ],
            ])
            ->exists();

        if ($documentExists) {
            $this->raiseDuplicateDocumentError($uuid, $extModelAttributes['date'] ?? null);
        }
    }

    private function createPayments(Document $document, Pain001FcyType $typeModel): void
    {
        $payments = ForeignCurrencyOperationFactory::constructForeignCurrencyPaymentsFromPain001($typeModel);
        foreach ($payments as $payment) {
            $fcoExt = new ForeignCurrencyOperationDocumentExt(['documentId' => $document->id]);
            $fcoExt->loadContentModel($payment);
            $isSaved = $fcoExt->save();
            if (!$isSaved) {
                throw new \Exception('Failed to save payment order to database, errors: ' . var_export($fcoExt->getErrors(), true));
            }
        }
    }
}
