<?php

namespace common\components;

use Resque as LibResque;
use Resque_Job;
use Resque_Job_Status as LibResqueJobStatus;
use ResqueScheduler as LibResqueScheduler;
use yii\base\Component;
use common\models\ProfilingData;
use common\models\ProfilingLink;

class Resque extends Component
{
    const DEFAULT_QUEUE = 'default';
    const REGULAR_QUEUE = 'regular';
    const SERVICE_QUEUE = 'service';
    const INCOMING_QUEUE = 'incoming';
    const OUTGOING_QUEUE = 'outgoing';

    public $redisLocation = 'localhost:6379';
    public $redisDataBase = 0;

    public function init()
    {
        LibResque::setBackend($this->redisLocation, $this->redisDataBase);
    }

    public function info()
    {
        $queues = LibResque::queues();

        $qStat = [];
        foreach ($queues as $queue) {
            $qData = LibResque::redis()->lrange('   queue:' . $queue, -100, -1);

            foreach ($qData as $payload) {
                $qStat['queued'][$queue][] = new Resque_Job($queue, json_decode($payload, true));
            }
        }

        $failedJobs = LibResque::redis()->lrange('failed', -100, -1);
        foreach ($failedJobs as $failure) {
            $qStat['failed'][] = json_decode($failure, true);
        }

        if (isset($qStat['failed'])) {
            krsort($qStat['failed']);
        }

        return $qStat;
    }

    /**
     *
     * @param type $items
     * @param type $queue
     */
    public function dequeue($items, $queue = self::DEFAULT_QUEUE)
    {
        LibResque::dequeue($queue, $items);
    }

    /**
     *
     * @param string $class
     * @param array|null $args
     * @param bool $trackStatus
     * @param string $queue
     * @return string|boolean
     */
    public function enqueue($class, $args = null, $trackStatus = false, $queue = self::DEFAULT_QUEUE)
    {
        return LibResque::enqueue($queue, $class, $args, $trackStatus);
    }

    /**
     *
     * @param type $seconds
     * @param type $class
     * @param type $args
     * @param type $queue
     */
    public function enqueueIn($seconds, $class, $args = null, $queue = self::DEFAULT_QUEUE)
    {
        LibResqueScheduler::enqueueIn($seconds, $queue, $class, $args);
    }

    /**
     *
     * @param type $time
     * @param type $class
     * @param type $args
     * @param type $queue
     */
    public function enqueueAt($time, $class, $args = null, $queue = self::DEFAULT_QUEUE)
    {
        LibResqueScheduler::enqueueAt($time, $queue, $class, $args);
    }

    /**
     *
     * @param type $token
     * @return type
     */
    public function getJobStatus($token)
    {
        $status = new LibResqueJobStatus($token);
        return $status->get();
    }

    /**
     *
     * @param type $token
     * @return type
     */
    public function getJobStatusLiteral($token)
    {
        $status = $this->getJobStatus($token);

        switch ($status) {
            case LibResqueJobStatus::STATUS_WAITING:
                return 'waiting';
            case LibResqueJobStatus::STATUS_RUNNING:
                return 'running';
            case LibResqueJobStatus::STATUS_COMPLETE:
                return 'complete';
            case LibResqueJobStatus::STATUS_FAILED:
                return 'failed';
        }

        return 'unknown (' . $status . ')';
    }

    /**
     * Stop tracking job status
     *
     * @param type $token
     */
    public function stopTracking($token)
    {
        $status = new LibResqueJobStatus($token);
        $status->stop();
    }

    public function getStatusData($token)
    {
        $status = new LibResqueJobStatus($token);
        if ($status->isTracking()) {
            return json_decode(LibResque::redis()->get((string)$status), true);
        }

        return null;
    }

    /**
     * Check if job is in actual status
     *
     * @param type $token
     * @return bool
     */
    public function isActualJob($token)
    {
        return in_array(
            $this->getJobStatus($token),
            [
                LibResqueJobStatus::STATUS_RUNNING,
                LibResqueJobStatus::STATUS_WAITING,
            ]
        );
    }

    public function startProfiling($jobId, $jobName = null)
    {
        $startTime = microtime(true);
        $data = [
            'startTime' => $startTime,
            'memoryUsageStart' => memory_get_usage(),
            'jobName' => $jobName,
            'jobId' => $jobId
        ];
        $model = new ProfilingData();
        $model->setAttributes($data);
        // Сохранить модель в БД
        $model->save();
    }

    public function endProfiling($jobId)
    {
        $model = ProfilingData::getProfilingByJobId($jobId);
        $endTime = microtime(true);
        $time = $endTime - $model->startTime;
        $model->setAttributes(['time' => round($time, 3), 'endTime' => $endTime, 'memoryUsageEnd' => memory_get_usage()]);
        // Сохранить модель в БД
        $model->save();
    }

    public function linkProfiling($entity, $entityId, $jobId)
    {
        $profilingLink = new ProfilingLink(['entity' => $entity, 'entityId' => $entityId, 'jobId' => $jobId]);
        // Сохранить модель в БД
        $profilingLink->save();
    }

}