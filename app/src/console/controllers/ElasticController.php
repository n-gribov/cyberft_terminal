<?php

namespace console\controllers;

use common\document\Document;
use Yii;
use yii\console\Controller;

class ElasticController extends Controller
{
    /**
     * Метод выводит текст подсказки
     */
    public function actionIndex()
    {
        $this->run('/help', ['elastic']);
    }

    /**
     * Массово заносит все документы из таблицы document в эластик
     */
    public function actionScan()
    {
        Yii::$app->elasticsearch->deleteType('_all');
        $query = Document::find()->all();
        $count = 0;
        $errCount = 0;
        $skipCount = 0;
        foreach($query as $document) {
            if ($document->isServiceType()) {
                continue;
            }

            if (empty($document->getSearchFields())) {
                echo 'Skipping ' . $document->type . ' document id ' . $document->id . " (search fields empty)\n";
                $skipCount++;
            } else if (Yii::$app->elasticsearch->putDocument($document)) {
                echo 'Added ' . $document->type . ' document id ' . $document->id . "\n";
                $count++;
            } else {
                echo 'Error: cannot add ' . $document->type . ' document id ' . $document->id . "\n";
                $errCount++;
            }
        }

        echo 'Added ' . $count . ' documents, ' . $errCount . ' errors, ' . $skipCount . " skipped.\n";
    }

    public function actionAdd($id)
    {
        $document = Document::findOne($id);
        if (!empty($document) && !$document->isServiceType()) {
            if (Yii::$app->elasticsearch->putDocument($document)) {
                echo "Added model id=" . $id . "\n";
            } else {
                echo "Could not add model id=" . $id . "\n";
            }
        } else {
            echo "Document id=" . $id . " not found or is technical\n";
        }
    }
}
