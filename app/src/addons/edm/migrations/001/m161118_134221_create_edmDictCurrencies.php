<?php

use yii\db\Migration;
use addons\edm\models\DictCurrency;

class m161118_134221_create_edmDictCurrencies extends Migration
{
    public function up()
    {
        // Справочник валют для edm
        //CYB-3317

        // Создание таблицы с валютами
        $this->createTable('edmDictCurrencies', [
            'id' => $this->primaryKey(),
            'code' => $this->integer(3)->notNull(),
            'name' => $this->string(3)->notNull(),
            'description' => $this->string()
        ]);

        // Формирование поставки валют
        $currenciesList = $this->getCurrenciesList();

        echo "Add default currencies" . PHP_EOL;

        foreach($currenciesList as $currency) {
            $newCurrency = new DictCurrency();
            $newCurrency->code = $currency[0];
            $newCurrency->name = $currency[1];
            $newCurrency->description = $currency[2];
            // Сохранить модель в БД
            $newCurrency->save();
        }
    }

    public function down()
    {
        $this->dropTable('edmDictCurrencies');
        return true;
    }

    /**
     * Список валют для загрузки в таблицу
     */
    protected function getCurrenciesList()
    {
        return [
            ['643', 'RUB', 'Российский Рубль'],
            ['810', 'RUR', 'Российский Рубль'],
            ['978', 'EUR', 'Евро'],
            ['840', 'USD', 'Доллар Сша'],
            ['756', 'CHF', 'Швейцарский Франк'],
            ['156', 'CNY', 'Китайский Юань'],
            ['826', 'GBP', 'Фунт Стерлингов'],
            ['398', 'KZT', 'Тенге (Казахский)'],
            ['392', 'JPY', 'Йена'],
            ['784', 'AED', 'Дирхам (Оаэ)'],
            ['971', 'AFN', 'Афгани'],
            ['008', 'ALL', 'Лек'],
            ['051', 'AMD', 'Армянский Драм'],
            ['532', 'ANG', 'Нидерландский Антильский Гульден'],
            ['973', 'AOA', 'Кванза'],
            ['032', 'ARS', 'Аргентинское Песо'],
            ['036', 'AUD', 'Австралийский Доллар'],
            ['533', 'AWG', 'Арубанский Флорин'],
            ['944', 'AZN', 'Азербайджанский Манат'],
            ['977', 'BAM', 'Конвертируемая Марка'],
            ['052', 'BBD', 'Барбадосский Доллар'],
            ['050', 'BDT', 'Така'],
            ['975', 'BGN', 'Болгарский Лев'],
            ['048', 'BHD', 'Бахрейнский Динар'],
            ['108', 'BIF', 'Бурундийский Франк'],
            ['060', 'BMD', 'Бермудский Доллар'],
            ['096', 'BND', 'Брунейский Доллар'],
            ['068', 'BOB', 'Боливиано'],
            ['986', 'BRL', 'Бразильский Реал'],
            ['044', 'BSD', 'Багамский Доллар'],
            ['064', 'BTN', 'Нгултрум'],
            ['072', 'BWP', 'Пула'],
            ['974', 'BYR', 'Белорусский Рубль'],
            ['084', 'BZD', 'Белизский Доллар'],
            ['124', 'CAD', 'Канадский Доллар'],
            ['976', 'CDF', 'Конголезский Франк'],
            ['152', 'CLP', 'Чилийское Песо'],
            ['170', 'COP', 'Колумбийское Песо'],
            ['970', 'COU', 'Единица Реальной Стоимости'],
            ['188', 'CRC', 'Костариканский Колон'],
            ['931', 'CUC', 'Конвертируемое Песо'],
            ['192', 'CUP', 'Кубинское Песо'],
            ['132', 'CVE', 'Эскудо Кабоверде'],
            ['203', 'CZK', 'Чешская Крона'],
            ['262', 'DJF', 'Франк Джибути'],
            ['208', 'DKK', 'Датская Крона'],
            ['214', 'DOP', 'Доминиканское Песо'],
            ['012', 'DZD', 'Алжирский Динар'],
            ['818', 'EGP', 'Египетский Фунт'],
            ['232', 'ERN', 'Накфа'],
            ['230', 'ETB', 'Эфиопский Быр'],
            ['242', 'FJD', 'Доллар Фиджи'],
            ['238', 'FKP', 'Фунт Фолклендских Островов'],
            ['981', 'GEL', 'Лари'],
            ['936', 'GHS', 'Ганский Седи'],
            ['292', 'GIP', 'Гибралтарский Фунт'],
            ['270', 'GMD', 'Даласи'],
            ['324', 'GNF', 'Гвинейский Франк'],
            ['320', 'GTQ', 'Кетсаль'],
            ['328', 'GYD', 'Гайанский Доллар'],
            ['344', 'HKD', 'Гонконгский Доллар'],
            ['340', 'HNL', 'Лемпира'],
            ['191', 'HRK', 'Хорватская Куна'],
            ['332', 'HTG', 'Гурд'],
            ['348', 'HUF', 'Форинт'],
            ['360', 'IDR', 'Рупия'],
            ['376', 'ILS', 'Новый Израильский Шекель'],
            ['356', 'INR', 'Индийская Рупия'],
            ['368', 'IQD', 'Иракский Динар'],
            ['364', 'IRR', 'Иранский Риал'],
            ['352', 'ISK', 'Исландская Крона'],
            ['388', 'JMD', 'Ямайский Доллар'],
            ['400', 'JOD', 'Иорданский Динар'],
            ['404', 'KES', 'Кенийский Шиллинг'],
            ['417', 'KGS', 'Сом'],
            ['116', 'KHR', 'Риель'],
            ['174', 'KMF', 'Франк Комор'],
            ['408', 'KPW', 'Северокорейская Вона'],
            ['410', 'KRW', 'Вона'],
            ['414', 'KWD', 'Кувейтский Динар'],
            ['136', 'KYD', 'Доллар Островов Кайман'],
            ['418', 'LAK', 'Кип'],
            ['422', 'LBP', 'Ливанский Фунт'],
            ['144', 'LKR', 'Шриланкийская Рупия'],
            ['430', 'LRD', 'Либерийский Доллар'],
            ['426', 'LSL', 'Лоти'],
            ['434', 'LYD', 'Ливийский Динар'],
            ['504', 'MAD', 'Марокканский Дирхам'],
            ['498', 'MDL', 'Молдавский Лей'],
            ['969', 'MGA', 'Малагасийский Ариари'],
            ['807', 'MKD', 'Денар'],
            ['104', 'MMK', 'Кьят'],
            ['496', 'MNT', 'Тугрик'],
            ['446', 'MOP', 'Патака'],
            ['478', 'MRO', 'Угия'],
            ['480', 'MUR', 'Маврикийская Рупия'],
            ['462', 'MVR', 'Руфия'],
            ['454', 'MWK', 'Квача'],
            ['484', 'MXN', 'Мексиканское Песо'],
            ['458', 'MYR', 'Малайзийский Ринггит'],
            ['943', 'MZN', 'Мозамбикский Метикал'],
            ['516', 'NAD', 'Доллар Намибии'],
            ['566', 'NGN', 'Найра'],
            ['558', 'NIO', 'Золотая Кордоба'],
            ['578', 'NOK', 'Норвежская Крона'],
            ['524', 'NPR', 'Непальская Рупия'],
            ['554', 'NZD', 'Новозеландский Доллар'],
            ['512', 'OMR', 'Оманский Риал'],
            ['590', 'PAB', 'Бальбоа'],
            ['604', 'PEN', 'Новый Соль'],
            ['598', 'PGK', 'Кина'],
            ['608', 'PHP', 'Филиппинское Песо'],
            ['586', 'PKR', 'Пакистанская Рупия'],
            ['985', 'PLN', 'Злотый'],
            ['600', 'PYG', 'Гуарани'],
            ['634', 'QAR', 'Катарский Риал'],
            ['946', 'RON', 'Новый Румынский Лей'],
            ['941', 'RSD', 'Сербский Динар'],
            ['646', 'RWF', 'Франк Руанды'],
            ['682', 'SAR', 'Саудовский Риял'],
            ['090', 'SBD', 'Доллар Соломоновых Островов'],
            ['690', 'SCR', 'Сейшельская Рупия'],
            ['938', 'SDG', 'Суданский Фунт'],
            ['752', 'SEK', 'Шведская Крона'],
            ['702', 'SGD', 'Сингапурский Доллар'],
            ['654', 'SHP', 'Фунт Святой Елены'],
            ['694', 'SLL', 'Леоне'],
            ['706', 'SOS', 'Сомалийский Шиллинг'],
            ['968', 'SRD', 'Суринамский Доллар'],
            ['728', 'SSP', 'Южносуданский Фунт'],
            ['678', 'STD', 'Добра'],
            ['222', 'SVC', 'Сальвадорский Колон'],
            ['760', 'SYP', 'Сирийский Фунт'],
            ['748', 'SZL', 'Лилангени'],
            ['764', 'THB', 'Бат'],
            ['972', 'TJS', 'Сомони'],
            ['934', 'TMT', 'Новый Туркменский Манат'],
            ['788', 'TND', 'Тунисский Динар'],
            ['776', 'TOP', 'Паанга'],
            ['949', 'TRY', 'Турецкая Лира'],
            ['780', 'TTD', 'Доллар Тринидада И Тобаго'],
            ['901', 'TWD', 'Новый Тайваньский Доллар'],
            ['834', 'TZS', 'Танзанийский Шиллинг'],
            ['980', 'UAH', 'Гривна'],
            ['800', 'UGX', 'Угандийский Шиллинг'],
            ['940', 'UYI', 'Уругвайское Песо В Индексированных Единицах'],
            ['858', 'UYU', 'Уругвайское Песо'],
            ['860', 'UZS', 'Узбекский Сум'],
            ['937', 'VEF', 'Боливар'],
            ['704', 'VND', 'Донг'],
            ['548', 'VUV', 'Вату'],
            ['882', 'WST', 'Тала'],
            ['950', 'XAF', 'Франк Кфа Веас'],
            ['951', 'XCD', 'Восточнокарибский Доллар'],
            ['960', 'XDR', 'Сдр (Специальные Права Заимствования)'],
            ['952', 'XOF', 'Франк Кфа Всеао'],
            ['953', 'XPF', 'Франк Кфп'],
            ['886', 'YER', 'Йеменский Риал'],
            ['710', 'ZAR', 'Рэнд'],
            ['967', 'ZMW', 'Замбийская Квача'],
            ['932', 'ZWL', 'Доллар Зимбабве']
        ];
    }
}
