<?php
namespace addons\ISO20022\models;

use addons\ISO20022\models\traits\WithAttachments;
use common\helpers\ZipHelper;
use common\models\listitem\AttachedFile;
use yii\helpers\ArrayHelper;

class Auth025Type extends ISO20022Type
{
    use WithAttachments;
    use traits\WithSignature;

    const TYPE = 'auth.025';

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
        $this->msgId = (string) $this->_xml->CcyCtrlSpprtgDocDlvry->GrpHdr->MsgId;
        $this->dateCreated = (string) $this->_xml->CcyCtrlSpprtgDocDlvry->GrpHdr->CreDtTm;
        $this->mmbId = (string) $this->_xml->CcyCtrlSpprtgDocDlvry->SpprtgDoc->AcctSvcr->FinInstnId->ClrSysMmbId->MmbId;

        if (isset($this->_xml->CcyCtrlSpprtgDocDlvry->SpprtgDoc->Ntry->Attchmnt->URL)) {
            $this->fileName = (string) $this->_xml->CcyCtrlSpprtgDocDlvry->SpprtgDoc->Ntry->Attchmnt->URL;
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

    public function getAttachmentNodes(): array
    {
        return $this->getByXpath('/a:Document/a:CcyCtrlSpprtgDocDlvry/a:SpprtgDoc/a:Ntry/a:Attchmnt');
    }

    public function getDocumentsNodes(): array
    {
        return $this->getByXpath('/a:Document/a:CcyCtrlSpprtgDocDlvry/a:SpprtgDoc/a:Ntry');
    }

    /**
     * @param string $xpath
     * @return \SimpleXMLElement[]
     */
    private function getByXpath(string $xpath): array
    {
        return $this->getRawXml()->xpath($xpath) ?: [];
    }

    /**
     * @return AttachedFile[][]
     */
    public function getAttachedFileList(): array
    {
        return array_map(
            function (\SimpleXMLElement $documentElement) {
                return $this->getDocumentAttachedFileList($documentElement);
            },
            $this->getDocumentsNodes()
        );
    }

    private function getDocumentAttachedFileList(\SimpleXMLElement $documentElement): array
    {
        return array_map(
            function (\SimpleXMLElement $attchmnt) {
                $filePath =  (string)$attchmnt->URL;
                $fileName = preg_replace('/^attach_/', '', $filePath);
                return new AttachedFile([
                    'name' => $fileName,
                    'path' => $filePath,
                ]);
            },
            $documentElement->xpath("./*[local-name() = 'Attchmnt']")
        );
    }

    /**
     * @param string $targetDirPath
     * @return AttachedFile[][]
     */
    public function extractAttachedFiles(string $targetDirPath): array
    {
        function saveFile($filePath, $content) {
            $saveResult = file_put_contents($filePath, $content);
            if ($saveResult === false) {
                throw new \Exception("Failed to save file to $filePath");
            }
        }

        $attachedFiles = $this->getAttachedFileList();

        if ($this->useZipContent) {
            $zip = ZipHelper::createArchiveFileZipFromString($this->zipContent);
            $filesFromZip = $zip->getFileList('cp866');
            foreach ($attachedFiles as $documentAttachedFiles) {
                foreach ($documentAttachedFiles as $attachedFile) {
                    $fileIndex = array_search($attachedFile->path, $filesFromZip);
                    if ($fileIndex === false) {
                        throw new \Exception("File $attachedFile->path is not found in zip archive");
                    }
                    $content = $zip->getFromIndex($fileIndex);
                    $filePath = "$targetDirPath/$fileIndex";
                    saveFile($filePath, $content);
                    $attachedFile->path = $filePath;
                }
            }
        } else {
            foreach ($attachedFiles as $documentIndex => $documentAttachedFiles) {
                foreach ($documentAttachedFiles as $attachedFileIndex => $attachedFile) {
                    $content = $this->getEmbeddedAttachmentContent($documentIndex, $attachedFileIndex);
                    $filePath = "$targetDirPath/$documentIndex-$attachedFileIndex";
                    saveFile($filePath, $content);
                    $attachedFile->path = $filePath;
                }
            }
        }

        return $attachedFiles;
    }

    public function getEmbeddedAttachmentContent(int $documentIndex, int $attachmentIndex): ?string
    {
        $documentElement = $this->getDocumentsNodes()[$documentIndex] ?? null;
        if ($documentElement === null) {
            return null;
        }
        $attachmentElement = $documentElement->xpath("./*[local-name() = 'Attchmnt']")[$attachmentIndex] ?? null;
        if ($attachmentElement === null) {
            return null;
        }
        if (isset($attachmentElement->AttchdBinryFile->InclBinryObjct)) {
            return base64_decode($attachmentElement->AttchdBinryFile->InclBinryObjct);
        }
        return null;
    }

    public function getSenderTxId(): ?string
    {
        return $this->getByXpath("/a:Document/a:CcyCtrlSpprtgDocDlvry/a:SpprtgDoc/a:AcctOwnr/a:Id/a:OrgId/a:Othr[a:SchmeNm/a:Cd/text()='TXID']/a:Id")[0] ?? null;
    }

    public function getReceiverRuCbc(): ?string
    {
        return $this->getByXpath("/a:Document/a:CcyCtrlSpprtgDocDlvry/a:SpprtgDoc/a:AcctSvcr/a:FinInstnId/a:ClrSysMmbId[a:ClrSysId/a:Cd/text()='RUCBC']/a:MmbId")[0] ?? null;
    }
}
