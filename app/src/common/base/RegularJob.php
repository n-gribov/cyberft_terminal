<?php
namespace common\base;

/**
 * Description of Job
 *
 * @author fuzz
 */
abstract class RegularJob extends Job
{
    const MAX_RUNTIME = 300;

	/**
	 *  TTL задачи в секундах
	 */
	const TTL = 30;

	/**
	 * Интервал перезапуска процесса
	 */
	const INTERVAL = 60;

	public $enableProfiling = false;


}