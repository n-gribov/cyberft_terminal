<?php
namespace addons\edm\console;

use addons\edm\helpers\Converter;
use addons\edm\models\EdmPayerAccount;
use addons\edm\models\ForeignCurrencyOperation\ForeignCurrencyPaymentTemplate;
use addons\edm\models\SBBOLStatement\SBBOLStatementType;
use addons\edm\models\Statement\StatementTypeConverter;
use addons\ISO20022\models\Camt053Type;
use addons\ISO20022\models\Camt054Type;
use addons\swiftfin\models\SwiftFinDictBank;
use common\base\ConsoleController;
use common\document\Document;
use common\models\cyberxml\CyberXmlDocument;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Edm console controller
 *
 * @package addons
 * @subpackage edm
 */
class DefaultController extends ConsoleController
{
    public function actionIndex() {
		$this->run('/help', ['edm']);
	}

    public function actionTemplateConvert($file, $terminalId)
    {
        $content = file_get_contents($file);

        $entries = explode("\n\n", $content);

        echo 'Total entries: ' . count($entries) . "\n";

        $pos1 = 0;

        foreach($entries as $entry) {

            $fcpt = new ForeignCurrencyPaymentTemplate();

            $fields = explode(':', $entry);

            for ($i = 1; $i < count($fields) - 1; $i += 2) {
                $key = trim($fields[$i]);
                $value = str_replace("\r", '', trim($fields[$i + 1]));
                //echo $key . ' : ' . $value . "\n";

                switch ($key) {
//                    case '20':
//                            $fcpt->number = $value;
//
//                            break;
                    case '70':
                            $fcpt->information = $value;

                            break;
                    case '72':
                            $fcpt->additionalInformation = $value;

                            break;
                    case '71A':
                            $fcpt->commission = $value;

                            break;
                    case '32A':
                            // валюта
                            $fcpt->currency = substr($value, 6, 3);

                            // сумма
                            $fcpt->sum = rtrim(substr($value, 9), ',');

                            break;
                    case '50F':
                            // Счет плательщика
                            $arrValue = explode("\n", $value);

                            $fcpt->payerAccount = str_replace('/', '', $arrValue[0]);

                            $account = EdmPayerAccount::findOne(['number' => $fcpt->payerAccount]);
                            if ($account) {
                                $org = $account->edmDictOrganization;
                                if ($org) {
                                    $fcpt->payerInn = $org->inn;
                                }
                            }

                            // Наименование плательщика
                            $fcpt->payerName = str_replace('1/', '', $arrValue[1]);

                            // Адрес плательщика
                            $fcpt->payerAddress = str_replace('2/', '', $arrValue[2]);

                            // Локация плательщика
                            $fcpt->payerLocation = str_replace('3/', '', $arrValue[3]);

                            break;
                    case '52A':
                            $swiftCode = $value;
                            $fcpt->payerBank = $swiftCode;
                            $swiftBank = SwiftFinDictBank::findOne(['swiftCode' => $swiftCode]);

                            if ($swiftBank) {
                                $fcpt->payerBankName = $swiftBank->name;
                                $fcpt->payerBankAddress = $swiftBank->address;
                            }

                            break;
                    case '56A':

                            $arrValue = explode("\n", $value);
                            $arrLength = count($arrValue);

                            // Получение swift bic
                            $swiftCode = $arrValue[0];
                            $fcpt->intermediaryBank = $swiftCode;
                            $swiftBank = SwiftFinDictBank::findOne(['swiftCode' => $swiftCode]);

                            if (!$swiftBank && $arrLength > 1) {
                                // наименование и адрес указаны вручную
                                $fcpt->intermediaryBankNameAndAddress = implode("\n", $arrValue);
                            }

                            break;
                    case '57A':
                            $arrValue = explode("\n", $value);
                            $arrLength = count($arrValue);

                            if ($arrLength > 1) {
                                $fcpt->beneficiaryBankAccount = str_replace('/', '', $arrValue[0]);
                                $swiftCode = $arrValue[1];
                            } else {
                                $swiftCode = $arrValue[0];
                            }
                            // Получение swift bic

                            $swiftBank = SwiftFinDictBank::findOne(['swiftCode' => $swiftCode]);
                            if (!$swiftBank && $arrLength > 1) {
                                // наименование и адрес указаны вручную
                                $fcpt->beneficiaryBankNameAndAddress = implode(' ', $arrValue);
                            } else {
                                $fcpt->beneficiaryBank = $swiftCode;
                            }

                            break;
                    case '59':
                            // Счет получателя
                            $arrValue = explode("\n", $value);

                            $fcpt->beneficiaryAccount = str_replace('/', '', $arrValue[0]);
                            array_shift($arrValue);
                            $fcpt->beneficiary = implode("\n", $arrValue);

                            break;
                }
            }

            if ($fcpt->beneficiary) {
                $pos = strpos($fcpt->beneficiary, ',');
                if ($pos !== false) {
                    $fcpt->templateName = substr($fcpt->beneficiary, 0, $pos);
                } else {
                    $fcpt->templateName = $fcpt->beneficiary;
                }
            }

            echo
                'templateName: ' . $fcpt->templateName
                . "\ninformation: " . $fcpt->information
                . "\nadditionalInformation: " . $fcpt->additionalInformation
                . "\ncommission: " . $fcpt->commission
                //. "\ndate: " . $fcpt->date
                . "\nINN: " . $fcpt->payerInn
                . "\ncurrency: " . $fcpt->currency . "\nsum: " . $fcpt->sum
                . "\npayerAccount: " . $fcpt->payerAccount
                . "\npayerName: " . $fcpt->payerName
                . "\npayerAddress: " . $fcpt->payerAddress
                . "\npayerLocation: " . $fcpt->payerLocation
                . "\npayerBank: " . $fcpt->payerBank
                . "\npayerBankName: " . $fcpt->payerBankName
                . "\npayerBankAddress: " . $fcpt->payerBankAddress
                . "\nintermediaryBank: " . $fcpt->intermediaryBank
                . "\nintermediaryBankNameAndAddress: " . $fcpt->intermediaryBankNameAndAddress
                . "\nbeneficiary: " . $fcpt->beneficiary
                . "\nbeneficiaryBank: " . $fcpt->beneficiaryBank
                . "\nbeneficiaryBankAccount: " . $fcpt->beneficiaryBankAccount
                . "\nbeneficiaryBankNameAndAddress: " . $fcpt->beneficiaryBankNameAndAddress
                . "\n";

            echo "\n";

            $fcpt->terminalId = $terminalId;
            $fcpt->save(false);

            $pos1++;
        }
    }

    public function actionValidateSbbolIsoStatements($useCamt054Format = false, $startDate = null)
    {
        printf("Will use ISO %s format\n", $useCamt054Format ? 'camt.054' : 'camt.053');

        $fs = new FileSystem();
        $fs->remove('invalid-iso-statements');

        $documents = Document::find()
            ->where(['direction' => Document::DIRECTION_IN])
            ->andWhere(['type' => SBBOLStatementType::TYPE])
            ->andFilterWhere(['>=', 'dateCreate', $startDate])
            ->all();

        $xsdPathAlias = $useCamt054Format ? '@addons/ISO20022/xsd/camt.054.001.02.xsd' : '@addons/ISO20022/xsd/camt.053.001.02.xsd';

        foreach ($documents as $document) {
            try {
                $cyxDocument = CyberXmlDocument::read($document->actualStoredFileId);
                /** @var SBBOLStatementType $typeModel */
                $typeModel = $cyxDocument->getContent()->getTypeModel();
                $statementTypeModel = StatementTypeConverter::convertFrom($typeModel);
                $statementUuid = str_replace('-', '', $typeModel->response->getStatements()->getStatement()[0]->getDocId());
                $isoCamtXml = Converter::statementToIsoCamtXml(
                    $statementTypeModel,
                    $document->dateCreate,
                    $document->uuidRemote,
                    $statementUuid,
                    $useCamt054Format ? Camt054Type::TYPE : Camt053Type::TYPE
                );
            } catch (\Throwable $exception) {
                echo "Failed to create ISO statement for document {$document->id}, caused by: $exception\n";
                continue;
            }

            list($isValid, $errors) = static::validateXml($isoCamtXml, $xsdPathAlias);
            if ($isValid) {
                echo "Document {$document->id} is valid\n";
            } else {
                $documentFilePath = "invalid-iso-statements/{$document->id}-camt053.xml";
                $fs->dumpFile($documentFilePath, $isoCamtXml);

                $errorsFilePath = "invalid-iso-statements/{$document->id}-errors.txt";
                $errorsString = array_reduce(
                    array_keys($errors),
                    function ($carry, $i) use ($errors) {
                        $error = $errors[$i];
                        $n = $i + 1;
                        return $carry . "$n. " . trim($error->message) . "\n";
                    },
                    ''
                );

                $fs->dumpFile($errorsFilePath, $errorsString);

                echo "Document {$document->id} is not valid\n";
                echo "ISO is saved to $documentFilePath\n";
                echo "Errors are saved to $errorsFilePath\n\n";
            }
        }
    }

    private static function validateXml($xml, $xsdPathAlias)
    {
        $xsdPath = \Yii::getAlias($xsdPathAlias);

        $dom = new \DOMDocument();
        $dom->loadXml($xml);

        libxml_use_internal_errors(true);
        $isValid = $dom->schemaValidate($xsdPath);
        if ($isValid) {
            return [true, null];
        } else {
            $errors = libxml_get_errors();
            return [false, $errors];
        }
    }
}
