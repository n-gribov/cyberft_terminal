<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing OrgManagementType
 *
 * Орган управления организации в соответствии с учредительными документами
 *  Значение Расшифровка
 *  1 Общее собрание акционеров (участников)
 *  2 Совет директоров
 *  3 Единоличный исполнительный орган (президент, директор, генеральный директор и др.)
 *  4 Коллегиальный орган
 *  (Правление, дирекция и др.)
 * XSD Type: OrgManagement
 */
class OrgManagementType
{

    /**
     * Общее собрание акционеров
     *
     * @property \common\models\sbbolxml\request\AnnualGeneralMeetingType $annualGeneralMeeting
     */
    private $annualGeneralMeeting = null;

    /**
     * Совет директоров
     *
     * @property \common\models\sbbolxml\request\BoardOfDirectorsType $boardOfDirectors
     */
    private $boardOfDirectors = null;

    /**
     * Единоличный исполнительный орган
     *
     * @property \common\models\sbbolxml\request\SoleExecutiveBodyType $soleExecutiveBody
     */
    private $soleExecutiveBody = null;

    /**
     * Коллегиальный орган
     *
     * @property \common\models\sbbolxml\request\CollectiveBodyType $collectiveBody
     */
    private $collectiveBody = null;

    /**
     * Gets as annualGeneralMeeting
     *
     * Общее собрание акционеров
     *
     * @return \common\models\sbbolxml\request\AnnualGeneralMeetingType
     */
    public function getAnnualGeneralMeeting()
    {
        return $this->annualGeneralMeeting;
    }

    /**
     * Sets a new annualGeneralMeeting
     *
     * Общее собрание акционеров
     *
     * @param \common\models\sbbolxml\request\AnnualGeneralMeetingType $annualGeneralMeeting
     * @return static
     */
    public function setAnnualGeneralMeeting(\common\models\sbbolxml\request\AnnualGeneralMeetingType $annualGeneralMeeting)
    {
        $this->annualGeneralMeeting = $annualGeneralMeeting;
        return $this;
    }

    /**
     * Gets as boardOfDirectors
     *
     * Совет директоров
     *
     * @return \common\models\sbbolxml\request\BoardOfDirectorsType
     */
    public function getBoardOfDirectors()
    {
        return $this->boardOfDirectors;
    }

    /**
     * Sets a new boardOfDirectors
     *
     * Совет директоров
     *
     * @param \common\models\sbbolxml\request\BoardOfDirectorsType $boardOfDirectors
     * @return static
     */
    public function setBoardOfDirectors(\common\models\sbbolxml\request\BoardOfDirectorsType $boardOfDirectors)
    {
        $this->boardOfDirectors = $boardOfDirectors;
        return $this;
    }

    /**
     * Gets as soleExecutiveBody
     *
     * Единоличный исполнительный орган
     *
     * @return \common\models\sbbolxml\request\SoleExecutiveBodyType
     */
    public function getSoleExecutiveBody()
    {
        return $this->soleExecutiveBody;
    }

    /**
     * Sets a new soleExecutiveBody
     *
     * Единоличный исполнительный орган
     *
     * @param \common\models\sbbolxml\request\SoleExecutiveBodyType $soleExecutiveBody
     * @return static
     */
    public function setSoleExecutiveBody(\common\models\sbbolxml\request\SoleExecutiveBodyType $soleExecutiveBody)
    {
        $this->soleExecutiveBody = $soleExecutiveBody;
        return $this;
    }

    /**
     * Gets as collectiveBody
     *
     * Коллегиальный орган
     *
     * @return \common\models\sbbolxml\request\CollectiveBodyType
     */
    public function getCollectiveBody()
    {
        return $this->collectiveBody;
    }

    /**
     * Sets a new collectiveBody
     *
     * Коллегиальный орган
     *
     * @param \common\models\sbbolxml\request\CollectiveBodyType $collectiveBody
     * @return static
     */
    public function setCollectiveBody(\common\models\sbbolxml\request\CollectiveBodyType $collectiveBody)
    {
        $this->collectiveBody = $collectiveBody;
        return $this;
    }


}

