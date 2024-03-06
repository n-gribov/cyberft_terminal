<?php
namespace common\components;

use Yii;
use yii\base\Component;
use yii\swiftmailer\Mailer;

/**
 * Notifier is used for sending event-triggered messages to the subscribed users
 *
 * @author a.nikolaenko
 *
 * @package core
 * @subpackage components
 *
 * @params string $from From params
 */
class MailNotifier extends Component
{
    /**
     * @var string $from From params
     */
    public $from;

    /**
     * @var array $_defaultParams Default mailer params
     */
    private $_defaultParams = [
        'subject' => 'No subject',
        'view'    => '@common/modules/monitor/views/mailer/default',
        'url'     => 'cyberplat.com',
    ];


    /**
     * @inheritdoc
     */
    public function init()
	{
		parent::init();
    }

    /**
     * Send message
     *
     * @param array $msgData     Message data
     * @param array $addressList List of address
     * @return boolean
     */
    public function sendMessage($msgData, $addressList = null)
    {
        /**
         * @var common\modules\monitor\settings\NotificationSettings $settings
         */
        $settings = Yii::$app->settings->get('monitor:Notification');

        if (is_null($addressList)) {
            $addressList = $settings->addressList;
        } else if (!is_array($addressList)) {
            $addressList = [$addressList];
        }

        $mailer = new Mailer([
            'transport' => [
                'class'      => 'Swift_SmtpTransport',
                'host'       => $settings->host,
                'username'   => $settings->login,
                'password'   => $settings->password,
                'port'       => $settings->port,
                'encryption' => $settings->encryption,
            ],
            'htmlLayout' => false
        ]);

        $msgData = array_merge($msgData, array_diff_key($this->getDefaultParams(), $msgData));

        $result = true;

        foreach($addressList as $address) {
            $message = $mailer->compose(
                    $msgData['view'],
                    $msgData
                )
                ->setTo($address)
                ->setSubject($msgData['subject']);

            if (isset($msgData['from']) && !empty($msgData['from'])) {
                $message->setFrom($msgData['from']);
            } else {
                $message->setFrom($this->from);
            }

            if (isset($msgData['body'])) {
                $message->setTextBody($msgData['body']);
            }

            if (isset($msgData['htmlBody'])) {
                $message->setHtmlBody($msgData['htmlBody']);
            }

            $result = $result && $message->send();
        }

        return $result;
	}

    /**
     * Get default params
     *
     * @return array
     */
    private function getDefaultParams()
    {
        $params = $this->_defaultParams;
        $params['from'] = $this->from;

        return $params;
    }
}