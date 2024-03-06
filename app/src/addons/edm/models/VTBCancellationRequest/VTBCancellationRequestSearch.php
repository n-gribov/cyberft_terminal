<?php
namespace addons\edm\models\VTBCancellationRequest;

use common\base\traits\ForSigningCountable;
use common\document\DocumentSearch;
use Yii;
use yii\db\ActiveQuery;

class VTBCancellationRequestSearch extends DocumentSearch
{
    use ForSigningCountable;

    /**
     * @param ActiveQuery $query
     * @return void
     */

    public $cancelDocumentNum;
    public $cancelDocumentType;
    public $cancelDocumentDate;

    public function rules() {
        return array_merge(
            parent::rules(),
            [
                [['cancelDocumentNum', 'cancelDocumentType', 'cancelDocumentDate'], 'safe'],
            ]
        );

    }

    public function attributeLabels() {
        return array_merge(
            parent::attributeLabels(),
            [
                'cancelDocumentNum' => Yii::t('edm', 'Called-off document number'),
                'cancelDocumentType' => Yii::t('edm', 'Called-off document type'),
                'cancelDocumentDate' => Yii::t('edm', 'Called-off document date'),
            ]
        );
    }

    public function applyExtFilters($query)
    {
        $query
            ->leftJoin(VTBCancellationRequestExt::tableName() . ' vtbCancel', 'vtbCancel.documentId = document.id');

        $this->_select[] = 'vtbCancel.cancelDocumentNum as cancelDocumentNum';
        $this->_select[] = 'vtbCancel.cancelDocumentType as cancelDocumentType';
        $this->_select[] = 'vtbCancel.cancelDocumentDate as cancelDocumentDate';

        $query->andWhere(['document.type' => VTBCancellationRequestType::TYPE]);


        $query->andFilterWhere(['like', 'vtbCancel.cancelDocumentNum', $this->cancelDocumentNum]);
        $query->andFilterWhere(['like', 'vtbCancel.cancelDocumentType', $this->cancelDocumentType]);
        $query->andFilterWhere(['vtbCancel.cancelDocumentDate' => $this->cancelDocumentDate]);
    }

    protected function applySignaturePermissionFilter(ActiveQuery $query): void
    {
        Yii::$app->edmAccountAccess->queryBankTerminalsHavingSignableAccounts($query, 'document.receiver');
    }

}
