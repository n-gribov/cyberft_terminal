<?php

use addons\edm\EdmModule;
use addons\edm\helpers\EdmHelper;
use addons\edm\models\EdmDocumentTypeGroup;
use common\document\DocumentPermission;
use common\models\User;
use kartik\time\TimePicker;
use kartik\touchspin\TouchSpin;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;

$this->title					 = Yii::t('edm', 'Payer account #{id}', ['id' => $model->number]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('edm', 'Banking'), 'url' => Url::toRoute(['/edm'])];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/menu', 'Payers Accounts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$userCanCreateStatementRequests = Yii::$app->user->can(
    DocumentPermission::CREATE,
    ['serviceId' => EdmModule::SERVICE_ID, 'documentTypeGroup' => EdmDocumentTypeGroup::STATEMENT]
);

?>

<p>
    <?php
    $backUrl = Yii::$app->user->identity->role === User::ROLE_USER
        ? ['/profile/dashboard']
        : ['/edm/dict-organization/view', 'id' => $model->organizationId];

    echo Html::a(
        Yii::t('app', 'Return'),
        $backUrl,
        ['class' => 'btn btn-default']
    )
    ?>

    <?php if (Yii::$app->user->can('admin')) : ?>
        <?=Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary'])?>
        <?=Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data'  => [
                'confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                'method'  => 'post',
            ],
        ])?>
    <?php endif ?>

    <?php if ($userCanCreateStatementRequests): ?>
        <?= $this->render('/documents/_sendRequestForm', ['account' => $model]) ?>
    <?php endif; ?>
</p>
<div class="row">
<div class="col-sm-6">
<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        'name',
        [
            'attribute' => 'edmDictOrganization.name',
            'value' => $model->getPayerName()
        ],
        [
            'attribute' => 'requireSignQty',
            'value' => EdmHelper::getPayerAccountSignaturesNumber($model)
        ],
        'number',
        [
            'attribute' => 'bank.name',
            'label' => Yii::t('edm', 'Account bank'),
        ]
    ]
])?>
</div>
</div>

<?php

if ($isVTBTerminal) {
	$form = ActiveForm::begin([
	   'id' => 'statement-auto-request' 
	]);

	?>

	<h4>
		<?= Yii::t('app', 'Automatic statement request') ?>
	</h4>
	<div class="row">
	    <div class="col-sm-6">
		<?= Html::checkbox('previousDayCheckBox', false, 
			[
			    'label' => Yii::t('app', 'Create statement requests for previous day'),
			    'class' => 'previous-day-checkbox'
			]); ?>	    
	    </div>
	</div>
	<div class="previous-day-block" >
	<div class="row">
	    <div class="col-sm-6">
	        <?=
		    $form->field($modelScheduledRequestPreviousDay, 'previousDaysSelect')->widget(Select2::className(), [
			'model' => $modelScheduledRequestPreviousDay,
			'attribute' => 'previousDaysSelect',
			'data' => [
			    '1' => 'Понедельник', 
			    '2' => 'Вторник', 
			    '3' => 'Среда', 
			    '4' => 'Четверг', 
			    '5' => 'Пятница', 
			    '6' => 'Суббота', 
			    '7' => 'Воскресенье'
			],
		        'options' => [
		    	    'multiple' => true
		        ],
		    ])	
		?>
	    </div>
	</div>
	<div class="row">
	    <div class="col-sm-2">
		<?=
		    $form->field($modelScheduledRequestPreviousDay, 'startTime')->widget(TimePicker::className(), [
			    'pluginOptions' => [
				'showSeconds' => false,
				'showMeridian' => false,
				'minuteStep' => 1,
				'secondStep' => 5,
				//'template' => false
			    ]
		    ])
		?>
	    </div>	
	    <div class="col-sm-2">
		<?=
		    $form->field($modelScheduledRequestPreviousDay, 'endTime')->widget(TimePicker::className(), [
			    'pluginOptions' => [
				'showSeconds' => false,
				'showMeridian' => false,
				'minuteStep' => 1,
				'secondStep' => 5,
				//'template' => false
			    ]
		    ])
		?>
	    </div>
	    <div class="col-sm-2">
		<?=
		    $form->field($modelScheduledRequestPreviousDay, 'interval')->widget(TouchSpin::className(), [
			   'pluginOptions' => [
				'verticalbuttons' => true
			   ]
		    ])
		?>
	    </div>
	</div>
	</div>
	<div class="row">
	    <div class="col-sm-6">
		<?= Html::checkbox('currentDayCheckBox', false, 
			[
				'label' => Yii::t('app', 'Create statement requests for current day'),
				'class' => 'current-day-checkbox'
			]); ?>
	    </div>
	</div>
	<div class="current-day-block " >
	<div class="row">
	    <div class="col-sm-6">
	        <?=
		    $form->field($modelScheduledRequestCurrentDay, 'currentDaysSelect')->widget(Select2::className(), [
			'model' => $modelScheduledRequestCurrentDay,
			'attribute' => 'currentDaysSelect',
			'data' => [
			    '1' => 'Понедельник', 
			    '2' => 'Вторник', 
			    '3' => 'Среда', 
			    '4' => 'Четверг', 
			    '5' => 'Пятница', 
			    '6' => 'Суббота', 
			    '7' => 'Воскресенье'
			],
		        'options' => [
		    	    'multiple' => true
		        ],
		    ])	
		?>
	    </div>
	</div>
	<div class="row">
	    <div class="col-sm-2">
		<?=
		    $form->field($modelScheduledRequestCurrentDay, 'startTime')->widget(TimePicker::className(), [
			    'pluginOptions' => [
				'showSeconds' => false,
				'showMeridian' => false,
				'minuteStep' => 1,
				'secondStep' => 5,
				//'template' => false
			    ]
		    ])
		?>
	    </div>
	    <div class="col-sm-2">
		<?=
		    $form->field($modelScheduledRequestCurrentDay, 'endTime', 
			    ['template' => '{label}{input}{hint}{error}'])->widget(TimePicker::className(), [
			    'pluginOptions' => [
				'showSeconds' => false,
				'showMeridian' => false,
				'minuteStep' => 1,
				'secondStep' => 5,
			    ]
		    ])
		?>
	    </div>
	    <div class="col-sm-2">
		<?=
		    $form->field($modelScheduledRequestCurrentDay, 'interval')->widget(TouchSpin::className(), [
			   'pluginOptions' => [
				'verticalbuttons' => true
			   ]
		    ])
		?>
	    </div>
	</div>
	</div>
	<div class="row">
	    <div class="col-sm-2">
		<button type="submit" id="btn-scheduled-request-save" class="btn btn-primary btn-sm btn-block">Сохранить</button>	
	    </div>
	</div>

	<?php

	ActiveForm::end();
}

$script = <<<JS
	
	let weekDaysPrevious = [$modelScheduledRequestPreviousDay->weekDays].map(function(day) {
		return '' + day;
	});
	
	let weekDaysCurrent = [$modelScheduledRequestCurrentDay->weekDays].map(function(day) {
		return '' + day;
	});

	console.log(weekDaysPrevious);
	console.log(weekDaysCurrent);

	if (weekDaysPrevious.length == 0) {
		$('.previous-day-checkbox')[0].checked = false;
		$('.previous-day-block').hide(); 		
	} else {
		$('.previous-day-checkbox')[0].checked = true;
	}     

	if (weekDaysCurrent.length == 0) {
		$('.current-day-checkbox')[0].checked = false;
		$('.current-day-block').hide(); 		
	} else {
		$('.current-day-checkbox')[0].checked = true;
	}
	
	$(document).ready(function() {
		$('#edmscheduledrequestprevious-previousdaysselect').val(weekDaysPrevious);
		$('#edmscheduledrequestprevious-previousdaysselect').trigger('change');
		$('#edmscheduledrequestcurrent-currentdaysselect').val(weekDaysCurrent);
		$('#edmscheduledrequestcurrent-currentdaysselect').trigger('change');
	});

	$('body').on('click', '.previous-day-checkbox', function() {
		$('.previous-day-block').slideToggle('400');
	});
	$('body').on('click', '.current-day-checkbox', function() {
		$('.current-day-block').slideToggle('400');
	});
JS;

	$this->registerJs($script, View::POS_READY);
	
	
$css = <<<CSS
	.select2-results__option[aria-selected=true] { display: none;}
	
	.s2-togall-button .s2-select-label i { display: none;}
	.s2-togall-button .s2-unselect-label i { display: none;}
	
	.s2-togall-button span { font-size: 16px;}
	
	.input-group-addon.picker i {margin: auto; font-size: 18px; }

    h4 {
        margin-top: 50px;
    }
CSS;
$this->registerCss($css);

$userRole = Yii::$app->user->identity->role;
if (in_array($userRole, [User::ROLE_ADMIN, User::ROLE_ADDITIONAL_ADMIN])) {
    echo $this->render('_statements-export-settings', compact('model'));
}
