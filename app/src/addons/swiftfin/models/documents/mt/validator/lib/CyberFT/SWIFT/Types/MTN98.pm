package CyberFT::SWIFT::Types::MTN98;
use CyberFT::SWIFT::Types::Utils;
use strict;

our $ValidationProfile = {

    fields => [
        {
            key => '20',
            required => 1,
        },
        {
            key => '12',
            required => 1,
        },
        {
            key => '77E',
            required => 1,
        },
    ],

};

1;