package CyberFT::SWIFT::Types::MT564;
use CyberFT::SWIFT::Types::Utils;
use strict;
use Storable ();
use Data::Dumper;

our $ValidationProfile = {

    fields => [
        # Описаны только обязательные поля обязательных последовательностей.
        {
            key => '16R',
            required => 1,
        },
        {
            key => '16S',
            required => 1,
        },
        # A
        {
            key => '20C',
            required => 1,
        },
        {
            key => '23G',
            required => 1,
        },
        {
            key => '22F',
            required => 1,
        },
        {
            key => '25D',
            required => 1,
        },
        # B
        {
            key => '35B',
            required => 1,
        },
        {
            key => '97a',
            key_regexp => '97[AC]',
            required => 1,
        },
    ],

    rules => [
        # Проверка обязательных последовательностей и полей
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                
                my $sequences = {
                    'GENL' => {
                        name => 'A',
                        required => 1,
                        required_fields => ['20C', '23G', '22F', '25D'],
                    },
                    'GENL/LINK' => {
                        name => 'A1',
                        required => 0,
                        required_fields => ['20C'],
                    },
                    'USECU' => {
                        name => 'B',
                        required => 1,
                        required_fields => ['35B'],
                    },
                    'USECU/FIA' => {
                        name => 'B1',
                        required => 0,
                        required_fields => [],
                    },
                    'USECU/ACCTINFO' => {
                        name => 'B2',
                        required => 1,
                        required_fields => ['97[AC]'],
                    },
                    'INTSEC' => {
                        name => 'C',
                        required => 0,
                        required_fields => ['35B', '98[AB]'],
                    },
                    'CADETL' => {
                        name => 'D',
                        required => 0,
                        required_fields => [],
                    },
                    'CAOPTN' => {
                        name => 'E',
                        required => 0,
                        required_fields => ['13A', '22F', '17B'],
                    },
                    'CAOPTN/SECMOVE' => {
                        name => 'E1',
                        required => 0,
                        required_fields => ['22[FH]', '35B', '98[ABCD]'],
                    },
                    'CAOPTN/SECMOVE/FIA' => {
                        name => 'E1a',
                        required => 0,
                        required_fields => [],
                    },
                    'CAOPTN/CASHMOVE' => {
                        name => 'E2',
                        required => 0,
                        required_fields => ['22[FH]', '98[ABC]'],
                    },
                    'ADDINFO' => {
                        name => 'F',
                        required => 0,
                        required_fields => [],
                    },
                };
                
                my $err = _check_required($tree, $sequences);
                if (defined $err) {
                    return (0, $err);
                }
                
                return 1;
            },
            err => 'Missing required sequence or field',
        },

        # С1
        # Если поле :19B::RESU «Сумма после конвертации» присутствует в последовательнгости Е2,
        # тогда поле :92В::EXCH «Курс конвертации» также должно присутствовать в той же
        # (под)последовательности. Если поле «Сумма после конвертации» отсутствует, то поле
        # «Курс конвертации» является необязательным (Код ошибки Е62).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);

                my $branches_e = $tree->{'CAOPTN'} || [];
                for my $branch_e (@$branches_e) {
                    my $branches_e2 = $branch_e->{'CASHMOVE'} || [];
                    for my $branch_e2 (@$branches_e2) {
                        if (seq_key_value_exists($branch_e2->{fields}, '19B', ':RESU')) {
                            return 0 unless (seq_key_value_exists($branch_e2->{fields}, '92B', ':EXCH'));
                        }
                    }
                }

                return 1;
            },
            err => 'E62',
        },

        # С2
        # Если счета депо не указываются, то есть если в любом из повторений подпоследовательности
        # В2 присутствует поле :97C::SAFE//GENR, то:
        # • подпоследовательность В2 «Информация о счете» не должна повторяться в этом сообщении
        # • в подпоследовательности В2 «Информация о счете» не должно присутствовать поле 93a
        # «Остаток ценных бумаг»
        # • в подпоследовательности Е1 «Движение ценных бумаг» не должно присутствовать поле 36B
        # «Количество финансового инструмента»
        # • в подпоследовательности Е2 «Движение денежных средств» не должно присутствовать поле 19В
        # «Сумма» (Код ошибки Е94).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);

                my $branches_b2 = $tree->{'USECU'}->[0]->{'ACCTINFO'} || [];
                if (scalar @$branches_b2 == 0) {
                    return 0;
                }

                my $flag = 0;
                for my $branch_b2 (@$branches_b2) {
                    if (seq_key_value_exists($branch_b2->{fields}, '97C', ':SAFE//GENR')) {
                        $flag = 1;
                        last;
                    }
                }

                if ($flag) {
                    if (scalar @$branches_b2 != 1) {
                        return 0;
                    }
                    if (seq_key_exists($branches_b2->[0]->{fields}, '93[A-Z]')) {
                        return 0;
                    }
                    my $branches_e = $tree->{'CAOPTN'} || [];
                    for my $branch_e (@$branches_e) {
                        my $branches_e1 = $branch_e->{'SECMOVE'} || [];
                        for my $branch_e1 (@$branches_e1) {
                            if (seq_key_exists($branch_e1->{fields}, '36B')) {
                                return 0;
                            }
                        }
                        my $branches_e2 = $branch_e->{'CASHMOVE'} || [];
                        for my $branch_e2 (@$branches_e2) {
                            if (seq_key_exists($branch_e2->{fields}, '19B')) {
                                return 0;
                            }
                        }
                    }
                }

                return 1;
            },
            err => 'E94',
        },

        # С3
        # Если в последовательности А присутствует поле :23G:REPE, то: когда присутствует
        # последовательность С, поле :36a::QINT является обязательным (Код ошибки Е02).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);

                my $branch_a = $tree->{'GENL'}->[0] || {};
                if (seq_key_value_exists($branch_a->{fields}, '23G', 'REPE')) {
                    my $branch_c = $tree->{'INTSEC'}->[0] || undef;
                    if (defined $branch_c) {
                        unless (seq_key_value_exists($branch_c->{fields}, '36[A-Z]', ':QINT')) {
                            return 0;
                        }
                    }
                }

                return 1;
            },
            err => 'E02',
        },

        # С4
        # Если в последовательности А присутствует поле :22F::CAEV//OTHR, то в сообщении должна
        # использоваться последовательность F и хотя бы одно поле :70E::ADTX должно присутствовать
        # в сообщении Код ошибки Е03.
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);

                my $branch_a = $tree->{'GENL'}->[0] || {};
                if (seq_key_value_exists($branch_a->{fields}, '22F', ':CAEV//OTHR')) {
                    my $branch_f = $tree->{'ADDINFO'}->[0] || undef;
                    unless (defined $branch_f) {
                        return 0;
                    }
                    unless (seq_key_value_exists($branch_f->{fields}, '70E', ':ADTX')) {
                        return 0;
                    }
                }

                return 1;
            },
            err => 'E03',
        },

        # С5
        # Если в любом из повторений последовательности Е присутствует поле :22F::CAОР//OTHR, то в
        # том же повторении последовательности Е поле :70E::ADTX является обязательным (Код ошибки Е79).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);

                my $branches_e = $tree->{'CAOPTN'} || [];
                for my $branch_e (@$branches_e) {
                    if (seq_key_value_exists($branch_e->{fields}, '22F', ':CAOP//OTHR')) {
                        unless (seq_key_value_exists($branch_e->{fields}, '70E', ':ADTX')) {
                            return 0;
                        }
                    }
                }

                return 1;
            },
            err => 'E79',
        },

        # С6
        # В любом повторении последовательности Е и подпоследовательности Е2,:
        # если присутствует поле ::92J::TAXЕ, то в том же повторении этой последовательности должно
        # присутствовать поле :92F::GRSS (Код ошибки Е80).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);

                my $branches_e = $tree->{'CAOPTN'} || [];
                for my $branch_e (@$branches_e) {
                    if (seq_key_value_exists($branch_e->{fields}, '92J', ':TAXE')) {
                        unless (seq_key_value_exists($branch_e->{fields}, '92F', ':GRSS')) {
                            return 0;
                        }
                    }
                    my $branches_e2 = $branch_e->{'CASHMOVE'} || [];
                    for my $branch_e2 (@$branches_e2) {
                        if (seq_key_value_exists($branch_e2->{fields}, '92J', ':TAXE')) {
                            unless (seq_key_value_exists($branch_e2->{fields}, '92F', ':GRSS')) {
                                return 0;
                            }
                        }
                    }
                }

                return 1;
            },
            err => 'E80',
        },

        # С7
        # Если в последовательности А присутствует поле :22F::CAEV//RHDI, то последовательность С
        # не используется.
        # Если в последовательности А присутствует поле :22F::CAEV//RHTS, то последовательность С
        # является обязательной.
        # Если в последовательности А присутствует поле :22F::CAEV//INFO, то указание
        # последовательности С и последовательности E не допускается (Код ошибки: E01).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);

                my $branch_a = $tree->{'GENL'}->[0] || {};
                if (seq_key_value_exists($branch_a->{fields}, '22F', ':CAEV//RHDI')) {
                    if (defined $tree->{'INTSEC'}->[0]) {
                        return 0;
                    }
                }
                elsif (seq_key_value_exists($branch_a->{fields}, '22F', ':CAEV//RHTS')) {
                    unless (defined $tree->{'INTSEC'}->[0]) {
                        return 0;
                    }
                }
                elsif (seq_key_value_exists($branch_a->{fields}, '22F', ':CAEV//INFO')) {
                    if (defined $tree->{'INTSEC'}->[0]) {
                        return 0;
                    }
                    if (defined $tree->{'CAOPTN'}->[0]) {
                        return 0;
                    }
                }

                return 1;
            },
            err => 'E01',
        },

        # С8
        # В любом повторении последовательности Е и подпоследовательности Е2
        #   * поле :92F::GRSS может быть использовано только один раз и поле :92K::GRSS может быть
        #     использовано только один раз и оба эти поля :92F::GRSS и :92K::GRSS не могут быть
        #     использованы одновременно,
        #   * поле :92F::NETT может быть использовано только один раз и поле :92K::NETT может быть
        #     использовано только один раз и оба эти поля :92F::NETT и :92K::NETT не могут быть
        #     использованы одновременно,
        # В любом повторении подпоследовательности Е1 и подпоследовательности Е2 поле :92A::TAXC
        # может быть использовано только один раз и поле :92F::TAXC может быть использовано только
        # один раз и поле :92K::TAXC может быть использовано только один раз и все эти три поля
        # :92A::TAXC, :92F::TAXC и :92K::TAXC не могут быть использованы ни два и ни три одновременно
        # (Код ошибки Е77).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);

                my $branches_e = $tree->{'CAOPTN'} || [];
                for my $branch_e (@$branches_e) {
                    my $cnt1 = seq_key_value_count($branch_e->{fields}, '92F', ':GRSS');
                    my $cnt2 = seq_key_value_count($branch_e->{fields}, '92K', ':GRSS');
                    if ($cnt1 + $cnt2 > 1) {
                        return 0;
                    }
                    my $branches_e2 = $branch_e->{'CASHMOVE'} || [];
                    for my $branch_e2 (@$branches_e2) {
                        my $cnt1 = seq_key_value_count($branch_e2->{fields}, '92F', ':GRSS');
                        my $cnt2 = seq_key_value_count($branch_e2->{fields}, '92K', ':GRSS');
                        if ($cnt1 + $cnt2 > 1) {
                            return 0;
                        }
                    }
                }
                for my $branch_e (@$branches_e) {
                    my $cnt1 = seq_key_value_count($branch_e->{fields}, '92F', ':NETT');
                    my $cnt2 = seq_key_value_count($branch_e->{fields}, '92K', ':NETT');
                    if ($cnt1 + $cnt2 > 1) {
                        return 0;
                    }
                    my $branches_e2 = $branch_e->{'CASHMOVE'} || [];
                    for my $branch_e2 (@$branches_e2) {
                        my $cnt1 = seq_key_value_count($branch_e2->{fields}, '92F', ':NETT');
                        my $cnt2 = seq_key_value_count($branch_e2->{fields}, '92K', ':NETT');
                        if ($cnt1 + $cnt2 > 1) {
                            return 0;
                        }
                    }
                }
                for my $branch_e (@$branches_e) {
                    my $branches_e1 = $branch_e->{'SECMOVE'} || [];
                    for my $branch_e1 (@$branches_e1) {
                        my $cnt1 = seq_key_value_count($branch_e1->{fields}, '92A', ':TAXC');
                        my $cnt2 = seq_key_value_count($branch_e1->{fields}, '92F', ':TAXC');
                        my $cnt3 = seq_key_value_count($branch_e1->{fields}, '92K', ':TAXC');
                        if ($cnt1 + $cnt2 + $cnt3 > 1) {
                            return 0;
                        }
                    }
                    my $branches_e2 = $branch_e->{'CASHMOVE'} || [];
                    for my $branch_e2 (@$branches_e2) {
                        my $cnt1 = seq_key_value_count($branch_e2->{fields}, '92A', ':TAXC');
                        my $cnt2 = seq_key_value_count($branch_e2->{fields}, '92F', ':TAXC');
                        my $cnt3 = seq_key_value_count($branch_e2->{fields}, '92K', ':TAXC');
                        if ($cnt1 + $cnt2 + $cnt3 > 1) {
                            return 0;
                        }
                    }
                }

                return 1;
            },
            err => 'E77',
        },

        # С9
        # В любом повторении последовательности Е и подпоследовательности Е2:
        #   * если поле :92J::GRSS используется более одного раза в сообщении, то для каждого повторения
        #     поля :92J::GRSS значения подполя «Код типа ставки» должны быть различны.
        #   * если поле :92J::TAXE используется более одного раза в сообщении, то для каждого повторения
        #     поля :92J::TAXE значения подполя «Код типа ставки» должны быть различны.
        #   * если поле :92J::NETT используется более одного раза в сообщении, то для каждого повторения
        #     поля :92J::NETT значения подполя «Код типа ставки» должны быть различны.
        # В любом повторении подпоследовательности E1 и подпоследовательности Е2, если поле :92J::TAXC
        # используется более одного раза в сообщении, то для каждого повторения поля :92J::TAXC значения
        # подполя «Код типа ставки» должны быть различны.
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);

                my $branches_e = $tree->{'CAOPTN'} || [];

                for my $code (qw'GRSS TAXE NETT') {
                    for my $branch_e (@$branches_e) {
                        my $dict = {};
                        my $vals = seq_get_all($branch_e->{fields}, '92J');
                        for my $v (@$vals) {
                            if ($v =~ /:$code\/[^\/]*?\/([^\/]{4})/) {
                                my $k = $1;
                                if ($dict->{$k} == 1) {
                                    return 0;
                                }
                                else {
                                    $dict->{$k} = 1;
                                }
                            }
                        }
                        my $branches_e2 = $branch_e->{'CASHMOVE'} || [];
                        for my $branch_e2 (@$branches_e2) {
                            my $dict = {};
                            my $vals = seq_get_all($branch_e2->{fields}, '92J');
                            for my $v (@$vals) {
                                if ($v =~ /:$code\/[^\/]*?\/([^\/]{4})/) {
                                    my $k = $1;
                                    if ($dict->{$k} == 1) {
                                        return 0;
                                    }
                                    else {
                                        $dict->{$k} = 1;
                                    }
                                }
                            }
                        }
                    }
                }

                for my $branch_e (@$branches_e) {
                    my $branches_e1 = $branch_e->{'SECMOVE'} || [];
                    for my $branch_e1 (@$branches_e1) {
                        my $dict = {};
                        my $vals = seq_get_all($branch_e1->{fields}, '92J');
                        for my $v (@$vals) {
                            if ($v =~ /:TAXC\/[^\/]*?\/([^\/]{4})/) {
                                my $k = $1;
                                if ($dict->{$k} == 1) {
                                    return 0;
                                }
                                else {
                                    $dict->{$k} = 1;
                                }
                            }
                        }
                    }
                    my $branches_e2 = $branch_e->{'CASHMOVE'} || [];
                    for my $branch_e2 (@$branches_e2) {
                        my $dict = {};
                        my $vals = seq_get_all($branch_e2->{fields}, '92J');
                        for my $v (@$vals) {
                            if ($v =~ /:TAXC\/[^\/]*?\/([^\/]{4})/) {
                                my $k = $1;
                                if ($dict->{$k} == 1) {
                                    return 0;
                                }
                                else {
                                    $dict->{$k} = 1;
                                }
                            }
                        }
                    }
                }

                return 1;
            },
            err => 'E78',
        },

        # С10
        # Если сообщение направляется для замены, уведомления о величине остатка или напоминания,
        # т.е. если поле 23 G «Функция сообщения» содержит код REPL, REPE или RMDR, в сообщении должна
        # присутствовать хотя бы одна подпоследовательность А1 «Связки», и в одном и только в одном
        # повторении А1 должно присутствовать поле :20C::PREV. Соответственно, в остальных повторениях
        # А1 поле :20C::PREV не допускается.
        # Если сообщение направляется для отмены или отзыва, т.е. если поле 23 G «Функция сообщения»
        # содержит код CANC или WITH, в сообщении может присутствовать необязательная
        # подпоследовательность А1 «Связки», и в одном и только в одном повторении А1 может
        # присутствовать поле :20C::PREV. Соответственно, в остальных повторениях А1 поле
        # :20C::PREV не допускается (Код ошибки :Е08).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $fields_a = $tree->{'GENL'}->[0]->{fields} || [];
                my $branches_a1 = $tree->{'GENL'}->[0]->{'LINK'} || [];
                if (seq_key_value_exists($fields_a, '23G', 'REPL|REPE|RMDR')) {
                    return 0 if (scalar @$branches_a1 < 1);
                    my $count = 0;
                    for my $b (@$branches_a1) {
                        if (seq_key_value_exists($b->{fields}, '20C', ':PREV')) {
                            $count++;
                        }
                    }
                    return 0 if ($count != 1);
                }
                elsif (seq_key_value_exists($fields_a, '23G', 'CANC|WITH')) {
                    my $count = 0;
                    for my $b (@$branches_a1) {
                        if (seq_key_value_exists($b->{fields}, '20C', ':PREV')) {
                            $count++;
                        }
                    }
                    return 0 if ($count > 1);
                }
                return 1;
            },
            err => 'E08',
        },

        # С11
        # Если в последовательности D присутствует поле :70E::NAME, то в последовательности А
        # должно присутствовать поле :22F::CAEV//CHAN, а в последовательности D должно присутствовать
        # поле :22F::CHAN//NAME (Код ошибки D99).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $branch_a = $tree->{'GENL'}->[0] || {};
                my $branch_d = $tree->{'CADETL'}->[0] || {};

                if (seq_key_value_exists($branch_d->{fields}, '70E', ':NAME')) {
                    unless (seq_key_value_exists($branch_a->{fields}, '22F', ':CAEV//CHAN')) {
                        return 0;
                    }
                    unless (seq_key_value_exists($branch_d->{fields}, '22F', ':CHAN//NAME')) {
                        return 0;
                    }
                }
                else {
                    if (seq_key_value_exists($branch_a->{fields}, '22F', ':CAEV//CHAN')) {
                        return 0;
                    }
                    if (seq_key_value_exists($branch_d->{fields}, '22F', ':CHAN//NAME')) {
                        return 0;
                    }
                }

                return 1;
            },
            err => 'D99',
        },

        # С12
        # Если в последовательности А присутствует поле :22F::CAEV//RHDI, то должна присутствовать
        # последовательность D и поле :22F::RHDI должно присутствовать в последовательности D
        # (Код ошибки Е06).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $branch_a = $tree->{'GENL'}->[0] || {};
                my $branch_d = $tree->{'CADETL'}->[0] || {};

                if (seq_key_value_exists($branch_a->{fields}, '22F', ':CAEV//RHDI')) {
                    unless (seq_key_value_exists($branch_d->{fields}, '22F', ':RHDI')) {
                        return 0;
                    }
                }

                return 1;
            },
            err => 'E06',
        },

        # С13
        # В любом повторении подпоследовательности В2 поле :93B::ELIG не может использоваться более
        # двух раз. Если это поле используется дважды, то в первом случае Код типа количества должен
        # быть «FAMT», а во втором случае Код типа количества должен быть «AMOR» (Код ошибки С71).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);

                my $branches_b2 = $tree->{'USECU'}->[0]->{'ACCTINFO'} || [];
                for my $b (@$branches_b2) {
                    my $counter = 0;
                    my ($first, $second);
                    for my $item (@{$b->{fields}}) {
                        if ($item->{key} =~ /93B/ && $item->{value} =~ /^:ELIG/) {
                            $counter++;
                            $first = $item->{value} if ($counter==1);
                            $second = $item->{value} if ($counter==2);
                            return 0 if ($counter==2 && ($first !~ /\/FAMT/ || $second !~ /\/AMOR/));
                            return 0 if ($counter>2);
                        }
                    }
                }

                return 1;
            },
            err => 'C71',
        },

        # С14
        # В любом повторении подпоследовательности E1 поле :36B::ENTL не может использоваться более
        # двух раз. Если это поле используется дважды, то в первом случае Код типа количества должен
        # быть «FAMT», а во втором случае Код типа количества должен быть «AMOR» (Код ошибки С72).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);

                my $branches_e = $tree->{'CAOPTN'} || [];
                for my $branch_e (@$branches_e) {
                    my $branches_e1 = $branch_e->{'SECMOVE'} || [];
                    for my $b (@$branches_e1) {
                        my $counter = 0;
                        my ($first, $second);
                        for my $item (@{$b->{fields}}) {
                            if ($item->{key} =~ /36B/ && $item->{value} =~ /^:ENTL/) {
                                $counter++;
                                $first = $item->{value} if ($counter==1);
                                $second = $item->{value} if ($counter==2);
                                return 0 if ($counter==2 && ($first !~ /\/FAMT/ || $second !~ /\/AMOR/));
                                return 0 if ($counter>2);
                            }
                        }
                    }
                }

                return 1;
            },
            err => 'C72',
        },

        # С15
        # Если сообщение направляется для замены (23G::REPL) то в последовательности А в поле
        # :25D::PROC не допускается использование кода «ENTL» и в последовательности D в поле
        # :22F::ADDB не допускается использование кода «CAPA». Если сообщение направляется для
        # уведомления о величине остатка (23G::REPE), то в последовательности A в поле :25D::PROC
        # не допускается использование кода «ENTL» и в последовательности D в поле :22F::ADDB
        # допускается использование необязательного кода «CAPA». (Код ошибки: Е09).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $fields_a = $tree->{'GENL'}->[0]->{fields} || [];
                my $fields_d = $tree->{'CADETL'}->[0]->{fields} || [];
                if (seq_key_value_exists($fields_a, '23G', 'REPL')) {
                    return 0 if (seq_key_value_exists($fields_a, '25D', ':PROC.*ENTL'));
                    return 0 if (seq_key_value_exists($fields_d, '22F', ':ADDB.*CAPA'));
                }
                elsif (seq_key_value_exists($fields_a, '23G', 'REPE')) {
                    return 0 if (seq_key_value_exists($fields_a, '25D', ':PROC.*ENTL'));
                }
                return 1;
            },
            err => 'E09',
        },

        # С16
        # Если сообщение содержит дополнительный бизнес процесс (23 G::ADDB), то последовательность
        # D «Детали корпоративного действия» обязательная, и в последовательности D обязательное
        # поле :22F::ADDB. Это поле может использоваться только один раз и должно содержать один из
        # следующих кодов: CLAI (Требование или компенсация), TAXR (Возврат налогов) или REVR
        # (Уведомление или обратная проводка) (Код ошибки Е11).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $fields_a = $tree->{'GENL'}->[0]->{fields} || [];
                my $fields_d = $tree->{'CADETL'}->[0]->{fields} || [];
                if (seq_key_value_exists($fields_a, '23G', 'ADDB')) {
                    return 0 unless (seq_key_value_count($fields_d, '22F', '^:ADDB') == 1);
                    return 0 unless (seq_key_value_exists($fields_d, '22F', '^:ADDB.*(CLAI|TAXR|REVR)'));
                }
                return 1;
            },
            err => 'E11',
        },

        # C17.
        # Если в любом повторении последовательности Е подполседовательности Е1 и Е2 отсутствуют,
        # тогда в последовательности D поле :98a::PAYD является необязательным, во всех остальных
        # случаях поле :98a::PAYD в последовательности D не допускается (Код ошибки Е24).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $branch_d = $tree->{'CADETL'}->[0] || undef;
                my $branches_e = $tree->{'CAOPTN'} || [];

                if (defined $branch_d) {
                    my $flag = 0;
                    for my $branch_e (@$branches_e) {
                        my $branches_e1 = $branch_e->{'SECMOVE'} || [];
                        my $branches_e2 = $branch_e->{'CASHMOVE'} || [];
                        if (scalar(@$branches_e1) > 0 || scalar(@$branches_e2) > 0) {
                            $flag = 1;
                            last;
                        }
                    }
                    if ($flag) {
                        if (seq_key_value_exists($branch_d->{fields}, '98[A-Z]', ':PAYD')) {
                            return 0;
                        }
                    }
                }

                return 1;
            },
            err => 'E24',
        },

        # C18.
        # Если сообщение новое (:23G::NEWM) и в последовательности A поле :25D::PROC указан код
        # «ENTL», то последовательность D «Детали корпоративного действия» является обязательной и
        # в последовательности D поле :22F::ADDB является обязательным и в одном повторении поля
        # :22F::ADDB должен быть указан код «CAPA» (Код ошибки: Е22).
        # Если сообщение новое (:23G::NEWM) и в последовательности A в поле :25D::PROC код «ENTL»
        # не указан, то в последовательности D «Детали корпоративного действия» поле :22F::ADDB код
        # «CAPA» не допускается (Код ошибки: Е22).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $fields_a = $tree->{'GENL'}->[0]->{fields} || [];
                my $fields_d = $tree->{'CADETL'}->[0]->{fields} || [];
                if (seq_key_value_exists($fields_a, '23G', 'NEWM')) {
                    if (seq_key_value_exists($fields_a, '25D', ':PROC.*ENTL')) {
                        return 0 unless (seq_key_value_exists($fields_d, '22F', '^:ADDB.*CAPA'));
                    }
                    else {
                        return 0 if (seq_key_value_exists($fields_d, '22F', '^:ADDB.*CAPA'));
                    }
                }
                return 1;
            },
            err => 'E22',
        },

        # С19
        # В любом повторении последовательности E если присутствует поле :92B::IDFX «Курс обмена,
        # объявленный эмитентом», должна присутствовать последовательность D, и поле :92a::DEVI
        # «Объявленная ставка» должна присутствовать в последовательности D (Код ошибки E21).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $fields_d = $tree->{'CADETL'}->[0]->{fields} || [];
                my $branches_e = $tree->{'CAOPTN'} || [];

                my $flag = 0;
                for my $branch_e (@$branches_e) {
                    if (seq_key_value_exists($branch_e->{fields}, '92B', ':IDFX')) {
                        $flag = 1;
                        last;
                    }
                }
                if ($flag) {
                    unless (seq_key_value_exists($fields_d, '92[A-Z]', ':DEVI')) {
                        return 0;
                    }
                }

                return 1;
            },
            err => 'E21',
        },

    ]
};

# Строим дерево (под)последовательностей. В основе функции лежит тот факт, что каждая последовательность
# начинается с поля 16R и заканчивается полем 16S с таким же содержанием. Последовательности могут быть вложенными.
sub _get_seq_tree {
    my %params = @_;
    my $data;

    if (defined $params{doc}) {
        # проверим, есть ли закэшированный результат
        if (defined $params{doc}->{__get_seq_tree_result__}) {
            return Storable::dclone($params{doc}->{__get_seq_tree_result__});
        }
        $data = $params{doc}->data;
    } else {
        $data = $params{data};
    }

    my $tree = {
        fields => [],
    };

    my $inner_seq_started = 0;
    my $inner_seq_name = '';
    my $inner_seq = [];

    for my $item (@$data) {
        my $key = $item->{key};
        my ($value) = ($item->{value} =~ /^\s*(.*?)\s*$/s);

        if ($key eq '16R' && !$inner_seq_started) {
            # начало подпоследовательности
            $inner_seq_started = 1;
            $inner_seq_name = $value;
            $inner_seq = [];
        }
        elsif ($key eq '16S' && $inner_seq_started && $inner_seq_name eq $value) {
            # конец подпоследовательности
            push @{$tree->{$inner_seq_name}}, _get_seq_tree(data => $inner_seq);
            $inner_seq_started = 0;
            $inner_seq_name = '';
            $inner_seq = [];
        }
        elsif ($inner_seq_started) {
            # поле внутри подпоследовательности
            push @$inner_seq, $item;
        }
        else {
            # поле, принадлежащее данной последовательности
            push @{$tree->{fields}}, $item;
        }
    }

    if (defined $params{doc}) {
        $params{doc}->{__get_seq_tree_result__} = $tree;
    }

    return $tree;
}

# Превращаем дерево (ветку) в плоский список полей.
sub _flatten_tree {
    my $tree = shift;
    my $items = [];

    for my $key (keys %$tree) {
        if ($key eq 'fields') {
            for my $item (@{$tree->{$key}}) {
                push @$items, $item;
            }
        }
        else {
            for my $branch (@{$tree->{$key}}) {
                my $branch_items = _flatten_tree($branch);
                push @$items, @$branch_items;
            }
        }
    }

    return $items;
}


# Рекурсивная проверка обязательных последовательностей и подпоследовательностей. 
sub _check_required_sequences {
    my $tree = shift;
    my $seqs = shift;
    
    my $_check_path;
    $_check_path = sub {
        my $tree = shift;
        my $path = shift;
        
        my ($first, $rest) = $path =~ /^([^\/]+)(?:\/(.+))?$/;
        my $branches = $tree->{$first} || undef;
        
        if ($rest) {
            if ($branches) {
                for my $b (@$branches) {
                    my $r = $_check_path->($b, $rest);
                    if (!$r) {
                        return $r;
                    }
                }
            }
        }
        else {
            if (!defined($branches) || scalar(@$branches) < 1) {
                return 0;
            }
        }
        
        return 1;
    };
    
    my @keys = sort {$seqs->{$a}->{name} cmp $seqs->{$b}->{name}} keys %$seqs;
    for my $path (@keys) {
        if ($seqs->{$path}->{required}) {
            my $r = $_check_path->($tree, $path);
            if (!$r) {
                return "Missing required sequence: " . $seqs->{$path}->{name};
            }
        }
    }
    
    return undef;
}

# Рекурсивная проверка обязательных полей в последовательностях и подпоследовательностях. 
sub _check_required_fields {
    my $tree = shift;
    my $seqs = shift;
    
    my $_check_path_fields;
    $_check_path_fields = sub {
        my $tree = shift;
        my $path = shift;
        my $fields = shift;
        
        my ($first, $rest) = $path =~ /^([^\/]+)(?:\/(.+))?$/;
        my $branches = $tree->{$first} || undef;
        
        if ($rest) {
            if ($branches) {
                for my $b (@$branches) {
                    my ($r, $f) = $_check_path_fields->($b, $rest, $fields);
                    if (!$r) {
                        return ($r, $f);
                    }
                }
            }
        }
        else {
            if (defined($branches) && scalar(@$branches) > 0) {
                for my $b (@$branches) {
                    for my $f (@$fields) {
                        unless (seq_key_exists($b->{fields}, $f)) {
                            return (0, $f);
                        }
                    }
                }
            }
        }
        
        return 1;
    };
    
    my @keys = sort {$seqs->{$a}->{name} cmp $seqs->{$b}->{name}} keys %$seqs;
    for my $path (@keys) {
        if ($seqs->{$path}->{required_fields}) {
            my ($r, $f) = $_check_path_fields->($tree, $path, $seqs->{$path}->{required_fields});
            if (!$r) {
                my $n = $seqs->{$path}->{name};
                if ($f =~ /^(\d+)\[[A-Z-]+\]$/) {
                    $f = $1.'a';
                }
                return "Missing required field ($n sequence): $f";
            }
        }
    }
    
    return undef;
}

# Рекурсивная проверка обязательных последовательностей и полей. 
sub _check_required {
    my $tree = shift;
    my $seqs = shift;
    
    my $err_s = _check_required_sequences($tree, $seqs);
    if (defined $err_s) {
        return $err_s;
    }
    
    my $err_f = _check_required_fields($tree, $seqs);
    if (defined $err_f) {
        return $err_f;
    }
    
    return undef;
}


1;