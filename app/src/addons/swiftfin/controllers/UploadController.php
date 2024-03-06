<?php

namespace addons\swiftfin\controllers;

use addons\swiftfin\helpers\SwiftfinHelper;
use addons\swiftfin\models\form\WizardForm;
use addons\swiftfin\models\SwiftFinType;
use common\base\BaseServiceController;
use common\document\DocumentPermission;
use Exception;
use Yii;
use yii\filters\AccessControl;
use yii\web\UploadedFile;
use addons\swiftfin\SwiftfinModule;

class UploadController extends BaseServiceController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => [DocumentPermission::CREATE],
                        'roleParams' => ['serviceId' => SwiftfinModule::SERVICE_ID],
                    ],
                ],
            ],
        ];
    }

    /**
     * Index action
     *
     * @return integer
     */
    public function actionIndex()
    {
        $error = '';
        // Если отправлены POST-данные
        if (Yii::$app->request->isPost) {
            $file = UploadedFile::getInstanceByName('documentfile');

            $model = $this->processUploadFile($file);

            if ($model !== false) {
                $cacheKey = 'swiftfin/wizard/doc-'.\Yii::$app->session->id;
                \Yii::$app->cache->set($cacheKey, $model);
                // Перенаправить на страницу 3-го шага визарда
                return $this->redirect(['/swiftfin/wizard/step3']);
            }
            // Поместить в сессию флаг сообщения об ошибке обработки документа
            \Yii::$app->session->addFlash('error',
                Yii::t('app/error', 'Error occurred while processing document'));
        }

        // Вывести страницу индекса
        return $this->render('index', compact('error'));
    }

    /**
     * Upload file process
     *
     * @param UploadedFile $file Uploaded file
     * @return SwiftFinType|boolean Return SwiftFinType class instance or false
     * @throws Exception
     */
    protected function processUploadFile(UploadedFile $file)
    {
        try {
            if (!$model = SwiftFinType::createFromFile($file->tempName)) {
                throw new Exception(Yii::t('app/error', 'Create type model from file error'));
            }

            if (SwiftfinHelper::FILE_FORMAT_SWIFT !== $model->sourceFormat) {
                // Поместить в сессию флаг сообщения о неподдерживаемом формате
                Yii::$app->session->addFlash('info', Yii::t('app/error', 'Unsupported data format'));
                throw new Exception(Yii::t('app/error', 'Unsupported data format'));
            }

            if (!$model->validateSender()) {
                // Поместить в сессию флаг сообщения о неправильном отправителе
                Yii::$app->session->addFlash('info', Yii::t('app/error', 'Incorrect sender'));
                throw new Exception(Yii::t('app/error', 'Incorrect sender'));
            }

            if (!$model->validateRecipient()) {
                // Поместить в сессию флаг сообщения об неправильном получателе
                Yii::$app->session->addFlash('info', Yii::t('app/error', 'Incorrect recipient'));
                throw new Exception(Yii::t('app/error', 'Incorrect recipient'));
            }

            $wizard = new WizardForm();
            $wizard->recipient = $model->recipient;
            $wizard->sender = $model->sender;
            $wizard->contentType = $model->contentType;
            $wizard->setContent($model->source->content);

            return $wizard;
        } catch (Exception $ex) {
            Yii::warning($ex->getMessage());
            return false;
        }
    }
}
