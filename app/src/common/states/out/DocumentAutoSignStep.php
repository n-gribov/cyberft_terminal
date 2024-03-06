<?php
namespace common\states\out;

use common\components\TerminalId;
use common\document\Document;
use common\models\cyberxml\CyberXmlDocument;
use common\states\BaseDocumentStep;
use Yii;

class DocumentAutoSignStep extends BaseDocumentStep
{
    public $name = 'autosign';

    public function run()
    {
        $document = $this->state->document;

        if (!$this->state->module->isAutoSignatureRequired($document->origin, $document->sender)) {
            return true;
        }

        $termData = Yii::$app->terminals->getTerminalData();
        $runningTerminals = [];
        foreach($termData as $terminalId => $data) {
            if (isset($data['isRunning']) && $data['isRunning'] == true) {
                $runningTerminals[] = $terminalId;
            }
        }

        if (empty($runningTerminals)) {
            return true;
        }

        $cyxDoc = $this->state->cyxDoc;

        if ($this->autosign($cyxDoc, $document)) {
            $document->updatestatus(Document::STATUS_AUTOSIGNED);
            $primaryAutobot = Yii::$app->terminals->findAutobotUsedForSigning($cyxDoc->senderId);
            if (empty($primaryAutobot)) {
                $this->log('Failed to find primary autobot for sender ' . $cyxDoc->senderId);

                return false;
            }

            if ($primaryAutobot->controllerVerificationFlag == 1) {
                $document->updateStatus(Document::STATUS_FOR_CONTROLLER_VERIFICATION);
                $this->state->stop();
            } else {
                $document->updateStatus(Document::STATUS_FOR_MAIN_AUTOSIGNING);
            }

            return true;
        }
    }

  	private function autosign(CyberXmlDocument $cyxDoc, Document $document)
	{
		$extracted = TerminalId::extract($document->sender);

		if (empty($extracted)) {
			$this->log('Document has invalid sender: ' . $document->sender);

			return false;
		}

        $autobotModule = Yii::$app->getModule('autobot');

        if (empty($autobotModule)) {
            $this->log('Could not find autobot module');

            return false;
        }

	    $autobots = $autobotModule->getParticipantAdditionalAutobots(
                $extracted->getParticipantId(),
                $document->sender
        );

		if (is_array($autobots) && !empty($autobots)) {

            $document->updateStatus(Document::STATUS_AUTOSIGNING);

			$terminalData = Yii::$app->terminals->findTerminalData($document->sender);
			if (!is_array($terminalData)) {
				$this->log('TerminalData is corrupted for sender ' . $document->sender);
                $document->updateStatus(Document::STATUS_AUTOSIGNING_ERROR);

				return false;
			}

			foreach ($autobots as $autobot) {

                if ($autobot->isBlocked) {
                    $document->updateStatus(Document::STATUS_AUTOSIGNING_ERROR, Yii::t('app/autobot',
                        'Auto-signing is failed! Key {key} is not activated!', ['key' => $autobot->fingerprint]));

                    return false;
                }

				if (!isset($terminalData['passwords'][$autobot->id])) {
					$this->log("TerminalData for autobot id {$autobot->id} is corrupted");
                    $document->updateStatus(Document::STATUS_AUTOSIGNING_ERROR);

					return false;
				}

				if (!$cyxDoc->sign(
                        $cyxDoc->senderId,
                        $autobot->privateKey,
                        $terminalData['passwords'][$autobot->id],
                        $autobot->fingerprint,
                        $autobot->certificate
                    )
                ) {
                    $document->updateStatus(Document::STATUS_AUTOSIGNING_ERROR);
					$this->log('Could not auto-sign');

					return false;
				}
			}

            $storedFile = Yii::$app->storage->get($document->actualStoredFileId);

            if (!$storedFile->updateData($cyxDoc->getDom()->saveXML())) {
                $document->updateStatus(Document::STATUS_AUTOSIGNING_ERROR);
                $this->log('Document autosigned, but could not be saved');

                return false;
            }
		}

		return true;
 	}

}