<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing TermBorrowType
 *
 * Код срока привлечения (предоставления) транша
 * XSD Type: TermBorrow
 */
class TermBorrowType
{

    /**
     * Код срока привлечения (предоставления) транша
     *
     * @property string $code
     */
    private $code = null;

    /**
     * Описание срока привлечения (предоставления) транша
     *
     * @property string $description
     */
    private $description = null;

    /**
     * Gets as code
     *
     * Код срока привлечения (предоставления) транша
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Sets a new code
     *
     * Код срока привлечения (предоставления) транша
     *
     * @param string $code
     * @return static
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * Gets as description
     *
     * Описание срока привлечения (предоставления) транша
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Sets a new description
     *
     * Описание срока привлечения (предоставления) транша
     *
     * @param string $description
     * @return static
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }


}

