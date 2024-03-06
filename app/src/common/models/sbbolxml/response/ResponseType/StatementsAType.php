<?php

namespace common\models\sbbolxml\response\ResponseType;

/**
 * Class representing StatementsAType
 */
class StatementsAType
{

    /**
     * Информация о движении ден. средств
     *
     * @property \common\models\sbbolxml\response\StatementType[] $statement
     */
    private $statement = array(
        
    );

    /**
     * Приложения к выписке
     *
     * @property \common\models\sbbolxml\response\StatementAttachmentType[] $statementAttachment
     */
    private $statementAttachment = array(
        
    );

    /**
     * Ошибка формирования выписки по счету
     *
     * @property \common\models\sbbolxml\response\ErrorStatementType[] $error
     */
    private $error = array(
        
    );

    /**
     * Adds as statement
     *
     * Информация о движении ден. средств
     *
     * @return static
     * @param \common\models\sbbolxml\response\StatementType $statement
     */
    public function addToStatement(\common\models\sbbolxml\response\StatementType $statement)
    {
        $this->statement[] = $statement;
        return $this;
    }

    /**
     * isset statement
     *
     * Информация о движении ден. средств
     *
     * @param scalar $index
     * @return boolean
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
     * @param scalar $index
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
     * @return \common\models\sbbolxml\response\StatementType[]
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
     * @param \common\models\sbbolxml\response\StatementType[] $statement
     * @return static
     */
    public function setStatement(array $statement)
    {
        $this->statement = $statement;
        return $this;
    }

    /**
     * Adds as statementAttachment
     *
     * Приложения к выписке
     *
     * @return static
     * @param \common\models\sbbolxml\response\StatementAttachmentType $statementAttachment
     */
    public function addToStatementAttachment(\common\models\sbbolxml\response\StatementAttachmentType $statementAttachment)
    {
        $this->statementAttachment[] = $statementAttachment;
        return $this;
    }

    /**
     * isset statementAttachment
     *
     * Приложения к выписке
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetStatementAttachment($index)
    {
        return isset($this->statementAttachment[$index]);
    }

    /**
     * unset statementAttachment
     *
     * Приложения к выписке
     *
     * @param scalar $index
     * @return void
     */
    public function unsetStatementAttachment($index)
    {
        unset($this->statementAttachment[$index]);
    }

    /**
     * Gets as statementAttachment
     *
     * Приложения к выписке
     *
     * @return \common\models\sbbolxml\response\StatementAttachmentType[]
     */
    public function getStatementAttachment()
    {
        return $this->statementAttachment;
    }

    /**
     * Sets a new statementAttachment
     *
     * Приложения к выписке
     *
     * @param \common\models\sbbolxml\response\StatementAttachmentType[] $statementAttachment
     * @return static
     */
    public function setStatementAttachment(array $statementAttachment)
    {
        $this->statementAttachment = $statementAttachment;
        return $this;
    }

    /**
     * Adds as error
     *
     * Ошибка формирования выписки по счету
     *
     * @return static
     * @param \common\models\sbbolxml\response\ErrorStatementType $error
     */
    public function addToError(\common\models\sbbolxml\response\ErrorStatementType $error)
    {
        $this->error[] = $error;
        return $this;
    }

    /**
     * isset error
     *
     * Ошибка формирования выписки по счету
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetError($index)
    {
        return isset($this->error[$index]);
    }

    /**
     * unset error
     *
     * Ошибка формирования выписки по счету
     *
     * @param scalar $index
     * @return void
     */
    public function unsetError($index)
    {
        unset($this->error[$index]);
    }

    /**
     * Gets as error
     *
     * Ошибка формирования выписки по счету
     *
     * @return \common\models\sbbolxml\response\ErrorStatementType[]
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Sets a new error
     *
     * Ошибка формирования выписки по счету
     *
     * @param \common\models\sbbolxml\response\ErrorStatementType[] $error
     * @return static
     */
    public function setError(array $error)
    {
        $this->error = $error;
        return $this;
    }


}

