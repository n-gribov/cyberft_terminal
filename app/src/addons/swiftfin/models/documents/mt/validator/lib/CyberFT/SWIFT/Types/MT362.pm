package CyberFT::SWIFT::Types::MT362;
use CyberFT::SWIFT::Types::Utils;
use strict;

our $ValidationProfile = {

    fields => [
        # ������������ ������������������ A ������ �����������
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
            required => 0,
        },
        {
            key => '22A',
            required => 1,
        },
        {
            key => '94A',
            required => 0,
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
            key => '21B',
            required => 0,
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
            key => '83a',
            key_regexp => '83[ADJ]',
            required => 0,
        },
        {
            key => '29A',
            required => 0,
        },
        {
            key => '72',
            required => 0,
        },
        # ��������� ������������������ � ����� ����������
        # �������������� ������������������ � ������������� �������, ������������� �������� �
        {
            key => '15B',
            required => 0, # ��� ���� �����������, ���� ������ ������������������ ������������.
            allow_empty => 1,
        },
        {
            key => '33F',
            required => 0, # ��� ���� �����������, ���� ������ ������������������ ������������.
        },
        {
            key => '30X',
            required => 0, # ��� ���� �����������, ���� ������ ������������������ ������������.
        },
        {
            key => '30Q',
            required => 0,
        },
        {
            key => '37G',
            required => 0, # ��� ���� �����������, ���� ������ ������������������ ������������.
        },
        {
            key => '37J',
            required => 0,
        },
        {
            key => '37L',
            required => 0,
        },
        {
            key => '37R',
            required => 0, # ��� ���� �����������, ���� ������ ������������������ ������������.
        },
        {
            key => '37M',
            required => 0, # ��� ���� �����������, ���� ������ ������������������ ������������.
        },
        {
            key => '30F',
            required => 0, # ��� ���� �����������, ���� ������ ������������������ ������������.
        },
        {
            key => '32H',
            required => 0,
        },
        {
            key => '33E',
            required => 0,
        },
        {
            key => '37N',
            required => 0,
        },
        # ��������� ������������������ � ���������� ������/�������� �����, ������������� �������� �
        # �������������� ������������������ � (������) �����, ������������� �������� B
        {
            key => '15C',
            required => 0, # ��� ���� �����������, ���� ������ ������������������ ������������.
            allow_empty => 1,
        },
        {
            key => '18A',
            required => 0, # ��� ���� �����������, ���� ������ ������������������ ������������.
        },
        {
            key => '30F',
            required => 0, # ��� ���� �����������, ���� ������ ������������������ ������������.
        },
        {
            key => '32M',
            required => 0, # ��� ���� �����������, ���� ������ ������������������ ������������.
        },
        {
            key => '53a',
            key_regexp => '53[AD]',
            required => 0,
        },
        {
            key => '56a',
            key_regexp => '56[AD]',
            required => 0,
        },
        {
            key => '86a',
            key_regexp => '86[AD]',
            required => 0,
        },
        {
            key => '57a',
            key_regexp => '57[AD]',
            required => 0, # ��� ���� �����������, ���� ������ ������������������ ������������.
        },
        # ��������� ������������������ � (������) �����, ������������� �������� B
        # �������������� ������������������ D ���������� ������/�������� �����, ������������� �������� �
        {
            key => '15D',
            required => 0, # ��� ���� �����������, ���� ������ ������������������ ������������.
            allow_empty => 1,
        },
        {
            key => '33F',
            required => 0, # ��� ���� �����������, ���� ������ ������������������ ������������.
        },
        {
            key => '30X',
            required => 0, # ��� ���� �����������, ���� ������ ������������������ ������������.
        },
        {
            key => '30Q',
            required => 0,
        },
        {
            key => '37G',
            required => 0, # ��� ���� �����������, ���� ������ ������������������ ������������.
        },
        {
            key => '37J',
            required => 0,
        },
        {
            key => '37L',
            required => 0,
        },
        {
            key => '37R',
            required => 0, # ��� ���� �����������, ���� ������ ������������������ ������������.
        },
        {
            key => '37M',
            required => 0, # ��� ���� �����������, ���� ������ ������������������ ������������.
        },
        {
            key => '30F',
            required => 0, # ��� ���� �����������, ���� ������ ������������������ ������������.
        },
        {
            key => '32H',
            required => 0,
        },
        {
            key => '33E',
            required => 0,
        },
        {
            key => '37N',
            required => 0,
        },
        # ��������� ������������������ D ���������� ������/�������� �����, ������������� �������� �
        # �������������� ������������������ � (������) �����, ������������� �������� �
        {
            key => '15E',
            required => 0, # ��� ���� �����������, ���� ������ ������������������ ������������.
            allow_empty => 1,
        },
        {
            key => '18A',
            required => 0, # ��� ���� �����������, ���� ������ ������������������ ������������.
        },
        {
            key => '30F',
            required => 0, # ��� ���� �����������, ���� ������ ������������������ ������������.
        },
        {
            key => '32M',
            required => 0, # ��� ���� �����������, ���� ������ ������������������ ������������.
        },
        {
            key => '53a',
            key_regexp => '53[AD]',
            required => 0,
        },
        {
            key => '56a',
            key_regexp => '56[AD]',
            required => 0,
        },
        {
            key => '86a',
            key_regexp => '86[AD]',
            required => 0,
        },
        {
            key => '57a',
            key_regexp => '57[AD]',
            required => 0, # ��� ���� �����������, ���� ������ ������������������ ������������.
        },
        # ��������� ������������������ � (������) �����, ������������� �������� �
    ],

    rules => [
        # �1. � ��������� ������ �������������� ���� �� ���� �� ������������������� � ��� D (��� ������ �47):
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                my $b = defined($seqs->{B});
                my $d = defined($seqs->{D});
                return 0 unless ($b || $d);
                return 1;
            },
            err => 'E47',
        },
        # �2. � ��������� ������ �������������� ���� �� ���� �� ������������������� � ��� � (��� ������ �48):
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                my $c = defined($seqs->{C});
                my $e = defined($seqs->{E});
                return 0 unless ($c || $e);
                return 1;
            },
            err => 'E48',
        },
        # �3. ���� ������� �������� �� ������ ������� ���� 23� ��������� ��� �NET�, �� � ���������
        # ������ �������������� ���� ������������������ �, ���� ������������������ �, �� �� ��� ���
        # ������������������ ������ (��� ������ �49).
        # ����������: ���� ������� �������� ��������� ��� �NET�, �� ��� ������� ����� ���������
        # ����� �������� �2.
        {
            func => sub {
                my $doc = shift;
                my $v32a = $doc->get_first('23A');
                if ($v32a =~ /\/NET/) {
                    my $seqs = _find_sequences($doc->data);
                    my $c = defined($seqs->{C});
                    my $e = defined($seqs->{E});
                    return 0 if ($c && $e);
                    return 0 unless ($c || $e);
                }
                return 1;
            },
            err => 'E49',
        },
        # �4. ���� ������� �������� �� ������ ������� ���� 23� ��������� ��� �NET�, �� ���� ���������
        # ���������� (�� ���� ���� 30F --- 57�) � ������������������ � ��� � ����� ��������������
        # ������ ���� ��� (��� ������ �50).
        # ��������������, ���� 18� � ������������������ � ��� � ������ ����� �������� �1� - ��. � 30
        # ��� � 51, �������� ���� 18�, ������������ ����� ������� (��� ������ D96).
        {
            func => sub {
                my $doc = shift;
                my $v32a = $doc->get_first('23A');
                if ($v32a =~ /\/NET/) {
                    my $seqs = _find_sequences($doc->data);
                    for my $s ('C', 'E') {
                        if (defined $seqs->{$s}) {
                            return 0 if (seq_key_count($seqs->{$s}, '30F') != 1);
                            return 0 if (seq_key_count($seqs->{$s}, '32M') != 1);
                            return 0 if (seq_key_count($seqs->{$s}, '53[AD]') != 1);
                            return 0 if (seq_key_count($seqs->{$s}, '56[AD]') != 1);
                            return 0 if (seq_key_count($seqs->{$s}, '86[AD]') != 1);
                            return 0 if (seq_key_count($seqs->{$s}, '57[AD]') != 1);
                        }
                    }
                }
                return 1;
            },
            err => 'E50',
        },
        {
            func => sub {
                my $doc = shift;
                my $v32a = $doc->get_first('23A');
                if ($v32a =~ /\/NET/) {
                    my $seqs = _find_sequences($doc->data);
                    for my $s ('C', 'E') {
                        if (defined $seqs->{$s}) {
                            my $v18a = seq_get_first($seqs->{$s}, '18A');
                            return 0 if ($v18a != 1);
                        }
                    }
                }
                return 1;
            },
            err => 'D96',
        },
        # �5. ���� ������� �������� �� ������ ������� ���� 23� ��������� ��� �GROSS�, �� ����
        # ��������� ���������� (�� ���� ���� 30F --- 57�) � ������������������ � ��� � �� �����
        # �������������� ����� ���� ��� (��� ������ �51).
        # ��������������, ���� 18� � ������������������ � ��� � ������ ����� �������� �������, ��� 4
        # - ��. � 30 ��� � 51, �������� ���� 18�, ������������ ����� ������� (��� ������ D96).
        {
            func => sub {
                my $doc = shift;
                my $v32a = $doc->get_first('23A');
                if ($v32a =~ /\/GROSS/) {
                    my $seqs = _find_sequences($doc->data);
                    for my $s ('C', 'E') {
                        if (defined $seqs->{$s}) {
                            return 0 if (seq_key_count($seqs->{$s}, '30F') > 3);
                            return 0 if (seq_key_count($seqs->{$s}, '32M') > 3);
                            return 0 if (seq_key_count($seqs->{$s}, '53[AD]') > 3);
                            return 0 if (seq_key_count($seqs->{$s}, '56[AD]') > 3);
                            return 0 if (seq_key_count($seqs->{$s}, '86[AD]') > 3);
                            return 0 if (seq_key_count($seqs->{$s}, '57[AD]') > 3);
                        }
                    }
                }
                return 1;
            },
            err => 'E51',
        },
        {
            func => sub {
                my $doc = shift;
                my $v32a = $doc->get_first('23A');
                if ($v32a =~ /\/GROSS/) {
                    my $seqs = _find_sequences($doc->data);
                    for my $s ('C', 'E') {
                        if (defined $seqs->{$s}) {
                            my $v18a = seq_get_first($seqs->{$s}, '18A');
                            return 0 if ($v18a > 3);
                        }
                    }
                }
                return 1;
            },
            err => 'D96',
        },
        # �6. ��� ������ � ����� 33F � 32� ������������������ � ������ ���� ����������. ��� ������ �
        # ����� 33F � 32� ������������������ D ������ ���� ���������� (��� ������ �38).
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                for my $s ('B', 'D') {
                    if (defined $seqs->{$s}) {
                        if (seq_key_exists($seqs->{$s}, '33F') && seq_key_exists($seqs->{$s}, '32H')) {
                            my ($cur33f) = seq_get_first($seqs->{$s}, '33F') =~ /.*([A-Z]{3})/;
                            my ($cur32h) = seq_get_first($seqs->{$s}, '32H') =~ /.*([A-Z]{3})/;
                            return 0 if ($cur33f ne $cur32h);
                        }
                    }
                }
                return 1;
            },
            err => 'E38',
        },
        # �7. ���� �B����� ��������� ������������ ������ � ��� �������, ����� ��� �������� �������
        # ��������� ��� ����������. ����� �������, � ��������� ���� ���������� ����� 56� � 86�
        # ��������� ��������� ������� (��� ������ �35).
        # ���� � �����-���� ������������������ ���� 56� ... �� � ��� �� ������������������ ���� 86� ...
        # ������������      ��������������
        # �����������       �� ������������
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                for my $s ('C', 'E') {
                    if (defined $seqs->{$s}) {
                        my $seq = $seqs->{$s};
                        my $exists56 = 0;
                        my $exists86 = 0;
                        for my $item (@$seq) {
                            if ($item->{key} =~ /^56[AD]$/) {
                                $exists56 = 1;
                            }
                            if ($item->{key} =~ /^86[AD]$/) {
                                $exists86 = 1;
                            }
                            if ($item->{key} =~ /^57[AD]$/) {
                                return 0 if (!$exists56 && $exists86);
                                $exists56 = 0;
                                $exists86 = 0;
                            }
                        }
                        return 0 if (!$exists56 && $exists86);
                    }
                }
                return 1;
            },
            err => 'E35',
        },
        # �8. � ���������� � ����������� ��� ������� ������ �������������� ���������� ��������. �� ����,
        # � ������������������ � ����������� ���� 21 ��������� ������� ������� �� ���������� ���� 22�
        # (��� ������ D02):
        # ������������������ � ���� ���� 22� ... ������������������ � �� ���� 21 ...
        # AMND      ������������
        # CANC      ������������
        # DUPL      ��������������
        # NEWT      ��������������
        {
            if => ['match', '22A', '^AMND\s*$'],
            must => ['exists', '21'],
            err => 'D02',
        },
        {
            if => ['match', '22A', '^CANC\s*$'],
            must => ['exists', '21'],
            err => 'D02',
        },

        # �10. �� ���� �������������� �������������������, ���� ���������� ������������������, ����
        # �� �������� � ������ ��������������, ������ �� ������������ (��� ������ �32).
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                my $seq;

                $seq = $seqs->{'B'};
                if ($seq) {
                    for my $k (qw( 33F 30X 37G 37R 37M 30F )) {
                        return 0 unless seq_key_exists($seq, $k);
                    }
                }

                $seq = $seqs->{'C'};
                if ($seq) {
                    for my $k (qw( 18A 30F 32M 57[AD] )) {
                        return 0 unless seq_key_exists($seq, $k);
                    }
                }

                $seq = $seqs->{'D'};
                if ($seq) {
                    for my $k (qw( 33F 30X 37G 37R 37M 30F )) {
                        return 0 unless seq_key_exists($seq, $k);
                    }
                }

                $seq = $seqs->{'E'};
                if ($seq) {
                    for my $k (qw( 18A 30F 32M 57[AD] )) {
                        return 0 unless seq_key_exists($seq, $k);
                    }
                }

                return 1;
            },
            err => 'C32',
        },
    ]
};

# ����������� ��� ������������������ ��� ��� ��������.
sub _find_sequences {
    my $data = shift;

    my $marks = {
        '15A' => 'A',
        '15B' => 'B',
        '15C' => 'C',
        '15D' => 'D',
        '15E' => 'E',
    };

    my $cur_seq = undef;
    my $seqs = {};

    for my $item (@$data) {
        my $k = $item->{key};
        if (defined $marks->{$k}) {
            $cur_seq = $marks->{$k};
        }
        if ($cur_seq) {
            push @{$seqs->{$cur_seq}}, $item;
        }
    }

    return $seqs;
}

1;