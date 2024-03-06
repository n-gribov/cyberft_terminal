<?php
namespace addons\edm\jobs;

use common\base\RegularJob;
use common\components\Resque;
use common\helpers\FileHelper;
use Resque_Job_DontPerform;
use Yii;

class ImportJob extends RegularJob
{
    private $_module;
    private $_resList;
    private $_jobResource;

    public function setUp()
    {
        parent::setUp();

        $this->_module = Yii::$app->addon->getModule('edm');

        if (empty($this->_module)) {
            throw new Resque_Job_DontPerform('EDM module not found');
        }

        $this->_jobResource = Yii::$app->registry->getImportResource($this->_module->serviceId, 'job');

        if (empty($this->_jobResource)) {
            throw new Resque_Job_DontPerform('Import resource configuration error');
        }

        // Получаем объединение множеств ресурсов и типов для сканирования
        // т.е. список вида
        // [
        //     resourceId => [docType1 => true, docType2 => true, ...],
        //     resourceId2 => [docType1 => true, ...],
        //     ...
        // ]
        $this->_resList = [];
        foreach ($this->_module->config->docTypes as $docType => $docTypeProps) {
            if (isset($docTypeProps['resources']['import'])) {
                $resId = $docTypeProps['resources']['import'];
                $this->_resList[$resId][$docType] = true;
            }
        }
    }

    public function perform()
    {
        $this->log('Importing EDM data', false, 'regular-jobs');
        // Надо пройти по ресурсам и импортировать найденные в них типы

        foreach ($this->_resList as $resId => $docTypeList) {

            $resImport = Yii::$app->registry->getImportResource($this->_module->serviceId, $resId);

            if (empty($resImport)) {
                $this->log('Import resource ' . $resId . ' is misconfigured or unavailable');

                continue;
            }

            foreach ($resImport->contents as $filePath) {

                $jobFilePath = $this->_jobResource->getPath() . '/' . FileHelper::mb_basename($filePath);
                rename($filePath, $jobFilePath);

                Yii::$app->resque->enqueue(
                    'common\jobs\StateJob',
                    [
                        'stateClass' => 'addons\edm\states\out\EdmOutState',
                        'params' => serialize([
                            'filePath' => $jobFilePath,
                            'docTypeList' => $docTypeList
                        ])
                    ],
                    true,
                    Resque::OUTGOING_QUEUE
                );
            }
        }
    }
}