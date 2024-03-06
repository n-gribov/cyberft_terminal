package CyberFT::SWIFT::Types::MT210;
use CyberFT::SWIFT::Types::Utils;
use strict;

our $ValidationProfile = {

    fields => [
        {
            key => '20',
            required => 1,
        },
        {
            key => '25',
            required => 0,
        },
        {
            key => '30',
            required => 1,
        },
        # -----> repetitive sequence starts
        {
            key => '21',
            required => 1,
        },
        {
            key => '32B',
            required => 1,
        },
        {
            key => '50a',
            key_regexp => '50[CF]?',
            required => 0,
        },
        {
            key => '52a',
            key_regexp => '52[AD]',
            required => 0,
        },
        {
            key => '56a',
            key_regexp => '56[AD]',
            required => 0,
        },
        # <----- repetitive sequence ends
    ],

    rules => [
        {
            func => sub {
                my $doc = shift;
                my $seqs = _get_sequences($doc->data);
                my $i = 0;
                for my $s (@$seqs) {
                    $i++;
                    unless ($s->{'32B'} =~ /\S/) {
                        return (0, "Missing required field (repetitive sequence #$i): 32B");
                    }
                }
                return 1;
            },
            err => 'Missing required field',
        },
    
        # С1. Повторяющаяся последовательность не должна использоваться более десяти раз
        # (Код ошибки Т10).
        {
            func => sub {
                my $doc = shift;
                my $fields_21 = $doc->get_all('21');
                return (scalar @$fields_21 <= 10);
            },
            err => 'T10',
        },

        # С2. В каждой повторяющейся последовательности должно присутствовать или поле 50а,
        # или поле 52а, но не оба эти поля вместе (Код ошибки С06).
        {
            func => sub {
                my $doc = shift;
                my $seqs = _get_sequences($doc->data);
                for my $s (@$seqs) {
                    my $k50a = grep /^50[CF]?$/, keys(%$s);
                    my $k52a = grep /^52[AD]$/, keys(%$s);
                    if (($k50a && $k52a) || (!$k50a && !$k52a)) {
                        return 0;
                    }
                }
                return 1;
            },
            err => 'C06',
        },

        # С3. Код валюты должен быть одинаковым во всех повторениях поля 32B в сообщении
        # (Код ошибки С02).
        {
            func => sub {
                my $doc = shift;

                my $values_32B = $doc->get_all('32B');
                return 1 if (scalar @$values_32B == 0);

                my ($first_curr) = $values_32B->[0] =~ /([A-Z]{3})/;
                for my $val (@$values_32B) {
                    my ($curr) = $val =~ /([A-Z]{3})/;
                    if ($curr ne $first_curr) {
                        return 0;
                    }
                }

                return 1;
            },
            err => 'C02',
        }
    ],

};

# Вытаскиваем все повторяющиеся последовательности как массив хэшей. Они начинаются с поля 21.
sub _get_sequences {
    my $data = shift;
    my $seqs = [];
    my $p;
    for my $item (@$data) {
        if ($item->{key} eq '21') {
            # начало последовательности
            push @$seqs, $p if (defined $p);
            $p = {};
        }
        if (defined $p) {
            $p->{$item->{key}} = $item->{value};
        }
    }
    push @$seqs, $p if ($p);
    return $seqs;
}


1;