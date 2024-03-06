package CyberFT::SWIFT::Types::MT558;
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
            key => '98a',
            key_regexp => '98[ACE]',
            required => 1,
        },
        {
            key => '22a',
            key_regexp => '22[FH]',
            required => 1,
        },
        {
            key => '95a',
            key_regexp => '95[PQR]',
            required => 1,
        },
        # B
        {
            key => '98a',
            key_regexp => '98[ABC]',
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
                        required_fields => ['28E', '20C', '23G', '98[ACE]', '22[FH]'],
                    },
                    'GENL/COLLPRTY' => {
                        name => 'A1',
                        required => 1,
                        required_fields => ['95[PQR]'],
                    },
                    'GENL/STAT' => {
                        name => 'A2',
                        required => 0,
                        required_fields => ['25D'],
                    },
                    'GENL/STAT/REAS' => {
                        name => 'A2a',
                        required => 0,
                        required_fields => [],
                    },
                    'GENL/LINK' => {
                        name => 'A3',
                        required => 0,
                        required_fields => ['20C'],
                    },
                    'DEALTRAN' => {
                        name => 'B',
                        required => 1,
                        required_fields => ['98[ABC]'],
                    },
                    'SECMOVE' => {
                        name => 'C',
                        required => 0,
                        required_fields => ['22H', '35B', '36B', '17B'],
                    },
                    'CASHMOVE' => {
                        name => 'D',
                        required => 0,
                        required_fields => ['22H', '19A', '17B'],
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
        # ���� � ������������������ � ����������� ���� :20C::CLTR, �� ����������� ������ ��������������
        # ���� :20C::TCTR, � ��������� ������ ���� :20C::TCTR �������������� (��� ������ �68).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $fields_a = $tree->{'GENL'}->[0]->{fields} || [];

                unless (seq_key_value_exists($fields_a, '20C', ':CLTR')) {
                    return 0 unless (seq_key_value_exists($fields_a, '20C', ':TCTR'));
                }

                return 1;
            },
            err => 'E68',
        },

        # �2
        # � ������������������ � ������������� ����� :25D:: � :24�:: ������� �� �������� ������� 1
        # � ���� :23G:: ��������� ��������� ������� (��� ������ �08):
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $fields_a = $tree->{'GENL'}->[0]->{fields} || [];
                my $exists_a2 = defined($tree->{'GENL'}->[0]->{'STAT'}) ? 1 : 0;

                if (seq_key_value_exists($fields_a, '23G', 'ADVD')) {
                    return 0 if ($exists_a2);
                } else {
                    return 0 unless ($exists_a2);
                }

                return 1;
            },
            err => 'E08',
        },

        # �3
        # ���� � ������������������ � �������� ���� :22F::COLA// ������� �� �SLEB�, �� �
        # ������������������ � ������ �������������� ���� :19A::TRAA (��� ������ �65).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $fields_a = $tree->{'GENL'}->[0]->{fields} || [];
                my $fields_b = $tree->{'DEALTRAN'}->[0]->{fields} || [];

                if (
                    seq_key_value_exists($fields_a, '22F', ':COLA')
                    && !seq_key_value_exists($fields_a, '22F', ':COLA//SLEB')
                ) {
                    return 0 unless (seq_key_value_exists($fields_b, '19A', ':TRAA'));
                }

                return 1;
            },
            err => 'E65',
        },

        # �4
        # � ������ ���������� ������������������ A ��� ������������ � ���� 98a ��������
        # ���������������, �� ���� �� ���� (�����) ������ �������������� (��� ������ D92).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $fields_a = $tree->{'GENL'}->[0]->{fields} || [];
                return 0 unless (seq_key_value_exists($fields_a, '98[ACE]', 'PREP|EXRQ|TRAD|SETT'));
                return 1;
            },
            err => 'D92',
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