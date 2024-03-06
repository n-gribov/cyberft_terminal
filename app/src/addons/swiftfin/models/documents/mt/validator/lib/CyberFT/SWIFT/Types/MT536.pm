package CyberFT::SWIFT::Types::MT536;
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
            key => '69a',
            key_regexp => '69[AB]',
            required => 1,
        },
        {
            key => '22F',
            required => 1,
        },
        {
            key => '97a',
            key_regexp => '97[AB]',
            required => 1,
        },
        {
            key => '17B',
            required => 1,
        },
        {
            key => '16S',
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
                        required_fields => ['28E', '20C', '23G', '69[AB]', '22F', '97[AB]', '17B'],
                    },
                    'GENL/LINK' => {
                        name => 'A1',
                        required => 0,
                        required_fields => ['20C'],
                    },
                    'SUBSAFE' => {
                        name => 'B',
                        required => 0,
                        required_fields => [],
                    },
                    'SUBSAFE/FIN' => {
                        name => 'B1',
                        required => 0,
                        required_fields => ['35B'],
                    },
                    'SUBSAFE/FIN/TRAN' => {
                        name => 'B1a',
                        required => 1,
                        required_fields => [],
                    },
                    'SUBSAFE/FIN/TRAN/LINK' => {
                        name => 'B1a1',
                        required => 1,
                        required_fields => ['20C'],
                    },
                    'SUBSAFE/FIN/TRAN/TRANSDET' => {
                        name => 'B1a2',
                        required => 0,
                        required_fields => ['36B', '22[FH]', '98[ABC]'],
                    },
                    'SUBSAFE/FIN/TRAN/TRANSDET/SETPRTY' => {
                        name => 'B1a2a',
                        required => 0,
                        required_fields => ['95[CPQR]'],
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

        # �1 ���� ����� �������� (���� :17B::ACTI) � ������������������ � ������ ����������� �����
        # �������� �N�, �� ������������������ � �� ������ ��������������. � ��������� ������
        # ������������������ � ������������ (��� ������ �66).
        {
            func => sub {
                my $doc = shift;
                my $tree = build_seq_tree($doc->data);
                my $fields_a = $tree->{'GENL'}->[0]->{fields} || [];
                my $branches_b = $tree->{'SUBSAFE'} || [];
                if (seq_key_value_exists($fields_a, '17B', ':ACTI//N')) {
                    return 0 if (scalar @$branches_b > 0);
                }
                else {
                    return 0 if (scalar @$branches_b == 0);
                }
                return 1;
            },
            err => 'E66',
        },

        # �2 ���� ���������� ��������������� �������� ������ ������� (:22H::PAYM//APMT), ��
        # ����������� ������ ���� ������� �����, ���������� �� ����� (���� :19A::PSTA).
        # ��� ������� ��������� � ��������������������� �1�2 (��� ������ �83).
        {
            func => sub {
                my $doc = shift;
                my $tree = build_seq_tree($doc->data);
                # ������������ �� ������ �� B1a2
                my $branches_b = $tree->{'SUBSAFE'} || [];                  # B
                for my $b_b (@$branches_b) {
                    my $branches_b1 = $b_b->{'FIN'} || [];                  # B1
                    for my $b_b1 (@$branches_b1) {
                        my $branches_b1a = $b_b1->{'TRAN'} || [];           # B1a
                        for my $b_b1a (@$branches_b1a) {
                            my $fields_b1a2 = $b_b1a->{'TRANSDET'}->[0]->{fields} || [];
                            if (seq_key_value_exists($fields_b1a2, '22H', ':PAYM//APMT')) {
                                return 0 unless seq_key_value_exists($fields_b1a2, '19A', ':PSTA');
                            }
                        }
                    }
                }
                return 1;
            },
            err => 'E83',
        },

        # �3 � ������ �� ���������� ��������������������� �1� � ��������������������� �1�2 ���������
        # ���� ����������� ������ �� ����� �������������� ����� ������ ���� (��� ������ �84):
        {
            func => sub {
                my $doc = shift;
                my $tree = build_seq_tree($doc->data);
                # ������������ �� ������ �� B1a2
                my $branches_b = $tree->{'SUBSAFE'} || [];                  # B
                for my $b_b (@$branches_b) {
                    my $branches_b1 = $b_b->{'FIN'} || [];                  # B1
                    for my $b_b1 (@$branches_b1) {
                        my $branches_b1a = $b_b1->{'TRAN'} || [];           # B1a
                        for my $b_b1a (@$branches_b1a) {
                            my $fields_b1a2 = flatten_tree($b_b1a->{'TRANSDET'}->[0]);
                            for my $c (qw`BUYR DEAG DECU DEI1 DEI2 PSET REAG RECU REI1 REI2 SELL`) {
                                return 0 if (seq_key_value_count($fields_b1a2, '95[A-Z]', ":$c") > 1);
                            }
                        }
                    }
                }
                return 1;
            },
            err => 'E84',
        },

        # �4 ���� � ���� :95a::4!� ��������������������� �1�2� ������������ ������������ �� ������
        # �����������/1 , �� ����� ���� ������ ���� ������� ��� ��������� ������������,
        # ��������� �� ��� � ������ �����������/1 (��� ������ �86).
        # � ���� � ��������������������� �1�2� ������������ ���� :95a::DEI2, �� � ������ ��������������������� �1�2� ������ �������������� ���� :95a::DEI1.
        # � ���� � ��������������������� �1�2� ������������ ���� :95a::DEI1, �� � ������ ��������������������� �1�2� ������ �������������� ���� :95a::DECU.
        # � ���� � ��������������������� �1�2� ������������ ���� :95a::DECU, �� � ������ ��������������������� �1�2� ������ �������������� ���� :95a::SELL.
        # � ���� � ��������������������� �1�2� ������������ ���� :95a::REI2, �� � ������ ��������������������� �1�2� ������ �������������� ���� :95a::REI1.
        # � ���� � ��������������������� �1�2� ������������ ���� :95a::REI1, �� � ������ ��������������������� �1�2� ������ �������������� ���� :95a::RECU.
        # � ���� � ��������������������� �1�2� ������������ ���� :95a::RECU, �� � ������ ��������������������� B1�2� ������ �������������� ���� :95a::BUYR.
        {
            func => sub {
                my $doc = shift;
                my $tree = build_seq_tree($doc->data);
                my $rules = {
                    'DEI2' => 'DEI1',
                    'DEI1' => 'DECU',
                    'DECU' => 'SELL',
                    'REI2' => 'REI1',
                    'REI1' => 'RECU',
                    'RECU' => 'BUYR',
                };
                # ������������ �� ������ �� B1a2
                my $branches_b = $tree->{'SUBSAFE'} || [];                  # B
                for my $b_b (@$branches_b) {
                    my $branches_b1 = $b_b->{'FIN'} || [];                  # B1
                    for my $b_b1 (@$branches_b1) {
                        my $branches_b1a = $b_b1->{'TRAN'} || [];           # B1a
                        for my $b_b1a (@$branches_b1a) {
                            my $fields_b1a2 = flatten_tree($b_b1a->{'TRANSDET'}->[0]);
                            for my $x1 (keys %$rules) {
                                my $x2 = $rules->{$x1};
                                return 0 if (
                                    seq_key_value_exists($fields_b1a2, '95[A-Z]', ":$x1")
                                    && !seq_key_value_exists($fields_b1a2, '95[A-Z]', ":$x2")
                                );
                            }
                        }
                    }
                }
                return 1;
            },
            err => 'E86',
        },

        # �5 ���� ��������� ������������ ��� ������, �.�. ���� ���� 23 G �������� ���������� ��������
        # ��� CANC, � ��������� ������ �������������� ���� �� ���� ��������������������� �1 �������,
        # � � ����� � ������ � ����� ���������� �1 ������ �������������� ���� :20C::PREV.
        # ��������������, � ��������� ����������� �1 ���� :20C::PREV �� �����������. (��� ������ �08).
        # ������������������ � ���� ���� :23G:... �� ��������������������� �1 ... � ���� :20C::PREV ...
        # CANC   ������������, �.�. ������ �������������� ���� �� ���� ��������������������� �1   ������������ � ����� �����-����� �1 � �� ����������� �� ���� ��������� ����������� �1.
        # NEWM   ��������������           �� �����������
        {
            func => sub {
                my $doc = shift;
                my $tree = build_seq_tree($doc->data);
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

        # �6 ���� � ��������������������� �1�2� ������������ ���� :95a::PSET, �� � ��� ��
        # ������������������ �� ����� �������������� ���� :97a::SAFE (��� ������ �52).
        # ��������������������� �1�2� ���� ���� :95a::PSET � ��������������������� �1�2� �� ���� :97a::SAFE �
        # ������������                  �� ����� �������������� � ��� �� ������������������
        # �����������                   ��������������
        {
            func => sub {
                my $doc = shift;
                my $tree = build_seq_tree($doc->data);
                # ������������ �� ������ �� B1a2a
                my $branches_b = $tree->{'SUBSAFE'} || [];                        # B
                for my $b_b (@$branches_b) {
                    my $branches_b1 = $b_b->{'FIN'} || [];                        # B1
                    for my $b_b1 (@$branches_b1) {
                        my $branches_b1a = $b_b1->{'TRAN'} || [];                 # B1a
                        for my $b_b1a (@$branches_b1a) {
                            my $branch_b1a2 = $b_b1a->{'TRANSDET'}->[0] || {};    # B1a2
                            my $branches_b1a2a = $branch_b1a2->{'SETPRTY'} || []; # B1a2a
                            for my $b (@$branches_b1a2a) {
                                return 0 if (
                                    seq_key_value_exists($b->{fields}, '95[A-Z]', ':PSET')
                                    && seq_key_value_exists($b->{fields}, '97[A-Z]', ':SAFE')
                                );
                            }
                        }
                    }
                }
                return 1;
            },
            err => 'E52',
        },

        # �7 ���� � ������������������ � ���� :17B::CONS ����� �������� �Y�, �� � ������ �� ����������
        # ������������������ � �������� ���� ���� :97a::SAFE � ���� :17B::ACTI �������� �������������
        # (��� ������ �56).
        # ���������� ����� ������� ����������� ������ � ��� �������, ����� � ��������� ������������
        # ������������������ � �������� ���� (�������� ������� �1), �.�. ����� ���� :17B::ACTI �����
        # �������� �Y�.
        {
            func => sub {
                my $doc = shift;
                my $tree = build_seq_tree($doc->data);
                my $fields_a = $tree->{'GENL'}->[0]->{fields} || [];
                my $branches_b = $tree->{'SUBSAFE'} || [];
                if (
                    seq_key_value_exists($fields_a, '17B', ':CONS//Y')
                    && seq_key_value_exists($fields_a, '17B', ':ACTI//Y')
                ){
                    for my $b (@$branches_b) {
                        return 0 unless seq_key_value_exists($b->{fields}, '97[A-Z]', ':SAFE');
                        return 0 unless seq_key_value_exists($b->{fields}, '17B', ':ACTI');
                    }
                }
                return 1;
            },
            err => 'E56',
        },

        # �8 ���� ����� �������� (���� :17B::ACTI) � ������������������ � �������� ���� ���������
        # �� ���������� ���������� ��� ����������, �.�. ����� �������� �N�, �� ���������������������
        # �1 ����������� ���������� �� ������ ��������������. � ��������� ������� ���������������������
        # �1 ����������� ���������� ������������ (��� ������ �69).
        # ���������� ����� ������� ����������� ������ � ��� �������, ����� � ��������� ������������
        # ������������������ � �������� ���� (�������� ������� �1), �.�. ����� ���� :17B::ACTI �����
        # �������� �Y�.
        {
            func => sub {
                my $doc = shift;
                my $tree = build_seq_tree($doc->data);
                my $branches_b = $tree->{'SUBSAFE'} || [];
                for my $b_b (@$branches_b) {
                    my $branches_b1 = $b_b->{'FIN'} || [];
                    if (seq_key_value_exists($b_b->{fields}, '17B', ':ACTI//Y')) {
                        return 0 if (scalar @$branches_b1 == 0);
                    }
                    elsif (seq_key_value_exists($b_b->{fields}, '17B', ':ACTI//N')) {
                        return 0 if (scalar @$branches_b1 > 0);
                    }
                    elsif (!seq_key_value_exists($b_b->{fields}, '17B', ':ACTI')) {
                        return 0 if (scalar @$branches_b1 == 0);
                    }
                }

                return 1;
            },
            err => 'E69',
        },

        # �9 � ����� ���������� ��������������������� �1a2 ���� :36B::PSTA �� ����� ��������������
        # ����� ���� ���. ���� ��� ���� ������������ ������, �� � ������ ������ ��� ���� ����������
        # ������ ���� �FAMT�, � �� ������ ������ ��� ���� ���������� ������ ���� �AMOR� (��� ������ �71).
        {
            func => sub {
                my $doc = shift;
                my $tree = build_seq_tree($doc->data);
                # ������������ �� ������ �� B1a2
                my $branches_b = $tree->{'SUBSAFE'} || [];                  # B
                for my $b_b (@$branches_b) {
                    my $branches_b1 = $b_b->{'FIN'} || [];                  # B1
                    for my $b_b1 (@$branches_b1) {
                        my $branches_b1a = $b_b1->{'TRAN'} || [];           # B1a
                        for my $b_b1a (@$branches_b1a) {
                            my $fields_b1a2 = $b_b1a->{'TRANSDET'}->[0]->{fields} || [];
                            my $counter = 0;
                            my ($first, $second);
                            for my $item (@$fields_b1a2) {
                                if ($item->{key} =~ /36B/ && $item->{value} =~ /:PSTA/) {
                                    $counter++;
                                    if ($counter == 1) {
                                        $first = $item->{value};
                                    }
                                    elsif ($counter == 2) {
                                        $second = $item->{value};
                                        return 0 unless ($first =~ /FAMT/ && $second =~ /AMOR/);
                                    }
                                    else {
                                        return 0;
                                    }
                                }
                            }
                        }
                    }
                }
                return 1;
            },
            err => 'C71',
        },

        # �10 ��� ������ ���������� � ������� �������� ������ ���� ������ �������� ����������� �����
        # ���������, �.�. � ������ ���������� ��������������������� �1� ���������� ���� :20C::RELA
        # ������ �������������� � ����� � ������ � ����� ���������� ��������������������� �1�1 �������;
        # � ��������� ����������� �1�1 ���� :20C::RELA �� ����������� (��� ������ �73).
        {
            func => sub {
                my $doc = shift;
                my $tree = build_seq_tree($doc->data);
                # ������������ �� ������ �� B1a1
                my $branches_b = $tree->{'SUBSAFE'} || [];                  # B
                for my $b_b (@$branches_b) {
                    my $branches_b1 = $b_b->{'FIN'} || [];                  # B1
                    for my $b_b1 (@$branches_b1) {
                        my $branches_b1a = $b_b1->{'TRAN'} || [];           # B1a
                        for my $b_b1a (@$branches_b1a) {
                            my $branches_b1a1 = $b_b1a->{'LINK'} || [];      # B1a1
                            my $counter = 0;
                            for my $b (@$branches_b1a1) {
                                $counter++ if (seq_key_value_exists($b->{fields}, '20C', ':RELA'));
                            }
                            return 0 if ($counter != 1);
                        }
                    }
                }
                return 1;
            },
            err => 'C73',
        },

    ]
};

# ������ ������ (���)�������������������. � ������ ������� ����� ��� ����, ��� ������ ������������������
# ���������� � ���� 16R � ������������� ����� 16S � ����� �� �����������. ������������������ ����� ���� ����������.
sub build_seq_tree {
    my $data = shift;
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
            push @{$tree->{$inner_seq_name}}, build_seq_tree($inner_seq);
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

    return $tree;
}

# ���������� ������ (�����) � ������� ������ �����.
sub flatten_tree {
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
                my $branch_items = flatten_tree($branch);
                push @$items, @$branch_items;
            }
        }
    }

    return $items;
}

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