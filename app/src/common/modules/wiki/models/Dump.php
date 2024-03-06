<?php

namespace common\modules\wiki\models;

use common\helpers\FileHelper;
use common\modules\wiki\WikiModule;
use creocoder\flysystem\ZipArchiveFilesystem;
use Exception;
use Yii;
use yii\base\Exception as Exception2;
use yii\base\Model;

/**
 * Wiki base export model
 *
 * @package modules
 * @subpackage wiki
 *
 */
class Dump extends Model
{
    const CACHE_KEY      = 'modules/wiki/exportData';
    const TARGET_PREFIX  = 'cyberft-wiki-';
    const STATUS_READY   = 'ready';
    const STATUS_WAITING = 'waiting';
    const STATUS_WORKING = 'working';
    const STATUS_ERROR   = 'error';

    public $exportId;
    public $jobToken;
    public $createdAt;
    public $updatedAt;
    public $status;
    protected $_target;
    protected $_storage;

    public static function hasCachedData()
    {
        if (!Yii::$app->cache->exists(static::CACHE_KEY)) {

            return false;
        }

        return true;
    }

    public static function flushCachedData()
    {
        return Yii::$app->cache->delete(static::CACHE_KEY);
    }

    public function rules()
    {
        return [
            [['jobToken', 'status'], 'string'],
            [['createdAt', 'updatedAt'], 'integer'],
            [['exportId', 'status'], 'required'],
        ];
    }

    public function init()
    {
        parent::init();
    }

    public static function create()
    {
        $model = Yii::createObject(Dump::className());

        $model->setAttributes([
            'exportId' => FileHelper::uniqueName(),
            'createdAt' => mktime(),
        ]);

        return $model;
    }

    public static function truncateWikiData()
    {
        $model = Yii::createObject(Dump::className());

        foreach ($model->getStorage()->listContents() as $item) {
            if ('dir' === $item['type']) {
                $model->getStorage()->deleteDir($item['path']);
            } else {
                $model->getStorage()->delete($item['path']);
            }
        }

        Yii::$app->db->createCommand()->truncateTable(Page::tableName())->execute();
        Yii::$app->db->createCommand()->truncateTable(Attachment::tableName())->execute();
        Yii::$app->db->createCommand()->truncateTable(WikiWidget::tableName())->execute();
    }

    public function save()
    {
        $this->updatedAt = mktime();

        Yii::$app->cache->set(static::CACHE_KEY, $this->attributes);
    }

    public static function loadFromCache()
    {
        if (static::hasCachedData()) {
            $model = Yii::createObject(Dump::className());
            $model->setAttributes(Yii::$app->cache->get(static::CACHE_KEY));

            if ($model->isReady() && is_null($model->getTargetStream())) {
                return null;
            }

            return $model;
        }

        return null;
    }

    public function isReady()
    {
        return $this->status === static::STATUS_READY;
    }

    protected function getTarget()
    {
        if (is_null($this->_target)) {
            $this->_target = Yii::createObject([
                'class' => 'creocoder\flysystem\ZipArchiveFilesystem',
                'path' => '@storage/' . WikiModule::SERVICE_ID . '/' . $this->getTargetFilename(),
            ]);
        }

        return $this->_target;
    }

    protected function getStorage()
    {
        if (is_null($this->_storage)) {
            $this->_storage = Yii::createObject([
                    'class' => 'creocoder\flysystem\LocalFilesystem',
                    'path' => '@storage/' . WikiModule::SERVICE_ID,
            ]);
        }

        return $this->_storage;
    }

    public function cleanup()
    {
        foreach ($this->getStorage()->listContents() as $item) {
            if (
                ('file' === $item['type'])
                && (0 === strpos($item['basename'], static::TARGET_PREFIX))
            ) {
                $this->getStorage()->delete($item['basename']);
            }
        }

        return true;
    }


    public function build()
    {
        $pages = Page::find()->all();
        foreach ($pages as $page) {
            $data                = $page->attributes;
            $data['attachments'] = [];
            foreach ($page->attachments as $attach) {
                $data['attachments'][] = $attach->attributes;
            }

            $this->getTarget()->write('page-' . $page->id . '.json', json_encode($data));
        }

        foreach ($this->getStorage()->listContents('files', true) as $item) {
            if ('file' === $item['type']) {
                $stream = $this->getStorage()->readStream($item['path']);
                $this->getTarget()->writeStream($item['path'], $stream);
            }
        }

        $widgets = WikiWidget::find()->all();
        foreach($widgets as $widget) {
            $this->getTarget()->write('pageWidget-' . $widget->id . '.json', json_encode($widget->attributes));
        }

        // Forcing archive to save
        $this->getTarget()->getAdapter()->getArchive()->close();

        return true;
    }

    /**
     * @todo Вынести определение структуры архива в отдельный метод так, чтобы использовать его в self::import()
     * @param ZipArchiveFilesystem $fileSystem
     */
    public static function validateFile(ZipArchiveFilesystem $fileSystem)
    {
        if (!$fileSystem->listContents('files', true)) {
            return false;
        }

        foreach($fileSystem->listContents() as $item) {
            if ('file' === $item['type'] && preg_match('/^page\-\d+\.json/', $item['basename'])) {
                return true;
            }
        }

        return false;
    }

    /**
     *
     * @param type $fileName
     * @return boolean
     * @throws Exception2
     */
    public static function import($fileName)
    {
        try {
            $source = Yii::createObject([
                'class' => 'creocoder\flysystem\ZipArchiveFilesystem',
                'path' => $fileName,
            ]);

            if (!static::validateFile($source)) {
                throw new \Exception('Dump file format incorrect.');
            }

            static::truncateWikiData();

            foreach ($source->listContents() as $item) {
                if ('file' === $item['type']) {
                    if (preg_match('/^page\-\d+\.json/', $item['basename'])) {
                        $data = json_decode($source->read($item['path']), true);
                        $page = new Page();
                        $page->id = $data['id'];
                        $page->setAttributes($data);
                        // Если модель успешно сохранена в БД
                        if ($page->save()) {
                            if (!isset($data['attachments'])) {
                                continue;
                            }
                            foreach ($data['attachments'] as $attachmentData) {
                                $attach = new Attachment(['scenario' => 'import']);
                                $attach->setAttributes($attachmentData);
                                // Сохранить модель в БД
                                $attach->save();

                            }
                        }
                    } else if (preg_match('/^pageWidget\-\d+\.json/', $item['basename'])) {
                        $data = json_decode($source->read($item['path']), true);
                        $widget = new WikiWidget($data);
                        $widget->id = $data['id'];
                        // Сохранить модель в БД
                        $widget->save();
                    }
                }
            }

            $storage =  Yii::createObject([
                'class' => 'creocoder\flysystem\LocalFilesystem',
                'path' => '@storage/' . WikiModule::SERVICE_ID,
            ]);

            foreach ($source->listContents('files', true) as $item) {
                if ('file' === $item['type']) {
                    $stream = $source->readStream($item['path']);
                    $storage->writeStream($item['path'], $stream);
                }
            }

            return  true;

        } catch (Exception $ex) {
            WikiModule::error($ex->getMessage());
        }

        return false;
    }

    public function getTargetFilename()
    {
        return static::TARGET_PREFIX . $this->exportId . '.zip';
    }


    public function getTargetStream()
    {
        $fs = $this->getStorage();

        if ($fs->has($this->getTargetFilename())) {
            return $fs->readStream($this->getTargetFilename());
        }

        return null;
    }

    public function getTargetMimeType()
    {
        return $this->getStorage()->getMimeType($this->getTargetFilename());
    }

}
