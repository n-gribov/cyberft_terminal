<?php

namespace addons\ISO20022\helpers;

use addons\edm\models\ConfirmingDocumentInformation\ConfirmingDocumentInformationExt;
use addons\edm\models\ContractRegistrationRequest\ContractRegistrationRequestExt;
use addons\edm\models\DictCurrency;
use addons\edm\models\DictOrganization;
use addons\edm\models\EdmPayerAccount;
use addons\edm\models\ForeignCurrencyControl\ForeignCurrencyOperationInformationExt;
use addons\edm\models\ForeignCurrencyOperation\ForeignCurrencyConversion;
use addons\edm\models\ForeignCurrencyOperation\ForeignCurrencyOperationType;
use addons\edm\models\ForeignCurrencyOperation\ForeignCurrencySellTransit;
use addons\edm\models\IBankStatement\IBankStatementType;
use addons\edm\models\PaymentOrder\PaymentOrderType;
use addons\ISO20022\models\Auth024Type;
use addons\ISO20022\models\Auth025Type;
use addons\ISO20022\models\Auth026Type;
use addons\ISO20022\models\Camt052Type;
use addons\ISO20022\models\Camt053Type;
use addons\ISO20022\models\Camt054Type;
use addons\ISO20022\models\Pain001Type;
use common\base\BaseType;
use common\components\TerminalId;
use common\components\xmlsec\xmlseclibs\XMLSecurityDSig;
use common\helpers\Address;
use common\helpers\ArchiveFileZip;
use common\helpers\DateHelper;
use common\helpers\StringHelper;
use common\helpers\TerminalAddressResolver;
use common\helpers\Uuid;
use common\helpers\ZipHelper;
use common\models\listitem\AttachedFile;
use common\models\Terminal;
use common\models\TerminalRemoteId;
use common\modules\certManager\components\ssl\Exception;
use common\modules\participant\models\BICDirParticipant;
use DOMDocument;
use SimpleXMLElement;
use Yii;
use yii\base\InvalidValueException;

class ISO20022Helper
{
    /**
     * Метод валидирует по XSD переданную type-модель документа ISO20022
     * @param $typeModel
     */
    public static function validateXSD(&$typeModel)
    {
        // Если невозможно получить xml, считаем валидацию провальной
        if (!$typeModel->getRawXml()) {
            $typeModel->addError('xml', 'Ошибка получения содержимого XML');

            return false;
        }

        // Если тип определить невозможно, считаем валидацию провальной
        if (!$typeModel->type) {
            $typeModel->addError('xml', 'Невозможно определить тип документа');

            return false;
        }

        if (!$typeModel->fullType) {
            $typeModel->addError('xml', 'Невозможно определить полный формат типа документа');

            return false;
        }

        // Получение схемы для валидации документа
        $schemePath = '@addons/ISO20022/xsd/' . $typeModel->fullType . '.xsd';
        $schemePath = Yii::getAlias($schemePath);

        // Если файла со схемой не существует, считаем валидацию успешной
        if (!file_exists($schemePath)) {
            return true;
        }

        // Создание dom-объекта
        $dom = new DOMDocument();
        $dom->loadXml($typeModel->getRawXml()->asXml());

        libxml_use_internal_errors(true);

        // Валидация документа
        if ($dom->schemaValidate($schemePath)) {
            return true;
        } else {
            $errors = libxml_get_errors();
            $messages = [];
            foreach ($errors as $error) {
                $messages[] = "[{$error->level}] {$error->message}";
            }

            $message = join(PHP_EOL, $messages);
            $typeModel->addError('xml', $message);

            return false;
        }
    }

    /**
     * Получение типов документов свободного формата
     */
    public static function getFreeFormatDocTypeLabels()
    {
        // @todo Добавить отбор по Auth.024

        $typeLabels = [Auth026Type::TYPE, Auth024Type::TYPE];
        $freeFormat = [];

        foreach($typeLabels as $value) {
            $freeFormat[$value] = Yii::t('app/iso20022', $value);
        }

        return $freeFormat;
    }

    /**
     * Получение типов документов свободного формата
     */
    public static function getStatementsDocTypeLabels()
    {
        $typeLabels = [Camt052Type::TYPE, Camt053Type::TYPE, Camt054Type::TYPE];
        $statements = [];

        foreach($typeLabels as $value) {
            $statements[$value] = Yii::t('app/iso20022', $value);
        }

        return $statements;
    }

    /**
     * Получение заголовков для типов документов ISO20022
     */
    public static function getDocTypeLabels()
    {
        $types = array_keys(Yii::$app->registry->getModuleTypes('ISO20022'));
        $types = array_merge($types, [Camt052Type::TYPE, Camt053Type::TYPE, Camt054Type::TYPE]);

        $typeLabels = [];
        foreach($types as $type) {
            $typeLabels[$type] = Yii::t('app/iso20022', $type);
        }

        return $typeLabels;
    }

    /**
     * Получение списка типов кодов для auth.026
     */
    public static function getTypeCodesLabels()
    {
        // Получение типов кодов из настроек модуля
        $typeCodes = Yii::$app->settings->get('ISO20022:ISO20022')->typeCodes;

        $arrayTypeCodes = [];

        // Формирование массива значений для возврата
        foreach($typeCodes as $code => $value) {
            $arrayTypeCodes[$code] = $code;
        }

        // Сортировка массива по возрастанию ключа
        ksort($arrayTypeCodes);

        return $arrayTypeCodes;
    }

    /**
     * Типы платежных документов
     */
    public static function getPaymentsDocTypeLabels()
    {
        $typeLabels = [Pain001Type::TYPE];
        $payments = [];

        foreach($typeLabels as $value) {
            $payments[$value] = Yii::t('app/iso20022', $value);
        }

        return $payments;
    }

    /**
     * Получение xml для pain.001 из модели PaymentOrder
     * @param PaymentOrderType $paymentOrder
     */
    public static function createPain001XmlFromPaymentOrder(PaymentOrderType $paymentOrder)
    {
        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?>'
            . "\n"
            . '<Document xmlns="urn:iso:std:iso:20022:tech:xsd:pain.001.001.06"></Document>');
        $xml->CstmrCdtTrfInitn->GrpHdr->MsgId = self::cleanUuid();
        $xml->CstmrCdtTrfInitn->GrpHdr->CreDtTm = date('c');
        $xml->CstmrCdtTrfInitn->GrpHdr->NbOfTxs = 1;

        // Информация по терминалу отправителю
        $terminalSender = Terminal::findOne(['terminalId' => $paymentOrder->sender]);
        if (!$terminalSender) {
            Yii::error('ISO20022Helper::createPain001XmlFromPaymentOrder: terminal id \'' . $paymentOrder->sender . '\' not found');

            return false;
        }
        // Информация по терминалу получателю
        $terminalRecipient = $paymentOrder->recipient;

        $truncatedIdRecipient = Address::truncateAddress($terminalRecipient);
        $participantData = BICDirParticipant::findOne(['participantBIC' => $truncatedIdRecipient]);

        if ($terminalSender->title) {
            $xml->CstmrCdtTrfInitn->GrpHdr->InitgPty->Nm = $terminalSender->title;
        }
        $xml->CstmrCdtTrfInitn->GrpHdr->InitgPty->Id->OrgId->Othr->Id = $terminalSender->terminalId;
        $xml->CstmrCdtTrfInitn->GrpHdr->InitgPty->Id->OrgId->Othr->SchmeNm->Prtry = 'CFTBIC';

        $xml->CstmrCdtTrfInitn->GrpHdr->FwdgAgt->FinInstnId->BICFI = $truncatedIdRecipient;

        if ($participantData) {
            $xml->CstmrCdtTrfInitn->GrpHdr->FwdgAgt->FinInstnId->Nm = $participantData->name;
        }

        $xml->CstmrCdtTrfInitn->PmtInf->PmtInfId = $paymentOrder->sender . '-PKG-' . date('Ymd-His');
        $xml->CstmrCdtTrfInitn->PmtInf->PmtMtd = 'TRF';
        $xml->CstmrCdtTrfInitn->PmtInf->ReqdExctnDt = date('Y-m-d');
        $xml->CstmrCdtTrfInitn->PmtInf->Dbtr->Nm = $paymentOrder->payerName1 ?: $paymentOrder->payerName;
        $xml->CstmrCdtTrfInitn->PmtInf->Dbtr->PstlAdr->Ctry = 'RU';
        $xml->CstmrCdtTrfInitn->PmtInf->Dbtr->Id->OrgId->Othr->Id = $paymentOrder->payerInn;
        $xml->CstmrCdtTrfInitn->PmtInf->Dbtr->Id->OrgId->Othr->SchmeNm->Cd = 'TXID';
        $xml->CstmrCdtTrfInitn->PmtInf->DbtrAcct->Id->Othr->Id = $paymentOrder->payerAccount;
        $xml->CstmrCdtTrfInitn->PmtInf->DbtrAcct->Id->Othr->SchmeNm->Cd = 'BBAN';
        $xml->CstmrCdtTrfInitn->PmtInf->DbtrAcct->Ccy = 'RUB';
        $xml->CstmrCdtTrfInitn->PmtInf->DbtrAgt->FinInstnId->ClrSysMmbId->ClrSysId->Cd = 'RUCBC';
        $xml->CstmrCdtTrfInitn->PmtInf->DbtrAgt->FinInstnId->ClrSysMmbId->MmbId = $paymentOrder->payerBik;
        $xml->CstmrCdtTrfInitn->PmtInf->DbtrAgt->FinInstnId->Nm = $paymentOrder->payerBank1;
        $xml->CstmrCdtTrfInitn->PmtInf->DbtrAgt->FinInstnId->PstlAdr->Ctry = 'RU';
        $xml->CstmrCdtTrfInitn->PmtInf->DbtrAgtAcct->Id->Othr->Id = $paymentOrder->payerCorrespondentAccount;
        $xml->CstmrCdtTrfInitn->PmtInf->DbtrAgtAcct->Id->Othr->SchmeNm->Cd = 'BBAN';
        $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf->PmtId->InstrId = $paymentOrder->sender . '-PMT-' . date('Ymd-His');
        $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf->PmtId->EndToEndId = $paymentOrder->number;
        $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf->PmtTpInf->SvcLvl->Cd = $paymentOrder->isUrgent() ? 'URGP' : 'NURG';

        if ($paymentOrder->isBudgetPayment()) {
            $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf->PmtTpInf->CtgyPurp->Cd = 'TAXS';
        }

        $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf->Amt->InstdAmt = $paymentOrder->sum;
        $instAmt = $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf->Amt->InstdAmt;
        $instAmt->addAttribute('Ccy', $paymentOrder->currency);
        $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf->ChrgBr = 'DEBT';
        $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf->CdtrAgt->FinInstnId->ClrSysMmbId->ClrSysId->Cd = 'RUCBC';
        $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf->CdtrAgt->FinInstnId->ClrSysMmbId->MmbId = $paymentOrder->beneficiaryBik;
        $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf->CdtrAgt->FinInstnId->Nm = $paymentOrder->beneficiaryBank1;
        $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf->CdtrAgt->FinInstnId->PstlAdr->Ctry = 'RU';

        if ($paymentOrder->beneficiaryCorrespondentAccount) {
            $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf->CdtrAgtAcct->Id->Othr->Id = $paymentOrder->beneficiaryCorrespondentAccount;
            $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf->CdtrAgtAcct->Id->Othr->SchmeNm->Cd = 'BBAN';
        }

        $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf->Cdtr->Nm = $paymentOrder->beneficiaryName1 ? $paymentOrder->beneficiaryName1 : $paymentOrder->beneficiaryName;
        $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf->Cdtr->PstlAdr->Ctry = 'RU';

        if ($paymentOrder->beneficiaryInn) {
            $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf->Cdtr->Id->OrgId->Othr->Id = $paymentOrder->beneficiaryInn;
            $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf->Cdtr->Id->OrgId->Othr->SchmeNm->Cd = 'TXID';
        }

        $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf->CdtrAcct->Id->Othr->Id = $paymentOrder->beneficiaryAccount;
        $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf->CdtrAcct->Id->Othr->SchmeNm->Cd = 'BBAN';
        $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf->Purp->Prtry = $paymentOrder->priority;

        if (empty($paymentOrder->beneficiaryKpp)) {
            $beneficiaryKpp = 0;
        } else {
            $beneficiaryKpp = $paymentOrder->beneficiaryKpp;
        }

        if (empty($paymentOrder->payerKpp)) {
            $payerKpp = 0;
        } else {
            $payerKpp = $paymentOrder->payerKpp;
        }

        $tax = $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf->addChild('Tax');
        $cdtr = $tax->addChild('Cdtr');
        $tax->Dbtr->TaxTp = $payerKpp;

        if ($paymentOrder->okato) {
            $tax->AdmstnZn = $paymentOrder->okato;
        }

        if ($paymentOrder->indicatorNumber !== null && $paymentOrder->indicatorNumber !== '') {
            $tax->RefNb = $paymentOrder->indicatorNumber;
        }

        if (in_array($paymentOrder->indicatorDate, [0, '0', '00'], true)) {
            $tax->Mtd = $paymentOrder->indicatorDate;
        } elseif ($paymentOrder->indicatorDate) {
            $indicatorDate = date('Y-m-d', strtotime($paymentOrder->indicatorDate));
            $tax->Dt = $indicatorDate;
        }

        if ($paymentOrder->indicatorType !== null && $paymentOrder->indicatorType !== '') {
            $tax->Rcrd->Tp = $paymentOrder->indicatorType;
        }

        if ($paymentOrder->indicatorReason !== null && $paymentOrder->indicatorReason !== '') {
            $tax->Rcrd->Ctgy = $paymentOrder->indicatorReason;
        }

        if ($paymentOrder->indicatorKbk !== null && $paymentOrder->indicatorKbk !== '') {
            $tax->Rcrd->CtgyDtls = $paymentOrder->indicatorKbk;
        }

        if ($paymentOrder->senderStatus !== null && $paymentOrder->senderStatus !== '') {
            $tax->Rcrd->DbtrSts = $paymentOrder->senderStatus;
        }

        // Определение показателя периода
        $indicatorPeriod = $paymentOrder->indicatorPeriod;
        if (strlen($indicatorPeriod) == 8) {
            $cdtr->RegnId = $indicatorPeriod;
        } else {
            $result = static::getBudgetPeriodFromTxt($indicatorPeriod);
            if ($result['type'] == 'date') {
                $tax->Rcrd->Prd->FrToDt->FrDt = $result['prdYr'];
                $tax->Rcrd->Prd->FrToDt->ToDt = $result['prdYr'];
            } else if ($result['prdYr']) {
                $tax->Rcrd->Prd->Yr = $result['prdYr'];
                if ($result['prdTp']) {
                    $tax->Rcrd->Prd->Tp = $result['prdTp'];
                }
            }
        }

        $cdtr->TaxTp = $beneficiaryKpp;

        if ($paymentOrder->paymentPurpose) {
            $lines = StringHelper::mb_wordwrap($paymentOrder->paymentPurpose, 140);

            // Добавление строк в назначение платежа
            foreach($lines as $lineId => $line) {
                $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf->RmtInf->Ustrd[$lineId] = $line;
            }
        }

        $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf->RmtInf->Strd->RfrdDocInf->Tp->CdOrPrtry->Prtry = 'POD';

        $date = strtotime($paymentOrder->date);

        $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf->RmtInf->Strd->RfrdDocInf->RltdDt = date('Y-m-d', $date);

        if (isset($paymentOrder->code)) {
            $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf->RmtInf->Strd->CdtrRefInf->Ref = $paymentOrder->code;
        }

        return $xml->saveXML();
    }

    private static function getXmlNodeValue($node, $path, $required, $default = false)
    {
        $path = explode('/', $path);
        $currentNode = $node;
        foreach($path as $p) {
            $currentNode = $currentNode->{$p};
            if (!$currentNode) {
                if ($required) {
                    throw new Exception('Could not get value from ' . implode('/', $path));
                }

                return $default;
            }
        }

        return (string) $currentNode;
    }

    /**
     *  Правила маппинга: [название атрибута => строка xml-путь или массив [xml-путь, ...параметры...]]
        параметры: 'date' - преобразовывать дату, 'constant' - путь является прямым значением,
        'required' - требуется чтобы значение существовало, 'default' => значение, если путь не найден
         если путь начинается с '/', то считается от $rootXml, иначе от $selectedNode
     * @param type $rootXml корневой документ
     * @param type $selectedNode узел, от которого считается относительный путь
     * @param type $map правила маппинга
     * @return string
     * @throws Exception
     */
    private static function mapXmlToText($rootXml, $selectedNode, $map)
    {
        $out = '';
        foreach($map as $attribute => $path) {
            $currentNode = $selectedNode;
            $required = false;
            $constant = false;
            $date = false;
            $default = false;
            if (is_array($path)) {
                $required = in_array('required', $path);
                $constant = in_array('constant', $path);
                $date = in_array('date', $path);
                if (array_key_exists('default', $path)) {
                    $default = $path['default'];
                }
                $path = $path[0];
            }
            if ($constant) {
                $out .= $attribute . '=' . $path . "\r\n";
            } else {
                if ($path{0} == '/') {
                    $currentNode = $rootXml;
                    $path = substr($path, 1);
                } else {
                    $currentNode = $selectedNode;
                }
                $value = static::getXmlnodeValue($currentNode, $path, $required, $default);
                if ($value !== false) {
                    if ($date) {
                        $value = DateHelper::formatDate($value);
                    }
                    $out .= $attribute . '=' . $value . "\r\n";
                }
            }
        }

        return $out;
    }

    public static function createIBankFromPain001(Pain001Type $typeModel)
    {
        $xmlMap = [
            'DATE_DOC' => ['RmtInf/Strd/RfrdDocInf/RltdDt', 'date'], // Дата документа
            'NUM_DOC' => 'PmtId/EndToEndId', // Номер документа
            'PAYMENT_TYPE' => [null, 'constant'], // Тип платежа
            'PAYER_INN' => '/CstmrCdtTrfInitn/PmtInf/Dbtr/Id/OrgId/Othr/Id', // ИНН плательщика
            'PAYER_NAME' => ['/CstmrCdtTrfInitn/PmtInf/Dbtr/Nm', 'required'],// Наименование плательщика
            'PAYER_ACCOUNT' => ['/CstmrCdtTrfInitn/PmtInf/DbtrAcct/Id/Othr/Id', 'required'], // Расчетный счет плательщика
            'AMOUNT' => ['Amt/InstdAmt', 'required'], // Сумма платежа
            'PAYER_BANK_NAME' => ['/CstmrCdtTrfInitn/PmtInf/DbtrAgt/FinInstnId/Nm', 'required'], // Наименование банка плательщика
            'PAYER_BANK_BIC' => ['/CstmrCdtTrfInitn/PmtInf/DbtrAgt/FinInstnId/ClrSysMmbId/MmbId', 'required'], // БИК банка плательщика
            'PAYER_BANK_ACC' => '/CstmrCdtTrfInitn/PmtInf/DbtrAgtAcct/Id/Othr/Id', // Кор. счет банка плательщика
            'RCPT_INN' => 'Cdtr/Id/OrgId/Othr/Id', // ИНН получателя
            'RCPT_NAME' => ['Cdtr/Nm', 'required'], // Наименование получателя
            'RCPT_ACCOUNT' => 'CdtrAcct/Id/Othr/Id', // Счет получателя
            'RCPT_BANK_NAME' => 'CdtrAgt/FinInstnId/Nm', // Наименование банка получателя
            'RCPT_BANK_BIC' => ['CdtrAgt/FinInstnId/ClrSysMmbId/MmbId', 'required'], // БИК банка получателя
            'RCPT_BANK_ACC' => 'CdtrAgtAcct/Id/Othr/Id', // Кор. счет банка плательщика
            'TYPE_OPER' => ['01', 'constant'], // Вид операции
            'QUEUE' => 'Purp/Prtry', // Очередность платежа
            'PAYMENT_DETAILS' => ['RmtInf/Ustrd', 'required'], // Назначение платежа
            'KPP' => 'Tax/Dbtr/TaxTp', // КПП плательщика
            'TERM' => ['/CstmrCdtTrfInitn/PmtInf/ReqdExctnDt', 'date'], // Срок платежа
            'RCPT_KPP' => 'Tax/Cdtr/TaxTp', // КПП получателя
        ];

        $xmlMap2 = [
            'CHARGE_CREATOR' => 'Tax/Rcrd/DbtrSts', // Статус составителя документа
            'CHARGE_KBK' => 'Tax/Rcrd/CtgyDtls', // КБК
            'CHARGE_OKATO' => 'Tax/AdmstnZn', // ОКАТО
            'CHARGE_BASIS' => ['Tax/Rcrd/Ctgy', 'default' => '0'], // Основание налогового платежа
        ];

        $xmlMap3 = [
            'CHARGE_NUM_DOC' => ['Tax/RefNb', 'default' => '0'], // Номер документа, на основании которого осуществляется бюджетный платеж
            'CHARGE_DATE_DOC' => ['Tax/Dt', 'date'], // Дата документа, на основании которого осуществляется бюджетный платеж
            'CHARGE_TYPE' => ['Tax/Rcrd/Tp', 'default' => '0'], // Тип платежа
            'CODE' => 'RmtInf/Strd/CdtrRefInf/Ref', // УИН
        ];

        $xml = $typeModel->getRawXml();

        $iBank = '';

        foreach($xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf as $node) {
            // Важен порядок следования тэгов!
            $iBank .= "Content-Type=doc/payment\r\n\r\n";
            $iBank .= static::mapXmlToText($xml, $node, $xmlMap);

            // Бюджетный платеж
            if (isset($node->PmtTpInf->CtgyPurp->Cd)) {
                $isBudget = $node->PmtTpInf->CtgyPurp->Cd;

                $iBank .= 'IS_CHARGE=' . (($isBudget == 'TAXS' || $isBudget == '1') ? '1' : '0') . "\r\n";

                $iBank .= static::mapXmlToText($xml, $node, $xmlMap2);

                // Налоговый период
                if (isset($node->Tax->Rcrd->Prd->Tp) ||
                    isset($node->Tax->Rcrd->Prd->Yr)) {
                    $taxDate = (string) $node->Tax->Rcrd->Prd->Yr;
                    $taxPeriod = (string) $node->Tax->Rcrd->Prd->Tp;

                    $taxPeriodValue = self::getBudgetPeriodFromXml($taxDate, $taxPeriod);

                    $iBank .= "CHARGE_PERIOD={$taxPeriodValue}\r\n";
                } else {
                    $iBank .= "CHARGE_PERIOD=0\r\n";
                }

                $iBank .= static::mapXmlToText($xml, $node, $xmlMap3);
            }

            // Комментарий клиента
            $iBank .= static::mapXmlToText($xml, $node,
                    ['CLIENT_COMMENTS' => '/CstmrCdtTrfInitn/PmtInf/SplmtryData/PlcAndNm/Envlp']);

            $iBank .= "\r\n";
        }

        // Удаление пустой строки в конце файла
        $iBank = rtrim($iBank);

        // Содержимое должно быть в кодировке windows-1251
        $iBank = iconv('UTF-8', 'windows-1251', $iBank);

        return $iBank;
    }

    public static function createCamt053XmlFromIBankStatement(IBankStatementType $typeModel)
    {
        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?>'
                . "\n"
                . '<Document xmlns="urn:iso:std:iso:20022:tech:xsd:camt.053.001.02" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"></Document>');
        $xml->BkToCstmrStmt->GrpHdr->MsgId = self::cleanUuid();
        $xml->BkToCstmrStmt->GrpHdr->CreDtTm = date('c');

        $xml->BkToCstmrStmt->Stmt->Id = self::cleanUuid();

        $xml->BkToCstmrStmt->Stmt->ElctrncSeqNb = date('YmdHis', time());

        $xml->BkToCstmrStmt->Stmt->CreDtTm = date('c');

        $beginDate = strtotime($typeModel->beginDate);
        $endDate = strtotime($typeModel->endDate);

        $beginDateFormat = date('Y-m-d', $beginDate) ;
        $endDateFormat = date('Y-m-d', $endDate);

        $xml->BkToCstmrStmt->Stmt->FrToDt->FrDtTm = $beginDateFormat . 'T00:00:00+03:00';
        $xml->BkToCstmrStmt->Stmt->FrToDt->ToDtTm = $endDateFormat  . 'T23:59:59+03:00';

        // Получение валюты для операций по счету
        $currencyCode = substr($typeModel->account, 5, 3);

        // Разные коды рубля воспринимаем одинаково
        if (in_array($currencyCode, [810, 643])) {
            $currencyName = 'RUB';
        } else {
            $currency = DictCurrency::findOne(['code' => $currencyCode]);

            if ($currency) {
                $currencyName = $currency->name;
            } else {
                $currencyName = '';
            }
        }

        $xml->BkToCstmrStmt->Stmt->Acct->Id->Othr->Id = $typeModel->account;
        $xml->BkToCstmrStmt->Stmt->Acct->Id->Othr->SchmeNm->Cd = 'BBAN';
        $xml->BkToCstmrStmt->Stmt->Acct->Ccy = $currencyName;

        // Входящий баланс
        $xml->BkToCstmrStmt->Stmt->Bal[0]->Tp->CdOrPrtry->Cd = 'OPBD';
        $xml->BkToCstmrStmt->Stmt->Bal[0]->Amt = $typeModel->inRest;
        $xml->BkToCstmrStmt->Stmt->Bal[0]->CdtDbtInd = 'CRDT';

        $xml->BkToCstmrStmt->Stmt->Bal[0]->Dt->Dt = $beginDateFormat;
        $amt = $xml->BkToCstmrStmt->Stmt->Bal[0]->Amt;
        $amt->addAttribute('Ccy', $currencyName);

        // Исходящий баланс
        $xml->BkToCstmrStmt->Stmt->Bal[1]->Tp->CdOrPrtry->Cd = 'CLBD';
        $xml->BkToCstmrStmt->Stmt->Bal[1]->Amt = $typeModel->outRest;
        $xml->BkToCstmrStmt->Stmt->Bal[1]->CdtDbtInd = 'DBIT';

        $xml->BkToCstmrStmt->Stmt->Bal[1]->Dt->Dt = $endDateFormat;
        $amt = $xml->BkToCstmrStmt->Stmt->Bal[1]->Amt;
        $amt->addAttribute('Ccy', $currencyName);

        // Данные по суммам кредита/дебита
        $xml->BkToCstmrStmt->Stmt->TxsSummry->TtlCdtNtries->Sum = $typeModel->credit;
        $xml->BkToCstmrStmt->Stmt->TxsSummry->TtlDbtNtries->Sum = $typeModel->debit;

        // Транзакции
        $i = 0;

        foreach($typeModel->transactions as $transaction) {
            $xml->BkToCstmrStmt->Stmt->Ntry[$i]->NtryRef = $transaction['operationId'];

            $sum = $transaction['sum'];

            $xml->BkToCstmrStmt->Stmt->Ntry[$i]->Amt = abs($sum);
            $transAmt = $xml->BkToCstmrStmt->Stmt->Ntry[$i]->Amt;
            $transAmt->addAttribute('Ccy', $currencyName);

            $CdtDbtInd = $sum > 0 ? 'CRDT' : 'DBIT';

            $xml->BkToCstmrStmt->Stmt->Ntry[$i]->CdtDbtInd = $CdtDbtInd;
            $xml->BkToCstmrStmt->Stmt->Ntry[$i]->Sts = 'BOOK';

            $operationDate = '';

            if (isset($transaction['operationDate']) && $transaction['operationDate']) {
                $operationDate = $transaction['operationDate'];
            } else if (isset($transaction['incomeBankDate']) && $transaction['incomeBankDate']) {
                $operationDate = $transaction['incomeBankDate'];
            }

            if ($operationDate) {
                $operationDate = strtotime($operationDate);
                $operationDate = date('Y-m-d', $operationDate);
            }

            $xml->BkToCstmrStmt->Stmt->Ntry[$i]->BookgDt->Dt = $operationDate;
            $xml->BkToCstmrStmt->Stmt->Ntry[$i]->BkTxCd->Prtry->Cd = $transaction['operationCode'];
            $xml->BkToCstmrStmt->Stmt->Ntry[$i]->NtryDtls->TxDtls->Refs->EndToEndId = $transaction['documentNumber'];

            // Плательщик/получатель могут меняться в зависимости от типа операции (дебит/кредит)
            if ($sum > 0) {
                $payerName = isset($transaction['participant1Name']) ? $transaction['participant1Name'] : '';
                $payerAccount = isset($transaction['participant1Account']) ? $transaction['participant1Account'] : '';
                $payerBankName = isset($transaction['participant1BankName']) ? $transaction['participant1BankName'] : '';
                $payerBankBic = isset($transaction['participant1BankBic']) ? $transaction['participant1BankBic'] : '';
                $payerBankCorrespondentAccount = isset($transaction['participant1BankCorrespondentAccount']) ? $transaction['participant1BankCorrespondentAccount'] : '';
                $payerInn = isset($transaction['participant1Inn']) ? $transaction['participant1Inn'] : '';
                $payerKpp = isset($transaction['participant1Kpp']) ? $transaction['participant1Kpp'] : '';
                //
                $beneficiaryName = isset($transaction['participant2Name']) ? $transaction['participant2Name'] : '';
                $beneficiaryAccount = isset($transaction['participant2Account']) ? $transaction['participant2Account'] : '';
                $beneficiaryBankName = isset($transaction['participant2BankName']) ? $transaction['participant2BankName'] : '';
                $beneficiaryBankBic = isset($transaction['participant2BankBic']) ? $transaction['participant2BankBic'] : '';
                $beneficiaryBankCorrespondentAccount = isset($transaction['participant2BankCorrespondentAccount']) ? $transaction['participant2BankCorrespondentAccount'] : '';
                $beneficiaryInn = isset($transaction['participant2Inn']) ? $transaction['participant2Inn'] : '';
                $beneficiaryKpp = isset($transaction['participant2Kpp']) ? $transaction['participant2Kpp'] : '';
            } else {
                $payerName = isset($transaction['participant2Name']) ? $transaction['participant2Name'] : '';
                $payerAccount = isset($transaction['participant2Account']) ? $transaction['participant2Account'] : '';
                $payerBankName = isset($transaction['participant2BankName']) ? $transaction['participant2BankName'] : '';
                $payerBankBic = isset($transaction['participant2BankBic']) ? $transaction['participant2BankBic'] : '';
                $payerBankCorrespondentAccount = isset($transaction['participant2BankCorrespondentAccount']) ? $transaction['participant2BankCorrespondentAccount'] : '';
                $payerInn = isset($transaction['participant2Inn']) ? $transaction['participant2Inn'] : '';
                $payerKpp = isset($transaction['participant2Kpp']) ? $transaction['participant2Kpp'] : '';
                //
                $beneficiaryName = isset($transaction['participant1Name']) ? $transaction['participant1Name'] : '';
                $beneficiaryAccount = isset($transaction['participant1Account']) ? $transaction['participant1Account'] : '';
                $beneficiaryBankName = isset($transaction['participant1BankName']) ? $transaction['participant1BankName'] : '';
                $beneficiaryBankBic = isset($transaction['participant1BankBic']) ? $transaction['participant1BankBic'] : '';
                $beneficiaryBankCorrespondentAccount = isset($transaction['participant1BankCorrespondentAccount']) ? $transaction['participant1BankCorrespondentAccount'] : '';
                $beneficiaryInn = isset($transaction['participant1Inn']) ? $transaction['participant1Inn'] : '';
                $beneficiaryKpp = isset($transaction['participant1Kpp']) ? $transaction['participant1Kpp'] : '';
            }

            if ($payerName) {
                $xml->BkToCstmrStmt->Stmt->Ntry[$i]->NtryDtls->TxDtls->RltdPties->Dbtr->Nm = $payerName;
            }

            if ($payerInn) {
                $xml->BkToCstmrStmt->Stmt->Ntry[$i]->NtryDtls->TxDtls->RltdPties->Dbtr->Id->OrgId->Othr->Id = $payerInn;
            }

            if ($payerAccount) {
                $xml->BkToCstmrStmt->Stmt->Ntry[$i]->NtryDtls->TxDtls->RltdPties->DbtrAcct->Id->Othr->Id = $payerAccount;
            }

            if ($beneficiaryName) {
                $xml->BkToCstmrStmt->Stmt->Ntry[$i]->NtryDtls->TxDtls->RltdPties->Cdtr->Nm = $beneficiaryName;
            }

            if ($beneficiaryInn) {
                $xml->BkToCstmrStmt->Stmt->Ntry[$i]->NtryDtls->TxDtls->RltdPties->Cdtr->Id->OrgId->Othr->Id = $beneficiaryInn;
            }

            if ($beneficiaryAccount) {
                $xml->BkToCstmrStmt->Stmt->Ntry[$i]->NtryDtls->TxDtls->RltdPties->CdtrAcct->Id->Othr->Id = $beneficiaryAccount;
            }

            if ($payerBankBic) {
                $xml->BkToCstmrStmt->Stmt->Ntry[$i]->NtryDtls->TxDtls->RltdAgts->DbtrAgt->FinInstnId->ClrSysMmbId->MmbId = $payerBankBic;
            }

            if ($payerBankName) {
                $xml->BkToCstmrStmt->Stmt->Ntry[$i]->NtryDtls->TxDtls->RltdAgts->DbtrAgt->FinInstnId->Nm = $payerBankName;
            }

            if ($payerBankCorrespondentAccount) {
                $xml->BkToCstmrStmt->Stmt->Ntry[$i]->NtryDtls->TxDtls->RltdAgts->DbtrAgt->FinInstnId->Othr->Id = $payerBankCorrespondentAccount;
            }

            if ($beneficiaryBankBic) {
                $xml->BkToCstmrStmt->Stmt->Ntry[$i]->NtryDtls->TxDtls->RltdAgts->CdtrAgt->FinInstnId->ClrSysMmbId->MmbId = $beneficiaryBankBic;
            }

            if ($beneficiaryBankName) {
                $xml->BkToCstmrStmt->Stmt->Ntry[$i]->NtryDtls->TxDtls->RltdAgts->CdtrAgt->FinInstnId->Nm = $beneficiaryBankName;
            }

            if ($beneficiaryBankCorrespondentAccount) {
                $xml->BkToCstmrStmt->Stmt->Ntry[$i]->NtryDtls->TxDtls->RltdAgts->CdtrAgt->FinInstnId->Othr->Id = $beneficiaryBankCorrespondentAccount;
            }

            if (isset($transaction['queue'])) {
                $xml->BkToCstmrStmt->Stmt->Ntry[$i]->NtryDtls->TxDtls->Purp->Prtry = $transaction['queue'];
            }

            $xml->BkToCstmrStmt->Stmt->Ntry[$i]->NtryDtls->TxDtls->RmtInf->Ustrd = $transaction['operationDetails'];

            $documentDate = strtotime($transaction['documentDate']);
            $documentDate = date('Y-m-d', $documentDate);

            $xml->BkToCstmrStmt->Stmt->Ntry[$i]->NtryDtls->TxDtls->RmtInf->Strd->RfrdDocInf->RltdDt = $documentDate;

            if ($beneficiaryKpp) {
                $xml->BkToCstmrStmt->Stmt->Ntry[$i]->NtryDtls->TxDtls->Tax->Cdtr->TaxTp = $beneficiaryKpp;
            }

            if ($payerKpp) {
                $xml->BkToCstmrStmt->Stmt->Ntry[$i]->NtryDtls->TxDtls->Tax->Dbtr->TaxTp = $payerKpp;
            }

            if (isset($transaction['chargeOkato'])) {
                $xml->BkToCstmrStmt->Stmt->Ntry[$i]->NtryDtls->TxDtls->Tax->AdmstnZn = $transaction['chargeOkato'];
            }

            if (isset($transaction['chargeDocNumber'])) {
                $xml->BkToCstmrStmt->Stmt->Ntry[$i]->NtryDtls->TxDtls->Tax->RefNb = $transaction['chargeDocNumber'];
            }

            if (isset($transaction['chargeBasis'])) {
                $xml->BkToCstmrStmt->Stmt->Ntry[$i]->NtryDtls->TxDtls->Tax->Rcrd->Ctgy = $transaction['chargeBasis'];
            }

            if (isset($transaction['chargeKbk'])) {
                $xml->BkToCstmrStmt->Stmt->Ntry[$i]->NtryDtls->TxDtls->Tax->Rcrd->CtgyDtls = $transaction['chargeKbk'];
            }

            if (isset($transaction['chargeCreator'])) {
                $xml->BkToCstmrStmt->Stmt->Ntry[$i]->NtryDtls->TxDtls->Tax->Rcrd->DbtrSts = $transaction['chargeCreator'];
            }

            if (isset($transaction['chargePeriod'])) {
                $xml->BkToCstmrStmt->Stmt->Ntry[$i]->NtryDtls->TxDtls->Tax->Rcrd->Prd = $transaction['chargePeriod'];
            }

            if (isset($transaction['chargeDocDate'])) {
                $xml->BkToCstmrStmt->Stmt->Ntry[$i]->NtryDtls->TxDtls->Tax->Dt = $transaction['chargeDocDate'];
            }

            if (isset($transaction['chargeType'])) {
                $xml->BkToCstmrStmt->Stmt->Ntry[$i]->NtryDtls->TxDtls->Tax->Rcrd->Tp = $transaction['chargeType'];
            }

            if (isset($transaction['valueDate'])) {
                $xml->BkToCstmrStmt->Stmt->Ntry[$i]->ValDt->Dt = $transaction['valueDate'];
            }

            $i++;
        }

        $rawXml = $xml->saveXML();

        return $rawXml;
    }

    // Получение представления налогового периода из xml
    public static function getBudgetPeriodFromXml($budgetDate, $budgetPeriod)
    {
        $budgetPeriodValue = 0;

        if ($budgetDate && empty($budgetPeriod)) {
            $regexp = '#(\d{4})-(\d{2})-(\d{2})#';
            if (preg_match_all($regexp, $budgetDate, $result)) {
                if ($result[2][0] == '01') {
                    // если указан год
                    $budgetPeriodValue = "ГД.00.{$result[1][0]}";
                } else {
                    // Если указана конкретная дата
                    $budgetPeriodValue = DateHelper::formatDate($budgetDate);
                }
            }
        } else if (stristr($budgetPeriod, 'MM') !== false) {
            // За конкретный месяц
            $regexp = '#(\d{4})-(\d{2})-(\d{2})#';
            if (preg_match_all($regexp, $budgetDate, $result)) {
                $monthValue = substr($budgetPeriod, 2);
                $budgetPeriodValue = "MC.{$monthValue}.{$result[1][0]}";
            }
        } else if (stristr($budgetPeriod, 'QTR') !== false) {
            // за конкретный квартал
            $regexp = '#(\d{4})-(\d{2})-(\d{2})#';
            if (preg_match_all($regexp, $budgetDate, $result)) {
                $qtrValue = substr($budgetPeriod, 3);
                $budgetPeriodValue = "КВ.0{$qtrValue}.{$result[1][0]}";
            }
        } else if (stristr($budgetPeriod, 'HLF') !== false) {
            // за понкретное полугодие
            $regexp = '#(\d{4})-(\d{2})-(\d{2})#';
            if (preg_match_all($regexp, $budgetDate, $result)) {
                $hlfValue = substr($budgetPeriod, 3);
                $budgetPeriodValue = "ПЛ.0{$hlfValue}.{$result[1][0]}";
            }
        }

        return $budgetPeriodValue;
    }

    /**
     * Получение налогового периода для представления в xml
     */
    public static function getBudgetPeriodFromTxt($indicatorPeriod)
    {
        $prdYr = '';
        $prdTp = '';
        $type = '';
        $result = [];

        if (!empty($indicatorPeriod)) {
            if (preg_match_all('#МС\.(\d{2})\.(\d{4})#u', $indicatorPeriod, $result)) {
                // платеж за конкретный месяц
                $prdYr = "{$result[2][0]}-01-01";
                $prdTp = "MM{$result[1][0]}";
            } else if (preg_match_all('#КВ\.(\d{2})\.(\d{4})#u', $indicatorPeriod, $result)) {
                // за квартал
                $month = str_replace('0', '', $result[1][0]);
                $prdYr = "{$result[2][0]}-01-01";
                $prdTp = "QTR{$month}";
            } else if (preg_match_all('#ПЛ\.(\d{2})\.(\d{4})#u', $indicatorPeriod, $result)) {
                // за полугодие
                $period = str_replace('0', '', $result[1][0]);
                $prdYr = "{$result[2][0]}-01-01";
                $prdTp = "HLF{$period}";
            } else if (preg_match_all('#ГД\.00\.(\d{4})#u', $indicatorPeriod, $result)) {
                // один раз в год
                $prdYr = "{$result[1][0]}-01-01";
            } else if (preg_match_all('#(\d{2})\.(\d{2})\.(\d{4})#', $indicatorPeriod, $result)) {
                // по конкретной дате
                $prdYr = "{$result[3][0]}-{$result[2][0]}-{$result[1][0]}";
                $type = 'date';
            }
        }

        return [
            'type' => $type,
            'prdYr' => $prdYr,
            'prdTp' => $prdTp
        ];
    }

    public static function createPain001From1cPaymentRegister($content, $encoding = null)
    {
        $content = StringHelper::utf8($content, $encoding);

        $rows = preg_split('/[\\r\\n]+/', $content);

        // Массив со строками платежек
        $paymentOrdersTxt = [];

        // Массив с объектами платежек
        $paymentOrders = [];

        $isOperation = false;
        $operation = [];

        $account = '';

        // Получение текстовых представлений платежек
        foreach($rows as $row) {
            // Тэги, которые надо пропустить
            if (stristr($row, '1CClientBankExchange') !== false
                || stristr($row, 'Кодировка') !== false
                || stristr($row, 'КонецФайла') !== false
                || stristr($row, 'Отправитель') !== false
                || stristr($row, 'СекцияРасчСчет') !== false
                || stristr($row, 'КонецРасчСчет') !== false
            ) {
                continue;
            }

            $row = trim($row);

            // Начало платежки
            if ($row == 'СекцияДокумент=Платежное поручение') {
                $operation[] = $row;
                $isOperation = true;

                continue;
            }

            // Конец платежки
            if ($row == 'КонецДокумента') {
                $operation[] = $row;
                $isOperation = false;
                $paymentOrdersTxt[] = $operation;
                $operation = [];

                continue;
            }

            // Собираем данные по платежке
            if ($isOperation) {
                $operation[] = $row;

                continue;
            }

            // Получаем номер счета
            if (stristr($row, 'РасчСчет')) {
                $account = str_replace('РасчСчет=', '', $row);
            }
        }
        // Формирование объектов платежек
        foreach ($paymentOrdersTxt as $paymentOrderTxt) {
            // Преобразование массива в набор строк
            $text = implode(PHP_EOL, $paymentOrderTxt);

            $paymentOrderItem = new PaymentOrderType();
            $paymentOrderItem->loadFromString($text);
            $paymentOrders[] = $paymentOrderItem;
        }

        $edmPayerAccount = EdmPayerAccount::findOne(['number' => $account]);
        if ($edmPayerAccount === null) {
            throw new Exception("Payer account $account not found");
        }
        $organization = $edmPayerAccount->edmDictOrganization;
        $bank = $edmPayerAccount->bank;
        $senderTerminal = Terminal::findOne($organization->terminalId);
        $recipientTerminal = $bank->terminalId;

        return [
            'xml' => static::createPain001XmlFromPaymentOrders($account, $paymentOrders),
            'sender' => $senderTerminal->terminalId,
            'receiver' => $recipientTerminal
        ];
    }

    /**
     * @param string $account
     * @param PaymentOrderType[] $paymentOrders
     * @return string
     */
    public static function createPain001XmlFromPaymentOrders(string $account, array $paymentOrders): string
    {
        $sum = 0;
        foreach ($paymentOrders as $paymentOrder) {
            $sum += $paymentOrder->sum;
        }

        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?>'
            . "\n"
            . '<Document xmlns="urn:iso:std:iso:20022:tech:xsd:pain.001.001.06"></Document>');
        $xml->CstmrCdtTrfInitn->GrpHdr->MsgId = $paymentOrder->registerExternalId ?: self::cleanUuid();
        $xml->CstmrCdtTrfInitn->GrpHdr->CreDtTm = date('c');
        $xml->CstmrCdtTrfInitn->GrpHdr->NbOfTxs = count($paymentOrders);
        $xml->CstmrCdtTrfInitn->GrpHdr->CtrlSum = $sum;

        // Информация по счету
        $edmPayerAccount = EdmPayerAccount::findOne(['number' => $account]);

        // Если не найден счет
        if (is_null($edmPayerAccount)) {
            throw new Exception("Payer account $account not found");
        }

        $organization = $edmPayerAccount->edmDictOrganization;

        $payerInn = $paymentOrders[0]->payerInn ?: $organization->inn;
        $senderName = $paymentOrders[0]->payerName1 ?: $paymentOrders[0]->payerName ?: $organization->name;
        $payerName = $paymentOrders[0]->payerName1 ?: $paymentOrders[0]->payerName ?: $edmPayerAccount->getPayerName();

        $bank = $edmPayerAccount->bank;
        $currency = $edmPayerAccount->edmDictCurrencies;
        $currencyTitle = ($currency->code == '810' || $currency->code == '643') ? 'RUB' : $currency->name;

        // Информация по отправителю
        $senderTerminal = Terminal::findOne($organization->terminalId);

        // Информация по получателю
        $recipientTerminal = $bank->terminalId;

        $truncatedIdRecipient = Address::truncateAddress($recipientTerminal);
        $participantData = BICDirParticipant::find()->where(['participantBIC' => $truncatedIdRecipient])->one();

        if ($senderName) {
            $xml->CstmrCdtTrfInitn->GrpHdr->InitgPty->Nm = $senderName;
        }

        $xml->CstmrCdtTrfInitn->GrpHdr->InitgPty->Id->OrgId->Othr[0]->Id = $senderTerminal->terminalId;
        $xml->CstmrCdtTrfInitn->GrpHdr->InitgPty->Id->OrgId->Othr[0]->SchmeNm->Prtry = 'CFTBIC';
        $xml->CstmrCdtTrfInitn->GrpHdr->InitgPty->Id->OrgId->Othr[1]->Id = $account;
        $xml->CstmrCdtTrfInitn->GrpHdr->InitgPty->Id->OrgId->Othr[1]->SchmeNm->Cd = 'BBAN';
        if ($payerInn) {
            $xml->CstmrCdtTrfInitn->GrpHdr->InitgPty->Id->OrgId->Othr[2]->Id = $payerInn;
            $xml->CstmrCdtTrfInitn->GrpHdr->InitgPty->Id->OrgId->Othr[2]->SchmeNm->Cd = 'TXID';
        }

        $xml->CstmrCdtTrfInitn->GrpHdr->FwdgAgt->FinInstnId->BICFI = $truncatedIdRecipient;

        if ($participantData) {
            $xml->CstmrCdtTrfInitn->GrpHdr->FwdgAgt->FinInstnId->Nm = $participantData->name;
        }

        $xml->CstmrCdtTrfInitn->PmtInf->PmtInfId = $paymentOrder->registerExternalId ?: $senderTerminal->terminalId . '-PKG-' . date('Ymd-His');
        $xml->CstmrCdtTrfInitn->PmtInf->PmtMtd = 'TRF';
        $xml->CstmrCdtTrfInitn->PmtInf->ReqdExctnDt = date('Y-m-d');
        $xml->CstmrCdtTrfInitn->PmtInf->Dbtr->Nm = $payerName;
        $xml->CstmrCdtTrfInitn->PmtInf->Dbtr->PstlAdr->Ctry = 'RU';
        $xml->CstmrCdtTrfInitn->PmtInf->Dbtr->Id->OrgId->Othr->Id = $payerInn;
        $xml->CstmrCdtTrfInitn->PmtInf->Dbtr->Id->OrgId->Othr->SchmeNm->Cd = 'TXID';
        $xml->CstmrCdtTrfInitn->PmtInf->DbtrAcct->Id->Othr->Id = $edmPayerAccount->number;
        $xml->CstmrCdtTrfInitn->PmtInf->DbtrAcct->Id->Othr->SchmeNm->Cd = 'BBAN';
        $xml->CstmrCdtTrfInitn->PmtInf->DbtrAcct->Ccy = $currencyTitle;
        $xml->CstmrCdtTrfInitn->PmtInf->DbtrAgt->FinInstnId->ClrSysMmbId->ClrSysId->Cd = 'RUCBC';
        $xml->CstmrCdtTrfInitn->PmtInf->DbtrAgt->FinInstnId->ClrSysMmbId->MmbId = $bank->bik;
        $xml->CstmrCdtTrfInitn->PmtInf->DbtrAgt->FinInstnId->Nm = $bank->name;
        $xml->CstmrCdtTrfInitn->PmtInf->DbtrAgt->FinInstnId->PstlAdr->Ctry = 'RU';
        $xml->CstmrCdtTrfInitn->PmtInf->DbtrAgtAcct->Id->Othr->Id = $bank->account;
        $xml->CstmrCdtTrfInitn->PmtInf->DbtrAgtAcct->Id->Othr->SchmeNm->Cd = 'BBAN';

        foreach($paymentOrders as $i => $paymentOrder) {
            $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[$i]->PmtId->InstrId = $paymentOrder->documentExternalId ?: self::cleanUuid();
            $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[$i]->PmtId->EndToEndId = $paymentOrder->number;
            $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[$i]->PmtTpInf->SvcLvl->Cd = $paymentOrder->isUrgent() ? 'URGP' : 'NURG';

            if ($paymentOrder->isBudgetPayment()) {
                $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[$i]->PmtTpInf->CtgyPurp->Cd = 'TAXS';
            }

            $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[$i]->Amt->InstdAmt = $paymentOrder->sum;
            $instAmt = $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[$i]->Amt->InstdAmt;
            $instAmt->addAttribute('Ccy', $currencyTitle);
            $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[$i]->ChrgBr = 'DEBT';
            $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[$i]->CdtrAgt->FinInstnId->ClrSysMmbId->ClrSysId->Cd = 'RUCBC';
            $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[$i]->CdtrAgt->FinInstnId->ClrSysMmbId->MmbId = $paymentOrder->beneficiaryBik;
            $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[$i]->CdtrAgt->FinInstnId->Nm = $paymentOrder->beneficiaryBank1;
            $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[$i]->CdtrAgt->FinInstnId->PstlAdr->Ctry = 'RU';

            if ($paymentOrder->beneficiaryCorrespondentAccount) {
                $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[$i]->CdtrAgtAcct->Id->Othr->Id = $paymentOrder->beneficiaryCorrespondentAccount;
                $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[$i]->CdtrAgtAcct->Id->Othr->SchmeNm->Cd = 'BBAN';
            }

            // Обработка наименования получателя
            $beneficiaryName = $paymentOrder->beneficiaryName1 ? $paymentOrder->beneficiaryName1 : $paymentOrder->beneficiaryName;

            $beneficiaryNameLines = StringHelper::mb_wordwrap($beneficiaryName, 140);

            $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[$i]->Cdtr->Nm = $beneficiaryNameLines[0];

            $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[$i]->Cdtr->PstlAdr->Ctry = 'RU';

            if ($paymentOrder->beneficiaryInn) {
                $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[$i]->Cdtr->Id->OrgId->Othr->Id = $paymentOrder->beneficiaryInn;
                $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[$i]->Cdtr->Id->OrgId->Othr->SchmeNm->Cd = 'TXID';
            }

            // Если наименование получателя очень длинное
            if (count($beneficiaryNameLines) > 1) {
                $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[$i]->Cdtr->CtctDtls->Nm = $beneficiaryNameLines[1];
            }

            $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[$i]->CdtrAcct->Id->Othr->Id = $paymentOrder->beneficiaryAccount;
            $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[$i]->CdtrAcct->Id->Othr->SchmeNm->Cd = 'BBAN';

            if ($paymentOrder->priority) {
                $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[$i]->Purp->Prtry = $paymentOrder->priority;
            }

            if ($paymentOrder->paymentOrderPaymentPurpose) {
                $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[$i]->RgltryRptg->Dtls->Tp = 'PTCD';
                $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[$i]->RgltryRptg->Dtls->Cd = $paymentOrder->paymentOrderPaymentPurpose;
            }

            // Определение показателя периода
            $tax = $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[$i]->addChild('Tax');
            $cdtr = $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[$i]->Tax->addChild('Cdtr');
            $payerKpp = $paymentOrder->payerKpp ?: $organization->kpp;
            if ($payerKpp) {
                $tax->Dbtr->TaxTp = $payerKpp;
            }

            if ($paymentOrder->okato) {
                $tax->AdmstnZn = $paymentOrder->okato;
            }
            if ($paymentOrder->indicatorNumber !== null && $paymentOrder->indicatorNumber !== '') {
                $tax->RefNb = $paymentOrder->indicatorNumber;
            }
            if (in_array($paymentOrder->indicatorDate, [0, '0', '00'], true)) {
                $tax->Mtd = $paymentOrder->indicatorDate;
            } elseif ($paymentOrder->indicatorDate) {
                $indicatorDate = date('Y-m-d', strtotime($paymentOrder->indicatorDate));
                $tax->Dt = $indicatorDate;
            }
            if ($paymentOrder->indicatorType !== null && $paymentOrder->indicatorType !== '') {
                $tax->Rcrd->Tp = $paymentOrder->indicatorType;
            }
            if ($paymentOrder->indicatorReason !== null && $paymentOrder->indicatorReason !== '') {
                $tax->Rcrd->Ctgy = $paymentOrder->indicatorReason;
            }
            if ($paymentOrder->indicatorKbk !== null && $paymentOrder->indicatorKbk !== '') {
                $tax->Rcrd->CtgyDtls = $paymentOrder->indicatorKbk;
            }
            if ($paymentOrder->senderStatus !== null && $paymentOrder->senderStatus !== '') {
                $tax->Rcrd->DbtrSts = $paymentOrder->senderStatus;
            }

            $indicatorPeriod = $paymentOrder->indicatorPeriod;
            if (strlen($indicatorPeriod) == 8) {
                $cdtr->RegnId = $indicatorPeriod;
            } else {
                $result = static::getBudgetPeriodFromTxt($indicatorPeriod);
                if ($result['type'] == 'date') {
                    $tax->Rcrd->Prd->FrToDt->FrDt = $result['prdYr'];
                    $tax->Rcrd->Prd->FrToDt->ToDt = $result['prdYr'];
                } else if ($result['prdYr']) {
                    $tax->Rcrd->Prd->Yr = $result['prdYr'];
                    if ($result['prdTp']) {
                        $tax->Rcrd->Prd->Tp = $result['prdTp'];
                    }
                }
            }

            if ($paymentOrder->beneficiaryKpp) {
                $cdtr->TaxTp = $paymentOrder->beneficiaryKpp;
            }

//            $indicatorPeriod = self::getBudgetPeriodFromTxt($paymentOrder->indicatorPeriod);
//
//            if ($indicatorPeriod['prdYr']) {
//                $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[$i]->Tax->Rcrd->Prd->Yr = $indicatorPeriod['prdYr'];
//                $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[$i]->Tax->Rcrd->Prd->Tp = $indicatorPeriod['prdTp'];
//            }

            if ($paymentOrder->paymentPurpose) {
                $lines = StringHelper::mb_wordwrap($paymentOrder->paymentPurpose, 140);

                // Добавление строк в назначение платежа
                foreach($lines as $lineId => $line) {
                    $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[$i]->RmtInf->Ustrd[$lineId] = $line;
                }
            }

            $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[$i]->RmtInf->Strd->RfrdDocInf->Tp->CdOrPrtry->Prtry = 'POD';
            $date = strtotime($paymentOrder->date);
            $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[$i]->RmtInf->Strd->RfrdDocInf->RltdDt = date('Y-m-d', $date);

            if ($paymentOrder->code !== '' && $paymentOrder->code !== null) {
                $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[$i]->RmtInf->Strd->CdtrRefInf->Ref = $paymentOrder->code;
            }
        }

        return $xml->saveXML();
    }

    /**
     * Формирование auth.024 из справки о валютных операциях
     * @param ForeignCurrencyOperationInformationExt $extModel
     */
    public static function createAuth024FromFCOI($extModel)
    {
        // Получение организации
        $organization = $extModel->organization;

        if (!$organization) {
            throw new \Exception("Organization {$extModel->organizationId} not found");
        }

        $terminal = Terminal::findOne($organization->terminalId);

        if (!$terminal) {
            throw new \Exception("Terminal {$organization->terminalId} not found");
        }

        // Получение счета
        $account = $extModel->account;

        if (!$account) {
            throw new \Exception("Account {$extModel->accountId} not found");
        }

        $bank = $account->bank;

        if (!$bank) {
            throw new \Exception('Account bank not found');
        }

        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?>'
                . "\n"
                . '<Document xmlns="urn:iso:std:iso:20022:tech:xsd:auth.024.001.01" xmlns:xs="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:ds="http://www.w3.org/2000/09/xmldsig#"></Document>');

        $xml->PmtRgltryInfNtfctn->GrpHdr->MsgId = self::cleanUuid();
        $xml->PmtRgltryInfNtfctn->GrpHdr->CreDtTm = date('c', strtotime($extModel->date));
        $xml->PmtRgltryInfNtfctn->GrpHdr->NbOfItms = count($extModel->operations);

        $xml->PmtRgltryInfNtfctn->GrpHdr->InitgPty->Pty->Nm = $organization->name;
        $xml->PmtRgltryInfNtfctn->GrpHdr->InitgPty->Pty->Id->OrgId->Othr[0]->Id = $terminal->terminalId;
        $xml->PmtRgltryInfNtfctn->GrpHdr->InitgPty->Pty->Id->OrgId->Othr[0]->SchmeNm->Prtry = 'CFTBIC';
        $xml->PmtRgltryInfNtfctn->GrpHdr->InitgPty->Pty->Id->OrgId->Othr[1]->Id = $account->number;
        $xml->PmtRgltryInfNtfctn->GrpHdr->InitgPty->Pty->Id->OrgId->Othr[1]->SchmeNm->Cd = 'BBAN';
        if ($organization->inn) {
            $xml->PmtRgltryInfNtfctn->GrpHdr->InitgPty->Pty->Id->OrgId->Othr[2]->Id = $organization->inn;
            $xml->PmtRgltryInfNtfctn->GrpHdr->InitgPty->Pty->Id->OrgId->Othr[2]->SchmeNm->Cd = 'TXID';
        }

        $xml->PmtRgltryInfNtfctn->GrpHdr->FwdgAgt->FinInstnId->BICFI = Address::truncateAddress($account->bank->terminalId);
        $xml->PmtRgltryInfNtfctn->GrpHdr->FwdgAgt->FinInstnId->Nm = $account->bank->name;

        $xml->PmtRgltryInfNtfctn->TxNtfctn->TxNtfctnId = self::cleanUuid();
        $xml->PmtRgltryInfNtfctn->TxNtfctn->AcctOwnr->Nm = $organization->name;
        $xml->PmtRgltryInfNtfctn->TxNtfctn->AcctOwnr->Id->OrgId->Othr->Id = $organization->inn;
        $xml->PmtRgltryInfNtfctn->TxNtfctn->AcctOwnr->Id->OrgId->Othr->SchmeNm->Prtry = 'TXID';

        $xml->PmtRgltryInfNtfctn->TxNtfctn->AcctSvcr->FinInstnId->ClrSysMmbId->ClrSysId->Cd = 'RUCBC';
        $xml->PmtRgltryInfNtfctn->TxNtfctn->AcctSvcr->FinInstnId->ClrSysMmbId->MmbId = $account->bankBik;

        $xml->PmtRgltryInfNtfctn->TxNtfctn->TxCert->TxId = self::cleanUuid();
        $xml->PmtRgltryInfNtfctn->TxNtfctn->TxCert->Cert->DtOfIsse = date('Y-m-d', strtotime($extModel->date));

        $xml->PmtRgltryInfNtfctn->TxNtfctn->TxCert->Acct->Id->Othr->Id = $account->number;
        $xml->PmtRgltryInfNtfctn->TxNtfctn->TxCert->BkAcctDmcltnCtry = $extModel->countryCode;

        $i = 0;
        $nodeNumber = 0;
        $totalAttachedFiles = [];

        foreach($extModel->operations as $operation) {

            $nodeNumber++;

            $currency = DictCurrency::findOne($operation->currencyId);

            $xml->PmtRgltryInfNtfctn->TxNtfctn->TxCert->CertRcrd[$i]->CertRcrdId = $nodeNumber;

            if ($operation->notRequiredSection1) {
                $opNumber = 'БН';
            } else {
                $opNumber = $operation->number;
            }
            $xml->PmtRgltryInfNtfctn->TxNtfctn->TxCert->CertRcrd[$i]->Tx->RfrdDoc->Id->EndToEndId = $opNumber;

            $xml->PmtRgltryInfNtfctn->TxNtfctn->TxCert->CertRcrd[$i]->Tx->RfrdDoc->Dt = date('Y-m-d', strtotime($operation->operationDate));
            $xml->PmtRgltryInfNtfctn->TxNtfctn->TxCert->CertRcrd[$i]->Tx->TxDt = date('Y-m-d', strtotime($operation->operationDate));
            $xml->PmtRgltryInfNtfctn->TxNtfctn->TxCert->CertRcrd[$i]->Tx->TxTp = $operation->paymentType;
            $xml->PmtRgltryInfNtfctn->TxNtfctn->TxCert->CertRcrd[$i]->Tx->LclInstrm = $operation->codeFCO;
            $xml->PmtRgltryInfNtfctn->TxNtfctn->TxCert->CertRcrd[$i]->Tx->Amt = $operation->operationSum;
            $operationSum = $xml->PmtRgltryInfNtfctn->TxNtfctn->TxCert->CertRcrd[$i]->Tx->Amt;
            $operationSum->addAttribute('Ccy', $currency->name);

            if ($operation->notRequiredSection1) {
                $xml->PmtRgltryInfNtfctn->TxNtfctn->TxCert->CertRcrd[$i]->Ctrct->CtrctRef->RegdCtrctId = 'БН';
            } else if ($operation->contractPassport) {
                $xml->PmtRgltryInfNtfctn->TxNtfctn->TxCert->CertRcrd[$i]->Ctrct->CtrctRef->RegdCtrctId = $operation->contractPassport;
            } else if ($operation->contractNumber) {
                $xml->PmtRgltryInfNtfctn->TxNtfctn->TxCert->CertRcrd[$i]->Ctrct->CtrctRef->Ctrct->Id = $operation->contractNumber;
                $xml->PmtRgltryInfNtfctn->TxNtfctn->TxCert->CertRcrd[$i]->Ctrct->CtrctRef->Ctrct->DtOfIsse = date('Y-m-d', strtotime($operation->contractDate));
            }

            if ($operation->operationSumUnits) {
                $xml->PmtRgltryInfNtfctn->TxNtfctn->TxCert->CertRcrd[$i]->Ctrct->TxAmtInCtrctCcy = $operation->operationSumUnits;
                $operationSumUnits = $xml->PmtRgltryInfNtfctn->TxNtfctn->TxCert->CertRcrd[$i]->Ctrct->TxAmtInCtrctCcy;
                $currencyUnits = DictCurrency::findOne($operation->currencyUnitsId);
                $name = $currencyUnits ? $currencyUnits->name : '';
                $operationSumUnits->addAttribute('Ccy', $name);
            }

            if ($operation->expectedDate || $operation->refundDate) {
                if ($operation->expectedDate) {
                    $xml->PmtRgltryInfNtfctn->TxNtfctn->TxCert->CertRcrd[$i]->Ctrct->XpctdShipmntDt = date('Y-m-d', strtotime($operation->expectedDate));
                }

                if ($operation->refundDate) {
                    $xml->PmtRgltryInfNtfctn->TxNtfctn->TxCert->CertRcrd[$i]->Ctrct->XpctdAdvncPmtRtrDt = date('Y-m-d', strtotime($operation->refundDate));
                }
            }

            if ($operation->comment) {
                $xml->PmtRgltryInfNtfctn->TxNtfctn->TxCert->CertRcrd[$i]->Ctrct->AddtlInf = $operation->comment;
            }

            if ($operation->attachedFiles) {
                $filePos = 0;
                foreach($operation->attachedFiles as $file) {
                    $xml->PmtRgltryInfNtfctn->TxNtfctn->TxCert->CertRcrd[$i]->addChild('Attchmnt');

                    $path = $file->getPath();
                    if (!is_readable($path)) {
                        throw new \Exception('Attached file not found with path ' . $path);
                    }

                    $attachFilename = 'attach_' . $file->name;

                    // помещаем в массив новый объект AttachedFile с измененным именем файла,
                    // чтобы не мутировало имя в исходном объекте
                    $totalAttachedFiles[] = new AttachedFile([
                        'name' => $attachFilename,
                        'path' => $file->getPath()
                    ]);

                    $attachNode = $xml->PmtRgltryInfNtfctn->TxNtfctn->TxCert->CertRcrd[$i]->Attchmnt[$filePos++];
                    $attachNode->addChild('DocTp', 'NONE');
                    $attachNode->addChild('DocNb', 0);
                    $attachNode->addChild('SndrRcvrSeqId', $operation->docRepresentation);
                    $attachNode->addChild('URL', $attachFilename);
                    $hashNode = $attachNode->addChild('LkFileHash');
                    $refNode = $hashNode->addChild('ds:Reference', null, 'http://www.w3.org/2000/09/xmldsig#');
                    $refNode->addAttribute('URI', $attachFilename);

                    $algorithm = XMLSecurityDSig::SHA256;

                    $fileHash = XMLSecurityDSig::calculateDigest(
                        $algorithm,
                        file_get_contents($path)
                    );

                    $digestNode = $refNode->addChild('ds:DigestMethod');
                    $digestNode->addAttribute('Algorithm', $algorithm);
                    $refNode->addChild('ds:DigestValue', $fileHash);
                    $attachNode->addChild('AttchdBinryFile', null);
                }
            }
            $i++;
        }

        $typeModel = new Auth024Type();
        $typeModel->loadFromString($xml->saveXML());

        if (!empty($totalAttachedFiles)) {
            static::attachZipContent($typeModel, $totalAttachedFiles);
        }

        return $typeModel;
    }

    public static function createAuth025FromCDI(ConfirmingDocumentInformationExt $model): Auth025Type
    {
        $organization = $model->organization;
        $bank = $model->bank;
        if ($bank === null) {
            throw new \Exception("Cannot find bank, BIK: $model->bankBik");
        }
        
        // Id организации в системе получателя
        $organizationTerminal = $organization->terminal;

	$remoteId = TerminalRemoteId::getRemoteIdByTerminal($organizationTerminal->id, $bank->terminalId);

        $useEmbeddedAttachments = RosbankHelper::isTerminalUsingRosbankFormat($model->bank->terminalId);

        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?>'
                . "\n"
                . '<Document xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="urn:iso:std:iso:20022:tech:xsd:auth.025.001.01"></Document>');

        $msgId = $model->uuid ?: self::cleanUuid();

        $xml->CcyCtrlSpprtgDocDlvry->GrpHdr->MsgId = $msgId;
        $xml->CcyCtrlSpprtgDocDlvry->GrpHdr->CreDtTm = date('c', time());
        $xml->CcyCtrlSpprtgDocDlvry->GrpHdr->NbOfItms = count($model->items); // общее количество подтверждающих документов в справке
        $xml->CcyCtrlSpprtgDocDlvry->GrpHdr->InitgPty->Pty->Id->OrgId->Othr->Id = $remoteId ?: $organization->inn;
        $xml->CcyCtrlSpprtgDocDlvry->GrpHdr->InitgPty->Pty->Id->OrgId->Othr->SchmeNm->Cd = 'TXID';
        $xml->CcyCtrlSpprtgDocDlvry->SpprtgDoc->SpprtgDocId = $msgId . '-1';
        $xml->CcyCtrlSpprtgDocDlvry->SpprtgDoc->Cert->Id = $model->number;
        $xml->CcyCtrlSpprtgDocDlvry->SpprtgDoc->Cert->DtOfIsse = date('Y-m-d', strtotime($model->date));
        $xml->CcyCtrlSpprtgDocDlvry->SpprtgDoc->AcctOwnr->Id->OrgId->Othr->Id = $remoteId ?: $organization->inn;
        $xml->CcyCtrlSpprtgDocDlvry->SpprtgDoc->AcctOwnr->Nm = $organization->name;
        $xml->CcyCtrlSpprtgDocDlvry->SpprtgDoc->AcctOwnr->Id->OrgId->Othr->SchmeNm->Cd = 'TXID';

        if ($model->person) {
            $xml->CcyCtrlSpprtgDocDlvry->SpprtgDoc->AcctOwnr->CtctDtls->Nm = $model->person;
        }

        if ($model->contactNumber) {
            $xml->CcyCtrlSpprtgDocDlvry->SpprtgDoc->AcctOwnr->CtctDtls->PhneNb = $model->contactNumber;
        }

        $xml->CcyCtrlSpprtgDocDlvry->SpprtgDoc->AcctSvcr->FinInstnId->ClrSysMmbId->ClrSysId->Cd = 'RUCBC';
        $xml->CcyCtrlSpprtgDocDlvry->SpprtgDoc->AcctSvcr->FinInstnId->ClrSysMmbId->MmbId = $bank->bik;
        $xml->CcyCtrlSpprtgDocDlvry->SpprtgDoc->AcctSvcr->FinInstnId->Nm = $bank->name;

        // Если указан номер корректировки справки
        if ($model->correctionNumber) {
            $xml->CcyCtrlSpprtgDocDlvry->SpprtgDoc->Amdmnt->CrrctnId = $model->correctionNumber;
        }

        $xml->CcyCtrlSpprtgDocDlvry->SpprtgDoc->CtrctRef->RegdCtrctId = $model->contractPassport;

        // Информация по каждому подтверждающему документу справки
        $i = 0;
        $nodeNumber = 0;
        $attachedFiles = [];

        foreach($model->documents as $document) {
            $nodeNumber++;

            $xml->CcyCtrlSpprtgDocDlvry->SpprtgDoc->Ntry[$i]->NtryId = $nodeNumber;

            $number = $document->number;
            if ($number === null || $number === '') {
                $number = 'БН';
            }

            $xml->CcyCtrlSpprtgDocDlvry->SpprtgDoc->Ntry[$i]->OrgnlDoc->Id = $number;
            $xml->CcyCtrlSpprtgDocDlvry->SpprtgDoc->Ntry[$i]->OrgnlDoc->DtOfIsse = date('Y-m-d', strtotime($document->date));
            $xml->CcyCtrlSpprtgDocDlvry->SpprtgDoc->Ntry[$i]->DocTp = $document->code;

            // Сумма в валюте документа
            $xml->CcyCtrlSpprtgDocDlvry->SpprtgDoc->Ntry[$i]->TtlAmt = $document->sumDocument;
            $sumDocumentNode = $xml->CcyCtrlSpprtgDocDlvry->SpprtgDoc->Ntry[$i]->TtlAmt;
            $sumDocumentNode->addAttribute('Ccy', $document->currencyDocumentTitle);

            // Сумма в валюте контракта, если указана
            if ($document->sumContract) {
                $xml->CcyCtrlSpprtgDocDlvry->SpprtgDoc->Ntry[$i]->TtlAmtInCtrctCcy = $document->sumContract;
                $sumContractNode = $xml->CcyCtrlSpprtgDocDlvry->SpprtgDoc->Ntry[$i]->TtlAmtInCtrctCcy;
                $sumContractNode->addAttribute('Ccy', $document->currencyContractTitle);
            }

            if ($document->type) {
                $xml->CcyCtrlSpprtgDocDlvry->SpprtgDoc->Ntry[$i]->ShipmntAttrbts->Conds->Cd = $document->type;
            }
            if ($document->expectedDate) {
                $xml->CcyCtrlSpprtgDocDlvry->SpprtgDoc->Ntry[$i]->ShipmntAttrbts->XpctdDt = date('Y-m-d', strtotime($document->expectedDate));
            }
            if ($document->countryCode) {
                $xml->CcyCtrlSpprtgDocDlvry->SpprtgDoc->Ntry[$i]->ShipmntAttrbts->CtryOfCntrPty = $document->countryCode;
            }

            if ($document->comment) {
                $xml->CcyCtrlSpprtgDocDlvry->SpprtgDoc->Ntry[$i]->AddtlInf = $document->comment;
            }

            if ($document->attachedFiles) {
                $filePos = 0;
                foreach($document->attachedFiles as $file) {
                    $xml->CcyCtrlSpprtgDocDlvry->SpprtgDoc->Ntry[$i]->addChild('Attchmnt');

                    $path = $file->getPath();
                    if (!is_readable($path)) {
                        throw new \Exception('Attached file not found with path ' . $path);
                    }

                    $attachFilename = 'attach_' . $file->name;

                    // помещаем в массив новый объект AttachedFile с измененным именем файла,
                    // чтобы не мутировало имя в исходном объекте
                    $attachedFiles[] = new AttachedFile([
                        'name' => $attachFilename,
                        'path' => $file->getPath()
                    ]);

                    $attachNode = $xml->CcyCtrlSpprtgDocDlvry->SpprtgDoc->Ntry[$i]->Attchmnt[$filePos++];
                    $attachNode->addChild('DocTp', 'CCDC');
                    $attachNode->addChild('DocNb', $filePos);
                    $attachNode->addChild('IsseDt', date('Y-m-d'));
                    if (!$useEmbeddedAttachments) {
                        $attachNode->addChild('URL', $attachFilename);
                        $hashNode = $attachNode->addChild('LkFileHash');
                        $refNode = $hashNode->addChild('ds:Reference', null, 'http://www.w3.org/2000/09/xmldsig#');
                        $refNode->addAttribute('URI', $attachFilename);

                        $algorithm = XMLSecurityDSig::SHA256;

                        $fileHash = XMLSecurityDSig::calculateDigest(
                            $algorithm,
                            file_get_contents($path)
                        );

                        $digestNode = $refNode->addChild('ds:DigestMethod');
                        $digestNode->addAttribute('Algorithm', $algorithm);
                        $refNode->addChild('ds:DigestValue', $fileHash);
                        $attachNode->AttchdBinryFile->MIMETp = 'NONE';
                    } else {
                        $attachNode->addChild('URL', $file->name);
                        $fileElement = $attachNode->addChild('AttchdBinryFile');
                        $mimeType = mime_content_type($file->getPath()) ?: null;;
                        if ($mimeType && strlen($mimeType) <= 35) { // MIMETp length is restricted in XSD
                            $fileElement->MIMETp = $mimeType;
                        }
                        $fileElement->NcodgTp = 'base64';
                        $fileElement->InclBinryObjct = base64_encode(file_get_contents($file->getPath()));
                    }
                }
            }

            if ($document->originalDocumentNumber) {
                $xml->CcyCtrlSpprtgDocDlvry->SpprtgDoc->Ntry[$i]->Amdmnt->OrgnlDocId = $document->originalDocumentNumber;
            }

            if ($document->originalDocumentDate) {
                $xml->CcyCtrlSpprtgDocDlvry->SpprtgDoc->Ntry[$i]->Amdmnt->OrgnlDocDt = date('Y-m-d', strtotime($document->originalDocumentDate));
            }

            $i++;
        }

        $typeModel = new Auth025Type();
        $typeModel->sender = $organizationTerminal->terminalId;
        $typeModel->loadFromString($xml->saveXML());

        if (!$useEmbeddedAttachments && !empty($attachedFiles)) {
            self::addAttachmentsToAuth025($typeModel, $attachedFiles);
        }

        return $typeModel;
    }

    /**
     * @param Auth025Type $typeModel
     * @param AttachedFile[] $attachedFiles
     */
    private static function addAttachmentsToAuth025(Auth025Type $typeModel, array $attachedFiles): void
    {
        if (empty($attachedFiles)) {
            return;
        }

        /** @var ArchiveFileZip $zip */
        $zip = ZipHelper::createTempArchiveFileZip();
        if (!$zip) {
            throw new \Exception('Failed to create temp zip archive');
        }

        $typeModel->buildXML();
        $modelName = $typeModel->createFileName();
        $zip->addFromString((string) $typeModel, $modelName . '.xml', 'cp866');

        foreach ($attachedFiles as $attachedFile) {
            $zip->addFile($attachedFile->path, $attachedFile->name, 'cp866');
        }

        $typeModel->zipContent = $zip->asString();
        $typeModel->useZipContent = true;
        $typeModel->zipFilename = $modelName . '.zip';

        $zip->purge();
    }

    public static function createAuth018FromCRR(ContractRegistrationRequestExt $model)
    {
        // Получение организации
        $organization = $model->organization;

        if (!$organization) {
            throw new \Exception("Organization {$model->organizationId} not found");
        }

        $terminal = Terminal::findOne($organization->terminalId);

        // Получение первого счета организации
        try {
            $account = $organization->accounts[0];
        } catch (Exception $e) {
            throw new \Exception("Failed to get accounts for organization {$organization->id}");
        }

        // Получение банка по счету организации
        try {
            $bank = $account->bank;
        } catch (Exception $e) {
            throw new \Exception("Failed to get bank for account {$account->id}");
        }

        // Получение валюты
        try {
            $currency = $model->currency;
        } catch (Exception $e) {
            throw new \Exception("Failed to get currency ($model->currencyId)");
        }

        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?>'
                . "\n"
                . '<Document xmlns="urn:iso:std:iso:20022:tech:xsd:auth.018.001.01" xmlns:n2="http://www.w3.org/2000/09/xmldsig#Reference" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"></Document>');

        $msgId = $terminal->terminalId . '-CRR-' . date('Ymd', strtotime($model->date)) . '-' . $model->number;

        $xml->CtrctRegnReq->GrpHdr->MsgId = $msgId;
        $xml->CtrctRegnReq->GrpHdr->CreDtTm = date('c', strtotime($model->date));
        $xml->CtrctRegnReq->GrpHdr->NbOfItms = 1;
        $xml->CtrctRegnReq->GrpHdr->InitgPty->Id->OrgId->Othr->Id = $organization->inn;
        $xml->CtrctRegnReq->GrpHdr->InitgPty->Id->OrgId->Othr->SchmeNm->Cd = 'TXID';

        $xml->CtrctRegnReq->CtrctRegn->CtrctRegnId = $msgId . '-01';
        $xml->CtrctRegnReq->CtrctRegn->RptgPty->PtyId->Nm = $organization->name;
        $xml->CtrctRegnReq->CtrctRegn->RptgPty->PtyId->PstlAdr->AdrTp = 'BIZZ';
        $xml->CtrctRegnReq->CtrctRegn->RptgPty->PtyId->PstlAdr->Dept = $model->building;
        $xml->CtrctRegnReq->CtrctRegn->RptgPty->PtyId->PstlAdr->SubDept = $model->apartment;
        $xml->CtrctRegnReq->CtrctRegn->RptgPty->PtyId->PstlAdr->StrtNm = $model->street;

        if ($model->buildingNumber) {
            $xml->CtrctRegnReq->CtrctRegn->RptgPty->PtyId->PstlAdr->BldgNb = $model->buildingNumber;
        }

        $xml->CtrctRegnReq->CtrctRegn->RptgPty->PtyId->PstlAdr->TwnLctnNm = $model->locality;
        $xml->CtrctRegnReq->CtrctRegn->RptgPty->PtyId->PstlAdr->DstrctNm = $model->district;
        $xml->CtrctRegnReq->CtrctRegn->RptgPty->PtyId->PstlAdr->CtrySubDvsn = $model->state;
        $xml->CtrctRegnReq->CtrctRegn->RptgPty->PtyId->Id->OrgId->Othr->Id = $organization->inn;
        $xml->CtrctRegnReq->CtrctRegn->RptgPty->PtyId->Id->OrgId->Othr->SchmeNm->Cd = 'TXID';
        $xml->CtrctRegnReq->CtrctRegn->RptgPty->PtyId->CtryOfRes = 'RU';
        $xml->CtrctRegnReq->CtrctRegn->RptgPty->LglOrg->Id = $model->ogrn;
        $xml->CtrctRegnReq->CtrctRegn->RptgPty->LglOrg->RegnDt = date('Y-m-d', strtotime($model->dateEgrul));
        $xml->CtrctRegnReq->CtrctRegn->RptgPty->TaxPty->TaxTp = $organization->kpp;

        $xml->CtrctRegnReq->CtrctRegn->RegnAgt->FinInstnId->ClrSysMmbId->ClrSysId->Cd = 'RUCBC';
        $xml->CtrctRegnReq->CtrctRegn->RegnAgt->FinInstnId->ClrSysMmbId->MmbId = $account->bankBik;
        $xml->CtrctRegnReq->CtrctRegn->RegnAgt->FinInstnId->Nm = $bank->name;

        $xml->CtrctRegnReq->CtrctRegn->CtrctRegnOpng->CtrctRegnOpngId = $msgId . '-02';
        $xml->CtrctRegnReq->CtrctRegn->CtrctRegnOpng->Prty = 'NORM';

        // Определение корневого тэга xml-дерева
        if ($model->passportType == $model::PASSPORT_TYPE_TRADE) {
            // Тип контракт
            $rootTag = 'Trad';
        } else if ($model->passportType == $model::PASSPORT_TYPE_LOAN) {
            // Тип кредитный договор
            $rootTag = 'Ln';
        }

        // Общие ветки для всех типов документов
        if ($model->passportTypeNumber) {
            $xml->CtrctRegnReq->CtrctRegn->CtrctRegnOpng->Ctrct->$rootTag->CtrctDocId->Id = $model->passportTypeNumber;
            $xml->CtrctRegnReq->CtrctRegn->CtrctRegnOpng->Ctrct->$rootTag->CtrctDocId->DtOfIsse = date('Y-m-d', strtotime($model->date));
        }

        // Сумма для контракта здесь
        if ($model->amount && $model->passportType == $model::PASSPORT_TYPE_TRADE) {
            $xml->CtrctRegnReq->CtrctRegn->CtrctRegnOpng->Ctrct->$rootTag->Amt = $model->amount;
            $contractSum = $xml->CtrctRegnReq->CtrctRegn->CtrctRegnOpng->Ctrct->$rootTag->Amt;
            $contractSum->addAttribute('Ccy', $currency->name);
        }

        $xml->CtrctRegnReq->CtrctRegn->CtrctRegnOpng->Ctrct->$rootTag->Buyr->PtyId->Id->OrgId->Othr->Id = $organization->inn;
        $xml->CtrctRegnReq->CtrctRegn->CtrctRegnOpng->Ctrct->$rootTag->Buyr->PtyId->Id->OrgId->Othr->SchmeNm->Cd = 'TXID';
        $xml->CtrctRegnReq->CtrctRegn->CtrctRegnOpng->Ctrct->$rootTag->Buyr->PtyId->CtryOfRes = 'RU';


        $i = 0;
        foreach($model->nonresidents as $nonresident) {
            if ($nonresident->isCredit) {
                continue;
            }

            $xml->CtrctRegnReq->CtrctRegn->CtrctRegnOpng->Ctrct->$rootTag->Sellr[$i]->PtyId->Nm = $nonresident->name;
            $xml->CtrctRegnReq->CtrctRegn->CtrctRegnOpng->Ctrct->$rootTag->Sellr[$i]->PtyId->CtryOfRes = $nonresident->countryCode;

            $i++;
        }

        // Сумма для кредитного договора здесь
        if ($model->amount && $model->passportType == $model::PASSPORT_TYPE_LOAN) {
            $xml->CtrctRegnReq->CtrctRegn->CtrctRegnOpng->Ctrct->$rootTag->Amt = $model->amount;
            $contractSum = $xml->CtrctRegnReq->CtrctRegn->CtrctRegnOpng->Ctrct->$rootTag->Amt;
            $contractSum->addAttribute('Ccy', $currency->name);
        }

        $xml->CtrctRegnReq->CtrctRegn->CtrctRegnOpng->Ctrct->$rootTag->MtrtyDt = date('Y-m-d', strtotime($model->completionDate));
        $xml->CtrctRegnReq->CtrctRegn->CtrctRegnOpng->Ctrct->$rootTag->PrlngtnFlg = 'false';

        $xml->CtrctRegnReq->CtrctRegn->CtrctRegnOpng->Ctrct->$rootTag->StartDt = date('Y-m-d', strtotime($model->signingDate));
        $xml->CtrctRegnReq->CtrctRegn->CtrctRegnOpng->Ctrct->$rootTag->SttlmCcy = $currency->name;

        // Специфичные данные для каждого типа
        if ($model->passportType == $model::PASSPORT_TYPE_TRADE) {
            // Тип контракт

            if ($model->existedPassport) {
                $xml->CtrctRegnReq->CtrctRegn->CtrctRegnOpng->PrvsRegnId->Id = $model->existedPassport;
            }
        } else if ($model->passportType == $model::PASSPORT_TYPE_LOAN) {
            // Тип кредитный договор

            $incAmount = $model->repaymentForeignCurrencyEarnings ?: 0;
            $xml->CtrctRegnReq->CtrctRegn->CtrctRegnOpng->Ctrct->$rootTag->SpclConds->IncmgAmt = $incAmount;
            $incAmountNode = $xml->CtrctRegnReq->CtrctRegn->CtrctRegnOpng->Ctrct->$rootTag->SpclConds->IncmgAmt;
            $incAmountNode->addAttribute('Ccy', $currency->name);

            $outAmount = $model->creditedAccountsAbroad ?: 0;
            $xml->CtrctRegnReq->CtrctRegn->CtrctRegnOpng->Ctrct->$rootTag->SpclConds->OutgngAmt = $outAmount;
            $outAmountNode = $xml->CtrctRegnReq->CtrctRegn->CtrctRegnOpng->Ctrct->$rootTag->SpclConds->OutgngAmt;
            $outAmountNode->addAttribute('Ccy', $currency->name);

            $xml->CtrctRegnReq->CtrctRegn->CtrctRegnOpng->Ctrct->$rootTag->DrtnCd = $model->codeTermInvolvement;

            if ($model->codeLibor) {
                $xml->CtrctRegnReq->CtrctRegn->CtrctRegnOpng->Ctrct->$rootTag->IntrstRate->Fltg->RefRate->Indx = 'LIBO';
                $xml->CtrctRegnReq->CtrctRegn->CtrctRegnOpng->Ctrct->$rootTag->IntrstRate->Fltg->Term->Unit = 'MNTH';

                // Получение срока кода ЛИБОР
                $liborPeriod = str_replace(['L', '0'], '', $model->codeLibor);

                $xml->CtrctRegnReq->CtrctRegn->CtrctRegnOpng->Ctrct->$rootTag->IntrstRate->Fltg->Term->Val = $liborPeriod;
                $xml->CtrctRegnReq->CtrctRegn->CtrctRegnOpng->Ctrct->$rootTag->IntrstRate->Fltg->BsisPtSprd = $model->fixedRate;
            }

            // График платежей
            $mainDept = [];
            $interest = [];

            foreach($model->paymentSchedule as $paymentSchedule) {
                if ($paymentSchedule->mainDeptDate || $paymentSchedule->mainDeptAmount) {
                    $mainDept[] = [
                        'date' => date('Y-m-d', strtotime($paymentSchedule->mainDeptDate)),
                        'amount' => $paymentSchedule->mainDeptAmount
                    ];
                }

                if ($paymentSchedule->interestPaymentsDate || $paymentSchedule->interestPaymentsAmount) {
                    $interest[] = [
                        'date' => date('Y-m-d', strtotime($paymentSchedule->interestPaymentsDate)),
                        'amount' => $paymentSchedule->interestPaymentsAmount,
                        'comment' => $paymentSchedule->comment
                    ];
                }
            }

            // Погашение основного долга
            foreach($mainDept as $id => $item) {
                $xml->CtrctRegnReq->CtrctRegn->CtrctRegnOpng->Ctrct->$rootTag->PmtSchdl->SubSchdl[$id]->Amt = $item['amount'];
                $amt = $xml->CtrctRegnReq->CtrctRegn->CtrctRegnOpng->Ctrct->$rootTag->PmtSchdl->SubSchdl[$id]->Amt;
                $amt->addAttribute('Ccy', $currency->name);
                $xml->CtrctRegnReq->CtrctRegn->CtrctRegnOpng->Ctrct->$rootTag->PmtSchdl->SubSchdl[$id]->DueDt = $item['date'];
            }

            // В счет процентных платежей
            foreach($interest as $id => $item) {
                $xml->CtrctRegnReq->CtrctRegn->CtrctRegnOpng->Ctrct->$rootTag->IntrstSchdl->SubSchdl[$id]->Amt = $item['amount'];
                $amt = $xml->CtrctRegnReq->CtrctRegn->CtrctRegnOpng->Ctrct->$rootTag->IntrstSchdl->SubSchdl[$id]->Amt;
                $amt->addAttribute('Ccy', $currency->name);
                $xml->CtrctRegnReq->CtrctRegn->CtrctRegnOpng->Ctrct->$rootTag->IntrstSchdl->SubSchdl[$id]->DueDt = $item['date'];
                $xml->CtrctRegnReq->CtrctRegn->CtrctRegnOpng->Ctrct->$rootTag->IntrstSchdl->SubSchdl[$id]->AddtlInf = $item['comment'];
            }

            $xml->CtrctRegnReq->CtrctRegn->CtrctRegnOpng->Ctrct->$rootTag->IntraCpnyLn = 'false';

            if ($model->amountCollateral) {
                $xml->CtrctRegnReq->CtrctRegn->CtrctRegnOpng->Ctrct->$rootTag->Coll->TtlAmt = $model->amountCollateral;
                $ttlAmt = $xml->CtrctRegnReq->CtrctRegn->CtrctRegnOpng->Ctrct->$rootTag->Coll->TtlAmt;
                $ttlAmt->addAttribute('Ccy', $currency->name);
            }
        }

        return $xml->saveXML();
    }

    /**
     * Создание pain001 из документа продажи валюты с транзитного счета
     * @param $typeModel
     */
    public static function createPain001FromFCST(ForeignCurrencySellTransit $typeModel)
    {
        /** @var DictOrganization $organization */
        $organization = $typeModel->getOrganization();

        $organizationTerminal = $organization->terminal;

        // Если у организации нет терминала - это ошибка
        if (!$organizationTerminal) {
            throw new InvalidValueException("Can't find organization terminal");
        }

        // Транзитный счет
        $transitAccount = $typeModel->getAccount($typeModel->transitAccount);

        if ($transitAccount) {
            $transitAccountTerminalId = $transitAccount->getTerminalId();
            $transitAccountCurrency = $transitAccount->edmDictCurrencies;
        } else {
            throw new InvalidValueException("Can't find transit account data");
        }

        // Валютный счет
        $foreignAccount = $typeModel->getAccount($typeModel->foreignAccount);
        $foreignAccountCurrency = $foreignAccount
            ? $foreignAccountCurrency = $foreignAccount->edmDictCurrencies
            : null;

        $commissionAccount = $typeModel->getAccount($typeModel->commissionAccount);
        $commissionAccountCurrency = $commissionAccount
            ? $foreignAccountCurrency = $commissionAccount->edmDictCurrencies
            : null;


        // Расчетный счет
        $account = $typeModel->getAccount($typeModel->transitAccount);

        if ($account) {
            $accountTerminalId = $account->getTerminalId();
        }

        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?>'
                . "\n"
                . '<Document xmlns="urn:iso:std:iso:20022:tech:xsd:pain.001.001.06"></Document>');

        $uuid = self::cleanUuid();
        $xml->CstmrCdtTrfInitn->GrpHdr->MsgId = $uuid;
        $xml->CstmrCdtTrfInitn->GrpHdr->CreDtTm = date('c', strtotime($typeModel->date));
        $xml->CstmrCdtTrfInitn->GrpHdr->NbOfTxs = 1;
        $xml->CstmrCdtTrfInitn->GrpHdr->CtrlSum = (int) $typeModel->amountTransfer + (int) $typeModel->amountSell;

        if ($organization->name) {
            $xml->CstmrCdtTrfInitn->GrpHdr->InitgPty->Nm = $organization->name;
        }
        $xml->CstmrCdtTrfInitn->GrpHdr->InitgPty->Id->OrgId->Othr[0]->Id = $organizationTerminal->terminalId;
        $xml->CstmrCdtTrfInitn->GrpHdr->InitgPty->Id->OrgId->Othr[0]->SchmeNm->Prtry = 'CFTBIC';
        $xml->CstmrCdtTrfInitn->GrpHdr->InitgPty->Id->OrgId->Othr[1]->Id = $foreignAccount->number;
        $xml->CstmrCdtTrfInitn->GrpHdr->InitgPty->Id->OrgId->Othr[1]->SchmeNm->Cd = 'BBAN';
        if ($organization->inn) {
            $xml->CstmrCdtTrfInitn->GrpHdr->InitgPty->Id->OrgId->Othr[2]->Id = $organization->inn;
            $xml->CstmrCdtTrfInitn->GrpHdr->InitgPty->Id->OrgId->Othr[2]->SchmeNm->Cd = 'TXID';
        }

        //$xml->CstmrCdtTrfInitn->GrpHdr->InitgPty->CtctDtls->Nm = $typeModel->contactPersonName;
        // $xml->CstmrCdtTrfInitn->GrpHdr->InitgPty->CtctDtls->PhneNb = $phone;

        $xml->CstmrCdtTrfInitn->GrpHdr->FwdgAgt->FinInstnId->BICFI = Address::truncateAddress($accountTerminalId);
        $xml->CstmrCdtTrfInitn->GrpHdr->FwdgAgt->FinInstnId->Nm = $account->bank->name;

        $xml->CstmrCdtTrfInitn->PmtInf->PmtInfId = $uuid;
        $xml->CstmrCdtTrfInitn->PmtInf->PmtMtd = 'TRF';
        $xml->CstmrCdtTrfInitn->PmtInf->PmtTpInf->LclInstrm->Prtry = 'RU-FCYRLS';
        $xml->CstmrCdtTrfInitn->PmtInf->ReqdExctnDt = date('Y-m-d', time());

        $xml->CstmrCdtTrfInitn->PmtInf->Dbtr->Nm = $organization->name;
        if ($organization->address) {
            $addressLines = StringHelper::mb_wordwrap($organization->address, 70);
            foreach ($addressLines as $lineIdx => $line) {
                $xml->CstmrCdtTrfInitn->PmtInf->Dbtr->PstlAdr->AdrLine[$lineIdx] = $line;
            }
        }

        $xml->CstmrCdtTrfInitn->PmtInf->Dbtr->Id->OrgId->Othr[0]->Id = Address::truncateAddress($organizationTerminal->terminalId);
        $xml->CstmrCdtTrfInitn->PmtInf->Dbtr->Id->OrgId->Othr[0]->SchmeNm->Prtry = 'CFTBIC';


        // Id организации в системе получателя
        $remoteId = TerminalRemoteId::getRemoteIdByTerminal($organizationTerminal->id);

        $n = 1;
        if ($remoteId) {
            $xml->CstmrCdtTrfInitn->PmtInf->Dbtr->Id->OrgId->Othr[$n]->Id = $remoteId;
            $xml->CstmrCdtTrfInitn->PmtInf->Dbtr->Id->OrgId->Othr[$n++]->SchmeNm->Prtry = 'BANK';
        }

        $xml->CstmrCdtTrfInitn->PmtInf->Dbtr->Id->OrgId->Othr[$n]->Id = $organization->inn;
        $xml->CstmrCdtTrfInitn->PmtInf->Dbtr->Id->OrgId->Othr[$n++]->SchmeNm->Cd = 'TXID';

        $xml->CstmrCdtTrfInitn->PmtInf->Dbtr->Id->OrgId->Othr[$n]->Id = $typeModel->transitAccount;
        $xml->CstmrCdtTrfInitn->PmtInf->Dbtr->Id->OrgId->Othr[$n++]->SchmeNm->Cd = 'BBAN';

        $xml->CstmrCdtTrfInitn->PmtInf->Dbtr->CtryOfRes = 'RU';
        $xml->CstmrCdtTrfInitn->PmtInf->Dbtr->CtctDtls->Nm = $typeModel->contactPersonName;
        // Подготовка номера телефона для представления в нужном формате
        $phone = $typeModel->contactPersonPhone;
        $phone = str_replace(['(', ')'], '', $phone);
        $phone = str_replace(' ', '-', $phone);
        $xml->CstmrCdtTrfInitn->PmtInf->Dbtr->CtctDtls->PhneNb = $phone;

        // Транзитный счет
        $xml->CstmrCdtTrfInitn->PmtInf->DbtrAcct->Id->Othr->Id = $typeModel->transitAccount;
        $xml->CstmrCdtTrfInitn->PmtInf->DbtrAcct->Id->Othr->SchmeNm->Cd = 'BBAN';
        $xml->CstmrCdtTrfInitn->PmtInf->DbtrAcct->Ccy = $transitAccountCurrency->name;

        $xml->CstmrCdtTrfInitn->PmtInf->DbtrAgt->FinInstnId->BICFI = Address::truncateAddress($transitAccountTerminalId);

        $xml->CstmrCdtTrfInitn->PmtInf->DbtrAgt->FinInstnId->ClrSysMmbId->ClrSysId->Cd = 'RUCBC';

        $xml->CstmrCdtTrfInitn->PmtInf->DbtrAgt->FinInstnId->ClrSysMmbId->MmbId = $account->bank->bik;
        $xml->CstmrCdtTrfInitn->PmtInf->DbtrAgt->FinInstnId->Nm = $account->bank->name;
        $xml->CstmrCdtTrfInitn->PmtInf->DbtrAgt->FinInstnId->PstlAdr->Ctry = 'RU';

        $xml->CstmrCdtTrfInitn->PmtInf->ChrgBr = 'DEBT';

        // Счет комиссий и расходов
        $xml->CstmrCdtTrfInitn->PmtInf->ChrgsAcct->Id->Othr->Id = $typeModel->commissionAccount;
        $xml->CstmrCdtTrfInitn->PmtInf->ChrgsAcct->Id->Othr->SchmeNm->Cd = 'BBAN';
        $xml->CstmrCdtTrfInitn->PmtInf->ChrgsAcct->Ccy = $commissionAccountCurrency->name;

        $infoNumber = 0;

        // Зачисление на транзитный счет
        $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[$infoNumber]->PmtId->InstrId = $uuid;
        $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[$infoNumber]->PmtId->EndToEndId = $typeModel->number;
        $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[$infoNumber]->PmtTpInf->LclInstrm->Prtry = 'NTF';

        $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[$infoNumber]->Amt->InstdAmt = $typeModel->amount;
        $instdAmt = $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[$infoNumber]->Amt->InstdAmt;
        $instdAmt->addAttribute('Ccy', $transitAccountCurrency->name);

        $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[$infoNumber]->RmtInf->Strd[0]->RfrdDocInf->Nb = $typeModel->currencyIncomingNumber;
        $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[$infoNumber]->RmtInf->Strd[0]->RfrdDocInf->RltdDt = date('Y-m-d', strtotime($typeModel->currencyIncomingDate));

        $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[$infoNumber]->RmtInf->Strd[0]->RfrdDocAmt->CdtNoteAmt = $typeModel->amount;
        $rfrdDocAmt = $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[$infoNumber]->RmtInf->Strd[0]->RfrdDocAmt->CdtNoteAmt;
        $rfrdDocAmt->addAttribute('Ccy', $transitAccountCurrency->name);

        $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[$infoNumber]->RmtInf->Strd[1]->RfrdDocInf->Tp->CdOrPrtry->Prtry = 'POD';
        $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[$infoNumber]->RmtInf->Strd[1]->RfrdDocInf->RltdDt = date('Y-m-d', strtotime($typeModel->date));

        $infoNumber++;

        // Валютный счет
        if ($foreignAccount) {
            $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[$infoNumber]->PmtId->InstrId = $uuid;
            $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[$infoNumber]->PmtId->EndToEndId = $typeModel->number;
            $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[$infoNumber]->PmtTpInf->LclInstrm->Prtry = 'TRF';

            $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[$infoNumber]->Amt->InstdAmt = $typeModel->amountTransfer;
            $instdAmt = $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[$infoNumber]->Amt->InstdAmt;
            $instdAmt->addAttribute('Ccy', $foreignAccountCurrency->name);

            //$xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[$infoNumber]->CdtrAgt->FinInstnId->BICFI = Address::truncateAddress($foreignAccountTerminalId);
            $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[$infoNumber]->CdtrAgt->FinInstnId->ClrSysMmbId->ClrSysId->Cd = 'RUCBC'; // всегда RUCBC
            $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[$infoNumber]->CdtrAgt->FinInstnId->ClrSysMmbId->MmbId = $account->bank->bik;
            $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[$infoNumber]->CdtrAgt->FinInstnId->Nm = $account->bank->name;
            $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[$infoNumber]->CdtrAgt->FinInstnId->PstlAdr->Ctry = 'RU'; // всегда RU
            $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[$infoNumber]->CdtrAcct->Id->Othr->Id = $typeModel->foreignAccount;
            $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[$infoNumber]->CdtrAcct->Id->Othr->SchmeNm->Cd = 'BBAN';
            $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[$infoNumber]->CdtrAcct->Ccy = $foreignAccountCurrency->name;

            $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[$infoNumber]->RmtInf->Strd->RfrdDocInf->Tp->CdOrPrtry->Prtry = 'POD';
            $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[$infoNumber]->RmtInf->Strd->RfrdDocInf->RltdDt = date('Y-m-d', strtotime($typeModel->date));

            $infoNumber++;
        }

        // Расчетный счет
        if ($account && $typeModel->sellOnMarket) {
            $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[$infoNumber]->PmtId->InstrId = $uuid;
            $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[$infoNumber]->PmtId->EndToEndId = $typeModel->number;
            $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[$infoNumber]->PmtTpInf->LclInstrm->Prtry = 'FX';

            //Убрал (int), для того, чтобы учитывались "копейки"
            $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[$infoNumber]->Amt->InstdAmt = $typeModel->amountSell;
            $instdAmt = $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[$infoNumber]->Amt->InstdAmt;
            $instdAmt->addAttribute('Ccy', $transitAccountCurrency->name);

            $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[$infoNumber]->XchgRateInf->RateTp = 'AGRD';
            $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[$infoNumber]->CdtrAgt->FinInstnId->BICFI = Address::truncateAddress($accountTerminalId);

            $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[$infoNumber]->CdtrAcct->Id->Othr->Id = $typeModel->account;
            $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[$infoNumber]->CdtrAcct->Id->Othr->SchmeNm->Cd = 'BBAN';
            $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[$infoNumber]->CdtrAcct->Ccy = $foreignAccountCurrency->name;
        }

        return $xml->saveXML();
    }

    public static function createPain001FromFCO(ForeignCurrencyOperationType $typeModel): string
    {
        $organization = $typeModel->getOrganization();

        $organizationTerminal = $organization->terminal;

        // Если у организации нет терминала - это ошибка
        if (!$organizationTerminal) {
            throw new InvalidValueException("Can't find organization terminal");
        }

        $debitAccount = EdmPayerAccount::findOne(['number' => $typeModel->getDebitAccount()->number]);

        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?>'
            . "\n"
            . '<Document xmlns="urn:iso:std:iso:20022:tech:xsd:pain.001.001.06"></Document>');

        $uuid = self::cleanUuid(false);

        $xml->CstmrCdtTrfInitn->GrpHdr->MsgId = $uuid;
        $xml->CstmrCdtTrfInitn->GrpHdr->CreDtTm = date('c', strtotime($typeModel->date));
        $xml->CstmrCdtTrfInitn->GrpHdr->NbOfTxs = 1;

        if ($organization->name) {
            $xml->CstmrCdtTrfInitn->GrpHdr->InitgPty->Nm = $organization->name;
        }
        $xml->CstmrCdtTrfInitn->GrpHdr->InitgPty->Id->OrgId->Othr[0]->Id = $organizationTerminal->terminalId;
        $xml->CstmrCdtTrfInitn->GrpHdr->InitgPty->Id->OrgId->Othr[0]->SchmeNm->Prtry = 'CFTBIC';
        $xml->CstmrCdtTrfInitn->GrpHdr->InitgPty->Id->OrgId->Othr[1]->Id = $debitAccount->number;
        $xml->CstmrCdtTrfInitn->GrpHdr->InitgPty->Id->OrgId->Othr[1]->SchmeNm->Cd = 'BBAN';
        if ($organization->inn) {
            $xml->CstmrCdtTrfInitn->GrpHdr->InitgPty->Id->OrgId->Othr[2]->Id = $organization->inn;
            $xml->CstmrCdtTrfInitn->GrpHdr->InitgPty->Id->OrgId->Othr[2]->SchmeNm->Cd = 'TXID';
        }

        $xml->CstmrCdtTrfInitn->GrpHdr->FwdgAgt->FinInstnId->BICFI = Address::truncateAddress($debitAccount->terminalId);
        $xml->CstmrCdtTrfInitn->GrpHdr->FwdgAgt->FinInstnId->Nm = $debitAccount->bank->name;

        $xml->CstmrCdtTrfInitn->PmtInf->PmtInfId = $uuid;
        $xml->CstmrCdtTrfInitn->PmtInf->PmtMtd = 'TRF';
        $xml->CstmrCdtTrfInitn->PmtInf->PmtTpInf->SvcLvl->Cd = 'NURG';
        $xml->CstmrCdtTrfInitn->PmtInf->PmtTpInf->LclInstrm->Prtry = 'RU-FX';
        $xml->CstmrCdtTrfInitn->PmtInf->ReqdExctnDt = date('Y-m-d', time());

        $xml->CstmrCdtTrfInitn->PmtInf->Dbtr->Nm = $organization->name;

        $addressLines = StringHelper::mb_wordwrap($organization->fullAddress, 70);
        foreach ($addressLines as $lineIdx => $line) {
            $xml->CstmrCdtTrfInitn->PmtInf->Dbtr->PstlAdr->AdrLine[$lineIdx] = $line;
        }
        $xml->CstmrCdtTrfInitn->PmtInf->Dbtr->Id->OrgId->Othr[0]->Id = $organization->inn;
        $xml->CstmrCdtTrfInitn->PmtInf->Dbtr->Id->OrgId->Othr[0]->SchmeNm->Cd = 'TXID';

        $xml->CstmrCdtTrfInitn->PmtInf->Dbtr->CtryOfRes = 'RU';
        if ($typeModel->getApplicant()->contactPerson) {
            $xml->CstmrCdtTrfInitn->PmtInf->Dbtr->CtctDtls->Nm = $typeModel->getApplicant()->contactPerson;
        }
        if ($typeModel->getApplicant()->phone) {
            $phone = $typeModel->getApplicant()->phone;
            $phone = str_replace(['(', ')'], '', $phone);
            $phone = str_replace(' ', '-', $phone);
            $xml->CstmrCdtTrfInitn->PmtInf->Dbtr->CtctDtls->PhneNb = $phone;
        }

        $xml->CstmrCdtTrfInitn->PmtInf->DbtrAcct->Id->Othr->Id = $debitAccount->number;
        $xml->CstmrCdtTrfInitn->PmtInf->DbtrAcct->Id->Othr->SchmeNm->Cd = 'BBAN';
        $xml->CstmrCdtTrfInitn->PmtInf->DbtrAcct->Ccy = $debitAccount->edmDictCurrencies->name;

        $xml->CstmrCdtTrfInitn->PmtInf->DbtrAgt->FinInstnId->ClrSysMmbId->ClrSysId->Cd = 'RUCBC';
        $xml->CstmrCdtTrfInitn->PmtInf->DbtrAgt->FinInstnId->ClrSysMmbId->MmbId = $debitAccount->bankBik;
        $xml->CstmrCdtTrfInitn->PmtInf->DbtrAgt->FinInstnId->Nm = $debitAccount->bank->name;

        $xml->CstmrCdtTrfInitn->PmtInf->ChrgsAcct->Id->Othr->Id = $typeModel->getCommissionAccount()->number;
        $xml->CstmrCdtTrfInitn->PmtInf->ChrgsAcct->Id->Othr->SchmeNm->Cd = 'BBAN';

        $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf->PmtId->InstrId = $uuid;
        $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf->PmtId->EndToEndId = $typeModel->numberDocument;
        $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf->PmtTpInf->SvcLvl->Prtry = 'FX';

        $amount = $typeModel->paymentOrderAmount ?: $typeModel->paymentOrderCurrAmount;
        $isPurchase = in_array($typeModel->getDebitAccountCurrency()->name, ['RUR', 'RUB']);
        $currency = ($typeModel->paymentOrderAmount && $isPurchase) || (!$typeModel->paymentOrderAmount && !$isPurchase)
            ? $typeModel->getDebitAccountCurrency()->name
            : $typeModel->getCreditAccountCurrency()->name;
        $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf->Amt->InstdAmt = $amount;
        $instdAmt = $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf->Amt->InstdAmt;
        $instdAmt->addAttribute('Ccy', $currency);

        if ($typeModel->paymentOrderCurrExchangeRate) {
            $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf->XchgRateInf->XchgRate = $typeModel->paymentOrderCurrExchangeRate;
        }
        $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf->XchgRateInf->RateTp = 'AGRD';

        $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf->ChrgBr = 'DEBT';

        $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf->CdtrAgt->FinInstnId->ClrSysMmbId->ClrSysId->Cd = 'RUCBC';
        $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf->CdtrAgt->FinInstnId->ClrSysMmbId->MmbId = $typeModel->getCreditAccount()->bik;
        $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf->CdtrAgt->FinInstnId->Nm = $typeModel->getCreditAccount()->bankName;

        $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf->CdtrAgtAcct->Id->Othr->Id = $typeModel->getCreditAccount()->bankAccountNumber;
        $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf->CdtrAgtAcct->Id->Othr->SchmeNm->Cd = 'BBAN';

        $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf->Cdtr->Nm = $organization->name;
        $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf->Cdtr->Id->OrgId->Othr->Id = $organization->inn;
        $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf->Cdtr->Id->OrgId->Othr->SchmeNm->Cd = 'TXID';

        $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf->CdtrAcct->Id->Othr->Id = $typeModel->getCreditAccount()->number;
        $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf->CdtrAcct->Id->Othr->SchmeNm->Cd = 'BBAN';
        $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf->CdtrAcct->Ccy = $typeModel->getCreditAccountCurrency()->name;

        $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf->RmtInf->Strd->RfrdDocInf->Tp->CdOrPrtry->Prtry = 'POD';
        $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf->RmtInf->Strd->RfrdDocInf->RltdDt = date('Y-m-d', strtotime($typeModel->date));

        return $xml->saveXML();
    }


    /**
     * Создание pain001 из документа Поручение на конвертацию валюты
     * @param $typeModel
     */
    public static function createPain001FromFCVN(ForeignCurrencyConversion $typeModel)
    {
        $organization = $typeModel->getOrganization();

        $organizationTerminal = $organization->terminal;

        // Если у организации нет терминала - это ошибка
        if (!$organizationTerminal) {
            throw new InvalidValueException("Can't find organization terminal");
        }

        try {
            // Счет списания
            $debitAccount = EdmPayerAccount::findOne(['number' => $typeModel->debitAccount]);
            $debitAccountCurrencyName = $debitAccount->edmDictCurrencies->name;
            $debitAccountBank = $debitAccount->bank;
            $debitAccountBankTerminalId = $debitAccountBank->terminalId;
            $debitAccountBankName = $debitAccountBank->name;
        } catch(\Exception $e) {
            throw new InvalidValueException('Failed to get debit account data');
        }

        try {
            // Счет зачисления
            $creditAccount = EdmPayerAccount::findOne(['number' => $typeModel->creditAccount]);
            $creditAccountCurrencyName = $creditAccount->edmDictCurrencies->name;
            $creditAccountBank = $creditAccount->bank;
            $creditAccountBankTerminalId = $creditAccountBank->terminalId;
        } catch(\Exception $e) {
            throw new InvalidValueException('Failed to get credit account data');
        }

        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?>'
                . "\n"
                . '<Document xmlns="urn:iso:std:iso:20022:tech:xsd:pain.001.001.06"></Document>');

        $uuid = self::cleanUuid();

        $xml->CstmrCdtTrfInitn->GrpHdr->MsgId = $uuid;
        $xml->CstmrCdtTrfInitn->GrpHdr->CreDtTm = date('c', strtotime($typeModel->date));
        $xml->CstmrCdtTrfInitn->GrpHdr->NbOfTxs = 1;
        $xml->CstmrCdtTrfInitn->GrpHdr->CtrlSum = $typeModel->debitAmount ?: $typeModel->creditAmount;

        if ($organization->name) {
            $xml->CstmrCdtTrfInitn->GrpHdr->InitgPty->Nm = $organization->name;
        }
        $xml->CstmrCdtTrfInitn->GrpHdr->InitgPty->Id->OrgId->Othr[0]->Id = $organizationTerminal->terminalId;
        $xml->CstmrCdtTrfInitn->GrpHdr->InitgPty->Id->OrgId->Othr[0]->SchmeNm->Prtry = 'CFTBIC';
        $xml->CstmrCdtTrfInitn->GrpHdr->InitgPty->Id->OrgId->Othr[1]->Id = $debitAccount->number;
        $xml->CstmrCdtTrfInitn->GrpHdr->InitgPty->Id->OrgId->Othr[1]->SchmeNm->Cd = 'BBAN';
        if ($organization->inn) {
            $xml->CstmrCdtTrfInitn->GrpHdr->InitgPty->Id->OrgId->Othr[2]->Id = $organization->inn;
            $xml->CstmrCdtTrfInitn->GrpHdr->InitgPty->Id->OrgId->Othr[2]->SchmeNm->Cd = 'TXID';
        }

        $xml->CstmrCdtTrfInitn->GrpHdr->InitgPty->CtctDtls->Nm = $typeModel->contactPersonName;

        // Подготовка номера телефона для представления в нужном формате
        $phone = $typeModel->contactPersonPhone;
        $phone = str_replace(['(', ')'], '', $phone);
        $phone = str_replace(' ', '-', $phone);

        $xml->CstmrCdtTrfInitn->GrpHdr->InitgPty->CtctDtls->PhneNb = $phone;
        $xml->CstmrCdtTrfInitn->GrpHdr->FwdgAgt->FinInstnId->BICFI = Address::truncateAddress($debitAccountBankTerminalId);
        $xml->CstmrCdtTrfInitn->GrpHdr->FwdgAgt->FinInstnId->Nm = $debitAccountBankName;

        $xml->CstmrCdtTrfInitn->PmtInf->PmtInfId = $uuid;
        $xml->CstmrCdtTrfInitn->PmtInf->PmtMtd = 'TRF';
        $xml->CstmrCdtTrfInitn->PmtInf->PmtTpInf->LclInstrm->Prtry = 'FX-FX';
        $xml->CstmrCdtTrfInitn->PmtInf->ReqdExctnDt = date('Y-m-d');

        $xml->CstmrCdtTrfInitn->PmtInf->Dbtr->Id->OrgId->Othr[0]->Id = Address::truncateAddress($organizationTerminal->terminalId);
        $xml->CstmrCdtTrfInitn->PmtInf->Dbtr->Id->OrgId->Othr[0]->SchmeNm->Prtry = 'CFTBIC';

        $remoteId = $organizationTerminal->getRemoteTerminalId($organizationTerminal);

        if ($remoteId) {
            $xml->CstmrCdtTrfInitn->PmtInf->Dbtr->Id->OrgId->Othr[1]->Id = $remoteId;
            $xml->CstmrCdtTrfInitn->PmtInf->Dbtr->Id->OrgId->Othr[1]->SchmeNm->Cd = 'BANK';
        }

        $xml->CstmrCdtTrfInitn->PmtInf->DbtrAcct->Id->Othr->Id = $typeModel->debitAccount;
        $xml->CstmrCdtTrfInitn->PmtInf->DbtrAgt->FinInstnId->BICFI = Address::truncateAddress($debitAccountBankTerminalId);
        $xml->CstmrCdtTrfInitn->PmtInf->ChrgBr = 'DEBT';

        $xml->CstmrCdtTrfInitn->PmtInf->ChrgsAcct->Id->Othr->Id = $typeModel->commissionAccount;
        $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf->PmtId->InstrId = $uuid;
        $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf->PmtId->EndToEndId = 0;
        $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf->PmtTpInf->LclInstrm->Prtry = 'FX';


        if ($typeModel->debitAmount) {
            $amount = $typeModel->debitAmount;
            $currencyName = $debitAccountCurrencyName;
        } else {
            $amount = $typeModel->creditAmount;
            $currencyName = $creditAccountCurrencyName;
        }

        $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf->Amt->InstdAmt = $amount;
        $instdAmt = $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf->Amt->InstdAmt;
        $instdAmt->addAttribute('Ccy', $currencyName);

        $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf->XchgRateInf->RateTp = 'AGRD';

        $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf->CdtrAgt->FinInstnId->BICFI = Address::truncateAddress($creditAccountBankTerminalId);

        $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf->CdtrAcct->Id->Othr->Id = $typeModel->creditAccount;

        return $xml->saveXML();
    }

    public static function loadTypeModelFromZip($typeModel, $zipStr, $isFile = false)
    {
        if ($isFile) {
            $zip = ZipHelper::copyArchiveFileZipFromFile($zipStr);
        } else {
            $zip = ZipHelper::createArchiveFileZipFromString($zipStr);
        }

        $strXml = false;
        $zipFilename = false;
        $files = $zip->getFileList('cp866');
        foreach($files as $index => $fileName) {
            if (mb_substr($fileName, 0, 7) == 'attach_') {
                $zipFilename = mb_substr($fileName, 7);

                continue;
            } else if (mb_substr($fileName, -4) == '.xml') {
                if ($strXml === false) {
                    $strXml = $zip->getFromIndex($index);
                } else {
                    throw new \Exception('Too many xml files in zip archive');
                }
            }
        }

        if ($strXml === false) {
            throw new \Exception('Failed to find xml file in zip archive');
        }

        $strXml = StringHelper::fixXmlHeader($strXml);

        $typeModel->loadFromString($strXml);
        if ($isFile) {
            $typeModel->zipContent = $zip->asString();
        } else {
            $typeModel->zipContent = $zipStr;
        }

        $typeModel->zipFilename = $zipFilename;
        $typeModel->useZipContent = true;

        $zip->purge();
    }

    public static function updateZipContent($typeModel)
    {
        if (!$typeModel->useZipContent) {
            return true;
        }

        /** @var ArchiveFileZip $zip */
        $zip = ZipHelper::createArchiveFileZipFromString($typeModel->zipContent);
        $files = $zip->getFileList('cp866');
        $xmlFileName = '';
        foreach($files as $index => $fileName) {
            if (mb_substr($fileName, 0, 7) != 'attach_' && mb_substr($fileName, -4) == '.xml') {
                $zip->deleteIndex($index);
                $xmlFileName = $fileName;

                break;
            }
        }

        if ($xmlFileName) {
            $zip->addFromString((string) $typeModel, $xmlFileName, 'cp866');
            $typeModel->zipContent = $zip->asString();
        } else {
            throw new Exception('XML filename not found in zipcontent of ' . $typeModel->getType() . ' model');
        }

        $zip->purge();
    }

    public static function attachZipContent($typeModel, $attachedFileList)
    {
        if (empty($attachedFileList)) {
            return;
        }

        // fileName и hash в случае нескольких аттачментов берутся из первого.
        // Это неправильно, но подразумеваем, что в этих случаях модель поддерживает
        // только один аттачмент (множественные сейчас поддерживаются только в auth.024
        // и считаются в ней же)

        $firstFile = $attachedFileList[0];
        if (empty($typeModel->fileNames)) {
            foreach ($attachedFileList as $attachedFile) {
                $typeModel->fileNames[] = $attachedFile->name;
            }
        }

        /**
         * @fixme @todo костыль - для auth024 filehash здесь не присваиваем,
         * т.к. у него нет такой проперти, он там где-то сам считается,
         * нужно все разрулить к единому виду
         */
        switch ($typeModel->getType()) {
            case Auth024Type::TYPE:
                break;
            case Auth026Type::TYPE:
                foreach ($attachedFileList as $attachedFile) {
                    $typeModel->fileHashes[] = XMLSecurityDSig::calculateDigest(
                        XMLSecurityDSig::SHA256, file_get_contents($attachedFile->path)
                    );
                }
                break;
            default:
                $typeModel->fileHash = XMLSecurityDSig::calculateDigest(
                    XMLSecurityDSig::SHA256, file_get_contents($firstFile->path)
                );
                break;
        }

        $zip = ZipHelper::createTempArchiveFileZip();
        if (!$zip) {
            throw new \Exception('Failed to create temp zip archive');
        }

        $typeModel->buildXML();
        $modelName = $typeModel->createFileName();
        $zip->addFromString((string) $typeModel, $modelName . '.xml', 'cp866');

        foreach($attachedFileList as $attachedFile) {
            $zip->addFile($attachedFile->path, $attachedFile->name, 'cp866');
        }

        $typeModel->zipContent = $zip->asString();
        $typeModel->useZipContent = true;
        $typeModel->zipFilename = $modelName . '.zip';

        $zip->purge();
    }

    public static function findSenderTerminalAddress(BaseType $typeModel): ?string
    {
        $senderAddress = Address::fixSender(static::findSender($typeModel));
        if (TerminalId::validate($senderAddress)) {
            return $senderAddress;
        } else {
            Yii::info("Sender address \"$senderAddress\" is not valid");
            return null;
        }
    }

    public static function findReceiverTerminalAddress(BaseType $typeModel): ?string
    {
        $receiverSwiftCode = static::findReceiver($typeModel);
        $receiverAddress = TerminalAddressResolver::resolveReceiver($receiverSwiftCode);

        if (TerminalId::validate($receiverAddress)) {
            return $receiverAddress;
        } else {
            Yii::info("Receiver address \"$receiverAddress\" is not valid, Swift code: \"$receiverSwiftCode\"");
            return null;
        }
    }

    private static function findSender(BaseType $typeModel): ?string
    {
        // CYB-4150
        /** @var \SimpleXMLElement $xml */
        $xml = $typeModel->getRawXml();
        $xpath = "//*[local-name()='InitgPty']/*[local-name()='Pty']/*[local-name()='Id']/*[local-name()='OrgId']";
        $othr = $xml->xpath("$xpath/*[local-name()='Othr']");

        if (!count($othr)) {
            $xpath = "//*[local-name()='InitgPty']/*[local-name()='Id']/*[local-name()='OrgId']";
            $othr = $xml->xpath("$xpath/*[local-name()='Othr']");
        }

        foreach($othr as $node) {
            if ((string) $node->SchmeNm->Prtry == 'CFTBIC') {
                return (string) $node->Id;
            }
        }

        $node = $xml->xpath($xpath);

        if (in_array($typeModel->getFullType(), ['pain.001.001.06', 'auth.026.001.01'])) {
            return (string) $node[0]->AnyBIC;
        }

        return isset($node[0]) ? (string) $node[0]->BICOrBEI : null;
    }

    private static function findReceiver(BaseType $typeModel): ?string
    {
        /** @var \SimpleXMLElement $xml */
        $xml = $typeModel->getRawXml();

        $nodes = $xml->xpath("//*[local-name()='FwdgAgt']/*[local-name()='FinInstnId']");

        if (!empty($nodes) && !empty($nodes[0]->BICFI)) {
            return (string) $nodes[0]->BICFI;
        }

        $othr = $xml->xpath("//*[local-name()='FwdgAgt']/*[local-name()='FinInstnId']/*[local-name()='Othr']");

        foreach($othr as $node) {
            if ((string) $node->SchmeNm->Prtry == 'CFTBIC') {
                return (string) $node->Id;
            }
        }

        $nodes = $xml->xpath("//*[local-name()='DbtrAgt']/*[local-name()='FinInstnId']");
        if (!empty($nodes) && !empty($nodes[0]->BICFI)) {
            return (string) $nodes[0]->BICFI;
        }

        $othr = $xml->xpath("//*[local-name()='DbtrAgt']/*[local-name()='FinInstnId']/*[local-name()='Othr']");

        foreach($othr as $node) {
            if ((string) $node->SchmeNm->Prtry == 'CFTBIC') {
                return (string) $node->Id;
            }
        }

        return null;
    }

    public static function addMissingHeadersToPain001(Pain001Type $typeModel): void
    {
        $xml = $typeModel->getRawXml();
        $payerAccountNumber = (string)@$xml->CstmrCdtTrfInitn->PmtInf->DbtrAcct->Id->Othr->Id;
        $payerAccount = EdmPayerAccount::findOne(['number' => $payerAccountNumber]);
        if (!$payerAccount) {
            return;
        }

        $receiverAddress = $typeModel->receiver ?: $payerAccount->bank->terminalId;
        $receiverParticipant = BICDirParticipant::findOne(['participantBIC' => Address::truncateAddress($receiverAddress)]);

        $typeModel->injectMissingHeaders(
            $payerAccount->edmDictOrganization->name,
            $payerAccount->edmDictOrganization->terminal->terminalId,
            $payerAccount->edmDictOrganization->inn,
            $payerAccount->number,
            $receiverParticipant ? $receiverParticipant->participantBIC : null,
            $receiverParticipant ? $receiverParticipant->name : null,
        );
    }

    private static function cleanUuid($uppercase = true)
    {
        return str_replace('-', '', Uuid::generate($uppercase));
    }

}
