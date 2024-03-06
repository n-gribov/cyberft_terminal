<?php
namespace common\states\in;

use common\base\BaseDocumentState;

class InitialInState extends BaseDocumentState
{
    public $queue;
    public $storedFileId;

    protected $_steps = [
        'register' => 'common\states\in\DocumentRegisterStep',
    ];

    public function decideState()
    {
        /**
         * Проверка корректности регистрации сделана в шаге DocumentValidateStep в последующих состояниях
         */

        if ($this->document->isServiceType()) {
            return new ServiceInState();
        }

        return new DocumentInState();
    }

}
