package CyberFT::SWIFT::Types::MT101;
use CyberFT::SWIFT::Types::Utils;
use strict;
use Data::Dumper;

our $ValidationProfile = {

    fields => [
        # ������� ������ ������������ ���� ������������ �������������������.
        {
            key => '20',
            required => 1,
        },
        {
            key => '21R',
            required => 0,
        },
        {
            key => '28D',
            required => 1,
        },
        {
            key => '50a',
            required => 0,
        },
        {
            key => '52a',
            required => 0,
        },
        {
            key => '51A',
            required => 0,
        },
        {
            key => '30',
            required => 1,
        },
        {
            key => '25',
            required => 0,
        },
    ],

    rules => [

        # �������� ������������ ���� � ������������������� B
        {
            func => sub {
                my $doc = shift;
                my $bs = _find_B_seqs($doc->{data});
                for my $b (@$bs) {
                    return 0 unless (seq_key_exists($b, '21'));
                    return 0 unless (seq_key_exists($b, '32B'));
                    return 0 unless (seq_key_exists($b, '59A?'));
                    return 0 unless (seq_key_exists($b, '71A'));
                }
                return 1;
            },
            err => 'Missing required field in B sequence',
        },

        # C1
        # ���� � ���� 36 ������ ���� �����������, �� � ���� 21F ������ �������������� ��������
        # ��������������� ������������� ������ (��� ������ D54).
        {
            func => sub {
                my $doc = shift;
                my $bs = _find_B_seqs($doc->{data});
                for my $b (@$bs) {
                    if (seq_key_exists($b, '36')) {
                        return 0 unless (seq_key_exists($b, '21F'));
                    }
                }
                return 1;
            },
            err => 'D54',
        },

        # C2
        # � ������ ���������� ������������������ �, ���� � ��� ������������ ���� 33B � ���� ����� �
        # ���� 32� �� ����� ����, ������ �������������� ���� 36; � ���������������� ���� 36 ��
        # ������������ (��� ������ D60).
        {
            func => sub {
                my $doc = shift;
                my $bs = _find_B_seqs($doc->{data});
                for my $b (@$bs) {
                    my ($sum_32b) = (seq_get_first($b, '32B') =~ /([\d\.\,]+)/);
                    $sum_32b =~ s/,/./;
                    if (seq_key_exists($b, '33B') && $sum_32b != 0) {
                        return 0 unless (seq_key_exists($b, '36'));
                    } else {
                        return 0 if (seq_key_exists($b, '36'));
                    }
                }
                return 1;
            },
            err => 'D60',
        },

        # C3
        # ���� ������� ����� ���� ���� ��� �����������, �� ������-�������� ������ ������������ �
        # ���� 50� (� ������ F, G ��� �) ������������������ A. � ��������, ���� �������� �������
        # ������������ � ��������� ������, �� ��� ����� ������ ����������� �������� ��� ������ ��
        # �������� � ���� 50� (� ������ F, G ��� �) ������������������ B.
        # ��������������, ���� 50� (� ������ F, G ��� �) ������ �������������� ���� �
        # ������������������ A (���������� ����� 5), ���� � ������ �� ���������� ������������������
        # B (���������� ����� 15), �� ������� �� ����� �������������� ��� ������������� ����� � �����
        # ������������������� (��� ������ D61).
        {
            func => sub {
                my $doc = shift;
                my $a = _find_A_seq($doc->{data});
                my $bs = _find_B_seqs($doc->{data});
                my $exists_in_a = seq_key_exists($a, '50[FGH]');
                for my $b (@$bs) {
                    if ($exists_in_a) {
                        return 0 if (seq_key_exists($b, '50[FGH]'));
                    } else {
                        return 0 unless (seq_key_exists($b, '50[FGH]'));
                    }
                }
                return 1;
            },
            err => 'D61',
        },

        # C4
        # ���� 50� (� ������ � ��� L) ����� �������������� ���� � ������������������ A
        # (���������� ����� 4), ���� � ������ �� ���������� ������������������ B (���������� ����� 14),
        # �� ������� �� ������ �������������� ����� � ����� ������������������� � � � (��� ������ D62).
        {
            func => sub {
                my $doc = shift;
                my $a = _find_A_seq($doc->{data});
                my $bs = _find_B_seqs($doc->{data});
                if (seq_key_exists($a, '50[CL]')) {
                    for my $b (@$bs) {
                        return 0 if (seq_key_exists($b, '50[CL]'));
                    }
                }
                return 1;
            },
            err => 'D62',
        },

        # C5
        # ���� � ������������������ B ������������ ���� 33B, �� ��������� � ��� ��� ������ ������
        # ���������� �� ���� ������ � ���� 32B ���� �� ���������� ������������������ B (��� ������ D68).
        {
            func => sub {
                my $doc = shift;
                my $bs = _find_B_seqs($doc->{data});
                for my $b (@$bs) {
                    if (seq_key_exists($b, '33B')) {
                        my ($cur_33b) = (seq_get_first($b, '33B') =~ /([A-Z]{3})/);
                        my ($cur_32b) = (seq_get_first($b, '32B') =~ /([A-Z]{3})/);
                        return 0 if ($cur_33b eq $cur_32b);
                    }
                }
                return 1;
            },
            err => 'D68',
        },

        # C6
        # ���� 52a ����� �������������� ���� � ������������������ A, ���� � ����� ��� ����� ��
        # ���������� ������������������ B, �� ������� �� ������ �������������� ����� � �����
        # ������������������� (��� ������ D64).
        {
            func => sub {
                my $doc = shift;
                my $a = _find_A_seq($doc->{data});
                my $bs = _find_B_seqs($doc->{data});
                if (seq_key_exists($a, '52[AC]')) {
                    for my $b (@$bs) {
                        return 0 if (seq_key_exists($b, '52[AC]'));
                    }
                }
                return 1;
            },
            err => 'D64',
        },

        # C7
        # ���� � ��������� ������������ ���� 56�, �� ����������� ������ �������������� ����� �
        # ���� 57� (��� ������ D65).
        {
            if => ['exists', '56[ACD]'],
            must => ['exists', '57[ACD]'],
            err => 'D65',
        },

        # C8
        # ���� � ������������������ A ������������ ���� 21R, �� �� ���� �����������
        # ������������������ B ��� ������ � ����� 32� ������ ���� ���������� (��� ������ D98).
        {
            func => sub {
                my $doc = shift;
                my $a = _find_A_seq($doc->{data});
                my $bs = _find_B_seqs($doc->{data});
                if (seq_key_exists($a, '21R')) {
                    my $check = '';
                    for my $b (@$bs) {
                        my ($cur_32b) = (seq_get_first($b, '32B') =~ /([A-Z]{3})/);
                        if ($check eq '') {
                            $check = $cur_32b;
                        }
                        else {
                            return 0 if ($check ne $cur_32b);
                        }
                    }
                }
                return 1;
            },
            err => 'D98',
        },

        # C9
        # ��� ������� �� ���������� ������������������ B ������������� ����� 33B � 21F ������� ��
        # ����������� � �������� ����� 32� � 36 ��������� ������� (��� ������ �54): (��. ������������)
        {
            func => sub {
                my $doc = shift;
                my $bs = _find_B_seqs($doc->{data});
                for my $b (@$bs) {
                    my ($sum_32b) = (seq_get_first($b, '32B') =~ /([\d\.\,]+)/);
                    $sum_32b =~ s/,/./;
                    if ($sum_32b == 0) {
                        if (seq_key_value_exists($b, '23E', 'EQUI')) {
                            return 0 unless (seq_key_exists($b, '33B'));
                        }
                        else {
                            return 0 if (seq_key_exists($b, '33B'));
                            return 0 if (seq_key_exists($b, '21F'));
                        }
                    }
                    else {
                        return 0 if (seq_key_exists($b, '23E'));
                    }
                }
                return 1;
            },
            err => 'E54',
        },
    ]

};

sub _find_A_seq {
    my $data = shift;
    my @seq;
    for my $item (@$data) {
        if ($item->{key} eq '21') {
            return \@seq;
        }
        push @seq, $item;
    }
    return \@seq;
}

sub _find_B_seqs {
    my $data = shift;
    my @seqs;
    my $seq_started = 0;
    for my $item (@$data) {
        if ($item->{key} eq '21') {
            push @seqs, [];
            $seq_started = 1;
        }
        if ($seq_started) {
            push @{$seqs[scalar(@seqs)-1]}, $item;
        }
    }
    return \@seqs;
}

1;