package CyberFT::SWIFT::Types::MT340;
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
            key => '23D',
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
            key => '15B',
            required => 1,
            allow_empty => 1,
        },
        {
            key => '30T',
            required => 1,
        },
        {
            key => '32B',
            required => 1,
        },
        {
            key => '30F',
            required => 1,
        },
        {
            key => '30P',
            required => 1,
        },
        {
            key => '37M',
            required => 1,
        },
        {
            key => '14F',
            required => 1,
        },
        {
            key => '38G',
            required => 1,
        },
        {
            key => '14D',
            required => 1,
        },
        {
            key => '17F',
            required => 1,
        },
        {
            key => '18A',
            required => 1,
        },
        {
            key => '22B',
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
        # �������� ����������� ������������ ����� ������������ �������������������
        {
            func => sub {
                my $doc = shift;
                
                my $seqs = _find_sequences($doc->data);
                my $reqs = {
                    'A' => [qw( 20 22A 22C 23D 82[AD] 87[AD] 77H )],
                    'B' => [qw( 30T 32B 30F 30P 37M 14F 38G 14D 17F 18A 22B )],
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
        
        # �1 
        # � ������������������ � ������������� ���� 21 ������� �� �������� ���� 22� � ������������ 
        # ��������� ������� (��� ������ D02):
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                my $a_seq = $seqs->{A}->[0] || [];
                my $val_22a = seq_get_first($a_seq, '22A');
                
                if ($val_22a =~ /AMND|CANC/) {
                    return 0 unless (seq_key_exists($a_seq, '21'));
                }
                
                return 1;
            },
            err => 'D02',
        },
        
        # �2 
        # � ������������������ � ����������� ���� 14� ������� �� �������� ������� 1 ���� 77� 
        # ��������� ������� (��� ������ �40):
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                my $a_seq = $seqs->{A}->[0] || [];
                my $val_77h = seq_get_first($a_seq, '77H');
                
                if ($val_77h =~ /ISDA/) {
                    return 0 unless (seq_key_exists($a_seq, '14C'));
                } else {
                    return 0 if (seq_key_exists($a_seq, '14C'));
                }
                
                return 1;
            },
            err => 'E40',
        },
        
        # �3 
        # � ������������������ � ����������� ��������������������� �1 ������� �� �������� ������� 1 
        # ���� 77� � ������������������ � ��������� ������� (��� ������ �41):
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                my $a_seq = $seqs->{A}->[0] || [];
                my $b_seq = $seqs->{B}->[0] || [];
                my $val_77h = seq_get_first($a_seq, '77H');
                if ($val_77h =~ /AFB|FRABBA/) {
                    return 0 unless (seq_key_exists($b_seq, '30V') || seq_key_exists($b_seq, '38D'));
                } 
                elsif ($val_77h =~ /DERV|EMA|ISDA|OTHR/) {
                    return 0 if (seq_key_exists($b_seq, '30V') || seq_key_exists($b_seq, '38D'));
                }
                
                return 1;
            },
            err => 'E41',
        },
        
        # �4 
        # ������������� ������������������ � � ������������� ���� 72 � ������������������ � 
        # ������� �� �������� ���� 14F � ������������������ � ��������� ������� (��� ������ D36):
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                my $b_seq = $seqs->{B}->[0] || [];
                my $val_14f = seq_get_first($b_seq, '14F');
                
                if ($val_14f =~ /OTHER/) {
                    my $e_seq = $seqs->{E}->[0] || undef;
                    return 0 unless (defined $e_seq);
                    return 0 unless (seq_key_exists($e_seq, '72'));
                }
                
                return 1;
            },
            err => 'D36',
        },
        
        # �5 
        # ������������� ������������������ � � ������������� ���� 72 � ������������������ � ������� 
        # �� �������� ������� ���������� ������� ������� ���� 38G � ��������������������� �2 
        # ��������� ������� (��� ������ D42):
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                my $b_seq = $seqs->{B}->[0] || [];
                my $val_38g = seq_get_first($b_seq, '38G');
                
                if ($val_38g =~ /^[^\/]*O\//) {
                    my $e_seq = $seqs->{E}->[0] || undef;
                    return 0 unless (defined $e_seq);
                    return 0 unless (seq_key_exists($e_seq, '72'));
                }
                
                return 1;
            },
            err => 'D42',
        },
        
        # �6 
        # ������������� ������������������ � � ������������� ���� 72 � ������������������ � ������� 
        # �� �������� ���� 14D � ��������������������� �2 ��������� ������� (��� ������ D37):
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                my $b_seq = $seqs->{B}->[0] || [];
                my $val_14d = seq_get_first($b_seq, '14D');
                
                if ($val_14d =~ /OTHER/) {
                    my $e_seq = $seqs->{E}->[0] || undef;
                    return 0 unless (defined $e_seq);
                    return 0 unless (seq_key_exists($e_seq, '72'));
                }
                
                return 1;
            },
            err => 'D37',
        },
        
        # �7 
        # ���� � ��������������������� �2 ���� �� ���� �� ���������� ���� 22� �������� ��� OTHR, 
        # �� ������������������ � �������� ������������ � ���� 72 � ������������������ � �������� 
        # ������������; � ��������� ������� (�� ����, ����� �� ���� �� ���������� ���� 22� �� 
        # �������� ��� OTHR) ���� 72 � ������������������ � �������������� (��� ������ D69):
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                my $b_seq = $seqs->{B}->[0] || [];
                my $vals_22b = seq_get_all($b_seq, '22B');
                
                my $req = 0;
                for my $v (@$vals_22b) {
                    if ($v =~ /OTHR/) {
                        $req = 1;
                        last;
                    }
                }
                
                if ($req) {
                    my $e_seq = $seqs->{E}->[0] || undef;
                    return 0 unless (defined $e_seq);
                    return 0 unless (seq_key_exists($e_seq, '72'));
                }
                
                return 1;
            },
            err => 'D69',
        },
        
        # �8 
        # ���� � ������������������� � � D ����������� ���� 56�, �� ���� 86� � ��� �� 
        # ������������������ � ��� D �� ������������, � ��������� ������ ���� 86� �������������� 
        # (��� ������ �35):
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                
                for my $s ('C', 'D', 'F') {
                    my $seq = $seqs->{$s}->[0] || [];
                    unless (seq_key_exists($seq, '56[A-Z]')) {
                        return 0 if (seq_key_exists($seq, '86[A-Z]'));
                    }
                }
                
                return 1;
            },
            err => 'E35',
        },
        
        # �9 
        # ������������� ������������������ � � ����������� ����� 88� � 71F � ������������������ � 
        # ������� �� �������� ���� 94� � ������������������ � ��������� ������� (��� ������ D74):
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                my $a_seq = $seqs->{A}->[0] || [];
                my $val_94a = seq_get_first($a_seq, '94A');
                
                if ($val_94a =~ /BROK/) {
                    my $e_seq = $seqs->{E}->[0] || undef;
                    return 0 unless (defined $e_seq);
                    return 0 unless (seq_key_exists($e_seq, '88[A-Z]'));
                }
                else {
                    my $e_seq = $seqs->{E}->[0] || [];
                    return 0 if (seq_key_exists($e_seq, '71F'));
                }
                
                return 1;
            },
            err => 'D74',
        },
        
        # �10 
        # � ������������������ � ���� 15� �� ����� ���� ������������ �����, �� ����, ���� 
        # ������������ ���� 15�, �� � ������������������ � ������ �������������� ��� ���� �� ���� 
        # ���� (��� ������ �98).
        # � ������������������ G ���� 15G �� ����� ���� ������������ �����, �� ����, ���� 
        # ������������ ���� 15G, �� � ������������������ G ������ �������������� ��� ���� �� ���� 
        # ���� (��� ������ �98).
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                
                for my $s ('E', 'G') {
                    my $seq = $seqs->{$s}->[0] || undef;
                    if (defined $seq && scalar(@$seq) < 2) {
                        return 0;
                    }
                }
                
                return 1;
            },
            err => 'C98',
        },
        
        # �11 
        # �� ���� �������������� ������������������� � ����������������������, ���� ���������� 
        # ������������������ ��� ���������������������, ���� �� �������� � ������ ��������������, 
        # ������ �� ������������ (��� ������ �32).
        {
            func => sub {
                my $doc = shift;
                
                my $seqs = _find_sequences($doc->data);
                my $reqs = {
                    'F' => [qw( 18A 30F 32H 57[ADJ] )],
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
                
                # G1a, G1a1
                my $g_seq = $seqs->{G}->[0] || undef;
                if (defined $g_seq) {
                    my $g_len = scalar(@$g_seq);
                    for my $i (0 .. $g_len-1){
                        if ($g_seq->[$i]->{key} eq '22M') {
                            unless ($g_seq->[$i+1]->{key} eq '22N') {
                                return 0;
                            }
                        }
                        if ($g_seq->[$i]->{key} eq '22P') {
                            unless ($g_seq->[$i+1]->{key} eq '22R') {
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