package CyberFT::SWIFT::Types::MT527;
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
            key => '22a',
            key_regexp => '22[FH]',
            required => 1,
        },
        {
            key => '95a',
            key_regexp => '95[PRQ]',
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
                        required_fields => ['28E', '20C', '23G', '98[ACE]', '22[FH]'],
                    },
                    'GENL/COLLPRTY' => {
                        name => 'A1',
                        required => 1,
                        required_fields => ['95[PQR]'],
                    },
                    'GENL/LINK' => {
                        name => 'A2',
                        required => 0,
                        required_fields => ['20C'],
                    },
                    'DEALTRAN' => {
                        name => 'B',
                        # В правиле С3 есть условие, когда этой последовательности быть не должно,
                        # хотя она и отмечена как обязательная.
                        required => 0,      
                        required_fields => ['98[ABC]'],
                    },
                    'SECMOVE' => {
                        name => 'C',
                        required => 0,
                        required_fields => ['22H', '35B', '36B', '17B'],
                    },
                    'CASHMOVE' => {
                        name => 'D',
                        required => 0,
                        required_fields => ['22H', '19A'],
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
        # Если в последовательности А отсутствует поле :20C::CLCI «Референс инструкций по
        # залоговому обеспечению клиента», то обязательно должно присутствовать поле :20C::TRCI
        # «Референс инструкций с трехсторонним обеспечением агента», в противном случае поле
        # :20C::TRCI необязательное (Код ошибки Е64).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $fields_a = $tree->{'GENL'}->[0]->{fields} || [];

                unless (seq_key_value_exists($fields_a, '20C', ':CLCI')) {
                    return 0 unless (seq_key_value_exists($fields_a, '20C', ':TRCI'));
                }

                return 1;
            },
            err => 'E64',
        },

        # С2
        # Если в последовательности А отсутствует поле :20C::SCTR «Референс операции с обеспечением
        # Отправителя», то обязательно должно присутствовать поле :20C::RCTR «Референс операции с
        # обеспечением Получателя», в противном случае поле :20C::RCTR необязательное (Код ошибки Е68).
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
        # Если в последовательности А в поле :23G: «Функция сообщения» подполе 1 имеет значение REPL,
        # то последовательность В не должна использоваться; в остальных случаях последовательность В
        # обязательная (Код ошибки Е08).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $fields_a = $tree->{'GENL'}->[0]->{fields} || [];

                if (seq_key_value_exists($fields_a, '23G', ':REPL')) {
                    return 0 if (defined $tree->{DEALTRAN}->[0]);
                }
                elsif (seq_key_value_exists($fields_a, '23G', ':NEWM|:CANC')) {
                    return 0 unless (defined $tree->{DEALTRAN}->[0]);
                } 
                else {
                    # В списке полей последовательность B указана, как обязательная, 
                    # хотя первая часть данного правила этому противоречит. 
                    return (0, "Missing required sequence: B") unless (defined $tree->{DEALTRAN}->[0]);
                }

                return 1;
            },
            err => 'E08',
        },

        # С4
        # Если в последовательности А значение поля :22F::COLA// отлично от «SLEB» и если в
        # сообщении присутствует последовательность В, то поле :19A::TRAA является обязательным
        # (Код ошибки Е65).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $fields_a = $tree->{'GENL'}->[0]->{fields} || [];
                my $branch_b = $tree->{DEALTRAN}->[0] || undef;

                if (defined $branch_b) {
                    my $not_sleb = 0;
                    for my $x (@$fields_a) {
                        if ($x->{key} eq '22F' && $x->{value} =~ /:COLA\/\/(.*)/) {
                            my $v = $1;
                            if ($v !~ /SLEB/) {
                                $not_sleb = 1;
                                last;
                            }
                        }
                    }
                    if ($not_sleb) {
                        return 0 unless (seq_key_value_exists($branch_b->{fields}, '19A', ':TRAA'));
                    }
                }
                else {
                    return 0 if (seq_key_value_exists($fields_a, '22F', ':COLA//'));
                }

                return 1;
            },
            err => 'E65',
        },

        # С5
        # В каждом повторении последовательности А, все определители в поле 98a являются
        # необязательными, но как минимум один (любой) должен присутствовать (Код ошибки D92).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $fields_a = $tree->{'GENL'}->[0]->{fields} || [];
                return 0 unless (seq_key_value_exists($fields_a, '98[ACE]', 'PREP|EXRQ|TRAD|SETT'));
                return 1;
            },
            err => 'D92',
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