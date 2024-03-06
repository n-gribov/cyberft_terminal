package CyberFT::SWIFT::Types::MT200;
use CyberFT::SWIFT::Types::Utils;
use strict;

our $ValidationProfile = {

    fields => [
        {
            key => '20',
            required => 1,
        },
        {
            key => '32A',
            required => 1,
        },
        {
            key => '53B',
            required => 0,
        },
        {
            key => '56a',
            key_regexp => '56[AD]',
            required => 0,
        },
        {
            key => '57a',
            key_regexp => '57[ABD]',
            required => 1,
        },
        {
            key => '72',
            required => 0,
        },
    ],

};

1;