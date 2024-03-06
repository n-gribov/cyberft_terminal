package CyberFT::SWIFT::Types::MT802;
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
		# M	32A	Settlement Amount	6!n3!a15d	See specification 3
		{
			key => '32A',
			required => 1,
		},
		# O	52a	Remitting Agent	A or D	See specification 4
		# O	53a	Sender's Correspondent	A, B, or D	See specification 5
		# O	54a	Receiver's Correspondent	A, B, or D	See specification 6
		# O	72	Sender to Receiver Information	6*35x	See specification 7

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

	]
};

1;