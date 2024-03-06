package CyberFT::SWIFT::Types::MT950;
use CyberFT::SWIFT::Types::Utils;
use strict;

our $ValidationProfile = {

    fields => [
        {
            key => '20',
            required => 1,
        },
        {
            key => '25',
            required => 1,
        },
        {
            key => '28C',
            required => 1,
        },
        {
            key => '60a',
            key_regexp => '60[FM]',
            required => 1,
        },
        # -----> repetitive sequence starts
        {
            key => '61',
            required => 0,
        },
        # <----- repetitive sequence ends
        {
            key => '62a',
            key_regexp => '62[FM]',
            required => 1,
        },
        {
            key => '64',
            required => 0,
        },
    ],

};

1;