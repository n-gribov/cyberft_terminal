package CyberFT::SWIFT::Types::MT306;
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
            key => '22C',
            required => 1,
        },
        {
            key => '21N',
            required => 1,
        },
        {
            key => '12F',
            required => 1,
        },
        {
            key => '12E',
            required => 1,
        },
        {
            key => '17A',
            required => 1,
        },
        {
            key => '17F',
            required => 1,
        },
        {
            key => '22K',
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
            key => '77H',
            required => 1,
        },
        {
            key => '15B',
            required => 1,
            allow_empty => 1,
        },
        {
            key => '17V',
            required => 1,
        },
        {
            key => '30T',
            required => 1,
        },
        {
            key => '30X',
            required => 1,
        },
        {
            key => '29E',
            required => 1,
        },
        {
            key => '30a',
            key_regexp => '30[FJ]',
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
                my @a_seq_required = qw(
                    20 20 22A 22C 21N 12F 12E 17A 17F 22K 82[ADJ] 87[ADJ] 77H
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
        
        # �������� ����������� ������������ ����� ������������������ B
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                my $b_seq = $seqs->{B}->[0] || [];
                my @b_seq_required = qw( 17V 30T 30X 29E 30[FJ] );
                for my $k (@b_seq_required) {
                    unless (seq_key_exists($b_seq, $k)) {
                        my $ks = $k;
                        $ks =~ s/(\d+)(\[.*\])/\1a/;
                        return (0, 'Missing required field (B sequence): ' . $ks);
                    }
                }                
                return 1;
            },
            err => 'Missing required field in B sequence',
        },
        
        # �1 
        # � ������������������ �, ���� ���� 12F � �������� ��� VANI, �� ���� �� � ����� �� ����� 17� 
        # ��� 17F ������ ���� ������ ��� Y. ��� Y ����� �������������� � ����� ���� ����� 
        # (��� ������ D24).
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                my $a_seq = $seqs->{A}->[0] || [];
                if (seq_key_value_exists($a_seq, '12F', 'VANI')) {
                    my $y17a = seq_key_value_exists($a_seq, '17A', 'Y');
                    my $y17f = seq_key_value_exists($a_seq, '17F', 'Y');
                    return 0 unless ($y17a || $y17f);
                }
                return 1;
            },
            err => 'D24',
        },
        
        # �2 
        # � ������������������ � ������������� ���� 21 ������� �� �������� ���� 22� � ������������ 
        # ��������� ������� (��� ������ D02):
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                my $a_seq = $seqs->{A}->[0] || [];
                if (seq_key_value_exists($a_seq, '22A', 'AMND|CANC')) {
                    return 0 unless (seq_key_exists($a_seq, '21'));
                }
                return 1;
            },
            err => 'D02',
        },
        
        # �3 
        # � ������������������ � ����������� �������� ���� 12� ������� �� �������� ���� 12F � 
        # ������������ ��������� ������� (��� ������ D26):
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                my $a_seq = $seqs->{A}->[0] || [];
                my $v12f = seq_get_first($a_seq, '12F');
                my $v12e = seq_get_first($a_seq, '12E');
                if ($v12f =~ /BINA/) {
                    return 0 unless ($v12e =~ /AMER|EURO/);
                }
                elsif ($v12f =~ /DIGI|NOTO/) {
                    return 0 unless ($v12e =~ /EURO/);
                }
                elsif ($v12f =~ /VANI/) {
                    return 0 unless ($v12e =~ /AMER|ASIA|BERM|EURO/);
                }
                return 1;
            },
            err => 'D26',
        },
        
        # �4 
        # � ������������������ � ����������� �������� ������� 1 ���� 22� ���� �������� ������� �� 
        # ����� 12F � 17� � ������������������. � ��������� ������� (��� ������ D27)
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                my $a_seq = $seqs->{A}->[0] || [];
                my $v12f = seq_get_first($a_seq, '12F');
                my $v17a = seq_get_first($a_seq, '17A');
                my $v22k = seq_get_first($a_seq, '22K');
                if ($v12f =~ /VANI/ && $v17a =~ /N/) {
                    return 0 unless ($v22k =~ /^\s*(CONF|CLST|OTHR)/);
                }
                elsif ($v12f =~ /VANI/ && $v17a =~ /Y/) {
                    return 0 unless ($v22k =~ /^\s*(CONF|CLST|KNIN|KNOT|OTHR)/);
                }
                elsif ($v12f !~ /VANI/ && $v17a =~ /N/) {
                    return 0 unless ($v22k =~ /^\s*(CONF|CLST|TRIG|OTHR)/);
                }
                elsif ($v12f !~ /VANI/ && $v17a =~ /Y/) {
                    return 0 unless ($v22k =~ /^\s*(CONF|CLST|KNIN|KNOT|TRIG|OTHR)/);
                }
                return 1;
            },
            err => 'D27',
        },
        
        # �5 
        # � ������������������ � ����������� ����� 30U � 29H ������� �� �������� ������� 1 ���� 22� 
        # ���� �������� ��������� ������� (��� ������ D28):
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                my $a_seq = $seqs->{A}->[0] || [];
                my $v22k = seq_get_first($a_seq, '22K');
                my $e30u = seq_key_exists($a_seq, '30U');
                my $e29h = seq_key_exists($a_seq, '29H');
                if ($v22k =~ /CONF|CLST/) {
                    return 0 if ($e30u || $e29h);
                }
                elsif ($v22k !~ /CONF|CLST/) {
                    return 0 unless ($e30u && $e29h);
                }
                return 1;
            },
            err => 'D28',
        },
        
        # �6 
        # � ������������������ � ����������� ���� 77D ������� �� �������� ������� 1 ���� 77� 
        # ���� ����������� ��������� ������� (��� ������ D36):
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                my $a_seq = $seqs->{A}->[0] || [];
                my $v77h = seq_get_first($a_seq, '77H');
                my $e77d = seq_key_exists($a_seq, '77D');
                if ($v77h =~ /^\s*OTHER/) {
                    return 0 unless ($e77d);
                }
                return 1;
            },
            err => 'D36',
        },
        
        # �7 
        # � ������������������ � ������������� ���� 30� ������� �� �������� ���� 12� � 
        # ������������������ � ��������� ������� (��� ������ �55):
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                my $a_seq = $seqs->{A}->[0] || [];
                my $b_seq = $seqs->{B}->[0] || [];
                my $v12e = seq_get_first($a_seq, '12E');
                my $e30f = seq_key_exists($b_seq, '30F');
                if ($v12e =~ /^\s*EURO/) {
                    return 0 unless ($e30f);
                }
                return 1;
            },
            err => 'E55',
        },
        
        # �8 
        # ���� � ������������������� � (���� ������������������ ������������), � (���� 
        # ������������������ ������������) � J (���� ������������������ ������������) ����������� 
        # ���� 56�, �� ���� 86� � ��� �� ������������������ �, E ��� J �� ������������, � ��������� 
        # ������ ���� 86� �������������� (��� ������ �35):
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                for my $letter ('C', 'E', 'J') { 
                    my $seq = $seqs->{$letter}->[0] || [];
                    unless (seq_key_exists($seq, '56[A-Z]')) {
                        return 0 if (seq_key_exists($seq, '86[A-Z]'));
                    }
                }
                return 1;
            },
            err => 'E35',
        },
        
        # �9 
        # ������������� ������������������ D � ������������������ G ������� �� �������� ���� 12F 
        # ������������������ A ��������� ������� (��� ������ D30):
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                my $a_seq = $seqs->{A}->[0] || [];
                my $v12f = seq_get_first($a_seq, '12F');
                if ($v12f =~ /VANI/) {
                    return 0 unless ($doc->key_exists('15D'));
                    return 0 if ($doc->key_exists('15G'));
                }
                else {
                    return 0 if ($doc->key_exists('15D'));
                    return 0 unless ($doc->key_exists('15G'));
                }
                return 1;
            },
            err => 'D30',
        },
        
        # �10 
        # � ������������������ D, ����� ��� ������������ - �� ����, ����� ���� 12F � 
        # ������������������ A ����� �������� VANI (��. ����� ������� �10), ����������� ���� 30� � 
        # ���� 30Q ������� �� �������� ���� 12� � ������������������ A ��������� ������� 
        # (��� ������ D31):
        {
            func => sub {
                my $doc = shift;
                if ($doc->key_exists('15D')) {
                    my $seqs = _find_sequences($doc->data);
                    my $a_seq = $seqs->{A}->[0] || [];
                    my $d_seq = $seqs->{D}->[0] || [];
                    my $v12e = seq_get_first($a_seq, '12E');
                    if ($v12e =~ /AMER/) {
                        return 0 unless (seq_key_exists($d_seq, '30P'));
                        return 0 if (seq_key_exists($d_seq, '30Q'));
                    }
                    elsif ($v12e =~ /BERM/) {
                        return 0 if (seq_key_exists($d_seq, '30P'));
                        return 0 unless (seq_key_exists($d_seq, '30Q'));
                    }
                    else {
                        return 0 if (seq_key_exists($d_seq, '30P'));
                        return 0 if (seq_key_exists($d_seq, '30Q'));
                    }
                }
                return 1;
            },
            err => 'D31',
        },
        
        # �11 
        # � ������������������ D, ����� ��� ������������ - �� ����, ����� ���� 12F � 
        # ������������������ A ����� �������� VANI (��. ����� ������� �10), ����������� �������� 
        # ���� 26F ������� �� �������� ���� 17F � ������������������ A ��������� ������� 
        # (��� ������ D33):
        {
            func => sub {
                my $doc = shift;
                if ($doc->key_exists('15D')) {
                    my $seqs = _find_sequences($doc->data);
                    my $a_seq = $seqs->{A}->[0] || [];
                    my $d_seq = $seqs->{D}->[0] || [];
                    my $v17f = seq_get_first($a_seq, '17F');
                    my $v26f = seq_get_first($d_seq, '26F');
                    if ($v17f =~ /Y/) {
                        return 0 unless ($v26f =~ /NETCASH/);
                    }
                    elsif ($v17f =~ /N/) {
                        return 0 unless ($v26f =~ /NETCASH|PRINCIPAL/);
                    }
                }
                return 1;
            },
            err => 'D33',
        },
        
        # �12 
        # ������������� ������������������ E ������� �� �������� ���� 12F � ������� 1 ���� 22� 
        # ���� �������� � ������������������ � ��������� ������� (��� ������ D32):
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                my $a_seq = $seqs->{A}->[0] || [];
                my $v12f = seq_get_first($a_seq, '12F');
                my $v22k = seq_get_first($a_seq, '22K');
                if ($v12f =~ /VANI/) {
                    return 0 if ($doc->key_exists('15E'));
                }
                elsif ($v12f =~ /BINA|DIGI/) {
                    return 0 unless ($doc->key_exists('15E'));
                }
                elsif ($v12f =~ /NOTO/ && $v22k !~ /TRIG/) {
                    return 0 unless ($doc->key_exists('15E'));
                }
                elsif ($v12f =~ /NOTO/ && $v22k =~ /TRIG/) {
                    return 0 if ($doc->key_exists('15E'));
                }
                return 1;
            },
            err => 'D32',
        },
        
        # �13 
        # � ������������������ �, ����� ��� ������������ (��. ����� ������� �13), ������������� ���� 
        # 30H ������� �� �������� ������� 1 ���� 22� ���� �������� � ������������������ A ��������� 
        # ������� (��� ������ D34):
        {
            func => sub {
                my $doc = shift;
                if ($doc->key_exists('15E')) {
                    my $seqs = _find_sequences($doc->data);
                    my $a_seq = $seqs->{A}->[0] || [];
                    my $v22k = seq_get_first($a_seq, '22K');
                    my $v12e = seq_get_first($a_seq, '12E');
                    my $e_seq = $seqs->{E}->[0] || [];
                    my $e30h = seq_key_exists($e_seq, '30H');
                    if ($v22k =~ /TRIG/) {
                        return 0 unless ($v12e =~ /AMER/);
                        return 0 unless ($e30h);
                    }
                    else {
                        return 0 unless ($v12e =~ /AMER|EURO/);
                        return 0 if ($e30h);
                    }
                }
                return 1;
            },
            err => 'D34',
        },
        
        # �14 
        # ������������� ������������������ F ������� �� �������� ���� 17� � ������������������ A 
        # ��������� ������� (��� ������ D43):
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                my $a_seq = $seqs->{A}->[0] || [];
                my $v17a = seq_get_first($a_seq, '17A');
                if ($v17a =~ /Y/) {
                    return 0 unless ($doc->key_exists('15F'));
                }
                elsif ($v17a =~ /N/) {
                    return 0 if ($doc->key_exists('15F'));
                }
                return 1;
            },
            err => 'D43',
        },
        
        # �15 
        # � ������������������ F (��. ����� ������� �15) ������������� ���� 37L ������� �� �������� 
        # ���� 22G ��������� ������� (��� ������ D44):
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                my $f_seq = $seqs->{F}->[0] || [];
                my $v22g = seq_get_first($f_seq, '22G');
                my $e37l = seq_key_exists($f_seq, '37L');
                if ($v22g =~ /SKIN|SKOT/) {
                    return 0 if ($e37l);
                }
                elsif ($v22g =~ /DKIN|DKOT/) {
                    return 0 unless ($e37l);
                }
                return 1;
            },
            err => 'D44',
        },
        
        # �16 
        # � ������������������ G (��. ����� ������� �10) ������������� ���� 37P ������� �� �������� 
        # ���� 22J ��������� ������� (��� ������ D46):
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                my $g_seq = $seqs->{G}->[0] || [];
                my $v22j = seq_get_first($g_seq, '22J');
                my $e37p = seq_key_exists($g_seq, '37P');
                if ($v22j =~ /SITR/) {
                    return 0 if ($e37p);
                }
                elsif ($v22j =~ /DBTR/) {
                    return 0 unless ($e37p);
                }
                return 1;
            },
            err => 'D46',
        },
        
        # �17 
        # ������������� ������������������ � ������� �� �������� ���� 17F � ������������������ A 
        # ��������� ������� (��� ������ D47):
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                my $a_seq = $seqs->{A}->[0] || [];
                my $v17f = seq_get_first($a_seq, '17F');
                if ($v17f =~ /Y/) {
                    return 0 unless ($doc->key_exists('15H'));
                }
                elsif ($v17f =~ /N/) {
                    return 0 if ($doc->key_exists('15H'));
                }
                return 1;
            },
            err => 'D47',
        },
        
        # �18 
        # ������������� ������������������ I � ����������� ����� 88� � 71F � ������������������ ������� 
        # �� �������� ���� 94� � ������������������ � ��������� ������� (��� ������ D74):
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                my $a_seq = $seqs->{A}->[0] || [];
                my $v94a = seq_get_first($a_seq, '94A');
                my $i_seq = $seqs->{I}->[0] || [];
                my $e88a = seq_key_exists($i_seq, '88[A-Z]');
                my $e71f = seq_key_exists($i_seq, '71F');
                
                if (!defined($v94a) || $v94a !~ /BROK/) {
                    return 0 if ($e71f);
                }
                elsif ($v94a =~ /BROK/) {
                    return 0 unless ($e88a);
                }
                
                return 1;
            },
            err => 'D74',
        },
        
        # �19 
        # � ������������������ I, ���� ������������ ���� 15I, �� � ������������������ I ������ 
        # �������������� ����� ���� �� ��� ���� ���� (��� ������ �98).
        # � ������������������ K, ���� ������������ ���� 15K, �� � ������������������ I ������ 
        # �������������� ����� ���� �� ��� ���� ���� (��� ������ �98).
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                if ($doc->key_exists('15I')) {
                    my $s = $seqs->{I}->[0] || [];
                    return 0 if (scalar(@$s) < 2);
                }
                if ($doc->key_exists('15K')) {
                    my $s = $seqs->{K}->[0] || [];
                    return 0 if (scalar(@$s) < 2);
                }
                return 1;
            },
            err => 'C98',
        },
        
        # �20 
        # ����������� ��������������������� B1 � ������������������ � ������� �� �������� �������� 1 
        # ���� 22K ���� �������� � ������������������ � ��������� ������� (K�� ������ D16):
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                my $a_seq = $seqs->{A}->[0] || [];
                my $b_seq = $seqs->{B}->[0] || [];
                my $v22k = seq_get_first($a_seq, '22K');
                if ($v22k =~ /CONF/) {
                    return 0 unless (seq_key_exists($b_seq, '37K|30V|34B'));
                    return 0 unless ($doc->key_exists('15C'));
                }
                elsif ($v22k =~ /KNIN|KNOT|TRIG/) {
                    return 0 if (seq_key_exists($b_seq, '37K|30V|34B'));
                    return 0 if ($doc->key_exists('15C'));
                }
                return 1;
            },
            err => 'D16',
        },
        
        # �21 
        # �� ���� �������������� ������������������� � ����������������������, ���� ���������� 
        # ������������������ ��� ���������������������, ���� �� �������� � ������ ��������������, 
        # ������ �� ������������ (��� ������ �32).
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                
                # �������������� ������������������
                
                my $req = {
                    C => ['57[ADJ]'],
                    D => ['26F', '32B', '36', '33B'],
                    E => ['33E', '57[ADJ]'],
                    F => ['22G', '37J'],
                    G => ['22J', '37U', '32Q'],
                    H => ['14S', '32E'],
                    J => ['18A', '30F', '32H', '57[ADJ]'],
                };
                
                for my $letter (keys %$req) {
                    my $seq = $seqs->{$letter}->[0] || undef;
                    if (defined $seq) {
                        my $seq_required = $req->{$letter};
                        for my $k (@$seq_required) {
                            unless (seq_key_exists($seq, $k)) {
                                return 0;
                            }
                        }
                    }
                }
                
                # �������������� ���������������������
                
                # B1
                my $b_seq = $seqs->{B}->[0] || [];
                if (seq_key_exists($b_seq, '37K|30V|34B')) {
                    return 0 unless (seq_key_exists($b_seq, '30V'));
                    return 0 unless (seq_key_exists($b_seq, '34B'));
                }
                
                # F1
                my $f_seq = $seqs->{F}->[0] || [];
                if (seq_key_exists($f_seq, '30G|29J|29K')) {
                    return 0 unless (seq_key_exists($f_seq, '30G'));
                    return 0 unless (seq_key_exists($f_seq, '29J'));
                    return 0 unless (seq_key_exists($f_seq, '29K'));
                }
                
                # K1a, K1a1
                my $k_seq = $seqs->{K}->[0] || undef;
                if (defined $k_seq) {
                    my $k_len = scalar(@$k_seq);
                    for my $i (0 .. $k_len-1){
                        if ($k_seq->[$i]->{key} eq '22M') {
                            unless ($k_seq->[$i+1]->{key} eq '22N') {
                                return 0;
                            }
                        }
                        if ($k_seq->[$i]->{key} eq '22P') {
                            unless ($k_seq->[$i+1]->{key} eq '22R') {
                                return 0; 
                            }
                        }
                    }
                }
                
                return 1;
            },
            err => 'C32',
        },
        
        # C22 
        # � �����, ��������� ����, ���� XAU, XAG, XPD � XPT �� ������������, ��������� ��� ���� 
        # ��������� � �������, ��� ������� ������ �������������� 6 �������� ��������� 
        # (��� ������ C08):
        {
            func => sub {
                my $doc = shift;
                my $values = $doc->get_all('(34B|32B|33B|33E|32Q|32E|71F|32H)');
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