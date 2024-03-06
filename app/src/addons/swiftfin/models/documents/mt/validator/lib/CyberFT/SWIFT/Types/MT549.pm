package CyberFT::SWIFT::Types::MT549;
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
            key => '13A',
            required => 1,
        },
        {
            key => '97a',
            key_regexp => '97[AB]',
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
                        required_fields => ['20C', '23G', '13A', '97[AB]'],
                    },
                    'GENL/LINK' => {
                        name => 'A1',
                        required => 0,
                        required_fields => ['20C'],
                    },
                    'BYSTAREA' => {
                        name => 'B',
                        required => 0,
                        required_fields => [],
                    },
                    'REF' => {
                        name => 'C',
                        required => 0,
                        required_fields => ['20C'],
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
        # ���� �����/����� ������� (:98a::STAT) � ������� ������� (:69�::STAT) ��������
        # ������������������. ��� ������� ��������� � ������������������ � (��� ������ �79).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $fields_a = $tree->{'GENL'}->[0]->{fields} || [];
                if (seq_key_value_exists($fields_a, '98[A-Z]', ':STAT')) {
                    return 0 if (seq_key_value_exists($fields_a, '69[A-Z]', ':STAT'));
                }
                return 1;
            },
            err => 'E79',
        },

        # �2
        # ������������������ � ������������ ������� �� �������/������� �/��� �� �����������
        # ����������� � ������������������ � ������������ ������� �� ��������� ���������� ��������
        # ������������������ (��� ������ �80).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                if (defined($tree->{'BYSTAREA'}->[0]) && defined($tree->{'REF'}->[0])) {
                    return 0;
                }

                return 1;
            },
            err => 'E80',
        },

        # �3
        # � ������ ���������� ������������������ � ������������ � ���� 24� ������ ��������� �
        # ������� ������ (���� �������), ������� ������� � ��������������� ������������� � ����
        # 25D ��� �� ������������������ � (��� ������ �37).
        # ���� � ���� :25D:: ������������ ������� ���������, �� ��� ������������� ������� �� �����������.
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $b = $tree->{'BYSTAREA'}->[0] || {};
                my $val_25d = seq_get_first($b->{fields}, '25D');
                my $vals_24b = seq_get_all($b->{fields}, '24B');

                return 1 if ($val_25d =~ /\/.+\//);

                if (scalar @$vals_24b > 0) {
                    return 0 unless (defined $val_25d);
                    for my $val_24b (@$vals_24b) {
                        if ($val_24b =~ /:NMAT/) {
                            return 0 unless ($val_25d =~ m/:(MTCH|INMH)\/\/NMAT/);
                        }
                        elsif ($val_24b =~ /:PEND/) {
                            return 0 unless ($val_25d =~ m/:(EPRC|SETT|RPRC)\/\/PEND/);
                        }
                        elsif ($val_24b =~ /:PENF/) {
                            return 0 unless ($val_25d =~ m/:(SETT)\/\/PENF/);
                        }
                        elsif ($val_24b =~ /:REJT/) {
                            return 0 unless ($val_25d =~ m/:(IPRC|CPRC|RPRC|REST|TPRC)\/\/REJT/);
                        }
                        elsif ($val_24b =~ /:DEND/) {
                            return 0 unless ($val_25d =~ m/:(CPRC|RPRC|CALL|TPRC)\/\/DEND/);
                        }
                        elsif ($val_24b =~ /:CAND/) {
                            return 0 unless ($val_25d =~ m/:(IPRC|CPRC)\/\/CAND/);
                        }
                        elsif ($val_24b =~ /:CANP/) {
                            return 0 unless ($val_25d =~ m/:(IPRC|CPRC)\/\/CANP/);
                        }
                        elsif ($val_24b =~ /:CGEN/) {
                            return 0 unless ($val_25d =~ m/:(IPRC)\/\/CGEN/);
                        }
                        elsif ($val_24b =~ /:NAFI/) {
                            return 0 unless ($val_25d =~ m/:(AFFM)\/\/NAFI/);
                        }
                        elsif ($val_24b =~ /:PACK/) {
                            return 0 unless ($val_25d =~ m/:(IPRC|CPRC|RPRC|RERC|TPRC)\/\/PACK/);
                        }
                        elsif ($val_24b =~ /:CACK/) {
                            return 0 unless ($val_25d =~ m/:(CALL)\/\/CACK/);
                        }
                        elsif ($val_24b =~ /:REPR/) {
                            return 0 unless ($val_25d =~ m/:(CPRC|IPRC|RPRC)\/\/REPR/);
                        }
                        elsif ($val_24b =~ /:PPRC/) {
                            return 0 unless ($val_25d =~ m/:(IPRC)\/\/PPRC/);
                        }
                        elsif ($val_24b =~ /:MOPN/) {
                            return 0 unless ($val_25d =~ m/:(TPRC)\/\/MOPN/);
                        }
                    }
                }
                else {
                    return 0 if (defined $val_25d);
                }

                return 1;
            },
            err => 'E37',
        },

        # �4
        # ���� ��������� ������������ ��� ������, �.�. ���� ���� 23 G �������� ���������� ��������
        # ��� CANC, � ��������� ������ �������������� ���� �� ���� ��������������������� �2 �������,
        # � � ����� � ������ � ����� ���������� �2 ������ �������������� ���� :20C::PREV.
        # ��������������, � ��������� ����������� �2 ���� :20C::PREV �� �����������. (��� ������ �08).
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