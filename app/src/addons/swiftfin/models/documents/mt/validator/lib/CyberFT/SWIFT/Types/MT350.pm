package CyberFT::SWIFT::Types::MT350;
use CyberFT::SWIFT::Types::Utils;
use strict;

our $ValidationProfile = {

    fields => [
        # ������������ ������������������ A ������ �����������
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
            key => '21',
            required => 0,
        },
        {
            key => '22A',
            required => 1,
        },
        {
            key => '94A',
            required => 0,
        },
        {
            key => '22C',
            required => 1,
        },
        {
            key => '21N',
            required => 0,
        },
        {
            key => '82a',
            key_regexp => '82[ADJ]',
            required => 1,
        },
        {
            key => '87a',
            key_regexp => '87[ADJ]',
            required => 1,
        },
        {
            key => '83a',
            key_regexp => '83[ADJ]',
            required => 0,
        },
        {
            key => '72',
            required => 0,
        },
        # ��������� ������������������ � ������ �����������
        # ������������ ������������������ � ���������� � ���������
        {
            key => '15B',
            required => 1,
            allow_empty => 1,
        },
        {
            key => '30G',
            required => 1,
        },
        {
            key => '32B',
            required => 1,
        },
        {
            key => '30V',
            required => 1,
        },
        {
            key => '34B',
            required => 1,
        },
        {
            key => '37J',
            required => 1,
        },
        {
            key => '14D',
            required => 1,
        },
        {
            key => '30F',
            required => 0,
        },
        # ��������� ������������������ � ���������� � ���������
        # ������������ ������������������ � ��������� ����������
        {
            key => '15C',
            required => 1,
            allow_empty => 1,
        },
        {
            key => '53a',
            key_regexp => '53[ADJ]',
            required => 0,
        },
        {
            key => '86a',
            key_regexp => '86[ADJ]',
            required => 0,
        },
        {
            key => '56a',
            key_regexp => '56[ADJ]',
            required => 0,
        },
        {
            key => '57a',
            key_regexp => '57[ADJ]',
            required => 1,
        },
        {
            key => '58a',
            key_regexp => '58[ADJ]',
            required => 0,
        },
        # ��������� ������������������ � ��������� ����������
        # �������������� ������������������ D ��������� ���������� � ���������� ��������
        {
            key => '15D',
            required => 0, # ��� ���� �����������, ���� ������ ������������������ ������������.
            allow_empty => 1,
        },
        {
            key => '34B',
            required => 0, # ��� ���� �����������, ���� ������ ������������������ ������������.
        },
        {
            key => '33B',
            required => 0, # ��� ���� �����������, ���� ������ ������������������ ������������.
        },
        {
            key => '36',
            required => 0,
        },
        # �������������� ������������������ D1 ��������� ������
        {
            key => '37L', # ��� ���� �����������, ���� ������ (���)������������������ ������������.
            required => 0,
        },
        {
            key => '33E', # ��� ���� �����������, ���� ������ (���)������������������ ������������.
            required => 0,
        },
        # ��������� ������������������ D1 ��������� ������
        # �������������� ������������������ D2 ���������� � ���������� ��������
        {
            key => '71F', # ��� ���� �����������, ���� ������ (���)������������������ ������������.
            required => 0,
        },
        {
            key => '37L',
            required => 0,
        },
        {
            key => '33E',
            required => 0,
        },
        # ��������� ������������������ D2 ���������� � ���������� ��������
        # ��������� ������������������ D ��������� ���������� � ���������� ��������
    ],

    rules => [
        # ������������ ���� � ������������ �������������������
        {
            func => sub {
                my $doc = shift;
                my $seq_a = _find_sequences($doc->data)->{'A'};
                if ($seq_a) {
                    for my $k (qw(20 22A 22C 82[ADJ] 87[ADJ])) {
                        return 0 unless seq_key_exists($seq_a, $k);
                    }
                }
                return 1;
            },
            err => 'Missing required field in A sequence',
        },
        {
            func => sub {
                my $doc = shift;
                my $seq_b = _find_sequences($doc->data)->{'B'};
                if ($seq_b) {
                    for my $k (qw(30G 32B 30V 34B 37J 14D)) {
                        return 0 unless seq_key_exists($seq_b, $k);
                    }
                }
                return 1;
            },
            err => 'Missing required field in B sequence',
        },
        {
            func => sub {
                my $doc = shift;
                my $seq_c = _find_sequences($doc->data)->{'C'};
                if ($seq_c) {
                    for my $k (qw(57[ADJ])) {
                        return 0 unless seq_key_exists($seq_c, $k);
                    }
                }
                return 1;
            },
            err => 'Missing required field in C sequence',
        },

        # C1. ���� � ������������������ � ���� 22� �������� ��� ADVC, �� ���� 21 ��������������,
        # � ��������� ������� ���� 21 ������������ (��� ������ D02).
        {
            func => sub {
                my $doc = shift;
                my $seq_a = _find_sequences($doc->data)->{A} || [];
                unless (seq_key_value_exists($seq_a, '22A', '^ADVC\s*$')) {
                    return 0 unless (seq_key_exists($seq_a, '21'));
                }
                return 1;
            },
            err => 'D02',
        },

        # C2. ���� � ������������������ � ������������ ���� 94� � ������� ������ AGNT, �� ���� 21N �
        # ������������������ � �������� ������������, � ��������� ������� ���� 21N ��������������
        # (��� ������ D72).
        {
            func => sub {
                my $doc = shift;
                my $seq_a = _find_sequences($doc->data)->{A} || [];
                if (seq_key_value_exists($seq_a, '94A', '^AGNT\s*$')) {
                    return 0 unless (seq_key_exists($seq_a, '21N'));
                }
                return 1;
            },
            err => 'D72',
        },

        # �3. ���� � ������������������ C ����������� ���� 56�, �� ���� 86� � ������������������ C ��
        # ������ ��������������, � ��������� ������� ���� 86� �������������� (��� ������ �35).
        {
            func => sub {
                my $doc = shift;
                my $seq_c = _find_sequences($doc->data)->{C} || [];
                unless (seq_key_exists($seq_c, '56[ADJ]')) {
                    return 0 if (seq_key_exists($seq_c, '86[ADJ]'));
                }
                return 1;
            },
            err => 'E35',
        },

        # �4. ��� ������ � ����� ���� 32B � 34B ������ ���� ���������� � ������������������ B
        # (��� ������ �02).
        {
            func => sub {
                my $doc = shift;
                my $seq_b = _find_sequences($doc->data)->{B} || [];
                my ($cur32b) = seq_get_first($seq_b, '32B') =~ /^(\w{3})/;
                my ($cur34b) = seq_get_first($seq_b, '34B') =~ /^(\w{3})/;
                return 0 if ($cur32b ne $cur34b);
                return 1;
            },
            err => 'C02',
        },

        # �5. ���� ������������������ D ������������, �� ������� ���� ���� �� �������������������
        # D1 � D2 ������ �������������� (��� ������ �47).
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                if ($seqs->{D}) {
                    return 0 unless ($seqs->{D1} || $seqs->{D2});
                }
                return 1;
            },
            err => 'E47',
        },

        # C6. �� ���� �������������� ������������������� � ����������������������, ���� ����������
        # ������������������ ��� ���������������������, ���� �� �������� � ������ ��������������,
        # ������ �� ������������ (��� ������ �32).
        {
            func => sub {
                my $doc = shift;
                my $seq_d = _find_sequences($doc->data)->{'D'};
                if ($seq_d) {
                    for my $k (qw(34B 33B)) {
                        return 0 unless seq_key_exists($seq_d, $k);
                    }
                }
                my $seq_d1 = _find_sequences($doc->data)->{'D1'};
                if ($seq_d1) {
                    for my $k (qw(37L 33E)) {
                        return 0 unless seq_key_exists($seq_d, $k);
                    }
                }
                return 1;
            },
            err => 'C32',
        },

    ]
};

# ����������� ��� ������������������ ��� ��� ��������.
sub _find_sequences {
    my $data = shift;

    my $marks = {
        '15A' => 'A',
        '15B' => 'B',
        '15C' => 'C',
        '15D' => 'D',
    };

    my $cur_seq = undef;
    my $seqs = {};

    for my $item (@$data) {
        my $k = $item->{key};
        if (defined $marks->{$k}) {
            $cur_seq = $marks->{$k};
        }
        if ($cur_seq) {
            push @{$seqs->{$cur_seq}}, $item;
        }
    }

    # ������� D1 � D2 � ��������� �������
    my $d_seq = $seqs->{D} || [];
    my $cur_d_seq = undef;
    my ($got37l, $got33e);
    for my $item (@$d_seq) {
        if ($item->{key} =~ /(37L|33E)/ && !$cur_d_seq) {
            $cur_d_seq = 'D1';
        }
        if ($item->{key} =~ /(71F)/) {
            $cur_d_seq = 'D2';
        }
        if ($cur_d_seq) {
            push @{$seqs->{$cur_d_seq}}, $item;
        }
        if ($cur_d_seq eq 'D2') {
            if ($item->{key} =~ /33E/) {
                $got33e = 1;
            }
            elsif ($item->{key} =~ /37L/) {
                $got37l = 1;
            }
            if ($got37l && $got33e) {
                $cur_d_seq = undef;
            }
        }
    }

    return $seqs;
}

1;