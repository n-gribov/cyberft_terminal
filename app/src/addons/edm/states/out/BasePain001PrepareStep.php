<?php

namespace addons\edm\states\out;

use addons\edm\models\DictOrganization;
use addons\edm\models\EdmPayerAccount;
use addons\ISO20022\models\Pain001Type;
use common\document\Document;
use common\exceptions\InvalidImportedDocumentException;
use common\helpers\DocumentHelper;
use common\models\Terminal;
use Yii;

/**
 * Класс содержит методы для подготовки отправки документа семейства Pain001
 */
abstract class BasePain001PrepareStep extends ISO20022DocumentPrepareStep
{
    /**
     * Метод создаёт документ типа Pain001
     * @param Pain001Type $typeModel
     * @param array $extModelAttributes
     * @return Document
     * @throws \Exception
     */
    protected function createDocument(Pain001Type $typeModel, array $extModelAttributes): Document
    {
        // Получить id терминала по адресу отправителя
        $terminalId = Terminal::getIdByAddress($this->state->sender);
        // Атрибуты документа
        $docAttributes = [
            'sender'     => $this->state->sender,
            'receiver'   => $typeModel->receiver,
            'type'       => $typeModel->getType(),
            'direction'  => Document::DIRECTION_OUT,
            'terminalId' => $terminalId,
            'origin'     => Document::ORIGIN_FILE,
        ];

        // Если стейт содержит Api UUID, добавить его в атрибуты документа
        if ($this->state->apiUuid) {
            $docAttributes['uuid'] = $this->state->apiUuid;
        }

        // Создать контекст документа
        $context = DocumentHelper::createDocumentContext($typeModel, $docAttributes, $extModelAttributes);

        if (!$context) {
            throw new \Exception(\Yii::t('app', 'Save document error'));
        }

        return $context['document'];
    }

    /**
     * Метод получает организацию отправителя
     * @param Pain001Type $typeModel
     * @return DictOrganization
     */
    protected function getSenderOrganization(Pain001Type $typeModel): DictOrganization
    {
        $account = $this->getEdmPayerAccount($typeModel);
        return $account->edmDictOrganization;
    }

    /**
     * Метод получает сч1т плательщика
     * @param Pain001Type $typeModel
     * @return EdmPayerAccount
     * @throws \DomainException
     */
    protected function getEdmPayerAccount(Pain001Type $typeModel): EdmPayerAccount
    {
        $xml = $typeModel->getRawXML();
        $accountNumber = (string)@$xml->CstmrCdtTrfInitn->PmtInf->DbtrAcct->Id->Othr->Id ?: null;
        if (empty($accountNumber)) {
            throw new \DomainException(Yii::t('edm', 'Payer account is not specified'));
        }

        $payerAccount = EdmPayerAccount::findOne(['number' => $accountNumber]);
        if ($payerAccount === null) {
            throw new \DomainException(Yii::t('edm', 'Payer account {number} is not found', ['number' => $accountNumber]));
        }

        return $payerAccount;
    }

    protected function raiseDuplicateDocumentError($number, $date): void
    {
        $message = Yii::t(
            'edm',
            'Found duplicate document number {num} date {date}',
            [
                'num'  => $number,
                'date' => $date,
            ]
        );

        throw new InvalidImportedDocumentException($message, $number);
    }

    /**
     * Метод получает валюту документа
     * @return string|null
     */
    protected function getDocumentCurrency(): ?string
    {
        try {
            $account = $this->getEdmPayerAccount($this->state->model);
            return $account->edmDictCurrencies
                ? $account->edmDictCurrencies->name
                : null;
        } catch (\Throwable $exception) {
            return null;
        }
    }

    /**
     * Метод получает идентификатор группы типов документа
     * @return string|null
     */
    protected function getDocumentTypeGroupId(): ?string
    {
        try {
            $resolver = $this->state->module->createDocumentTypeGroupResolver();
            if (!$resolver) {
                return null;
            }
            /** @var Pain001Type $typeModel */
            $typeModel = $this->state->model;
            $group = $resolver->resolve($typeModel->getType(), $this->createExtModelAttributes($typeModel));
            return $group ? $group->id : null;
        } catch (\Throwable $exception) {
            $this->log("Cannot detect document type group, caused by: $exception");
            return null;
        }
    }

    /**
     * Метод создаёт расширенные атрибуты типа
     */
    abstract protected function createExtModelAttributes(Pain001Type $typeModel): array;
}
