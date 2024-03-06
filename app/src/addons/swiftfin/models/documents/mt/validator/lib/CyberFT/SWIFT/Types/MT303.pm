package CyberFT::SWIFT::Types::MT303;
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
            key => '94A',
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
    ],

    rules => [
        # Проверка присутствия обязательных полей последовательности A
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                my $a = $seqs->{A}->[0] || [];
                my @required = qw(20 22A 94A 22C 82[ADJ] 87[ADJ]);
                for my $k (@required) {
                    unless (seq_key_exists($a, $k)) {
                        my $ks = $k;
                        $ks =~ s/(\d+)(\[.*\])/\1a/;
                        return (0, 'Missing required field (A sequence): ' . $ks);
                    }
                }                
                return 1;
            },
            err => 'Missing required field in A sequence',
        },
        
        # C1
        # В последовательности А использование поля 21 зависит от значения поля 22А и определяется 
        # следующим образом (Код ошибки С84)
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);                
                my $a = $seqs->{A}->[0] || [];
                if (seq_key_value_exists($a, '22A', 'AMNA|AMND|CANC')) {
                    unless (seq_key_exists($a, '21')) {
                        return 0;
                    }
                }
                return 1;
            },
            err => 'C84',
        },
        
        # C2 
        # Последовательности В и С являются взаимоисключающими. Поле 94А в последовательности А 
        # определяет тип операции, части которой распределяются - а тем самым и то, какая из этих 
        # двух альтернативных последовательностей присутствует в сообщении (Код ошибки С92)
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);                
                my $a = $seqs->{A}->[0] || [];
                my $b_exists = $doc->key_exists('15B');
                my $c_exists = $doc->key_exists('15C');
                if (seq_key_value_exists($a, '94A', 'FORX')) {
                    return 0 if (!$b_exists || $c_exists);
                }
                elsif (seq_key_value_exists($a, '94A', 'FXOP')) {
                    return 0 if ($b_exists || !$c_exists);
                }
                return 0 if ($b_exists && $c_exists);
                return 1;
            },
            err => 'C92',
        },
        
        # С3 
        # Использование поля 30X в последовательности С зависит от значения поля 23В и определяется 
        # следующим образом (Код ошибки С95):
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);                
                my $c = $seqs->{C}->[0] || [];
                if (seq_key_value_exists($c, '23B', 'CLAM|PTAM')) {
                    return 0 unless (seq_key_exists($c, '30X'));
                }
                elsif (seq_key_value_exists($c, '23B', 'CLEU|PTEU')) {
                    return 0 if (seq_key_exists($c, '30X'));
                }
                
                return 1;
            },
            err => 'C95',
        },
        
        # С4 
        # Использование последовательности D зависит от значения поля 22А в последовательности А и 
        # определяется следующим образом (Код ошибки С97):
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);                
                my $a = $seqs->{A}->[0] || [];
                my $d_exists = $doc->key_exists('15D');
                if (seq_key_value_exists($a, '22A', 'AMNA|AMND|DUPL|NEWT')) {
                    return 0 unless ($d_exists);
                }
                return 1;
            },
            err => 'C97',
        },
        
        # C5 
        # В подпоследовательности D3 последовательности D использование поля 34В и следующих за ним 
        # полей участников движения средств зависит от значения поля 94А в последовательности А и 
        # определяется следующим образом (Код ошибки С99):
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);                
                my $a = $seqs->{A}->[0] || [];
                my $d = $seqs->{D}->[0] || [];
                if (seq_key_value_exists($a, '94A', 'FORX')) {
                    return 0 if (seq_key_exists($d, '34B'));
                }
                elsif (seq_key_value_exists($a, '94A', 'FXOP')) {
                    return 0 unless (seq_key_exists($d, '34B'));
                }
                return 1;
            },
            err => 'C99',
        },
        
        # С6 
        # Во всех необязательных последовательностях и подпоследовательностях, если существует 
        # последовательность или подпоследовательность, поля со статусом М должны присутствовать, 
        # другие не используются (Код ошибки С32).
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                
                my $bs = $seqs->{B} || [];
                my @b_required_fields = qw(30T 30V 36 32B 33B);
                for my $b (@$bs) {
                    for my $k (@b_required_fields) {
                        unless (seq_key_exists($b, $k)) {
                            return 0;
                        }
                    }
                }
                
                my $cs = $seqs->{C} || [];
                my @c_required_fields = qw(23B 17A 30T 29C 13E 32B 36A 33B 39P 30P 34B);
                for my $c (@$cs) {
                    for my $k (@c_required_fields) {
                        unless (seq_key_exists($c, $k)) {
                            return 0;
                        }
                    }
                }
                
                my $ds = $seqs->{D} || [];
                my @d_required_fields = qw(28C 21A 83[ADJ] 32B 33B 57[ADJ]);
                for my $d (@$ds) {
                    for my $k (@d_required_fields) {
                        unless (seq_key_exists($d, $k)) {
                            return 0;
                        }
                    }
                }
                for my $d (@$ds) {
                    my $subseqs = {'D1'=>[], 'D2'=>[], 'D3'=>[]};
                    my $cur_subseq = '';
                    for my $item (@$d) {
                        if ($item->{key} eq '32B') {
                            $cur_subseq = 'D1';
                        }
                        elsif ($item->{key} eq '33B') {
                            $cur_subseq = 'D2';
                        }
                        elsif ($item->{key} eq '34B') {
                            $cur_subseq = 'D3';
                        }
                        if ($cur_subseq) {
                            push @{$subseqs->{$cur_subseq}}, $item;
                        }
                    }
                    for my $subseq ('D1', 'D2', 'D3') {
                        if (scalar @{$subseqs->{$subseq}} > 0) {
                            unless (seq_key_exists($subseqs->{$subseq}, '57[ADJ]')) {
                                return 0;
                            }
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