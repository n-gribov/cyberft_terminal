<?php

namespace addons\edm\states\out;

use addons\edm\models\EdmPayerAccount;
use addons\edm\models\ForeignCurrencyOperation\ForeignCurrencyOperationDocumentExt;
use addons\edm\models\ForeignCurrencyOperation\ForeignCurrencyOperationFactory;
use addons\edm\models\Pain001Fx\Pain001FxType;
use addons\ISO20022\helpers\ISO20022Helper;
use addons\ISO20022\models\Pain001Type;
use common\document\Document;
use common\modules\transport\helpers\DocumentTransportHelper;
use Yii;

class Pain001FxPrepareStep extends BasePain001PrepareStep
{
    public function run()
    {
        /** @var Pain001FxType $typeModel */
        $typeModel = $this->state->model;
        ISO20022Helper::addMissingHeadersToPain001($typeModel);

        $transaction = Yii::$app->db->beginTransaction();
        try {      
            $organization = $this->getSenderOrganization($typeModel);
            $typeModel->injectMissingDebtorAttributes(
                $organization->name,
                $organization->fullAddress
            );
            $this->ensureTypeModelIsValid($typeModel);
            $extModelAttributes = $this->createExtModelAttributes($typeModel);
            $this->ensureDocumentIsNotDuplicate($extModelAttributes);
            $document = $this->createDocument($typeModel, $extModelAttributes);
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
        $fco = ForeignCurrencyOperationFactory::constructFCOFromPain001($typeModel);
        return [
            'documentType'   => $fco->getType(),
            'numberDocument' => $fco->numberDocument,
            'date'           => date('Y-m-d', strtotime($fco->date)),
            'debitAccount'   => $fco->debitAccount->number,
            'creditAccount'  => $fco->creditAccount->number,
            'currency'       => $fco->paymentOrderCurrCode,
            'currencySum'    => $fco->paymentOrderCurrAmount,
            'sum'            => $fco->paymentOrderAmount,
            'uuid'           => $fco->uuid,
        ];
    }

    private function ensureDocumentIsNotDuplicate(array $extModelAttributes): void
    {
        $extTableName = ForeignCurrencyOperationDocumentExt::tableName();
        $baseQuery = Document::find()
            ->innerJoin(
                "$extTableName ext",
                'ext.documentId = document.id'
            )
            ->where([
                'and',
                ['=', 'document.direction', Document::DIRECTION_OUT],
                ['not in', 'document.status', [Document::STATUS_REJECTED, Document::STATUS_DELETED]],
                [
                    'or',
                    ['!=', 'ext.businessStatus', 'RJCT'],
                    ['ext.businessStatus' => null],
                ],
            ]);

        $isDuplicateNumberDateAndAccount = (clone $baseQuery)
            ->andWhere([
                'ext.numberDocument' => $extModelAttributes['numberDocument'],
                'ext.date'           => $extModelAttributes['date'],
                'ext.debitAccount'   => $extModelAttributes['debitAccount'],
            ])
            ->exists();

        if ($isDuplicateNumberDateAndAccount) {
            $this->raiseDuplicateDocumentError($extModelAttributes['numberDocument'], $extModelAttributes['date']);
        }

        $isDuplicateUuid = (clone $baseQuery)
            ->andWhere(['ext.uuid' => $extModelAttributes['uuid']])
            ->exists();

        if ($isDuplicateUuid) {
            throw new \DomainException(
                Yii::t(
                    'app/iso20022',
                    'Document with message id {messageId} already exists',
                    ['messageId' => $extModelAttributes['uuid']]
                )
            );
        }
    }

    private function ensureTypeModelIsValid(Pain001FxType $typeModel): void
    {
        $this->validateXml($typeModel);

        $xml = $typeModel->getRawXML();
        $accountNumber = (string)@$xml->CstmrCdtTrfInitn->PmtInf->DbtrAcct->Id->Othr->Id ?: null;
        if (!$accountNumber) {
            throw new \DomainException(Yii::t('edm', 'Payer account is not specified'));
        }

        $this->ensureAccountExists($accountNumber);
        $this->ensureAccountCurrenciesAreValid($typeModel);
    }

    private function ensureAccountExists(string $accountNumber): void
    {
        $accountExists = EdmPayerAccount::find()
            ->where(['number' => $accountNumber])
            ->exists();
        if (!$accountExists) {
            throw new \DomainException(Yii::t('edm', 'Payer account {number} is not found', ['number' => $accountNumber]));
        }
    }

    private function ensureAccountCurrenciesAreValid(Pain001FxType $typeModel): void
    {
        $debitAccountCurrency = $typeModel->getDebitAccountCurrency();
        if (!$debitAccountCurrency) {
            throw new \Exception('Debit account currency is not found');
        }
        $creditAccountCurrency = $typeModel->getCreditAccountCurrency();
        if (!$creditAccountCurrency) {
            throw new \Exception('Credit account currency is not found');
        }

        function isRuble(string $currency): bool
        {
            return in_array($currency, ['RUR', 'RUB']);
        }

        if (!isRuble($debitAccountCurrency) && !isRuble($creditAccountCurrency)) {
            throw new \DomainException(Yii::t('edm', 'One of transaction accounts must be in rubles'));
        }

        if (isRuble($debitAccountCurrency) && isRuble($creditAccountCurrency)) {
            throw new \DomainException(Yii::t('edm', 'One of transaction accounts must be in foreign currency'));
        }
    }
}
