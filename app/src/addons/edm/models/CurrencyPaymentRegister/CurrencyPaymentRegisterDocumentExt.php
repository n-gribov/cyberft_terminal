<?php

namespace addons\edm\models\CurrencyPaymentRegister;

use addons\edm\models\EdmPayerAccount;
use addons\edm\models\EdmPayerAccountUser;
use common\base\interfaces\DocumentExtInterface;
use common\document\Document;
use common\helpers\DocumentHelper;
use common\models\User;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int $documentId
 * @property string $date
 * @property string $payer
 * @property string $debitAccount
 * @property int $paymentsCount
 * @property string $uuid
 * @property string $businessStatus
 * @property string $businessStatusDescription
 * @property string $businessStatusComment
 * @property Document $document
 */
class CurrencyPaymentRegisterDocumentExt extends ActiveRecord implements DocumentExtInterface
{
    public static function tableName()
    {
        return 'documentExtEdmCurrencyPaymentRegister';
    }

    public function loadContentModel($model)
    {
    }

    public function isDocumentDeletable(User $user = null)
    {
        return true;
    }

    public function getDocument()
    {
        return $this->hasOne(Document::class, ['id' => 'documentId']);
    }

    public function getBusinessStatusTranslation()
    {
        $labels = DocumentHelper::getBusinessStatusesList();

        return isset($labels[$this->businessStatus]) ? $labels[$this->businessStatus] : $this->businessStatus;
    }

    public function canBeSignedByUser(User $user, Document $document): bool
    {
        $account = EdmPayerAccount::findOne(['number' => $this->debitAccount]);
        return $account !== null && EdmPayerAccountUser::userCanSingDocuments($user->id, $account->id);
    }
}
