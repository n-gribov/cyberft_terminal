<div id="view-table-record-modal" class="fade modal" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><?= Yii::t('edm', 'View record') ?></h4>
            </div>
            <div class="modal-body">
                <?= // Вывести блок детализации документа
                    $this->render('_bsDocumentDetails', compact('document', 'bsDocument')) ?>
            </div>
            <div class="modal-footer">
                <div>
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        <?= Yii::t('app', 'Close') ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
