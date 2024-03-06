package CyberFT::SWIFT::Types::MT935;
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
		# O	23	Further Identification	16x	See specification 2
		# O	25	Account Identification	35x	See specification 3
		# M	30	Effective Date of New Rate	6!n	See specification 4
		{
			key => '30',
			required => 1,
		},
		# ----->
		# M	37H	New Interest Rate	1!a12d	See specification 5
		{
			key => '37H',
			required => 1,
		},
		# -----|
		# -----|
		# O	72	Sender to Receiver Information	6*35x	See specification 6

	],

	rules => [

		# Check keys sequence
		{
			func => sub {
				my $document = shift;
				my ( $is_valid_sequence, $loops ) = extract_loops($document);
				return $is_valid_sequence;
			},
			err => 'Invalid sequence structure'
		},

		# C1 The repetitive sequence must appear at least once, but not more than ten times (Error code(s): T10).
		{
			func => sub {
				my $document = shift;
				my ( $is_valid_sequence, $loops ) = extract_loops($document);
				return 0
					unless @$loops >= 1 && @$loops <= 10;
				return 1;
			},
			err => 'T10'
		},

		# C2 Either field 23 or field 25, but not both, must be present in any repetitive sequence (Error code(s): C83).
		{
			func => sub {
				my $document = shift;
				my ( $is_valid_sequence, $loops ) = extract_loops($document);
				for (@$loops) {
					return 0
						unless seq_key_exists($_, '23') ^ seq_key_exists($_, '25');
				}
				return 1;
			},
			err => 'C83'
		}

	]
};

sub extract_loops {
	my $document = shift;

	my $data = $document->{data};
	for (@$data) {
		$_->{value} =~ s/[\r\n]+$//;
	}

	my $keys_string = join('|', map { $_->{key} } @$data); # '20|23|25|30|37H|37H|23|30|37H|72'
	unless ($keys_string =~ m/^20(?:(?:\|23)?(?:\|25)?(?:\|30)(?:\|37H)+)*(?:\|72)?$/) {
		warn "Invalid keys sequence: $keys_string";
		return ( 0, undef );
	}

	my @loops_keys;
	@loops_keys = $keys_string =~ m/((?:\|23)?(?:\|25)?(?:\|30)(?:\|37H)+)/g;
	@loops_keys = map { $_ =~ s/^\|//; [ split /\|/, $_ ] } @loops_keys;

	my $loops = [];
	my $data_idx = 1; # skip field 20
	foreach my $loop_keys (@loops_keys) {
		my $current_loop = [];
		foreach my $loop_key (@$loop_keys) {
			unless ($data->[$data_idx]{key} eq $loop_key) {
				warn "Unexpected key " .  $data->[$data_idx]{key};
				return ( 0, undef );
			}
			push @$current_loop, $data->[$data_idx];
			$data_idx++;
		}
		push @$loops, $current_loop;
	}
	return ( 1, $loops );
}

1;