<?php

namespace console\controllers;

use common\base\ConsoleController as ConsoleController;
use common\document\Document;
use Resque_Job_Status;
use yii\helpers\Console;

/**
 * Main messaging transport module
 */
class TransportController extends ConsoleController
{
    public function actionCheck($token)
    {
        $status = new Resque_Job_Status($token);
        echo $status->get(); // Outputs the status
    }

    public function getUniqueID()
    {
        return $this->id;
    }

    public function actionIndex() {
        $this->run('/help', ['transport']);
    }

    /**
     * @param $id
     * @param $status
     */
    public function actionSetDocumentStatus($id, $status)
    {
        $document = Document::findOne($id);
        $document->status = $status;

        if (!$document->save(false, ['status'])) {
            Console::error(print_r($document->getFirstErrors()));
        } else {
            Console::output("Status '{$status}' set for document #{$id}");
        }
    }

}
