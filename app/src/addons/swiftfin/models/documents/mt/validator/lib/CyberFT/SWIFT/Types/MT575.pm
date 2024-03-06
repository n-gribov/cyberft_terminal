package CyberFT::SWIFT::Types::MT575;
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
            key => '17B',
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
                        required_fields => ['28E', '20C', '23G', '69[AB]', '17B'],
                    },
                    'GENL/LINK' => {
                        name => 'A1',
                        required => 0,
                        required_fields => ['20C'],
                    },
                    'CASHACCT' => {
                        name => 'B',
                        required => 0,
                        required_fields => ['97[AE]'],
                    },
                    'CASHACCT/ACTCURR' => {
                        name => 'B1',
                        required => 1,
                        required_fields => ['11A', '17B', '93D'],
                    },
                    'CASHACCT/ACTCURR/ACTINFO' => {
                        name => 'B1a',
                        required => 0,
                        required_fields => [],
                    },
                    'CASHACCT/ACTCURR/ACTINFO/LINK' => {
                        name => 'B1a1',
                        required => 1,
                        required_fields => ['20C'],
                    },
                    'CASHACCT/ACTCURR/ACTINFO/CASHDET' => {
                        name => 'B1a2',
                        required => 0,
                        required_fields => ['19A', '22[FH]', '98[ABC]'],
                    },
                    'CASHACCT/ACTCURR/ACTINFO/CASHSECDET' => {
                        name => 'B1a3',
                        required => 0,
                        required_fields => ['19A', '36B', '35B', '22[FH]', '98[ABC]'],
                    },
                    'CASHACCT/ACTCURR/ACTINFO/SETPRTY' => {
                        name => 'B1a4',
                        required => 0,
                        required_fields => ['95[CPQR]'],
                    },
                    'FREEASS' => {
                        name => 'C',
                        required => 0,
                        required_fields => [],
                    },
                    'FREEASS/LINK' => {
                        name => 'C1',
                        required => 1,
                        required_fields => ['20C'],
                    },
                    'FREEASS/TRANSDET' => {
                        name => 'C2',
                        required => 0,
                        required_fields => ['36B', '35B', '22[FH]', '98[ABC]'],
                    },
                    'FREEASS/TRANSDET/SETPRTY' => {
                        name => 'C2a',
                        required => 0,
                        required_fields => ['95[CPQR]'],
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

        # �1
        # ���� ����� �������� (���� :17B::ACTI) � ������������������ � ������ ����������� ���������
        # �� ���������� ���������� ��� ������, �� ���� ����� �������� �N�, �� ������������������ �
        # ��������� �� ������ �������� ������� � ������������������ � ��������� ������� ��� �������
        # �� ������ ��������������. � ��������� ������ ������������������ � � � ��������������
        # (��� ������ �66).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $fields_a = $tree->{'GENL'}->[0]->{fields} || [];
                if (seq_key_value_exists($fields_a, '17B', 'ACTI//N')) {
                    return 0 if (defined $tree->{'CASHACCT'}->[0]);
                    return 0 if (defined $tree->{'FREEASS'}->[0]);
                }
                return 1;
            },
            err => 'E66',
        },

        # �2
        # � ������ �� ���������� ��������������������� �1 ��������� � ������� �� �������, ����
        # ����� �������� (���� :17B::ACTI) ��������� �� ���������� ���������� ��� ������, �� ����
        # ����� �������� �N�, �� ��������������������� �1� ��������� � ������� �� ��������� ��
        # ������ �������������� � ���� ��������������������� �1.
        # ���� ���� :17B::ACTI ����� �������� �Y�, �� ��������������������� �1� ������������
        # (��� ������ �95).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);

                for my $b_b (@{ $tree->{'CASHACCT'} }) {
                    for my $b_b1 (@{ $b_b->{'ACTCURR'} }) {
                        if (seq_key_value_exists($b_b1->{fields}, '17B', 'ACTI//N')) {
                            return 0 if (defined $b_b1->{'ACTINFO'}->[0]);
                        }
                        if (seq_key_value_exists($b_b1->{fields}, '17B', 'ACTI//Y')) {
                            return 0 unless (defined $b_b1->{'ACTINFO'}->[0]);
                        }
                    }
                }

                return 1;
            },
            err => 'E95',
        },

        # �3
        # � ��������������������� �1� ��������� � ������� �� ��������� ��������������������� �1�2
        # ��������� ������ �������� ������� � �1�3 ��������� �������� ������� � ������ �����
        # �������� ������������������ (��� ������ �96).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);

                for my $b_b (@{ $tree->{'CASHACCT'} }) {
                    for my $b_b1 (@{ $b_b->{'ACTCURR'} }) {
                        for my $b_b1a (@{ $b_b1->{'ACTINFO'} }) {
                            my $exists_b1a2 = defined($b_b1a->{'CASHDET'}->[0]);
                            my $exists_b1a3 = defined($b_b1a->{'CASHSECDET'}->[0]);
                            if ($exists_b1a2) {
                                return 0 if ($exists_b1a3);
                            }
                            else {
                                return 0 unless ($exists_b1a3);
                            }
                        }
                    }
                }

                return 1;
            },
            err => 'E96',
        },

        # �4
        # ��������� ���� ����������� ������ �� ����� �������������� ����� ������ ���� � ����� � ���
        # �� ���������� ��������������������� �1� (��� ������ �84):
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);

                for my $b_b (@{ $tree->{'CASHACCT'} }) {
                    for my $b_b1 (@{ $b_b->{'ACTCURR'} }) {
                        for my $b_b1a (@{ $b_b1->{'ACTINFO'} }) {
                            my $fields = _flatten_tree($b_b1a);
                            for my $code (qw(BUYR DEAG DECU DEI1 DEI2 PSET REAG RECU REI1 REI2 SELL)) {
                                if (seq_key_value_count($fields, '95[A-Z]', ":$code") > 1) {
                                    return 0;
                                }
                            }
                        }
                    }
                }

                return 1;
            },
            err => 'E84',
        },

        # �5
        # ���� � ���� :95a::4!� ��������������������� �1�4 ������������ ������������ �� ������
        # ����������� (��. ����), �� ������ ���� ������� ��� ��������� ������������, ��������� �� ���
        # � ������ ����������� (��� ������ �86).
        # ���� � ���� :95a::4!� ��������������������� �1�4 ������������ ������������ �� ������
        # ����������� (��. ����), �� ������ ���� ������� ��� ��������� ������������, ��������� �� ���
        # � ������ �����������.
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

                for my $b_b (@{ $tree->{'CASHACCT'} }) {
                    for my $b_b1 (@{ $b_b->{'ACTCURR'} }) {
                        for my $b_b1a (@{ $b_b1->{'ACTINFO'} }) {
                            for my $x1 (keys %$rules) {
                                my $x2 = $rules->{$x1};
                                my $exists_x1 = 0;
                                my $exists_x2 = 0;
                                for my $b_b1a4 (@{ $b_b1a->{'SETPRTY'} }) {
                                    if (seq_key_value_exists($b_b1a4->{fields}, '95[A-Z]', ":$x1")) {
                                        $exists_x1 = 1;
                                    } elsif (seq_key_value_exists($b_b1a4->{fields}, '95[A-Z]', ":$x2")) {
                                        $exists_x2 = 1;
                                    }
                                };
                                return 0 if ($exists_x1 && !$exists_x2);
                            }
                        }
                    }
                }

                return 1;
            },
            err => 'E86',
        },

        # �6
        # ��������� ���� ����������� ������ �� ����� �������������� ����� ������ ���� � ����� � ���
        # �� ���������� ��������������������� �2 (��� ������ �84):
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);

                for my $b_c (@{ $tree->{'FREEASS'} }) {
                    for my $b_c2 (@{ $b_c->{'TRANSDET'} }) {
                        my $fields = _flatten_tree($b_c2);
                        for my $code (qw(BUYR DEAG DECU DEI1 DEI2 PSET REAG RECU REI1 REI2 SELL)) {
                            if (seq_key_value_count($fields, '95[A-Z]', ":$code") > 1) {
                                return 0;
                            }
                        }
                    }
                }

                return 1;
            },
            err => 'E84',
        },

        # �7
        # ���� � ���� :95a::4!� ��������������������� �2� ������������ ������������ �� ������
        # ����������� (��. ����), �� ������ ���� ������� ��� ��������� ������������, ��������� ��
        # ��� � ������ ����������� (��� ������ �86).
        # ���� � ���� :95a::4!� ��������������������� �2� ������������ ������������ �� ������
        # ����������� (��. ����), �� ������ ���� ������� ��� ��������� ������������, ��������� ��
        # ��� � ������ �����������.
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

                for my $b_c (@{ $tree->{'FREEASS'} }) {
                    for my $b_c2 (@{ $b_c->{'TRANSDET'} }) {
                        for my $x1 (keys %$rules) {
                            my $x2 = $rules->{$x1};
                            my $exists_x1 = 0;
                            my $exists_x2 = 0;
                            for my $b_c2a (@{ $b_c2->{'SETPRTY'} }) {
                                if (seq_key_value_exists($b_c2a->{fields}, '95[A-Z]', ":$x1")) {
                                    $exists_x1 = 1;
                                } elsif (seq_key_value_exists($b_c2a->{fields}, '95[A-Z]', ":$x2")) {
                                    $exists_x2 = 1;
                                }
                            };
                            return 0 if ($exists_x1 && !$exists_x2);
                        }
                    }
                }

                return 1;
            },
            err => 'E86',
        },

        # �8
        # ���� ��������� ������������ ��� ������, �.�. ���� ���� 23G �������� ���������� ��������
        # ��� CANC, � ��������� ������ �������������� ���� �� ���� ��������������������� �1 �������,
        # � � ����� � ������ � ����� ���������� �1 ������ �������������� ���� :20C::PREV.
        # ��������������, � ��������� ����������� �1 ���� :20C::PREV �� ����� ��������������
        # (��� ������ �08).
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

        # C9
        # ���� � ��������������������� �1�4 ������������ ���� :95a::PSET, �� � ��� ��
        # ������������������ �� ����� �������������� ���� :97a::SAFE (��� ������ �52).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);

                for my $b_b (@{ $tree->{'CASHACCT'} }) {
                    for my $b_b1 (@{ $b_b->{'ACTCURR'} }) {
                        for my $b_b1a (@{ $b_b1->{'ACTINFO'} }) {
                            for my $b_b1a4 (@{ $b_b1a->{'SETPRTY'} }) {
                                if (seq_key_value_exists($b_b1a4->{fields}, '95[A-Z]', ":PSET")) {
                                    return 0 if (seq_key_value_exists($b_b1a4->{fields}, '97[A-Z]', ":SAFE"));
                                }
                            }
                        }
                    }
                }

                return 1;
            },
            err => 'E52',
        },

        # �10
        # ���� � ��������������������� �2� ������������ ���� :95a::PSET, �� � ��� ��
        # ������������������ �� ����� �������������� ���� :97a::SAFE (��� ������ �53).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);

                for my $b_c (@{ $tree->{'FREEASS'} }) {
                    for my $b_c2 (@{ $b_c->{'TRANSDET'} }) {
                        for my $b_c2a (@{ $b_c2->{'SETPRTY'} }) {
                            if (seq_key_value_exists($b_c2a->{fields}, '95[A-Z]', ":PSET")) {
                                return 0 if (seq_key_value_exists($b_c2a->{fields}, '97[A-Z]', ":SAFE"));
                            }
                        }
                    }
                }

                return 1;
            },
            err => 'E53',
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