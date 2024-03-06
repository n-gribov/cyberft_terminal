<?php

namespace common\modules\transport\models\events;

use common\base\BaseEvent;
use Exception;
use Yii;

abstract class Event extends BaseEvent
{
	protected $_messageId;
	
	public function setMessageId($id)
	{
		$this->_messageId = $id;
	}

	public function getMessageId()
	{
		return $this->_messageId;
	}	
	
	/**
	 * Возвращает данные в неком читабельном виде
	 * @return string
	 */
	public function __toString()
	{
		try {
			$result = Yii::t('app/message',
				'Message id {messageId}: Event \'{code}\' on {date}, event data: {eventData}',
				[
					'messageId' => $this->_messageId, 
					'code' => $this->_code,
					'date' => $this->_date,
					'eventData' => $this->serialize()
				]
			);
		} catch(Exception $e) {
			$result = $e->getMessage();
		}

		return $result;
	}

}
