package CyberFT::SWIFT::Types::MT566;
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
        # B
        {
            key => '97A',
            required => 1,
        },
        {
            key => '35B',
            required => 1,
        },
        {
            key => '93a',
            key_regexp => '93[BC]',
            required => 1,
        },
        # D
        {
            key => '13A',
            required => 1,
        },
        {
            key => '22a',
            key_regexp => '22[HF]',
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
                        required => 0,
                        required_fields => ['20C'],
                    },
                    'USECU' => {
                        name => 'B',
                        required => 1,
                        required_fields => ['97A', '35B', '93[BC]'],
                    },
                    'USECU/FIA' => {
                        name => 'B1',
                        required => 0,
                        required_fields => [],
                    },
                    'CADETL' => {
                        name => 'C',
                        required => 0,
                        required_fields => [],
                    },
                    'CACONF' => {
                        name => 'D',
                        required => 1,
                        required_fields => ['13A', '22[FH]'],
                    },
                    'CACONF/SECMOVE' => {
                        name => 'D1',
                        required => 0,
                        required_fields => ['22[FH]', '35B', '36B', '98[ABCE]'],
                    },
                    'CACONF/SECMOVE/FIA' => {
                        name => 'D1a',
                        required => 0,
                        required_fields => [],
                    },
                    'CACONF/SECMOVE/RECDEL' => {
                        name => 'D1b',
                        required => 0,
                        required_fields => ['95[CPQRS]'],
                    },
                    'CACONF/CASHMOVE' => {
                        name => 'D2',
                        required => 0,
                        required_fields => ['22[FH]', '19A', '98[ACE]'],
                    },
                    'CACONF/CASHMOVE/CSHPRTY' => {
                        name => 'D2a',
                        required => 0,
                        required_fields => ['95[PRQS]'],
                    },
                    'CACONF/CASHMOVE/TAXVODET' => {
                        name => 'D2b',
                        required => 0,
                        required_fields => ['20C'],
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
        # ���� ������������ ���� :92�::EXCH ����� �����������, �� � ��� �� (���)������������������
        # ������ ����� �������������� ���� :19A::RESU ������ ����� �����������. ���� ����
        # ����� ����������� �����������, �� ���� ������ ����� ����������� �������������� �� �����
        # (��� ������ �62). ��� �������� ��������� � ��������������������� D2.
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $branches_d2 = $tree->{'CACONF'}->[0]->{'CASHMOVE'} || [];

                for my $b (@$branches_d2) {
                    if (seq_key_value_exists($b->{fields}, '92B', ':EXCH')) {
                        return 0 unless (seq_key_value_exists($b->{fields}, '19A', 'RESU'));
                    }
                    else {
                        return 0 if (seq_key_value_exists($b->{fields}, '19A', 'RESU'));
                    }

                }

                return 1;
            },
            err => 'E62',
        },

        # �2
        # ���� � ������������������ � ������� ��������� ���������� ��� ��������� ��������
        # (:23G::REVR), �� � ��������� ������ �������������� ���� �� ���� ���������������������
        # �1 �������, � � ����� � ������ � ����� ���������� �1 ������ �������������� ���� :20C::PREV.
        # ��������������, � ��������� ����������� �1 ���� :20C::PREV �� ����������� (��� ������ �08).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $fields_a = $tree->{'GENL'}->[0]->{fields} || [];
                my $branches_a1 = $tree->{'GENL'}->[0]->{'LINK'} || [];
                if (seq_key_value_exists($fields_a, '23G', 'REVR')) {
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

        # �3 � ������������������ D � � ������ ���������� ��������������������� D2:
        # - ���� :92F::GRSS �� ����� �������������� ����� ������ ����.
        # - ���� :92F::NETT �� ����� �������������� ����� ������ ����.
        # � ����� ���������� ��������������������� D1 � ��������������������� D2 � ���� :92A::TAXC
        # ����� �������������� ������ ���� ��� � ���� :92F::TAXC ����� �������������� ������ ����
        # ��� � ��� ���� :92A::TAXC � :92F::TAXC �� ����� �������������� ������������
        # (��� ������ E77).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $branch_d = $tree->{'CACONF'}->[0] || {};
                my $branches_d1 = $tree->{'CACONF'}->[0]->{'SECMOVE'} || [];
                my $branches_d2 = $tree->{'CACONF'}->[0]->{'CASHMOVE'} || [];

                if (seq_key_value_count($branch_d->{fields}, '92F', ':GRSS') > 1) {
                    return 0;
                }
                if (seq_key_value_count($branch_d->{fields}, '92F', ':NETT') > 1) {
                    return 0;
                }

                for my $branch_d2 (@$branches_d2) {
                    if (seq_key_value_count($branch_d2->{fields}, '92F', ':GRSS') > 1) {
                        return 0;
                    }
                    if (seq_key_value_count($branch_d2->{fields}, '92F', ':NETT') > 1) {
                        return 0;
                    }
                    my $c1 = seq_key_value_count($branch_d2->{fields}, '92A', ':TAXC');
                    my $c2 = seq_key_value_count($branch_d2->{fields}, '92F', ':TAXC');
                    if ($c1 + $c2 > 1) {
                        return 0;
                    }
                }

                for my $branch_d1 (@$branches_d1) {
                    my $c1 = seq_key_value_count($branch_d1->{fields}, '92A', ':TAXC');
                    my $c2 = seq_key_value_count($branch_d1->{fields}, '92F', ':TAXC');
                    if ($c1 + $c2 > 1) {
                        return 0;
                    }
                }

                return 1;
            },
            err => 'E77',
        },

        # �4
        # � ������������������ D � � ������ ���������� ��������������������� D2::
        # - ���� ���� :92J::GRSS ������������ ����� ������ ���� � ���������, �� ��� ������� ����������
        # ���� :92J::GRSS �������� ������� ���� ���� ������ ������ ���� ��������,
        # - ���� ���� :92J::TAXE ������������ ����� ������ ���� � ���������, �� ��� ������� ����������
        # ���� :92J::TAXE �������� ������� ���� ���� ������ ������ ���� ��������,
        # - ���� ���� :92J::NETT ������������ ����� ������ ���� � ���������, �� ��� ������� ����������
        # ���� :92J::NETT �������� ������� ���� ���� ������ ������ ���� ��������,
        # � ������ ���������� ��������������������� D1 � ��������������������� D2: ���� ����
        # :92J::TAXC ������������ ����� ������ ���� � ���������, �� ��� ������� ���������� ����
        # :92J::TAXC �������� ������� ���� ���� ������ ������ ���� �������� (��� ������ E78),
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);

                my $branch_d = $tree->{'CACONF'}->[0] || {};
                my $branches_d1 = $tree->{'CACONF'}->[0]->{'SECMOVE'} || [];
                my $branches_d2 = $tree->{'CACONF'}->[0]->{'CASHMOVE'} || [];

                # D
                for my $code (qw'GRSS TAXE NETT') {
                    my $dict = {};
                    my $vals = seq_get_all($branch_d->{fields}, '92J');
                    for my $v (@$vals) {
                        if ($v =~ /:$code\/[^\/]*?\/([^\/]{4})/) {
                            my $k = $1;
                            if ($dict->{$k} == 1) {
                                return 0;
                            }
                            else {
                                $dict->{$k} = 1;
                            }
                        }
                    }
                }

                # D2
                for my $code (qw'GRSS TAXE NETT TAXC') {
                    for my $branch_d2 (@$branches_d2) {
                        my $dict = {};
                        my $vals = seq_get_all($branch_d2->{fields}, '92J');
                        for my $v (@$vals) {
                            if ($v =~ /:$code\/[^\/]*?\/([^\/]{4})/) {
                                my $k = $1;
                                if ($dict->{$k} == 1) {
                                    return 0;
                                }
                                else {
                                    $dict->{$k} = 1;
                                }
                            }
                        }
                    }
                }

                # D1
                for my $code (qw'TAXC') {
                    for my $branch_d1 (@$branches_d1) {
                        my $dict = {};
                        my $vals = seq_get_all($branch_d1->{fields}, '92J');
                        for my $v (@$vals) {
                            if ($v =~ /:$code\/[^\/]*?\/([^\/]{4})/) {
                                my $k = $1;
                                if ($dict->{$k} == 1) {
                                    return 0;
                                }
                                else {
                                    $dict->{$k} = 1;
                                }
                            }
                        }
                    }
                }

                return 1;
            },
            err => 'E78',
        },

        # �5
        # ���� � ������������������ � ������������ ���� :70E::NAME, �� � ������������������ � ������
        # �������������� ���� :22F::CAEV//CHAN, � � ������������������ � ������ �������������� ����
        # :22F::CHAN//NAME (��� ������ D99).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $branch_a = $tree->{'GENL'}->[0] || {};
                my $branch_c = $tree->{'CADETL'}->[0] || {};

                if (seq_key_value_exists($branch_c->{fields}, '70E', ':NAME')) {
                    unless (seq_key_value_exists($branch_a->{fields}, '22F', ':CAEV//CHAN')) {
                        return 0;
                    }
                    unless (seq_key_value_exists($branch_c->{fields}, '22F', ':CHAN//NAME')) {
                        return 0;
                    }
                }
                else {
                    if (seq_key_value_exists($branch_a->{fields}, '22F', ':CAEV//CHAN')) {
                        return 0;
                    }
                    if (seq_key_value_exists($branch_c->{fields}, '22F', ':CHAN//NAME')) {
                        return 0;
                    }
                }

                return 1;
            },
            err => 'D99',
        },

        # �6
        # ���� � ������������������ � ������������ ���� :22F::CAEV//RHDI, �� ������������������ �
        # �������� ������������ � ���� :22F::RHDI ������ �������������� � ������������������ �
        # (��� ������ �06).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $branch_a = $tree->{'GENL'}->[0] || {};
                my $branch_c = $tree->{'CADETL'}->[0] || {};

                if (seq_key_value_exists($branch_a->{fields}, '22F', ':CAEV//RHDI')) {
                    unless (seq_key_value_exists($branch_c->{fields}, '22F', ':RHDI')) {
                        return 0;
                    }
                }

                return 1;
            },
            err => 'E06',
        },

        # �7
        # ���� � ������������������ D � � ������ ���������� ��������������������� D2 ������������
        # ���� :92J::TAXE, �� ������ �������������� ����� ���� :92F::GRSS � ��� �� ����� ����������
        # ��������������������� (��� ������ �80).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);

                my $branch_d = $tree->{'CACONF'}->[0] || {};
                my $branches_d2 = $tree->{'CACONF'}->[0]->{'CASHMOVE'} || [];

                if (seq_key_value_exists($branch_d->{fields}, '92J', ':TAXE')) {
                    unless (seq_key_value_exists($branch_d->{fields}, '92F', ':GRSS')) {
                        return 0;
                    }
                }

                for my $branch_d2 (@$branches_d2) {
                    if (seq_key_value_exists($branch_d2->{fields}, '92J', ':TAXE')) {
                        unless (seq_key_value_exists($branch_d2->{fields}, '92F', ':GRSS')) {
                            return 0;
                        }
                    }
                }

                return 1;
            },
            err => 'E80',
        },

        # �8
        # � ������������������ � ���� :93B::ELIG �� ����� �������������� ����� ���� ���.
        # ���� ��� ���� ������������ ������, �� � ������ ������ ��� ���� ���������� ������ ����
        # �FAMT�, � �� ������ ������ ��� ���� ���������� ������ ���� �AMOR� (��� ������ �71).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);

                my $branch_b = $tree->{'USECU'}->[0] || {};
                my $counter = 0;
                my ($first, $second);
                for my $item (@{$branch_b->{fields}}) {
                    if ($item->{key} =~ /93B/ && $item->{value} =~ /^:ELIG/) {
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

        # �9
        # � ����� ���������� ��������������������� D1 ���� :36B::PSTA �� ����� �������������� �����
        # ���� ���. ���� ��� ���� ������������ ������, �� � ������ ������ ��� ���� ���������� ������
        # ���� �FAMT�, � �� ������ ������ ��� ���� ���������� ������ ���� �AMOR� (��� ������ �72).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);

                my $branches_d1 = $tree->{'CACONF'}->[0]->{'SECMOVE'} || [];
                for my $branch_d1 (@$branches_d1) {
                    my $counter = 0;
                    my ($first, $second);
                    for my $item (@{$branch_d1->{fields}}) {
                        if ($item->{key} =~ /36B/ && $item->{value} =~ /^:PSTA/) {
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
            err => 'C72',
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