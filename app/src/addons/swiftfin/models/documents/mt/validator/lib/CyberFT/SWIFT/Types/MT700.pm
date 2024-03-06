package CyberFT::SWIFT::Types::MT700;
use CyberFT::SWIFT::Types::Utils;
use strict;
our $ValidationProfile = {
    
    fields => [
        {   
            key => '27',
            required => 1,
            allow_empty => 1,
        },
        {   
            key => '40A',
            required => 1,
        },
        {   
            key => '20',
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
        
        #C1 - В сообщении может присутствовать либо поле 39A, либо поле 39B, но не оба эти поля
        # вместе (Код ошибки D05)
        {
            func => sub {
                my $doc = shift;
                if(( $doc->key_exists('39A')) and $doc->key_exists('39B')){
                    return 0;
                }
                return 1;
            },
            err => 'D05',
        },
        # C2  Поля 42C и 42a, если они используются, должны присутствовать вместе (Код ошибки
        # C90).
        {
            func => sub {
                my $doc = shift;
                if($doc->key_exists('42C')){
                    if(not $doc->key_exists('42[AD]')){
                        return 0;
                    }
                }

                if($doc->key_exists('42[AD]')){
                    if(not $doc->key_exists('42C')){
                        return 0;
                    }
                }
                return 1;
            },
            err => 'C90',
        },
        # C3  Могут присутствовать либо поля 42C и 42a вместе, либо только поле 42M, либо только
        # поле 42P. Никакие другие комбинации этих полей не допускаются (Код ошибки C90).
        {
            func => sub {
                my $doc = shift;
                if($doc->key_exists('42C')){
                    if(not $doc->key_exists('42[AD]')){
                        return 0;
                    } else {
                        if($doc->key_exists('42M') or $doc->key_exists('42P')){
                            return 0;
                        }
                    }
                }

                if($doc->key_exists('42[AD]')){
                    if(not $doc->key_exists('42C')){
                        return 0;
                    } else {
                        if($doc->key_exists('42M') or $doc->key_exists('42P')){
                            return 0;
                        }
                    }
                }

                if($doc->key_exists('42M')){
                    if($doc->key_exists('42C') or $doc->key_exists('42[AD]') or $doc->key_exists('42P')){
                        return 0;
                    }
                }


                if($doc->key_exists('42P')){
                    if($doc->key_exists('42C') or $doc->key_exists('42[AD]') or $doc->key_exists('42M')){
                        return 0;
                    }
                }
                return 1;
            },
            err => 'C90',
        },
        # C4  В сообщении может присутствовать либо поле 44C, либо поле 44D, но не оба эти поля
        # вместе (Код ошибки D06)
        {
            func => sub {
                my $doc = shift;
                if($doc->key_exists('44C') and $doc->key_exists('44D')){
                        return 0;
                }

                return 1;
            },
            err => 'D06',
        },

    ],
};

# Вытаскиваем основные последовательности как хэш. Каждая последовательность начинается
# с поля 15 c буквенной опцией. Например, поле 15B - это начало последовательности "B".
sub _find_sequences {
    my $data = shift;
    my $seqs = {};
    my $cur_seq = undef;
    my $cur_seq_code = undef;
    for my $item (@$data) {
        my $k = $item->{key};
        if ($k =~ /^15([A-Z])$/) {
            $cur_seq_code = $1;
            $cur_seq = [];
            push @{$seqs->{$cur_seq_code}}, $cur_seq;
        }
        if ($cur_seq) {
            push @$cur_seq, $item;
        }
    }
    return $seqs;
}

1;