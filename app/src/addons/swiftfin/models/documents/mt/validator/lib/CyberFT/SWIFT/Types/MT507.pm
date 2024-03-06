package CyberFT::SWIFT::Types::MT507;
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
            key => '22a',
            key_regexp => '22[FH]',
            required => 1,
        },
        {
            key => '95a',
            key_regexp => '95[PRQ]',
            required => 1,
        },
        {
            key => '25D',
            required => 1,
        },
        {
            key => '20C',
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
                        required_fields => ['20C', '23G', '22[FH]', '95[PQR]', '25D'],
                    },
                    'GENL/AGRE' => {
                        name => 'A1',
                        required => 1,
                        required_fields => [],
                    },
                    
                    'GENL/LINK' => {
                        name => 'A2',
                        required => 1,
                        required_fields => ['20C'],
                    },
                    'COLD' => {
                        name => 'B',
                        required => 0,
                        required_fields => ['20C', '22H', '25D'],
                    },
                    'COLD/SETTL' => {
                        name => 'B1',
                        required => 0,
                        required_fields => [],
                    },
                    'COLD/SETTL/SETDET' => {
                        name => 'B1a',
                        required => 0,
                        required_fields => ['22[FH]'],
                    },
                    'COLD/SETTL/SETDET/SETPRTY' => {
                        name => 'B1a1',
                        required => 0,
                        required_fields => ['95[CPQRS]'],
                    },
                    'COLD/SETTL/CASHSET' => {
                        name => 'B1b',
                        required => 0,
                        required_fields => [],
                    },
                    'COLD/SETTL/CASHSET/CSHPRTY' => {
                        name => 'B1b1',
                        required => 0,
                        required_fields => ['95[PQRS]'],
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

        # �2
        # ����������� � ��������� ������������������ � ������� �� �������� ���� :25D::4!c//<Status>
        # (�������) � ������������������ � � �� �������� ���� :13A::LINK//<Number Id>
        # (������������ ������) � ��������������������� �2 ��������� ������� (��� ������ D29):
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $fields_a = $tree->{'GENL'}->[0]->{fields} || [];
                my $branches_b = $tree->{'COLD'} || [];
                my $b_exists = (scalar @$branches_b > 0);
                my $field_25d = seq_get_first($fields_a, '25D');
                my $flatten_a = _flatten_tree($tree->{'GENL'}->[0]);

                if ($field_25d =~ m`:REST//ACC[TP]`) {
                    if (seq_key_value_exists($flatten_a, '13A', ':LINK//504')) {
                        return 0 unless ($b_exists);
                    }
                }
                elsif ($field_25d =~ m`(:REST//REJT|:CPRC|:IPRC)`) {
                    return 0 if ($b_exists);
                }

                return 1;
            },
            err => 'D29',
        },

        # �3
        # � ������ ���������� ������������������ � ����������� ��������������������� �1 ������� ��
        # �������� ���� :25D::COLL//<Status> (�������) � ���� :22�::COLL//<Indicator> (��������)
        # ��������� ������� (��� ������ �70):
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $branches_b = $tree->{'COLD'} || [];

                for my $b (@$branches_b) {
                    my $b1_exists = exists($b->{'SETTL'});
                    if (seq_key_value_exists($b->{fields}, '25D', ':COLL//ACCT')) {
                        if (seq_key_value_exists($b->{fields}, '22H', ':COLL//BCOL')) {
                            return 0 if ($b1_exists);
                        }
                        elsif (seq_key_value_exists($b->{fields}, '22H', ':COLL//(CCOL|SCOL)')) {
                            return 0 unless ($b1_exists);
                        }
                    }
                    elsif (seq_key_value_exists($b->{fields}, '25D', ':COLL//REJT')) {
                        return 0 if ($b1_exists);
                    }
                    elsif (seq_key_value_exists($b->{fields}, '25D', ':COLL/[^/]+')) {
                        if (seq_key_value_exists($b->{fields}, '22H', ':COLL//BCOL')) {
                            return 0 if ($b1_exists);
                        }
                    }
                }

                return 1;
            },
            err => 'C70',
        },

        # �4
        # � ����� ���������� ������������������ � � ���������������������� �1�1 � �1b1 ���������
        # ���� ����������� ������ �� ����� �������������� ����� ������ ���� (��� ������ �84):
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $branches_b = $tree->{'COLD'} || [];

                for my $b (@$branches_b) {
                    # B1a1
                    my $branches_b1a1 = $b->{'SETTL'}->[0]->{'SETDET'}->[0]->{'SETPRTY'} || [];
                    for my $code (qw{BUYR DEAG DECU DEI1 DEI2 PSET REAG RECU REI1 REI2 SELL}) {
                        my $counter = 0;
                        for my $bb (@$branches_b1a1) {
                            $counter += seq_key_value_count($bb->{fields}, '95[A-Z]', ":$code");
                        }
                        return 0 if ($counter > 1);
                    }
                    # B1b1
                    my $branches_b1b1 = $b->{'SETTL'}->[0]->{'CASHSET'}->[0]->{'CSHPRTY'} || [];
                    for my $code (qw{ACCW BENM PAYE DEBT INTM}) {
                        my $counter = 0;
                        for my $bb (@$branches_b1b1) {
                            $counter += seq_key_value_count($bb->{fields}, '95[A-Z]', ":$code");
                        }
                        return 0 if ($counter > 1);
                    }
                }

                return 1;
            },
            err => 'E84',
        },

        # �5
        # � ������������������ ������ ��� �������� �� ������ ������� (��������������������� �1�1)
        # ����������� ������ ���� ������ �����-���������:
        # � ��� ������ ���������� ������������������ � (���� ��� ������������) ������ �����������
        # ��������� �������: ���� ������������ ��������������������� �1�1 �������� ��� �������� ��
        # ������ �������, �� ���� :95a::DEAG ������ �������������� � ����� � ������ � �����
        # ��������-������������� �1�1 � ��� �� ���������� ������������������ � (��� ������ �91).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $branches_b = $tree->{'COLD'} || [];

                for my $b (@$branches_b) {
                    # B1a1
                    my $branches_b1a1 = $b->{'SETTL'}->[0]->{'SETDET'}->[0]->{'SETPRTY'} || [];
                    if (scalar(@$branches_b1a1) > 0) {
                        my $counter = 0;
                        for my $bb (@$branches_b1a1) {
                            $counter += seq_key_value_count($bb->{fields}, '95[A-Z]', ":REAG");
                        }
                        return 0 if ($counter != 1);
                     }
                }

                return 1;
            },
            err => 'E91',
        },

        # �6
        # ���� � ����� ���������� ������������������ � � ���� :95a::4!� ������ ����������
        # ��������-������������� �1�1 ������������ ������������ �� ������ ����������� (��. ����),
        # �� � ������ ����������� ��������������������� �1�1 � ��� �� ������������������ B ������
        # ���� ������� ��� ��������� ������������, ��������� �� ��� � ������ �����������
        # (��� ������ �86).
        # ���� � ����� ���������� ������������������ � � ���� :95a::4!� ������ ����������
        # ��������-������������� �1�1 ������������ ������������ �� ������ ����������� (��. ����),
        # �� � ������ ����������� ��������������������� �1�1 � ��� �� ������������������ � ������
        # ���� ������� ��� ��������� ������������, ��������� �� ��� � ������ �����������
        # (��� ������ �86).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);

                my $rules = {
                    'DEI2' => 'DEI1',
                    'DEI1' => 'DECU',
                    'DECU' => 'SELL',
                    'REI2' => 'REI1',
                    'REI1' => 'RECU',
                    'RECU' => 'BUYR',
                };

                my $branches_b = $tree->{'COLD'} || [];
                for my $branch_b (@$branches_b) {
                    # B1a1
                    my $branches = $branch_b->{'SETTL'}->[0]->{'SETDET'}->[0]->{'SETPRTY'} || [];

                    for my $x1 (keys %$rules) {
                        my $x2 = $rules->{$x1};

                        my ($exists_x1, $exists_x2);
                        for my $b (@$branches) {
                            if (seq_key_value_exists($b->{fields}, '95[A-Z]', ":$x1")) {
                                $exists_x1 = 1;
                            }
                            elsif (seq_key_value_exists($b->{fields}, '95[A-Z]', ":$x2")) {
                                $exists_x2 = 1;
                            }
                        }

                        return 0 if ($exists_x1 && !$exists_x2);
                    }
                }

                return 1;
            },
            err => 'E86',
        },

        # �7
        # � ������ ���������� ��������������������� �1� ������������� ��������������������� �1�1
        # ������� �� ����������� ���� :22F::STCO//NSSP ��������� ������� (��� ������ �48):
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $branches_b = $tree->{'COLD'} || [];

                for my $branch_b (@$branches_b) {
                    my $branch_b1a = $branch_b->{'SETTL'}->[0]->{'SETDET'}->[0] || undef;
                    if ($branch_b1a) {
                        if (seq_key_value_exists($branch_b1a->{fields}, '22F', ":STCO//NSSP")) {
                            my $branches_b1a1 = $branch_b1a->{'SETPRTY'} || [];
                            return 0 if (scalar(@$branches_b1a1) == 0);
                        }
                    }
                }

                return 1;
            },
            err => 'E48',
        },

        # �8
        # � ������ ���������� ��������������������� B1b ������������� ��������������������� �1b1
        # ������� �� ����������� ���� :22F::STCO//NSSP ��������� ������� (��� ������ �49):
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $branches_b = $tree->{'COLD'} || [];

                for my $branch_b (@$branches_b) {
                    my $branch_b1b = $branch_b->{'SETTL'}->[0]->{'CASHSET'}->[0] || undef;
                    if ($branch_b1b) {
                        if (seq_key_value_exists($branch_b1b->{fields}, '22F', ":STCO//NSSP")) {
                            my $branches_b1b1 = $branch_b1b->{'CSHPRTY'} || [];
                            return 0 if (scalar(@$branches_b1b1) == 0);
                        }
                    }
                }

                return 1;
            },
            err => 'E49',
        },

        # �9
        # � ����� ���������� ��������������������� �1, ���� � ��� ����������� ���� :22F::AGRE, ��
        # ���� :70�::AGRE �������� ������������, � ��������� ������ ���� :70�::AGRE ��������������
        # (��� ������ �71):
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

        # �11
        # � ������ ���������� ������������������ � (���� ��� ������������ � ���������), ���� �
        # ���� ������������������ ������������ ��������������������� �1, �������������
        # ���������������������� �1� � �1b ������� �� �������� ���� :22�::COLL//<Indicator>
        # (��������) � ������������������ � ��������� ������� (��� ������ �69):
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $branches_b = $tree->{'COLD'} || [];

                for my $b (@$branches_b) {
                    my $b1 = $b->{'SETTL'}->[0] || undef;
                    if ($b1) {
                        my $b1a = $b1->{'SETDET'}->[0] || undef;
                        my $b1b = $b1->{'CASHSET'}->[0] || undef;
                        if (seq_key_value_exists($b->{fields}, '22H', ':COLL//CCOL')) {
                            return 0 if ($b1a);
                            return 0 unless ($b1b);
                        }
                        elsif (seq_key_value_exists($b->{fields}, '22H', ':COLL//SCOL')) {
                            return 0 unless ($b1a);
                            return 0 if ($b1b);
                        }
                        elsif (seq_key_value_exists($b->{fields}, '22H', ':COLL//BCOL')) {
                            return 0;
                        }
                    }
                }

                return 1;
            },
            err => 'C69',
        },

        # �12
        # ���� :13a:LINK ������ �������������� � ����� � ������ � ����� ����������
        # ��������������������� �2 ������� (��� ������ D52).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);

                my $branches_a2 = $tree->{'GENL'}->[0]->{'LINK'} || [];
                my $count = 0;
                for my $b (@$branches_a2) {
                    if (seq_key_value_exists($b->{fields}, '13[A-Z]', '^:LINK')) {
                        $count++;
                    }
                }
                return 0 if ($count != 1);

                return 1;
            },
            err => 'D52',
        },

        # �13
        # ���� :20C::RELA ������ �������������� � ��� �� ���������� ��������������������� �2 �������,
        # � ������� ������������ ���� :13a:LINK (��� ������ D53).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);

                my $branches_a2 = $tree->{'GENL'}->[0]->{'LINK'} || [];
                for my $b (@$branches_a2) {
                    if (seq_key_value_exists($b->{fields}, '13[A-Z]', '^:LINK')) {
                        return 0 unless (seq_key_value_exists($b->{fields}, '20C', ':RELA'));
                    }
                }

                return 1;
            },
            err => 'D53',
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
    
    for my $path (keys %$seqs) {
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