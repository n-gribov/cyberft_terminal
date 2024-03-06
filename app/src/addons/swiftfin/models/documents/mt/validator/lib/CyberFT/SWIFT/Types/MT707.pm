package CyberFT::SWIFT::Types::MT707;
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
            key => '59',
            required => 1,
        },

    ],
    
    rules => [
        # C1   Если в сообщении присутствует поле 32В или поле 33B, то обязательно должно
        # присутствовать также и поле 34В (Код ошибки С12).
        {
            func => sub {
                my $doc = shift;
                if($doc->key_exists('32B') or $doc->key_exists('33B')){
                    if( not $doc->key_exists('34B')){
                        return 0;
                    }
                }
                return 1;
            },
            err => 'C12',
        },
        # C2 Если в сообщении присутствует поле 34В, должно также присутствовать либо поле 32В,
        # либо поле 33B (Код ошибки С12).
        {
            func => sub {
                my $doc = shift;
                if($doc->key_exists('34B') ){
                    if( $doc->key_exists('32B') and $doc->key_exists('33B')){
                        return 0;
                    }
                    if((not $doc->key_exists('32B')) and (not $doc->key_exists('33B')))
                    {
                        return 0;
                    }
                }
                return 1;
            },
            err => 'C12',
        },
        # C3 Если в сообщении присутствует поле 23, то обязательно должно присутствовать также
        # и поле 52а (Код ошибки С16)
        {
            func => sub {
                my $doc = shift;
                if($doc->key_exists('23') ){
                    if(not $doc->key_exists('52[AD]')){
                        return 0;
                    }
                }
                return 1;
            },
            err => 'C16',
        },        
        # C4 В сообщении могут присутствовать либо поле 39A, либо поле 39B, но не оба эти поля
        # вместе (Код ошибки D05).
        {
            func => sub {
                my $doc = shift;
                if($doc->key_exists('39A') and $doc->key_exists('39B')){
                    return 0;
                }
                return 1;
            },
            err => 'D05',
        }, 
        # C5 В сообщении могут присутствовать либо поле 44C, либо поле 44D, но не оба эти поля
        # вместе (Код ошибки D06)
        {
            func => sub {
                my $doc = shift;
                if($doc->key_exists('44C') and $doc->key_exists('44D')){
                    return 0;
                }
                return 1;
            },
            err => 'D06',
        },        
        # C6 Должно быть использовано хотя бы одно из следующих полей: 31Е, 32В, 33В, 34В, 39А,
        # 39С, 44А, 44В, 44С, 44D, 79 или 72 (Код ошибки C30).
        {
            func => sub {
                my $doc = shift;
                if(    (not $doc->key_exists('31E')  )
                   and (not  $doc->key_exists('32B') )
                   and (not  $doc->key_exists('33B') )
                   and (not  $doc->key_exists('34B') )
                   and (not  $doc->key_exists('39A') )
                   and (not  $doc->key_exists('39C') )
                   and (not  $doc->key_exists('44A') )
                   and (not  $doc->key_exists('44B') )
                   and (not  $doc->key_exists('44C') )
                   and (not  $doc->key_exists('44D') )
                   and (not  $doc->key_exists('79') )
                   and (not  $doc->key_exists('72') )

                    ){
                    return 0;
                }
                return 1;
            },
            err => 'C30',
        },            
        # C7 Код валюты в полях сумм 32В, 33В и 34В должен быть одинаковым (Код ошибки С02)
        {
            func => sub {
                my $doc = shift;
                my $vs = seq_get_all($doc->data, '32B|33B|34B');
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