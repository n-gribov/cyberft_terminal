package CyberFT::SWIFT::Types::MT645;
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
		# O	29A	From	4*35x	See specification 3
		# O	29B	To the Attention of	4*35x	See specification 4
		# M	88D	Borrower(s)	[/1!a][/34x]4*35x	See specification 5
		{
			key => '88D',
			required => 1,
		},
		# M	32A	Original Facility Amount	6!n3!a15d	See specification 6
		{
			key => '32A',
			required => 1,
		},
		# O	71B	Flat Fees	6*35x	See specification 7

		# -----> Optional Sequence B Variable Fees
		# M	23	Type of Fee	16x	See specification 8
		# M	31F	Fee Period	6!n[/6!n][//35x]	See specification 9
		# O	33B	Computation Base Amount	3!a15d	See specification 10
		# M	34B	Fee Amount	3!a15d	See specification 11
		# O	37a	Fee Rate	A, B, C, D, E or F	See specification 12
		# O	72	Sender to Receiver Information	6*35x	See specification 13
		# -----|

		# Sequence C Summation of Fees
		# O	71C	Summary of Variable Fees	6*35x	See specification 14
		# M	33A	Total Fees	6!n3!a15d	See specification 15
		{
			key => '33A',
			required => 1,
		},
		# O	34A	Amount to be Transferred	6!n3!a15d	See specification 16
		# O	57a	Account With Institution	A, B or D	See specification 17
		# O	72	Sender to Receiver Information	6*35x	See specification 18

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
							(?:\|29A)?
							(?:\|29B)?
							\|88D
							\|32A
							(?:\|71B)?

							(?:
								(?:\|23)?
								(?:\|31F)?
								(?:\|33B)?
								(?:\|34B)?
								(?:\|37[ABCDEF])?
								(?:\|72)?
							)*

							(?:\|71C)?
							\|33A
							(?:\|34A)?
							(?:\|57[ABD])?
							(?:\|72)?
						$/x;
				return 1;
			},
			err => 'Invalid sequence structure'
		},

		# C1 In optional sequence B, fields with status M must be present (Error code(s): C32).
		{
			func => sub {
				my $document = shift;
				my $keys_string = join('|', map { $_->{key} } @{ $document->data }); # '20|23|25|30|37H|37H|23|30|37H|72'
				return 0
					unless $keys_string =~ m/^
							20
							(?:\|21)?
							(?:\|29A)?
							(?:\|29B)?
							\|88D
							\|32A
							(?:\|71B)?

							(?:
								\|23
								\|31F
								(?:\|33B)?
								\|34B
								(?:\|37[ABCDEF])?
								(?:\|72)?
							)*

							(?:\|71C)?
							\|33A
							(?:\|34A)?
							(?:\|57[ABD])?
							(?:\|72)?
						$/x;

				return 1;
			},
			err => 'C32'
		},

		# C2 At least one of fields 21 or 29B must be present (Error code(s): C35).
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


		# C3 At least one of fields 71B, 23 or 71C must be present (Error code(s): C29).
		{
			func => sub {
				my $document = shift;

				return 0
					unless seq_key_exists($document->data, '71B')
						|| seq_key_exists($document->data, '23')
						|| seq_key_exists($document->data, '71C');

				return 1;
			},
			err => 'C29'
		},

		# C4 The currency code in the amount fields 33B, 34B, 33A and 34A must be the same for all occurrences
		# of these fields in the message (Error code(s): C02).
		#
		# 33B 3!a15d
		# 34B 3!a15d
		# 33A 6!n3!a15d
		# 34A 6!n3!a15d
		{
			func => sub {
				my $document = shift;

				my @currency_codes;
				my @fields = grep { $_->{key} =~ /^33B|34B|33A|34A$/ } @{ $document->data };

				for (@fields) {
					my $currency_code;
					if ($_->{key} =~ m/^33B|34B$/) {
						$currency_code = $1
							if $_->{value} =~ m/^([A-Z]{3})/;
					} else {
						$currency_code = $1
							if $_->{value} =~ m/^\d{6}([A-Z]{3})/;
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

1;