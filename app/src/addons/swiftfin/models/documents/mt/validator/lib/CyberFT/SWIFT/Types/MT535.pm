package CyberFT::SWIFT::Types::MT535;
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
        # Проверка обязательных последовательностей и полей
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

        # C1 Если «Флаг действий» (поле :17B::ACTI) в последовательности А «Общая информация» имеет
        # значение «N», то последовательность В не должна использоваться. В противном случае
        # последовательность В обязательная (Код ошибки Е66).
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

        # С2 Если выписка относится к выпискам финансового учета, т.е. если в сообщении присутствует
        # поле :22F::STTY//ACCT, обязательно должна присутствовать хотя бы одна подпоследовательность В1.
        # Выполнение этого правила проверяется только в тех случаях, когда в сообщении присутствует
        # последовательность В «Субсчет депо» (согласно правилу С1), т.е. когда поле :17B::ACTI имеет
        # значение «Y» (Код ошибки Е67).
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

        # C3 В каждом из повторений подпоследовательности В1, если в нем нет ни одной
        # подпоследовательности B1b, должны быть указаны как «Цена» (поле :90a:), так и «Стоимость
        # хранящихся на счете финансовых инструментов» (поле :19A::HOLD).
        # В каждом из повторений подпоследовательности В1, если в нем присутствует хотя бы одна
        # подпоследовательности B1b, в каждом повторении B1b должны быть указаны как «Цена»
        # (поле :90a:), так и «Стоимость хранящихся на счете финансовых инструментов» (поле :19A::HOLD).
        # Это правило относится только к выпискам финансового учета (согласно правилу С2), т.е.
        # когда в сообщении присутствует поле :22F::STTY//ACCT (Код ошибки Е82).
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

        # С4 Если сообщение направляется для отмены ранее отправленной выписки, т.е. если поле 23G
        # «Функция сообщения» содержит код CANC, в сообщении должна присутствовать хотя бы одна
        # подпоследовательность А1 «Связки», и в блоке связок должен быть указан референс предыдущего
        # сообщения – т.е., хотя бы один раз в сообщении должно присутствовать поле :20C::PREV.
        # (Код ошибки Е08).
        # Последовательность А если поле :23G:... то подпоследоват. А1 ... и поле :20C::PREV
        # CANC    Обязательная, т.е. хотя бы одна подпоследовательность А1    Обязательное в одном повторении А1 и не допускается в остальных повторениях А1
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

        # С5 Если в последовательности А поле :17B::CONS имеет значение «Y», то в каждом из повторений
        # последовательности В поле :97a::SAFE является обязательным (Код ошибки Е56).
        # Выполнение этого правила проверяется только в тех случаях, когда в сообщении присутствует
        # последовательность В «Субсчет депо» (согласно правилу С1), т.е. когда поле :17B::ACTI в
        # последовательности А имеет значение «Y».
        # (см. таблицу в документации)
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

        # С6 Если «Флаг действий» (поле :17B::ACTI) в последовательности В «Субсчет депо» указывает
        # на отсутствие информации для отчетности, т.е. имеет значение «N», то подпоследователь-ность
        # В1 «Финансовый инструмент» не должна использоваться. В остальных случаях подпоследовательность
        # В1 «Финансовый инструмент» обязательная (Код ошибки Е69).
        # Выполнение этого правила проверяется только в тех случаях, когда в сообщении присутствует
        # последовательность В «Субсчет депо» (согласно правилу С1), т.е., когда поле :17B::ACTI имеет
        # значение «Y».
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

        # С7 Если поле :94a:: присутствует в последовательности В, то поля :93B::AGGR и :94a::SAFE
        # не должны использоваться ни в одном из повторений подпоследовательности В1b (Код ошибки D03).
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                my $seqs_b = $seqs->{'B'} || [];
                for my $s (@$seqs_b) {

                    # Проверим, есть ли поле 94a в последовательности B (но подпоследовательности не считаем).
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

        # С8 Если поле :93B::AGGR присутствует в подпоследовательности В1b, то поле :94a::SAFE также
        # должно присутствовать в том же повторении подпоследовательности В1b (Код ошибки D04).
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

        # С9 Если в любом из повторений подпоследовательности В1b присутствует поле :93B::AVAI и/или
        # поле :93B::NAVL, то в том же повторении подпоследовательности В1b также должно присутствовать
        # поле :93B::AGGR (Код ошибки D05).
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

        # С10 В любом повторении подпоследовательности В1 поле :93B::AGGR не может использоваться
        # более двух раз. Если это поле используется дважды, то в первом случае Код типа количества
        # должен быть «FAMT», а во втором случае Код типа количества должен быть «AMOR» (Код ошибки С71).
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

# Вытаскиваем все последовательности как хэш массивов.
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