package CyberFT::SWIFT::Types::MT502;
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
            key => '16S',
            required => 1,
        },
        # B
        {
            key => '22a',
            key_regexp => '22[FH]',
            required => 1,
        },
        {
            key => '98a',
            key_regexp => '98[ABC]',
            required => 1,
        },
        {
            key => '95a',
            key_regexp => '95[PQRS]',
            required => 1,
        },
        {
            key => '35B',
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
                        required_fields => ['20C', '23G', '22F'],
                    },
                    'GENL/LINK' => {
                        name => 'A1',
                        required => 0,
                        required_fields => ['20C'],
                    },
                    'ORDRDET' => {
                        name => 'B',
                        required => 1,
                        required_fields => ['22[FH]', '98[ABC]', '35B'],
                    },
                    'ORDRDET/PRIC' => {
                        name => 'B1',
                        required => 0,
                        required_fields => ['90[AB]'],
                    },
                    'ORDRDET/TRADPRTY' => {
                        name => 'B2',
                        required => 1,
                        required_fields => ['95[PQRS]'],
                    },
                    'ORDRDET/FIA' => {
                        name => 'B3',
                        required => 0,
                        required_fields => [],
                    },
                    'SETDET' => {
                        name => 'C',
                        required => 0,
                        required_fields => ['22F'],
                    },
                    'SETDET/SETPRTY' => {
                        name => 'C1',
                        required => 0,
                        required_fields => ['95[CPQRS]'],
                    },
                    'SETDET/CSHPRTY' => {
                        name => 'C2',
                        required => 0,
                        required_fields => ['95[PQRS]'],
                    },
                    'SETDET/AMT' => {
                        name => 'C3',
                        required => 0,
                        required_fields => ['19A'],
                    },
                    'OTHRPRTY' => {
                        name => 'D',
                        required => 0,
                        required_fields => ['95[PQRS]'],
                    },
                    'REPO' => {
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
        # Если в сообщении присутствует поле :92В::EXCH «Курс конвертации», то в той же
        # подпоследовательности должно также присутствовать поле :19А::RESU «Сумма после конвертации».
        # Если поле «Курс конвертации» отсутствует, то поле «Сумма после конвертации» использоваться
        # не может (Код ошибки Е62).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $branches_c3 = $tree->{'SETDET'}->[0]->{'AMT'} || [];

                for my $branch_c3 (@$branches_c3) {
                    if (seq_key_value_exists($branch_c3->{fields}, '92B', ':EXCH')) {
                        return 0 unless (seq_key_value_exists($branch_c3->{fields}, '19A', ':RESU'));
                    } else {
                        return 0 if (seq_key_value_exists($branch_c3->{fields}, '19A', ':RESU'));
                    }
                }

                return 1;
            },
            err => 'E62',
        },

        # С2
        # Если поле 23G «Функция сообщения» содержит код CANC, и если при этом в сообщении указано
        # «Количество в поручении» (поле :36В::ORDR), то в последовательности «Детали поручения»
        # должно быть указано «Подлежащее отмене количество» (поле : 36B::CANC).
        # Если поле 23G «Функция сообщения» содержит код CANC, и если при этом в сообщении указана
        # «Сумма поручения» (поле :19A::ORDR), то в последовательности «Детали поручения» должна
        # быть указана «Подлежащая отмене сумма» (поле :19A::CANC).
        # Если функция сообщения отлична CANC, то поля «Подлежащее отмене количество» и «Подлежащая
        # отмене сумма» не должны использоваться (Код ошибки Е64).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $branch_a = $tree->{'GENL'}->[0] || {};
                my $branch_b = $tree->{'ORDRDET'}->[0] || {};

                if (seq_key_value_exists($branch_a->{fields}, '23G', 'CANC')) {
                    if (seq_key_value_exists($branch_b->{fields}, '36B', ':ORDR')) {
                        return 0 unless (seq_key_value_exists($branch_b->{fields}, '36B', ':CANC'));
                    }
                    if (seq_key_value_exists($branch_b->{fields}, '19A', ':ORDR')) {
                        return 0 unless (seq_key_value_exists($branch_b->{fields}, '19A', ':CANC'));
                    }
                }
                else {
                    if (seq_key_value_exists($branch_b->{fields}, '36B', ':ORDR')) {
                        return 0 if (seq_key_value_exists($branch_b->{fields}, '36B', ':CANC'));
                    }
                    if (seq_key_value_exists($branch_b->{fields}, '19A', ':ORDR')) {
                        return 0 if (seq_key_value_exists($branch_b->{fields}, '19A', ':CANC'));
                    }
                }

                return 1;
            },
            err => 'E64',
        },

        # С3
        # В сообщении должно присутствовать поле :22F::TOOR «Признак типа поручения» и/или поле
        # :90a::LIMI «Ограничения по цене» (Код ошибки Е74).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $branch_b = $tree->{'ORDRDET'}->[0] || {};
                my $branches_b1 = $tree->{'ORDRDET'}->[0]->{'PRIC'} || [];

                unless (seq_key_value_exists($branch_b->{fields}, '22F', ':TOOR')) {
                    my $found_90alimi = 0;
                    for my $branch_b1 (@$branches_b1) {
                        if (seq_key_value_exists($branch_b1->{fields}, '90[A-Z]', ':LIMI')) {
                            $found_90alimi = 1;
                            last;
                        }
                    }
                    return 0 unless ($found_90alimi);
                }

                return 1;
            },
            err => 'E74',
        },

        # С4
        # Если сообщение направляется для отмены или для замены, т.е. если поле 23 G «Функция сообщения»
        # содержит код CANC или REPL, в сообщении должна присутствовать хотя бы одна
        # подпоследовательность А1 «Связки», и в одном и только в одном повторении А1 должно
        # присутствовать поле :20C::PREV. Соответственно, в остальных повторениях А1 поле :20C::PREV
        # не допускается. (Код ошибки Е08).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $fields_a = $tree->{'GENL'}->[0]->{fields} || [];
                my $branches_a1 = $tree->{'GENL'}->[0]->{'LINK'} || [];
                if (seq_key_value_exists($fields_a, '23G', 'CANC|REPL')) {
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

        # С5
        # Следующие поля определения сторон в подпоследовательностях С1 и С2 не могут присутство-вать
        # более одного раза в последовательности С. Поля определения сторон в последовательности D
        # не могут присутствовать более одного раза в сообщении (Код ошибки Е84):
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $fields = _flatten_tree($tree);

                # C1
                for my $code (qw(BUYR DEAG DECU DEI1 DEI2 PSET REAG RECU REI1 REI2 SELL)) {
                    return 0 if (seq_key_value_count($fields, '95[A-Z]', ":$code") > 1);
                }

                # C2
                for my $code (qw(ACCW BENM PAYE DEBT INTM)) {
                    return 0 if (seq_key_value_count($fields, '95[A-Z]', ":$code") > 1);
                }

                # D
                for my $code (qw(EXCH MEOR MERE TRRE VEND TRAG)) {
                    return 0 if (seq_key_value_count($fields, '95[A-Z]', ":$code") > 1);
                }

                return 1;
            },
            err => 'E84',
        },

        # С6
        # В последовательности В должно присутствовать либо поле :36B::ORDR «Количество в поручении»,
        # либо поле :19А::ORDR «Сумма поручения», но не оба эти поля вместе (Код ошибки Е58).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $branch_b = $tree->{'ORDRDET'}->[0] || {};

                if (seq_key_value_exists($branch_b->{fields}, '36B', ':ORDR')) {
                    return 0 if (seq_key_value_exists($branch_b->{fields}, '19A', ':ORDR'));
                }
                else {
                    return 0 unless (seq_key_value_exists($branch_b->{fields}, '19A', ':ORDR'));
                }

                return 1;
            },
            err => 'E58',
        },

        # С7
        # Если в поле :95a::4!с в подпоследовательности С1 присутствует определитель из Списка
        # поставщиков (см. ниже), то после него должны быть указаны все остальные определители,
        # следующие за ним в Списке поставщиков (Код ошибки Е86).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);

                my $rules = {
                    'DEI2' => 'DEI1',
                    'DEI1' => 'DECU',
                    'DECU' => 'SELL',
                    'SELL' => 'DEAG',
                    'REI2' => 'REI1',
                    'REI1' => 'RECU',
                    'RECU' => 'BUYR',
                    'BUYR' => 'REAG',
                };

                my $fields_c = _flatten_tree($tree->{'SETDET'}->[0]) || [];
                for my $x1 (keys %$rules) {
                    my $x2 = $rules->{$x1};
                    return 0 if (
                        seq_key_value_exists($fields_c, '95[A-Z]', ":$x1")
                        && !seq_key_value_exists($fields_c, '95[A-Z]', ":$x2")
                    );
                }

                return 1;
            },
            err => 'E86',
        },

        # С8
        # Если в подпоследовательности C1 присутствует поле :95a::PSET, то в той же
        # последовательности не может использоваться поле :97a::SAFE (Код ошибки Е52).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);

                my $branches_c1 = $tree->{'SETDET'}->[0]->{'SETPRTY'} || [];
                for my $branch_c1 (@$branches_c1) {
                    return 0 if (
                        seq_key_value_exists($branch_c1->{fields}, '95[A-Z]', ':PSET')
                        && seq_key_value_exists($branch_c1->{fields}, '97[A-Z]', ':SAFE')
                    );
                }

                return 1;
            },
            err => 'E52',
        },

        # С9
        # Если в последовательности В присутствует поле:22H::BUSE//SWIT, то подпоследовательность
        # А1 «Связки» является обязательной, и хотя бы в одном повторении подпоследовательности
        # А1 «Связки» должно присутствовать поле :20C::PREV. (Код ошибки Е53).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $branch_b = $tree->{'ORDRDET'}->[0] || {};
                my $branches_a1 = $tree->{'GENL'}->[0]->{'LINK'} || [];

                if (seq_key_value_exists($branch_b->{fields}, '22H', ':BUSE//SWIT')) {
                    return 0 if (scalar @$branches_a1 < 1);
                    my $count = 0;
                    for my $b (@$branches_a1) {
                        if (seq_key_value_exists($b->{fields}, '20C', ':PREV')) {
                            $count++;
                        }
                    }
                    return 0 if ($count == 0);
                }

                return 1;
            },
            err => 'E53',
        },

        # С10
        # Если в подпоследовательности С1 присутствует поле :22F::DBNM//VEND, то в сообщении должен
        # быть указан поставщик базы данных, т.е. в одном из повторений последовательности D должно
        # присутствовать поле :95a::VEND (Код ошибки D71).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);

                my $branch_c = $tree->{'SETDET'}->[0] || {};
                my $branches_d = $tree->{'OTHRPRTY'} || [];

                if (seq_key_value_exists($branch_c->{fields}, '22F', ':DBNM//VEND')) {
                    my $counter = 0;
                    for my $b (@$branches_d) {
                        $counter++ if (seq_key_value_exists($b->{fields}, '95[A-Z]', ':VEND'));
                    }
                    return 0 if ($counter < 1);
                }

                return 1;
            },
            err => 'D71',
        },

        # C11
        # Если в подпоследовательности D присутствует поле :95a::EXCH Фондовая биржа или :95a::TRRE
        # Регулирующий орган, то в той же последовательности не может использоваться поле :97a::
        # (Код ошибки Е63).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);

                my $branches_d = $tree->{'OTHRPRTY'} || [];
                for my $b (@$branches_d) {
                    if (seq_key_value_exists($b->{fields}, '95[A-Z]', ':EXCH')) {
                        return 0 if (seq_key_exists($b->{fields}, '97[A-Z]'));
                    }
                    if (seq_key_value_exists($b->{fields}, '95[A-Z]', ':TRRE')) {
                        return 0 if (seq_key_exists($b->{fields}, '97[A-Z]'));
                    }
                }

                return 1;
            },
            err => 'E63',
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