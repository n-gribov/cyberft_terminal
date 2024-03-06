<?php
namespace addons\swiftfin\controllers;

use addons\swiftfin\events\DictionaryUploadedEvent;
use addons\swiftfin\helpers\DictBankReader;
use addons\swiftfin\models\SwiftFinDictBank;
use addons\swiftfin\models\SwiftFinDictBankSearch;
use addons\swiftfin\SwiftfinModule;
use common\base\BaseServiceController;
use common\document\DocumentPermission;
use common\helpers\ArchiveFile;
use common\helpers\FileHelper;
use common\modules\monitor\events\BaseEvent;
use InvalidArgumentException;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;
use addons\swiftfin\helpers\SwiftfinHelper;
use yii\web\MethodNotAllowedHttpException;

class DictBankController extends BaseServiceController
{
	public function behaviors() {
		return [
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'list', 'view', 'get-bank-info'],
                        'roles' => [DocumentPermission::VIEW],
                        'roleParams' => ['serviceId' => SwiftfinModule::SERVICE_ID],
                    ],
					[
						'allow' => true,
                        'actions' => ['upload'],
						'roles' => ['admin'],
					],
				],
			],
			'verbs' => [
				'class'   => VerbFilter::className(),
				'actions' => [
					'delete' => ['post'],
				],
			],
		];
	}

    /**
     * Lists all DictBank models.
     * @return mixed
     */
    public function actionIndex()
    {
        $monitorLog = Yii::$app->monitoring->getLastLog('swiftfin:DictionaryUploaded');

        $searchModel = new SwiftFinDictBankSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'lastUpload' => $monitorLog ? $monitorLog : null,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DictBank model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($swiftCode, $branchCode)
    {
        return $this->render('view', [
            'model' => $this->findModel($swiftCode, $branchCode),
        ]);
    }

	public function actionUpload()
    {
		if (!Yii::$app->request->getIsPost()) {
			return $this->redirect('index');
		}

		$file   = UploadedFile::getInstanceByName('file');

        if (!$file) {
			Yii::$app->session->setFlash('error', Yii::t('yii', '{attribute} cannot be blank.', ['attribute' => Yii::t('app', 'File')]));
		} else if (!in_array($file->type, [
            'application/x-7z-compressed', 'application/x-zip-compressed',
            'application/zip', 'application/octet-stream'])
        ) {
			Yii::$app->session->setFlash('error', Yii::t('app/error', 'Invalid source file format'));
		} else {
			try {
                $this->processFile($file);
			} catch (\Exception $ex) {
				Yii::$app->session->setFlash('error', $ex->getMessage());
			}
		}

        return $this->redirect(['index']);
	}

    private function processFile($file)
    {
        $eventCode = 'swiftfin:DictionaryUploaded';
        $params = [
            'userId' => Yii::$app->user->identity->id,
            'type' => '',
            'fileName' => $file->name,
            'fileSize' => filesize($file->tempName),
            'status' => DictionaryUploadedEvent::STATUS_PROCESSING,
        ];

        $event = BaseEvent::getEventObject($eventCode, $params);
        $event->entity = 'user';
        $event->entityId = Yii::$app->user->identity->id;
        $loggedEvent = Yii::$app->monitoring->log(
                $event->code, $event->entity, $event->entityId, $params
        );

        try {
            $archiveReader = ArchiveFile::getHandler($file->name);

            $resource = Yii::$app->registry->getTempResource($this->module->serviceId);
            $dir = $resource->createDir(FileHelper::uniqueName());
            $result = $archiveReader->extract($file->tempName, $dir);

            if ($result === false) {
                throw new InvalidArgumentException(Yii::t('doc/swiftfin', 'Cannot extract archive'));
            }

            $files = $resource->getContents($dir);

            $dictBankReader = DictBankReader::getReader($files);

            if (empty($dictBankReader)) {
                throw new InvalidArgumentException(Yii::t('doc/swiftfin', 'File format not recognized'));
            }

            $event->type = $dictBankReader->getType();

            /**
             * Бьем общее количество записей на части, чтобы избежать переполнения памяти
             */
            while(true) {
                $values = [];
                $count = 5000;
                while($count--) {
                    $record = $dictBankReader->getRecord();
                    if (empty($record)) {
                        break;
                    }

                    $values[] = implode(',', [
                        Yii::$app->db->quoteValue($record['swiftCode']),
                        Yii::$app->db->quoteValue($record['branchCode']),
                        Yii::$app->db->quoteValue($record['name']),
                        Yii::$app->db->quoteValue($record['address']),
                        Yii::$app->db->quoteValue($record['swiftCode'].$record['branchCode'])
                    ]);
                }

                if (!count($values)) {
                    break;
                }

                $event->recordCount += count($values);

                /**
                 * Здесь используется bulk-вставка с mySQL-специфичной заменой (ON DUPLICATE KEY...)
                 * В фреймворке ничего такого нет именно по причине специфичности.
                 * Стандартные средства типа проверки уникальности моделей работают очень медленно
                 * (в базу грузится более 100 тысяч записей)
                 */
                $cmd = Yii::$app->db->createCommand(
                    'INSERT INTO ' . SwiftFinDictBank::tableName() . ' (`swiftCode`, `branchCode`, `name`, `address`,`fullCode`)
                    VALUES (' . implode("),\n (", $values) . ')
                    ON DUPLICATE KEY UPDATE name=VALUES(name), address=VALUES(address)
                ');

                $event->changedCount += $cmd->execute();
            }

            foreach($files as $file) {
                unlink($file);
            }

            rmdir($dir);

            $event->status = DictionaryUploadedEvent::STATUS_PROCESSED;

            // Регистрация события загрузки справочника swift-кодов
            Yii::$app->monitoring->extUserLog('UploadSwiftCodes');

        } catch(Exception $ex) {
            $event->status = DictionaryUploadedEvent::STATUS_ERROR;
            throw $ex;
        } finally {
            $loggedEvent->loadEvent($event);
            $loggedEvent->save();
        }
    }

	public function actionList($q = null)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        return SwiftfinHelper::getBanksList($q);
	}

    public function actionGetBankInfo($swiftInfo)
    {
        // Если не ajax-запрос
        if (!Yii::$app->request->isAjax) {
            throw new MethodNotAllowedHttpException();
        }

        $bankInfo = SwiftfinHelper::getBankInfo($swiftInfo);

        return json_encode($bankInfo);
    }

    protected function findModel($swiftCode, $branchCode)
    {
        if (($model = SwiftFinDictBank::findOne(['swiftCode' => $swiftCode, 'branchCode' => $branchCode])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
