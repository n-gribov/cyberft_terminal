<?php

namespace addons\swiftfin\models\events;

use common\base\BaseEvent;

abstract class Event extends BaseEvent
{
	protected $_documentId;
	
	public function setDocumentId($id)
	{
		$this->_documentId = $id;
	}

	public function getDocumentId()
	{
		return $this->_documentId;
	}	
	
	/**
	 * Возвращает данные в неком читабельном виде
	 * @return string
	 */
	public function __toString()
	{
		$result = 
			"Swiftfin document #{$this->_documentId}: Event \'{$this->_code}\' on {$this->_date}, event data: {$this->serialize()}";

		return $result;
	}

}
