package CyberFT::SWIFT::Types::MT752;
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
            key => '23',
            required => 1,
        },
        {   
            key => '30',
            required => 1,
        },
    ],
    
    rules => [
        # C1 В тех случаях, когда поля 32В и 71В оба присутствуют в сообщении, в нем должно
        # присутствовать также и поле 33а (Код ошибки С18).
        {
            func => sub {
                my $doc = shift;
                if($doc->key_exists('32B') and  $doc->key_exists('71B')){
                    if (not $doc->key_exists('33[AB]')){
                        return 0;
                    }
                }
                return 1;
            },
            err => 'C18',
        },
        # C1 Код валюты в полях сумм 32В и 33а должен быть одинаковым (Код ошибки С02).
        {
            func => sub {
                my $doc = shift;
                my $vs = seq_get_all($doc->data, '32B|33A|33B');
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