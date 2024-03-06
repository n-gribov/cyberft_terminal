<?php

namespace common\models\raiffeisenxml\response\ResponseType;

/**
 * Class representing StatementsRaifAType
 */
class StatementsRaifAType
{

    /**
     * Райффайзен. Выписки.
     *
     * @property \common\models\raiffeisenxml\response\StatementTypeRaifType[] $statementRaif
     */
    private $statementRaif = [
        
    ];

    /**
     * Adds as statementRaif
     *
     * Райффайзен. Выписки.
     *
     * @return static
     * @param \common\models\raiffeisenxml\response\StatementTypeRaifType $statementRaif
     */
    public function addToStatementRaif(\common\models\raiffeisenxml\response\StatementTypeRaifType $statementRaif)
    {
        $this->statementRaif[] = $statementRaif;
        return $this;
    }

    /**
     * isset statementRaif
     *
     * Райффайзен. Выписки.
     *
     * @param int|string $index
     * @return bool
     */
    public function issetStatementRaif($index)
    {
        return isset($this->statementRaif[$index]);
    }

    /**
     * unset statementRaif
     *
     * Райффайзен. Выписки.
     *
     * @param int|string $index
     * @return void
     */
    public function unsetStatementRaif($index)
    {
        unset($this->statementRaif[$index]);
    }

    /**
     * Gets as statementRaif
     *
     * Райффайзен. Выписки.
     *
     * @return \common\models\raiffeisenxml\response\StatementTypeRaifType[]
     */
    public function getStatementRaif()
    {
        return $this->statementRaif;
    }

    /**
     * Sets a new statementRaif
     *
     * Райффайзен. Выписки.
     *
     * @param \common\models\raiffeisenxml\response\StatementTypeRaifType[] $statementRaif
     * @return static
     */
    public function setStatementRaif(array $statementRaif)
    {
        $this->statementRaif = $statementRaif;
        return $this;
    }


}

