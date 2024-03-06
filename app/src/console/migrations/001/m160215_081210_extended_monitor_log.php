<?php

use common\modules\monitor\models\MonitorLogAR;
use Psr\Log\LogLevel;
use yii\db\Migration;

class m160215_081210_extended_monitor_log extends Migration
{
    public function up()
    {
        $this->execute('alter table `monitor_log` add column `logLevel` tinyint unsigned not null after `id`');
        $this->execute('alter table `monitor_log` add column `componentId` tinyint unsigned not null after `id`');
        $this->execute('alter table `monitor_log` add column `userId` int unsigned after `id`');
        $this->execute('alter table `monitor_log` add key(`logLevel`)');
        $this->execute('alter table `monitor_log` add key(`componentId`)');
        $this->execute('alter table `monitor_log` add key(`userId`)');

        $this->execute(
                "update `monitor_log` set `logLevel` = " . MonitorLogAR::LOG_LEVEL_INFO
        );

        $this->execute(
                "update `monitor_log` set `logLevel` = " . MonitorLogAR::LOG_LEVEL_ERROR
                . " where `eventCode` in ('documentProcessError', 'cftcpFailed', 'sftpOpenFailed', 'stompFailed')"
        );

        $this->execute(
                "update `monitor_log` set `logLevel` = " . MonitorLogAR::LOG_LEVEL_ALERT
                . " where `eventCode` in ('failedLogin')"
        );

        $this->execute(
                "update `monitor_log` set `componentId` = " . MonitorLogAR::COMPONENT_DOCUMENT
                . " where `eventCode` in (
                    'documentForSigning', 'documentProcessError', 'documentStatusChange',
                    'documentRegistered')"
        );

        $this->execute
        (
                "update `monitor_log` set `componentId` = " . MonitorLogAR::COMPONENT_TRANSPORT
                . " where `eventCode` in ('cftcpFailed', 'sftpOpenFailed', 'stompFailed')"
        );

        $this->execute(
                "update `monitor_log` set `componentId` = " . MonitorLogAR::COMPONENT_USER
                . " where `eventCode` = 'failedLogin'"
        );

        $this->execute(
                "update `monitor_log` set `componentId` = " . MonitorLogAR::COMPONENT_SWIFTFIN
                . ", `eventCode` = substring(`eventCode`, 10) where `eventCode` like 'swiftfin:%'"
        );

    }

    public function down()
    {
        $this->execute(
                "update `monitor_log` set eventCode = concat('swiftfin:', `eventCode`)
                    where `componentId` = " . MonitorLogAR::COMPONENT_SWIFTFIN
        );

        $this->execute('alter table `monitor_log` drop column `logLevel`');
        $this->execute('alter table `monitor_log` drop column `componentId`');
        $this->execute('alter table `monitor_log` drop column `userId`');

        return true;
    }

}
