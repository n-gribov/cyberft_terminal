package CyberFT::SWIFT::Types::MT567;
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
                        required => 1,
                        required_fields => ['20C'],
                    },
                    'GENL/STAT' => {
                        name => 'A2',
                        required => 1,
                        required_fields => ['25D'],
                    },
                    'GENL/STAT/REAS' => {
                        name => 'A2a',
                        required => 0,
                        required_fields => ['24B'],
                    },
                    'CADETL' => {
                        name => 'B',
                        required => 0,
                        required_fields => ['13A'],
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
        # В каждом повторении подпоследовательности А2а определитель в поле 24В должен совпадать с
        # кодовым словом («Код статуса»), которое указано с соответствующим определителем в поле 25D
        # той же подпоследовательности А2 (Код ошибки Е37).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);

                my $branches_a2 = $tree->{'GENL'}->[0]->{'STAT'} || [];
                for my $branch_a2 (@$branches_a2) {
                    my $value_25d = seq_get_first($branch_a2->{fields}, '25D');
                    next if ($value_25d =~ m/\/.+\//);
                    my $branches_a2a = $branch_a2->{'REAS'} || [];
                    for my $branch_a2a (@$branches_a2a) {

                        if (seq_key_value_exists($branch_a2a->{fields}, '24B', ':CAND')) {
                            return 0 unless ($value_25d =~ m/:(CPRC|IPRC)\/\/CAND/);
                        }
                        elsif (seq_key_value_exists($branch_a2a->{fields}, '24B', ':CANP')) {
                            return 0 unless ($value_25d =~ m/:CPRC\/\/CANP/);
                        }
                        elsif (seq_key_value_exists($branch_a2a->{fields}, '24B', ':CGEN')) {
                            return 0 unless ($value_25d =~ m/:IPRC\/\/CGEN/);
                        }
                        elsif (seq_key_value_exists($branch_a2a->{fields}, '24B', ':PACK')) {
                            return 0 unless ($value_25d =~ m/:(CPRC|IPRC)\/\/PACK/);
                        }
                        elsif (seq_key_value_exists($branch_a2a->{fields}, '24B', ':PEND')) {
                            return 0 unless ($value_25d =~ m/:(EPRC|IPRC)\/\/PEND/);
                        }
                        elsif (seq_key_value_exists($branch_a2a->{fields}, '24B', ':REJT')) {
                            return 0 unless ($value_25d =~ m/:(CPRC|IPRC)\/\/REJT/);
                        }
                    }
                }

                return 1;
            },
            err => 'E37',
        },

        # С2
        # Если сообщение направляется для запроса статуса инструкций или статуса запроса об отмене
        # (:23G::INST или CAST), И если в сообщении присутствует последовательность В, то в
        # последовательности В должны быть указаны номер и код варианта корпоративного действия
        # (т.е. поля :13A::CAON и :22a::CAOP являются обязательными) (Код ошибки D29).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $fields_a = $tree->{'GENL'}->[0]->{fields} || [];
                my $branch_b = $tree->{'CADETL'}->[0] || undef;
                if (seq_key_value_exists($fields_a, '23G', 'INST|CAST') && defined($branch_b)) {
                    return 0 unless (seq_key_value_exists($branch_b->{fields}, '13A', ':CAON'));
                    return 0 unless (seq_key_value_exists($branch_b->{fields}, '22[A-Z]', ':CAOP'));
                }
                elsif (seq_key_value_exists($fields_a, '23G', 'EVST') && defined($branch_b)) {
                    return 0;
                }
                return 1;
            },
            err => 'D29',
        },

        # С3
        # В последовательности В поле :36B::STAQ или QREC не может использоваться более двух раз.
        # Если это поле используется дважды, то в одном случае Код типа количества должен быть «FAMT»,
        # а в другом случае Код типа количества должен быть «AMOR» (Код ошибки С71).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);

                my $branch_b = $tree->{'CADETL'}->[0] || {};
                my $counter = 0;
                my ($first, $second);
                for my $item (@{$branch_b->{fields}}) {
                    if ($item->{key} =~ /36B/ && $item->{value} =~ /^:(STAQ|QREC)/) {
                        $counter++;
                        $first = $item->{value} if ($counter==1);
                        $second = $item->{value} if ($counter==2);
                        return 0 if ($counter==2 && ($first !~ /\/FAMT/ || $second !~ /\/AMOR/));
                        return 0 if ($counter>2);
                    }
                }

                return 1;
            },
            err => 'C71',
        },

        # С4
        # Если сообщение извещает о статусе запроса об отмене (:23G::CAST), то каждом повторении
        # подпоследовательности А2 «Статус» должен быть указан статус обработки запроса об отмене
        # ( поле :25D::CPRC… ).
        # Если сообщение извещает о статусе инструкций (:23G::INST), то каждом повторении
        # подпоследовательности А2 «Статус» должен быть указан статус обработки инструкции
        # (поле :25D::IPRC…)
        # Если сообщение извещает о статусе обработки события корпоративного дейстивия (:23G::EVST),
        # то каждом повторении подпоследовательности А2 «Статус» должен быть указан статус
        # коропративного действия ( поле :25D::EPRC…. )(Код ошибки С65).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $fields_a = $tree->{'GENL'}->[0]->{fields} || [];
                my $branches_a2 = $tree->{'GENL'}->[0]->{'STAT'} || [];

                if (seq_key_value_exists($fields_a, '23G', 'CAST')) {
                        for my $branch_a2 (@$branches_a2) {
                            return 0 unless (seq_key_value_exists($branch_a2->{fields}, '25D', ':CPRC'));
                        }
                }
                elsif (seq_key_value_exists($fields_a, '23G', 'INST')) {
                        for my $branch_a2 (@$branches_a2) {
                            return 0 unless (seq_key_value_exists($branch_a2->{fields}, '25D', ':IPRC'));
                        }
                }
                elsif (seq_key_value_exists($fields_a, '23G', 'EVST')) {
                        for my $branch_a2 (@$branches_a2) {
                            return 0 unless (seq_key_value_exists($branch_a2->{fields}, '25D', ':EPRC'));
                        }
                }

                return 1;
            },
            err => 'C65',
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