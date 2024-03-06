package CyberFT::SWIFT::Types::MT747;
use CyberFT::SWIFT::Types::Utils;
use strict;
our $ValidationProfile = {
    
    fields => [
        {   
            key => '20',
            required => 1,
        },
        {   
            key => '30',
            required => 1,
        },
    ],
    
    rules => [
        # C1   � ��������� ������ �������������� ���� �� ���� �� ����� 31�, 32�, 33�, 34�, 39A,
        # 39B, 39�, 72 ��� 77� (��� ������ �15).
        {
            func => sub {
                my $doc = shift;
                if($doc->key_exists('31E') 
                    or  $doc->key_exists('32B')
                    or  $doc->key_exists('33B')
                    or  $doc->key_exists('34B')
                    or  $doc->key_exists('39A')
                    or  $doc->key_exists('39B')
                    or  $doc->key_exists('39�')
                    or  $doc->key_exists('72')
                    or  $doc->key_exists('77A')
                 ){
                    return 1;
                } else {
                    return 0;
                }
                return 1;
            },
            err => 'C15',
        },
        # C2   ���� � ��������� ������������ ���� 32� ��� ���� 33B, �� ����������� ������
        # �������������� ����� � ���� 34� (��� ������ �12).
        {
            func => sub {
                my $doc = shift;
                if($doc->key_exists('32B') or $doc->key_exists('33B')  ){
                    if ( not $doc->key_exists('34B') ){
                        return 0;
                    }
                }
                return 1;
            },
            err => 'C12',
        },
        # C3   ���� � ��������� ������������ ���� 34�, ������ ����� �������������� ���� ����
        # 32�, ���� ���� 33B (��� ������ �12).
        {
            func => sub {
                my $doc = shift;
                if($doc->key_exists('34B')){
                    if ( (not $doc->key_exists('32B')) and (not $doc->key_exists('33B')) ){
                        return 0;
                    }
                }
                return 1;
            },
            err => 'C12',
        },
        # C4   � ��������� ����� �������������� ���� ���� 39A, ���� ���� 39B, �� �� ��� ��� ����
        # ������ (��� ������ D05)
        {
            func => sub {
                my $doc = shift;
                if($doc->key_exists('39A') and  $doc->key_exists('39B') ){
                    return 0;
                }
                return 1;
            },
            err => 'D05',
        },
        # C5 ��� ������ � ����� ���� 32�, 33� � 34� ������ ���� ���������� (��� ������ �02)
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