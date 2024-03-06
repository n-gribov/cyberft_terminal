package CyberFT::SWIFT::Types::MT304;
use CyberFT::SWIFT::Types::Utils;
use strict;
use Data::Dumper;

our $ValidationProfile = {

    fields => [
        # ������������ ���� ������������ �������������������.
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
        # �������� ����������� ������������ ����� ������������������ A
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
        
        # �������� ����������� ������������ ����� ������������������� B, B1, B2
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
        
        # �1 
        # � ������������������ � ������������� ���� 21 ������� �� �������� ���� 22� � ������������ 
        # ��������� ������� (��� ������ D02):
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
        # � ������������������ � ������������� ����� 17O � 17N ������� �� �������� ���� 94� � 
        # ������������ ��������� ������� (��� ������ D03)
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
        
        # �3 
        # � ������������������ � ������������� ���� 17F ������� �� ���� 17� � ������������ ��������� 
        # ������� (��� ������ D04):
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
        
        # �4 
        # ������������� ������������������ D ������� �� ���� 17� � ������������������ � 
        # ������������ ��������� ������� (��� ������ D23):
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
        # ������������� ������������������ � ������� �� ����� 17F � 17N � ������������ ��������� 
        # ������� (��� ������ D29):
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
        
        # �6 
        # ���� � ������������������ � ������������ ���� 72, � ���� ������ 6 ������ ������ ������ 
        # ����� ���� ����� �������� /VALD/, �� ��������� 8 ������ ������ ��������� ����������� ����, 
        # ���������� � ������� YYYYMMDD (���, �����, ����), �� ������� ������ ��������� ������� 
        # ��������� ������ CrLf (��� ������ �58).
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
        
        # �7 
        # ���� � ������������������ � ������������ ���� 72, ��:
        # * ���� ������ ������ ���������� �� /SETC/, �� ��� ������� ��������� ��� ������ (ISO 4217) 
        #   � ������� ��������� ������ (/SWTC/currencyCrLf) (��� ������ �59).
        # * ���� ������ ����� ������ ������ ������ ����� ���� ����� �������� /SETC/, �� ������ ����� 
        #   ������ ������ ������ ������ ��������� ��� /VALD/ (��� ������ C59).
        # * ��� /SETC/ ������������ ������ � ������ ����� ������ ������ ������ (��� ������ C59).
        # * ���� ������ ����� ������ ������� ������ ����� ���� ����� �������� /SRCE/, �� ������ ����� 
        #   ������ ������ ������ ������ ��������� ��� /SETC/ (��� ������ C59).
        # * ��� / SRCE / ������������ ������ � ������ ����� ������ ������� ������ (��� ������ C59).
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
        
        # �8 
        # �� ���� �������������� �������������������, ���� ���������� ������������������, ���� �� 
        # �������� � ������ ��������������, ������ �� ������������ (��� ������ �32).
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                
                # �������� �1 � �1a (����� 22M ������ ���� 22N; ����� 22P ������ ���� 22R)
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
                
                # �������� ������������ ����� ������������������ D
                my $d_sequence = $seqs->{D}->[0] || undef;
                my @d_required_fields = qw(21P 17G 32G);
                if (defined $d_sequence) {
                    for my $k (@d_required_fields) {
                        unless (seq_key_exists($d_sequence, $k)) {
                            return 0;
                        }
                    }
                }
                
                # �������� ������������ ����� ������������������ E
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
        # � �����, ��������� ����, ���� XAU, XAG, XPD � XPT �� ������������, ��������� ��� ����
        # ��������� � �������, ��� ������� ������ �������������� 6 �������� ��������� 
        # (��� ������ C08):
        # ��������������������� B1 ��������� �����, ���� 32B ������, �����.
        # ��������������������� B2 ��������� �����, ���� 33B ������, �����.
        # ������������������ D ������ ��� �������������� �����, ���� 32G ������, �����.
        # ������������������ E ������ ����� ��������, ���� 32G ������, �����.
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

# ����������� �������� ������������������ ��� ���. ������ ������������������ ����������
# � ���� 15 c ��������� ������. ��������, ���� 15B - ��� ������ ������������������ "B".
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