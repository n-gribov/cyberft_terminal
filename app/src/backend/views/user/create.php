<?php

use yii\data\ArrayDataProvider;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = Yii::t('app', 'Create');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php

    // Если у нас не pjax-запрос, то очищаем кэш
    // терминалов нового пользователя
    if (!isset($isPjax) && (!isset($dataProvider) || empty($dataProvider))) {
        if (Yii::$app->cache->exists('new-user-data-provider-' . Yii::$app->session->id)) {
            Yii::$app->cache->delete('new-user-data-provider-' . Yii::$app->session->id);
        }

        // Если нет сформированного data-провайдера,
        // то создаем его для передачи
        $dataProvider = new ArrayDataProvider([
            'allModels' => [],
        ]);
    }

    echo $this->render('_form', [
        'model' => $model,
        'dataProvider' => $dataProvider
    ]);

?>
