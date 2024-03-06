<?php

namespace common\models\sbbolxml\response\TicketType\InfoAType\AddInfoAType;

/**
 * Class representing BigFilesStatusesAType
 */
class BigFilesStatusesAType
{

    /**
     * Ответ со статусом загружаемых/загруженных реестров
     *
     * @property \common\models\sbbolxml\response\BigFilesStatusType[] $bigFilesStatus
     */
    private $bigFilesStatus = array(
        
    );

    /**
     * Adds as bigFilesStatus
     *
     * Ответ со статусом загружаемых/загруженных реестров
     *
     * @return static
     * @param \common\models\sbbolxml\response\BigFilesStatusType $bigFilesStatus
     */
    public function addToBigFilesStatus(\common\models\sbbolxml\response\BigFilesStatusType $bigFilesStatus)
    {
        $this->bigFilesStatus[] = $bigFilesStatus;
        return $this;
    }

    /**
     * isset bigFilesStatus
     *
     * Ответ со статусом загружаемых/загруженных реестров
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetBigFilesStatus($index)
    {
        return isset($this->bigFilesStatus[$index]);
    }

    /**
     * unset bigFilesStatus
     *
     * Ответ со статусом загружаемых/загруженных реестров
     *
     * @param scalar $index
     * @return void
     */
    public function unsetBigFilesStatus($index)
    {
        unset($this->bigFilesStatus[$index]);
    }

    /**
     * Gets as bigFilesStatus
     *
     * Ответ со статусом загружаемых/загруженных реестров
     *
     * @return \common\models\sbbolxml\response\BigFilesStatusType[]
     */
    public function getBigFilesStatus()
    {
        return $this->bigFilesStatus;
    }

    /**
     * Sets a new bigFilesStatus
     *
     * Ответ со статусом загружаемых/загруженных реестров
     *
     * @param \common\models\sbbolxml\response\BigFilesStatusType[] $bigFilesStatus
     * @return static
     */
    public function setBigFilesStatus(array $bigFilesStatus)
    {
        $this->bigFilesStatus = $bigFilesStatus;
        return $this;
    }


}

