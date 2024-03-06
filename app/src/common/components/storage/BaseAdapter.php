<?php
namespace common\components\storage;

use common\helpers\FileHelper;
use Yii;
use yii\base\BaseObject;

abstract class BaseAdapter extends BaseObject
{
    protected $_servicePath;

    /**
     * @var integer $_maxFiles Max file count per partition
     */
	protected $_maxFiles = 1000;

	/**
	 * @var boolean $usePartition Use partition status
	 */
	public $usePartition = true;
	/**
	 * @var boolean $createIfNotExists Create if now exists status
	 */
	public $createIfNotExists = true;

    /**
     * @var boolean $useUniqName Use unique prefix for filename
     */
    public $useUniqueName = true;


    public $directory;

    /**
	 * @var integer $permissions Permissions
	 */
	public $permissions = 0755;

    /**
     *
     * @var boolean $ignoreSftp Ignore sftp seetings even if they are set
     */
    public $ignoreSftp = false;

	/**
	 * Get filename
	 *
	 * @param string  $filename File name
	 * @return string
	 */
	public function createFileName($filename)
    {
        if (!$this->useUniqueName) {
            return $filename;
        }

        $uniq = FileHelper::uniqueName();

		if (!empty($filename)) {
			$uniq .= '.' . FileHelper::mb_basename($filename);
		}

        return $uniq;
	}

    /**
     * Get subfolders in specific dir
     *
     * @param string $dir Dir for scan
     * @param bool $needFullPath
     * @return array
     */
	public function getDirSubfolders($dir, $needFullPath = true)
	{
        $result = [];
        $path = Yii::getAlias($dir) . '/';
		$entries = scandir($path);

		if (!empty($entries)) {
			foreach ($entries as $entry) {
                $fullPath = $path . $entry;
                if ($entry{0} != '.' && is_dir($fullPath)) {
                    $result[filectime($fullPath) . '_' . $entry] = ($needFullPath) ? $fullPath : $entry;
				}
			}
		}

		ksort($result); // сортировка по времени создания

		return $result;
	}

    /**
	 * Generate uniq directory name
	 *
	 * @return string
	 */
	protected function generateDirectoryName()
	{
		return FileHelper::uniqueName();
	}


    public function checkPath($path)
    {
  		$testPath = pathinfo($path, PATHINFO_DIRNAME);

		// Путь должен быть не пустой, должен начинаться в директории ресурса и быть ранее созданным
		return (!empty($testPath) && strpos($testPath, $this->getPath()) === 0 && file_exists($path));
    }

    /**
     * Set service path
     *
     * @param string $servicePath Service path
     */
    public function setServicePath($servicePath)
    {
        $this->_servicePath = $servicePath;
    }

    public function getDirectoryStatus($path = null)
    {
        return static::STATUS_NORMAL;
    }

    public abstract function isArchivable();

	/**
	 * Содержимое папки
	 * @return array
	 */
    public abstract function getContents($path = null);

    public abstract function createPath($filename = '');

    public abstract function chmod($path, $permissions);

    public abstract function getServicePath();

    public abstract function createDir($dirName);

	public abstract function putStream($readStream, $filename = '');

    public abstract function putData($data, $filename = '');

	public abstract function putFile($path, $filename = '');

    public abstract function getPath($relPath = null);

    public abstract function fsname();

    public abstract function getRealPath($relPath);

}