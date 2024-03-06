package CyberFT::SWIFT::Types::MT559;
use CyberFT::SWIFT::Types::Utils;
use strict;
use Storable ();
use Data::Dumper;

our $ValidationProfile = {

    fields => [
        # Описаны только обязательные поля.
        {
            key => '19',
            required => 1,
        },
        {
            key => '23',
            required => 1,
        },
        {
            key => '20',
            required => 1,
        },
        {
            key => '35A',
            required => 1,
        },
        {
            key => '35B',
            required => 1,
        },
        {
            key => '34A',
            required => 1,
        },
    ],

    rules => [
        # Проверка обязательных полей в повторяющейся последовательности
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_seqs($doc);
                my $i = 0;
                for my $seq (@$seqs) {
                    $i++;
                    for my $k ('20', '35A', '35B', '34A') {
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

        # С1
        # Повторяющаяся последовательность не должна использоваться более десяти раз (Код ошибки Т10).
        {
            func => sub {
                my $doc = shift;
                my $count = scalar(@{$doc->get_all('20')});
                if ($count > 10) {
                    return 0
                }
                return 1;
            },
            err => 'T10',
        },
        
        # Проверка обязательных полей повторяющейся последовательности
        {
            func => sub {
                my $doc = shift;
                
                my $seqs = [];
                my $started = 0;
                for my $f (@{$doc->data()}) {
                    if ($f->{key} =~ /20/) {
                        push @$seqs, [];
                        $started = 1;
                    }
                    if ($started) {
                        push @{$seqs->[scalar(@$seqs)-1]}, $f;
                    }
                }
                my $counter = 0;
                my @req = ('35A', '35B', '34A');
                for my $seq (@$seqs) {
                    $counter++;
                    for my $k (@req) {
                        unless (seq_key_exists($seq, $k)) {
                            return (0, "Missing required field in sequence #$counter: $k");
                        }
                    }
                }
                return 1;
            },
            err => 'Missing required field',
        },

        # С2
        # Сумма в поле 19 должна равняться результату сложения сумм во всех повторениях поля 34А
        # (Код ошибки С01).
        {
            func => sub {
                my $doc = shift;

                my ($sum_19) = $doc->get_first('19') =~ /([\d\.\,]+)/;
                $sum_19 =~ s/,/./;
                $sum_19 = sprintf("%.4f", $sum_19);

                my $values_34A = $doc->get_all('34A');
                my $sum_34A = 0;
                for my $v (@$values_34A) {
                    my ($n) = ($v =~ /([\d\.\,]+)/);
                    $n =~ s/,/./;
                    $sum_34A += $n;
                }
                $sum_34A = sprintf("%.4f", $sum_34A);

                if ($sum_19 != $sum_34A) {
                    return 0;
                }

                return 1;
            },
            err => 'C01',
        },

        # С3
        # В сообщении может присутствовать либо поле 53а, либо поле 57а, но не оба эти поля вместе
        # (Код ошибки С14).
        {
            func => sub {
                my $doc = shift;

                if ($doc->key_exists('53[A-Z]') && $doc->key_exists('57[A-Z]')) {
                    return 0;
                }

                return 1;
            },
            err => 'C14',
        },

        # С4
        # Код валюты в поле суммы 34А должен быть одинаковым во всех повторениях этого поля в
        # сообщении (Код ошибки С02).
        {
            func => sub {
                my $doc = shift;
                my $values = $doc->get_all('34A');
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