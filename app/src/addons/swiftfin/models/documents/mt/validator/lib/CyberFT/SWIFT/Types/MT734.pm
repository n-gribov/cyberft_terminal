package CyberFT::SWIFT::Types::MT734;
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
            key => '32A',
            required => 1,
        },
        {   
            key => '77J',
            required => 1,
        },
        {   
            key => '77B',
            required => 1,
        },

    ],
    
    rules => [
        # C1   Если в сообщении присутствует поле 73, обязательно должно присутствовать также и
        # поле 33а (Код ошибки С17).
        {
            func => sub {
                my $doc = shift;
                if($doc->key_exists('73') and  ( not $doc->key_exists('33[AB]') ) ){
                    return 0;
                }
                return 1;
            },
            err => 'C17',
        },
        # C2 Код валюты в полях сумм 32А и 33а должен быть одинаковым (Код ошибки С02).
        {
            func => sub {
                my $doc = shift;
                my $vs = seq_get_all($doc->data, '32A|33A|33B');
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
sub _find_sequences {
    my $data = shift;
    my $seqs = [];
    my $cur_seq = undef;
    my $cur_seq_code = undef;
    for my $item (@$data) {
        my $k = $item->{key};
        if ($k =~ /^20$/) {
            $cur_seq_code = $1;
            $cur_seq = [];
            push @{$seqs}, $cur_seq;
        }
        if ($cur_seq) {
            push @$cur_seq, $item;
        }
    }
    return $seqs;
}

1;