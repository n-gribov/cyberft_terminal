package CyberFT::SWIFT::Types::MT601;
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
            key => '22',
            required => 1,
        },
        {   
            key => '82a',
            key_regexp => '82[ADJ]',
            required => 1,
        },
        {   
            key => '87a',
            key_regexp => '87[ADJ]',
            required => 1,
        },
        {   
            key => '23',
            required => 1,
        },
        {   
            key => '30',
            required => 1,
        },
        {   
            key => '26C',
            required => 1,
        },
        {   
            key => '31G',
            required => 1,
        },
        {   
            key => '32F',
            required => 1,
        },
        {   
            key => '32B',
            required => 1,
        },
        {   
            key => '33B',
            required => 1,
        },
        {   
            key => '34a',
            key_regexp => '34[PR]',
            required => 1,
        },
        {   
            key => '57a',
            key_regexp => '57[ABD]',
            required => 1,
        },
    ],
    
    rules => [
        
        #C1 - Field 53a may only be present if field 34P is used (Error code(s): C20).
        #    Поле 53a имеет право присутствовать, только если есть 34P - если такого поля нет, а есть 53a - то C20
        {
            func => sub {
                my $doc = shift;
                if((not $doc->key_exists('34P')) and $doc->key_exists('53[ABD]')){
                    return 0;
                }
                return 1;
            },
            err => 'C20',
        },
        # C2
        # Field 31C may only be present in the case of an American style option (ie, subfield 3 of field 23 contains ’A’) (Error code(s): C79).
        # Поле 31C может присутствовать только в тех случаях, когда в поле 23 указан опцион 
        # американского типа (Код ошибки C79).
        {
            func => sub {
                my $doc = shift;
                my ($code) = ($doc->get_first('23') =~ m`^\S+?/\S+?/(\S)/`);
                if ($code ne 'A') {
                    if ($doc->key_exists('31C')) {
                        return 0;
                    }
                }
                return 1;
            },
            err => 'C79',
        },
        # C3
        # The currency in the amount fields 32B and 34a must be the same (Error code(s): C02)
        # message (Error code(s): C02).
        {
            func => sub {
                my $doc = shift;
                my $vs = seq_get_all($doc->data, '32B|34P|34R');
                my $curr;
                for my $v (@$vs) {
                    my ($c) = $v =~ /([A-Z]{3})\d+/;
                    if (!$curr) {
                        $curr = $c;
                    } 
                    elsif ($curr ne $c) {
                        return 0;
                    }
                }
                return 1;
            },
            err => 'C02',
        },        
    ],
};

1;