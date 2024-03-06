package CyberFT::SWIFT::Types::MT564;
use CyberFT::SWIFT::Types::Utils;
use strict;
use Storable ();
use Data::Dumper;

our $ValidationProfile = {

    fields => [
        # ������� ������ ������������ ���� ������������ �������������������.
        {
            key => '16R',
            required => 1,
        },
        {
            key => '16S',
            required => 1,
        },
        # A
        {
            key => '20C',
            required => 1,
        },
        {
            key => '23G',
            required => 1,
        },
        {
            key => '22F',
            required => 1,
        },
        {
            key => '25D',
            required => 1,
        },
        # B
        {
            key => '35B',
            required => 1,
        },
        {
            key => '97a',
            key_regexp => '97[AC]',
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
                        required_fields => ['20C', '23G', '22F', '25D'],
                    },
                    'GENL/LINK' => {
                        name => 'A1',
                        required => 0,
                        required_fields => ['20C'],
                    },
                    'USECU' => {
                        name => 'B',
                        required => 1,
                        required_fields => ['35B'],
                    },
                    'USECU/FIA' => {
                        name => 'B1',
                        required => 0,
                        required_fields => [],
                    },
                    'USECU/ACCTINFO' => {
                        name => 'B2',
                        required => 1,
                        required_fields => ['97[AC]'],
                    },
                    'INTSEC' => {
                        name => 'C',
                        required => 0,
                        required_fields => ['35B', '98[AB]'],
                    },
                    'CADETL' => {
                        name => 'D',
                        required => 0,
                        required_fields => [],
                    },
                    'CAOPTN' => {
                        name => 'E',
                        required => 0,
                        required_fields => ['13A', '22F', '17B'],
                    },
                    'CAOPTN/SECMOVE' => {
                        name => 'E1',
                        required => 0,
                        required_fields => ['22[FH]', '35B', '98[ABCD]'],
                    },
                    'CAOPTN/SECMOVE/FIA' => {
                        name => 'E1a',
                        required => 0,
                        required_fields => [],
                    },
                    'CAOPTN/CASHMOVE' => {
                        name => 'E2',
                        required => 0,
                        required_fields => ['22[FH]', '98[ABC]'],
                    },
                    'ADDINFO' => {
                        name => 'F',
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
        # ���� ���� :19B::RESU ������ ����� ����������� ������������ � ������������������� �2,
        # ����� ���� :92�::EXCH ����� ����������� ����� ������ �������������� � ��� ��
        # (���)������������������. ���� ���� ������ ����� ����������� �����������, �� ����
        # ����� ����������� �������� �������������� (��� ������ �62).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);

                my $branches_e = $tree->{'CAOPTN'} || [];
                for my $branch_e (@$branches_e) {
                    my $branches_e2 = $branch_e->{'CASHMOVE'} || [];
                    for my $branch_e2 (@$branches_e2) {
                        if (seq_key_value_exists($branch_e2->{fields}, '19B', ':RESU')) {
                            return 0 unless (seq_key_value_exists($branch_e2->{fields}, '92B', ':EXCH'));
                        }
                    }
                }

                return 1;
            },
            err => 'E62',
        },

        # �2
        # ���� ����� ���� �� �����������, �� ���� ���� � ����� �� ���������� ���������������������
        # �2 ������������ ���� :97C::SAFE//GENR, ��:
        # � ��������������������� �2 ����������� � ����� �� ������ ����������� � ���� ���������
        # � � ��������������������� �2 ����������� � ����� �� ������ �������������� ���� 93a
        # �������� ������ �����
        # � � ��������������������� �1 ��������� ������ ����� �� ������ �������������� ���� 36B
        # ����������� ����������� �����������
        # � � ��������������������� �2 ��������� �������� ������� �� ������ �������������� ���� 19�
        # ������ (��� ������ �94).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);

                my $branches_b2 = $tree->{'USECU'}->[0]->{'ACCTINFO'} || [];
                if (scalar @$branches_b2 == 0) {
                    return 0;
                }

                my $flag = 0;
                for my $branch_b2 (@$branches_b2) {
                    if (seq_key_value_exists($branch_b2->{fields}, '97C', ':SAFE//GENR')) {
                        $flag = 1;
                        last;
                    }
                }

                if ($flag) {
                    if (scalar @$branches_b2 != 1) {
                        return 0;
                    }
                    if (seq_key_exists($branches_b2->[0]->{fields}, '93[A-Z]')) {
                        return 0;
                    }
                    my $branches_e = $tree->{'CAOPTN'} || [];
                    for my $branch_e (@$branches_e) {
                        my $branches_e1 = $branch_e->{'SECMOVE'} || [];
                        for my $branch_e1 (@$branches_e1) {
                            if (seq_key_exists($branch_e1->{fields}, '36B')) {
                                return 0;
                            }
                        }
                        my $branches_e2 = $branch_e->{'CASHMOVE'} || [];
                        for my $branch_e2 (@$branches_e2) {
                            if (seq_key_exists($branch_e2->{fields}, '19B')) {
                                return 0;
                            }
                        }
                    }
                }

                return 1;
            },
            err => 'E94',
        },

        # �3
        # ���� � ������������������ � ������������ ���� :23G:REPE, ��: ����� ������������
        # ������������������ �, ���� :36a::QINT �������� ������������ (��� ������ �02).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);

                my $branch_a = $tree->{'GENL'}->[0] || {};
                if (seq_key_value_exists($branch_a->{fields}, '23G', 'REPE')) {
                    my $branch_c = $tree->{'INTSEC'}->[0] || undef;
                    if (defined $branch_c) {
                        unless (seq_key_value_exists($branch_c->{fields}, '36[A-Z]', ':QINT')) {
                            return 0;
                        }
                    }
                }

                return 1;
            },
            err => 'E02',
        },

        # �4
        # ���� � ������������������ � ������������ ���� :22F::CAEV//OTHR, �� � ��������� ������
        # �������������� ������������������ F � ���� �� ���� ���� :70E::ADTX ������ ��������������
        # � ��������� ��� ������ �03.
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);

                my $branch_a = $tree->{'GENL'}->[0] || {};
                if (seq_key_value_exists($branch_a->{fields}, '22F', ':CAEV//OTHR')) {
                    my $branch_f = $tree->{'ADDINFO'}->[0] || undef;
                    unless (defined $branch_f) {
                        return 0;
                    }
                    unless (seq_key_value_exists($branch_f->{fields}, '70E', ':ADTX')) {
                        return 0;
                    }
                }

                return 1;
            },
            err => 'E03',
        },

        # �5
        # ���� � ����� �� ���������� ������������������ � ������������ ���� :22F::CA��//OTHR, �� �
        # ��� �� ���������� ������������������ � ���� :70E::ADTX �������� ������������ (��� ������ �79).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);

                my $branches_e = $tree->{'CAOPTN'} || [];
                for my $branch_e (@$branches_e) {
                    if (seq_key_value_exists($branch_e->{fields}, '22F', ':CAOP//OTHR')) {
                        unless (seq_key_value_exists($branch_e->{fields}, '70E', ':ADTX')) {
                            return 0;
                        }
                    }
                }

                return 1;
            },
            err => 'E79',
        },

        # �6
        # � ����� ���������� ������������������ � � ��������������������� �2,:
        # ���� ������������ ���� ::92J::TAX�, �� � ��� �� ���������� ���� ������������������ ������
        # �������������� ���� :92F::GRSS (��� ������ �80).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);

                my $branches_e = $tree->{'CAOPTN'} || [];
                for my $branch_e (@$branches_e) {
                    if (seq_key_value_exists($branch_e->{fields}, '92J', ':TAXE')) {
                        unless (seq_key_value_exists($branch_e->{fields}, '92F', ':GRSS')) {
                            return 0;
                        }
                    }
                    my $branches_e2 = $branch_e->{'CASHMOVE'} || [];
                    for my $branch_e2 (@$branches_e2) {
                        if (seq_key_value_exists($branch_e2->{fields}, '92J', ':TAXE')) {
                            unless (seq_key_value_exists($branch_e2->{fields}, '92F', ':GRSS')) {
                                return 0;
                            }
                        }
                    }
                }

                return 1;
            },
            err => 'E80',
        },

        # �7
        # ���� � ������������������ � ������������ ���� :22F::CAEV//RHDI, �� ������������������ �
        # �� ������������.
        # ���� � ������������������ � ������������ ���� :22F::CAEV//RHTS, �� ������������������ �
        # �������� ������������.
        # ���� � ������������������ � ������������ ���� :22F::CAEV//INFO, �� ��������
        # ������������������ � � ������������������ E �� ����������� (��� ������: E01).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);

                my $branch_a = $tree->{'GENL'}->[0] || {};
                if (seq_key_value_exists($branch_a->{fields}, '22F', ':CAEV//RHDI')) {
                    if (defined $tree->{'INTSEC'}->[0]) {
                        return 0;
                    }
                }
                elsif (seq_key_value_exists($branch_a->{fields}, '22F', ':CAEV//RHTS')) {
                    unless (defined $tree->{'INTSEC'}->[0]) {
                        return 0;
                    }
                }
                elsif (seq_key_value_exists($branch_a->{fields}, '22F', ':CAEV//INFO')) {
                    if (defined $tree->{'INTSEC'}->[0]) {
                        return 0;
                    }
                    if (defined $tree->{'CAOPTN'}->[0]) {
                        return 0;
                    }
                }

                return 1;
            },
            err => 'E01',
        },

        # �8
        # � ����� ���������� ������������������ � � ��������������������� �2
        #   * ���� :92F::GRSS ����� ���� ������������ ������ ���� ��� � ���� :92K::GRSS ����� ����
        #     ������������ ������ ���� ��� � ��� ��� ���� :92F::GRSS � :92K::GRSS �� ����� ����
        #     ������������ ������������,
        #   * ���� :92F::NETT ����� ���� ������������ ������ ���� ��� � ���� :92K::NETT ����� ����
        #     ������������ ������ ���� ��� � ��� ��� ���� :92F::NETT � :92K::NETT �� ����� ����
        #     ������������ ������������,
        # � ����� ���������� ��������������������� �1 � ��������������������� �2 ���� :92A::TAXC
        # ����� ���� ������������ ������ ���� ��� � ���� :92F::TAXC ����� ���� ������������ ������
        # ���� ��� � ���� :92K::TAXC ����� ���� ������������ ������ ���� ��� � ��� ��� ��� ����
        # :92A::TAXC, :92F::TAXC � :92K::TAXC �� ����� ���� ������������ �� ��� � �� ��� ������������
        # (��� ������ �77).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);

                my $branches_e = $tree->{'CAOPTN'} || [];
                for my $branch_e (@$branches_e) {
                    my $cnt1 = seq_key_value_count($branch_e->{fields}, '92F', ':GRSS');
                    my $cnt2 = seq_key_value_count($branch_e->{fields}, '92K', ':GRSS');
                    if ($cnt1 + $cnt2 > 1) {
                        return 0;
                    }
                    my $branches_e2 = $branch_e->{'CASHMOVE'} || [];
                    for my $branch_e2 (@$branches_e2) {
                        my $cnt1 = seq_key_value_count($branch_e2->{fields}, '92F', ':GRSS');
                        my $cnt2 = seq_key_value_count($branch_e2->{fields}, '92K', ':GRSS');
                        if ($cnt1 + $cnt2 > 1) {
                            return 0;
                        }
                    }
                }
                for my $branch_e (@$branches_e) {
                    my $cnt1 = seq_key_value_count($branch_e->{fields}, '92F', ':NETT');
                    my $cnt2 = seq_key_value_count($branch_e->{fields}, '92K', ':NETT');
                    if ($cnt1 + $cnt2 > 1) {
                        return 0;
                    }
                    my $branches_e2 = $branch_e->{'CASHMOVE'} || [];
                    for my $branch_e2 (@$branches_e2) {
                        my $cnt1 = seq_key_value_count($branch_e2->{fields}, '92F', ':NETT');
                        my $cnt2 = seq_key_value_count($branch_e2->{fields}, '92K', ':NETT');
                        if ($cnt1 + $cnt2 > 1) {
                            return 0;
                        }
                    }
                }
                for my $branch_e (@$branches_e) {
                    my $branches_e1 = $branch_e->{'SECMOVE'} || [];
                    for my $branch_e1 (@$branches_e1) {
                        my $cnt1 = seq_key_value_count($branch_e1->{fields}, '92A', ':TAXC');
                        my $cnt2 = seq_key_value_count($branch_e1->{fields}, '92F', ':TAXC');
                        my $cnt3 = seq_key_value_count($branch_e1->{fields}, '92K', ':TAXC');
                        if ($cnt1 + $cnt2 + $cnt3 > 1) {
                            return 0;
                        }
                    }
                    my $branches_e2 = $branch_e->{'CASHMOVE'} || [];
                    for my $branch_e2 (@$branches_e2) {
                        my $cnt1 = seq_key_value_count($branch_e2->{fields}, '92A', ':TAXC');
                        my $cnt2 = seq_key_value_count($branch_e2->{fields}, '92F', ':TAXC');
                        my $cnt3 = seq_key_value_count($branch_e2->{fields}, '92K', ':TAXC');
                        if ($cnt1 + $cnt2 + $cnt3 > 1) {
                            return 0;
                        }
                    }
                }

                return 1;
            },
            err => 'E77',
        },

        # �9
        # � ����� ���������� ������������������ � � ��������������������� �2:
        #   * ���� ���� :92J::GRSS ������������ ����� ������ ���� � ���������, �� ��� ������� ����������
        #     ���� :92J::GRSS �������� ������� ���� ���� ������ ������ ���� ��������.
        #   * ���� ���� :92J::TAXE ������������ ����� ������ ���� � ���������, �� ��� ������� ����������
        #     ���� :92J::TAXE �������� ������� ���� ���� ������ ������ ���� ��������.
        #   * ���� ���� :92J::NETT ������������ ����� ������ ���� � ���������, �� ��� ������� ����������
        #     ���� :92J::NETT �������� ������� ���� ���� ������ ������ ���� ��������.
        # � ����� ���������� ��������������������� E1 � ��������������������� �2, ���� ���� :92J::TAXC
        # ������������ ����� ������ ���� � ���������, �� ��� ������� ���������� ���� :92J::TAXC ��������
        # ������� ���� ���� ������ ������ ���� ��������.
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);

                my $branches_e = $tree->{'CAOPTN'} || [];

                for my $code (qw'GRSS TAXE NETT') {
                    for my $branch_e (@$branches_e) {
                        my $dict = {};
                        my $vals = seq_get_all($branch_e->{fields}, '92J');
                        for my $v (@$vals) {
                            if ($v =~ /:$code\/[^\/]*?\/([^\/]{4})/) {
                                my $k = $1;
                                if ($dict->{$k} == 1) {
                                    return 0;
                                }
                                else {
                                    $dict->{$k} = 1;
                                }
                            }
                        }
                        my $branches_e2 = $branch_e->{'CASHMOVE'} || [];
                        for my $branch_e2 (@$branches_e2) {
                            my $dict = {};
                            my $vals = seq_get_all($branch_e2->{fields}, '92J');
                            for my $v (@$vals) {
                                if ($v =~ /:$code\/[^\/]*?\/([^\/]{4})/) {
                                    my $k = $1;
                                    if ($dict->{$k} == 1) {
                                        return 0;
                                    }
                                    else {
                                        $dict->{$k} = 1;
                                    }
                                }
                            }
                        }
                    }
                }

                for my $branch_e (@$branches_e) {
                    my $branches_e1 = $branch_e->{'SECMOVE'} || [];
                    for my $branch_e1 (@$branches_e1) {
                        my $dict = {};
                        my $vals = seq_get_all($branch_e1->{fields}, '92J');
                        for my $v (@$vals) {
                            if ($v =~ /:TAXC\/[^\/]*?\/([^\/]{4})/) {
                                my $k = $1;
                                if ($dict->{$k} == 1) {
                                    return 0;
                                }
                                else {
                                    $dict->{$k} = 1;
                                }
                            }
                        }
                    }
                    my $branches_e2 = $branch_e->{'CASHMOVE'} || [];
                    for my $branch_e2 (@$branches_e2) {
                        my $dict = {};
                        my $vals = seq_get_all($branch_e2->{fields}, '92J');
                        for my $v (@$vals) {
                            if ($v =~ /:TAXC\/[^\/]*?\/([^\/]{4})/) {
                                my $k = $1;
                                if ($dict->{$k} == 1) {
                                    return 0;
                                }
                                else {
                                    $dict->{$k} = 1;
                                }
                            }
                        }
                    }
                }

                return 1;
            },
            err => 'E78',
        },

        # �10
        # ���� ��������� ������������ ��� ������, ����������� � �������� ������� ��� �����������,
        # �.�. ���� ���� 23 G �������� ���������� �������� ��� REPL, REPE ��� RMDR, � ��������� ������
        # �������������� ���� �� ���� ��������������������� �1 �������, � � ����� � ������ � �����
        # ���������� �1 ������ �������������� ���� :20C::PREV. ��������������, � ��������� �����������
        # �1 ���� :20C::PREV �� �����������.
        # ���� ��������� ������������ ��� ������ ��� ������, �.�. ���� ���� 23 G �������� ����������
        # �������� ��� CANC ��� WITH, � ��������� ����� �������������� ��������������
        # ��������������������� �1 �������, � � ����� � ������ � ����� ���������� �1 �����
        # �������������� ���� :20C::PREV. ��������������, � ��������� ����������� �1 ����
        # :20C::PREV �� ����������� (��� ������ :�08).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $fields_a = $tree->{'GENL'}->[0]->{fields} || [];
                my $branches_a1 = $tree->{'GENL'}->[0]->{'LINK'} || [];
                if (seq_key_value_exists($fields_a, '23G', 'REPL|REPE|RMDR')) {
                    return 0 if (scalar @$branches_a1 < 1);
                    my $count = 0;
                    for my $b (@$branches_a1) {
                        if (seq_key_value_exists($b->{fields}, '20C', ':PREV')) {
                            $count++;
                        }
                    }
                    return 0 if ($count != 1);
                }
                elsif (seq_key_value_exists($fields_a, '23G', 'CANC|WITH')) {
                    my $count = 0;
                    for my $b (@$branches_a1) {
                        if (seq_key_value_exists($b->{fields}, '20C', ':PREV')) {
                            $count++;
                        }
                    }
                    return 0 if ($count > 1);
                }
                return 1;
            },
            err => 'E08',
        },

        # �11
        # ���� � ������������������ D ������������ ���� :70E::NAME, �� � ������������������ �
        # ������ �������������� ���� :22F::CAEV//CHAN, � � ������������������ D ������ ��������������
        # ���� :22F::CHAN//NAME (��� ������ D99).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $branch_a = $tree->{'GENL'}->[0] || {};
                my $branch_d = $tree->{'CADETL'}->[0] || {};

                if (seq_key_value_exists($branch_d->{fields}, '70E', ':NAME')) {
                    unless (seq_key_value_exists($branch_a->{fields}, '22F', ':CAEV//CHAN')) {
                        return 0;
                    }
                    unless (seq_key_value_exists($branch_d->{fields}, '22F', ':CHAN//NAME')) {
                        return 0;
                    }
                }
                else {
                    if (seq_key_value_exists($branch_a->{fields}, '22F', ':CAEV//CHAN')) {
                        return 0;
                    }
                    if (seq_key_value_exists($branch_d->{fields}, '22F', ':CHAN//NAME')) {
                        return 0;
                    }
                }

                return 1;
            },
            err => 'D99',
        },

        # �12
        # ���� � ������������������ � ������������ ���� :22F::CAEV//RHDI, �� ������ ��������������
        # ������������������ D � ���� :22F::RHDI ������ �������������� � ������������������ D
        # (��� ������ �06).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $branch_a = $tree->{'GENL'}->[0] || {};
                my $branch_d = $tree->{'CADETL'}->[0] || {};

                if (seq_key_value_exists($branch_a->{fields}, '22F', ':CAEV//RHDI')) {
                    unless (seq_key_value_exists($branch_d->{fields}, '22F', ':RHDI')) {
                        return 0;
                    }
                }

                return 1;
            },
            err => 'E06',
        },

        # �13
        # � ����� ���������� ��������������������� �2 ���� :93B::ELIG �� ����� �������������� �����
        # ���� ���. ���� ��� ���� ������������ ������, �� � ������ ������ ��� ���� ���������� ������
        # ���� �FAMT�, � �� ������ ������ ��� ���� ���������� ������ ���� �AMOR� (��� ������ �71).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);

                my $branches_b2 = $tree->{'USECU'}->[0]->{'ACCTINFO'} || [];
                for my $b (@$branches_b2) {
                    my $counter = 0;
                    my ($first, $second);
                    for my $item (@{$b->{fields}}) {
                        if ($item->{key} =~ /93B/ && $item->{value} =~ /^:ELIG/) {
                            $counter++;
                            $first = $item->{value} if ($counter==1);
                            $second = $item->{value} if ($counter==2);
                            return 0 if ($counter==2 && ($first !~ /\/FAMT/ || $second !~ /\/AMOR/));
                            return 0 if ($counter>2);
                        }
                    }
                }

                return 1;
            },
            err => 'C71',
        },

        # �14
        # � ����� ���������� ��������������������� E1 ���� :36B::ENTL �� ����� �������������� �����
        # ���� ���. ���� ��� ���� ������������ ������, �� � ������ ������ ��� ���� ���������� ������
        # ���� �FAMT�, � �� ������ ������ ��� ���� ���������� ������ ���� �AMOR� (��� ������ �72).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);

                my $branches_e = $tree->{'CAOPTN'} || [];
                for my $branch_e (@$branches_e) {
                    my $branches_e1 = $branch_e->{'SECMOVE'} || [];
                    for my $b (@$branches_e1) {
                        my $counter = 0;
                        my ($first, $second);
                        for my $item (@{$b->{fields}}) {
                            if ($item->{key} =~ /36B/ && $item->{value} =~ /^:ENTL/) {
                                $counter++;
                                $first = $item->{value} if ($counter==1);
                                $second = $item->{value} if ($counter==2);
                                return 0 if ($counter==2 && ($first !~ /\/FAMT/ || $second !~ /\/AMOR/));
                                return 0 if ($counter>2);
                            }
                        }
                    }
                }

                return 1;
            },
            err => 'C72',
        },

        # �15
        # ���� ��������� ������������ ��� ������ (23G::REPL) �� � ������������������ � � ����
        # :25D::PROC �� ����������� ������������� ���� �ENTL� � � ������������������ D � ����
        # :22F::ADDB �� ����������� ������������� ���� �CAPA�. ���� ��������� ������������ ���
        # ����������� � �������� ������� (23G::REPE), �� � ������������������ A � ���� :25D::PROC
        # �� ����������� ������������� ���� �ENTL� � � ������������������ D � ���� :22F::ADDB
        # ����������� ������������� ��������������� ���� �CAPA�. (��� ������: �09).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $fields_a = $tree->{'GENL'}->[0]->{fields} || [];
                my $fields_d = $tree->{'CADETL'}->[0]->{fields} || [];
                if (seq_key_value_exists($fields_a, '23G', 'REPL')) {
                    return 0 if (seq_key_value_exists($fields_a, '25D', ':PROC.*ENTL'));
                    return 0 if (seq_key_value_exists($fields_d, '22F', ':ADDB.*CAPA'));
                }
                elsif (seq_key_value_exists($fields_a, '23G', 'REPE')) {
                    return 0 if (seq_key_value_exists($fields_a, '25D', ':PROC.*ENTL'));
                }
                return 1;
            },
            err => 'E09',
        },

        # �16
        # ���� ��������� �������� �������������� ������ ������� (23 G::ADDB), �� ������������������
        # D ������� �������������� ��������� ������������, � � ������������������ D ������������
        # ���� :22F::ADDB. ��� ���� ����� �������������� ������ ���� ��� � ������ ��������� ���� ��
        # ��������� �����: CLAI (���������� ��� �����������), TAXR (������� �������) ��� REVR
        # (����������� ��� �������� ��������) (��� ������ �11).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $fields_a = $tree->{'GENL'}->[0]->{fields} || [];
                my $fields_d = $tree->{'CADETL'}->[0]->{fields} || [];
                if (seq_key_value_exists($fields_a, '23G', 'ADDB')) {
                    return 0 unless (seq_key_value_count($fields_d, '22F', '^:ADDB') == 1);
                    return 0 unless (seq_key_value_exists($fields_d, '22F', '^:ADDB.*(CLAI|TAXR|REVR)'));
                }
                return 1;
            },
            err => 'E11',
        },

        # C17.
        # ���� � ����� ���������� ������������������ � ��������������������� �1 � �2 �����������,
        # ����� � ������������������ D ���� :98a::PAYD �������� ��������������, �� ���� ���������
        # ������� ���� :98a::PAYD � ������������������ D �� ����������� (��� ������ �24).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $branch_d = $tree->{'CADETL'}->[0] || undef;
                my $branches_e = $tree->{'CAOPTN'} || [];

                if (defined $branch_d) {
                    my $flag = 0;
                    for my $branch_e (@$branches_e) {
                        my $branches_e1 = $branch_e->{'SECMOVE'} || [];
                        my $branches_e2 = $branch_e->{'CASHMOVE'} || [];
                        if (scalar(@$branches_e1) > 0 || scalar(@$branches_e2) > 0) {
                            $flag = 1;
                            last;
                        }
                    }
                    if ($flag) {
                        if (seq_key_value_exists($branch_d->{fields}, '98[A-Z]', ':PAYD')) {
                            return 0;
                        }
                    }
                }

                return 1;
            },
            err => 'E24',
        },

        # C18.
        # ���� ��������� ����� (:23G::NEWM) � � ������������������ A ���� :25D::PROC ������ ���
        # �ENTL�, �� ������������������ D ������� �������������� ��������� �������� ������������ �
        # � ������������������ D ���� :22F::ADDB �������� ������������ � � ����� ���������� ����
        # :22F::ADDB ������ ���� ������ ��� �CAPA� (��� ������: �22).
        # ���� ��������� ����� (:23G::NEWM) � � ������������������ A � ���� :25D::PROC ��� �ENTL�
        # �� ������, �� � ������������������ D ������� �������������� ��������� ���� :22F::ADDB ���
        # �CAPA� �� ����������� (��� ������: �22).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $fields_a = $tree->{'GENL'}->[0]->{fields} || [];
                my $fields_d = $tree->{'CADETL'}->[0]->{fields} || [];
                if (seq_key_value_exists($fields_a, '23G', 'NEWM')) {
                    if (seq_key_value_exists($fields_a, '25D', ':PROC.*ENTL')) {
                        return 0 unless (seq_key_value_exists($fields_d, '22F', '^:ADDB.*CAPA'));
                    }
                    else {
                        return 0 if (seq_key_value_exists($fields_d, '22F', '^:ADDB.*CAPA'));
                    }
                }
                return 1;
            },
            err => 'E22',
        },

        # �19
        # � ����� ���������� ������������������ E ���� ������������ ���� :92B::IDFX ����� ������,
        # ����������� ���������, ������ �������������� ������������������ D, � ���� :92a::DEVI
        # ������������ ������ ������ �������������� � ������������������ D (��� ������ E21).
        {
            func => sub {
                my $doc = shift;
                my $tree = _get_seq_tree(doc => $doc);
                my $fields_d = $tree->{'CADETL'}->[0]->{fields} || [];
                my $branches_e = $tree->{'CAOPTN'} || [];

                my $flag = 0;
                for my $branch_e (@$branches_e) {
                    if (seq_key_value_exists($branch_e->{fields}, '92B', ':IDFX')) {
                        $flag = 1;
                        last;
                    }
                }
                if ($flag) {
                    unless (seq_key_value_exists($fields_d, '92[A-Z]', ':DEVI')) {
                        return 0;
                    }
                }

                return 1;
            },
            err => 'E21',
        },

    ]
};

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

# ���������� ������ (�����) � ������� ������ �����.
sub _flatten_tree {
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
                my $branch_items = _flatten_tree($branch);
                push @$items, @$branch_items;
            }
        }
    }

    return $items;
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