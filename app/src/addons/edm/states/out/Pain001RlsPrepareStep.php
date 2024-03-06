<?php

namespace addons\edm\states\out;

use addons\edm\models\ForeignCurrencyOperation\ForeignCurrencyOperationDocumentExt;
use addons\edm\models\ForeignCurrencyOperation\ForeignCurrencyOperationFactory;
use addons\edm\models\Pain001Rls\Pain001RlsType;
use addons\ISO20022\helpers\ISO20022Helper;
use addons\ISO20022\models\Pain001Type;
use common\document\Document;
use common\modules\transport\helpers\DocumentTransportHelper;
use Yii;

class Pain001RlsPrepareStep extends BasePain001PrepareStep
{
    public function run()
    {
        /** @var Pain001RlsType $typeModel */
        $typeModel = $this->state->model;
        ISO20022Helper::addMissingHeadersToPain001($typeModel);

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $organization = $this->getSenderOrganization($typeModel);
            $typeModel->injectMissingDebtorAttributes(
                $organization->name,
                $organization->fullAddress
            );

            $this->validateXml($typeModel);

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
        $fcst = ForeignCurrencyOperationFactory::constructFCSTFromPain001($typeModel, $typeModel->getDebitAccountNumber());
        return [
            'numberDocument' => $fcst->number,
            'date'           => date('Y-m-d', strtotime($fcst->date)),
            'debitAccount'   => $fcst->transitAccount,
            'creditAccount'  => $fcst->account ?: '',
            'currency'       => $fcst->amountCurrency,
            'currencySum'    => (float)$fcst->amountSell + (float)$fcst->amountTransfer,
            'documentType'   => ForeignCurrencyOperationFactory::OPERATION_SELL_TRANSIT_ACCOUNT,
            'uuid'           => $typeModel->msgId,
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
}
