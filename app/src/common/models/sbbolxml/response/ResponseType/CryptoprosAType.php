<?php

namespace common\models\sbbolxml\response\ResponseType;

/**
 * Class representing CryptoprosAType
 */
class CryptoprosAType
{

    /**
     * Криптопрофиль
     *
     * @property \common\models\sbbolxml\response\CryptoproType[] $cryptopro
     */
    private $cryptopro = array(
        
    );

    /**
     * Adds as cryptopro
     *
     * Криптопрофиль
     *
     * @return static
     * @param \common\models\sbbolxml\response\CryptoproType $cryptopro
     */
    public function addToCryptopro(\common\models\sbbolxml\response\CryptoproType $cryptopro)
    {
        $this->cryptopro[] = $cryptopro;
        return $this;
    }

    /**
     * isset cryptopro
     *
     * Криптопрофиль
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetCryptopro($index)
    {
        return isset($this->cryptopro[$index]);
    }

    /**
     * unset cryptopro
     *
     * Криптопрофиль
     *
     * @param scalar $index
     * @return void
     */
    public function unsetCryptopro($index)
    {
        unset($this->cryptopro[$index]);
    }

    /**
     * Gets as cryptopro
     *
     * Криптопрофиль
     *
     * @return \common\models\sbbolxml\response\CryptoproType[]
     */
    public function getCryptopro()
    {
        return $this->cryptopro;
    }

    /**
     * Sets a new cryptopro
     *
     * Криптопрофиль
     *
     * @param \common\models\sbbolxml\response\CryptoproType[] $cryptopro
     * @return static
     */
    public function setCryptopro(array $cryptopro)
    {
        $this->cryptopro = $cryptopro;
        return $this;
    }


}

