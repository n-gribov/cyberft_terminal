<div class="counters-today-block">
    <div class="panel panel-default">
        <div class="panel-heading clearfix">
            <div class="pull-left">
                <?= Yii::t('app/profile', 'Current Status') ?>
            </div>
        </div>
        <div class="panel-body main-panel-body">
            <?php
                if (isset($todayBlocks['edmToday'])) {
                    // Вывод информации по показателями
                    // банковского обслуживания за текущий день
                    echo $this->render('_edmToday', ['edmToday' => $todayBlocks['edmToday']]);
                }

                if (isset($todayBlocks['swiftfinToday'])) {
                    // Swiftfin сегодня
                    echo $this->render('_swiftfinToday', ['swiftDocs' => $todayBlocks['swiftfinToday']]);
                }

                if (isset($todayBlocks['isoToday'])) {
                    // ISO20022 сегодня
                    echo $this->render('_isoToday', ['isoDocs' => $todayBlocks['isoToday']]);
                }

                if (isset($todayBlocks['fileactToday'])) {
                    // Fileact сегодня
                    echo $this->render('_fileactToday', ['fileactDocs' => $todayBlocks['fileactToday']]);
                }

                if (isset($todayBlocks['finzipToday'])) {
                    // Fileact сегодня
                    echo $this->render('_finzipToday', ['finzipDocs' => $todayBlocks['finzipToday']]);
                }

                if (isset($todayBlocks['errorsToday'])) {
                    // Ошибки
                    echo $this->render('_errorsToday', ['errors' => $todayBlocks['errorsToday']]);
                }
            ?>
        </div>
    </div>
</div>