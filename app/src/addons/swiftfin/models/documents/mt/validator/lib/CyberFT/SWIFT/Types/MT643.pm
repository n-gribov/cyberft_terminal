package CyberFT::SWIFT::Types::MT643;
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
		# O	27	Sequence of Total	1!n/1!n	See specification 4
		# O	29A	From	4*35x	See specification 5
		# O	29B	To the Attention of	4*35x	See specification 6
		# M	88D	Borrower(s)	[/1!a][/34x]4*35x	See specification 7
		{
			key => '88D',
			required => 1,
		},
		# M	32A	Original Facility Amount	6!n3!a15d	See specification 8
		{
			key => '32A',
			required => 1,
		},
		# End of Sequence A Identification of the Facility

		# -----> Optional Repetitive Sequence B Existing Drawings
		# M	26P	Drawing Identification	3!a/4x	See specification 9
		# O	31F	Drawdown Period	6!n[/6!n][//35x]	See specification 10
		# O	32P	Repayment of Principal	6!n3!a15d	See specification 11
		# M	33a	Interest Amount	P or R	See specification 12
		# O	71C	Details of Adjustments	6*35x	See specification 13
		# O	34a	Net Interest Amount	P or R	See specification 14
		# O	57a	Account With Institution	A, B, or D	See specification 15
		# O	72	Sender to Receiver Information	6*35x	See specification 16
		# -----| End of Sequence B Existing Drawings

		# -----> Mandatory Repetitive Sequence C New Drawing(s)
		# M	26N	Drawing Identification (New)	3!a/4x	See specification 17
		{
			key => '26N',
			required => 1,
		},
		# O	32E	Selected Currency	3!a	See specification 18
		# M	31F	Drawdown Period	6!n[/6!n][//35x]	See specification 19
		{
			key => '31F',
			required => 1,
		},
		# O	31R	Rate Fixing Date	6!n[/6!n]	See specification 20
		# M	32B	Amount of Drawdown	3!a15d	See specification 21
		{
			key => '32B',
			required => 1,
		},
		# M	33B	Receiver's Participation	3!a15d	See specification 22
		{
			key => '33B',
			required => 1,
		},
		# O	57a	Account With Institution	A, B, or D	See specification 23
		# O	72	Sender to Receiver Information	6*35x	See specification 24
		# -----| End of Sequence C New Drawing(s)

	],

	rules => [

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
							(?:\|27)?
							(?:\|29A)?
							(?:\|29B)?
							\|88D
							\|32A

							(?:
								(?:\|26P)?
								(?:\|31F)?
								(?:\|32P)?
								(?:\|33[PR])?
								(?:\|71C)?
								(?:\|34[PR])?
								(?:\|57[ABD])?
								(?:\|72)?
							)*

							(?:
								\|26N
								(?:\|32E)?
								\|31F
								(?:\|31R)?
								\|32B
								\|33B
								(?:\|57[ABD])?
								(?:\|72)?
							)+
						$/x;
				return 1;
			},
			err => 'Invalid sequence structure'
		},

		# C9 In all optional sequences, the fields with status M must be present if the sequence is present, and are otherwise not allowed (Error code(s): C32).
		{
			func => sub {
				my $document = shift;
				my $keys_string = join('|', map { $_->{key} } @{ $document->data }); # '20|23|25|30|37H|37H|23|30|37H|72'

				return 0
					unless $keys_string =~ m/^
							20
							(?:\|21)?
							\|23
							(?:\|27)?
							(?:\|29A)?
							(?:\|29B)?
							\|88D
							\|32A

							(?:
								\|26P
								(?:\|31F)?
								(?:\|32P)?
								\|33[PR]
								(?:\|71C)?
								(?:\|34[PR])?
								(?:\|57[ABD])?
								(?:\|72)?
							)*

							(?:
								\|26N
								(?:\|32E)?
								\|31F
								(?:\|31R)?
								\|32B
								\|33B
								(?:\|57[ABD])?
								(?:\|72)?
							)+
						$/x;

				return 1;
			},
			err => 'C32'
		},

		# C1 Each sequence B must be accompanied by a sequence C (sequence C may be present without sequence B) (Error code(s): C66).
		{
			func => sub {
				my $document = shift;

				my $sequences_b_count = seq_key_count($document->data, '26P');
				my $sequences_c_count = seq_key_count($document->data, '26N');

				return $sequences_c_count >= $sequences_b_count ? 1 : 0;
			},
			err => 'C66'
		},

		# C2 If field 23 contains the code word LOAN, field 31R must be present (Error code(s): C51).
		{
			func => sub {
				my $document = shift;

				my $field_23_value = seq_get_first($document->data, '23');
				if ($field_23_value =~ /LOAN/) {
					return 0
						unless seq_key_exists($document->data, '31R');
				}

				return 1;
			},
			err => 'C51'
		},

		# C3 If field 23 contains the code word DRAWDOWN, sequence B must not be present (Error code(s): C47).
		{
			func => sub {
				my $document = shift;

				my $field_23_value = seq_get_first($document->data, '23');
				if ($field_23_value =~ /DRAWDOWN/) {
					return 0
						if seq_key_exists($document->data, '26P');
				}

				return 1;
			},
			err => 'C47'
		},

		# C4 If field 23 contains the code word RENEWAL, both sequence B and C must be present (Error code(s): C48).
		{
			func => sub {
				my $document = shift;

				my $field_23_value = seq_get_first($document->data, '23');
				if ($field_23_value =~ /RENEWAL/) {
					return 0
						unless seq_key_exists($document->data, '26P')
							&& seq_key_exists($document->data, '26N');
				}

				return 1;
			},
			err => 'C48'
		},

		# C5 When sequence B is present and fields 33a and 71C are present, field 34a must
		# also be present in that sequence (Error code(s): C53).
		{
			func => sub {
				my $document = shift;

				my $sequences = get_b_sequences($document->data);
				foreach my $sequence (@$sequences) {
					return 0
						if seq_key_exists($sequence, '33[PR]')
						&& seq_key_exists($sequence, '71C')
						&& !seq_key_exists($sequence, '34[PR]');
				}

				return 1;
			},
			err => 'C53'
		},

		# C6 At least one of fields 21 or 29B must be present (Error code(s): C35).
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

		# C7 In each sequence B, the currency in fields 32P, 33a and 34a must be the same (Error code(s): C60).
		# 32P 6!n3!a15d
		# 33a 6!n3!a15d
		# 34a 6!n3!a15d
		{
			func => sub {
				my $document = shift;

				my $sequences = get_b_sequences($document->data);
				foreach my $sequence (@$sequences) {
					my @currency_codes;
					my @fields = grep { $_->{key} =~ /^32P|33[PR]|34[PR]$/ } @{ $sequence };
					for (@fields) {
						my $currency_code;
						if ($_->{value} =~ /^\d{6}([A-Z]{3})/) {
							$currency_code = $1;
						} else {
							return 0;
						}
						push @currency_codes, $currency_code;
					}
					for (@currency_codes) {
						return 0
							unless $_ eq $currency_codes[0];
					}
				}

				return 1;
			},
			err => 'C60'
		},

		# C8 In each sequence C, the currency in fields 32B and 33B must be the same (Error code(s): C61).
		# 3!a15d
		{
			func => sub {
				my $document = shift;

				my $sequences = get_c_sequences($document->data);
				foreach my $sequence (@$sequences) {
					my @currency_codes;
					my @fields = grep { $_->{key} =~ /^32B|33B$/ } @{ $sequence };
					for (@fields) {
						my $currency_code;
						if ($_->{value} =~ /^([A-Z]{3})/) {
							$currency_code = $1;
						} else {
							return 0;
						}
						push @currency_codes, $currency_code;
					}
					for (@currency_codes) {
						return 0
							unless $_ eq $currency_codes[0];
					}
				}

				return 1;
			},
			err => 'C61'
		},

	]
};

sub get_b_sequences {
	my $data = shift;
	my $sequences = [];
	my $current_sequence;
	for (@$data) {
		if ($_->{key} eq '26P') {
			push(@$sequences, $current_sequence)
				if $current_sequence;
			$current_sequence = [];
		}
		if ($_->{key} eq '26N') {
			last;
		}
		push(@$current_sequence, $_)
			if $current_sequence;
	}
	push (@$sequences, $current_sequence)
		if $current_sequence;
	return $sequences;
}

sub get_c_sequences {
	my $data = shift;
	my $sequences = [];
	my $current_sequence;
	for (@$data) {
		if ($_->{key} eq '26N') {
			push(@$sequences, $current_sequence)
				if $current_sequence;
			$current_sequence = [];
		}
		push(@$current_sequence, $_)
			if $current_sequence;
	}
	push (@$sequences, $current_sequence)
		if $current_sequence;
	return $sequences;
}

1;