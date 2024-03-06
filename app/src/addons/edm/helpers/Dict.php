<?php
namespace addons\edm\helpers;

use Yii;

abstract class Dict
{
	/**
	 * Назначение платежа
	 * @return array
	 */
	static public function paymentPurpose() {
		return [
			0 => 'Без НДС',
			10 => 'в т.ч. НДС 10%',
            18 => 'в т.ч. НДС 18%',
			20 => 'в т.ч. НДС 20%'
		];
	}

	/**
	 * Вид платежа
	 * @return array
	 */
	static public function paymentType()
    {
		return [
			'электронно' => 'электронно',
			'почтой'     => 'почтой',
			'телеграфом' => 'телеграфом',
			'срочно'     => 'срочно',
		];
	}

	/**
	 * Очередность платежа
	 * @return array
	 */
	static public function priority()
    {
		return [
			1 => '1: Удовлетворение требований о возмещении вреда, причиненного жизни и здоровью, а также требований о взыскании алиментов.',
			2 => '2: Расчеты по выплате выходных пособий и оплате труда  с лицами, работающими по трудовому договору, в том числе по контракту, по выплате вознаграждений по авторскому договору.',
			3 => '3: Расчеты по оплате труда с лицами, работающими по трудовому договору (контракту), по уплате налогов и сборов, а также списание и перечисление сумм страховых взносов в бюджеты государственных внебюджетных фондов',
			4 => '4: Расчеты по исполнительным документам, предусматривающим удовлетворение других денежных требований.',
			5 => '5: Расчеты по другим платежным документам в порядке календарной очередности.',
			6 => '6: Другие платежные документы.'
		];
	}

    /**
     * Валидатор для проверки значений ИНН и КПП
     */
    static function validateCodeNumber($obj, $attribute, $params)
    {
        $value = $obj->$attribute;

        if ($value && !preg_match('/^[0-9]+$/', $value)) {
            $obj->addError($attribute,	Yii::t('edm', 'Only digits are allowed'));

            return;
        }

        if ($obj->type == 'IND') {
            if ($attribute == 'inn') {
                if (!empty($value) && strlen($value) != 12) {
                    $obj->addError($attribute,
                        Yii::t('edm', 'The length must be {options} digits', ['options' => 12])
                    );
                }
            } else if ($attribute == 'kpp') {
                if (!empty($value) && $value !== '0') {
                    $obj->addError($attribute,
                        Yii::t('edm', 'This field must be null for individual account')
                    );
                }
            }

            return;
        }

        if (isset($params['options'])) {
            $options = $params['options'];
            $length = mb_strlen($value);
            if (!array_key_exists($length, array_flip($options))) {
                $obj->addError(
                    $attribute,
                    Yii::t('edm', 'The length must be {options} digits',
                            ['options' => implode(Yii::t('edm', ' or '), $options)])
                );

                return;
            }
        }

        if (isset($params['nozero12'])) {
            if ($value{0} == '0' && $value{1} == '0') {
                $obj->addError(
                    $attribute,
                    Yii::t('edm', 'The first and second digit cannot be zero at once')
                );

                return;
            }
        } else {
            if (trim($value, '0') == '') {
                $obj->addError($attribute,	Yii::t('edm', 'All digits cannot be zero'));

                return;
            }
        }
    }

}