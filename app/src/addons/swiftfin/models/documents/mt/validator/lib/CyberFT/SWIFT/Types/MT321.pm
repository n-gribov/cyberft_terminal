package CyberFT::SWIFT::Types::MT321;
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
            key => '22H',
            required => 1,
        },
        {
            key => '16S',
            required => 1,
        },
        # B
        {
            key => '20C',
            required => 1,
        },
        {
            key => '22H',
            required => 1,
        },
        {
            key => '98A',
            required => 1,
        },
        {
            key => '19A',
            required => 1,
        },
        {
            key => '92A',
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
        # C
        {
            key => '22H',
            required => 1,
        },
        {
            key => '95a',
            key_regexp => '95[PQR]',
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
                        required_fields => ['20C', '23G', '22H'],
                    },
                    'GENL/LINK' => {
                        name => 'A1',
                        required => 0,
                        required_fields => ['20C'],
                    },
                    'LDDET' => {
                        name => 'B',
                        required => 1,
                        required_fields => ['20C', '22H', '98A', '19A', '92A'],
                    },
                    'LDDET/LDPRTY1' => {
                        name => 'B1',
                        required => 1,
                        required_fields => ['95[PQR]'],
                    },
                    'LDDET/LDPRTY2' => {
                        name => 'B2',
                        required => 1,
                        required_fields => ['97A'],
                    },
                    'LDDET/OTHRPRTY' => {
                        name => 'B3',
                        required => 0,
                        required_fields => ['95[PQR]'],
                    },
                    'SETDET' => {
                        name => 'C',
                        required => 1,
                        required_fields => ['22H'],
                    },
                    'SETDET/SETPRTY' => {
                        name => 'C1',
                        required => 1,
                        required_fields => ['95[PQR]'],
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
        
        # С1 
        # В последовательности В присутствие полей 19А и :98A::INTR («Следующая дата выплаты процентов») 
        # зависит от определения «Типа события, относящегося к кредиту/депозиту» в поле :22H::TLDE в 
        # последовательности А следующим образом (Код ошибки C59):
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $fields_a = $tree->{'GENL'}->[0]->{fields} || [];
                my $fields_b = $tree->{'LDDET'}->[0]->{fields} || [];
                
                if (seq_key_value_exists($fields_a, '22H', ':TLDE//CONF')) {
                    return 0 unless (seq_key_value_exists($fields_b, '98A', ':INTR'));
                    return 0 unless (seq_key_value_exists($fields_b, '19A', ':NINT'));
                    return 0 if (seq_key_value_exists($fields_b, '19A', ':SETT'));
                    return 0 if (seq_key_value_exists($fields_b, '19A', ':RODI'));
                    return 0 if (seq_key_value_exists($fields_b, '19A', ':CINT'));
                }
                elsif (seq_key_value_exists($fields_a, '22H', ':TLDE//MATU')) {
                    return 0 unless (seq_key_value_exists($fields_b, '98A', ':INTR'));
                    return 0 unless (seq_key_value_exists($fields_b, '19A', ':SETT'));
                    return 0 unless (seq_key_value_exists($fields_b, '19A', ':NINT'));
                }
                elsif (seq_key_value_exists($fields_a, '22H', ':TLDE//ROLL')) {
                    return 0 unless (seq_key_value_exists($fields_b, '19A', ':SETT'));
                    return 0 if (seq_key_value_exists($fields_b, '98A', ':INTR'));
                    return 0 if (seq_key_value_exists($fields_b, '19A', ':RODI'));
                    return 0 if (seq_key_value_exists($fields_b, '19A', ':NINT'));
                }
                
                return 1;
            },
            err => 'C59',
        },
        
        # С2 
        # В последовательности В использование поля «Последний день первого процентного периода» 
        # (:98А::LDFP) зависит от значения поля «Тип события, относящегося к кредиту/депозиту» 
        # (:22H::TLDE) следующим образом (Код ошибки С61):
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $fields_a = $tree->{'GENL'}->[0]->{fields} || [];
                my $fields_b = $tree->{'LDDET'}->[0]->{fields} || [];
                
                if (seq_key_value_exists($fields_a, '22H', ':TLDE//MATU')) {
                    return 0 if (seq_key_value_exists($fields_b, '98A', ':LDFP'));
                }
                
                return 1;
            },
            err => 'C61',
        },
        
        # С3 
        # В последовательности В использование поля «Количество дней, за которые начисляются проценты» 
        # (:99В::DAAC) зависит от присутствия поля «Последний день первого процентного периода» 
        # (:98А::LDFP) следующим образом (Код ошибки С62):
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $fields_b = $tree->{'LDDET'}->[0]->{fields} || [];
                
                if (seq_key_value_exists($fields_b, '98A', ':LDFP')) {
                    return 0 unless (seq_key_value_exists($fields_b, '99B', ':DAAC'));
                } else {
                    return 0 if (seq_key_value_exists($fields_b, '99B', ':DAAC'));
                }
                
                return 1;
            },
            err => 'C62',
        },
        
        # С4 
        # В каждом из повторений последовательности С следующие поля определения сторон не могут 
        # присутствовать более одного раза (Код ошибки Е84):
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $bs = $tree->{'SETDET'} || [];
                
                for my $b (@$bs) {
                    my $fields = _flatten_tree($b);
                    for my $code (qw(CDEA INT2 INTE ACCW BENM)) {
                        if (seq_key_value_count($fields, '95[A-Z]', ":$code") > 1) {
                            return 0;
                        }
                    }
                }
                
                return 1;
            },
            err => 'E84',
        },
        
        # С5 
        # В одном из повторений последовательности С должно присутствовать следующее 
        # значение (Код ошибки Е90): :22H::PRIT//APFM
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $bs = $tree->{'SETDET'} || [];
                my $found = 0;
                for my $b (@$bs) {
                    if (seq_key_value_exists($b->{fields}, '22H', ":PRIT//APFM")) {
                        $found = 1;
                        last;
                    }
                }
                return 0 unless ($found);
                return 1;
            },
            err => 'E90',
        },
        
        # С6 
        # В одном из повторений последовательности С должно присутствовать следующее значение 
        # (Код ошибки Е90): :22H::PRIT//APCP
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $bs = $tree->{'SETDET'} || [];
                my $found = 0;
                for my $b (@$bs) {
                    if (seq_key_value_exists($b->{fields}, '22H', ":PRIT//APCP")) {
                        $found = 1;
                        last;
                    }
                }
                return 0 unless ($found);
                return 1;
            },
            err => 'E90',
        },
        
        # С7 
        # Следующие поля в последовательности С не могут присутствовать более одного раза в сообщении 
        # (Код ошибки Е92):
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $bs = $tree->{'SETDET'} || [];
                
                for my $code (qw(APFM APCP IPFM IPCP)) {
                    my $count = 0;
                    for my $b (@$bs) {
                        $count += seq_key_value_count($b->{fields}, '22H', ":PRIT//$code");
                        return 0 if ($count > 1);
                    }
                }
                
                return 1;
            },
            err => 'E92',
        },
        
        # С8 
        # Код валюты в полях сумм (поле 19A в последовательности В) должен быть одинаковым во всех 
        # повторениях этого поля в сообщении (Код ошибки С02)
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $fields_b = $tree->{'LDDET'}->[0]->{fields} || [];
                
                my $values = seq_get_all($fields_b, '19A');
                my $first_found_curr;
                for my $v (@$values) {
                    my ($curr) = $v =~ /\/\/([A-Z]{3})/;
                    if (!$first_found_curr) {
                        $first_found_curr = $curr;
                    } else {
                        return 0 if ($curr ne $first_found_curr);
                    }
                }
                
                return 1;
            },
            err => 'C02',
        },
        
        # С9 
        # С учетом правил С4, С6 и С7, в каждом из повторений последовательности С присутствие 
        # различных полей определения сторон (то есть полей 95a::CDEA и 95a::ACCW) в 
        # подпоследовательности С1 зависит от значения поля 22H в последовательности С следующим 
        # образом (Код ошибки Е91):
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $bs_c = $tree->{'SETDET'} || [];
                for my $b_c (@$bs_c) {
                    my $bs_c1 = $b_c->{'SETPRTY'} || [];
                    if (seq_key_value_exists($b_c->{fields}, '22H', ":PRIT//(APCP|IPCP)")) {
                        my $found = 0;
                        for my $b_c1 (@$bs_c1) {
                            if (seq_key_value_exists($b_c1->{fields}, '95[A-Z]', ':CDEA')) {
                                $found = 1;
                                last;
                            }
                        }
                        return 0 unless ($found);
                    }
                    elsif (seq_key_value_exists($b_c->{fields}, '22H', ":PRIT//(APFM|IPFM)")) {
                        my $found = 0;
                        for my $b_c1 (@$bs_c1) {
                            if (seq_key_value_exists($b_c1->{fields}, '95[A-Z]', ':ACCW')) {
                                $found = 1;
                                last;
                            }
                        }
                        return 0 unless ($found);
                    }
                }
                return 1;
            },
            err => 'E91',
        },
        
        # С10 
        # В последовательности А использование поля «Количество для подсчета» (:99В) зависит от 
        # признака пакетной сделки (:22H::BLOC) следующим образом (Код ошибки С60):
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $fields_a = $tree->{'GENL'}->[0]->{fields} || [];
                
                if (seq_key_value_exists($fields_a, '22H', ':BLOC')) {
                    return 0 unless (seq_key_exists($fields_a, '99B'));
                } else {
                    return 0 if (seq_key_exists($fields_a, '99B'));
                }
                
                return 1;
            },
            err => 'C60',
        },
        
        # C11 
        # В последовательности А наличие поля 99В влечет за собой обязательное присутствие всех 
        # определителей (Код ошибки С63)
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $fields_a = $tree->{'GENL'}->[0]->{fields} || [];
                
                if (seq_key_exists($fields_a, '99B')) {
                    for my $code ('SETT', 'TOSE') {
                        return 0 unless (seq_key_value_exists($fields_a, '99B', ":$code"));
                    }
                }
                
                return 1;
            },
            err => 'C63',
        },
        
        # C12 
        # В подпоследовательности В3, все заказы в поле 95а являются необязательными, но хотя бы 
        # один (любой) должен присутствовать (Код ошибки D92)
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                
                if (defined $tree->{'LDDET'}->[0]->{'OTHRPRTY'}->[0]) {
                    my $fields_b3 = $tree->{'LDDET'}->[0]->{'OTHRPRTY'}->[0]->{fields} || [];
                    return 0 unless (seq_key_value_exists($fields_b3, '95[A-Z]', ":(EXBO|MEOR)"));
                }
                
                return 1;
            },
            err => 'D92',
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