<?php

namespace addons\edm\controllers;

use addons\edm\models\EdmPayerAccountUser;
use addons\edm\models\EdmTemplateSearch;
use addons\edm\models\ForeignCurrencyOperation\ForeignCurrencyPaymentTemplate;
use addons\edm\models\PaymentOrder\PaymentOrderType;
use addons\edm\models\PaymentRegister\PaymentRegisterPaymentOrderTemplate;
use common\base\BaseServiceController;
use common\helpers\UserHelper;
use common\models\User;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\ForbiddenHttpException;
use yii\web\MethodNotAllowedHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class PaymentOrderTemplatesController extends BaseServiceController
{
    /**
     * Удаление шаблона доступно только через POST-запрос
     * @return array
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }
    
    /**
     * Метод обрабатывает страницу индекса
     * с журналом шаблонов платежных поручений
     */
    public function actionIndex()
    {
        $searchModel = new EdmTemplateSearch();

        $queryParams = Yii::$app->request->queryParams;

        // Вывести страницу
        return $this->render('index', [
            'model' => $searchModel,
            'queryParams' => $queryParams,
            'dataProvider' => $searchModel->search($queryParams),
            'listType' => 'edmTemplates',
        ]);
    }

    /**
     * Удаление шаблона платежного поручения
     * @param $id
     * @throws NotFoundHttpException
     */
    public function actionPaymentOrderDelete($id)
    {
        $this->redirectPaymentOrderJournal();

        // Получить из БД шаблон ПП с указанным id
        $template = $this->findModel($id);

        if (!$this->allowUserAccount($template->payerAccount)) {
            throw new ForbiddenHttpException;
        }

        // Удалить шаблон из БД
        $template->delete();

        // Перенаправить на страницу индекса
        return $this->redirect('index');
    }

    /**
     * Удаление шаблона валютного платежа
     * @param $id
     * @throws NotFoundHttpException
     */
    public function actionFcpDelete($id)
    {
        $this->redirectPaymentOrderJournal();

        $template = $this->findFcpModel($id);

        if (!$this->allowUserAccount($template->payerAccount)) {
            throw new ForbiddenHttpException;
        }

        // Удалить шаблон из БД
        $template->delete();

        // Перенаправить на страницу индекса
        return $this->redirect('index');
    }

    /**
     * Действие создает документ из шаблона
     */
    public function actionCreatePaymentOrder($id, $updateOrganizationRequisites = false)
    {
        // Получить из БД документ с указанным id
        $template = $this->findModel($id);

        // Создание платежных поручений только по доступным пользователю счетам
        if (!$this->allowUserAccount($template->payerAccount)) {
            throw new ForbiddenHttpException;
        }

        if ($template->isOutdated && $updateOrganizationRequisites) {
            $isUpdated = $template->updateOrganizationRequisites();
            if ($isUpdated) {
                // Зарегистрировать событие изменения шаблона ПП в модуле мониторинга
                Yii::$app->monitoring->log(
                    'edm:updatePaymentOrderTemplateRequisites',
                    'PaymentRegisterPaymentOrder',
                    $id,
                    [
                        'userId'        => Yii::$app->user->id,
                        'templateId'    => $id,
                        'templateName'  => $template->name,
                        'initiatorType' => UserHelper::getEventInitiatorType(Yii::$app->user),
                    ]
                );
            } else {
                Yii::info("Failed to update organization requisites in payment order template $id, errors: " . var_export($template->getErrors(), true));
            }
        }

        // Сформировать type-модель из шаблона
        $typeModel = new PaymentOrderType();
        $typeModel->loadFromString($template->body);
        $typeModel->unsetParticipantsNames();

        // Задать текущую дату про создании документа шаблона
        $typeModel->date = date('d.m.Y');

        // Закешировать type-модель для дальнейшей передачи в форму визарда
        Yii::$app->cache->set('edm/template-' . Yii::$app->session->id, $typeModel);
        // Поместить в сессию флаг сохранения кеша шаблонов
        Yii::$app->session->setFlash('preserveTemplateCache', true);
        // Перенаправить на страницу 2 шага визарда
        $wizardUrl = Url::to(['/edm/wizard/step2', 'type' => 'PaymentOrder']);
        $this->redirect($wizardUrl);
    }

    /**
     * Метод ищет модель документа в БД по первичному ключу.
     * Если модель не найдена, выбрасывается исключение HTTP 404
     */
    protected function findModel($id): ?PaymentRegisterPaymentOrderTemplate
    {
        // Получить из БД документ с указанным id через компонент авторизации доступа к терминалам
        return Yii::$app->terminalAccess->findModel(PaymentRegisterPaymentOrderTemplate::className(), $id);
    }

    protected function findFcpModel($id)
    {
        // Получить из БД документ с указанным id через компонент авторизации доступа к терминалам
        return Yii::$app->terminalAccess->findModel(ForeignCurrencyPaymentTemplate::className(), $id);
    }

    /**
     * Получение формы создания нового шаблона платежки
     */
    public function actionPaymentOrderGetNewTemplateForm()
    {
        // Только ajax
        if (!Yii::$app->request->isAjax) {
            throw new NotFoundHttpException(Yii::t('app', 'This request must not be called directly'));
        }

        $id = Yii::$app->request->get('id');

        if ($id) {
            // Формируем PaymentOrderType из тела шаблона
            // Получить из БД документ с указанным id
            $template = $this->findModel($id);

            $model = new PaymentOrderType();
            $model->loadFromString($template->body);

            // Загрузка данных по НДС
            $model->vat = $template->vat;
            $model->paymentPurposeNds = $template->paymentPurposeNds;
        } else {
            // Формируем PaymentOrderType из тела шаблона
            $template = new PaymentRegisterPaymentOrderTemplate();

            $model = new PaymentOrderType();
            $model->loadFromString('');
        }

        // Получаем форму шаблона
        $html = $this->renderAjax('payment-order/_form', ['model' => $model, 'template' => $template]);

        return $html;
    }

    /**
     * Получение формы создания нового шаблона платежки
     */
    public function actionFcpGetNewTemplateForm()
    {
        // Только ajax
        if (!Yii::$app->request->isAjax) {
            throw new NotFoundHttpException(Yii::t('app', "This request must not be called directly"));
        }

        $id = Yii::$app->request->get('id');

        if ($id) {
            $model = $this->findFCPModel($id);
        } else {
            $model = new ForeignCurrencyPaymentTemplate;
        }

        // Получаем форму шаблона
        return $this->renderAjax('foreign-currency-payment/_form', ['model' => $model]);
    }

    /**
     * Сохранение данных модальной
     * формы управления шаблонами
     * @return Response
     * @throws NotFoundHttpException
     */
    public function actionPaymentOrderSaveTemplate()
    {
        if (!Yii::$app->request->isAjax) {
            throw new NotFoundHttpException(Yii::t('app', 'This request must not be called directly'));
        }

        // Если отправлены POST-данные
        if (Yii::$app->request->isPost) {

            $post = Yii::$app->request->post();

            $typeModel = new PaymentOrderType();

            if ($typeModel->load($post) && $typeModel->validate()) {
                if (isset($post['PaymentRegisterPaymentOrderTemplate']['id']) &&
                    !empty($post['PaymentRegisterPaymentOrderTemplate']['id'])) {
                    // Получить из БД документ с указанным id
                    $template = $this->findModel($post['PaymentRegisterPaymentOrderTemplate']['id']);
                } else {
                    $template = new PaymentRegisterPaymentOrderTemplate();
                }

                // Получаем имя шаблона для добавления
                $templateName = $post['PaymentRegisterPaymentOrderTemplate']['name'];

                // Получаем основной терминал пользователя
                $terminal = Yii::$app->exchange->getPrimaryTerminal();

                // Получаем модель документа, который надо изменить
                $template->loadFromTypeModel($typeModel);
                $template->terminalId = $terminal->id;
                $template->name = $templateName;

                if (strlen($typeModel->paymentPurposeNds) > 1) {
                    $template->paymentPurposeNds = $typeModel->paymentPurposeNds;
                    $template->vat = $typeModel->vat;
                    $template->paymentPurpose = $typeModel->paymentPurposeNds;
                } else {
                    $paymentPurpose = $post['PaymentOrderType']['paymentPurpose'];
                    $template->paymentPurpose = $paymentPurpose;
                    $template->vat = '';
                    $template->paymentPurposeNds = '';
                }

                // Сохранить модель в БД
                if ($template->save()) {
                    if ($post['createDocument'] == 1) {
                        // Перенаправить на страницу создания
                        return $this->redirect('/edm/payment-order-templates/create-payment-order?id=' . $template->id);
                    } else {
                        // Перенаправить на страницу индекса
                        return $this->redirect('/edm/payment-order-templates');
                    }
                } else {
                    // Поместить в сессию флаг сообщения об ошибке создания шаблона
                    Yii::$app->session->setFlash('error', Yii::t('edm', 'Creating payment order template error'));
                    // Перенаправить на страницу индекса
                    return $this->redirect(['/edm/payment-order-templates']);
                }
            }
        }
    }

    public function actionFcpSaveTemplate()
    {
        if (!Yii::$app->request->isAjax) {
            throw new NotFoundHttpException(Yii::t('app', 'This request must not be called directly'));
        }

        // Если отправлены POST-данные
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();

            if (isset($post['ForeignCurrencyPaymentTemplate']['id']) && !empty($post['ForeignCurrencyPaymentTemplate']['id'])) {
                $model = $this->findFcpModel($post['ForeignCurrencyPaymentTemplate']['id']);
            } else {
                $model = new ForeignCurrencyPaymentTemplate();

                // Получить основной терминал пользователя
                $terminal = Yii::$app->exchange->getPrimaryTerminal();
                $model->terminalId = $terminal->id;
            }

            // Если модель успешно загружена из формы в браузере
            if ($model->load($post) && $model->validate()) {
                // Если модель успешно сохранена в БД
                if ($model->save()) {
                    if ($post['createDocument'] == 1) {
                        // Перенаправить на страницу создания
                        return $this->redirect('/edm/payment-order-templates/create-fcp?id=' . $model->id);
                    } else {
                        // Поместить в сессию флаг сообщения об успешном сохранении документа
                        Yii::$app->session->setFlash('success', Yii::t('edm', 'Foreign currency payment was saved successfully'));
                        // Перенаправить на страницу индекса
                        return $this->redirect('/edm/payment-order-templates');
                    }
                } else {
                    // Поместить в сессию флаг сообщения об ошибке создания документа
                    Yii::$app->session->setFlash('error', Yii::t('edm', 'Creating foreign currency operation template error'));
                    // Перенаправить на страницу индекса
                    return $this->redirect(['/edm/payment-order-templates']);
                }
            }
        }
    }

    public function actionPaymentOrderGetTemplateView()
    {
        $id = Yii::$app->request->get('id');

        if ($id) {
            return $this->renderAjax('payment-order/view', [
                // Получить из БД документ с указанным id
                'model' => $this->findModel($id)
            ]);
        }
    }

    /**
     * @ajax
     * @return string
     */
    public function actionFcpGetTemplateView()
    {
        if (!Yii::$app->request->isAjax) {
            throw new MethodNotAllowedHttpException;
        }

        $id = Yii::$app->request->get('id');

        if ($id) {
            return $this->renderAjax('@addons/edm/views/documents/readable/foreignCurrencyPayment', [
                'model' => $this->findFcpModel($id)
            ]);
        }
    }

    public function actionGetFcpTemplatesList($q = null, $id = null)
    {
        // Включить формат вывода JSON
        Yii::$app->response->format = Response::FORMAT_JSON;

        $out = ['results' => []];

        $query = Yii::$app->terminalAccess->query(ForeignCurrencyPaymentTemplate::className());
        $query->select('id, templateName, beneficiary');

        if (!is_null($q)) {
            $query->where([
                'or',
                ['like', 'templateName', $q],
                ['like', 'beneficiary', $q]
            ]);
        }

        $query->asArray();
        $templates = $query->all();

        foreach ($templates as $i => $item) {
            $item['beneficiaryName'] = preg_split('/([\r\n]+|,)/', $item['beneficiary'])[0];
            $out['results'][$i] = $item;
        }

        return $out;
    }

    // Переадресация админа на общий журнал документов edm
    // Вместо отображения ошибки доступа
    private function redirectPaymentOrderJournal()
    {
        if (Yii::$app->user->can('admin')) {
            // Перенаправить на страницу индекса
            $this->redirect('/edm/payment-register/payment-order');
        }
    }

    /**
     * Ограничение действий пользователей с счетами согласно правам доступа
     */
    private function allowUserAccount($accountNumber)
    {
        // Получить модель пользователя из активной сессии
        $user = Yii::$app->user->identity;

        // Администраторам разрешены все действия со счетами
        if ($user->role == User::ROLE_ADMIN ||
            $user->role == User::ROLE_ADDITIONAL_ADMIN) {
            return true;
        }

        // Проверяем доступ пользователя к счету
        return EdmPayerAccountUser::isUserAllowAccountByNumber($user->id, $accountNumber);
    }
}
