package CyberFT::SWIFT::Types::MT526;
use CyberFT::SWIFT::Types::Utils;
use strict;
use Storable ();
use Data::Dumper;

our $ValidationProfile = {

    fields => [
        # Описаны только обязательные поля обязательных последовательностей.
        {
            key => '20',
            required => 1,
        },
        {
            key => '23',
            required => 1,
        },
        {
            key => '35B',
            required => 1,
        },
    ],

    rules => [
        # Для этого типа сообщений не предусмотрено проверяемых сетью правил.
    ]
};

1;