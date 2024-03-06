package CyberFT::SWIFT::Types::MT110;
use CyberFT::SWIFT::Types::Utils;
use strict;
use Data::Dumper;

our $ValidationProfile = {

    fields => [
        # ������� ������ ������������ ���� ������������ �������������������.
        {
            key => '20',
            required => 1,
        },
        {
            key => '21',
            required => 1,
        },
        {
            key => '30',
            required => 1,
        },
        {
            key => '32a',
            key_regexp => '32[AB]',
            required => 1,
        },
        {
            key => '59',
            key_regexp => '59',
            required => 0,
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
                    for my $k ('21', '30', '32[AB]') {
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
        # ������������ ������������������ �� ������ ����������� ����� ������ ��� (��� ������ �10).
        {
            func => sub {
                my $doc = shift;
                my $v_21 = $doc->get_all('21');
                return 0 if (scalar @$v_21 > 10);
                return 1;
            },
            err => 'T10',
        },

        # �2
        # ��� ������ � ���� �������� ����� 32� ������ ���� ���������� �� ���� ����������� ����,
        # �������������� � ��������� (��� ������ �02).
        {
            func => sub {
                my $doc = shift;
                my $values = $doc->get_all('32[AB]');
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
    ]

};

sub _find_seqs {
    my $doc = shift;
    my $seqs = [];
    my $s;
    for my $item (@{$doc->data}) {
        if ($item->{key} eq '21') {
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