package CyberFT::SWIFT::Types::MT537;
use CyberFT::SWIFT::Types::Utils;
use strict;
use Storable ();
use Data::Dumper;

our $ValidationProfile = {

    fields => [
        # ������� ������ ������������ ���� ������������ �������������������.
        # A
        {
            key => '16R',
            required => 1,
        },
        {
            key => '28E',
            required => 1,
        },
        {
            key => '20C',
            required => 1,
        },
        {
            key => '23G',
            required => 1,
        },
        {
            key => '98a',
            key_regexp => '98[ACE]',
            required => 1,
        },
        {
            key => '22a',
            key_regexp => '22[FH]',
            required => 1,
        },
        {
            key => '97a',
            key_regexp => '97[AB]',
            required => 1,
        },
        {
            key => '17B',
            required => 1,
        },
        {
            key => '16S',
            required => 1,
        },
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
                        required_fields => ['28E', '20C', '23G', '98[ACE]', '22[FH]', '97[AB]', '17B'],
                    },
                    'GENL/LINK' => {
                        name => 'A1',
                        required => 0,
                        required_fields => ['20C'],
                    },
                    'STAT' => {
                        name => 'B',
                        required => 0,
                        required_fields => ['25D'],
                    },
                    'STAT/REAS' => {
                        name => 'B1',
                        required => 0,
                        required_fields => ['24B'],
                    },
                    'STAT/TRAN' => {
                        name => 'B2',
                        required => 1,
                        required_fields => [],
                    },
                    'STAT/TRAN/LINK' => {
                        name => 'B2a',
                        required => 1,
                        required_fields => ['20C'],
                    },
                    'STAT/TRAN/TRANSDET' => {
                        name => 'B2b',
                        required => 0,
                        required_fields => ['35B', '36B', '22[FH]', '98[ABC]'],
                    },
                    'STAT/TRAN/TRANSDET/SETPRTY' => {
                        name => 'B2b1',
                        required => 0,
                        required_fields => ['95[CPQR]'],
                    },
                    'TRANS' => {
                        name => 'C',
                        required => 0,
                        required_fields => [],
                    },
                    'TRANS/LINK' => {
                        name => 'C1',
                        required => 1,
                        required_fields => ['20C'],
                    },
                    'TRANS/TRANSDET' => {
                        name => 'C2',
                        required => 0,
                        required_fields => ['35B', '36B', '22[FH]', '98[ABC]'],
                    },
                    'TRANS/TRANSDET/SETPRTY' => {
                        name => 'C2a',
                        required => 0,
                        required_fields => ['95[CPQR]'],
                    },
                    'TRANS/STAT' => {
                        name => 'C3',
                        required => 0,
                        required_fields => ['25D'],
                    },
                    'TRANS/STAT/REAS' => {
                        name => 'C3a',
                        required => 0,
                        required_fields => ['24B'],
                    },
                    'ADDINFO' => {
                        name => 'D',
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

        # �1
        # ���� � ������������������ � ������ ����������� ���� ����� �������� (���� :17B::ACTI)
        # ��������� �� ���������� ���������� ��� ����������, �.�. ����� �������� �N�, ��
        # ������������������ � ������� � ������������������ � ��������� �� ������ ��������������
        # (��� ������ �66).
        # ���� ����� �������� (���� :17B::ACTI) ��������� �� ������� ���������� ��� ����������,
        # �.�. ����� �������� �Y�, � �������� ���� ��������� ������� ��������� ��� �������� �� �������
        # (:22H::STST//STAT), �� ������������������ � ������� �������� ������������, �
        # ������������������ � ��������� �� ������ �������������� (��� ������ �66).
        # ���� ����� �������� (���� :17B::ACTI) ��������� �� ������� ���������� ��� ����������,
        # �.�. ����� �������� �Y�, � �������� ���� ��������� ������� ��������� ���
        # �������� �� ��������� (:22H::STST//TRAN), �� ������������������ � ������� �� ������
        # ��������������, � ������������������ � ��������� �������� ������������ (��� ������ �66).
        {
            func => sub {
                my $doc = shift;
                my $tree = build_seq_tree($doc->data);
                my $fields_a = $tree->{'GENL'}->[0]->{fields} || [];
                my $branches_b = $tree->{'STAT'} || [];
                my $branches_c = $tree->{'TRANS'} || [];
                if (seq_key_value_exists($fields_a, '17B', ':ACTI//N')) {
                    return 0 if (scalar @$branches_b > 0);
                    return 0 if (scalar @$branches_c > 0);
                }
                elsif (seq_key_value_exists($fields_a, '17B', ':ACTI//Y')) {
                    if (seq_key_value_exists($fields_a, '22H', ':STST//STAT')){
                        return 0 if (scalar @$branches_b == 0);
                        return 0 if (scalar @$branches_c > 0);
                    }
                    elsif (seq_key_value_exists($fields_a, '22H', ':STST//TRAN')){
                        return 0 if (scalar @$branches_b > 0);
                        return 0 if (scalar @$branches_c == 0);
                    }
                }
                return 1;
            },
            err => 'E66',
        },

        # C2
        # ���� ���������� ��������������� �������� ������ ������� (:22H::PAYM//APMT), ��
        # ����������� ������ ���� ������� �����, ���������� �� ����� (���� :19A::PSTA). ��� �������
        # ��������� � ���������������������� �2b � �2 (��� ������ �83).
        {
            func => sub {
                my $doc = shift;
                my $tree = build_seq_tree($doc->data);
                # ������������ �� ������ �� B2b
                my $branches_b = $tree->{'STAT'} || [];                      # B
                for my $b_b (@$branches_b) {
                    my $branches_b2 = $b_b->{'TRAN'} || [];                  # B2
                    for my $b_b2 (@$branches_b2) {
                        my $branches_b2b = $b_b2->{'TRANSDET'} || [];        # B2b
                        for my $b_b2b (@$branches_b2b) {
                            my $fields = $b_b2b->{fields} || [];
                            if (seq_key_value_exists($fields, '22H', ':PAYM//APMT')) {
                                return 0 unless (seq_key_value_exists($fields, '19A', ':PSTA'));
                            }
                        }
                    }
                }
                # ������������ �� ������ �� C2
                my $branches_c = $tree->{'TRANS'} || [];               # C
                for my $b_c (@$branches_c) {
                    my $branches_c2 = $b_c->{'TRANSDET'} || [];        # C2
                    for my $b_c2 (@$branches_c2) {
                        my $fields = $b_c2->{fields} || [];
                        if (seq_key_value_exists($fields, '22H', ':PAYM//APMT')) {
                            return 0 unless (seq_key_value_exists($fields, '19A', ':PSTA'));
                        }
                    }
                }
                return 1;
            },
            err => 'E83',
        },

        # �3
        # ��������� ���� ����������� ������ �� ����� �������������� ����� ������ ���� � ����� �
        # ��� �� ���������� ��������������������� �2b (��� ������ �84):
        # :95a::BUYR
        # :95a::DEAG
        # :95a::DECU
        # :95a::DEI1
        # :95a::DEI2
        # :95a::PSET
        # :95a::REAG
        # :95a::RECU
        # :95a::REI1
        # :95a::REI2
        # :95a::SELL
        # ��������� ���� ����������� ������ �� ����� �������������� ����� ������ ���� � ����� � ���
        # �� ���������� ��������������������� �2 (��� ������ �84):
        # :95a::BUYR
        # :95a::DEAG
        # :95a::DECU
        # :95a::DEI1
        # :95a::DEI2
        # :95a::PSET
        # :95a::REAG
        # :95a::RECU
        # :95a::REI1
        # :95a::REI2
        # :95a::SELL
        {
            func => sub {
                my $doc = shift;
                my $tree = build_seq_tree($doc->data);
                # ������������ �� ������ �� B2b
                my $branches_b = $tree->{'STAT'} || [];                      # B
                for my $b_b (@$branches_b) {
                    my $branches_b2 = $b_b->{'TRAN'} || [];                  # B2
                    for my $b_b2 (@$branches_b2) {
                        my $branches_b2b = $b_b2->{'TRANSDET'} || [];        # B2b
                        for my $b_b2b (@$branches_b2b) {
                            my $fields = flatten_tree($b_b2b) || [];
                            for my $c (qw`BUYR DEAG DECU DEI1 DEI2 PSET REAG RECU REI1 REI2 SELL`) {
                                return 0 if (seq_key_value_count($fields, '95[A-Z]', ":$c") > 1);
                            }
                        }
                    }
                }
                # ������������ �� ������ �� C2
                my $branches_c = $tree->{'TRANS'} || [];               # C
                for my $b_c (@$branches_c) {
                    my $branches_c2 = $b_c->{'TRANSDET'} || [];        # C2
                    for my $b_c2 (@$branches_c2) {
                        my $fields = flatten_tree($b_c2) || [];
                        for my $c (qw`BUYR DEAG DECU DEI1 DEI2 PSET REAG RECU REI1 REI2 SELL`) {
                            return 0 if (seq_key_value_count($fields, '95[A-Z]', ":$c") > 1);
                        }
                    }
                }
                return 1;
            },
            err => 'E84',
        },

        # C4
        # ���� ���������� �������� ������������ �� �������� (���� :22�::REDE//DELI � ��������������������� �2b)
        # � ��� ���� ������������ ��������������������� �2b1 �������� ��� ���������, �� ����������� ������ ����
        # ������ �����-����������: � ����� �� ���������� ��������������������� �2b1 �������� ��� ���������
        # ������ �������������� ���� :95a::REAG (��� ������ �85).
        # ���� ���������� �������� ������������ �� ��������� (���� :22�::REDE//RECE � ��������������������� �2b)
        # � ��� ���� ������������ ��������������������� �2b1 �������� ��� ���������, �� ����������� ������ ����
        # ������ �����-���������: � ����� �� ���������� ��������������������� �2b1 �������� ��� ���������
        # ������ �������������� ���� :95a::DEAG (��� ������ �85).
        # ���� ���������� �������� ������������ �� �������� (���� :22�::REDE//DELI � ��������������������� �2)
        # � ��� ���� ������������ ��������������������� �2� �������� ��� ���������, �� ����������� ������ ����
        # ������ �����-����������: � ����� �� ���������� ��������������������� �2� �������� ��� ���������
        # ������ �������������� ���� :95a::REAG (��� ������ �85).
        # ���� ���������� �������� ������������ �� ��������� (���� :22�::REDE//RECE � ��������������������� �2
        # � ��� ���� ������������ ��������������������� �2� �������� ��� ���������, �� ����������� ������ ����
        # ������ �����-���������: � ����� �� ���������� ��������������������� �2� �������� ��� ���������
        # ������ �������������� ���� :95a::DEAG (��� ������ �85).
        {
            func => sub {
                my $doc = shift;
                my $tree = build_seq_tree($doc->data);
                # ������������ �� ������ �� B2b
                my $branches_b = $tree->{'STAT'} || [];                      # B
                for my $b_b (@$branches_b) {
                    my $branches_b2 = $b_b->{'TRAN'} || [];                  # B2
                    for my $b_b2 (@$branches_b2) {
                        my $branches_b2b = $b_b2->{'TRANSDET'} || [];        # B2b
                        for my $b_b2b (@$branches_b2b) {
                            my $fields_b2b = $b_b2b->{fields} || [];
                            my $fields_b2b_flat = flatten_tree($b_b2b) || [];
                            my $branches_b2b1 = $b_b2b->{'SETPRTY'} || [];
                            if (
                                seq_key_value_exists($fields_b2b, '22H', ':REDE//DELI')
                                && scalar(@$branches_b2b1) > 0
                            ) {
                                return 0 unless(seq_key_value_exists($fields_b2b_flat, '95[A-Z]', ':REAG'));
                            }
                            if (
                                seq_key_value_exists($fields_b2b, '22H', ':REDE//RECE')
                                && scalar(@$branches_b2b1) > 0
                            ) {
                                return 0 unless(seq_key_value_exists($fields_b2b_flat, '95[A-Z]', ':DEAG'));
                            }
                        }
                    }
                }
                # ������������ �� ������ �� C2
                my $branches_c = $tree->{'TRANS'} || [];               # C
                for my $b_c (@$branches_c) {
                    my $branches_c2 = $b_c->{'TRANSDET'} || [];        # C2
                    for my $b_c2 (@$branches_c2) {
                        my $fields_c2 = $b_c2->{fields} || [];
                        my $fields_c2_flat = flatten_tree($b_c2) || [];
                        my $branches_c2a = $b_c2->{'SETPRTY'} || [];
                        if (
                            seq_key_value_exists($fields_c2, '22H', ':REDE//DELI')
                            && scalar(@$branches_c2a) > 0
                        ) {
                            return 0 unless(seq_key_value_exists($fields_c2_flat, '95[A-Z]', ':REAG'));
                        }
                        if (
                            seq_key_value_exists($fields_c2, '22H', ':REDE//RECE')
                            && scalar(@$branches_c2a) > 0
                        ) {
                            return 0 unless(seq_key_value_exists($fields_c2_flat, '95[A-Z]', ':DEAG'));
                        }
                    }
                }
                return 1;
            },
            err => 'E85',
        },

        # �5
        # ���� � ��������� ������������ ��������������������� �2b, �� ��������� ��������� �������:
        # ���� � ���� :95a::4!� � ��������������������� �2b1 ������������ ������������ �� ������
        # �����������, �� ����� ���� ������ ���� ������� ��� ��������� ������������, ��������� ��
        # ��� � ������ ����������� (��. ����) (��� ������ �86).
        # ���� � ���� :95a::4!� � ��������������������� C2a ������������ ������������ �� ������
        # �����������, �� ����� ���� ������ ���� ������� ��� ��������� ������������, ��������� �� ���
        # � ������ ����������� (��. ����).
        {
            func => sub {
                my $doc = shift;
                my $tree = build_seq_tree($doc->data);
                my $rules = {
                    'DEI2' => 'DEI1',
                    'DEI1' => 'DECU',
                    'DECU' => 'SELL',
                    'REI2' => 'REI1',
                    'REI1' => 'RECU',
                    'RECU' => 'BUYR',
                };
                # ������������ �� ������ �� B2b
                my $branches_b = $tree->{'STAT'} || [];                      # B
                for my $b_b (@$branches_b) {
                    my $branches_b2 = $b_b->{'TRAN'} || [];                  # B2
                    for my $b_b2 (@$branches_b2) {
                        my $branches_b2b = $b_b2->{'TRANSDET'} || [];        # B2b
                        for my $b_b2b (@$branches_b2b) {
                            my $fields_b2b_flat = flatten_tree($b_b2b) || [];
                            for my $x1 (keys %$rules) {
                                my $x2 = $rules->{$x1};
                                return 0 if (
                                    seq_key_value_exists($fields_b2b_flat, '95[A-Z]', ":$x1")
                                    && !seq_key_value_exists($fields_b2b_flat, '95[A-Z]', ":$x2")
                                );
                            }

                        }
                    }
                }
                # ������������ �� ������ �� C2
                my $branches_c = $tree->{'TRANS'} || [];               # C
                for my $b_c (@$branches_c) {
                    my $branches_c2 = $b_c->{'TRANSDET'} || [];        # C2
                    for my $b_c2 (@$branches_c2) {
                        my $fields_c2_flat = flatten_tree($b_c2) || [];
                        for my $x1 (keys %$rules) {
                            my $x2 = $rules->{$x1};
                            return 0 if (
                                seq_key_value_exists($fields_c2_flat, '95[A-Z]', ":$x1")
                                && !seq_key_value_exists($fields_c2_flat, '95[A-Z]', ":$x2")
                            );
                        }

                    }
                }
                return 1;
            },
            err => 'E86',
        },

        # �6
        # ���� ��������� ������������ ��� ������ ����� ������������ �������, �.�. ���� ���� 23G
        # �������� ���������� �������� ��� CANC, � ��������� ������ �������������� ���� �� ����
        # ��������������������� �1 �������, � � ����� � ������ � ����� ���������� �1 ������
        # �������������� ���� :20C::PREV. ��������������, � ��������� ����������� �1 ���� :20C::PREV
        # �� �����������. (��� ������ �08).
        {
            func => sub {
                my $doc = shift;
                my $tree = build_seq_tree($doc->data);
                my $fields_a = $tree->{'GENL'}->[0]->{fields} || [];
                my $branches_a1 = $tree->{'GENL'}->[0]->{'LINK'} || [];
                if (seq_key_value_exists($fields_a, '23G', 'CANC')) {
                    return 0 if (scalar @$branches_a1 < 1);
                    my $count = 0;
                    for my $b (@$branches_a1) {
                        if (seq_key_value_exists($b->{fields}, '20C', ':PREV')) {
                            $count++;
                        }
                    }
                    return 0 if ($count != 1);
                }
                elsif (seq_key_value_exists($fields_a, '23G', 'NEWM')) {
                    my $count = 0;
                    for my $b (@$branches_a1) {
                        if (seq_key_value_exists($b->{fields}, '20C', ':PREV')) {
                            $count++;
                        }
                    }
                    return 0 if ($count > 0);
                }
                return 1;
            },
            err => 'E08',
        },

        # �7
        # ���� � ��������������������� �2b1 ������������ ���� :95a::PSET, �� � ��� ��
        # ������������������ �� ����� �������������� ���� :97a::SAFE (��� ������ �52).
        # ���� � ��������������������� �2� ������������ ���� :95a::PSET, �� � ��� ��
        # ������������������ �� ����� �������������� ���� :97a::SAFE (��� ������ �52).
        {
            func => sub {
                my $doc = shift;
                my $tree = build_seq_tree($doc->data);
                # ������������ �� ������ �� B2b
                my $branches_b = $tree->{'STAT'} || [];                      # B
                for my $b_b (@$branches_b) {
                    my $branches_b2 = $b_b->{'TRAN'} || [];                  # B2
                    for my $b_b2 (@$branches_b2) {
                        my $branches_b2b = $b_b2->{'TRANSDET'} || [];        # B2b
                        for my $b_b2b (@$branches_b2b) {
                            my $branches_b2b1 = $b_b2b->{'SETPRTY'} || [];   # B2b1
                            for my $b (@$branches_b2b1) {
                                return 0 if (
                                    seq_key_value_exists($b->{fields}, '95[A-Z]', ':PSET')
                                    && seq_key_value_exists($b->{fields}, '97[A-Z]', ':SAFE')
                                );
                            }
                        }
                    }
                }
                # ������������ �� ������ �� C2
                my $branches_c = $tree->{'TRANS'} || [];               # C
                for my $b_c (@$branches_c) {
                    my $branches_c2 = $b_c->{'TRANSDET'} || [];        # C2
                    for my $b_c2 (@$branches_c2) {
                        my $branches_c2a = $b_c2->{'SETPRTY'} || [];   # C2a
                        for my $b (@$branches_c2a) {
                            return 0 if (
                                seq_key_value_exists($b->{fields}, '95[A-Z]', ':PSET')
                                && seq_key_value_exists($b->{fields}, '97[A-Z]', ':SAFE')
                            );
                        }

                    }
                }
                return 1;
            },
            err => 'E52',
        },

        # �8
        # � ������ ���������� ��������������������� �1 ������������ � ���� 24B ������ ��������� �
        # ������� ������ (���� �������), ������� ������� � ��������������� ������������� � ���� 25D
        # ��� �� ������������������ � (��� ������ �37).
        # � ������ ���������� ��������������������� �3� �������� ������������ � ���� 24B ������
        # ��������� � ������� ������ (���� �������), ������� ������� � ��������������� �������������
        # � ���� 25D ��� �� ��������������������� �3 ������� (��� ������ �37).
        {
            func => sub {
                my $doc = shift;
                my $tree = build_seq_tree($doc->data);
                # ������������ �� ������ �� B1
                my $branches_b = $tree->{'STAT'} || [];
                for my $b_b (@$branches_b) {
                    my $val = seq_get_first($b_b->{fields}, '25D');
                    my ($opred, $coding, $status_code) = $val =~ /^(.*?)\/(.*?)\/(.*?)\s*$/s;
                    unless ($coding) {
                        my $branches_b1 =  $b_b->{'REAS'} || [];
                        for my $b (@$branches_b1) {
                            for my $item (@{$b->{fields}}) {
                                if ($item->{key} eq '24B' && $item->{value} !~ /^:$status_code/) {
                                    return 0;
                                }
                            }
                        }
                    }
                }
                # ������������ �� ������ �� C3a
                my $branches_c = $tree->{'TRANS'} || [];
                for my $b_c (@$branches_c) {
                    my $branches_c3 = $b_c->{'STAT'} || [];
                    for my $b_c3 (@$branches_c3) {
                        my $val = seq_get_first($b_c3->{fields}, '25D');
                        my ($opred, $coding, $status_code) = $val =~ /^(.*?)\/(.*?)\/(.*?)\s*$/s;
                        unless ($coding) {
                            my $branches_c3a =  $b_c3->{'REAS'} || [];
                            for my $b (@$branches_c3a) {
                                for my $item (@{$b->{fields}}) {
                                    if ($item->{key} eq '24B' && $item->{value} !~ /^:$status_code/) {
                                        return 0;
                                    }
                                }
                            }
                        }
                    }
                }
                return 1;
            },
            err => 'E37',
        },
        # �9
        # � ����� ���������� ��������������������� �2b ���� :36B::PSTA �� ����� ��������������
        # ����� ���� ���. ���� ��� ���� ������������ ������, �� ���� ���� ���������� � ������ ������
        # ������ ���� �FAMT�, � �� ������ ������ ������ ���� �AMOR� (��� ������ �71).
        # � ����� ���������� ��������������������� C2 ���� :36B::PSTA �� ����� ��������������
        # ����� ���� ���. ���� ��� ���� ������������ ������, �� ���� ���� ���������� � ������ ������
        # ������ ���� �FAMT�, � �� ������ ������ ������ ���� �AMOR� (��� ������ �72).
        {
            func => sub {
                my $doc = shift;
                my $tree = build_seq_tree($doc->data);
                # ������������ �� ������ �� B2b
                my $branches_b = $tree->{'STAT'} || [];                      # B
                for my $b_b (@$branches_b) {
                    my $branches_b2 = $b_b->{'TRAN'} || [];                  # B2
                    for my $b_b2 (@$branches_b2) {
                        my $branches_b2b = $b_b2->{'TRANSDET'} || [];        # B2b
                        for my $b_b2b (@$branches_b2b) {
                            my $fields = $b_b2b->{fields};
                            my $counter = 0;
                            my ($first, $second);
                            for my $item (@$fields) {
                                if ($item->{key} =~ /36B/ && $item->{value} =~ /:PSTA/) {
                                    $counter++;
                                    if ($counter == 1) {
                                        $first = $item->{value};
                                    }
                                    elsif ($counter == 2) {
                                        $second = $item->{value};
                                        return 0 unless ($first =~ /FAMT/ && $second =~ /AMOR/);
                                    }
                                    else {
                                        return 0;
                                    }
                                }
                            }
                        }
                    }
                }
                return 1;
            },
            err => 'C71',
        },
        {
            func => sub {
                my $doc = shift;
                my $tree = build_seq_tree($doc->data);
                # ������������ �� ������ �� C2
                my $branches_c = $tree->{'TRANS'} || [];               # C
                for my $b_c (@$branches_c) {
                    my $branches_c2 = $b_c->{'TRANSDET'} || [];        # C2
                    for my $b_c2 (@$branches_c2) {
                        my $fields = $b_c2->{fields};
                        my $counter = 0;
                        my ($first, $second);
                        for my $item (@$fields) {
                            if ($item->{key} =~ /36B/ && $item->{value} =~ /:PSTA/) {
                                $counter++;
                                if ($counter == 1) {
                                    $first = $item->{value};
                                }
                                elsif ($counter == 2) {
                                    $second = $item->{value};
                                    return 0 unless ($first =~ /FAMT/ && $second =~ /AMOR/);
                                }
                                else {
                                    return 0;
                                }
                            }
                        }
                    }
                }
                return 1;
            },
            err => 'C72',
        },

        # �10
        # ��� ������ ���������� � ������� �������� ������ ���� ������ �������� ����������� �����
        # ���������, �.�. � ������ ���������� ��������������������� �2 ���������� ���� :20C::RELA
        # ������ �������������� � ����� � ������ � ����� ���������� ��������������������� �2� �������;
        # � ��������� ����������� �2� ���� :20C::RELA �� ����������� (��� ������ �73).
        # ��� ������ ���������� � ������� �������� ������ ���� ������ �������� ����������� �����
        # ���������, �.�. � ������ ���������� ��������������������� � ���������� ���� :20C::RELA
        # ������ �������������� � ����� � ������ � ����� ���������� ��������������������� �1 �������;
        # � ��������� ����������� �2� ���� :20C::RELA �� ����������� (��� ������ �73).
        {
            func => sub {
                my $doc = shift;
                my $tree = build_seq_tree($doc->data);
                # ������������ �� ������ �� B2a
                my $branches_b = $tree->{'STAT'} || [];                      # B
                for my $b_b (@$branches_b) {
                    my $branches_b2 = $b_b->{'TRAN'} || [];                  # B2
                    for my $b_b2 (@$branches_b2) {
                        my $branches_b2a = $b_b2->{'LINK'} || [];            # B2a
                        my $counter = 0;
                        for my $b (@$branches_b2a) {
                            $counter++ if (seq_key_value_exists($b->{fields}, '20C', ':RELA'));
                        }
                        return 0 if ($counter != 1);
                    }
                }
                # ������������ �� ������ �� C1
                my $branches_c = $tree->{'TRANS'} || [];           # C
                for my $b_c (@$branches_c) {
                    my $branches_c1 = $b_c->{'LINK'} || [];        # C1
                    my $counter = 0;
                    for my $b (@$branches_c1) {
                        $counter++ if (seq_key_value_exists($b->{fields}, '20C', ':RELA'));
                    }
                    return 0 if ($counter != 1);
                }
                return 1;
            },
            err => 'C73',
        },
    ]
};

# ������ ������ (���)�������������������. � ������ ������� ����� ��� ����, ��� ������ ������������������
# ���������� � ���� 16R � ������������� ����� 16S � ����� �� �����������. ������������������ ����� ���� ����������.
sub build_seq_tree {
    my $data = shift;
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
            push @{$tree->{$inner_seq_name}}, build_seq_tree($inner_seq);
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

    return $tree;
}

# ���������� ������ (�����) � ������� ������ �����.
sub flatten_tree {
    my $tree = shift;
    my $items = [];

    for my $key (keys %$tree) {
        if ($key eq 'fields') {
            for my $item (@{$tree->{$key}}) {
                push @$items, $item;
            }
        }
        else {
            for my $branch (@{$tree->{$key}}) {
                my $branch_items = flatten_tree($branch);
                push @$items, @$branch_items;
            }
        }
    }

    return $items;
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