package CyberFT::SWIFT::Types::MT400;
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
            key => '32a',
            key_regexp => '32[ABK]',
            required => 1,
        },
        {   
            key => '33A',
            required => 1,
        },
    ],
    
    rules => [
        #C1 - Поле 57а может присутствовать только если в сообщении присутствуют поля 53а и
        # 54а. (Код ошибки С11)
        {
            func => sub {
                my $doc = shift;
                if((!$doc->key_exists('53[ABD]') || !$doc->key_exists('54[ABD]')) && $doc->key_exists('57[AD]')){
                    return 0;
                }
                return 1;
            },
            err => 'C11',
        },
        # C2
        # Код валюты в полях сумм 32а и 33А должен быть одинаковым (Код ошибки С02).
        {
            func => sub {
                my $doc = shift;
                my $vs = seq_get_all($doc->data, '32A|32B|32K|33A');
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
        if ($k =~ /^23$/) {
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