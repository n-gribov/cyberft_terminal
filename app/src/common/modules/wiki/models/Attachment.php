<?php

namespace common\modules\wiki\models;

use common\helpers\FileHelper;
use common\helpers\Uuid;
use common\modules\wiki\WikiModule;
use Yii;
use yii\base\Exception;
use yii\base\InvalidParamException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\web\UploadedFile;

/**
 * Wiki page attachment
 *
 * @package modules
 * @subpackage wiki
 *
 * @property integer    $id             Page id
 * @property string     $type           Attachment type (file, image)
 * @property integer    $page_id        Parent page id
 * @property string     $title
 * @property string     $description
 * @property datetime   $path
 */
class Attachment extends ActiveRecord
{
    const STORAGE_DIR = 'files';

    const TYPE_IMAGE = 'image';
    const TYPE_FILE = 'file';

    protected $_fileSystem;

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created',
                'updatedAtAttribute' => false,
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['import'] = $this->attributes();
        return $scenarios;
    }


    public function rules()
    {
        return [
            [['id', 'page_id'], 'integer'],
            [['title', 'description', 'path', 'type'], 'string'],
            [['page_id', 'type', 'path'], 'required'],
            ['type', 'in', 'range' => [static::TYPE_IMAGE, static::TYPE_FILE]],
        ];
    }

    public static function tableName()
    {
        return 'page_attachment';
    }

    public function attributeLabels()
    {
        return [
            'id' => WikiModule::t('models', 'ID'),
            'page_id' => WikiModule::t('models', 'Page ID'),
            'type' => WikiModule::t('models', 'Type'),
            'title' => WikiModule::t('models', 'Title'),
            'description' => WikiModule::t('models', 'Description'),
            'path' => WikiModule::t('models', 'File path'),
            'created' => WikiModule::t('models', 'Created at'),
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                try {
                    if ($this->scenario != 'import') {
                        $this->path = $this->storeFile($this->path);
                    }
                } catch (Exception $ex) {
                    $this->addError('path', $ex->getMessage());
                }
            }

            return true;
        } else {
            return false;
        }
    }

    public function getPage()
    {
        return $this->hasOne(Page::className(), ['id' => 'page_id']);
    }

    protected function storeFile($filePath)
    {
        $mimeType = FileHelper::getMimeType($filePath);
        $fileName = implode('.', [Uuid::generate(), FileHelper::getExtensionByMimeType($mimeType)]);
        $fileName = FileHelper::inflateFilename($fileName);

        $stream = fopen($filePath, 'r');
        if (!$this->getFileSystem()->putStream($fileName, $stream)) {
            throw new Exception(WikiModule::t('default', 'Cannot write file to storage'));
        }
        
        return $fileName;
    }

    protected function getFileSystem()
    {
        if (is_null($this->_fileSystem)) {
            $this->_fileSystem = Yii::$app->storage->getFileSystem(implode('/', [
                WikiModule::SERVICE_ID,
                static::STORAGE_DIR
            ]));
        }

        return $this->_fileSystem;
    }

    public function beforeDelete()
    {
        if (parent::beforeDelete()) {
            if ($this->getFileSystem()->has($this->path)) {
                if (!$this->getFileSystem()->delete($this->path)) {
                    return false;
                }
            }

            return true;
        } else {
            return false;
        }
    }

    /**
     * @param type $page_id
     * @param string|UploadedFile $file
     * @param string $type
     */
    public static function loadFromFile($page_id, $file, $type)
    {
        $model     = new static(compact('page_id', 'type'));

        if ($file instanceof UploadedFile) {
            $model->path = $file->tempName;
        } else if (is_string($file)) {
            $model->path = $file;
        } else {
            throw new InvalidParamException('File should be path string or UploadedFile');
        }

        return $model;
    }

    public function getFileStream()
    {
        if (false === $this->getFileSystem()->has($this->path)) {
            return null;
        }

        return $this->getFileSystem()->readStream($this->path);
    }

    public function getMimeType()
    {
        if ($this->getFileSystem()->has($this->path)) {
            return $this->getFileSystem()->getMimeType($this->path);
        }

        return null;
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        Dump::flushCachedData();
    }

    public function afterDelete()
    {
        parent::afterDelete();
        Dump::flushCachedData();
    }

}