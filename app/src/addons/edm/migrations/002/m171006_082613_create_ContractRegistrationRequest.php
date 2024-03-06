<?php

use yii\db\Migration;

/**
 * Документ "Паспорт сделки"
 * @task CYB-3857
 */
class m171006_082613_create_ContractRegistrationRequest extends Migration
{
    public function up()
    {
        $this->createTable('contractRegistrationRequest', [
            'id' => $this->primaryKey(),
            'number' => $this->string(),
            'date' => $this->string(),
            'passportNumber' => $this->string(),
            'passportType' => $this->string(10),
            'organizationId' => $this->integer(),
            'amount' => $this->float(2),
            'currencyId' => $this->integer(),
            'signingDate' => $this->string(),
            'completionDate' => $this->string(),
            'existedPassport' => $this->string(),
            'passportTypeNumber' => $this->string(),
            'terminalId' => $this->integer(),
            'documentId' => $this->integer(),
            'ogrn' => $this->string(13),
            'inn' => $this->string(12),
            'kpp' => $this->string(9),
            'dateEgrul' => $this->string(),
            'state' => $this->string(),
            'city' => $this->string(),
            'street' => $this->string(),
            'building' => $this->string(),
            'district' => $this->string(),
            'locality' => $this->string(),
            'buildingNumber' => $this->string(),
            'apartment' => $this->string(),
            'creditedAccountsAbroad' => $this->string(),
            'repaymentForeignCurrencyEarnings' => $this->string(),
            'codeTermInvolvement' => $this->integer(1),
            'fixedRate' => $this->float(2),
            'codeLibor' => $this->string(5),
            'otherMethodsDeterminingRate' => $this->text(),
            'bonusBaseRate' => $this->float(2),
            'otherPaymentsLoanAgreement' => $this->text(),
            'amountMainDebt' => $this->float(2),
            'contractCurrencyId' => $this->integer(),
            'reasonFillPaymentsSchedule' => $this->string(),
            'directInvestment' => $this->boolean(),
            'amountCollateral' => $this->float(2),
            'contractTypeCode' => $this->integer(),
        ]);
    }

    public function down()
    {
        $this->dropTable('contractRegistrationRequest');
        return true;
    }
}
