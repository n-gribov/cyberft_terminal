package CyberFT::SWIFT::Types::MT503;
use CyberFT::SWIFT::Types::Utils;
use strict;
use Storable ();
use Data::Dumper;

our $ValidationProfile = {

    fields => [
        # ������� ������ ������������ ���� ������������ �������������������.
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
            key => '22a',
            key_regexp => '22[FH]',
            required => 1,
        },
        {
            key => '95a',
            key_regexp => '95[PRQ]',
            required => 1,
        },
        # B
        {
            key => '19B',
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
                        required_fields => ['20C', '23G', '22[FH]', '95[PQR]'],
                    },
                    'GENL/AGRE' => {
                        name => 'A1',
                        required => 1,
                        required_fields => [],
                    },
                    'GENL/LINK' => {
                        name => 'A2',
                        required => 0,
                        required_fields => ['20C'],
                    },
                    'SUMM' => {
                        name => 'B',
                        required => 1,
                        required_fields => ['19B'],
                    },
                    'SUMM/SUMD' => {
                        name => 'B1',
                        required => 0,
                        required_fields => [],
                    },
                    'COLD' => {
                        name => 'C',
                        required => 0,
                        required_fields => ['20C', '22[FH]'],
                    },
                    'COLD/SCOL' => {
                        name => 'C1',
                        required => 0,
                        required_fields => ['35B', '36B'],
                    },
                    'COLD/CCOL' => {
                        name => 'C2',
                        required => 0,
                        required_fields => ['19B', '22H'],
                    },
                    'COLD/BCOL' => {
                        name => 'C3',
                        required => 0,
                        required_fields => ['22H', '98[AB]', '95[PQR]', '19B'],
                    },
                    'ADDINFO' => {
                        name => 'F',
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
        # ���� ��������� ������������ ��� ������, �.�. ���� ���� 23 G �������� ���������� ��������
        # ��� CANC, � ��������� ������ �������������� ���� �� ���� ��������������������� �2 �������,
        # � � ����� � ������ � ����� ���������� �2 ������ �������������� ���� :20C::PREV.
        # ��������������, � ��������� ����������� �2 ���� :20C::PREV �� �����������. (��� ������ �08).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $fields_a = $tree->{'GENL'}->[0]->{fields} || [];
                my $branches_a2 = $tree->{'GENL'}->[0]->{'LINK'} || [];
                if (seq_key_value_exists($fields_a, '23G', 'CANC')) {
                    return 0 if (scalar @$branches_a2 < 1);
                    my $count = 0;
                    for my $b (@$branches_a2) {
                        if (seq_key_value_exists($b->{fields}, '20C', ':PREV')) {
                            $count++;
                        }
                    }
                    return 0 if ($count != 1);
                }
                elsif (seq_key_value_exists($fields_a, '23G', 'NEWM')) {
                    my $count = 0;
                    for my $b (@$branches_a2) {
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

        # �2
        # ���� � ������������������ � ����������� ���� :20C::SCTR, �� ���� :20C::RCTR ��������
        # ������������, � ��������� ������ ���� :20C::RCTR �������������� (��� ������ �68).
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

        # �3
        # ���� � ������������������ � ����������� ���� :19�::TEXA, �� ���� :19�::TCRL ��������
        # ������������, � ��������� ������ ���� :19�::TCRL �������������� (��� ������ �04).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $fields_b = $tree->{'SUMM'}->[0]->{fields} || [];
                
                unless (seq_key_value_exists($fields_b, '19B', ':TEXA')) {
                    return 0 unless (seq_key_value_exists($fields_b, '19B', ':TCRL'));
                }

                return 1;
            },
            err => 'C04',
        },

        # �4
        # � ������ ���������� ������������������ � ������������� ���������������������� �1, �2 � �3
        # ������� �� �������� ���� :22H::COLL//�������� ��������� ������� (��� ������ �83): (see docs)
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $branches_c = $tree->{'COLD'} || [];

                for my $b (@$branches_c) {
                    if (seq_key_value_exists($b->{fields}, '22H', ':COLL//SCOL')) {
                        return 0 unless (exists $b->{'SCOL'});
                        return 0 if (exists $b->{'CCOL'});
                        return 0 if (exists $b->{'BCOL'});
                    }
                    elsif (seq_key_value_exists($b->{fields}, '22H', ':COLL//CCOL')) {
                        return 0 if (exists $b->{'SCOL'});
                        return 0 unless (exists $b->{'CCOL'});
                        return 0 if (exists $b->{'BCOL'});
                    }
                    elsif (seq_key_value_exists($b->{fields}, '22H', ':COLL//BCOL')) {
                        return 0 if (exists $b->{'SCOL'});
                        return 0 if (exists $b->{'CCOL'});
                        return 0 unless (exists $b->{'BCOL'});
                    }
                }

                return 1;
            },
            err => 'E83',
        },

        # �5
        # � ������ ���������� ��������������������� �2 ������������� ���� :98A::MATU ������� ��
        # �������� ���� :22H::DEPO//�������� ��������� ������� (��� ������ �85): (see docs)
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);

                my $branches_c = $tree->{'COLD'} || [];
                for my $bc (@$branches_c) {
                    my $branches_c2 = $bc->{'CCOL'} || [];
                    for my $bc2 (@$branches_c2) {

                        if (seq_key_value_exists($bc2->{fields}, '22H', ':DEPO//FIXT')) {
                            return 0 unless (seq_key_value_exists($bc2->{fields}, '98A', ':MATU'));
                        }
                        elsif (seq_key_value_exists($bc2->{fields}, '22H', ':DEPO//CLNT')) {
                            return 0 if (seq_key_value_exists($bc2->{fields}, '98A', ':MATU'));
                        }

                    }
                }

                return 1;
            },
            err => 'E85',
        },

        # �6
        # � ����� ���������� ��������������������� �3, ���� � ��� ������������ ���� :22H::BCOL//LCOL,
        # �� ���� :98B::EXPI//OPEN (�.�. ������������ = EXPI, ��� ������� �������� ���������,
        # ������� ���� ����� = OPEN) �� ������ �������������� � ��� �� ����������, � ��������� ������
        # ���� :98B::EXPI//OPEN �������������� (��� ������ �72):
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);

                my $branches_c = $tree->{'COLD'} || [];
                for my $bc (@$branches_c) {
                    my $branches_c3 = $bc->{'BCOL'} || [];
                    for my $bc3 (@$branches_c3) {

                        if (seq_key_value_exists($bc3->{fields}, '22H', ':BCOL//LCOL')) {
                            return 0 if (seq_key_value_exists($bc3->{fields}, '98B', ':EXPI//OPEN'));
                        }

                    }
                }

                return 1;
            },
            err => 'E72',
        },

        # �7
        # � ������������������ � ������������� ���� :95a::EXPP ������� �� �������� ����
        # :22H::COAL//�������� � ������������������ � ��������� ������� (��� ������ �97):(see docs)
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $fields_a = $tree->{'GENL'}->[0]->{fields} || [];
                my $fields_b = $tree->{'SUMM'}->[0]->{fields} || [];

                if (seq_key_value_exists($fields_a, '22H', ':COAL//(INIT|VARI)')) {
                    return 0 unless (seq_key_value_exists($fields_b, '95[A-Z]', ':EXPP'));
                }
                elsif (seq_key_value_exists($fields_a, '22H', ':COAL//MATU')) {
                    return 0 if (seq_key_value_exists($fields_b, '95[A-Z]', ':EXPP'));
                }

                return 1;
            },
            err => 'E97',
        },

        # �8
        # � ����� ���������� ��������������������� �1, ���� � ��� ����������� ���� :22F::AGRE, ��
        # ���� :70�::AGRE �������� ������������ � ��� �� ����������, � ��������� ������ ����
        # :70�::AGRE �������������� (��� ������ �71):
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $branches_a1 = $tree->{'GENL'}->[0]->{'AGRE'} || [];

                for my $b (@$branches_a1) {
                    unless (seq_key_value_exists($b->{fields}, '22F', ':AGRE')) {
                        return 0 unless (seq_key_value_exists($b->{fields}, '70C', ':AGRE'));
                    }
                }

                return 1;
            },
            err => 'E71',
        },

        # �9
        # � ��������������������� �1 ���� 16R � 16S �� ����� ���� ������������� ������������� ������.
        # ���� ������������ ��� ���� 16R � 16S, �� ����������� ������ ����� �������������� ��� ����
        # �� ���� �� ��������� ����� ��� �� ��������������������� (��� ������ D13).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $branches_b1 = $tree->{'SUMM'}->[0]->{'SUMD'} || [];

                for my $b (@$branches_b1) {
                    my $fields = $b->{fields} || [];
                    return 0 if (scalar @$fields == 0);
                }

                return 1;
            },
            err => 'D13',
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