<?php

namespace common\components;

use Yii;
use yii\helpers\Inflector;

class Settings
{
    const DEFAULT_SETTINGS_NAMESPACE = 'settings\\';

    protected $_settingsModels = [];
    protected $_defaultTerminalId = null;

    public function get($code, $terminalId = null)
    {
        $savedTerminal = $terminalId ? $terminalId : 'default';

        if (!isset($this->_settingsModels[$code][$savedTerminal])) {
            $className = $this->inflectSettingsClassCode($code);
            $this->_settingsModels[$code][$savedTerminal] = new $className(['terminalId' => $terminalId]);
        }

        return $this->_settingsModels[$code][$savedTerminal];
    }

    public function getVolatile($code, $terminalId = null)
    {
        $savedTerminal = $terminalId ? $terminalId : 'default';

        $className = $this->inflectSettingsClassCode($code);
        $this->_settingsModels[$code][$savedTerminal] = new $className(['terminalId' => $terminalId]);

        return $this->_settingsModels[$code][$savedTerminal];
    }

    protected function inflectSettingsClassCode($code)
    {
        $moduleNamespace = $className = '';

        $codeParts = explode(':', $code);
        $className = Inflector::camelize(array_pop($codeParts)) . 'Settings';

        if (!empty($codeParts)) {
            $reflection = new \ReflectionClass(Yii::$app->getModule(array_pop($codeParts)));
            $moduleNamespace = $reflection->getNamespaceName() . '\\';
        }

        return (empty($moduleNamespace) ? 'common\\' : $moduleNamespace)
                        . static::DEFAULT_SETTINGS_NAMESPACE . $className;
    }

}