package CyberFT::SWIFT::Types::MT800;
use CyberFT::SWIFT::Types::Utils;
use strict;

our $ValidationProfile = {

	fields => [

		# M	20	Transaction Reference Number	16x	See specification 1
		{
			key => '20',
			required => 1,
		},
		# M	51a	Selling Agent	A or C	See specification 2
		{
			key => '51a',
			required => 1,
			key_regexp => '51[AC]',
		},
		# -----> Mandatory Repetitive Sequence A Purchase Agreement Details
		# M	23	Purchase Agreement ID	16x	See specification 3
		{
			key => '23',
			required => 1,
		},
		# O	30	Sales Date	6!n	See specification 4
		# ----->
		# M	26A	Serial Numbers	16x[/4!x]	See specification 5
		{
			key => '26A',
			required => 1,
		},
		# -----|
		# M	33B	Face Amount	3!a15d	See specification 6
		{
			key => '33B',
			required => 1,
		},
		# O	73	Additional Amounts	6*35x	See specification 7
		# -----| End of Sequence A Purchase Agreement Details
		# Mandatory Sequence B Settlement Details
		# M	34B	Selling Agent Amount	3!a15d	See specification 8
		{
			key => '34B',
			required => 1,
		},
		# M	16A	Selling Agent's No. of PAs	5n	See specification 9
		{
			key => '16A',
			required => 1,
		},
		# M	32A	Settlement Amount	6!n3!a15d	See specification 10
		{
			key => '32A',
			required => 1,
		},
		# O	52a	Remitting Agent	A or D	See specification 11
		# O	53a	Sender's Correspondent	A, B, or D	See specification 12
		# O	54a	Receiver's Correspondent	A, B, or D	See specification 13
		# O	72	Sender to Receiver Information	6*35x	See specification 14

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
							\|32A
							(?:\|52[AD])?
							(?:\|53[ABD])?
							(?:\|54[ABD])?
							(?:\|72)?
						$/x;
				return 1;
			},
			err => 'Invalid sequence structure'
		},

		# C1 The amount specified in field 32A must be the same as the amount specified in
		# the preceding field 34B (Error code(s): C59).
		{
			func => sub {
				my $document = shift;

				my $field_32A_value = seq_get_first($document->data, '32A');
				$field_32A_value =~ s/[\r\n]+$//;
				my $field_34B_value = seq_get_first($document->data, '34B');
				$field_34B_value =~ s/[\r\n]+$//;

				my ( $field_32A_amount, $field_34B_amount );
				if ($field_32A_value =~ m/^\d{6}\w{3}(.+)$/) {
					$field_32A_amount = $1;
				}
				if ($field_34B_value =~ m/^\w{3}(.+)$/) {
					$field_34B_amount = $1;
				}
				return 0
					unless $field_32A_amount && $field_34B_amount;

				return 0
					unless $field_32A_amount eq $field_34B_amount;

				return 1;
			},
			err => 'C59'
		},

		# C2 The currency code in fields 34B, 32A and in all occurrences of field 33B must be the same (Error code(s): C02).
		# 32A	6!n3!a15d
		# 34B	3!a15d
		# 33B	3!a15d
		{
			func => sub {
				my $document = shift;

				my @currency_codes;
				my @fields = grep { $_->{key} =~ /^34B|32A|33B$/ } @{ $document->data };
				for (@fields) {
					my $currency_code;
					if ($_->{key} =~ /^34B|33B$/) {
						if ($_->{value} =~ /^([A-Z]{3})/) {
							$currency_code = $1;
						}
					} else {
						if ($_->{value} =~ /^\d{6}([A-Z]{3})/) {
							$currency_code = $1;
						}
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