<?php

use common\widgets\GridView;
use yii\data\ActiveDataProvider;
use common\models\Terminal;
use common\helpers\Html;
use common\models\UserTerminal;
use yii\helpers\ArrayHelper;
use common\models\User;
use kartik\select2\Select2;

if (!$userId) {
    return;
}

// Получить модель пользователя из активной сессии
$adminIdentity = Yii::$app->user->identity;

$userTerminals = UserTerminal::find()->where(['userId' => $userId]);

// Получение терминалов пользователя для формирования списка доступных ему терминалов
$terminals = Terminal::find()->where(['status' => Terminal::STATUS_ACTIVE]);

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


$userTerminalsQuery = $userTerminals->select('terminalId')->asArray()->all();

if ($userTerminalsQuery) {

    $currentTerminals = [];

    foreach ($userTerminalsQuery as $terminal) {
        $currentTerminals[] = $terminal['terminalId'];
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

?>

<?= \common\widgets\Alert::widget() ?>

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
        <a id="add-user-terminal" href="#" class="btn btn-primary" data-id="<?=$model->id?>"><?= Yii::t('app', 'Add') ?></a>
    </div>
</div>

<?php

// Вывод терминалов пользователя
$terminals = UserTerminal::find()->where(['userId' => $userId])->orderBy(['id' => 'ASC']);

$dataProvider = new ActiveDataProvider([
    'query' => $terminals,
    'pagination' => false,
]);
// Создать таблицу для вывода
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'id' => 'user-terminals-list',
    'columns' => [
        [
            'label' => 'Терминал',
            'value' => 'terminal.terminalId'
        ],
        [
            'label' => 'Наименование',
            'value' => 'terminal.title'
        ],
        [
            'format' => 'raw',
            'value' => function($model) use ($userId) {
                return Html::a('<span class="glyphicon glyphicon-trash"></span>', '#',
                    [
                        'class' => 'delete-terminal',
                        'data' => [
                            'user-id' => $userId,
                            'terminal-id' => $model->terminalId
                        ],
                    ]);
            }
        ],
    ],
]);

?>


