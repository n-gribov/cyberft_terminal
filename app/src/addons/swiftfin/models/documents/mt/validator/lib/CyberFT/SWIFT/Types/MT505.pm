package CyberFT::SWIFT::Types::MT505;
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
            key => '22a',
            key_regexp => '22[FH]',
            required => 1,
        },
        {
            key => '95a',
            key_regexp => '95[PRQ]',
            required => 1,
        },

        # B
        {
            key => '20C',
            required => 1,
        },
        {
            key => '22a',
            key_regexp => '22[FH]',
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
                        required_fields => ['20C', '23G', '22[FH]', '95[PQR]'],
                    },
                    'GENL/AGRE' => {
                        name => 'A1',
                        required => 1,
                        required_fields => [],
                    },
                    'GENL/LINK' => {
                        name => 'A2',
                        required => 0,
                        required_fields => ['20C'],
                    },
                    'COLD' => {
                        name => 'B',
                        required => 1,
                        required_fields => ['20C', '22[FH]'],
                    },
                    'COLD/SCOL' => {
                        name => 'B1',
                        required => 0,
                        required_fields => ['35B', '36B'],
                    },
                    'COLD/SCOL/SETDET' => {
                        name => 'B1a',
                        required => 0,
                        required_fields => ['22[FH]'],
                    },
                    'COLD/SCOL/SETDET/SETPRTY' => {
                        name => 'B1a1',
                        required => 0,
                        required_fields => ['95[CPQRS]'],
                    },
                    'COLD/CCOL' => {
                        name => 'B2',
                        required => 0,
                        required_fields => ['19B', '22H'],
                    },
                    'COLD/CCOL/CASHSET' => {
                        name => 'B2a',
                        required => 0,
                        required_fields => [],
                    },
                    'COLD/CCOL/CASHSET/CSHPRTY' => {
                        name => 'B2a1',
                        required => 0,
                        required_fields => ['95[PQRS]'],
                    },
                    'COLD/BCOL' => {
                        name => 'B3',
                        required => 0,
                        required_fields => ['22H', '98[AB]', '95[PQR]', '19B'],
                    },
                    'SETDET1' => {
                        name => 'C',
                        required => 0,
                        required_fields => ['22[FH]'],
                    },
                    'SETDET1/SETPRTY1' => {
                        name => 'C1',
                        required => 0,
                        required_fields => ['95[CPQRS]'],
                    },
                    'CASHSET1' => {
                        name => 'D',
                        required => 0,
                        required_fields => [],
                    },
                    'CASHSET1/CSHPRTY1' => {
                        name => 'D1',
                        required => 0,
                        required_fields => ['95[PQRS]'],
                    },
                    'ADDINFO' => {
                        name => 'E',
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
        # Если сообщение направляется для отмены, т.е. если поле 23 G «Функция сообщения» содержит
        # код CANC, в сообщении должна присутствовать хотя бы одна подпоследовательность А2 «Связки»,
        # и в одном и только в одном повторении А2 должно присутствовать поле :20C::PREV.
        # Соответственно, в остальных повторениях А2 поле :20C::PREV не допускается. (Код ошибки Е08).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $fields_a = $tree->{'GENL'}->[0]->{fields} || [];
                my $branches_a2 = $tree->{'GENL'}->[0]->{'LINK'} || [];
                if (seq_key_value_exists($fields_a, '23G', 'CANC')) {
                    return 0 if (scalar @$branches_a2 < 1);
                    my $count = 0;
                    for my $b (@$branches_a2) {
                        if (seq_key_value_exists($b->{fields}, '20C', ':PREV')) {
                            $count++;
                        }
                    }
                    return 0 if ($count != 1);
                }
                elsif (seq_key_value_exists($fields_a, '23G', 'NEWM')) {
                    my $count = 0;
                    for my $b (@$branches_a2) {
                        if (seq_key_value_exists($b->{fields}, '20C', ':PREV')) {
                            $count++;
                        }
                    }
                    return 0 if ($count > 0);
                }
                return 1;
            },
            err => 'E08',
        },

        # С2
        # Если в последовательности А отсутствует поле :20C::SCTR, то поле :20C::RCTR является
        # обязательным, в противном случае поле :20C::RCTR необязательное (Код ошибки Е68).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $fields_a = $tree->{'GENL'}->[0]->{fields} || [];

                unless (seq_key_value_exists($fields_a, '20C', ':SCTR')) {
                    return 0 unless (seq_key_value_exists($fields_a, '20C', ':RCTR'));
                }

                return 1;
            },
            err => 'E68',
        },

        # С3
        # В каждом повторении последовательности В использование подпоследовательностей В1, В2 и В3
        # зависит от значения поля :22H::COLL//«Признак» следующим образом (Код ошибки Е83):
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $branches_b = $tree->{'COLD'} || [];

                for my $b (@$branches_b) {
                    if (seq_key_value_exists($b->{fields}, '22H', ':COLL//SCOL')) {
                        return 0 unless (exists $b->{'SCOL'});
                        return 0 if (exists $b->{'CCOL'});
                        return 0 if (exists $b->{'BCOL'});
                    }
                    elsif (seq_key_value_exists($b->{fields}, '22H', ':COLL//CCOL')) {
                        return 0 if (exists $b->{'SCOL'});
                        return 0 unless (exists $b->{'CCOL'});
                        return 0 if (exists $b->{'BCOL'});
                    }
                    elsif (seq_key_value_exists($b->{fields}, '22H', ':COLL//BCOL')) {
                        return 0 if (exists $b->{'SCOL'});
                        return 0 if (exists $b->{'CCOL'});
                        return 0 unless (exists $b->{'BCOL'});
                    }
                }

                return 1;
            },
            err => 'E83',
        },

        # С4
        # В каждом повторении подпоследовательности В2 использование поля :98A::MATU зависит от
        # значения поля :22H::DEPO//«Признак» следующим образом (Код ошибки Е85):
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);

                my $branches_b = $tree->{'COLD'} || [];
                for my $bb (@$branches_b) {
                    my $branches_b2 = $bb->{'CCOL'} || [];
                    for my $bb2 (@$branches_b2) {

                        if (seq_key_value_exists($bb2->{fields}, '22H', ':DEPO//FIXT')) {
                            return 0 unless (seq_key_value_exists($bb2->{fields}, '98A', ':MATU'));
                        }
                        elsif (seq_key_value_exists($bb2->{fields}, '22H', ':DEPO//CLNT')) {
                            return 0 if (seq_key_value_exists($bb2->{fields}, '98A', ':MATU'));
                        }

                    }
                }

                return 1;
            },
            err => 'E85',
        },

        # С5
        # В любом повторении подпоследовательности В3, если в нем присутствует поле :22H::BCOL//LCOL,
        # поле :98B::EXPI//OPEN (т.е. определитель = EXPI, без подполя «Система кодировки», подполе
        # «Код даты» = OPEN) не должно использоваться в том же повторении, в противном случае поле
        # :98B::EXPI//OPEN необязательное (Код ошибки Е72):
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);

                my $branches_b = $tree->{'COLD'} || [];
                for my $bb (@$branches_b) {
                    my $branches_b3 = $bb->{'BCOL'} || [];
                    for my $bb3 (@$branches_b3) {

                        if (seq_key_value_exists($bb3->{fields}, '22H', ':BCOL//LCOL')) {
                            return 0 if (seq_key_value_exists($bb3->{fields}, '98B', ':EXPI//OPEN'));
                        }

                    }
                }

                return 1;
            },
            err => 'E72',
        },

        # С6
        # Последовательность С является обязательной в тех случаях, когда в любом из повторений
        # последовательности В присутствует подпоследовательность В1, но отсутствует
        # подпоследовательность В1а (Код ошибки С97).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);

                my $required = 0;
                my $branches_b = $tree->{'COLD'} || [];
                for my $bb (@$branches_b) {
                    my $branch_b1 = $bb->{'SCOL'}->[0] || undef;
                    if ($branch_b1) {
                        my $branch_b1a = $branch_b1->{'SETDET'}->[0] || undef;
                        unless ($branch_b1a) {
                            $required = 1;
                            last;
                        }
                    }
                }

                if ($required) {
                    my $branch_c = $tree->{'SETDET1'}->[0] || undef;
                    return 0 unless ($branch_c);
                }

                return 1;
            },
            err => 'C97',
        },

        # С7
        # Последовательность С не используется если во всех повторениях последовательности В либо
        # отсутствует подпоследовательность В1, либо во всех повторениях подпоследо вательности В1
        # присутствует подпоследовательность В1а (Код ошибки D49).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);

                my $allowed = 0;
                my $branches_b = $tree->{'COLD'} || [];
                for my $bb (@$branches_b) {
                    my $branch_b1 = $bb->{'SCOL'}->[0] || undef;
                    if ($branch_b1) {
                        my $branch_b1a = $branch_b1->{'SETDET'}->[0] || undef;
                        unless ($branch_b1a) {
                            $allowed = 1;
                            last;
                        }
                    }
                }

                unless ($allowed) {
                    my $branch_c = $tree->{'SETDET1'}->[0] || undef;
                    return 0 if ($branch_c);
                }

                return 1;
            },
            err => 'D49',
        },

        # С8
        # Последовательность D является обязательной если в любом из повторений последовательности В
        # присутствует подпоследовательность В2, но отсутствует подпоследовательность В2а
        # (Код ошибки С99).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);

                my $required = 0;
                my $branches_b = $tree->{'COLD'} || [];
                for my $bb (@$branches_b) {
                    my $branch_b2 = $bb->{'CCOL'}->[0] || undef;
                    if ($branch_b2) {
                        my $branch_b2a = $branch_b2->{'CASHSET'}->[0] || undef;
                        unless ($branch_b2a) {
                            $required = 1;
                            last;
                        }
                    }
                }

                if ($required) {
                    my $branch_d = $tree->{'CASHSET1'}->[0] || undef;
                    return 0 unless ($branch_d);
                }

                return 1;
            },
            err => 'C99',
        },

        # С9
        # Последовательность D не не используется если во всех повторениях последователь- ности В
        # либо отсутствует подпоследовательность В2, либо подпоследователь-ность В2а присутствует
        # во всех повторениях подпоследовательности С2 (Код ошибки D49).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);

                my $allowed = 0;
                my $branches_b = $tree->{'COLD'} || [];
                for my $bb (@$branches_b) {
                    my $branch_b2 = $bb->{'CCOL'}->[0] || undef;
                    if ($branch_b2) {
                        my $branch_b2a = $branch_b2->{'CASHSET'}->[0] || undef;
                        unless ($branch_b2a) {
                            $allowed = 1;
                            last;
                        }
                    }
                }

                unless ($allowed) {
                    my $branch_d = $tree->{'CASHSET1'}->[0] || undef;
                    return 0 if ($branch_d);
                }

                return 1;
            },
            err => 'D49',
        },

        # С10
        # В каждом из повторений последовательности В в подпоследовательностях В1а1 и В2а1 следующие
        # поля определения сторон не могут присутствовать более одного раза (Код ошибки Е84):
        # В последовательностях С и D: в подпоследовательностях С1 и D1 следующие поля определения
        # сторон не могут присутствовать более одного раза (Код ошибки Е84):
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);

                my $branches_b = $tree->{'COLD'} || [];
                for my $branch_b (@$branches_b) {
                    my $branches;
                    # B1a1
                    $branches = $branch_b->{'SCOL'}->[0]->{'SETDET'}->[0]->{'SETPRTY'} || [];
                    for my $code (qw{BUYR DEAG DECU DEI1 DEI2 PSET REAG RECU REI1 REI2 SELL}) {
                        my $counter = 0;
                        for my $b (@$branches) {
                            $counter += seq_key_value_count($b->{fields}, '95[A-Z]', ":$code");
                        }
                        return 0 if ($counter > 1);
                    }
                    # B2a1
                    $branches = $branch_b->{'CCOL'}->[0]->{'CASHSET'}->[0]->{'CSHPRTY'} || [];
                    for my $code (qw{ACCW BENM PAYE DEBT INTM}) {
                        my $counter = 0;
                        for my $b (@$branches) {
                            $counter += seq_key_value_count($b->{fields}, '95[A-Z]', ":$code");
                        }
                        return 0 if ($counter > 1);
                    }
                }

                # C1
                my $branches_c1 = $tree->{'SETDET1'}->[0]->{'SETPRTY1'} || [];
                for my $code (qw{BUYR DEAG DECU DEI1 DEI2 PSET REAG RECU REI1 REI2 SELL}) {
                    my $counter = 0;
                    for my $b (@$branches_c1) {
                        $counter += seq_key_value_count($b->{fields}, '95[A-Z]', ":$code");
                    }
                    return 0 if ($counter > 1);
                }

                # D1
                my $branches_d1 = $tree->{'CASHSET1'}->[0]->{'CSHPRTY1'} || [];
                for my $code (qw{ACCW BENM PAYE DEBT INTM}) {
                    my $counter = 0;
                    for my $b (@$branches_d1) {
                        $counter += seq_key_value_count($b->{fields}, '95[A-Z]', ":$code");
                    }
                    return 0 if ($counter > 1);
                }

                return 1;
            },
            err => 'E84',
        },

        # С11
        # Если в любом повторении последовательности В в поле :95a::4!с любого повторения
        # подпосле-довательности В1а1 присутствует определитель из Списка поставщиков (см. ниже),
        # то в других повторениях подпоследовательности В1а1 в той же последовательности В должны
        # быть указа-ны все остальные определители, следующие за ним в Списке поставщиков (Код ошибки Е86).
        # Если в любом повторении последовательности В в поле :95a::4!с любого повторения
        # подпосле-довательности В1а1 присутствует определитель из Списка получателей (см. ниже),
        # то в других повторениях подпоследовательности В1а1 в той же последовательности С должны
        # быть указаны все остальные определители, следующие за ним в Списке получателей (Код ошибки Е86).
        # ... то же самое для С1
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);

                my $rules = {
                    'DEI2' => 'DEI1',
                    'DEI1' => 'DECU',
                    'DECU' => 'SELL',
                    'REI2' => 'REI1',
                    'REI1' => 'RECU',
                    'RECU' => 'BUYR',
                };

                my $branches_b = $tree->{'COLD'} || [];
                for my $branch_b (@$branches_b) {
                    # B1a1
                    my $branches = $branch_b->{'SCOL'}->[0]->{'SETDET'}->[0]->{'SETPRTY'} || [];

                    for my $x1 (keys %$rules) {
                        my $x2 = $rules->{$x1};

                        my ($exists_x1, $exists_x2);
                        for my $b (@$branches) {
                            if (seq_key_value_exists($b->{fields}, '95[A-Z]', ":$x1")) {
                                $exists_x1 = 1;
                            }
                            elsif (seq_key_value_exists($b->{fields}, '95[A-Z]', ":$x2")) {
                                $exists_x2 = 1;
                            }
                        }

                        return 0 if ($exists_x1 && !$exists_x2);
                    }
                }

                # C1
                my $branches = $tree->{'SETDET1'}->[0]->{'SETPRTY1'} || [];
                for my $x1 (keys %$rules) {
                    my $x2 = $rules->{$x1};

                    my ($exists_x1, $exists_x2);
                    for my $b (@$branches) {
                        if (seq_key_value_exists($b->{fields}, '95[A-Z]', ":$x1")) {
                            $exists_x1 = 1;
                        }
                        elsif (seq_key_value_exists($b->{fields}, '95[A-Z]', ":$x2")) {
                            $exists_x2 = 1;
                        }
                    }

                    return 0 if ($exists_x1 && !$exists_x2);
                }

                return 1;
            },
            err => 'E86',
        },

        # С12
        # В каждом повторении подпоследовательности В1а использование подпоследовательности
        # В1а1 зависит от присутствия поля :22F::STCO//NSSP следующим образом (Код ошибки Е48):
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);

                my $branches_b = $tree->{'COLD'} || [];
                for my $branch_b (@$branches_b) {
                    my $branch_b1a = $branch_b->{'SCOL'}->[0]->{'SETDET'}->[0] || undef;
                    if ($branch_b1a && seq_key_value_exists($branch_b1a->{fields}, '22F', ':STCO//NSSP')) {
                        return 0 unless (defined $branch_b1a->{'SETPRTY'}); # B1a1
                    }
                }

                return 1;
            },
            err => 'E48',
        },

        # С13 В каждом повторении подпоследовательности В2а использование подпоследовательности В2а1
        # зависит от присутствия поля :22F::STCO//NSSP следующим образом (Код ошибки Е49):
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);

                my $branches_b = $tree->{'COLD'} || [];
                for my $branch_b (@$branches_b) {
                    my $branch_b2a = $branch_b->{'CCOL'}->[0]->{'CASHSET'}->[0] || undef;
                    if ($branch_b2a && seq_key_value_exists($branch_b2a->{fields}, '22F', ':STCO//NSSP')) {
                        return 0 unless (defined $branch_b2a->{'CSHPRTY'}); # B2a1
                    }
                }

                return 1;
            },
            err => 'E49',
        },

        # С14
        # В последовательности С использование подпоследовательности С1 зависит от присутствия
        # поля :22F::STCO//NSSP следующим образом (Код ошибки Е50):
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);

                my $branch_c = $tree->{'SETDET1'}->[0] || undef;
                if ($branch_c && seq_key_value_exists($branch_c->{fields}, '22F', ':STCO//NSSP')) {
                    return 0 unless (defined $branch_c->{'SETPRTY'}); # C1
                }

                return 1;
            },
            err => 'E50',
        },

        # С15
        # В последовательности D использование подпоследовательности D1 зависит от присутствия поля
        # :22F::STCO//NSSP следующим образом (Код ошибки Е51):
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);

                my $branch_d = $tree->{'CASHSET1'}->[0] || undef;
                if ($branch_d && seq_key_value_exists($branch_d->{fields}, '22F', ':STCO//NSSP')) {
                    return 0 unless (defined $branch_d->{'CSHPRTY'}); # D1
                }

                return 1;
            },
            err => 'E51',
        },

        # С16
        # В любом повторении подпоследовательности А1, если в нем отсутствует поле :22F::AGRE, то
        # поле :70С::AGRE является обязательным в том же повторении, в противном случае поле
        # :70С::AGRE необязательное (Код ошибки Е71):
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $branches_a1 = $tree->{'GENL'}->[0]->{'AGRE'} || [];

                for my $b (@$branches_a1) {
                    unless (seq_key_value_exists($b->{fields}, '22F', ':AGRE')) {
                        return 0 unless (seq_key_value_exists($b->{fields}, '70C', ':AGRE'));
                    }
                }

                return 1;
            },
            err => 'E71',
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