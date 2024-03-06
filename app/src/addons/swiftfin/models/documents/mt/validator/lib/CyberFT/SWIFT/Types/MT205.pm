package CyberFT::SWIFT::Types::MT205;
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
            required => 1,
        },
        # -----> repetitive sequence starts
        {
            key => '13C',
            required => 0,
        },
        # <----- repetitive sequence ends
        {
            key => '32A',
            required => 1,
        },
        {
            key => '52a',
            key_regexp => '52[AD]',
            required => 1,
        },
        {
            key => '53a',
            key_regexp => '53[ABD]',
            required => 0,
        },
        {
            key => '56a',
            key_regexp => '56[AD]',
            required => 0,
        },
        {
            key => '57a',
            key_regexp => '57[ABD]',
            required => 0,
        },
        {
            key => '58a',
            key_regexp => '58[AD]',
            required => 1,
        },
        {
            key => '72',
            required => 0,
        },
    ],

    rules => [
        # С1. Если в сообщении присутствует поле 56а, то обязательно должно присутствовать также и
        # поле 57а (Код ошибки С81).
        {
            if   => ['exists', '56[AD]'],
            must => ['exists', '57[ABD]'],
            err  => 'C81',
        },
    ],

};

1;