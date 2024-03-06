package CyberFT::SWIFT::Types::MT430;
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
    ],
    rules => [
        #C1 Если в каком-либо из повторений последовательности А присутствует поле 33а, то в том же повторении этой последовательности должно также присуствовать поле 32а
        # (Код ошибки С09)
			{
				func => sub {
					my $doc = shift;
					my $v_20 = _find_sequences($doc->data);
					#my $v_21 = $doc->get_all('21');
					#my $v_32 = $doc->get_all('(32A|32B|32K)');
					my $counter = 0;
					for my $seq (@$v_20){
					$counter += 1;
					if( seq_key_exists($seq,'33[AK]') && !seq_key_exists($seq,'32[AK]') )
					{
						#return(0, "Field 33a exists, but field 32a missed");
						#warn 'At sequence number '.$counter.' Field 33a exists, but field 32a missed';
						return 0;
					}
                    my $check = {
                        '20' => 0,
                        '21' => 0,
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
                    #if ($counter > 10 || scalar(@$v_21) > 10 || scalar(@$v_32) > 10)
					#{
                    #    return 0;
                    #}
                }
                return 1;
            },
            err => 'C09',
        },

        #C2 В сообщении обязательно должно присутствовать хотя бы одно из необязательных полей 32а или 74
        # (Код ошибки С26)
        {
            func => sub {
                my $doc = shift;
                if( !$doc->key_exists('32[AK]') && !$doc->key_exists('74') ) {
                    return 0;
                }
                return 1;
            },
            err => 'C26',
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
        if ($k =~ /^20$/) {
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