package CyberFT::SWIFT::Types::MT600;
use CyberFT::SWIFT::Types::Utils;
use strict;
our $ValidationProfile = {
    
    fields => [
        {   
            key => '15A',
            required => 1,
            allow_empty => 1,
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
            key => '22',
            required => 1,
        },
        {   
            key => '82a',
            key_regexp => '82[ADJ]',
            required => 1,
        },
        {   
            key => '87a',
            key_regexp => '87[ADJ]',
            required => 1,
        },
        {   
            key => '30',
            required => 1,
        },
        {   
            key => '26C',
            required => 1,
        },
        {   
            key => '33G',
            required => 1,
        },
        # {   
        #     key => '15B',
        #     required => 1,
        #     allow_empty => 1,
        # },
        # {   
        #     key => '32F',
        #     required => 1,
        # },
        # {   
        #     key => '87a',
        #     key_regexp => '87[ABD]',
        #     required => 1,
        # },
        # {   
        #     key => '34P',
        #     required => 1,
        # },
        # {   
        #     key => '57a',
        #     key_regexp => '57[ABD]',
        #     required => 1,
        # },
        # {   
        #     key => '15C',
        #     required => 1,
        #     allow_empty => 1,
        # },
        # {   
        #     key => '32F',
        #     required => 1,
        # },
        # {   
        #     key => '87a',
        #     key_regexp => '87[ABD]',
        #     required => 1,
        # },
        # {   
        #     key => '34R',
        #     required => 1,
        # },
        # {   
        #     key => '57a',
        #     key_regexp => '57[ABD]',
        #     required => 1,
        # },        
    ],
    
    rules => [
        
        #C1 - Either sequence B or sequence C, but not both, must be present (Error code(s): C93).
        #     Либо последовательность B либо C, но не обе вместе - иначе ошибка C93
        {
            func => sub {
                my $doc = shift;
                
                my $seqs = _find_sequences($doc->data);
                my $reqs = {
                    # 'A' => [qw( 20 21 22 82[ADJ] 87[ADJ] 30 26C 33G )],
                    'B' => [qw( 32F 87[ABD] 34P 57[ABD])],
                    'C' => [qw( 32F 87[ABD] 34R 57[ABD])],
                };
                my $seq_exists = {};
                for my $l (keys %$reqs) {

                    my $fields = $reqs->{$l};
                    my $seq = $seqs->{$l}->[0] || undef;
                    # my $flag = 0;
                    # for my $k (@$fields) {
                    #     # print @$seq." - ".$k." exists=".seq_key_exists($seq, $k)."\n";
                    #     unless (seq_key_exists($seq, $k)) {
                    #         $flag = 1;
                    #     }
                    # }
                    if (defined $seq){
                        $seq_exists->{$l} = 1;
                    } else {
                        $seq_exists->{$l} = 0;
                    }
                    # warn $l;
                    # warn $seq_exists->{$l};
                }
                # warn Dumper($seq_exists);
                # warn Dumper($seqs);
                if (  ($seq_exists->{B} == 1 and $seq_exists->{C} == 1) 
                      or ($seq_exists->{B} == 0 and $seq_exists->{C} == 0)
                    ){
                    return 0;
                }
                return 1;
            },
            err => 'C93',
        },
        # C2
        # In the optional sequences, all fields with status M must be present (Error code(s): C32).
        # в опциональных последовательностях - все поля обозначенные М - должны быть - иначе код ошибки C32
        {
            func => sub {
                my $doc = shift;
                
                my $seqs = _find_sequences($doc->data);
                my $reqs = {
                    # 'A' => [qw( 20 21 22 82[ADJ] 87[ADJ] 30 26C 33G )],
                    'B' => [qw( 32F 87[ABD] 34P 57[ABD])],
                    'C' => [qw( 32F 87[ABD] 34R 57[ABD])],
                };
                for my $l (keys %$reqs) {
                    my $fields = $reqs->{$l};
                    my $seq = $seqs->{$l}->[0] || undef;
                    my $flag = 0;
                    if (defined $seq){
                        for my $k (@$fields) {
                            # print @$seq." - ".$k." exists=".seq_key_exists($seq, $k)."\n";
                            unless (seq_key_exists($seq, $k)) {
                                if( $l eq 'C' or $l eq 'B'){
                                    return (0, "C32");
                                } else {
                                    my $ks = $k;
                                    $ks =~ s/(\d+)(\[.*\])/\1a/;
                                    return (0, "Missing required field ($l sequence): $ks");
                                }
                            }

                        }
                    }
                }
                return 1;
            },
            err => 'C32',
        },        
        # C3
        # The currency in the amount fields 33G and 34P or 34R must be the same for all occurrences of these fields in the
        # message (Error code(s): C02).
        {
            func => sub {
                my $doc = shift;
                
                my $seqs = _find_sequences($doc->data);
                my $reqs = {
                    'A' => [qw( 20 21 22 82[ADJ] 87[ADJ] 30 26C 33G )],
                    'B' => [qw( 32F 87[ABD] 34P 57[ABD])],
                    'C' => [qw( 32F 87[ABD] 34R 57[ABD])],
                };
                my $curr;
                for my $l (keys %$reqs) {
                    my $fields = $reqs->{$l};
                    my $seq = $seqs->{$l}->[0] || undef;
                    my $vs = seq_get_all($seq, '33G|34P|34R');
                    for my $v (@$vs) {
                        my ($c) = $v =~ /([A-Z]{3})\d+/;
                        if (!$curr) {
                            $curr = $c;
                        } 
                        elsif ($curr ne $c) {
                            return 0;
                        }
                    }
                }
                return 1;
            },
            err => 'C02',
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