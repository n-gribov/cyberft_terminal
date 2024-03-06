<?php

namespace addons\swiftfin\models\documents\mt;

use addons\swiftfin\models\documents\mt\mtUniversal\Sequence;
use addons\swiftfin\models\documents\mt\validator\MtExternalValidator;
use Yii;
use yii\base\Exception;
use yii\widgets\ActiveForm;

class MtUniversalDocument extends Sequence implements MtInterface
{
    /**
     * @var string
     */
    public $label;

    /**
     * @var boolean
     */
    public $formable;

    /**
     * Пометка, что документ не должен отображаться в интерфейсах системы
     * @var bool
     */
    public $hidden        = false;

    /**
     * @var boolean
     */
    public $textEdit      = true;

    /**
     * @var string
     */
    public $view;

    /**
     * @var array Дополнения к стандартному view
     */
    public $dataViews     = [];

    /**
     * В некотороых случаях может потребоваться иметь быстрый доступ по имени к секции, тэгу или атрибуту тэга
     * из дерева объектов описывающих документ
     * Пример:
     * [
     *     'someAlias' => [SectionName1, tag1, attribute1]
     *     'someAlias2' => [SectionName2, SubSectionName2, tag2, attribute2]
     * ]
     * @var array
     */
    public $aliases;

    /**
     * Максимальная допустимая длина тела документа
     * @var int
     */
    public $maxBodyLength = 10000;

    /**
     * Массив правил сети индексированный по коду ошибки
     * см. интерпретацию в MtExternalValidator
     * @todo можно перенести валидацию в "колбэки" данного конфига и уйти от перловой валидации
     * @var array
     */
    public $networkRules;

    /**
     * данные установленные ранее через setBody
     * @var string
     */
    protected $_rawBody;

    /**
     * данные разобранные при чтении тела документа из parse
     * @var array
     */
    protected $rawParsed  = [];

    /**
     * ссылка на владельца инкапсулирующего работу с MT документом
     * возможно не очень удачное решение, но добавлено в контексте задачи CYB-1475
     * пример взимодействия см. в MtUniversal300
     * @var MtOwnerInterface
     */
    public $owner;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['body', 'required'],
            ['body', 'string', 'length' => [16, $this->maxBodyLength]],
            [
                'body', MtExternalValidator::className(),
                'type' => $this->getType(), 'ruleMessages' => $this->networkRules
            ]
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'data' => Yii::t('doc/mt', 'Document body'), /** @deprecated */
            'body' => Yii::t('doc/mt', 'Document body'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function __get($name)
    {
        if (isset($this->aliases[$name])) {
            if (!is_array($this->aliases[$name]) || empty($this->aliases[$name])) {
                throw new Exception('Broken alias scheme '.$name.'. Alias must have array with structure path to variable');
            }
            return $this->getValueByPath($this->aliases[$name]);
        }

        return parent::__get($name);
    }

    /**
     * @inheritdoc
     */
    public function __isset($name)
    {
        if (isset($this->aliases[$name])) {
            return true;
        }

        return parent::__isset($name);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'mt';
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return (string) $this;
    }

    /**
     * Отдаем неизмененные данные установленные ранее через setBody
     * @return string
     */
    public function getRawBody()
    {
        return $this->_rawBody;
    }

    /**
     * @return string
     */
    public function getBodyReadable()
    {
        $translit = $this->translitNeeded();
        $result   = '';
        foreach ($this->_model as $v) {
            $result .= $v->toReadable($translit);
        }

        return $result;
    }

    public function getTags()
    {
        $out = [];
        foreach ($this->_model as $v) {
            $out[$v->name] = $v->toPrintable(false);
        }

        return $out;
    }

    /**
     * @return string
     */
    public function getBodyPrintable()
    {
        $translit = $this->translitNeeded();
        $result   = '';
        foreach ($this->_model as $v) {
            $result .= $v->toPrintable($translit);
        }

        return $result;
    }

    /**
     * Разрешено ли транслитерировать поля данного документа
     * @return bool
     */
    protected function translitNeeded()
    {
        if (
            !in_array($this->getType(), ['101', '103', '202', '900', '910', '940', '950']) && $this->getType()[0] != 'n'
        ) {
            return false;
        }

        $val = $this->getValueByPath(['20'])->getValue();

        return ($val[0] == '+');
    }

    /**
     * @param $value
     * @return $this
     */
    public function setBody($value)
    {
        $this->setRawBody($value);
        $this->parse($value);

        return $this;
    }

    /**
     * @param $value
     * @return $this
     */
    public function setRawBody($value)
    {
        $this->_rawBody = $value;

        return $this;
    }

    /**
     * @deprecated
     */
    public function getData()
    {
        return $this->getBody();
    }

    /**
     * @deprecated
     */
    public function setData($value)
    {
        $this->setBody($value);
    }

    /**
     * @deprecated
     */
    public function getDataReadable()
    {
        return $this->getBodyReadable();
    }

    /**
     * @deprecated
     */
    public function getContentPrintable()
    {
        return $this->getBodyPrintable();
    }

    /**
     * @deprecated
     */
    public function getText()
    {
        return $this->getBody();
    }

    /**
     * @return string
     */
    public function getOperationReference()
    {
        $node = $this->getNode('20');
        if (!empty($node)) {
            if (($value = $node->getValue())) {
                return $value;
            }
        }

        return null;
    }

    /**
     * @param array $data
     * @param null  $formName
     * @return bool
     * @throws \yii\base\Exception
     */
    public function load($data, $formName = null)
    {
        $scope = $formName === null ? $this->formName() : $formName;
        $this->setValue($data[$scope]);
        $this->afterLoad();

        return true;
    }

    protected function hasOwner()
    {
        return isset($this->owner) && ($this->owner instanceof MtOwnerInterface);
    }

    /**
     * Постопроцесс загрузки формы
     * @return $this
     */
    protected function afterLoad()
    {
        return $this;
    }

    /**
     * @param null|ActiveForm $form
     * @return string
     */
    public function toHtmlForm($form = null)
    {
        if (!$form) {
            $form = new \kartik\form\ActiveForm();
        }

        $html = '';
        foreach ($this->_model as $v) {
            $html .= $v->toHtmlForm($form);
        }

        return $html;
    }

    /**
     * Логика парсинга и последующего мапинга:
     * Читаем тэги из документа
     * Массив прочитанных тэгов по ссылке передаем по всей структуре запрашивая маппинг у каждого элемента
     * каждый узел структуры пытается замэпить тэг, в случае успеха возвращает true и смещает указатель массива далее
     * В случае ошибки на уровнь самого документа у нас вернется false
     * и массив с указателем на том элементе, на котором не удалось произвести парсинг
     *
     * @param $string
     * @return $this
     * @throws Exception
     */
    protected function parse($string)
    {
        if (!$string) {
            return null;
        }

        // едрить колотить, делаем дубовее, одной регуляркой не выходит каменный цветок
        // @todo можно заменить одной регуляркой, см. git history, но нужно дебажить
        $match = preg_split("/\n:/", trim($string, ':'));
        $c     = count($match);
        if ($c > 0) {
            // раскладываем
            $parsed = [];
            for ($i = 0; $i < $c; $i++) {
                if (preg_match('/(?P<name>[0-9]{2}[A-Z]?)\:(?P<value>.*)/s', $match[$i], $found)) {
                    $parsed[] = [
                        'name' => $found['name'],
                        'value' => $found['value'],
                    ];
                } else {
                    $this->addError('body', Yii::t('doc/mt', 'Broken tag name for {string}', ['string' => $match[$i]]));
                }
            }

            // мапим
            foreach ($this->_model as $item) {
                $item->mapArray($parsed);
            }

            $current = current($parsed);
            $cKey    = key($parsed);
            //$last    = end($parsed);
            $lKey    = key($parsed);
            if ($current && $cKey != $lKey) {
                $this->addError(
                    'body',
                    'Can\'t parse body, parsing is ended in step ' . $cKey
                    . ': tag ' . $current['name'] . ' with value ' . $current['value']
                );
            }
        } else {
            throw new Exception('Broken data format');
        }
    }

    public function __toString()
    {
        return trim(parent::__toString());
    }

    public function parseForTags()
    {
        $parsed = [];

        if (!$this->_rawBody) {
            return $parsed;
        }

        $match = preg_split("/\n:/", trim($this->_rawBody, ':'));
        $c = count($match);
        if ($c > 0) {
            // раскладываем
            $parsed = [];
            for ($i = 0; $i < $c; $i++) {
                if (preg_match('/(?P<name>[0-9]{2}[A-Z]?)\:(?P<value>.*)/s', $match[$i], $found)) {
                    $parsed[] = [
                        'name' => $found['name'],
                        'value' => $found['value'],
                    ];
                } else {
                    $this->addError('body', Yii::t('doc/mt', 'Broken tag name for {string}', ['string' => $match[$i]]));
                }
            }

            $current = current($parsed);
            $cKey    = key($parsed);
            //$last    = end($parsed);
            $lKey    = key($parsed);
            if ($current && $cKey != $lKey) {
                $this->addError(
                    'body',
                    'Can\'t parse body, parsing is ended in step ' . $cKey
                    . ': tag ' . $current['name'] . ' with value ' . $current['value']
                );
            }
        } else {
            throw new Exception('Broken data format');
        }

        return $parsed;
    }

}