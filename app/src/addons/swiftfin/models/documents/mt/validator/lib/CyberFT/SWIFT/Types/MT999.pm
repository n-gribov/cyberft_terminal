package CyberFT::SWIFT::Types::MT999;
use CyberFT::SWIFT::Types::Utils;
use strict;

our $ValidationProfile = {

    fields => [
        {
            key => '20',
            required => 1,
        },
        {
            key => '21',
            required => 0,
        },
        {
            key => '79',
            required => 1,
        },

    ],

    rules => [
        # ���� 20: � 21: �� ������ ���������� �� ����� "/", ������������� ������ ��� ���������
        # ������� ���� "//" (��� ������ �26).
        {
            if   => ['exists', '20'],
            must => ['not_match', '20', '^/|/$|//'],
            err  => 'T26',
        },
        {
            if   => ['exists', '21'],
            must => ['not_match', '21', '^/|/$|//'],
            err  => 'T26',
        },
    ],

};

1;