package CyberFT::SWIFT::Types::MT103_REMIT;
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
        # необязательное.
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
           err => '33B field required for sender and receiver country codes',
        },

        # С3. Если поле 23В содержит код SPRI, то в поле 23Е могут использоваться только коды
        # SDVA, TELB, PHOB, INTC (Код ошибки E01). Если поле 23В содержит код SSTD или SPAY,
        # то поле 23Е не должно использоваться.
        {
            if   => ['match', '23B', '^SPRI\s*$'],
            must => ['match', '23E', '^(SDVA|TELB|PHOB|INTC)'],
            err  => 'E01',
        },
        {
            if   => ['match', '23B', '^(SSTD|SPAY)\s*$'],
            must => ['not_exists', '23E'],
            err  => 'E01',
        },

        # C4. Если поле 23В содержит один из кодов SPRI, SSTD или SPAY, то поле 53a не должно
        # использоваться с опцией D (Код ошибки E03).
        {
            if   => ['match', '23B', '^(SPRI|SSTD|SPAY)\s*$'],
            must => ['not_exists', '53D'],
            err  => 'E03',
        },

        # C5. Если поле 23В содержит один из кодов SPRI, SSTD или SPAY, а поле 53a используется
        # с опцией В, то в поле 53В должно присутствовать подполе «Идентификация стороны»
        # (Код ошибки E04).
        {
            if   => [
                ['match', '23B', '^(SPRI|SSTD|SPAY)\s*$'],
                ['exists', '53B'],
            ],
            must => ['match', '53B', '/\S+'],
            err  => 'E04',
        },

        # C6. Если поле 23В содержит один из кодов SPRI, SSTD или SPAY, то поле 54a может
        # использоваться только с опцией А (Код ошибки E05).
        {
            if   => ['match', '23B', '^(SPRI|SSTD|SPAY)\s*$'],
            must => ['not_exists', '54[^A]?'],
            err  => 'E05',
        },

        # C7. Если в сообщении присутствует поле 55а, то должны также присутствовать как поле 53а,
        # так и поле 54а (Код ошибки Е06).
        {
            if   => ['exists', '55[ABD]'],
            must => [
                ['exists', '53[ABD]'],
                ['exists', '54[ABD]'],
            ],
            err  => 'E06',
        },

        # C8. Если поле 23В содержит один из кодов SPRI, SSTD или SPAY, то поле 55a может
        # использоваться только с опцией А (Код ошибки E07).
        {
            if   => ['match', '23B', '^(SPRI|SSTD|SPAY)\s*$'],
            must => ['not_exists', '55[^A]?'],
            err  => 'E07',
        },

        # С9. Если в сообщении присутствует поле 56а, то должно присутствовать также и поле 57а
        # (Код ошибки С81).
        {
            if   => ['exists', '56[ACD]'],
            must => ['exists', '57[ABCD]'],
            err  => 'C81',
        },

        # C10. Если поле 23В содержит код SPRI, то поле 56а не должно использоваться
        # (Код ошибки Е16). Если поле 23В содержит один из кодов SSTD или SPAY, то поле 56a может
        # использоваться либо с опцией А, либо с опцией С. Если используется опция С, то в этом поле
        # должен быть указан клиринговый код. (Код ошибки E17).
        {
            if   => ['match', '23B', '^SPRI\s*$'],
            must => ['not_exists', '56[ACD]'],
            err  => 'E16',
        },
        {
            if   => ['match', '23B', '^(SSTD|SPAY)\s*$'],
            must => ['not_exists', '56[^AC]?'],
            err  => 'E17',
        },
        {
            if   => [
                ['match', '23B', '^(SSTD|SPAY)\s*$'],
                ['exists', '56C']
            ],
            must => ['match', '56C', '\S+'],
            err  => 'E17',
        },

        # С11. Если поле 23В содержит код SPRI, SSTD или SPAY, то поле 57a может использоваться с
        # опцией А, с опцией С или с опцией D. При использовании опции D в поле 57a должно
        # присутствовать подполе 1 «Идентификация стороны» (Код ошибки E09).
        {
            if   => ['match', '23B', '^(SPRI|SSTD|SPAY)\s*$'],
            must => ['not_exists', '57[^ACD]?'],
            err  => 'E09',
        },
        {
            if   => [
                ['match', '23B', '^(SPRI|SSTD|SPAY)\s*$'],
                ['exists', '57D'],
            ],
            must => ['match', '57D', '/\S+'],
            err  => 'E09',
        },

        # С12. Если поле 23В содержит один из кодов SPRI, SSTD или SPAY, то в поле 59а
        # «Клиент-бенефициар» обязательно должно присутствовать подполе 1 «Счет» (Код ошибки E10).
        {
            if   => ['match', '23B', '^(SPRI|SSTD|SPAY)\s*$'],
            must => ['match', '59A?', '^/\S+'],
            err  => 'E10',
        },

        # С13. Если какое-либо из полей 23Е содержит код CHQB, подполе 1 «Счет» в поле 59а
        # «Клиент-бенефициар» не используется (Код ошибки E18).
        {
            if   => ['match_any', '23E', '^CHQB'],
            must => ['not_match', '59A?', '^/\S+'],
            err => 'E18'
        },

        # С14. Поля 70 и 77T являются взаимоисключающими. (Код ошибки E12).
        {
            if   => ['exists', '70'],
            must => ['not_exists', '77T'],
            err  => 'E12',
        },
        {
            if   => ['exists', '77T'],
            must => ['not_exists', '70'],
            err  => 'E12',
        },

        # С15. Если поле 71A содержит код «OUR», то поле 71F не должно использоваться, а поле 71G
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

        # С16. Если в сообщении присутствует либо поле 71F (хотя бы один раз), либо поле 71G, то
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

        # С17. Если поле 56а отсутствует, ни одно из полей 23Е не должно содержать коды TELI или
        # PHOI (Код ошибки Е44).
        {
            if   => ['not_exists', '56[ACD]'],
            must => ['not_match', '23E', '^(TELI|PHOI)'],
            err  => 'E44'
        },

        # С18. Если поле 57а отсутствует, ни одно из полей 23Е не должно содержать коды TELE или
        # PHON (Код ошибки Е45).
        {
            if   => ['not_exists', '57[ABCD]'],
            must => ['not_match', '23E', '^(TELE|PHON)'],
            err  => 'E45'
        },

        # С19. Код валюты в полях 71G и 32А должен быть одинаковым (Код ошибки С02).
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
    ]

};

1;