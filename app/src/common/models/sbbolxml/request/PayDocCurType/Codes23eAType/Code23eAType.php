<?php

namespace common\models\sbbolxml\request\PayDocCurType\Codes23eAType;

/**
 * Class representing Code23eAType
 */
class Code23eAType
{

    /**
     * Описание
     *
     * @property string $instr23EInfo
     */
    private $instr23EInfo = null;

    /**
     * Код инструкции (состоит из 4х лат букв, например, TELE)
     *
     * @property string $code
     */
    private $code = null;

    /**
     * Дополнительная информация
     *
     * @property string $addInfo
     */
    private $addInfo = null;

    /**
     * Gets as instr23EInfo
     *
     * Описание
     *
     * @return string
     */
    public function getInstr23EInfo()
    {
        return $this->instr23EInfo;
    }

    /**
     * Sets a new instr23EInfo
     *
     * Описание
     *
     * @param string $instr23EInfo
     * @return static
     */
    public function setInstr23EInfo($instr23EInfo)
    {
        $this->instr23EInfo = $instr23EInfo;
        return $this;
    }

    /**
     * Gets as code
     *
     * Код инструкции (состоит из 4х лат букв, например, TELE)
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
     * Код инструкции (состоит из 4х лат букв, например, TELE)
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
     * Gets as addInfo
     *
     * Дополнительная информация
     *
     * @return string
     */
    public function getAddInfo()
    {
        return $this->addInfo;
    }

    /**
     * Sets a new addInfo
     *
     * Дополнительная информация
     *
     * @param string $addInfo
     * @return static
     */
    public function setAddInfo($addInfo)
    {
        $this->addInfo = $addInfo;
        return $this;
    }


}

