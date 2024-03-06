<?php

namespace common\modules\transport\components;

use Yii;
use yii\base\Component;

/**
 * Name: CftcpTransport
 * Description: Wrapper to CFTCP CLI client
 */
class CftcpTransport extends Component
{
    /**
     * Action send type
     */
    const ACTION_TYPE_SEND = 'send';

    /**
     * Action receive type
     */
    const ACTION_TYPE_RECEIVE = 'receive';

    /**
     * Action type
     *
     * @var string Action type
     */
    private $_actionType;

    /**
     * Enable|disable resume option
     *
     * @var boolean Resume option
     */
    private $_resume;

    /**
     * Array of error messages
     *
     * @var array Error messages
     */
    private $_errors;

    /**
     * CFTCP client path
     *
     * @var string CFTCP path
     */
    public $cftcp = 'cftcp';

    /**
     * Host address
     *
     * @var string Host
     */
    public $host;

    /**
     * Port number
     *
     * @var integer Port
     */
    public $port = 40090;

    /**
     * File input dir
     *
     * @var string Input dir
     */
    public $dirIn;

    /**
     * Timeout in second
     *
     * @var integer Timeout
     */
    public $timeout = 15;

    /**
     * Chunk size (bytes)
     *
     * @var integer Chunk size
     */
    public $chunkSize = 1000000;

	public function init()
	{
		$settings = Yii::$app->settings->get('app');

        $processingSettings = $settings->processing;
        $url = parse_url($processingSettings['dsn']);

        if (isset($url['host'])) {
            $this->host = $url['host'];
        }

        if (isset($url['port'])) {
            $this->port = $url['port'];
        }

        $cftcpSettings = $settings->cftcp;

		$this->dirIn = $cftcpSettings['dirIn'];
		$this->timeout = $cftcpSettings['timeout'];
		$this->chunkSize = $cftcpSettings['chunkSize'];
	}

    /**
     * Send file to server via CFTCP client
     *
     * @access public
     * @param string $login Terminal login
     * @param string $password Terminal password (usually equal terminal login)
     * @param string $uuid Message UUID
     * @param string $file File with path
     * @param boolean $resume Resume option
     * @return boolean
     */
    public function send($login, $password, $uuid, $file, $resume = false)
    {
        $this->_actionType = self::ACTION_TYPE_SEND;
        $this->_resume     = $resume;

        $command = $this->makeCLICommand($login, $password, $uuid, $file);
        exec($command, $output, $return_var);

        if ($return_var === 0) {
            return true;
        } elseif ($return_var === 1) {
            $this->addError('File not sent');

            return false;
        } else {
            $this->addError('Error. Bad command or option?');
            
            return false;
        }
    }

    /**
     * Receiver file from server via CFTCP client
     *
     * @access public
     * @param string $login Terminal login
     * @param string $password Terminal password (usually equal termianl login)
     * @param string $uuid File UUID
     * @param string $file Path where save file
     * @param boolean $resume
     * @return boolean
     */
    public function receive($login, $password, $uuid, $file, $resume = false)
    {
        $this->_actionType = self::ACTION_TYPE_RECEIVE;
        $this->_resume     = $resume;

        $command = $this->makeCLICommand($login, $password, $uuid, $file);
        exec($command, $output, $return_var);

        if ($return_var === 0) {
            return true;
        } elseif ($return_var === 1) {
            $this->addError('File not received');

            return false;
        } else {
            $this->addError('Error. Bad command or option?');

            return false;
        }
    }

    /**
     * Returns a value indicating whether there is any error.
     *
     * @access public
     * @return boolean Whether there is any error.
     */
    public function hasErrors()
    {
        return !empty($this->_errors);
    }

    /**
     * Returns the errors.
     *
     * @access public
     */
    public function getErrors()
    {
        return $this->_errors === NULL ? [] : $this->_errors;
    }

    /**
     * Remove error messages
     *
     * @access public
     */
    public function clearErros()
    {
        $this->_errors = [];
    }

    /**
     * Make send|receive file CLI command
     *
     * @access private
     * @param string $login Terminal login
     * @param string $password Terminal password (usually equal terminal login)
     * @param string $uuid File|Message UUID
     * @param string $file File name with path
     * @return string CLI command
     */
    private function makeCLICommand($login, $password, $uuid, $file)
    {
        /**
         * @todo если в пароле кавычка или апостроф, будет конфликтовать с echo
         * надо или экранировать, или переделать с write/read pipes, как в подписалках
         */

        $command = "echo '" . $password. "' | " . $this->cftcp . ' -P ' . $this->port . ' -T ' . $this->timeout . ' -C ' . $this->chunkSize . ' -U ' . $login;
        // If resume option is enabled add "-R" option to command
        if ($this->_resume) {
            $command .= ' -R';
        }

        if ($this->_actionType === self::ACTION_TYPE_SEND) {
            $command .= ' '.$file.' '.$this->host.':'.$uuid;
        } else {
            $command .= ' '.$this->host.':'.$uuid.' '.$file;
        }

        return $command;
    }

    /**
     * Add a new error message
     *
     * @access private
     * @param string $error New error message
     */
    private function addError($error = '')
    {
        $this->_errors[] = $error;
    }
}