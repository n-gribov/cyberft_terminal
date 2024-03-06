<?php
$template = $data['template'];
// Вывести страницу настроек автоподписанта
echo $this->render('@common/modules/autobot/views/settings/' . $template, ['data' => $data]);
