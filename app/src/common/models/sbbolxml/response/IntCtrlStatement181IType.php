<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing IntCtrlStatement181IType
 *
 * Ведомость банковского контроля (ВБК)
 * XSD Type: IntCtrlStatement181I
 */
class IntCtrlStatement181IType
{

    /**
     * Глобальный идентификатор документа ВБК в АС ЕКС
     *
     * @property string $docExtGuid
     */
    private $docExtGuid = null;

    /**
     * Код состояния документа
     *
     * @property string $statusStateCode
     */
    private $statusStateCode = null;

    /**
     * Идентификатор паспорта сделки по контракту в СББОЛ (UUID документа)
     *
     * @property string $docId
     */
    private $docId = null;

    /**
     * Идентификатор организации в СББОЛ
     *
     * @property string $orgId
     */
    private $orgId = null;

    /**
     * Общие реквизиты ВК (паспортов сделок) ДБО
     *
     * @property \common\models\sbbolxml\response\DocDataVBKType $docData
     */
    private $docData = null;

    /**
     * xml-файл ВБК в формате Инструкции
     *
     * @property string $xmlBody
     */
    private $xmlBody = null;

    /**
     * xml-файл ВБК в формате Инструкции
     *
     * @property \common\models\sbbolxml\response\IntCtrlStatementHeaderType $intCtrlStatementHeader
     */
    private $intCtrlStatementHeader = null;

    /**
     * Сведения о резиденте
     *
     * @property \common\models\sbbolxml\response\ResInfoType $resInfo
     */
    private $resInfo = null;

    /**
     * Реквизиты нерезидента (нерезидентов)
     *
     * @property \common\models\sbbolxml\response\BeneficiarInfoISCType[] $beneficiarInfo
     */
    private $beneficiarInfo = null;

    /**
     * Общие сведения о контракте, кредитном договоре
     *
     * @property \common\models\sbbolxml\response\ComDataConCredType $comDataConCred
     */
    private $comDataConCred = null;

    /**
     * Сведения о сумме и сроках привлечения (предоставления) траншей
     *
     * @property \common\models\sbbolxml\response\TrancheOperInfoType[] $trancheInfo
     */
    private $trancheInfo = null;

    /**
     * Сведения о ранее присвоенном контракту, кредитному договору уникальном номере
     *
     * @property string $numPSOtherBank
     */
    private $numPSOtherBank = null;

    /**
     * Специальные сведения о Кредитном договоре
     *
     * @property \common\models\sbbolxml\response\DealPassSpecDataType $specData
     */
    private $specData = null;

    /**
     * Сведения о ранее присвоенном контракту, кредитному договору уникальном номере
     *
     * @property \common\models\sbbolxml\response\SuppleInfoICSType $suppleInfo
     */
    private $suppleInfo = null;

    /**
     * Справочная информация о кредитном договоре
     *
     * @property \common\models\sbbolxml\response\HelpInfoICSType $helpInfo
     */
    private $helpInfo = null;

    /**
     * Сведения о постановке на учет, переводе и снятии с учета контракта,
     *  кредитного договора
     *
     * @property \common\models\sbbolxml\response\DPEndDataType[] $dPEnd
     */
    private $dPEnd = null;

    /**
     * Сведения о переоформлении паспортов сделок
     *
     * @property \common\models\sbbolxml\response\DPReissueDataType[] $dPRe
     */
    private $dPRe = null;

    /**
     * Итоговые данные расчетов по контракту - раздел V ВБК
     *
     * @property \common\models\sbbolxml\response\FinalTransactDataType[] $finalTransact
     */
    private $finalTransact = null;

    /**
     * График платежей по возврату основного долга и процентных платежей - 4- раздел ВБК
     *
     * @property \common\models\sbbolxml\response\FinalDebtPrincipalDataType[] $finalDebtPrincipal
     */
    private $finalDebtPrincipal = null;

    /**
     * Электронная подпись
     *
     * @property \common\models\sbbolxml\response\DigitalSignType $sign
     */
    private $sign = null;

    /**
     * Gets as docExtGuid
     *
     * Глобальный идентификатор документа ВБК в АС ЕКС
     *
     * @return string
     */
    public function getDocExtGuid()
    {
        return $this->docExtGuid;
    }

    /**
     * Sets a new docExtGuid
     *
     * Глобальный идентификатор документа ВБК в АС ЕКС
     *
     * @param string $docExtGuid
     * @return static
     */
    public function setDocExtGuid($docExtGuid)
    {
        $this->docExtGuid = $docExtGuid;
        return $this;
    }

    /**
     * Gets as statusStateCode
     *
     * Код состояния документа
     *
     * @return string
     */
    public function getStatusStateCode()
    {
        return $this->statusStateCode;
    }

    /**
     * Sets a new statusStateCode
     *
     * Код состояния документа
     *
     * @param string $statusStateCode
     * @return static
     */
    public function setStatusStateCode($statusStateCode)
    {
        $this->statusStateCode = $statusStateCode;
        return $this;
    }

    /**
     * Gets as docId
     *
     * Идентификатор паспорта сделки по контракту в СББОЛ (UUID документа)
     *
     * @return string
     */
    public function getDocId()
    {
        return $this->docId;
    }

    /**
     * Sets a new docId
     *
     * Идентификатор паспорта сделки по контракту в СББОЛ (UUID документа)
     *
     * @param string $docId
     * @return static
     */
    public function setDocId($docId)
    {
        $this->docId = $docId;
        return $this;
    }

    /**
     * Gets as orgId
     *
     * Идентификатор организации в СББОЛ
     *
     * @return string
     */
    public function getOrgId()
    {
        return $this->orgId;
    }

    /**
     * Sets a new orgId
     *
     * Идентификатор организации в СББОЛ
     *
     * @param string $orgId
     * @return static
     */
    public function setOrgId($orgId)
    {
        $this->orgId = $orgId;
        return $this;
    }

    /**
     * Gets as docData
     *
     * Общие реквизиты ВК (паспортов сделок) ДБО
     *
     * @return \common\models\sbbolxml\response\DocDataVBKType
     */
    public function getDocData()
    {
        return $this->docData;
    }

    /**
     * Sets a new docData
     *
     * Общие реквизиты ВК (паспортов сделок) ДБО
     *
     * @param \common\models\sbbolxml\response\DocDataVBKType $docData
     * @return static
     */
    public function setDocData(\common\models\sbbolxml\response\DocDataVBKType $docData)
    {
        $this->docData = $docData;
        return $this;
    }

    /**
     * Gets as xmlBody
     *
     * xml-файл ВБК в формате Инструкции
     *
     * @return string
     */
    public function getXmlBody()
    {
        return $this->xmlBody;
    }

    /**
     * Sets a new xmlBody
     *
     * xml-файл ВБК в формате Инструкции
     *
     * @param string $xmlBody
     * @return static
     */
    public function setXmlBody($xmlBody)
    {
        $this->xmlBody = $xmlBody;
        return $this;
    }

    /**
     * Gets as intCtrlStatementHeader
     *
     * xml-файл ВБК в формате Инструкции
     *
     * @return \common\models\sbbolxml\response\IntCtrlStatementHeaderType
     */
    public function getIntCtrlStatementHeader()
    {
        return $this->intCtrlStatementHeader;
    }

    /**
     * Sets a new intCtrlStatementHeader
     *
     * xml-файл ВБК в формате Инструкции
     *
     * @param \common\models\sbbolxml\response\IntCtrlStatementHeaderType $intCtrlStatementHeader
     * @return static
     */
    public function setIntCtrlStatementHeader(\common\models\sbbolxml\response\IntCtrlStatementHeaderType $intCtrlStatementHeader)
    {
        $this->intCtrlStatementHeader = $intCtrlStatementHeader;
        return $this;
    }

    /**
     * Gets as resInfo
     *
     * Сведения о резиденте
     *
     * @return \common\models\sbbolxml\response\ResInfoType
     */
    public function getResInfo()
    {
        return $this->resInfo;
    }

    /**
     * Sets a new resInfo
     *
     * Сведения о резиденте
     *
     * @param \common\models\sbbolxml\response\ResInfoType $resInfo
     * @return static
     */
    public function setResInfo(\common\models\sbbolxml\response\ResInfoType $resInfo)
    {
        $this->resInfo = $resInfo;
        return $this;
    }

    /**
     * Adds as beneficiar
     *
     * Реквизиты нерезидента (нерезидентов)
     *
     * @return static
     * @param \common\models\sbbolxml\response\BeneficiarInfoISCType $beneficiar
     */
    public function addToBeneficiarInfo(\common\models\sbbolxml\response\BeneficiarInfoISCType $beneficiar)
    {
        $this->beneficiarInfo[] = $beneficiar;
        return $this;
    }

    /**
     * isset beneficiarInfo
     *
     * Реквизиты нерезидента (нерезидентов)
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetBeneficiarInfo($index)
    {
        return isset($this->beneficiarInfo[$index]);
    }

    /**
     * unset beneficiarInfo
     *
     * Реквизиты нерезидента (нерезидентов)
     *
     * @param scalar $index
     * @return void
     */
    public function unsetBeneficiarInfo($index)
    {
        unset($this->beneficiarInfo[$index]);
    }

    /**
     * Gets as beneficiarInfo
     *
     * Реквизиты нерезидента (нерезидентов)
     *
     * @return \common\models\sbbolxml\response\BeneficiarInfoISCType[]
     */
    public function getBeneficiarInfo()
    {
        return $this->beneficiarInfo;
    }

    /**
     * Sets a new beneficiarInfo
     *
     * Реквизиты нерезидента (нерезидентов)
     *
     * @param \common\models\sbbolxml\response\BeneficiarInfoISCType[] $beneficiarInfo
     * @return static
     */
    public function setBeneficiarInfo(array $beneficiarInfo)
    {
        $this->beneficiarInfo = $beneficiarInfo;
        return $this;
    }

    /**
     * Gets as comDataConCred
     *
     * Общие сведения о контракте, кредитном договоре
     *
     * @return \common\models\sbbolxml\response\ComDataConCredType
     */
    public function getComDataConCred()
    {
        return $this->comDataConCred;
    }

    /**
     * Sets a new comDataConCred
     *
     * Общие сведения о контракте, кредитном договоре
     *
     * @param \common\models\sbbolxml\response\ComDataConCredType $comDataConCred
     * @return static
     */
    public function setComDataConCred(\common\models\sbbolxml\response\ComDataConCredType $comDataConCred)
    {
        $this->comDataConCred = $comDataConCred;
        return $this;
    }

    /**
     * Adds as oper
     *
     * Сведения о сумме и сроках привлечения (предоставления) траншей
     *
     * @return static
     * @param \common\models\sbbolxml\response\TrancheOperInfoType $oper
     */
    public function addToTrancheInfo(\common\models\sbbolxml\response\TrancheOperInfoType $oper)
    {
        $this->trancheInfo[] = $oper;
        return $this;
    }

    /**
     * isset trancheInfo
     *
     * Сведения о сумме и сроках привлечения (предоставления) траншей
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetTrancheInfo($index)
    {
        return isset($this->trancheInfo[$index]);
    }

    /**
     * unset trancheInfo
     *
     * Сведения о сумме и сроках привлечения (предоставления) траншей
     *
     * @param scalar $index
     * @return void
     */
    public function unsetTrancheInfo($index)
    {
        unset($this->trancheInfo[$index]);
    }

    /**
     * Gets as trancheInfo
     *
     * Сведения о сумме и сроках привлечения (предоставления) траншей
     *
     * @return \common\models\sbbolxml\response\TrancheOperInfoType[]
     */
    public function getTrancheInfo()
    {
        return $this->trancheInfo;
    }

    /**
     * Sets a new trancheInfo
     *
     * Сведения о сумме и сроках привлечения (предоставления) траншей
     *
     * @param \common\models\sbbolxml\response\TrancheOperInfoType[] $trancheInfo
     * @return static
     */
    public function setTrancheInfo(array $trancheInfo)
    {
        $this->trancheInfo = $trancheInfo;
        return $this;
    }

    /**
     * Gets as numPSOtherBank
     *
     * Сведения о ранее присвоенном контракту, кредитному договору уникальном номере
     *
     * @return string
     */
    public function getNumPSOtherBank()
    {
        return $this->numPSOtherBank;
    }

    /**
     * Sets a new numPSOtherBank
     *
     * Сведения о ранее присвоенном контракту, кредитному договору уникальном номере
     *
     * @param string $numPSOtherBank
     * @return static
     */
    public function setNumPSOtherBank($numPSOtherBank)
    {
        $this->numPSOtherBank = $numPSOtherBank;
        return $this;
    }

    /**
     * Gets as specData
     *
     * Специальные сведения о Кредитном договоре
     *
     * @return \common\models\sbbolxml\response\DealPassSpecDataType
     */
    public function getSpecData()
    {
        return $this->specData;
    }

    /**
     * Sets a new specData
     *
     * Специальные сведения о Кредитном договоре
     *
     * @param \common\models\sbbolxml\response\DealPassSpecDataType $specData
     * @return static
     */
    public function setSpecData(\common\models\sbbolxml\response\DealPassSpecDataType $specData)
    {
        $this->specData = $specData;
        return $this;
    }

    /**
     * Gets as suppleInfo
     *
     * Сведения о ранее присвоенном контракту, кредитному договору уникальном номере
     *
     * @return \common\models\sbbolxml\response\SuppleInfoICSType
     */
    public function getSuppleInfo()
    {
        return $this->suppleInfo;
    }

    /**
     * Sets a new suppleInfo
     *
     * Сведения о ранее присвоенном контракту, кредитному договору уникальном номере
     *
     * @param \common\models\sbbolxml\response\SuppleInfoICSType $suppleInfo
     * @return static
     */
    public function setSuppleInfo(\common\models\sbbolxml\response\SuppleInfoICSType $suppleInfo)
    {
        $this->suppleInfo = $suppleInfo;
        return $this;
    }

    /**
     * Gets as helpInfo
     *
     * Справочная информация о кредитном договоре
     *
     * @return \common\models\sbbolxml\response\HelpInfoICSType
     */
    public function getHelpInfo()
    {
        return $this->helpInfo;
    }

    /**
     * Sets a new helpInfo
     *
     * Справочная информация о кредитном договоре
     *
     * @param \common\models\sbbolxml\response\HelpInfoICSType $helpInfo
     * @return static
     */
    public function setHelpInfo(\common\models\sbbolxml\response\HelpInfoICSType $helpInfo)
    {
        $this->helpInfo = $helpInfo;
        return $this;
    }

    /**
     * Adds as dPEndData
     *
     * Сведения о постановке на учет, переводе и снятии с учета контракта,
     *  кредитного договора
     *
     * @return static
     * @param \common\models\sbbolxml\response\DPEndDataType $dPEndData
     */
    public function addToDPEnd(\common\models\sbbolxml\response\DPEndDataType $dPEndData)
    {
        $this->dPEnd[] = $dPEndData;
        return $this;
    }

    /**
     * isset dPEnd
     *
     * Сведения о постановке на учет, переводе и снятии с учета контракта,
     *  кредитного договора
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetDPEnd($index)
    {
        return isset($this->dPEnd[$index]);
    }

    /**
     * unset dPEnd
     *
     * Сведения о постановке на учет, переводе и снятии с учета контракта,
     *  кредитного договора
     *
     * @param scalar $index
     * @return void
     */
    public function unsetDPEnd($index)
    {
        unset($this->dPEnd[$index]);
    }

    /**
     * Gets as dPEnd
     *
     * Сведения о постановке на учет, переводе и снятии с учета контракта,
     *  кредитного договора
     *
     * @return \common\models\sbbolxml\response\DPEndDataType[]
     */
    public function getDPEnd()
    {
        return $this->dPEnd;
    }

    /**
     * Sets a new dPEnd
     *
     * Сведения о постановке на учет, переводе и снятии с учета контракта,
     *  кредитного договора
     *
     * @param \common\models\sbbolxml\response\DPEndDataType[] $dPEnd
     * @return static
     */
    public function setDPEnd(array $dPEnd)
    {
        $this->dPEnd = $dPEnd;
        return $this;
    }

    /**
     * Adds as dPReissueData
     *
     * Сведения о переоформлении паспортов сделок
     *
     * @return static
     * @param \common\models\sbbolxml\response\DPReissueDataType $dPReissueData
     */
    public function addToDPRe(\common\models\sbbolxml\response\DPReissueDataType $dPReissueData)
    {
        $this->dPRe[] = $dPReissueData;
        return $this;
    }

    /**
     * isset dPRe
     *
     * Сведения о переоформлении паспортов сделок
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetDPRe($index)
    {
        return isset($this->dPRe[$index]);
    }

    /**
     * unset dPRe
     *
     * Сведения о переоформлении паспортов сделок
     *
     * @param scalar $index
     * @return void
     */
    public function unsetDPRe($index)
    {
        unset($this->dPRe[$index]);
    }

    /**
     * Gets as dPRe
     *
     * Сведения о переоформлении паспортов сделок
     *
     * @return \common\models\sbbolxml\response\DPReissueDataType[]
     */
    public function getDPRe()
    {
        return $this->dPRe;
    }

    /**
     * Sets a new dPRe
     *
     * Сведения о переоформлении паспортов сделок
     *
     * @param \common\models\sbbolxml\response\DPReissueDataType[] $dPRe
     * @return static
     */
    public function setDPRe(array $dPRe)
    {
        $this->dPRe = $dPRe;
        return $this;
    }

    /**
     * Adds as finalTransactData
     *
     * Итоговые данные расчетов по контракту - раздел V ВБК
     *
     * @return static
     * @param \common\models\sbbolxml\response\FinalTransactDataType $finalTransactData
     */
    public function addToFinalTransact(\common\models\sbbolxml\response\FinalTransactDataType $finalTransactData)
    {
        $this->finalTransact[] = $finalTransactData;
        return $this;
    }

    /**
     * isset finalTransact
     *
     * Итоговые данные расчетов по контракту - раздел V ВБК
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetFinalTransact($index)
    {
        return isset($this->finalTransact[$index]);
    }

    /**
     * unset finalTransact
     *
     * Итоговые данные расчетов по контракту - раздел V ВБК
     *
     * @param scalar $index
     * @return void
     */
    public function unsetFinalTransact($index)
    {
        unset($this->finalTransact[$index]);
    }

    /**
     * Gets as finalTransact
     *
     * Итоговые данные расчетов по контракту - раздел V ВБК
     *
     * @return \common\models\sbbolxml\response\FinalTransactDataType[]
     */
    public function getFinalTransact()
    {
        return $this->finalTransact;
    }

    /**
     * Sets a new finalTransact
     *
     * Итоговые данные расчетов по контракту - раздел V ВБК
     *
     * @param \common\models\sbbolxml\response\FinalTransactDataType[] $finalTransact
     * @return static
     */
    public function setFinalTransact(array $finalTransact)
    {
        $this->finalTransact = $finalTransact;
        return $this;
    }

    /**
     * Adds as finalDebtPrincipalData
     *
     * График платежей по возврату основного долга и процентных платежей - 4- раздел ВБК
     *
     * @return static
     * @param \common\models\sbbolxml\response\FinalDebtPrincipalDataType $finalDebtPrincipalData
     */
    public function addToFinalDebtPrincipal(\common\models\sbbolxml\response\FinalDebtPrincipalDataType $finalDebtPrincipalData)
    {
        $this->finalDebtPrincipal[] = $finalDebtPrincipalData;
        return $this;
    }

    /**
     * isset finalDebtPrincipal
     *
     * График платежей по возврату основного долга и процентных платежей - 4- раздел ВБК
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetFinalDebtPrincipal($index)
    {
        return isset($this->finalDebtPrincipal[$index]);
    }

    /**
     * unset finalDebtPrincipal
     *
     * График платежей по возврату основного долга и процентных платежей - 4- раздел ВБК
     *
     * @param scalar $index
     * @return void
     */
    public function unsetFinalDebtPrincipal($index)
    {
        unset($this->finalDebtPrincipal[$index]);
    }

    /**
     * Gets as finalDebtPrincipal
     *
     * График платежей по возврату основного долга и процентных платежей - 4- раздел ВБК
     *
     * @return \common\models\sbbolxml\response\FinalDebtPrincipalDataType[]
     */
    public function getFinalDebtPrincipal()
    {
        return $this->finalDebtPrincipal;
    }

    /**
     * Sets a new finalDebtPrincipal
     *
     * График платежей по возврату основного долга и процентных платежей - 4- раздел ВБК
     *
     * @param \common\models\sbbolxml\response\FinalDebtPrincipalDataType[] $finalDebtPrincipal
     * @return static
     */
    public function setFinalDebtPrincipal(array $finalDebtPrincipal)
    {
        $this->finalDebtPrincipal = $finalDebtPrincipal;
        return $this;
    }

    /**
     * Gets as sign
     *
     * Электронная подпись
     *
     * @return \common\models\sbbolxml\response\DigitalSignType
     */
    public function getSign()
    {
        return $this->sign;
    }

    /**
     * Sets a new sign
     *
     * Электронная подпись
     *
     * @param \common\models\sbbolxml\response\DigitalSignType $sign
     * @return static
     */
    public function setSign(\common\models\sbbolxml\response\DigitalSignType $sign)
    {
        $this->sign = $sign;
        return $this;
    }


}

