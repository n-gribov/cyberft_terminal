package CyberFT::SWIFT::Types::MT105;
use CyberFT::SWIFT::Types::Utils;
use strict;
use Data::Dumper;

our $ValidationProfile = {

    fields => [
        # ������� ������ ������������ ���� ������������ �������������������.
        {
            key => '27',
            required => 1,
        },
        {
            key => '20',
            required => 1,
        },
        {
            key => '21',
            required => 1,
        },
        {
            key => '12',
            required => 1,
        },
        {
            key => '77F',
            required => 1,
        },
    ],

    rules => [
        # ��� ����� ���� ��������� �� ������������� ������, ������� ������ ����������� �����.
    ]

};

1;