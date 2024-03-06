<?php
namespace addons\fileact\helpers;

use common\components\TerminalId;
use common\document\Document;
use common\modules\certManager\models\Cert;
use SimpleXMLElement;
use ZipArchive;

class FileActHelper
{
	/**
	 * Сохраняет ZIP-файл в указанную временную папку
	 * @param Document $document
	 * @param string $tempPath
	 * @return string $filePath сохраненного файлв
	 */
	public static function extractZipFile($document, $tempPath = null)
    {
		if (!$tempPath) {
			$tempPath = \Yii::getAlias('@temp');
		}
		
		$filePath = $tempPath . '/zip' . getmypid() . uniqid() . '.zip';
		file_put_contents($filePath, self::extractRawData($document));

		return $filePath;
	}

	/**
	 * Возвращает строковое значение RawData из XML,
	 * раскодированное из base64
	 * @param type $document
	 * @return string rawdata
	 */
	public static function extractRawData($document)
	{
		$domDoc = $document->container->getXml();

		return base64_decode($domDoc->getElementsByTagName('RawData')->item(0)->nodeValue);
	}

	/**
	 * Возвращает список распакованных файлов
	 * @param string $filePath
	 * @return array
	 */
	public static function unpackZipFile($filePath, $extractPath)
	{
		$fileList = array();
		$zip = new ZipArchive();

		if ($zip->open($filePath) === TRUE) {
			if (!is_dir($extractPath)) {
				mkdir($extractPath);
			}
			$zip->extractTo($extractPath);
			$zip->close();
			$dirHandle = opendir($extractPath);
			if ($dirHandle) {
				while (($fileName = readdir($dirHandle)) !== false) {
					$srcPath = $extractPath . $fileName;
					if (is_file($srcPath)) {
						$fileList[] = $srcPath;
					}
				}
				closedir($dirHandle);
			}
		}
		return $fileList;
	}

    /**
     * Extract 8 letter BIC
     *
     * @param string $name BIC name
     * @return string
     */
	public static function extractBIC8($name)
	{
		return strtolower(substr($name, 0, 8));
	}

    public static function generateBinHeader($pduString)
    {
		/**
		 * CYB-1336
		 * Здесь считаем бинарный заголовок для PDU. Он имеет длину 31 байт и состоит из следующих полей:
		 * 1 байт - константа 0x1f
		 * 6 байт - сумма длин сигнатуры и тела pdu, в текстовом виде, выровнено нулями.
		 * 24 байта - сигнатура. Должна считаться как SHA-265, у нас не считается, заполняется 0x0.
		 * Длина сигнатуры у нас всегда 24 байта. Значит, к длине pdu просто добавляем 24.
		 */
        $count = str_pad(strlen($pduString) + 24, 6, '0', STR_PAD_LEFT);
		$binHeader = chr(0x1f) . $count . str_repeat(chr(0), 24);

        return $binHeader;
    }

	private static function getSnlId($sender)
    {
		$res = TerminalId::extract($sender);
		$cert = Cert::findOne([
			'participantCode' => $res->participantCode,
			'countryCode' => $res->countryCode,
			'sevenSymbol' => $res->sevenSymbol,
			'delimiter' => $res->delimiter,
			'terminalCode' => $res->terminalCode,
			'participantUnitcode' => $res->participantUnitCode
		]);
		if ($cert) {
			return str_pad($cert->id, 5, '0', STR_PAD_LEFT);
		} else {
			return null;
		}
	}

    public static function generatePDU($uuid, $sender, $receiver, $binFileName)
    {
        $organization = 'cyberplat';
        $xml = new SimpleXMLElement('<Saa:DataPDU xmlns:Saa="urn:swift:saa:xsd:saa.2.0"'
                . ' xmlns:Sw="urn:swift:snl:ns.Sw"'
                . ' xmlns:SwInt="urn:swift:snl:ns.SwInt" xmlns:SwGbl="urn:swift:snl:ns.SwGbl"'
                . ' xmlns:SwSec="urn:swift:snl:ns.SwSec"></Saa:DataPDU>');

        $service = 'cyberft_fileact';

        $xml->Revision = '2.0.2';
        $xml->Header->Message->SenderReference = $uuid;
        $xml->Header->Message->MessageIdentifier = 'fileact';
        $xml->Header->Message->Format = 'File';
        $xml->Header->Message->Sender->DN = 'o=' . self::extractBIC8($sender) . ',o=' . $organization;
        $xml->Header->Message->Sender->FullName->X1 = $sender;
        $xml->Header->Message->Receiver->DN = 'o=' . self::extractBIC8($receiver) . ',o=' . $organization;
        $xml->Header->Message->Receiver->FullName->X1 = $receiver;
        $xml->Header->Message->InterfaceInfo->UserReference = $uuid;
        $xml->Header->Message->NetworkInfo->Service = $service;
        $xml->Body = $binFileName;

        return $xml;
    }

}
