package CyberFT::SWIFT::Types::MT720;
use CyberFT::SWIFT::Types::Utils;
use strict;

our $ValidationProfile = {

    fields => [
        {
            key => '27',
            required => 1,
        },
        {
            key => '40B',
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
            key => '40E',
            required => 1,
        },
        {
            key => '31D',
            required => 1,
        },
        {
            key => '50',
            required => 1,
        },
        {
            key => '59',
            required => 1,
        },
        {
            key => '32B',
            required => 1,
        },
        {
            key => '41a',
            key_regexp => '41[AD]',
            required => 1,
        },
        {
            key => '49',
            required => 1,
        },
    ],

    rules => [
        # C1 В сообщении может присутствовать либо поле 39A, либо поле 39B, но не оба эти поля
        # вместе (Код ошибки D05).
        {
            func => sub {
                my $doc = shift;
                if ($doc->key_exists('39A') && $doc->key_exists('39B')) {
                    return 0;
                }
                return 1;
            },
            err => 'D05',
        },

        # C2 Поля 42C и 42a, если они используются, должны присутствовать вместе (Код ошибки
        # C90).
        {
            func => sub {
                my $doc = shift;
                if ($doc->key_exists('42C')) {
                    unless ($doc->key_exists('42[AD]')) {
                        return 0;
                    }
                }
                if ($doc->key_exists('42[AD]')) {
                    unless ($doc->key_exists('42C')) {
                        return 0;
                    }
                }
                return 1;
            },
            err => 'C90',
        },

        # C3 Могут присутствовать либо поля 42C и 42a вместе, либо только поле 42M, либо только
        # поле 42P. Никакие другие комбинации этих полей не допускаются (Код ошибки C90)
        {
            func => sub {
                my $doc = shift;
                if ($doc->key_exists('42C') || $doc->key_exists('42[AD]')) {
                    if ($doc->key_exists('42M') || $doc->key_exists('42P')) {
                        return 0;
                    } else {
                        return 1;
                    }
                }
                if ($doc->key_exists('42M')) {
                    if ($doc->key_exists('42C') || $doc->key_exists('42[AD]') || $doc->key_exists('42P')) {
                        return 0;
                    } else {
                        return 1;
                    }
                }
                if ($doc->key_exists('42P')) {
                    if ($doc->key_exists('42C') || $doc->key_exists('42[AD]') || $doc->key_exists('42M')) {
                        return 0;
                    } else {
                        return 1;
                    }
                }
                return 1;
            },
            err => 'C90',
        },

        # C4 Могут присутствовать либо поле 44C, либо поле 44D, но не оба эти поля вместе (Код ошибки D06).
        {
            func => sub {
                my $doc = shift;
                if ($doc->key_exists('44C') && $doc->key_exists('44D')) {
                    return 0;
                }
                return 1;
            },
            err => 'D06',
        },

        # С5 Могут присутствовать либо поле 52а «Банк-эмитент», либо поле 50В «Эмитент, не являющийся
        # банком», но не оба эти поля вместе (Код ошибки С06).
        {
            func => sub {
                my $doc = shift;
                if ($doc->key_exists('52[AD]') && $doc->key_exists('50B')) {
                    return 0;
                }
                return 1;
            },
            err => 'C06',
        },
    ],
};

1;