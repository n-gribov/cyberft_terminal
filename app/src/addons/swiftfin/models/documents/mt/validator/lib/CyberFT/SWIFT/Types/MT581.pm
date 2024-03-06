package CyberFT::SWIFT::Types::MT581;
use CyberFT::SWIFT::Types::Utils;
use strict;
use Data::Dumper;

our $ValidationProfile = {

    fields => [
        # ������� ������ ������������ ���� ������������ �������������������.
        {
            key => '20',
            required => 1,
        },
        {
            key => '21',
            required => 1,
        },
        {
            key => '23',
            required => 1,
        },
        {
            key => '35H',
            required => 1,
        },
        {
            key => '80C',
            required => 1,
        },
    ],

    rules => [
        # ��� ����� ���� ��������� ��� ����������� ����� ������.
    ]

};

1;