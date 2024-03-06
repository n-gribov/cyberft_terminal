<?php

use common\models\Terminal;
use common\widgets\GridView;
use yii\helpers\Url;
use common\helpers\Html;
use common\models\UserTerminal;
use yii\helpers\ArrayHelper;
use common\models\User;
use kartik\select2\Select2;

// Получение терминалов пользователя для формирования списка доступных ему терминалов
$terminals = Terminal::find()->where(['status' => Terminal::STATUS_ACTIVE]);

$adminIdentity = Yii::$app->user->identity;

// Для доп. админа получаем только доступные ему терминалы
if ($adminIdentity->role == User::ROLE_ADDITIONAL_ADMIN) {

    $allTerminals = UserTerminal::find()
        ->where(['userId' => $adminIdentity->id])
        ->select('terminalId')
        ->asArray()
        ->all();

    $allTerminalsList = ArrayHelper::getColumn($allTerminals, 'terminalId');

    $terminals->andWhere(['id' => $allTerminalsList]);
}


// Если существует data-провайдер с массивом
// текущих выбранных терминалов, то формируем запрос согласно ему

if (isset($dataProvider) && $dataProvider->allModels) {

    $currentTerminals = [];

    foreach ($dataProvider->allModels as $terminalId=>$title) {
        $currentTerminals[] = $terminalId;
    }

    $terminals = $terminals->andWhere(['not in', 'id', $currentTerminals]);
}

$terminals = $terminals->all();

// Формирование списка доступных пользователю терминалов
$terminalsList = [];
$optionsTerminalId = [
    'id' => 'terminal-select'
];

foreach ($terminals as $terminal) {
    $terminalsList[$terminal->id] = $terminal->title ? $terminal->title : $terminal->terminalId;
}

if (count($terminalsList) > 1) {
    $optionsTerminalId['prompt'] = Yii::t('app', 'All');
} else {
    $optionsTerminalId['disabled'] = 'disabled';
}

// Базовый url для добавления терминалов
$addTerminalUrl = Url::to('/user/add-terminal-new-user');

?>

<!-- Вывод списка доступных для выбора терминалов и кнопки добавления терминалов -->
<div class="row">
    <div class="col-lg-10">
        <?= $form->field($model, 'terminalsList')->widget(Select2::classname(), [
            'data' => $terminalsList,
            'options' => $optionsTerminalId,
            'pluginOptions' => [
                'allowClear' => true,
            ],
        ])->label(Yii::t('app/user', 'Available terminals'));?>
    </div>
    <div class="col-lg-1">
        <a id="add-new-user-terminal" href="<?=$addTerminalUrl?>" class="btn btn-primary"><?= Yii::t('app', 'Add') ?></a>
    </div>
</div>


<?php

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'id' => 'user-terminals-list',
    'columns' => [
        [
            'label' => 'Терминал',
            'value' => 'terminalId'
        ],
        [
            'label' => 'Наименование',
            'value' => 'terminalName'
        ],
        [
            'format' => 'raw',
            'value' => function($model) {
                return Html::a('<span class="glyphicon glyphicon-trash"></span>', '#',
                    [
                        'class' => 'delete-terminal-new-user',
                        'data' => [
                            'terminal-id' => $model['id']
                        ],
                    ]);
            }
        ],
    ],
]);

$this->registerCss(
    "#add-new-user-terminal {
      margin-top: 22px;
    }"
);

?>