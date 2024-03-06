package CyberFT::SWIFT::Types::MT605;
use CyberFT::SWIFT::Types::Utils;
use strict;
our $ValidationProfile = {
    
    fields => [
        {   
            key => '20',
            required => 1,
        },
        {   
            key => '26C',
            required => 1,
        },
        {   
            key => '30',
            required => 1,
        },
        {   
            key => '21',
            required => 1,
        },
        {   
            key => '23',
            required => 1,
        },
        {   
            key => '32F',
            required => 1,
        },
        {   
            key => '87a',
            key_regexp => '87[ABD]',
            required => 1,
        },
    ],
    
    rules => [
        # C1
        # The repetitive sequence must appear at least once, but not more than ten times (Error code(s): T10)
        # Повторяющаяся последовательность не должна использоваться более десяти раз (Код ошибки Т10).
        {
            func => sub {
                my $doc = shift;
                my $v_20 = _find_sequences($doc->data);
                my $counter = 0;
                for my $seq (@$v_20){
                    $counter += 1;
                    my $check = {
                        '21' => 0,
                        '23' => 0,
                        '32F' => 0,
                        '87[ABD]' => 0,
                    };
                    for my $element (@$seq){
                        for my $key_el (keys %$check){
                            if ($element->{key} =~ m/^$key_el$/){
                                $check->{$key_el} = 1;
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
                    if ($counter > 10){
                        return 0;
                    }
                }
                return 1;
            },
            err => 'T10',
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
        if ($k =~ /^21$/) {
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