<?php

use addons\edm\models\DictOrganization;
use addons\edm\models\EdmPayerAccount;
use addons\edm\models\PaymentRegister\PaymentRegisterAR;
use addons\edm\models\PaymentRegister\PaymentRegisterDocumentExt;
use addons\edm\models\PaymentRegister\PaymentRegisterPaymentOrder;
use addons\edm\models\PaymentRegister\PaymentRegisterType;
use addons\edm\models\PaymentRegister\PaymentRegisterWizardForm;
use common\document\Document;
use common\helpers\DocumentHelper;
use common\models\Terminal;
use common\modules\transport\helpers\DocumentTransportHelper;
use yii\db\Migration;

class m171220_095744_convert_payment_registers extends Migration
{
    public function up()
    {
        $paymentRegisters = PaymentRegisterAR::find()->all();

        PaymentRegisterPaymentOrder::updateAll(['updated' => 0]);

        foreach($paymentRegisters as $register) {

            try {

                $typeModel = new PaymentRegisterType();
                $storedFile = Yii::$app->storage->get($register->bodyStorageId);
                if (!$storedFile) {
                    echo 'stored file not found for PaymentRegisterAR id ' . $register->id . "\n";
                    continue;
                }

                $typeModel->loadFromString($storedFile->getData());

                $extModel = PaymentRegisterDocumentExt::findOne([
                    'registerId' => $register->id
                ]);

                /**
                 * Если у АР есть экстмодель, значит создан документ. Если создан документ, то весь реестр
                 * лежит в cyberxml-формате. В этом случае нужно перенести доп. поля из АР в экстмодель
                 * и удалить АР.
                 */
                if ($extModel) {

                    echo 'extmodel document id: ' . $extModel->documentId . "\n";

                    $extModel->loadContentModel($typeModel);
                    $extModel->businessStatus = $register->businessStatus;
                    $extModel->businessStatusComment = $register->businessStatusComment;
                    $extModel->statusComment = $register->statusComment;
                    $extModel->businessStatusDescription = $register->businessStatusDescription;
                    $extModel->currency = $register->currency;

                    // Если модель успешно сохранена в БД
                    if ($extModel->save()) {
                        echo 'Updated extmodel for register ' . $register->id . ' (document ' . $extModel->documentId . ")\n";

                        $document = Document::findOne($extModel->documentId);

                        if ($document) {
                            $document->signaturesRequired = $register->signaturesRequired;
                            $document->signaturesCount = $register->signaturesCount;
                            $document->save(false, ['signaturesRequired', 'signaturesCount']);
                        }

                        PaymentRegisterPaymentOrder::updateAll(
                            [
                                'registerId' => $extModel->documentId,
                                'updated' => 1
                            ],
                            [
                                'registerId' => $register->id,
                                'updated' => 0
                            ]
                        );
                    } else {
                        echo 'Error: failed to update extmodel for register ' . $register->id . "\n";
                    }
                } else {
                    /**
                     * Если у АР нет экстмодели, то документ еще не создан и перед нами чистый АР. Нужно создать
                     * документ и экстмодель стандартным способом через EdmHelper и удалить АР.
                     */
                    $orders = PaymentRegisterPaymentOrder::findAll([
                       'registerId' => $register->id
                    ]);

                    if (!count($orders)) {
                        //echo 'register ' . $register->id . " does not have orders\n";
                        continue;
                    }

                    $form = new PaymentRegisterWizardForm();
                    $form->addPaymentOrders($orders);

                    // Поддержка отката назад: через $form передаем бывший id PaymentRegisterAR,
                    // чтобы он попал в extModel
                    $form->docId = $register->id;

                    echo "Ready to create document! Account: {$form->account}\n";

                    $document = $this->createPaymentRegister($form, $typeModel, [
                        'origin' => Document::ORIGIN_FILE,
                        'status' => $register->status,
                        'dateCreate' => $register->dateCreate,
                        'signaturesRequired' => $register->signaturesRequired,
                        'signaturesCount' => $register->signaturesCount,
                    ]);

                    echo 'created document ' . $document->id . "\n";

                    // Тупо меняем все чувствительные данные назад, что бы там ни произошло при создании
                    $document->status = $register->status;
                    $document->dateCreate = $register->dateCreate;
                    $document->dateUpdate = $register->dateUpdate;
                    $document->signaturesRequired = $register->signaturesRequired;
                    $document->signaturesCount = $register->signaturesCount;

                    $document->save(false, [
                        'status', 'dateCreate', 'dateUpdate',
                        'signaturesRequired', 'signaturesCount'
                    ]);

                    // Отправить документ на обработку в транспортном уровне
                    DocumentTransportHelper::processDocument($document);

                    // Необходимо у PaymentRegisterPaymentOrder поменять registerId на полученный document->id
                    PaymentRegisterPaymentOrder::updateAll(
                        [
                            'registerId' => $document->id,
                            'updated' => 1
                        ],
                        [
                            'registerId' => $register->id,
                            'updated' => 0
                        ]
                    );

                    $register->documentId = $document->id;
                    $register->save(false, ['documentId']);
                }
            } catch(Exception $ex) {
                echo 'Error with register id ' . $register->id . "\n";
                echo $ex->getMessage() . "\n";

                continue;
            }
        }
    }

    public function down()
    {
        $paymentRegisters = PaymentRegisterAR::find()->all();

        foreach($paymentRegisters as $register) {

            PaymentRegisterPaymentOrder::updateAll(
                [
                    'registerId' => $register->id,
                    'updated' => 0
                ],
                [
                    'registerId' => $register->documentId,
                    'updated' => 1
                ]
            );

            Document::deleteAll(['id' => $register->documentId]);

            $register->documentId = null;
            $register->save(false, ['documentId']);
        }

        return true;
    }

    public function createPaymentRegister($form, $typeModel, $docAttributes = [])
    {
        $account = EdmPayerAccount::findOne(['number' => $form->account]);

        if (!$account) {
            throw new Exception(Yii::t('edm', 'Account ' . $form->account . ' not found'));
        }

        $terminalId = Terminal::getIdByAddress($form->sender);
        if (!$terminalId) {
            throw new Exception('Terminal not found for sender ' . $form->sender);
        }

        if (isset($account->bank) && !empty($account->bank->terminalId)) {
            $typeModel->recipient = $account->bank->terminalId;
        } else {
            throw new Exception('Recipient not determined for account ' . $account->number);
        }

        $docAttributes['sender'] = $form->sender;
        $docAttributes['receiver'] = $typeModel->recipient;
        $docAttributes['type'] = $typeModel->getType();
        $docAttributes['direction'] = Document::DIRECTION_OUT;
        $docAttributes['terminalId'] = $terminalId;

        $org = DictOrganization::findOne(['id' => $account->organizationId]);
        if (!$org) {
            throw new Exception('Organization not found for account ' . $account->number);
        }

        $extAttributes = [
            'sum' => $typeModel->sum,
            'count' => $typeModel->count,
            'currency' => $typeModel->currency ?: 'RUB',
            'accountId' => $account->id,
            'accountNumber' => $typeModel->getAccountNumber(),
            'orgId' => $org->id, //$typeModel->getPayerName(),
            'date' => date('Y-m-d', strtotime($typeModel->date)),
            // Поддержка отката назад: в registerId хранится бывший id PaymentRegisterAR,
            // который нужно передать сюда через $form
            'registerId' => $form->docId
        ];

        // Проверка количества необходимых подписей
        if ($account && $account->requireSignQty) {
            // Из персональных настроек счета
            $docAttributes['status'] = Document::STATUS_FORSIGNING;
            $docAttributes['signaturesRequired'] = $account->requireSignQty;
        }

        // Создать контекст документа
        $context = DocumentHelper::createDocumentContext($typeModel, $docAttributes, $extAttributes);

        if (!$context) {
            throw new Exception(Yii::t('app', 'Save document error'));
        }

        // Получить документ из контекста
        $document = $context['document'];
        $form->docId = $document->id;

        return $document;
    }

}
