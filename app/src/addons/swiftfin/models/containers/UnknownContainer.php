<?php
namespace addons\swiftfin\models\containers;

use addons\swiftfin\models\containers\BaseContainer;
use Yii;

/**
 * Description of UnknownContainer
 *
 * @author fuzz
 */
class UnknownContainer extends BaseContainer 
{
	public function getDocumentData()
	{
		return [];
	}


	public function getRawText()
	{
		return $this->getRawContents();
	}
 	/**
	 * 
	 * @return string
	 */
	public function export()
	{
		return '';
	}
	
	public function validate($attributeNames = null, $clearErrors = true)
	{
		$this->addError('error', Yii::t('document', 'Unknown document format'));
        
		return false;
	}
}
