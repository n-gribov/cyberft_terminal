package CyberFT::SWIFT::Document;
use strict;
use Data::Dumper;
use Storable ();

# При создании объекта нужно передать параметры:
#   sender - SWIFT-код отправителя
#   receiver - SWIFT-код получателя
#   type - тип SWIFT-сообщения (например 103 или 202)
#   msg - тело сообщения
sub new {
    my $class = shift;
    my %params = @_;

    my $sender = $params{sender};
    my $receiver = $params{receiver} ;
    my $type = $params{type};
    my $msg = $params{msg};
    my $priority = $params{priority};

    $type =~ s/^MT//i;

    # Разберем сообщение на отдельные поля и сохраним в data.
    my $data = [];
    my $msg_copy = $msg;
    while ($msg_copy =~ s/:(\d+[A-Z]?):(.*?)(?=:\d+[A-Z]?:|\z)//s) {
        push @$data, {key => $1, value => $2};
    }

    my $self = {
        sender => $sender,
        receiver => $receiver,
        type => $type,
        msg => $msg,
        data => $data,
        priority => $priority,
    };

    bless $self, $class;
}

sub sender { my $self = shift; return $self->{sender}; }
sub receiver { my $self = shift; return $self->{receiver}; }
sub type { my $self = shift; return $self->{type}; }
sub data { my $self = shift; return Storable::dclone($self->{data}); }
sub msg { my $self = shift; return $self->{msg}; }
sub priority { my $self = shift; return $self->{priority}; }

# Получаем значение параметра по ключу. Можно передать регулярное выражение, например '50[AС]'.
# Возвращает первое значение с подходящим колючом.
sub get_first {
    my ($self, $key_regexp) = @_;
    my $data = $self->{data};

    my $val;
    for my $item (@$data) {
        if ($item->{key} =~ /^($key_regexp)$/) {
            $val = $item->{value};
            last;
        }
    }

    return $val;
}

# Проверяем, присутствует ли поле с данным ключом в документе.
sub key_exists {
    my ($self, $key_regexp) = @_;
    return defined($self->get_first($key_regexp));
}

# Получаем значение параметров по ключу. Можно передать регулярное выражение, например '50[AС]'.
# Возвращает все значения с подходящим колючом как ссылку на массив.
sub get_all {
    my ($self, $key_regexp) = @_;
    my $data = $self->{data};

    my $vals = [];
    for my $item (@$data) {
        if ($item->{key} =~ /^($key_regexp)$/) {
            push @$vals, $item->{value};
        }
    }

    return $vals;
}

# Получаем список ключей в свифте как ссылку на массив.
sub get_keys {
    my $self = shift;
    my $data = $self->{data};

    my $keys = [];
    for my $item (@$data) {
        push @$keys, $item->{key};
    }

    return $keys;
}



1;
