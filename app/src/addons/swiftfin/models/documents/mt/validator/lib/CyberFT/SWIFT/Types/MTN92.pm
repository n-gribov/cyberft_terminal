package CyberFT::SWIFT::Types::MTN92;
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
            required => 1,
        },
        {
            key => '11S',
            required => 1,
        },
    ],

    rules => [
        # С1
        # В сообщении должно присутствовать либо поле 79, либо копия по крайней мере обязательных
        # полей исходного сообщения (Код ошибки С25).
        {
            func => sub {
                my $doc = shift;

                my $exists_79 = $doc->key_exists('79');

                my @keys = qw(20  21  11S  79);
                my $data = $doc->data;
                for my $k (@keys) {
                    _remove_first($data, $k);
                }
                my $exist_extra_fields = (scalar @$data > 0);

                if (!$exists_79 && !$exist_extra_fields) {
                    return 0;
                }
                return 1;
            },
            err => 'C25',
        }
    ]

};

# Удаляем первое встречающееся поле из данных, которое подойдет по ключу
sub _remove_first {
    my ($data, $k) = @_;
    my $found_index = undef;
    my $index = 0;
    while ($index < scalar(@$data)) {
        if ($data->[$index]->{key} =~ /^($k)$/) {
            $found_index = $index;
            last;
        }
        $index++;
    }
    splice(@$data, $found_index, 1) if defined($found_index);
}

1;