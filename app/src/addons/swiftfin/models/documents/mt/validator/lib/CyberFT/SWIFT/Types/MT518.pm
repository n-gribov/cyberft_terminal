package CyberFT::SWIFT::Types::MT518;
use CyberFT::SWIFT::Types::Utils;
use strict;
use Storable ();
use Data::Dumper;

our $ValidationProfile = {

    fields => [
        # ������������ ������������������ � ����� ����������
        {
            key => '16R', # = GENL
            required => 1,
        },
        {
            key => '20C', # :SEME
            required => 1,
        },
        {
            key => '23G',
            required => 1,
        },
        {
            key => '98a', # :PREP
            key_regexp => '98[ACE]',
            required => 0,
        },
        {
            key => '22F', # :TRTR
            required => 1,
        },
        # --> �������������� ������������� ��������������������� �1 ������
        {
            key => '16R', # = LINK
            required => 0,
        },
        {
            key => '13a',
            key_regexp => '13[AB]',
            required => 0,
        },
        {
            key => '20C',
            required => 0,
        },
        {
            key => '16S', # = LINK
            required => 0,
        },
        # --| ����� ������������ ��������������������� �1 ������
        {
            key => '16S', # = GENL
            required => 1,
        },
        # ����� ������������������ � ����� ����������
        # ������������ ������������������ � ������ �������������
        {
            key => '16R', # = CONFDET
            required => 1,
        },
        {
            key => '98a',
            key_regexp => '98[ABCE]',
            required => 1,
        },
        # -->
        {
            key => '90a', # :DEAL
            key_regexp => '90[AB]',
            required => 1,
        },
        # --|
        {
            key => '92A',
            required => 0,
        },
        {
            key => '99A',
            required => 0,
        },
        {
            key => '94a',
            key_regexp => '94[BCF]',
            required => 0,
        },
        {
            key => '19A',
            required => 0,
        },
        {
            key => '22a',
            key_regexp => '22[FH]',
            required => 1,
        },
        {
            key => '11A',
            required => 0,
        },
        # ������������ ������������� ��������������������� �1 �������������� ������� ������
        {
            key => '16R', # = CONFPRTY
            required => 1,
        },
        {
            key => '95a',
            key_regexp => '95[PRQS]',
            required => 1,
        },
        {
            key => '97a',
            key_regexp => '97[ABE]',
            required => 0,
        },
        {
            key => '98a',
            key_regexp => '98[AC]',
            required => 0,
        },
        {
            key => '20C',
            required => 0,
        },
        {
            key => '70a',
            key_regexp => '70[CE]',
            required => 0,
        },
        {
            key => '22F',
            required => 0,
        },
        {
            key => '16S', # = CONFPRTY
            required => 1,
        },
        {
            key => '36B', # :CONF
            required => 1,
        },
        {
            key => '35B',
            required => 1,
        },
        # �������������� ��������������������� �2 �������� (��������������) ����������� �����������
        {
            key => '16R', # =FIA [M]
            required => 0,
        },
        {
            key => '94B',
            required => 0,
        },
        {
            key => '22F',
            required => 0,
        },
        {
            key => '12a',
            key_regexp => '12[ABC]',
            required => 0,
        },
        {
            key => '11A', # :DENO
            required => 0,
        },
        {
            key => '98A',
            required => 0,
        },
        {
            key => '92A',
            required => 0,
        },
        {
            key => '13a',
            key_regexp => '13[AB]',
            required => 0,
        },
        {
            key => '17B',
            required => 0,
        },
        {
            key => '90a',
            key_regexp => '90[AB]',
            required => 0,
        },
        {
            key => '36B',
            required => 0,
        },
        {
            key => '35B',
            required => 0,
        },
        {
            key => '70E', #:FIAN
            required => 0,
        },
        {
            key => '16S', # = FIA
            required => 0,
        },
        # ����� �������������� ��������������������� �2 �������� (��������������) ����������� �����������
        {
            key => '13B', # :CERT
            required => 0,
        },
        {
            key => '70E',
            required => 0,
        },
        {
            key => '16S', # = CONFDET
            required => 1,
        },
        # ����� ������������������ � ������ �������������
        # �������������� ������������������ � ������ ��������
        {
            key => '16R', # = SETDET
            required => 0, # M
        },
        {
            key => '22F', # M
            required => 0,
        },
        {
            key => '11A',
            required => 0,
        },
        # �������������� ������������� ��������������������� �1 ������� ��� ��������
        {
            key => '16R', # = SETPRTY
            required => 0, # M
        },
        {
            key => '95a',
            key_regexp => '95[CPQRS]',
            required => 0, # M
        },
        {
            key => '97a', # :SAFE
            key_regexp => '97[AB]',
            required => 0,
        },
        {
            key => '98a', # :PROC
            key_regexp => '98[AC]',
            required => 0,
        },
        {
            key => '20C', # :PROC
            required => 0,
        },
        {
            key => '70a',
            key_regexp => '70[CD]',
            required => 0,
        },
        {
            key => '16S', # = SETPRTY
            required => 0, # M
        },
        # ����� �������������� ��������������������� �1 ������� ��� ��������
        # �������������� ������������� ��������������������� �2 ������� ��� �������� ��������
        {
            key => '16R', # = CSHPRTY
            required => 0, # M
        },
         {
            key => '95a',
            key_regexp => '95[PQRS]',
            required => 0, # M
        },
        {
            key => '97a',
            key_regexp => '97[AE]',
            required => 0,
        },
        {
            key => '98a', # :PROC
            key_regexp => '98[AC]',
            required => 0,
        },
        {
            key => '20C', # :PROC
            required => 0,
        },
        {
            key => '70C', # :PACO
            required => 0,
        },
        {
            key => '16S', # = CSHPRTY
            required => 0, # M
        },
        # ����� �������������� ��������������������� �2 ������� ��� �������� ��������
        # �������������� ������������� ��������������������� �3 �����
        {
            key => '16R', # = AMT
            required => 0,
        },
        {
            key => '17B',
            required => 0,
        },
        {
            key => '19A',
            required => 0, # M
        },
        {
            key => '98a', # :VALU
            key_regexp => '98[AC]',
            required => 0,
        },
        {
            key => '92B', # :EXCH
            required => 0,
        },
        {
            key => '16S', # = AMT
            required => 0,
        },
        # ����� �������������� ��������������������� �3 �����
        {
            key => '16S', # = SETDET
            required => 0,
        },
        # ����� ������������������ � ������ ��������
        # �������������� ������������� ������������������ D ������ �������
        {
            key => '16R', # = OTHRPRTY
            required => 0, # M
        },
        {
            key => '95a',
            key_regexp => '95[PQRS]',
            required => 0,# M
        },
        {
            key => '97a',
            key_regexp => '97[ABE]',
            required => 0,
        },
        {
            key => '70C', #:PACO
            required => 0,
        },
        {
            key => '20C', #:PROC
            required => 0,
        },
        {
            key => '16S', # = OTHRPRTY
            required => 0,# M
        },
        # ����� ������������������ D ������ �������
        # �������������� ������������������ � ������ ��������, ��������� �� ���� ��������������� ������
        {
            key => '16R', # =REPO
            required => 0,
        },
        {
            key => '98a',
            key_regexp => '98[ABC]',
            required => 0,
        },
        {
            key => '22F',
            required => 0,
        },
        {
            key => '20C',
            required => 0,
        },
        {
            key => '92a',
            key_regexp => '95[AC]',
            required => 0,
        },
        {
            key => '99B',
            required => 0,
        },
        {
            key => '19A',
            required => 0,
        },
        {
            key => '70C', #:REPO
            required => 0,
        },
        {
            key => '16S',# =REPO
            required => 0,
        },
        # ����� ������������������ � ������ ��������, ��������� �� ���� ��������������� ������
    ],

    rules => [
        # �������� ������������ ������������������� � �����
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                
                my $sequences = {
                    'GENL' => {
                        name => 'A',
                        required => 1,
                        required_fields => ['20C', '23G', '22F'],
                    },
                    'GENL/LINK' => {
                        name => 'A1',
                        required => 0,
                        required_fields => ['20C'],
                    },
                    'CONFDET' => {
                        name => 'B',
                        required => 1,
                        required_fields => ['98[ABCE]', '90[AB]', '22[FH]', '36B', '35B'],
                    },
                    'CONFDET/CONFPRTY' => {
                        name => 'B1',
                        required => 1,
                        required_fields => ['95[PQRS]'],
                    },
                    'CONFDET/FIA' => {
                        name => 'B2',
                        required => 0,
                        required_fields => [],
                    },
                    'SETDET' => {
                        name => 'C',
                        required => 0,
                        required_fields => ['22F'],
                    },
                    'SETDET/SETPRTY' => {
                        name => 'C1',
                        required => 0,
                        required_fields => ['95[CPQRS]'],
                    },
                    'SETDET/CSHPRTY' => {
                        name => 'C2',
                        required => 0,
                        required_fields => ['95[PQRS]'],
                    },
                    'SETDET/AMT' => {
                        name => 'C3',
                        required => 0,
                        required_fields => ['19A'],
                    },
                    'OTHRPRTY' => {
                        name => 'D',
                        required => 0,
                        required_fields => ['95[PQRS]'],
                    },
                    'REPO' => {
                        name => 'E',
                        required => 0,
                        required_fields => [],
                    },
                };
                
                my $err = _check_required($tree, $sequences);
                if (defined $err) {
                    return (0, $err);
                }
                
                return 1;
            },
            err => 'Missing required sequence or field',
        },

        # �1 ���� � ��������� ������������ ���� :92�::EXCH ����� �����������, �� � ��� ��
        # ��������������������� ������ ����� �������������� ���� :19�::RESU ������ ����� �����������.
        # ���� ���� ����� ����������� �����������, �� ���� ������ ����� ����������� ��������������
        # �� ����� (��� ������ �62).
        # ��������������������� �3 ���� ���� :92�::EXCH ... ��������������������� �3 �� ���� :19�::RESU ...
        # ������������          ������������
        # �����������           �� ������������
        {
            func => sub {
                my $doc = shift;
                my $seqs_c3 = _find_sequences($doc->data)->{'C3'};
                if (defined $seqs_c3) {
                    for my $seq (@$seqs_c3) {
                        if (seq_key_value_exists($seq, '92B', '^:EXCH')) {
                            return 0 unless (seq_key_value_exists($seq, '19A', '^:RESU'));
                        }
                        unless (seq_key_value_exists($seq, '92B', '^:EXCH')) {
                            return 0 if (seq_key_value_exists($seq, '19A', '^:RESU'));
                        }
                    }
                }
                return 1;
            },
            err => 'E62',
        },

        # �2 ���� ���� :19�::SETT ������ �������� ������������ � ������������������ �, �� ��� ��
        # ������ �������������� �� � ����� �� ���������� ��������������������� �3 (��� ������ �73).
        # ������������������ � ���� ���� :19�::SETT ... �� � ������ �� ���������� ��������������������� �3 ���� :19�::SETT ...
        # ������������    �� ������������
        # �����������     ��������������
        {
            func => sub {
                my $doc = shift;
                my $seq_b = _find_sequences($doc->data)->{'B'}->[0] || [];
                my $seqs_c3 = _find_sequences($doc->data)->{'C3'} || [];
                if (seq_key_value_exists($seq_b, '19A', '^:SETT')) {
                    for my $seq (@$seqs_c3) {
                        return 0 if (seq_key_value_exists($seq, '19A', '^:SETT'));
                    }
                }
                return 1;
            },
            err => 'E73',
        },

        # �3 ���� ��������� ������������ ��� ������, �.�. ���� ���� 23 G �������� ���������� ��������
        # ��� CANC, � ��������� ������ �������������� ���� �� ���� ��������������������� �1 �������,
        # � � ����� � ������ � ����� ���������� �1 ������ �������������� ���� :20C::PREV.
        # ��������������, � ��������� ����������� �1 ���� :20C::PREV �� �����������. (��� ������ �08).
        # ������������������ � ���� ���� :23G:... �� ��������������������� �1 ... � ���� :20C::PREV ...
        # CANC    ������������, �.�. ������ �������������� ���� �� ���� ��������������������� �1    ������������ � ����� ���������� �1 � �� ����������� �� ���� ��������� ����������� �1.
        # NEWM    ��������������                                                                    �� �����������
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                my $seqs_a = $seqs->{'A'};
                my $seqs_a1 = $seqs->{'A1'};

                if (seq_key_value_exists($seqs_a->[0], '23G', 'CANC')) {
                    return 0 unless (defined($seqs_a1) && scalar(@$seqs_a1) > 0);
                    my $cnt = 0;
                    for my $s (@$seqs_a1) {
                        if (seq_key_value_exists($s, '20C', '^:PREV')) {
                            $cnt++;
                        }
                    }
                    return 0 if ($cnt != 1);
                }
                elsif (seq_key_value_exists($seqs_a->[0], '23G', 'NEWM')) {
                    if (defined $seqs_a1) {
                        for my $s (@$seqs_a1) {
                            return 0 if (seq_key_value_exists($s, '20C', '^:PREV'));
                        }
                    }
                }
                return 1;
            },
            err => 'E08',
        },

        # �4 ��������� ���� ����������� ������ � ���������������������� �1 � �2 �� ����� ��������������
        # ����� ������ ���� � ������������������ �. ���� ����������� ������ � ������������������ D ��
        # ����� �������������� ����� ������ ���� � ��������� (��� ������ �84):
        # ��������������������� �1:    # ��������������������� �2:   # ������������������ D:
        # :95a::BUYR                    # :95a::ACCW                  # :95a::EXCH
        # :95a::DEAG                    # :95a::BENM                  # :95a::MEOR
        # :95a::DECU                    # :95a::PAYE                  # :95a::MERE
        # :95a::DEI1                    # :95a::DEBT                  # :95a::TRRE
        # :95a::DEI2                    # :95a::INTM                  # :95a::VEND
        # :95a::PSET                                                  # :95a::TRAG
        # :95a::REAG
        # :95a::RECU
        # :95a::REI1
        # :95a::REI2
        # :95a::SELL
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                my $seq_c = $seqs->{'C'}->[0] || [];

                for my $k (qw`BUYR DEAG DECU DEI1 DEI2 PSET REAG RECU REI1 REI2 SELL`) {
                    return 0 if (seq_key_value_count($seq_c, '95[A-Z]', ":$k") > 1);
                }

                for my $k (qw`ACCW BENM PAYE DEBT INTM`) {
                    return 0 if (seq_key_value_count($seq_c, '95[A-Z]', ":$k") > 1);
                }

                for my $k (qw`EXCH MEOR MERE TRRE VEND TRAG`) {
                    return 0 if (seq_key_value_count($doc->data, '95[A-Z]', ":$k") > 1);
                }

                return 1;
            },
            err => 'E84',
        },

        # �5 ���� � ���� :95a::4!� � ��������������������� �1 ������������ ������������ ��
        # ������ ����������� (��. ����), �� ����� ���� ������ ���� ������� ��� ��������� ������������,
        # ��������� �� ��� � ������ ����������� (��� ������ �86).
        # ������� �������, ��� ������������������ � ������ ����������� ��������� ��������:
        # � ���� � ��������������������� �1 ������������ ���� :95a::DEI2, �� � ������ ��������������������� �1 ������ �������������� ���� :95a::DEI1.
        # � ���� � ��������������������� �1 ������������ ���� :95a::DEI1, �� � ������ ��������������������� �1 ������ �������������� ���� :95a::DECU.
        # � ���� � ��������������������� �1 ������������ ���� :95a::DECU, �� � ������ ��������������������� �1 ������ �������������� ���� :95a::SELL.
        # � ���� � ��������������������� �1 ������������ ����:95a::SELL, �� � ������ ��������������������� �1 ������ �������������� ����:95a::DEAG.
        # ���� � ���� :95a::4!� � ��������������������� �1 ������������ ������������ ��
        # ������ ����������� (��. ����), �� ����� ���� ������ ���� ������� ��� ��������� ������������,
        # ��������� �� ��� � ������ �����������.
        # ������� �������, ��� ������������������ � ������ ����������� ��������� ��������:
        # � ���� � ��������������������� �1 ������������ ���� :95a::REI2, �� � ������ ��������������������� �1 ������ �������������� ���� :95a::REI1.
        # � ���� � ��������������������� �1 ������������ ���� :95a::REI1, �� � ������ ��������������������� �1 ������ �������������� ���� :95a::RECU.
        # � ���� � ��������������������� �1 ������������ ���� :95a::RECU, �� � ������ ��������������������� �1 ������ �������������� ���� :95a::BUYR.
        # � ���� � ��������������������� �1 ������������ ���� :95a::BUYR, �� � ������ ��������������������� �1 ������ �������������� ����:95a::REAG.
        {
            func => sub {
                my $doc = shift;

                my $rules = {
                    'DEI2' => 'DEI1',
                    'DEI1' => 'DECU',
                    'DECU' => 'SELL',
                    'SELL' => 'DEAG',
                    'REI2' => 'REI1',
                    'REI1' => 'RECU',
                    'RECU' => 'BUYR',
                    'BUYR' => 'REAG',
                };

                my $items = [];
                my $seqs = _find_sequences($doc->data);
                my $seqs_c1 = $seqs->{'C1'} || [];
                for my $s (@$seqs_c1) {
                    for my $item (@$s) {
                        if ($item->{key} =~ /95[A-Z]/) {
                            push @$items, $item;
                        }
                    }
                }

                for my $k (keys %$rules) {
                    my $v1 = $k;
                    my $v2 = $rules->{$k};
                    if (seq_key_value_exists($items, '95[A-Z]', ":$v1")) {
                        return 0 unless (seq_key_value_exists($items, '95[A-Z]', ":$v2"));
                    }
                }

                return 1;
            },
            err => 'E86',
        },

        # �6 ���� � ��������������������� C1 ������������ ���� :95a::PSET, �� � ��� �� ������������������ �� ����� �������������� ���� :97a::SAFE (��� ������ �52).
        # ��������������������� C1 ���� ���� :95a::PSET ... ��������������������� C1 �� ���� :97a::SAFE ...
        # ������������      �� ����� �������������� � ��� �� ����������
        # �����������       ��������������
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                my $seqs_c1 = $seqs->{'C1'} || [];
                for my $s (@$seqs_c1) {
                    if (seq_key_value_exists($s, '95[A-Z]', ':PSET')) {
                        return 0 if (seq_key_value_exists($s, '97[A-Z]', ':SAFE'))
                    }
                }
                return 1;
            },
            err => 'E52',
        },

        # �7 ���� � ������������������ � ������������ ���� :22F::DBNM//VEND, �� � ��������� ������
        # ���� ������ ��������� ���� ������, �. �. � ����� �� ���������� ������������������ D ������
        # �������������� ���� :95a::VEND (��� ������ D71).
        # (*) ���� � ���� :22F::DBNM//VEND ������������ ������� �������� ���������,
        # �� ��� ������������� ������� �� �����������
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                my $seq_c = $seqs->{'C'}->[0] || [];
                if (seq_key_value_exists($seq_c, '22F', ':DBNM//VEND')) {
                    return 0 unless (seq_key_value_exists($doc->data, '95[A-Z]', ':VEND'))
                }
                return 1;
            },
            err => 'D71',
        },

        # �8 ���� � ������������������ D ������������ ���� :95a::EXCH �������� ����� ��� :95a::TRRE
        # ������������ �����, �� � ��� �� ������������������ �� ����� �������������� ���� :97a::
        # (��� ������ �63).
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                my $seqs_d = $seqs->{'D'} || [];
                for my $s (@$seqs_d) {
                    if (seq_key_value_exists($s, '95[A-Z]', ':(EXCH|TRRE)')) {
                        return 0 if (seq_key_exists($s, '97[A-Z]', '^:'))
                    }
                }
                return 1;
            },
            err => 'E63',
        },
    ]
};

# ����������� ��� ������������������ ��� ��� ��������.
sub _find_sequences {
    my $data = shift;

    my $level1 = _findsq(
        $data,
        {
            'GENL'     => 'A',
            'CONFDET'  => 'B',
            'SETDET'   => 'C',
            'OTHRPRTY' => 'D',
            'REPO'     => 'E',
        }
    );

    my $level2 = _findsq(
        $data,
        {
            'LINK'     => 'A1',
            'CONFPRTY' => 'B1',
            'FIA'      => 'B2',
            'SETPRTY'  => 'C1',
            'CSHPRTY'  => 'C2',
            'AMT'      => 'C3',
        },
    );

    return {%$level1, %$level2};
}

sub _findsq {
    my $data = shift;
    my $marks = shift;

    my $cur_seq = undef;
    my $cur_mark = undef;
    my $seqs = {};

    for my $item (@$data) {
        my $key = $item->{key};
        my ($value) = ($item->{value} =~ /^\s*(.*?)\s*$/s);
        if ($key eq '16R' && defined($marks->{$value})) {
            $cur_mark = $value;
            $cur_seq = $marks->{$cur_mark};
            push @{$seqs->{$cur_seq}}, [];
        }
        if ($key eq '16S' && $value eq $cur_mark) {
            $cur_mark = undef;
            $cur_seq = undef;
        }
        if ($cur_seq) {
            my @cur_seqs = @{$seqs->{$cur_seq}};
            my $last_cur_seq = @cur_seqs[scalar(@cur_seqs)-1];
            push @$last_cur_seq, $item;
        }
    }

    return $seqs;
}

# ������ ������ (���)�������������������. � ������ ������� ����� ��� ����, ��� ������ ������������������
# ���������� � ���� 16R � ������������� ����� 16S � ����� �� �����������. ������������������ ����� ���� ����������.
sub _get_seq_tree {
    my %params = @_;
    my $data;

    if (defined $params{doc}) {
        # ��������, ���� �� �������������� ���������
        if (defined $params{doc}->{__get_seq_tree_result__}) {
            return Storable::dclone($params{doc}->{__get_seq_tree_result__});
        }
        $data = $params{doc}->data;
    } else {
        $data = $params{data};
    }

    my $tree = {
        fields => [],
    };

    my $inner_seq_started = 0;
    my $inner_seq_name = '';
    my $inner_seq = [];

    for my $item (@$data) {
        my $key = $item->{key};
        my ($value) = ($item->{value} =~ /^\s*(.*?)\s*$/s);

        if ($key eq '16R' && !$inner_seq_started) {
            # ������ ���������������������
            $inner_seq_started = 1;
            $inner_seq_name = $value;
            $inner_seq = [];
        }
        elsif ($key eq '16S' && $inner_seq_started && $inner_seq_name eq $value) {
            # ����� ���������������������
            push @{$tree->{$inner_seq_name}}, _get_seq_tree(data => $inner_seq);
            $inner_seq_started = 0;
            $inner_seq_name = '';
            $inner_seq = [];
        }
        elsif ($inner_seq_started) {
            # ���� ������ ���������������������
            push @$inner_seq, $item;
        }
        else {
            # ����, ������������� ������ ������������������
            push @{$tree->{fields}}, $item;
        }
    }

    if (defined $params{doc}) {
        $params{doc}->{__get_seq_tree_result__} = $tree;
    }

    return $tree;
}


# ����������� �������� ������������ ������������������� � ����������������������. 
sub _check_required_sequences {
    my $tree = shift;
    my $seqs = shift;
    
    my $_check_path;
    $_check_path = sub {
        my $tree = shift;
        my $path = shift;
        
        my ($first, $rest) = $path =~ /^([^\/]+)(?:\/(.+))?$/;
        my $branches = $tree->{$first} || undef;
        
        if ($rest) {
            if ($branches) {
                for my $b (@$branches) {
                    my $r = $_check_path->($b, $rest);
                    if (!$r) {
                        return $r;
                    }
                }
            }
        }
        else {
            if (!defined($branches) || scalar(@$branches) < 1) {
                return 0;
            }
        }
        
        return 1;
    };
    
    my @keys = sort {$seqs->{$a}->{name} cmp $seqs->{$b}->{name}} keys %$seqs;
    for my $path (@keys) {
        if ($seqs->{$path}->{required}) {
            my $r = $_check_path->($tree, $path);
            if (!$r) {
                return "Missing required sequence: " . $seqs->{$path}->{name};
            }
        }
    }
    
    return undef;
}

# ����������� �������� ������������ ����� � ������������������� � ����������������������. 
sub _check_required_fields {
    my $tree = shift;
    my $seqs = shift;
    
    my $_check_path_fields;
    $_check_path_fields = sub {
        my $tree = shift;
        my $path = shift;
        my $fields = shift;
        
        my ($first, $rest) = $path =~ /^([^\/]+)(?:\/(.+))?$/;
        my $branches = $tree->{$first} || undef;
        
        if ($rest) {
            if ($branches) {
                for my $b (@$branches) {
                    my ($r, $f) = $_check_path_fields->($b, $rest, $fields);
                    if (!$r) {
                        return ($r, $f);
                    }
                }
            }
        }
        else {
            if (defined($branches) && scalar(@$branches) > 0) {
                for my $b (@$branches) {
                    for my $f (@$fields) {
                        unless (seq_key_exists($b->{fields}, $f)) {
                            return (0, $f);
                        }
                    }
                }
            }
        }
        
        return 1;
    };
    
    my @keys = sort {$seqs->{$a}->{name} cmp $seqs->{$b}->{name}} keys %$seqs;
    for my $path (@keys) {
        if ($seqs->{$path}->{required_fields}) {
            my ($r, $f) = $_check_path_fields->($tree, $path, $seqs->{$path}->{required_fields});
            if (!$r) {
                my $n = $seqs->{$path}->{name};
                if ($f =~ /^(\d+)\[[A-Z-]+\]$/) {
                    $f = $1.'a';
                }
                return "Missing required field ($n sequence): $f";
            }
        }
    }
    
    return undef;
}

# ����������� �������� ������������ ������������������� � �����. 
sub _check_required {
    my $tree = shift;
    my $seqs = shift;
    
    my $err_s = _check_required_sequences($tree, $seqs);
    if (defined $err_s) {
        return $err_s;
    }
    
    my $err_f = _check_required_fields($tree, $seqs);
    if (defined $err_f) {
        return $err_f;
    }
    
    return undef;
}

1;