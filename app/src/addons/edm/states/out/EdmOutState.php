<?php

namespace addons\edm\states\out;

use addons\edm\models\Pain001Fcy\Pain001FcyType;
use addons\edm\models\Pain001Fx\Pain001FxType;
use addons\edm\models\Pain001Rls\Pain001RlsType;
use addons\edm\states\out\Auth024PrepareStep;
use addons\edm\states\out\CheckSenderStep;
use addons\edm\states\out\DetectFormatStep;
use addons\edm\states\out\IBankCurrencyPaymentsRegisterPrepareStep;
use addons\edm\states\out\IBankV1DocumentPrepareStep;
use addons\edm\states\out\IBankV2DocumentPrepareStep;
use addons\edm\states\out\Pain001FcyPrepareStep;
use addons\edm\states\out\Pain001FxPrepareStep;
use addons\edm\states\out\Pain001RlsPrepareStep;
use addons\edm\states\out\PaymentRegisterPrepareStep;
use addons\edm\states\out\ProcessFileStep;
use addons\ISO20022\models\Auth024Type;
use addons\ISO20022\models\Auth025Type;
use common\base\BaseDocumentState;
use common\document\Document;
use common\states\out\DocumentOutState;

class EdmOutState extends BaseDocumentState
{
    public $filePath;
    public $documentFilePath;
    public $attachmentsPaths = [];
    public $isImportingZipArchive = false;
    public $origin = Document::ORIGIN_FILE;
    public $sender = null;
    public $receiver = null;
    public $authorizedSender;

    public $format;
    public $model;

    public $apiFileName;

    protected $_steps = [
        'processFile' => ProcessFileStep::class,
        'detect' => DetectFormatStep::class,
        'authorizeSender' => AuthorizeSenderStep::class,
        'checkSender' => CheckSenderStep::class,
        'decide' => null, // step decider
    ];

    public function decideState()
    {
        /**
         * Для формата PaymentRegister отправка документа происходит автоматически в EdmHelper,
         * поэтому дальнейшие действия не нужны.
         */
        if (!$this->_isStopped && $this->format != 'PaymentRegister') {
            return new DocumentOutState();
        }
    }

    public function decideStep()
    {
        if ($this->_isStopped) {
            return null;
        }

        switch ($this->format) {

            //case 'CyberXML': не требуется действий
            case 'PaymentRegister': return PaymentRegisterPrepareStep::class;
            case 'iBankV1': return IBankV1DocumentPrepareStep::class;
            case 'iBankV2': return IBankV2DocumentPrepareStep::class;
            case 'iBankCurrencyPaymentsRegister': return IBankCurrencyPaymentsRegisterPrepareStep::class;
            case Pain001FcyType::TYPE: return Pain001FcyPrepareStep::class;
            case Pain001FxType::TYPE: return Pain001FxPrepareStep::class;
            case Pain001RlsType::TYPE: return Pain001RlsPrepareStep::class;
            case Auth024Type::TYPE: return Auth024PrepareStep::class;
            case Auth025Type::TYPE: return Auth025PrepareStep::class;
        }

        return null;
    }

    protected function cleanup(): void
    {
        parent::cleanup();
        if (is_file($this->filePath)) {
            unlink($this->filePath);
        }
    }
}
