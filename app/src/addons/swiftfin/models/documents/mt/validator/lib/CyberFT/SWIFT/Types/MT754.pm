package CyberFT::SWIFT::Types::MT754;
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
            key_regexp => '32[AB]',
            required => 1,
        },
    ],
    
    rules => [
        # C1 � ��������� ����� �������������� ���� ���� 72, ���� ���� 77�, �� �� ��� ��� ����
        # ������ (��� ������ �19)
        {
            func => sub {
                my $doc = shift;
                if($doc->key_exists('72') and  $doc->key_exists('77A')){
                    return 0;
                }
                return 1;
            },
            err => 'C19',
        },
        # C2 � ��������� ����� �������������� ���� ���� 53�, ���� ���� 57�, �� �� ��� ��� ����
        # ������ (��� ������ �14)
        {
            func => sub {
                my $doc = shift;
                if($doc->key_exists('53[ABD]') and  $doc->key_exists('57[ABD]')){
                    return 0;
                }
                return 1;
            },
            err => 'C14',
        },
        # C3 ��� ������ � ����� ���� 32� � 34� ������ ���� ���������� (��� ������ �02)
        {
            func => sub {
                my $doc = shift;
                my $vs = seq_get_all($doc->data, '32A|32B|34A|34B');
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