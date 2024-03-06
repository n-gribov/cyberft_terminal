package CyberFT::SWIFT::Types::MT910;
use CyberFT::SWIFT::Types::Utils;
use strict;

our $ValidationProfile = {

    fields => [
        {
            key => '20',
            required => 1,
        },
        {
            key => '21',
            required => 1,
        },
        {
            key => '25',
            required => 1,
        },
        {
            key => '32A',
            required => 1,
        },
        {
            key => '50a',
            key_regexp => '50[AKF]',
            required => 0,
        },
        {
            key => '52a',
            key_regexp => '52[AD]',
            required => 0,
        },
        {
            key => '56a',
            key_regexp => '56[AD]',
            required => 0,
        },
        {
            key => '72',
            required => 0,
        },
    ],

    rules => [
        # В сообщении должно присутствовать или поле 50а, или поле 52а, но не оба эти поля вместе
        # (Код ошибки С06):
        #   Если поле 50а ...    то поле 52а ...
        #   Присутствует         Не используется
        #   Отсутствует          Обязательное
        {
            if   => ['exists', '50[AKF]'],
            must => ['not_exists', '52[AD]'],
            err  => 'C06',
        },
        {
            if   => ['not_exists', '50[AKF]'],
            must => ['exists', '52[AD]'],
            err  => 'C06',
        },
    ],

};

1;