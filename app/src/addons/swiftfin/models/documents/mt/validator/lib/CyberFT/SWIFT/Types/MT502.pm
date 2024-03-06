package CyberFT::SWIFT::Types::MT502;
use CyberFT::SWIFT::Types::Utils;
use strict;
use Storable ();
use Data::Dumper;

our $ValidationProfile = {

    fields => [
        # ������� ������ ������������ ���� ������������ �������������������.
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
        # �������� ������������ ������������������� � �����
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

        # �1
        # ���� � ��������� ������������ ���� :92�::EXCH ����� �����������, �� � ��� ��
        # ��������������������� ������ ����� �������������� ���� :19�::RESU ������ ����� �����������.
        # ���� ���� ����� ����������� �����������, �� ���� ������ ����� ����������� ��������������
        # �� ����� (��� ������ �62).
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

        # �2
        # ���� ���� 23G �������� ���������� �������� ��� CANC, � ���� ��� ���� � ��������� �������
        # ����������� � ��������� (���� :36�::ORDR), �� � ������������������ ������� ����������
        # ������ ���� ������� ����������� ������ ���������� (���� : 36B::CANC).
        # ���� ���� 23G �������� ���������� �������� ��� CANC, � ���� ��� ���� � ��������� �������
        # ������ ���������� (���� :19A::ORDR), �� � ������������������ ������� ���������� ������
        # ���� ������� ����������� ������ ����� (���� :19A::CANC).
        # ���� ������� ��������� ������� CANC, �� ���� ����������� ������ ���������� � �����������
        # ������ ����� �� ������ �������������� (��� ������ �64).
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

        # �3
        # � ��������� ������ �������������� ���� :22F::TOOR �������� ���� ���������� �/��� ����
        # :90a::LIMI ������������ �� ���� (��� ������ �74).
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

        # �4
        # ���� ��������� ������������ ��� ������ ��� ��� ������, �.�. ���� ���� 23 G �������� ����������
        # �������� ��� CANC ��� REPL, � ��������� ������ �������������� ���� �� ����
        # ��������������������� �1 �������, � � ����� � ������ � ����� ���������� �1 ������
        # �������������� ���� :20C::PREV. ��������������, � ��������� ����������� �1 ���� :20C::PREV
        # �� �����������. (��� ������ �08).
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

        # �5
        # ��������� ���� ����������� ������ � ���������������������� �1 � �2 �� ����� ����������-����
        # ����� ������ ���� � ������������������ �. ���� ����������� ������ � ������������������ D
        # �� ����� �������������� ����� ������ ���� � ��������� (��� ������ �84):
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

        # �6
        # � ������������������ � ������ �������������� ���� ���� :36B::ORDR ����������� � ���������,
        # ���� ���� :19�::ORDR ������ ����������, �� �� ��� ��� ���� ������ (��� ������ �58).
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

        # �7
        # ���� � ���� :95a::4!� � ��������������������� �1 ������������ ������������ �� ������
        # ����������� (��. ����), �� ����� ���� ������ ���� ������� ��� ��������� ������������,
        # ��������� �� ��� � ������ ����������� (��� ������ �86).
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

        # �8
        # ���� � ��������������������� C1 ������������ ���� :95a::PSET, �� � ��� ��
        # ������������������ �� ����� �������������� ���� :97a::SAFE (��� ������ �52).
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

        # �9
        # ���� � ������������������ � ������������ ����:22H::BUSE//SWIT, �� ���������������������
        # �1 ������� �������� ������������, � ���� �� � ����� ���������� ���������������������
        # �1 ������� ������ �������������� ���� :20C::PREV. (��� ������ �53).
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

        # �10
        # ���� � ��������������������� �1 ������������ ���� :22F::DBNM//VEND, �� � ��������� ������
        # ���� ������ ��������� ���� ������, �.�. � ����� �� ���������� ������������������ D ������
        # �������������� ���� :95a::VEND (��� ������ D71).
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
        # ���� � ��������������������� D ������������ ���� :95a::EXCH �������� ����� ��� :95a::TRRE
        # ������������ �����, �� � ��� �� ������������������ �� ����� �������������� ���� :97a::
        # (��� ������ �63).
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

# ������ ������ (���)�������������������. � ������ ������� ����� ��� ����, ��� ������ ������������������
# ���������� � ���� 16R � ������������� ����� 16S � ����� �� �����������. ������������������ ����� ���� ����������.
sub _get_seq_tree {
    my %params = @_;
    my $data;

    if (defined $params{doc}) {
        # ��������, ���� �� �������������� ���������
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
            # ������ ���������������������
            $inner_seq_started = 1;
            $inner_seq_name = $value;
            $inner_seq = [];
        }
        elsif ($key eq '16S' && $inner_seq_started && $inner_seq_name eq $value) {
            # ����� ���������������������
            push @{$tree->{$inner_seq_name}}, _get_seq_tree(data => $inner_seq);
            $inner_seq_started = 0;
            $inner_seq_name = '';
            $inner_seq = [];
        }
        elsif ($inner_seq_started) {
            # ���� ������ ���������������������
            push @$inner_seq, $item;
        }
        else {
            # ����, ������������� ������ ������������������
            push @{$tree->{fields}}, $item;
        }
    }

    if (defined $params{doc}) {
        $params{doc}->{__get_seq_tree_result__} = $tree;
    }

    return $tree;
}

# ���������� ������ (�����) � ������� ������ �����.
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

# ����������� �������� ������������ ������������������� � ����������������������. 
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

# ����������� �������� ������������ ����� � ������������������� � ����������������������. 
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

# ����������� �������� ������������ ������������������� � �����. 
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