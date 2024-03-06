<?php

namespace addons\edm\jobs;

use common\jobs\BaseDocumentSignJob;

class VTBRegisterSignJob extends BaseDocumentSignJob
{
    protected function injectSignature()
    {
        $typeModel = $this->_cyxDoc->getContent()->getTypeModel();

        $signatures = is_array($this->_signature)
            ? $this->_signature
            : [$this->_signature];

        foreach ($typeModel->paymentOrders as $index => $vtbPayDoc) {
            $isInjected = $vtbPayDoc->injectSignature($signatures[$index], $this->_certBody);
            if (!$isInjected) {
                $this->log('Failed to inject signature into type model');
                return false;
            }
        }

        $this->_cyxDoc->getContent()->markDirty();

        return true;
    }
}