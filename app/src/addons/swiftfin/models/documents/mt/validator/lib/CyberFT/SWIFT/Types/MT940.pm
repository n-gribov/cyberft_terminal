package CyberFT::SWIFT::Types::MT940;
use CyberFT::SWIFT::Types::Utils;
use strict;

our $ValidationProfile = {

    fields => [
        {
            key => '20',
            required => 1,
        },
        {
            key => '21',
            required => 0,
        },
        {
            key => '25',
            required => 1,
        },
        {
            key => '28C',
            required => 1,
        },
        {
            key => '60a',
            key_regexp => '60[FM]',
            required => 1,
        },
        # -----> repetitive sequence starts
        {
            key => '61',
            required => 0,
        },
        {
            key => '86',
            required => 0,
        },
        # <----- repetitive sequence ends
        {
            key => '62a',
            key_regexp => '62[FM]',
            required => 1,
        },
        {
            key => '64',
            required => 0,
        },
        # -----> repetitive sequence starts
        {
            key => '65',
            required => 0,
        },
        # <----- repetitive sequence ends
        {
            key => '86',
            required => 0,
        },
    ],

    rules => [

        # C1. Если поле 86 присутствует в каком-либо из повторений многократной последовательности,
        # ему должно предшествовать поле 61. Кроме того, если в сообщении содержится поле 86, оно
        # должно присутствовать на той же странице (в том же сообщении) выписки, что и
        # соответствующее ему поле 61 (Код ошибки С24).
        {
            func => sub {
                my $doc = shift;
                my $data = $doc->data;

                # Достанем последовательность между обязательными полями 60a и 62a.
                my @seq = ();
                my $seq_started = 0;
                for my $f (@$data) {
                    if ($seq_started) {
                        last if ($f->{key} =~ /^62[FM]$/);
                        push @seq, $f;
                    }
                    elsif ($f->{key} =~ /^60[FM]$/) {
                        $seq_started = 1;
                    }
                }

                # Проверим, что перед каждым полем 86 в последовательности есть поле 61.
                for my $i (0 .. $#seq) {
                    if ($seq[$i]->{key} eq '86' && ($i == 0 || $seq[$i-1]->{key} ne '61')) {
                        return 0;
                    }
                }

                return 1;
            },
            err => 'С24',
        },

        # C2. Первые две буквы трехзначного кода валюты в полях 60a, 62a, 64 и 65 должны быть
        # одинаковыми во всех повторениях этого поля в сообщении (Код ошибки С27).
        {
            func => sub {
                my $doc = shift;

                my $values = $doc->get_all('(60[FM]|62[FM]|64|65)');
                return 1 if (scalar @$values == 0);

                my ($first_code) = $values->[0] =~ /^\S{7}(\S{2})/;
                for my $val (@$values) {
                    my ($code) = $val =~ /^\S{7}(\S{2})/;
                    if ($code ne $first_code) {
                        return 0;
                    }
                }

                return 1;
            },
            err => 'С27',
        },
    ]
};

1;