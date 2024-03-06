package CyberFT::SWIFT::Types::MT970;
use CyberFT::SWIFT::Types::Utils;
use strict;

our $ValidationProfile = {

	fields => [

		# M	20	Transaction Reference Number	16x	See specification 1
		{
			key => '20',
			required => 1,
		},
		# M	25	Account Identification	35x	See specification 2
		{
			key => '25',
			required => 1,
		},
		# M	28C	Statement Number/Sequence Number	5n[/5n]	See specification 3
		{
			key => '28C',
			required => 1,
		},
		# M	60a	Opening Balance	F or M	See specification 4
		{
			key => '60a',
			required => 1,
			key_regexp => '60[FM]',
		},
		# ----->
		# O	61	Statement Line	6!n[4!n]2a[1!a]15d1!a3!c16x[//16x][34x]	See specification 5
		# -----|
		# M	62a	Closing Balance	F or M	See specification 6
		{
			key => '62a',
			required => 1,
			key_regexp => '62[FM]',
		},
		# O	64	Closing Available Balance	1!a6!n3!a15d	See specification 7

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
							\|25
							\|28C
							\|60[FM]
							(?:\|61)*
							\|62[FM]
							(?:\|64)?
						$/x;
				return 1;
			},
			err => 'Invalid sequence structure'
		},

		# C1 The first two characters of the three character currency code in fields
		# 60a, 62a and 64 must be the same (Error code(s): C27).
		{
			func => sub {
				my $document = shift;

				my @currency_codes;
				my @fields = grep { $_->{key} =~ /^60[FM]|62[FM]|64$/ } @{ $document->data };
				for (@fields) {
					my $currency_code;
					if ($_->{value} =~ /^.{7}([A-Z]{2})/) {
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
			err => 'C27'
		},

	]
};

1;