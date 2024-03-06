package CyberFT::SWIFT::Types::MT412;
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
    ],

    rules => [
        # C1
        # The repetitive sequence must appear at least once, but not more than ten times (Error code(s): T10)
        # Повторяющаяся последовательность не должна использоваться более десяти раз (Код ошибки Т10).
        {
            func => sub {
                my $doc = shift;
                my $v_20 = _find_sequences($doc->data);
                my $v_21 = $doc->get_all('21');
                my $v_32 = $doc->get_all('32A');
                my $counter = 0;
                for my $seq (@$v_20){
                    $counter += 1;
                    my $check = {
                        '20' => 0,
                        '21' => 0,
                        '32A' => 0,
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
                    if ($counter > 10 || scalar(@$v_21) > 10 || scalar(@$v_32) > 10){
                        return 0;
                    }
                }
                return 1;
            },
            err => 'T10',
        },
        # С2
        # Код валюты в поле 32А должен быть одинаковым во всех повторениях этого поля в
        # сообщении (Код ошибки С02).
        {
            func => sub {
                my $doc = shift;
                my $values = $doc->get_all('32A');
                my $check = '';
                for my $v (@$values) {
                    my ($cur) = $v =~ /([A-Z]{3})/;
                    if ($check) {
                        return 0 if ($check ne $cur);
                    }
                    else {
                        $check = $cur;
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