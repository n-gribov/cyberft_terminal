<?php
use common\models\cyberxml\CyberXmlDocument;
use common\widgets\GridView;
use yii\data\ArrayDataProvider;

?>
<div class="panel">
<?php

/** @var $model Document */

if ($model->getValidStoredFileId()) {
    $data = str_getcsv(CyberXmlDocument::getTypeModel($model->getValidStoredFileId()), "\n");

    foreach($data as &$row) {
        $row = str_getcsv($row, ";");
    }

    $headers = array_shift($data);
    foreach($headers as $key => $value) {
       $headers[$key] = str_replace(':', '', trim($value));
    }

    $dataProvider = [];

    foreach ($data as $key => $value ) {

        $item = [];

        foreach ($value as $key2 => $value2) {
            if (!isset($headers[$key2])) {
                $headers[$key2] = $key2;
            }
            $item[$headers[$key2]] = $value2;
        }

        $dataProvider[] = $item;
    }
    // Создать таблицу для вывода
    echo GridView::widget([
        'dataProvider' => new ArrayDataProvider([
            'allModels' => $dataProvider,
            'pagination' => ['pageSize' => 20]
        ]),
        'columns' => [
            'ID в АБС',
            'Дата документа',
            'Дата регистрации',
            'Дата проведения',
            'Название чужого лицевого счета',
            'Детали (назначение) платежа',
            'Статус',
            'Счет дебета',
            'Валюта дебетового счета',
            'Счет кредита',
            'Валюта кредитового счета',
            'Сумма в валюте дебетового счета',
            'Сумма в валюте кредитового счета',
            'Номер документа',
            [
                'attribute' => 'Для межбанковских документов ИНН клиента нашего банка (для дебетовых док-ов -- плательщик, для кредитовых -- получатель), для внутрибанковских документов ИНН дебитора (плательщика)',
                'label' => 'ИНН клиента'
            ],

            'КПП нашего счета',
            [
                'attribute' => 'Для межбанковских документов наименование клиента нашего банка (для дебетовых док-ов -- плательщик, для кредитовых -- получатель), для внутрибанковских документов наименование дебитора (плательщика)',
                'label' => 'Наименование клиента'
            ],
            'Чужой МФО/BIC код',
            'Чужой корсчет',
            'Название чужого банка',
            'Чужой лицевой счет',
            'ИНН корреспондента',
            'КПП корреспондента',
            'SWIFT reference',
        ],
    ]);
} else if ($model->status == $model::STATUS_CREATING) {

    echo 'Документ еще не создан';
} else {
    echo 'К сожалению, нет возможности отобразить документ данного типа';
}
?>
</div>
