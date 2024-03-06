package CyberFT::SWIFT::Types::MT330;
use CyberFT::SWIFT::Types::Utils;
use strict;
use Data::Dumper;

our $ValidationProfile = {

    fields => [
        # Обязательные поля обязательных последовательностей.
        {
            key => '15A',
            required => 1,
            allow_empty => 1,
        },
        {
            key => '20',
            required => 1,
        },
        {
            key => '22A',
            required => 1,
        },
        {
            key => '22B',
            required => 1,
        },
        {
            key => '22C',
            required => 1,
        },
        {
            key => '82a',
            key_regexp => '82[ADJ]',
            required => 1,
        },
        {
            key => '87a',
            key_regexp => '87[ADJ]',
            required => 1,
        },
        
        {
            key => '15B',
            required => 1,
            allow_empty => 1,
        },
        {
            key => '17R',
            required => 1,
        },
        {
            key => '30T',
            required => 1,
        },
        {
            key => '30V',
            required => 1,
        },
        {
            key => '38A',
            required => 1,
        },
        {
            key => '37G',
            required => 1,
        },
        {
            key => '14D',
            required => 1,
        },
        
        {
            key => '15C',
            required => 1,
            allow_empty => 1,
        },
        {
            key => '57a',
            key_regexp => '57[ADJ]',
            required => 1,
        },
        
        {
            key => '15D',
            required => 1,
            allow_empty => 1,
        },
        {
            key => '57a',
            key_regexp => '57[ADJ]',
            required => 1,
        },
    ],

    rules => [
        # Проверка присутствия обязательных полей обязятельных последовательностей
        {
            func => sub {
                my $doc = shift;
                
                my $seqs = _find_sequences($doc->data);
                my $reqs = {
                    'A' => [qw( 20 22A 22B 22C 82[ADJ] 87[ADJ] )],
                    'B' => [qw( 17R 30T 30V 38A 37G 14D )],
                    'C' => [qw( 57[ADJ] )],
                    'D' => [qw( 57[ADJ] )],
                };
                
                for my $l (keys %$reqs) {
                    my $fields = $reqs->{$l};
                    my $seq = $seqs->{$l}->[0] || [];
                    for my $k (@$fields) {
                        unless (seq_key_exists($seq, $k)) {
                            my $ks = $k;
                            $ks =~ s/(\d+)(\[.*\])/\1a/;
                            return (0, "Missing required field ($l sequence): $ks");
                        }
                    }
                }
                
                return 1;
            },
            err => 'Missing required field',
        },
        
        # C1 
        # В последовательности А присутствие поля 21 зависит от значения полей 22В и 22А и 
        # определяется следующим образом (Код ошибки D70):
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                my $a_seq = $seqs->{A}->[0] || [];
                my $val_22a = seq_get_first($a_seq, '22A');
                my $val_22b = seq_get_first($a_seq, '22B');
                
                if (($val_22b =~ /CONF/ && $val_22a !~ /NEWT/) || $val_22b !~ /CONF/) {
                    return 0 unless (seq_key_exists($a_seq, '21'));
                }
                
                return 1;
            },
            err => 'D70',
        },
        
        # C2 
        # Если в последовательности А присутствует поле 94А с кодом AGNT, то поле 21N в 
        # последовательности А является обязательным, в остальных случаях поле 21N необязательное 
        # (Код ошибки D72):
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                my $a_seq = $seqs->{A}->[0] || [];
                
                if (seq_key_value_exists($a_seq, '94A', 'AGNT')) {
                    return 0 unless (seq_key_exists($a_seq, '21N'));
                }
                
                return 1;
            },
            err => 'D72',
        },
        
        # C3 
        # В последовательности В присутствие полей 32В, 32Н и 30Х зависит от значения поля 22В в 
        # последовательности А и определяется следующим образом (Код ошибки D56):
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                my $a_seq = $seqs->{A}->[0] || [];
                my $b_seq = $seqs->{B}->[0] || [];
                
                my $val_22b = seq_get_first($a_seq, '22B');
                
                if ($val_22b =~ /CHNG|CINT/) {
                    return 0 unless (seq_key_exists($b_seq, '32B'));
                    return 0 unless (seq_key_exists($b_seq, '32H'));
                }
                elsif ($val_22b =~ /CONF/) {
                    return 0 unless (seq_key_exists($b_seq, '32B'));
                    return 0 if (seq_key_exists($b_seq, '32H'));
                    return 0 if (seq_key_exists($b_seq, '30X'));
                }
                elsif ($val_22b =~ /SETT/) {
                    return 0 if (seq_key_exists($b_seq, '32B'));
                    return 0 unless (seq_key_exists($b_seq, '32H'));
                    return 0 unless (seq_key_exists($b_seq, '30X'));
                }
                
                return 1;
            },
            err => 'D56',
        },
        
        # C4 
        # В последовательности В разрешенные значения поля 32Н зависят от значений полей 22В в 
        # последовательности А и 17 R в последовательности В следующим образом (Код ошибки D57):
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                my $a_seq = $seqs->{A}->[0] || [];
                my $b_seq = $seqs->{B}->[0] || [];
                
                my $val_22b = seq_get_first($a_seq, '22B');
                my $val_17r = seq_get_first($b_seq, '17R');
                my $val_32h = seq_get_first($b_seq, '32H');
                
                my $sum;
                if ($val_32h =~ /^(N?)[A-Z]{3}(\d+[\.\,]?\d*)/) {
                    my $sign = $1;
                    $sum = $2;
                    $sum =~ s/,/./;
                    
                    # Если подполе 3 (Сумма) поля 32Н = 0 (то есть содержит нулевую сумму), 
                    # то буква N (Знак) в подполе 1 поля 32Н не должна указываться (Код ошибки Т14).
                    if ($sum == 0 && $sign eq 'N') {
                        return (0, 'T14');
                    }
                    
                    if ($sign) {
                        $sum = '-' . $sum;
                    }
                }
                
                if ($val_22b =~ /SETT/) {
                    if ($val_17r =~ /L/) {
                        return 0 unless ($sum <= 0);
                    }
                    elsif ($val_17r =~ /B/) {
                        return 0 unless ($sum >= 0);
                    }
                }
                
                return 1;
            },
            err => 'D57',
        },
        
        # C5 
        # Если в последовательности В присутствует поле 30Х, то поле 34Е в последовательности В 
        # является обязательным, в остальных случаях поле 34Е не используется (Код ошибки D85)
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                my $b_seq = $seqs->{B}->[0] || [];
                
                if (seq_key_exists($b_seq, '30X')) {
                    return 0 unless (seq_key_exists($b_seq, '34E'));
                } else {
                    return 0 if (seq_key_exists($b_seq, '34E'));
                }
                
                return 1;
            },
            err => 'D85',
        },
        
        # C6 
        # Если поле 22В в последовательности А содержит код SETT, то поле 30F в последовательности 
        # В не используется, в остальных случаях поле 30F необязательное (Код ошибки D69).
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                my $a_seq = $seqs->{A}->[0] || [];
                my $b_seq = $seqs->{B}->[0] || [];
                
                if (seq_key_value_exists($a_seq, '22B', 'SETT')) {
                    return 0 if (seq_key_exists($b_seq, '30F'));
                }
                
                return 1;
            },
            err => 'D69',
        },
        
        # C7 
        # Если в последовательности В присутствует поле 30F, то поле 38J в последовательности В 
        # является обязательным, в остальных случаях поле 38J не используется (Код ошибки D60).
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                my $b_seq = $seqs->{B}->[0] || [];
                
                if (seq_key_exists($b_seq, '30F')) {
                    return 0 unless (seq_key_exists($b_seq, '38J'));
                } else {
                    return 0 if (seq_key_exists($b_seq, '38J'));
                }
                
                return 1;
            },
            err => 'D60',
        },
        
        # С8 
        # Если в последовательностях C, D, E (если они присутствуют) и F (если она присутствует) 
        # отсутствует поле 56а, то поле 86а в той же последовательности, C, D, E или F, 
        # соответственно, не должно использоваться, в остальных случаях поле 86а необязательное 
        # (Код ошибки Е35).
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                
                for my $k ('C', 'D', 'E', 'F') {
                    my $kseqs = $seqs->{$k} || [];
                    for my $seq (@$kseqs) {
                        unless (seq_key_exists($seq, '56[A-Z]')) {
                            return 0 if (seq_key_exists($seq, '86[A-Z]'));
                        }
                    }
                }
                
                return 1;
            },
            err => 'E35',
        },
        
        # С9 
        # Код валюты в полях сумм должен быть одинаковым во всех повторениях этих полей в сообщении, 
        # за исключением полей 33В и 33Е в последовательности G (Код ошибки С02).
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                my $b_seq = $seqs->{B}->[0] || [];
                
                my $curr;
                my $vs = seq_get_all($b_seq, '32B|32H|34E');
                for my $v (@$vs) {
                    my ($c) = $v =~ /([A-Z]{3})\d+/;
                    if (!$curr) {
                        $curr = $c;
                    } 
                    elsif ($curr ne $c) {
                        return 0;
                    }
                }
                
                return 1;
            },
            err => 'C02',
        },
        
        # С10 
        # В последовательности Н поле 15Н не может быть единственным полем, то есть если присутствует 
        # поле 15Н, то должно присутствовать еще хотя бы одно из других полей последовательности Н 
        # (Код ошибки С98).
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                my $h_seq = $seqs->{H}->[0] || undef;
                if (defined $h_seq) {
                    return 0 if (scalar @$h_seq < 2);
                }
                return 1;
            },
            err => 'C98',
        },
        
        # С11 
        # Во всех необязательных последовательностях, если существует последовательность, поля со 
        # статусом М должны присутствовать, другие не используются (Код ошибки С32).
        # Проверка присутствия обязательных полей обязятельных последовательностей
        {
            func => sub {
                my $doc = shift;
                
                my $seqs = _find_sequences($doc->data);
                my $reqs = {
                    'E' => [qw( 57[ADJ] )],
                    'F' => [qw( 57[ADJ] )],
                    'G' => [qw( 37L 33B )],
                };
                
                for my $k (keys %$reqs) {
                    my $fields = $reqs->{$k};
                    my $kseqs = $seqs->{$k} || [];
                    for my $seq (@$kseqs) {
                        for my $f (@$fields) {
                            return 0 unless (seq_key_exists($seq, $f));
                        }
                    }
                }
                
                return 1;
            },
            err => 'C32',
        },
        
    ]
};

# Вытаскиваем основные последовательности как хэш. Каждая последовательность начинается
# с поля 15 c буквенной опцией. Например, поле 15B - это начало последовательности "B".
sub _find_sequences {
    my $data = shift;
    my $seqs = {};
    my $cur_seq = undef;
    my $cur_seq_code = undef;
    for my $item (@$data) {
        my $k = $item->{key};
        if ($k =~ /^15([A-Z])$/) {
            $cur_seq_code = $1;
            $cur_seq = [];
            push @{$seqs->{$cur_seq_code}}, $cur_seq;
        }
        if ($cur_seq) {
            push @$cur_seq, $item;
        }
    }
    return $seqs;
}

1;