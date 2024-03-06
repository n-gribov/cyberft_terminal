<?php
namespace common\base;

use common\document\Document;
use common\models\cyberxml\CyberXmlDocument;
use common\models\StateAR;
use common\modules\api\ApiModule;

use Throwable;
use Yii;

class BaseDocumentState
{
    /**
     * @var StateAR
     */
    public $dataModel = null;

    public $step = null;
    protected $_stepPosition = 0;
    protected $_steps = [];

    public $terminalId;
    /**
     * @var BaseBlock $module
     */
    public $module;
    public $documentId;
    /**
     * @var Document
     */
    public $document;
    /**
     * @var CyberXmlDocument $cyxDoc
     */
    public $cyxDoc;

    /**
     * @var BaseType $typeModel
     */
    public $typeModel;
    public $origin;

    public $apiUuid;
    private $apiImportErrors = [];

    protected $_isStopped = false;
    public $status = '';
    public $lockName = null;
    public $lockValue = null;

    public $logCategory;

    protected $_retrySteps = ['decide' => ['attempts' => 3, 'interval' => 5]];
    public $attempt = 0;

    protected $_stepStats = [];

    public $logData = [];

    public function __construct($params = null)
    {
        if (is_array($params)) {

            $class = get_called_class();

            foreach($params as $key => $value) {
                if (property_exists($class, $key)) {
                    $this->$key = $value;
                }
            }

            if (isset($params['data'])) {
                foreach ($params['data'] as $key => $value) {
                    if (property_exists($class, $key)) {
                        $this->$key = $value;
                    }
                }
            }
        }
    }

    public function stop()
    {
        $this->_isStopped = true;
    }

    /**
     * Передача данных от одного state к другому
     * @param BaseDocumentState $oldState предыдущее state
     */
    public function transfer(BaseDocumentState $oldState)
    {
        $this->module = $oldState->module;
        $this->document = $oldState->document;
        $this->cyxDoc = $oldState->cyxDoc;
        $this->typeModel = $oldState->typeModel;
        $this->origin = $oldState->origin;
        $this->apiUuid = $oldState->apiUuid;
    }

    public function log($msg, $category = null)
    {
        $class = $this->getShortClassName(get_called_class());

        if (!empty($this->document)) {
            $msg = $this->document->direction . ' ' . $this->document->type . ' ' . $this->document->id . ' '
                    . $class . ': ' . $msg;
        } else {
            $msg = $class . ': ' . $msg;
        }

        if (!$category) {
            $category = $this->logCategory;
        }

        if ($category) {
            Yii::info($msg, $category);
        } else {
            Yii::info($msg);
        }
    }

    public function logError($msg)
    {
        $class = $this->getShortClassName(get_called_class());

        if (!empty($this->document)) {
            $msg = $this->document->direction . ' ' . $this->document->type . ' ' . $this->document->id . ' '
                    . $class . ': ' . $msg;
        } else {
            $msg = $class . ': ' . $msg;
        }

        Yii::error($msg);
    }

    protected function getShortClassName($class)
    {
        return substr($class, strrpos($class, '\\') + 1);
    }

    public function run()
    {
        $elapsed = microtime(true);

        if ($this->status) {
            $keys = array_keys($this->_steps);
            $this->_stepPosition = array_search($this->status, $keys);

            if ($this->_stepPosition === false) {
                $this->cleanup();
                return false;
            }
        }

        $result = true;

        while ($result === true && !$this->_isStopped && $this->nextStep()) {
            try {
                if ($this->step->name) {
                    $this->_stepStats[] = $this->step->name;
                }
                $result = $this->step->run();
            } catch(Throwable $ex) {
                $result = false;
                $this->log('State step exception: ' . $ex);
            }

            if ($result === true) {
                $this->step->onSuccess();
                $this->attempt = 0;
            } else {
                $isRetryPossible = false;
                if (isset($this->_retrySteps[$this->status])) {

                    $retryParams = $this->_retrySteps[$this->status];

                    if ($this->attempt < $retryParams['attempts']) {
                        $this->attempt++;
                        $isRetryPossible = $this->step->onRetry($retryParams);
                    }
                }

                if (!$isRetryPossible) {
                    if ($this->step->onFail() !== false) {
                        $this->onStepFail();
                    }
                }
            }

            $this->step->cleanup();
        }

        $this->log(implode('|', $this->_stepStats) . ' ' . number_format(microtime(true) - $elapsed, 2));
        $this->cleanup();

        return $result;
    }

    public function nextStep()
    {
        if ($this->_stepPosition < count($this->_steps)) {
            return $this->getStep($this->_stepPosition++);
        }

        $this->step = null;

        return null;
    }

    public function getStep($position)
    {
        $stepKeys = array_keys($this->_steps);
        $key = $stepKeys[$position];

        $currentStep = $this->_steps[$key];

        if (is_null($currentStep)) {
            $currentStep = $this->decideStep();
        }

        $this->status = $key;

        if (!is_null($currentStep)) {
            if (is_array($currentStep)) {
                $stepClass = array_keys($currentStep)[0];
                $stepParams = $currentStep[$stepClass];
                $this->step = new $stepClass($stepParams);
            } else {
                $this->step = new $currentStep();
            }
            $this->step->state = $this;
        } else {
            $this->step = null;
        }

        return $this->step;
    }

    protected function decideStep()
    {
        return null;
    }

    public function decideState()
    {
        return null;
    }

    public function save($interval = 0)
    {
        if (!$this->dataModel) {
            $this->dataModel = new StateAR([
                'code' => get_called_class(),
            ]);
        }

        $now = date_create();
        $retryTimestamp = date_timestamp_get($now) + $interval;

        $attributes = [
            'terminalId' => $this->terminalId,
            'dateRetry' => date('Y-m-d H:i:s', $retryTimestamp),
            'documentId' => $this->document->id,
            'status' => $this->status,
            'data' => serialize(['attempt' => $this->attempt])
        ];

        $this->dataModel->setAttributes($attributes);

        $result = $this->dataModel->save();

        if (!$result) {
            $this->log('Could not save state: ' . var_export($this->dataModel->errors, true));
        }

        return $result;
    }

    public function packString($s)
    {
        if (strlen($s) < 1024) {
            return $s;
        }

        return substr($s, 0, 512) . '...' . substr($s, -256);
    }

    public function onStepFail()
    {
        if ($this->origin === Document::ORIGIN_API) {
            $this->reportApiImportError();
        }
    }

    public function getLogCallback(): callable
    {
        return function(string $message, bool $isError = false) {
            if ($isError) {
                $this->logError($message);
            } else {
                $this->log($message);
            }
        };
    }

    public function addApiImportError(string $errorMessage): void
    {
        $this->apiImportErrors[] = $errorMessage;
    }

    private function reportApiImportError(): void
    {
        $errors = $this->apiImportErrors ?: [$this->getDefaultApiImportErrorMessage()];
        ApiModule::storeApiImportErrors($this->apiUuid, $errors);
    }

    protected function getDefaultApiImportErrorMessage(): ?string
    {
        return Yii::t('document', 'Failed to import document');
    }

    protected function cleanup(): void
    {
    }
}
