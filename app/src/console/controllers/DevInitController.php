<?php
namespace console\controllers;

use addons\edm\helpers\DictBankHelper;
use addons\edm\models\DictBank;
use addons\edm\models\DictOrganization;
use addons\edm\models\EdmPayerAccount;
use addons\swiftfin\helpers\DictBankReader;
use addons\swiftfin\models\SwiftFinDictBank;
use common\helpers\ArchiveFile;
use common\helpers\FileHelper;
use common\models\Terminal;
use InvalidArgumentException;
use Yii;
use yii\console\Controller;

class DevInitController extends Controller
{
    public function actionIndex()
    {
        // webdriver/dev_suite/002, 005 , 006. 016, 017, 122
        // 1. dictbank
        $result = DictBank::find()->count();
        if (!$result) {
            $filename = Yii::getAlias('@console/controllers/initdata/bik_db_17112015.zip');

            if (is_readable($filename)) {
                DictBankHelper::syncFile($filename);
                echo "DictBank loaded\n";
            } else {
                echo "DictBank file not found: $filename\n";
            }
        } else {
            echo "DictBank already exists\n";
        }

        // 2. SWIFT-codes
        $filename = Yii::getAlias('@console/controllers/initdata/ALLIANCEBANK_WIN_FULL_20141004_DAT.zip');
        $this->loadSwiftCodeDict($filename);

        $terminal = Terminal::findOne(['isDefault' => 1]);

        if (!$terminal) {
            echo "Default terminal not found\n";

            return;
        }

        echo "Default terminal: {$terminal->terminalId}\n";

        // 3. organization
        $org = $this->createOrganization($terminal);
        if (!$org) {
            return;
        }

        // 4. account
        if (!$this->createAccounts($org)) {
            return;
        }

        // 5. Добавление terminalId для БИК 044525931
        $bank = DictBank::findOne(['bik' => '044525931']);
        if (!$bank) {
            echo "Bank 044525931 not found\n";

            return;
        }

        $bank->terminalId = $terminal->terminalId;
        if (!$bank->save()) {
            echo 'Failed to update terminal id for bank 044525931: ' . var_export($bank->errors, true) . "\n";

            return;
        }

        echo "Bank 044525931 terminal id updated\n";
    }

    private function createOrganization($terminal)
    {
        $org = DictOrganization::find()->one();
        if ($org) {
            echo "Organization already exists\n";

            return $org;
        }

        $org = new DictOrganization([
            'terminalId' => $terminal->id,
            'name' => 'ООО "' . $terminal->terminalId . '"',
            'type' => DictOrganization::TYPE_ENTITY,
            'inn' => '6450013459',
            'kpp' => '645001001',
            'ogrn' => '1234567890123',
            'propertyTypeCode' => 'ООО',
            'city' => 'Москва',
            'locality' => 'Москва',
            'district' => 'Красная Пресня',
            'street' => 'Краснопресненская набережная',
            'buildingNumber' => '12',
            'building' => '12',
            'apartment' => '12',
            'dateEgrul' => date('d.m.Y'),
            'nameLatin' => 'CyberFT Organization',
            'addressLatin' => 'Krasnopresnenskaya 12',
            'locationLatin' => 'Russia,Moscow',
        ]);

        if ($org->save()) {
            echo "Organization created\n";
        } else {
            echo 'Failed to create organization: ' . var_export($org->errors, true) . "\n";

            return false;
        }

        return $org;
    }

    private function createAccounts($org)
    {
        $account = EdmPayerAccount::find()->one();
        if ($account) {
            echo "Account(s) already exist\n";

            return true;
        }

        $accountData = [
            [
                'name' => 'АО "Экономбанк"',  'organizationId' => $org->id,
                'number' => '30109810900000000330', 'currencyId' => 1, 'bankBik' => '044525931',
            ],
            [
                'name' => 'ООО "КИБЕРПЛАТ"', 'organizationId' => $org->id,
                'number' => '40702810200000000317', 'currencyId' => 1, 'bankBik' => '044525931'
            ],
            [
                'name' => 'Производственные технологии', 'organizationId' => $org->id,
                'number' => '40702840500000003031', 'currencyId' => 10, 'bankBik' => '044525931',
            ]
        ];

        foreach($accountData as $accData) {
            $account = new EdmPayerAccount($accData);
            echo "Creating account '" . $account->name . "'...";

            if ($account->save()) {
                echo "OK\n";
            } else {
                echo "ERROR\n" . var_export($account->errors, true) . "\n";

                return false;
            }
        }

        return true;
    }

    private function loadSwiftCodeDict($filePath)
    {
        $dict = SwiftFinDictBank::find()->one();
        if ($dict) {
            echo "Swift codes are already loaded\n";

            return;
        }

        $archiveReader = ArchiveFile::getHandler($filePath);

        $resource = Yii::$app->registry->getTempResource('swiftfin');
        $dir = $resource->createDir(FileHelper::uniqueName());
        $result = $archiveReader->extract($filePath, $dir);

        if ($result === false) {
            throw new InvalidArgumentException(Yii::t('doc/swiftfin', 'Cannot extract archive'));
        }

        $files = $resource->getContents($dir);
        $dictBankReader = DictBankReader::getReader($files);

        if (empty($dictBankReader)) {
            throw new InvalidArgumentException(Yii::t('doc/swiftfin', 'File format not recognized'));
        }

        /**
         * Бьем общее количество записей на части, чтобы избежать переполнения памяти
         */
        while(true) {
            $values = [];
            $count = 5000;
            while($count--) {
                $record = $dictBankReader->getRecord();
                if (empty($record)) {
                    break;
                }

                $values[] = implode(',', [
                    Yii::$app->db->quoteValue($record['swiftCode']),
                    Yii::$app->db->quoteValue($record['branchCode']),
                    Yii::$app->db->quoteValue($record['name']),
                    Yii::$app->db->quoteValue($record['address']),
                    Yii::$app->db->quoteValue($record['swiftCode'].$record['branchCode'])
                ]);
            }

            if (!count($values)) {
                break;
            }
            /**
             * Здесь используется bulk-вставка с mySQL-специфичной заменой (ON DUPLICATE KEY...)
             * В фреймворке ничего такого нет именно по причине специфичности.
             * Стандартные средства типа проверки уникальности моделей работают очень медленно
             * (в базу грузится более 100 тысяч записей)
             */
            $cmd = Yii::$app->db->createCommand(
                'INSERT INTO ' . SwiftFinDictBank::tableName() . ' (`swiftCode`, `branchCode`, `name`, `address`,`fullCode`)
                VALUES (' . implode("),\n (", $values) . ')
                ON DUPLICATE KEY UPDATE name=VALUES(name), address=VALUES(address)
            ');

            $cmd->execute();
        }

        foreach($files as $file) {
            unlink($file);
        }

        rmdir($dir);

        echo "Swift codes loaded\n";
    }

}
