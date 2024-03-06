<?php

namespace addons\VTB\models;


class VTBDocumentStatus
{
    const STATUSES = [
        '0' => [
            'name'        => 'Ожидает обработки',
            'description' => 'Документ ожидает обработки',
            'isFinal'     => false,
        ],
        '13023' => [
            'name'        => 'ЭП Не верна',
            'description' => 'Ошибка проверки подписи',
            'isFinal'     => true,
        ],
        '15003' => [
            'name'        => 'Принят',
            'description' => 'Документ принят СДБО (проверена подпись и прошел контроль в СДБО)',
            'isFinal'     => false,
        ],
        '15013' => [
            'name'        => 'Не принят',
            'description' => 'Документ не принят банком (отказан операционистом)',
            'isFinal'     => true,
        ],
        '15033' => [
            'name'        => 'Ошибка реквизитов',
            'description' => 'Не пройден контроль в СДБО',
            'isFinal'     => true,
        ],
        '15063' => [
            'name'        => 'Ожидает визирования',
            'description' => 'Документ ожидает подписи визирующей подписью',
            'isFinal'     => false,
        ],
        '17183' => [
            'name'        => 'Принят ВК',
            'description' => 'Документ принят валютным контролем Банка',
            'isFinal'     => false,
        ],
        '17193' => [
            'name'        => 'Отказан ВК',
            'description' => 'Документ отказана валютным контролем Банка',
            'isFinal'     => true,
        ],
        '17203' => [
            'name'        => 'Закрыт',
            'description' => 'Документ закрыт',
            'isFinal'     => true,
        ],
        '17013' => [
            'name'        => 'Принят Банком',
            'description' => 'Документ принят АБС Банка',
            'isFinal'     => false,
        ],
        '17023' => [
            'name'        => 'Ошибка реквизитов после АБС',
            'description' => 'Не пройден контроль в АБС Банка',
            'isFinal'     => true,
        ],
        '17033' => [
            'name'        => 'Отложен',
            'description' => 'Документ отложен',
            'isFinal'     => false,
        ],
        '17043' => [
            'name'        => 'Исполнен',
            'description' => 'Документ Исполнен',
            'isFinal'     => true,
        ],
        '17063' => [
            'name'        => 'Отказан АБС',
            'description' => 'Документ отказан АБС Банка',
            'isFinal'     => true,
        ],
        '17083' => [
            'name'        => 'Не принят банком',
            'description' => 'Документ не принят банком',
            'isFinal'     => true,
        ],
        '18013' => [
            'name'        => 'Обработан',
            'description' => 'Документ обработан',
            'isFinal'     => true,
        ],
        '19003' => [
            'name'        => 'Отозван',
            'description' => 'Документ отозван',
            'isFinal'     => true,
        ],
        '19013' => [
            'name'        => 'Сторнирован',
            'description' => 'Документ Сторнирован',
            'isFinal'     => true,
        ],
        '19023' => [
            'name'        => 'Картотека',
            'description' => 'Документ поставлен на картотеку',
            'isFinal'     => false,
        ],
        '16013' => [
            'name'        => 'Доставлен в РЦК',
            'description' => 'Документ доставлен в РЦК',
            'isFinal'     => false,
        ],
        '16023' => [
            'name'        => 'Не принят РЦК',
            'description' => 'Документ не принят в РЦК',
            'isFinal'     => true,
        ],
        '16033' => [
            'name'        => 'Обрабатывается РЦК',
            'description' => 'Документ на обработке в РЦК',
            'isFinal'     => false,
        ],
        '16043' => [
            'name'        => 'Акцептован РЦК',
            'description' => 'Документ Акцептован',
            'isFinal'     => true,
        ],
        '16063' => [
            'name'        => 'Отказан РЦК',
            'description' => 'Документ отказан РЦК',
            'isFinal'     => true,
        ],
    ];

    private $code;
    private $name;
    private $description;
    private $isFinal;
    private $isError;

    public function __construct($code)
    {
        if (!static::isValidStatusCode($code)) {
            throw new \Exception("Unknown status '$code'");
        }

        $status = static::STATUSES[$code];
        foreach ($status as $property => $value) {
            $this->$property = $value;
        }
        $this->code = $code;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function isFinal()
    {
        return $this->isFinal;
    }

    public function isError()
    {
        return $this->isError;
    }

    public static function isValidStatusCode($code)
    {
        return array_key_exists($code, static::STATUSES);
    }
}
