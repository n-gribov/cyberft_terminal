<?php

namespace addons\swiftfin\controllers;

use addons\swiftfin\helpers\SwiftfinHelper;
use addons\swiftfin\models\form\TemplatesForm;
use addons\swiftfin\models\SwiftFinDocumentExt;
use addons\swiftfin\models\SwiftFinSearch;
use addons\swiftfin\models\SwiftfinTemplate;
use addons\swiftfin\models\SwiftFinUserExt;
use addons\swiftfin\SwiftfinModule;
use common\base\BaseServiceController;
use common\commands\CommandAR;
use common\document\Document;
use common\document\DocumentPermission;
use common\helpers\UserHelper;
use common\models\cyberxml\CyberXmlDocument;
use common\models\form\DocumentCorrectionForm;
use common\models\Terminal;
use common\models\User;
use common\models\UserTerminal;
use common\modules\participant\helpers\ParticipantHelper;
use common\modules\transport\helpers\DocumentTransportHelper;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\RowCellIterator;
use PhpOffice\PhpSpreadsheet\Worksheet\RowIterator;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use function GuzzleHttp\json_decode;

class DocumentsController extends BaseServiceController
{
	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
                    [
                        'allow' => true,
                        'actions' => [
                            'user-verify',
                            'send',
                            'templates',
                            'correction',
                            'authorize',
                            'send-correction',
			    'list',
                        ],
                        'roles' => [DocumentPermission::CREATE],
                        'roleParams' => ['serviceId' => SwiftfinModule::SERVICE_ID],
                    ],
                    [
                        'allow' => true,
                        'actions' => [
                            'index',
                            'view',
                            'signing-index',
                            'correction-index',
                            'user-verification-index',
                            'authorization-index',
                            'errors',
                            'print',
			    'list',
                        ],
                        'roles' => [DocumentPermission::VIEW, DocumentPermission::CREATE],
                        'roleParams' => ['serviceId' => SwiftfinModule::SERVICE_ID],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete'],
                        'roles' => [DocumentPermission::DELETE],
                        'roleParams' => ['serviceId' => SwiftfinModule::SERVICE_ID],
                    ]
				],
			],
		];
	}

    public function actions()
    {
        $actions = parent::actions();
        $actions['delete'] = [
            'class' => 'common\actions\documents\DeleteAction',
            'serviceId' => SwiftfinModule::SERVICE_ID,
        ];
        return $actions;
    }
    
    public function actionList($type, $page, $q = null)
    {
	Yii::$app->response->format = Response::FORMAT_JSON;
        $searchModel = new SwiftFinSearch(['typeGroup' => $this->module->getServiceId()]);
	
	switch ($page) 
	{
		case 'index':
			$dataProvider = $searchModel->search([]);
			break;
		case 'signing-index':
			$dataProvider = $searchModel->searchForSigning([]);
			break;
		case 'correction-index':
			$dataProvider = $searchModel->searchForCorrection([]);
			break;
		case 'user-verification-index':
			$dataProvider = $searchModel->searchForUserVerify([]);
			break;			
		case 'authorization-index':
			$dataProvider = $searchModel->searchForAuthorization([]);
			break;
		case 'errors':
			$dataProvider = $searchModel->searchForErrors([]);
	}
	
	switch ($type)
	{
		case 'sender':
			$out['results'] = ParticipantHelper::getSenderListForDocumentSearch($dataProvider, $q);
			break;
		case 'receiver':
			$out['results'] = ParticipantHelper::getReceiverListForDocumentSearch($dataProvider, $q);
			break;
		
	}

	return $out;
    }

	/**
	 * Lists all Document models.
	 * @return mixed
	 */
	public function actionIndex($mode = '')
	{
            $searchModel = new SwiftFinSearch(['typeGroup' => $this->module->getServiceId()]);

            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

                    if ($mode == 'xls') {
                            return $this->downloadXls($dataProvider);
                    }

            //Берем список терминалов отправителей (для пользователя)
            $terminals = Terminal::find()->orderBy(['terminalId' => SORT_ASC])->all();

            //Создание списков отправителей/получаталей
            $senderParticipants = UserTerminal::getUserTerminalIds(Yii::$app->user->identity->id);
            $receiverParticipants = [];
            foreach($terminals as $terminal) {
                $receiverParticipants[$terminal->terminalId] = $terminal->terminalId;
            }

            Url::remember(Url::to());

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                            'colored' => true,
                'urlParams' => $this->getSearchUrl('SwiftFinSearch'),
                'listType' => 'swiftIndex',
                'senderParticipants' => $senderParticipants,
                'receiverParticipants' => $receiverParticipants
            ]);

	}

	/**
	 * Lists all Document models.
	 * @return mixed
	 */
	public function actionSigningIndex($mode = '')
	{
            $searchModel = new SwiftFinSearch(['typeGroup' => $this->module->getServiceId()]);
            $dataProvider = $searchModel->searchForSigning(Yii::$app->request->queryParams);        

            $user = \Yii::$app->user->identity;
            // Список терминалов для фильтра в журнале
            if ($user->role == User::ROLE_ADMIN) {
                $query = Terminal::find()->all();
            } else {
                // Для доп. админов отбор только по доступым терминалам
                $terminalId = $user->terminalId;

                if (empty($terminalId) && $user->disableTerminalSelect) {
                    $userTerminals = array_keys(UserTerminal::getUserTerminalIds($user->id));
                } else {
                    $userTerminals = [$terminalId];
                }

                $query = Terminal::find()->where(['id' => $userTerminals])->all();
            }

            $terminals = ArrayHelper::map($query, 'id', 'terminalId');
            
            if ($mode == 'xls') {
                return $this->downloadXls($dataProvider);
            }

            Url::remember(Url::to());

            return $this->render('forSigning', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'colored' => true,
                'urlParams' => $this->getSearchUrl('SwiftFinSearch'),
                'listType' => 'swiftSigning',
                'terminals' => $terminals
            ]);
	}

    /**
     * Correction log
     *
     * @return mixed
     */
	public function actionCorrectionIndex()
	{
            $filterModel = new SwiftFinSearch(['typeGroup' => $this->module->getServiceId()]);
            $dataProvider = $filterModel->searchForCorrection(\Yii::$app->request->queryParams);

            Url::remember(Url::to());

            return $this->render('forCorrection', [
                'filterModel' => $filterModel,
                'dataProvider' => $dataProvider,
                'filterStatus' => (!empty(Yii::$app->request->queryParams)),
                'urlParams' => $this->getSearchUrl('SwiftFinSearch'),
                'listType' => 'swiftCorrection',
            ]);
	}

    public function actionUserVerificationIndex($mode = '')
    {
        $searchModel = new SwiftFinSearch(['typeGroup' => $this->module->getServiceId()]);
        $dataProvider = $searchModel->searchForUserVerify(Yii::$app->request->queryParams);

        if ($mode == 'xls') {
            return $this->downloadXls($searchModel);
        }

        Url::remember(Url::to());

        return $this->render('forUserVerification', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'colored' => true,
            'urlParams' => $this->getSearchUrl('SwiftFinSearch'),
            'listType' => 'swiftVerification'
        ]);
    }

    public function actionUserVerify($id)
    {
        try {
            $model = Yii::$app->terminalAccess->findModel(
                Document::className(),
                [
                    'id' => $id,
                    'status' => Document::getUserVerifiableStatus()
                ]
            );
        } catch (Exception $ex) {
            Yii::$app->session->setFlash('error', Yii::t('doc', 'Document not found'));

            return $this->redirect(['user-verification-index']);
        }

        $verifyModelClass = $this->module->getUserVerificationDocumentType($model->type);
        if ($verifyModelClass && class_exists($verifyModelClass)) {
            $verifyModel = new $verifyModelClass;
        }

        $typeModel = CyberXmlDocument::getTypeModel($model->actualStoredFileId);

        if (!$typeModel) {
            Yii::$app->session->setFlash('error', Yii::t('doc', 'Failed to load the document model'));
        }

        if ($verifyModel && $typeModel) {

            $contentModel = $typeModel->source->getContentModel();

            if (Yii::$app->request->isPost) {
                $verifyModel->contentModel = $contentModel;
                if ($verifyModel->verify(Yii::$app->request->post($contentModel->formName()))) {

                    $model->updateStatus(Document::STATUS_USER_VERIFIED);

                    $this->module->processDocument($model);

                    if ($model->status === Document::STATUS_ACCEPTED) {
                        DocumentTransportHelper::createSendingState($model);
                    }

                    // Регистрация события успешной верификации документа
                    Yii::$app->monitoring->log(
                        'user:verifyDocumentSuccess',
                        'document',
                        $id,
                        [
                            'userId' => Yii::$app->user->id,
                            'initiatorType' => UserHelper::getEventInitiatorType(Yii::$app->user)
                        ]
                    );

                    Yii::$app->session->setFlash('success', Yii::t('doc', 'Document verified'));

                    return $this->redirect(['index']);
                } else {
                    $contentModel->load(Yii::$app->request->post());

                    Yii::$app->session->setFlash('error',
                        Yii::t('doc', 'Document verify error'));

                    if (Document::STATUS_USER_VERIFICATION_ERROR !== $model->status) {
                        $model->updateStatus(Document::STATUS_USER_VERIFICATION_ERROR);

                        // Регистрация события ошибки верификации
                        Yii::$app->monitoring->log(
                            'user:verifyDocumentError',
                            'document',
                            $id,
                            [
                                'userId' => Yii::$app->user->id,
                                'initiatorType' => UserHelper::getEventInitiatorType(Yii::$app->user)
                            ]
                        );
                    }
                }
            }

            return $this->render('userVerify', [
                'model' => $contentModel,
                'verifyTags' => $verifyModel->verifyTags,
            ]);
        } else {
            Yii::$app->session->addFlash('error',
                    Yii::t('doc', 'Verify model for type {type} not found',
                    ['type' => $model->type])
            );

            return $this->redirect(['user-verification-index']);
        }
    }

	public function actionAuthorizationIndex($mode = '')
        {
            $searchModel = new SwiftFinSearch(['typeGroup' => $this->module->getServiceId()]);
            $dataProvider = $searchModel->searchForAuthorization(Yii::$app->request->queryParams);

            if ($mode == 'xls') {
                return $this->downloadXls($searchModel);
            }

            Url::remember(Url::to());

            return $this->render('forAuthorization', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'colored' => true,
                'urlParams' => $this->getSearchUrl('SwiftFinSearch'),
                'listType' => 'swiftAuthorization'
            ]);
	}

	public function actionAuthorize($id)
	{
		$document = $this->findModel($id);

		if (SwiftfinHelper::isAuthorizable($document, Yii::$app->user->identity->id)) {

            $extStatus = '';

			$userExt = $this->module->getUserExtModel(Yii::$app->user->identity->id);
			$extModel = $document->extModel;

			if ($userExt->role == SwiftFinUserExt::ROLE_AUTHORIZER) {
				/*
				 * Авторизовано финальным авторизатором
				 */
                $extStatus = SwiftFinDocumentExt::STATUS_AUTHORIZED;

                // Регистрация события финальной авторизации документа
                Yii::$app->monitoring->log(
                    'user:AuthDocument',
                    'document',
                    $id,
                    [
                        'userId' => Yii::$app->user->id,
                        'initiatorType' => UserHelper::getEventInitiatorType(Yii::$app->user)
                    ]
                );
			} else {
				/*
				 * Авторизовано предварительным авторизатором
				 */
                $extStatus = SwiftFinDocumentExt::STATUS_AUTHORIZATION;

                // Регистрация события предварительной авторизации документа
                Yii::$app->monitoring->log(
                    'user:preAuthDocument',
                    'document',
                    $id,
                    [
                        'userId' => Yii::$app->user->id,
                        'initiatorType' => UserHelper::getEventInitiatorType(Yii::$app->user)
                    ]
                );
			}

            $params = [
                'code'     => 'SwiftFinDocumentAuthorize',
                'entity'   => 'document',
                'entityId' => $id,
                'userId' => Yii::$app->user->id,
                'extStatus' => $extStatus
            ];

            Yii::$app->commandBus->addCommand(
                Yii::$app->user->id,
                'SwiftFinDocumentAuthorize',
                $params
            );

            Yii::$app->session->setFlash('info', Yii::t('doc', 'Document is undergoing authorization'));

            $extModel->extStatus = SwiftFinDocumentExt::STATUS_INAUTHORIZATION;
			$extModel->save(false, ['extStatus']);
		}

		return $this->redirect(['view', 'id' => $id]);
	}

	public function actionErrors($mode = '')
	{
            $searchModel = new SwiftFinSearch(['typeGroup' => $this->module->getServiceId()]);
            $dataProvider = $searchModel->searchForErrors(Yii::$app->request->queryParams);

                    if ($mode == 'xls') {
                            return $this->downloadXls($dataProvider);
                    }

                    Url::remember(Url::to());

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'colored' => true,
                'urlParams' => $this->getSearchUrl('SwiftFinSearch'),
                'listType' => 'swiftErrors'
            ]);
	}

	/**
	 * Displays a single Document model.
	 * @param string $id
	 * @return mixed
	 */
	public function actionView($id, $mode = '')
	{
		$model = $this->findModel($id);

		$referencingDataProvider = new ActiveDataProvider([
			'query' => $model->findReferencingDocuments(),
			'pagination' => [
				'pageSize' => 20,
			],
		]);

        $commands = CommandAR::find()
                ->where([
                    'entityId' => $id,
                    'code' => 'SwiftFinDocumentAuthorize',
                    'status' => 'executed',
                ])
                ->orderBy('id')
                ->all();

        $providerModels = [];
        foreach($commands as $command) {
            $args = json_decode($command->args);
            $user = User::findOne($args->userId);
            $status = $args->extStatus;
            if ($status == SwiftFinDocumentExt::STATUS_AUTHORIZATION) {
                $action = Yii::t('app', 'Preauthorized');
            } else {
                $action = Yii::t('app', 'Authorized');
            }

            $providerModels[] = [
                'userId' => $user->id,
                'userName' => $user->name,
                'action' => $action,
                'date' => $command->dateUpdate,
                'extStatus' => $model->extModel->getStatusLabel($status)
            ];
        }

        $commandDataProvider = new ArrayDataProvider([
            'allModels' => $providerModels
        ]);

        // Регистрация события просмотра документа
        // только если это новый просмотр (т.е., не переход по вкладкам)

        if (empty($mode)) {

            $previousUrl = Url::previous();
            $currentUrl = Url::current();

            if (empty($previousUrl) || $previousUrl !== $currentUrl) {
                Url::remember();
            }

            if ($previousUrl !== $currentUrl) {
                if (!$model->viewed) {
                    $model->viewed = 1;
                    $model->save(false, ['viewed']);
                }

                Yii::$app->monitoring->log(
                    'user:viewDocument',
                    'document',
                    $id,
                    [
                        'userId' => Yii::$app->user->id,
                        'initiatorType' => UserHelper::getEventInitiatorType(Yii::$app->user)
                    ]
                );
            }
        }

		return $this->render('view', [
			'model' => $model,
			'referencingDataProvider' => $referencingDataProvider,
            'commandDataProvider' => $commandDataProvider,
			'mode' => $mode,
            'urlParams' => $this->getSearchUrl('SwiftFinSearch')
		]);
	}

	/**
	 * @param $id
	 * @param $action
	 * @return string
	 * @throws NotFoundHttpException
	 */
	public function actionPrint($id, $action)
	{
		$document = $this->findModel($id);

        // Регистрация события печати документа
        Yii::$app->monitoring->log(
            'user:printDocument',
            'document',
            $id,
            [
                'userId' => Yii::$app->user->id,
                'initiatorType' => UserHelper::getEventInitiatorType(Yii::$app->user)
            ]
        );

        if ('mt' == $action) {
			return $this->renderPartial('printmt', [
						'model' => $document,
						'mode' => 'printmt'
			]);
		} else if ('readable' == $action) {
			return $this->renderPartial('printmt', [
						'model' => $document,
						'mode' => 'readable'
			]);
		} else if ('printable' == $action) {
			return $this->renderPartial('printmt', [
						'model' => $document,
						'mode' => 'printable'
			]);
		} else {
			throw new NotFoundHttpException("Unknown action '{$action}'");
		}
	}

	public function actionSend($id)
	{
		/** @todo избавиться от атавизмов */
		return $this->redirect(['/swiftfin/documents/view', 'id' => $id]);
	}

	/**
	 * Finds the Document model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param string $id
	 * @return Document the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
        return Yii::$app->terminalAccess->findModel(Document::className(), $id);
	}

	protected function downloadXls($dataProvider)
	{
		//$searchModel = new DocumentSearch();
		//$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		$dataProvider->pagination = false;

		$typeLabels = Yii::$app->registry->getModuleTypes($this->module->serviceId);

		foreach($typeLabels as $key => $value) {
			$typeLabels[$key] = $key;
		}

		$xlsView = $this->module->basePath . '/xlsViews/journal.xls';

		$xls = IOFactory::load($xlsView);
		$xls->garbageCollect();
		$xls->getProperties()->setCompany('Киберплат');
		$xls->getProperties()->setCreator('Киберплат');
		$xls->getProperties()->setLastModifiedBy('Киберплат');
		$xls->getProperties()->setModified(date('U'));

		/** @var RowIterator $rows */
		$sheet = $xls->getActiveSheet();
		$rows  = $sheet->getRowIterator(); // по строкам
		//$sheet->insertNewRowBefore(1);
		$repeatRow = null;
		foreach ($rows as $row) {
			/** @var RowCellIterator $cells */
			$cells = $row->getCellIterator();
			foreach ($cells as $cell) {
				$value = $cell->getValue();
				if ($value	&& preg_match_all('/(?P<placeholder>\{(?P<tag>.*)\})/U', $value, $matches)) {
					foreach ($matches['tag'] as $k => $tag) {
						// 'repeat:row' находится в экселе в ячейке в той строке, которую надо повторять.
						if ($tag === 'repeat:row') {
							$repeatRow = $row;
							$cell->setValue(''); // сбрасываем тэг инструкции
							continue 2;
						}
						if ($tag == 'DocDate') { // узнаем значения
							$value = str_replace($matches['placeholder'][$k], 'DOCDATE', $value); // и делаем "красиво"
						}
					}
				}
				$cell->setValue($value);
			}
		}

		$count = $dataProvider->count;
		// дублируем строку с повторяющейся информацией
		$rowIndex = $repeatRow->getRowIndex();
		$sheet->insertNewRowBefore($rowIndex + 1, $count - 1);
		$sheet->duplicateStyle(
			$sheet->getStyle('A' . $rowIndex),
			'A' . $rowIndex . ':A' . ($rowIndex + $count - 1)
		);

		// читаем шаблон дублируемой строки и формируем карту шаблонов по колонкам
		$map = [];
		$cells = $repeatRow->getCellIterator();
		foreach ($cells as $cell) {
			if (($value = $cell->getValue())) {
				$map[$cell->getColumn()] = $value;
			}
		}

		// проходим построчно и наполняем данными
		foreach($dataProvider->models as $model) {
			foreach ($map as $col => $pattern) {
				$cell  = $sheet->getCell($col . $rowIndex);
				$cellValue = $pattern;
				preg_match_all('/(?P<placeholder>\{(?P<tag>.*)\})/U', $pattern, $matches);
				foreach ($matches['tag'] as $tag) {
					$value = $model->getAttribute($tag);

                    if (NULL === $value && !empty($model->documentExtSwiftFin)) {
                        $value = $model->documentExtSwiftFin->getAttribute($tag);
                    }

					if (NULL === $value) {
						$value = '';
					}

					// Costylvania?
					if ($tag == 'status') {
						$value = $model->getStatusLabel();
					} else if ($tag == 'direction') {
						$value = $model->getDirectionLabels()[$model->direction];
					}
					$cellValue = str_replace('{' . $tag . '}', $value, $cellValue);
				}
				$cell->setValue($cellValue);
			}
			$rowIndex++;
		}
		// exit;
		$outFileName = Yii::$app->terminals->defaultTerminalId . '_' . date('d.m.y_Hi') . '.xlsx';
		$objWriter = IOFactory::createWriter($xls, 'Xlsx');

		ob_start();
		$objWriter->save('php://output');
        $data = ob_get_clean();

        return Yii::$app->response->sendContentAsFile($data, $outFileName);
    }

    public function actionTemplates()
    {
        $form = new TemplatesForm();

        if (Yii::$app->request->get('fromId')) {
            $importDocument = Yii::$app->controller->findModel(Yii::$app->request->get('fromId'));
            $form->docType = $importDocument->typeCode;

            if (file_exists($importDocument->pathSource) || is_readable($importDocument->pathSource)) {
                $form->text = file_get_contents($importDocument->pathSource);
            }
        }

        if (Yii::$app->request->get('id')) {
            $template = Yii::$app->terminalAccess->findModel(
                SwiftfinTemplate::className(),
                Yii::$app->request->get('id')
            );

            if ($template) {
                $form->load($template);
            }
        }

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            $template = new SwiftfinTemplate();
            $template->comment = $form->comment;
            $template->docType = $form->docType;
            $template->text = $form->text;
            $template->title = $form->title;
            $template->terminalId = Yii::$app->terminals->defaultTerminal->id;

            if ($template->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app/error', 'Template saved'));
            } else {
                Yii::$app->session->setFlash('error', Yii::t('app/error', 'Error! Template not saved!'));
            }
        }

        return $this->render('templates', [
            'model' => $form,
        ]);
    }

    /**
     * Correction action
     *
     * @return mixed
     */
    public function actionCorrection()
    {
        if (\Yii::$app->request->isPost) {
            $model             = new DocumentCorrectionForm();
            $model->documentId = \Yii::$app->request->post('documentId');
            $model->load(\Yii::$app->request->post());
            if ($model->validate() && $model->toCorrection(\Yii::$app->user->id)) {
                \Yii::$app->session->setFlash('success',
                    \Yii::t('doc', 'The document was sent for correction'));
                return $this->redirect(['/swiftfin/documents/index']);
            }
            \Yii::$app->session->setFlash('error',
                \Yii::t('doc', 'The document was not sent for correction'));
            return $this->redirect(['/swiftfin/documents/view/', ['id' => $model->documentId]]);
        }

        $referer = Url::previous('edit');
        if (empty($referer)) {
            return $this->redirect(['/swiftfin/documents/index']);
        }

        return $this->redirect([$referer]);
    }

    public function actionSendCorrection() {

        if (\Yii::$app->request->isPost) {
            $id = \Yii::$app->request->post('documentId');
            $correctionReason = \Yii::$app->request->post('correctionReason');
            $document = Document::findOne($id);

            if (empty($document)) {
                \Yii::$app->session->setFlash('error',
                    \Yii::t('doc', 'The document was not sent for modification'));
                return $this->redirect(['/swiftfin/documents/index']);
            }

            // Смена статуса документа
            $document->updateStatus(Document::STATUS_CORRECTION);

            // Запись причины коррекции
            $document->extModel->correctionReason = Html::encode($correctionReason);
            $document->extModel->save();

            \Yii::$app->session->setFlash('success',
                \Yii::t('doc', 'The document was sent for modification'));

            // Регистрация события передачи документа на исправление
            Yii::$app->monitoring->log(
                'user:CorrectDocument',
                'document',
                $id,
                [
                    'userId' => Yii::$app->user->id,
                    'initiatorType' => UserHelper::getEventInitiatorType(Yii::$app->user)
                ]
            );

            return $this->redirect(['/swiftfin/documents/index']);
        }

        $referer = Url::previous('edit');
        if (empty($referer)) {
            return $this->redirect(['/swiftfin/documents/index']);
        }

        return $this->redirect([$referer]);
    }

}
