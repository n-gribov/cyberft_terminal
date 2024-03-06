package CyberFT::SWIFT::Types::MT646;
use CyberFT::SWIFT::Types::Utils;
use strict;

our $ValidationProfile = {

	fields => [

		# M	20	Transaction Reference Number	16x	See specification 1
		{
			key => '20',
			required => 1,
		},
		# O	21	Related Reference	16x	See specification 2
		# M	23	Further Identification	16x	See specification 3
		{
			key => '23',
			required => 1,
		},
		# O	29A	From	4*35x	See specification 4
		# O	29B	To the Attention of	4*35x	See specification 5
		# M	88D	Borrower(s)	[/1!a][/34x]4*35x	See specification 6
		{
			key => '88D',
			required => 1,
		},
		# M	32A	Original Facility Amount	6!n3!a15d	See specification 7
		{
			key => '32A',
			required => 1,
		},
		# O	26P	Drawing Identification	3!a/4x	See specification 8
		# End of Sequence A Identification of the Facility

		# -----> Optional Repetitive Sequence B Interest Calculation
		# O	31F	Interest Period	6!n[/6!n][//35x]	See specification 9
		# O	33B	Computation Base Amount	3!a15d	See specification 10
		# O	34N	Interest Amount	[N]6!n3!a15d	See specification 11
		# O	37a	Interest Rate	A, B, C, D, E, or F	See specification 12
		# O	72	Sender to Receiver Information	6*35x	See specification 13
		# -----| End of Sequence B Interest Calculation

		# Mandatory Sequence C Principal Payment/Sum of Interest Due
		# M	32A	Total Principal Amount Repaid/Prepaid	6!n3!a15d	See specification 14
		{
			key => '32A',
			required => 1,
		},
		# O	32N	Principal Amount Due to Receiver	[N]6!n3!a15d	See specification 15
		# O	33N	Gross Interest Amount Due to Receiver	[N]6!n3!a15d	See specification 16
		# O	34N	Net Interest Amount Due to Receiver	[N]6!n3!a15d	See specification 17
		# O	34a	Total Amount Transferred	P or R	See specification 18
		# O	57a	Account With Institution	A, B, or D	See specification 19
		# O	71C	Details of Adjustments	6*35x	See specification 20
		# O	72	Sender to Receiver Information	6*35x	See specification 21
		# End of Sequence C Principal Payment/Sum of Interest Due

	],

	rules => [

		# Check both 32A exist
		{
			func => sub {
				my $document = shift;
				return 0
					unless seq_key_count($document->data, '32A') == 2;
				return 1;
			},
			err => 'Missing field 32A'
		},

		# Check keys sequence
		{
			func => sub {
				my $document = shift;
				my $keys_string = join('|', map { $_->{key} } @{ $document->data }); # '20|23|25|30|37H|37H|23|30|37H|72'
				return 0
					unless $keys_string =~ m/^
							20
							(?:\|21)?
							\|23
							(?:\|29A)?
							(?:\|29B)?
							\|88D
							\|32A
							(?:\|26P)?

							(?:
								(?:\|31F)?
								(?:\|33B)?
								(?:\|34N)?
								(?:\|37[ABCDEF])?
								(?:\|72)?
							)*

							\|32A
							(?:\|32N)?
							(?:\|33N)?
							(?:\|34N)?
							(?:\|34[PR])?
							(?:\|57[ABD])?
							(?:\|71C)?
							(?:\|72)?
						$/x;
				return 1;
			},
			err => 'Invalid sequence structure'
		},

		# C1 If field 34N of sequence B is present, field 31F of that sequence and field 33N in sequence C must also be present (Error code(s): C57).
		{
			func => sub {
				my $document = shift;

				my $sequences = get_b_sequences($document->data);
				foreach my $sequence (@$sequences) {
					next unless
						seq_key_exists($sequence, '34N');
					return 0
						unless seq_key_exists($sequence, '31F')
							&& seq_key_exists($document->data, '33N');
				}

				return 1;
			},
			err => 'C57'
		},

		# C2-1 In sequence C, at least one of fields 32N or 33N must be present.
		# If both fields are present, fields 34a must also be present (Error code(s): C43,C44).
		{
			func => sub {
				my $document = shift;

				my $sequence = get_c_sequence($document->data);
				return 0
					unless seq_key_exists($sequence, '32N')
						|| seq_key_exists($sequence, '33N');

				return 1;
			},
			err => 'C43'
		},

		# C2-2 In sequence C, at least one of fields 32N or 33N must be present.
		# If both fields are present, fields 34a must also be present (Error code(s): C43,C44).
		{
			func => sub {
				my $document = shift;

				my $sequence = get_c_sequence($document->data);
				return 0
					if seq_key_exists($sequence, '32N')
					&& seq_key_exists($sequence, '33N')
					&& !seq_key_exists($sequence, '34[PR]');

				return 1;
			},
			err => 'C44'
		},

		# C3 If field 23 contains the code word PREPRINC or REPRINC, field 32N must be present (Error code(s): C45).
		{
			func => sub {
				my $document = shift;

				my $sequence = get_c_sequence($document->data);
				return 0
					if seq_get_first($document->data, '23') =~ m/PREPRINC|REPRINC/
					&& !seq_key_exists($sequence, '32N');

				return 1;
			},
			err => 'C45'
		},

		# C4 If field 23 contains the code word INT, field 33N must be present (Error code(s): C46).
		{
			func => sub {
				my $document = shift;

				my $sequence = get_c_sequence($document->data);
				return 0
					if seq_get_first($document->data, '23') =~ m/INT/
					&& !seq_key_exists($sequence, '33N');

				return 1;
			},
			err => 'C46'
		},

		# C5 The amount component in field 33N of sequence C must be equal to the total of the
		# amount component of all sequence B field 34Ns present in the message (Error code(s): C58).
		# [N]6!n3!a15d
		{
			func => sub {
				my $document = shift;

				return 1
					unless seq_key_exists($document->data, '33N');

				my $amount_to_check = parse_amount(seq_get_first($document->data, '33N'));
				return 0
					unless defined($amount_to_check);

				my $total_amount = 0;
				my $sequences = get_b_sequences($document->data);
				foreach my $sequence (@$sequences) {
					next unless
						seq_key_exists($sequence, '34N');
					$total_amount += parse_amount(seq_get_first($sequence, '34N'));
				}

				return 0
					unless abs($total_amount - $amount_to_check) <= 0.0001;

				return 1;
			},
			err => 'C58'
		},

		# C6 At least one of fields 21 or field 29B must be present (Error code(s): C35).
		{
			func => sub {
				my $document = shift;

				return 0
					unless seq_key_exists($document->data, '21')
						|| seq_key_exists($document->data, '29B');

				return 1;
			},
			err => 'C35'
		},

		# C7 The currency code in the amount fields 32A (in sequence C) 33B, 34N, 32N,
		# 33N and 34a must be the same for all occurrences of these fields in the message (Error code(s): C02).
		#
		# 32A 6!n3!a15d
		# 33B 3!a15d
		# 34N [N]6!n3!a15d
		# 32N [N]6!n3!a15d
		# 33N [N]6!n3!a15d
		# 34a 6!n3!a15d
		{
			func => sub {
				my $document = shift;

				my @currency_codes;
				my @fields = grep { $_->{key} =~ /^33B|34N|32N|33N|34[PR]$/ } @{ $document->data };

				# Field 32A from sequence A doesn't have to be checked
				# So we get only 32A from C
				my $sequence_c = get_c_sequence($document->data);
				push @fields, ( grep { $_->{key} eq '32A' } @$sequence_c );
				for (@fields) {
					my $currency_code;
					if ($_->{key} eq '33B') {
						$currency_code = $1
							if $_->{value} =~ m/^([A-Z]{3})/;
					} else {
						$currency_code = $1
							if $_->{value} =~ m/^N?\d{6}([A-Z]{3})/;
					}
					return 0
						unless $currency_code;
					push @currency_codes, $currency_code;
				}
				for (@currency_codes) {
					return 0
						unless $_ eq $currency_codes[0];
				}

				return 1;
			},
			err => 'C02'
		},

	]
};

sub parse_amount {
	my $string = shift;
	if ($string =~ m/^N?\d{6}[A-Z]{3}(\d+,\d*)[\r\n]*$/) {
		my $amount = $1;
		$amount =~ s/\,$//;
		$amount =~ s/,/\./g;
		return $amount;
	} else {
		return undef;
	}
}

sub get_b_sequences {
	my $data = shift;

	my $keys_indexes = {};
	my $i = 1;
	for (qw/31F 33B 34N 37A 37B 37C 37D 37E 37F 72/) {
		$keys_indexes->{$_} = $i;
		$i++;
	}

	my $last_key_index = 0;
	my $sequences = [];
	my $current_sequence = [];
	my $key_32A_count = 0;
	for (@$data) {
		if ($_->{key} eq '32A') {
			$key_32A_count++;
			last if $key_32A_count == 2;
		}
		next unless $keys_indexes->{ $_->{key} };
		my $current_key_index = $keys_indexes->{ $_->{key} };
		if ($current_key_index > $last_key_index) {
			push @$current_sequence, $_;
			$last_key_index = $current_key_index;
		} else {
			push @$sequences, $current_sequence;
			$current_sequence = [$_];
			$last_key_index = 0;
		}
	}
	push(@$sequences, $current_sequence)
		if $current_sequence && @$current_sequence;

	return $sequences;
}

sub get_c_sequence {
	my $data = shift;
	my $key_32A_count = 0;
	my $sequence = [];
	for (@$data) {
		$key_32A_count++
			if $_->{key} eq '32A';
		next unless $key_32A_count == 2;
		push @$sequence, $_;
	}
	return $sequence;
}

1;