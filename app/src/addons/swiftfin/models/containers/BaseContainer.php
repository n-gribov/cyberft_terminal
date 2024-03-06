<?php
namespace addons\swiftfin\models\containers;

use common\base\Model;

/**
 * Description of CyberXmlDocument
 *
 * @property string $rawContents
 * @property string $rawData
 * @property string $sourceFile
 * @author fuzz
 */
abstract class BaseContainer extends Model {

	/**
	 * @var
	 */
	protected $_sourceFile;
	/**
	 * @var
	 */
	protected $_rawContents;

	/**
	 * @var array
	 */
	protected $_models = [];


	public function init()
	{
		if (!empty($this->sourceFile)) {
			$this->loadData();
		}
	}

	/**
	 * @return mixed
	 */
	public function getSourceFile()
	{
		return $this->_sourceFile;
	}

	/**
	 * @param $path
	 */
	public function setSourceFile($path)
	{
		$this->_sourceFile = $path;
//		$this->loadData();
	}

	/**
	 * @return string
	 */
	public function getRawContents()
	{
		if (is_null($this->_rawContents) && file_exists($this->_sourceFile)) {
			$this->_rawContents = file_get_contents($this->_sourceFile);
		}
		
		return $this->_rawContents;
	}

	/**
	 * @param $value
	 */
	public function setRawData($value)
    {
		$this->_rawContents = $value;
	}

	/**
	 * @param null $data
	 */
	public function loadData($data = null)
    {
		if (!is_null($data)) {
			$this->_rawContents = $data;
		}
	}

	/**
	 * @return array
	 */
	public function getModels()
	{
		return $this->_models;
	}

	public function getContentText() {

    }

	/**
	 * @param $filePath
	 */
	public function export()
	{
		
	}

	public function getContent()
    {
		
	}
	
	/**
	 * 
	 * @return string
	 */
	public function getRawText()
	{
		return '';
	}

    public function getReadableErrors()
    {
        $errors = $this->errors;
        $out  = [];

        foreach($errors as $field => $content) {
            $out[] = $field . ': ' . $this->recursiveCollect($content);
        }

        return implode("\n", $out);
    }

    private function recursiveCollect($input)
    {
        if (!is_array($input)) {
            return $input;
        }

        $out = [];
        foreach($input as $key => $entry) {
            $out[] = $key . ': ' . $this->recursiveCollect($entry);
        }

        return implode("\n", $out);
    }

}
