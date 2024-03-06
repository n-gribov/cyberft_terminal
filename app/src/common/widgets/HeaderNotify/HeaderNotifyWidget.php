<?php

namespace common\widgets\HeaderNotify;

use addons\edm\models\BankLetter\BankLetterSearch;
use addons\edm\models\ConfirmingDocumentInformation\ConfirmingDocumentInformationSearch;
use addons\edm\models\ContractRegistrationRequest\ContractRegistrationRequestSearch;
use addons\edm\models\CurrencyPayment\CurrencyPaymentDocumentSearch;
use addons\edm\models\ForeignCurrencyControl\ForeignCurrencyControlSearch;
use addons\edm\models\ForeignCurrencyOperation\ForeignCurrencyOperationSearch;
use addons\edm\models\PaymentRegister\PaymentRegisterSearch;
use addons\edm\models\Statement\StatementSearch;
use addons\edm\models\StatementRequest\StatementRequestSearch;
use addons\edm\models\VTBCancellationRequest\VTBCancellationRequestSearch;
use addons\finzip\models\FinZipSearch;
use common\document\Document;
use common\document\DocumentPermission;
use common\models\User;
use common\models\UserTerminal;
use Yii;
use yii\base\Widget;
use yii\helpers\Url;

class HeaderNotifyWidget extends Widget
{
    const TYPE_FOR_SIGNING = 1;
    const TYPE_NEW = 2;
    const TYPE_STOPPED_TERMINALS_USER = 3;
    const TYPE_STOPPED_TERMINALS_ADMIN = 4;

    public $type;

    public function run()
    {
        // Вывод виджета по типу
        switch ($this->type) {
            case self::TYPE_FOR_SIGNING:
                $html = $this->renderForSinging();
                break;
            case self::TYPE_NEW:
                $html = $this->renderNew();
                break;
            case self::TYPE_STOPPED_TERMINALS_USER:
                $html = $this->renderStoppedTerminals();
                break;
            case self::TYPE_STOPPED_TERMINALS_ADMIN:
                $html = $this->renderStoppedTerminals();
                break;
        }

        return $html;
    }

    /**
     * Документы к подписанию
     * @return null|string
     */
    private function renderForSinging()
    {
        $docCountList = [];

        $allForSinging = 0;

        $addons = [
            'finzip' => [
                'name' => 'finzip',
                'title' => Yii::t('app/menu', 'Free Format'),
                'url' => Url::toRoute('/finzip/documents/signing-index')
            ],
            'fileact' => [
                'name' => 'fileact',
                'title' => 'FileAct',
                'url' => Url::toRoute('/fileact/documents/signing-index')
            ],
            'swiftfin' => [
                'name' => 'swiftfin',
                'title' => 'Swiftfin',
                'url' => Url::toRoute('/swiftfin/documents/signing-index')
            ],
        ];

        foreach($addons as $name => $addon) {
            if ($module = Yii::$app->addon->getModule($name)) {

                $userCanSignDocuments = \Yii::$app->user->can(DocumentPermission::SIGN, ['serviceId' => $addon['name']]);
                if (!$userCanSignDocuments) {
                    continue;
                }

                $queryParams = [
                    'typeGroup' => $name,
                    'status' => [Document::STATUS_FORSIGNING],
                    'signaturesCount' => Yii::$app->user->identity->signatureNumber - 1
                ];

                // Получить из БД список документов через компонент авторизации доступа к терминалам
                $countForSigning = Yii::$app->terminalAccess
                    ->query(Document::className(), $queryParams)->count();

                if ($countForSigning) {
                    $allForSinging += $countForSigning;
                    $docCountList[$addon['title'] . ': ' . $countForSigning] = $addon['url'];
                }
            }
        }

        // Реестры платежных поручений

        $userCanSignEdmDocuments = \Yii::$app->user->can(
            DocumentPermission::SIGN,
            [
                'serviceId' => 'edm',
                'documentTypeGroup' => '*',
            ]
        );
        if ($userCanSignEdmDocuments) {

            $substituteConfig = ['substituteServices' => ['edm' => 'ISO20022']];

            $groups = [
                PaymentRegisterSearch::class => [
                    'documentTypeName' => Yii::t('edm', 'Payment registers'),
                    'signingUrl' => '/edm/documents/signing-index',
                    'config' => $substituteConfig
                ],
                CurrencyPaymentDocumentSearch::class => [
                    'documentTypeName' => Yii::t('edm', 'Currency payments'),
                    'signingUrl' => '/edm/documents/signing-index?tabMode=tabCurrencyPayments',
                    'config' => $substituteConfig
                ],
                ForeignCurrencyOperationSearch::class => [
                    'documentTypeName' => Yii::t('edm', 'Foreign currency operations'),
                    'signingUrl' => '/edm/documents/signing-index?tabMode=tabFCOSigning',
                    'config' => $substituteConfig
                ],
                ForeignCurrencyControlSearch::class => [
                    'documentTypeName' => Yii::t('edm', 'Information'),
                    'signingUrl' => '/edm/documents/signing-index?tabMode=tabFCCSigning',
                    'config' => $substituteConfig
                ],
                ConfirmingDocumentInformationSearch::class => [
                    'documentTypeName' => Yii::t('edm', 'Confirmation'),
                    'signingUrl' => '/edm/documents/signing-index?tabMode=tabCDISigning',
                    'config' => $substituteConfig
                ],
                ContractRegistrationRequestSearch::class => [
                    'documentTypeName' => Yii::t('edm', 'Contract requests'),
                    'signingUrl' => '/edm/documents/signing-index?tabMode=tabCRRSigning',
                    'config' => $substituteConfig
                ],
                BankLetterSearch::class => [
                    'documentTypeName' => Yii::t('edm', 'Letters to bank'),
                    'signingUrl' => '/edm/documents/signing-index?tabMode=tabBankLetters',
                    'config' => $substituteConfig
                ],
                StatementRequestSearch::class => [
                    'documentTypeName' => Yii::t('edm', 'Statement requests'),
                    'signingUrl' => '/edm/documents/signing-index?tabMode=tabStatementRequests',
                ],
                VTBCancellationRequestSearch::class => [
                    'documentTypeName' => Yii::t('edm', 'Cancellation requests'),
                    'signingUrl' => '/edm/documents/signing-index?tabMode=tabVTBCancellationRequests',
                ],
            ];

            foreach ($groups as $searchModelClassName => $group) {
                $config = isset($group['config']) ? $group['config'] : [];
                $searchModel = new $searchModelClassName($config);
                if (!method_exists($searchModel, 'countForSigning')) {
                    continue;
                }
                $countForSigning = $searchModel->countForSigning();
                if ($countForSigning > 0) {
                    $allForSinging += $countForSigning;
                    $docCountList[$group['documentTypeName'] . ': ' . $countForSigning] = $group['signingUrl'];
                }
            }
        }

        if ($allForSinging) {
            // Вывести страницу
            return $this->render('forSigning', [
                'countNotify' => $allForSinging,
                'docList' => $docCountList
            ]);
        } else {
            return null;
        }

    }

    private function renderNew()
    {
        $docList = [];

        $unreadFinZipCount = FinZipSearch::getUnreadCount();
        if ($unreadFinZipCount) {
            $docList['FinZip: ' . $unreadFinZipCount] = '/finzip/default';
        }

        $unreadStatementsCount = StatementSearch::getUnreadCount();
        if ($unreadStatementsCount) {
            $docList[Yii::t('edm', 'Statements') . ': ' . $unreadStatementsCount] = '/edm/documents/statement';
        }

        $unreadBankLettersCount = BankLetterSearch::getUnreadCount();
        if ($unreadBankLettersCount) {
            $docList[Yii::t('edm', 'Letters from bank') . ': ' . $unreadBankLettersCount] = '/edm/bank-letter';
        }

        // Общее количество новых документов
        $totalUnreadCount = $unreadFinZipCount + $unreadStatementsCount + $unreadBankLettersCount;

        if ($totalUnreadCount > 0) {
            // Вывести страницу
            return $this->render('new', [
                'countNotify' => $totalUnreadCount,
                'docList' => $docList
            ]);
        } else {
            return null;
        }
    }

    private function renderStoppedTerminals()
    {
        $stoppedTerminals = [];

        if ($this->type == self::TYPE_STOPPED_TERMINALS_USER) {
            // Выключенные терминалы, доступные пользователю
            $userTerminals = UserTerminal::getUserTerminals(Yii::$app->user->identity->id);
            $terminalData = Yii::$app->exchange->terminalData;

            foreach($userTerminals as $terminalId => $terminal) {
                $data = $terminalData[$terminalId];
                if ($data['isRunning'] == false) {
                    $stoppedTerminals[] = $terminalId;
                }
            }

            $isAdmin = false;

        } else if ($this->type == self::TYPE_STOPPED_TERMINALS_ADMIN) {
            // Получить модель пользователя из активной сессии
            $adminIdentity = Yii::$app->user->identity;
            // Все выключенные терминалы
            $terminals = UserTerminal::getUserTerminalIds($adminIdentity->id);
            foreach(Yii::$app->exchange->terminalData as $terminalId => $data) {
                // Для доп. админа отображаем информацию только о доступных ему терминалах
                if ($adminIdentity->role == User::ROLE_ADDITIONAL_ADMIN
                        && !in_array($terminalId, $terminals)
                ) {
                    continue;
                }

                if ($data['isRunning'] == false) {
                    $stoppedTerminals[] = $terminalId;
                }
            }

            $isAdmin = true;
        }

        if ($stoppedTerminals) {
            // Вывести страницу остановленных терминалов
            return $this->render('stoppedTerminals', [
                'countNotify' => count($stoppedTerminals),
                'stoppedTerminals' => $stoppedTerminals,
                'isAdmin' => $isAdmin
            ]);
        } else {
            return null;
        }
    }
}
