package CyberFT::SWIFT::Types::MT750;
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
            key => '32B',
            required => 1,
        },
        {   
            key => '77J',
            required => 1,
        },
    ],
    
    rules => [
        # C1 ���� � ��������� ������������ ���� 33� �/��� ���� 71� �/��� ���� 73, �����������
        # ������ �������������� ����� � ���� 34� (��� ������ �13)
        {
            func => sub {
                my $doc = shift;
                if($doc->key_exists('33B') or  $doc->key_exists('71B') or  $doc->key_exists('73') ){
                    if (not $doc->key_exists('34B')){
                        return 0;
                    }
                }
                return 1;
            },
            err => 'C13',
        },
        # C1 ��� ������ � ����� ���� 32� � 34� ������ ���� ���������� (��� ������ �02)
        {
            func => sub {
                my $doc = shift;
                my $vs = seq_get_all($doc->data, '32B|34B');
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