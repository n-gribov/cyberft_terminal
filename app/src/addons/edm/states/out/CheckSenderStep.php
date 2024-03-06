<?php

namespace addons\edm\states\out;

use common\components\TerminalId;
use common\states\BaseDocumentStep;
use Yii;
use yii\base\ErrorException;

class CheckSenderStep extends BaseDocumentStep
{
    public $name = 'checkSender';

    public function run()
    {
        $sender = $this->state->sender;

    	try {
			// Выясняем, какому участнику принадлежит терминал, от лица которого отсылается документ
			if (($extracted = TerminalId::extract($sender))) {
				$participant = $extracted->participantId;
			} else {
				throw new ErrorException("No participant exists for sender {$sender}");
			}

			// В цикле ищем соответствие участника терминалам, указанным в конфигурации терминала
			foreach (Yii::$app->terminals->addresses as $terminalId) {
				if (($extracted = TerminalId::extract($terminalId))) {
					$configuredParticipant = $extracted->participantId;
				} else {
					throw new ErrorException("No participant exists for Terminal configuration {$terminalId}");
				}
				// Соответствие есть.
				if ($participant == $configuredParticipant) {
					return true;
				}
			}
			// Сюда попадаем, если нет соответствий в конфиге, и мы не можем отсылать
			// "не свои" документы
			throw new ErrorException("Illegal document sender {$sender}");
		} catch(ErrorException $ex) {
            $this->log('Error while checking sender: ' . $ex->getMessage());
        }

        return false;
    }

}