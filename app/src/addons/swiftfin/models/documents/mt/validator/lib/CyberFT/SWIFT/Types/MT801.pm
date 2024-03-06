package CyberFT::SWIFT::Types::MT801;
use CyberFT::SWIFT::Types::Utils;
use strict;

our $ValidationProfile = {

	fields => [

		# M	20	Transaction Reference Number	16x	See specification 1
		{
			key => '20',
			required => 1,
		},
		# M	28	Settlement No./Page No.	5n[/2n]	See specification 2
		{
			key => '28',
			required => 1,
		},
		# ----->
		# M	51a	Selling Agent	A or C	See specification 3
		{
			key => '51a',
			required => 1,
			key_regexp => '51[AC]',
		},
		# ----->
		# M	23	Purchase Agreement ID	16x	See specification 4
		{
			key => '23',
			required => 1,
		},
		# O	30	Sales Date	6!n	See specification 5
		# ----->
		# M	26A	Serial Numbers	16x[/4!x]	See specification 6
		{
			key => '26A',
			required => 1,
		},
		# -----|
		# M	33B	Face Amount	3!a15d	See specification 7
		{
			key => '33B',
			required => 1,
		},
		# O	73	Additional Amounts	6*35x	See specification 8
		# -----|
		# M	34B	Selling Agent Amount	3!a15d	See specification 9
		{
			key => '34B',
			required => 1,
		},
		# M	16A	Selling Agent's No. of PAs	5n	See specification 10
		{
			key => '16A',
			required => 1,
		},
		# -----|

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
							\|28
							(?:
								\|51[AC]
								(?:
									\|23
									(?:\|30)?
									(?:\|26A)+
									\|33B
									(?:\|73)?
								)+
								\|34B
								\|16A
							)+
						$/x;
				return 1;
			},
			err => 'Invalid sequence structure'
		},

		# C1 The currency code in all occurrences of fields 33B and 34B must be the same (Error code(s): C02).
		# 34B	3!a15d
		# 33B	3!a15d
		{
			func => sub {
				my $document = shift;

				my @currency_codes;
				my @fields = grep { $_->{key} =~ /^34B|33B$/ } @{ $document->data };
				for (@fields) {
					my $currency_code;
					if ($_->{value} =~ /^([A-Z]{3})/) {
						$currency_code = $1;
					}
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