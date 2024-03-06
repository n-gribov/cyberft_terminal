package CyberFT::SWIFT::Types::MT900;
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
            key => '52a',
            key_regexp => '52[AD]',
            required => 0,
        },
        {
            key => '72',
            required => 0,
        },
    ],

};

1;