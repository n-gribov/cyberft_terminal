<?php

namespace addons\swiftfin\models\events;

use addons\swiftfin\models\events\Event;

/**
 * Event: Сообщение изменило статус
 */

class EventChangeStatus extends Event
{
	/**
	 * Предыдущий статус
	 * @var string
	 */
	public $previousStatus;
	/**
	 * Новый статус
	 * @var string
	 */
	public $status;
	/**
	 * Идентификатор задания, сменившего статус
	 * @var string
	 */
	public $job;
}