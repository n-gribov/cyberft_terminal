<?php

namespace common\components\terminal;

use common\db\RedisConnection;
use common\helpers\RedisHelper;
use common\models\Terminal;
use common\models\User;
use common\modules\autobot\models\search\AutobotSearch;
use Yii;
use yii\base\Component;
use yii\base\Exception;
use yii\helpers\ArrayHelper;

/**
 * Компонент обслуживает запущенные терминалы и автоматические процессы
 */
class Exchange extends Component
{
    const RUNNING_FLAG = 'isRunning';
    const STARTED_AT = 'startedAt';
    const DEFAULT_TERM_CODE_PATCH = 'X';

    /**
     * Конфигурация терминалов
     * @var array
     */
    public $addresses = [];
    protected $_defaultAddress = '';
    /**
     * @var Terminal
     */
    protected $_defaultTerminal;
    protected $_userTerminal = [];

    /**
     * Ключи для всех терминалов, хранятся в Redis
     * @var array
     */
    protected $terminalData = [];

    /**
     * @var RedisConnection
     */
    private $redis;
    private $_currentTerminalId;

    /**
     * Читает данные по всем зарегистрированным терминалам
     */
    public function init()
    {
        try {
            $terminalsConfig = Terminal::findAll(['status' => Terminal::STATUS_ACTIVE]);
            if (!empty($terminalsConfig)) {
                foreach ($terminalsConfig as $config) {
                    $this->addresses[] = $config->terminalId;

                    if ($config->isDefault) {
                        $this->_defaultAddress = $config->terminalId;
                    }
                }
            }
        } catch (Exception $ex){
            // Отлов ошибки первого запуска приложения
        }

        $this->redis = Yii::$app->redis;
        foreach ($this->addresses as $terminalId) {
            // Читаем данные, ассоциированные с Id терминала
            $this->load($terminalId);
        }
    }

    public function setCurrentTerminalId($terminalId)
    {
        $this->_currentTerminalId = $terminalId;
    }

    public function getCurrentTerminalId()
    {
        return $this->_currentTerminalId;
    }

    public function getProcessingAddress()
    {
        return Yii::$app->settings->get('app')->processing['address'];
    }

    /**
     * Возвращает информацию о терминале $terminalId.
     * Если терминал не существует, то возвращается пустой массив.
     * @param string $terminalId Id терминала
     * @return array
     */
    public function findTerminalData($terminalId)
    {
        if (array_key_exists($terminalId, $this->terminalData)) {
            return $this->terminalData[$terminalId];
        }

        return [];
    }

    /**
     * Функция запоминает информацию о терминале $terminalId
     * @param string $terminalId ID терминала
     * @param array|null $terminalData Настройки терминала
     */
    public function storeTerminalData($terminalId, $terminalData = [])
    {
        $this->terminalData[$terminalId] = $terminalData;
    }

    /**
     * Функция очищает информацию о терминале $terminalId - выполняет сброс
     * флага, метки времени и всех паролей.
     * @param string $terminalId ID терминала
     */
    public function resetTerminalData($terminalId)
    {
        $this->storeTerminalData($terminalId, [
            'isRunning' => false,
            'passwords' => [],
        ]);
    }

    /**
     * Определяет, запущен ли автопроцесс, соответствующий терминалу $terminalId
     * @param string $terminalId Id терминала
     * @return boolean
     */
    public function isRunning($terminalId = null)
    {
        if (empty($terminalId)) {
            $terminalId = $this->getAddress();
        }

        $data = $this->load($terminalId, true);

        return (array_key_exists(static::RUNNING_FLAG, $data) ? boolval($data[static::RUNNING_FLAG]) : false);
    }

    public function getTerminalStatus($terminalId)
    {
        $terminalData = $this->load($terminalId, true);

        if (isset($terminalData[self::RUNNING_FLAG])) {
            return $terminalData;
        }
    }

    /**
     * Возвращает метку времени, определяющую момент запуска автопроцессов.
     * Если произошла ошибка, то возвращается null.
     * @param string $terminalId Id терминала
     * @return mixed
     */
    public function startedAt($terminalId)
    {
        return $this->attributeValue($terminalId, static::STARTED_AT);
    }

    /**
     * Сохраняет в Redis данные по терминалу $terminalId
     * @param string $terminalId
     * @return boolean
     */
    public function save($terminalId)
    {
        $data = $this->findTerminalData($terminalId);

        return $this->redis->set(static::getKeyName($terminalId), json_encode($data));
    }

    /**
     * Функция загружает данные о терминале из Redis по его id
     * @param string $terminalId Id терминала
     */
    public function load($terminalId, $return=false)
    {
        // Читаем данные, ассоциированные с Id терминала
        $data = $this->redis->get(static::getKeyName($terminalId));

        // Если нет данных, соответствующих терминалу, просто выполняем очистку
        if (empty($data)) {
            if (!$return) {
                $this->resetTerminalData($terminalId);
            } else {
                return [];
            }
        } else {
            if (!$return) {
                // Запоминаем декодированные данные
                $this->storeTerminalData($terminalId, json_decode($data, true));
            } else {
                return json_decode($data, true);
            }
        }
    }

    /**
     * Функция проверяет набор паролей для приватных ключей терминала $terminalId,
     * заданный массивом $keys: [autobot_id => 'password',...]
     * @param string $terminalId Id терминала
     * @param array $passwords
     * @return boolean В случае успеха возвращает true, иначе - false.
     */
    public function verifyKeyPasswords($terminalId, $passwords)
    {
        // Ищем все автоботы, соответствующие терминалу
        $models = $this->findAutobots($terminalId);

        // Число найденных моделей и число паролей должно совпадать
        if (count($models) == 0) {
            throw new Exception(Yii::t('app/autobot', 'No keys configured for terminal {terminal}', ['terminal' => $terminalId]));
        }
        // Число найденных моделей и число паролей должно совпадать
        if (count($models) != count($passwords)) {
            throw new Exception(Yii::t('app/autobot', 'Passwords count mismatch for terminal {terminal}', ['terminal' => $terminalId]));
        }
        // Перестраиваем найденные модели так, чтобы их легко было искать по id
        $autobots = [];
        foreach ($models as $model) {
            $autobots[$model->id] = $model;
        }

        // Проверяем каждый из паролей на соответствующей модели
        foreach ($passwords as $id => $password) {
            $result = openssl_pkey_get_private($autobots[$id]->privateKey, $password);
            if (false === $result) {
                throw new Exception(Yii::t('app/autobot', 'Wrong password for key #{id}', ['id' => $id]));
            } else {
                openssl_free_key($result);
            }
        }

        return true;
    }

    /**
     * Функция выполняет запуск автоматических процессов для терминала terminalId.
     * Не выполняет проверку на соответствие паролей ключам.
     * @param string $terminalId Id терминала
     * @param array $passwords Массив паролей [autobot_id => 'password',...]
     * @return boolean
     */
    public function start($terminalId, $passwords)
    {
        // Запоминаем данные терминала (флаг, метку времени и пароли)
        $this->storeTerminalData($terminalId, [
            'isRunning' => true,
            'startedAt' => time(),
            'passwords' => $passwords,
        ]);

        // Сохраняем данные в Redis
        return $this->save($terminalId);
    }

    /**
     * Функция выполняет остановку автоматических процессов для терминала terminalId.
     * @param string $terminalId Id терминала
     * @return boolean
     */
    public function stop($terminalId)
    {
        $terminalData = $this->findTerminalData($terminalId);
        $passwords = $terminalData['passwords'] ?? [];

        $this->storeTerminalData($terminalId, [
            'isRunning' => false,
            'passwords' => $passwords,
        ]);

        // Сохраняем данные в Redis
        return $this->save($terminalId);
    }

    /**
     * Формирует строку - словесное описание статуса автопроцессов терминала $terminalId
     * @param string $terminalId Id терминала
     * @return string
     */
    public function statusReport($terminalId)
    {
        $myStatusReport = '';
        $startedAt = $this->startedAt($terminalId);

        if ($this->isRunning($terminalId)) {
            $myStatusReport .= Yii::t('app/autobot', 'Auto processing for terminal {terminal} is running since {time}', [
                'time' => empty($startedAt)
                    ? '<strong class="bg-danger">[Время неизвестно]</strong>'
                    : Yii::$app->formatter->asDatetime($startedAt),
                'terminal' => $terminalId,
            ]);
        } else {
            $myStatusReport .= Yii::t('app/autobot', 'Auto processing for terminal {terminal} is not running', [
                'terminal' => $terminalId,
            ]);
        }
        
        $myStatusReport .= "\n";

        return $myStatusReport;
    }

    /**
     * Функция возвращает все ключи, зарегистрированные для терминала $terminalId
     * @param type $terminalId
     * @return array Массив моделей Autobot
     */
    public function findAutobots($terminalId)
    {
        // Ищем все автоботы, соответствующие терминалу
        $searchModel = new AutobotSearch();

        return $searchModel->findByTerminalId($terminalId)->getModels();
    }

    public function findAutobotUsedForSigning($terminalId)
    {
        $searchModel = new AutobotSearch();
        return $searchModel->findUsedForSigning($terminalId);
    }

    public function findAutobotsUsedForDecryption($terminalId)
    {
        $searchModel = new AutobotSearch();
        return $searchModel->findUsedForDecryption($terminalId);
    }

    /**
     * Функция возвращает автобота, который используется для подписания
     * @param $terminalId
     * @return static
     */
    public function findUsedForSigningAutobot($terminalId)
    {
        $searchModel = new AutobotSearch();
        return $searchModel->findUsedForSigning($terminalId);
    }

    /**
     * Функция возвращает значение атрибута $attribute для терминала $terminalId.
     * Если произошла ошибка, то возвращается null.
     * @param string $terminalId Id терминала
     * @param string $attribute
     * @return mixed
     */
    protected function attributeValue($terminalId, $attribute)
    {
        $data = $this->findTerminalData($terminalId);
        if (empty($data)) {
            return null;
        }
        if (!array_key_exists($attribute, $data)) {
            return null;
        } else {
            return $data[$attribute];
        }
    }

    /**
     * Возвращает ключ к конфигурации терминала $terminalId
     * @param string $terminalId Id терминала
     * @return string Ключ
     */
    protected static function getKeyName($terminalId)
    {
        return RedisHelper::getKeyName('terminal', $terminalId);
    }

    /**
     * Для совместимости со старым terminal эмулирует свойство address через геттер,
     * возвращающий первый адрес из списка
     * @return string
     */
    public function getAddress()
    {
        return $this->_defaultAddress;
    }

    public function getDefaultTerminal()
    {
        if (is_null($this->_defaultTerminal)) {
            $this->_defaultTerminal = Terminal::getDefaultTerminal();
        }

        return $this->_defaultTerminal;
    }

    public function getDefaultScreenName()
    {
        if (!empty($this->getDefaultTerminal())) {
            return $this->getDefaultTerminal()->screenName;
        }

        return '';
    }

    public function getDefaultTerminalId()
    {
        if (!empty($this->getDefaultTerminal())) {
            return $this->getDefaultTerminal()->terminalId;
        }

        return '';
    }

    public function getTerminalData()
    {
        return $this->terminalData;
    }

    /**
     * Метод получает первый терминал пользователя
     * при использовании мультитерминальности
     *
     * @todo избавиться от адского треша
     */
    public function getPrimaryTerminal($userId = null)
    {
        // Получаем пользователя - либо текущего, либо по id
        if ($userId) {
            $user = User::findOne($userId);
        } else {
            if (!empty(Yii::$app->user) && !empty(Yii::$app->user->identity)) {
                // Получить модель пользователя из активной сессии
                $user = Yii::$app->user->identity;
            } else {
                $user = null;
            }
        }

        // Если пользователь не определен,
        // возвращаем текущий основной терминал
        if (!$user) {
            return Terminal::getDefaultTerminal();
        }

        // Получаем основной терминал терминал пользователя

        // Проверяем, заполнен ли основной терминал пользователя,
        // он активен и находится в списке терминалов пользователя
        if (!empty($user->terminalId)) {
            $userTerminal = Terminal::findOne(['id' => $user->terminalId, 'status' => Terminal::STATUS_ACTIVE]);

            // если такой терминал найден, проверяем его в списке терминалов пользователя
            if ($userTerminal) {

                $userTerminals = $user->getTerminals()->where(['terminalId' => $userTerminal->id])->all();

                if ($userTerminals) {
                    return $userTerminal;
                }
            }
        }

        // Если такой терминал не найден, то ищем среди остальных терминалов пользователя активный терминал

        // Получаем все терминалы пользователя
        $userTerminals = $user->getTerminals()->select('terminalId')->orderBy(['id' => 'ASC'])->asArray()->all();

        // Преобразовываем результаты в односложный массив
        $userTerminals = ArrayHelper::getColumn($userTerminals, 'terminalId');

        // Делаем запрос ко всем терминалам
        // с отбором по терминалам пользователя и ищем активный терминал
        $activeTerminal = Terminal::find()
            ->where(['id' => $userTerminals, 'status' => Terminal::STATUS_ACTIVE])
            ->orderBy(['id' => 'ASC'])
            ->limit(1)
            ->one();

        // Если активный терминал не найден, то возвращаем терминал по умолчанию
        if (!$activeTerminal) {
            return Terminal::getDefaultTerminal();
        } else {
            // Если найден, записываем его пользователю основным и возвращаем
            // Получить модель пользователя из активной сессии
            $userModel = Yii::$app->user->identity;

            if (!$userModel->disableTerminalSelect) {
                $userModel->terminalId = $activeTerminal->id;
                $userModel->save(false, ['terminalId']);
            }

            return $activeTerminal;
        }
    }

}
