package CyberFT::SWIFT::Types::MT567;
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
            key => '22F',
            required => 1,
        },
        {
            key => '25D',
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

        # �1
        # � ������ ���������� ��������������������� �2� ������������ � ���� 24� ������ ��������� �
        # ������� ������ (���� �������), ������� ������� � ��������������� ������������� � ���� 25D
        # ��� �� ��������������������� �2 (��� ������ �37).
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

        # �2
        # ���� ��������� ������������ ��� ������� ������� ���������� ��� ������� ������� �� ������
        # (:23G::INST ��� CAST), � ���� � ��������� ������������ ������������������ �, �� �
        # ������������������ � ������ ���� ������� ����� � ��� �������� �������������� ��������
        # (�.�. ���� :13A::CAON � :22a::CAOP �������� �������������) (��� ������ D29).
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

        # �3
        # � ������������������ � ���� :36B::STAQ ��� QREC �� ����� �������������� ����� ���� ���.
        # ���� ��� ���� ������������ ������, �� � ����� ������ ��� ���� ���������� ������ ���� �FAMT�,
        # � � ������ ������ ��� ���� ���������� ������ ���� �AMOR� (��� ������ �71).
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

        # �4
        # ���� ��������� �������� � ������� ������� �� ������ (:23G::CAST), �� ������ ����������
        # ��������������������� �2 ������� ������ ���� ������ ������ ��������� ������� �� ������
        # ( ���� :25D::CPRC� ).
        # ���� ��������� �������� � ������� ���������� (:23G::INST), �� ������ ����������
        # ��������������������� �2 ������� ������ ���� ������ ������ ��������� ����������
        # (���� :25D::IPRC�)
        # ���� ��������� �������� � ������� ��������� ������� �������������� ��������� (:23G::EVST),
        # �� ������ ���������� ��������������������� �2 ������� ������ ���� ������ ������
        # �������������� �������� ( ���� :25D::EPRC�. )(��� ������ �65).
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