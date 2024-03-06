package CyberFT::SWIFT::Types::MT824;
use CyberFT::SWIFT::Types::Utils;
use strict;

our $ValidationProfile = {

	fields => [

		# M	20	Transaction Reference Number	16x	See specification 1
		{
			key => '20',
			required => 1,
		},
		# O	30	Date Destroyed/Cancelled	6!n	See specification 2
		# M	51a	Selling Agent	A or C	See specification 3
		{
			key => '51a',
			required => 1,
			key_regexp => '51[AC]',
		},
		# ----->
		# ----->
		# M	68A	Details of Cheques	6n3!a6n/2n[/15d][//10x]	See specification 4
		{
			key => '68A',
			required => 1,
		},
		# ----->
		# M	26B	Serial Numbers	16x[/16x]	See specification 5
		{
			key => '26B',
			required => 1,
		},
		# -----|
		# -----|
		# M	19	Total Face Value of Currency	17d	See specification 6
		{
			key => '19',
			required => 1,
		},
		# -----|
		# M	77B	Reason for Destruction/Cancellation	3*35x	See specification 7
		{
			key => '77B',
			required => 1,
		},
		# O	72	Sender to Receiver Information	6*35x	See specification 8

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
							(?:\|30)?
							\|51[AC]
							(?:
								(?:
									\|68A
									(?:\|26B)+
								)+
								\|19
							)+
							\|77B
							(?:\|72)?
						$/x;
				return 1;
			},
			err => 'Invalid sequence structure'
		},

		# C1 The currency code in all fields 68A in a currency details sequence preceding a field 19, must be the same (Error code(s): C42).
		# 6n3!a6n/2n[/15d][//10x]
		{
			func => sub {
				my $document = shift;

				my @currency_codes;
				my @fields = grep { $_->{key} eq '68A' } @{ $document->data };
				for (@fields) {
					my $currency_code;
					if ($_->{value} =~ /^\d+([A-Z]{3})/) {
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

				return 1;
			},
			err => 'C42'
		},

		# C2 Field 19 at the completion of each outer repetitive sequence must equal the sum
		# of the products of the Number and Denomination subfields in all occurrences of
		# field 68A from the respective inner repetitive sequence (Error code(s): C01).
		{
			func => sub {
				my $document = shift;

				my $current_sequence = {};
				my $amount = 0;
				foreach my $field (@{ $document->data }) {
					if ($field->{key} eq '68A') {
						if ($field->{value} =~ m/^(\d+)[A-Z]{3}(\d+)/) {
							$amount += $1 * $2;
						} else {
							return 0;
						}
					} elsif ($field->{key} eq '19') {
						if ($field->{value} =~ m/^(\d+,\d*)[\r\n]*$/){
							my $amount_to_check = $1;
							$amount_to_check =~ s/\,$//;
							$amount_to_check =~ s/,/\./g;
							return 0
								unless abs($amount_to_check - $amount) <= 0.0001;
						} else {
							return 0;
						}
						$amount = 0;
					}
				}

				return 1;
			},
			err => 'C01'
		},

	]
};

1;