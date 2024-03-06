<?php

namespace console\controllers;

use common\models\Terminal;
use Yii;
use yii\base\Exception;
use yii\console\Controller;
use yii\helpers\Console;

class TerminalController extends Controller
{
    /**
     * Help message
     */
    public function actionIndex() 
    {
        $this->run('/help', ['terminal']);
    }
	
    /**
     * Функция сообщает о текущем статусе автопроцессов.
     * @return int
     */
    public function actionStatus() 
    {
        $autoProcessing = Yii::$app->terminal->autoProcessing;
        $this->stdout(Yii::t('app', 'Terminal status') . "\n", Console::BOLD);

        Console::output($autoProcessing->statusReport());

        $myProcesses = TerminalController::getProcesses();

        if ($myProcesses->allModels && is_array($myProcesses->allModels) && count($myProcesses->allModels) > 0) {
            Console::output('Autoprocesses list');
            foreach($myProcesses->allModels as $index => $process) {
                Console::output("Идентификатор: {$process['PID']} | Пользователь: {$process['UID']} | Запущен: {$process['STIME']} | Команда: {$process['CMD']}");
            }
        } else {
            Console::output(Yii::t('app', 'No Autoprocesses found'));
        }

        return Controller::EXIT_CODE_NORMAL;		
    }
	
    /**
     * Функция пытается восстановить терминал из нештатного состояния после сбоя
     * @return int
     */
    public function actionRecovery() 
    {
        $autoProcessing = Yii::$app->terminal->autoProcessing;
        $this->stdout(Yii::t('app', 'Recovering') . "\n", Console::BOLD);

        try {
            $autoProcessing->recovery();
        }  catch (Exception $ex) {
            Console::error(Yii::t('app', 'Error: {message}', ['message' => $ex->getMessage()]));
        }
        $this->actionStatus();

        return Controller::EXIT_CODE_NORMAL;
    }

    /**
     * @param $privateKey path to private key
     * @param $certificate path to certificate
     * @param $publicKey path to public key
     * @return int
     */
    public function actionRegisterKeyAttributes($privateKey, $certificate, $publicKey)
    {
        if (!is_file($privateKey)) {
            Console::error(Yii::t('app', 'Error: {message}', ['message' => "File $privateKey not found"]));
            return Controller::EXIT_CODE_ERROR;
        }
        if (!is_file($certificate)) {
            Console::error(Yii::t('app', 'Error: {message}', ['message' => "File $certificate not found"]));
            return Controller::EXIT_CODE_ERROR;
        }
        if (!is_file($publicKey)) {
            Console::error(Yii::t('app', 'Error: {message}', ['message' => "File $publicKey not found"]));
            return Controller::EXIT_CODE_ERROR;
        }

        try {
            // Сохранить модель в БД
            Yii::$app->terminal->autobot
                ->registerKeyAttributes($privateKey, $certificate, $publicKey)
                ->save();
        } catch (Exception $ex) {
            Console::error(Yii::t('app', 'Error: {message}', ['message' => $ex->getMessage()]));
        }

        return Controller::EXIT_CODE_NORMAL;
    }

    public function actionExportCsv($fileName)
    {
        $terminals = Terminal::findAll(['status' => Terminal::STATUS_ACTIVE]);

        if (!count($terminals)) {
            Console::output('No active terminals');
            return Controller::EXIT_CODE_NORMAL;
        }

        $csv = fopen($fileName, 'w');

        if (!$csv) {
            Console::error('Cannot open output file: ' . $fileName);
            return Controller::EXIT_CODE_ERROR;
        }

        fputs($csv, "id; terminalId; title; isDefault\n");

        foreach($terminals as $terminal) {
            fputs($csv,
                $terminal->id
                . '; ' . $terminal->terminalId
                . '; ' . $terminal->title
                . '; ' . $terminal->isDefault
                . "\n"
            );
        }

        fclose($csv);
        Console::output('Active terminals are exported to file ' . $fileName);

        return Controller::EXIT_CODE_NORMAL;

    }

}
