package CyberFT::SWIFT::Types::MT720;
use CyberFT::SWIFT::Types::Utils;
use strict;

our $ValidationProfile = {

    fields => [
        {
            key => '27',
            required => 1,
        },
        {
            key => '40B',
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
            key => '40E',
            required => 1,
        },
        {
            key => '31D',
            required => 1,
        },
        {
            key => '50',
            required => 1,
        },
        {
            key => '59',
            required => 1,
        },
        {
            key => '32B',
            required => 1,
        },
        {
            key => '41a',
            key_regexp => '41[AD]',
            required => 1,
        },
        {
            key => '49',
            required => 1,
        },
    ],

    rules => [
        # C1 � ��������� ����� �������������� ���� ���� 39A, ���� ���� 39B, �� �� ��� ��� ����
        # ������ (��� ������ D05).
        {
            func => sub {
                my $doc = shift;
                if ($doc->key_exists('39A') && $doc->key_exists('39B')) {
                    return 0;
                }
                return 1;
            },
            err => 'D05',
        },

        # C2 ���� 42C � 42a, ���� ��� ������������, ������ �������������� ������ (��� ������
        # C90).
        {
            func => sub {
                my $doc = shift;
                if ($doc->key_exists('42C')) {
                    unless ($doc->key_exists('42[AD]')) {
                        return 0;
                    }
                }
                if ($doc->key_exists('42[AD]')) {
                    unless ($doc->key_exists('42C')) {
                        return 0;
                    }
                }
                return 1;
            },
            err => 'C90',
        },

        # C3 ����� �������������� ���� ���� 42C � 42a ������, ���� ������ ���� 42M, ���� ������
        # ���� 42P. ������� ������ ���������� ���� ����� �� ����������� (��� ������ C90)
        {
            func => sub {
                my $doc = shift;
                if ($doc->key_exists('42C') || $doc->key_exists('42[AD]')) {
                    if ($doc->key_exists('42M') || $doc->key_exists('42P')) {
                        return 0;
                    } else {
                        return 1;
                    }
                }
                if ($doc->key_exists('42M')) {
                    if ($doc->key_exists('42C') || $doc->key_exists('42[AD]') || $doc->key_exists('42P')) {
                        return 0;
                    } else {
                        return 1;
                    }
                }
                if ($doc->key_exists('42P')) {
                    if ($doc->key_exists('42C') || $doc->key_exists('42[AD]') || $doc->key_exists('42M')) {
                        return 0;
                    } else {
                        return 1;
                    }
                }
                return 1;
            },
            err => 'C90',
        },

        # C4 ����� �������������� ���� ���� 44C, ���� ���� 44D, �� �� ��� ��� ���� ������ (��� ������ D06).
        {
            func => sub {
                my $doc = shift;
                if ($doc->key_exists('44C') && $doc->key_exists('44D')) {
                    return 0;
                }
                return 1;
            },
            err => 'D06',
        },

        # �5 ����� �������������� ���� ���� 52� �����-�������, ���� ���� 50� ��������, �� ����������
        # ������, �� �� ��� ��� ���� ������ (��� ������ �06).
        {
            func => sub {
                my $doc = shift;
                if ($doc->key_exists('52[AD]') && $doc->key_exists('50B')) {
                    return 0;
                }
                return 1;
            },
            err => 'C06',
        },
    ],
};

1;