package CyberFT::SWIFT::Types::MT456;
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
             key => '32a',
			 key_regexp => '32[AB]',
             required => 1,
         },
         {   
             key => '33D',
             required => 1,
         },
		 {   
             key => '77A',
             required => 1,
         },
         {   
             key => '77D',
             required => 1,
         },

    ],
    
    rules => [
		# С2 Повторяющаяся последовательность не должна использоваться более десяти раз
        # (Код ошибки Т10)
        {
            func => sub {
                my $doc = shift;
                my $v_20 = _find_sequences($doc->data);
                my $counter = 0;
                for my $seq (@$v_20){
                    $counter += 1;
                    my $check = {
                        '20' => 0,
                        '21' => 0,
                        '32[AB]' => 0,
                        '33D' => 0,
                        '77A' => 0,
                        '77D' => 0,
                    };
                    for my $element (@$seq){
                        for my $key_el (keys %$check){
                            if ($element->{key} =~ m/^$key_el$/){
                                $check->{$key_el} = 1;
                            }
                        }
                    }
                    
                    # for my $key_el (keys %$check){
                    #     if ($check->{$key_el} == 0){
                    #         my $problem_seq = "";
                    #         for my $element (@$seq){
                    #             $problem_seq .= $element->{key}." ";
                    #         }
                    #         # return (0, "Missing required field ($problem_seq sequence, repetition $counter): $key_el");
                    #     }
                    # }
                    if ($counter > 10){
                        return 0;
                    }
                }
                return 1;
            },
            err => 'T10',
        },
        # Если в сообщении присутствует поле 71В, то суммы, указанные в полях 32а и 33D,
        # должны быть различны (Код ошибки С49)
        {
            func => sub {
                my $doc = shift;
				my $v20 = _find_sequences($doc->data);
				for my $s (@$v20) 
				{
					my ($sum_32a) = (seq_get_first($s, '32[AB]') =~ /[A-Z]{3}([\d\.\,]+)/);
					my ($sum_33d) = (seq_get_first($s, '33D') =~ /[A-Z]{3}([\d\.\,]+)/);
					#warn $sum_32a;
					#warn $sum_33d;
					if($sum_32a eq $sum_33d)
					{
						return 0;
					}
				}

                return 1;
            },
            err => 'C49',
        },  
        # Код валюты в полях сумм 32а и 33D должен быть одинаковым во всех повторениях
        # этих полей в сообщении (Код ошибки С02)
        {
            func => sub {
                my $doc = shift;
                my $vs = seq_get_all($doc->data, '32B|32A|33D');
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
                return 1;
            },
            err => 'C02',
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