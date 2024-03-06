<?php
namespace common\models\cyberxml;

use common\base\XmlBoundModel;

/**
 * Description of CyberXML
 *
 * @property string $rawData
 * @author fuzz
 */
class CyberXmlContent extends XmlBoundModel
{
	/**
	 * Данный тип контента не требует дополнительной проверки,
	 * т.к. охватывается схемой основного документа
	 */
	const XSD_SCHEMA = null;

	const ROOT_ELEMENT = 'RawData';
	const DEFAULT_NS_PREFIX = 'content';
	const DEFAULT_NS_URI = 'http://cyberft.ru/xsd/cftdata.02';
    const DEFAULT_MIME_TYPE = 'application/xml';

    public function init()
    {
        parent::init();
    }

    public function getDocumentData()
	{
		return [];
	}

	public function boundAttributes()
	{
		return [
			'rawData'	=> '//content:RawData'
		];
	}

	public function pushRawData()
	{
		$this->_rootElement->nodeValue = base64_encode($this->_attributes['rawData']);
        $this->_rootElement->setAttribute('encoding', 'base64');
        $this->_rootElement->setAttribute('mimeType', static::DEFAULT_MIME_TYPE);
    }

	public function fetchRawData()
	{
		$this->_attributes['rawData'] = base64_decode($this->_rootElement->nodeValue);
	}

	public function saveXML()
	{
		$this->updateDOM();

		return $this->_dom->saveXML($this->_rootElement);
	}

	public function validateXSD()
	{
		return $this->validateByScheme(static::XSD_SCHEMA);
	}

    public function markDirty()
    {
        $this->_dirtyAttributes[] = true;
    }

}