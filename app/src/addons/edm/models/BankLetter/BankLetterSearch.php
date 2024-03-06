<?php

namespace addons\edm\models\BankLetter;

use addons\edm\models\DictBank;
use addons\edm\models\EdmPayerAccount;
use addons\edm\models\EdmPayerAccountUser;
use addons\edm\models\VTBFreeBankDoc\VTBFreeBankDocType;
use addons\edm\models\VTBFreeClientDoc\VTBFreeClientDocType;
use addons\ISO20022\models\Auth026Type;
use addons\ISO20022\models\ISO20022DocumentExt;
use common\base\traits\ForSigningCountable;
use common\document\Document;
use common\document\DocumentSearch;
use common\helpers\DocumentHelper;
use common\models\Terminal;
use common\models\User;
use common\modules\participant\models\BICDirParticipant;
use Yii;
use yii\db\ActiveQuery;

class BankLetterSearch extends DocumentSearch
{
    use ForSigningCountable;

    public $subject;
    public $message;
    public $sender;
    public $receiver;
    public $businessStatus;

    public function rules()
    {
        return array_merge(
            parent::rules(),
            [
                [['subject', 'message', 'sender', 'receiver', 'businessStatus',
                    'signaturesRequired', 'signaturesCount'], 'safe']
            ]
        );
    }

    public function attributeLabels()
    {
        return array_merge(
            parent::attributeLabels(),
            [
                'subject' => Yii::t('edm', 'Subject'),
                'message' => Yii::t('edm', 'Message text'),
                'businessStatus' => Yii::t('document','Execution status'),
            ]
        );
    }

    public function getBusinessStatusLabel()
    {
        $statusLabels = static::getBusinessStatusLabels();
        if (array_key_exists($this->businessStatus, $statusLabels)) {
            return $statusLabels[$this->businessStatus];
        }
        return $this->businessStatus;
    }

    public static function getBusinessStatusLabels()
    {
        static $statusLabels = null;
        if ($statusLabels === null) {
            $statusLabels = DocumentHelper::getBusinessStatusesList();
        }
        return $statusLabels;
    }

    /**
     * @param ActiveQuery $query
     * @return void
     */
    public function applyExtFilters($query)
    {
        $query
            ->joinWith('senderParticipant senderParticipant')
            ->joinWith('receiverParticipant receiverParticipant');

        $query
            ->leftJoin(BankLetterDocumentExt::tableName() . ' bankLetterExt', 'bankLetterExt.documentId = document.id')
            ->leftJoin(ISO20022DocumentExt::tableName() . ' isoExt', 'isoExt.documentId = document.id');

        $query->andWhere(['document.type' => static::getDocumentsTypes()]);
        static::applyBankAccessFilter($query);

        $this->_select[] = 'coalesce(bankLetterExt.subject, isoExt.subject) as subject';
        $this->_select[] = 'coalesce(bankLetterExt.message, isoExt.descr) as message';
        $this->_select[] = 'coalesce(bankLetterExt.businessStatus, isoExt.statusCode) as businessStatus';

        $query->andFilterWhere([
            'or',
            ['like', 'bankLetterExt.subject', $this->subject],
            ['like', 'isoExt.subject', $this->subject]
        ]);
        $query->andFilterWhere([
            'or',
            ['like', 'bankLetterExt.message', $this->message],
            ['like', 'isoExt.descr', $this->message]
        ]);
        $query->andFilterWhere([
            'or',
            ['like', 'bankLetterExt.businessStatus', $this->businessStatus],
            ['like', 'isoExt.statusCode', $this->businessStatus]
        ]);
        $query->andFilterWhere(['like', 'senderParticipant.name', $this->sender]);
        $query->andFilterWhere(['like', 'receiverParticipant.name', $this->receiver]);
        $query->andFilterWhere(['signaturesRequired' => $this->signaturesRequired]);
        $query->andFilterWhere(['signaturesCount' => $this->signaturesCount]);
    }

    public static function getUnreadCount(): int
    {
        return static::unreadQuery()->count();
    }

    public static function getUnreadIds(): array
    {
        return static::unreadQuery()
            ->select('id')
            ->column();
    }

    private static function unreadQuery(): ActiveQuery
    {
        $query = Yii::$app->terminalAccess->query(
            static::class,
            [
                'direction' => static::DIRECTION_IN,
                'type' => static::getDocumentsTypes(),
                'viewed' => 0
            ]
        );
        static::applyBankAccessFilter($query);
        return $query;
    }

    public function getTerminal()
    {
        return $this->hasOne(Terminal::className(), ['id' => 'terminalId']);
    }

    public function getSenderParticipant()
    {
        return $this->hasOne(BICDirParticipant::className(), ['participantBIC' => 'senderParticipantId']);
    }

    public function getReceiverParticipant()
    {
        return $this->hasOne(BICDirParticipant::className(), ['participantBIC' => 'receiverParticipantId']);
    }

    private static function applyBankAccessFilter(ActiveQuery $query)
    {
        // Получить модель пользователя из активной сессии
        $currentUser = Yii::$app->user->identity;
        if (!in_array($currentUser->role, [User::ROLE_ADMIN, User::ROLE_ADDITIONAL_ADMIN])) {
            $accountsIds = EdmPayerAccountUser::getUserAllowAccounts($currentUser->id);

            $bikQuery = EdmPayerAccount::find()
                ->where(['in', 'id', $accountsIds])
                ->select('bankBik');
            $bankTerminalQuery = DictBank::find()
                ->where(['bik' => $bikQuery])
                ->select('terminalId');
        } else {
            $bankTerminalQuery = DictBank::find()
                ->where([
                    'and',
                    ['not', ['terminalId' => null]],
                    ['not', ['terminalId' => '']],
                ])
                ->select('terminalId');
        }

        $query->andWhere([
            'or',
            [
                'direction' => Document::DIRECTION_IN,
                'sender' => $bankTerminalQuery
            ],
            [
                'direction' => Document::DIRECTION_OUT,
                'receiver' => $bankTerminalQuery
            ]
        ]);
    }

    private static function getDocumentsTypes()
    {
        return [VTBFreeClientDocType::TYPE, VTBFreeBankDocType::TYPE, Auth026Type::TYPE];
    }

    protected function applySignaturePermissionFilter(ActiveQuery $query): void
    {
        Yii::$app->edmAccountAccess->queryBanksHavingSignableAccounts($query, 'bankBik');
    }
}
