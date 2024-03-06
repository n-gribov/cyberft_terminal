package CyberFT::SWIFT::Types::MT507;
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
            key => '22a',
            key_regexp => '22[FH]',
            required => 1,
        },
        {
            key => '95a',
            key_regexp => '95[PRQ]',
            required => 1,
        },
        {
            key => '25D',
            required => 1,
        },
        {
            key => '20C',
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
                        required_fields => ['20C', '23G', '22[FH]', '95[PQR]', '25D'],
                    },
                    'GENL/AGRE' => {
                        name => 'A1',
                        required => 1,
                        required_fields => [],
                    },
                    
                    'GENL/LINK' => {
                        name => 'A2',
                        required => 1,
                        required_fields => ['20C'],
                    },
                    'COLD' => {
                        name => 'B',
                        required => 0,
                        required_fields => ['20C', '22H', '25D'],
                    },
                    'COLD/SETTL' => {
                        name => 'B1',
                        required => 0,
                        required_fields => [],
                    },
                    'COLD/SETTL/SETDET' => {
                        name => 'B1a',
                        required => 0,
                        required_fields => ['22[FH]'],
                    },
                    'COLD/SETTL/SETDET/SETPRTY' => {
                        name => 'B1a1',
                        required => 0,
                        required_fields => ['95[CPQRS]'],
                    },
                    'COLD/SETTL/CASHSET' => {
                        name => 'B1b',
                        required => 0,
                        required_fields => [],
                    },
                    'COLD/SETTL/CASHSET/CSHPRTY' => {
                        name => 'B1b1',
                        required => 0,
                        required_fields => ['95[PQRS]'],
                    },
                    'ADDINFO' => {
                        name => 'C',
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

        # С2
        # Присутствие в сообщении последовательности В зависит от значения поля :25D::4!c//<Status>
        # («Статус») в последовательности А и от значения поля :13A::LINK//<Number Id>
        # («Определение номера») в подпоследовательности А2 следующим образом (Код ошибки D29):
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $fields_a = $tree->{'GENL'}->[0]->{fields} || [];
                my $branches_b = $tree->{'COLD'} || [];
                my $b_exists = (scalar @$branches_b > 0);
                my $field_25d = seq_get_first($fields_a, '25D');
                my $flatten_a = _flatten_tree($tree->{'GENL'}->[0]);

                if ($field_25d =~ m`:REST//ACC[TP]`) {
                    if (seq_key_value_exists($flatten_a, '13A', ':LINK//504')) {
                        return 0 unless ($b_exists);
                    }
                }
                elsif ($field_25d =~ m`(:REST//REJT|:CPRC|:IPRC)`) {
                    return 0 if ($b_exists);
                }

                return 1;
            },
            err => 'D29',
        },

        # С3
        # В каждом повторении последовательности В присутствие подпоследовательности В1 зависит от
        # значения поля :25D::COLL//<Status> («Статус») и поля :22Н::COLL//<Indicator> («Признак»)
        # следующим образом (Код ошибки С70):
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $branches_b = $tree->{'COLD'} || [];

                for my $b (@$branches_b) {
                    my $b1_exists = exists($b->{'SETTL'});
                    if (seq_key_value_exists($b->{fields}, '25D', ':COLL//ACCT')) {
                        if (seq_key_value_exists($b->{fields}, '22H', ':COLL//BCOL')) {
                            return 0 if ($b1_exists);
                        }
                        elsif (seq_key_value_exists($b->{fields}, '22H', ':COLL//(CCOL|SCOL)')) {
                            return 0 unless ($b1_exists);
                        }
                    }
                    elsif (seq_key_value_exists($b->{fields}, '25D', ':COLL//REJT')) {
                        return 0 if ($b1_exists);
                    }
                    elsif (seq_key_value_exists($b->{fields}, '25D', ':COLL/[^/]+')) {
                        if (seq_key_value_exists($b->{fields}, '22H', ':COLL//BCOL')) {
                            return 0 if ($b1_exists);
                        }
                    }
                }

                return 1;
            },
            err => 'C70',
        },

        # С4
        # В любом повторении последовательности В в подпоследовательностях В1а1 и В1b1 следующие
        # поля определения сторон не могут присутствовать более одного раза (Код ошибки Е84):
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $branches_b = $tree->{'COLD'} || [];

                for my $b (@$branches_b) {
                    # B1a1
                    my $branches_b1a1 = $b->{'SETTL'}->[0]->{'SETDET'}->[0]->{'SETPRTY'} || [];
                    for my $code (qw{BUYR DEAG DECU DEI1 DEI2 PSET REAG RECU REI1 REI2 SELL}) {
                        my $counter = 0;
                        for my $bb (@$branches_b1a1) {
                            $counter += seq_key_value_count($bb->{fields}, '95[A-Z]', ":$code");
                        }
                        return 0 if ($counter > 1);
                    }
                    # B1b1
                    my $branches_b1b1 = $b->{'SETTL'}->[0]->{'CASHSET'}->[0]->{'CSHPRTY'} || [];
                    for my $code (qw{ACCW BENM PAYE DEBT INTM}) {
                        my $counter = 0;
                        for my $bb (@$branches_b1b1) {
                            $counter += seq_key_value_count($bb->{fields}, '95[A-Z]', ":$code");
                        }
                        return 0 if ($counter > 1);
                    }
                }

                return 1;
            },
            err => 'E84',
        },

        # С5
        # В последовательности сторон при расчетах по ценным бумагам (подпоследовательность В1а1)
        # обязательно должен быть указан агент-поставщик:
        # • Для любого повторения последовательности В (если она используется) должно выполняться
        # следующее правило: если присутствует подпоследовательность В1а1 «Стороны при расчетах по
        # ценным бумагам», то поле :95a::DEAG должно присутствовать в одной и только в одной
        # подпосле-довательности В1а1 в том же повторении последовательности С (Код ошибки Е91).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $branches_b = $tree->{'COLD'} || [];

                for my $b (@$branches_b) {
                    # B1a1
                    my $branches_b1a1 = $b->{'SETTL'}->[0]->{'SETDET'}->[0]->{'SETPRTY'} || [];
                    if (scalar(@$branches_b1a1) > 0) {
                        my $counter = 0;
                        for my $bb (@$branches_b1a1) {
                            $counter += seq_key_value_count($bb->{fields}, '95[A-Z]', ":REAG");
                        }
                        return 0 if ($counter != 1);
                     }
                }

                return 1;
            },
            err => 'E91',
        },

        # С6
        # Если в любом повторении последовательности В в поле :95a::4!с любого повторения
        # подпосле-довательности В1а1 присутствует определитель из Списка поставщиков (см. ниже),
        # то в других повторениях подпоследовательности В1а1 в той же последовательности B должны
        # быть указаны все остальные определители, следующие за ним в Списке поставщиков
        # (Код ошибки Е86).
        # Если в любом повторении последовательности В в поле :95a::4!с любого повторения
        # подпосле-довательности В1а1 присутствует определитель из Списка получателей (см. ниже),
        # то в других повторениях подпоследовательности В1а1 в той же последовательности В должны
        # быть указаны все остальные определители, следующие за ним в Списке получателей
        # (Код ошибки Е86).
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
                    my $branches = $branch_b->{'SETTL'}->[0]->{'SETDET'}->[0]->{'SETPRTY'} || [];

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

                return 1;
            },
            err => 'E86',
        },

        # С7
        # В каждом повторении подпоследовательности В1а использование подпоследовательности В1а1
        # зависит от присутствия поля :22F::STCO//NSSP следующим образом (Код ошибки Е48):
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $branches_b = $tree->{'COLD'} || [];

                for my $branch_b (@$branches_b) {
                    my $branch_b1a = $branch_b->{'SETTL'}->[0]->{'SETDET'}->[0] || undef;
                    if ($branch_b1a) {
                        if (seq_key_value_exists($branch_b1a->{fields}, '22F', ":STCO//NSSP")) {
                            my $branches_b1a1 = $branch_b1a->{'SETPRTY'} || [];
                            return 0 if (scalar(@$branches_b1a1) == 0);
                        }
                    }
                }

                return 1;
            },
            err => 'E48',
        },

        # С8
        # В каждом повторении подпоследовательности B1b использование подпоследовательности В1b1
        # зависит от присутствия поля :22F::STCO//NSSP следующим образом (Код ошибки Е49):
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $branches_b = $tree->{'COLD'} || [];

                for my $branch_b (@$branches_b) {
                    my $branch_b1b = $branch_b->{'SETTL'}->[0]->{'CASHSET'}->[0] || undef;
                    if ($branch_b1b) {
                        if (seq_key_value_exists($branch_b1b->{fields}, '22F', ":STCO//NSSP")) {
                            my $branches_b1b1 = $branch_b1b->{'CSHPRTY'} || [];
                            return 0 if (scalar(@$branches_b1b1) == 0);
                        }
                    }
                }

                return 1;
            },
            err => 'E49',
        },

        # С9
        # В любом повторении подпоследовательности А1, если в нем отсутствует поле :22F::AGRE, то
        # поле :70С::AGRE является обязательным, в противном случае поле :70С::AGRE необязательное
        # (Код ошибки Е71):
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

        # С11
        # В каждом повторении последовательности В (если она используется в сообщении), если в
        # этой последовательности присутствует подпоследовательность В1, использование
        # подпоследовательностей В1а и В1b зависит от значения поля :22Н::COLL//<Indicator>
        # («Признак») в последовательности В следующим образом (Код ошибки С69):
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $branches_b = $tree->{'COLD'} || [];

                for my $b (@$branches_b) {
                    my $b1 = $b->{'SETTL'}->[0] || undef;
                    if ($b1) {
                        my $b1a = $b1->{'SETDET'}->[0] || undef;
                        my $b1b = $b1->{'CASHSET'}->[0] || undef;
                        if (seq_key_value_exists($b->{fields}, '22H', ':COLL//CCOL')) {
                            return 0 if ($b1a);
                            return 0 unless ($b1b);
                        }
                        elsif (seq_key_value_exists($b->{fields}, '22H', ':COLL//SCOL')) {
                            return 0 unless ($b1a);
                            return 0 if ($b1b);
                        }
                        elsif (seq_key_value_exists($b->{fields}, '22H', ':COLL//BCOL')) {
                            return 0;
                        }
                    }
                }

                return 1;
            },
            err => 'C69',
        },

        # С12
        # Поле :13a:LINK должно присутствовать в одном и только в одном повторении
        # подпоследовательности А2 «Связки» (Код ошибки D52).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);

                my $branches_a2 = $tree->{'GENL'}->[0]->{'LINK'} || [];
                my $count = 0;
                for my $b (@$branches_a2) {
                    if (seq_key_value_exists($b->{fields}, '13[A-Z]', '^:LINK')) {
                        $count++;
                    }
                }
                return 0 if ($count != 1);

                return 1;
            },
            err => 'D52',
        },

        # С13
        # Поле :20C::RELA должно присутствовать в том же повторении подпоследовательности А2 «Связки»,
        # в котором присутствует поле :13a:LINK (Код ошибки D53).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);

                my $branches_a2 = $tree->{'GENL'}->[0]->{'LINK'} || [];
                for my $b (@$branches_a2) {
                    if (seq_key_value_exists($b->{fields}, '13[A-Z]', '^:LINK')) {
                        return 0 unless (seq_key_value_exists($b->{fields}, '20C', ':RELA'));
                    }
                }

                return 1;
            },
            err => 'D53',
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
    
    for my $path (keys %$seqs) {
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
    
    for my $path (keys %$seqs) {
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