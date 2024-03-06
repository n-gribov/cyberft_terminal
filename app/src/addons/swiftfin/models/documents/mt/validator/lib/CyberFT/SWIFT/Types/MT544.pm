package CyberFT::SWIFT::Types::MT544;
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
            key => '16S',
            required => 1,
        },
        {
            key => '20C',
            required => 1,
        },
        # B
        {
            key => '98a',
            key_regexp => '98[ABCE]',
            required => 1,
        },
        {
            key => '35B',
            required => 1,
        },
        # C
        {
            key => '36B',
            required => 1,
        },
        {
            key => '97a',
            key_regexp => '97[ABE]',
            required => 1,
        },
        # E
        {
            key => '22F',
            required => 1,
        },
        {
            key => '95a',
            key_regexp => '95[CPQRS]',
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
                        required_fields => ['20C', '23G'],
                    },
                    'GENL/LINK' => {
                        name => 'A1',
                        required => 1,
                        required_fields => ['20C'],
                    },
                    'TRADDET' => {
                        name => 'B',
                        required => 1,
                        required_fields => ['98[ABCE]', '35B'],
                    },
                    'TRADDET/FIA' => {
                        name => 'B1',
                        required => 0,
                        required_fields => [],
                    },
                    'FIAC' => {
                        name => 'C',
                        required => 1,
                        required_fields => ['36B', '97[ABE]'],
                    },
                    'FIAC/BREAK' => {
                        name => 'C1',
                        required => 0,
                        required_fields => [],
                    },
                    'REPO' => {
                        name => 'D',
                        required => 0,
                        required_fields => [],
                    },
                    'SETDET' => {
                        name => 'E',
                        required => 1,
                        required_fields => ['22F'],
                    },
                    'SETDET/SETPRTY' => {
                        name => 'E1',
                        required => 1,
                        required_fields => ['95[CPQRS]'],
                    },
                    'SETDET/CSHPRTY' => {
                        name => 'E2',
                        required => 0,
                        required_fields => ['95[PQRS]'],
                    },
                    'SETDET/AMT' => {
                        name => 'E3',
                        required => 0,
                        required_fields => ['19A'],
                    },
                    'OTHRPRTY' => {
                        name => 'F',
                        required => 0,
                        required_fields => ['95[CPQRS]'],
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
        # ��������� ���� ���� �� ����� �������������� ����� ��� � ����� ����������
        # ��������������������� �3 ������� (��� ������ �87):
        {
            func => sub {
                my $doc = shift;
                my $tree = build_seq_tree($doc->data);
                my $branches_e3 = $tree->{'SETDET'}->[0]->{'AMT'} || [];

                my @codes = qw(
                    ACRU ANTO BOOK CHAR COMT COUN DEAL EXEC ESTT ISDI LADT LEVY LOCL LOCO
                    MARG OTHR REGF SHIP SPCN STAM STEX TRAN TRAX VATA WITH COAX ACCA
                );

                for my $code (@codes) {
                    my $counter = 0;
                    for my $branch_e3 (@$branches_e3) {
                        if (seq_key_value_exists($branch_e3->{fields}, '19A', ":$code")) {
                            $counter++;
                        }
                    }
                    return 0 if ($counter > 1);
                }

                return 1;
            },
            err => 'E87',
        },

        # �2
        # ���� � ��������������������� �3 ������������ ���� :92B::EXCH ����� �����������, �� � ���
        # �� ��������������������� ������ ����� �������������� ���� :19A::RESU
        # ������ ����� �����������. ���� ���� ����� ����������� �����������, �� ����
        # ������ ����� ����������� �������������� �� ����� (��� ������ �62).
        {
            func => sub {
                my $doc = shift;
                my $tree = build_seq_tree($doc->data);
                my $branches_e3 = $tree->{'SETDET'}->[0]->{'AMT'} || [];

                for my $branch_e3 (@$branches_e3) {
                    if (seq_key_value_exists($branch_e3->{fields}, '92B', ':EXCH')) {
                        return 0 unless (seq_key_value_exists($branch_e3->{fields}, '19A', ':RESU'));
                    } else {
                        return 0 if (seq_key_value_exists($branch_e3->{fields}, '19A', ':RESU'));
                    }
                }

                return 1;
            },
            err => 'E62',
        },

        # �3
        # ��������� ���� ����������� ������ �� ����� �������������� ����� ������ ���� � ���������
        # (��� ������ �84):
        {
            func => sub {
                my $doc = shift;
                my $tree = build_seq_tree($doc->data);
                my $fields = flatten_tree($tree);

                # E1
                for my $code (qw(BUYR DEAG DECU DEI1 DEI2 PSET REAG RECU REI1 REI2 SELL)) {
                    return 0 if (seq_key_value_count($fields, '95[A-Z]', ":$code") > 1);
                }

                # E2
                for my $code (qw(ACCW BENM PAYE DEBT INTM)) {
                    return 0 if (seq_key_value_count($fields, '95[A-Z]', ":$code") > 1);
                }

                # F
                for my $code (qw(EXCH MEOR MERE TRRE VEND TRAG QFIN BRKR)) {
                    return 0 if (seq_key_value_count($fields, '95[A-Z]', ":$code") > 1);
                }

                return 1;
            },
            err => 'E84',
        },

        # C4
        # ���� � ������������������ � �� ������������ ���� :22F�::DBNM, �� � ��������� �����������
        # ������ ���� ������� ����� �� ��������� � ����� ���������� ��������: � ����� �� ����������
        # ��������������������� �1 �������� ��� ��������� ������ �������������� ���� :95a::DEAG, � �
        # ������ ���������� � ���� :95a::PSET (��� ������ �91).
        {
            func => sub {
                my $doc = shift;
                my $tree = build_seq_tree($doc->data);
                my $branch_e = $tree->{'SETDET'}->[0] || {};
                my $branches_e1 = $tree->{'SETDET'}->[0]->{'SETPRTY'} || [];

                if (!seq_key_value_exists($branch_e->{fields}, '22F', ':DBNM')) {
                    my ($found_reag, $found_pset);
                    for my $branch_e1 (@$branches_e1) {
                        if (seq_key_value_exists($branch_e1->{fields}, '95[A-Z]', ':DEAG')) {
                            $found_reag = 1;
                            next;
                        }
                        if (seq_key_value_exists($branch_e1->{fields}, '95[A-Z]', ':PSET')) {
                            $found_pset = 1;
                            next;
                        }
                    }
                    return 0 unless($found_reag && $found_pset);
                }

                return 1;
            },
            err => 'E91',
        },

        # �5
        # ���� � ���� :95a::4!� � ��������������������� �1 ������������ ������������ �� ������
        # ����������� (��. ����), �� ����� ���� ������ ���� ������� ��� ��������� ������������,
        # ��������� �� ��� � ������ ����������� (��� ������ �86).
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

                my $fields_e = flatten_tree($tree->{'SETDET'}->[0]) || [];
                for my $x1 (keys %$rules) {
                    my $x2 = $rules->{$x1};
                    return 0 if (
                        seq_key_value_exists($fields_e, '95[A-Z]', ":$x1")
                        && !seq_key_value_exists($fields_e, '95[A-Z]', ":$x2")
                    );
                }

                return 1;
            },
            err => 'E86',
        },

        # �6
        # ���� ��������� �������� ������ ��� �����, �.�. ���� ���� 23 G �������� ���������� ��������
        # ��� CANC ��� RVSL, �� � ����� � ������ � ����� ���������� ������������������ ������ �1 ������
        # �������������� ���� :20C::PREV. ��������������, � ��������� ����������� �1 ���� :20C::PREV
        # �� ����� �������������� (��� ������ �08).
        {
            func => sub {
                my $doc = shift;
                my $tree = build_seq_tree($doc->data);
                my $fields_a = $tree->{'GENL'}->[0]->{fields} || [];
                my $branches_a1 = $tree->{'GENL'}->[0]->{'LINK'} || [];
                if (seq_key_value_exists($fields_a, '23G', 'CANC|RVSL')) {
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

        # �7
        # ���� � ��������������������� �1 ������������ ���� :95a::PSET, �� � ��� ��
        # ������������������ �� ������ �������������� ���� :97a::SAFE (��� ������ �52).
        {
            func => sub {
                my $doc = shift;
                my $tree = build_seq_tree($doc->data);

                my $branches_e1 = $tree->{'SETDET'}->[0]->{'SETPRTY'} || [];
                for my $branch_e1 (@$branches_e1) {
                    return 0 if (
                        seq_key_value_exists($branch_e1->{fields}, '95[A-Z]', ':PSET')
                        && seq_key_value_exists($branch_e1->{fields}, '97[A-Z]', ':SAFE')
                    );
                }

                return 1;
            },
            err => 'E52',
        },

        # �8
        # � ��������� ������ ���� ������ �������� ����� ����������� ���������, �.�. � ����� � ������
        # � ����� ���������� ������������������ ������ �1 ������ �������������� ���� :20C::RELA; �
        # ��������� ����������� �1 ���� :20C::RELA �� ����� �������������� (��� ������ �73).
        {
            func => sub {
                my $doc = shift;
                my $tree = build_seq_tree($doc->data);

                my $branches_a1 = $tree->{'GENL'}->[0]->{'LINK'} || [];
                my $count = 0;
                for my $b (@$branches_a1) {
                    if (seq_key_value_exists($b->{fields}, '20C', ':RELA')) {
                        $count++;
                    }
                }
                return 0 if ($count != 1);

                return 1;
            },
            err => 'C73',
        },

        # �9
        # � ������������������ C ���� :36B::ESTT �� ����� �������������� ����� ���� ���. ���� ���
        # ���� ������������ ������, �� � ������ ������ ���� ���� ���������� ������ ���� �FAMT�, �
        # �� ������ ������ ���� ���� ���������� ������ ���� �AMOR� (��� ������ �71).
        {
            func => sub {
                my $doc = shift;
                my $tree = build_seq_tree($doc->data);

                my $fields_c = $tree->{'FIAC'}->[0]->{fields} || [];
                my $counter = 0;
                my ($first, $second);
                for my $item (@$fields_c) {
                    if ($item->{key} =~ /36B/ && $item->{value} =~ /:ESTT/) {
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

                return 1;
            },
            err => 'C71',
        },

        # �10
        # ���� ������������� ������ ���� ������� ������ ��� ����� ���������� ����������� ��������,
        # �� ����, ���� � ����� ���������� ��������������������� �3 ������������ ���� ����
        # ������������� :98a::VALU, �� � ��� �� ��������������������� �3 ������ �������������� ����
        # ����� ����������� �������� :19A::ESTT (��� ������ �28).
        {
            func => sub {
                my $doc = shift;
                my $tree = build_seq_tree($doc->data);
                my $branches_e3 = $tree->{'SETDET'}->[0]->{'AMT'} || [];

                for my $branch_e3 (@$branches_e3) {
                    if (seq_key_value_exists($branch_e3->{fields}, '98[A-Z]', ':VALU')) {
                        return 0 unless (seq_key_value_exists($branch_e3->{fields}, '19A', ':ESTT'));
                    }
                }

                return 1;
            },
            err => 'C28',
        },

        # C11
        # ���� � ������������������ F ������������ ���� :95a::EXCH �������� ����� ��� :95a::TRRE
        # ������������ �����, �� � ��� �� ������������������ �� ����� �������������� ���� :97a::
        # (��� ������ �63).
        {
            func => sub {
                my $doc = shift;
                my $tree = build_seq_tree($doc->data);

                my $branches_f = $tree->{'OTHRPRTY'} || [];
                for my $b (@$branches_f) {
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