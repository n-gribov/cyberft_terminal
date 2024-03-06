package CyberFT::SWIFT::Types::MT455;
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
        {   
            key => '32A',
            required => 1,
        },
        {   
            key => '33a',
            key_regexp => '33[CD]',
            required => 1,
        },
        {   
            key => '77A',
            required => 1,
        },

    ],
    
    rules => [
		# С2
        # Код валюты в полях сумм 32A и 33а должен быть одинаковым во всех повторениях этих полей в сообщении
        # (Код ошибки С02).
        {
            func => sub {
                my $doc = shift;
                my $vs = seq_get_all($doc->data, '32A|33C|33D');
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