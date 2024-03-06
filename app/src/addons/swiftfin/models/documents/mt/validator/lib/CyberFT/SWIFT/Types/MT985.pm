package CyberFT::SWIFT::Types::MT985;
use CyberFT::SWIFT::Types::Utils;
use strict;

our $ValidationProfile = {

	fields => [

		# M	20	Transaction Reference Number	16x	See specification 1
		{
			key => '20',
			required => 1,
		},
		# O	57a	Account With Institution	A, B, or D	See specification 2
		# M	59	Enquired Party	[/34x]4*35x	See specification 3
		{
			key => '59',
			required => 1,
		},
		# O	75	Queries	6*35x	See specification 4

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
							(?:\|57[ABD])?
							\|59
							(?:\|75)?
						$/x;
				return 1;
			},
			err => 'Invalid sequence structure'
		},

	]
};

1;