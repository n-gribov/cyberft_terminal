<?php

namespace common\helpers;

class MonitorLogHelper
{
    public static function saveCheckerSettings($checker, $post, $terminalId = null)
    {
        $selection = [];

        if (isset($post['selection'])) {
            $selection = $post['selection'];
        }

        // Подбор настроек
        $data = [];

        $active = in_array($checker->code, $selection);

        $data['active'] = $active;
        $data['terminalId'] = $terminalId;

        if ($active) {
            $data['activeSince'] = time();
        } else {
            $data['activeSince'] = '';
        }

        $data['settings'] = [];

        $dataCode = ucfirst($checker->code . 'Checker');

        if (isset($post[$dataCode])) {
            $data['settings'] = $post[$dataCode];
        }

        $checker->saveSettingsData($data);
    }
}