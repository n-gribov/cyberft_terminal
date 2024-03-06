package CyberFT::SWIFT::Types::MT304;
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
            key => '83a',
            key_regexp => '83[ADJ]',
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
            key => '30T',
            required => 1,
        },
        {
            key => '30V',
            required => 1,
        },
        {
            key => '36',
            required => 1,
        },
        {
            key => '32B',
            required => 1,
        },
        {
            key => '53a',
            key_regexp => '53[ADJ]',
            required => 1,
        },
        {
            key => '33B',
            required => 1,
        },
        {
            key => '57a',
            key_regexp => '57[ADJ]',
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
                my @required = qw(20 22A 94A 83[ADJ] 82[ADJ] 87[ADJ]);
                for my $k (@required) {
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
        
        # Проверка присутствия обязательных полей последовательностей B, B1, B2
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                my $b_seq = $seqs->{B}->[0] || [];
                my @required = qw(30T 30V 36 32B 53[ADJ] 57[ADJ]);
                for my $k (@required) {
                    unless (seq_key_exists($b_seq, $k)) {
                        my $ks = $k;
                        $ks =~ s/(\d+)(\[.*\])/\1a/;
                        return (0, 'Missing required field (B sequence): ' . $ks);
                    }
                }
                my $cur_subseq = '';
                my ($b1_53a, $b2_57a);
                for my $item (@$b_seq) {
                    if ($item->{key} eq '32B') {
                        $cur_subseq = 'B1';
                    }
                    elsif ($item->{key} eq '33B') {
                        $cur_subseq = 'B2';
                    }
                    elsif ($item->{key} =~ /53[ADJ]/ && $cur_subseq eq 'B1') {
                        $b1_53a = 1;
                    }
                    elsif ($item->{key} =~ /57[ADJ]/ && $cur_subseq eq 'B2') {
                        $b2_57a = 1;
                    }
                }
                unless ($b1_53a) {
                    return (0, 'Missing required field (B1 sequence): 53a');
                }
                unless ($b2_57a) {
                    return (0, 'Missing required field (B2 sequence): 57a');
                }
                return 1;
            },
            err => 'Missing required field in B sequence',
        },
        
        # С1 
        # В последовательности А использование поля 21 зависит от значения поля 22А и определяется 
        # следующим образом (Код ошибки D02):
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);                
                my $a = $seqs->{A}->[0] || [];
                if (seq_key_value_exists($a, '22A', 'AMND|CANC')) {
                    unless (seq_key_exists($a, '21')) {
                        return 0;
                    }
                }
                return 1;
            },
            err => 'D02',
        },
        
        # C2 
        # В последовательности А использование полей 17O и 17N зависит от значения поля 94А и 
        # определяется следующим образом (Код ошибки D03)
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);                
                my $a = $seqs->{A}->[0] || [];
                if (seq_key_value_exists($a, '94A', 'ASET')) {
                    if (seq_key_exists($a, '17[ON]')) {
                        return 0;
                    }
                }
                elsif (seq_key_value_exists($a, '94A', 'AFWD')) {
                    unless (seq_key_exists($a, '17O') && seq_key_exists($a, '17N')) {
                        return 0;
                    }
                }
                return 1;
            },
            err => 'D03',
        },
        
        # С3 
        # В последовательности А использование поля 17F зависит от поля 17О и определяется следующим 
        # образом (Код ошибки D04):
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);                
                my $a = $seqs->{A}->[0] || [];
                if (seq_key_value_exists($a, '17O', 'Y') || !seq_key_exists($a, '17O')) {
                    if (seq_key_exists($a, '17F')) {
                        return 0;
                    }
                }
                elsif (seq_key_value_exists($a, '17O', 'N')) {
                    unless (seq_key_exists($a, '17F')) {
                        return 0;
                    }
                }
                return 1;
            },
            err => 'D04',
        },
        
        # С4 
        # Использование последовательности D зависит от поля 17О в последовательности А 
        # определяется следующим образом (Код ошибки D23):
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);                
                my $a = $seqs->{A}->[0] || [];
                my $d_exists = $doc->key_exists('15D');
                if (seq_key_value_exists($a, '17O', 'Y') || !seq_key_exists($a, '17O')) {
                    if ($d_exists) {
                        return 0;
                    }
                }
                elsif (seq_key_value_exists($a, '17O', 'N')) {
                    unless ($d_exists) {
                        return 0;
                    }
                }
                return 1;
            },
            err => 'D23',
        },
        
        # C5 
        # Использование последовательности Е зависит от полей 17F и 17N и определяется следующим 
        # образом (Код ошибки D29):
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);                
                my $a = $seqs->{A}->[0] || [];
                my $val_17F = seq_get_first($a, '17F');
                my $val_17N = seq_get_first($a, '17N');
                my $e_exists = $doc->key_exists('15E');
                if ($val_17F =~ /Y/ && $val_17N =~ /Y/) {
                    unless ($e_exists) {
                        return 0;
                    }
                }
                elsif (
                    ($val_17F =~ /Y/ && $val_17N =~ /N/) ||
                    ($val_17F =~ /N/ && $val_17N =~ /Y|N/) ||
                    (!defined($val_17F) && $val_17N =~ /Y|N/) ||
                    (!defined($val_17F) && !defined($val_17N))
                ) {
                    if ($e_exists) {
                        return 0;
                    }
                }
                return 1;
            },
            err => 'D29',
        },
        
        # С6 
        # Если в последовательности С присутствует поле 72, и если первые 6 знаков первой строки 
        # этого поля имеют значение /VALD/, то следующие 8 знаков должны содержать определение даты, 
        # выраженное в формате YYYYMMDD (год, месяц, день), за которым должен следовать признак 
        # окончания строки CrLf (Код ошибки С58).
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);                
                my $c = $seqs->{C}->[0] || [];
                my $val_72 = seq_get_first($c, '72');
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
        
        # С7 
        # Если в последовательности С присутствует поле 72, то:
        # * если вторая строка начинается на /SETC/, за ней следует указывать код валюты (ISO 4217) 
        #   и признак окончания строки (/SWTC/currencyCrLf) (Код ошибки С59).
        # * если первые шесть знаков второй строки этого поля имеют значение /SETC/, то первые шесть 
        #   знаков первой строки должны содержать код /VALD/ (Код ошибки C59).
        # * код /SETC/ используется только в первых шести знаках второй строки (Код ошибки C59).
        # * если первые шесть знаков третьей строки этого поля имеют значение /SRCE/, то первые шесть 
        #   знаков второй строки должны содержать код /SETC/ (Код ошибки C59).
        # * код / SRCE / используется только в первых шести знаках третьей строки (Код ошибки C59).
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                
                my $c = $seqs->{C}->[0] || [];
                my $val_72 = seq_get_first($c, '72');
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
        
        # С8 
        # Во всех необязательных последовательностях, если существует последовательность, поля со 
        # статусом М должны присутствовать, другие не используются (Код ошибки С32).
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                
                # Проверка С1 и С1a (после 22M должно идти 22N; после 22P должно идти 22R)
                my $c_sequence = $seqs->{C}->[0] || undef;
                if (defined $c_sequence) {
                    my $c_len = scalar(@$c_sequence);
                    for my $i (0 .. $c_len-1){
                        if ($c_sequence->[$i]->{key} eq '22M') {
                            unless ($c_sequence->[$i+1]->{key} eq '22N') {
                                return 0;
                            }
                        }
                        if ($c_sequence->[$i]->{key} eq '22P') {
                            unless ($c_sequence->[$i+1]->{key} eq '22R') {
                                return 0; 
                            }
                        }
                    }
                }
                
                # Проверка обязательных полей последовательности D
                my $d_sequence = $seqs->{D}->[0] || undef;
                my @d_required_fields = qw(21P 17G 32G);
                if (defined $d_sequence) {
                    for my $k (@d_required_fields) {
                        unless (seq_key_exists($d_sequence, $k)) {
                            return 0;
                        }
                    }
                }
                
                # Проверка обязательных полей последовательности E
                my $e_sequence = $seqs->{E}->[0] || undef;
                my @e_required_fields = qw(17G 32G);
                if (defined $e_sequence) {
                    for my $k (@e_required_fields) {
                        unless (seq_key_exists($e_sequence, $k)) {
                            return 0;
                        }
                    }
                }
                
                return 1;
            },
            err => 'C32',
        },
        
        # C9 
        # В полях, указанных ниже, коды XAU, XAG, XPD и XPT не используются, поскольку эти коды
        # относятся к товарам, для которых должна использоваться 6 категоря сообщений 
        # (Код ошибки C08):
        # Подпоследовательность B1 Купленная Сумма, поле 32B Валюта, Сумма.
        # Подпоследовательность B2 Купленная Сумма, поле 33B Валюта, Сумма.
        # Последовательность D Данные для бухгалтерского учета, поле 32G Валюта, Сумма.
        # Последовательность E Чистая сумма расчетов, поле 32G Валюта, Сумма.
        {
            func => sub {
                my $doc = shift;
                my $values = $doc->get_all('(32B|33B|32G)');
                for my $v (@$values) {
                    if ($v =~ /XAU|XAG|XPD|XPT/) {
                        return 0;
                    }
                }
                return 1;
            },
            err => 'C08',
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