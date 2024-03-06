package CyberFT::SWIFT::Types::MT361;
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
            key => '23A',
            required => 1,
        },
        {
            key => '21N',
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
            key => '30P',
            required => 1,
        },
        {
            key => '32B',
            required => 1,
        },
        {
            key => '33B',
            required => 1,
        },
        {
            key => '82a',
            key_regexp => '82[AD]',
            required => 1,
        },
        {
            key => '87a',
            key_regexp => '87[AD]',
            required => 1,
        },
        {
            key => '77H',
            required => 1,
        },
        {
            key => '14C',
            required => 1,
        },
        
        {
            key => '15D',
            required => 1,
            allow_empty => 1,
        },
        {
            key => '57a',
            key_regexp => '57[AD]',
            required => 1,
        },
        
        {
            key => '15G',
            required => 1,
            allow_empty => 1,
        },
        {
            key => '57a',
            key_regexp => '57[AD]',
            required => 1,
        },
    ],

    rules => [
        # �������� ����������� ������������ ����� ������������ �������������������
        {
            func => sub {
                my $doc = shift;
                
                my $seqs = _find_sequences($doc->data);
                my $reqs = {
                    'A' => [qw( 20 22A 22C 23A 21N 30T 30V 30P 32B 33B 82[AD] 87[AD] 77H 14C )],
                    'D' => [qw( 57[AD] )],
                    'G' => [qw( 57[AD] )],
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
        
        # �1 
        # � ������������������ A, ���� ���� 14A �������� ��� "OTHER", ������ �������������� ���� 77D 
        # (��� ������ D35).
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                my $a_seq = $seqs->{A}->[0] || [];
                
                if (seq_key_value_exists($a_seq, '14A', 'OTHER')) {
                    return 0 unless (seq_key_exists($a_seq, '77D'));
                }
                
                return 1;
            },
            err => 'D35',
        },
        
        # �2 
        # � ������������������ A, ���� ������� 1 ���� 77H �������� ��� "OTHER", ������ �������������� 
        # ���� 77D (��� ������ D36).
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                my $a_seq = $seqs->{A}->[0] || [];
                
                if (seq_key_value_exists($a_seq, '77H', 'OTHER')) {
                    return 0 unless (seq_key_exists($a_seq, '77D'));
                }
                
                return 1;
            },
            err => 'D36',
        },
        
        # �3 
        # � ������������������� B, C, E � F, ���� ���� 14A �������� ��� "OTHER", � ��������������� 
        # ������������������ ������ �������������� ���� 37N (��� ������ D55).
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                
                for my $s ('B', 'C', 'E', 'F') {
                    my $seq = $seqs->{$s}->[0] || [];
                    if (seq_key_value_exists($seq, '14A', 'OTHER')) {
                        return 0 unless (seq_key_exists($seq, '37N'));
                    }
                }
                
                return 1;
            },
            err => 'D55',
        },
        
        # �4 
        # � ������������������� B, C, E � F, ���� ���� 14D �������� ��� "OTHER", � ��������������� 
        # ������������������ ������ �������������� ���� 37N (��� ������ D37).
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                
                for my $s ('B', 'C', 'E', 'F') {
                    my $seq = $seqs->{$s}->[0] || [];
                    if (seq_key_value_exists($seq, '14D', 'OTHER')) {
                        return 0 unless (seq_key_exists($seq, '37N'));
                    }
                }
                
                return 1;
            },
            err => 'D37',
        },
        
        # �5 
        # � ������������������� C � F, ���� ���� 14F �������� ��� "OTHER", � ��������������� 
        # ������������������ ������ �������������� ���� 37N (��� ������ D38).
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                
                for my $s ('C', 'F') {
                    my $seq = $seqs->{$s}->[0] || [];
                    if (seq_key_value_exists($seq, '14F', 'OTHER')) {
                        return 0 unless (seq_key_exists($seq, '37N'));
                    }
                }
                
                return 1;
            },
            err => 'D38',
        },
        
        # �6 
        # � ������������������� C � F, ���� ���� 14J �������� ��� "OTHER", �� � ��������������� 
        # ������������������ ������ �������������� ���� 37N (��� ������ D39).
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                
                for my $s ('C', 'F') {
                    my $seq = $seqs->{$s}->[0] || [];
                    if (seq_key_value_exists($seq, '14J', 'OTHER')) {
                        return 0 unless (seq_key_exists($seq, '37N'));
                    }
                }
                
                return 1;
            },
            err => 'D39',
        },
        
        # �7 
        # � ������������������� C � F, ���� ������� �������� ���� 14G �������� ��� "O", � 
        # ��������������� ������������������ ������ �������������� ���� 37N (��� ������ D40).
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                
                for my $s ('C', 'F') {
                    my $seq = $seqs->{$s}->[0] || [];
                    if (seq_key_value_exists($seq, '14G', '^O')) {
                        return 0 unless (seq_key_exists($seq, '37N'));
                    }
                }
                
                return 1;
            },
            err => 'D40',
        },
        
        # �8 
        # � ������������������� C � F, ���� ������� ������� ���� 38E �������� ��� "O", � 
        # ��������������� ������������������ ������ �������������� ���� 37N (��� ������ D41).
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                
                for my $s ('C', 'F') {
                    my $seq = $seqs->{$s}->[0] || [];
                    if (seq_key_value_exists($seq, '38E', 'O')) {
                        return 0 unless (seq_key_exists($seq, '37N'));
                    }
                }
                
                return 1;
            },
            err => 'D41',
        },
        
        # �9 
        # � ������������������� C � F, ���� ������� ������� � ...� ��� ������� �� ...� � ���� 38G 
        # ��� ���� 38H �������� ��� "O", � ��������������� ������������������ ������ �������������� 
        # ���� 37N (��� ������ D42).
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                
                for my $s ('C', 'F') {
                    my $seq = $seqs->{$s}->[0] || [];
                    if (seq_key_value_exists($seq, '38[GH]', 'O')) {
                        return 0 unless (seq_key_exists($seq, '37N'));
                    }
                }
                
                return 1;
            },
            err => 'D42',
        },
        
        # �10 
        # ����������� ������ ����� � ������������� � � ��������� ��������� ������� �� ���� ��������. 
        # ����� �������, � ����������� �� �������� ���� � ������� ���� ����� ���� 23A � 
        # ������������������ A ����������� ������ ��������� ���������� �������������� 
        # ������������������� B, C, E, � F (��� ������ �43):
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                my $a_seq = $seqs->{A}->[0] || [];
                my $b_exists = defined($seqs->{B});
                my $c_exists = defined($seqs->{C});
                my $e_exists = defined($seqs->{E});
                my $f_exists = defined($seqs->{F});
                my $val_23a = seq_get_first($a_seq, '23A');
                
                if ($val_23a =~ /^\s*FIXEDFIXED/) {
                    return 0 unless ($b_exists);
                    return 0 if ($c_exists);
                    return 0 unless ($e_exists);
                    return 0 if ($f_exists);
                }
                elsif ($val_23a =~ /^\s*FLOATFLOAT/) {
                    return 0 if ($b_exists);
                    return 0 unless ($c_exists);
                    return 0 if ($e_exists);
                    return 0 unless ($f_exists);
                }
                elsif ($val_23a =~ /^\s*FLOATFIXED/) {
                    return 0 unless ($b_exists);
                    return 0 if ($c_exists);
                    return 0 if ($e_exists);
                    return 0 unless ($f_exists);
                }
                elsif ($val_23a =~ /^\s*FIXEDFLOAT/) {
                    return 0 if ($b_exists);
                    return 0 unless ($c_exists);
                    return 0 unless ($e_exists);
                    return 0 if ($f_exists);
                }
                
                return 1;
            },
            err => 'E43',
        },
        
        # �11 
        # ��� ������� ����� � ������������� ���������, ������������� �������� �, ������������
        # ���� ������������� �����, ���� ���������� ������ � ����� ���� ��� �������. � ��������� 
        # ������ ������ ����� �������������� ������� ������������� ���� ��������� �������. 
        # ����� �������, ���� ������������ ��������������������� B1, �� ����������� �
        # ��� ����� 32�, 17F � 14D ������� �� ������� ���� 37U � ������������ ��������� ������� 
        # (��� ������ D45):
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                my $b_seq = $seqs->{B}->[0] || undef;
                
                unless (defined $b_seq) {
                    return 1;
                }
                
                my $b1_exists = seq_key_exists($b_seq, '18A');
                unless ($b1_exists) {
                    return 1;
                }
                
                if (seq_key_exists($b_seq, '37U')) {
                    return 0 if (seq_key_exists($b_seq, '32M'));
                    return 0 unless (seq_key_exists($b_seq, '17F'));
                    return 0 unless (seq_key_exists($b_seq, '14D'));
                } else {
                    return 0 unless (seq_key_exists($b_seq, '32M'));
                    return 0 if (seq_key_exists($b_seq, '17F'));
                    return 0 if (seq_key_exists($b_seq, '14D'));
                }
                
                return 1;
            },
            err => 'D45',
        },
        
        # �12 
        # ��� ������� ����� � ������������� ���������, ������������� �������� �, ������������ ���� 
        # ������������� �����, ���� ���������� ������ � ����� ���� ��� �������. � ��������� ������ 
        # ������ ����� �������������� ������� ������������� ���� ��������� �������. ����� �������, 
        # ���� ������������ ��������������������� �1, �� ����������� � ��� ����� 32�, 17F � 14D 
        # ������� �� ������� ���� 37U � ������������ ��������� ������� (��� ������ D59):
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                my $e_seq = $seqs->{E}->[0] || undef;
                
                unless (defined $e_seq) {
                    return 1;
                }
                
                my $e1_exists = seq_key_exists($e_seq, '18A');
                unless ($e1_exists) {
                    return 1;
                }
                
                if (seq_key_exists($e_seq, '37U')) {
                    return 0 if (seq_key_exists($e_seq, '32M'));
                    return 0 unless (seq_key_exists($e_seq, '17F'));
                    return 0 unless (seq_key_exists($e_seq, '14D'));
                } else {
                    return 0 unless (seq_key_exists($e_seq, '32M'));
                    return 0 if (seq_key_exists($e_seq, '17F'));
                    return 0 if (seq_key_exists($e_seq, '14D'));
                }
                
                return 1;
            },
            err => 'D59',
        },
        
        # �13 
        # ���� ���������� ��������� ����������, �� ����������� ������ ���� ������ �����-����������, 
        # � ��������� ��������� ���������� �������� ���������������. ����� �������, � ��������� ���� 
        # ���������� ����� 53�, 56� � 57� � ������������������� L ��� � ��������� ��������� ������� 
        # (��� ������ D48):
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                
                for my $s ('L', 'M') {
                    my $seq = $seqs->{$s}->[0] || [];
                    unless (seq_key_exists($seq, '57[A-Z]')) {
                        return 0 if (seq_key_exists($seq, '53[A-Z]'));
                        return 0 if (seq_key_exists($seq, '56[A-Z]'));
                    }
                }
                
                return 1;
            },
            err => 'D48',
        },
        
        # �14 
        # ���� �B����� ��������� ����� �������������� ������ � ��� �������, ����� ��� ��������
        # ������� ��������� ��� ����������. ����� �������, � ��������� ���� ���������� ����� 56� �
        # 86� ��������� ��������� ������� (��� ������ �35):
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                
                for my $s (keys %$seqs) {
                    my $seq = $seqs->{$s}->[0] || [];
                    unless (seq_key_exists($seq, '56[A-Z]')) {
                        return 0 if (seq_key_exists($seq, '86[A-Z]'));
                    }
                }
                
                return 1;
            },
            err => 'E35',
        },
        
        # �15 
        # ���� ��������� ������������ ��� �������� ��������� ��� ��� ������, � ��� ������ 
        # �������������� ���������� ��������. ����� �������, � ������������������ � ����������� 
        # ���� 21 ������� �� ���������� ���� 22� ��������� ������� (��� ������ D02):
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
        
        # �16 
        # ���� ��������� ������������ ���������� ��������, � ��� ������ ��������������
        # ������������� �������. ���������� � �������� ������� ����������� ������ � ��������������, 
        # ������������ ���������� ��������. ����� �������, ����������� ����� 88� � 71F � 
        # ������������������ N, � ������������� � ���� ������������� ������������������ N, ������� 
        # �� ���������� ���� 94� � ������������ ��������� ������� (��� ������ D74):
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                my $a_seq = $seqs->{A}->[0] || [];
                my $n_seq = $seqs->{N}->[0] || undef;
                my $val_94a = seq_get_first($a_seq, '94A');
                
                if (!defined($val_94a) || $val_94a =~ /AGNT|BILA/) {
                    if (defined $n_seq) {
                       return 0 if (seq_key_exists($n_seq, '71F')); 
                    }
                }
                elsif ($val_94a =~ /BROK/) {
                    return 0 unless (defined $n_seq);
                    return 0 unless (seq_key_exists($n_seq, '88[A-Z]'));
                }
                
                return 1;
            },
            err => 'D74',
        },
        
        # �17 
        # �������� ����������� ����� ������� �10:
        # ��� ���������� � ������ �������� ���������� AFB ����������, ������� ��������������� �
        # �������� ������������ �������� ISDA � AFB�, ����������� � ��������� ����, ������� 
        # ��� ���������� ("Not Defined"), �� ������ �������������� (��� ������ �40).
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                my $a_seq = $seqs->{A}->[0] || [];
                my $val_77h = seq_get_first($a_seq, '77H');
                my $c_seq = $seqs->{C}->[0] || [];
                my $f_seq = $seqs->{F}->[0] || [];
                my $c1_seq = _find_subsequence($c_seq, '14J', '22D|38[GH]');
                my $f1_seq = _find_subsequence($f_seq, '14J', '22D|38[GH]');
                my $c2_exists = seq_key_exists($c_seq, '22D');
                my $f2_exists = seq_key_exists($f_seq, '22D');
                
                if ($val_77h =~ /^\s*AFB/) {
                    return 0 if (seq_key_exists($c1_seq, '14G|37R'));
                    return 0 if (seq_key_exists($f1_seq, '14G|37R'));
                    return 0 if ($c2_exists);
                    return 0 if ($f2_exists);
                }
                
                return 1;
            },
            err => 'E40',
        },
        
        # �18 
        # �������� ����������� ����� ������� �10:
        # ���� ������� ��������� ������ (�Floating Rate Option�, �������� ������������ AFB ��� ISDA)
        # ��������������� ������� ����������� ������ (post determined rate), �� � ������������������ 
        # � ��� E ����������� ������������� ������, � ��������������������� �1, �1, �2, �3, �1, F1, 
        # F2 � F3 �� ������ ��������������. ����� �������, ����������� ���� 37U � ������������������� 
        # � � E, � ����� ����������� ���������������������� �1, �1, �2, �3, �1,F1, F2 � F3 ������� 
        # �� ���������� ������� ������� ���� 77� � �� ���������� ���� 14F � ������������ ��������� 
        # �������: (��� ������ �41):
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                my $a_seq = $seqs->{A}->[0] || [];
                my $b_seq = $seqs->{B}->[0] || [];
                my $c_seq = $seqs->{C}->[0] || [];
                my $e_seq = $seqs->{E}->[0] || [];
                my $f_seq = $seqs->{F}->[0] || [];
                my $val_77h = seq_get_first($a_seq, '77H');
                my $val_c_14f = seq_get_first($c_seq, '14F');
                my $val_f_14f = seq_get_first($f_seq, '14F');
                my $b1_exists = seq_key_exists($b_seq, '18A');
                my $e1_exists = seq_key_exists($e_seq, '18A');
                my $c1_exists = seq_key_exists($c_seq, '14J');
                my $f1_exists = seq_key_exists($f_seq, '14J');
                my $c2_exists = seq_key_exists($c_seq, '22D');
                my $f2_exists = seq_key_exists($f_seq, '22D');
                my $c3_exists = seq_key_exists($c_seq, '38[GH]');
                my $f3_exists = seq_key_exists($f_seq, '38[GH]');
                
                if ($val_77h =~ /^\s*ISDA/) {
                    if (
                        (
                            (defined $seqs->{C}->[0]) &&
                            ($val_c_14f =~ /^\s*FRF-(TAM-CDC|T4M-CDC|T4M-CDCCOMP|TAG-CDC|TAG-CDCCOMP|TMP-CDCAVERAG)\s*$/)
                        ) ||
                        (
                            (defined $seqs->{F}->[0]) &&
                            ($val_f_14f =~ /^\s*FRF-(TAM-CDC|T4M-CDC|T4M-CDCCOMP|TAG-CDC|TAG-CDCCOMP|TMP-CDCAVERAG)\s*$/)
                        )
                    ) {
                        return 0 if (
                            $b1_exists || $c1_exists || $c2_exists || $c3_exists || 
                            $e1_exists || $f1_exists || $f2_exists || $f3_exists
                        );
                        return 0 if ((defined $seqs->{B}->[0]) && !seq_key_exists($b_seq, '37U'));
                        return 0 if ((defined $seqs->{E}->[0]) && !seq_key_exists($e_seq, '37U'));
                    }
                }
                elsif ($val_77h =~ /^\s*AFB/) {
                    if (
                        (
                            (defined $seqs->{C}->[0]) &&
                            ($val_c_14f =~ /^\s*FRF-(SWAP-(AMR|TMP-IF|TMP-M|T4M-AMR)|CAP-TAM|CAP-T4M|FLOOR-TAM|FLOOR-T4M)\s*$/)
                        ) ||
                        (
                            (defined $seqs->{F}->[0]) &&
                            ($val_f_14f =~ /^\s*FRF-(SWAP-(AMR|TMP-IF|TMP-M|T4M-AMR)|CAP-TAM|CAP-T4M|FLOOR-TAM|FLOOR-T4M)\s*$/)
                        )
                    ) {
                        return 0 if (
                            $b1_exists || $c1_exists || $c2_exists || $c3_exists || 
                            $e1_exists || $f1_exists || $f2_exists || $f3_exists
                        );
                        return 0 if ((defined $seqs->{B}->[0]) && !seq_key_exists($b_seq, '37U'));
                        return 0 if ((defined $seqs->{E}->[0]) && !seq_key_exists($e_seq, '37U'));
                    }
                }
                else {
                    return 0 if ((defined $seqs->{B}->[0]) && !$b1_exists);
                    return 0 if ((defined $seqs->{C}->[0]) && !$c1_exists);
                    return 0 if ((defined $seqs->{E}->[0]) && !$e1_exists);
                    return 0 if ((defined $seqs->{F}->[0]) && !$f1_exists);
                }
                
                return 1;
            },
            err => 'E41',
        },
        
        # �19 
        # �� ���� �������������� ������������������� � ����������������������, ���� ����������
        # ������������������ ��� ���������������������, ���� �� �������� � ������ ��������������,
        # ������ �� ������������ (��� ������ �32).
        {
            func => sub {
                my $doc = shift;
                
                my $seqs = _find_sequences($doc->data);
                my $reqs = {
                    'C' => [qw( 14F )],
                    'D' => [qw( 57[AD] )],
                    'F' => [qw( 14F )],
                    'G' => [qw( 57[AD] )],
                    'H' => [qw( 18A 30G 32U 14A 18A 22B )],
                    'I' => [qw( 18A 30G 32U 14A 18A 22B )],
                    'J' => [qw( 18A 22X 30F 32M 57[AD] 14A 18A 22B )],
                    'K' => [qw( 18A 22X 30F 32M 57[AD] 14A 18A 22B )],
                    'L' => [qw( 18A 22E 30F 32M 14A 18A 22B )],
                    'M' => [qw( 18A 22E 30F 32M 14A 18A 22B )],
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
                
                my $b_seq = $seqs->{B}->[0] || [];
                my $c_seq = $seqs->{C}->[0] || [];
                my $e_seq = $seqs->{B}->[0] || [];
                my $f_seq = $seqs->{F}->[0] || [];
                
                # B1
                my $b1_seq = _find_subsequence($b_seq, '18A', '--END--');
                if (scalar(@$b1_seq) > 0) {
                    for my $k (qw( 30F 14A 18A 22B )) {
                        return 0 unless (seq_key_exists($b1_seq, $k));
                    }
                    return 0 if (seq_key_count($b1_seq, '18A') < 2);
                }
                # E1
                my $e1_seq = _find_subsequence($e_seq, '18A', '--END--');
                if (scalar(@$e1_seq) > 0) {
                    for my $k (qw( 30F 14A 18A 22B )) {
                        return 0 unless (seq_key_exists($e1_seq, $k));
                    }
                    return 0 if (seq_key_count($e1_seq, '18A') < 2);
                }
                # C1
                my $c1_seq = _find_subsequence($c_seq, '14J', '22D|38[GH]');
                if (scalar(@$c1_seq) > 0) {
                    for my $k (qw( 14J 38E 18A 30F 17F 14D 14A 18A 22B )) {
                        return 0 unless (seq_key_exists($c1_seq, $k));
                    }
                }
                # F1
                my $f1_seq = _find_subsequence($f_seq, '14J', '22D|38[GH]');
                if (scalar(@$f1_seq) > 0) {
                    for my $k (qw( 14J 38E 18A 30F 17F 14D 14A 18A 22B )) {
                        return 0 unless (seq_key_exists($f1_seq, $k));
                    }
                }
                # C2
                my $c2_seq = _find_subsequence($c_seq, '22D', '38[GH]');
                if (scalar(@$c2_seq) > 0) {
                    for my $k (qw( 22D 18A 30X )) {
                        return 0 unless (seq_key_exists($c2_seq, $k));
                    }
                }
                # F2
                my $f2_seq = _find_subsequence($f_seq, '22D', '38[GH]');
                if (scalar(@$f2_seq) > 0) {
                    for my $k (qw( 22D 18A 30X )) {
                        return 0 unless (seq_key_exists($f2_seq, $k));
                    }
                }
                
                # O1a, O1a1
                my $o_seq = $seqs->{O}->[0] || undef;
                if (defined $o_seq) {
                    my $o_len = scalar(@$o_seq);
                    for my $i (0 .. $o_len-1){
                        if ($o_seq->[$i]->{key} eq '22M') {
                            unless ($o_seq->[$i+1]->{key} eq '22N') {
                                return 0;
                            }
                        }
                        if ($o_seq->[$i]->{key} eq '22P') {
                            unless ($o_seq->[$i+1]->{key} eq '22R') {
                                return 0; 
                            }
                        }
                    }
                }
                
                return 1;
            },
            err => 'C32',
        },
        
        # �20 
        # � ������������������ � ���� 15� �� ����� ���� ������������ �����, �� ����, ���� ������������
        # ���� 15�, �� � ������������������ � ������ �������������� ��� ���� �� ���� ���� 
        # (��� ������ �98).
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                my $o_seq = $seqs->{O}->[0] || undef;
                
                if (defined $o_seq) {
                    return 0 if (scalar @$o_seq < 2);
                }
                
                return 1;
            },
            err => 'C98',
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

sub _find_subsequence {
    my $seq = shift;
    my $from = shift;
    my $to = shift;
    my $sub = [];
    my $started = 0;
    for my $field (@$seq) {
        if ($started) {
            if ($field->{key} =~ /$to/) {
                last;
            } else {
                push @$sub, $field;
            }
        } else {
            if ($field->{key} =~ /$from/) {
                $started = 1;
                push @$sub, $field;
            }
        }
    }
    return $sub;
}


1;