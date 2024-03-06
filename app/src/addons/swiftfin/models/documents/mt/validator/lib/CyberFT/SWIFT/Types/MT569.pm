package CyberFT::SWIFT::Types::MT569;
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
            key => '22a',
            key_regexp => '22[FH]',
            required => 1,
        },
        # B
        {
            key => '19A',
            required => 1,
        },
        {
            key => '98a',
            key_regexp => '98[AC]',
            required => 1,
        },
        # C
        {
            key => '22a',
            key_regexp => '22[FH]',
            required => 1,
        },
        {
            key => '19A',
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
                        required_fields => ['28E', '20C', '23G', '22[FH]'],
                    },
                    'GENL/COLLPRTY' => {
                        name => 'A1',
                        required => 0,
                        required_fields => ['95[PQR]'],
                    },
                    'GENL/LINK' => {
                        name => 'A2',
                        required => 0,
                        required_fields => ['20C'],
                    },
                    'SUMM' => {
                        name => 'B',
                        required => 1,
                        required_fields => ['19A', '98[AC]'],
                    },
                    'SUME' => {
                        name => 'C',
                        required => 1,
                        required_fields => ['22[FH]', '19A'],
                    },
                    'SUME/SUMC' => {
                        name => 'C1',
                        required => 0,
                        required_fields => ['95[PQR]', '19A'],
                    },
                    'SUME/SUMC/TRANSDET' => {
                        name => 'C1a',
                        required => 1,
                        required_fields => ['20C', '98[ABC]', '19A'],
                    },
                    'SUME/SUMC/TRANSDET/VALDET' => {
                        name => 'C1a1',
                        required => 0,
                        required_fields => ['17B', '19A', '92[AB]'],
                    },
                    'SUME/SUMC/TRANSDET/VALDET/SECDET' => {
                        name => 'C1a1A',
                        required => 0,
                        required_fields => ['35B', '36B'],
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
        # В каждом повторении подпоследовательности С1а1 использование подпоследовательности С1а1А
        # зависит от значения поля :17B::SECU//«Флаг» следующим образом (Код ошибки Е66):
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                for my $b_c (@{ $tree->{'SUME'} }) {
                    for my $b_c1 (@{ $b_c->{'SUMC'} }) {
                        for my $b_c1a (@{ $b_c1->{'TRANSDET'} }) {
                            for my $b_c1a1 (@{ $b_c1a->{'VALDET'} }) {
                                if (seq_key_value_exists($b_c1a1->{fields}, '17B', ':SECU//Y')) {
                                    return 0 unless (defined $b_c1a1->{'SECDET'}->[0]);
                                }
                                elsif (seq_key_value_exists($b_c1a1->{fields}, '17B', ':SECU//N')) {
                                    return 0 if (defined $b_c1a1->{'SECDET'}->[0]);
                                }
                            }
                        }
                    }
                }
                return 1;
            },
            err => 'E66',
        },

        # С2
        # В каждом повторении подпоследовательности С1а1 использование поля :98a::SETT зависит
        # от значения поля :17B::COLL следующим образом (Код ошибки Е72):
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                for my $b_c (@{ $tree->{'SUME'} }) {
                    for my $b_c1 (@{ $b_c->{'SUMC'} }) {
                        for my $b_c1a (@{ $b_c1->{'TRANSDET'} }) {
                            for my $b_c1a1 (@{ $b_c1a->{'VALDET'} }) {
                                if (seq_key_value_exists($b_c1a1->{fields}, '17B', ':COLL//Y')) {
                                    return 0 unless (seq_key_value_exists($b_c1a1->{fields}, '98[A-Z]', ':SETT'));
                                }
                                elsif (seq_key_value_exists($b_c1a1->{fields}, '17B', ':COLL//N')) {
                                    return 0 if (seq_key_value_exists($b_c1a1->{fields}, '98[A-Z]', ':SETT'));
                                }
                            }
                        }
                    }
                }
                return 1;
            },
            err => 'E72',
        },

        # С3
        # В каждом повторении подпоследовательности С1а1А использование поля :70C::RATS зависит
        # от присутствия в этой подпоследовательности поля :94B::RATS следующим образом (Код ошибки Е60):
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                for my $b_c (@{ $tree->{'SUME'} }) {
                    for my $b_c1 (@{ $b_c->{'SUMC'} }) {
                        for my $b_c1a (@{ $b_c1->{'TRANSDET'} }) {
                            for my $b_c1a1 (@{ $b_c1a->{'VALDET'} }) {
                                for my $b_c1a1a (@{ $b_c1a1->{'SECDET'} }) {
                                    if (seq_key_value_exists($b_c1a1a->{fields}, '94B', ':RATS')) {
                                        return 0 unless (seq_key_value_exists($b_c1a1a->{fields}, '70C', ':RATS'));
                                    }
                                    else {
                                        return 0 if (seq_key_value_exists($b_c1a1a->{fields}, '70C', ':RATS'));
                                    }
                                }
                            }
                        }
                    }
                }
                return 1;
            },
            err => 'E60',
        },

        # С4
        # Если сообщение направляется для отмены, т.е. если поле 23 G «Функция сообщения»
        # содержит код CANC, в сообщении должна присутствовать хотя бы одна подпоследовательность
        # А2 «Связки», и в одном и только в одном повторении А2 должно присутствовать поле :20C::PREV.
        # Соответственно, в остальных повторениях А2 поле :20C::PREV не допускается. (Код ошибки Е08).
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