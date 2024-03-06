<?php
namespace common\components\storage;

use common\helpers\FileHelper;
use Exception;
use Psr\Log\LogLevel;
use Yii;
use yii\base\InvalidConfigException;
use common\helpers\MonitorLogHelper;

class SftpAdapter extends BaseAdapter
{
    /**
     * @var array $settingsSftp SFTP settings
     */
    private $_settingsSftp;

    public $serviceId;

    public $type;

    private $_sftp;
    private $_connection;

     /**
	 * Create path to save file
	 *
	 * @param string  $filename File name
	 * @return string
	 */
	public function createPath($filename = '')
	{
		$path = $this->getPath();

		if (!empty($filename)) {
			$path .= '/' . $filename;
		}

		return $path;
	}

    public function getContents($path = null)
	{
		if (is_null($path)) {
            $path = $this->path;
        }

        $items = scandir($path);
		foreach ($items as $key => &$fileName) {
			if (in_array($fileName, ['.', '..'])) {
				unset($items[$key]);
			} else {
				$fileName = $path . '/' . $fileName;
                if (is_dir($fileName)) {
                    unset($items[$key]);
                }
			}
		}

		return array_values($items);
	}

    public function chmod($path, $permissions)
    {
        $prefix = 'ssh2.sftp://' . intval($this->_sftp);

        if (strpos($path, $prefix) === 0) {
            $localPath = substr($path, strlen($prefix));

            $cn = ssh2_sftp($this->getSSH2Connection());

            return ssh2_sftp_chmod($cn, $localPath, $permissions);
        }

        return false;
    }

    public function getSettingsSftp()
    {
        return $this->_settingsSftp;
    }

    /**
     * Set SFTP settings
     *
     * @param array $settingsSftp SFTP settings (['host', 'port', 'username', 'password'])
     */
    public function setSettingsSftp($settingsSftp)
    {
        $this->_settingsSftp = $settingsSftp;
        $connection = $this->getSSH2Connection();
        $this->_sftp = ssh2_sftp($connection);
    }

    /**
     * Get ssh2 prefix
     *
     * @return string|boolean Return ssh2 prefix or FALSE
     */
    private function getSSH2Prefix()
    {
        try {
            return 'ssh2.sftp://' . intval($this->_sftp) . $this->_settingsSftp['path'];
        } catch (Exception $ex) {
            Yii::info("Bad SSH2 settings! Error: [{$ex->getMessage()}]", 'system');
            $this->logSFTPError();
            return false;
        }
    }

    /**
     * Perform command via SSH2
     *
     * @param string $command Command to perform
     * @return boolean
     * @throws Exception
     */
    private function performSSH2Command($command)
    {
        try {
            $result = ssh2_exec($this->getSSH2Connection(), $command);

            if ($result === false) {
                throw new Exception("Failed to exec command[{$command}]");
            }

            return true;
        } catch (Exception $ex) {
            Yii::warning($ex->getMessage());
            $this->logSFTPError();
            return false;
        }
    }

    private function getSSH2Connection()
    {
        if (!$this->_connection) {

            ini_set('default_socket_timeout', 5);

            try {
                $connection = ssh2_connect($this->_settingsSftp['host'], $this->_settingsSftp['port']);
            } catch (Exception $ex) {
                $this->logSFTPError();
                throw new Exception($ex->getMessage());
            }

            if ($connection === false) {
                $this->logSFTPError();
                throw new Exception("Failed to connect to {$this->_settingsSftp['host']}:{$this->_settingsSftp['port']}");
            }

            if (!ssh2_auth_password($connection, $this->_settingsSftp['username'], $this->_settingsSftp['password'])) {
                $this->logSFTPError();
                throw new Exception("Failed to auth with username {$this->_settingsSftp['username']}");
            }

            $this->_connection = $connection;
        }

        return $this->_connection;
    }

    /**
     * Get service path
     *
     * @return string
     */
    public function getServicePath()
    {
        $servicePath = $this->getSSH2Prefix() . $this->type;

        if (!is_readable($servicePath)) {
            $this->logSFTPError();
        }

        return $servicePath;
    }

    public function createDir($dirName)
    {
        try {
            $createdPath = $this->path . '/' . $dirName;

            if (FileHelper::createDirectory($createdPath, $this->permissions, true, true)) {
                return $createdPath;
            }
        } catch(Exception $ex) {
            $this->logSFTPError();
            throw new InvalidConfigException($ex->getMessage());
        }

        return false;
    }

  	/**
	 * Delete old files
	 *
	 * @param integer $maxDays Maximum lifetime
	 */
	public function deleteOldFiles($maxDays)
	{
		$dirs = array_merge([$this->getPath()], $this->getDirSubfolders($this->getPath()));

        foreach ($dirs as $dir) {
            $command = 'find ' . $dir . ' -type f -mtime +' . $maxDays . ' -exec rm -rf {} \;';
            $this->performSSH2Command($command);
        }
	}

    /**
	 * Save stream into storage
	 *
	 * @param stream $readStream Stream to save
	 * @param string $filename   File name
	 * @return string Storage path
	 */
	public function putStream($readStream, $filename = '')
	{
		if (empty($filename)) {
			$filename = FileHelper::uniqueName();
		} else {
			$filename = FileHelper::mb_basename($filename);
		}

		$filePath = $this->createPath() . '/' . $this->createFileName($filename);
		$writeStream = fopen($filePath, 'w');

		stream_copy_to_stream($readStream, $writeStream);
		fclose($readStream);
		fclose($writeStream);

		return $filePath;
	}


  	public function putData($data, $filename = '')
	{
		$savePath = $this->createPath() . '/' . $this->createFileName($filename);

        if (file_put_contents($savePath, $data) === false) {
            return false;
        }

        return $savePath;
	}

    public function putFile($path, $filename = '')
	{
		$savePath = $this->createPath() . '/' . $this->createFileName($filename);

        if (!copy($path,  $savePath)) {

            return false;
		}

        return $savePath;
	}

    public function isArchivable($path = null)
    {
        return false;
    }

    public function archive($relPath)
    {
        return [];
    }

    /**
	 * Get path
	 *
	 * @return string
	 */
	public function getPath($relPath = null)
	{
		if (empty($this->directory)) {
           $path = Yii::getAlias($this->getServicePath());
		} else {
            $path = Yii::getAlias($this->getServicePath() . '/' . $this->directory);
		}

        if (!$relPath) {
            return $path;
        }

        return $path . '/' . $relPath;
	}

    public function fsname()
    {
        return 'sftp';
    }

    public function getRealPath($relPath)
    {
        return $this->getPath($relPath);
    }

    private function logSFTPError()
    {
        return Yii::$app->monitoring->log('transport:sftpOpenFailed', 'sftp', 0, [
            'logLevel' => LogLevel::ERROR,
            'serviceId' => $this->serviceId,
            'path' => $this->type,
        ]);
    }

}
