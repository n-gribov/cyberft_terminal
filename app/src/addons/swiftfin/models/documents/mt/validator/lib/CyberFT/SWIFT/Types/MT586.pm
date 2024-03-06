package CyberFT::SWIFT::Types::MT586;
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
            key => '98a',
            key_regexp => '98[ACE]',
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
                        required_fields => ['28E', '20C', '23G', '98[ACE]', '97[AB]', '17B'],
                    },
                    'GENL/LINK' => {
                        name => 'A1',
                        required => 0,
                        required_fields => ['20C'],
                    },
                    'ALLDET' => {
                        name => 'B',
                        required => 0,
                        required_fields => ['98[ABC]', '35B', '22[FH]'],
                    },
                    'ALLDET/LINK' => {
                        name => 'B1',
                        required => 0,
                        required_fields => ['20C'],
                    },
                    'ALLDET/FIA' => {
                        name => 'B2',
                        required => 0,
                        required_fields => [],
                    },
                    'ALLDET/FIAC' => {
                        name => 'B3',
                        required => 1,
                        required_fields => ['36B'],
                    },
                    'ALLDET/FIAC/BREAK' => {
                        name => 'B3a',
                        required => 0,
                        required_fields => ['36B'],
                    },
                    'ALLDET/REPO' => {
                        name => 'B4',
                        required => 0,
                        required_fields => [],
                    },
                    'ALLDET/SETDET' => {
                        name => 'B5',
                        required => 1,
                        required_fields => ['22F'],
                    },
                    'ALLDET/SETDET/SETPRTY' => {
                        name => 'B5a',
                        required => 1,
                        required_fields => ['95[CPQRS]'],
                    },
                    'ALLDET/SETDET/AMT' => {
                        name => 'B5b',
                        required => 0,
                        required_fields => ['19A'],
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
        # Если в последовательности А «Общая информация» поле «Флаг действий» (поле :17B::ACTI)
        # имеет значение «N», то последовательность В не должна использоваться. В противном случае
        # последовательность B является обязательной (Код ошибки Е66).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $fields_a = $tree->{'GENL'}->[0]->{fields} || [];
                if (seq_key_value_exists($fields_a, '17B', 'ACTI//N')) {
                    return 0 if (defined $tree->{'ALLDET'}->[0]);
                }
                if (seq_key_value_exists($fields_a, '17B', 'ACTI//Y')) {
                    return 0 unless (defined $tree->{'ALLDET'}->[0]);
                }
                return 1;
            },
            err => 'E66',
        },

        # С2
        # Если инструкции предусматривают поставку против платежа (:22H::PAYM//APMT), то обязательно
        # должна быть указана сумма, отражаемая по счету (поле :19A::PSTA). Это правило относится к
        # последовательности В (Код ошибки Е83).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $branches_b = $tree->{'ALLDET'} || [];
                for my $b_b (@$branches_b) {
                    if (seq_key_value_exists($b_b->{fields}, '22H', ':PAYM//APMT')) {
                        my $branches_b5b = $b_b->{'SETDET'}->[0]->{'AMT'} || [];
                        my $found = 0;
                        for my $b_b5b (@$branches_b5b) {
                            if (seq_key_value_exists($b_b5b->{fields}, '19A', ':SETT')) {
                                $found = 1;
                            }
                        }
                        unless ($found) {
                            return 0;
                        }
                    }
                }
                return 1;
            },
            err => 'E83',
        },

        # С3
        # В каждом из повторений последовательности В следующие поля сумм не могут присутствовать
        # более чем в одном повторении подпоследовательности В5b «Суммы» (Код ошибки Е87):
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $branches_b = $tree->{'ALLDET'} || [];
                for my $b_b (@$branches_b) {
                    my $branches_b5b = $b_b->{'SETDET'}->[0]->{'AMT'} || [];
                    for my $code (qw'ACRU CHAR DEAL EXEC LOCL LOCO OTHR SETT STAM TRAX WITH COAX') {
                        my $count = 0;
                        for my $b5b (@$branches_b5b) {
                            if (seq_key_value_exists($b5b->{fields}, '19A', ":$code")) {
                                $count++;
                            }
                        }
                        return 0 if ($count > 1);
                    }
                }
                return 1;
            },
            err => 'E87',
        },

        # С4
        # Если в сообщении присутствует поле :92В::EXCH «Курс конвертации», то в той же
        # подпоследовательности должно также присутствовать поле :19A::RESU «Сумма после конвертации».
        # Если поле «Курс конвертации» отсутствует, то поле «Сумма после конвертации» использоваться
        # не должно. Это правило проверяется для всех повторений подпоследовательности В5с
        # (Код ошибки Е62).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $branches_b = $tree->{'ALLDET'} || [];
                for my $b_b (@$branches_b) {
                    my $branches_b5b = $b_b->{'SETDET'}->[0]->{'AMT'} || [];
                    for my $b5b (@$branches_b5b) {
                        if (seq_key_value_exists($b5b->{fields}, '92B', ':EXCH')) {
                            return 0 unless (seq_key_value_exists($b5b->{fields}, '19A', ':RESU'));
                        } else {
                            return 0 if (seq_key_value_exists($b5b->{fields}, '19A', ':RESU'));
                        }
                    }
                }
                return 1;
            },
            err => 'E62',
        },

        # С5
        # Следующие поля определения сторон не могут присутствовать более одного раза в одном и том
        # же повторении последовательности В (Код ошибки Е84):
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $branches_b = $tree->{'ALLDET'} || [];
                for my $b_b (@$branches_b) {
                    my $seq = _flatten_tree($b_b);
                    for my $code (qw'BUYR DEAG DECU DEI1 DEI2 PSET REAG RECU REI1 REI2 SELL') {
                        my $count = seq_key_value_count($seq, '95[A-Z]', ":$code");
                        return 0 if ($count > 1);
                    }
                }
                return 1;
            },
            err => 'E84',
        },

        # С6
        # В каждом из повторений последовательности В, если полученные от третьей стороны непарные
        # инструкции являются инструкциями на поставку (поле :22Н::REDE//DELI в последовательности В),
        # то обязательно должен быть указан агент-получатель: в одном из повторений
        # подпоследовательности В5а «Стороны при расчетах» должно присутствовать поле :95a::REAG
        # (Код ошибки Е85).
        # В каждом из повторений последовательности В, если полученные от третьей стороны непарные
        # инструкции являются инструкциями на получение (поле :22Н::REDE//RECE в последовательности В),
        # то обязательно должен быть указан агент-поставщик: в одном из повторений
         #подпоследовательности В5а «Стороны при расчетах» должно присутствовать поле :95a::DEAG
         # (Код ошибки Е85).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);

                my $branches_b = $tree->{'ALLDET'} || [];
                for my $branch_b (@$branches_b) {

                    if (seq_key_value_exists($branch_b->{fields}, '22H', ':REDE//DELI')) {
                        my $branches_b5a = $branch_b->{'SETDET'}->[0]->{'SETPRTY'} || [];
                        my $flag = 0;
                        for my $b (@$branches_b5a) {
                            if (seq_key_value_exists($b->{fields}, '95[A-Z]', ':REAG')) {
                                $flag = 1;
                                last;
                            }
                        }
                        return 0 unless ($flag);
                    }

                    if (seq_key_value_exists($branch_b->{fields}, '22H', ':REDE//RECE')) {
                        my $branches_b5a = $branch_b->{'SETDET'}->[0]->{'SETPRTY'} || [];
                        my $flag = 0;
                        for my $b (@$branches_b5a) {
                            if (seq_key_value_exists($b->{fields}, '95[A-Z]', ':DEAG')) {
                                $flag = 1;
                                last;
                            }
                        }
                        return 0 unless ($flag);
                    }

                }

                return 1;
            },
            err => 'E85',
        },

        # С7
        # Если в поле :95a::4!с в подпоследовательности В5а присутствует определитель из Списка
        # поставщиков, то после него должны быть указаны все остальные определители, следующие за
        # ним в Списке поставщиков (см. ниже) (Код ошибки Е86).
        # Если в поле :95a::4!с в подпоследовательности B5а присутствует определитель из Списка
        # получателей, то после него должны быть указаны все остальные определители, следующие за
        # ним в Списке получателей (см. ниже).
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

                my $branches_b = $tree->{'ALLDET'} || [];
                for my $branch_b (@$branches_b) {
                    for my $x1 (keys %$rules) {
                        my $x2 = $rules->{$x1};
                        my $found1 = 0;
                        my $found2 = 0;
                        my $branches_b5a = $branch_b->{'SETDET'}->[0]->{'SETPRTY'} || [];
                        for my $b5a (@$branches_b5a) {
                            if (seq_key_value_exists($b5a->{fields}, '95[A-Z]', ":$x1")) {
                                $found1 = 1;
                            }
                            elsif (seq_key_value_exists($b5a->{fields}, '95[A-Z]', ":$x2")) {
                                $found2 = 1;
                            }
                        }
                        return 0 if ($found1 && !$found2);
                    }
                }

                return 1;
            },
            err => 'E86',
        },

        # С8
        # Если сообщение направляется для отмены, то есть если поле 23 G «Функция сообщения» содержит
        # код CANC, то в сообщении должна присутствовать хотя бы одна подпоследовательность
        # А1 «Связки», и в одном и только в одном повторении А1 должно присутствовать поле :20C::PREV.
        # Соответственно, в остальных повторениях А1 поле :20C::PREV не может использоваться.
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

        # С9
        # Если в подпоследовательности В5а присутствует поле :95a::PSET, то в той же
        # подпоследовательности не может использоваться поле :97a::SAFE (Код ошибки Е52).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);

                my $branches_b = $tree->{'ALLDET'} || [];
                for my $branch_b (@$branches_b) {
                    my $branches_b5a = $branch_b->{'SETDET'}->[0]->{'SETPRTY'} || [];
                    for my $b5a (@$branches_b5a) {
                        return 0 if (
                            seq_key_value_exists($b5a->{fields}, '95[A-Z]', ':PSET')
                            && seq_key_value_exists($b5a->{fields}, '97[A-Z]', ':SAFE')
                        );
                    }
                }

                return 1;
            },
            err => 'E52',
        },

        # C10
        # Дата валютирования должна указываться только в случае, если расчеты по ценным бумагам и
        # денежным средствам проводятся в разных местах (split settlement). Т.е. в любом повторении
        # подпоследовательности B5b если дата валютирования (поле :98a::VALU) присутствует, тогда в
        # последовательности B5 должны присутствовать поле :22F::STCO//SPST и сумма по расчетам
        # (поле :19A::SETT) в той же подпоследовательности B5b (Код ошибки С28).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);

                my $branches_b = $tree->{'ALLDET'} || [];
                for my $branch_b (@$branches_b) {
                    my $branch_b5 = $branch_b->{'SETDET'}->[0] || {};
                    my $branches_b5b = $branch_b->{'SETDET'}->[0]->{'AMT'} || [];
                    for my $branch_b5b (@$branches_b5b) {
                        if (seq_key_value_exists($branch_b5b->{fields}, '98[A-Z]', ':VALU')) {
                            return 0 unless (seq_key_value_exists($branch_b5->{fields}, '22F', ':STCO//SPST'));
                            return 0 unless (seq_key_value_exists($branch_b5b->{fields}, '19A', ':SETT'));
                        }
                    }
                }

                return 1;
            },
            err => 'C28',
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