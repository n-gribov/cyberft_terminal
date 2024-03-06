package CyberFT::SWIFT::Types::MT105;
use CyberFT::SWIFT::Types::Utils;
use strict;
use Data::Dumper;

our $ValidationProfile = {

    fields => [
        # Описаны только обязательные поля обязательных последовательностей.
        {
            key => '27',
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
            key => '12',
            required => 1,
        },
        {
            key => '77F',
            required => 1,
        },
    ],

    rules => [
        # Для этого типа сообщения не предусмотрено правил, которые должны проверяться сетью.
    ]

};

1;