<?php
namespace addons\ISO20022\models;

use addons\ISO20022\models\traits\WithAttachments;
use common\models\listitem\AttachedFile;
use addons\ISO20022\models\traits\WithSignature;
use yii\helpers\ArrayHelper;

class Auth024Type extends ISO20022Type
{
    use WithAttachments;
    use WithSignature;

    const TYPE = 'auth.024';

    public $fileName;

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [array_values($this->attributes()), 'safe'],
        ]);
    }

    public function getType()
    {
        return self::TYPE;
    }

    protected function parseXml($xml = null)
    {
        $this->msgId = (string) $this->_xml->PmtRgltryInfNtfctn->GrpHdr->MsgId;
        $this->dateCreated = (string) $this->_xml->PmtRgltryInfNtfctn->GrpHdr->CreDtTm;
        $this->mmbId = (string) $this->_xml->PmtRgltryInfNtfctn->TxNtfctn->AcctSvcr->FinInstnId->ClrSysMmbId->MmbId;

        if (isset($this->_xml->PmtRgltryInfNtfctn->TxNtfctn->TxCert->CertRcrd->Attchmnt->URL)) {
            $this->fileName = (string) $this->_xml->PmtRgltryInfNtfctn->TxNtfctn->TxCert->CertRcrd->Attchmnt->URL;
        }
    }

    /**
     * Метод возвращает поля для поиска в ElasticSearch
     * @return bool
     */
    public function getSearchFields()
    {
        return false;
    }

    public function getOperations()
    {
        $xml = $this->getRawXml();

        return $xml->PmtRgltryInfNtfctn->TxNtfctn->TxCert->CertRcrd;
    }

    public function getAttachmentNodes($operations = [])
    {
        $out = [];

        if (empty($operations)) {
            $operations = $this->getOperations();
        } else if (!is_array($operations)) {
            $operations = [$operations];
        }

        foreach($operations as $op) {
            $attachments = $op->Attchmnt;
            foreach($attachments as $attachment) {
                $out[] = $attachment;
            }
        }

        return $out;
    }

    public function getAttachedFileList()
    {
        $attachedFiles = [];

        $operations = $this->getOperations();
        $pos = 0;
        foreach($operations as $op) {
            $nodes = $this->getAttachmentNodes($op);
            foreach($nodes as $node) {
                $fileName = (string) $node->URL;
                $fixedName = $fileName;
                if (substr($fileName, 0, 7) == 'attach_') {
                    $fixedName = substr($fileName, 7);
                }

                $attachedFiles[$pos][] = new AttachedFile([
                    'name' => $fixedName,
                    'path' => $fileName
                ]);
            }

            $pos++;
        }

        return $attachedFiles;
    }

    public function getSenderAccountNumber(): ?string
    {
        return $this->getValueByXpath("/a:Document/a:PmtRgltryInfNtfctn/a:TxNtfctn/a:TxCert/a:Acct/a:Id/a:Othr/a:Id");
    }

    private function getValueByXpath(string $xpath): ?string
    {
        $xml = $this->_xml;
        $nodes = $xml->xpath($xpath);

        if (!empty($nodes)) {
            return (string)$nodes[0];
        }
        return null;
    }
}
