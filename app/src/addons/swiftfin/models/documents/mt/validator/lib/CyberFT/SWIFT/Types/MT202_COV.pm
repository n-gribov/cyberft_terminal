package CyberFT::SWIFT::Types::MT202_COV;
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
        {
            key => '32A',
            required => 1,
        },
        {
            key => '58a',
            key_regexp => '58[AD]',
            required => 1,
        },
        {
            key => '50a',
            key_regexp => '50[AFK]',
            required => 1,
        },
        {
            key => '59a',
            key_regexp => '59A?',
            required => 1,
        },
    ],

    rules => [
        # Проверка обязательных полей
        {
            func => sub {
                my $doc = shift;
                my $seq_a = _find_a($doc);
                unless (seq_key_exists($seq_a, '20')) {
                    return 0, 'Missing required field from sequence A: 20';
                }
                unless (seq_key_exists($seq_a, '21')) {
                    return 0, 'Missing required field from sequence A: 21';
                }
                unless (seq_key_exists($seq_a, '32A')) {
                    return 0, 'Missing required field from sequence A: 32A';
                }
                unless (seq_key_exists($seq_a, '58[AD]')) {
                    return 0, 'Missing required field from sequence A: 58a';
                }
                my $seq_b = _find_b($doc);
                if (scalar @$seq_b == 0) {
                    return 0, 'Missing required sequence B';
                }
                unless (seq_key_exists($seq_b, '59A?')) {
                    return 0, 'Missing required field from sequence B: 59a';
                }
                return 1;
            },
            err  => 'Missing required sequence or field',
        },

        # С1. Если в последовательности А присутствует поле 56а, то поле 57а также должно
        # присутствовать в последовательности А (Код ошибки С81).
        {
            func => sub {
                my $doc = shift;
                my $seq_a = _find_a($doc);
                if (seq_key_exists($seq_a, '56[AD]')) {
                    return 0 unless (seq_key_exists($seq_a, '57[ABD]'));
                }
                return 1;
            },
            err  => 'C81',
        },

        # С2. Если в последовательности B присутствует поле 56а, то поле 57а также должно
        # присутствовать в последовательности B (Код ошибки С68).
        {
            func => sub {
                my $doc = shift;
                my $seq_b = _find_b($doc);
                if (seq_key_exists($seq_b, '56[ACD]')) {
                    return 0 unless (seq_key_exists($seq_b, '57[ABCD]'));
                }
                return 1;
            },
            err  => 'C68',
        },
    ],

};

sub _find_a {
    my $doc = shift;
    my $seq = [];
    for my $field (@{$doc->{data}}) {
        if ($field->{key} =~ /^50[AFK]/) {
            last;
        }
        push @$seq, $field;
    }
    return $seq;
}

sub _find_b {
    my $doc = shift;
    my $seq = [];
    my $started = 0;
    for my $field (@{$doc->{data}}) {
        if ($field->{key} =~ /^50[AFK]/) {
            $started = 1;
        }
        if ($started) {
            push @$seq, $field;
        }
    }
    return $seq;
}

1;