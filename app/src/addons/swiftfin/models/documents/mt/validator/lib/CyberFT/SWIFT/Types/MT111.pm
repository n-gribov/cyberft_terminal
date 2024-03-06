package CyberFT::SWIFT::Types::MT111;
use CyberFT::SWIFT::Types::Utils;
use strict;
use Data::Dumper;

our $ValidationProfile = {

    fields => [
        # Описаны только обязательные поля обязательных последовательностей.
        {
            key => '20',
            required => 1,
        },
        {
            key => '21',
            required => 1,
        },
        {
            key => '30',
            required => 1,
        },
        {
            key => '32a',
            key_regexp => '32[AB]',
            required => 1,
        },
    ],

};

1;