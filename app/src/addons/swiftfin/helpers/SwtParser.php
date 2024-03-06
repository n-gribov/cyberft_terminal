<?php

namespace addons\swiftfin\helpers;

use Yii;

class SwtParser {

	public static function getMapTags() 
	{
		return [
			'1' => ['label' => 'Basic header'],
			'2' => ['label' => 'Application header'],
			'3' => ['label' => 'User header'],
			'4' => ['label' => 'Content'],
			'5' => ['label' => 'Trailer'],
		];
	}

	
	/**
	 * Разбивает содержимое файла на сообщения и возвращает их данные
	 * @param type $filepath
	 * @return type
	 */
	public static function parse($filepath)
	{
		$input = file_get_contents($filepath);

		// разбиение пакета на сообщения по признаку начала собщения "{1:"
		$documents = preg_replace('/(\{\s*1\s*\:)/', '%message%${1}', $input);
		$documents = explode('%message%', $documents);
		array_shift($documents); // убрать пустой элемент
		// парсинг документов
		$result = [];
		foreach ($documents as $document) {
			$result[] = static::parseSwt($document);
		}


		return $result;
	}

	public static function parseSwt($input)
	{
		preg_match_all('/(\{([^{}]|(?R))*\})/s', $input, $matches); // разбиение сообщения на поля
		$fields = $matches[1];

		$message = [];
		// Формирование документа из полей сообщения
		foreach ($fields as $key => $field) {
			preg_match('/^\s*\{\s*([^\:]+)\:(.*)\}$/s', $field, $matches);
			$message[trim($matches[1])] = $matches[2];
		}

		$doc = [];

		//Обработка документа
		$h1 = $message[1];
		$h2 = !empty($message[2]) ? $message[2] : '';

		if (strlen($h1) == 25 && strlen($h2)) {
			$doc['type'] = 'MT' . substr($h2, 1, 3);

			/**
			 * Свифт принимает документы с меткой "I"
			 * То есть АБС отдает документ "I" на отправку, транспорт получает документ с меткой "O"
			 */
			if ($h2[0] == 'I') {

				$doc['direction'] = 'out';
				$doc['sender'] = substr($h1, 3, 12);
				$doc['recipient'] = substr($h2, 4, 12);
			} else if ($h2[0] == 'O') {
				$doc['direction'] = 'in';
				$doc['sender'] = substr($h2, 14, 12);
				$doc['recipient'] = substr($h1, 3, 12);
			}

			$doc['content'] = $message[4];
		}


		$doc['message'] = $message;


		return $doc;
	}

}
