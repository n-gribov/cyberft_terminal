<?php
use common\models\cyberxml\CyberXmlDocument;
use yii\helpers\Html;
use yii\helpers\Url;

if ($model->getValidStoredFileId()) {
    $typeModel = CyberXmlDocument::getTypeModel($model->getValidStoredFileId());
    $paymentOrder = $typeModel->getPaymentOrder($num);
}
?>

<div class="btn-group">
  <button type="button" class="btn btn-default dropdown-toggle"
		  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <?=Yii::t('app', 'Actions')?> <span class="caret"></span>
  </button>
  <ul class="dropdown-menu pull-left">
	<li><?=Html::a(Yii::t('app', 'Print'),
		Url::toRoute(['/edm/documents/print-statement-payment-order', 'id' => $model->id, 'num' => $num]), ['target' => '_blank'])?>
	</li>
    <li><?=Html::a(Yii::t('app', 'Export as {format}', ['format' => 'Excel']),
		Url::toRoute(['/edm/export/export-statement-paymentorder', 'id' => $model->id, 'num' => $num, 'exportType' => 'excel']))?>
	</li>
    <li><?=Html::a(Yii::t('app', 'Export as {format}', ['format' => '1C']),
	Url::toRoute(['/edm/export/export-statement-paymentorder', 'id' => $model->id, 'num' => $num, 'exportType' => '1c']))?>
	</li>
  </ul>
</div>

<?= // Вывести страницу
    $this->render('readable/paymentOrder', ['paymentOrder' => $paymentOrder]) ?>