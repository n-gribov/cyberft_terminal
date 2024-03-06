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

# Вспомогательные функции для работы с последовательностями следующего типа:
#     [{key => '10', value => 'X'}, {key => '20', value => 'Y'}]
# Все функции принимают регулярные выражения в качестве ключа или значения. 
# Для ключа проверяется полное совпадение. Т.е. нужно указывать '20[ABC]' вместо '^20[ABC]$'.  

# Проверка существования непустого элемента с определенным ключом
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

# Проверка существования элемента с определенным ключом и значением
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

# Подсчет количества элементов с определенным ключом
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

# Подсчет количества элементов с определенным ключом и значением
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

# Получение значения первого элемента с определенным ключом. Если элемент не найден - вернет undef.
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

# Получение значений всех элементов с определенным ключом. Возвращает ссылку на массив значений.
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