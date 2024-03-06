<?php

namespace addons\SBBOL\console;

use addons\SBBOL\helpers\SBBOLModuleHelper;
use addons\SBBOL\models\SBBOLCustomer;

class StatementController extends BaseController
{
    /**
     * Метод выводит текст с подсказкой
     */
    public function actionIndex()
    {
        $this->run('/help', ['SBBOL/statement']);
    }

    public function actionSendRequest($customerId, $startDate = 'yesterday', $endDate = null)
    {
        $customer = SBBOLCustomer::findOne($customerId);
        if ($customer === null) {
            echo "Customer $customerId is not found\n";
            return;
        }

        if (empty($endDate)) {
            $endDate = $startDate;
        }

        list($isSent, $errorMessage) = SBBOLModuleHelper::sendStatementRequestToSBBOL(
            $customer->accounts,
            new \DateTime($startDate),
            new \DateTime($endDate)
        );

        echo $isSent
            ? "Statement request has been sent\n"
            : "Failed to send statement request, error: {$errorMessage}\n";
    }
}
