package CyberFT::SWIFT::Types::MT644;
use CyberFT::SWIFT::Types::Utils;
use strict;
use Data::Dumper;
our $ValidationProfile = {
    
    fields => [
        # #seq A
        {   
            key => '20',
            # allow_empty => 1,
            required => 1,
        },
        {   
            key => '88D',
            required => 1,
        },
        {   
            key => '32A',
            required => 1,
        },

    ],
    
    rules => [
        #Sequence checker
        {
            func => sub {
                my $doc = shift;
                my $v_all_seq = _find_sequences($doc->data,'26[NP]');
                if(not $doc->key_exists('26[NP]')){
                    return (0,'Missing field mandatory sequence 26a');
                }
                 # die Dumper(${@{@$v_A[2]}[0]}{key});
                my $v_A = [];
                for my $seqseq (@$v_all_seq){
                    if ( ${@{$seqseq}[0]}{key} eq '26N' or ${@{$seqseq}[0]}{key} eq '26P' ){
                        push(@{$v_A},$seqseq);
                    }
                }
                my $res_A = _check_seq($v_all_seq,{
                        # '15A' => 0, #это поле может быть уже пустым, а его наличие мы уже проверили
                        '26[NP]' => 0,
                        '31F' => 0,
                        '37G' => 0, 
                    });
                if ($res_A != 1){
                    return @$res_A;
                }
                return 1;
            },
            err => '',
        },
        # C1 At least one of fields 21 or 29B must be present (Error code(s): C35).
        {
            func => sub {
                my $doc = shift;
                if((not $doc->key_exists('21')) and (not $doc->key_exists('29B'))){
                    return 0;
                }
                return 1;
            },
            err => 'C35',
        },            
        # С2 At least one of fields 36 or 37 (A-F) must be present in each sequence B within the message (Error code(s): C54).
        {
            func => sub {
                my $doc = shift;
                my $v_A = _find_sequences($doc->data,'26[NP]');
                for my $seq (@$v_A){
                    if( (not seq_key_exists($seq,'36') ) and (not seq_key_exists($seq, '37[ABCDEF]')) ){
                        return 0;
                    }
                }
                return 1;
            },
            err => 'C54',
        },
        # C3 In any sequence B, the currency code in fields 33B and 34a must be the same (Error code(s): C55).
        {
            func => sub {
                my $doc = shift;
                my $v_A = _find_sequences($doc->data,'26[NP]');
                for my $seq (@$v_A){
                    my $vs = seq_get_all($seq, '33B|34P|34R');
                    my $curr;
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
            err => 'C55',
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