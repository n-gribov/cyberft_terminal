<?php

namespace common\models\raiffeisenxml\response\ResponseType;

/**
 * Class representing CryptoprosAType
 */
class CryptoprosAType
{

    /**
     * Криптопрофиль
     *
     * @property \common\models\raiffeisenxml\response\CryptoproType[] $cryptopro
     */
    private $cryptopro = [
        
    ];

    /**
     * Adds as cryptopro
     *
     * Криптопрофиль
     *
     * @return static
     * @param \common\models\raiffeisenxml\response\CryptoproType $cryptopro
     */
    public function addToCryptopro(\common\models\raiffeisenxml\response\CryptoproType $cryptopro)
    {
        $this->cryptopro[] = $cryptopro;
        return $this;
    }

    /**
     * isset cryptopro
     *
     * Криптопрофиль
     *
     * @param int|string $index
     * @return bool
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
     * @param int|string $index
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
     * @return \common\models\raiffeisenxml\response\CryptoproType[]
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
     * @param \common\models\raiffeisenxml\response\CryptoproType[] $cryptopro
     * @return static
     */
    public function setCryptopro(array $cryptopro)
    {
        $this->cryptopro = $cryptopro;
        return $this;
    }


}

