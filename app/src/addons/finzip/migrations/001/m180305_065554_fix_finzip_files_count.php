<?php

use addons\finzip\models\FinZipDocumentExt;
use common\components\storage\StoredFile;
use yii\db\Migration;

class m180305_065554_fix_finzip_files_count extends Migration
{
    public function up()
    {
        $documentExts = FinZipDocumentExt::find()->all();
        foreach ($documentExts as $documentExt) {
            try {
                $this->processFinZipDocumentExt($documentExt);
            } catch (\Exception $exception) {
                echo "Document {$documentExt->documentId} is not processed: " . $exception->getMessage() . "\n";
            }
        }
    }

    public function down()
    {
        return true;
    }

    private function processFinZipDocumentExt(FinZipDocumentExt $documentExt)
    {
        if (empty($documentExt->zipStoredFileId)) {
            throw new \Exception("No stored zip file found");
        }
        $storedFile = StoredFile::findOne($documentExt->zipStoredFileId);
        if ($storedFile === null) {
            throw new \Exception("Stored file record is not found in database");
        }
        $zipArchive = new \ZipArchive();
        $zipArchive->open($storedFile->getRealPath());
        $documentExt->fileCount = $zipArchive->numFiles;
        $documentExt->save(false, ['fileCount']);
        $zipArchive->close();
    }
}
