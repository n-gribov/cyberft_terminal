<?php

namespace addons\swiftfin\components;

use addons\swiftfin\models\documents\mt\MtUnknown;
use addons\swiftfin\models\documents\mt\MtXXXDocument;
use Yii;
use yii\base\Component;
use yii\base\Exception;

/**
 * Class MtDispatcher
 *
 * @package addons\swiftfin\components
 */
class MtDispatcher extends Component
{
    const DEFAULT_FIELD = 'textInput';
    /**
     * @var array
     */
    public $_rules = [];

    public function rules($type)
    {
        if (is_string($this->_rules[$type]) && is_file($this->_rules[$type])) {
            $this->_rules[$type] = include ($this->_rules[$type]);
        }
        if (isset($this->_rules[$type]['scheme'])
            && is_string($this->_rules[$type]['scheme'])
            && is_file($this->_rules[$type]['scheme'])
        ) {
            $this->_rules[$type]['scheme'] = include ($this->_rules[$type]['scheme']);
        }

        return $this->_rules[$type];
    }

    /**
     * @param string $type
     * @param array $options
     * @return MtXXXDocument
     * @throws Exception
     */
    public function instantiateMt($type, $options = [])
    {
        if (empty($type) || !array_key_exists($type, $this->_rules)) {
            // Создаем неизвестный документ
            $unknownMt = new MtUnknown([]);
            return $unknownMt;
        }

        $rules = $this->rules($type);
        if (!isset($rules['label'])) {
            $rules['label'] = $this->typeLabel($type);
        }
        if (isset($rules['class'])) {
            $class = $rules['class'];
            unset($rules['class']);
            $rules['type'] = (isset($rules['type']) && $rules['type']?$rules['type']:$type);
            $rules = array_merge($rules, $options);

            return new $class($rules);
        } else {
            $mt        = new MtXXXDocument();
            $mt->label = $this->typeLabel($type);
            $mt->formable = (isset($rules['formable'])?$rules['formable']:false);

            return $mt->setType($type);
        }
    }

    /**
     * @param string $type
     * @return mixed
     * @throws Exception
     */
    public function getViewPath($type)
    {
        if (!array_key_exists($type, $this->_rules)) {
            throw new Exception('Unconfigured mt document type');
        }

        $rules = $this->rules($type);
        if (isset($rules['view'])) {
            return $rules['view'];
        }

        return $this->_rules['default']['view'];
    }

    /**
     * @return array
     */
    public function getTypes()
    {
        return array_keys($this->_rules);
    }

    /**
     * @return array
     */
    public function getTypesLabels()
    {
        $res = [];
        foreach ($this->_rules as $k => $v) {
            if ($k === 'default' || (isset($v['hidden']) && true === $v['hidden'])) {
                continue;
            }
            $res[$k] = $this->typeLabel($k);
            if (!empty($v)) {
                $res[$k] .= '*';
            }
        }

        return $res;
    }

    /**
     * @param $type
     * @return null|string
     */
    public function typeLabel($type)
    {
        $rules = &$this->_rules[$type];
        if (isset($rules['label'])) {
            return isset($rules['label']) ? $rules['label'] : null;
        }
        
        return Yii::t('doc/mt', 'MT' . $type);
    }

    /**
     * @param array $type
     * @return mixed
     */
    public function getTags($type)
    {
	return $this->rules($type)['tags'];
    }

    /**
     * @param string $type
     * @return array
     * @throws Exception
     */
    public function attributeTags($type)
    {
        $rules = $this->rules($type);
        if (!isset($rules['tags'])) {
            return [];
        }

        $arr = [];
        foreach ($rules['tags'] as $k => $v) {
            if (is_array($v) && isset($v['attribute'])) {
                $arr[(string)$v['attribute']] = $k;
            } else if (is_array($v) && !isset($v['attribute'])){
                $arr[$this->getAttributeNameFromTag($k)] = $k;
            } else if (is_string($v)) {
                $arr[(string)$v] = $k;
            } else if (is_numeric($k)) {
                $arr[(string)$v] = $v;
            } else {
                throw new Exception('Wrong tags configuration for '.$k);
            }
        }

        return $arr;
    }

    /**
     * @param string $type
     * @return array
     * @throws Exception
     */
    public function attributes($type)
    {
        $rules = $this->rules($type);
        if (!isset($rules['tags'])) {
            return [];
        }

        $arr = [];
        foreach ($rules['tags'] as $k => $v) {
            if (is_array($v) && isset($v['attribute'])) {
                $arr[] = $v['attribute'];
            } else if (is_array($v) && !isset($v['attribute'])){
                $arr[] = $this->getAttributeNameFromTag($k);
            } else if (is_string($v)) {
                $arr[] = $v;
            } else {
                throw new Exception('Wrong tags configuration for '.$k);
            }
        }

        return $arr;
    }

    /**
     * @param string $type
     * @return array
     * @throws Exception
     */
    public function attributeLabels($type)
    {
        $rules = $this->rules($type);
        if (!isset($rules)) {
            throw new Exception('Unconfigured mt document type ' . var_export($type));
        }

        if (!isset($rules['tags'])) {
            return [];
        }

        $arr = [];
        foreach ($rules['tags'] as $k => $v) {
            if (is_array($v)) {
                if (isset($v['attribute'])) {
                    $attribute = $v['attribute'];
                } else {
                    $attribute = $this->getAttributeNameFromTag($k);
                }
                if (isset($v['label'])) {
                    $arr[$attribute] = $v['label'];
                } else {
                    $arr[$attribute] = $attribute;
                }
            } else if (is_string($v)) {
                $arr[] = $v;
            } else {
                throw new Exception('Wrong tags configuration for '.$k);
            }
        }

        return $arr;
    }

    /**
     * @param string $type
     * @return array
     * @throws Exception
     */
    public function attributeFields($type)
    {
        $rules = $this->rules($type);
        if (!isset($rules)) {
            throw new Exception('Unconfigured mt document type ' . var_export($type));
        }

        if (!isset($rules['tags'])) {
            return [];
        }

        $arr = [];
        foreach ($rules['tags'] as $k => $v) {
            if (is_array($v)) {
                if (isset($v['attribute'])) {
                    $attribute = $v['attribute'];
                } else {
                    $attribute = $this->getAttributeNameFromTag($k);
                }
                if (isset($v['field'])) {
                    $arr[$attribute] = $v['field'];
                } else {
                    $arr[$attribute] = self::DEFAULT_FIELD;
                }
            } else if (is_string($v)) {
                $arr[$k] = self::DEFAULT_FIELD;
            } else {
                throw new Exception('Wrong tags configuration for '.$k);
            }
        }

        return $arr;
    }

    /**
     * @return array
     */
    public function getRules()
    {
        return $this->_rules;
    }

    /**
     * @param array $rules
     */
    public function setRules($rules)
    {
        $this->_rules = $rules;
    }

    protected function getAttributeNameFromTag($tag)
    {
        // такой изврат, т.к. BaseHtml::getInputName запрещает использовать цифры в именовании полей
        $mask = 'abcdefghklmn';
        $len = strlen($tag);
        for ($i = 0; $i < $len; $i++) {
            if (is_numeric($tag[$i])) {
                $tag[$i] = $mask[$tag[$i]];
            }
        }

        return 'tag' . $tag;
    }

    /**
     * @return array
     */
    public function getRegisteredTypes()
    {
        $types = [];

        // Собираем все типы MT-документов, перечисленные в конфиге
        foreach (array_keys($this->_rules) as $type) {
            if ('default' === $type) {
                continue;
            }
            $typeConfig = $this->rules($type);
            $label = 'MT' . strval($type);
            $types[$label] = [
                'contentClass' => '\addons\swiftfin\models\SwiftFinCyberXmlContent',
                'extModelClass' => '\addons\swiftfin\models\SwiftFinDocumentExt',
                'typeModelClass' => '\addons\swiftfin\models\SwiftFinType',
                // Дополнительные элементы для view SWIFTFIN-документа
                'dataViews' => $typeConfig['dataViews'] ?? [],
            ];
        }

        return $types;
    }
}
