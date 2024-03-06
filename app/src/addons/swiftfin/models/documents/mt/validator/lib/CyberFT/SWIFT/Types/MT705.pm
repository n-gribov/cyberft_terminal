package CyberFT::SWIFT::Types::MT705;
use CyberFT::SWIFT::Types::Utils;
use strict;
our $ValidationProfile = {
    
    fields => [
        {   
            key => '40A',
            required => 1,
        },
        {   
            key => '20',
            required => 1,
        },
        {   
            key => '31D',
            required => 1,
        },
        {   
            key => '50',
            required => 1,
        },
        {   
            key => '59',
            required => 1,
        },
        {   
            key => '32B',
            required => 1,
        },

    ],
    
    rules => [
        # C1   В сообщении может присутствовать либо поле 39A, либо поле 39B, но не оба эти поля
        # вместе (Код ошибки D05)
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
        # C2   В сообщении может присутствовать либо поле 44C, либо поле 44D, но не оба эти поля
        # вместе (Код ошибки D06).
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