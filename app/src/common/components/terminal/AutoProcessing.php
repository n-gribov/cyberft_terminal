<?php

namespace common\components\terminal;

use Yii;
use yii\base\Exception;

/**
 * @author fuzz
 */
class AutoProcessing extends RedisModel
{
    const KEY_VARS = 'terminal:autoProcessing';

    /** Configurations */

    /** Attributes */
    public $fixSender = false;
    /**
     * активация маршрутизации документов от ABS в swift
     * @var bool
     */
    public $swiftIsRoute = false;
    /**
     * директория документов от ABS
     * @var string
     */
    public $swiftRoutePath;
    /**
     * @var bool
     */
    public $exportIsActive = false;

    /**
     * @var string
     */
    public $exportExtension = 'swt';
    public $isRunning;
    public $startedAt;

    /**
     * Флаг разрешения/запрета экспорта XML-файлов
     * @var boolean
     */
    public $exportXml = false;

    public function attributeLabels()
    {
        return [
            'fixSender' => Yii::t('app/terminal', 'Automatic correction of sender\'s address'),
            'swiftIsRoute' => Yii::t('app/terminal', 'Activate swift documents routing'),
            'swiftRoutePath' => Yii::t('app/terminal', 'Swift documents are redirected to'),
            'exportIsActive' => Yii::t('app/terminal', 'Export documents in SWIFT FIN format'),
            'exportExtension' => Yii::t('app/terminal', 'File extension for exported files'),
            'exportXml' => Yii::t('app/terminal', 'Activate CyberFT documents export'),
        ];
    }

    public function rules()
    {
        return [
            [['fixSender', 'swiftIsRoute', 'exportIsActive', 'exportXml'], 'boolean'],
            ['swiftRoutePath', 'string', 'max' => 255],
            ['exportExtension', 'string', 'max' => 5],
            ['swiftRoutePath', '\common\validators\PathValidator'],
            ['swiftIsRoute', 'validateDepend']
        ];
    }

    public function validateDepend($attribute, $params)
    {
        $params['dependent'] = 'swiftRoutePath';
        if ($this->$attribute && !$this->{$params['dependent']}) {
            $this->addError($params['dependent'],Yii::t('app/terminal', 'Please, select a path to route SWIFT documents'));
        }
    }


    public function start($params = [])
    {
        $autobot = $this->_terminal->autobot;

        // Проверка пароля приватного ключа
        $autobot->privatePassword = !empty($params['privatePassword']) ? $params['privatePassword'] : null;
        // Сохранить модель в БД
        $autobot->save();

        if (!$autobot->isValidPassword) {
            throw new Exception(Yii::t('app', 'Private key invalid password'));
        }

        $this->isRunning = true;
        $this->startedAt = gmdate('Y-m-d H:i:s');
        // Сохранить модель в БД
        $this->save();

        return true;
    }

    public function stop()
    {
        $this->isRunning = false;
        // Сохранить модель в БД
        $this->save();

        // Далее действуем только, если не активирован режим Легкого восстановления
        if (!Yii::$app->terminal->easyRecoveryMode) {
            // Сбрасываем запомненный пароль контролёра
            $this->_terminal->autobot->privatePassword = null;
            // Сохранить модель в БД
            $this->_terminal->autobot->save();
        }

        return true;
    }

    /**
     * Функция восстанавливает состояние терминала после сбоя.
     * Использует значение флажка easyRecoveryMode для определения режима восстановления
     * - true - терминал восстанавливается и автопроцессы запускаются автоматически
     * - false - терминал приводится к состоянию, в котором возможен дальнейший
     * запуск автопроцессов вручную.
     * @return bool
     * @throws Exception
     */
    public function recovery()
    {
        $this->isRunning = false;
        // Сохранить модель в БД
        $this->save();

        // Далее действуем только, если активирован режим Легкого восстановления
        if (Yii::$app->terminal->easyRecoveryMode) {
            // Запускаем Автопроцессы с сохраненными паролями
            $this->start([
                'privatePassword' => $this->_terminal->autobot->privatePassword,
            ]);
        }

        return true;
    }

    public function statusReport()
    {
        $myStatusReport = '';
        if ($this->isRunning) {
            // $myStatusReport .= Yii::t('app', 'Auto processing is running');
            $myStatusReport .= Yii::t('app', 'Auto processing is running {time}', [
                            'time' => empty($this->startedAt)
                            ? '<strong class="bg-danger">Время неизвестно</strong>'
                            : Yii::$app->formatter->asDatetime($this->startedAt),
                            ]);
        } else {
            $myStatusReport .= Yii::t('app', 'Auto processing is not running');
        }
        $myStatusReport .= "\n";

        return $myStatusReport;
    }
}
