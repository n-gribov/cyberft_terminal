package CyberFT::SWIFT::Types::MT103_STP;
use CyberFT::SWIFT::Types::Utils;
use strict;

our $ValidationProfile = {

    fields => [
        {
            key => '20',
            required => 1,
        },
        {
            key => '23B',
            required => 1,
        },
        {
            key => '32A',
            required => 1,
        },
        {
            key => '50a',
            key_regexp => '50[AKF]',
            required => 1,
        },
        {
            key => '59a',
            key_regexp => '59A?',
            required => 1,
        },
        {
            key => '71A',
            required => 1,
        },
    ],

    rules => [

        # С1. Если в сообщении присутствует поле 33В и если указанный в нем код валюты отличен
        # от кода валюты в поле 32А, в сообщении должно присутствовать поле 36; в остальных
        # случаях поле 36 не используется (Код ошибки D75).
        {
            func => sub {
                my $doc = shift;
                if ($doc->key_exists('33B')) {
                    my ($currency_33B) = $doc->get_first('33B') =~ /([A-Z]{3})/;
                    my ($currency_32A) = $doc->get_first('32A') =~ /([A-Z]{3})/;
                    if ($currency_33B ne $currency_32A) {
                        return $doc->key_exists('36');
                    } else {
                        return !$doc->key_exists('36');
                    }
                }
                return 1;
            },
            err => 'D75',
        },

        # С2. Если коды стран в кодах BIC Отправителя и Получателя входят в следующий перечень
        # кодов стран: AD, AT, BE, BV, BG, CH, CY, CZ, DE, DK, EE, ES, FI, FR, GB, GF, GI, GP,
        # GR, HU, IE, IS, IT, LI, LT, LU, LV, MC, MQ, MT, NL, NO, PL, PM, PT, RE, RO, SE, SI,
        # SJ, SK, SM, TF и VA, - то поле 33В является обязательным, в остальных случаях поле 33В
        # необязательное (Код ошибки D49).
        {
           func => sub {
               my $doc = shift;
               my ($sender_country) = $doc->sender =~ /^\S{4}(\S{2})/;
               my ($receiver_country) = $doc->receiver =~ /^\S{4}(\S{2})/;
               my $re = join '|', qw{
                   AD AT BE BV BG CH CY CZ DE DK EE ES FI FR GB GF GI GP GR HU IE IS
                   IT LI LT LU LV MC MQ MT NL NO PL PM PT RE RO SE SI SJ SK SM TF VA
               };
               if ($sender_country =~ $re && $receiver_country =~ $re) {
                   return $doc->key_exists('33B');
               }
               return 1;
           },
           err => 'D49',
        },

        # С3. Если поле 23В содержит код SPRI, то в поле 23Е могут использоваться только коды
        # SDVA, INTC (Код ошибки E01). Если поле 23В содержит код SSTD или SPAY, то поле 23Е
        # не должно использоваться (Код ошибки E02).
        {
            if   => ['match', '23B', '^SPRI\s*$'],
            must => ['match', '23E', '^(SDVA|INTC)'],
            err  => 'E01',
        },
        {
            if   => ['match', '23B', '^(SSTD|SPAY)\s*$'],
            must => ['not_exists', '23E'],
            err  => 'E02',
        },

        # C4. Если в сообщении присутствует поле 55A, то должны также присутствовать как поле 53A,
        # так и поле 54A (Код ошибки Е06).
        {
            if   => ['exists', '55A'],
            must => [
                ['exists', '53A'],
                ['exists', '54A'],
            ],
            err  => 'E06',
        },

        # С5. Если в сообщении присутствует поле 56A, то должно присутствовать также и поле 57A
        # (Код ошибки С81).
        {
            if   => ['exists', '56A'],
            must => ['exists', '57A'],
            err  => 'C81',
        },

        # C6. Если поле 23В содержит код SPRI, то поле 56A не должно использоваться
        # (Код ошибки Е16).
        {
            if   => ['match', '23B', '^SPRI\s*$'],
            must => ['not_exists', '56A'],
            err  => 'E16',
        },

        # С7. Если поле 71A содержит код «OUR», то поле 71F не должно использоваться, а поле 71G
        # необязательное (Код ошибки E13).
        {
            if   => ['match', '71A', '^OUR\s*$'],
            must => ['not_exists', '71F'],
            err  => 'E13',
        },
        # Если поле 71А содержит код «SHA», то поле (поля) 71F необязательное, а поле 71G
        # не должно использоваться (Код ошибки D50).
        {
            if   => ['match', '71A', '^SHA\s*$'],
            must => ['not_exists', '71G'],
            err  => 'D50',
        },
        # Если поле 71А содержит код «BEN», то обязательно должно присутствовать хотя бы одно
        # поле 71F, а поле 71G не используется (Код ошибки E15).
        {
            if   => ['match', '71A', '^BEN\s*$'],
            must => ['not_exists', '71G'],
            err  => 'E15',
        },
        {
            if   => ['match', '71A', '^BEN\s*$'],
            must => ['exists', '71F'],
            err  => 'E15',
        },

        # С8. Если в сообщении присутствует либо поле 71F (хотя бы один раз), либо поле 71G, то
        # поле 33В является обязательным, в остальных случаях поле 33В необязательное
        # (Код ошибки D51).
        {
            if   => ['exists', '71F'],
            must => ['exists', '33B'],
            err  => 'D51',
        },
        {
            if   => ['exists', '71G'],
            must => ['exists', '33B'],
            err  => 'D51',
        },

        # С9. Код валюты в полях 71G и 32А должен быть одинаковым (Код ошибки С02).
        {
            func => sub {
                my $doc = shift;
                if ($doc->key_exists('71G') && $doc->key_exists('32A')) {
                    my ($currency_71G) = $doc->get_first('71G') =~ /([A-Z]{3})/;
                    my ($currency_32A) = $doc->get_first('32A') =~ /([A-Z]{3})/;
                    if ($currency_71G ne $currency_32A) {
                        return 0;
                    }
                }
                return 1;
            },
            err => 'C02',
        },

        # C10
        # Если коды стран в кодах BIC Отправителя и Получателя входят в следующий перечень
        # кодов стран: AD, AT, BE, BV, BG, CH, CY, CZ, DE, DK, EE, ES, FI, FR, GB, GF, GI, GP,
        # GR, HU, IE, IS, IL, IT, LI, LT, LU, LV, MC, MQ, MT, NL, NO, PL, PM, PT, RE, RO, SE,
        # SI, SJ, SK, SM, TF и VA, - то должны выполняться следующие правила:
        # - Если отсутствует поле 57А, то в подполе «Счет» поля 59а обязательно
        # должен быть указан Международный номер банковского счета IBAN
        # (стандарт ISO-13616) (Код ошибки D19).
        # - Если поле 57А присутствует, и если указанный в нем код BIC содержит код страны,
        # входящий в приведенный выше перечень, то в подполе «Счет» поля 59а обязательно
        # должен быть указан Международный номер банковского счета IBAN
        # (стандарт ISO-13616) (Код ошибки D19).
        # Во всех остальных случаях использование номера счета IBAN (стандарт ISO-13616) не
        # обязательно, и формат подполя «Счет» в поле 59а не проверяется системой SWIFT.
        {
            func => sub {
                my $doc = shift;
                my ($sender_country) = $doc->sender =~ /^\S{4}(\S{2})/;
                my ($receiver_country) = $doc->receiver =~ /^\S{4}(\S{2})/;
                my $country_regexp = 'AD|AT|BE|BV|BG|CH|CY|CZ|DE|DK|EE|ES|FI|FR|GB|GF|GI|GP|GR|HU|IE|IS|IT|LI|LT|LU|LV|MC|MQ|MT|NL|NO|PL|PM|PT|RE|RO|SE|SI|SJ|SK|SM|TF|VA';
                unless ($sender_country =~ /$country_regexp/ && $receiver_country =~ /$country_regexp/) {
                    return 1;
                }
                my $need_to_check_59a = 0;
                unless (seq_key_exists($doc->{data}, '57A')) {
                    $need_to_check_59a = 1;
                }
                if (seq_key_exists($doc->{data}, '57A')) {
                    my $v57a = seq_get_first($doc->{data}, '57A');
                    $v57a =~ s|^/.*[\r\n]||;
                    my ($bic_country) = $v57a =~ /^\S{4}(\S{2})/;
                    if ($bic_country =~ $country_regexp) {
                        $need_to_check_59a = 1;
                    }
                }
                if ($need_to_check_59a) {
                    my $v59a = seq_get_first($doc->{data}, '59A?');
                    my ($acc) = $v59a =~ m|^/(\S+)|;
                    return 0 unless ($acc =~ /^[A-Z]{2}\d{2}\S+$/);
                    return 0 if (length($acc) > 34);
                }
                return 1;
            },
            err => 'D19',
        },
    ]

};

1;