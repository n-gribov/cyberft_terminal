package CyberFT::SWIFT::Types::MT516;
use CyberFT::SWIFT::Types::Utils;
use strict;
use Storable ();
use Data::Dumper;

our $ValidationProfile = {

    fields => [
        # Описаны только обязательные поля обязательных последовательностей.
        {
            key => '20',
            required => 1,
        },
        {
            key => '23',
            required => 1,
        },
        {
            key => '31P',
            required => 1,
        },
        {
            key => '35B',
            required => 1,
        },
        {
            key => '30',
            required => 1,
        },
    ],

    rules => [

        # С1
        # В сообщении должно присутствовать либо поле 35А, либо поле 35N (Код ошибки С07).
        {
            if => ['not_exists', '35A'],
            must => ['exists', '35N'],
            err => 'C07',
        },

        # C2
        # В последовательности А должно присутствовать либо поле 83С, либо поле 87а, но не оба эти
        # поля вместе (Код ошибки С67).
        {
            func => sub {
                my $doc = shift;

                my $exists_83C = seq_key_exists($doc->data, '83C');
                my $exists_87a = 0;
                for my $item (@{$doc->data}) {
                    if ($item->{key} =~ /87[AD]/) {
                        $exists_87a = 1;
                        last;
                    }
                    elsif ($item->{key} =~ /37J|26H|33S|32[AB]|37[ABCDEF]|57[ABD]|77D|72/) {
                        # если встретили одно из этих полей, значит это уже последовательность B или C.
                        last;
                    }
                }

                return 0 unless ($exists_83C || $exists_87a);
                return 0 if ($exists_83C && $exists_87a);

                return 1;
            },
            err => 'C67',
        }

    ]
};

1;