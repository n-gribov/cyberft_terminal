<?php

use yii\db\Migration;
use addons\edm\models\Statement\StatementDocumentExt;
use addons\edm\models\DictCurrency;

class m170209_094929_edm_ext_update_currency extends Migration
{
    public function up()
    {
        // @CYB-3533
        // Поле валюты не должно быть обязательным для записи extModel Выписки
        $this->execute("alter table `documentExtEdmStatement` modify `currency` varchar(3) null");

        // Проходимся по всем extModel выписок, где не заполнена валюта, и пытаемся подобрать валюту по счету, если она не заполнена
        $statements = StatementDocumentExt::find()->where(['currency' => null])->orWhere(['currency' => ''])->all();

        foreach($statements as $statement) {
            // Пропускаем строки, где не заполнен номер счета или он не равен 20 символам
            if (!$statement->accountNumber || strlen($statement->accountNumber) != 20) {
                continue;
            }

            // Получаем 5-8 символы из номера счета
            $accountCurrency = substr($statement->accountNumber, 5, 3);

            // Ищем в справочнике валюту по её номеру
            $currencyDict = DictCurrency::findOne(['code' => $accountCurrency]);

            // Если валюта найдена в справочнике, заполняем выписку
            if ($currencyDict) {
                $statement->currency = $currencyDict->name;
                // Сохранить модель в БД
                $statement->save();
            }
        }
    }

    public function down()
    {
        $this->execute("alter table `documentExtEdmStatement` modify `currency` varchar(3) not null");

        return true;
    }
}
