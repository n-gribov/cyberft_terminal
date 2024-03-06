<?php

namespace common\base;

use common\base\Model;
use DOMDocument;
use DOMXPath;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * @author fuzz
 */
abstract class XmlBoundModel extends Model
{
	const XSD_SCHEMA        = '';
	const XML_VERSION       = '1.0';
	const XML_ENCODING      = 'utf-8';
	const ROOT_ELEMENT      = 'Model';
	const DEFAULT_NS_PREFIX = '';
	const DEFAULT_NS_URI    = 'http://cyberft.ru/xsd/cftmodel.01';

	protected $_dirtyAttributes		= [];
	protected $_attributes			= [];
	/**
	 * @var boolean Флаг, разрешающий при формировании XML создание атрибутов,
	 * для которых в конфигурации указаны значения по умолчанию.
	 * Необходим, чтобы предотвратить появление лишних элементов в XML, загружаемом
	 * из исходного файла или узла.
	 */
	protected $enableDefaults = true;

	/**
	 * @var \DOMDocument
	 */
	protected $_parent;

	/**
	 * @var \DOMDocument
	 */
	protected $_dom;
	protected $_rootElement;
	protected $_schema;

    /**
     * @var \DOMXPath
     */
    protected $_xpath;

	/**
	 * @return DOMDocument
	 */
	public function getDom() {
		return $this->_dom;
	}

	public function init()
	{
		parent::init();
	}

	public function attributes()
	{
		return ArrayHelper::merge(parent::attributes(), array_keys($this->boundAttributes()));
	}

	public function boundAttributes()
	{
		return [];
	}

	public function isBoundAttribute($name)
	{
		return array_key_exists($name, $this->boundAttributes());
	}

	protected function getBoundAttributeXpath($attribute)
	{
		if (!$this->isBoundAttribute($attribute)) {
			return null;
		}

		$bindConfig = $this->boundAttributes()[$attribute];
		if (is_array($bindConfig)) {
			return array_key_exists('xpath', $bindConfig) ? $bindConfig['xpath'] : null;
		}

        return $bindConfig;
	}

	protected function getBoundAttributeDefaultValue($attribute)
	{
		if (!$this->isBoundAttribute($attribute)) {
			return null;
		}

		$bindConfig = $this->boundAttributes()[$attribute];
		if (is_array($bindConfig)) {
			return array_key_exists('default', $bindConfig) ? $bindConfig['default'] : null;
		}

        return null;
	}

	public function __get($name)
	{
		if ($this->isBoundAttribute($name)) {
			$getter = 'get' . $name;
			if (
				isset($this->_attributes[$name])
				|| array_key_exists($name, $this->_attributes)
			) {
				return $this->_attributes[$name];
			} else if (method_exists($this, $getter)) {
				return $this->$getter();
			}

            return null;
		}

        return parent::__get($name);
	}

	public function __set($name, $value)
	{
		if ($this->isBoundAttribute($name)) {
			$this->_dirtyAttributes[$name] = $name;
			$setter  = 'set' . $name;
			if (method_exists($this, $setter)) {
				$this->$setter($value);
			} else {
				$this->_attributes[$name] = $value;
			}
		} else {
			parent::__set($name, $value);
		}
	}

	public function setParent(XmlBoundModel $parent)
	{
		$this->_parent = $parent;
	}


	public function isDirty()
	{
		return !empty($this->_dirtyAttributes);
	}

	public function isDirtyAttribute($name)
	{
		return in_array($name, $this->_dirtyAttributes);
	}

	/**
	 *
	 * @param string $xml xml string data
	 */
	public function loadXml($xml)
	{
		// Запрещаем создание отсутствующих в XML-данных атрибутов, но описанных
		// в конфигурации дочерних моделей
		$this->enableDefaults = false;

		if (!$this->initDOM($xml)) {
            return false;
		}

		$this->fetchBoundAttributes();

		return true;
	}

	public function loadFromNode(\DOMNode $node)
	{
		// Запрещаем создание отсутствующих в XML-данных атрибутов, но описанных
		// в конфигурации дочерних моделей
		$this->enableDefaults = false;
		$this->initDOM();

        //$content = $this->_dom->importNode($node, true);
        //$this->_dom->removeChild($this->_rootElement);
        //$this->_dom->appendChild($content);

        /**
         * Не допустить появления префиксов default при вставке dom
         */

        DOMCyberXml::replaceChild($this->_rootElement, $node->ownerDocument->saveXML($node));

		//$this->_rootElement = $content;
        $this->_rootElement = $this->_dom->documentElement;

		$this->createXpath();

		$this->fetchBoundAttributes();
	}

	protected function fetchBoundAttributes()
	{
		$this->_dirtyAttributes = [];
		foreach (array_keys($this->boundAttributes()) as $attribute) {
			$this->fetchBoundAttribute($attribute);
		}
	}

	protected function fetchBoundAttribute($attribute)
	{
		$fetcher = 'fetch' . $attribute;
		if (method_exists($this, $fetcher)) {
			$this->$fetcher();
		} else {
			// Без фетчера всегда вытягивается первый нод
			$value = $this->getBoundNodeValue($attribute);
			if (!is_null($value)) {
				$this->_attributes[$attribute] = $value;
			}
		}
	}

	/**
	 * Значение нода связанного атрибута
	 * @param string $name имя атрибута
	 * @param int $nodeNumber номер нода (zero based)
	 * @return string содержимое нода
	 */
	public function getBoundNodeValue($name, $nodeNumber = 0)
	{
 		$xpath = $this->getBoundAttributeXpath($name);
        $nodeList = $this->_xpath->query($xpath);

		return ($nodeNumber < $nodeList->length) ? $nodeList->item($nodeNumber)->nodeValue : null;
	}

	protected function pushBoundAttributes($force = false)
	{
		foreach (array_keys($this->boundAttributes()) as $attribute) {
			$this->pushBoundAttribute($attribute, $force);
		}
	}

	protected function pushBoundAttribute($attribute, $force = false)
	{
		$default = $this->getBoundAttributeDefaultValue($attribute);

		if (!$force && !$this->isDirtyAttribute($attribute) && is_null($default)) {
			return;
		}

		/**
		 * CYB-1326 Запрет появления лишних узлов, описанных в конфигурации,
		 * но отсутствующих в DOM
		 */
		if (!$this->enableDefaults && !is_null($default) && !$this->isDirtyAttribute($attribute)) {
			return;
		}

		$pusher = 'push' . $attribute;
		if (method_exists($this, $pusher)) {
			$this->$pusher();
		} else {
			$value = $default;
			if (array_key_exists($attribute, $this->_attributes)) {
				$value = $this->_attributes[$attribute];
			}
			if (!is_null($value)) {
				$this->pushBoundNodeValue($attribute, $value);
			}
		}
	}

	public function pushBoundNodeValue($name, $value, $nodeNumber = 0)
	{
		$xpath = $this->getBoundAttributeXpath($name);
		$nodeList = $this->_xpath->query($xpath);
		if (!$nodeList->length) {
			$node = $this->forceCreateElement($xpath);
		} else {
			$node = $nodeList->item($nodeNumber);
		}

		$node->nodeValue = $value;
	}

	/**
	 * Очень плохая реализация, не учитывает сложные выражения
	 * Предполагается, что для связи с атрибутом модели, выражение должно быть
	 * точным и однозначным
	 *
	 * @param type $xpath
	 */
	protected function forceCreateElement($xpath)
	{
		$parentNode	 = $this->_dom->documentElement;
		$node		 = null;
		foreach (explode('/', $xpath) as $name) {
			$name = preg_replace('/^[[:alnum:]]+\:/', '', $name);

			if (empty($name) || static::ROOT_ELEMENT == $name) {
				continue;
			}

			$nodeList = $parentNode->getElementsByTagName($name);
			if (!$nodeList->length) {
				if ($name[0] == '@') {
					$node = $this->_dom->createAttribute(trim($name, '@'));
				} else {
					$node = $this->_dom->createElementNS(static::DEFAULT_NS_URI, $name);
				}

				$parentNode->appendChild($node);
			} else {
				$node = $nodeList->item(0);
			}

			$parentNode = $nodeList->item(0);
		}

		return $node;
	}

	public function schemaValidate()
	{
		/**
		 * @todo
		 */
		return true;
	}

    protected function createXpath()
    {
		$this->_xpath = new DOMXPath($this->_dom);
		$uri = $this->_dom->documentElement->lookupNamespaceURI(null);

        $this->_xpath->registerNamespace(
            static::DEFAULT_NS_PREFIX,
            $uri ?: static::DEFAULT_NS_URI
		);
    }

	/**
	 *
	 * @param string $xml
	 * @return bool
	 */
	protected function initDOM($xml = null)
	{
		$this->_dom = new DOMDocument(static::XML_VERSION, static::XML_ENCODING);

		if (!is_null($xml)) {
			try {
				$this->_dom->loadXML($xml, LIBXML_PARSEHUGE);
				$this->_rootElement = $this->_dom->documentElement;
			} catch(\Exception $ex) {
				$this->addError('xml',  Yii::t('app', 'Can\'t load XML: {message}', ['message'	=> $ex->getMessage()]));

				return false;
			}
		} else {
			$this->_rootElement = $this->_dom->createElementNS(static::DEFAULT_NS_URI, static::ROOT_ELEMENT);
			$this->_dom->appendChild($this->_rootElement);
		}

        $this->createXpath();

		return true;
	}

	public function updateDOM()
	{
		if (is_null($this->_dom)) {
			$this->initDOM();
		}

		$this->beforeUpdateDOM();
		$this->pushBoundAttributes();
		$this->afterUpdateDOM();
	}

	public function beforeUpdateDOM()
    {

    }

	public function afterUpdateDOM()
    {

    }

	public function getRootElement()
	{
		return $this->_rootElement;
	}

    public function getRootElementName()
    {
        return static::ROOT_ELEMENT;
    }

	public function saveXML()
	{
		$this->updateDOM();

		return $this->_dom->saveXML();
	}

	public function __toString()
	{
		return $this->saveXML();
	}

	public function validateByScheme($schema)
	{
		if (is_null($schema)) {
			return true;
		}

		libxml_use_internal_errors(true);

		$this->updateDOM();

		if ($this->_dom->schemaValidate(Yii::getAlias($schema))) {

			return true;
		}

		$errors = libxml_get_errors();
		$messages = [];
		foreach ($errors as $error) {
			$messages[] = "[{$error->level}] {$error->message}";
		}

		$message = join(PHP_EOL, $messages);
		$this->addError('dom', $message);

		return false;
	}

}