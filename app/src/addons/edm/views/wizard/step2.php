<?php

use addons\edm\models\Document;
use common\helpers\Html;
use addons\edm\models\PaymentRegister\PaymentRegisterPaymentOrderTemplate;
use common\widgets\InlineHelp\InlineHelp;

/* @var $model Document */
/* @var $this yii\web\View */

// Получаем список всех шаблонов платежных поручений с учетом доступа к терминалам
$query = Yii::$app->terminalAccess->query(PaymentRegisterPaymentOrderTemplate::className());
$query->orderBy('date', 'DESC');
$query->limit(10);
$templates = $query->all();

// Общее количество шаблонов
$count = count($templates);
?>
<div class="row">
	<div style="min-width: 700px">
        <div class="col-md-12" style="margin-bottom: 15px;">
            <?= // Вывести кнопку возврата на предыдущую страницу
                Html::a(
                    Yii::t('app', 'Back'),
                    Yii::$app->request->referrer,
                    [
                        'class' => 'btn btn-default',
                        'style' => 'margin-right: 10px'
                    ]
                ) ?>
            <?= // Вывести кнопку для загрузки платежного поручения из файла
                $this->render('_uploadTemplateForm') ?>
            <div class="dropdown edm-templates-dropdown">
                <?php
                    // Кнопка для вызова списка шаблонов
                    echo Html::a(
                        Yii::t('edm', 'New from templates ({count})', ['count' => $count]), '#',
                        [
                            'class' => 'templates-link btn btn-primary dropdown-toggle',
                            'data' => [
                                'toggle' => 'dropdown'
                            ]
                        ]
                    );
                ?>
                <ul class="dropdown-menu dropdown-templates-list">
                    <?php foreach($templates as $template) {?>
                        <li class="dropdown-templates-item">
                            <div>
                                <?php
                                    $html = Html::tag('div', $template->name, ['class' => 'dropdown-templates-item-block']);
                                    $html .= Html::tag('div', $template->beneficiaryName, ['class' => 'dropdown-templates-item-block']);                                    
                                    echo Html::a(
                                        $html,
                                        ['/edm/payment-order-templates/create-payment-order', 'id' => $template->id],
                                        [
                                            'class' => 'template-load-link',
                                            'data'  => ['is-outdated' => (int)$template->isOutdated],
                                        ],
                                    );
                                ?>
                            </div>
                        </li>
                    <?php } ?>
                    <li class="dropdown-templates-item dropdown-templates-item-last">
                        <div class="dropdown-templates-item-block">
                            <?php
                            echo Html::a(
                                Yii::t('edm', 'My templates'),
                                ['/edm/payment-order-templates'],
                                ['class' => 'templates-content-all-templates']);
                            ?>
                        </div>
                    </li>
                </ul>


            </div>
            <div class="pull-right">
                <?=InlineHelp::widget(['widgetId' => 1])?>
            </div>
        </div>

        <?= // Вывести страницу
            $this->render('type/' . $model->getType(), [
                'model'  => $model,
                'errors' => isset($errors) ? $errors : null,
            ]) ?>
	</div>
</div>

<?php
// Регистрация стилей для блока с шаблонами
$this->registerCss(<<<CSS
    .edm-templates-dropdown {
        display: inline-block;

    }
    .dropdown-templates-list {
        width: 600px;
        padding: 0;
        margin: 0;
    }
    .dropdown-templates-item.dropdown-templates-item-last {
        padding: 10px;
    }

    .dropdown-templates-item:hover {
        background-color: #ccd;
    }

    .dropdown-templates-item-block {
        display: inline-block;
        vertical-align: top;
    }

    .dropdown-templates-item .template-load-link {            
        padding: 10px;
        display: block;
        color: #333;
        text-decoration: none;
    }

    .dropdown-templates-item-block:nth-child(1) {
        margin-right: 15px;
        width: 200px;
        font-size: 16px;
        font-weight: bold;
    }

    .dropdown-templates-item-block:nth-child(2) {
        margin-right: 15px;
        width: 300px;
        font-size: 14px;
    }

    .templates-content-all-templates {
        text-decoration: underline;
    }

    .dropdown-templates-item-last:hover {
        background-color: initial;
    }
CSS);
// Вывести модальное окно с формой
echo $this->render('@addons/edm/views/payment-order-templates/payment-order/_modalForm');
// Добавить скрипты для редактирования
echo $this->render('@addons/edm/views/payment-order-templates/_update-template-js');
