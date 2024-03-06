<?php

namespace common\models\sbbolxml\request\RequestType\CryptoIncomingAType;

/**
 * Class representing ContractAccessCodesAType
 */
class ContractAccessCodesAType
{

    /**
     * ТОЛЬКО ДЛЯ ТК. Идентификатор, который используется для
     *  получения персональных данных.
     *  (DBOContractChannel.accessCode)
     *
     * @property string[] $contractAccessCode
     */
    private $contractAccessCode = array(
        
    );

    /**
     * Adds as contractAccessCode
     *
     * ТОЛЬКО ДЛЯ ТК. Идентификатор, который используется для
     *  получения персональных данных.
     *  (DBOContractChannel.accessCode)
     *
     * @return static
     * @param string $contractAccessCode
     */
    public function addToContractAccessCode($contractAccessCode)
    {
        $this->contractAccessCode[] = $contractAccessCode;
        return $this;
    }

    /**
     * isset contractAccessCode
     *
     * ТОЛЬКО ДЛЯ ТК. Идентификатор, который используется для
     *  получения персональных данных.
     *  (DBOContractChannel.accessCode)
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetContractAccessCode($index)
    {
        return isset($this->contractAccessCode[$index]);
    }

    /**
     * unset contractAccessCode
     *
     * ТОЛЬКО ДЛЯ ТК. Идентификатор, который используется для
     *  получения персональных данных.
     *  (DBOContractChannel.accessCode)
     *
     * @param scalar $index
     * @return void
     */
    public function unsetContractAccessCode($index)
    {
        unset($this->contractAccessCode[$index]);
    }

    /**
     * Gets as contractAccessCode
     *
     * ТОЛЬКО ДЛЯ ТК. Идентификатор, который используется для
     *  получения персональных данных.
     *  (DBOContractChannel.accessCode)
     *
     * @return string[]
     */
    public function getContractAccessCode()
    {
        return $this->contractAccessCode;
    }

    /**
     * Sets a new contractAccessCode
     *
     * ТОЛЬКО ДЛЯ ТК. Идентификатор, который используется для
     *  получения персональных данных.
     *  (DBOContractChannel.accessCode)
     *
     * @param string[] $contractAccessCode
     * @return static
     */
    public function setContractAccessCode(array $contractAccessCode)
    {
        $this->contractAccessCode = $contractAccessCode;
        return $this;
    }


}

