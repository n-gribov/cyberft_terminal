package CyberFT::SWIFT::Types::MT362;
use CyberFT::SWIFT::Types::Utils;
use strict;

our $ValidationProfile = {

    fields => [
        # Обязательная последовательность A «Общая информация»
        {
            key => '15A',
            required => 1,
            allow_empty => 1,
        },
        {
            key => '20',
            required => 1,
        },
        {
            key => '21',
            required => 0,
        },
        {
            key => '22A',
            required => 1,
        },
        {
            key => '94A',
            required => 0,
        },
        {
            key => '22C',
            required => 1,
        },
        {
            key => '23A',
            required => 1,
        },
        {
            key => '21N',
            required => 1,
        },
        {
            key => '21B',
            required => 0,
        },
        {
            key => '30V',
            required => 1,
        },
        {
            key => '30P',
            required => 1,
        },
        {
            key => '82a',
            key_regexp => '82[AD]',
            required => 1,
        },
        {
            key => '87a',
            key_regexp => '87[AD]',
            required => 1,
        },
        {
            key => '83a',
            key_regexp => '83[ADJ]',
            required => 0,
        },
        {
            key => '29A',
            required => 0,
        },
        {
            key => '72',
            required => 0,
        },
        # Окончание последовательности А Общая информация
        # Необязательная последовательность В Фиксированный процент, выплачиваемый стороной В
        {
            key => '15B',
            required => 0, # Это поле обязательно, если данная последовательность присутствует.
            allow_empty => 1,
        },
        {
            key => '33F',
            required => 0, # Это поле обязательно, если данная последовательность присутствует.
        },
        {
            key => '30X',
            required => 0, # Это поле обязательно, если данная последовательность присутствует.
        },
        {
            key => '30Q',
            required => 0,
        },
        {
            key => '37G',
            required => 0, # Это поле обязательно, если данная последовательность присутствует.
        },
        {
            key => '37J',
            required => 0,
        },
        {
            key => '37L',
            required => 0,
        },
        {
            key => '37R',
            required => 0, # Это поле обязательно, если данная последовательность присутствует.
        },
        {
            key => '37M',
            required => 0, # Это поле обязательно, если данная последовательность присутствует.
        },
        {
            key => '30F',
            required => 0, # Это поле обязательно, если данная последовательность присутствует.
        },
        {
            key => '32H',
            required => 0,
        },
        {
            key => '33E',
            required => 0,
        },
        {
            key => '37N',
            required => 0,
        },
        # Окончание последовательности В Процентная ставка/основная сумма, выплачиваемая стороной В
        # Необязательная последовательность С (Чистые) суммы, выплачиваемые стороной B
        {
            key => '15C',
            required => 0, # Это поле обязательно, если данная последовательность присутствует.
            allow_empty => 1,
        },
        {
            key => '18A',
            required => 0, # Это поле обязательно, если данная последовательность присутствует.
        },
        {
            key => '30F',
            required => 0, # Это поле обязательно, если данная последовательность присутствует.
        },
        {
            key => '32M',
            required => 0, # Это поле обязательно, если данная последовательность присутствует.
        },
        {
            key => '53a',
            key_regexp => '53[AD]',
            required => 0,
        },
        {
            key => '56a',
            key_regexp => '56[AD]',
            required => 0,
        },
        {
            key => '86a',
            key_regexp => '86[AD]',
            required => 0,
        },
        {
            key => '57a',
            key_regexp => '57[AD]',
            required => 0, # Это поле обязательно, если данная последовательность присутствует.
        },
        # Окончание последовательности С (Чистые) суммы, выплачиваемые стороной B
        # Необязательная последовательность D Процентная ставка/основная сумма, выплачиваемая стороной А
        {
            key => '15D',
            required => 0, # Это поле обязательно, если данная последовательность присутствует.
            allow_empty => 1,
        },
        {
            key => '33F',
            required => 0, # Это поле обязательно, если данная последовательность присутствует.
        },
        {
            key => '30X',
            required => 0, # Это поле обязательно, если данная последовательность присутствует.
        },
        {
            key => '30Q',
            required => 0,
        },
        {
            key => '37G',
            required => 0, # Это поле обязательно, если данная последовательность присутствует.
        },
        {
            key => '37J',
            required => 0,
        },
        {
            key => '37L',
            required => 0,
        },
        {
            key => '37R',
            required => 0, # Это поле обязательно, если данная последовательность присутствует.
        },
        {
            key => '37M',
            required => 0, # Это поле обязательно, если данная последовательность присутствует.
        },
        {
            key => '30F',
            required => 0, # Это поле обязательно, если данная последовательность присутствует.
        },
        {
            key => '32H',
            required => 0,
        },
        {
            key => '33E',
            required => 0,
        },
        {
            key => '37N',
            required => 0,
        },
        # Окончание последовательности D Процентная ставка/основная сумма, выплачиваемая стороной А
        # Необязательная последовательность Е (Чистые) суммы, выплачиваемые стороной А
        {
            key => '15E',
            required => 0, # Это поле обязательно, если данная последовательность присутствует.
            allow_empty => 1,
        },
        {
            key => '18A',
            required => 0, # Это поле обязательно, если данная последовательность присутствует.
        },
        {
            key => '30F',
            required => 0, # Это поле обязательно, если данная последовательность присутствует.
        },
        {
            key => '32M',
            required => 0, # Это поле обязательно, если данная последовательность присутствует.
        },
        {
            key => '53a',
            key_regexp => '53[AD]',
            required => 0,
        },
        {
            key => '56a',
            key_regexp => '56[AD]',
            required => 0,
        },
        {
            key => '86a',
            key_regexp => '86[AD]',
            required => 0,
        },
        {
            key => '57a',
            key_regexp => '57[AD]',
            required => 0, # Это поле обязательно, если данная последовательность присутствует.
        },
        # Окончание последовательности Е (Чистые) суммы, выплачиваемые стороной А
    ],

    rules => [
        # С1. В сообщении должна присутствовать хотя бы одна из последовательностей В или D (Код ошибки Е47):
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                my $b = defined($seqs->{B});
                my $d = defined($seqs->{D});
                return 0 unless ($b || $d);
                return 1;
            },
            err => 'E47',
        },
        # С2. В сообщении должна присутствовать хотя бы одна из последовательностей С или Е (Код ошибки Е48):
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                my $c = defined($seqs->{C});
                my $e = defined($seqs->{E});
                return 0 unless ($c || $e);
                return 1;
            },
            err => 'E48',
        },
        # С3. Если «Способ расчетов» во втором подполе поля 23А определен как «NET», то в сообщении
        # должна присутствовать либо последовательность С, либо последовательность Е, но не обе эти
        # последовательности вместе (Код ошибки Е49).
        # Примечание: Если «Способ расчетов» определен как «NET», то это правило имеет приоритет
        # перед правилом С2.
        {
            func => sub {
                my $doc = shift;
                my $v32a = $doc->get_first('23A');
                if ($v32a =~ /\/NET/) {
                    my $seqs = _find_sequences($doc->data);
                    my $c = defined($seqs->{C});
                    my $e = defined($seqs->{E});
                    return 0 if ($c && $e);
                    return 0 unless ($c || $e);
                }
                return 1;
            },
            err => 'E49',
        },
        # С4. Если «Способ расчетов» во втором подполе поля 23А определен как «NET», то блок платежных
        # инструкций (то есть поля 30F --- 57а) в последовательности С или Е может присутствовать
        # только один раз (Код ошибки Е50).
        # Соответственно, поле 18А в последовательности С или Е должно иметь значение «1» - см. № 30
        # или № 51, описание поля 18А, «Проверяемые сетью правила» (Код ошибки D96).
        {
            func => sub {
                my $doc = shift;
                my $v32a = $doc->get_first('23A');
                if ($v32a =~ /\/NET/) {
                    my $seqs = _find_sequences($doc->data);
                    for my $s ('C', 'E') {
                        if (defined $seqs->{$s}) {
                            return 0 if (seq_key_count($seqs->{$s}, '30F') != 1);
                            return 0 if (seq_key_count($seqs->{$s}, '32M') != 1);
                            return 0 if (seq_key_count($seqs->{$s}, '53[AD]') != 1);
                            return 0 if (seq_key_count($seqs->{$s}, '56[AD]') != 1);
                            return 0 if (seq_key_count($seqs->{$s}, '86[AD]') != 1);
                            return 0 if (seq_key_count($seqs->{$s}, '57[AD]') != 1);
                        }
                    }
                }
                return 1;
            },
            err => 'E50',
        },
        {
            func => sub {
                my $doc = shift;
                my $v32a = $doc->get_first('23A');
                if ($v32a =~ /\/NET/) {
                    my $seqs = _find_sequences($doc->data);
                    for my $s ('C', 'E') {
                        if (defined $seqs->{$s}) {
                            my $v18a = seq_get_first($seqs->{$s}, '18A');
                            return 0 if ($v18a != 1);
                        }
                    }
                }
                return 1;
            },
            err => 'D96',
        },
        # С5. Если «Способ расчетов» во втором подполе поля 23А определен как «GROSS», то блок
        # платежных инструкций (то есть поля 30F --- 57а) в последовательности С или Е не может
        # присутствовать более трех раз (Код ошибки Е51).
        # Соответственно, поле 18А в последовательности С или Е должно иметь значение меньшее, чем 4
        # - см. № 30 или № 51, описание поля 18А, «Проверяемые сетью правила» (Код ошибки D96).
        {
            func => sub {
                my $doc = shift;
                my $v32a = $doc->get_first('23A');
                if ($v32a =~ /\/GROSS/) {
                    my $seqs = _find_sequences($doc->data);
                    for my $s ('C', 'E') {
                        if (defined $seqs->{$s}) {
                            return 0 if (seq_key_count($seqs->{$s}, '30F') > 3);
                            return 0 if (seq_key_count($seqs->{$s}, '32M') > 3);
                            return 0 if (seq_key_count($seqs->{$s}, '53[AD]') > 3);
                            return 0 if (seq_key_count($seqs->{$s}, '56[AD]') > 3);
                            return 0 if (seq_key_count($seqs->{$s}, '86[AD]') > 3);
                            return 0 if (seq_key_count($seqs->{$s}, '57[AD]') > 3);
                        }
                    }
                }
                return 1;
            },
            err => 'E51',
        },
        {
            func => sub {
                my $doc = shift;
                my $v32a = $doc->get_first('23A');
                if ($v32a =~ /\/GROSS/) {
                    my $seqs = _find_sequences($doc->data);
                    for my $s ('C', 'E') {
                        if (defined $seqs->{$s}) {
                            my $v18a = seq_get_first($seqs->{$s}, '18A');
                            return 0 if ($v18a > 3);
                        }
                    }
                }
                return 1;
            },
            err => 'D96',
        },
        # С6. Код валюты в полях 33F и 32Н последовательности В должен быть одинаковым. Код валюты в
        # полях 33F и 32Н последовательности D должен быть одинаковым (Код ошибки Е38).
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                for my $s ('B', 'D') {
                    if (defined $seqs->{$s}) {
                        if (seq_key_exists($seqs->{$s}, '33F') && seq_key_exists($seqs->{$s}, '32H')) {
                            my ($cur33f) = seq_get_first($seqs->{$s}, '33F') =~ /.*([A-Z]{3})/;
                            my ($cur32h) = seq_get_first($seqs->{$s}, '32H') =~ /.*([A-Z]{3})/;
                            return 0 if ($cur33f ne $cur32h);
                        }
                    }
                }
                return 1;
            },
            err => 'E38',
        },
        # С7. Поле «Bторой посредник» используется только в тех случаях, когда при переводе средств
        # требуются два посредника. Таким образом, в отношении всех повторений полей 56а и 86а
        # действуют следующие правила (Код ошибки Е35).
        # Если в какой-либо последовательности поле 56а ... то в той же последовательности поле 86а ...
        # Присутствует      Необязательное
        # Отсутствует       Не используется
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                for my $s ('C', 'E') {
                    if (defined $seqs->{$s}) {
                        my $seq = $seqs->{$s};
                        my $exists56 = 0;
                        my $exists86 = 0;
                        for my $item (@$seq) {
                            if ($item->{key} =~ /^56[AD]$/) {
                                $exists56 = 1;
                            }
                            if ($item->{key} =~ /^86[AD]$/) {
                                $exists86 = 1;
                            }
                            if ($item->{key} =~ /^57[AD]$/) {
                                return 0 if (!$exists56 && $exists86);
                                $exists56 = 0;
                                $exists86 = 0;
                            }
                        }
                        return 0 if (!$exists56 && $exists86);
                    }
                }
                return 1;
            },
            err => 'E35',
        },
        # С8. В сообщениях с изменениями или отменой должен присутствовать «Связанный референс». То есть,
        # в последовательности А присутствие поля 21 следующим образом зависит от содержания поля 22А
        # (Код ошибки D02):
        # Последовательность А если поле 22А ... Последовательность А то поле 21 ...
        # AMND      Обязательное
        # CANC      Обязательное
        # DUPL      Необязательное
        # NEWT      Необязательное
        {
            if => ['match', '22A', '^AMND\s*$'],
            must => ['exists', '21'],
            err => 'D02',
        },
        {
            if => ['match', '22A', '^CANC\s*$'],
            must => ['exists', '21'],
            err => 'D02',
        },

        # С10. Во всех необязательных последовательностях, если существует последовательность, поля
        # со статусом М должны присутствовать, другие не используются (Код ошибки С32).
        {
            func => sub {
                my $doc = shift;
                my $seqs = _find_sequences($doc->data);
                my $seq;

                $seq = $seqs->{'B'};
                if ($seq) {
                    for my $k (qw( 33F 30X 37G 37R 37M 30F )) {
                        return 0 unless seq_key_exists($seq, $k);
                    }
                }

                $seq = $seqs->{'C'};
                if ($seq) {
                    for my $k (qw( 18A 30F 32M 57[AD] )) {
                        return 0 unless seq_key_exists($seq, $k);
                    }
                }

                $seq = $seqs->{'D'};
                if ($seq) {
                    for my $k (qw( 33F 30X 37G 37R 37M 30F )) {
                        return 0 unless seq_key_exists($seq, $k);
                    }
                }

                $seq = $seqs->{'E'};
                if ($seq) {
                    for my $k (qw( 18A 30F 32M 57[AD] )) {
                        return 0 unless seq_key_exists($seq, $k);
                    }
                }

                return 1;
            },
            err => 'C32',
        },
    ]
};

# Вытаскиваем все последовательности как хэш массивов.
sub _find_sequences {
    my $data = shift;

    my $marks = {
        '15A' => 'A',
        '15B' => 'B',
        '15C' => 'C',
        '15D' => 'D',
        '15E' => 'E',
    };

    my $cur_seq = undef;
    my $seqs = {};

    for my $item (@$data) {
        my $k = $item->{key};
        if (defined $marks->{$k}) {
            $cur_seq = $marks->{$k};
        }
        if ($cur_seq) {
            push @{$seqs->{$cur_seq}}, $item;
        }
    }

    return $seqs;
}

1;