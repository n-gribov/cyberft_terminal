package CyberFT::SWIFT::Types::MT608;
use CyberFT::SWIFT::Types::Utils;
use strict;
our $ValidationProfile = {
    
    fields => [
        {   
            key => '20',
            required => 1,
        },
        {   
            key => '26C',
            required => 1,
        },
        {   
            key => '28',
            required => 1,
        },
        {   
            key => '60a',
            key_regexp => '60[FM]',
            required => 1,
        },
        {   
            key => '62a',
            key_regexp => '62[FM]',
            required => 1,
        },

    ],
    
    rules => [
            # There are no network validated rules for this message type
            #С1 - игнорировать - Михаил Гудов
    ],  
};

1;