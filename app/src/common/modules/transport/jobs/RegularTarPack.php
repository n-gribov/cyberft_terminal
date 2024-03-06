<?php

namespace common\modules\transport\jobs;

use common\base\RegularJob;
use common\components\storage\StoredFile;
use Yii;

class RegularTarPack extends RegularJob
{
	public function perform()
	{
        $transportModuleId = Yii::$app->getModule('transport')->id;
        $resources = [
            Yii::$app->registry->getStorageResource($transportModuleId, 'in'),
            Yii::$app->registry->getStorageResource($transportModuleId, 'out')
        ];

        foreach($resources as $resource) {
            if (!$resource->usePartition) {
                continue;
            }

            $cachePath = $resource->getPath() . '/.cache';

            if (is_dir($cachePath)) {
                $entries = scandir($cachePath);

                if (is_array($entries) && count($entries) > 250) {
                    $this->clearCache($cachePath, $entries);
                }
            }

            $subDirs = $resource->getDirSubfolders($resource->getPath(), false);
            $count = 0;
            foreach($subDirs as $dir) {
                if ($resource->isArchivable($dir)) {

                    $fileList = $resource->archive($dir);

                    StoredFile::updateAll(
                        ['fileSystem' => 'tar'],
                        [
                            'and',
                            ['serviceId' => $resource->serviceId],
                            ['resourceId' => $resource->id],
                            ['like', 'path', $dir . '/%', false]
                        ]
                    );

                    $this->log('archived ' . count($fileList) . 'in ' . $resource->getPath($dir), false, 'regular-jobs');

                    $count++;

                    // Обрабатываем не больше 20 папок за 1 раз
                    if ($count > 19) {
                        break;
                    }
                }
            }
        }
	}

    private function clearCache($cachePath, $entries)
    {
        $result = [];

        foreach ($entries as $entry) {
            $fullPath = $cachePath . '/' . $entry;
            if ($entry{0} != '.' && !is_dir($fullPath)) {
                $result[filectime($fullPath) . '_' . $entry] = $fullPath;
            }
        }

        ksort($result);

        $count = count($result) - 250;
        if ($count < 0) {
            return;
        }

        foreach($result as $path) {
            unlink($path);
            $count--;
            if ($count < 1) {
                break;
            }
        }
    }

}