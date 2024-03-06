package CyberFT::SWIFT::Types::MT767;
use CyberFT::SWIFT::Types::Utils;
use strict;
our $ValidationProfile = {
    
    fields => [
        {   
            key => '27',
            required => 1,
        },
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
            key => '31C',
            required => 1,
        },     
        {   
            key => '77C',
            required => 1,
        },        
    ],
    
    rules => [

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