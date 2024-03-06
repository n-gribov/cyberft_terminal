package CyberFT::SWIFT::Types::MT305;
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
        
        # �������� ����������� ������������ ����� ������������������ A
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
        
        # �1 
        # ���� 31C ����� �������������� ������ � ��� �������, ����� � ���� 23 ������ ������ 
        # ������������� ���� (��� ������ C79).
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
        
        # �2 
        # ��� ������ � ��������� ������� (�������) ���� 23 ������ ��������� � ����� ������ � ���� 
        # 32B (��� ������ C88).
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
        
        # C3 � �����, ��������� ����, ���� XAU, XAG, XPD � XPT �� ������������, ��������� ��� ���� 
        # ��������� � �������, ��� ������� ������ �������������� 6 �������� ��������� (��� ������ C08):
        # 32B �������� ������ � �����, 33B ������ � ����� ������, 34A ������ ������
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
        
        # �4 
        # ���� � ������������������ � ������������ ���� 72, � ���� ������ 6 ������ ������ ������ 
        # ����� ���� ����� �������� /VALD/, �� ��������� 8 ������ ������ ��������� ����������� ����, 
        # ���������� � ������� YYYYMMDD (���, �����, ����), �� ������� ������ ��������� ������� 
        # ��������� ������ CrLf (��� ������ �58).
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
        
        # �5 
        # ���� � ������������������ � ������������ ���� 72, ��:
        # ���� ������ ����� ������ ������ ������ ����� ���� ����� �������� /VALD/, �� ������ 
        # �������������� ������ ������� � ���������� � ���� /SETC/, � �� ��� ������� ��������� ��� 
        # ������ (ISO 4217) � ������� ��������� ������ (/SWTC/currencyCrLf) (��� ������ �59).
        # ���� ������ ����� ������ ������ ������ ����� ���� ����� �������� /SETC/, �� ������ ����� 
        # ������ ������ ������ ������ ��������� ��� /VALD/ (��� ������ C59).
        # ��� /SETC/ ������������ ������ � ������ ����� ������ ������ ������ (��� ������ C59).
        # ���� ������ ����� ������ ������� ������ ����� ���� ����� �������� /SRCE/, �� ������ ����� 
        # ������ ������ ������ ������ ��������� ��� /SETC/ (��� ������ C59).
        # ��� / SRCE / ������������ ������ � ������ ����� ������ ������� ������ (��� ������ C59).
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
        
        # �6 
        # �� ���� �������������� �������������������, ���� ���������� ������������������, ���� �� 
        # �������� � ������ ��������������, ������ �� ������������ (��� ������ �32).
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                
                # �������� B1a � B1a1 (����� 22M ������ ���� 22N; ����� 22P ������ ���� 22R)
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