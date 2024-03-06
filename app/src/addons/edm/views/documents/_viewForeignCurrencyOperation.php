<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<div class="btn-group">
  <button type="button" class="btn btn-default dropdown-toggle"
          data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <?=Yii::t('app', 'Actions')?> <span class="caret"></span>
  </button>
  <ul class="dropdown-menu pull-left">
    <li><?=Html::a(Yii::t('app', 'Print'),
        Url::toRoute(['/edm/documents/foreign-currency-operation-print', 'id' => $model->id]), ['target' => '_blank'])?>
    </li>
  </ul>
</div>
<?php
$cyxDoc = \common\models\cyberxml\CyberXmlDocument::read($model->actualStoredFileId);
$typeModel = $cyxDoc->getContent()->getTypeModel();

// Вывести страницу
echo $this->render('readable/foreignCurrencyOperation', ['document' => $model, 'typeModel' => $typeModel]);
