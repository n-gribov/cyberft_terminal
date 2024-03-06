package CyberFT::SWIFT::Types::MT538;
use CyberFT::SWIFT::Types::Utils;
use strict;
use Storable ();
use Data::Dumper;

our $ValidationProfile = {

    fields => [
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
            key => '69a',
            key_regexp => '69[AB]',
            required => 0,
        },
        {
            key => '97a',
            key_regexp => '97[AB]',
            required => 0,
        },
        {
            key => '17B',
            required => 0,
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
                        required_fields => ['28E', '20C', '23G', '69[AB]', '97[AB]', '17B'],
                    },
                    'GENL/LINK' => {
                        name => 'A1',
                        required => 0,
                        required_fields => ['20C'],
                    },
                    'FIN' => {
                        name => 'B',
                        required => 0,
                        required_fields => ['35B'],
                    },
                    'FIN/FIA' => {
                        name => 'B1',
                        required => 0,
                        required_fields => ['35B'],
                    },
                    'FIN/SUBBAL' => {
                        name => 'B2',
                        required => 1,
                        required_fields => ['93A'],
                    },
                    'FIN/SUBBAL/INPOS' => {
                        name => 'B2a',
                        required => 1,
                        required_fields => ['36B', '93A', '98[AC]'],
                    },
                    'FIN/SUBBAL/INPOS/LINK' => {
                        name => 'B2a1',
                        required => 0,
                        required_fields => ['20C'],
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
        # Если «Флаг действий» (поле :17B::ACTI) в последовательности А «Общая информация» имеет
        # значение «N», то последовательность В не должна использоваться. В противном случае
        # последовательность В обязательная (Код ошибки Е66).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $branch_a = $tree->{'GENL'}->[0] || {};
                my $branches_b = $tree->{'FIN'} || [];
                if (seq_key_value_exists($branch_a->{fields}, '17B', ':ACTI//N')) {
                    return 0 if (scalar @$branches_b > 0);
                } elsif (seq_key_value_exists($branch_a->{fields}, '17B', ':ACTI//Y')) {
                    return 0 if (scalar @$branches_b == 0);
                }
                return 1;
            },
            err => 'E66',
        },

        # С2
        # Значение поля «Списание с субсчета» должно быть отлично от значения поля «Зачисление на
        # субсчет»: во всех повторениях подпоследовательности В2 «Остаток на субсчете» подполе «Тип
        # остатка на субсчете» в поле :93A::FROM должно быть отлично от подполя «Тип остатка на
        # субсчете» во всех повторениях поля :93A::TOBA в подпоследовательности В2а «Движение внутри
        # позиции» (Код ошибки Е81).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $branches_b = $tree->{'FIN'} || [];
                for my $branch_b (@$branches_b) {
                    my $branches_b2 = $branch_b->{'SUBBAL'} || [];
                    for my $branch_b2 (@$branches_b2) {
                        my $from;
                        for my $f (@{$branch_b2->{fields}}) {
                            if ($f->{key} eq '93A' && $f->{value} =~ m`:FROM/\S*?/(\S+)`) {
                                $from = $1;
                                last;
                            }
                        }
                        my $branches_b2a = $branch_b2->{'INPOS'} || [];
                        for my $branch_b2a (@$branches_b2a) {
                            my $to;
                            for my $f (@{$branch_b2a->{fields}}) {
                                if ($f->{key} eq '93A' && $f->{value} =~ m`:TOBA/\S*?/(\S+)`) {
                                    $to = $1;
                                    last;
                                }
                            }
                            return 0 if ($from && $to && $from eq $to);
                        }
                    }
                }

                return 1;
            },
            err => 'E81',
        },

        # С3
        # Если сообщение направляется для отмены, т.е. если поле 23 G «Функция сообщения» содержит
        # код CANC, в сообщении должна присутствовать хотя бы одна подпоследовательность А1 «Связки»,
        # и в одном и только в одном повторении А1 должно присутствовать поле :20C::PREV.
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