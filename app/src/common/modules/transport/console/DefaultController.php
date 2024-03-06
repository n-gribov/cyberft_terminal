<?php

namespace common\modules\transport\console;

use common\base\ConsoleController as ConsoleController;
use common\document\Document;
use common\models\StateAR;
use common\models\Terminal;

/**
 * Main messaging transport module
 */
class DefaultController extends ConsoleController
{

    public function getUniqueID()
    {
        return $this->id;
    }

    /**
     * Метод выводит текст подсказки
     */
    public function actionIndex()
    {
        $this->run('/help', ['transport']);
    }

    public function actionResend($status, $type = null, $terminal = null, $startId = null, $endId = null)
    {
        $validStatuses = [
            Document::STATUS_SIGNING_ERROR => 'autoSign',
            Document::STATUS_ACCEPTED => 'autoSign',
            Document::STATUS_AUTOSIGNING_ERROR => 'autoSign',
            Document::STATUS_FORSENDING => 'analyze',
            Document::STATUS_ENCRYPTING => 'encrypt',
            Document::STATUS_ENCRYPTING_ERROR => 'encrypt',
            Document::STATUS_COMPRESSING => 'compress',
            Document::STATUS_COMPRESSION_ERROR => 'compress',
            Document::STATUS_DELIVERING => 'analyze',
            Document::STATUS_SENDING => 'analyze',
            Document::STATUS_SENDING_FAIL => 'analyze',
            Document::STATUS_NOT_SENT => 'analyze',
        ];

        if (!isset($validStatuses[$status])) {
            echo 'Wrong status: ' . $status
                    . "\nValid statuses: " . implode(', ', array_keys($validStatuses)) . "\n";

            exit();
        }

        echo "Filter by:\n\tstatus = $status\n";

        $query = Document::find()
            ->where([
                'status' => $status,
                'direction' => Document::DIRECTION_OUT,
            ]);

        if ($type && $type != '-') {
            echo "\ttype = $type\n";

            $query->andWhere(['type' => $type]);
        }

        if ($terminal && $terminal != '-') {
            $terminalId = Terminal::getIdByAddress($terminal);
            echo "\tterminal = $terminal (id = $terminalId)\n";
            $query->andWhere(['terminalId' => $terminalId]);
        }

        if ($startId && $startId != '-') {
            echo "\tid from $startId\n";
            $query->andWhere(['>=', 'id', $startId]);
        }

        if ($endId && $endId != '-') {
            echo "\tid up to $endId\n";
            $query->andWhere(['<=', 'id', $endId]);
        }

        $documents = $query->all();
        echo 'Found ' . count($documents) . " documents. Continue? Y/N:\n";
        $line = strtoupper(trim(fgets(STDIN)));

        if ($line !== 'Y') {
            echo "Aborted.\n";

            exit(0);
        }

        foreach($documents as $document) {
            $terminal = Terminal::findOne($document->terminalId);

            $state = new StateAR([
                'documentId' => $document->id,
                'dateRetry' => '1970-01-01 00:03:00',
                'code' => 'common\\states\\out\\SendingState',
                'terminalId' => $terminal->terminalId,
                'status' => $validStatuses[$document->status],
                'data' => 'a:1:{s:7:"attempt";i:0;}'
            ]);

            if (!$state->save()) {
                echo "Error saving state for document id " . $document->id . "\n";
            } else {
                echo "Saved state for document id " . $document->id . "\n";
            }
        }
    }
}
