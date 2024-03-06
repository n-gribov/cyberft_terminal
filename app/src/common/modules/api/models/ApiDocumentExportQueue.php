<?php

namespace common\modules\api\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "api_documentExportQueue".
 *
 * @property int $id
 * @property string $uuid
 * @property string $path
 * @property string $terminalAddress
 */
class ApiDocumentExportQueue extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'api_documentExportQueue';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uuid', 'path', 'terminalAddress'], 'required'],
            [['uuid', 'path', 'terminalAddress'], 'string'],
        ];
    }

    public function deleteWithFile(): void
    {
        // Удалить запись о файле из БД
        if ($this->delete()) {
            // Удалить файл
            unlink($this->path);
        }
    }
}
