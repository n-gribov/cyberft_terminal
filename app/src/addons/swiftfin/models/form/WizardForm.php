<?php

namespace addons\swiftfin\models\form;

use addons\swiftfin\models\containers\swift\SwtContainer;
use Yii;
use common\base\Model;
use addons\swiftfin\SwiftfinModule;
use yii\helpers\ArrayHelper;
use addons\swiftfin\models\documents\mt\MtUniversalDocument;
use addons\swiftfin\models\documents\mt\MtOwnerInterface;

class WizardForm extends Model implements MtOwnerInterface
{
	public $recipient;
	public $sender;
	public $terminalCode;
	public $contentType;
	public $bankPriority;
	/**
	 * @var bool
	 */
	public $rawMode = false;

	/**
	 * @var MtUniversalDocument
	 */
	protected $_contentModel;
	protected $_content;

	public function rules()
    {
		return [
			[['sender', 'recipient', 'contentType'], 'required',],
			[['sender', 'recipient'], 'string', 'length' => [11, 12]],
			['contentType', 'string', 'length' => [3, 8]],
    		['bankPriority', 'match', 'pattern' => '/^[a-zA-Z0-9]{4}$/i'],
			['bankPriority', 'string', 'length' => 4]
		];
	}

    public function getRecipient()
    {
        return $this->recipient;
    }

	public function getSender()
    {
    		return $this->sender;
    }

    public function getTerminalCode()
    {
    		return $this->terminalCode;
    }

	/**
	 * Функция возвращает вектор, содаржащий имена всех safe-атрибутов данной
	 * модели.
	 * CYB-1200. Значение terminalCode не пробрасывается из формы, т.к. данный
	 * атрибут не считается безопасным (для него нет rules). Необходимо в явном
	 * виде указать его как безопасный.
	 * @return array
	 */
	public function safeAttributes()
	{
		return ArrayHelper::merge(parent::safeAttributes(), ['terminalCode']);
	}

	public function attributeLabels()
    {
		return ArrayHelper::merge(
            parent::attributeLabels(),
            [
                'contentType'  => Yii::t('doc', 'Type'),
                'typeCode'     => Yii::t('doc', 'Type'),
                'sender'       => Yii::t('doc', 'Sender'),
                'recipient'    => Yii::t('doc', 'Recipient'),
                'terminalCode' => Yii::t('doc', 'Terminal code')
            ]
        );
	}

	public function getSupportedTypes()
    {
		return SwiftfinModule::getInstance()->mtDispatcher->getTypesLabels();
	}

	/**
	 * @return MtUniversalDocument
	 */
	public function getContentModel()
    {
		if (is_null($this->_contentModel)) {
            $this->_contentModel = SwiftfinModule::getInstance()
                                    ->mtDispatcher->instantiateMt($this->contentType, ['owner' => $this]);
		}

		return $this->_contentModel;
	}

	public function unsetContentModel()
    {
		$this->_contentModel = null;
	}

	/**
	 * @param string $value
	 */
	public function setContent($value)
    {
		$this->_content = $value;
		$this->getContentModel()->clearErrors();
		$this->getContentModel()->setBody($value);
	}

	public function getContent()
    {
		if ($this->rawMode && $this->_content) {
			return $this->_content;
		} else {
			return $this->getContentModel()->getBody();
		}
	}

	public function validate($attributeNames = null, $clearErrors = true)
    {
		if (!$this->contentModel->validate($attributeNames, $clearErrors)) {
			// Пробрасываем ошибки в контейнер, теперь они отобразятся при
			// сохранении документа в таблице БД
			$this->addErrors($this->contentModel->getErrors());

			return false;
		}

		return parent::validate($attributeNames, $clearErrors);
	}

	/**
	 * @return SwtContainer
	 */
	public function getSwtContainer()
    {
		$swt = new SwtContainer();
		$swt->setRecipient($this->recipient);
		$swt->setSender($this->sender);
		$swt->terminalCode = $this->terminalCode;
		$swt->setBankPriority($this->bankPriority);
		$swt->setContentType($this->_contentModel->type);
		$swt->setContent($this->getContent());

		return $swt;
	}

}