package CyberFT::SWIFT::Types::MT941;
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
		# M	25	Account Identification	35x	See specification 3
		{
			key => '25',
			required => 1,
		},
		# M	28	Statement Number/Sequence Number	5n[/2n]	See specification 4
		{
			key => '28',
			required => 1,
		},
		# O	13D	Date/Time Indication	6!n4!n1!x4!n	See specification 5
		# O	60F	Opening Balance	1!a6!n3!a15d	See specification 6
		# O	90D	Number and Sum of Entries	5n3!a15d	See specification 7
		# O	90C	Number and Sum of Entries	5n3!a15d	See specification 8
		# M	62F	Book Balance	1!a6!n3!a15d	See specification 9
		{
			key => '62F',
			required => 1,
		},
		# O	64	Closing Available Balance (Available Funds)	1!a6!n3!a15d	See specification 10
		# ----->
		# O	65	Forward Available Balance	1!a6!n3!a15d	See specification 11
		# -----|
		# O	86	Information to Account Owner	6*65x	See specification 12

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
							\|25
							\|28
							(?:\|13D)?
							(?:\|60F)?
							(?:\|90D)?
							(?:\|90C)?
							\|62F
							(?:\|64)?
							(?:\|65)*
							(?:\|86)?
						$/x;
				return 1;
			},
			err => 'Invalid sequence structure'
		},

		# C1 The first two characters of the three character currency code in fields
		# 60F, 90D, 90C, 62F, 64 and 65 must be the same for all occurrences of
		# these fields (Error code(s): C27).
		# O	60F	1!a6!n3!a15d
		# O	90D	5n3!a15d
		# O	90C	5n3!a15d
		# M	62F	1!a6!n3!a15d
		# O	64	1!a6!n3!a15d
		# O	65	1!a6!n3!a15d
		{
			func => sub {
				my $document = shift;

				my @currency_codes;
				my @fields = grep { $_->{key} =~ /^60F|90D|90C|62F|64|65$/ } @{ $document->data };
				for (@fields) {
					my $currency_code;
					if ($_->{key} =~ /^90D|90C$/) {
						if ($_->{value} =~ /^\d*?([A-Z]{2})/) {
							$currency_code = $1;
						}
					} else {
						if ($_->{value} =~ /^\S\d{6}([A-Z]{2})/) {
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
			err => 'C27'
		},

	]
};

1;