package CyberFT::SWIFT::Types::MT104;
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
            key => '30',
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
            key => '59a',
            key_regexp => '59A?',
            required => 1,
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
                }
                return 1;
            },
            err => 'Missing required field in B sequence',
        },

        # C1
        # ���� ���� 23� ������������ � ������������������ � � �������� ��� RFDD, �� ���� 23�
        # ������ ����� �������������� � ������ �� ���������� ������������������ �.
        # ���� ���� 23� ������������ � ������������������ � � �� �������� ��� RFDD, �� ���� 23�
        # �� ������ �������������� �� � ����� �� ���������� ������������������ �.
        # ���� ���� 23� �� ������������ � ������������������ �, �� ��� ����������� ������
        # �������������� �� ���� ����������� ������������������ �. (��� ������ �75):
        {
            func => sub {
                my $doc = shift;
                my $a = _find_A_seq($doc->{data});
                my $bs = _find_B_seqs($doc->{data});
                if (seq_key_value_exists($a, '23E', 'RFDD') || !seq_key_exists($a, '23E')) {
                    for my $b (@$bs) {
                        return 0 unless (seq_key_exists($b, '23E'));
                    }
                }
                else {
                    for my $b (@$bs) {
                        return 0 if (seq_key_exists($b, '23E'));
                    }
                }
                return 1;
            },
            err => 'C75',
        },

        # �2
        # ���� 50� (� ������ � ��� �) ������ �������������� ���� � ������������������ �
        # (���������� ����� 8), ���� � ������ �� ���������� ������������������ � (���������� ����� 21),
        # �� ������� �� ����� �������������� ��� ������������� � ����� ������������������� ������������
        # (��� ������ �76)
        {
            func => sub {
                my $doc = shift;
                my $a = _find_A_seq($doc->{data});
                my $bs = _find_B_seqs($doc->{data});
                my $exists_in_a = seq_key_exists($a, '50[AK]');
                for my $b (@$bs) {
                    if ($exists_in_a) {
                        return 0 if (seq_key_exists($b, '50[AK]'));
                    } else {
                        return 0 unless (seq_key_exists($b, '50[AK]'));
                    }
                }
                return 1;
            },
            err => 'C76',
        },

        # �3
        # ���� � ������������������ � ������������ ���� 21�, 26�, 52�, 71�, 77� � 50� (� ������ � ��� L),
        # �� ��� ����, ���������� ���� �� �����, �� ������ �������������� �� � ����� �� ����������
        # ������������������ �. � ��������, ���� ���� 21�, 26�, 52�, 71�, 77� � 50� (� ������ � ��� L)
        # ������������ � ����� ��� ����� �� ���������� ������������������ �, �� ��� �� ������
        # �������������� � ������������������ � (��� ������ D73).
        {
            func => sub {
                my $doc = shift;
                my $a = _find_A_seq($doc->{data});
                my $bs = _find_B_seqs($doc->{data});
                for my $k (qw`  21E  26T  52[A-Z]  71A  77B  50[CL]  `) {
                    if (seq_key_exists($a, $k)) {
                        for my $b (@$bs) {
                            return 0 if (seq_key_exists($b, $k));
                        }
                    }
                }
                return 1;
            },
            err => 'D73',
        },

        # �4
        # ���� ���� 21� ������������ � ������������������ �, �� ������ ���������� ���� 50� (� ������ � ��� �)
        # ����� ������ �������������� � ������������������ �. ���� � ����� �� ���������� ������������������
        # � ������������ ���� 21�, �� � ���� �� ���������� ������ �������������� ����� � ������
        # ���� 50� (� ������ � ��� �) (��� ������ D77).
        {
            func => sub {
                my $doc = shift;
                my $a = _find_A_seq($doc->{data});
                my $bs = _find_B_seqs($doc->{data});
                if (seq_key_exists($a, '21E')) {
                    return 0 unless (seq_key_exists($a, '50[AK]'));
                }
                for my $b (@$bs) {
                    if (seq_key_exists($b, '21E')) {
                        return 0 unless (seq_key_exists($b, '50[AK]'));
                    }
                }
                return 1;
            },
            err => 'D77',
        },

        # C5
        # ���� � ������������������ � ������������ ���� 23�, � � ��� ����������� ��� RTND, �� ������
        # ����� �������������� � ���� 72. �� ���� ��������� ������� - �� ����, ����� ���� 23�
        # ����������� ��� �� �������� �RTND� - ���� 72 �� ������ �������������� (��� ������ �82).
        {
            func => sub {
                my $doc = shift;
                my $a = _find_A_seq($doc->{data});
                if (seq_key_value_exists($a, '23E', 'RTND')) {
                    return 0 unless (seq_key_exists($a, '72'));
                }
                else {
                    return 0 if (seq_key_exists($a, '72'));
                }
                return 1;
            },
            err => 'C82',
        },

        # C6
        # ���� ���� 71F ������������ � ����� ��� ����� �� ���������� ������������������ �,
        # �� ��� ����� ������ �������������� � ������������������ �, � �������� (��� ������ D79).
        # ���� ���� 71G ������������ � ����� ��� ����� �� ���������� ������������������ �,
        # �� ��� ����� ������ �������������� � ������������������ �, � �������� (��� ������ D79).
        {
            func => sub {
                my $doc = shift;
                my $bs = _find_B_seqs($doc->{data});
                my $c = _find_C_seq($doc->{data});
                my ($e71f, $e71g);
                for my $b (@$bs) {
                    if (seq_key_exists($b, '71F')) {
                        $e71f = 1;
                    }
                    if (seq_key_exists($b, '71G')) {
                        $e71g = 1;
                    }
                }
                return 0 if ($e71f && !seq_key_exists($c, '71F'));
                return 0 if (!$e71f && seq_key_exists($c, '71F'));
                return 0 if ($e71g && !seq_key_exists($c, '71G'));
                return 0 if (!$e71g && seq_key_exists($c, '71G'));
                return 1;
            },
            err => 'D79',
        },

        # C7
        # ���� � ����� �� ���������� ������������������ � ������������ ���� 33�, �� ��� ������,
        # ��� �����, ��� ��� ���� ���������, ��������� � ����� 33� � 32�, ������ �����������
        # (��� ������ D21).
        {
            func => sub {
                my $doc = shift;
                my $bs = _find_B_seqs($doc->{data});
                for my $b (@$bs) {
                    if (seq_key_exists($b, '33B')) {
                        my ($cur_33b, $sum_33b) = (seq_get_first($b, '33B') =~ /([A-Z]{3})([\d\.\,]+)/);
                        my ($cur_32b, $sum_32b) = (seq_get_first($b, '32B') =~ /([A-Z]{3})([\d\.\,]+)/);
                        $sum_33b =~ s/,/./;
                        $sum_32b =~ s/,/./;
                        return 0 if (($cur_33b eq $cur_32b) && ($sum_33b == $sum_32b));
                    }

                }
                return 1;
            },
            err => 'D21',
        },

        # C8
        # ���� � ����� ���������� ������������������ � ������������ ���� 33�, � ��� ������ � ����
        # 32� ���������� �� ���� ������ � ���� 33�, �� ������ �������������� ���� 36. � ���������
        # ������� ���� 36 �� ������������ (��� ������ D75).
        {
            func => sub {
                my $doc = shift;
                my $bs = _find_B_seqs($doc->{data});
                for my $b (@$bs) {
                    if (seq_key_exists($b, '33B')) {
                        my ($cur_33b) = (seq_get_first($b, '33B') =~ /([A-Z]{3})/);
                        my ($cur_32b) = (seq_get_first($b, '32B') =~ /([A-Z]{3})/);
                        if ($cur_33b ne $cur_32b) {
                            return 0 unless (seq_key_exists($b, '36'));
                        }
                        else {
                            return 0 if (seq_key_exists($b, '36'));
                        }
                    }
                    else {
                        return 0 if (seq_key_exists($b, '36'));
                    }

                }
                return 1;
            },
            err => 'D75',
        },

        # �9
        # ���� ������������ ������������������ �, � ���� ����� � ���� 32� ������������������ �
        # ����� ����� ����� ���� ���������� ���� 32� � ������������������ �, �� ���� 19 �� ������
        # ��������������; �� ���� ��������� ������� ���� 19 ����������� ������ ��������������
        # (��� ������ D80).
        {
            func => sub {
                my $doc = shift;
                my $bs = _find_B_seqs($doc->{data});
                my $c = _find_C_seq($doc->{data});
                if (seq_key_exists($c, '32B')) {
                    my ($sum_c_32b) = (seq_get_first($c, '32B') =~ /([\d\.\,]+)/);
                    $sum_c_32b =~ s/,/./;
                    $sum_c_32b = sprintf("%.4f", $sum_c_32b);
                    my $sum_b_32b = 0;
                    for my $b (@$bs) {
                        my ($sum_32b) = (seq_get_first($b, '32B') =~ /([\d\.\,]+)/);
                        $sum_32b =~ s/,/./;
                        $sum_b_32b += $sum_32b;
                    }
                    $sum_b_32b = sprintf("%.4f", $sum_b_32b);
                    if ($sum_c_32b == $sum_b_32b) {
                        return 0 if ($doc->key_exists('19'));
                    }
                    else {
                        return 0 unless ($doc->key_exists('19'));
                    }
                }
                return 1;
            },
            err => 'D80',
        },

        # �10
        # ���� ���� 19 ������������ � ������������������ �, �� ��������� � ��� ����� ������ ����
        # ����� ����� ����� ���� ���������� ���� 32� � ������������������ � (��� ������ �01).
        {
            func => sub {
                my $doc = shift;
                my $c = _find_C_seq($doc->{data});
                my $bs = _find_B_seqs($doc->{data});

                if (seq_key_exists($c, '19')) {
                    my ($total) = (seq_get_first($c, '19') =~ /([\d\.\,]+)/);
                    $total =~ s/,/./;
                    $total = sprintf("%.4f", $total);

                    my $sum_b_32b = 0;
                    for my $b (@$bs) {
                        my ($sum_32b) = (seq_get_first($b, '32B') =~ /([\d\.\,]+)/);
                        $sum_32b =~ s/,/./;
                        $sum_b_32b += $sum_32b;
                    }
                    $sum_b_32b = sprintf("%.4f", $sum_b_32b);

                    return 0 if ($sum_b_32b != $total);
                }
                return 1;
            },
            err => 'C01',
        },

        # �11
        # ��� ������ � ����� 32� � 71 G ������������������� � � � ������ ���� ���������� �� ����
        # ����������� ���� ����� � ��������� (��� ������ �02).
        # ��� ������ � ����� �������� 71F (� ������������������� � � �) ������ ���� ���������� ��
        # ���� ����������� ���� ����� � ��������� (��� ������ �02).
        {
            func => sub {
                my $doc = shift;
                my $values = $doc->get_all('(71G|32B)');
                my $check = '';
                for my $v (@$values) {
                    my ($cur) = $v =~ /([A-Z]{3})/;
                    if ($check) {
                        return 0 if ($check ne $cur);
                    }
                    else {
                        $check = $cur;
                    }
                }
                return 1;
            },
            err => 'C02',
        },
        {
            func => sub {
                my $doc = shift;
                my $values = $doc->get_all('71F');
                my $check = '';
                for my $v (@$values) {
                    my ($cur) = $v =~ /([A-Z]{3})/;
                    if ($check) {
                        return 0 if ($check ne $cur);
                    }
                    else {
                        $check = $cur;
                    }
                }
                return 1;
            },
            err => 'C02',
        },

        # C12
        # ���� � ������������������ � ������������ ���� 23�, � � ��� ������ ��� RFDD, ��:
        # � � ������������������ � ���� 21R ��������������
        # � � � ������������������ � ���� 21E, 50� (� ������ � ��� �), 52a, 71F � 71G �� ������
        # ��������������
        # � � ������������������ � �� ������ ��������������
        # � ��������� �������, �� ����, ���� ���� 23� ����������� � ������������������ � ��� ��
        # �������� ��� RFDD:
        # � � ������������������ � ���� 21R �� ������ ��������������
        # � � � ������������������ � ���� 21E, 50� (� ������ � ��� �), 52a, 71F � 71G ��������������
        # � � ������������������ � ������ ��������������. (��� ������ �96).
        {
            func => sub {
                my $doc = shift;
                my $a = _find_A_seq($doc->{data});
                my $bs = _find_B_seqs($doc->{data});
                my $c = _find_C_seq($doc->{data});
                if (seq_key_value_exists($a, '23E', 'RFDD')) {
                    for my $b (@$bs) {
                        return 0 if (seq_key_exists($b, '(21E|50[AK]|52[A-Z]|71[FG])'));
                    }
                    return 0 if (scalar @$c > 0);
                }
                else {
                    return 0 if (seq_key_exists($a, '21R'));
                    return 0 if (scalar @$c < 1);
                }
                return 1;
            },
            err => 'C96',
        },
    ]

};

sub _find_A_seq {
    my $data = shift;
    my @seq;
    for my $item (@$data) {
        if ($item->{key} eq '21' || $item->{key} eq '32A') {
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
    my $was_32b = 0;
    for my $item (@$data) {
        if ($item->{key} eq '21') {
            push @seqs, [];
            $seq_started = 1;
            $was_32b = 0;
        }
        if ($item->{key} eq '32B') {
            if ($was_32b) {
                # ������, �������� ������������������ C.
                return \@seqs;
            }
            $was_32b = 1;
        }
        if ($seq_started) {
            push @{$seqs[scalar(@seqs)-1]}, $item;
        }
    }
    return \@seqs;
}

sub _find_C_seq {
    my $data = shift;
    my @seq;
    my $seq_started = 0;
    my $was_32b = 0;
    for my $item (@$data) {
        if (!$seq_started) {
            if ($item->{key} eq '21') {
                $was_32b = 0;
            }
            if ($item->{key} eq '32B') {
                if ($was_32b) {
                    # ������, �������� ������������������ C.
                    $seq_started = 1;
                }
                else {
                    $was_32b = 1;
                }
            }
        }
        if ($seq_started) {
            push @seq, $item;
        }
    }
    return \@seq;
}

1;