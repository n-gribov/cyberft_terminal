package CyberFT::SWIFT::Types::MT986;
use CyberFT::SWIFT::Types::Utils;
use strict;

our $ValidationProfile = {

	fields => [

		# M	20	Transaction Reference Number	16x	See specification 1
		{
			key => '20',
			required => 1,
		},
		# M	21	Related Reference	16x	See specification 2
		{
			key => '21',
			required => 1,
		},
		# O	59	Enquired Party	[/34x] 4*35x	See specification 3
		# M	79	Narrative	35*50x	See specification 4
		{
			key => '79',
			required => 1,
		},

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
							\|21
							(?:\|59)?
							\|79
						$/x;
				return 1;
			},
			err => 'Invalid sequence structure'
		},

	]
};

1;