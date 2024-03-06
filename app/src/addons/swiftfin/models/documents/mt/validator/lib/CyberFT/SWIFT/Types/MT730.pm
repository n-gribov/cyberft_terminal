package CyberFT::SWIFT::Types::MT730;
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
        # C1  � ��������� ����� �������������� ���� ���� 25, ���� ���� 57�, �� �� ��� ��� ����
        # ������ (��� ������ �77).
        {
            func => sub {
                my $doc = shift;
                if($doc->key_exists('25') and $doc->key_exists('57[AD]')){
                    return 0;
                }
                return 1;
            },
            err => 'C77',
        },
        # C2 ���� � ��������� ������������ ���� 32D, �� ���� 57� �� ������ �������������� (���
        # ������ �78).
        {
            func => sub {
                my $doc = shift;
                if($doc->key_exists('32D') and $doc->key_exists('57[AD]')){
                    return 0;
                }
                return 1;
            },
            err => 'C78',
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