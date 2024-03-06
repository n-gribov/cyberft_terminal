package CyberFT::SWIFT::Types::MT365;
use CyberFT::SWIFT::Types::Utils;
use strict;

our $ValidationProfile = {

	fields => [

		# M	15A	New Sequence	Empty field	See specification
		{
			key => '15A',
			required => 1,
			allow_empty => 1
		},
		# M	20	Sender's Reference	16x	See specification
		{
			key => '20',
			required => 1,
		},
		# O	21	Related Reference	16x	See specification
		# M	22A	Type of Operation	4!c	See specification
		{
			key => '22A',
			required => 1,
		},
		# O	94A	Scope of Operation	4!c	See specification
		# M	22B	Type of Event	4!c	See specification
		{
			key => '22B',
			required => 1,
		},
		# M	22C	Common Reference	4!a2!c4!n4!a2!c	See specification
		{
			key => '22C',
			required => 1,
		},
		# M	23A	Identification of the Swap	10a/5a	See specification
		{
			key => '23A',
			required => 1,
		},
		# M	21N	Contract Number Party A	16x	See specification
		{
			key => '21N',
			required => 1,
		},
		# O	21B	Contract Number Party B	16x	See specification
		# M	30T	Termination/Recouponing Trade Date	8!n	See specification
		{
			key => '30T',
			required => 1,
		},
		# M	30Q	Termination/Recouponing Effective Date	8!n	See specification
		{
			key => '30Q',
			required => 1,
		},
		# M	30P	Original Termination Date	8!n	See specification
		{
			key => '30P',
			required => 1,
		},
		# M	30V	Original Effective Date	8!n	See specification
		{
			key => '30V',
			required => 1,
		},
		# M	32B	Party B Current Currency, Notional Amount	3!a15d	See specification
		{
			key => '32B',
			required => 1,
		},
		# M 33B	Party A Current Currency, Notional Amount	3!a15d	See specification
		{
			key => '33B',
			required => 1,
		},
		# M	82a	Party A	A or D	See specification
		{
			key => '82a',
			required => 1,
			key_regexp => '82[AD]',
		},
		# M	87a	Party B	A or D	See specification
		{
			key => '87a',
			required => 1,
			key_regexp => '87[AD]',
		},
		# O	83a	Fund or Beneficiary Customer	A, D, or J	See specification
		# O	22D	Accrual of Interest Specification	4!c	See specification
		# O	32G	Party B New Currency, Notional Amount	3!a15d	See specification
		# O	33E	Party A New Currency, Notional Amount	3!a15d	See specification
		# O	37N	Details of Interest Rate	6*35x	See specification
		# O	29A	Contact Information	4*35x	See specification
		# O	72	Sender to Receiver Information	6*35x	See specification
		# End of Sequence A General Information

		# Optional Sequence B Fixed Interest Payable by Party B
		# M	15B	New Sequence	Empty field	See specification
		# M	37U	Current Fixed Rate	12d	See specification
		# O	37P	New Fixed Rate	12d	See specification
		# End of Sequence B Fixed Interest Payable by Party B

		# Optional Sequence E Fixed Interest Payable by Party A
		# M	15E	New Sequence	Empty field	See specification
		# M	37U	Current Fixed Rate	12d	See specification
		# O	37P	New Fixed Rate	12d	See specification
		# End of Sequence E Fixed Interest Payable by Party A

		# Optional Sequence J Re-exchange of Principal Payable by Party B
		# M	15J	New Sequence	Empty field	See specification
		# M	30F	Payment Date	8!n	See specification
		# M	32M	Currency, Payment Amount	3!a15d	See specification
		# O	53a	Delivery Agent	A or D	See specification
		# O	56a	Intermediary	A or D	See specification
		# O	86a	Second Intermediary	A or D	See specification
		# M	57a	Receiving Agent	A or D	See specification
		# End of Sequence J Re-exchange of Principal Payable by Party B

		# Optional Sequence K Re-exchange of Principal Payable by Party A
		# M	15K	New Sequence	Empty field	See specification
		# M	30F	Payment Date	8!n	See specification
		# M	32M	Currency, Payment Amount	3!a15d	See specification
		# O	53a	Delivery Agent	A or D	See specification
		# O	56a	Intermediary	A or D	See specification
		# O	86a	Second Intermediary	A or D	See specification
		# M	57a	Receiving Agent	A or D	See specification
		# End of Sequence K Re-exchange of Principal Payable by Party A

		# Optional Sequence L Fee Payable by Party B
		# M	15L	New Sequence	Empty field	See specification
		# M	30F	Payment Date	8!n	See specification
		# M	32M	Currency, Payment Amount	3!a15d	See specification
		# O	53a	Delivery Agent	A or D	See specification
		# O	56a	Intermediary	A or D	See specification
		# O	86a	Second Intermediary	A or D	See specification
		# M	57a	Receiving Agent	A or D	See specification
		# End of Sequence L Fee Payable by Party B

		# Optional Sequence M Fee Payable by Party A
		# M	15M	New Sequence	Empty field	See specification
		# M	30F	Payment Date	8!n	See specification
		# M	32M	Currency, Payment Amount	3!a15d	See specification
		# O	53a	Delivery Agent	A or D	See specification
		# O	56a	Intermediary	A or D	See specification
		# O	86a	Second Intermediary	A or D	See specification
		# M	57a	Receiving Agent	A or D	See specification
		# End of Sequence M Fee Payable by Party A
	],

	rules => [
		# Mandatory sequences
		mandatory_sequence_check_rule('A'),

		# Mandatory fields in sequences
		sequence_mandatory_fields_check_rule('A', [qw/20 22A 22B 22C 23A 21N 30T 30Q 30P 30V 32B 33B 82[AD] 87[AD]/]),

		# C6 In all optional sequences, the fields with status M must be present if the sequence is present
		# and are otherwise not allowed (Error code(s): C32).
		sequence_mandatory_fields_check_rule('B', [qw/37U/], 'C32'),
		sequence_mandatory_fields_check_rule('E', [qw/37U/], 'C32'),
		sequence_mandatory_fields_check_rule('J', [qw/30F 32M 57[AD]/], 'C32'),
		sequence_mandatory_fields_check_rule('K', [qw/30F 32M 57[AD]/], 'C32'),
		sequence_mandatory_fields_check_rule('L', [qw/30F 32M 57[AD]/], 'C32'),
		sequence_mandatory_fields_check_rule('M', [qw/30F 32M 57[AD]/], 'C32'),

		# C1 The message must contain the current fixed interest rate(s). The use of the fixed interest rate
		# sequences depends on the type of the original transaction. Thus, the presence of sequences B and E
		# depends on subfield 1 in field 23A of sequence A as follows (Error code(s): E33):
		#
		# Sequence A
		# if subfield 1 of field 23A is ...	Then sequence B is ...	And sequence E is ...
		# FIXEDFIXED	Mandatory	Mandatory
		# FLOATFLOAT	Not allowed	Not allowed
		# FLOATFIXED	Mandatory	Not allowed
		# FIXEDFLOAT	Not allowed	Mandatory
		{
			func => sub {
				my $document = shift;
				my $field_23A_value = seq_get_first($document->data, '23A');
				my $prefix;
				if ($field_23A_value && $field_23A_value =~ m/^([A-Z]{1,10})\//) {
					$prefix = $1;
				} else {
					return 1;
				}
				my $sequence_B_exists = sequence_exists($document, 'B');
				my $sequence_E_exists = sequence_exists($document, 'E');
				my $sequence_B_should_exist =
					( $prefix eq 'FIXEDFIXED' || $prefix eq 'FLOATFIXED' ) ? 1 : 0;
				my $sequence_E_should_exist =
					( $prefix eq 'FIXEDFIXED' || $prefix eq 'FIXEDFLOAT' ) ? 1 : 0;
				return 0
					if ( $sequence_B_exists ^ $sequence_B_should_exist )
					|| ( $sequence_E_exists ^ $sequence_E_should_exist );

				return 1;
			},
			err => 'E33'
		},

		# C2 The new fixed rates should only be used in the case of recouponing; the new notional amounts
		# should only be used in the case of a partial termination. Thus, in sequence A, the presence of
		# fields 32G, 33E and 22D and, in sequences B and E, the presence of field 37P, depends on field 22B
		# in sequence A as follows (Error code(s): E34):
		#
		# Sequence A       Sequence A         Sequence A        Sequence A        Sequence B        Sequence E
		# if field 22B is  then field 32G is  and field 33E is  and field 22D is  and field 37P is  and field 37P is
		# PTRC             Mandatory          Mandatory         Mandatory         Mandatory         Mandatory
		# PTRM             Mandatory          Mandatory         Mandatory         Not allowed       Not allowed
		# RCPN             Not allowed        Not allowed       Mandatory         Mandatory         Mandatory
		# TERM             Not allowed        Not allowed       Not allowed       Not allowed       Not allowed
		{
			func => sub {
				my $document = shift;
				my $field_22B_value = seq_get_first($document->data, '22B');
				$field_22B_value =~ s/[\r\n]+$//;
				return 1
					unless $field_22B_value =~ /^PTRC|PTRM|RCPN|TERM$/;

				my $field_should_exist = {
					A => {
						'32G' => $field_22B_value =~ m/PTRC|PTRM/ ? 1 : 0,
						'33E' => $field_22B_value =~ m/PTRC|PTRM/ ? 1 : 0,
						'22D' => $field_22B_value =~ m/PTRC|PTRM|RCPN/ ? 1 : 0
					},
					B => {
						'37P' => $field_22B_value =~ m/PTRC|RCPN/ ? 1 : 0
					},
					E => {
						'37P' => $field_22B_value =~ m/PTRC|RCPN/ ? 1 : 0
					}
				};
				foreach my $sequence_id (keys %$field_should_exist) {
					foreach my $key_id (keys %{ $field_should_exist->{$sequence_id} }) {
						my $value = get_sequence_field_value($document, $sequence_id, $key_id);
						my $should_exist = $field_should_exist->{$sequence_id}{$key_id};
						my $exists = defined($value) && $value ne '';
						return 0
							if $should_exist ^ $exists;
					}
				}

				return 1;
			},
			err => 'E34'
		},

		# C3 The second intermediary field can only be used if more than one intermediary is required.
		# Thus, for all occurrences of fields 56a and 86a, the following rules apply (Error code(s): E35):
		# In any sequence,  Then, in the same sequence,
		# if field 56a is   field 86a is
		# Present           Optional
		# Not present       Not allowed
		{
			func => sub {
				my $document = shift;
				foreach my $sequence_id (qw/J K L M/) {
					my $sequence = get_sequence_by_id($document, $sequence_id);
					return 0
						if !seq_key_exists($sequence, '56[AD]')
							&& seq_key_exists($sequence, '86[AD]');
				}
				return 1;
			},
			err => 'E35'
		},

		# C4 The related reference is required in the case of an amendment or cancellation.
		# Thus, in sequence A, the presence of field 21 depends on field 22A as follows (Error code(s): D02):
		# Sequence A       Sequence A
		# if field 22A is  then field 21 is
		# AMND             Mandatory
		# CANC             Mandatory
		# DUPL             Optional
		# NEWT             Optional
		{
			func => sub {
				my $document = shift;
				my $field_22A_value = seq_get_first($document->data, '22A');
				return 1
					unless defined($field_22A_value);

				$field_22A_value =~ s/[\r\n]+$//;

				return 1
					unless $field_22A_value eq 'AMND' || $field_22A_value eq 'CANC';
				my $sequence_A = get_sequence_by_id($document, 'A');

				return 0
					unless seq_key_exists($sequence_A, '21');
				return 1;
			},
			err => 'D02'
		},

		# C5 In sequence A, if field 22D contains code OTHR, then field 37N must be present (Error code(s): E36).
		{
			func => sub {
				my $document = shift;
				my $field_22D_value = seq_get_first($document->data, '22D');

				return 1
					unless defined($field_22D_value);

				$field_22D_value =~ s/[\r\n]+$//;
				return 1
					unless $field_22D_value eq 'OTHR';
				my $sequence_A = get_sequence_by_id($document, 'A');
				return 0
					unless seq_key_exists($sequence_A, '37N');
				return 1;
			},
			err => 'E36'
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
	my $error = shift;

	return {
		func => sub {
			my $document = shift;
			my $sequence = get_sequence_by_id($document, $sequence_id);
			return 1
				unless $sequence && @$sequence;
			foreach my $key_regexp (@$keys_regexps) {
				return 0
					unless seq_key_exists($sequence, $key_regexp);
			}
			return 1;
		},
		err => $error || "Missing required field in $sequence_id sequence"
	};
}

sub sequence_exists {
	my $document = shift;
	my $sequence_id = shift;
	my $sequence = get_sequence_by_id($document, $sequence_id);
	return 0
		unless $sequence && @$sequence > 0;
	return 1;
}

sub get_sequence_field_value {
	my $document = shift;
	my $sequence_id = shift;
	my $field_key = shift;
	my $sequence = get_sequence_by_id($document, $sequence_id);
	return undef
		unless $sequence && @$sequence > 0;
	for (@$sequence) {
		return $_->{value}
			if $_->{key} eq $field_key;
	}
	return undef;
}

sub get_sequence_by_id {
	my $document = shift;
	my $sequence_id = shift;
	return find_sequences($document)->{$sequence_id};
}

sub find_sequences {
	my $document = shift;

	my $sequence_markers = {
		'15A' => 'A',
		'15B' => 'B',
		'15E' => 'E',
		'15J' => 'J',
		'15K' => 'K',
		'15L' => 'L',
		'15M' => 'M'
	};

	my $sequences = {};
	my $current_sequence_id;
	for (@{ $document->data }) {
		my ( $key, $value ) = ( $_->{key}, $_->{value} );
		$value =~ s/[\r\n]+$//s;

		$current_sequence_id = $sequence_markers->{$key}
			if $sequence_markers->{$key};

		if ($current_sequence_id) {
			push @{ $sequences->{$current_sequence_id} }, { key => $key, value => $value };
		}
	}
	return $sequences;
}

1;