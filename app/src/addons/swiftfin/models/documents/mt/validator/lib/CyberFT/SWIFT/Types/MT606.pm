package CyberFT::SWIFT::Types::MT606;
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
            key => '26C',
            required => 1,
        },
        {   
            key => '30',
            required => 1,
        },
        {   
            key => '32F',
            required => 1,
        },
    ],
    
    rules => [
            # There are no network validated rules for this message type
    ],
};

1;