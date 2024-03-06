<?php

namespace console\controllers;

use common\helpers\Uuid;
use common\modules\transport\models\CFTAckType;
use common\modules\transport\models\CFTStatusReportType;
use common\models\cyberxml\CyberXmlDocument;
use SimpleXMLElement;
use Yii;
use yii\console\Controller;
use common\modules\transport\components\StompTransport;

/**
 * Manages users
 */
class LoadTestController extends Controller
{
    private static $senders_keys = [
        'TESTXXX@X001' => 'D0357B3B4D7AE4636EB9D98E72A8E2C6F0772D3D',
		'VELERUM@A001' => '4A9BB9B32BBBC6F15364FDD83D18A36C5492B608',
		'TESTRUM@A763' => '97F193BBD0F2042A0727A6BCDA16E1A12619BAC0',
		'TESTPDV@X001' => '9B7B34AF26D40555FC48D1CE42AEB8046BB96689',
		'TESTDEP@B001' => '40864FE5CEE86D3EB6EC31A76341F9C7C7EFB689',
		'TESTDEP@A001' => 'F7F8756F0C9803B7CD2D9BE943FB86CCA7B7ABFD',
		'SIMKIN1@BEST' => 'F264E6878C7D1E17ED4CEA663C1BB00EE337FA2A',
		'KOVALKO@XDEV' => 'E9D1B49CE737E6387EBDE172BAC580609376AC78',
		'EGORRUM@AXXX' => '076FBA619E732E9CC97BDEE61E77372778A28DD4',
		'DANIXXL@ENKO' => 'BBD1E681688FA5B75DC5425074DCECAD731A63A9'
    ];

	    private static $recipients = [
		'DANIXXL@ENKO' => 'TESTXXX@X001',
		'EGORRUM@AXXX' => 'VELERUM@A001',
		'KOVALKO@XDEV' => 'TESTRUM@A763',
		'SIMKIN1@BEST' => 'TESTPDV@X001',
		'TESTDEP@A001' => 'TESTDEP@B001',
		'TESTDEP@B001' => 'TESTDEP@A001',
		'TESTPDV@X001' => 'SIMKIN1@BEST',
		'TESTRUM@A763' => 'KOVALKO@XDEV',
		'VELERUM@A001' => 'EGORRUM@AXXX',
        'TESTXXX@X001' => 'DANIXXL@ENKO'
		];



    public function actionProduce($count = 5)
	{
        $queue = 'INPUT';
        $myMsg = '<?xml version="1.0" encoding="UTF-8"?>
<Document xmlns="http://cyberft.ru/xsd/cftdoc.01"><Header><DocId>__DOC_ID__</DocId><DocDate>2016-05-12T10:53:31+03:00</DocDate><SenderId>__SENDER__</SenderId><ReceiverId>__RECIPIENT__</ReceiverId><DocType>MT999</DocType><DocDetails><PaymentRegisterInfo sum="0" count="1"/></DocDetails><SignatureContainer><Signature xmlns="http://www.w3.org/2000/09/xmldsig#">
		<SignedInfo><CanonicalizationMethod Algorithm="http://www.w3.org/TR/2001/REC-xml-c14n-20010315"/>
			<SignatureMethod Algorithm="http://www.w3.org/2000/09/xmldsig#rsa-sha1"/>
		<Reference URI=""><Transforms><Transform Algorithm="http://www.w3.org/2000/09/xmldsig#enveloped-signature"/><Transform Algorithm="http://www.w3.org/TR/1999/REC-xpath-19991116"><XPath xmlns:doc="http://cyberft.ru/xsd/cftdoc.01">not(ancestor-or-self::doc:SignatureContainer or ancestor-or-self::doc:TraceList)</XPath></Transform></Transforms><DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1"/><DigestValue>HtwVwz8jbyUedubxkTlZGMMl2Ro=</DigestValue></Reference></SignedInfo><SignatureValue>hegcpHQN8stiMR7BgASug0dkxCXqsHcmGJpBSi1mhVBnbLG8cI4FzBGbK3ORJBlDJefK2uehzpItO5hSRcnGp34PVFMeeiBLb6qqrWpdXN+QxCxWRAqxK1g0eiE3tj26TrhXGYC+P3R3SOO0pHp+zIkdOIbX9nKNeY63T4zYpBGP4ghgXne5k1ponUP8pcnn1R3IsM7Mg6egjZEdg3Png0H/982zgc4FJHy0by1YyhIh7x29TaKDvwPQuUAI1JqNascDv2G6aSi/wRg/cfNNHiYjpV0nboO0tTlKzIaEJmPtug6CMowfYRGiZMyCkKdAJu3CIecpTuNQBAhGxOM0IA==</SignatureValue>
	<KeyInfo><KeyName>__KEY__</KeyName></KeyInfo></Signature></SignatureContainer></Header><Body encoding="base64" mimeType="application/text"><RawData xmlns="http://cyberft.ru/xsd/cftdata.01">AXsxOkYwMVNJTUtJTjFAVEVTVDAwMDAwMDAwMDB9ezI6STk5OVNJTUtJTjFAVEVTVE59ezQ6CjoyMDp0eWh0eQ0KOjc5OnR5aHR5aHl0DQotfQMNCg==</RawData></Body></Document>';
        $pids = [];

		foreach(self::$senders_keys as $sender => $key) {

            $pid = pcntl_fork();
            $pids[] = $pid;

            if ($pid == 0) {
                $stomp = new StompTransport();

                $headers = [
                    'sender_id' => $sender,
                    'doc_type' => 'MT999',
                ];
				$recipient = self::$recipients[$sender];
                $msg = str_replace('__KEY__', $key, $myMsg);
                $msg = str_replace('__SENDER__', $sender, $msg);
                $msg = str_replace('__RECIPIENT__', $recipient, $msg);

                for ($i = 0; $i < $count; $i++) {
                    $doc_id = $sender . str_pad($i, 5, '0', STR_PAD_LEFT) . "_" . str_pad(microtime(true), 16, '0', STR_PAD_LEFT);
                    $finalMsg = str_replace('__DOC_ID__', $doc_id, $msg);
                    $headers['receipt'] = uniqid('Document', true);
                    $headers['doc_id'] = $doc_id;
					$result = $stomp->send($finalMsg, $sender, $sender, $queue, $headers);

                    if ($result) {
                        echo "sent msg #{$i} from {$sender} to {$recipient}\n";
						file_put_contents('/var/www/cyberft/app/src/sending_report_' . $sender . ".txt",
                            $doc_id . PHP_EOL,
                            FILE_APPEND);
						//print_r($finalMsg);
						usleep(200000);
                    } else {
                        echo "ERROR: can't send msg #{$i} from {$sender} to {$recipient}\n";
						file_put_contents('/var/www/cyberft/app/src/sending_report.err',
                            "can't send msg #{$i} from {$sender}" . "\t" . $doc_id . PHP_EOL,
                            FILE_APPEND);
                    }
                }

                exit(0);
            }
		}

        foreach ($pids as $pid) {
            pcntl_waitpid($pid, $status);
        }

    }

	public function actionConsume()
	{
    	$pids = [];

        foreach(self::$senders_keys as $sender => $key) {
            $pid = pcntl_fork();
            $pids[] = $pid;

            if ($pid == 0) {
                $this->consume($sender, $key);

                break;
            }
        }

        foreach ($pids as $pid) {
            pcntl_waitpid($pid, $status);
        }
    }

    private function consume($sender, $key)
    {
		$stomp = new StompTransport();
        $queue = $sender;
        echo "Start consuming for {$sender}...\n";

        while (true) {
            //usleep(1000);
            $msg = $stomp->receive($sender, $sender, $sender);
			if ($msg) {
				if (!isset($msg['0']->headers['doc_id'])) {
					file_put_contents('/var/www/cyberft/app/src/transport_report.err', print_r($msg, true), FILE_APPEND);
				}

				$receipt = null;
				if (!isset($msg['0']->headers['receipt'])) {
					file_put_contents('/var/www/cyberft/app/src/transport_report.err', print_r($msg, true), FILE_APPEND);
				} else {
					$receipt = trim($msg['0']->headers['receipt']);
					if (empty($receipt)) {
						file_put_contents('/var/www/cyberft/app/src/transport_report.err', print_r($msg, true), FILE_APPEND);
					}
				}


				print('RECEIVED: ' . $msg['0']->headers['doc_id'] . " - " . $msg['0']->headers['receipt'] . PHP_EOL);

				//var_dump($msg);
				$cyx = new CyberXmlDocument();
				$cyx->loadXML($msg['0']->body);
				if (!$cyx->isAckable()) {
					continue;
				}
				// print("ACKABLE\n");
				$typeModelAck = new CFTAckType([
					'refDocId' => $cyx->docId,
					'refSenderId' => $cyx->senderId,
				]);

				$ack = new CyberXmlDocument([
					'docId' => Uuid::generate(),
					'docDate' => Yii::$app->formatter->asDatetime(gmdate('Y-m-d H:i:s'), 'php:c'),
					'senderId' => $sender,
					'receiverId' => "CYBERUM@TEST",
					'docType' => $typeModelAck->type

				]);

				$ack->getContent()->loadFromTypeModel($typeModelAck);
				$a = new SimpleXMLElement($ack->saveXML());
				$a->Header->SignatureContainer->Signature->KeyInfo->KeyName = $key;
                $stomp->send($a->saveXML(), $sender, $sender, 'INPUT',
                    [
                        'receipt' => uniqid('Document', true),
                        'doc_id' => $ack->docId,
                        'sender_id' => $sender,
                        'doc_type' => $ack->docType
                    ]
                 );

				if ($cyx->isReport()) {
					continue;
				}

				// Атрибуты StatusReport
				$typeModel = new CFTStatusReportType([
					'refDocId' => $cyx->docId,
					'statusCode' => 'ACDC',
					'errorCode' => '0',
					'errorDescription' => '',
				]);

				// Создаем ответный StatusReport в виде CyberXmlDocument,
				// указываем значения для атрибутов заголовка

				$statusReport = new CyberXmlDocument([
					'docId' => Uuid::generate(),
					'docDate' => Yii::$app->formatter->asDatetime(gmdate('Y-m-d H:i:s'), 'php:c'),
					'senderId' => $sender,
					'receiverId' => $msg['0']->headers['sender_id'],
					'docType' => $typeModel->type
				]);

				$statusReport->getContent()->loadFromTypeModel($typeModel);
				$b = new SimpleXMLElement($statusReport->saveXML());
				$b->Header->SignatureContainer->Signature->KeyInfo->KeyName = $key;


				// print("REPORTABLE\n");
        		$stomp->send($b->saveXML(), $sender, $sender, 'INPUT',
                    [
                        'receipt' => uniqid('Document', true),
                        'doc_id' => $ack->docId,
                        'sender_id' => $sender,
                        'doc_type' => $ack->docType
					]
				);

				$remoteId = $msg['0']->headers['doc_id'];
				$parts = explode('_', $remoteId);
                if (count($parts) > 1) {
					$cur_time = microtime(true);
                    $time = $cur_time - $parts[1];
                    file_put_contents('/var/www/cyberft/app/src/transport_report.txt',
                            $cur_time . "\t" . $msg['0']->headers['doc_id'] . "\t" . $time . "\t" . $receipt ."\n",
                            FILE_APPEND);
                } else {
                    echo "ERROR: wrong remoteId: " . $remoteId . "\n";
                }
			} else {
                echo "{$sender}: No frames to read\n";
			}
        }
	}

    public function actionParse($file)
    {
        $fp = fopen($file, 'r');
        $stringCount = 0;

        $accuTime = 0.0;
        $startTime = null;
        $accuCount = 0;
        $secCount = 0;
        $totalCount = 0;
        $maxCount = 0;
        $totalElapsed = 0;

        $prevTime = 0;

        $minCount = null;

        $out = fopen('out.csv', 'w');

        fputs($out, "Кол-во секунд; Сообщений в секунду; Всего сообщений\n");

        $senders = [];
        $maxRoundTrip = 0;
        $count = 0;

        while(!feof($fp)) {
            $count++;
//            if ($count > 100) {
//                break;
//            }
            $s = trim(fgets($fp));
            $stringCount++;
            if (!$s) {
                echo 'empty string #' . $stringCount . "\n";
                continue;
            }

            $parts = explode("\t", $s);

            if (count($parts) < 3) {
                throw new \yii\base\InvalidValueException('wrong log format in string #' . $stringCount);
            }

            $time = (float) $parts[0];

            $senderParts = explode('_', $parts[1]);

            if ($prevTime == 0) {
                $prevTime = $time;
            }

            $roundtrip = $time - $prevTime;


            if ($maxRoundTrip < $roundtrip) {
                $maxRoundTrip = $roundtrip;
            }

            $totalElapsed += ($time - $prevTime);
            $prevTime = $time;

            $num = (int) substr($senderParts[0], 12);
            $sender = substr($senderParts[0], 0, 12);


            //echo "sender = $sender, num = $num\n";
            if (!isset($senders[$sender][$num])) {
                $senders[$sender][$num] = 1;
            } else {
                $senders[$sender][$num]++;
            }

            $accuCount++;
            if ($accuCount > $maxCount) {
                $maxCount = $accuCount;
            }

            $totalCount++;

            if (is_null($startTime)) {
                $startTime = $time;
            } else {
                $diff = $time - $startTime;
                $accuTime += $diff;
                //echo "accuTime = $accuTime, diff = $diff, accuCount = $accuCount\n";
                if ($accuTime > 1.0) {
                    $secCount++;
                    fputs($out, $secCount . '; ' . $accuCount . '; ' . $totalCount . "\n");

                    if (is_null($minCount)) {
                        $minCount = $accuCount;
                    } else if ($accuCount < $minCount) {
                        $minCount = $accuCount;
                    }

                    $accuCount = 0;
                    $accuTime -= 1.0;
                }
            }

            $startTime = $time;
        }

        if ($accuCount) {
            $secCount++;
            fputs($out, $secCount . '; ' . $accuCount . '; ' . $totalCount . "\n");
            if (is_null($minCount)) {
                $minCount = $accuCount;
            } else if ($accuCount < $minCount) {
                $minCount = $accuCount;
            }
        }

        fclose($fp);
        fclose($out);

        echo "minCount: $minCount, maxCount: $maxCount, average per sec: " . ($totalCount / $secCount)
                . ", average time per document: " . ($secCount / $totalCount) . ", maxRoundTrip = " . $maxRoundTrip . "\n";

        foreach($senders as $sender => $numbers) {
            ksort($numbers);

            $missing = [];
			$prevNum = 0;
			$usedNumbers = [];

            foreach ($numbers as $num => $numCount) {
				if ($numCount > 1) {
					echo "Repeated x" . $numCount . ":" . $sender . " ". $num . "\n";
				}
				$usedNumbers[$num] = true;
				if ($num > 0) {
					$prevNum = $num - 1;
				} else {
					$prevNum = $num;
				}

				while(!isset($usedNumbers[$prevNum]) && $prevNum > -1) {
					$usedNumbers[$prevNum] = true;
                    $missing[] = $prevNum;
					$prevNum--;
                }

				$prevNum = $num;
            }

            echo "$sender has " . count($numbers) . " numbers";

            if (!empty($missing)) {
                echo ", missing ##: " . implode(', ', $missing);
            }

            echo "\n";
        }
    }

    public function actionParse2($file)
    {
//Jun  1 15:35:41 cyberswift-p72 cyberft-dev:[16161]: router: [1] <info> In: size=1834; time=2016-06-01T15:35:41.431110; Broker headers: source=KOVALKO@XDEV, source-ip=127.0.0.1, sender_id=KOVALKO@XDEV, doc_id=KOVALKO@XDEV00003_001464784540.275, doc_type=MT999

        $fp = fopen($file, 'r');
        $stringCount = 0;

        $buffer = [];
        $startTime = null;
        $endTime = 0;

        $out = fopen('out.csv', 'w');
        fputs($out, "Кол-во секунд; Сообщений в секунду; Всего сообщений\n");

        $lastTime = null;
        $accuTime = 0.0;
        $accuCount = 0.0;
        $secCount = 0;
        $totalCount = 0;

        $minCountPerSec = null;
        $maxCountPerSec = 0;

        while(!feof($fp)) {
            $s = trim(fgets($fp));
            $stringCount++;

            if ($stringCount < 2400) {
                continue;
            }

            if (!$s) {
                echo 'empty string #' . $stringCount . "\n";
                continue;
            }

            $matches = [];

            if (strpos($s, 'router:') !== false) {
                preg_match('/time=(?P<time>.+);.+doc_id=(?P<doc_id>.+),.+doc_type=(?P<doc_type>.+)/', $s, $matches);

                if (!isset($matches['doc_id'])) {
                    var_dump($stringCount);
                    var_dump($s);
                    var_dump($matches);
                    continue;
                }
                //var_dump($matches);

                if ($matches['doc_type'] == 'CFTAck') {
                    continue;
                }

                $doc_id = $matches['doc_id'];
                list($datetime, $fraction) = explode('.', $matches['time']);

                $microtime = floatval(strtotime($datetime) . '.' . $fraction);
                $buffer[$doc_id]['start'] = $microtime;

                if (is_null($startTime)) {
                    $startTime = $microtime;
                }

                if (is_null($lastTime)) {
                    $lastTime = $microtime;
                }

                $accuTime += ($microtime - $lastTime);
                $accuCount += 0.5;
                $totalCount += 0.5;
                if ($accuTime > 1.0) {
                    $accuTime -= 1.0;
                    $secCount++;
                    fputs($out, $secCount . '; ' . $accuCount . '; ' . $totalCount . "\n");

                    if (is_null($minCountPerSec) || $minCountPerSec > $accuCount) {
                        $minCountPerSec = $accuCount;
                    }
                    if ($maxCountPerSec < $accuCount) {
                        $maxCountPerSec = $accuCount;
                    }

                    $accuCount = 0;
                    while($accuTime > 1.0) {
                        $accuTime -= 1.0;
                        $secCount++;
                        fputs($out, $secCount . '; ' . $accuCount . '; ' . $totalCount . "\n");
                    }
                }

                $lastTime = $microtime;

                //echo $stringCount . " " . $microtime . "\n";

            } else if (strpos($s, 'forwarder:') !== false) {

//Jun  1 15:35:41 cyberswift-p72 cyberft-dev:[16183]: forwarder: [1] <info> Process (TESTRUM@A76300000_01464784540.0236): Successfully sent; time=2016-06-01T15:35:41.470549
                preg_match('/\((?P<doc_id>.+)\).+time=(?P<time>.+)/', $s, $matches);

                if (!isset($matches['doc_id'])) {
                    var_dump($stringCount);
                    var_dump($s);
                    var_dump($matches);
                    continue;
                }

                $doc_id = $matches['doc_id'];
                list($datetime, $fraction) = explode('.', $matches['time']);
                $microtime = floatval(strtotime($datetime) . '.' . $fraction);
                $buffer[$doc_id]['end'] = $microtime;

                if ($microtime > $endTime) {
                    $endTime = $microtime;
                }

                if (is_null($lastTime)) {
                    $lastTime = $microtime;
                }

                $accuTime += ($microtime - $lastTime);
                $accuCount += 0.5;
                $totalCount += 0.5;
                if ($accuTime > 1.0) {
                    $accuTime -= 1.0;
                    $secCount++;
                    fputs($out, $secCount . '; ' . $accuCount . '; ' . $totalCount . "\n");
                    if (is_null($minCountPerSec) || $minCountPerSec > $accuCount) {
                        $minCountPerSec = $accuCount;
                    }
                    if ($maxCountPerSec < $accuCount) {
                        $maxCountPerSec = $accuCount;
                    }
                    $accuCount = 0;
                    while($accuTime > 1.0) {
                        $accuTime -= 1.0;
                        $secCount++;
                        fputs($out, $secCount . '; ' . $accuCount . '; ' . $totalCount . "\n");
                    }
                }

                $lastTime = $microtime;
            }

        }

        if ($accuCount > 0) {
            $secCount++;
            $totalCount = count($buffer);
            if ($minCountPerSec > $accuCount) {
                $minCountPerSec = $accuCount;
            }
            if ($maxCountPerSec < $accuCount) {
                $maxCountPerSec = $accuCount;
            }

            fputs($out, $secCount . '; ' . $accuCount . '; ' . $totalCount . "\n");
        }

        fclose($fp);

        $max = 0;
        $min = null;
        $total = 0;
        $count = 0;
        $maxDocId = null;

        $groups = [
            '< 0.1' => 0, '0.1 ... 0.5' => 0, '0.5 ... 0.75' => 0, '0.75 ... 1' => 0,
            '1 ... 1.5' => 0, '1.5 ... 2' => 0, '2 ... 3' => 0, '3 ... 4' => 0, '> 4' => 0
        ];

        foreach($buffer as $doc_id => $entry) {
            if (!isset($entry['start']) || !isset($entry['end'])) {
                echo $doc_id . "\n";
                var_dump($entry);
                continue;
            }

            $amount = ($entry['end'] - $entry['start']);

            if ($amount < 0.1) {
                $groups['< 0.1']++;
            } else if ($amount < 0.5) {
                $groups['0.1 ... 0.5']++;
            } else if ($amount < 0.75) {
                $groups['0.5 ... 0.75']++;
            } else if ($amount < 1) {
                $groups['0.75 ... 1']++;
            } else if ($amount < 1.5) {
                $groups['1 ... 1.5']++;
            } else if ($amount < 2) {
                $groups['1.5 ... 2']++;
            } else if ($amount < 3) {
                $groups['2 ... 3']++;
            } else if ($amount < 4) {
                $groups['3 ... 4']++;
            } else {
                $groups['> 4']++;
            }

            //echo $doc_id . ' ' . $entry['start'] . ' - ' . $entry['end'] . ' = ' . $amount . "\n";
            $total += $amount;
            if ($amount > $max) {
                $max = $amount;
                $maxDocId = $doc_id;
            }

            if (is_null($min) || $min > $amount) {
                $min = $amount;
            }
            $count++;
        }

        if ($accuCount > 0) {
            $secCount++;
            fputs($out, $secCount . '; ' . $accuCount . '; ' . $totalCount . "\n");
        }

        fclose($out);

        echo "\n\nmin count per sec: " . $minCountPerSec . " max count per sec: " . $maxCountPerSec . "\n";
        echo "message count: " . $count . "\nmin time: " . $min . "\nmax time: " . $max . "\naverage: " . ($total / $count) . "\n\n";

        foreach($groups as $group => $cnt) {
            $pacento = round($cnt / $count * 100, 1);
            echo str_pad($group, 12, ' ', STR_PAD_RIGHT) . ": " . str_pad($cnt, 8, ' ', STR_PAD_RIGHT)
                    . str_pad($pacento . '%', 7, ' ')
                    . str_repeat('*', (int) $pacento)
                    . "\n";
        }

        echo "\n";

        echo 'startTime: ' . $startTime . ' endTime: ' . $endTime . ', time spent: ' . ($endTime - $startTime) . ' s ('
                . (($endTime - $startTime) / 60) . " m)\n";

        echo "\n";

    }

    public function actionParse3($file)
    {
//Jun  1 15:35:41 cyberswift-p72 cyberft-dev:[16161]: router: [1] <info> In: size=1834; time=2016-06-01T15:35:41.431110; Broker headers: source=KOVALKO@XDEV, source-ip=127.0.0.1, sender_id=KOVALKO@XDEV, doc_id=KOVALKO@XDEV00003_001464784540.275, doc_type=MT999

        $divisor = 1000;

        $fp = fopen($file, 'r');
        $stringCount = 0;

        $startTime = 999999;
        $endTime = 0;

        $out = fopen('out.csv', 'w');
        fputs($out, "Кол-во секунд; Сообщений в секунду; Всего сообщений\n");

        $accuTime = 0.0;
        $accuCount = 0.0;
        $secCount = 0;
        $totalCount = 0;

        $minCountPerSec = null;
        $maxCountPerSec = 0;
        $ackTimeErrorCount = 0;

        $totalTime = 0;

        fgets($fp);

        while(!feof($fp)) {
            $s = trim(fgets($fp));

            echo $s . "\n";
            //continue;

            $stringCount++;

            if (!$s) {
                echo 'empty string #' . $stringCount . "\n";
                continue;
            }

            $parts = explode(';', $s);

            if (count($parts) < 4) {
                echo $s . "\n";
                continue;
            }

            $sendTime = intval($parts[1]);
            $ackTime = intval($parts[3]);
            if ($startTime > $sendTime) {
                $startTime = $sendTime;
            }
//echo 'line: ' . $stringCount . ' sendTime: ' . $sendTime . ' ackTime: ' . $ackTime . ' diff: ' . ($ackTime - $sendTime) . "\n";
            if ($endTime < $ackTime) {
                $endTime = $ackTime;
            }
if ($ackTime < $sendTime) {
    $ackTimeErrorCount++;
    continue;
    //die('wrong time!');
}
            $diff = $ackTime - $sendTime;
            $accuTime += $diff;
            $totalTime += $diff;
            $accuCount++;
            $totalCount++;
            if ($accuTime > $divisor) {
                $accuTime -= $divisor;
                $secCount++;
                //fputs($out, $secCount . '; ' . $accuCount . '; ' . $totalCount . "\n");

                if (is_null($minCountPerSec) || $minCountPerSec > $accuCount) {
                    $minCountPerSec = $accuCount;
                }
                if ($maxCountPerSec < $accuCount) {
                    $maxCountPerSec = $accuCount;
                }

                $accuCount = 0;
                while($accuTime > $divisor) {
                    //echo 'line: ' . $stringCount . ' extended ' . $accuTime . "\n";
                    $accuTime -= $divisor;
                    $secCount++;
                    //fputs($out, $secCount . '; ' . $accuCount . '; ' . $totalCount . "\n");
                }
            }
        }

        if ($accuCount > 0) {
            $secCount++;
            if ($minCountPerSec > $accuCount) {
                $minCountPerSec = $accuCount;
            }
            if ($maxCountPerSec < $accuCount) {
                $maxCountPerSec = $accuCount;
            }

            fputs($out, $secCount . '; ' . $accuCount . '; ' . $totalCount . "\n");
        }

        fclose($fp);

        echo "\n\ntotalTime: " . ($totalTime / $divisor) . "s, min count per sec: " . $minCountPerSec . " max count per sec: " . $maxCountPerSec . "\n";
        echo "message count: " . $totalCount . "\n\n";


        echo 'startTime: ' . $startTime . ' endTime: ' . $endTime . ', time spent: ' . (($endTime - $startTime) / $divisor) . ' s ('
                . (($endTime - $startTime) / $divisor / 60) . " m)\n";
        echo "errorCount: " . $ackTimeErrorCount . "\n";
        echo "\n";
    }

    public function actionParse4($file)
    {
//Jun  1 15:35:41 cyberswift-p72 cyberft-dev:[16161]: router: [1] <info> In: size=1834; time=2016-06-01T15:35:41.431110; Broker headers: source=KOVALKO@XDEV, source-ip=127.0.0.1, sender_id=KOVALKO@XDEV, doc_id=KOVALKO@XDEV00003_001464784540.275, doc_type=MT999

        $divisor = 1000;

        $fp = fopen($file, 'r');
        $stringCount = 0;

        $startTime = 999999;
        $endTime = 0;

        $out = fopen('out.csv', 'w');
        fputs($out, "Кол-во секунд; Сообщений в секунду; Всего сообщений\n");

        $accuTime = 0.0;
        $accuCount = 0.0;
        $secCount = 0;
        $totalCount = 0;

        $minCountPerSec = null;
        $maxCountPerSec = 0;

        $minTime = null;
        $maxTime = null;
        $ackTimeErrorCount = 0;

        $groups = [
            '< 0.1' => 0, '0.1 ... 0.5' => 0, '0.5 ... 0.75' => 0, '0.75 ... 1' => 0,
            '1 ... 1.5' => 0, '1.5 ... 2' => 0, '2 ... 3' => 0, '3 ... 4' => 0, '> 4' => 0
        ];

        $totalTime = 0;

        $seconds = [];

        fgets($fp);

        while(!feof($fp)) {
            $s = trim(fgets($fp));

            //echo $s . "\n";
            //continue;

            $stringCount++;

            if (!$s) {
                echo 'empty string #' . $stringCount . "\n";
                continue;
            }

            $parts = explode(';', $s);

            if (count($parts) < 4) {
                echo $s . "\n";
                continue;
            }

            $sendTime = intval($parts[1]);
            $ackTime = intval($parts[3]);
            if ($startTime > $sendTime) {
                $startTime = $sendTime;
            }
//echo 'line: ' . $stringCount . ' sendTime: ' . $sendTime . ' ackTime: ' . $ackTime . ' diff: ' . ($ackTime - $sendTime) . "\n";
            if ($endTime < $ackTime) {
                $endTime = $ackTime;
            }

if ($ackTime < $sendTime) {
    $ackTimeErrorCount++;
    continue;
    //die('wrong time!');
}
            $second = floor($sendTime / $divisor);
            if (!isset($seconds[$second])) {
                $seconds[$second] = 0.5;
            } else {
                $seconds[$second] += 0.5;
            }

            $second = floor($ackTime / $divisor);
            if (!isset($seconds[$second])) {
                $seconds[$second] = 0.5;
            } else {
                $seconds[$second] += 0.5;
            }

            $diff = $ackTime - $sendTime;

            if (is_null($minTime) || $minTime > $diff) {
                $minTime = $diff;
            }

            if ($maxTime < $diff) {
                $maxTime = $diff;
            }

            if ($diff < 100) {
                $groups['< 0.1']++;
            } else if ($diff < 500) {
                $groups['0.1 ... 0.5']++;
            } else if ($diff < 7500) {
                $groups['0.5 ... 0.75']++;
            } else if ($diff < 1000) {
                $groups['0.75 ... 1']++;
            } else if ($diff < 1500) {
                $groups['1 ... 1.5']++;
            } else if ($diff < 2000) {
                $groups['1.5 ... 2']++;
            } else if ($diff < 3000) {
                $groups['2 ... 3']++;
            } else if ($diff < 4000) {
                $groups['3 ... 4']++;
            } else {
                $groups['> 4']++;
            }

            $accuTime += $diff;
            $totalTime += $diff;
            $accuCount++;
            $totalCount++;
            if ($accuTime > $divisor) {
                $accuTime -= $divisor;
                $secCount++;
                //fputs($out, $secCount . '; ' . $accuCount . '; ' . $totalCount . "\n");

                if (is_null($minCountPerSec) || $minCountPerSec > $accuCount) {
                    $minCountPerSec = $accuCount;
                }
                if ($maxCountPerSec < $accuCount) {
                    $maxCountPerSec = $accuCount;
                }

                $accuCount = 0;
                while($accuTime > $divisor) {
                    //echo 'line: ' . $stringCount . ' extended ' . $accuTime . "\n";
                    $accuTime -= $divisor;
                    $secCount++;
                    //fputs($out, $secCount . '; ' . $accuCount . '; ' . $totalCount . "\n");
                }
            }
        }

        if ($accuCount > 0) {
            $secCount++;
            if ($minCountPerSec > $accuCount) {
                $minCountPerSec = $accuCount;
            }
            if ($maxCountPerSec < $accuCount) {
                $maxCountPerSec = $accuCount;
            }

            fputs($out, $secCount . '; ' . $accuCount . '; ' . $totalCount . "\n");
        }

        fclose($fp);


        echo "\n\ntotalTime: " . ($totalTime / $divisor) . "s, min count per sec: " . $minCountPerSec . " max count per sec: " . $maxCountPerSec . "\n";
        echo "min time per message: " . $minTime . " max time per message: " . $maxTime . "\n";
        echo "message count: " . $totalCount . "\n\n";


        echo 'startTime: ' . $startTime . ' endTime: ' . $endTime . ', time spent: ' . (($endTime - $startTime) / $divisor) . ' s ('
                . (($endTime - $startTime) / $divisor / 60) . " m)\n";

        echo "\n";

        foreach($groups as $group => $cnt) {
            $pacento = round($cnt / $stringCount * 100, 1);
            echo str_pad($group, 12, ' ', STR_PAD_RIGHT) . ": " . str_pad($cnt, 8, ' ', STR_PAD_RIGHT)
                    . str_pad($pacento . '%', 7, ' ')
                    . str_repeat('*', (int) $pacento)
                    . "\n";
        }


        ksort($seconds);

        $totalCount = 0;

        $prevKey = null;
        $minCountPerSec = null;
        $maxCountPerSec = 0;

        foreach($seconds as $key => $value) {

            if (!is_null($prevKey)) {
                while($prevKey < $key - 1) {
                    $prevKey++;
                    fputs($out, $prevKey . '; ' . 0 . '; ' . $totalCount . "\n");
                }
            }

            $prevKey = $key;

            if (is_null($minCountPerSec) || $minCountPerSec > $value) {
                $minCountPerSec = $value;
            }

            if ($maxCountPerSec < $value) {
                $maxCountPerSec = $value;
            }

            $totalCount += $value;
            fputs($out, $key . '; ' . $value . '; ' . $totalCount . "\n");
        }

        echo "totalCount: " . $totalCount . " minCountPerSec: " . $minCountPerSec . " maxCountPerSec: " . $maxCountPerSec . "\n";
        echo "average per sec: " . ($totalCount / count($seconds)) . "\n";
        echo "average time per message: " . (count($seconds) / $totalCount) . "\n";
        echo "errorCount: " . $ackTimeErrorCount . "\n";
        fclose($out);
    }


}
