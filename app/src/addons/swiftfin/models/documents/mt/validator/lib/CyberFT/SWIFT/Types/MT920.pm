package CyberFT::SWIFT::Types::MT920;
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
		# M	12	Message Requested	3!n	See specification 2
		{
			key => '12',
			required => 1,
		},
		# M	25	Account Identification	35x	See specification 3
		{
			key => '25',
			required => 1,
		},
		# O	34F	Debit/(Debit and Credit) Floor Limit Indicator	3!a[1!a]15d	See specification 4
		# O	34F	Credit Floor Limit Indicator	3!a[1!a]15d	See specification 5
		# -----|
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

		# C1 If field 12 contains the value '942', at least field 34F Debit/(Debit and Credit)
		# Floor Limit Indicator must be present in the same repetitive sequence (Error code(s): C22).
		{
			func => sub {
				my $document = shift;
				my ( $is_valid_sequence, $loops ) = extract_loops($document);
				for (@$loops) {
					if (seq_key_value_exists($_, '12','942')) {
						return 0
							unless seq_key_exists($_, '34F');
					}
				}
				return 1;
			},
			err => 'C22'
		},

		# C2 Within each repetitive sequence, when only one field 34F is present, the second subfield
		# must not be used. When both fields 34F are present, subfield 2 of the first 34F must contain
		# the value 'D', and subfield 2 of the second 34F must contain the value 'C' (Error code(s): C23).
		{
			func => sub {
				my $document = shift;
				my ( $is_valid_sequence, $loops ) = extract_loops($document);
				for (@$loops) {
					my @field_34F_values = map { $_->{value} } grep { $_->{key} eq '34F' } @$_;
					if (@field_34F_values == 1) {
						return 0
							unless $field_34F_values[0] =~ m/^[A-Z]{3}\d/;
					} elsif (@field_34F_values == 2) {
						return 0
							unless $field_34F_values[0] =~ m/^[A-Z]{3}D\d/
								&& $field_34F_values[1] =~ m/^[A-Z]{3}C\d/;
					} elsif (@field_34F_values != 0) {
						return 0;
					}
				}
				return 1;
			},
			err => 'C23'
		},

		# C3 The currency code must be the same for each occurrence of field 34F within
		# each repetitive sequence (Error code(s): C40).
		{
			func => sub {
				my $document = shift;
				my ( $is_valid_sequence, $loops ) = extract_loops($document);
				foreach my $loop (@$loops) {

					my @field_34F_values = map { $_->{value} } grep { $_->{key} eq '34F' } @$loop;
					return 1 unless @field_34F_values == 2;
					my @currency_codes = map { substr($_, 0, 3) } @field_34F_values;
					return 0
						unless $currency_codes[0] eq $currency_codes[1];

				}
				return 1;
			},
			err => 'C40'
		},

	]
};

sub extract_loops {
	my $document = shift;

	my $data = $document->{data};
	for (@$data) {
		$_->{value} =~ s/[\r\n]+$//;
	}

	my $keys_string = join('|', map { $_->{key} } @$data); # '20|12|25|12|25|34F|34F|12|25'
	unless ($keys_string =~ m/^20(?:\|12\|25(?:\|34F)?(?:\|34F)?)+$/) {
		warn "Invalid keys sequence: $keys_string";
		return ( 0, undef );
	}

	my @loops_keys;
	@loops_keys = $keys_string =~ m/(\|12\|25(?:\|34F)?(?:\|34F)?)/g;
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