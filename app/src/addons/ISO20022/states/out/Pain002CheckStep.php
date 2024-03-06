<?php
namespace addons\ISO20022\states\out;

use addons\ISO20022\models\ISO20022DocumentExt;
use addons\ISO20022\models\Pain002Type;
use common\document\Document;
use common\modules\monitor\models\MonitorLogAR;
use common\states\BaseDocumentStep;
use Yii;

class Pain002CheckStep extends BaseDocumentStep
{
    public $name = 'check002';

    public function run()
    {
        $document = $this->state->document;
        $typeModel = $this->state->typeModel;
        // Если pain.002, то изменяем статус связанного iso20022-документа
        if ($typeModel->type == Pain002Type::TYPE) {

            $originalExtModel = $document->extModel;
            $originalExtModel->originalFilename = $typeModel->originalFilename;
            // Сохранить модель в БД
            $originalExtModel->save();

            // Поиск в ExtModel документа по его message id и запись туда нужной информации
            $extModel = ISO20022DocumentExt::find()->where(['msgId' => $typeModel->originalMsgId])->one();

            if ($extModel) {
                // Получаем оригинальный документ, к которому относится pain.002, чтобы получить его uuid
                $linkedDocument = Document::findOne($extModel->documentId);

                // Бизнес статус и описание ошибки заполняем, только если пришли какие-то значения
                $statusCode = $typeModel->getStatusCodeByType($linkedDocument->type);
                $errorDescription = $typeModel->getErrorDescriptionByType($linkedDocument->type);

                if ($statusCode) {
                    $extModel->statusCode = $statusCode;
                }

                if ($errorDescription) {
                    $extModel->errorDescription = $errorDescription;
                }

                $extModel->errorCode = $typeModel->errorCode;
                $extModel->save(false);

                if (!empty($linkedDocument)) {
                    // Заносим uuid оригинального документа связи с pain.002
                    $document->uuidReference = $linkedDocument->uuid;
                    $document->save(false);
                } else {
                    $this->log('Could not find pain.002 linked document ' . $extModel->documentId);
                }

                // Зарегистрировать событие получения статуса документа ISO в модуле мониторинга
                Yii::$app->monitoring->log(
                    'user:ISOReceiveStatus',
                    'document',
                    $extModel->documentId,
                    [
                        'initiatorType' => MonitorLogAR::INITIATOR_TYPE_SYSTEM,
                        'msgId' => $typeModel->originalMsgId,
                        'status' => $statusCode,
                        'reason' => $errorDescription
                    ]
                );
            }
        }

        return true;
    }

}