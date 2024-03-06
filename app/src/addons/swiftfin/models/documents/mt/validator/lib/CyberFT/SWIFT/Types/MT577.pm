package CyberFT::SWIFT::Types::MT577;
use CyberFT::SWIFT::Types::Utils;
use strict;
use Data::Dumper;

our $ValidationProfile = {

    fields => [
        {
            key => '28',
            required => 1,
        },
        {
            key => '20',
            required => 1,
        },
        {
            key => '67A',
            required => 1,
        },
        {
            key => '35B',
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