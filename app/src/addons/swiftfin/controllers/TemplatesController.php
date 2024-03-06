<?php

namespace addons\swiftfin\controllers;

use addons\swiftfin\models\form\WizardForm;
use addons\swiftfin\models\SwiftfinTemplate;
use addons\swiftfin\SwiftfinModule;
use common\base\BaseServiceController;
use common\document\Document;
use common\document\DocumentPermission;
use common\models\cyberxml\CyberXmlDocument;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;

/**
 * TemplatesController implements the CRUD actions for SwiftfinTemplate model.
 */
class TemplatesController extends BaseServiceController
{
    public function behaviors()
    {
        return [
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
					[
						'allow' => true,
                        'roles' => [DocumentPermission::VIEW],
                        'roleParams' => ['serviceId' => SwiftfinModule::SERVICE_ID],
					],
				],
			],
        ];
    }

    /**
     * Lists all SwiftfinTemplate models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Yii::$app->terminalAccess->query(SwiftfinTemplate::className())
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'listType' => 'swiftTemplates',
            'model' => new SwiftfinTemplate()
        ]);
    }

    /**
     * Displays a single SwiftfinTemplate model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new SwiftfinTemplate model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SwiftfinTemplate(['terminalId' => Yii::$app->terminals->defaultTerminal->id]);

        if (Yii::$app->request->get('fromId')) {
            $importDocument = Yii::$app->terminalAccess->findModel(
                    Document::className(),
                    Yii::$app->request->get('fromId')
            );
            
            if ($importDocument !== null) {
                $model->sender = $importDocument->sender;
                $model->recipient = $importDocument->receiverParticipantId;
                $model->docType = str_replace('MT', '', $importDocument->type);
                $text = (string) CyberXmlDocument::getTypeModel($importDocument->actualStoredFileId);

                if (preg_match('#{3:{113:(.+?)}}#is', $text, $arr)) {
                    $model->bankPriority = $arr[1];
                }

                if (preg_match('#{4:(.+?)-}#is', $text, $arr)) {
                    $model->text = $arr[1];
                }

            }
        }

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                // Регистрация события создания шаблона
                Yii::$app->monitoring->extUserLog('CreateTemplateSwiftfin', ['swiftfinTemplateId' => $model->id]);

                return $this->redirect(['view', 'id' => $model->id]);
            }

            Yii::$app->session->setFlash('error', Yii::t('app/swiftfin', 'Could not save template'));
        }

        $model->sender = Yii::$app->terminals->defaultTerminal->terminalId;

        return $this->render('create', [
            'model' => $model,
            'docTypes' => $this->getSwiftTypes()
        ]);
        
    }

    /**
     * Updates an existing SwiftfinTemplate model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {

            return $this->render('update', [
                'model' => $model,
                'docTypes' => $this->getSwiftTypes()
            ]);
        }
    }

    /**
     * Deletes an existing SwiftfinTemplate model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionCreateSwiftfin($id)
    {
        $template = Yii::$app->terminalAccess->findModel(SwiftfinTemplate::className(), $id);

        // Обработка ошибки, когда данный шаблон не найден

        if (empty($template)) {
            Yii::$app->session->setFlash('error',Yii::t('doc/swiftfin','Unable to create document from template'));
            return $this->redirect(['view', 'id' => $id]);
        }

        // Кэширование данных шаблона

        $wizard = new WizardForm;
        $wizard->contentType = $template->docType;
        $wizard->sender = $template->sender;
        $wizard->recipient = $template->recipient;
        $wizard->terminalCode = $template->terminalCode;
        $wizard->bankPriority = $template->bankPriority;

        Yii::$app->cache->set('swiftfin/wizard/step1-' . Yii::$app->session->id, $wizard);
        Yii::$app->cache->set('swiftfin/template-text', $template->text);

        return $this->redirect(['/swiftfin/wizard/']);

    }

    /**
     * Finds the SwiftfinTemplate model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SwiftfinTemplate the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $model = Yii::$app->terminalAccess->findModel(
            SwiftfinTemplate::className(),
            $id
        );

        if ($model) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function getSwiftTypes()
    {
        $wizard = new WizardForm;
        return $wizard->getSupportedTypes();
    }
}
