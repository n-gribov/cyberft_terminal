package CyberFT::SWIFT::Types::MT341;
use CyberFT::SWIFT::Types::Utils;
use strict;
use Data::Dumper;

our $ValidationProfile = {

    fields => [
        # ������������ ���� ������������ �������������������.
        {
            key => '15A',
            required => 1,
            allow_empty => 1,
        },
        {
            key => '20',
            required => 1,
        },
        {
            key => '22A',
            required => 1,
        },
        {
            key => '22C',
            required => 1,
        },
        {
            key => '23D',
            required => 1,
        },
        {
            key => '82a',
            key_regexp => '82[AD]',
            required => 1,
        },
        {
            key => '87a',
            key_regexp => '87[AD]',
            required => 1,
        },
        
        {
            key => '15B',
            required => 1,
            allow_empty => 1,
        },
        {
            key => '30T',
            required => 1,
        },
        {
            key => '32B',
            required => 1,
        },
        {
            key => '30F',
            required => 1,
        },
        {
            key => '30P',
            required => 1,
        },
        {
            key => '37M',
            required => 1,
        },
        
        {
            key => '15C',
            required => 1,
            allow_empty => 1,
        },
        {
            key => '37R',
            required => 1,
        },
        {
            key => '34E',
            required => 1,
        },
        {
            key => '57a',
            key_regexp => '57[ADJ]',
            required => 1,
        },
    ],

    rules => [
        # �������� ����������� ������������ ����� ������������ �������������������
        {
            func => sub {
                my $doc = shift;
                
                my $seqs = _find_sequences($doc->data);
                my $reqs = {
                    'A' => [qw( 20 22A 22C 23D 82[AD] 87[AD] )],
                    'B' => [qw( 30T 32B 30F 30P 37M )],
                    'C' => [qw( 37R 34E 57[ADJ] )],
                };
                
                for my $l (keys %$reqs) {
                    my $fields = $reqs->{$l};
                    my $seq = $seqs->{$l}->[0] || [];
                    for my $k (@$fields) {
                        unless (seq_key_exists($seq, $k)) {
                            my $ks = $k;
                            $ks =~ s/(\d+)(\[.*\])/\1a/;
                            return (0, "Missing required field ($l sequence): $ks");
                        }
                    }
                }
                
                return 1;
            },
            err => 'Missing required field',
        },
        
        # �1 
        # � ������������������ � ������������� ���� 21 ������� �� �������� ���� 22� � ������������ 
        # ��������� ������� (��� ������ D02):
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                my $a_seq = $seqs->{A}->[0] || [];
                my $val_22a = seq_get_first($a_seq, '22A');
                
                if ($val_22a =~ /AMND|CANC/) {
                    return 0 unless (seq_key_exists($a_seq, '21'));
                }
                
                return 1;
            },
            err => 'D02',
        },
        
        # �2 
        # ���� � ������������������ � ����������� ���� 56�, �� ���� 86� � ��� �� ������������������ 
        # � �� ������������, � ��������� ������ ���� 86� �������������� (��� ������ �35):
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                
                my $seq = $seqs->{C}->[0] || [];
                unless (seq_key_exists($seq, '56[A-Z]')) {
                    return 0 if (seq_key_exists($seq, '86[A-Z]'));
                }
                
                return 1;
            },
            err => 'E35',
        },
        
        # �3 
        # ���� � ������������������� �1 ������������ ���� 30V, �� ���� 38D ������������, � ��������� 
        # ������ ���� 38D �� ������������ (��� ������ D60).
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                
                my $seq = $seqs->{B}->[0] || [];
                if (seq_key_exists($seq, '30V')) {
                    return 0 unless (seq_key_exists($seq, '38D'));
                } else {
                    return 0 if (seq_key_exists($seq, '38D'));
                }
                
                return 1;
            },
            err => 'D60',
        },
        
        # �10 
        # � ������������������ D ���� 15D �� ����� ���� ������������ �����, �� ����, ���� 
        # ������������ ���� 15D, �� � ������������������ D ������ �������������� ��� ���� �� ���� 
        # ���� (��� ������ �98).
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                
                my $seq = $seqs->{D}->[0] || undef;
                if (defined $seq && scalar(@$seq) < 2) {
                    return 0;
                }
                
                return 1;
            },
            err => 'C98',
        },
        
        # �11 
        # �� ���� �������������� ������������������� � ����������������������, ���� ���������� 
        # ������������������ ��� ���������������������, ���� �� �������� � ������ ��������������, 
        # ������ �� ������������ (��� ������ �32).
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                
                # D1a, D1a1
                my $d_seq = $seqs->{D}->[0] || undef;
                if (defined $d_seq) {
                    my $d_len = scalar(@$d_seq);
                    for my $i (0 .. $d_len-1){
                        if ($d_seq->[$i]->{key} eq '22M') {
                            unless ($d_seq->[$i+1]->{key} eq '22N') {
                                return 0;
                            }
                        }
                        if ($d_seq->[$i]->{key} eq '22P') {
                            unless ($d_seq->[$i+1]->{key} eq '22R') {
                                return 0; 
                            }
                        }
                    }
                }
                
                return 1;
            },
            err => 'C32',
        },
        
    ]
};

# ����������� �������� ������������������ ��� ���. ������ ������������������ ����������
# � ���� 15 c ��������� ������. ��������, ���� 15B - ��� ������ ������������������ "B".
sub _find_sequences {
    my $data = shift;
    my $seqs = {};
    my $cur_seq = undef;
    my $cur_seq_code = undef;
    for my $item (@$data) {
        my $k = $item->{key};
        if ($k =~ /^15([A-Z])$/) {
            $cur_seq_code = $1;
            $cur_seq = [];
            push @{$seqs->{$cur_seq_code}}, $cur_seq;
        }
        if ($cur_seq) {
            push @$cur_seq, $item;
        }
    }
    return $seqs;
}

1;