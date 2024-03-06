#!/usr/bin/env php
<?php
require(__DIR__ . '/../app-include.php');

try {
    new yii\console\Application($config);
} catch (Exception $ex) {
    echo 'Error on app init: ' . $ex->getMessage() . "\n";

    return;
}

$settings = Yii::$app->settings->get('app');

if (!$settings->jobsEnabled) {
    return;
}

$jobsList = Yii::$app->registry->getRegularJobs();

if (empty($jobsList)) {
    return;
}

foreach ($jobsList as $job => $descriptors) {
    foreach ($descriptors as $descriptor => $jobParams) {
        $jobData = Yii::$app->registry->getRegularJobData($job, $descriptor);
        if (!empty($jobData['token'])) {
            if (Yii::$app->resque->isActualJob($jobData['token'])) {
                $jobStatusData = Yii::$app->resque->getStatusData($jobData['token']);
                $delta         = time() - $jobStatusData['updated'];
                if ($delta > $job::MAX_RUNTIME) {
                    Yii::warning(
                        "Terminating job {$job} with uptime " . gmdate('H:i:s', $delta), 'system'
                    );
                    $jobStatusData = Yii::$app->resque->stopTracking($jobData['token']);
                } else {
                    // Skip iteration for this job
                    continue;
                }
            }
        }

        if (is_callable($jobParams['interval'])) {
            $callback = $jobParams['interval'];
            $intervalDescr = $callback();
        } else {
            $intervalDescr = $jobParams['interval'];
        }

        if (strpos($intervalDescr, ':')) {
            $timeToStartJob = false;
            preg_match("/(?P<time>\d+:\d+) (?P<type>\w+) (?P<period>[\*\w+])/",
                $intervalDescr, $interval);

            if (isset($interval['time'])) {
                $dispatcher_interval = (isset($argv[1])) ? ($argv[1] - round($argv[1] / 2)) : 0;
                $cur_time = time();
                $testTime = strtotime($interval['time']);
                $timeToStartJob = (
                    ($testTime - $dispatcher_interval <= $cur_time)
                    && ($testTime + $dispatcher_interval > $cur_time)
                );

                if ($timeToStartJob) {
                    $period = $interval['period'];
                    switch ($interval['type']) {
                        case 'weekday' :
                            if ($period != '*' && $period !== date('w')) {
                                $timeToStartJob = false;
                            }
                            break;

                        case 'monthday' :
                            if ($period !== date('j')) {
                                $timeToStartJob = false;
                            }
                    }
                }
            }
        } else {
            $interval = !empty($intervalDescr) ? $intervalDescr : 0;
            $nextRunTime = ($jobData['dateStart'] ?: 0) + $interval;

            $shouldRound = ($interval >= 300) && (3600 % $interval === 0);
            if ($shouldRound) {
                $nextRunTime = (int)($nextRunTime / $interval) * $interval;
            }

            $timeToStartJob = $nextRunTime <= time();
        }

        if ($timeToStartJob) {
            $token = Yii::$app->resque->enqueue(
                $jobParams['job'], $jobParams['args'], true, common\components\Resque::REGULAR_QUEUE
            );

            Yii::$app->resque->stopTracking($jobData['token']);
            Yii::$app->registry->storeRegularJobData($job, $descriptor, ['token' => $token]);
        }
    }
}