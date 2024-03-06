package CyberFT::SWIFT::Types::MT536;
use CyberFT::SWIFT::Types::Utils;
use strict;
use Storable ();
use Data::Dumper;

our $ValidationProfile = {

    fields => [
        # Описаны только обязательные поля обязательных последовательностей.
        # A
        {
            key => '16R',
            required => 1,
        },
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
            key => '22F',
            required => 1,
        },
        {
            key => '97a',
            key_regexp => '97[AB]',
            required => 1,
        },
        {
            key => '17B',
            required => 1,
        },
        {
            key => '16S',
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
                        required_fields => ['28E', '20C', '23G', '69[AB]', '22F', '97[AB]', '17B'],
                    },
                    'GENL/LINK' => {
                        name => 'A1',
                        required => 0,
                        required_fields => ['20C'],
                    },
                    'SUBSAFE' => {
                        name => 'B',
                        required => 0,
                        required_fields => [],
                    },
                    'SUBSAFE/FIN' => {
                        name => 'B1',
                        required => 0,
                        required_fields => ['35B'],
                    },
                    'SUBSAFE/FIN/TRAN' => {
                        name => 'B1a',
                        required => 1,
                        required_fields => [],
                    },
                    'SUBSAFE/FIN/TRAN/LINK' => {
                        name => 'B1a1',
                        required => 1,
                        required_fields => ['20C'],
                    },
                    'SUBSAFE/FIN/TRAN/TRANSDET' => {
                        name => 'B1a2',
                        required => 0,
                        required_fields => ['36B', '22[FH]', '98[ABC]'],
                    },
                    'SUBSAFE/FIN/TRAN/TRANSDET/SETPRTY' => {
                        name => 'B1a2a',
                        required => 0,
                        required_fields => ['95[CPQR]'],
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

        # С1 Если «Флаг действий» (поле :17B::ACTI) в последовательности А «Общая информация» имеет
        # значение «N», то последовательность В не должна использоваться. В противном случае
        # последовательность В обязательная (Код ошибки Е66).
        {
            func => sub {
                my $doc = shift;
                my $tree = build_seq_tree($doc->data);
                my $fields_a = $tree->{'GENL'}->[0]->{fields} || [];
                my $branches_b = $tree->{'SUBSAFE'} || [];
                if (seq_key_value_exists($fields_a, '17B', ':ACTI//N')) {
                    return 0 if (scalar @$branches_b > 0);
                }
                else {
                    return 0 if (scalar @$branches_b == 0);
                }
                return 1;
            },
            err => 'E66',
        },

        # С2 Если инструкции предусматривают поставку против платежа (:22H::PAYM//APMT), то
        # обязательно должна быть указана сумма, отражаемая по счету (поле :19A::PSTA).
        # Это правило относится к подпоследовательности В1а2 (Код ошибки Е83).
        {
            func => sub {
                my $doc = shift;
                my $tree = build_seq_tree($doc->data);
                # путешествуем по дереву до B1a2
                my $branches_b = $tree->{'SUBSAFE'} || [];                  # B
                for my $b_b (@$branches_b) {
                    my $branches_b1 = $b_b->{'FIN'} || [];                  # B1
                    for my $b_b1 (@$branches_b1) {
                        my $branches_b1a = $b_b1->{'TRAN'} || [];           # B1a
                        for my $b_b1a (@$branches_b1a) {
                            my $fields_b1a2 = $b_b1a->{'TRANSDET'}->[0]->{fields} || [];
                            if (seq_key_value_exists($fields_b1a2, '22H', ':PAYM//APMT')) {
                                return 0 unless seq_key_value_exists($fields_b1a2, '19A', ':PSTA');
                            }
                        }
                    }
                }
                return 1;
            },
            err => 'E83',
        },

        # С3 В каждом из повторений подпоследовательности В1а в подпоследовательности В1а2 следующие
        # поля определения сторон не могут присутствовать более одного раза (Код ошибки Е84):
        {
            func => sub {
                my $doc = shift;
                my $tree = build_seq_tree($doc->data);
                # путешествуем по дереву до B1a2
                my $branches_b = $tree->{'SUBSAFE'} || [];                  # B
                for my $b_b (@$branches_b) {
                    my $branches_b1 = $b_b->{'FIN'} || [];                  # B1
                    for my $b_b1 (@$branches_b1) {
                        my $branches_b1a = $b_b1->{'TRAN'} || [];           # B1a
                        for my $b_b1a (@$branches_b1a) {
                            my $fields_b1a2 = flatten_tree($b_b1a->{'TRANSDET'}->[0]);
                            for my $c (qw`BUYR DEAG DECU DEI1 DEI2 PSET REAG RECU REI1 REI2 SELL`) {
                                return 0 if (seq_key_value_count($fields_b1a2, '95[A-Z]', ":$c") > 1);
                            }
                        }
                    }
                }
                return 1;
            },
            err => 'E84',
        },

        # С4 Если в поле :95a::4!с подпоследовательности В1а2а присутствует определитель из Списка
        # поставщиков/1 , то после него должны быть указаны все остальные определители,
        # следующие за ним в Списке поставщиков/1 (Код ошибки Е86).
        # • Если в подпоследовательности В1а2а присутствует поле :95a::DEI2, то в другой подпоследовательности В1а2а должно присутствовать поле :95a::DEI1.
        # • Если в подпоследовательности В1а2а присутствует поле :95a::DEI1, то в другой подпоследовательности В1а2а должно присутствовать поле :95a::DECU.
        # • Если в подпоследовательности В1а2а присутствует поле :95a::DECU, то в другой подпоследовательности В1а2а должно присутствовать поле :95a::SELL.
        # • Если в подпоследовательности В1а2а присутствует поле :95a::REI2, то в другой подпоследовательности В1а2а должно присутствовать поле :95a::REI1.
        # • Если в подпоследовательности В1а2а присутствует поле :95a::REI1, то в другой подпоследовательности В1а2а должно присутствовать поле :95a::RECU.
        # • Если в подпоследовательности В1а2а присутствует поле :95a::RECU, то в другой подпоследовательности B1а2а должно присутствовать поле :95a::BUYR.
        {
            func => sub {
                my $doc = shift;
                my $tree = build_seq_tree($doc->data);
                my $rules = {
                    'DEI2' => 'DEI1',
                    'DEI1' => 'DECU',
                    'DECU' => 'SELL',
                    'REI2' => 'REI1',
                    'REI1' => 'RECU',
                    'RECU' => 'BUYR',
                };
                # путешествуем по дереву до B1a2
                my $branches_b = $tree->{'SUBSAFE'} || [];                  # B
                for my $b_b (@$branches_b) {
                    my $branches_b1 = $b_b->{'FIN'} || [];                  # B1
                    for my $b_b1 (@$branches_b1) {
                        my $branches_b1a = $b_b1->{'TRAN'} || [];           # B1a
                        for my $b_b1a (@$branches_b1a) {
                            my $fields_b1a2 = flatten_tree($b_b1a->{'TRANSDET'}->[0]);
                            for my $x1 (keys %$rules) {
                                my $x2 = $rules->{$x1};
                                return 0 if (
                                    seq_key_value_exists($fields_b1a2, '95[A-Z]', ":$x1")
                                    && !seq_key_value_exists($fields_b1a2, '95[A-Z]', ":$x2")
                                );
                            }
                        }
                    }
                }
                return 1;
            },
            err => 'E86',
        },

        # С5 Если сообщение направляется для отмены, т.е. если поле 23 G «Функция сообщения» содержит
        # код CANC, в сообщении должна присутствовать хотя бы одна подпоследовательность А1 «Связки»,
        # и в одном и только в одном повторении А1 должно присутствовать поле :20C::PREV.
        # Соответственно, в остальных повторениях А1 поле :20C::PREV не допускается. (Код ошибки Е08).
        # Последовательность А если поле :23G:... то подпоследовательность А1 ... и поле :20C::PREV ...
        # CANC   Обязательная, т.е. должна присутствовать хотя бы одна подпоследовательность А1   Обязательное в одном повто-рении А1 и не допускается во всех остальных повторениях А1.
        # NEWM   Необязательная           Не допускается
        {
            func => sub {
                my $doc = shift;
                my $tree = build_seq_tree($doc->data);
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

        # С6 Если в подпоследовательности В1а2а присутствует поле :95a::PSET, то в той же
        # последовательности не может использоваться поле :97a::SAFE (Код ошибки Е52).
        # Подпоследовательность В1а2а если поле :95a::PSET … Подпоследовательность В1а2а то поле :97a::SAFE …
        # Присутствует                  Не может использоваться в той же последовательности
        # Отсутствует                   Необязательное
        {
            func => sub {
                my $doc = shift;
                my $tree = build_seq_tree($doc->data);
                # путешествуем по дереву до B1a2a
                my $branches_b = $tree->{'SUBSAFE'} || [];                        # B
                for my $b_b (@$branches_b) {
                    my $branches_b1 = $b_b->{'FIN'} || [];                        # B1
                    for my $b_b1 (@$branches_b1) {
                        my $branches_b1a = $b_b1->{'TRAN'} || [];                 # B1a
                        for my $b_b1a (@$branches_b1a) {
                            my $branch_b1a2 = $b_b1a->{'TRANSDET'}->[0] || {};    # B1a2
                            my $branches_b1a2a = $branch_b1a2->{'SETPRTY'} || []; # B1a2a
                            for my $b (@$branches_b1a2a) {
                                return 0 if (
                                    seq_key_value_exists($b->{fields}, '95[A-Z]', ':PSET')
                                    && seq_key_value_exists($b->{fields}, '97[A-Z]', ':SAFE')
                                );
                            }
                        }
                    }
                }
                return 1;
            },
            err => 'E52',
        },

        # С7 Если в последовательности А поле :17B::CONS имеет значение «Y», то в каждом из повторений
        # последовательности В «Субсчет депо» поле :97a::SAFE и поле :17B::ACTI являются обязательными
        # (Код ошибки Е56).
        # Выполнение этого правила проверяется только в тех случаях, когда в сообщении присутствует
        # последовательность В «Субсчет депо» (согласно правилу С1), т.е. когда поле :17B::ACTI имеет
        # значение «Y».
        {
            func => sub {
                my $doc = shift;
                my $tree = build_seq_tree($doc->data);
                my $fields_a = $tree->{'GENL'}->[0]->{fields} || [];
                my $branches_b = $tree->{'SUBSAFE'} || [];
                if (
                    seq_key_value_exists($fields_a, '17B', ':CONS//Y')
                    && seq_key_value_exists($fields_a, '17B', ':ACTI//Y')
                ){
                    for my $b (@$branches_b) {
                        return 0 unless seq_key_value_exists($b->{fields}, '97[A-Z]', ':SAFE');
                        return 0 unless seq_key_value_exists($b->{fields}, '17B', ':ACTI');
                    }
                }
                return 1;
            },
            err => 'E56',
        },

        # С8 Если «Флаг действий» (поле :17B::ACTI) в последовательности В «Субсчет депо» указывает
        # на отсутствие информации для отчетности, т.е. имеет значение «N», то подпоследовательность
        # В1 «Финансовый инструмент» не должна использоваться. В остальных случаях подпоследовательность
        # В1 «Финансовый инструмент» обязательная (Код ошибки Е69).
        # Выполнение этого правила проверяется только в тех случаях, когда в сообщении присутствует
        # последовательность В «Субсчет депо» (согласно правилу С1), т.е. когда поле :17B::ACTI имеет
        # значение «Y».
        {
            func => sub {
                my $doc = shift;
                my $tree = build_seq_tree($doc->data);
                my $branches_b = $tree->{'SUBSAFE'} || [];
                for my $b_b (@$branches_b) {
                    my $branches_b1 = $b_b->{'FIN'} || [];
                    if (seq_key_value_exists($b_b->{fields}, '17B', ':ACTI//Y')) {
                        return 0 if (scalar @$branches_b1 == 0);
                    }
                    elsif (seq_key_value_exists($b_b->{fields}, '17B', ':ACTI//N')) {
                        return 0 if (scalar @$branches_b1 > 0);
                    }
                    elsif (!seq_key_value_exists($b_b->{fields}, '17B', ':ACTI')) {
                        return 0 if (scalar @$branches_b1 == 0);
                    }
                }

                return 1;
            },
            err => 'E69',
        },

        # С9 В любом повторении подпоследовательности В1a2 поле :36B::PSTA не может использоваться
        # более двух раз. Если это поле используется дважды, то в первом случае Код типа количества
        # должен быть «FAMT», а во втором случае Код типа количества должен быть «AMOR» (Код ошибки С71).
        {
            func => sub {
                my $doc = shift;
                my $tree = build_seq_tree($doc->data);
                # путешествуем по дереву до B1a2
                my $branches_b = $tree->{'SUBSAFE'} || [];                  # B
                for my $b_b (@$branches_b) {
                    my $branches_b1 = $b_b->{'FIN'} || [];                  # B1
                    for my $b_b1 (@$branches_b1) {
                        my $branches_b1a = $b_b1->{'TRAN'} || [];           # B1a
                        for my $b_b1a (@$branches_b1a) {
                            my $fields_b1a2 = $b_b1a->{'TRANSDET'}->[0]->{fields} || [];
                            my $counter = 0;
                            my ($first, $second);
                            for my $item (@$fields_b1a2) {
                                if ($item->{key} =~ /36B/ && $item->{value} =~ /:PSTA/) {
                                    $counter++;
                                    if ($counter == 1) {
                                        $first = $item->{value};
                                    }
                                    elsif ($counter == 2) {
                                        $second = $item->{value};
                                        return 0 unless ($first =~ /FAMT/ && $second =~ /AMOR/);
                                    }
                                    else {
                                        return 0;
                                    }
                                }
                            }
                        }
                    }
                }
                return 1;
            },
            err => 'C71',
        },

        # С10 Для каждой отражаемой в выписке операции должен быть указан референс полученного ранее
        # сообщения, т.е. в каждом повторении подпоследовательности В1а «Операция» поле :20C::RELA
        # должно присутствовать в одном и только в одном повторении подпоследовательности В1а1 «Связки»;
        # в остальных повторениях В1а1 поле :20C::RELA не допускается (Код ошибки С73).
        {
            func => sub {
                my $doc = shift;
                my $tree = build_seq_tree($doc->data);
                # путешествуем по дереву до B1a1
                my $branches_b = $tree->{'SUBSAFE'} || [];                  # B
                for my $b_b (@$branches_b) {
                    my $branches_b1 = $b_b->{'FIN'} || [];                  # B1
                    for my $b_b1 (@$branches_b1) {
                        my $branches_b1a = $b_b1->{'TRAN'} || [];           # B1a
                        for my $b_b1a (@$branches_b1a) {
                            my $branches_b1a1 = $b_b1a->{'LINK'} || [];      # B1a1
                            my $counter = 0;
                            for my $b (@$branches_b1a1) {
                                $counter++ if (seq_key_value_exists($b->{fields}, '20C', ':RELA'));
                            }
                            return 0 if ($counter != 1);
                        }
                    }
                }
                return 1;
            },
            err => 'C73',
        },

    ]
};

# Строим дерево (под)последовательностей. В основе функции лежит тот факт, что каждая последовательность
# начинается с поля 16R и заканчивается полем 16S с таким же содержанием. Последовательности могут быть вложенными.
sub build_seq_tree {
    my $data = shift;
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
            push @{$tree->{$inner_seq_name}}, build_seq_tree($inner_seq);
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

    return $tree;
}

# Превращаем дерево (ветку) в плоский список полей.
sub flatten_tree {
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
                my $branch_items = flatten_tree($branch);
                push @$items, @$branch_items;
            }
        }
    }

    return $items;
}

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