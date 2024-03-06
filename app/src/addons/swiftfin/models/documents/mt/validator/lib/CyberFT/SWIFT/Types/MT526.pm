package CyberFT::SWIFT::Types::MT526;
use CyberFT::SWIFT::Types::Utils;
use strict;
use Storable ();
use Data::Dumper;

our $ValidationProfile = {

    fields => [
        # ������� ������ ������������ ���� ������������ �������������������.
        {
            key => '20',
            required => 1,
        },
        {
            key => '23',
            required => 1,
        },
        {
            key => '35B',
            required => 1,
        },
    ],

    rules => [
        # ��� ����� ���� ��������� �� ������������� ����������� ����� ������.
    ]
};

1;