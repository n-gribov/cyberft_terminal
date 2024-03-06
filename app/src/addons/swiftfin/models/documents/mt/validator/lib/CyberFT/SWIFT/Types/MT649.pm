package CyberFT::SWIFT::Types::MT649;
use CyberFT::SWIFT::Types::Utils;
use strict;
use Data::Dumper;
our $ValidationProfile = {
    
    fields => [
        {   
            key => '20',
            required => 1,
        },
        {   
            key => '88D',
            required => 1,
        },
        {   
            key => '79',
            required => 1,
        },

    ],
    
    rules => [
        # C1 At least one of the fields 21 or 29B must be present (Error code(s): C35).
        {
            func => sub {
                my $doc = shift;
                if(( not $doc->key_exists('21') ) and (not $doc->key_exists('29B'))){
                    return 0;
                }
                return 1;
            },
            err => 'C35',
        },   
    ],
};

sub _check_seq {
    my $v_SEQ = shift;
    my $check_rules = shift;
    my $counter = 0;
    for my $seq (@$v_SEQ){
        $counter += 1;
        my $check = { %$check_rules };
        for my $element (@$seq){
            for my $key_el (keys %$check){
                if ($element->{key} =~ m/^$key_el$/){
                    if ($element->{value} ne ''){
                        $check->{$key_el} = 1;
                    }
                }
            }
        }
        
        for my $key_el (keys %$check){
            if ($check->{$key_el} == 0){
                my $problem_seq = "";
                for my $element (@$seq){
                    $problem_seq .= $element->{key}." ";
                }
                my $res = [0, "Missing required field ($problem_seq sequence, repetition $counter): $key_el"];
                return $res;
            }
        }
    }
    return 1;
}
sub _find_sequences {
    my $data = shift;
    my $val = shift;
    my $seqs = [];
    my $cur_seq = undef;
    my $cur_seq_code = undef;
    for my $item (@$data) {
        my $k = $item->{key};
        if ($k =~ /^$val$/) {
            
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