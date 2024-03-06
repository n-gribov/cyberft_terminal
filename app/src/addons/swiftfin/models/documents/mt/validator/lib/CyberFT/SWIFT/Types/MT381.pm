package CyberFT::SWIFT::Types::MT381;
use CyberFT::SWIFT::Types::Utils;
use strict;

our $ValidationProfile = {

	fields => [

		# Mandatory Sequence A General Information
		# M	16R	 	 	Start of Block	GENL	See specification 1
		{
			key => '16R',
			required => 1,
		},
		# M	20C	SEME	Reference	Sender's Reference	:4!c//16x	See specification 2
		{
			key => '20C',
			required => 1,
		},
		# M	23G	 	 	Function of the Message	4!c[/4!c]	See specification 3
		{
			key => '23G',
			required => 1,
		},
		# O	22H	FXTR	Indicator	FX Order Transaction Type Indicator	:4!c//4!c	See specification 4
		{
			key => '22H',
			required => 0,
		},

		# -----> Optional Repetitive Subsequence A1 Linkages
		# M	16R	 	 	Start of Block	LINK	See specification 5
		{
			key => '16R',
			required => 0,
		},
		# O	13a	LINK	Number Identification	Linked Message	A or B	See specification 6
		{
			key => '13a',
			required => 0,
			key_regexp => '13[AB]'
		},
		# M	20C	4!c	Reference	(see qualifier description)	:4!c//16x	See specification 7
		{
			key => '20C',
			required => 0,
		},
		# M	16S	 	 	End of Block	LINK	See specification 8
		{
			key => '16S',
			required => 0,
		},
		# -----| End of Subsequence A1 Linkages

		# M	16S	 	 	End of Block	GENL	See specification 9
		{
			key => '16S',
			required => 1,
		},
		# End of Sequence A General Information


		# Mandatory Sequence B FX Order Details
		# M	16R	 	 	Start of Block	FXORDER	See specification 10
		{
			key => '16R',
			required => 1,
		},
		# ----->
		# M	98a	4!c	Date/Time	(see qualifier description)	A or C	See specification 11
		{
			key => '98a',
			required => 1,
			key_regexp => '98[AC]'
		},
		# -----|
		# ----->
		# M	19B	4!c	Amount	(see qualifier description)	:4!c//3!a15d	See specification 12
		{
			key => '19B',
			required => 1,
		},
		# -----|
		# M	92B	EXCH	Rate	Exchange Rate	:4!c//3!a/3!a/15d	See specification 13
		{
			key => '92B',
			required => 1,
		},

		# Mandatory Subsequence B1 FX Order Parties
		# M	16R	 	 	Start of Block	ORDRPRTY	See specification 14
		{
			key => '16R',
			required => 1,
		},
		# O	95a	INVE	Party	Investor	P, Q, or R	See specification 15
		{
			key => '95a',
			required => 0,
			key_regexp => '95[PQR]'
		},
		# M	97A	SAFE	Account	Safekeeping Account	:4!c//35x	See specification 16
		{
			key => '97A',
			required => 1,
		},
		# M	16S	 	 	End of Block	ORDRPRTY	See specification 17
		{
			key => '16S',
			required => 1,
		},
		# End of Subsequence B1 FX Order Parties

		# Optional Subsequence B2 Reason
		# M	16R	 	 	Start of Block	REAS	See specification 18
		{
			key => '16R',
			required => 0,
		},
		# M	24B	REAS	Reason Code	Transaction Reason Code	:4!c/[8c]/4!c	See specification 19
		{
			key => '24B',
			required => 0,
		},
		# O	70D	REAS	Narrative	Transaction Reason Narrative	:4!c//6*35x	See specification 20
		{
			key => '70D',
			required => 0,
		},
		# M	16S	 	 	End of Block	REAS	See specification 21
		{
			key => '16S',
			required => 0,
		},
		# End of Subsequence B2 Reason

		# M	16S	 	 	End of Block	FXORDER	See specification 22
		{
			key => '16S',
			required => 1,
		},
		# End of Sequence B FX Order Details

		# Optional Sequence C Underlying Transaction Details
		# M	16R	 	 	Start of Block	UNDE	See specification 23
		{
			key => '16R',
			required => 0,
		},
		# O	35B	 	 	Identification of the Financial Instrument	[ISIN1!e12!c] [4*35x]	See specification 24
		{
			key => '35B',
			required => 0,
		},
		# O	36B	ESTT	Quantity of Financial Instrument	Quantity of Financial Instrument Settled	:4!c//4!c/15d	See specification 25
		{
			key => '36B',
			required => 0,
		},
		# O	22F	AGRE	Indicator	Agreement Indicator	:4!c/[8c]/4!c	See specification 26
		{
			key => '22F',
			required => 0,
		},
		# O	70E	TRDE	Narrative	Transaction Details Narrative	:4!c//10*35x	See specification 27
		{
			key => '70E',
			required => 0,
		},
		# M	16S	 	 	End of Block	UNDE	See specification 28
		{
			key => '16S',
			required => 0,
		},
		# End of Sequence C Underlying Transaction Details
	],

	rules => [
		# Mandatory sequences
		mandatory_sequence_check_rule('A'),
		mandatory_sequence_check_rule('B'),
		mandatory_sequence_check_rule('B1'),

		# Mandatory fields in sequences
		sequence_mandatory_fields_check_rule('A',  [qw/16R 20C 23G 16S/]),
		sequence_mandatory_fields_check_rule('A1', [qw/16R 20C 16S/]),
		sequence_mandatory_fields_check_rule('B',  [qw/16R 98[AC] 19B 92B 16S/]),
		sequence_mandatory_fields_check_rule('B1', [qw/16R 97A 16S/]),
		sequence_mandatory_fields_check_rule('B2', [qw/16R 24B 16S/]),

		# Check that sequence A1 is the only repetative sequence
		{
			func => sub {
				my $document = shift;
				my $sequences_hash = find_sequences($document);

				foreach my $sequence_id (keys %$sequences_hash) {
					return 0
						if $sequence_id ne 'A1' && @{ $sequences_hash->{$sequence_id} } > 1;
				}

				return 1;
			},
			err => "Disallowed repetative sequence found"
		},

		# C 1 If the message is a cancellation, that is, field 23G is CANC, then subsequence A1
		# must be present at least once, and a reference to the previous message must be
		# specified in the Linkage section, that is, field :20C::PREV must be present at
		# least once in that message (Error code(s): E08).
		{
			func => sub {
				my $document = shift;
				return 1
					unless sequence_key_with_value_exists($document, "A", "23G", "CANC");

				my $a1_sequences = get_sequences_by_id($document, 'A1');
				foreach my $sequence (@$a1_sequences) {
					foreach my $field (@$sequence) {
						return 1
							if $field->{key} eq '20C' && $field->{value} =~ /^\:PREV\/\/.{0,16}$/;
					}
				}
				return 0;
			},
			err => 'E08',
		},

		# C2 In sequence C, fields 16R and 16S may not be the only fields present. If both
		# fields 16R and 16S are present, then at least one other field of the same
		# sequence must be present (Error code(s): D13).
		{
			func => sub {
				my $document = shift;
				return 1
					unless sequence_exists($document, 'C');

				my $c_sequence = get_sequences_by_id($document, 'C')->[0];
				for (qw/35B 36B 22F 70E/) {
					return 1
						if seq_key_exists($c_sequence, $_);
				}
				return 0;
			},
			err => 'D13',
		}
	]
};

sub mandatory_sequence_check_rule{
	my $sequence_id = shift;
	return {
		func => sub {
			my $document = shift;
			return sequence_exists($document, $sequence_id);
		},
		err => "Missing required sequence $sequence_id"
	};
}

sub sequence_mandatory_fields_check_rule {
	my $sequence_id = shift;
	my $keys_regexps = shift;

	return {
		func => sub {
			my $document = shift;
			my $sequences = get_sequences_by_id($document, $sequence_id);
			foreach my $sequence (@$sequences) {
				foreach my $key_regexp (@$keys_regexps) {
					return 0
						unless seq_key_exists($sequence, $key_regexp);
				}
			}
			return 1;
		},
		err => "Missing required field in $sequence_id sequence"
	};
}

sub sequence_exists {
	my $document = shift;
	my $sequence_id = shift;
	my $sequences = get_sequences_by_id($document, $sequence_id);
	return 0
		unless $sequences && @$sequences > 0;
	return 1;
}

sub sequence_key_with_value_exists {
	my $document = shift;
	my $sequence_id = shift;
	my $key = shift;
	my $value = shift;
	my $sequences = get_sequences_by_id($document, $sequence_id);
	return 0
		unless $sequences && @$sequences > 0;
	foreach my $sequece (@$sequences) {
		for (@$sequece) {
			return 1
				if $_->{key} eq $key && $_->{value} eq $value;
		}
	}
	return 1;
}

sub get_sequences_by_id {
	my $document = shift;
	my $sequence_id = shift;
	return find_sequences($document)->{$sequence_id} || [];
}

sub find_sequences {
	my $document = shift;

	my $sequence_start_key = '16R';
	my $sequence_end_key = '16S';
	my $sequence_markers = {
		GENL     => 'A',
		LINK     => 'A1',
		FXORDER  => 'B',
		ORDRPRTY => 'B1',
		REAS     => 'B2',
		UNDE     => 'C'
	};

	my $sequences = {};
	my ( $current_sequence_id, $parent_sequence_id );
	my ( $current_sequence, $parent_sequence ) = ( [], [] );
	for (@{ $document->data }) {
		my ( $key, $value ) = ( $_->{key}, $_->{value} );
		$value =~ s/[\r\n]+$//s;

		if ($key eq $sequence_start_key) {
			$parent_sequence = $current_sequence;
			$current_sequence = [];

			$parent_sequence_id = $current_sequence_id;
			$current_sequence_id = $sequence_markers->{$value};
		}

		next unless $current_sequence_id;
		push @$current_sequence, { key => $key, value => $value };

		if ($key eq $sequence_end_key) {
			push @{ $sequences->{$current_sequence_id} }, $current_sequence;

			$current_sequence = $parent_sequence_id ? $parent_sequence : [];
			$parent_sequence = [];

			$current_sequence_id = $parent_sequence_id;
			$parent_sequence_id = undef;
		}
	}
	return $sequences;
}

1;