package CyberFT::SWIFT::Types::MT575;
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
            key => '28E',
            required => 1,
        },
        {
            key => '20C',
            required => 1,
        },
        {
            key => '23G',
            required => 1,
        },
        {
            key => '69a',
            key_regexp => '69[AB]',
            required => 1,
        },
        {
            key => '17B',
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
                        required_fields => ['28E', '20C', '23G', '69[AB]', '17B'],
                    },
                    'GENL/LINK' => {
                        name => 'A1',
                        required => 0,
                        required_fields => ['20C'],
                    },
                    'CASHACCT' => {
                        name => 'B',
                        required => 0,
                        required_fields => ['97[AE]'],
                    },
                    'CASHACCT/ACTCURR' => {
                        name => 'B1',
                        required => 1,
                        required_fields => ['11A', '17B', '93D'],
                    },
                    'CASHACCT/ACTCURR/ACTINFO' => {
                        name => 'B1a',
                        required => 0,
                        required_fields => [],
                    },
                    'CASHACCT/ACTCURR/ACTINFO/LINK' => {
                        name => 'B1a1',
                        required => 1,
                        required_fields => ['20C'],
                    },
                    'CASHACCT/ACTCURR/ACTINFO/CASHDET' => {
                        name => 'B1a2',
                        required => 0,
                        required_fields => ['19A', '22[FH]', '98[ABC]'],
                    },
                    'CASHACCT/ACTCURR/ACTINFO/CASHSECDET' => {
                        name => 'B1a3',
                        required => 0,
                        required_fields => ['19A', '36B', '35B', '22[FH]', '98[ABC]'],
                    },
                    'CASHACCT/ACTCURR/ACTINFO/SETPRTY' => {
                        name => 'B1a4',
                        required => 0,
                        required_fields => ['95[CPQR]'],
                    },
                    'FREEASS' => {
                        name => 'C',
                        required => 0,
                        required_fields => [],
                    },
                    'FREEASS/LINK' => {
                        name => 'C1',
                        required => 1,
                        required_fields => ['20C'],
                    },
                    'FREEASS/TRANSDET' => {
                        name => 'C2',
                        required => 0,
                        required_fields => ['36B', '35B', '22[FH]', '98[ABC]'],
                    },
                    'FREEASS/TRANSDET/SETPRTY' => {
                        name => 'C2a',
                        required => 0,
                        required_fields => ['95[CPQR]'],
                    },
                    'ADDINFO' => {
                        name => 'D',
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
        # Если «Флаг действий» (поле :17B::ACTI) в последовательности А «Общая информация» указывает
        # на отсутствие информации для отчета, то есть имеет значение «N», то последовательность В
        # «Движение по счетам денежных средств» и последовательность С «Движение активов без платежа»
        # не должны использоваться. В противном случае последовательности В и С необязательные
        # (Код ошибки Е66).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $fields_a = $tree->{'GENL'}->[0]->{fields} || [];
                if (seq_key_value_exists($fields_a, '17B', 'ACTI//N')) {
                    return 0 if (defined $tree->{'CASHACCT'}->[0]);
                    return 0 if (defined $tree->{'FREEASS'}->[0]);
                }
                return 1;
            },
            err => 'E66',
        },

        # С2
        # В каждом из повторений подпоследовательности В1 «Движение в разрезе по валютам», если
        # «Флаг действий» (поле :17B::ACTI) указывает на отсутствие информации для отчета, то есть
        # имеет значение «N», то подпоследовательность В1а «Движение в разрезе по операциям» не
        # должна использоваться в этой подпоследовательности В1.
        # Если поле :17B::ACTI имеет значение «Y», то подпоследовательность В1а обязательная
        # (Код ошибки Е95).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);

                for my $b_b (@{ $tree->{'CASHACCT'} }) {
                    for my $b_b1 (@{ $b_b->{'ACTCURR'} }) {
                        if (seq_key_value_exists($b_b1->{fields}, '17B', 'ACTI//N')) {
                            return 0 if (defined $b_b1->{'ACTINFO'}->[0]);
                        }
                        if (seq_key_value_exists($b_b1->{fields}, '17B', 'ACTI//Y')) {
                            return 0 unless (defined $b_b1->{'ACTINFO'}->[0]);
                        }
                    }
                }

                return 1;
            },
            err => 'E95',
        },

        # С3
        # В подпоследовательности В1а «Движение в разрезе по операциям» подпоследовательности В1а2
        # «Движение только денежных средств» и В1а3 «Движение денежных средств и ценных бумаг»
        # являются взаимоисключающими (Код ошибки Е96).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);

                for my $b_b (@{ $tree->{'CASHACCT'} }) {
                    for my $b_b1 (@{ $b_b->{'ACTCURR'} }) {
                        for my $b_b1a (@{ $b_b1->{'ACTINFO'} }) {
                            my $exists_b1a2 = defined($b_b1a->{'CASHDET'}->[0]);
                            my $exists_b1a3 = defined($b_b1a->{'CASHSECDET'}->[0]);
                            if ($exists_b1a2) {
                                return 0 if ($exists_b1a3);
                            }
                            else {
                                return 0 unless ($exists_b1a3);
                            }
                        }
                    }
                }

                return 1;
            },
            err => 'E96',
        },

        # С4
        # Следующие поля определения сторон не могут присутствовать более одного раза в одном и том
        # же повторении подпоследовательности В1а (Код ошибки Е84):
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);

                for my $b_b (@{ $tree->{'CASHACCT'} }) {
                    for my $b_b1 (@{ $b_b->{'ACTCURR'} }) {
                        for my $b_b1a (@{ $b_b1->{'ACTINFO'} }) {
                            my $fields = _flatten_tree($b_b1a);
                            for my $code (qw(BUYR DEAG DECU DEI1 DEI2 PSET REAG RECU REI1 REI2 SELL)) {
                                if (seq_key_value_count($fields, '95[A-Z]', ":$code") > 1) {
                                    return 0;
                                }
                            }
                        }
                    }
                }

                return 1;
            },
            err => 'E84',
        },

        # С5
        # Если в поле :95a::4!с подпоследовательности В1а4 присутствует определитель из Списка
        # поставщиков (см. ниже), то должны быть указаны все остальные определители, следующие за ним
        # в Списке поставщиков (Код ошибки Е86).
        # Если в поле :95a::4!с подпоследовательности В1а4 присутствует определитель из Списка
        # получателей (см. ниже), то должны быть указаны все остальные определители, следующие за ним
        # в Списке получателей.
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

                for my $b_b (@{ $tree->{'CASHACCT'} }) {
                    for my $b_b1 (@{ $b_b->{'ACTCURR'} }) {
                        for my $b_b1a (@{ $b_b1->{'ACTINFO'} }) {
                            for my $x1 (keys %$rules) {
                                my $x2 = $rules->{$x1};
                                my $exists_x1 = 0;
                                my $exists_x2 = 0;
                                for my $b_b1a4 (@{ $b_b1a->{'SETPRTY'} }) {
                                    if (seq_key_value_exists($b_b1a4->{fields}, '95[A-Z]', ":$x1")) {
                                        $exists_x1 = 1;
                                    } elsif (seq_key_value_exists($b_b1a4->{fields}, '95[A-Z]', ":$x2")) {
                                        $exists_x2 = 1;
                                    }
                                };
                                return 0 if ($exists_x1 && !$exists_x2);
                            }
                        }
                    }
                }

                return 1;
            },
            err => 'E86',
        },

        # С6
        # Следующие поля определения сторон не могут присутствовать более одного раза в одном и том
        # же повторении подпоследовательности С2 (Код ошибки Е84):
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);

                for my $b_c (@{ $tree->{'FREEASS'} }) {
                    for my $b_c2 (@{ $b_c->{'TRANSDET'} }) {
                        my $fields = _flatten_tree($b_c2);
                        for my $code (qw(BUYR DEAG DECU DEI1 DEI2 PSET REAG RECU REI1 REI2 SELL)) {
                            if (seq_key_value_count($fields, '95[A-Z]', ":$code") > 1) {
                                return 0;
                            }
                        }
                    }
                }

                return 1;
            },
            err => 'E84',
        },

        # С7
        # Если в поле :95a::4!с подпоследовательности С2а присутствует определитель из Списка
        # поставщиков (см. ниже), то должны быть указаны все остальные определители, следующие за
        # ним в Списке поставщиков (Код ошибки Е86).
        # Если в поле :95a::4!с подпоследовательности С2а присутствует определитель из Списка
        # получателей (см. ниже), то должны быть указаны все остальные определители, следующие за
        # ним в Списке получателей.
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

                for my $b_c (@{ $tree->{'FREEASS'} }) {
                    for my $b_c2 (@{ $b_c->{'TRANSDET'} }) {
                        for my $x1 (keys %$rules) {
                            my $x2 = $rules->{$x1};
                            my $exists_x1 = 0;
                            my $exists_x2 = 0;
                            for my $b_c2a (@{ $b_c2->{'SETPRTY'} }) {
                                if (seq_key_value_exists($b_c2a->{fields}, '95[A-Z]', ":$x1")) {
                                    $exists_x1 = 1;
                                } elsif (seq_key_value_exists($b_c2a->{fields}, '95[A-Z]', ":$x2")) {
                                    $exists_x2 = 1;
                                }
                            };
                            return 0 if ($exists_x1 && !$exists_x2);
                        }
                    }
                }

                return 1;
            },
            err => 'E86',
        },

        # С8
        # Если сообщение направляется для отмены, т.е. если поле 23G «Функция сообщения» содержит
        # код CANC, в сообщении должна присутствовать хотя бы одна подпоследовательность А1 «Связки»,
        # и в одном и только в одном повторении А1 должно присутствовать поле :20C::PREV.
        # Соответственно, в остальных повторениях А1 поле :20C::PREV не может использоваться
        # (Код ошибки Е08).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $fields_a = $tree->{'GENL'}->[0]->{fields} || [];
                my $branches_a1 = $tree->{'GENL'}->[0]->{'LINK'} || [];
                if (seq_key_value_exists($fields_a, '23G', 'CANC')) {
                    return 0 if (scalar @$branches_a1 < 1);
                    my $count = 0;
                    for my $b (@$branches_a1) {
                        if (seq_key_value_exists($b->{fields}, '20C', ':PREV')) {
                            $count++;
                        }
                    }
                    return 0 if ($count != 1);
                }
                elsif (seq_key_value_exists($fields_a, '23G', 'NEWM')) {
                    my $count = 0;
                    for my $b (@$branches_a1) {
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

        # C9
        # Если в подпоследовательности В1а4 присутствует поле :95a::PSET, то в той же
        # последовательности не может использоваться поле :97a::SAFE (Код ошибки Е52).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);

                for my $b_b (@{ $tree->{'CASHACCT'} }) {
                    for my $b_b1 (@{ $b_b->{'ACTCURR'} }) {
                        for my $b_b1a (@{ $b_b1->{'ACTINFO'} }) {
                            for my $b_b1a4 (@{ $b_b1a->{'SETPRTY'} }) {
                                if (seq_key_value_exists($b_b1a4->{fields}, '95[A-Z]', ":PSET")) {
                                    return 0 if (seq_key_value_exists($b_b1a4->{fields}, '97[A-Z]', ":SAFE"));
                                }
                            }
                        }
                    }
                }

                return 1;
            },
            err => 'E52',
        },

        # С10
        # Если в подпоследовательности С2а присутствует поле :95a::PSET, то в той же
        # последовательности не может использоваться поле :97a::SAFE (Код ошибки Е53).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);

                for my $b_c (@{ $tree->{'FREEASS'} }) {
                    for my $b_c2 (@{ $b_c->{'TRANSDET'} }) {
                        for my $b_c2a (@{ $b_c2->{'SETPRTY'} }) {
                            if (seq_key_value_exists($b_c2a->{fields}, '95[A-Z]', ":PSET")) {
                                return 0 if (seq_key_value_exists($b_c2a->{fields}, '97[A-Z]', ":SAFE"));
                            }
                        }
                    }
                }

                return 1;
            },
            err => 'E53',
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