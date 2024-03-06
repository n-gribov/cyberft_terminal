<?php
namespace common\components;

use yii\base\Component;

/**
 * @property string $value               Идентификатор должен иметь 12 символов и использовать только латинские символы;
 * @property string $participantCode     Первые 4 символа: уникальные для Участника, пример CYCP;
 * @property string $countryCode         Следующие 2 символа: страна, для РФ – RU;
 * @property string $sevenSymbol         Седьмой символ: месторасположение или дополнительный символ для уникального названия компании, например M (Москва);
 * @property string $delimiter           для сети CyberFT @, но для более удобной интеграции с другими сетями, может быть всем чем угодно
 * @property string $terminalCode        Девятый символ является кодом терминала.
 * @property string $participantUnitCode Последние 3 символа: подразделения компании.
 *
 * @property string $participantId идентификатор участника
 * @property int $type
 * @package common\components
 */
class TerminalId extends Component
{
    const DEFAULT_DELIMITER     = '@';
    const DEFAULT_TERMINAL_CODE = 'A';
    const TYPE_UNDEFINED   = 0;
    const TYPE_TERMINAL    = 1;
    const TYPE_PARTICIPANT = 2;
    const TYPE_BIC         = 3;

    protected $_attr = [];
    protected $_type = self::TYPE_UNDEFINED;

    /**
     * @param string $value
     * @param TerminalId|null $item
     * @return TerminalId|null
     */
    public static function extract($value, TerminalId $item = null)
    {
        if (
            ! ($res = self::parse($value))
            && !($res = self::extractParticipantId($value, $item))
            && !($res = self::extractBic($value, $item))
        ) {
            return null;
        }

        $item = self::extractByArray($res, $item);

        if ($item->_type === self::TYPE_UNDEFINED) {
            $item->_type = self::TYPE_TERMINAL;
        }

        return $item;
    }

    public static function validate($value)
    {
        return (self::parse($value) !== null);
    }

    /**
     * @param string $value
     * @param TerminalId|null $item
     * @return TerminalId|null
     */
    public static function extractParticipantId($value, TerminalId $item = null)
    {
        $res = self::parse($value, ['terminalCode']);

        if (!$res) {
            return null;
        }

        $item = self::extractByArray($res, $item);
        $item->_type = self::TYPE_PARTICIPANT;

        return $item;
    }

    /**
     * Метод извлекает BIC из адреса терминала
     * @param string $value
     * @param TerminalId|null $item
     * @return TerminalId|null
     */
    public static function extractBic($value, TerminalId $item = null)
    {
        if (!($res = self::parse($value, ['terminalCode', 'participantUnitCode']))) {
            return null;
        }
        $item = self::extractByArray($res, $item);
        $item->_type = self::TYPE_BIC;

        return $item;
    }

    /**
     * Extract 8 letter BIC
     *
     * @param string $name BIC name
     * @return string
     */
    public static function extractBIC8($name)
    {
        return strtolower(substr($name, 0, 8));
    }
    
    /**
     * @param array $ignore
     * @return string
     */
    public static function getMask($ignore = [])
    {
        $scheme = self::getScheme();
        $scheme = array_diff_key($scheme, array_fill_keys($ignore, null));
        $mask   = '/^';
        foreach ($scheme as $k => $v) {
            $mask .= '(?P<'.$k.'>'.$v.')';
        }
        $mask .= '$/i';

        return $mask;
    }

    /**
     * @return string
     */
    public static function getMaskParticipant()
    {
        return self::getMask(['terminalCode']);
    }

    /**
     * @return string
     */
    public static function getMaskBic()
    {
        return self::getMask(['terminalCode', 'participantUnitCode']);
    }

    public function init()
    {
        $this->_attr = array_merge(
            array_fill_keys(array_keys(self::getScheme()), null), $this->_attr
        );
    }

    /**
     * @return integer
     */
    public function getType()
    {
        return $this->_type;
    }

    /**
     * terminal id value
     *
     * @return string
     */
    public function getValue()
    {
        if (array_search(null, $this->_attr)) {
            return null;
        }

        return implode('', $this->_attr);
    }

    /**
     * @param $value
     */
    public function setValue($value)
    {
        self::extract($value, $this);
    }

    /**
     * @return string
     */
    public function getParticipantId()
    {
        $attr = $this->_attr;
        unset($attr['terminalCode']);

        return implode('', $attr);
    }

    /**
     * @return string
     */
    public function getBic()
    {
        $attr = $this->_attr;
        unset($attr['terminalCode']);
        unset($attr['participantUnitCode']);

        return implode('', $attr);
    }

    /**
     * Схема формирования идентификатора
     *
     * @return array
     */
    protected static function getScheme()
    {
        return [
            'participantCode' => '[A-z0-9]{4}',
            'countryCode' => '([A-z0-9]{2})',
            'sevenSymbol' => '[A-z0-9]{1}',
            'delimiter' => '[A-z0-9\\' . self::DEFAULT_DELIMITER . ']{1}',
            'terminalCode' => '[A-z0-9]{1}',
            'participantUnitCode' => '[A-z0-9]{3}'
        ];
    }

    /**
     * @return array
     */
    public function toArray($removeEmpty = false)
    {
        if ($removeEmpty) {
            return array_diff($this->_attr, ['' => null]); // выбиваем пустые условия
        }

        return $this->_attr;
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->_attr)) {
            return $this->_attr[$name];
        }

        return parent::__get($name);
    }

    public function __set($name, $value)
    {
        if (array_key_exists($name, $this->_attr)) {
            $this->_attr[$name] = $value;
            return;
        }
        return parent::__set($name, $value);
    }

    public function __isset($name)
    {
        if (isset($this->_attr[$name])) {
            return true;
        }

        return parent::__isset($name);
    }

    public function __unset($name)
    {
        if (isset($this->_attr[$name])) {
            $this->_attr[$name] = null;
            return;
        }
        return parent::__unset($name);
    }

    public function __toString()
    {
        return (string) $this->getValue();
    }

    /**
     * @param array           $arr
     * @param TerminalId|null $item
     * @return TerminalId|null
     */
    protected static function extractByArray($arr, TerminalId $item = null)
    {
        if (!$item) {
            $item = new static();
        }

        foreach ($arr as $k => $v) {
            $item->{$k} = $v;
        }

        return $item;
    }

    /**
     * Распаковка terminalId на составляющие
     *
     * @param       $value
     * @param array $ignore
     * @return string|null
     */
    protected static function parse($value, $ignore = [])
    {
        if (preg_match(self::getMask($ignore), $value, $res)) {

            /** убираем числовые члены массива
             * @todo вроде можно убрать числовые члены массива через опции, но не уверен
             */
            foreach ($res as $k => $v) {
                if (is_numeric($k)) {
                    unset($res[$k]);
                }
            }

            return $res;
        }

        return null;
    }
}
