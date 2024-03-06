package CyberFT::SWIFT::Types::MT940;
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
            key => '25',
            required => 1,
        },
        {
            key => '28C',
            required => 1,
        },
        {
            key => '60a',
            key_regexp => '60[FM]',
            required => 1,
        },
        # -----> repetitive sequence starts
        {
            key => '61',
            required => 0,
        },
        {
            key => '86',
            required => 0,
        },
        # <----- repetitive sequence ends
        {
            key => '62a',
            key_regexp => '62[FM]',
            required => 1,
        },
        {
            key => '64',
            required => 0,
        },
        # -----> repetitive sequence starts
        {
            key => '65',
            required => 0,
        },
        # <----- repetitive sequence ends
        {
            key => '86',
            required => 0,
        },
    ],

    rules => [

        # C1. ���� ���� 86 ������������ � �����-���� �� ���������� ������������ ������������������,
        # ��� ������ �������������� ���� 61. ����� ����, ���� � ��������� ���������� ���� 86, ���
        # ������ �������������� �� ��� �� �������� (� ��� �� ���������) �������, ��� �
        # ��������������� ��� ���� 61 (��� ������ �24).
        {
            func => sub {
                my $doc = shift;
                my $data = $doc->data;

                # �������� ������������������ ����� ������������� ������ 60a � 62a.
                my @seq = ();
                my $seq_started = 0;
                for my $f (@$data) {
                    if ($seq_started) {
                        last if ($f->{key} =~ /^62[FM]$/);
                        push @seq, $f;
                    }
                    elsif ($f->{key} =~ /^60[FM]$/) {
                        $seq_started = 1;
                    }
                }

                # ��������, ��� ����� ������ ����� 86 � ������������������ ���� ���� 61.
                for my $i (0 .. $#seq) {
                    if ($seq[$i]->{key} eq '86' && ($i == 0 || $seq[$i-1]->{key} ne '61')) {
                        return 0;
                    }
                }

                return 1;
            },
            err => '�24',
        },

        # C2. ������ ��� ����� ������������ ���� ������ � ����� 60a, 62a, 64 � 65 ������ ����
        # ����������� �� ���� ����������� ����� ���� � ��������� (��� ������ �27).
        {
            func => sub {
                my $doc = shift;

                my $values = $doc->get_all('(60[FM]|62[FM]|64|65)');
                return 1 if (scalar @$values == 0);

                my ($first_code) = $values->[0] =~ /^\S{7}(\S{2})/;
                for my $val (@$values) {
                    my ($code) = $val =~ /^\S{7}(\S{2})/;
                    if ($code ne $first_code) {
                        return 0;
                    }
                }

                return 1;
            },
            err => '�27',
        },
    ]
};

1;