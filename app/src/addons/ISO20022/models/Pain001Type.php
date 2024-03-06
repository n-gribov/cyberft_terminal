<?php

namespace addons\ISO20022\models;

use addons\ISO20022\models\traits\WithSignature;
use common\base\interfaces\SignableType;
use common\helpers\SimpleXMLHelper;
use common\helpers\StringHelper;
use yii\helpers\ArrayHelper;

class Pain001Type extends ISO20022Type implements SignableType
{
    use WithSignature;

    const TYPE = 'pain.001';

    private $_count = false;
    private $_currency = false;
    private $_sum = false;

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(),
            [
                [array_values($this->attributes()), 'safe'],
            ]);
    }

    public function getPaymentRegisterInfo()
    {
        return [
            'sum' => $this->sum,
            'count' => $this->count,
            'currency' => $this->currency,
        ];
    }

    public function injectMissingDebtorAttributes($name, $address)
    {
        $xml = $this->getRawXml();

        $dbtrNode = $xml->CstmrCdtTrfInitn->PmtInf->Dbtr;

        if (!$dbtrNode->Nm) {
            $nmNode = new \SimpleXMLElement('<Nm/>');
            $nmNode[0] = $name;
            SimpleXMLHelper::prependChild($nmNode, $dbtrNode);
        }

        if (!$dbtrNode->PstlAdr) {
            SimpleXMLHelper::insertAfterTags(new \SimpleXMLElement('<PstlAdr/>'), $dbtrNode, ['Nm']);
        }
        if (!$dbtrNode->PstlAdr->Ctry) {
            $ctryNode = new \SimpleXMLElement('<Ctry/>');
            $ctryNode[0] = 'RU';
            SimpleXMLHelper::prependChild($ctryNode, $dbtrNode->PstlAdr);
        }
        if (!$dbtrNode->PstlAdr->AdrLine) {
            $addressLines = StringHelper::mb_wordwrap($address, 70);
            foreach ($addressLines as $lineIdx => $line) {
                $dbtrNode->PstlAdr->AdrLine[$lineIdx] = $line;
            }
        }
    }

    public function getCount()
    {
        if ($this->_count !== false) {
            return $this->_count;
        }

        if (empty($this->_xml)) {
            return false;
        }

        $nbOfTxs = $this->_xml->CstmrCdtTrfInitn->GrpHdr->NbOfTxs;
        if (!empty($nbOfTxs)) {
            $this->_count = (int) $nbOfTxs;
        } else {
            $this->registerNameSpaces();
            $nodes = $this->_xml->xpath('//a:CdtTrfTxInf');
            $this->_count = count($nodes);
        }

        return $this->_count;
    }

    public function getSum()
    {
        if ($this->_sum !== false) {
            return $this->_sum;
        }

        if (empty($this->_xml)) {
            return false;
        }

        $ctrlSum = $this->_xml->CstmrCdtTrfInitn->GrpHdr->CtrlSum;
        if (!empty($ctrlSum)) {
            $this->_sum = (float) $ctrlSum;
        } else {
            $this->_sum = 0.0;
            $this->registerNameSpaces();
            $nodes = $this->_xml->xpath('//a:CdtTrfTxInf');
            foreach($nodes as $node) {
                $this->_sum += (float) $node->Amt->InstdAmt;
            }
        }

        return $this->_sum;
    }

    public function getCurrency()
    {
        if ($this->_currency !== false) {
            return $this->_currency;
        }

        if (empty($this->_xml)) {
            return false;
        }

        $this->registerNameSpaces();
        $nodes = $this->_xml->xpath('//a:CdtTrfTxInf');

        if (count($nodes)) {
            $this->_currency = (string) $nodes[0]->Amt->InstdAmt['Ccy'];
        }

        return $this->_currency;
    }

    public function setSum($value)
    {
        $this->_sum = $value;
    }

    public function setCount($value)
    {
        $this->_count = $value;
    }

    public function setCurrency($value)
    {
        $this->_currency = $value;
    }

    private function registerNameSpaces()
    {
        foreach($this->_xml->getDocNamespaces() as $strPrefix => $strNamespace) {
            if (empty($strPrefix)) {
                $strPrefix = 'a'; // Assign an arbitrary namespace prefix.
            }
            $this->_xml->registerXPathNamespace($strPrefix, $strNamespace);
        }
    }

    public function validate($attributeNames = null, $clearErrors = true)
    {
        if (!parent::validate($attributeNames, $clearErrors)) {
            return false;
        }

        $this->registerNameSpaces();
        $nodes = $this->_xml->xpath('//a:CdtTrfTxInf');

        $nbOfTxs = $this->_xml->CstmrCdtTrfInitn->GrpHdr->NbOfTxs;
        if (!empty($nbOfTxs)) {
            // Значение узла nbOfTxs должно совпадать с фактическим счетчиком
            $cnt = count($nodes);
            if ($cnt != (int) $nbOfTxs) {
                $this->addError('nbOfTxs', 'Значение узла nbOfTxs ' . $nbOfTxs . ' не совпадает с фактическим счетчиком ' . $cnt);
            }
        }

        $sum = 0.0;
        if ($this->count) {
            $currencies = [];
            foreach($nodes as $node) {
                $sum += (float) $node->Amt->InstdAmt;
                $currencies[(string) $node->Amt->InstdAmt['Ccy']] = true;
            }
            // Валюта во всех узлах должна быть одна
            if (count($currencies) != 1) {
                $this->addError('currency', 'Валюта во всех узлах должна быть одна');
            }
        }

        $ctrlSum = $this->_xml->CstmrCdtTrfInitn->GrpHdr->CtrlSum;
        if (!empty($ctrlSum)) {
            // Вычисленная сумма должна совпадать с контрольной
            if ((string) $sum !== (string) $ctrlSum) {
                $this->addError('ctrlSum', 'Вычисленная сумма ' . $sum . ' не совпадает с контрольной суммой ' . $ctrlSum);
            }

        }

        return !$this->hasErrors();
    }

    public function getPainDocumentType()
    {
        if (isset($this->_xml->CstmrCdtTrfInitn->PmtInf->PmtTpInf->LclInstrm)) {
            return (string) $this->_xml->CstmrCdtTrfInitn->PmtInf->PmtTpInf->LclInstrm->Prtry;
        } else {
            return null;
        }
    }

    public function searchReceiver()
    {
        $xml = $this->_xml;

        $nodes = $xml->xpath('//a:FwdgAgt/a:FinInstnId');

        if (!empty($nodes) && !empty($nodes[0]->BICFI)) {
            return (string) $nodes[0]->BICFI;
        }

        $othr = $xml->xpath('//a:FwdgAgt/a:FinInstnId/a:Othr');

        foreach($othr as $node) {
            if ((string) $node->SchmeNm->Prtry == 'CFTBIC') {
                return (string) $node->Id;
            }
        }

        $nodes = $xml->xpath('//a:DbtrAgt/a:FinInstnId');
        if (!empty($nodes) && !empty($nodes[0]->BICFI)) {
            return (string) $nodes[0]->BICFI;
        }

        $othr = $xml->xpath('//a:DbtrAgt/a:FinInstnId/a:Othr');

        foreach($othr as $node) {
            if ((string) $node->SchmeNm->Prtry == 'CFTBIC') {
                return (string) $node->Id;
            }
        }

        return null;
    }

    public function getPayerName(): ?string
    {
        return $this->getValueByXpath('//a:Dbtr/a:Nm');
    }

    public function getPayerBank(): ?string
    {
        return $this->getValueByXpath('//a:DbtrAgt/a:FinInstnId/a:Nm');
    }

    public function getDebitAccountCurrency(): ?string
    {
        return $this->getValueByXpath('/a:Document/a:CstmrCdtTrfInitn/a:PmtInf/a:DbtrAcct/a:Ccy');
    }

    public function getDebitAccountNumber(): ?string
    {
        return $this->getValueByXpath('/a:Document/a:CstmrCdtTrfInitn/a:PmtInf/a:DbtrAcct/a:Id/a:Othr/a:Id')
            ?: $this->getValueByXpath("/a:Document/a:CstmrCdtTrfInitn/a:PmtInf/a:Dbtr/a:Id/a:OrgId/a:Othr[a:SchmeNm/a:Cd/text()='BBAN']/a:Id");
    }

    public function getCreditAccountCurrency(): ?string
    {
        return $this->getValueByXpath('/a:Document/a:CstmrCdtTrfInitn/a:PmtInf/a:CdtTrfTxInf/a:CdtrAcct/a:Ccy');
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

    public function injectMissingHeaders($senderName, $senderCftbic, $senderTaxId, $senderAccount, $receiverBicfi, $receiverName): void
    {
        $xml = $this->getRawXml();

        $grpHdr = $xml->CstmrCdtTrfInitn->GrpHdr;
        $initgPty = $grpHdr->InitgPty;
        if (!$initgPty) {
            $initgPty = SimpleXMLHelper::insertAfterTags(
                new \SimpleXMLElement('<InitgPty/>'),
                $grpHdr,
                ['MsgId', 'CreDtTm', 'Authstn', 'NbOfTxs', 'CtrlSum']
            );
        }
        if (!$initgPty->Nm) {
            $nm = new \SimpleXMLElement('<Nm/>');
            $nm[0] = $senderName;
            SimpleXMLHelper::prependChild($nm, $initgPty);
        }

        if (!$initgPty->Id) {
            SimpleXMLHelper::insertAfterTags(new \SimpleXMLElement('<Id/>'), $initgPty, ['Nm', 'PstlAdr']);
        }
        if (!$initgPty->Id->OrgId) {
            SimpleXMLHelper::prependChild(new \SimpleXMLElement('<OrgId/>'), $initgPty->Id);
        }
        $orgId = $initgPty->Id->OrgId;

        foreach ($xml->getDocNamespaces() as $strPrefix => $strNamespace) {
            if (empty($strPrefix)) {
                $strPrefix = 'a'; //Assign an arbitrary namespace prefix
            }
            $orgId->registerXPathNamespace($strPrefix, $strNamespace);
        }

        $addMissingId = function ($schemeType, $scheme, $value) use ($orgId) {
            $idPath = "./a:Othr[a:SchmeNm/a:$schemeType/text()='$scheme']/a:Id";
            if ($orgId->xpath($idPath)) {
                return;
            }
            $newElementIndex = count($orgId->Othr);
            $orgId->Othr[$newElementIndex]->Id = $value;
            $orgId->Othr[$newElementIndex]->SchmeNm->$schemeType = $scheme;
        };

        $addMissingId('Prtry', 'CFTBIC', $senderCftbic);
        $addMissingId('Cd', 'BBAN', $senderAccount);
        if ($senderTaxId) {
            $addMissingId('Cd', 'TXID', $senderTaxId);
        }

        if ($receiverBicfi && $receiverName) {
            if (!$grpHdr->FwdgAgt) {
                $grpHdr->addChild('FwdgAgt');
            }
            if (!$grpHdr->FwdgAgt->FinInstnId) {
                SimpleXMLHelper::prependChild(new \SimpleXMLElement('<FinInstnId/>'), $grpHdr->FwdgAgt);
            }
            if (!$grpHdr->FwdgAgt->FinInstnId->BICFI) {
                SimpleXMLHelper::prependChild(new \SimpleXMLElement('<BICFI/>'), $grpHdr->FwdgAgt->FinInstnId);
                $grpHdr->FwdgAgt->FinInstnId->BICFI = $receiverBicfi;
            }
            if (!$grpHdr->FwdgAgt->FinInstnId->Nm) {
                SimpleXMLHelper::insertAfterTags(new \SimpleXMLElement('<Nm/>'), $grpHdr->FwdgAgt->FinInstnId, ['BICFI', 'ClrSysMmbId']);
                $grpHdr->FwdgAgt->FinInstnId->Nm = $receiverName;
            }
        }
    }
}