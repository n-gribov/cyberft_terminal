package CyberFT::SWIFT::Types::MT535;
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
            key => '98a',
            key_regexp => '98[ACE]',
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
                        required_fields => ['28E', '20C', '23G', '98[ACE]', '22F', '97[AB]', '17B'],
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
                        required_fields => ['35B', '93B'],
                    },
                    'SUBSAFE/FIN/FIA' => {
                        name => 'B1a',
                        required => 0,
                        required_fields => [],
                    },
                    'SUBSAFE/FIN/SUBBAL' => {
                        name => 'B1b',
                        required => 0,
                        required_fields => ['93[BC]'],
                    },
                    'SUBSAFE/FIN/SUBBAL/BREAK' => {
                        name => 'B1b1',
                        required => 0,
                        required_fields => [],
                    },
                    'SUBSAFE/FIN/BREAK' => {
                        name => 'B1c',
                        required => 0,
                        required_fields => [],
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

        # C1 ���� ����� �������� (���� :17B::ACTI) � ������������������ � ������ ����������� �����
        # �������� �N�, �� ������������������ � �� ������ ��������������. � ��������� ������
        # ������������������ � ������������ (��� ������ �66).
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                my $seq_a = $seqs->{'A'}->[0] || [];
                my $seq_b = $seqs->{'B'}->[0] || undef;
                if (seq_key_value_exists($seq_a, '17B', '^:ACTI//N')) {
                    return 0 if $seq_b;
                }
                elsif (seq_key_value_exists($seq_a, '17B', '^:ACTI//Y')) {
                    return 0 unless $seq_b;
                }
                return 1;
            },
            err => 'E66',
        },

        # �2 ���� ������� ��������� � �������� ����������� �����, �.�. ���� � ��������� ������������
        # ���� :22F::STTY//ACCT, ����������� ������ �������������� ���� �� ���� ��������������������� �1.
        # ���������� ����� ������� ����������� ������ � ��� �������, ����� � ��������� ������������
        # ������������������ � �������� ���� (�������� ������� �1), �.�. ����� ���� :17B::ACTI �����
        # �������� �Y� (��� ������ �67).
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                my $seq_a = $seqs->{'A'}->[0] || [];
                my $seq_b1 = $seqs->{'B1'}->[0] || undef;
                if (
                    seq_key_value_exists($doc->data, '22F', '^:STTY//ACCT')
                    && seq_key_value_exists($seq_a, '17B', '^:ACTI//Y')
                ) {
                    return 0 unless $seq_b1;
                }
                return 1;
            },
            err => 'E67',
        },

        # C3 � ������ �� ���������� ��������������������� �1, ���� � ��� ��� �� �����
        # ��������������������� B1b, ������ ���� ������� ��� ����� (���� :90a:), ��� � ����������
        # ���������� �� ����� ���������� ������������ (���� :19A::HOLD).
        # � ������ �� ���������� ��������������������� �1, ���� � ��� ������������ ���� �� ����
        # ��������������������� B1b, � ������ ���������� B1b ������ ���� ������� ��� �����
        # (���� :90a:), ��� � ���������� ���������� �� ����� ���������� ������������ (���� :19A::HOLD).
        # ��� ������� ��������� ������ � �������� ����������� ����� (�������� ������� �2), �.�.
        # ����� � ��������� ������������ ���� :22F::STTY//ACCT (��� ������ �82).
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                my $seqs_b1 = $seqs->{'B1'} || [];
                return 1 unless (seq_key_value_exists($doc->data, '22F', '^:STTY//ACCT'));
                for my $seq (@$seqs_b1) {
                    if (seq_key_value_exists($seq, '16R', 'SUBBAL')) {
                        my ($exists_90, $exists_19);
                        for my $item (@$seq) {
                            if ($item->{key} =~ /16R/ && $item->{value} =~ /SUBBAL/) {
                                $exists_90 = 0;
                                $exists_19 = 0;
                            }
                            elsif ($item->{key} =~ /16S/ && $item->{value} =~ /SUBBAL/) {
                                return 0 unless ($exists_90 && $exists_19);
                            }
                            elsif ($item->{key} =~ /90[A-Z]/) {
                                $exists_90 = 1;
                            }
                            elsif ($item->{key} =~ /19A/ && $item->{value} =~ /:HOLD/) {
                                $exists_19 = 1;
                            }
                        }
                    }
                    else {
                        return 0 unless seq_key_exists($seq, '90[A-Z]');
                        return 0 unless seq_key_value_exists($seq, '19A', '^:HOLD');
                    }
                }
                return 1;
            },
            err => 'E82',
        },

        # �4 ���� ��������� ������������ ��� ������ ����� ������������ �������, �.�. ���� ���� 23G
        # �������� ���������� �������� ��� CANC, � ��������� ������ �������������� ���� �� ����
        # ��������������������� �1 �������, � � ����� ������ ������ ���� ������ �������� �����������
        # ��������� � �.�., ���� �� ���� ��� � ��������� ������ �������������� ���� :20C::PREV.
        # (��� ������ �08).
        # ������������������ � ���� ���� :23G:... �� �������������. �1 ... � ���� :20C::PREV
        # CANC    ������������, �.�. ���� �� ���� ��������������������� �1    ������������ � ����� ���������� �1 � �� ����������� � ��������� ����������� �1
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                my $seq_a = $seqs->{'A'}->[0] || [];
                my $seqs_a1 = $seqs->{'A1'} || [];
                if (seq_key_value_exists($seq_a, '23G', 'CANC')) {
                    return 0 if (scalar(@$seqs_a1) < 1);
                    my $counter = 0;
                    for my $s (@$seqs_a1) {
                        $counter++ if (seq_key_value_exists($s, '20C', ':PREV'));
                    }
                    return 0 if ($counter != 1);
                }
                return 1;
            },
            err => 'E08',
        },

        # �5 ���� � ������������������ � ���� :17B::CONS ����� �������� �Y�, �� � ������ �� ����������
        # ������������������ � ���� :97a::SAFE �������� ������������ (��� ������ �56).
        # ���������� ����� ������� ����������� ������ � ��� �������, ����� � ��������� ������������
        # ������������������ � �������� ���� (�������� ������� �1), �.�. ����� ���� :17B::ACTI �
        # ������������������ � ����� �������� �Y�.
        # (��. ������� � ������������)
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                my $seq_a = $seqs->{'A'}->[0] || [];
                my $seqs_b = $seqs->{'B'} || [];
                if (seq_key_value_exists($seq_a, '17B', ':ACTI//Y')) {
                    if (seq_key_value_exists($seq_a, '17B', ':CONS//Y')) {
                        for my $s (@$seqs_b) {
                            return 0 unless (seq_key_value_exists($s, '97[A-Z]', ':SAFE'));
                            return 0 unless (seq_key_value_exists($s, '17B', ':ACTI'));
                        }
                    }
                    elsif (seq_key_value_exists($seq_a, '17B', ':CONS//N')) {
                        for my $s (@$seqs_b) {
                            return 0 if (seq_key_value_exists($s, '97[A-Z]', ':SAFE'));
                            return 0 if (seq_key_value_exists($s, '17B', ':ACTI'));
                        }
                    }
                }
                return 1;
            },
            err => 'E56',
        },

        # �6 ���� ����� �������� (���� :17B::ACTI) � ������������������ � �������� ���� ���������
        # �� ���������� ���������� ��� ����������, �.�. ����� �������� �N�, �� ����������������-�����
        # �1 ����������� ���������� �� ������ ��������������. � ��������� ������� ���������������������
        # �1 ����������� ���������� ������������ (��� ������ �69).
        # ���������� ����� ������� ����������� ������ � ��� �������, ����� � ��������� ������������
        # ������������������ � �������� ���� (�������� ������� �1), �.�., ����� ���� :17B::ACTI �����
        # �������� �Y�.
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                my $seq_a = $seqs->{'A'}->[0] || [];
                my $seqs_b = $seqs->{'B'} || [];
                if (seq_key_value_exists($seq_a, '17B', ':ACTI//Y')) {
                    for my $s (@$seqs_b) {
                        if (seq_key_value_exists($s, '17B', ':ACTI//Y')) {
                            return 0 unless (seq_key_value_exists($s, '16R', 'FIN'));
                        }
                        elsif (seq_key_value_exists($s, '17B', ':ACTI//N')) {
                            return 0 if (seq_key_value_exists($s, '16R', 'FIN'));
                        }
                        elsif (!seq_key_value_exists($s, '17B', ':ACTI')) {
                            return 0 unless (seq_key_value_exists($s, '16R', 'FIN'));
                        }
                    }
                }
                return 1;
            },
            err => 'E69',
        },

        # �7 ���� ���� :94a:: ������������ � ������������������ �, �� ���� :93B::AGGR � :94a::SAFE
        # �� ������ �������������� �� � ����� �� ���������� ��������������������� �1b (��� ������ D03).
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                my $seqs_b = $seqs->{'B'} || [];
                for my $s (@$seqs_b) {

                    # ��������, ���� �� ���� 94a � ������������������ B (�� ��������������������� �� �������).
                    my $exists_94a = 0;
                    my $in_subseq = '';
                    for my $item (@$s) {
                        if (!$in_subseq && $item->{key} =~ /94[A-Z]/) {
                            $exists_94a = 1;
                            last;
                        }
                        elsif (!$in_subseq && $item->{key} =~ /16R/ && $item->{value} !~ /SUBSAFE/) {
                            ($in_subseq) = $item->{value} =~ /^\s*(.*?)\s*$/s;
                        }
                        elsif ($item->{key} =~ /16S/ && $item->{value} =~ /^\s*$in_subseq\s*$/) {
                            $in_subseq = '';
                        }
                    }

                    if ($exists_94a) {
                        my $seqs_b1b = _findsq($s, {'SUBBAL'=>'B1b'})->{'B1b'} || [];
                        for my $sb1b (@$seqs_b1b) {
                            return 0 if (seq_key_value_exists($sb1b, '93B', ':AGGR'));
                            return 0 if (seq_key_value_exists($sb1b, '94[A-Z]', ':SAFE'));
                        }
                    }
                }
                return 1;
            },
            err => 'D03',
        },

        # �8 ���� ���� :93B::AGGR ������������ � ��������������������� �1b, �� ���� :94a::SAFE �����
        # ������ �������������� � ��� �� ���������� ��������������������� �1b (��� ������ D04).
        {
            func => sub {
                my $doc = shift;
                my $seqs_b1b = _findsq($doc->data, {'SUBBAL'=>'B1b'})->{'B1b'} || [];
                for my $sb1b (@$seqs_b1b) {
                    if (seq_key_value_exists($sb1b, '93B', ':AGGR')) {
                        return 0 unless (seq_key_value_exists($sb1b, '94[A-Z]', ':SAFE'));
                    }
                }
                return 1;
            },
            err => 'D04',
        },

        # �9 ���� � ����� �� ���������� ��������������������� �1b ������������ ���� :93B::AVAI �/���
        # ���� :93B::NAVL, �� � ��� �� ���������� ��������������������� �1b ����� ������ ��������������
        # ���� :93B::AGGR (��� ������ D05).
        {
            func => sub {
                my $doc = shift;
                my $seqs_b1b = _findsq($doc->data, {'SUBBAL'=>'B1b'})->{'B1b'} || [];
                for my $sb1b (@$seqs_b1b) {
                    if (
                        seq_key_value_exists($sb1b, '93B', ':AVAI')
                        || seq_key_value_exists($sb1b, '93B', ':NAVL')
                    ) {
                        return 0 unless (seq_key_value_exists($sb1b, '93B', ':AGGR'));
                    }
                }
                return 1;
            },
            err => 'D05',
        },

        # �10 � ����� ���������� ��������������������� �1 ���� :93B::AGGR �� ����� ��������������
        # ����� ���� ���. ���� ��� ���� ������������ ������, �� � ������ ������ ��� ���� ����������
        # ������ ���� �FAMT�, � �� ������ ������ ��� ���� ���������� ������ ���� �AMOR� (��� ������ �71).
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                my $seqs_b1 = $seqs->{'B1'} || [];
                for my $s (@$seqs_b1) {
                    my $counter = 0;
                    my ($first, $second);
                    for my $item (@$s) {
                        if ($item->{key} =~ /93B/ && $item->{value} =~ /^:AGGR/) {
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

# ����������� ��� ������������������ ��� ��� ��������.
sub _find_sequences {
    my $data = shift;

    my $level1 = _findsq(
        $data,
        {
            'GENL'     => 'A',
            'SUBSAFE'  => 'B',
            'ADDINFO'  => 'C',
        }
    );

    my $level2 = _findsq(
        $data,
        {
            'LINK' => 'A1',
            'FIN'  => 'B1',
        },
    );

    return {%$level1, %$level2};
}

sub _findsq {
    my $data = shift;
    my $marks = shift;

    my $cur_seq = undef;
    my $cur_mark = undef;
    my $seqs = {};

    for my $item (@$data) {
        my $key = $item->{key};
        my ($value) = ($item->{value} =~ /^\s*(.*?)\s*$/s);
        if ($key eq '16R' && defined($marks->{$value})) {
            $cur_mark = $value;
            $cur_seq = $marks->{$cur_mark};
            push @{$seqs->{$cur_seq}}, [];
        }
        if ($key eq '16S' && $value eq $cur_mark) {
            $cur_mark = undef;
            $cur_seq = undef;
        }
        if ($cur_seq) {
            my @cur_seqs = @{$seqs->{$cur_seq}};
            my $last_cur_seq = @cur_seqs[scalar(@cur_seqs)-1];
            push @$last_cur_seq, $item;
        }
    }

    return $seqs;
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