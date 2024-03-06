<?php

namespace addons\swiftfin\jobs;

use common\base\DocumentJob;
use Exception;
use Yii;

/**
 * Print job class
 */
class PrintJob extends DocumentJob
{
    /**
     * @var string $_printCommand Print command
     */
    private $_printCommand = 'lp';

    /**
     * @inheritdoc
     */
    public function setUp()
    {
        $this->_logCategory = 'SwiftFin';
        $this->_module = Yii::$app->getModule('swiftfin');

        parent::setUp();
    }

    /**
     * @inheritdoc
     */
    public function perform()
    {
        try {
            $currentSetup = Yii::$app->getModule('swiftfin')->settings->autoPrintMt;

            if (array_key_exists($this->_document->type, $currentSetup)) {
                $this->log("Send document ID[{$this->_documentId}] to print");

                $typeModel = $this->_cyxDocument->getContent()->getTypeModel();

                $result = $this->printDocument($typeModel->getSource()->getContentPrintable());
                $message = ($result) ? "Document ID[{$this->_documentId}] was printed" : "Print document ID[{$this->_documentId}] failed";

                $this->log($message);
            }
        } catch (Exception $ex) {
            $this->log($ex->getMessage());
        }
    }

    /**
     * Print document
     *
     * @param string $data Data for print
     * @return boolean
     * @throws Exception
     */
    protected function printDocument($data)
    {
        try{
            $pipes = null;
            $descriptorspec = [
                0 => ['pipe', 'r'],
                1 => ['pipe', 'w'],
                2 => ['pipe', 'w'],
            ];

            $process = proc_open($this->_printCommand, $descriptorspec, $pipes);
            if (!is_resource($process)){
                throw new Exception('Open LP process command error');
            }

            fwrite($pipes[0], $data);
            fclose($pipes[0]);

            $error = stream_get_contents($pipes[2]);
            fclose($pipes[1]);
            fclose($pipes[2]);
            proc_close($process);

            if (!empty($error)) {
                throw new Exception($error);
            }

            return true;
        } catch (Exception $ex) {
            $this->log($ex->getMessage());

            return false;
        }
    }

}