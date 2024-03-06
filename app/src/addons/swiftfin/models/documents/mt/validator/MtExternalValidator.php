<?php
namespace addons\swiftfin\models\documents\mt\validator;

use yii\validators\Validator;
use Yii;

/**
 * Валидация МТ документа с помощью внешнего перлового компонента
 */
class MtExternalValidator extends Validator
{
	const VALIDATOR_PATH = '@addons/swiftfin/models/documents/mt/validator';

	public $type;

	/**
	 * Массив правил сети индексированный по коду ошибки
	 * на данный момент служит только для получения сообщения об ошибке
	 * @var array
	 */
	public $ruleMessages;

	public function validateAttribute($model, $attribute)
    {
		$template = <<<EOT
From: %s
To: %s
Content-Type: swift/%s
Priority: %s
Begin
%s
End
EOT;
		/**
		 * Подставляются тестовые данные отправителя-получателя,
		 * чтобы не замешивать здесь логику с родительскими объектами
		 * Dependency Inversion or Death muthafucka!
		 */
		$body     = $model->$attribute;
		$envelope = sprintf($template,
			Yii::$app->exchange->address,
			'TESTRUM3A001',
			$this->type,
            $model->owner->bankPriority,
			base64_encode($body)
		);

		$cwd = Yii::getAlias(self::VALIDATOR_PATH);

		$descriptorspec = [
			0 => ['pipe', 'r'],
			1 => ['pipe', 'w'],
		];

		$process = proc_open(
			"/usr/bin/perl {$cwd}/swift_validate.pl",
			$descriptorspec,
			$pipes,
			$cwd
		);

		$result = '';
		if (is_resource($process)) {
			fwrite($pipes[0], $envelope);
			fclose($pipes[0]);
			$result = trim(stream_get_contents($pipes[1]));
			fclose($pipes[1]);

			proc_close($process);
		}

		if (1 == $result[0]) {
			$lines = explode(PHP_EOL, $result);
			array_shift($lines);

			foreach ($lines as $line) {
				// Если пришел просто код ошибки
				if (
					preg_match('/^[A-Z]{1}[0-9]{2}$/', $line)
					&& !empty($this->ruleMessages) && isset($this->ruleMessages[$line])
				) {
					$this->addError(
                        $model,
                        $attribute,
                        Yii::t('app/swiftfin', 'Error code {errCode}', ['errCode' => $line])
                        . ': ' . $this->ruleMessages[$line]['message']
                    );

					continue;
				}

				$line    = explode(':', $line);
				$message = trim($line[0]);
				$tag     = '';
				if (!empty($line[1])) {
					$tag = trim($line[1]);
				}

				if (preg_match('/Character at position (\d+) is not allowed/', $message, $matches)) {
					$char  = mb_substr($body, $matches[1], 1);
					$split = str_split($body, --$matches[1]);
					$tag   = 'undefined';
					if ($split[0]) {
						preg_match('/(:([[:alnum:]]+):).*$/uim', strrev($split[0]), $tagMatches);
						$tag = strrev($tagMatches[2]);
					}

					$message = Yii::t('doc/mt', 'Character is not allowed');
				}
				$this->addError($model, $attribute, $message . (!empty($tag) ? " \":{$tag}:\"" : null));
			}

			return false;
		}

		return true;
	}

}