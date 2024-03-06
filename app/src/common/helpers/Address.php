<?php
namespace common\helpers;

use common\components\TerminalId;
use common\models\Terminal;

/**
 * Класс-полузаглушка для помощи с исправлением адресов
 *
 */
class Address
{
	const PATCHABLE_ADDRESS_LENGTH = 11;
    const MIN_ADDRESS_LENGTH = 8;
	const DEFAULT_TERM_CODE_PATCH = 'X';

	/**
	 * Функция, оригинально происходящая из hotfix/CYB-1184 для замены адресов FileAct.
	 * Патчит 11-значный адрес путем вставки спецсимвола в 9-ю позицию.
	 * @param string $address
	 * @param type $patchCode
	 * @return string
	 */

	public static function fixAddress($address, $patchCode = false)
	{
		if ($patchCode === false) {
			$patchCode = static::DEFAULT_TERM_CODE_PATCH;
		}

        $length = strlen($address);
		if (static::PATCHABLE_ADDRESS_LENGTH === $length) {
			$address = substr($address, 0, 8) . $patchCode . substr($address, 8, 3);
		} else if (static::MIN_ADDRESS_LENGTH === $length) {
            $address .= 'XXX';
        }

		return $address;
	}

    public static function truncateAddress($address)
    {
        if (strlen($address) == 12) {
            return substr($address, 0, 8) . substr($address, 9);
        }

        return $address;
    }

    /**
     * Функция корректирует имя терминала отправителя для StatusReport'ов,
     * формируемых в ответ на входящий документ.
     * @do-not-try-to-understand-just-believe
     * Проблема: документ, адресованный конкретному терминалу участника, может
     * быть доставлен на любой из терминалов этого участника, не обязательно
     * на "тот самый".
     * Функция пытается найти в конфигурации терминала первое вхождение имени
     * терминала, принадлежащего тому же участнику, что и переданный ей иден-
     * тификатор терминала и возвращает его.
     * @param $senderTerminalId
     * @return mixed
     */
    public static function fixSender($senderTerminalId)
    {
        $fixedSenderTerminalId = self::fixAddress($senderTerminalId);

        $senderTerminalIdObject = TerminalId::extract($senderTerminalId);

        $existTerminalId = Terminal::getIdByAddress($fixedSenderTerminalId);

        if (!empty($senderTerminalIdObject) && empty($existTerminalId)) {

            $useExactMatch = ($senderTerminalId !== $senderTerminalIdObject->getBic());
            $participant = $useExactMatch
                    ? $senderTerminalIdObject->participantId
                    : $senderTerminalIdObject->getBic();

            $terminals = Terminal::find()
                    ->where(['status' => Terminal::STATUS_ACTIVE])
                    ->orderBy(['terminalId' => SORT_ASC])
                    ->all();

            foreach ($terminals as $terminal) {
                $terminalId = $terminal->terminalId;
                $terminalIdObject = TerminalId::extract($terminalId);

                $result = $useExactMatch
                    ? $participant == $terminalIdObject->participantId
                    : $participant == $terminalIdObject->getBic();

                if ($result) {
                    $fixedSenderTerminalId = $terminalId;
                    break;
                }
            }
        }

        return $fixedSenderTerminalId;
    }
}
