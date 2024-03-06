package CyberFT::SWIFT::Types::MT203;
use CyberFT::SWIFT::Types::Utils;
use strict;

our $ValidationProfile = {

    fields => [
        {
            key => '19',
            required => 1,
        },
        {
            key => '30',
            required => 1,
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
            key => '32B',
            required => 1,
        },
        {
            key => '58a',
            key_regexp => '58[AD]',
            required => 1,
        },
    ],

    rules => [
        # �������� ������������ ����� � ������������� ������������������
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_seqs($doc);
                my $i = 0;
                for my $seq (@$seqs) {
                    $i++;
                    for my $k ('20', '21', '32B', '58[AD]') {
                        unless (seq_key_exists($seq, $k)) {
                            my $f = $k;
                            if ($f =~ /^(\d+)\[[A-Z-]+\]$/) {
                                $f = $1.'a';
                            }
                            return (0, "Missing required field (repetitive sequence #$i): $f");
                        }
                    }
                }
                return 1;
            },
            err => 'Missing required field',
        },
    
        # �1
        # ����� � ���� 19 ������ ��������� ���������� �������� ����, ��������� �� ���� �����������
        # ���� 32� (��� ������ �01).
        {
            func => sub {
                my $doc = shift;
                my $values_32b = $doc->get_all('32B');
                my $value_19 = $doc->get_first('19');

                my ($sum_19) = ($value_19 =~ /([\d\.\,]+)/);
                $sum_19 =~ s/,/./;
                $sum_19 = sprintf("%.4f", $sum_19);

                my $sum_32b = 0;
                for my $val (@$values_32b) {
                    my ($v) = ($val =~ /([\d\.\,]+)/);
                    $v =~ s/,/./;
                    $sum_32b += $v;
                }
                $sum_32b = sprintf("%.4f", $sum_32b);

                return 0 if ($sum_32b != $sum_19);
                return 1;
            },
            err => 'C01',
        },

        # �2
        # ��� ������ � ���� ����� 32� ������ ���� ���������� �� ���� ����������� ����� ���� � ���������
        # (��� ������ �02).
        {
            func => sub {
                my $doc = shift;
                my $values = $doc->get_all('32B');
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

        # �3
        # ������������� ������������������ ������ �������������� �� ����� ���� ���, �� �� �����
        # ������ ��� (���� ������ �11 � �10, ��������������).
        {
            func => sub {
                my $doc = shift;
                my $v_20 = $doc->get_all('20');
                return 0 if (scalar @$v_20 < 2);
                return 1;
            },
            err => 'T11',
        },
        {
            func => sub {
                my $doc = shift;
                my $v_20 = $doc->get_all('20');
                return 0 if (scalar @$v_20 > 10);
                return 1;
            },
            err => 'T10',
        },

        # �4
        # ���� � ����������� �� �������� ������������ ���� 56�, �� ���� 57� ����� ������ ��������������
        # (��� ������ �81).
        {
            func => sub {
                my $doc = shift;
                my $data = $doc->data;
                my ($seq_started, $found56a, $found57a);
                for my $item (@$data) {
                    if ($item->{key} eq '20') {
                        if ($seq_started) {
                            return 0 if ($found56a && !$found57a);
                        }
                        $seq_started = 1;
                        $found56a = 0;
                        $found57a = 0;
                    }
                    elsif ($item->{key} =~ '56[AD]') {
                        $found56a = 1;
                    }
                    elsif ($item->{key} =~ '57[ABD]') {
                        $found57a = 1;
                    }
                }
                if ($seq_started) {
                    return 0 if ($found56a && !$found57a);
                }
                return 1;
            },
            err => 'C81',
        },
    ],

};

sub _find_seqs {
    my $doc = shift;
    my $seqs = [];
    my $s;
    for my $item (@{$doc->data}) {
        if ($item->{key} eq '20') {
            $s = [];
            push @$seqs, $s;
        }
        if (defined $s) {
            push @$s, $item;
        }
    }
    return $seqs;
}

1;