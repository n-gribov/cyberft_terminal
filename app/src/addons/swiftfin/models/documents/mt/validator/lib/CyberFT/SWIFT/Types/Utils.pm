package CyberFT::SWIFT::Types::Utils;
use strict;

use Exporter;
our @ISA = qw(Exporter);
our @EXPORT = qw( 
    seq_key_exists
    seq_key_value_exists
    seq_key_count
    seq_key_value_count
    seq_get_first
    seq_get_all
);

# ��������������� ������� ��� ������ � �������������������� ���������� ����:
#     [{key => '10', value => 'X'}, {key => '20', value => 'Y'}]
# ��� ������� ��������� ���������� ��������� � �������� ����� ��� ��������. 
# ��� ����� ����������� ������ ����������. �.�. ����� ��������� '20[ABC]' ������ '^20[ABC]$'.  

# �������� ������������� ��������� �������� � ������������ ������
sub seq_key_exists {
    my $data = shift;
    my $key_regexp = shift;
    for my $item (@$data) {
        if ($item->{key} =~ /^($key_regexp)$/ && $item->{value} !~ /^\s*$/) {
            return 1;
        }
    }
    return 0;
}

# �������� ������������� �������� � ������������ ������ � ���������
sub seq_key_value_exists {
    my $data = shift;
    my $key_regexp = shift;
    my $value_regexp = shift;
    for my $item (@$data) {
        if ($item->{key} =~ /^($key_regexp)$/ && $item->{value} =~ /$value_regexp/) {
            return 1;
        }
    }
    return 0;
}

# ������� ���������� ��������� � ������������ ������
sub seq_key_count {
    my $data = shift;
    my $key_regexp = shift;
    my $count = 0;
    for my $item (@$data) {
        if ($item->{key} =~ /^($key_regexp)$/) {
            $count++;
        }
    }
    return $count;
}

# ������� ���������� ��������� � ������������ ������ � ���������
sub seq_key_value_count {
    my $data = shift;
    my $key_regexp = shift;
    my $value_regexp = shift;
    my $count = 0;
    for my $item (@$data) {
        if ($item->{key} =~ /^($key_regexp)$/ && $item->{value} =~ /$value_regexp/) {
            $count++;
        }
    }
    return $count;
}

# ��������� �������� ������� �������� � ������������ ������. ���� ������� �� ������ - ������ undef.
sub seq_get_first {
    my $data = shift;
    my $key_regexp = shift;
    for my $item (@$data) {
        if ($item->{key} =~ /^($key_regexp)$/) {
            return $item->{value};
        }
    }
    return undef;
}

# ��������� �������� ���� ��������� � ������������ ������. ���������� ������ �� ������ ��������.
sub seq_get_all {
    my $data = shift;
    my $key_regexp = shift;
    my $res = [];
    for my $item (@$data) {
        if ($item->{key} =~ /^($key_regexp)$/) {
            push @$res, $item->{value};
        }
    }
    return $res;
}

1;