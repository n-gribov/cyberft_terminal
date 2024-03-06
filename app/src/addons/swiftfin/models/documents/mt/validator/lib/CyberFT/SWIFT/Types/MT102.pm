package CyberFT::SWIFT::Types::MT102;
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
            key => '23',
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
        {
            key => '32A',
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
        # ���� � ������������������ � ������������ ���� 19, �� ��������� � ��� ����� ������ ���������
        # ���������� �������� ���� �� ���� ����������� ���� 32� (��� ������ �01).
        {
            func => sub {
                my $doc = shift;
                my $c = _find_C_seq($doc->{data});
                my $bs = _find_B_seqs($doc->{data});

                if (seq_key_exists($c, '19')) {
                    my ($total) = (seq_get_first($c, '19') =~ /([\d\.\,]+)/);
                    $total =~ s/,/./;
                    $total = sprintf("%.4f", $total);

                    my $values_32b = $doc->get_all('32B');
                    my $sum = 0;

                    for my $v (@$values_32b) {
                        my ($n) = ($v =~ /([\d\.\,]+)/);
                        $n =~ s/,/./;
                        $sum += $n;
                    }
                    $sum = sprintf("%.4f", $sum);

                    return 0 if ($sum != $total);
                }
                return 1;
            },
            err => 'C01',
        },

        # �2
        # ��� ������ � ����� 71G, 32� � 32� ������ ���� ���������� �� ���� ����������� ���� ����� �
        # ��������� (��� ������ �02).
        {
            func => sub {
                my $doc = shift;
                my $values = $doc->get_all('(71G|32B|32A)');
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

        # C3
        # ���� 50� ������ �������������� ���� � ������������������ �, ���� � ������ �� ����������
        # ������������������ �, �� ������� �� ������ �������������� ��� ������������� ������������ �
        # ����� ���� ������������������� (��� ������ D17).
        {
            func => sub {
                my $doc = shift;
                my $a = _find_A_seq($doc->{data});
                my $bs = _find_B_seqs($doc->{data});
                my $exists_in_a = seq_key_exists($a, '50[AFK]');
                for my $b (@$bs) {
                    if ($exists_in_a) {
                        return 0 if (seq_key_exists($b, '50[AFK]'));
                    } else {
                        return 0 unless (seq_key_exists($b, '50[AFK]'));
                    }
                }
                return 1;
            },
            err => 'D17',
        },

        # C4
        # ���� 71� ������ �������������� ���� � ������������������ �, ���� � ������ �� ����������
        # ������������������ �, �� ������� �� ������ �������������� ��� ������������� ������������
        # � ����� ���� ������������������� (��� ������ D20).
        {
            func => sub {
                my $doc = shift;
                my $a = _find_A_seq($doc->{data});
                my $bs = _find_B_seqs($doc->{data});
                my $exists_in_a = seq_key_exists($a, '71A');
                for my $b (@$bs) {
                    if ($exists_in_a) {
                        return 0 if (seq_key_exists($b, '71A'));
                    } else {
                        return 0 unless (seq_key_exists($b, '71A'));
                    }
                }
                return 1;
            },
            err => 'D20',
        },

        # C5
        # ���� ����� �� ����� 52�, 26� ��� 77� ������������ � ������������������ �, �� ��� ���� ��
        # ������ �������������� �� � ����� �� ���������� ������������������ �. � ��������, ���� �����
        # �� ����� 52�, 26� ��� 77� ������������ � ����� ��� ����� �� ���������� ������������������
        # �, �� ��� �� ������ �������������� � ������������������ � (��� ������ D18).
        {
            func => sub {
                my $doc = shift;
                my $a = _find_A_seq($doc->{data});
                my $bs = _find_B_seqs($doc->{data});
                if (seq_key_exists($a, '52[ABC]')) {
                    for my $b (@$bs) {
                        return 0 if (seq_key_exists($b, '52[ABC]'));
                    }
                }
                if (seq_key_exists($a, '26T')) {
                    for my $b (@$bs) {
                        return 0 if (seq_key_exists($b, '26T'));
                    }
                }
                if (seq_key_exists($a, '77B')) {
                    for my $b (@$bs) {
                        return 0 if (seq_key_exists($b, '77B'));
                    }
                }
                return 1;
            },
            err => 'D18',
        },

        # C6
        # ���� 36 (� ������������������ � ��� �) ������ �������������� � ��������� ���� � �����
        # �� ���������� ������������������ � ������������ ���� 33� � ����� ������ �������� �� ����
        # ������ � ���� 32�; �� ���� ��������� ������� ���� 36 �� ������ �������������� � ���������.
        # ���� ��������� ������������ ���� 36 (� ������������������ � ��� �), �� ���� 36 ������
        # �������������� ���� � ������������������ � � � ����� ��� �� ����������� �� � ����� ��
        # ���������� ������������������ �, ���� �� ���� ������������������� �, ��� ���� ���� 32� �
        # 33� � ������� ������ ����� � � ����� ��� �� ������ �������������� �� � ������������������ �,
        # �� � ��������� ������������������� �. (��� ������ D22).
        {
            func => sub {
                my $doc = shift;
                my $a = _find_A_seq($doc->{data});
                my $bs = _find_B_seqs($doc->{data});

                # ��������, ������ �� �������������� � ��������� ���� 36
                my $musthave_36;
                for my $b (@$bs) {
                    my ($cur_33b) = (seq_get_first($b, '33B') =~ /([A-Z]{3})/);
                    my ($cur_32b) = (seq_get_first($b, '32B') =~ /([A-Z]{3})/);
                    $musthave_36 = 1 if ($cur_33b ne $cur_32b);
                }

                return 0 if ($musthave_36 && !$doc->key_exists('36'));
                return 0 if (!$musthave_36 && $doc->key_exists('36'));

                if ($musthave_36) {
                    # ���� 36 ������ ���� � ��� ����, ������ ��������, ��� ��� ���������
                    if (seq_key_exists($a, '36')) {
                        # ���� ���� 36 ���� � A, ��������, ��� ��� ��� �� � ����� �� B
                        for my $b (@$bs) {
                            return 0 if (seq_key_exists($b, '36'));
                        }
                    }
                    else {
                        # ���� 36 ����������� � A, ������ ��������, ��� ��� ���� � ��� B, � �������
                        # ���� ����� 33B � 32B ����������, a � ��������� ���
                        for my $b (@$bs) {
                            my ($cur_33b) = (seq_get_first($b, '33B') =~ /([A-Z]{3})/);
                            my ($cur_32b) = (seq_get_first($b, '32B') =~ /([A-Z]{3})/);
                            if ($cur_33b ne $cur_32b) {
                                return 0 unless (seq_key_exists($b, '36'));
                            }
                            else {
                                return 0 if (seq_key_exists($b, '36'));
                            }
                        }
                    }
                }

                return 1;
            },
            err => 'D22',
        },

        # C7
        # ���� ���� 23 �������� ������� ����� "CHQB", �� � ���� 59� �� ������ �������������� �����
        # �����. �� ���� ��������� ������� ����� ����� ����������� ����������� (��� ������ D93).
        {
            func => sub {
                my $doc = shift;
                my $val_23 = $doc->get_first('23');
                my $vals_59a = $doc->get_all('59[A-Z]?');
                my $musthave_account = ($val_23 !~ /CHQB/);
                for my $v (@$vals_59a) {
                    if ($musthave_account) {
                        return 0 unless ($v =~ /^\//);
                    }
                    else {
                        return 0 if ($v =~ /^\//);
                    }
                }
                return 1;
            },
            err => 'D93',
        },

        # C8
        # ���� ���� ����� � ����� BIC ����������� � ���������� ������ � ��������� �������� ����� �����:
        # AD, AT, BE, BV, BG, CH, CY, CZ, DE, DK, EE, ES, FI, FR, GB, GF, GI, GP, GR, HU, IE, IS, IT,
        # LI, LT, LU, LV, MC, MQ, MT, NL, NO, PL, PM, PT, RE, RO, SE, SI, SJ, SK, SM, TF � VA, -
        # �� ���� 33� �������� ������������ � ������ �� ���������� ������������������ �, � ���������
        # ������� ���� 33� �������������� (��� ������ D49).
        {
            func => sub {
                my $doc = shift;
                    my ($sender_country) = $doc->sender =~ /^\S{4}(\S{2})/;
                    my ($receiver_country) = $doc->receiver =~ /^\S{4}(\S{2})/;
                    my $bs = _find_B_seqs($doc->{data});
                    my $country_regexp = 'AD|AT|BE|BV|BG|CH|CY|CZ|DE|DK|EE|ES|FI|FR|GB|GF|GI|GP|GR|HU|IE|IS|IT|LI|LT|LU|LV|MC|MQ|MT|NL|NO|PL|PM|PT|RE|RO|SE|SI|SJ|SK|SM|TF|VA';
                    if ($sender_country =~ /$country_regexp/ && $receiver_country =~ /$country_regexp/) {
                        for my $b (@$bs) {
                            return 0 unless (seq_key_exists($b, '33B'));
                        }
                    }
                    return 1;
            },
            err => 'D49',
        },

        # �9
        # ���� ���� 71� � ������������������ � �������� ������� ����� �OUR�, �� ���� 71F �� ������
        # ��������������, � ���� 71G �������������� �� ���� ����������� ������������������ �
        # (��� ������ E13).
        # ���� ���� 71� � ������������������ � �������� ������� ����� �OUR�, �� � ��� �� ����������
        # ������������������ � ���� 71F �� ������������, � ���� 71G �������������� (��� ������ E13).
        {
            func => sub {
                my $doc = shift;
                my $a = _find_A_seq($doc->{data});
                my $bs = _find_B_seqs($doc->{data});
                if (seq_key_value_exists($a, '71A', 'OUR')) {
                    for my $b (@$bs) {
                        return 0 if (seq_key_exists($b, '71F'));
                    }
                }
                for my $b (@$bs) {
                    if (seq_key_value_exists($b, '71A', 'OUR')) {
                        return 0 if (seq_key_exists($b, '71F'));
                    }
                }
                return 1;
            },
            err => 'E13',
        },
        # ���� ���� 71� � ������������������ � �������� ������� ����� �SHA�, �� �� ���� �����������
        # ������������������ � ���� 71F ��������������, � ���� 71G �� ������������ (��� ������ D50).
        # ���� ���� 71� � ������������������ � �������� ������� ����� �SHA�, �� � ��� �� ����������
        # ������������������ � ���� 71F �������� ���������������, � ���� 71G �� ������������
        # (��� ������ D50).
        {
            func => sub {
                my $doc = shift;
                my $a = _find_A_seq($doc->{data});
                my $bs = _find_B_seqs($doc->{data});
                if (seq_key_value_exists($a, '71A', 'SHA')) {
                    for my $b (@$bs) {
                        return 0 if (seq_key_exists($b, '71G'));
                    }
                }
                for my $b (@$bs) {
                    if (seq_key_value_exists($b, '71A', 'SHA')) {
                        return 0 if (seq_key_exists($b, '71G'));
                    }
                }
                return 1;
            },
            err => 'D50',
        },
        # ���� ���� 71� � ������������������ � �������� ������� ����� �BEN�, �� � ������ �� ����������
        # ������������������ � ������ �������������� ���� �� ���� ���� 71F, � ���� 71G �� ������������
        # (��� ������ E15).
        # ���� ���� 71� � ������������������ � �������� ������� ����� �BEN�, �� � ��� �� ����������
        # ������������������ � ������ �������������� ���� �� ���� ���� 71F, � ���� 71G �� ������������
        # (��� ������ E15).
        {
            func => sub {
                my $doc = shift;
                my $a = _find_A_seq($doc->{data});
                my $bs = _find_B_seqs($doc->{data});
                if (seq_key_value_exists($a, '71A', 'BEN')) {
                    for my $b (@$bs) {
                        return 0 unless (seq_key_exists($b, '71F'));
                        return 0 if (seq_key_exists($b, '71G'));
                    }
                }
                for my $b (@$bs) {
                    if (seq_key_value_exists($b, '71A', 'BEN')) {
                        return 0 unless (seq_key_exists($b, '71F'));
                        return 0 if (seq_key_exists($b, '71G'));
                    }
                }
                return 1;
            },
            err => 'E15',
        },

        # �10
        # ���� � ����� �� ���������� ������������������ � ������������ ���� ���� 71F (���� �� ���� ���),
        # ���� ���� 71G, �� � ��� �� ���������� ������������������ � ���� 33� �������� ������������
        # (��� ������ D51).
        {
            func => sub {
                my $doc = shift;
                my $bs = _find_B_seqs($doc->{data});
                for my $b (@$bs) {
                    if (seq_key_exists($b, '71[FG]')) {
                        return 0 unless (seq_key_exists($b, '33B'));
                    }
                }
                return 1;
            },
            err => 'D51',
        },

        # �11
        # ���� � ����� �� ���������� ������������������ � ������������ ���� 71G, �� ���� 71G
        # ������������ � ������������������ � (��� ������ D79).
        {
            func => sub {
                my $doc = shift;
                my $bs = _find_B_seqs($doc->{data});
                my $c = _find_C_seq($doc->{data});
                for my $b (@$bs) {
                    if (seq_key_exists($b, '71G')) {
                        return 0 unless (seq_key_exists($c, '71G'));
                        return 1;
                    }
                }
                return 1;
            },
            err => 'D79',
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
    for my $item (@$data) {
        if ($item->{key} eq '21') {
            push @seqs, [];
            $seq_started = 1;
        }
        if ($item->{key} eq '32A') {
            $seq_started = 0;
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
    for my $item (@$data) {
        if ($item->{key} eq '32A') {
            $seq_started = 1;
        }
        if ($seq_started) {
            push @seq, $item;
        }
    }
    return \@seq;
}

1;