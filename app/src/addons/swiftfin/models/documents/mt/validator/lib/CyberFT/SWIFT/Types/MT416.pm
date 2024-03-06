package CyberFT::SWIFT::Types::MT416;
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
            key => '21A',
            required => 1,
        },
        {   
            key => '32a',
            key_regexp => '32[ABK]',
            required => 1,
        },

    ],
    
    rules => [
		# �������� ������������ ���� � ������������������� B
		{
            func => sub {
                my $doc = shift;
                my $bs = _find_B_seqs($doc->{data});
                for my $b (@$bs) {
                    return 0 unless (seq_key_exists($b, '21A'));
                    return 0 unless (seq_key_exists($b, '32[ABK]'));
                }
                return 1;
            },
            err => 'Missing required field in B sequence',
        },

		# C1
		#���� 23� ������ �������������� ���� � ������������������ �, ���� � ������ �� 
		# ���������� ������������������ �, �� �� ����� �������������� ��� ������������� �
		# ����� ������������������� (��� ������ D78).
		{
			func => sub {
				my $doc = shift;
				my $as = _find_A_seq($doc->{data});
				my $f23e_a = seq_key_exists( $as, '23E' );
				
				my $bs = _find_B_seqs($doc->{data});
				for my $b (@$bs) {
					if( seq_key_exists( $b, '23E' ) )
					{
						if( $f23e_a == 1 )
						{
							return 0;
						}
					}
					else
					{
						if( $f23e_a == 0 )
						{
							return 0;
						}
					}
				}
				return 1;
			},
			err => 'D78',
		},
		# C2
		#���� � ������������������ � ������������ ���� 71F � 77�, �� ���, ���������� ����
		# �� �����, �� ������ �������������� �� � ����� �� ���������� ������������������ �.
		# � ��������, ���� ���� 71F � 77� ����������� � ������������������ �, �� ���,
		# ���������� ���� �� �����, �������� ��������������� � ������ �� ����������
		# ������������������ � (��� ������ D83).
		{
			func => sub {
				my $doc = shift;
				my $as = _find_A_seq($doc->{data});
				my $f71f_a = seq_key_exists($as, '71F');
				my $f77a_a = seq_key_exists($as, '77A');
				my $bs = _find_B_seqs($doc->{data});
				for my $b (@$bs) {
					if( seq_key_exists( $b, '71F' ) )
					{
						if( $f71f_a == 1 )
						{
							return 0;
						}
					}
					if( seq_key_exists( $b, '77A' ) )
					{
						if( $f77a_a == 1 )
						{
							return 0;
						}
					}

				}
				return 1;
			},
			err => 'D83',
		},
		
		# C3
		# � ��������� �� 416 ����� �������������� ������ ���� ������. ������� ��� ������
		# �� ���� ����� ����, �� ����, � ���� 71F � ������������������ � � � ����� 32� � 71F �
		# ������������������ � ������ ���� ���������� �� ���� ����������� ���� ����� �
		# ��������� (��� ������ �02).
        {
            func => sub {
                my $doc = shift;
                my $vs = seq_get_all($doc->data, '71F|32A|32B|32K');
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

sub _find_A_seq {
    my $data = shift;
    my @seq;
    for my $item (@$data) {
        if ($item->{key} eq '21A') {
            return \@seq;
        }
        push @seq, $item;
    }
    return \@seq;
}

sub _find_B_seqs {
    my $data = shift;
    my @seqs;
    my $seq_started = 0;
    for my $item (@$data) {
        if ($item->{key} eq '21A') {
            push @seqs, [];
            $seq_started = 1;
        }
        if ($seq_started) {
            push @{$seqs[scalar(@seqs)-1]}, $item;
        }
    }
    return \@seqs;
}

1;