<?php

namespace common\models\listitem;

use common\helpers\Uuid;
use Yii;

/**
 * Расширение класса AttachedFile, которое хранит список файлов в сессии
 */
/**
 * @property string $sessionPath
 */
class BaseAttachedFileSession extends AttachedFile
{
    const SESSION_KEY = 'AttachedFiles';

    public function getSessionKey()
    {
        return static::SESSION_KEY;
    }

    public function getPath()
    {
        $userFiles = Yii::$app->session->get($this->getSessionKey(), []);

        return $userFiles[$this->id] ?? null;
    }

    public function saveStream($stream)
    {
        $fileInfo = parent::saveStream($stream);

        return $this->saveToSession($fileInfo);
    }

    public function save($path)
    {
        $fileInfo = parent::save($path);

        return $this->saveToSession($fileInfo);
    }

    public function saveToSession($fileInfo)
    {
        $userFiles = Yii::$app->session->get($this->getSessionKey(), []);
        $userFiles[$this->id] = $fileInfo['path'];
        Yii::$app->session->set($this->getSessionKey(), $userFiles);
    }

    /**
     * @param AttachedFile $attachedFile
     * @return static
     */
    public static function createFromAttachedFile(AttachedFile $attachedFile)
    {
        $instance = new static([
            'id'        => (string)Uuid::generate(),
            'name'      => $attachedFile->name,
            'path'      => $attachedFile->path,
            'serviceId' => $attachedFile->getServiceId()
        ]);
        $instance->saveToSession(['path' => $instance->path]);
        return $instance;
    }
}
