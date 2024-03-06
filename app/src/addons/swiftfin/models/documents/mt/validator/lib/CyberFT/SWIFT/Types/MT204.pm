package CyberFT::SWIFT::Types::MT204;
use CyberFT::SWIFT::Types::Utils;
use strict;

our $ValidationProfile = {

    fields => [
        {
            key => '20',
            required => 1,
        },
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
            key => '32B',
            required => 1,
        },
        {
            key => '53a',
            key_regexp => '53[ABD]',
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
                    for my $k ('20', '32B', '53[ABD]') {
                        unless (seq_key_exists($seq, $k)) {
                            my $f = $k;
                            if ($f =~ /^(\d+)\[[A-Z-]+\]$/) {
                                $f = $1.'a';
                            }
                            return (0, "Missing required field (repetitive sequence B #$i): $f");
                        }
                    }
                }
                return 1;
            },
            err => 'Missing required field',
        },
    
        # C1
        # Сумма в поле 19 должна равняться результату сложения сумм, указанных во всех повторениях
        # поля 32В (Код ошибки С01).
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

        # С2
        # Код валюты должен быть одинаковым во всех повторениях поля 32В в сообщении (Код ошибки С02).
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

        # Повторяющаяся последовательность не должна использоваться более десяти раз (Код ошибки Т10).
        {
            func => sub {
                my $doc = shift;
                my $v_20 = $doc->get_all('20');
                return 0 if (scalar @$v_20 > 11); # Первое поле '20' не относится к повтор. последов.
                return 1;
            },
            err => 'T10',
        },
    ],

};

sub _find_seqs {
    my $doc = shift;
    my $seqs = [];
    my $counter = 0;
    my $s;
    for my $item (@{$doc->data}) {
        if ($item->{key} eq '20') {
            $counter++;
            if ($counter > 1) { # Пропускаем последовательность A
                $s = [];
                push @$seqs, $s;
            }
        }
        if (defined $s) {
            push @$s, $item;
        }
    }
    return $seqs;
}

1;