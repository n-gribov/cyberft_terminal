package CyberFT::SWIFT::Types::MT102;
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
        # Если в последовательности С присутствует поле 19, то указанная в нем сумма должна равняться
        # результату сложения сумм во всех повторениях поля 32В (Код ошибки С01).
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

        # С2
        # Код валюты в полях 71G, 32В и 32А должен быть одинаковым во всех повторениях этих полей в
        # сообщении (Код ошибки С02).
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
        # Поле 50а должно присутствовать либо в последовательности А, либо в каждом из повторений
        # последовательности В, но никогда не должно присутствовать или отсутствовать одновременно в
        # обеих этих последовательностях (Код ошибки D17).
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
        # Поле 71А должно присутствовать либо в последовательности А, либо в каждом из повторений
        # последовательности В, но никогда не должно присутствовать или отсутствовать одновременно
        # в обеих этих последовательностях (Код ошибки D20).
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
        # Если любое из полей 52а, 26Т или 77В присутствует в последовательности А, то это поле не
        # должно присутствовать ни в одном из повторений последовательности В. И наоборот, если любое
        # из полей 52а, 26Т или 77В присутствует в одном или более из повторений последовательности
        # В, то оно не должно присутствовать в последовательности А (Код ошибки D18).
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
        # Поле 36 (в последовательности А или В) должно использоваться в сообщении если в любом
        # из повторений последовательности В присутствует поле 33В с кодом валюты отличным от кода
        # валюты в поле 32В; во всех остальных случаях поле 36 не должно присутствовать в сообщении.
        # Если требуется использовать поле 36 (в последовательности А или В), то поле 36 должно
        # присутствовать ЛИБО в последовательности А – и тогда оно не допускается ни в одном из
        # повторений последовательности В, ЛИБО во всех последовательностях В, где есть поля 32В и
        # 33В с разными кодами валют – и тогда оно не должно присутствовать ни в последовательности А,
        # ни в остальных последовательностях В. (Код ошибки D22).
        {
            func => sub {
                my $doc = shift;
                my $a = _find_A_seq($doc->{data});
                my $bs = _find_B_seqs($doc->{data});

                # проверим, должно ли присутствовать в сообщении поле 36
                my $musthave_36;
                for my $b (@$bs) {
                    my ($cur_33b) = (seq_get_first($b, '33B') =~ /([A-Z]{3})/);
                    my ($cur_32b) = (seq_get_first($b, '32B') =~ /([A-Z]{3})/);
                    $musthave_36 = 1 if ($cur_33b ne $cur_32b);
                }

                return 0 if ($musthave_36 && !$doc->key_exists('36'));
                return 0 if (!$musthave_36 && $doc->key_exists('36'));

                if ($musthave_36) {
                    # поле 36 должно быть и оно есть, теперь проверим, где оно находится
                    if (seq_key_exists($a, '36')) {
                        # если поле 36 есть в A, проверим, что его нет ни в одном из B
                        for my $b (@$bs) {
                            return 0 if (seq_key_exists($b, '36'));
                        }
                    }
                    else {
                        # поле 36 отсутствует в A, значит проверим, что оно есть в тех B, в которых
                        # коды валют 33B и 32B отличаются, a в остальных нет
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
        # Если поле 23 содержит кодовое слово "CHQB", то в поле 59а не должен присутствовать номер
        # счета. Во всех остальных случаях номер счета указывается обязательно (Код ошибки D93).
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
        # Если коды стран в кодах BIC Отправителя и Получателя входят в следующий перечень кодов стран:
        # AD, AT, BE, BV, BG, CH, CY, CZ, DE, DK, EE, ES, FI, FR, GB, GF, GI, GP, GR, HU, IE, IS, IT,
        # LI, LT, LU, LV, MC, MQ, MT, NL, NO, PL, PM, PT, RE, RO, SE, SI, SJ, SK, SM, TF и VA, -
        # то поле 33В является обязательным в каждом из повторений последовательности В, в остальных
        # случаях поле 33В необязательное (Код ошибки D49).
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

        # С9
        # Если поле 71А в последовательности А содержит кодовое слово «OUR», то поле 71F не должно
        # использоваться, а поле 71G необязательное во всех повторениях последовательности В
        # (Код ошибки E13).
        # Если поле 71А в последовательности В содержит кодовое слово «OUR», то в том же повторении
        # последовательности В поле 71F не используется, а поле 71G необязательное (Код ошибки E13).
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
        # Если поле 71А в последовательности А содержит кодовое слово «SHA», то во всех повторениях
        # последовательности В поля 71F необязательные, а поле 71G не используется (Код ошибки D50).
        # Если поле 71А в последовательности В содержит кодовое слово «SHA», то в том же повторении
        # последовательности В поля 71F являются необязательными, а поле 71G не используется
        # (Код ошибки D50).
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
        # Если поле 71А в последовательности А содержит кодовое слово «BEN», то в каждом из повторений
        # последовательности В должно присутствовать хотя бы одно поле 71F, а поле 71G не используется
        # (Код ошибки E15).
        # Если поле 71А в последовательности В содержит кодовое слово «BEN», то в том же повторении
        # последовательности В должно присутствовать хотя бы одно поле 71F, а поле 71G не используется
        # (Код ошибки E15).
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

        # С10
        # Если в любом из повторений последовательности В присутствует либо поле 71F (хотя бы один раз),
        # либо поле 71G, то в том же повторении последовательности В поле 33В является обязательным
        # (Код ошибки D51).
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

        # С11
        # Если в любом из повторений последовательности В присутствует поле 71G, то поле 71G
        # обязательное в последовательности С (Код ошибки D79).
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