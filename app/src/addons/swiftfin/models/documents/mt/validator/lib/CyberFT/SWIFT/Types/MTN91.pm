package CyberFT::SWIFT::Types::MTN91;
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
            key => '32B',
            required => 1,
        },
        {
            key => '52a',
            key_regexp => '52[AD]',
            required => 0,
        },
        {
            key => '57a',
            key_regexp => '57[ABD]',
            required => 0,
        },
        {
            key => '71B',
            required => 1,
        },
        {
            key => '72',
            required => 0,
        },
    ],

};

1;