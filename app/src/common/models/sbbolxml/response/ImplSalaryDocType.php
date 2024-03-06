<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing ImplSalaryDocType
 *
 *
 * XSD Type: ImplSalaryDoc
 */
class ImplSalaryDocType
{

    /**
     * @property \common\models\sbbolxml\response\TransfInfoAccType[] $transfInfos
     */
    private $transfInfos = null;

    /**
     * Информирование о реквизитах зачисления, указанных в расчетном документе
     *
     * @property \common\models\sbbolxml\response\PayDocRuInfoTicketType[] $payDocRuInfo
     */
    private $payDocRuInfo = null;

    /**
     * Доп. статус СБК (поле РЦК)
     *
     * @property string $rzkStatus
     */
    private $rzkStatus = null;

    /**
     * Последнее действие с документом в СБК (поле РЦК)
     *
     * @property string $rzkAction
     */
    private $rzkAction = null;

    /**
     * Adds as transfInfo
     *
     * @return static
     * @param \common\models\sbbolxml\response\TransfInfoAccType $transfInfo
     */
    public function addToTransfInfos(\common\models\sbbolxml\response\TransfInfoAccType $transfInfo)
    {
        $this->transfInfos[] = $transfInfo;
        return $this;
    }

    /**
     * isset transfInfos
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetTransfInfos($index)
    {
        return isset($this->transfInfos[$index]);
    }

    /**
     * unset transfInfos
     *
     * @param scalar $index
     * @return void
     */
    public function unsetTransfInfos($index)
    {
        unset($this->transfInfos[$index]);
    }

    /**
     * Gets as transfInfos
     *
     * @return \common\models\sbbolxml\response\TransfInfoAccType[]
     */
    public function getTransfInfos()
    {
        return $this->transfInfos;
    }

    /**
     * Sets a new transfInfos
     *
     * @param \common\models\sbbolxml\response\TransfInfoAccType[] $transfInfos
     * @return static
     */
    public function setTransfInfos(array $transfInfos)
    {
        $this->transfInfos = $transfInfos;
        return $this;
    }

    /**
     * Adds as payDocRu
     *
     * Информирование о реквизитах зачисления, указанных в расчетном документе
     *
     * @return static
     * @param \common\models\sbbolxml\response\PayDocRuInfoTicketType $payDocRu
     */
    public function addToPayDocRuInfo(\common\models\sbbolxml\response\PayDocRuInfoTicketType $payDocRu)
    {
        $this->payDocRuInfo[] = $payDocRu;
        return $this;
    }

    /**
     * isset payDocRuInfo
     *
     * Информирование о реквизитах зачисления, указанных в расчетном документе
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetPayDocRuInfo($index)
    {
        return isset($this->payDocRuInfo[$index]);
    }

    /**
     * unset payDocRuInfo
     *
     * Информирование о реквизитах зачисления, указанных в расчетном документе
     *
     * @param scalar $index
     * @return void
     */
    public function unsetPayDocRuInfo($index)
    {
        unset($this->payDocRuInfo[$index]);
    }

    /**
     * Gets as payDocRuInfo
     *
     * Информирование о реквизитах зачисления, указанных в расчетном документе
     *
     * @return \common\models\sbbolxml\response\PayDocRuInfoTicketType[]
     */
    public function getPayDocRuInfo()
    {
        return $this->payDocRuInfo;
    }

    /**
     * Sets a new payDocRuInfo
     *
     * Информирование о реквизитах зачисления, указанных в расчетном документе
     *
     * @param \common\models\sbbolxml\response\PayDocRuInfoTicketType[] $payDocRuInfo
     * @return static
     */
    public function setPayDocRuInfo(array $payDocRuInfo)
    {
        $this->payDocRuInfo = $payDocRuInfo;
        return $this;
    }

    /**
     * Gets as rzkStatus
     *
     * Доп. статус СБК (поле РЦК)
     *
     * @return string
     */
    public function getRzkStatus()
    {
        return $this->rzkStatus;
    }

    /**
     * Sets a new rzkStatus
     *
     * Доп. статус СБК (поле РЦК)
     *
     * @param string $rzkStatus
     * @return static
     */
    public function setRzkStatus($rzkStatus)
    {
        $this->rzkStatus = $rzkStatus;
        return $this;
    }

    /**
     * Gets as rzkAction
     *
     * Последнее действие с документом в СБК (поле РЦК)
     *
     * @return string
     */
    public function getRzkAction()
    {
        return $this->rzkAction;
    }

    /**
     * Sets a new rzkAction
     *
     * Последнее действие с документом в СБК (поле РЦК)
     *
     * @param string $rzkAction
     * @return static
     */
    public function setRzkAction($rzkAction)
    {
        $this->rzkAction = $rzkAction;
        return $this;
    }


}

