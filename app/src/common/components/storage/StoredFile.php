<?php

namespace common\components\storage;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * Stored file active record class
 *
 * @package core
 * @subpackage storage
 *
 * @property integer  $id                Row ID
 * @property string   $path              File path
 * @property string   $originalFilename  File original  name
 * @property string   $dateCreate        Created date
 * @property string   $serviceId         Service Id
 * @property string   $resourceId        Resource Id
 * @property integer  $size              File size
 * @property string   $entity            Entity
 * @property int      $entityId          Entity Id
 * @property string   $fileType          File type
 * @property string   $context           Context
 * @property string   $status            Status
 * @property string   $descriptor        Descriptor
 * @property string   $fileSystem        Filesystem: local or tar
 * @property integer  $isEncrypted       Encrypted status
 */
class StoredFile extends ActiveRecord
{
	const STATUS_PROCESSING = 'processing';
	const STATUS_PROCESSING_ERROR = 'processingError';
	const STATUS_READY = 'ready';

	public static function tableName()
	{
		return 'storage';
	}

    public function behaviors()
	{
		return [
			[
				'class' => TimestampBehavior::className(),
				'createdAtAttribute' => 'dateCreate',
				'updatedAtAttribute' => false,
				'value' => new Expression('NOW()'),
			],
		];
	}

	public function rules()
	{
		return [
			[['path'], 'required'],
			[['path'], 'string', 'max' => 255],
		];
	}

	public function beforeSave($insert)
	{
		if (parent::beforeSave($insert)) {
            $path = $this->getRealPath();
			if (file_exists($path)) {
				$this->size = filesize($path);
			}

			return true;
		} else {
            return false;
		}
	}

	public function getData()
	{
        $path = $this->getRealPath();
        if (is_file($path)) {
    		return file_get_contents($path);
        }

        return null;
	}

	public function getContextValue()
	{
		return (empty($this->context) || is_null($this->context))
                ? []
                : json_decode($this->context, true);
	}

	public function setContextValue($context)
	{
		$this->context = json_encode($context);
	}

    public function getRealPath()
    {
        $resource = Yii::$app->registry->getStorageResource($this->serviceId, $this->resourceId);

        if (empty($resource)) {
            return null;
        }

        return $resource->getRealPath($this->path);
    }

    public function updateData($data)
    {
        $resource = Yii::$app->registry->getStorageResource($this->serviceId, $this->resourceId);

        if (empty($resource)) {
            return null;
        }

        return $resource->updateData($this->path, $data);
    }

}