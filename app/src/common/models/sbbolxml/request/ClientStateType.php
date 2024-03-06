<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing ClientStateType
 *
 *
 * XSD Type: ClientState
 */
class ClientStateType
{

    /**
     * @property \common\models\sbbolxml\request\ManageCodeType $manageCode
     */
    private $manageCode = null;

    /**
     * Gets as manageCode
     *
     * @return \common\models\sbbolxml\request\ManageCodeType
     */
    public function getManageCode()
    {
        return $this->manageCode;
    }

    /**
     * Sets a new manageCode
     *
     * @param \common\models\sbbolxml\request\ManageCodeType $manageCode
     * @return static
     */
    public function setManageCode(\common\models\sbbolxml\request\ManageCodeType $manageCode)
    {
        $this->manageCode = $manageCode;
        return $this;
    }


}

