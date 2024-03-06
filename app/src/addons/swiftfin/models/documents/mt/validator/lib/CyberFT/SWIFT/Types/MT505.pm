package CyberFT::SWIFT::Types::MT505;
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
            key => '20C',
            required => 1,
        },
        {
            key => '22a',
            key_regexp => '22[FH]',
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
                    'COLD' => {
                        name => 'B',
                        required => 1,
                        required_fields => ['20C', '22[FH]'],
                    },
                    'COLD/SCOL' => {
                        name => 'B1',
                        required => 0,
                        required_fields => ['35B', '36B'],
                    },
                    'COLD/SCOL/SETDET' => {
                        name => 'B1a',
                        required => 0,
                        required_fields => ['22[FH]'],
                    },
                    'COLD/SCOL/SETDET/SETPRTY' => {
                        name => 'B1a1',
                        required => 0,
                        required_fields => ['95[CPQRS]'],
                    },
                    'COLD/CCOL' => {
                        name => 'B2',
                        required => 0,
                        required_fields => ['19B', '22H'],
                    },
                    'COLD/CCOL/CASHSET' => {
                        name => 'B2a',
                        required => 0,
                        required_fields => [],
                    },
                    'COLD/CCOL/CASHSET/CSHPRTY' => {
                        name => 'B2a1',
                        required => 0,
                        required_fields => ['95[PQRS]'],
                    },
                    'COLD/BCOL' => {
                        name => 'B3',
                        required => 0,
                        required_fields => ['22H', '98[AB]', '95[PQR]', '19B'],
                    },
                    'SETDET1' => {
                        name => 'C',
                        required => 0,
                        required_fields => ['22[FH]'],
                    },
                    'SETDET1/SETPRTY1' => {
                        name => 'C1',
                        required => 0,
                        required_fields => ['95[CPQRS]'],
                    },
                    'CASHSET1' => {
                        name => 'D',
                        required => 0,
                        required_fields => [],
                    },
                    'CASHSET1/CSHPRTY1' => {
                        name => 'D1',
                        required => 0,
                        required_fields => ['95[PQRS]'],
                    },
                    'ADDINFO' => {
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
        # � ������ ���������� ������������������ � ������������� ���������������������� �1, �2 � �3
        # ������� �� �������� ���� :22H::COLL//�������� ��������� ������� (��� ������ �83):
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $branches_b = $tree->{'COLD'} || [];

                for my $b (@$branches_b) {
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

        # �4
        # � ������ ���������� ��������������������� �2 ������������� ���� :98A::MATU ������� ��
        # �������� ���� :22H::DEPO//�������� ��������� ������� (��� ������ �85):
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);

                my $branches_b = $tree->{'COLD'} || [];
                for my $bb (@$branches_b) {
                    my $branches_b2 = $bb->{'CCOL'} || [];
                    for my $bb2 (@$branches_b2) {

                        if (seq_key_value_exists($bb2->{fields}, '22H', ':DEPO//FIXT')) {
                            return 0 unless (seq_key_value_exists($bb2->{fields}, '98A', ':MATU'));
                        }
                        elsif (seq_key_value_exists($bb2->{fields}, '22H', ':DEPO//CLNT')) {
                            return 0 if (seq_key_value_exists($bb2->{fields}, '98A', ':MATU'));
                        }

                    }
                }

                return 1;
            },
            err => 'E85',
        },

        # �5
        # � ����� ���������� ��������������������� �3, ���� � ��� ������������ ���� :22H::BCOL//LCOL,
        # ���� :98B::EXPI//OPEN (�.�. ������������ = EXPI, ��� ������� �������� ���������, �������
        # ���� ����� = OPEN) �� ������ �������������� � ��� �� ����������, � ��������� ������ ����
        # :98B::EXPI//OPEN �������������� (��� ������ �72):
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);

                my $branches_b = $tree->{'COLD'} || [];
                for my $bb (@$branches_b) {
                    my $branches_b3 = $bb->{'BCOL'} || [];
                    for my $bb3 (@$branches_b3) {

                        if (seq_key_value_exists($bb3->{fields}, '22H', ':BCOL//LCOL')) {
                            return 0 if (seq_key_value_exists($bb3->{fields}, '98B', ':EXPI//OPEN'));
                        }

                    }
                }

                return 1;
            },
            err => 'E72',
        },

        # �6
        # ������������������ � �������� ������������ � ��� �������, ����� � ����� �� ����������
        # ������������������ � ������������ ��������������������� �1, �� �����������
        # ��������������������� �1� (��� ������ �97).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);

                my $required = 0;
                my $branches_b = $tree->{'COLD'} || [];
                for my $bb (@$branches_b) {
                    my $branch_b1 = $bb->{'SCOL'}->[0] || undef;
                    if ($branch_b1) {
                        my $branch_b1a = $branch_b1->{'SETDET'}->[0] || undef;
                        unless ($branch_b1a) {
                            $required = 1;
                            last;
                        }
                    }
                }

                if ($required) {
                    my $branch_c = $tree->{'SETDET1'}->[0] || undef;
                    return 0 unless ($branch_c);
                }

                return 1;
            },
            err => 'C97',
        },

        # �7
        # ������������������ � �� ������������ ���� �� ���� ����������� ������������������ � ����
        # ����������� ��������������������� �1, ���� �� ���� ����������� ���������� ����������� �1
        # ������������ ��������������������� �1� (��� ������ D49).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);

                my $allowed = 0;
                my $branches_b = $tree->{'COLD'} || [];
                for my $bb (@$branches_b) {
                    my $branch_b1 = $bb->{'SCOL'}->[0] || undef;
                    if ($branch_b1) {
                        my $branch_b1a = $branch_b1->{'SETDET'}->[0] || undef;
                        unless ($branch_b1a) {
                            $allowed = 1;
                            last;
                        }
                    }
                }

                unless ($allowed) {
                    my $branch_c = $tree->{'SETDET1'}->[0] || undef;
                    return 0 if ($branch_c);
                }

                return 1;
            },
            err => 'D49',
        },

        # �8
        # ������������������ D �������� ������������ ���� � ����� �� ���������� ������������������ �
        # ������������ ��������������������� �2, �� ����������� ��������������������� �2�
        # (��� ������ �99).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);

                my $required = 0;
                my $branches_b = $tree->{'COLD'} || [];
                for my $bb (@$branches_b) {
                    my $branch_b2 = $bb->{'CCOL'}->[0] || undef;
                    if ($branch_b2) {
                        my $branch_b2a = $branch_b2->{'CASHSET'}->[0] || undef;
                        unless ($branch_b2a) {
                            $required = 1;
                            last;
                        }
                    }
                }

                if ($required) {
                    my $branch_d = $tree->{'CASHSET1'}->[0] || undef;
                    return 0 unless ($branch_d);
                }

                return 1;
            },
            err => 'C99',
        },

        # �9
        # ������������������ D �� �� ������������ ���� �� ���� ����������� �������������- ����� �
        # ���� ����������� ��������������������� �2, ���� ����������������-����� �2� ������������
        # �� ���� ����������� ��������������������� �2 (��� ������ D49).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);

                my $allowed = 0;
                my $branches_b = $tree->{'COLD'} || [];
                for my $bb (@$branches_b) {
                    my $branch_b2 = $bb->{'CCOL'}->[0] || undef;
                    if ($branch_b2) {
                        my $branch_b2a = $branch_b2->{'CASHSET'}->[0] || undef;
                        unless ($branch_b2a) {
                            $allowed = 1;
                            last;
                        }
                    }
                }

                unless ($allowed) {
                    my $branch_d = $tree->{'CASHSET1'}->[0] || undef;
                    return 0 if ($branch_d);
                }

                return 1;
            },
            err => 'D49',
        },

        # �10
        # � ������ �� ���������� ������������������ � � ���������������������� �1�1 � �2�1 ���������
        # ���� ����������� ������ �� ����� �������������� ����� ������ ���� (��� ������ �84):
        # � ������������������� � � D: � ���������������������� �1 � D1 ��������� ���� �����������
        # ������ �� ����� �������������� ����� ������ ���� (��� ������ �84):
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);

                my $branches_b = $tree->{'COLD'} || [];
                for my $branch_b (@$branches_b) {
                    my $branches;
                    # B1a1
                    $branches = $branch_b->{'SCOL'}->[0]->{'SETDET'}->[0]->{'SETPRTY'} || [];
                    for my $code (qw{BUYR DEAG DECU DEI1 DEI2 PSET REAG RECU REI1 REI2 SELL}) {
                        my $counter = 0;
                        for my $b (@$branches) {
                            $counter += seq_key_value_count($b->{fields}, '95[A-Z]', ":$code");
                        }
                        return 0 if ($counter > 1);
                    }
                    # B2a1
                    $branches = $branch_b->{'CCOL'}->[0]->{'CASHSET'}->[0]->{'CSHPRTY'} || [];
                    for my $code (qw{ACCW BENM PAYE DEBT INTM}) {
                        my $counter = 0;
                        for my $b (@$branches) {
                            $counter += seq_key_value_count($b->{fields}, '95[A-Z]', ":$code");
                        }
                        return 0 if ($counter > 1);
                    }
                }

                # C1
                my $branches_c1 = $tree->{'SETDET1'}->[0]->{'SETPRTY1'} || [];
                for my $code (qw{BUYR DEAG DECU DEI1 DEI2 PSET REAG RECU REI1 REI2 SELL}) {
                    my $counter = 0;
                    for my $b (@$branches_c1) {
                        $counter += seq_key_value_count($b->{fields}, '95[A-Z]', ":$code");
                    }
                    return 0 if ($counter > 1);
                }

                # D1
                my $branches_d1 = $tree->{'CASHSET1'}->[0]->{'CSHPRTY1'} || [];
                for my $code (qw{ACCW BENM PAYE DEBT INTM}) {
                    my $counter = 0;
                    for my $b (@$branches_d1) {
                        $counter += seq_key_value_count($b->{fields}, '95[A-Z]', ":$code");
                    }
                    return 0 if ($counter > 1);
                }

                return 1;
            },
            err => 'E84',
        },

        # �11
        # ���� � ����� ���������� ������������������ � � ���� :95a::4!� ������ ����������
        # ��������-������������� �1�1 ������������ ������������ �� ������ ����������� (��. ����),
        # �� � ������ ����������� ��������������������� �1�1 � ��� �� ������������������ � ������
        # ���� �����-�� ��� ��������� ������������, ��������� �� ��� � ������ ����������� (��� ������ �86).
        # ���� � ����� ���������� ������������������ � � ���� :95a::4!� ������ ����������
        # ��������-������������� �1�1 ������������ ������������ �� ������ ����������� (��. ����),
        # �� � ������ ����������� ��������������������� �1�1 � ��� �� ������������������ � ������
        # ���� ������� ��� ��������� ������������, ��������� �� ��� � ������ ����������� (��� ������ �86).
        # ... �� �� ����� ��� �1
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
                    my $branches = $branch_b->{'SCOL'}->[0]->{'SETDET'}->[0]->{'SETPRTY'} || [];

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

                # C1
                my $branches = $tree->{'SETDET1'}->[0]->{'SETPRTY1'} || [];
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

                return 1;
            },
            err => 'E86',
        },

        # �12
        # � ������ ���������� ��������������������� �1� ������������� ���������������������
        # �1�1 ������� �� ����������� ���� :22F::STCO//NSSP ��������� ������� (��� ������ �48):
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);

                my $branches_b = $tree->{'COLD'} || [];
                for my $branch_b (@$branches_b) {
                    my $branch_b1a = $branch_b->{'SCOL'}->[0]->{'SETDET'}->[0] || undef;
                    if ($branch_b1a && seq_key_value_exists($branch_b1a->{fields}, '22F', ':STCO//NSSP')) {
                        return 0 unless (defined $branch_b1a->{'SETPRTY'}); # B1a1
                    }
                }

                return 1;
            },
            err => 'E48',
        },

        # �13 � ������ ���������� ��������������������� �2� ������������� ��������������������� �2�1
        # ������� �� ����������� ���� :22F::STCO//NSSP ��������� ������� (��� ������ �49):
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);

                my $branches_b = $tree->{'COLD'} || [];
                for my $branch_b (@$branches_b) {
                    my $branch_b2a = $branch_b->{'CCOL'}->[0]->{'CASHSET'}->[0] || undef;
                    if ($branch_b2a && seq_key_value_exists($branch_b2a->{fields}, '22F', ':STCO//NSSP')) {
                        return 0 unless (defined $branch_b2a->{'CSHPRTY'}); # B2a1
                    }
                }

                return 1;
            },
            err => 'E49',
        },

        # �14
        # � ������������������ � ������������� ��������������������� �1 ������� �� �����������
        # ���� :22F::STCO//NSSP ��������� ������� (��� ������ �50):
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);

                my $branch_c = $tree->{'SETDET1'}->[0] || undef;
                if ($branch_c && seq_key_value_exists($branch_c->{fields}, '22F', ':STCO//NSSP')) {
                    return 0 unless (defined $branch_c->{'SETPRTY'}); # C1
                }

                return 1;
            },
            err => 'E50',
        },

        # �15
        # � ������������������ D ������������� ��������������������� D1 ������� �� ����������� ����
        # :22F::STCO//NSSP ��������� ������� (��� ������ �51):
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);

                my $branch_d = $tree->{'CASHSET1'}->[0] || undef;
                if ($branch_d && seq_key_value_exists($branch_d->{fields}, '22F', ':STCO//NSSP')) {
                    return 0 unless (defined $branch_d->{'CSHPRTY'}); # D1
                }

                return 1;
            },
            err => 'E51',
        },

        # �16
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