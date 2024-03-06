package CyberFT::SWIFT::Validator;
use strict;
use Data::Dumper;


# Здесь будут храниться зарегистрированные типы сообщений и кэшироваться загруженные профили.
our $PROFILES = {
    types => {},
    cache => {},
};

_register_validation_profiles(qw{
    101 102 102_STP 103 103_STP 103_REMIT 104 105 107 110 111 112
    200 201 202 202_COV 203 204 205 205_COV 207 210 256
    300 303 304 305 306 307 320 321 330 340 341 350 360 361 362 364 365 370 380 381
    400 410 412 416 420 422 430 450 455 456
    500 501 502 503 504 505 506 507 508 509 510 513 514 515 516 517 518 519
    524 526 527 530 535 536 537 538 540 541 542 543 544 545 546 547 548
    549 558 559 564 565 566 567 568 569 574_IRSLST 574_W8BENO 575 576 577 578 579 581 586
    600 601 604 605 606 607 608 609 620 643 644 645 646 649
    700 701 705 707 710 711 720 721 730 732 734 740 742 747 750 752 754 756 760 767 768 769
    800 801 802 824
    900 910 920 935 940 941 942 950 970 971 972 973 985 986 999
    N90 N91 N92 N95 N96 N98 N99
});


sub new {
    my $class = shift;
    my $self = {};
    bless $self, $class;
}


# Валидация данных свифта. На вход передавать CyberFT::SWIFT::Document.
# Возвращает ссылку на хэш с полями result и error.
# Если result = 0 - валидация прошла успешно. Если result = 1 - неуспешно.
# Если result = 2 - валидация невозможна (например, неизвестный тип SWIFT-сообщения).
sub validate {
    my $self = shift;
    my $doc = shift;

    # Валидируем допустимые символы.
    my $v = $self->validate_allowed_chars($doc);
    if ($v->{result} != 0) {
        return $v;
    }

    # Подберем подходящий профиль валидации.
    my $profile = _get_profile($doc->type);
    unless (defined $profile) {
        return {
            result => 2,
            error => "Unknown SWIFT type: " . $doc->type,
        }
    }

    # Валидируем обязательные поля.
    if (defined $profile->{fields}) {
        my $v = $self->validate_required_fields($doc);
        if ($v->{result} != 0) {
            return $v;
        }
    }

    # Валидируем правила зависмостей между полями.
    if (defined $profile->{rules}) {
        my $v = $self->validate_rules($doc);
        if ($v->{result} != 0) {
            return $v;
        }
    }

    return {result => 0};
}

# Валидация допустимых символов.
sub validate_allowed_chars {
    my $self = shift;
    my $doc = shift;

    my $pos = 0;
    my @chars = split(//, $doc->msg);
    for my $c (@chars) {
        $pos++;
        if ($c !~ /[a-zA-Z0-9?:().,'+\/\-\r\n \@]/) {
            return {
                result => 1,
                error => "Character at position $pos is not allowed: $c (" . sprintf('0x%02x', ord($c)) . ")",
            }
        }
    }

    return {result => 0};
}

# Валидация обязательных полей. Проверка, что все обязательные поля присутствуют и они не пустые.
sub validate_required_fields {
    my $self = shift;
    my $doc = shift;

    my $profile = _get_profile($doc->type);
    unless (defined $profile) {
        return {
            result => 2,
            error => "Unknown SWIFT type: " . $doc->type,
        }
    }

    my $fields = $profile->{fields};

    if (ref($fields) ne 'ARRAY') {
        return {result => 0};
    }

    for my $field (@$fields){

        if ($doc->priority eq 'RUR6') {
            if ($field->{key} eq '72') {

                my $k = $field->{key_regexp} || $field->{key};
                my $values = $doc->get_all($k);

                if (scalar @$values == 0) {
                    return {
                        result => 1,
                        error => "Missing required field: " . $field->{key},
                    }
                }
            }
        }

        next unless ($field->{required});

        my $k = $field->{key_regexp} || $field->{key};
        my $values = $doc->get_all($k);

        if (scalar @$values == 0) {
            return {
                result => 1,
                error => "Missing required field: " . $field->{key},
            }
        }

        # Если у поля выставлен флаг allow_empty, то оно может быть пустым, даже если оно обязательное.
        unless ($field->{allow_empty}) {
            for my $v (@$values) {
                if ($v =~ /^\s*$/) {
                    return {
                        result => 1,
                        error => "Empty required field: " . $field->{key},
                    }
                }
            }
        }
    }

    return {result => 0};
}

# Валидация правил зависимостей между полями.
sub validate_rules {
    my $self = shift;
    my $doc = shift;

    my $profile = _get_profile($doc->type);
    unless (defined $profile) {
        return {
            result => 2,
            error => "Unknown SWIFT type: " . $doc->type,
        }
    }

    my $rules = $profile->{rules};

    for my $rule (@$rules) {
        my $func = $rule->{func};
        my $if = $rule->{if};
        my $must = $rule->{must};
        my $err = $rule->{err};

        if (defined $func) {
            # Если указана функция, то вызываем ее и передаем doc.
            # Если все ок, то она должна вернуть истину. Во втором возвращаемом значении опционально
            # может быть сообщение об ошибке. Если его нет, это сообщение берется из $rule->{err}.
            my ($ok, $err_msg) = $func->($doc);
            unless ($ok) {
                unless (defined $err_msg) {
                    $err_msg = $err;
                }
                return {
                    result => 1,
                    error => $err_msg,
                }
            }
        }
        else {
            # В общем случае может быть список из нескольких условий.
            # Если указано только одно условие, то обернем его в массив.
            if (defined $if && ref($if->[0]) ne 'ARRAY') {
                $if = [$if];
            }
            if (defined $must && ref($must->[0]) ne 'ARRAY') {
                $must = [$must];
            }

            # Проверим, удовлетворяют ли данные условиям в параметре if.
            # Если нет, то правило не применяется, переходим к следующему.
            # Если if не указан, то просто проверяем блок must.
            if (defined $if){
                my $rule_applies = _check_conditions($doc, $if);
                unless ($rule_applies) {
                    next;
                }
            }

            # Проверим, удовлетворяют ли данные условиям из параметра must.
            if (defined $must){
                my $ok = _check_conditions($doc, $must);
                unless ($ok) {
                    return {
                        result => 1,
                        error => $err,
                    }
                }
            }
        }
    }

    return {result => 0};
}

# Проверяем, что данные удовлетворяют всем условиям из списка
sub _check_conditions {
    my $doc = shift;
    my $conditions = shift;

    for my $c (@$conditions) {
        my ($type, @params) = @$c;
        my $result = 0;

        if ($type eq 'exists') {
            $result = _check_exists($doc, @params);
        }
        elsif ($type eq 'not_exists') {
            $result = !_check_exists($doc, @params);
        }
        elsif ($type eq 'match') {
            $result = _check_match($doc, @params);
        }
        elsif ($type eq 'match_any') {
            $result = _check_match_any($doc, @params);
        }
        elsif ($type eq 'not_match') {
            $result = _check_not_match($doc, @params);
        }
        return 0 unless ($result);
    }

    return 1;
}

# Проверяем, что поле с данным ключом присутствует в данных
sub _check_exists {
    my ($doc, $key) = @_;
    my $p = $doc->get_first($key);
    return (defined $p);
}

# Проверяем, что все поля, которые подойдут по ключу, соответствуют регулярному выражению.
sub _check_match {
    my ($doc, $key, $regexp) = @_;
    my $vals = $doc->get_all($key);
    for my $v (@$vals) {
        return 0 if ($v !~ /$regexp/);
    }
    return 1;
}

# Проверяем, хотя бы одно поле, которое подойдет по ключу, соответствует регулярному выражению.
sub _check_match_any {
    my ($doc, $key, $regexp) = @_;
    my $vals = $doc->get_all($key);
    for my $v (@$vals) {
        return 1 if ($v =~ /$regexp/);
    }
    return 0;
}

# Проверяем, что ни одно поле, которое подойдет по ключу, не соответствует регулярному выражению.
sub _check_not_match {
    my ($doc, $key, $regexp) = @_;
    my $vals = $doc->get_all($key);
    for my $v (@$vals) {
        return 0 if ($v =~ /$regexp/);
    }
    return 1;
}


# Регистрируем типы сообщений, которые поддерживаются валидатором.
sub _register_validation_profiles {
    my @types = @_;
    for my $type (@types) {
        $PROFILES->{types}->{$type} = 1;
    }
}

# Ищем подходящий профиль для данного типа сообщения.
sub _get_profile {
    my $type = shift;

    my $match_type;
    if ($PROFILES->{types}->{$type}) {
        $match_type = $type;
    }
    else {
        # Если напрямую нужный тип не нашли проверим всякие обобщенные типы.
        # Например для сообщений 296 и 496 подойдет валидатор N96.
        for my $k (keys %{$PROFILES->{types}}) {
            my $re = $k;
            $re =~ s/N/\\d/i;
            if ($type =~ /^$re$/) {
                $match_type = $k;
                last;
            }
        }
    }

    # если подходящего профиля не зарегистрировано, вернем undef.
    unless (defined $match_type) {
        return undef;
    }

    # проверим в кэше: если модуль еще не загружен - загрузим его.
    unless (defined $PROFILES->{cache}->{$match_type}) {
        $PROFILES->{cache}->{$match_type} = _load_profile($match_type);
    }

    return $PROFILES->{cache}->{$match_type};
}

# Загружаем модуль с профилем валидации.
sub _load_profile {
    no strict 'refs';
    my $type = shift;
    my $profile;
    require "CyberFT/SWIFT/Types/MT$type.pm";
    $profile = ${"CyberFT::SWIFT::Types::MT$type\::ValidationProfile"};
    return $profile;
}

# Загружаем все зарегистрированные профили (может быть полезно для тестов).
sub _load_registered_profiles {
    for my $type (keys %{$PROFILES->{types}}) {
        $PROFILES->{cache}->{$type} = _load_profile($type);
    }
}

1;
