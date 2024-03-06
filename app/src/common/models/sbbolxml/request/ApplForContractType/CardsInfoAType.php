<?php

namespace common\models\sbbolxml\request\ApplForContractType;

/**
 * Class representing CardsInfoAType
 */
class CardsInfoAType
{

    /**
     * Количество банковских карт с типом Visa Electron / Maestro
     *
     * @property \common\models\sbbolxml\request\ApplForContractType\CardsInfoAType\VisaElectronMaestroAType $visaElectronMaestro
     */
    private $visaElectronMaestro = null;

    /**
     * Количество банковских карт с типом Visa Classic / MasterCard Mass
     *
     * @property \common\models\sbbolxml\request\ApplForContractType\CardsInfoAType\VisaClassicMasterCardMassAType $visaClassicMasterCardMass
     */
    private $visaClassicMasterCardMass = null;

    /**
     * Количество банковских карт с типом Visa Gold / MasterCard Gold (для
     *  отдельных сотрудников предприятия)
     *
     * @property \common\models\sbbolxml\request\ApplForContractType\CardsInfoAType\VisaGoldMasterCardGoldAType $visaGoldMasterCardGold
     */
    private $visaGoldMasterCardGold = null;

    /**
     * Количество счетов по вкладам
     *
     * @property \common\models\sbbolxml\request\ApplForContractType\CardsInfoAType\DepositsAType $deposits
     */
    private $deposits = null;

    /**
     * Количество банковских карт с типом Сберкарт
     *
     * @property \common\models\sbbolxml\request\ApplForContractType\CardsInfoAType\SbercardAType $sbercard
     */
    private $sbercard = null;

    /**
     * Gets as visaElectronMaestro
     *
     * Количество банковских карт с типом Visa Electron / Maestro
     *
     * @return \common\models\sbbolxml\request\ApplForContractType\CardsInfoAType\VisaElectronMaestroAType
     */
    public function getVisaElectronMaestro()
    {
        return $this->visaElectronMaestro;
    }

    /**
     * Sets a new visaElectronMaestro
     *
     * Количество банковских карт с типом Visa Electron / Maestro
     *
     * @param \common\models\sbbolxml\request\ApplForContractType\CardsInfoAType\VisaElectronMaestroAType $visaElectronMaestro
     * @return static
     */
    public function setVisaElectronMaestro(\common\models\sbbolxml\request\ApplForContractType\CardsInfoAType\VisaElectronMaestroAType $visaElectronMaestro)
    {
        $this->visaElectronMaestro = $visaElectronMaestro;
        return $this;
    }

    /**
     * Gets as visaClassicMasterCardMass
     *
     * Количество банковских карт с типом Visa Classic / MasterCard Mass
     *
     * @return \common\models\sbbolxml\request\ApplForContractType\CardsInfoAType\VisaClassicMasterCardMassAType
     */
    public function getVisaClassicMasterCardMass()
    {
        return $this->visaClassicMasterCardMass;
    }

    /**
     * Sets a new visaClassicMasterCardMass
     *
     * Количество банковских карт с типом Visa Classic / MasterCard Mass
     *
     * @param \common\models\sbbolxml\request\ApplForContractType\CardsInfoAType\VisaClassicMasterCardMassAType $visaClassicMasterCardMass
     * @return static
     */
    public function setVisaClassicMasterCardMass(\common\models\sbbolxml\request\ApplForContractType\CardsInfoAType\VisaClassicMasterCardMassAType $visaClassicMasterCardMass)
    {
        $this->visaClassicMasterCardMass = $visaClassicMasterCardMass;
        return $this;
    }

    /**
     * Gets as visaGoldMasterCardGold
     *
     * Количество банковских карт с типом Visa Gold / MasterCard Gold (для
     *  отдельных сотрудников предприятия)
     *
     * @return \common\models\sbbolxml\request\ApplForContractType\CardsInfoAType\VisaGoldMasterCardGoldAType
     */
    public function getVisaGoldMasterCardGold()
    {
        return $this->visaGoldMasterCardGold;
    }

    /**
     * Sets a new visaGoldMasterCardGold
     *
     * Количество банковских карт с типом Visa Gold / MasterCard Gold (для
     *  отдельных сотрудников предприятия)
     *
     * @param \common\models\sbbolxml\request\ApplForContractType\CardsInfoAType\VisaGoldMasterCardGoldAType $visaGoldMasterCardGold
     * @return static
     */
    public function setVisaGoldMasterCardGold(\common\models\sbbolxml\request\ApplForContractType\CardsInfoAType\VisaGoldMasterCardGoldAType $visaGoldMasterCardGold)
    {
        $this->visaGoldMasterCardGold = $visaGoldMasterCardGold;
        return $this;
    }

    /**
     * Gets as deposits
     *
     * Количество счетов по вкладам
     *
     * @return \common\models\sbbolxml\request\ApplForContractType\CardsInfoAType\DepositsAType
     */
    public function getDeposits()
    {
        return $this->deposits;
    }

    /**
     * Sets a new deposits
     *
     * Количество счетов по вкладам
     *
     * @param \common\models\sbbolxml\request\ApplForContractType\CardsInfoAType\DepositsAType $deposits
     * @return static
     */
    public function setDeposits(\common\models\sbbolxml\request\ApplForContractType\CardsInfoAType\DepositsAType $deposits)
    {
        $this->deposits = $deposits;
        return $this;
    }

    /**
     * Gets as sbercard
     *
     * Количество банковских карт с типом Сберкарт
     *
     * @return \common\models\sbbolxml\request\ApplForContractType\CardsInfoAType\SbercardAType
     */
    public function getSbercard()
    {
        return $this->sbercard;
    }

    /**
     * Sets a new sbercard
     *
     * Количество банковских карт с типом Сберкарт
     *
     * @param \common\models\sbbolxml\request\ApplForContractType\CardsInfoAType\SbercardAType $sbercard
     * @return static
     */
    public function setSbercard(\common\models\sbbolxml\request\ApplForContractType\CardsInfoAType\SbercardAType $sbercard)
    {
        $this->sbercard = $sbercard;
        return $this;
    }


}

