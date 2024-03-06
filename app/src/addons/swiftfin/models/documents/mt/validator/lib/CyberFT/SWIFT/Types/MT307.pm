package CyberFT::SWIFT::Types::MT307;
use CyberFT::SWIFT::Types::Utils;
use strict;
use Storable ();
use Data::Dumper;

our $ValidationProfile = {

    fields => [
        # Описаны только обязательные поля обязательных последовательностей.
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
            key => '22a',
            key_regexp => '22[FH]',
            required => 1,
        },
        {
            key => '16S',
            required => 1,
        },
        # B
        {
            key => '98A',
            required => 1,
        },
        {
            key => '92B',
            required => 1,
        },
        {
            key => '95a',
            key_regexp => '95[PQR]',
            required => 1,
        },
        {
            key => '97A',
            required => 1,
        },
        {
            key => '19B',
            required => 1,
        },
    ],

    rules => [
        # Проверка обязательных последовательностей и полей
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                
                my $sequences = {
                    'GENL' => {
                        name => 'A',
                        required => 1,
                        required_fields => ['20C', '23G', '22[FH]'],
                    },
                    'GENL/LINK' => {
                        name => 'A1',
                        required => 0,
                        required_fields => ['20C'],
                    },
                    'FXDET' => {
                        name => 'B',
                        required => 1,
                        required_fields => ['98A', '92B'],
                    },
                    'FXDET/FXPRTY1' => {
                        name => 'B1',
                        required => 1,
                        required_fields => ['95[PQR]'],
                    },
                    'FXDET/FXPRTY2' => {
                        name => 'B2',
                        required => 1,
                        required_fields => ['97A'],
                    },
                    'FXDET/FXSETDET' => {
                        name => 'B3',
                        required => 1,
                        required_fields => ['19B'],
                    },
                    'FXDET/FXSETDET/CSHPRTY' => {
                        name => 'B3a',
                        required => 0,
                        required_fields => [],
                    },
                    'ACCOUNT' => {
                        name => 'C',
                        required => 0,
                        required_fields => ['22H', '19B'],
                    },
                    'ACCOUNT/LINK' => {
                        name => 'C1',
                        required => 0,
                        required_fields => ['20C'],
                    },
                    'NET' => {
                        name => 'D',
                        required => 0,
                        required_fields => ['22H', '19B'],
                    },
                    'NET/CSHPRTY' => {
                        name => 'D1',
                        required => 0,
                        required_fields => ['95[PQR]', '19B'],
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
        
        # C1 
        # В последовательности А использование полей «Признак открытия» (:22H::APER) и «Признак 
        # нетто-расчетов» (:22H::NEGR) зависит от значения поля «Тип контракта» (:22H::CRTR) 
        # следующим образом (Код ошибки С60)
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $fields_a = $tree->{'GENL'}->[0]->{fields} || [];
                if (seq_key_value_exists($fields_a , '22H', ':CRTR/.*/ASET')) {
                    return 0 if (seq_key_value_exists($fields_a , '22H', ':APER'));
                }
                if (seq_key_value_exists($fields_a , '22H', ':CRTR/.*/AFWD')) {
                    return 0 unless (seq_key_value_exists($fields_a , '22H', ':APER'));
                    return 0 unless (seq_key_value_exists($fields_a , '22H', ':NEGR'));
                }
                return 1;
            },
            err => 'C60',
        },
        
        # C2 
        # В последовательности А использование поля «Признак закрытия» (:22H::PAFI) зависит от 
        # значения поля «Признак открытия» (:22H::APER) следующим образом (Код ошибки С61):
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $fields_a = $tree->{'GENL'}->[0]->{fields} || [];
                if (seq_key_value_exists($fields_a , '22H', ':APER/.*/OPEF')) {
                    return 0 if (seq_key_value_exists($fields_a , '22H', ':PAFI'));
                }
                if (seq_key_value_exists($fields_a , '22H', ':APER/.*/NOPE')) {
                    return 0 unless (seq_key_value_exists($fields_a , '22H', ':PAFI'));
                }
                unless (seq_key_value_exists($fields_a , '22H', ':APER/.*/\S+')) {
                    return 0 if (seq_key_value_exists($fields_a , '22H', ':PAFI'));
                }
                return 1;
            },
            err => 'C61',
        },
        
        # C3 
        # Использование последовательности С зависит от значения поля «Признак открытия» (:22H::APER) 
        # следующим образом (Код ошибки С62):
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $fields_a = $tree->{'GENL'}->[0]->{fields} || [];
                my $exists_c = defined($tree->{'ACCOUNT'});
                if (seq_key_value_exists($fields_a , '22H', ':APER/.*/OPEF')) {
                    return 0 if ($exists_c);
                }
                if (seq_key_value_exists($fields_a , '22H', ':APER/.*/NOPE')) {
                    return 0 unless ($exists_c);
                }
                unless (seq_key_value_exists($fields_a , '22H', ':APER/.*/\S+')) {
                    return 0 if ($exists_c);
                }
                return 1;
            },
            err => 'C62',
        },
        
        # C4 
        # В последовательности А использование определителя «UNKN» в поле «Признак нетто- -расчетов» 
        # (поле :22H::NEGR//UNKN) зависит от значения полей «Тип контракта» (:22H::CRTR), 
        # «Признак открытия» (:22H::APER) и «Признак закрытия» (:22H::PAFI) следующим образом 
        # (Код ошибки С63):
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $fields_a = $tree->{'GENL'}->[0]->{fields} || [];
                my $exists_c = defined($tree->{'ACCOUNT'});
                if (seq_key_value_exists($fields_a , '22H', ':CRTR//ASET')) {
                    return 0 if (seq_key_value_exists($fields_a , '22H', ':NEGR//UNKN'));
                }
                if (
                       seq_key_value_exists($fields_a , '22H', ':CRTR//AFWD')
                    && seq_key_value_exists($fields_a , '22H', ':APER//NOPE')
                    && seq_key_value_exists($fields_a , '22H', ':PAFI//FINA')
                ) {
                    return 0 if (seq_key_value_exists($fields_a , '22H', ':NEGR//UNKN'));
                }
                return 1;
            },
            err => 'C63',
        },
        
        # C5 
        # Использование последовательности D зависит от значения поля 22H «Признак» следующим образом 
        # (Код ошибки С64):
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $fields_a = $tree->{'GENL'}->[0]->{fields} || [];
                my $exists_d = defined($tree->{'NET'});
                
                if (
                    seq_key_value_exists($fields_a , '22H', ':CRTR/.*/ASET')
                    && seq_key_value_exists($fields_a , '22H', ':NEGR/.*/GRSC')
                ) {
                    return 0 if ($exists_d);
                }
                
                if (
                    seq_key_value_exists($fields_a , '22H', ':CRTR/.*/ASET')
                    && !seq_key_value_exists($fields_a , '22H', ':NEGR')
                ) {
                    return 0 if ($exists_d);
                }
                
                if (
                    seq_key_value_exists($fields_a , '22H', ':CRTR/.*/AFWD')
                    && seq_key_value_exists($fields_a , '22H', ':APER/.*/OPEF')
                    && seq_key_value_exists($fields_a , '22H', ':NEGR/.*/(NETC|GRSC|UNKN)')
                ) {
                    return 0 if ($exists_d);
                }
                
                if (
                    seq_key_value_exists($fields_a , '22H', ':CRTR/.*/AFWD')
                    && seq_key_value_exists($fields_a , '22H', ':APER/.*/NOPE')
                    && seq_key_value_exists($fields_a , '22H', ':PAFI/.*/PAIN')
                    && seq_key_value_exists($fields_a , '22H', ':NEGR/.*/(NETC|GRSC|UNKN)')
                ) {
                    return 0 if ($exists_d);
                }
                
                if (
                    seq_key_value_exists($fields_a , '22H', ':CRTR/.*/AFWD')
                    && seq_key_value_exists($fields_a , '22H', ':APER/.*/NOPE')
                    && seq_key_value_exists($fields_a , '22H', ':PAFI/.*/FINA')
                    && seq_key_value_exists($fields_a , '22H', ':NEGR/.*/NETC')
                ) {
                    return 0 unless ($exists_d);
                }
                
                if (
                    seq_key_value_exists($fields_a , '22H', ':CRTR/.*/AFWD')
                    && seq_key_value_exists($fields_a , '22H', ':APER/.*/NOPE')
                    && seq_key_value_exists($fields_a , '22H', ':PAFI/.*/FINA')
                    && seq_key_value_exists($fields_a , '22H', ':NEGR/.*/GRSC')
                ) {
                    return 0 if ($exists_d);
                }
                
                
                return 1;
            },
            err => 'C64',
        },
        
        # C6 
        # Следующие поля определения сторон не могут присутствовать более одного раза в сообщении 
        # (Код ошибки Е83):
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $fields_b_all = _flatten_tree($tree->{'FXDET'}->[0] || {});
                for my $code (qw(BUYE SELL)) {
                    if (seq_key_value_count($fields_b_all, '19B', ":$code") > 1) {
                        return 0;
                    }
                }
                my $fields_d_all = _flatten_tree($tree->{'NET'}->[0] || {});
                for my $code (qw(CDEA INTE ACCW BENM)) {
                    if (seq_key_value_count($fields_d_all, '95[A-Z]', ":$code") > 1) {
                        return 0;
                    }
                }
                return 1;
            },
            err => 'E83',
        },
        
        # C7 
        # Повторяющаяся подпоследовательность В3 «Детали расчетов по форексной сделке» должнен 
        # присутствовать в сообщении ровно два раза (Код ошибки Е90).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $branches_b3 = $tree->{'FXDET'}->[0]->{'FXSETDET'} || [];
                if (scalar(@$branches_b3) != 2) {
                    return 0;
                }
                return 1;
            },
            err => 'E90',
        },
        
        # C8 
        # В каждом из повторений подпоследовательности В3 использование повторяющейся 
        # подпоследовательности В3а «Стороны при денежных расчетах» зависит от значения поля 
        # «Признак нетто-расчетов» (:22H::NEGR) следующим образом (Код ошибки Е91):
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $fields_a = $tree->{'GENL'}->[0]->{fields} || [];
                
                my $b3a_required;
                if (
                    seq_key_value_exists($fields_a, '22H', ':NEGR/.*/GRSC')
                    || !seq_key_value_exists($fields_a, '22H', ':NEGR')
                ) {
                    $b3a_required = 1;
                }
                
                my $b3a_not_allowed;
                if (seq_key_value_exists($fields_a, '22H', ':NEGR/.*/(NETC|UNKN)')) {
                    $b3a_not_allowed = 1;
                }
                
                my $branches_b3 = $tree->{'FXDET'}->[0]->{'FXSETDET'} || [];
                for my $b (@$branches_b3) {
                    my $b3a_exists = defined($b->{'CSHPRTY'}->[0]);
                    if ($b3a_required) {
                        return 0 unless ($b3a_exists);
                    }
                    if ($b3a_not_allowed) {
                        return 0 if ($b3a_exists);
                    }
                }
                
                return 1;
            },
            err => 'E91',
        },
        
        # С9 
        # Принимая во внимая Правило С8: в каждом из повторений подпоследовательности В3 
        # использование различных определений сторон (поле 95а) в подпоследовательности В3а зависит 
        # от значения поля 19В в подпоследовательности В3 следующим образом (Код ошибки Е92):
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $branches_b3 = $tree->{'FXDET'}->[0]->{'FXSETDET'} || [];
                
                for my $b (@$branches_b3) {
                    if (seq_key_value_exists($b->{fields}, '19B', ':BUYE')) {
                        my $b3as = $b->{'CSHPRTY'} || [];
                        my $cdea_exists = 0;
                        for my $b3a (@$b3as) {
                            if (seq_key_value_exists($b3a->{fields}, '95[A-Z]', ':CDEA')) {
                                $cdea_exists = 1;
                            }
                            if (seq_key_value_exists($b3a->{fields}, '95[A-Z]', ':BENM')) {
                                return 0;
                            }
                        }
                        return 0 unless ($cdea_exists);
                        
                    }
                    if (seq_key_value_exists($b->{fields}, '19B', ':SELL')) {
                        my $b3as = $b->{'CSHPRTY'} || [];
                        my $accw_exists = 0;
                        for my $b3a (@$b3as) {
                            if (seq_key_value_exists($b3a->{fields}, '95[A-Z]', ':ACCW')) {
                                $accw_exists = 1;
                            }
                        }
                        return 0 unless ($accw_exists);
                    }
                }
                
                return 1;
            },
            err => 'E92',
        },
        
        # C10 
        # В каждом из повторений подпоследовательности В3 следующие поля определения сторон не могут 
        # присутствовать более одного раза (Код ошибки Е84):
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $branches_b3 = $tree->{'FXDET'}->[0]->{'FXSETDET'} || [];
                
                for my $b (@$branches_b3) {
                    my $fields_b3_all = _flatten_tree($b);
                    for my $code (qw(CDEA INTE ACCW BENM)) {
                        if (seq_key_value_count($fields_b3_all, '95[A-Z]', ":$code") > 1) {
                            return 0;
                        }
                    }
                }
                
                return 1;
            },
            err => 'E84',
        },
        
    ]
};

# Строим дерево (под)последовательностей. В основе функции лежит тот факт, что каждая последовательность
# начинается с поля 16R и заканчивается полем 16S с таким же содержанием. Последовательности могут быть вложенными.
sub _get_seq_tree {
    my %params = @_;
    my $data;

    if (defined $params{doc}) {
        # проверим, есть ли закэшированный результат
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
            # начало подпоследовательности
            $inner_seq_started = 1;
            $inner_seq_name = $value;
            $inner_seq = [];
        }
        elsif ($key eq '16S' && $inner_seq_started && $inner_seq_name eq $value) {
            # конец подпоследовательности
            push @{$tree->{$inner_seq_name}}, _get_seq_tree(data => $inner_seq);
            $inner_seq_started = 0;
            $inner_seq_name = '';
            $inner_seq = [];
        }
        elsif ($inner_seq_started) {
            # поле внутри подпоследовательности
            push @$inner_seq, $item;
        }
        else {
            # поле, принадлежащее данной последовательности
            push @{$tree->{fields}}, $item;
        }
    }

    if (defined $params{doc}) {
        $params{doc}->{__get_seq_tree_result__} = $tree;
    }

    return $tree;
}

# Превращаем дерево (ветку) в плоский список полей.
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

# Рекурсивная проверка обязательных последовательностей и подпоследовательностей. 
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

# Рекурсивная проверка обязательных полей в последовательностях и подпоследовательностях. 
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

# Рекурсивная проверка обязательных последовательностей и полей. 
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