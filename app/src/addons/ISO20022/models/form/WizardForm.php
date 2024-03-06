<?php

namespace addons\ISO20022\models\form;

use addons\ISO20022\helpers\RosbankHelper;
use addons\ISO20022\models\Auth026Type;
use common\base\Model;
use common\models\listitem\AttachedFile;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

/**
 * @property string|null $fileName
 * @property string|null $filePath
 */
class WizardForm extends Model {

	public $docId;
	public $subject;
	public $descr;
	public $sender;
	public $recipient;
	public $terminalCode;
    public $file;
    public $typeCode;

	public function rules()
	{
		return [
			[['sender', 'recipient', 'subject', 'descr'], 'required'],
			[['sender', 'recipient'], 'string', 'length' => [11, 12]],
            ['typeCode', 'string', 'length' => [4, 4]],
            ['file', 'file'],
            ['file', 'safe'],
            ['file', 'validateFileSize', 'skipOnEmpty' => false],
		];
	}

	/**
	 * Функция возвращает вектор, содержащий имена всех safe-атрибутов данной
	 * модели.
	 * CYB-1200. Значение terminalCode не пробрасывается из формы, т.к. данный
	 * атрибут не считается безопасным (для него нет rules). Необходимо в явном
	 * виде указать его как безопасный.
	 * @return array
	 */
	public function safeAttributes()
	{
		return ArrayHelper::merge(parent::safeAttributes(), ['terminalCode']);
	}

	public function attributeLabels()
    {
        return ArrayHelper::merge(
            parent::attributeLabels(),
            [
                'sender'       => Yii::t('doc', 'Sender'),
                'recipient'    => Yii::t('doc', 'Recipient'),
                'terminalCode' => Yii::t('doc', 'Terminal code'),
                'subject'      => Yii::t('doc', 'Subject'),
                'descr'        => Yii::t('doc', 'Description'),
                'file'         => Yii::t('app', 'File'),
                'typeCode'     => Yii::t('app/iso20022', 'Document type code')
            ]
        );
    }

    public function addFile(UploadedFile $file, $serviceId = 'ISO20022')
    {
        // загруженные файлы сохраняются во временный ресурс заданного модуля
        // через интерфейс класс AttachedFile
        $attachedFile = AttachedFile::createFromUploadedFile($file, $serviceId);
        // Не присваиваю file = $attachedFile из-за экономии на кешировании данной формы
        $this->file = [
            'name' => $attachedFile->name,
            'path' => $attachedFile->path
        ];
    }

    public function load($data, $formName = null)
    {
        if (parent::load($data, $formName)) {
            $file = UploadedFile::getInstance($this, 'file');
            if ($file) {
                $this->addFile($file);
            }

            return true;
        }
        return false;
    }

    public function validateFileSize($attribute): void
    {
        if (empty($this->file)) {
            return;
        }

        if (!RosbankHelper::isGatewayTerminal($this->recipient)) {
            return;
        }

        $fileSize = filesize($this->file['path']);
        if ($fileSize > Auth026Type::MAX_EMBEDDED_ATTACHMENT_FILE_SIZE_KB * 1024) {
            $this->addError(
                $attribute,
                Yii::t(
                    'app/iso20022',
                    'Attachment file size cannot exceed {limit} {unit,select,MB{MB} KB{KB} other{}}',
                    [
                        'limit' => Auth026Type::MAX_EMBEDDED_ATTACHMENT_FILE_SIZE_KB >> 10,
                        'unit' => 'MB',
                    ]
                )
            );
        }
    }

    public function getFileName(): ?string
    {
        return $this->file ? $this->file['name'] : null;
    }

    public function getFilePath(): ?string
    {
        return $this->file ? $this->file['path'] : null;
    }
}
