package CyberFT::SWIFT::Types::MT104;
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

        # C1
        # Если поле 23Е присутствует в последовательности А и содержит код RFDD, то поле 23Е
        # должно также присутствовать в каждом из повторений последовательности В.
        # Если поле 23Е присутствует в последовательности А и не содержит код RFDD, то поле 23Е
        # не должно использоваться ни в одном из повторений последовательности В.
        # Если поле 23Е не используется в последовательности А, то оно обязательно должно
        # использоваться во всех повторениях последовательности В. (Код ошибки С75):
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

        # С2
        # Поле 50а (с опцией А или К) должно присутствовать либо в последовательности А
        # (порядковый номер 8), либо в каждом из повторений последовательности В (порядковый номер 21),
        # но никогда не может присутствовать или отсутствовать в обеих последовательностях одновременно
        # (Код ошибки С76)
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

        # С3
        # Если в последовательности А присутствуют поля 21Е, 26Т, 52а, 71А, 77В и 50а (с опцией С или L),
        # то эти поля, независимо друг от друга, не должны присутствовать ни в одном из повторений
        # последовательности В. И наоборот, если поля 21Е, 26Т, 52а, 71А, 77В и 50а (с опцией С или L)
        # присутствуют в одном или более из повторений последовательности В, то они не должны
        # использоваться в последовательности А (Код ошибки D73).
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

        # С4
        # Если поле 21Е присутствует в последовательности А, то второе повторение поля 50а (с опцией А или К)
        # также должно присутствовать в последовательности А. Если в любом из повторений последовательности
        # В присутствует поле 21Е, то в этом же повторении должно присутствовать также и второе
        # поле 50а (с опцией А или К) (Код ошибки D77).
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
        # Если в последовательности А присутствует поле 23Е, и в нем использован код RTND, то должно
        # также присутствовать и поле 72. Во всех остальных случаях - то есть, когда поле 23Е
        # отсутствует или не содержит «RTND» - поле 72 не должно использоваться (Код ошибки С82).
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
        # Если поле 71F присутствует в одном или более из повторений последовательности В,
        # то оно также должно присутствовать в последовательности С, и наоборот (Код ошибки D79).
        # Если поле 71G присутствует в одном или более из повторений последовательности В,
        # то оно также должно присутствовать в последовательности С, и наоборот (Код ошибки D79).
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
        # Если в любом из повторений последовательности В присутствует поле 33В, то код валюты,
        # или сумма, или оба этих параметра, указанные в полях 33В и 32В, должны различаться
        # (Код ошибки D21).
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
        # Если в любом повторении последовательности В присутствует поле 33В, и код валюты в поле
        # 32В отличается от кода валюты в поле 33В, то должно присутствовать поле 36. В остальных
        # случаях поле 36 не используется (Код ошибки D75).
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

        # С9
        # Если используется последовательность С, и если сумма в поле 32В последовательности С
        # равна общей сумме всех повторений поля 32В в последовательности В, то поле 19 не должно
        # присутствовать; во всех остальных случаях поле 19 обязательно должно использоваться
        # (Код ошибки D80).
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

        # С10
        # Если поле 19 присутствует в последовательности С, то указанная в нем сумма должна быть
        # равна общей сумме всех повторений поля 32В в последовательности В (Код ошибки С01).
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

        # С11
        # Код валюты в полях 32В и 71 G последовательностей В и С должен быть одинаковым во всех
        # повторениях этих полей в сообщении (Код ошибки С02).
        # Код валюты в полях расходов 71F (в последовательностях В и С) должен быть одинаковым во
        # всех повторениях этих полей в сообщении (Код ошибки С02).
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
        # Если в последовательности А присутствует поле 23Е, и в нем указан код RFDD, то:
        # • в последовательности А поле 21R необязательное
        # • и в последовательности В поля 21E, 50а (с опцией А или К), 52a, 71F и 71G не должны
        # использоваться
        # • и последовательность С не должна использоваться
        # В остальных случаях, то есть, если поле 23Е отсутствует в последовательности А или не
        # содержит код RFDD:
        # • в последовательности А поле 21R не должно использоваться
        # • и в последовательности В поля 21E, 50а (с опцией А или К), 52a, 71F и 71G необязательные
        # • и последовательность С должна использоваться. (Код ошибки С96).
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
                # видимо, началась последовательность C.
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
                    # видимо, началась последовательность C.
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