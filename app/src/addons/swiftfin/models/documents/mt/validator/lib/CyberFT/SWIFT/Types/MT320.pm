package CyberFT::SWIFT::Types::MT320;
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
            key => '22B',
            required => 1,
        },
        {
            key => '22C',
            required => 1,
        },
        {
            key => '21N',
            required => 0,
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
            key => '83a',
            key_regexp => '83[ADJ]',
            required => 0,
        },
        {
            key => '77D',
            required => 0,
        },
        # ��������� ������������������ � ������ �����������
        # ������������ ������������������ B ������� ��������
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
            key => '30P',
            required => 1,
        },
        {
            key => '32B',
            required => 1,
        },
        {
            key => '32H',
            required => 0,
        },
        {
            key => '30X',
            required => 0,
        },
        {
            key => '34E',
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
            key => '30F',
            required => 0,
        },
        {
            key => '38J',
            required => 0,
        },
        # ��������� ������������������ B ������� ��������
        # ������������ ������������������ � ���������� ���������� ��� ����, ������������� �������� ��
        {
            key => '15C',
            required => 1,
            allow_empty => 1,
        },
        {
            key => '53a',
            key_regexp => '53[ADJ]',
            required => 0,
        },
        {
            key => '86a',
            key_regexp => '86[ADJ]',
            required => 0,
        },
        {
            key => '56a',
            key_regexp => '56[ADJ]',
            required => 0,
        },
        {
            key => '57a',
            key_regexp => '57[ADJ]',
            required => 1,
        },
        {
            key => '58a',
            key_regexp => '58[ADJ]',
            required => 0,
        },
        # ��������� ������������������ � ���������� ���������� ��� ����, ������������� �������� ��
        # ������������ ������������������ D ���������� ���������� ��� ����, ������������� �������� »
        {
            key => '15D',
            required => 1,
            allow_empty => 1,
        },
        {
            key => '53a',
            key_regexp => '53[ADJ]',
            required => 0,
        },
        {
            key => '86a',
            key_regexp => '86[ADJ]',
            required => 0,
        },
        {
            key => '56a',
            key_regexp => '56[ADJ]',
            required => 0,
        },
        {
            key => '57a',
            key_regexp => '57[ADJ]',
            required => 1,
        },
        {
            key => '58a',
            key_regexp => '58[ADJ]',
            required => 0,
        },
        # ��������� ������������������ D ���������� ���������� ��� ����, ������������� �������� »
        # �������������� ������������������ � ���������� ���������� ��� ���������, ������������� �������� ��
        {
            key => '15E',
            required => 0, # ��� ���� �����������, ���� ������ ������������������ ������������.
            allow_empty => 1,
        },
        {
            key => '53a',
            key_regexp => '53[ADJ]',
            required => 0,
        },
        {
            key => '86a',
            key_regexp => '86[ADJ]',
            required => 0,
        },
        {
            key => '56a',
            key_regexp => '56[ADJ]',
            required => 0,
        },
        {
            key => '57a',
            key_regexp => '57[ADJ]',
            required => 0, # ��� ���� �����������, ���� ������ ������������������ ������������.
        },
        {
            key => '58a',
            key_regexp => '58[ADJ]',
            required => 0,
        },
        # ��������� ������������������ E ���������� ���������� ��� ���������, ������������� �������� ��
        # �������������� ������������������ F ���������� ���������� ��� ���������, ������������� �������� ��
        {
            key => '15F',
            required => 0, # ��� ���� �����������, ���� ������ ������������������ ������������.
            allow_empty => 1,
        },
        {
            key => '53a',
            key_regexp => '53[ADJ]',
            required => 0,
        },
        {
            key => '86a',
            key_regexp => '86[ADJ]',
            required => 0,
        },
        {
            key => '56a',
            key_regexp => '56[ADJ]',
            required => 0,
        },
        {
            key => '57a',
            key_regexp => '57[ADJ]',
            required => 0, # ��� ���� �����������, ���� ������ ������������������ ������������.
        },
        {
            key => '58a',
            key_regexp => '58[ADJ]',
            required => 0,
        },
        # ��������� ������������������ F ���������� ���������� ��� ���������, ������������� �������� ��
        # �������������� ������������������ G ���������� �����������
        {
            key => '15G',
            required => 0, # ��� ���� �����������, ���� ������ ������������������ ������������.
            allow_empty => 1,
        },
        {
            key => '37L',
            required => 0, # ��� ���� �����������, ���� ������ ������������������ ������������.
        },
        {
            key => '33B',
            required => 0, # ��� ���� �����������, ���� ������ ������������������ ������������.
        },
        {
            key => '36',
            required => 0,
        },
        {
            key => '33E',
            required => 0,
        },
        # ��������� ������������������ G ���������� �����������
        # �������������� ������������������ H ��������������� �����������
        {
            key => '15H',
            required => 0, # ��� ���� �����������, ���� ������ ������������������ ������������.
            allow_empty => 1,
        },
        {
            key => '29A',
            required => 0,
        },
        {
            key => '24D',
            required => 0,
        },
        {
            key => '84a',
            key_regexp => '84[ABDJ]',
            required => 0,
        },
        {
            key => '85a',
            key_regexp => '85[ADJ]',
            required => 0,
        },
        {
            key => '88a',
            key_regexp => '88[ABDJ]',
            required => 0,
        },
        {
            key => '71F',
            required => 0,
        },
        {
            key => '26H',
            required => 0,
        },
        {
            key => '21G',
            required => 0,
        },
        {
            key => '72',
            required => 0,
        },
        # ��������� ������������������ H ��������������� �����������
        # �������������� ������������������ I ��������������� ������
        {
            key => '15I',
            required => 0, # ��� ���� �����������, ���� ������ ������������������ ������������.
            allow_empty => 1,
        },
        {
            key => '18A',
            required => 0, # ��� ���� �����������, ���� ������ ������������������ ������������.
        },
            # --->
        {
            key => '30F',
            required => 0, # ��� ���� �����������, ���� ������ ������������������ ������������.
        },
        {
            key => '32H',
            required => 0, # ��� ���� �����������, ���� ������ ������������������ ������������.
        },
            # ---|
        {
            key => '53a',
            key_regexp => '53[ADJ]',
            required => 0,
        },
        {
            key => '86a',
            key_regexp => '86[ADJ]',
            required => 0,
        },
        {
            key => '56a',
            key_regexp => '56[ADJ]',
            required => 0,
        },
        {
            key => '57a',
            key_regexp => '57[ADJ]',
            required => 0, # ��� ���� �����������, ���� ������ ������������������ ������������.
        },
    ],

    rules => [
        # ������������ ���� � ������������ �������������������
        {
            func => sub {
                my $doc = shift;
                my $seq_a = _find_sequences($doc->data)->{'A'};
                if ($seq_a) {
                    for my $k (qw(20 22A 22B 22C 82[ADJ] 87[ADJ])) {
                        return 0 unless seq_key_exists($seq_a, $k);
                    }
                }
                return 1;
            },
            err => 'Missing required field in A sequence',
        },
        {
            func => sub {
                my $doc = shift;
                my $seq_b = _find_sequences($doc->data)->{'B'};
                if ($seq_b) {
                    for my $k (qw(17R 30T 30V 30P 32B 34E 37G 14D)) {
                        return 0 unless seq_key_exists($seq_b, $k);
                    }
                }
                return 1;
            },
            err => 'Missing required field in B sequence',
        },
        {
            func => sub {
                my $doc = shift;
                my $seq_c = _find_sequences($doc->data)->{'C'};
                if ($seq_c) {
                    for my $k (qw(57[ADJ])) {
                        return 0 unless seq_key_exists($seq_c, $k);
                    }
                }
                return 1;
            },
            err => 'Missing required field in C sequence',
        },
        {
            func => sub {
                my $doc = shift;
                my $seq_d = _find_sequences($doc->data)->{'D'};
                if ($seq_d) {
                    for my $k (qw(57[ADJ])) {
                        return 0 unless seq_key_exists($seq_d, $k);
                    }
                }
                return 1;
            },
            err => 'Missing required field in D sequence',
        },
        # ������������ ���� � �������������� �������������������
        {
            func => sub {
                my $doc = shift;
                my $seq_e = _find_sequences($doc->data)->{'E'};
                if ($seq_e) {
                    for my $k (qw(57[ADJ])) {
                        return 0 unless seq_key_exists($seq_e, $k);
                    }
                }
                return 1;
            },
            err => 'C32',
        },
        {
            func => sub {
                my $doc = shift;
                my $seq_f = _find_sequences($doc->data)->{'F'};
                if ($seq_f) {
                    for my $k (qw(57[ADJ])) {
                        return 0 unless seq_key_exists($seq_f, $k);
                    }
                }
                return 1;
            },
            err => 'C32',
        },
        {
            func => sub {
                my $doc = shift;
                my $seq_g = _find_sequences($doc->data)->{'G'};
                if ($seq_g) {
                    for my $k (qw(37L 33B)) {
                        return 0 unless seq_key_exists($seq_g, $k);
                    }
                }
                return 1;
            },
            err => 'C32',
        },
        {
            func => sub {
                my $doc = shift;
                my $seq_i = _find_sequences($doc->data)->{'I'};
                if ($seq_i) {
                    for my $k (qw(18A 30F 32H 57[ADJ])) {
                        return 0 unless seq_key_exists($seq_i, $k);
                    }
                }
                return 1;
            },
            err => 'C32',
        },

        # C1. � ������������������ � ����������� ���� 21 ������� �� �������� ����� 22� � 22� � ������������ ��������� ������� (��� ������ D70):
        # ������������������ � ���� ���� 22B ... ������������������ � � ���� ���� 22� ... ������������������ � �� ���� 21 ...
        # CONF              NEWT                ��������������
        # CONF              ������� �� NEWT     ������������
        # ������� �� CONF   ����� ��������      ������������
        {
            func => sub {
                my $doc = shift;
                my $seq_a = _find_sequences($doc->data)->{'A'} || [];
                if (
                    seq_key_value_exists($seq_a, '22B', '^CONF\s*$')
                    && !seq_key_value_exists($seq_a, '22A', '^NEWT\s*$')
                ) {
                    return 0 unless seq_key_exists($seq_a, '21');
                }
                if (!seq_key_value_exists($seq_a, '22B', '^CONF\s*$')) {
                    return 0 unless seq_key_exists($seq_a, '21');
                }
                return 1;
            },
            err => 'D70',
        },

        # C2. ���� � ������������������ � ������������ ���� 94� � ����� AGNT, �� ���� 21N �
        # ������������������ � �������� ������������, � ��������� ������� ���� 21N ��������������
        # (��� ������ D72).
        {
            func => sub {
                my $doc = shift;
                my $seq_a = _find_sequences($doc->data)->{'A'} || [];
                if (seq_key_value_exists($seq_a, '94A', '^AGNT\s*$')) {
                    return 0 unless seq_key_exists($seq_a, '21N');
                }
                return 1;
            },
            err => 'D72',
        },

        # C3. � ������������������ � ����������� ����� 32� � 30� ������� �� �������� ���� 22� �
        # ������������������ � � ������������ ��������� ������� (��� ������ D56):
        # ������������������ � ���� ���� 22� ... ������������������ � �� ���� 32� ... ������������������ � � ���� 30� ...
        # CONF      �� ������������     ������������
        # MATU      ������������        �� ������������
        # ROLL      ������������        ������������
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                my $seq_a = $seqs->{'A'} || [];
                my $seq_b = $seqs->{'B'} || [];
                if (seq_key_value_exists($seq_a, '22B', '^CONF\s*$')) {
                    return 0 if seq_key_exists($seq_b, '32H');
                    return 0 unless seq_key_exists($seq_b, '30X');
                }
                elsif (seq_key_value_exists($seq_a, '22B', '^MATU\s*$')) {
                    return 0 unless seq_key_exists($seq_b, '32H');
                    return 0 if seq_key_exists($seq_b, '30X');
                }
                elsif (seq_key_value_exists($seq_a, '22B', '^ROLL\s*$')) {
                    return 0 unless seq_key_exists($seq_b, '32H');
                    return 0 unless seq_key_exists($seq_b, '30X');
                }
                return 1;
            },
            err => 'D56',
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
        '15F' => 'F',
        '15G' => 'G',
        '15H' => 'H',
        '15I' => 'I',
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