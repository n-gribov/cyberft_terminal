package CyberFT::SWIFT::Types::MT768;
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
            key => '30',
            required => 1,
        },
    ],
    
    rules => [
        # C1 В сообщении может присутствовать либо поле 25, либо поле 57а, но не оба эти поля
        # вместе (Код ошибки С77).
        {
            func => sub {
                my $doc = shift;
                if($doc->key_exists('25') and  $doc->key_exists('57[ABD]')){
                    return 0;
                }
                return 1;
            },
            err => 'C77',
        },
        # C2 Если в сообщении присутствует поле 32D, то поле 57а не должно присутствовать (Код
        # ошибки С78)
        {
            func => sub {
                my $doc = shift;
                if($doc->key_exists('32D') and  $doc->key_exists('57[ABD]')){
                    return 0;
                }
                return 1;
            },
            err => 'C78',
        },
        # C3 Если в сообщении присутствует поле 71В, то обязательно должно присутствовать также
        # и поле 32а (Код ошибки С33).
        {
            func => sub {
                my $doc = shift;
                if($doc->key_exists('71B') and ( not $doc->key_exists('32[BD]') ) ){
                    return 0;
                }
                return 1;
            },
            err => 'C33',
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