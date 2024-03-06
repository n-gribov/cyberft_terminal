<?php

namespace common\modules\transport\models\events;

use common\modules\transport\models\events\Event;

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
	 * Номер попытки на которой был изменен статус
	 * @var integer
	 */
	public $attempt;
	/**
	 * Идентификатор задания, сменившего статус
	 * @var string
	 */
	public $job;
    /**
     * Дополнительная информация
     * @var string
     */
    public $info;
}