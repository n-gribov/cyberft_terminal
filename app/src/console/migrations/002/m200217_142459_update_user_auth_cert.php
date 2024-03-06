<?php

use yii\db\Migration;

class m200217_142459_update_user_auth_cert extends Migration
{
    private $tableName = '{{%user_auth_cert}}';

    public function safeUp()
    {
        $this->delete($this->tableName, ['not', ['status' => 1]]);

        $this->dropColumn($this->tableName, 'status');
        $this->dropColumn($this->tableName, 'name');
        $this->addColumn($this->tableName, 'expiryDate', $this->date());

        $this->fillExpiryDate();
    }

    public function safeDown()
    {
        $this->addColumn($this->tableName, 'status', $this->smallInteger(1)->defaultValue(0));
        $this->addColumn($this->tableName, 'name', $this->string(64));
        $this->dropColumn($this->tableName, 'expiryDate');

        $this->update($this->tableName, ['status' => 1]);
    }

    private function fillExpiryDate(): void
    {
        $certs = (new \yii\db\Query())
            ->select(['id', 'certificate'])
            ->from($this->tableName)
            ->all();

        foreach ($certs as $cert) {
            $this->update(
                $this->tableName,
                ['expiryDate' => $this->getExpiryDate($cert['certificate'])],
                ['id' => $cert['id']]
            );
        }
    }

    private function getExpiryDate(string $certBody): ?string
    {
        try {
            $x509 = \common\modules\certManager\components\ssl\X509FileModel::loadData($certBody);
            $expiryDate = $x509->getValidTo();
            return $expiryDate ? $expiryDate->format('Y-m-d') : null;
        } catch (\Exception $exception) {
            echo "Failed to get expiry date, caused by $exception\n";
        }
        return null;
    }
}
