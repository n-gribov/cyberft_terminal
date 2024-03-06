package CyberFT::SWIFT::Types::MT579;
use CyberFT::SWIFT::Types::Utils;
use strict;
use Data::Dumper;

our $ValidationProfile = {

    fields => [
        # Описаны только обязательные поля обязательных последовательностей.
        {
            key => '28',
            required => 1,
        },
        {
            key => '20',
            required => 1,
        },
        {
            key => '21',
            required => 1,
        },
        {
            key => '35F',
            required => 1,
        },
    ],

    rules => [
        # Для этого типа сообщений нет проверяемых сетью правил.
    ]

};

1;