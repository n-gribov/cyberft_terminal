<?php

namespace addons\swiftfin\models\documents\mt\mtUniversal;

use addons\swiftfin\models\documents\mt\widgets\TagAttribute;
use \addons\swiftfin\models\documents\mt\widgets\Tag as Widget;
use yii\base\ErrorException;
use yii\base\Exception;
use yii\base\InvalidParamException;
use Yii;

/**
 * Class Tag
 * @package addons\swiftfin\models\documents\mt\mtUniversal
 */
class Tag extends Entity implements EntityInterface
{
    /**
     * @deprecated
     * @var string
     */
    public $field = 'textInput';

    /**
     * Тэг ведет себя как константа
     * т.е. значение может быть задано только единожды и не может быть переопределено
     * @var bool
     */
    public $constant = false;

    /**
     * Тэг в представлениях присутствует в скрытом виде
     * @var bool
     */
    public $hidden = false;

    /**
     * Поле не должно присутствовать в представлении
     * @var bool
     */
    public $invisible = false;

    /**
     * для удобства навигации по конфигу и документации
     * @var integer
     */
    public $number;

    /**
     * Спецкласс для вывода нестандартных тэгов
     */
    public $wrapperClass;

    /**
     * список транслитерируемых полей
     * @var array
     */
    public $transliterable;

    /**
     * тэг является служебным и должен быть обработан в зависимости от иных условий,
     * чаще в зависимости от наличия других тэгов в последовательности
     * @var bool
     */
    protected $_service = false;

    /**
     * Схема валидации и чтения поля
     * @var string
     */
    protected $_mask;

    /**
     * Разобранная детализированная схема валидации и чтения поля
     * @var array
     */
    protected $_maskScheme = [];

    /**
     * Значения аттрибутов тэга
     * @var array
     */
    protected $_attributes = [];

    /**
     * @var string
     */
    protected $_value;

    public function rules()
    {
        $rules = [];
        if (!empty($this->_attributes)) {
            foreach ($this->_attributes as $attribute => $v) {
                $scheme = $this->getAttributeMaskScheme($attribute);
                if (($rule   = MtMask::getStringValidatorByMaskScheme($attribute, $scheme))) {
                    $rules[] = $rule;
                }
                if (($rule = MtMask::getRegexpValidatorByMaskScheme(
                        $attribute, $this->getAttributeLabel($attribute), $scheme
                    ))
                ) {
                    $rules[] = $rule;
                }
            }
        }

        // полное значение так же необходимо валидировать, т.к. на данный момент пользователь может не заполнить
        // обязательное поле и собираемое значение получится не валидным
        if (($rule = MtMask::getStringValidatorByMaskScheme('value', $this->_maskScheme))) {
            $rules[] = $rule;
        }
        if (($rule = MtMask::getRegexpValidatorByMaskScheme('value', $this->label, $this->_maskScheme))) {
            $rules[] = $rule;
        }

        return $rules;
    }

    /**
     * @return array
     */
    public function attributes()
    {
        if ($this->getIsMultiTag()) {
            return array_keys($this->_attributes);
        } else {
            return ['value'];
        }
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        $labels = [
            'value' => $this->label
        ];

        if ($this->getIsMultiTag()) {
            foreach ($this->_scheme as $k => $v) {
                $labels[$k] = $v['label'];
            }
        }

        return $labels;
    }

    /**
     * @return bool
     */
    public function getIsMultiTag()
    {
        return (!$this->constant && !empty($this->_scheme) && !empty($this->_maskScheme));
    }

    /**
     * @param array $scheme
     * @return $this
     * @throws ErrorException
     */
    public function setScheme(&$scheme)
    {
        if ($this->_scheme) {
            throw new ErrorException('You can\'t change instance scheme');
        }

        foreach ($scheme as $k => $v) {
            $item = $v;
            if (isset($item['name'])) {
                $k = $item['name'];
            } else if (is_string($k)) {
                $item['name'] = $k;
            } else if ($item['label']) {
                $item['name'] = $k = 'sub' . MtHelper::getIdentifierByString($item['label']);
            } else {
                $item['name'] = $k = 'sub' . MtHelper::getIdentifierByString(serialize($v));
            }

            $this->_scheme[$k]     = $item;
            $this->_attributes[$k] = null;
        }

        $this->relateMaskWithAttributes();

        return $this;
    }

    /**
     * @param array|null $names
     * @param array      $except
     * @return array
     */
    public function getAttributes($names = null, $except = [])
    {
        $values = [];
        if ($names === null) {
            $names = $this->attributes();
        }
        foreach ($names as $name) {
            $values[$name] = $this->getAttribute($name);
        }
        foreach ($except as $name) {
            unset($values[$name]);
        }

        return $values;
    }

    /**
     * @param array $values
     * @param bool  $safeOnly
     */
    public function setAttributes($values, $safeOnly = true)
    {
        if (is_array($values)) {
            $attributes = array_flip($safeOnly ? $this->safeAttributes() : $this->attributes());
            foreach ($values as $name => $value) {
                if (isset($attributes[$name])) {
                    $this->setAttribute($name, $value); // сетим через метод
                } else if ($safeOnly) {
                    $this->onUnsafeAttribute($name, $value);
                }
            }
        }
    }

    /**
     * Returns a value indicating whether the model has an attribute with the specified name.
     * @param string $name the name of the attribute
     * @return boolean whether the model has an attribute with the specified name.
     */
    public function hasAttribute($name)
    {
        return isset($this->_attributes[$name]) || in_array($name, $this->attributes());
    }

    /**
     * Returns the named attribute value.
     * If this record is the result of a query and the attribute is not loaded,
     * null will be returned.
     * @param string $name the attribute name
     * @return mixed the attribute value. Null if the attribute is not set or does not exist.
     * @see hasAttribute()
     */
    public function getAttribute($name)
    {
        return isset($this->_attributes[$name]) ? $this->_attributes[$name] : null;
    }

    /**
     * Alias, нужен для совместимости с механикой возрата значения по пути см. Sequence::getValueByPath
     * @todo обратить внимание при рефакторинге, может будут мысли как гармоничнее организовать все интерфейсы
     * @param $name
     * @return mixed
     */
    public function getNode($name)
    {
        return $this->getAttribute($name);
    }

    /**
     * Sets the named attribute value.
     * @param string $name  the attribute name
     * @param mixed  $value the attribute value.
     * @throws InvalidParamException if the named attribute does not exist.
     * @see hasAttribute()
     */
    public function setAttribute($name, $value)
    {
        if ($this->hasAttribute($name)) {
            $this->_attributes[$name] = MtHelper::translitEncode(trim($value));
        } else {
            throw new InvalidParamException(get_class($this).' has no attribute named "'.$name.'".');
        }
    }

    /**
     * Временное упрощение-костыль
     * @return array
     */
    public function safeAttributes()
    {
        return $this->attributes();
    }

    /**
     * @param string $name
     * @return mixed
     * @throws Exception
     */
    public function getAttributeMaskScheme($name)
    {
        if (!isset($this->_maskScheme['scheme'])) {
            throw new Exception('Undefined scheme for tag '.implode('->', $this->schemePath()).' with number '.$this->number);
        }
        return $this->_maskScheme['scheme'][$name];
    }

    /**
     * @param $name
     * @return array
     */
    public function getAttributeScheme($name)
    {
        return $this->_scheme[$name];
    }

    /**
     * @inheritdoc
     */
    public function setValue($value)
    {
        if ($this->constant && isset($this->_value)) {
            // пока просто игнорируем повторный set констант
            //throw new Exception("Tag $this->name is constant");
        } else if (is_array($value)) {
            if (isset($value['value'])) {
                $value = $value['value'];
            } else {
                $this->setAttributes($value);

                return;
            }
        }

        $value = MtHelper::translitEncode(rtrim($value));
        if (!$this->getIsMultiTag()) {
            $this->_value = $value;
        } else if ($value) {
            reset($this->_attributes);
            $this->parseValue($this->_maskScheme, $value);
        } else {
            foreach ($this->_attributes as &$v) {
                $v = null;
            }
        }
    }

    private function parseValue($maskScheme, $value)
    {
        if (isset($maskScheme['regexpMtNamed'])) {
            $mask       = $maskScheme['regexpMtNamed'];
            $maskScheme = $maskScheme['scheme'];
        } else {
            array_pop($maskScheme);
            $mask = null;

            foreach ($maskScheme as $scheme) {
                $mask .= $scheme['regexpMtNamed'];
            }

            $mask .= '[\s\S]{0,}';
        }

        // символы конца и начала строки обязательно использовать здесь
        // если вдруг пришла мысль пофиксить какой-то баг парсинга в данном месте
        // не делай этого тут, поломаешь то что сейчас сходу и не заметишь
        // правь логику MtMask

        if (($с = preg_match("~^{$mask}$~", $value, $found))) {
            foreach ($found as $k => $v) {
                // нужные нам переменные имеют ключ в виде строки
                if (!is_string($k)) {
                    continue;
                }
                // схема маски и схема подполей должны иметь полностью идентичный параметр
                $this->_attributes[key($this->_attributes)] = trim($v);
                next($this->_attributes);
            }
        } else {
            if (count($maskScheme) > 1) {
                $this->parseValue($maskScheme, $value);
            } else {
                $this->addError(
                    'value',
                    \Yii::t('doc/mt', 'Value "{value}" does not match the mask "{mask}"',
                    [
                        'mask' => $this->_maskScheme['mask'],
                        'value' => (string) $value,
                    ]
                ));
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function getValue($translitDecode = false)
    {
        if ($this->getIsMultiTag()) {
            $trans = $this->transliterable;
            
            if (is_array($trans)) {
                foreach($trans as & $tr) {
                    $tr = $tr && $translitDecode;
                }
            } else if ($this->name == '70' || $this->name == '72') {
                // Все равно нужен полный рефактор этой херни,
                // поэтому сейчас костыль
                $trans = [$translitDecode, $translitDecode, $translitDecode, $translitDecode, $translitDecode, $translitDecode];
            } else {
                $trans = [];
            }

            return MtMask::mtValueByMaskScheme($this->_attributes, $this->_maskScheme, $trans, $this);

        } else if ($translitDecode && $this->isFieldTransliterable()) {
            return MtHelper::translitDecode($this->_value, $this);
        } else {
            return $this->_value;
        }
    }

    public function getReadableValue($translitDecode = false)
    {
        if ($this->wrapperClass) {
            $class = new $this->wrapperClass($this);
            return $class->getReadable();
        }

        return $this->getValue($translitDecode);
    }

    /**
     * @return bool
     */
    protected function isFieldTransliterable()
    {
        $root = $this->getRoot();
        // Транслитерации не подлежат:
        // так же смотри MtUniversalDocument::translitNeeded(), там условия по типам документов
        return (
            // значение поля референса (поле 20) и значение поля связанного референса (поле 21);
            !in_array($this->_name, ['20', '21'])
            // поля с опцией А, идентифицирующие участников расчетов
            && ((!isset($this->_name[3]) || !$this->_name[3] != 'A'))
            // в случаях с последовательностями типа choice вложенные поля имеют только букву
            && $this->_name[0] != 'A'
            // поля, состоящие из цифр, кодовых слов или других кодов, в соответствии со стандартами SWIFT
            // цифры и так не попадают под схему транслитерации, так что проверяем только поля с настроенными кодами
            // НО, не всегда коды сконфигурированы
            && (!isset($this->_scheme['strict']) || empty($this->_scheme['strict']))
            // все поля и подполя сообщения MT940 «Выписка по счету клиента», за исключением подполя 9
            // «Дополнительная информация», поля 61 «Строка движения по счету” и поля 86 «Информация для
            // владельца счета»
            && !$root->getType() != '940' && !in_array($this->_name, ['9', '61', '86'])
            // все поля и подполя сообщения MT 950 «Выписка», за исключением подполя 9 «Дополнительная
            // информация» и поля 61 «Строка движения по счету»
            && !$root->getType() != '950' && !in_array($this->_name, ['9', '61'])
        );
    }

    /**
     * @return void
     */
    public function unsetValue()
    {
        if (!$this->constant) {
            $this->setValue(null);
        }
    }

    /**
     * @return string
     */
    public function getMask()
    {
        return $this->_mask;
    }

    /**
     * @param string $mask
     */
    public function setMask($mask)
    {
        $this->_mask       = $mask;
        $this->_maskScheme = MtMask::generalizeMaskScheme(MtMask::maskScheme($this->_mask));
        $this->relateMaskWithAttributes();
    }

    /**
     * @return array
     */
    public function getMaskScheme()
    {
        return $this->_maskScheme;
    }

    /**
     * @return boolean
     */
    public function getService()
    {
        return $this->_service;
    }

    /**
     * @param boolean $service
     */
    public function setService($service)
    {
        $this->_service = $service;
        if (YII_DEBUG === false) {
            if ($service) {
                $this->invisible = true;
            } else {
                $this->invisible = false;
            }
        }
    }

    /**
     * @param null $form
     * @return null|string
     */
    public function toHtmlForm($form = null)
    {
        if ($this->invisible) {
            return null;
        }

        return Widget::widget([
            'model' => $this,
            'form' => $form,
        ]);
    }

    /**
     * @param string $attribute
     * @param null   $form
     * @param array  $options
     * @return string
     * @throws \Exception
     */
    public function attributeToHtmlForm($attribute, $form = null, $options = [])
    {
        return TagAttribute::widget(
            array_merge(
                [
                    'model' => $this,
                    'attribute' => $attribute,
                    'form' => $form,
                    'disableContainer' => true
                ],
                $options
            )
        );
    }

    /**
     * @inheritdoc
     */
    public function mapArray(&$array)
    {
        $current = current($array);
        if ($current['name'] == $this->_name) {
            /**
             * @warning если не является определенной константой, которую СООТВЕТСТВЕННО нельзя изменить
             */
            if (!$this->constant || trim($current['value']) == $this->_value) {
                $this->setValue($current['value']);
                next($array);

                return true;
            }
        }

        return false;
    }

    /**
     * @return string
     */
    public function toReadable($translitDecode = false)
    {
        $value = $this->getReadableValue($translitDecode);

        if ($value || $this->constant || $this->_service) {
            $linePad = "\t\t";//str_repeat(' ', $this->printableNamePadSize);
            return ' ' . trim($this->name) . "\t:\t" . Yii::t('doc/mt', $this->label)
                    .($value ? self::INLINE_BREAK . $linePad . preg_replace("/([\r\n]{1,2})/m", "$1$linePad", $value) : null
                    )
                    .self::LINE_BREAK;
        }

        return '';
    }

    /**
     * @return string
     */
    public function toPrintable()
    {
        return $this->toReadable();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $value = $this->getValue();

        if ($value || $this->constant || $this->_service) {
            return ':' . $this->name . ':' . $value;
        }

        return '';
    }

    function __clone()
    {
        parent::__clone();
        $this->clearErrors();
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->_attributes)) {
            return $this->_attributes[$name];
        } else {
            return parent::__get($name);
        }
    }

    /**
     * линкует ключит схемы с именами аттрибутов для упрощения последующей работы
     * @todo место для размышлений над рефакторингом
     * @void
     */
    protected function relateMaskWithAttributes()
    {
        if (empty($this->_attributes) || empty($this->_maskScheme)) {
            return;
        }

        $i = 0;
        foreach ($this->_attributes as $k => $v) {
            // если числового ключа нет, значит уже соотнесли
            if (!isset($this->_maskScheme['scheme'][$i])) {
                continue;
            }
            $this->_maskScheme['scheme'][$k] = $this->_maskScheme['scheme'][$i];
            unset($this->_maskScheme['scheme'][$i]);
            $i++;
        }
    }

}