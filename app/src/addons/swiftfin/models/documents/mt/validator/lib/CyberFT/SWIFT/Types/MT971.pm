package CyberFT::SWIFT::Types::MT971;
use CyberFT::SWIFT::Types::Utils;
use strict;

our $ValidationProfile = {

	fields => [

		# M	20	Transaction Reference Number	16x	See specification 1
		{
			key => '20',
			required => 1,
		},
		# ----->
		# M	25	Account Identification	35x	See specification 2
		{
			key => '25',
			required => 1,
		},
		# M	62F	Final Closing Balance	1!a6!n3!a15d	See specification 3
		{
			key => '62F',
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
							(?:
								\|25
								\|62F
							)+
						$/x;
				return 1;
			},
			err => 'Invalid sequence structure'
		},

	]
};

1;