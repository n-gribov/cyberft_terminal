package CyberFT::SWIFT::Types::MT942;
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
		# M	28C	Statement Number/Sequence Number	5n[/5n]	See specification 4
		{
			key => '28C',
			required => 1,
		},
		# M	34F	Debit/(Debit and Credit) Floor Limit Indicator	3!a[1!a]15d	See specification 5
		{
			key => '34F',
			required => 1,
		},
		# O	34F	Credit Floor Limit Indicator	3!a[1!a]15d	See specification 6
		# M	13D	Date/Time Indication	6!n4!n1!x4!n	See specification 7
		{
			key => '13D',
			required => 1,
		},
		# ----->
		# O	61	Statement Line	6!n[4!n]2a[1!a]15d1!a3!c16x[//16x][34x]	See specification 8
		# O	86	Information to Account Owner	6*65x	See specification 9
		# -----|
		# O	90D	Number and Sum of Entries	5n3!a15d	See specification 10
		# O	90C	Number and Sum of Entries	5n3!a15d	See specification 11
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
							\|28C
							\|34F
							(?:\|34F)?
							\|13D
							(?:
								(?:\|61)?
								(?:\|86)?
							)*
							(?:\|90D)?
							(?:\|90C)?
							(?:\|86)?
						$/x;
				return 1;
			},
			err => 'Invalid sequence structure'
		},

		# C1 The first two characters of the three character currency code in fields 34F, 90D, and 90C
		# must be the same (Error code(s): C27).
		# 34F 3!a[1!a]15d
		# 90D 5n3!a15d
		# 90C 5n3!a15d
		{
			func => sub {
				my $document = shift;

				my @currency_codes;
				my @fields = grep { $_->{key} =~ /^34F|90D|90C$/ } @{ $document->data };
				for (@fields) {
					my $currency_code;
					if ($_->{key} =~ /^90D|90C$/) {
						if ($_->{value} =~ /^\d*?([A-Z]{2})/) {
							$currency_code = $1;
						}
					} else {
						if ($_->{value} =~ /^([A-Z]{2})/) {
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

		# C2 When only one field 34F is present, the second subfield must not be used. When both fields 34F
		# are present, subfield 2 of the first 34F must contain the value 'D', and subfield 2 of the second
		# 34F must contain the value 'C' (Error code(s): C23).
		{
			func => sub {
				my $document = shift;

				my @currency_codes;
				my @fields = grep { $_->{key} =~ /^34F$/ } @{ $document->data };
				if (@fields == 1) {
					return 0
						if $fields[0]->{value} =~ /^.{3}[CD]/;
				} else {
					return 0
						unless $fields[0]->{value} =~ /^.{3}D/
							&& $fields[1]->{value} =~ /^.{3}C/;
				}

				return 1;
			},
			err => 'C23'
		},

		# C3 If field 86 is present in any occurrence of the repetitive sequence, it must be preceded by
		# a field 61 except if that field 86 is the last field in the message, then field 61 is optional.
		# In addition, if field 86 is present, it must be on the same page (message) of the statement as
		# the related field 61 (Error code(s): C24).
		#
		# If the field 86 is the last field in the message and it is immediately preceded by field 61,
		# then that field 86 is considered to provide information about the preceding field 61; otherwise
		# the field 86 is considered to provide information about the message as a whole.
		{
			func => sub {
				my $document = shift;
				my $keys_string = join('|', map { $_->{key} } @{ $document->data }); # '20|23|25|30|37H|37H|23|30|37H|72'
				return 0
					unless $keys_string =~ m/^
								20
								(?:\|21)?
								\|25
								\|28C
								\|34F
								(?:\|34F)?
								\|13D
								(?:
									\|61
									(:?\|86)?
								)*
								(?:\|90D)?
								(?:\|90C)?
								(?:\|86)?
							$/x;
				return 1;
			},
			err => 'C24'
		}

	]
};

1;