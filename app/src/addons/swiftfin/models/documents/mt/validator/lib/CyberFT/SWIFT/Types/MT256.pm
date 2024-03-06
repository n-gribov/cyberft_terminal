package CyberFT::SWIFT::Types::MT256;
use CyberFT::SWIFT::Types::Utils;
use strict;

our $ValidationProfile = {

    fields => [
        {
            key => '20',
            required => 1,
        },
        {
            key => '44A',
            required => 1,
        },
        {
            key => '23E',
            required => 1,
        },
        {
            key => '32J',
            required => 1,
        },
        {
            key => '32A',
            required => 1,
        },
    ],

    rules => [
        # �1.
        # ���� � ��������� ������������ ���� 37J, �� ������ �������������� ����� ���� 71G
        # (��� ������ �25).
        {
            func => sub {
                my $doc = shift;
                my $seqs = _get_sequences_B($doc->data);
                for my $s (@$seqs) {
                    return 0 if (exists($s->{'37J'}) && !exists($s->{'71G'}));
                }
                return 1;
            },
            err => 'E25',
        },

        # �2
        # �����, ��������� � ���� 71L � ������������������ �, ������ ���� ����� ����� ��������
        # �������� ���� ���������� ���� 71F � ������������������ � (��� ������ �26).
        {
            func => sub {
                my $doc = shift;
                my $values_71F = $doc->get_all('71F');
                my $value_71L = $doc->get_first('71L');

                my ($sum_71L) = ($value_71L =~ /[A-Z]{3}([\d\.\,]+)/);
                $sum_71L =~ s/,/./;
                $sum_71L = sprintf("%.4f", $sum_71L);

                my $sum_71F = 0;
                for my $val (@$values_71F) {
                    my ($v) = ($val =~ /[A-Z]{3}([\d\.\,]+)/);
                    $v =~ s/,/./;
                    $sum_71F += $v;
                }
                $sum_71F = sprintf("%.4f", $sum_71F);

                return 0 if ($sum_71F != $sum_71L);
                return 1;
            },
            err => 'E26',
        },

        # �3
        # �����, ��������� � ���� 71J ������������������ �, ������ ���� ����� ����� �������� ��������
        # ���� ���������� ���� 71G � ������������������ � (��� ������ �26).
        {
            func => sub {
                my $doc = shift;
                my $values_71G = $doc->get_all('71G');
                my $value_71J = $doc->get_first('71J');

                my ($sum_71J) = ($value_71J =~ /[A-Z]{3}([\d\.\,]+)/);
                $sum_71J =~ s/,/./;
                $sum_71J = sprintf("%.4f", $sum_71J);

                my $sum_71G = 0;
                for my $val (@$values_71G) {
                    my ($v) = ($val =~ /[A-Z]{3}([\d\.\,]+)/);
                    $v =~ s/,/./;
                    $sum_71G += $v;
                }
                $sum_71G = sprintf("%.4f", $sum_71G);

                return 0 if ($sum_71G != $sum_71J);
                return 1;
            },
            err => 'E26',
        },

        # �4
        # ��� ������ � ����� ���� (71F, 71G, 71H, 71J, 71K, 71L, 32A) ������ ���� ���������� �� ����
        # �������, ����� ��� ���� ������������ � ��������� (��� ������ �02).
        {
            func => sub {
                my $doc = shift;
                my $values = $doc->get_all('71[FGHJKL]|32A');
                my $check = '';
                for my $v (@$values) {
                    my ($cur) = $v =~ /([A-Z]{3})/;
                    if ($check) {
                        return 0 if ($check ne $cur);
                    }
                    else {
                        $check = $cur;
                    }
                }
                return 1;
            },
            err => 'C02',
        },

        # �5
        # ����� �� ���������� ������� �������, ����� ����� ���� ����� 32J � ������������������ �
        # ������ ���� ������� � ���� 32� ������������������ �. ���� 19 � ���� ������ �� ������������
        # (��� ������ D80).
        {
            func => sub {
                my $doc = shift;
                unless ($doc->key_exists('19')) {
                    my $values_32J = $doc->get_all('32J');
                    my $value_32A = $doc->get_first('32A');

                    my ($sum_32A) = ($value_32A =~ /[A-Z]{3}([\d\.\,]+)/);
                    $sum_32A =~ s/,/./;
                    $sum_32A = sprintf("%.4f", $sum_32A);

                    my $sum_32J = 0;
                    for my $val (@$values_32J) {
                        my ($v) = ($val =~ /([\d\.\,]+)/);
                        $v =~ s/,/./;
                        $sum_32J += $v;
                    }
                    $sum_32J = sprintf("%.4f", $sum_32J);

                    return 0 if ($sum_32J != $sum_32A);
                }
                return 1;
            },
            err => 'D80',
        },

        # �6
        # ���� ������� ����������, �� ���� 19 ������ ��������������, � � ��� ����������� ��������,
        # ������ ����� �������� ����� 32J ���� ���������� ������������������ � (��� ������ �01).
        {
            func => sub {
                my $doc = shift;
                if ($doc->key_exists('19')) {
                    my $values_32J = $doc->get_all('32J');
                    my $value_19 = $doc->get_first('19');

                    my ($sum_19) = ($value_19 =~ /[A-Z]{3}([\d\.\,]+)/);
                    $sum_19 =~ s/,/./;
                    $sum_19 = sprintf("%.4f", $sum_19);

                    my $sum_32J = 0;
                    for my $val (@$values_32J) {
                        my ($v) = ($val =~ /([\d\.\,]+)/);
                        $v =~ s/,/./;
                        $sum_32J += $v;
                    }
                    $sum_32J = sprintf("%.4f", $sum_32J);

                    return 0 if ($sum_32J != $sum_19);
                }
                return 1;
            },
            err => 'C01',
        },

        # �7
        # ���� 21 ������ �������������� ���� � ������������������ �, ���� � ������ �� ����������
        # ������������������ � (��� ������ �28).
        {
            func => sub {
                my $doc = shift;
                my $seq_A = _get_sequence_A($doc->data);
                my $seqs_B = _get_sequences_B($doc->data);
                if (defined $seq_A->{21}) {
                    for my $s (@$seqs_B) {
                        return 0 if (defined $s->{'21'});
                    }
                }
                else {
                    for my $s (@$seqs_B) {
                        return 0 unless (defined $s->{'21'});
                    }
                }
                return 1;
            },
            err => 'E28',
        },

        # �8
        # ���� ���� 71F ������������ ���� �� � ����� �� ���������� ������������������ �, �� ���� 71L
        # ������ �������������� � ������������������ �. � ��������� ������ ���� 71L �� ������������
        # (��� ������ �29).
        {
            if => ['exists', '71F'],
            must => ['exists', '71L'],
            err => 'E29',
        },
        {
            if => ['not_exists', '71F'],
            must => ['not_exists', '71L'],
            err => 'E29',
        },

        # �9
        # ���� ���� 71G ������������ ���� �� � ����� �� ���������� ������������������ �, �� ���� 71J
        # ������ �������������� � ������������������ �. � ��������� ������ ���� 71J �� ������������
        # (��� ������ �30).
        {
            if => ['exists', '71G'],
            must => ['exists', '71J'],
            err => 'E30',
        },
        {
            if => ['not_exists', '71G'],
            must => ['not_exists', '71J'],
            err => 'E30',
        },

        # �10
        # ���� ���� 71H ������������ ���� �� � ����� �� ���������� ������������������ �, �� ���� 71K
        # ������ �������������� � ������������������ �. � ��������� ������ ���� 71K �� ������������
        # (��� ������ �31).
        {
            if => ['exists', '71H'],
            must => ['exists', '71K'],
            err => 'E31',
        },
        {
            if => ['not_exists', '71H'],
            must => ['not_exists', '71K'],
            err => 'E31',
        },

        # �11
        # �����, ��������� � ���� 71� ������������������ �, ������ ���� ����� ����� �������� ����
        # ���������� ���� 71� � ������������������ � (��� ������ �32).
        {
            func => sub {
                my $doc = shift;
                my $values_71H = $doc->get_all('71H');
                my $value_71K = $doc->get_first('71K');

                my ($sum_71K) = ($value_71K =~ /[A-Z]{3}([\d\.\,]+)/);
                $sum_71K =~ s/,/./;
                $sum_71K = sprintf("%.4f", $sum_71K);

                my $sum_71H = 0;
                for my $val (@$values_71H) {
                    my ($v) = ($val =~ /[A-Z]{3}([\d\.\,]+)/);
                    $v =~ s/,/./;
                    $sum_71H += $v;
                }
                $sum_71H = sprintf("%.4f", $sum_71H);

                return 0 if ($sum_71H != $sum_71K);
                return 1;
            },
            err => 'E32',
        },
    ],

};

# ����������� ��� ������������� ������������������ B ��� ������ �����. ��� ���������� � ���� 44A.
# � ������������� ���, ��� ���������� ������������������ C, �.�. ����� ����� 32A.
sub _get_sequences_B {
    my $data = shift;
    my $seqs = [];
    my $p;
    for my $item (@$data) {
        if ($item->{key} eq '44A') {
            # ������ ������������������ B
            push @$seqs, $p if (defined $p);
            $p = {};
        }
        if ($item->{key} eq '32A') {
            # ������ ������������������ C
            push @$seqs, $p if (defined $p);
            $p = undef;
        }
        if (defined $p) {
            $p->{$item->{key}} = $item->{value};
        }
    }
    push @$seqs, $p if ($p);
    return $seqs;
}

sub _get_sequence_A {
    my $data = shift;
    my $seq = {};
    for my $item (@$data) {
        last unless ($item->{key} =~ /^(20|21)$/);
        $seq->{$item->{key}} = $item->{value};
        last if (exists($seq->{20}) && exists($seq->{21}));
    }
    return $seq;
}

1;