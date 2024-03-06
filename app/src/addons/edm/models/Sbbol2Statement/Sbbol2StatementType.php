<?php
namespace addons\edm\models\Sbbol2Statement;

use addons\edm\models\Statement\StatementType;

class Sbbol2StatementType extends StatementType
{
    const TYPE = 'Sbbol2Statement';

    protected function getDefaultMapper()
    {
        return new StatementMapperSbbol2();
    }

}
