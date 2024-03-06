package CyberFT::SWIFT::Types::MT107;
use CyberFT::SWIFT::Types::Utils;
use strict;
use Data::Dumper;

our $ValidationProfile = {

    fields => [
        # Описаны только обязательные поля обязательных последовательностей.
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
        {
            key => '32A',
            required => 1,
        },
    ],

    rules => [

        # Проверим обязательные поля в последовательностях B
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

        # С1
        # Поле 23Е и второе повторение поля 50а (с опцией А или К) должны, независимо друг от друга,
        # присутствовать либо в последовательности А, либо в каждом из повторений последовательности В,
        # но не в обеих последовательностях одновременно (Код ошибки С86):
        {
            func => sub {
                my $doc = shift;
                my $a = _find_A_seq($doc->{data});
                my $bs = _find_B_seqs($doc->{data});
                for my $k (qw'  23E  50[AK]  ') {
                    my $exists_in_a = seq_key_exists($a, $k);
                    for my $b (@$bs) {
                        if ($exists_in_a) {
                            return 0 if (seq_key_exists($b, $k));
                        } else {
                            return 0 unless (seq_key_exists($b, $k));
                        }
                    }
                }
                return 1;
            },
            err => 'C86',
        },

        # С2
        # Если в последовательности А присутствуют поля 21Е, 26Т, 77В, 71А, 52а и 50а (с опцией С или L),
        # то эти поля, независимо друг от друга, не должны присутствовать ни в одном из повторений
        # последовательности В. И наоборот, если поля 21Е, 26Т, 77В, 71А, 52а и 50а (с опцией С или L)
        # присутствуют в одном или более из повторений последовательности В, то они не должны
        # использоваться в последовательности А (Код ошибки D73).
        {
            func => sub {
                my $doc = shift;
                my $a = _find_A_seq($doc->{data});
                my $bs = _find_B_seqs($doc->{data});
                for my $k (qw`  21E  26T  77B  71A  52[A-Z]  50[CL]  `) {
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

        # С3
        # Если поле 21Е присутствует в последовательности А, то поле 50а (с опцией А или К) также
        # должно присутствовать в последовательности А. Если в любом из повторений последовательности В
        # присутствует поле 21Е, то в этом же повторении должно присутствовать также поле 50а
        # (с опцией А или К) (Код ошибки D77).
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

        # C4
        # Если в последовательности А поле 23Е присутствует и содержит кодовое слово RTND,
        # должно также присутствовать поле 72. Во всех остальных случаях - то есть, когда поле
        # 23Е отсутствует или не содержит «RTND» - поле 72 не должно использоваться (Код ошибки С82).
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

        # C5
        # Если, независимо друг от друга, поля 71F и 71G присутствуют в одном или более из повторений
        # последовательности В, то они также должны присутствовать в последовательности С, и наоборот
        # (Код ошибки D79).
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
                return 0 if ($e71g && !seq_key_exists($c, '71G'));
                return 1;
            },
            err => 'D79',
        },

        # C6
        # Если в любом из повторений последовательности В присутствует поле 33В, то код валюты,
        # или сумма, или оба этих параметра должны быть различными в полях 33В и 32В (Код ошибки D21).
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

        # C7
        # Если в любом повторении последовательности В присутствует поле 33В, и код валюты в поле 32В
        # отличается от кода валюты в поле 33В, то в сообщении должно присутствовать поле 36.
        # В остальных случаях поле 36 не используется (Код ошибки D75).
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

        # С8
        # Общая сумма значений всех повторений поля 32В в последовательности В должна быть указана
        # либо в поле 32В последовательности С - если сумма не включает расходы, либо в поле 19
        # последовательности С. В первом случае поле 19 не должно использоваться (Код ошибки D80).
        # Во втором случае значение поля 19 должно быть равно общей сумме всех повторений поля 32В в
        # последовательности В (Код ошибки С01).
        {
            func => sub {
                my $doc = shift;
                my $c = _find_C_seq($doc->{data});
                my $bs = _find_B_seqs($doc->{data});

                # посчитаем сумму всех 32В в последовательностях B
                my $sum_b_32b = 0;
                for my $b (@$bs) {
                    my ($val) = (seq_get_first($b, '32B') =~ /([\d\.\,]+)/);
                    $val =~ s/,/./;
                    $sum_b_32b += $val;
                }
                $sum_b_32b = sprintf("%.4f", $sum_b_32b);

                # сначала проверим, записана ли сумма в поле 32B последовательности С
                if (seq_key_exists($c, '32B')) {
                    my ($val_c_32b) = (seq_get_first($c, '32B') =~ /([\d\.\,]+)/);
                    $val_c_32b =~ s/,/./;
                    $val_c_32b = sprintf("%.4f", $val_c_32b);
                    if ($val_c_32b == $sum_b_32b) {
                        return 0 if ($doc->key_exists('19'));
                        return 1;
                    }
                }

                # сюда мы придем, только если в поле 32B последовательности С нет полной суммы, значит
                # она должна быть в поле 19 последовательности С
                my ($val_c_19) = (seq_get_first($c, '19') =~ /([\d\.\,]+)/);
                $val_c_19 =~ s/,/./;
                $val_c_19 = sprintf("%.4f", $val_c_19);
                return 0 if ($val_c_19 != $sum_b_32b);
                return 1;
            },
            err => 'C01',
        },

        # С9
        # Код валюты в полях 32В и 71G в последовательностях В и С должен быть одинаковым во всех
        # повторениях этих полей в сообщении (Код ошибки С02).
        # Код валюты в полях расходов 71F (в последовательностях В и С) должен быть одинаковым во
        # всех повторениях этого поля в сообщении (Код ошибки С02).
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