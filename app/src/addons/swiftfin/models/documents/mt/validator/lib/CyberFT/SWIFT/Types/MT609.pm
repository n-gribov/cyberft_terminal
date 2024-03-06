package CyberFT::SWIFT::Types::MT609;
use CyberFT::SWIFT::Types::Utils;
use strict;
our $ValidationProfile = {
    
    fields => [
        {   
            key => '27',
            required => 1,
        },
        {   
            key => '20',
            required => 1,
        },
        {   
            key => '31C',
            required => 1,
        },
        {   
            key => '23',
            required => 1,
        },
        {   
            key => '26C',
            required => 1,
        },
        {   
            key => '68a',
            key_regexp => '68[BC]',
            required => 1,
        },

    ],
    
    rules => [
        # Ñ1 Field 68B must be used when the immediately preceding field 23 contains either SPOTS or FORWARDS (Error
        # code(s): C85).
        {
            func => sub {
                my $doc = shift;
                my $v_20 = _find_sequences($doc->data);
                my $counter = 0;
                for my $seq (@$v_20){
                    $counter += 1;
                    my $check = {
                        '23' => 0,
                        '26C' => 0,
                        '68[BC]' => 0,
                    };
                    my $field_23_stop_word  = 0;
                    my $field_68B_exists = 0;
                    for my $element (@$seq){
                        for my $key_el (keys %$check){
                            if ($element->{key} =~ m/^$key_el$/){
                                $check->{$key_el} = 1;
                            }
                            if ($element->{key} eq "23"){
                                if($element->{value} eq 'SPOTS' or $element->{value} eq 'FORWARDS'){
                                    $field_23_stop_word = 1;
                                }
                            }
                            if ($element->{key} eq '68B'){
                                $field_68B_exists = 1;
                            }

                        }
                    }
                    
                    for my $key_el (keys %$check){
                        if ($check->{$key_el} == 0){
                            my $problem_seq = "";
                            for my $element (@$seq){
                                $problem_seq .= $element->{key}." ";
                            }
                            return (0, "Missing required field ($problem_seq sequence, repetition $counter): $key_el");
                        }
                    }
                    if ($field_23_stop_word == 1){
                        unless ($field_68B_exists == 1){
                            return 0;
                        }
                    }
                }
                return 1;
            },
            err => 'C85',
        },
    ],
};
sub _find_sequences {
    my $data = shift;
    my $seqs = [];
    my $cur_seq = undef;
    my $cur_seq_code = undef;
    for my $item (@$data) {
        my $k = $item->{key};
        if ($k =~ /^23$/) {
            $cur_seq_code = $1;
            $cur_seq = [];
            push @{$seqs}, $cur_seq;
        }
        if ($cur_seq) {
            push @$cur_seq, $item;
        }
    }
    return $seqs;
}

1;