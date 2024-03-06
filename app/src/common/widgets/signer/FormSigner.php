<?php
namespace common\widgets\signer;
use \yii\web\View;

/**
 * @todo перенести в certManager
 *
 * Usage example:
 * <code>
 * <?php ActiveForm::begin(['id' => 'login-form']);
 * <?=FormSigner::widget([
 * 'form' => $form,
 * 'model' => $model
 * ])?>
 * <?php ActiveForm::end();?>
 * </code>
 *
 * @author vk
 */
class FormSigner extends \yii\base\Widget {
	/**
	 * @var \yii\widgets\ActiveForm
	 */
	public $form;
	/**
	 * Нужно ли помещать подписываемые данные внутрь контейнера (поддерживается не всеми контейнерами)
	 * @var bool
	 */
	public $isDetached = false;
	/**
	 * Именование js адаптера для подписи
	 * @var string
	 */
	public $signer = 'capicom';
	/**
	 * Переменная POST запроса, куда будет помещен массив всех элементов подписываемой формы
	 * @var type 
	 */
	public $signedVar = 'signedRequest';
	/**
	 * Ошибки отдаваемые при исключениях внутри JS
	 * @var array
	 */
	public $error = [];

	/**
	 * Предустановленные сообщения об ошибках
	 * @var array
	 */
	public $defaultError = [
		'undefinedSigner' => 'Your browser does not support any of the existing ways of e-signature implementation',
		'brokenSigner' => 'Signature driver has encountered an error',
		'emptyCertField' => 'Please, select the certificate to sign the action',
	];
	/**
	 * @var \yii\base\Model
	 */
	public $model;
	
	public function init() {
		// Трансляция сообщений возможна только здесь
		foreach($this->defaultError as $errorName => $errorMsg) {
			$this->defaultError[$errorName] = \Yii::t('app', $errorMsg);
		}
		$this->error = array_merge($this->defaultError, $this->error);
		FormSignerAsset::register($this->getView());
		parent::init();
	}
	
	
	public function run() {
		$this->getView()->registerJs(
			'formSigner.init('
				.json_encode([
						'formId'		=> $this->form->id,
						'signedVar'		=> $this->model->formName().'['.$this->signedVar.']',
						'signer'		=> $this->signer,
						'isDetached'	=> $this->isDetached,
						'error'			=> $this->error
					],
					JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_FORCE_OBJECT
				)
			.');',
			View::POS_LOAD
		);
    }
}
