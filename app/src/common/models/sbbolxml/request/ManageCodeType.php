<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing ManageCodeType
 *
 *
 * XSD Type: ManageCode
 */
class ManageCodeType
{

    /**
     * Код ошибки безопасности: 0-нет удаленного управления, 1-обнаружено удаленное
     *  управление
     *
     * @property integer $code
     */
    private $code = null;

    /**
     * Параметр для дополнительной информации по ошибке безопасности. В случае наличия
     *  удаленного управления
     *  заполняется строкой с IP и всеми остальными данными, которые удастся собрать о компьютере, с
     *  которого ведется удаленное
     *  управление.
     *
     * @property string $param
     */
    private $param = null;

    /**
     * Gets as code
     *
     * Код ошибки безопасности: 0-нет удаленного управления, 1-обнаружено удаленное
     *  управление
     *
     * @return integer
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Sets a new code
     *
     * Код ошибки безопасности: 0-нет удаленного управления, 1-обнаружено удаленное
     *  управление
     *
     * @param integer $code
     * @return static
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * Gets as param
     *
     * Параметр для дополнительной информации по ошибке безопасности. В случае наличия
     *  удаленного управления
     *  заполняется строкой с IP и всеми остальными данными, которые удастся собрать о компьютере, с
     *  которого ведется удаленное
     *  управление.
     *
     * @return string
     */
    public function getParam()
    {
        return $this->param;
    }

    /**
     * Sets a new param
     *
     * Параметр для дополнительной информации по ошибке безопасности. В случае наличия
     *  удаленного управления
     *  заполняется строкой с IP и всеми остальными данными, которые удастся собрать о компьютере, с
     *  которого ведется удаленное
     *  управление.
     *
     * @param string $param
     * @return static
     */
    public function setParam($param)
    {
        $this->param = $param;
        return $this;
    }


}

