<?php
namespace common\helpers;

use common\components\xmlsec\xmlseclibs\XMLSecEnc;
use common\models\cyberxml\CyberXmlDocument;
use common\modules\autobot\models\Autobot;
use common\modules\certManager\components\ssl\X509FileModel;
use common\modules\certManager\models\Cert;
use Exception;
use Yii;

class SigningHelper
{
    /*
    * Метод получает список пользовательских подписей из xml
    */
    public static function getUserSignaturesList($xml) {

        $signatures = [];

        // Необходимо собрать подписи пользователей-подписантов
        // Подписи могут хранится в контейнере с namespace (новый вариант) и без него (старые документы)
        // Здесь учитываем оба способа хранения подписей

        foreach ($xml->xpath('//*[local-name() = "Signature"]') as $node) {

            $ds = null;
            $ns = $node->getNamespaces(true);
            if (isset($ns['ds'])) {
                $ds = $node->children($ns['ds']);
            } else if (isset($node->KeyInfo->KeyName)) {
                $ds = $node;
            }
            if (!$ds) {
                continue;
            }
            // Если существует ветка с fingerprint
            if (isset($ds->KeyInfo->KeyName)) {
                $fingerprint = (string) $ds->KeyInfo->KeyName;
                // Получаем время подписания, если оно присутствует
                $signingTime = '';

                if (isset($ds->SignedInfo->SigningTime)) {
                    $signingTime = (string) $ds->SignedInfo->SigningTime;
                } else if (isset($ds->Object->QualifyingProperties->SignedProperties
                    ->SignedSignatureProperties->SigningTime)
                ) {
                    $signingTime = (string) $ds->Object->QualifyingProperties->SignedProperties
                        ->SignedSignatureProperties->SigningTime;
                }

                $name = '';
                if (isset($ds->KeyInfo->X509Data->X509SubjectName)) {
                    $name = (string) $ds->KeyInfo->X509Data->X509SubjectName;
                    $pos = strpos($name, 'CN=');
                    if ($pos !== false) {
                        $pos2 = strpos($name, ';', $pos + 3);
                        if ($pos2 === false) {
                            $pos2 = strlen($name);
                        }
                        $name = substr($name, $pos  + 3, $pos2);
                    }
                }

                if (!$name) {
                    if (isset($ds->KeyInfo->X509Data->X509Certificate)) {
                        $certBody = (string) $ds->KeyInfo->X509Data->X509Certificate;
                        $x509Data = X509FileModel::loadData($certBody);
                        $name = $x509Data->getSubjectCN();
                    } else {
                        $cert = Cert::findByFingerprint($fingerprint);
                        if ($cert) {
                            $name = $cert->fullName;
                        }
                    }
                }

                $signatures[] = [
                    'name' => $name,
                    'fingerprint' => $fingerprint,
                    'signingTime' => $signingTime ? date('Y-m-d H:i:s', strtotime($signingTime)) : '',
                    'role' => Cert::ROLE_SIGNER
                ];
            }
        }

        return $signatures;
    }

    public static function isSignatureRequired($origin, $type, $terminalId = null)
    {
        $addon = Yii::$app->registry->getTypeModule($type);

        return $addon->isSignatureRequired($origin, $terminalId);
    }

    public static function getDecryptKeys(CyberXmlDocument $cyxDoc)
    {
        $certs = [];

        $dom = $cyxDoc->getDom();
        $encryptedData = Yii::$app->xmlsec->locateEncryptedData($dom);

        $xmlsec = new XMLSecEnc();
        $keys = $xmlsec->locateKeyInfo(null, $encryptedData);

        $terminalData = Yii::$app->terminals->findTerminalData($cyxDoc->receiverId);
        if (empty($terminalData)) {
            throw new Exception('Terminal data is empty for ' . $cyxDoc->receiverId);
        }

        if (!empty($keys)) {
            foreach($keys as $key) {
                $autobots = [];
                if (!empty($key->name)) {
                    $autobot = Autobot::find()
                        ->joinWith('controller.terminal')
                        ->where([
                            'terminal.terminalId' => $cyxDoc->receiverId,
                            'autobot.fingerprint' => $key->name,
                            'autobot.status' => [Autobot::STATUS_USED_FOR_SIGNING, Autobot::STATUS_ACTIVE, Autobot::STATUS_WAITING_FOR_ACTIVATION],
                        ])
                        ->one();
                    if (!empty($autobot)) {
                        $autobots[] = $autobot;
                    } else {
                        $autobots = Yii::$app->terminals->findAutobotsUsedForDecryption($cyxDoc->receiverId);
                    }

                    if (empty($autobots)) {
                        Yii::info('Failed to find autobot(s) for key ' . $key->name);

                        continue;
                    }

                    $password = false;
                    foreach($autobots as $autobot) {
                        if (isset($terminalData['passwords'][$autobot->id])) {
                            $password = $terminalData['passwords'][$autobot->id];
                            $certs[$autobot->fingerprint] = [
                                'privateKey' => $autobot->privateKey,
                                'privatePassword' => $password
                            ];

                            break;
                        }
                    }

                    if ($password === false) {
                        Yii::info('Failed to find password for private key ' . $key->name);
                    }
                }
            }
        }

        return $certs;
    }

}
