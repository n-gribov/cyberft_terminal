<?php

namespace addons\edm\jobs;

use addons\edm\models\PaymentOrder\PaymentOrderDocumentExt;
use common\base\Job;
use common\document\Document;
use common\helpers\DocumentHelper;
use common\models\cyberxml\CyberXmlDocument;
use Resque_Job_DontPerform;
use Exception;
use Yii;

/**
 * Edm processing incoming provCSV job class
 */
class EdmInProvCSVJob extends Job
{
    /**
     * @var array $_provCSV Array of payment order reports
     */
    private $_provCSV;

    /**
     * @inheritdoc
     */
    public function setUp()
    {
        parent::setUp();

        $csvData = $this->getCSVData();
        if ($csvData === false) {
            throw new Resque_Job_DontPerform('Invalid CSV data');
        }

        /**
         * Remove first title row
         */
        if (count($csvData) > 1) {
            array_shift($csvData);
        }

        if (count($csvData) === 0) {
            $this->log('Empty data');
        }

        $this->_provCSV = $csvData;
    }

    /**
     * @inheritdoc
     */
    public function perform()
    {
        foreach ($this->_provCSV as $value) {
            $csv = str_getcsv(trim($value), ';');
            if (!$this->checkProvCSVData($csv)) {
                $this->log('Wrong format');

                continue;
            }

            $documentNumber = $csv[10];

            $this->log("Searching for payment document(s) with number [{$documentNumber}]");

            $paymentOrders = $this->getPaymentDocument($documentNumber);
            if (empty($paymentOrders)) {
                $this->log("Payment document(s) with number [{$documentNumber}] not found");

                continue;
            }

            $this->log("Payment document(s) [{$documentNumber}] found, starting update");

            $result = $this->updatePayment($paymentOrders, $csv[3], $csv[2]);
            if ($result) {
                $this->log("Payment document(s) [{$documentNumber}] updated sucessfully");
            } else {
                $this->log("Payment document(s) [{$documentNumber}] update error");
            }
        }
    }

    /**
     * Get provCSV data
     *
     * @return array|boolean Return array or provcsv data or false
     */
    private function getCSVData()
    {
        $provCSVId = (isset($this->args['id'])) ? $this->args['id'] : null;
        if (is_null($provCSVId)) {
            $this->log('Document ID must be set');

            return false;
        }

        $provCSV = Document::findOne($provCSVId);
        if (is_null($provCSV)) {
            $this->log('Document ' . $provCSVId . ' not found');

            return false;
        }

        if ($provCSV->type !== 'PROVCSV') {
            $this->log('Document ' . $provCSVId . ' is not a ProvCSV');

            return false;
        }

        $cyxDocument = CyberXmlDocument::read($provCSV->actualStoredFileId);
        $rawData = $cyxDocument->content->rawData;
        if (empty($rawData)) {
            $this->log('Empty CyberXml document raw data');

            return false;
        }

        return explode("\n", $rawData);
    }

    /**
     * Update payment.
     *
     * Set "Due" status for main document.
     * Set processing and due dates for extension document.
     *
     * @param array  $paymentOrders   List of extension documents
     * @param string $dateDue         Document due date
     * @param string $dateProcessing  Document processing date
     * @return boolean
     * @throws Exception
     */
    protected function updatePayment($paymentOrders, $dateDue, $dateProcessing)
    {
        $transaction = Yii::$app->db->beginTransaction();

        try {
            foreach ($paymentOrders as $order) {
                $order->dateProcessing = $dateProcessing;
                $order->dateDue        = $dateDue;
                if (!$order->save()) {
                    $this->log(print_r($order->errors, true));
                    throw new Exception('Error updating extension document ' . $order->id);
                }

                $documentResult = DocumentHelper::updateDocumentStatusById($order->documentId, Document::STATUS_EXECUTED);
                if (!$documentResult) {
                    throw new Exception('Error updating document ' . $order->documentId);
                }
            }

            $transaction->commit();

            return true;
        } catch (Exception $ex) {
            $this->log($ex->getMessage());
            $transaction->rollBack();

            return false;
        }
    }

    /**
     * Get all payment document with specific number
     *
     * @param string $number Payment document number
     * @see http://www.yiiframework.com/doc-2.0/yii-db-baseactiverecord.html#findAll()-detail
     * @return array
     */
    protected function getPaymentDocument($number)
    {
        return PaymentOrderDocumentExt::findAll(['number' => $number]);
    }

    /**
     * Check prov csv data
     *
     * @param array $csv Array of csv data
     * @return boolean
     */
    protected function checkProvCSVData($csv)
    {
        if (!isset($csv[10])) {
            $this->log('Empty document number');

            return false;
        }

        $number = (int) $csv[10];
        if ($csv[10] != $number) {
            $this->log("Number is not a digit: [{$csv[10]}] != [{$number}]");

            return false;
        }

        return true;
    }

}