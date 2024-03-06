<?php


namespace common\widgets\documents;

use yii\base\Widget;
use yii\web\JsExpression;

class SignDocumentsButton extends Widget
{
    /** @var string */
    public $id = 'sign-documents-button';

    /** @var string */
    public $buttonText;

    /** @var integer[] */
    public $documentsIds = [];

    /** @var JsExpression|null
     * JS-функция, формирующая массив с id документов для подписания. Принимает callback, инициирующий подписание,
     * в который должна передать массив с id документов. Пример:
     * function (signCallback) {
     *     var documentsIds = ...;
     *     signCallback(documentsIds);
     * }
     */
    public $prepareDocumentsIds;

    /** @var string|null
     * URL, на который надо отправить пользователя после успешного подписания.
     * Если не задан, делается редирект на текущую страницу
     */
    public $successRedirectUrl;

    /** @var string|null
     * Ключ кэша выбранных документов (common\helpers\ControllerCache).
     * После успешного подписания кэш будет очищен.
     */
    public $entriesSelectionCacheKey;

    /** @var boolean
     * Находится ли кнопка внутри документа, загруженного через Ajax-запрос
     */
    public $isInsideAjaxDocument = false;

    /** @var string jQuery-селектор для контейнера, в котором нужно показывать алерты с сообщениями для пользователя */
    public $alertsContainerSelector = '.well.well-content:first';

    public $useVersionCheck = true;
    public $requiredVersion = '2.0 build 202002141515';

    public function run()
    {
        return $this->render(
            'signDocumentsButton',
            ['widget' => $this]
        );
    }
}
