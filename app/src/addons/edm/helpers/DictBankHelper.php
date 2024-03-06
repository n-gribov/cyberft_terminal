<?php
namespace addons\edm\helpers;

use SplFileInfo;
use common\extensions\Dbf;
use addons\edm\models\DictBank;
use yii\base\Exception;
use yii\log\Logger;
use Yii;

abstract class DictBankHelper
{
	/**
	 * @param string $path
	 * @return array
	 * @throws Exception
	 */
	public static function syncFile($path)
        {            
            $log = [];
            $zip = new \ZipArchive();
            $yiidb = Yii::$app->db;
            $values = [];
            $openStatus = $zip->open($path);

            // Check archive
            if ($openStatus !== true) {
                throw new Exception(\Yii::t('edm', 'Error opening archive'));
            }
            
            if (($src = $zip->getStream('BNKSEEK.DBF')) || ($src = $zip->getStream('bnkseek.dbf'))) {
                $dbf = new Dbf($src);
                $count = $dbf->recsNum();

                for ($i = 0; $i < $count; $i++) {
                    $row = $dbf->nextRec();
                    $values[] = '' . implode(',', [
                        $yiidb->quoteValue($row['NEWNUM']),
                        empty($row['OKPO']) ? 'NULL' : $yiidb->quoteValue($row['OKPO']),
                        empty($row['KSNP']) ? 'NULL' : $yiidb->quoteValue($row['KSNP']),
                        empty($row['NAMEP'])? 'NULL' : $yiidb->quoteValue($row['NAMEP']),
                        empty($row['NNP']) ? 'NULL' : $yiidb->quoteValue($row['NNP']),
                        empty($row['ADR']) ? 'NULL' : $yiidb->quoteValue($row['ADR']),
                        empty($row['IND']) ? 'NULL' : $yiidb->quoteValue($row['IND']),
                    ]) . '';
                    $log[] = ["{$row['NEWNUM']}: {$row['NAMEP']}", Logger::LEVEL_INFO];
                }                
            } else if ($zip->numFiles == 1) {
                //CYB-4337 Поддержка справочников в формате XML
                $xmlName = $zip->getNameIndex(0);
                $info = new SplFileInfo($xmlName);
                if ($info->getExtension() !== 'xml'){
                    throw new Exception('Not XML');
                } else {
                    $src = $zip->getFromName($xmlName);
                    $xml = new \SimpleXMLElement($src);

                    $quoteValue = function (?string $value) use ($yiidb): string {
                        return empty($value) ? 'NULL' : $yiidb->quoteValue($value);
                    };

                    $addError = function (string $bic, string $name, string $errorMessage) use (&$log): void {
                        $log[] = [
                            "$bic, $name: $errorMessage",
                            Logger::LEVEL_ERROR
                        ];
                    };

                    $hardcodedTreasuryAccounts = [
                        '011117401' => '40102810045370000016',
                        '024501901' => '40102810045370000002',
                    ];

                    foreach ($xml->BICDirectoryEntry as $entry){
                        $isBank = substr($entry['BIC'],0,2) === '04';
                        $isTreasury = in_array((string)$entry->ParticipantInfo[0]['PtType'], ['51', '52']);

                        if (!$isBank && !$isTreasury){
                            continue;
                        }

                        $bic = (string)$entry['BIC'];
                        $accountEntry = null;
                        if ($isTreasury) {
                            $entries = [];
                            foreach ($entry->Accounts as $currentAccount){
                                $accountNumber = (string)$currentAccount['Account'];
                                if ((string)$currentAccount['RegulationAccountType'] === 'UTRA') {
                                    $entries[$accountNumber] = $currentAccount;
                                }
                            }
                            if (count($entries) > 1) {
                                $hardcodedAccount = $hardcodedTreasuryAccounts[$bic] ?? null;
                                if ($hardcodedAccount && array_key_exists($hardcodedAccount, $entries)) {
                                    $accountEntry = $entries[$hardcodedAccount];
                                } else {
                                    $addError(
                                        $bic,
                                        (string)$entry->ParticipantInfo[0]['NameP'],
                                        Yii::t('edm', 'record is not added/updated because multiple treasury accounts found')
                                    );
                                    continue;
                                }
                            } elseif (count($entries) === 1) {
                                $accountEntry = array_values($entries)[0];
                            }
                        } else {
                            foreach ($entry->Accounts as $currentAccount){
                                if ((string)$currentAccount['RegulationAccountType'] === 'CRSA') {
                                    $accountEntry= $currentAccount;
                                    break;
                                }
                            }
                        }

                        $name = (string)($entry->ParticipantInfo[0]['NameP'] ?? null);
                        if ($isTreasury) {
                            if ($accountEntry === null) {
                                continue;
                            }
                            $accountBic = (string)$accountEntry['AccountCBRBIC'] ?: null;
                            $bankInfo = $accountBic ? self::getBankParticipantInfo($xml, $accountBic) : null;
                            if ($bankInfo === null) {
                                $addError(
                                    $bic,
                                    (string)$entry->ParticipantInfo[0]['NameP'],
                                    Yii::t('edm', 'record is not added/updated because bank for treasury account is not found in dictionary')
                                );
                                continue;
                            }
                            $name = "{$bankInfo['NameP']}//$name";
                        }

                        $values[] = '' . implode(',', [
                            $quoteValue($bic),
                            'NULL',
                            $quoteValue((string)$accountEntry['Account']),
                            $quoteValue($name),
                            $quoteValue((string)$entry->ParticipantInfo[0]['Nnp']),
                            $quoteValue((string)$entry->ParticipantInfo[0]['Adr']),
                            $quoteValue((string)$entry->ParticipantInfo[0]['Ind']),
                        ]) . '';
                    }
                    
                }
            } else {
                throw new Exception(\Yii::t('edm', 'Broken archive, file {file} not found', ['file' => 'BNKSEEK.DBF/*.XML']));               
            }

                if (!$values){
                    throw new Exception(\Yii::t('edm', 'Parsing file error'));
                }            
            
                $cmd = $yiidb->createCommand(
                    'INSERT INTO ' . DictBank::tableName() . ' (`bik`, `okpo`, `account`, `name`, `city`, `address`, `postalCode`)
                    VALUES (' . implode("),\n (", $values) . ')
                    ON DUPLICATE KEY UPDATE
                            okpo=VALUES(okpo),
                            account=VALUES(account),
                            name=VALUES(name),
                            city=VALUES(city),
                            address=VALUES(address),
                            postalCode=VALUES(postalCode)
            ');   
            
            $changed =  $cmd->execute();
            
            Yii::info('changed: '.$changed);

            array_unshift($log, ["Всего изменено/добавлено объектов: $changed", Logger::LEVEL_INFO]);
            array_unshift($log, ['Время выполнения: ' . round(Yii::getLogger()->getElapsedTime(), 3) . 'c', Logger::LEVEL_INFO]);

            return $log;
	}

    private static function getBankParticipantInfo(\SimpleXMLElement $xml, string $bic): ?\SimpleXMLElement
    {
        $bank = $xml->xpath("//*[local-name() = 'BICDirectoryEntry' and @BIC = '$bic']")[0] ?? null;
        return $bank->ParticipantInfo ?? null;
    }
}