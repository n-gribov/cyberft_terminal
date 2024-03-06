<?php

namespace addons\SBBOL\states\in\response\processAsyncResponseSteps;

use addons\SBBOL\states\BaseStep;
use addons\SBBOL\states\in\response\ProcessAsyncResponseState;
use DOMDocument;
use Exception;
use LibXMLError;
use Yii;

/**
 * @property ProcessAsyncResponseState $state
 */
class ValidateXmlStep extends BaseStep
{
    public function run()
    {
        $originalLibxmlUseInternalErrorsValue = libxml_use_internal_errors(true);
        $isValid = false;

        try {
            $document = new DOMDocument();
            $document->loadXML($this->state->responseBody);
            $xsdPath = Yii::getAlias('@addons/SBBOL/resources/schema/response/response.xsd');
            $isValid = $document->schemaValidate($xsdPath);
            if (!$isValid) {
//                Yii::info($this->state->responseBody);
                $this->logXmlErrors(libxml_get_errors());
            }
        } catch (Exception $exception) {
            $this->log('Got exception while validating document: ' . $exception);
        } finally {
            libxml_use_internal_errors($originalLibxmlUseInternalErrorsValue);
        }

        // Игнорируем ошибки валидации, т.к. Сбер присылает невалидные выписки
        return true;

//        if (!$isValid) {
//            SBBOLRequest::updateStatus($this->state->requestId, SBBOLRequest::STATUS_RESPONSE_PROCESSING_ERROR);
//        }
//
//        return $isValid;
    }

    /**
     * @param LibXMLError[] $errors
     */
    private function logXmlErrors($errors)
    {
        $errorsStrings = array_map(
            function (LibXMLError $error) {
                return "    Line $error->line, column $error->column: " . trim($error->message);
            },
            $errors
        );

        $this->log("XML validation has failed:\n" . implode(PHP_EOL, $errorsStrings));
    }

}
