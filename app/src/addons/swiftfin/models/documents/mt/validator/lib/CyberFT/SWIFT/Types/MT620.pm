package CyberFT::SWIFT::Types::MT620;
use CyberFT::SWIFT::Types::Utils;
use strict;
use Data::Dumper;
our $ValidationProfile = {
    
    fields => [
        #seq A
        {   
            key => '15A',
            allow_empty => 1,
            required => 1,
        },
        #seq B
        {   
            key => '15B',
            allow_empty => 1,
            required => 1,
        },
        #seq C
        {   
            key => '15C',
            allow_empty => 1,
            required => 1,
        },
        #seq D
        {   
            key => '15D',
            allow_empty => 1,
            required => 1,
        },
        # {   
        #     key => '20',
        #     required => 1,
        # },
        # {   
        #     key => '22A',
        #     required => 1,
        # },
        # {   
        #     key => '22B',
        #     required => 1,
        # },
        # {   
        #     key => '82a',
        #     key_regexp => '82[ADJ]',
        #     required => 1,
        # },
        # {   
        #     key => '87a',
        #     key_regexp => '87[ADJ]',
        #     required => 1,
        # },
        # {   
        #     key => '26C',
        #     required => 1,
        # },

        # #seq b
        # {   
        #     key => '15B',
        #     allow_empty => 1,
        #     required => 1,
        # },
        # {   
        #     key => '17R',
        #     required => 1,
        # },
        # {   
        #     key => '30T',
        #     required => 1,
        # },
        # {   
        #     key => '30V',
        #     required => 1,
        # },
        # {   
        #     key => '30P',
        #     required => 1,
        # },
        # {   
        #     key => '32a',
        #     key_regexp => '32[BF]',
        #     required => 1,
        # },
        # {   
        #     key => '34a',
        #     key_regexp => '34[EJ]',
        #     required => 1,
        # },
        # {   
        #     key => '37G',
        #     required => 1,
        # },
        # {   
        #     key => '14D',
        #     required => 1,
        # },
        # # seq C
        # {   
        #     key => '15C',
        #     allow_empty => 1,
        #     required => 1,
        # },
        # {   
        #     key => '57a',
        #     key_regexp => '57[ADJ]',
        #     required => 1,
        # },
        # # seq D
        # {   
        #     key => '15D',
        #     allow_empty => 1,
        #     required => 1,
        # },
        # {   
        #     key => '57a',
        #     key_regexp => '57[ADJ]',
        #     required => 1,
        # },




        # # seq E
        # {   
        #     key => '15E',
        #     allow_empty => 1,
        #     required => 1,
        # },
        # {   
        #     key => '57a',
        #     key_regexp => '57[ADJ]',
        #     required => 1,
        # },
        # # seq F
        # {   
        #     key => '15F',
        #     allow_empty => 1,
        #     required => 1,
        # },
        # {   
        #     key => '57a',
        #     key_regexp => '57[ADJ]',
        #     required => 1,
        # },        
        # # seq G
        # {   
        #     key => '15G',
        #     allow_empty => 1,
        #     required => 1,
        # },
        # seq G1
        # {   
        #     key => '37L',
        #     required => 1,
        # },
        # {   
        #     key => '33a',
        #     key_regexp => '33[BJ]',
        #     required => 1,
        # },        
        # seq G2 - optional
        # seq H - optional
    ],
    
    rules => [
        #Sequence checker
        {
            func => sub {
                my $doc = shift;
                my $v_all_seq = _find_sequences($doc->data,'(15.)|(37L)');
                my $v_A =[];
                my $v_B =[];
                my $v_C =[];
                my $v_D =[];
                my $v_G1 =[];

                # if (scalar @$v_all_seq == 0 ){
                #     return (0, "Missing required field 15A");
                # }
                for my $seqseq (@$v_all_seq){
                    if ( ${@{$seqseq}[0]}{key} eq '15A'){
                        push(@{$v_A},$seqseq);
                    }
                    if ( ${@{$seqseq}[0]}{key} eq '15B'){
                        push(@{$v_B},$seqseq);
                    }
                    if ( ${@{$seqseq}[0]}{key} eq '15C'){
                        push(@{$v_C},$seqseq);
                    }
                    if ( ${@{$seqseq}[0]}{key} eq '15D'){
                        push(@{$v_D},$seqseq);
                    }
                    if ( ${@{$seqseq}[0]}{key} eq '37L'){
                        push(@{$v_G1},$seqseq);
                    }

                }
                # die Dumper(${@{@$v_A[2]}[0]}{key});
                my $res_A = _check_seq($v_A,{
                        # '15A' => 0, #это поле может быть уже пустым, а его наличие мы уже проверили
                        '20' => 0,
                        '22A' => 0,
                        '22B' => 0, 
                        '82[ADJ]' => 0,
                        '87[ADJ]' => 0,
                        '26C' => 0,
                    });
                my $res_B = _check_seq($v_B,{
                        # '15B' => 0,
                        '17R' => 0,
                        '30T' => 0,
                        '30V' => 0, 
                        '30P' => 0,
                        '32[BF]' => 0,
                        '34[EJ]' => 0,
                        '37G' => 0,
                        '14D' => 0,
                    });
                my $res_C = _check_seq($v_C,{
                        # '15C' => 0,
                        '57[ADJ]' => 0,
                    });
                my $res_D = _check_seq($v_D,{
                        # '15D' => 0,
                        '57[ADJ]' => 0,
                    });
                my $res_G1 = _check_seq($v_G1,{
                        '37L' => 0,
                        '33[BJ]' => 0,
                    });
                #better rework this part
                if ($res_A != 1){
                    return @$res_A;
                }
                if ($res_B != 1){
                    return @$res_B;
                }
                if ($res_C != 1){
                    return @$res_C;
                }
                if ($res_D != 1){
                    return @$res_D;
                }
                if ($res_G1 != 1){
                    return @$res_G1;
                }
                return 1;
            },
            err => '',
        },


        # C1 In sequence A, the presence of field 21 depends on the value of fields 22B and 22A as follows (Error code(s): D70):
        {
            func => sub {
                my $doc = shift;
                my $v_A = _find_sequences($doc->data,'15A');
                for my $seq (@$v_A){
                    if (seq_key_value_exists($seq, '22B', 'CONF') 
                        and (not seq_key_value_exists($seq, '22A', 'NEWT')) ){
                        if(not seq_key_exists($seq,'21')){
                            return 0;
                        }
                    }
                    if ( (not seq_key_value_exists($seq, '22B', 'CONF'))  ){
                        if(not seq_key_exists($seq,'21')){
                            return 0;
                        }
                    }


                }
                return 1;
            },
            err => 'D70',
        },
        # С2 In sequence A, if field 94A is present and contains AGNT, then field 21N in sequence A is mandatory,
        # otherwise field 21N is optional (Error code(s): D72):
        {
            func => sub {
                my $doc = shift;
                my $v_A = _find_sequences($doc->data,'15A');
                for my $seq (@$v_A){
                    if(seq_key_exists($seq,'94A') and seq_key_value_exists($seq, '94A', 'AGNT') and not seq_key_exists($seq,'21N')){
                        return 0;
                    }
                }
                return 1;
            },
            err => 'D72',
        },
        # C3 In sequence B, the presence of fields 32H, 32R and 30X depends on the value of field 22B 
        # in sequence A as follows (Error code(s): D56):
        {
            func => sub {
                my $doc = shift;

                my $v_all_seq = _find_sequences($doc->data,'(15.)|(37L)');
                my $v_A =[];
                my $v_B =[];
                my $v_C =[];
                my $v_D =[];
                my $v_G1 =[];
                for my $seqseq (@$v_all_seq){
                    if ( ${@{$seqseq}[0]}{key} eq '15A'){
                        push(@{$v_A},$seqseq);
                    }
                    if ( ${@{$seqseq}[0]}{key} eq '15B'){
                        push(@{$v_B},$seqseq);
                    }
                    if ( ${@{$seqseq}[0]}{key} eq '15C'){
                        push(@{$v_C},$seqseq);
                    }
                    if ( ${@{$seqseq}[0]}{key} eq '15D'){
                        push(@{$v_D},$seqseq);
                    }
                    if ( ${@{$seqseq}[0]}{key} eq '37L'){
                        push(@{$v_G1},$seqseq);
                    }

                }

                for my $seq_A (@$v_A){                
                    for my $seq (@$v_B){
                        if(seq_key_value_exists($seq_A, '22B', 'CONF')){
                            # warn Dumper($seq_A);
                            if (seq_key_exists($seq,'32[HR]')){
                                return 0;
                            }
                            if (not seq_key_exists($seq,'30X')){
                                return 0;
                            }

                        }
                        if(seq_key_value_exists($seq_A, '22B', 'MATU')){
                            if (not seq_key_exists($seq,'32[HR]')){
                                return 0;
                            }

                            if (seq_key_exists($seq,'30X')){
                                return 0;
                            }
                        }
                        if(seq_key_value_exists($seq_A, '22B', 'ROLL')){
                            if (not seq_key_exists($seq,'32[HR]')){
                                return 0;
                            }

                            if (not seq_key_exists($seq,'30X')){
                                return 0;
                            }
                        }
                    }
                }
                return 1;
            },
            err => 'D56',
        },       
        # C4 In sequence B, the values allowed for field 32H and 32R depend on the values of fields 22B
        # in sequence A and 17R in sequence B as follows (Error code(s): D57): 
        {
            func => sub {
                my $doc = shift;

                my $v_all_seq = _find_sequences($doc->data,'(15.)|(37L)');
                my $v_A =[];
                my $v_B =[];
                my $v_C =[];
                my $v_D =[];
                my $v_G1 =[];
                for my $seqseq (@$v_all_seq){
                    if ( ${@{$seqseq}[0]}{key} eq '15A'){
                        push(@{$v_A},$seqseq);
                    }
                    if ( ${@{$seqseq}[0]}{key} eq '15B'){
                        push(@{$v_B},$seqseq);
                    }
                    if ( ${@{$seqseq}[0]}{key} eq '15C'){
                        push(@{$v_C},$seqseq);
                    }
                    if ( ${@{$seqseq}[0]}{key} eq '15D'){
                        push(@{$v_D},$seqseq);
                    }
                    if ( ${@{$seqseq}[0]}{key} eq '37L'){
                        push(@{$v_G1},$seqseq);
                    }

                }
                for my $seq_A (@$v_A){
                    for my $seq (@$v_B){
                        if(seq_key_value_exists($seq_A, '22B', 'MATU')){
                            if (seq_key_value_exists($seq, '17R', 'L')){
                                my $numbers = "";
                                if (seq_get_first($seq,'32[HR]') =~ /^(-?\d+\.?\d*)$/){
                                    $numbers = $1;
                                } else {
                                    return(0,"Required 32[HR] field contains non digit value ");
                                }
                                if ($numbers > 0){
                                    return 0;
                                }
                            }

                        }
                        if(seq_key_value_exists($seq_A, '22B', 'MATU')){
                            if (seq_key_value_exists($seq, '17R', 'B')){
                                my $numbers = "";
                                if (seq_get_first($seq,'32[HR]') =~ /^(-?\d+\.?\d*)$/){
                                    $numbers = $1;
                                } else {
                                    return(0,"Required 32[HR] field contains non digit value ");
                                }
                                if ($numbers < 0){
                                    return 0;
                                }
                            }
                        }
                    }
                }
                return 1;
            },
            err => 'D57',
        },  

        # C5 In sequence A, if field 22B contains MATU, then field 30F in sequence B is not allowed, otherwise
        # field 30F is optional (Error code(s): D69):  
        {
            func => sub {
                my $doc = shift;

                my $v_all_seq = _find_sequences($doc->data,'(15.)|(37L)');
                my $v_A =[];
                my $v_B =[];
                my $v_C =[];
                my $v_D =[];
                my $v_G1 =[];
                for my $seqseq (@$v_all_seq){
                    if ( ${@{$seqseq}[0]}{key} eq '15A'){
                        push(@{$v_A},$seqseq);
                    }
                    if ( ${@{$seqseq}[0]}{key} eq '15B'){
                        push(@{$v_B},$seqseq);
                    }
                    if ( ${@{$seqseq}[0]}{key} eq '15C'){
                        push(@{$v_C},$seqseq);
                    }
                    if ( ${@{$seqseq}[0]}{key} eq '15D'){
                        push(@{$v_D},$seqseq);
                    }
                    if ( ${@{$seqseq}[0]}{key} eq '37L'){
                        push(@{$v_G1},$seqseq);
                    }

                }
                for my $seq (@$v_A){
                    if(seq_key_value_exists($seq, '22B', 'MATU')){
                        if (seq_key_exists($seq,'30F')){
                            return 0;
                        }                            

                    }
                }
                return 1;
            },
            err => 'D69',
        },    
        # С6 In sequence B, if field 30F is present then field 38J in sequence B is mandatory,
        # otherwise field 38J is not allowed (Error code(s): D60):
        {
            func => sub {
                my $doc = shift;

                my $v_all_seq = _find_sequences($doc->data,'(15.)|(37L)');
                my $v_A =[];
                my $v_B =[];
                my $v_C =[];
                my $v_D =[];
                my $v_G1 =[];
                for my $seqseq (@$v_all_seq){
                    if ( ${@{$seqseq}[0]}{key} eq '15A'){
                        push(@{$v_A},$seqseq);
                    }
                    if ( ${@{$seqseq}[0]}{key} eq '15B'){
                        push(@{$v_B},$seqseq);
                    }
                    if ( ${@{$seqseq}[0]}{key} eq '15C'){
                        push(@{$v_C},$seqseq);
                    }
                    if ( ${@{$seqseq}[0]}{key} eq '15D'){
                        push(@{$v_D},$seqseq);
                    }
                    if ( ${@{$seqseq}[0]}{key} eq '37L'){
                        push(@{$v_G1},$seqseq);
                    }

                }
                for my $seq (@$v_B){
                    if (seq_key_exists($seq,'30F') and not seq_key_exists($seq,'38J') ){
                        return 0;
                    }
                    if( (not seq_key_exists($seq,'30F')) and seq_key_exists($seq,'38J') ){
                        return 0;
                    }
                }
                return 1;
            },
            err => 'D60',
        },   
        # C7 In sequences C, D, E (if present) and F (if present), if field 56a is not present,
        # then field 86a in the same sequence C, D, E or F is not allowed, otherwise field 86a
        # is optional (Error code(s): E35)   
        {
            func => sub {
                my $doc = shift;

                my $v_all_seq = _find_sequences($doc->data,'(15.)|(37L)');
                my $v_A =[];
                my $v_B =[];
                my $v_C =[];
                my $v_D =[];
                my $v_E =[];
                my $v_F =[];
                my $v_G1 =[];
                for my $seqseq (@$v_all_seq){
                    if ( ${@{$seqseq}[0]}{key} eq '15A'){
                        push(@{$v_A},$seqseq);
                    }
                    if ( ${@{$seqseq}[0]}{key} eq '15B'){
                        push(@{$v_B},$seqseq);
                    }
                    if ( ${@{$seqseq}[0]}{key} eq '15C'){
                        push(@{$v_C},$seqseq);
                    }
                    if ( ${@{$seqseq}[0]}{key} eq '15D'){
                        push(@{$v_D},$seqseq);
                    }
                    if ( ${@{$seqseq}[0]}{key} eq '15E'){
                        push(@{$v_E},$seqseq);
                    }
                    if ( ${@{$seqseq}[0]}{key} eq '15F'){
                        push(@{$v_F},$seqseq);
                    }
                    if ( ${@{$seqseq}[0]}{key} eq '37L'){
                        push(@{$v_G1},$seqseq);
                    }

                }
                for my $seq (@$v_C){
                    if (not seq_key_exists($seq,'56[ADJ]') and seq_key_exists($seq,'86[ADJ]') ){
                        return 0;
                    }
                }

                for my $seq (@$v_D){
                    if (not seq_key_exists($seq,'56[ADJ]') and seq_key_exists($seq,'86[ADJ]') ){
                        return 0;
                    }
                }

                for my $seq (@$v_E){
                    if (not seq_key_exists($seq,'56[ADJ]') and seq_key_exists($seq,'86[ADJ]') ){
                        return 0;
                    }
                }

                for my $seq (@$v_F){
                    if (not seq_key_exists($seq,'56[ADJ]') and seq_key_exists($seq,'86[ADJ]') ){
                        return 0;
                    }
                }
                return 1;
            },
            err => 'E35',
        },                 
        # C8 The presence of sequence H and the presence of fields 88a and 71F in sequence H,
        # depend on the value of field 94A in sequence A as follows (Error code(s): D74):
        {
            func => sub {
                my $doc = shift;

                my $v_all_seq = _find_sequences($doc->data,'(15.)|(37L)');
                my $v_A =[];
                my $v_B =[];
                my $v_C =[];
                my $v_D =[];
                my $v_E =[];
                my $v_F =[];
                my $v_H =[];
                my $v_G1 =[];
                for my $seqseq (@$v_all_seq){
                    if ( ${@{$seqseq}[0]}{key} eq '15A'){
                        push(@{$v_A},$seqseq);
                    }
                    if ( ${@{$seqseq}[0]}{key} eq '15B'){
                        push(@{$v_B},$seqseq);
                    }
                    if ( ${@{$seqseq}[0]}{key} eq '15C'){
                        push(@{$v_C},$seqseq);
                    }
                    if ( ${@{$seqseq}[0]}{key} eq '15D'){
                        push(@{$v_D},$seqseq);
                    }
                    if ( ${@{$seqseq}[0]}{key} eq '15E'){
                        push(@{$v_E},$seqseq);
                    }
                    if ( ${@{$seqseq}[0]}{key} eq '15F'){
                        push(@{$v_F},$seqseq);
                    }
                    if ( ${@{$seqseq}[0]}{key} eq '15H'){
                        push(@{$v_H},$seqseq);
                    }
                    if ( ${@{$seqseq}[0]}{key} eq '37L'){
                        push(@{$v_G1},$seqseq);
                    }

                }
                for my $seq_A (@$v_C){
                    for my $seq_H (@$v_H){
                        if (not seq_key_exists($seq_A,'94A') and seq_key_exists($seq_H,'71F') ){
                            return 0;
                        }
                        if (seq_key_value_exists($seq_A, '94A', 'AGNT') and seq_key_exists($seq_H,'71F') ){
                            return 0;
                        }
                        if (seq_key_value_exists($seq_A, '94A', 'BILA') and seq_key_exists($seq_H,'71F') ){
                            return 0;
                        }
                        if (seq_key_value_exists($seq_A, '94A', 'BROK') 
                            and (not seq_key_exists($seq_H,'15H') or not seq_key_exists($seq_H,'88[ADJ]')) ){

                            return 0;
                        }
                    }
                }
                return 1;
            },
            err => 'D74',
        },     
        # C9 If field 32H is present, then the currency code must be the same as the currency code in field 32B (Error code(s): C02).
        {
            func => sub {
                my $doc = shift;
                if($doc->key_exists('32H')){
                    my $vs = seq_get_all($doc->data, '32B|32H');
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
            err => 'C02',
        },
        # C10 In sequence H, field 15H may not be the only field, that is, if field 15H is present, 
        # then at least one of the other fields of sequence H must be present (Error code(s): C98).
        {
            func => sub {
                my $doc = shift;
                my $v_all_seq = _find_sequences($doc->data,'(15.)|(37L)');
                my $v_A =[];
                my $v_B =[];
                my $v_C =[];
                my $v_D =[];
                my $v_E =[];
                my $v_F =[];
                my $v_H =[];
                my $v_G1 =[];
                for my $seqseq (@$v_all_seq){
                    if ( ${@{$seqseq}[0]}{key} eq '15A'){
                        push(@{$v_A},$seqseq);
                    }
                    if ( ${@{$seqseq}[0]}{key} eq '15B'){
                        push(@{$v_B},$seqseq);
                    }
                    if ( ${@{$seqseq}[0]}{key} eq '15C'){
                        push(@{$v_C},$seqseq);
                    }
                    if ( ${@{$seqseq}[0]}{key} eq '15D'){
                        push(@{$v_D},$seqseq);
                    }
                    if ( ${@{$seqseq}[0]}{key} eq '15E'){
                        push(@{$v_E},$seqseq);
                    }
                    if ( ${@{$seqseq}[0]}{key} eq '15F'){
                        push(@{$v_F},$seqseq);
                    }
                    if ( ${@{$seqseq}[0]}{key} eq '15H'){
                        push(@{$v_H},$seqseq);
                    }
                    if ( ${@{$seqseq}[0]}{key} eq '37L'){
                        push(@{$v_G1},$seqseq);
                    }

                }
                for my $seq_H (@$v_H){
                    if (seq_key_exists($seq_H,'15H') 
                        and (not seq_key_exists($seq_H,'29A'))
                        and (not seq_key_exists($seq_H,'24D'))
                        and (not seq_key_exists($seq_H,'84[ABDJ]'))
                        and (not seq_key_exists($seq_H,'85[ABDJ]'))
                        and (not seq_key_exists($seq_H,'88[ADJ]'))
                        and (not seq_key_exists($seq_H,'71F'))
                        and (not seq_key_exists($seq_H,'26H'))
                        and (not seq_key_exists($seq_H,'21G'))
                        and (not seq_key_exists($seq_H,'22Z'))
                        and (not seq_key_exists($seq_H,'72'))
                        ){
                            return 0;
                    }       
                }             
                return 1;
            },
            err => 'C98',
        },
        # C11 In all optional sequences and sub-sequences, the field with status M must be present
        # if the sequence or sub-sequence is present, and are otherwise
        # not allowed (Error code(s): C32).
        {
            func => sub {
                my $doc = shift;
                my $v_all_seq = _find_sequences($doc->data,'(15.)|(37L)');
                my $v_A =[];
                my $v_B =[];
                my $v_C =[];
                my $v_D =[];
                my $v_E =[];
                my $v_F =[];
                my $v_H =[];
                my $v_G =[];
                my $v_G1 =[];
                for my $seqseq (@$v_all_seq){
                    if ( ${@{$seqseq}[0]}{key} eq '15A'){
                        push(@{$v_A},$seqseq);
                    }
                    if ( ${@{$seqseq}[0]}{key} eq '15B'){
                        push(@{$v_B},$seqseq);
                    }
                    if ( ${@{$seqseq}[0]}{key} eq '15C'){
                        push(@{$v_C},$seqseq);
                    }
                    if ( ${@{$seqseq}[0]}{key} eq '15D'){
                        push(@{$v_D},$seqseq);
                    }
                    if ( ${@{$seqseq}[0]}{key} eq '15E'){
                        push(@{$v_E},$seqseq);
                    }
                    if ( ${@{$seqseq}[0]}{key} eq '15F'){
                        push(@{$v_F},$seqseq);
                    }
                    if ( ${@{$seqseq}[0]}{key} eq '15H'){
                        push(@{$v_H},$seqseq);
                    }
                    if ( ${@{$seqseq}[0]}{key} eq '15G'){
                        push(@{$v_G},$seqseq);
                    }
                    if ( ${@{$seqseq}[0]}{key} eq '37L'){
                        push(@{$v_G1},$seqseq);
                    }

                }
                for my $seq_H (@$v_G){
                    if (seq_key_exists($seq_H,'15G') 
                    or seq_key_exists($seq_H,'37L')
                    or seq_key_exists($seq_H,'36')
                    or seq_key_exists($seq_H,'33[EJ]')
                    or seq_key_exists($seq_H,'33[BJ]')){
                        if ((not seq_key_exists($seq_H,'33[BJ]'))
                        or (not seq_key_exists($seq_H,'15G'))
                        or (not seq_key_exists($seq_H,'37L'))){
                            return 0;
                        }
                    }
                }
                $v_G = _find_sequences($doc->data,'15F');  
                for my $seq_H (@$v_G){
                    if ( (not seq_key_exists($seq_H,'15F') ) or (not seq_key_exists($seq_H,'57[ADJ]')) ){
                        return 0;
                    }
                }

                $v_G = _find_sequences($doc->data,'15E');  
                for my $seq_H (@$v_G){
                    if ( (not seq_key_exists($seq_H,'15E') ) or (not seq_key_exists($seq_H,'57[ADJ]')) ){
                        return 0;
                    }
                }
                return 1;
            },
            err => 'C98',      
        },
        # C12 When GOLD is defined in subfield 4 (Type) of field 26C, then the Unit GOZ and TOZ
        # can not be used in the following fields (Error code(s): D07):
        # Mandatory Sequence B field 32a Currency and Principal Amount (Option F only)
        # Mandatory Sequence B field 32a Amount to be Settled (Option R only)
        # Mandatory Sequence B field 34a Currency and Interest Amount (Option J only)
        # Optional Sequence G Mandatory Subsequence G1 field 33a Transaction Currency and Net Interest Amount (Option J only)
        # Optional Sequence G Optional Subsequence G2 field 33a Reporting Currency and Tax Amount (Option J only)
        # {
        #     func => sub {
        #         return 1;
        #     },
        #     err => 'D07',      
        # },               

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