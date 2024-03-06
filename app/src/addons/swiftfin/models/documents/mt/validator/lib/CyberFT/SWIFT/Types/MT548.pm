package CyberFT::SWIFT::Types::MT548;
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
                        required_fields => ['20C', '23G'],
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
                    'SETTRAN' => {
                        name => 'B',
                        required => 0,
                        required_fields => ['35B', '36B', '97[AB]', '22[FH]', '98[ABCE]'],
                    },
                    'SETTRAN/SETPRTY' => {
                        name => 'B1',
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

        # �1
        # ���� ��������� ��������� � ����������� ������ ������� (:22H::PAYM//APMT), �� � ���������
        # ����������� ������ ���� ������� ����� �������� (���� :19A::SETT). ��� ������� ��������� �
        # ������������������ � (��� ������ �83).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $fields_b = $tree->{'SETTRAN'}->[0]->{fields} || [];
                if (seq_key_value_exists($fields_b, '22H', ':PAYM//APMT')) {
                    return 0 unless (seq_key_value_exists($fields_b, '19A', ':SETT'));
                }
                return 1;
            },
            err => 'E83',
        },

        # �2
        # ��������� ���� ����������� ������ �� ����� �������������� � ��������� ����� ������ ����
        # (��� ������ �84): ��������������������� �1: :95a::BUYR,DEAG,DECU,DEI1,DEI2,PSET,REAG,RECU,
        # REI1,REI2,SELL.
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $fields = _flatten_tree($tree->{'SETTRAN'}->[0]);

                for my $code (qw(BUYR DEAG DECU DEI1 DEI2 PSET REAG RECU REI1 REI2 SELL)) {
                    return 0 if (seq_key_value_count($fields, '95[A-Z]', ":$code") > 1);
                }

                return 1;
            },
            err => 'E84',
        },

        # �3
        # ���� ���������� �������� ������������ �� �������� (���� :22�::REDE//DELI �
        # ������������������ �) � ��� ���� ������������ ��������������������� �1 �������� ���
        # ���������, �� ����������� ������ ���� ������ �����-����������: � ����� �� ����������
        # ��������������������� �1 �������� ��� ��������� ������ �������������� ���� :95a::REAG
        # (��� ������ �85).
        # ���� ���������� �������� ������������ �� ��������� (���� :22�::REDE//RECE �
        # ������������������ �) � ��� ���� ������������ ��������������������� �1 �������� ���
        # ���������, �� ����������� ������ ���� ������ �����-���������: � ����� �� ����������
        # ��������������������� �1 �������� ��� ��������� ������ �������������� ���� :95a::DEAG
        # (��� ������ �85).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $branch_b = $tree->{'SETTRAN'}->[0] || {};

                if (seq_key_value_exists($branch_b->{fields}, '22H', ':REDE//DELI')) {
                    my $branches_b1 = $branch_b->{'SETPRTY'} || undef;
                    if (defined $branches_b1) {
                        my $flag = 0;
                        for my $b (@$branches_b1) {
                            if (seq_key_value_exists($b->{fields}, '95[A-Z]', ':REAG')) {
                                $flag = 1;
                                last;
                            }
                        }
                        return 0 unless ($flag);
                    }
                }

                if (seq_key_value_exists($branch_b->{fields}, '22H', ':REDE//RECE')) {
                    my $branches_b1 = $branch_b->{'SETPRTY'} || undef;
                    if (defined $branches_b1) {
                        my $flag = 0;
                        for my $b (@$branches_b1) {
                            if (seq_key_value_exists($b->{fields}, '95[A-Z]', ':DEAG')) {
                                $flag = 1;
                                last;
                            }
                        }
                        return 0 unless ($flag);
                    }
                }

                return 1;
            },
            err => 'E85',
        },

        # �4
        # ���� � ���� :95a::4!� � ��������������������� �1 ������������ ������������ �� ������
        # ����������� (��. ����), �� ����� ���� ������ ���� ������� ��� ��������� ������������,
        # ��������� �� ��� � ������ ����������� (��� ������ �86).
        # ���� � ���� :95a::4!� � ��������������������� �1 ������������ ������������ �� ������
        # ����������� (��. ����), �� ����� ���� ������ ���� ������� ��� ��������� ������������,
        # ��������� �� ��� � ������ �����������.
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

                my $fields_b = _flatten_tree($tree->{'SETTRAN'}->[0]) || [];
                for my $x1 (keys %$rules) {
                    my $x2 = $rules->{$x1};
                    return 0 if (
                        seq_key_value_exists($fields_b, '95[A-Z]', ":$x1")
                        && !seq_key_value_exists($fields_b, '95[A-Z]', ":$x2")
                    );
                }

                return 1;
            },
            err => 'E86',
        },

        # �5
        # ���� � ��������������������� �1 ������������ ���� :95a::PSET, �� � ��� �� ������������������
        # �� ����� �������������� ���� :97a::SAFE (��� ������ �52).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $branches_b1 = $tree->{'SETTRAN'}->[0]->{'SETPRTY'} || [];

                for my $b (@$branches_b1) {
                    if (seq_key_value_exists($b->{fields}, '95[A-Z]', ':PSET')) {
                        return 0 if (seq_key_value_exists($b->{fields}, '97[A-Z]', ':SAFE'));
                    }
                }

                return 1;
            },
            err => 'E52',
        },

        # �6
        # � ������ ���������� ��������������������� �2� ������������ � ���� 24� ������ ��������� �
        # ������� ������ (���� �������), ������� ������� � ��������������� ������������� � ���� 25D
        # ��� �� ��������������������� �2 (��� ������ �37).
        # ���� � ���� :25D:: ������������ ������� ���������, �� ��� ������������� ������� �� �����������.
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);

                my $bs_a2 = $tree->{'GENL'}->[0]->{'STAT'} || [];
                for my $b_a2 (@$bs_a2) {
                    my $value_25d = seq_get_first($b_a2->{fields}, '25D');
                    my ($encoding_25D) = $value_25d =~ /:\w+\/(.*?)\//;
                    next if ($encoding_25D ne '');
                    my $bs_a2a = $b_a2->{'REAS'} || [];
                    for my $b_a2a (@$bs_a2a) {
                        if (seq_key_value_exists($b_a2a->{fields}, '24B', ':CAND')) {
                            return 0 unless ($value_25d =~ m/:(CPRC|IPRC)\/\/CAND/);
                        }
                        if (seq_key_value_exists($b_a2a->{fields}, '24B', ':CANP')) {
                            return 0 unless ($value_25d =~ m/:(CPRC|IPRC)\/\/CANP/);
                        }
                        if (seq_key_value_exists($b_a2a->{fields}, '24B', ':CGEN')) {
                            return 0 unless ($value_25d =~ m/:(IPRC)\/\/CGEN/);
                        }
                        if (seq_key_value_exists($b_a2a->{fields}, '24B', ':DEND')) {
                            return 0 unless ($value_25d =~ m/:(CPRC|CALL|TPRC)\/\/DEND/);
                        }
                        if (seq_key_value_exists($b_a2a->{fields}, '24B', ':NMAT')) {
                            return 0 unless ($value_25d =~ m/:(MTCH|INMH)\/\/NMAT/);
                        }
                        if (seq_key_value_exists($b_a2a->{fields}, '24B', ':PACK')) {
                            return 0 unless ($value_25d =~ m/:(CPRC|IPRC|TPRC)\/\/PACK/);
                        }
                        if (seq_key_value_exists($b_a2a->{fields}, '24B', ':PEND')) {
                            return 0 unless ($value_25d =~ m/:(SETT)\/\/PEND/);
                        }
                        if (seq_key_value_exists($b_a2a->{fields}, '24B', ':PENF')) {
                            return 0 unless ($value_25d =~ m/:(SETT)\/\/PENF/);
                        }
                        if (seq_key_value_exists($b_a2a->{fields}, '24B', ':REPR')) {
                            return 0 unless ($value_25d =~ m/:(IPRC)\/\/REPR/);
                        }
                        if (seq_key_value_exists($b_a2a->{fields}, '24B', ':REJT')) {
                            return 0 unless ($value_25d =~ m/:(CPRC|IPRC|SPRC|TPRC)\/\/REJT/);
                        }
                        if (seq_key_value_exists($b_a2a->{fields}, '24B', ':CACK')) {
                            return 0 unless ($value_25d =~ m/:(CALL)\/\/CACK/);
                        }
                        if (seq_key_value_exists($b_a2a->{fields}, '24B', ':PPRC')) {
                            return 0 unless ($value_25d =~ m/:(IPRC)\/\/PPRC/);
                        }
                        if (seq_key_value_exists($b_a2a->{fields}, '24B', ':MOPN')) {
                            return 0 unless ($value_25d =~ m/:(TPRC)\/\/MOPN/);
                        }
                    }
                }

                return 1;
            },
            err => 'E37',
        },

        # �7
        # � ��������� ������ ���� ������ �������� ����� ����������� ���������, �.�. � ����� � ������
        # � ����� ���������� ������������������ ������ �1 ������ �������������� ���� :20C::RELA;
        # � ��������� ����������� �1 ���� :20C::RELA �� ����� �������������� (��� ������ �73).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);

                my $count = 0;
                my $bs_a1 = $tree->{'GENL'}->[0]->{'LINK'} || [];
                for my $b_a1 (@$bs_a1) {
                    if (seq_key_value_exists($b_a1->{fields}, '20C', ':RELA')) {
                        $count++;
                    }
                }
                return 0 if ($count != 1);

                return 1;
            },
            err => 'C73',
        },

        # �8
        # � ����� ���������� ������������������ � ���� :36B::SETT �� ����� �������������� ����� ����
        # ���. ���� ��� ���� ������������ ������, �� � ������ ������ ��� ���� ���������� ������ ����
        # �FAMT�, � �� ������ ������ ��� ���� ���������� ������ ���� �AMOR� (��� ������ �71).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $branches_b = $tree->{'SETTRAN'} || [];
                for my $b (@$branches_b) {
                    my $counter = 0;
                    my ($first, $second);
                    for my $item (@{$b->{fields}}) {
                        if ($item->{key} =~ /36B/ && $item->{value} =~ /^:SETT/) {
                            $counter++;
                            $first = $item->{value} if ($counter==1);
                            $second = $item->{value} if ($counter==2);
                            return 0 if ($counter==2 && ($first !~ /\/FAMT/ || $second !~ /\/AMOR/));
                            return 0 if ($counter>2);
                        }
                    }
                }
                return 1;
            },
            err => 'C71',
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