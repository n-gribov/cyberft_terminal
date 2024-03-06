package CyberFT::SWIFT::Types::MT514;
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
        # B
        {
            key => '98a',
            key_regexp => '98[ABCE]',
            required => 1,
        },
        {
            key => '22a',
            key_regexp => '22[FH]',
            required => 1,
        },
        {
            key => '95a',
            key_regexp => '95[PQRS]',
            required => 1,
        },
        {
            key => '36B',
            required => 1,
        },
        {
            key => '35B',
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
                        required_fields => ['20C', '23G', '22F'],
                    },
                    'GENL/LINK' => {
                        name => 'A1',
                        required => 0,
                        required_fields => ['20C'],
                    },
                    'CONFDET' => {
                        name => 'B',
                        required => 1,
                        required_fields => ['98[ABCE]', '22[FH]', '36B', '35B'],
                    },
                    'CONFDET/CONFPRTY' => {
                        name => 'B1',
                        required => 1,
                        required_fields => ['95[PQRS]'],
                    },
                    'CONFDET/FIA' => {
                        name => 'B2',
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

        # C1
        # If an Exchange Rate (field :92B::EXCH) is present, the corresponding Resulting Amount
        # (field :19A::RESU) must be present in the same subsequence. If the Exchange Rate is not
        # present, the Resulting Amount is not allowed (Error code(s): E62).
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

        # C2
        # When the Type of Price (field :22F::PRIC) is present, the Deal Price (field :90a::DEAL)
        # must also be present (Error code(s): E61).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);

                my $fields_b = $tree->{'CONFDET'}->[0]->{fields} || [];
                if (seq_key_value_exists($fields_b, '22F', ':PRIC')) {
                    return 0 unless (seq_key_value_exists($fields_b, '90[A-Z]', ':DEAL'));
                }

                return 1;
            },
            err => 'E61',
        },

        # C3
        # If the Settlement Amount (:19A::SETT) is present in sequence B, it must not be present
        # in any occurrence of subsequence C3 (Error code(s): E73).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);

                my $branch_b = $tree->{'CONFDET'}->[0] || {};
                my $branches_c3 = $tree->{'SETDET'}->[0]->{'AMT'} || [];

                if (seq_key_value_exists($branch_b->{fields}, '19A', ':SETT')) {
                    for my $branch_c3 (@$branches_c3) {
                        return 0 if (seq_key_value_exists($branch_c3->{fields}, '19A', ':SETT'));
                    }
                }

                return 1;
            },
            err => 'E73',
        },

        # C4
        # If the message is a cancellation, that is, Function of the Message (field 23G) is CANC,
        # then subsequence A1 (Linkages) must be present at least once in the message, and in one
        # and only one occurrence of A1, field :20C::PREV must be present; consequently, in all
        # other occurrences of A1, field :20C::PREV is not allowed (Error code(s): E08).
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
                return 1;
            },
            err => 'E08',
        },

        # C5
        # The following party fields for subsequences C1 and C2 cannot appear more than once in sequence D.
        # The party fields for sequence D cannot appear more than once in a message (Error code(s): E84):
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

        # C6
        # If a qualifier from the list Deliverers is present in a subsequence C1, in a field :95a::4!c,
        # then all the remaining qualifiers following this qualifier in the list Deliverers (see below)
        # must be present (Error code(s): E86).
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

        # C7
        # In subsequence C1, if field :95a::PSET is present, then field :97a::SAFE is not allowed
        # in the same sequence. (Error code(s): E52).
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

        # C8
        # If field :22F::DBNM//VEND is present in sequence C, then a vendor must be specified;
        # that is one occurrence of subsequence D must contain field :95a::VEND (Error code(s): D71).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);

                my $branch_c = $tree->{'SETDET'}->[0] || {};
                my $branches_d = $tree->{'OTHRPRTY'} || [];

                if (seq_key_value_exists($branch_c->{fields}, '22F', ':DBNM//VEND')) {
                    my $counter = 0;
                    for my $branch_d (@$branches_d) {
                        $counter++ if (seq_key_value_exists($branch_d->{fields}, '95[A-Z]', ':VEND'));
                    }
                    return 0 if ($counter < 1);
                }

                return 1;
            },
            err => 'D71',
        },

        # C9
        # In sequence D, if field :95a::EXCH Stock Exchange or :95a::TRRE Trade Regulator is present,
        # then field :97a:: is not allowed in the same sequence (Error code(s): E63).
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