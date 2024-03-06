package CyberFT::SWIFT::Types::MT574_IRSLST;
use CyberFT::SWIFT::Types::Utils;
use strict;
use Storable ();
use Data::Dumper;

our $ValidationProfile = {

    fields => [
        # ќписаны только об€зательные пол€ об€зательных последовательностей.
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
            key => '17B',
            required => 1,
        },
        # B
        {
            key => '22F',
            required => 1,
        },
        {
            key => '17B',
            required => 1,
        },
        {
            key => '95a',
            key_regexp => '95[ST]',
            required => 1,
        },
        {
            key => '94a',
            key_regexp => '94[CDG]',
            required => 1,
        },
    ],

    rules => [
        # ѕроверка об€зательных последовательностей и полей
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);

                my $sequences = {
                    'GENL' => {
                        name => 'A',
                        required => 1,
                        required_fields => ['28E', '20C', '23G', '98[ACE]', '17B'],
                    },
                    'GENL/LINK' => {
                        name => 'A1',
                        required => 0,
                        required_fields => ['20C'],
                    },
                    'BENODET' => {
                        name => 'B',
                        required => 1,
                        required_fields => ['22F'],
                    },
                    'BENODET/ADDRESS' => {
                        name => 'B1',
                        required => 1,
                        required_fields => ['17B', '95[ST]', '94[CDG]'],
                    },
                    'BENODET/INCOME' => {
                        name => 'B2',
                        required => 0,
                        required_fields => ['22F', '92A', '19A'],
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
    ]
};

# —троим дерево (под)последовательностей. ¬ основе функции лежит тот факт, что кажда€ последовательность
# начинаетс€ с пол€ 16R и заканчиваетс€ полем 16S с таким же содержанием. ѕоследовательности могут быть вложенными.
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

# ѕревращаем дерево (ветку) в плоский список полей.
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


# –екурсивна€ проверка об€зательных последовательностей и подпоследовательностей.
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

# –екурсивна€ проверка об€зательных полей в последовательност€х и подпоследовательност€х.
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

# –екурсивна€ проверка об€зательных последовательностей и полей.
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