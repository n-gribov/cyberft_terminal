<?php

namespace common\models\raiffeisenxml\response\ResponseType;

/**
 * Class representing StatementsAType
 */
class StatementsAType
{

    /**
     * Информация о движении ден. средств
     *
     * @property \common\models\raiffeisenxml\response\StatementType[] $statement
     */
    private $statement = [
        
    ];

    /**
     * Adds as statement
     *
     * Информация о движении ден. средств
     *
     * @return static
     * @param \common\models\raiffeisenxml\response\StatementType $statement
     */
    public function addToStatement(\common\models\raiffeisenxml\response\StatementType $statement)
    {
        $this->statement[] = $statement;
        return $this;
    }

    /**
     * isset statement
     *
     * Информация о движении ден. средств
     *
     * @param int|string $index
     * @return bool
     */
    public function issetStatement($index)
    {
        return isset($this->statement[$index]);
    }

    /**
     * unset statement
     *
     * Информация о движении ден. средств
     *
     * @param int|string $index
     * @return void
     */
    public function unsetStatement($index)
    {
        unset($this->statement[$index]);
    }

    /**
     * Gets as statement
     *
     * Информация о движении ден. средств
     *
     * @return \common\models\raiffeisenxml\response\StatementType[]
     */
    public function getStatement()
    {
        return $this->statement;
    }

    /**
     * Sets a new statement
     *
     * Информация о движении ден. средств
     *
     * @param \common\models\raiffeisenxml\response\StatementType[] $statement
     * @return static
     */
    public function setStatement(array $statement)
    {
        $this->statement = $statement;
        return $this;
    }


}

