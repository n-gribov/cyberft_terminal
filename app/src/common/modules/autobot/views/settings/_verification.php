<?php

$template = $data['template'];

echo $this->render('@common/modules/autobot/views/settings/' . $template, ['data' => $data]);

?>

