<?php

namespace common\modules\monitor\checkers;

use common\base\Model;
use common\models\Terminal;
use common\modules\monitor\models\CheckerAR;
use common\modules\monitor\models\CheckerSettingsAR;
use Yii;
use yii\base\ErrorException;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/**
 * Description of Basechecker
 *
 * @author fuzz
 *
 * @package modules
 * @subpackage monitor
 *
 * @property integer $active Active
 */
abstract class BaseChecker extends Model
{
	const LOG_CATEGORY = 'system';

    /**
     * Iteration interval
     */
    const ITERATION_INTERVAL = 60;

    /**
     * @var string $_code Checker code
     */
    private $_code;

    /**
     * @var integer $_checkTime Checking time
     */
    private $_checkTime;

    private $_checkerId;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->_code = Inflector::camelize(preg_replace('/Checker$/', '',
                    StringHelper::basename($this->className())));
    }

    /**
     * Get checker code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->_code;
    }

    public function getCheckerId()
    {
        return $this->_checkerId;
    }

    /**
     * get checker time
     *
     * @return integer
     */
    public function getCheckTime()
    {
        return $this->_checkTime;
    }

    /**
     * Get iteration interval
     *
     * @return integer
     */
    public function getIterationInterval()
    {
        return static::ITERATION_INTERVAL;
    }

    /**
     * Update checker
     */
    protected function update()
    {
        $checkerData            = $this->getCheckerDataObject(true);
        $checkerData->checkTime = $this->_checkTime       = mktime();
        $checkerData->save();
    }

    protected function check()
    {
        // Получаем список терминалов, по которым надо сформировать сообщения
        $terminals = $this->getCheckerActiveTerminals();

        $results = [];

        foreach($terminals as $terminal) {
            /**
             * Проверка на наличие получателей для чекеров по терминалу
             */
            $settings = $this->getNotificationSettings($terminal);

            try {
                $this->getAddressesList($this->_code, $settings);
            } catch (\ErrorException $ex) {
                Yii::warning($ex->getMessage(), 'regular-jobs');
                $results[] = false;

                continue;
            }

            /**
             * Собираем параметры, нужные для выполнения чекера
             */
            $checkerParams = [];

            $active = $this->isActive($terminal);

            if ($active == false) {
                throw new \Exception($this->_code . ' is not active, but trying to execute');
            }

            $activeSince = $this->getActiveSince($terminal);

            if (is_null($activeSince)) {
                Yii::error($this->_code . ' has got empty activeSince parameter');

                $results[] = false;

                continue;
            }

            $opData = $this->getOpData($terminal);

            $checkerParams['activeSince'] = $activeSince;
            $checkerParams['opData'] = $opData;

            /**
             * Выполнение чекера
             */
            $results[] = $this->checkByTerminalId($terminal, $checkerParams);
        }

        return !in_array(false, $results);
    }

    protected abstract function checkByTerminalId($terminal, $data = []);

    /**
     * Run checker
     *
     * @return boolean
     */
    public function run()
    {
        $result = $this->check();
        $this->update();

        return $result;
    }

    /**
     * Load data
     */
    public function loadData()
    {
        $checkerData = $this->getCheckerDataObject(false);

        if ($checkerData) {
            $this->_checkerId = $checkerData->id;
            $this->_checkTime = $checkerData->checkTime;
        }
    }

    /**
     * Save checker
     *
     * @return boolean
     */
    public function save()
    {
        if (!$this->validate()) {
            return false;
        }

        $checkerData = $this->getCheckerDataObject(true);
        return $checkerData->save();
    }

    /**
     * Get settings
     *
     * @return array
     */
    public function getSettings()
    {
        $settings = $this->attributes;

        return $settings;
    }

    public function saveSettingsData($data)
    {
        $checkerData = $this->getCheckerDataObject(true);
        $checkerData->save();

        $terminalId = isset($data['terminalId']) ? $data['terminalId']: null;

        $checkerSettings = CheckerSettingsAR::findOne(['checkerId' => $checkerData->id, 'terminalId' => $terminalId]);

        if (empty($checkerSettings)) {
            $checkerSettings = new CheckerSettingsAR(['checkerId' => $checkerData->id]);
        }

        $checkerSettings->active = $data['active'];

        // Записываем время активности, только если оно не указано в текущих настройках
        if (!$checkerSettings->activeSince) {
            $checkerSettings->activeSince = $data['activeSince'];
        }

        $checkerSettings->settingsData = $data['settings'];
        $checkerSettings->terminalId = $terminalId;
        $checkerSettings->save();
    }

    /**
     * Set email notify
     *
     * @param array $params Email params
     */
    public function notify($params, $terminalId, $addressList = null)
    {
        try {
            // Получение адресов рассылки по конкретному событию и терминалу
            if ($terminalId) {
                $terminal = Terminal::findOne($terminalId);
                $params['terminalId'] = $terminal->terminalId;
            } else {
                $params['terminalId'] = 'CyberFT Terminal';
            }

            if (is_null($addressList)) {
                $settings = $this->getNotificationSettings($terminalId);
                $addressList = $this->getAddressesList($this->_code, $settings);
            }

        Yii::$app->mailNotifier->sendMessage($params, $addressList);

        } catch (ErrorException $ex) {
            $this->log($ex->getMessage());
        }
    }

    /**
     * Получение настроек оповещения по конкретному терминалу
     * @param $terminalId
     * @return mixed
     */
    protected function getNotificationSettings($terminalId)
    {
        if ($terminalId) {
            $terminal = Terminal::findOne($terminalId);
            $settings = Yii::$app->settings->get('monitor:Notification', $terminal->terminalId);
        } else {
            $settings = Yii::$app->settings->get('monitor:Notification');
        }

        return $settings;
    }

    /**
     * Получение списка получателей по чекеру и терминалу
     * @param $checkerCode
     * @param $settings
     * @param $fullList for backward compatibility: return only emails (false) or full data array (true)
     * @return array
     * @throws ErrorException
     */
    protected function getAddressesList($checkerCode, $settings, $fullList = false)
    {
        $addressList = $settings->addressList;

        if (isset($addressList[$checkerCode]) && count($addressList[$checkerCode]) > 0) {
            return $fullList ? $addressList[$checkerCode] : array_keys($addressList[$checkerCode]);
        } else {
            $terminalTitle = 'CyberFT Terminal';

            if ($settings->terminalId) {
                $terminalTitle = $settings->terminalId;
            }

            throw new ErrorException("{$checkerCode} checker - {$terminalTitle} - empty mailing list");
        }
    }

    /**
     * Get checker data object
     *
     * @param boolean $createIfEmpty Create checker if data is empty
     * @return CheckerAR
     */
    private function getCheckerDataObject($createIfEmpty = false)
    {
        $checkerData = CheckerAR::findOne(['code' => $this->code]);
        if (empty($checkerData) && $createIfEmpty) {
            $checkerData = new CheckerAR(['code' => $this->code]);
        }

        return $checkerData;
    }

    /**
     * Get from row view
     *
     * @return string
     */
    public function getFormRowView()
    {
        return '@common/modules/monitor/views/checkers/default';
    }

    /**
     * Get email params
     *
     * @return array
     */
    public abstract function getParams($data = null);

    /**
     * Get code label
     *
     * @return string
     */
    public function getCodeLabel()
    {
        return $this->code;
    }

	public function log($message)
	{
		$loggedMessage = '[' . StringHelper::basename(get_called_class()) . '] ' . $message;

		Yii::info($loggedMessage, self::LOG_CATEGORY);
	}

    /**
     * Получение дополнительных данных из настроек чекера по терминалу
     * @param $terminalId
     * @return array|mixed
     */
    protected function getOpData($terminalId)
    {
        $data = CheckerSettingsAR::findOne(['checkerId' => $this->checkerId, 'terminalId' => $terminalId]);

        if ($data && !empty($data->opSettings)) {
            return $data->opSettings;
        } else {
            return [];
        }
    }

    /**
     * Запись дополнительных настроек для чекера по терминалу
     * @param $key
     * @param $value
     * @param $terminalId
     */
    protected function setOpData($key, $value, $terminalId)
    {
        $settings = CheckerSettingsAR::findOne(['checkerId' => $this->checkerId, 'terminalId' => $terminalId]);

        $data = [];

        if (!is_null($settings->opSettings)) {
            $data = $settings->opSettings;
        }

        $data[$key] = $value;

        $settings->opSettings = $data;
        $settings->save();
    }

    /**
     * Получение списка терминалов, для которых включен чекер
     * @return array
     */
    private function getCheckerActiveTerminals()
    {
        $terminals = CheckerSettingsAR::find()
            ->select('terminalId')
            ->where(['checkerId' => $this->checkerId, 'active' => 1])
            ->asArray()
            ->orderBy('terminalId ASC')
            ->all();

        return ArrayHelper::getColumn($terminals, 'terminalId');
    }

    protected function getSettingsData($terminalId)
    {
        $data = $this->getCheckerSettingsObject($terminalId);

        if ($data && !empty($data->settingsData)) {
            return $data->settingsData;
        } else {
            return [];
        }
    }

    protected function getCheckerSettingsObject($terminalId)
    {
        return CheckerSettingsAR::findOne(['checkerId' => $this->checkerId, 'terminalId' => $terminalId]);
    }

    /**
     * Проверка, был ли включен данный чекер
     * @param $terminalId
     * @return bool|mixed
     */
    public function isActive($terminalId)
    {
        $object = $this->getCheckerSettingsObject($terminalId);

        if ($object) {
            $active = $object->active;
        } else {
            $active = false;
        }

        return $active;
    }

    /**
     * Получение timestamp момента, с которого включен чекер
     * @param $terminalId
     * @return mixed|null
     */
    public function getActiveSince($terminalId)
    {
        $object = $this->getCheckerSettingsObject($terminalId);
        if ($object) {
            return $object->activeSince;
        } else {
            return null;
        }
    }

}