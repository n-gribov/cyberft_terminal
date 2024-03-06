package CyberFT::SWIFT::Types::MT305;
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
            key => '21',
            required => 1,
        },
        {
            key => '22',
            required => 1,
        },
        {
            key => '23',
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
            key => '30',
            required => 1,
        },
        {
            key => '31G',
            required => 1,
        },
        {
            key => '31E',
            required => 1,
        },
        {
            key => '26F',
            required => 1,
        },
        {
            key => '32B',
            required => 1,
        },
        {
            key => '36',
            required => 1,
        },
        {
            key => '33B',
            required => 1,
        },
        {
            key => '37K',
            required => 1,
        },
        {
            key => '34a',
            key_regexp => '34[PR]',
            required => 1,
        },
        {
            key => '57a',
            key_regexp => '57[AD]',
            required => 1,
        },
    ],

    rules => [
        
        # Проверка присутствия обязательных полей последовательности A
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                my $a_seq = $seqs->{A}->[0] || [];
                my @a_seq_required = qw(
                    20 20 21 22 23 82[ADJ] 87[ADJ] 30 31G 31E 26F 32B 36 33B 37K 34[PR] 57[AD]
                );
                for my $k (@a_seq_required) {
                    unless (seq_key_exists($a_seq, $k)) {
                        my $ks = $k;
                        $ks =~ s/(\d+)(\[.*\])/\1a/;
                        return (0, 'Missing required field (A sequence): ' . $ks);
                    }
                }                
                return 1;
            },
            err => 'Missing required field in A sequence',
        },
        
        # С1 
        # Поле 31C может присутствовать только в тех случаях, когда в поле 23 указан опцион 
        # американского типа (Код ошибки C79).
        {
            func => sub {
                my $doc = shift;
                my ($code) = ($doc->get_first('23') =~ m`^\S+?/\S+?/(\S)/`);
                if ($code ne 'A') {
                    if ($doc->key_exists('31C')) {
                        return 0;
                    }
                }
                return 1;
            },
            err => 'C79',
        },
        
        # С2 
        # Код валюты в четвертом подполе («Валюта») поля 23 должен совпадать с кодом валюты в поле 
        # 32B (Код ошибки C88).
        {
            func => sub {
                my $doc = shift;
                my ($code1) = ($doc->get_first('23') =~ m`^\S+?/\S+?/\S/([A-Z]{3})`);
                my ($code2) = ($doc->get_first('32B') =~ m`([A-Z]{3})`);
                if ($code1 ne $code2) {
                    return 0;
                }
                return 1;
            },
            err => 'C88',
        },
        
        # C3 В полях, указанных ниже, коды XAU, XAG, XPD и XPT не используются, поскольку эти коды 
        # относятся к товарам, для которых должна использоваться 6 категоря сообщений (Код ошибки C08):
        # 32B Базисная валюта и сумма, 33B Валюта и сумма оплаты, 34A Оплата премии
        {
            func => sub {
                my $doc = shift;
                my $values = $doc->get_all('(32B|33B|34[APR])');
                for my $v (@$values) {
                    if ($v =~ /XAU|XAG|XPD|XPT/) {
                        return 0;
                    }
                }
                return 1;
            },
            err => 'C08',
        },
        
        # С4 
        # Если в последовательности А присутствует поле 72, и если первые 6 знаков первой строки 
        # этого поля имеют значение /VALD/, то следующие 8 знаков должны содержать определение даты, 
        # выраженное в формате YYYYMMDD (год, месяц, день), за которым должен следовать признак 
        # окончания строки CrLf (Код ошибки С58).
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);                
                my $a_sequence = $seqs->{A}->[0] || [];
                my $val_72 = seq_get_first($a_sequence, '72');
                if ($val_72 && $val_72 =~ /^\/VALD\//) {
                    if ($val_72 =~ /^\/VALD\/(\d{4})(\d{2})(\d{2})\r\n/) {
                        my $year = $1;
                        my $month = $2;
                        my $day = $2;
                        if ($month > 12 || $day > 31) {
                            return 0;
                        }
                    } else {
                        return 0;
                    }
                }
                return 1;
            },
            err => 'C58',
        },
        
        # С5 
        # Если в последовательности А присутствует поле 72, то:
        # если первые шесть знаков первой строки этого поля имеют значение /VALD/, то должна 
        # присутствовать вторая сторока и начинаться с кода /SETC/, а за ней следует указывать код 
        # валюты (ISO 4217) и признак окончания строки (/SWTC/currencyCrLf) (Код ошибки С59).
        # если первые шесть знаков второй строки этого поля имеют значение /SETC/, то первые шесть 
        # знаков первой строки должны содержать код /VALD/ (Код ошибки C59).
        # код /SETC/ используется только в первых шести знаках второй строки (Код ошибки C59).
        # если первые шесть знаков третьей строки этого поля имеют значение /SRCE/, то первые шесть 
        # знаков второй строки должны содержать код /SETC/ (Код ошибки C59).
        # код / SRCE / используется только в первых шести знаках третьей строки (Код ошибки C59).
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                
                my $a_sequence = $seqs->{A}->[0] || [];
                my $val_72 = seq_get_first($a_sequence, '72');
                return 1 unless (defined $val_72);
                
                my @lines = split /\r\n/, $val_72;
                
                if ($lines[1] =~ /^\/SETC\//) {
                    return 0 unless ($lines[1] =~ /^\/SETC\/[A-Z]{3}$/);
                    return 0 unless ($lines[0]=~ /^\/VALD\//);
                }
                
                for my $i (0 .. scalar(@lines)-1) {
                    if ($i == 1) {
                        return 0 if ($lines[$i] =~ /^.+\/SETC\//);
                    } 
                    else {
                        return 0 if ($lines[$i] =~ /\/SETC\//);
                    }
                }
                
                if ($lines[2] =~ /^\/SRCE\//) {
                    return 0 unless ($lines[1] =~ /^\/SETC\//);
                }
                
                for my $i (0 .. scalar(@lines)-1) {
                    if ($i == 2) {
                        return 0 if ($lines[$i] =~ /^.+\/SRCE\//);
                    } 
                    else {
                        return 0 if ($lines[$i] =~ /\/SRCE\//);
                    }
                }
                
                return 1;
            },
            err => 'C59',
        },
        
        # С6 
        # Во всех необязательных последовательностях, если существует последовательность, поля со 
        # статусом М должны присутствовать, другие не используются (Код ошибки С32).
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                
                # Проверка B1a и B1a1 (после 22M должно идти 22N; после 22P должно идти 22R)
                my $b_sequence = $seqs->{B}->[0] || undef;
                if (defined $b_sequence) {
                    my $b_len = scalar(@$b_sequence);
                    for my $i (0 .. $b_len-1){
                        if ($b_sequence->[$i]->{key} eq '22M') {
                            unless ($b_sequence->[$i+1]->{key} eq '22N') {
                                return 0;
                            }
                        }
                        if ($b_sequence->[$i]->{key} eq '22P') {
                            unless ($b_sequence->[$i+1]->{key} eq '22R') {
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