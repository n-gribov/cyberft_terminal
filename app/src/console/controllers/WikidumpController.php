<?php

namespace console\controllers;

use common\modules\wiki\models\Dump;
use yii\console\Controller;

class WikidumpController extends Controller
{
    public function actionImport($filePath)
    {
        if (!is_readable($filePath)) {
            die($filePath . ' is not readable');
        }

        if (Dump::import($filePath)) {
            echo "Dump file imported\n";
        } else {
            echo "Dump file import failed\n";
        }
    }

    public function actionExport()
    {
        echo "Building dump\n";

        $dump = Dump::create();

        $dump->status = Dump::STATUS_WORKING;
        $dump->save();

        $dump->cleanup();
        $dump->build();

        $dump->status = Dump::STATUS_READY;
        $dump->save();

        echo 'Dump created: app/storage/wiki/' . $dump->getTargetFilename() . "\n";
    }

}
