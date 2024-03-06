package CyberFT::SWIFT::Types::MT207;
use CyberFT::SWIFT::Types::Utils;
use strict;

our $ValidationProfile = {

    fields => [
        {
            key => '20',
            required => 1,
        },
        {
            key => '28D',
            required => 1,
        },
        {
            key => '30',
            required => 1,
        },
        {
            key => '52G',
            required => 1,
        },
        {
            key => '32B',
            required => 1,
        },
        {
            key => '58a',
            key_regexp => '58[AD]',
            required => 1,
        },

    ],

    rules => [
        # ���� � �����-���� �� ���������� ������������������ � ������������ ���� 56�, �� � ���
        # ����������� ������ �������������� ����� � ���� 57� (��� ������ D65).
        {
            func => sub {
                my $doc = shift;
                my $data = $doc->data;
                my ($seq_started, $found56a, $found57a);
                for my $item (@$data) {
                    if ($item->{key} eq '21') {
                        if ($seq_started) {
                            return 0 if ($found56a && !$found57a);
                        }
                        $seq_started = 1;
                        $found56a = 0;
                        $found57a = 0;
                    }
                    elsif ($item->{key} =~ '56[AD]') {
                        $found56a = 1;
                    }
                    elsif ($item->{key} =~ '57[ACD]') {
                        $found57a = 1;
                    }
                }
                if ($seq_started) {
                    return 0 if ($found56a && !$found57a);
                }
                return 1;
            },
            err => 'D65',
        },
    ],

};

1;