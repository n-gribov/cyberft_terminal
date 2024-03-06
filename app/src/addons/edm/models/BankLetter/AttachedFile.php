<?php

namespace addons\edm\models\BankLetter;

use addons\edm\EdmModule;
use common\helpers\Uuid;
use common\models\listitem\NestedListItem;
use Yii;
use yii\web\UploadedFile;

/**
 * @todo разрулить зоопарк с аттачмент файлами разных типов документов
 * @todo NestedItem тоже зоопарк
 * @property string $tempStoredFilePath
 */
class AttachedFile extends NestedListItem
{
    const USER_FILES_SESSION_KEY = 'BankLetterFormAttachedFiles';

    public $id;
    public $name;
    public $path;

    public function rules()
    {
        return [
            [['id', 'name'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => Yii::t('app', 'File name'),
        ];
    }

    public function getTempStoredFilePath()
    {
        $userFiles = Yii::$app->session->get(static::USER_FILES_SESSION_KEY, []);

        return $userFiles[$this->id] ?? null;
    }

    public function storeTempFile($path)
    {
        $tempResource = Yii::$app->registry->getTempResource(EdmModule::SERVICE_ID);
        $fileInfo = $tempResource->putFile($path);
        
        $this->userFileToSession($fileInfo['path']);
    }
    
    private function userFileToSession($path)
    {
        $userFiles = Yii::$app->session->get(static::USER_FILES_SESSION_KEY, []);
        $userFiles[$this->id] = $path;
        Yii::$app->session->set(static::USER_FILES_SESSION_KEY, $userFiles);        
    }

    public static function createFromUploadedFile(UploadedFile $uploadedFile)
    {
        return self::createFromFile($uploadedFile->name, $uploadedFile->tempName);
    }
    
    public static function createFromFile($name, $path)
    {        
        $attachedFile = new AttachedFile([
            'id' => Uuid::generate(),
            'name' => $name,
        ]);
        $attachedFile->storeTempFile($path);
        return $attachedFile;
    }

}
