<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing SuppleInfoType
 *
 * 7. Справочная информация
 * XSD Type: SuppleInfo
 */
class SuppleInfoType
{

    /**
     * Способ передачи резидентом документов для оформления (переоформления, принятия на обслуживание и
     *  закрытия) ПС.
     *  1 – на бумажном носителе
     *  2 – в электронном виде
     *
     * @property string $provideMethod
     */
    private $provideMethod = null;

    /**
     * Gets as provideMethod
     *
     * Способ передачи резидентом документов для оформления (переоформления, принятия на обслуживание и
     *  закрытия) ПС.
     *  1 – на бумажном носителе
     *  2 – в электронном виде
     *
     * @return string
     */
    public function getProvideMethod()
    {
        return $this->provideMethod;
    }

    /**
     * Sets a new provideMethod
     *
     * Способ передачи резидентом документов для оформления (переоформления, принятия на обслуживание и
     *  закрытия) ПС.
     *  1 – на бумажном носителе
     *  2 – в электронном виде
     *
     * @param string $provideMethod
     * @return static
     */
    public function setProvideMethod($provideMethod)
    {
        $this->provideMethod = $provideMethod;
        return $this;
    }


}

