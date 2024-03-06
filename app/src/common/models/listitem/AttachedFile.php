<?php

namespace common\models\listitem;

use common\helpers\Uuid;
use Yii;
use yii\web\UploadedFile;


/**
 * Класс AttachedFile дает унифицированный интерфейс для загруженных файлов для разных типов форм.
 */
class AttachedFile extends NestedListItem
{
    const SERVICE_ID = 'ISO20022';

    public $id;
    public $name;
    public $path;
    public $serviceId;

    public function rules()
    {
        return [
            [['id', 'name', 'path', 'serviceId'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => Yii::t('app', 'File name'),
        ];
    }

    public function getServiceId()
    {
        return $this->serviceId ?: static::SERVICE_ID;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function save($path)
    {
        $tempResource = Yii::$app->registry->getTempResource($this->getServiceId());

        $fileInfo = $tempResource->putFile($path);
        $this->path = $fileInfo['path'];

        return $fileInfo;
    }

    public static function createFromUploadedFile(UploadedFile $uploadedFile, $serviceId = null)
    {
        $instance = new static([
            'id' => Uuid::generate(),
            'name' => $uploadedFile->name,
            'serviceId' => $serviceId
        ]);

        $instance->save($uploadedFile->tempName);

        return $instance;
    }

    public function saveStream($stream)
    {
        $tempResource = Yii::$app->registry->getTempResource($this->getServiceId());

        $fileInfo = $tempResource->putStream($stream);
        $this->path = $fileInfo['path'];

        return $fileInfo;
    }

    public static function createFromStream($stream, $name, $serviceId = null)
    {
        $instance = new static([
            'id' => Uuid::generate(),
            'name' => $name,
            'serviceId' => $serviceId
        ]);

        $instance->saveStream($stream);

        return $instance;
    }

}
