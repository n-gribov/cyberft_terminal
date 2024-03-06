<?php

namespace addons\finzip\jobs;

use addons\finzip\FinZipModule;
use addons\finzip\models\FinZipType;
use addons\finzip\settings\FinZipSettings;
use common\base\RegularJob;
use common\document\Document;
use common\helpers\DocumentHelper;
use common\helpers\Uuid;
use common\models\Terminal;
use common\modules\transport\helpers\DocumentTransportHelper;
use PhpImap\IncomingMail;
use PhpImap\IncomingMailAttachment;
use PhpImap\Mailbox;
use Yii;

class ImportFromEmailJob extends RegularJob
{
    /**
     * @var FinZipModule $module
     */
    private $module;
    private $senderTerminalAddress;
    private $receiverTerminalAddress;
    private $mailServerHost;
    private $mailServerPort;
    private $mailServerLogin;
    private $mailServerPassword;

    public function setUp()
    {
        parent::setUp();

        $this->module = Yii::$app->getModule('finzip');

        /** @var FinZipSettings $setting */
        $setting = $this->module->settings;

        if (!$setting->enableImportFromEmailServer) {
            throw new \Resque_Job_DontPerform();
        }

        $this->senderTerminalAddress   = $setting->emailImportSenderTerminalAddress;
        $this->receiverTerminalAddress = $setting->emailImportReceiverTerminalAddress;
        $this->mailServerHost          = $setting->emailImportServerHost;
        $this->mailServerPort          = $setting->emailImportServerPort;
        $this->mailServerLogin         = $setting->emailImportServerLogin;
        $this->mailServerPassword      = $setting->emailImportServerPassword;
    }

    public function perform()
    {
        $attachmentsPath = sys_get_temp_dir() . '/finzip-email-export-' . Uuid::generate(false);
        mkdir($attachmentsPath);

        $mailbox = new Mailbox(
            "{{$this->mailServerHost}:{$this->mailServerPort}/pop3/novalidate-cert}INBOX",
            $this->mailServerLogin,
            $this->mailServerPassword,
            $attachmentsPath
        );

        $mailsIds = $mailbox->searchMailbox('UNSEEN');
        foreach ($mailsIds as $mailId) {
            $mail = $mailbox->getMail($mailId);
            try {
                $this->sendAsFinZipDocument($mail);
                $mailbox->deleteMail($mailId);
            } catch (\Exception $exception) {
                $this->log("Import failed, caused by: $exception");
            }
        }

        array_map('unlink', glob("$attachmentsPath/*"));
        rmdir($attachmentsPath);
    }

    private function sendAsFinZipDocument(IncomingMail $mail)
    {
        $senderTerminal = Terminal::find()->where(['terminalId' => $this->senderTerminalAddress])->one();
        if ($senderTerminal === null) {
            throw new \Exception("Sender terminal {$this->senderTerminalAddress} does not exist");
        }

        $from = $mail->fromName ? "{$mail->fromName} ({$mail->fromAddress})" : $mail->fromAddress;
        $documentMessage = preg_replace('/(\r\n|\r|\n)/', "\r\n", "От: $from\n\n{$mail->textPlain}");

        $typeModel = new FinZipType([
            'subject'   => $mail->subject,
            'descr'     => $documentMessage,
            'sender'    => $this->senderTerminalAddress,
            'recipient' => $this->receiverTerminalAddress,
        ]);

        $mailAttachments = array_values($mail->getAttachments());
        $documentAttachments = array_map(
            function (IncomingMailAttachment $mailAttachment) {
                return [
                    'path'     => $mailAttachment->filePath,
                    'fileName' => $mailAttachment->name,
                ];
            },
            $mailAttachments
        );

        if (!$this->module->saveFinZip($typeModel, $documentAttachments)) {
            throw new \Exception('Error saving zip archive to storage');
        }

        Yii::$app->exchange->setCurrentTerminalId($typeModel->sender);
        // Атрибуты документа
        $docAttributes = [
            'type'               => $typeModel->getType(),
            'direction'          => Document::DIRECTION_OUT,
            'origin'             => Document::ORIGIN_SERVICE,
            'sender'             => $typeModel->sender,
            'receiver'           => $typeModel->recipient,
            'terminalId'         => $senderTerminal->id,
            'signaturesRequired' => 0,
            'isEncrypted' => true
        ];

        // Атрибуты расширяющей модели
        $extModelAttributes = [
            'fileCount'       => $typeModel->fileCount,
            'subject'         => Yii::$app->xmlsec->encryptData($typeModel->subject, true),
            'descr'           => Yii::$app->xmlsec->encryptData($typeModel->descr, true),
            'zipStoredFileId' => $typeModel->zipStoredFileId,
        ];

        // Создать контекст документа
        $context = DocumentHelper::createDocumentContext(
            $typeModel,
            $docAttributes,
            $extModelAttributes
        );

        if ($context === false) {
            throw new \Exception('Failed to create FinZip document');
        }

        // Создать стейт отправки документа
        $sendJobId = DocumentTransportHelper::createSendingState($context['document']);
        if ($sendJobId === false) {
            throw new \Exception('Failed to create sending state');
        }
    }
}
