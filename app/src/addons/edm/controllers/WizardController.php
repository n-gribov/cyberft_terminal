<?php

namespace addons\edm\controllers;

use addons\edm\EdmModule;
use addons\edm\helpers\EdmHelper;
use addons\edm\models\DictOrganization;
use addons\edm\models\DictPaymentPurpose;
use addons\edm\models\EdmDocumentTypeGroup;
use addons\edm\models\EdmPayerAccount;
use addons\edm\models\PaymentOrder\PaymentOrderType;
use addons\edm\models\PaymentRegister\PaymentRegisterPaymentOrder;
use addons\edm\models\PaymentRegister\PaymentRegisterPaymentOrderTemplate;
use backend\controllers\helpers\TerminalCodes;
use common\base\BaseServiceController;
use common\document\Document;
use common\document\DocumentPermission;
use common\helpers\DocumentHelper;
use common\helpers\sbbol\SBBOLHelper;
use common\helpers\UserHelper;
use common\helpers\WizardCacheHelper;
use common\models\cyberxml\CyberXmlDocument;
use Exception;
use InvalidArgumentException;
use kartik\widgets\ActiveForm;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * @property EdmModule $module
 * Class WizardController
 * @package addons\edm\controllers
 */
class WizardController extends BaseServiceController {

    use TerminalCodes;

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    $this->traitBehaviorsRules,
                        [
                        'allow' => true,
                        'roles' => [DocumentPermission::CREATE],
                        'roleParams' => [
                            'serviceId' => EdmModule::SERVICE_ID,
                            'documentTypeGroup' => EdmDocumentTypeGroup::RUBLE_PAYMENT,
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Метод обрабатывает страницу индекса
     */
    public function actionIndex()
    {
        $this->clearCachedDocumentId();

        // Удалить возможный кэш шаблонов платежных поручений
        if (Yii::$app->cache->exists('edm/template-' . Yii::$app->session->id)) {
            Yii::$app->cache->delete('edm/template-' . Yii::$app->session->id);
        }

        // Вывести страницу индекса
        return $this->render('index', [
            'types' => $this->module->getDocTypes(),
            'currentStep' => 2,
            'documentId' => $this->getCachedDocumentId(),
        ]);
    }

    /**
     * @return string|Response
     */
    public function actionStep2Upload()
    {
        if (!Yii::$app->request->getIsPost()) {
            // Перенаправить на страницу 2-го шага визарда
            return $this->redirect('step2');
        }

        $errors = [];
        $model  = $this->getCachedModel();
        $file   = UploadedFile::getInstanceByName('payment-order-file');

        if (!$file) {
            $errors['file'] = Yii::t('yii', '{attribute} cannot be blank.', ['attribute' => Yii::t('app', 'File')]);
        } else {
            try {
                $body = file_get_contents($file->tempName);
                $body = iconv('cp1251', 'UTF-8', $body);
                $model->loadFromString($body);

                // Проверяем доступность указанного счета плательщика текущему пользователю
                // Получаем организации, доступные текущему пользователю
                $queryOrganizations = Yii::$app->terminalAccess->query(DictOrganization::className());
                $queryOrganizations->select('id')->asArray();
                $organizations = $queryOrganizations->all();

                // Получение счетов по доступным организациям
                $query = EdmPayerAccount::find();

                $account = $model->payerCheckingAccount;
                $query->where([
                    'organizationId' => ArrayHelper::getColumn($organizations, 'id'),
                    'number' => $account
                ]);

                $payerAccount = $query->all();

                // Если такого счета не найдено, формируем ошибку
                if (!$payerAccount) {
                    $model = $this->getCachedModel();
                    if ($account) {
                        throw new Exception(
                            Yii::t('edm', 'Account {account} is not found in contractors dictionary',
                                    ['account' => $account]
                            )
                        );
                    } else {
                        throw new Exception(Yii::t('edm', 'Can\'t find payer account on loaded file'));
                    }
                }

                // Check model on errors
                if ($model->hasErrors()) {
                    $model = $this->getCachedModel();
                    throw new Exception();
                }
            } catch (\Exception $ex) {
                // Возвращаем конкретное сообщение, если оно было в исключении
                if ($ex->getMessage()) {
                    $errors['file'] = $ex->getMessage();
                } else {
                    $errors['file'] = Yii::t('app', 'Broken file');
                }
            }
        }

        // Если заполнен массив с ошибками, выводим их
        if (isset($errors['file'])) {
            // Поместить в сессию флаг сообщения об ошибках
            Yii::$app->session->setFlash('error', $errors['file']);
        }

        // Вывести страницу
        return $this->render('index', [
            'model' => $model,
            'currentStep' => 2,
            'documentId' => $this->getCachedDocumentId(),
        ]);
    }

    public function actionValidateAjax()
    {
        // Ajax-валидация данных формы
        $model = $this->getCachedModel();
        // Загрузить данные модели из формы в браузере
        $model->load(Yii::$app->request->post());
        // Включить формат вывода JSON
        Yii::$app->response->format = Response::FORMAT_JSON;
        $result = ActiveForm::validate($model);

        return $result;
    }

    /**
     * @return array|string|Response
     */
    public function actionStep2()
    {
        /** @var Document | Document1C $model */
        $type = Yii::$app->request->get('type');

        if (Yii::$app->request->isGet && $type) {
            // Очистка кэша создания документа и редирект
            if (Yii::$app->request->get('clearWizardCache')) {
                WizardCacheHelper::deletePaymentOrderWizardCache();
                // Перенаправить на страницу
                return $this->redirect('/edm/wizard/step2?type=PaymentOrder');
            }

            $id = (int) Yii::$app->request->get('id');
            $fromId = (int) Yii::$app->request->get('fromId');
            $model = $this->getModelByType($type);

            if (!empty($id)) {
                // Если указан id модели, загрузить
                $storedModel = $this->getStorageModelByType($type, $id);
                if ($storedModel && $storedModel->id) {
                    $this->cacheDocumentId($storedModel->id);
                    $model->loadFromString($storedModel->body);
                    $model->unsetParticipantsNames();
                }
            } else if (!empty($fromId)) {
                // Создание на основе уже имеющегося документа
                $storedModel = $this->getStorageModelByType($type, $fromId);
                if ($storedModel && $storedModel->id) {
                    $model->loadFromString($storedModel->body);
                    $model->unsetParticipantsNames();
                    $model->dateCreated = date('d.m.Y');
                    $model->timeCreated = date('H:i:s');
                    $model->date = date('d.m.Y');
                    $model->number = $this->getNumberForModel($model);
                }
            } else {
                // Проверка на случай создания документа из шаблона
                $referrer = Yii::$app->request->referrer;
                $templateKey = 'edm/template-' . Yii::$app->session->id;
                if (
                    strpos($referrer, 'payment-order-templates') !== false
                    || strpos($referrer, 'profile/dashboard') !== false
                    || Yii::$app->session->getFlash('preserveTemplateCache')
                ) {
                    // Если документ создается из шаблона или из шаблона со стартовой страницы,
                    // то загрузить кэш содержимого шаблона
                    if (Yii::$app->cache->exists($templateKey)) {
                        // Удалить кеш визарда
                        WizardCacheHelper::deletePaymentOrderWizardCache();
                        $model = Yii::$app->cache->get($templateKey);
                    }
                } else {
                    // Иначе очистить кэш шаблона, если он существует
                    if (Yii::$app->cache->exists($templateKey)) {
                        Yii::$app->cache->delete($templateKey);
                    }
                }

                // Проверка на кэш после перезагрузки страницы
                if ($cacheData = WizardCacheHelper::getPaymentOrderWizardCache()) {
                    $model = $cacheData;
                }
                
                // Проверяем, есть ли номер документа в кэше
                // если есть, подставляем его
                if (Yii::$app->cache->exists('edm/payment-order-number' . Yii::$app->session->id)) {
                    $number = Yii::$app->cache->get('edm/payment-order-number' . Yii::$app->session->id);
                } else {
                    // В противном случае делаем инкремент и кэшируем его
                    $number = $model->counterIncrement()->getCounter();
                    Yii::$app->cache->set('edm/payment-order-number' . Yii::$app->session->id, $number);
                }
                $model->number = $number;
            }
            $this->cacheModel($model);
        } else if (Yii::$app->request->isPost && ($model = $this->getCachedModel())) {
            // если в массиве были данные для мапинга и все валидно идем дальше

            // Записываем основной терминал пользователя
            $terminalId = Yii::$app->exchange->getPrimaryTerminal()->id;

            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                if (!DictPaymentPurpose::find()->where([
                        'and',
                        ['value' => $model->getRawPaymentPurpose()],
                        ['terminalId' => $terminalId]
                    ])->exists()
                ) {
                    $paymentPurpose = new DictPaymentPurpose([
                        'value' => $model->getRawPaymentPurpose(),
                        'terminalId' => $terminalId
                    ]);
                    // Сохранить модель в БД
                    $paymentPurpose->save();
                }

                $documentId = $this->getCachedDocumentId();

                if (PaymentOrderType::TYPE == $model->type) {
                    if (!$documentId && EdmHelper::checkPaymentOrderDuplicate($model) !== false) {
                        // Поместить в сессию флаг сообщения об ошибке создания документа
                        Yii::$app->session->setFlash(
                            'error',
                            Yii::t('edm', 'Failed to create document {num} - number was used before', ['num' => $model->number])
                        );

                        // Вывести страницу
                        return $this->render('index', [
                            'model' => $model,
                            'currentStep' => 2,
                            'documentId' => $this->getCachedDocumentId(),
                        ]);
                    }

                    $document = $this->storePaymentOrder($model, $documentId);
                } else if (!empty($documentId)) {
                    $document = $this->editDocument($model, $documentId);
                } else {
                    $document = $this->createDocument($model);
                }

                if ($document !== false && !$document->hasErrors()) {
                    $this->clearCachedModel();
                    $this->clearCachedDocumentId();

                    // Зарегистрировать событие создания документа в модуле мониторинга
                    Yii::$app->monitoring->log(
                        'user:createDocument', 'PaymentRegisterPaymentOrder', $document->id, [
                            'userId' => Yii::$app->user->id,
                            'docType' => 'edmPaymentOrder',
                            'initiatorType' => UserHelper::getEventInitiatorType(Yii::$app->user)
                        ]
                    );

                    // Удалить кэш текущего номера документа
                    if (Yii::$app->cache->exists('edm/payment-order-number' . Yii::$app->session->id)) {
                        Yii::$app->cache->delete('edm/payment-order-number' . Yii::$app->session->id);
                    }

                    // Перенаправить на страницу просмотра
                    return $this->redirect([
                        '/edm/payment-register/payment-order-view',
                        'id' => $document->id,
                        'from' => 'wizard'
                    ]);
                } else {
                    // Поместить в сессию флаг сообщения о неправильных данных визарда
                    Yii::$app->session->setFlash('error', Yii::t('doc', 'Invalid wizard data'));
                }
            } else {
                $errorMsg = Yii::t('doc', 'Invalid wizard data') . ':';
                $errorMsg .= '<ul>';
                foreach ($model->errors as $field => $errors) {
                    $errorMsg .= sprintf(
                        '<li>%s: %s</li>', $model->getAttributeLabel($field), implode(', ', $errors)
                    );
                }
                $errorMsg .= '</ul>';
                // Поместить в сессию флаг сообщения об ошибках
                Yii::$app->session->setFlash('error', $errorMsg);
            }
        } else if (!($model = $this->getCachedModel())) {
            // Перенаправить на страницу индекса
            return $this->redirect('index');
        }

        // Вывести страницу
        return $this->render('index', [
            'model' => $model,
            'currentStep' => 2,
            'documentId' => $this->getCachedDocumentId(),
        ]);
    }

    /**
     * Edit action
     *
     * @param integer $id Document ID
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionEdit($id)
    {
        // Получить из БД документ с указанным id через компонент авторизации доступа к терминалам
        $document = Yii::$app->terminalAccess->findModel(Document::className(), $id);
        $model = CyberXmlDocument::getTypeModel($document->getValidStoredFileId());
        if ($model === false) {
            Yii::warning("Get type model for document ID[{$document->id}] in storage ID[{$document->getValidStoredFileId()}] error!");

            throw new NotFoundHttpException();
        }

        $this->cacheDocumentId($document->id);
        $this->cacheModel($model);

        // Перенаправить на страницу 2-го шага визарда
        return $this->redirect(['step2']);
    }

    protected function storePaymentOrder(PaymentOrderType $typeModel, $documentId)
    {
        try {
            if (!empty($documentId)) {
                $model = $this->getStorageModelByType($typeModel->getType(), $documentId);
                if (empty($model)) {
                    throw new InvalidArgumentException('PaymentOrder with id ' . $documentId . ' is not available');
                }
            } else {
                $model = $this->getStorageModelByType($typeModel->getType());
            }

            $typeModel->setParticipantsNames();

            $model->loadFromTypeModel($typeModel);
            $model->terminalId = $typeModel->terminalId;

            // Если модель успешно сохранена в БД
            if ($model->save()) {
                if ($documentId) {
                    // Поместить в сессию флаг сообщения об успешном сохранении документа
                    Yii::$app->session->setFlash('success', Yii::t('edm', 'Payment order updated'));
                } else {
                    // Поместить в сессию флаг сообщения об успешном создании документа
                    Yii::$app->session->setFlash('success', Yii::t('edm', 'Payment order saved with id {id}', ['id' => $model->id]));
                }

                // Сохраняем шаблон, если это необходимо
                if ($typeModel->setTemplate) {
                    // Создаем новый объект, который будет копией только что созданного
                    $template = new PaymentRegisterPaymentOrderTemplate();
                    // Не все атрибуты модели нужны в шаблоне
                    $template->setAttributes(array_diff_key(
                        $model->attributes,
                        [
                            'registerId' => true, 'status' => true, 'businessStatus' => true,
                            'businessStatusDescription' => true, 'businessStatusComment' => true
                        ]
                    ));

                    $template->name = $typeModel->setTemplateName;
                    // Сохранить модель в БД
                    $template->save();
                }

                //  Удаляем кэш формы
                WizardCacheHelper::deletePaymentOrderWizardCache();

                return $model;
            }
        } catch (Exception $ex) {
            // Поместить в сессию флаг сообщения об ошибке
            Yii::$app->session->setFlash('error', $ex->getMessage());
        }

        return false;
    }

    protected function createPaymentOrderRecord($model, $documentId)
    {
        $paymentOrder = $this->storePaymentOrder($model, $documentId);

        // Формирование платежного поручения в формате сбербанка
        if (SBBOLHelper::isGatewayTerminal($model->recipient)) {
            try {
                $document = EdmHelper::createAndProcessSBBOLPayDocRuDocument($model);
                $paymentOrder->registerId = $document->id;
                // Сохранить модель в БД
                $paymentOrder->save();
            } catch(Exception $e) {
                // Поместить в сессию флаг сообщения об ошибке создания документа
                Yii::$app->session->setFlash('error', 'Ошибка создания платежного поручения в формате Сбербанка');
                Yii::error($e->getMessage());
            }
        }

        return $paymentOrder;
    }

    /**
     * Create document
     *
     * @param PaymentOrderType $typeModel
     * @return Document|boolean
     * @throws Exception
     */
    protected function createDocument($typeModel)
    {
        try {
            $terminalId = $typeModel->terminalId;

            $document = DocumentHelper::reserveDocument(
                $typeModel->getType(), Document::DIRECTION_OUT, Document::ORIGIN_WEB, $terminalId
            );

            if ($document) {
                DocumentHelper::createCyberXml(
                    $document, $typeModel, [
                        'sender' => $typeModel->sender,
                        'receiver' => $typeModel->getRecipient(),
                    ]
                );

                return $document;
            } else {
                throw new Exception(yii::t('app', 'Save document error'));
            }
        } catch (Exception $ex) {
            // Поместить в сессию флаг сообщения об ошибке
            Yii::$app->session->setFlash('error', $ex->getMessage());

            return false;
        }
    }

    /**
     * Edit document
     *
     * @param Document $model Document model
     * @param integer $documentId Document ID
     * @return Document|boolean
     * @throws Exception
     * @throws NotFoundHttpException
     */
    public function editDocument($model, $documentId)
    {
        try {
            // Получить из БД документ с указанным id через компонент авторизации доступа к терминалам
            $document = Yii::$app->terminalAccess->findModel(Document::className(), $documentId);

            $params = [
                'code' => 'DocumentEdit',
                'entity' => 'document',
                'entityId' => $document->id,
                'typeModel' => serialize($model),
            ];

            $result = Yii::$app->commandBus->addCommand(Yii::$app->user->id, 'DocumentEdit', $params);

            if (!$result) {
                throw new Exception(Yii::t('doc', 'Add command error'));
            }

            return $document;
        } catch (Exception $ex) {
            // Поместить в сессию флаг сообщения об ошибке
            Yii::$app->session->setFlash('error', $ex->getMessage());

            return false;
        }
    }

    /**
     * Получение списка элементов назначений платежа
     * @param null $q
     * @return mixed
     */
    public function actionPaymentPurposeList($q = null)
    {
        // Включить формат вывода JSON
        Yii::$app->response->format = Response::FORMAT_JSON;
        /** @var DictPaymentPurpose[] $items */
        $query = Yii::$app->terminalAccess->query(DictPaymentPurpose::className());

        $query->select('value as id, value as text')->limit(20);

        if (!is_null($q)) {
            $query->andWhere(['like', 'value', $q]);
        }

        $out['results'] = $query->createCommand()->queryAll();

        return $out;
    }

    /**
     * @param $type
     * @return Document
     */
    protected function getModelByType($type)
    {
        $class = yii::$app->registry->getTypeModelClass($type);

        return new $class();
    }

    protected function getStorageModelByType($type, $id = null)
    {
        if ($type == PaymentOrderType::TYPE) {
            if ($id) {
                return PaymentRegisterPaymentOrder::findOne([
                    'and',
                    ['id' => $id],
                    ['!=', 'status', Document::STATUS_DELETED],
                    ['registerId' => null]
                ]);
            } else {
                return new PaymentRegisterPaymentOrder();
            }
        }

        return null;
    }

    /**
     * @param Document $model
     */
    protected function cacheModel($model)
    {
        Yii::$app->cache->set('edm/wizard/doc-' . Yii::$app->session->id, $model);
    }

    /**
     * @return Document
     */
    protected function getCachedModel()
    {
        return Yii::$app->cache->get('edm/wizard/doc-' . Yii::$app->session->id);
    }

    protected function clearCachedModel()
    {
        // Удалить кеш визарда
        Yii::$app->cache->delete('edm/wizard/doc-' . Yii::$app->session->id);
    }

    protected function cacheCheckingAccount($value)
    {
        Yii::$app->cache->set('edm/wizard/checkingAccount-', $value);
    }

    protected function getCachedCheckingAccount()
    {
        return Yii::$app->cache->get('edm/wizard/checkingAccount-');
    }

    /**
     * Save to cache document ID
     *
     * @param integer $documentId EDM document ID
     */
    protected function cacheDocumentId($documentId)
    {
        Yii::$app->cache->set('edm/wizard/edit-' . Yii::$app->session->id, $documentId);
    }

    /**
     * Get edit document ID from cache
     *
     * @return integer
     */
    protected function getCachedDocumentId()
    {
        return Yii::$app->cache->get('edm/wizard/edit-' . Yii::$app->session->id);
    }

    /**
     * Clear edit document cache
     */
    protected function clearCachedDocumentId()
    {
        // Удалить кеш визарда
        Yii::$app->cache->delete('edm/wizard/edit-' . Yii::$app->session->id);
    }

    protected function getNumberForModel($model)
    {
        // Проверяем, есть ли номер документа в кэше
        // если есть подставляем его
        if (Yii::$app->cache->exists('edm/payment-order-number' . Yii::$app->session->id)) {
            $number = Yii::$app->cache->get('edm/payment-order-number' . Yii::$app->session->id);
        } else {
            // В противном случае делаем инкремент и кэшируем его
            $number = $model->counterIncrement()->getCounter();
            Yii::$app->cache->set('edm/payment-order-number' . Yii::$app->session->id, $number);
        }

        return $number;
    }
}
