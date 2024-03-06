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

        # �1. ���� � ��������� ������������ ���� 33� � ���� ��������� � ��� ��� ������ �������
        # �� ���� ������ � ���� 32�, � ��������� ������ �������������� ���� 36; � ���������
        # ������� ���� 36 �� ������������ (��� ������ D75).
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

        # �2. ���� ���� ����� � ����� BIC ����������� � ���������� ������ � ��������� ��������
        # ����� �����: AD, AT, BE, BV, BG, CH, CY, CZ, DE, DK, EE, ES, FI, FR, GB, GF, GI, GP,
        # GR, HU, IE, IS, IT, LI, LT, LU, LV, MC, MQ, MT, NL, NO, PL, PM, PT, RE, RO, SE, SI,
        # SJ, SK, SM, TF � VA, - �� ���� 33� �������� ������������, � ��������� ������� ���� 33�
        # ��������������.
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

        # �3. ���� ���� 23� �������� ��� SPRI, �� � ���� 23� ����� �������������� ������ ����
        # SDVA, TELB, PHOB, INTC (��� ������ E01). ���� ���� 23� �������� ��� SSTD ��� SPAY,
        # �� ���� 23� �� ������ ��������������.
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

        # C4. ���� ���� 23� �������� ���� �� ����� SPRI, SSTD ��� SPAY, �� ���� 53a �� ������
        # �������������� � ������ D (��� ������ E03).
        {
            if   => ['match', '23B', '^(SPRI|SSTD|SPAY)\s*$'],
            must => ['not_exists', '53D'],
            err  => 'E03',
        },

        # C5. ���� ���� 23� �������� ���� �� ����� SPRI, SSTD ��� SPAY, � ���� 53a ������������
        # � ������ �, �� � ���� 53� ������ �������������� ������� �������������� ��������
        # (��� ������ E04).
        {
            if   => [
                ['match', '23B', '^(SPRI|SSTD|SPAY)\s*$'],
                ['exists', '53B'],
            ],
            must => ['match', '53B', '/\S+'],
            err  => 'E04',
        },

        # C6. ���� ���� 23� �������� ���� �� ����� SPRI, SSTD ��� SPAY, �� ���� 54a �����
        # �������������� ������ � ������ � (��� ������ E05).
        {
            if   => ['match', '23B', '^(SPRI|SSTD|SPAY)\s*$'],
            must => ['not_exists', '54[^A]?'],
            err  => 'E05',
        },

        # C7. ���� � ��������� ������������ ���� 55�, �� ������ ����� �������������� ��� ���� 53�,
        # ��� � ���� 54� (��� ������ �06).
        {
            if   => ['exists', '55[ABD]'],
            must => [
                ['exists', '53[ABD]'],
                ['exists', '54[ABD]'],
            ],
            err  => 'E06',
        },

        # C8. ���� ���� 23� �������� ���� �� ����� SPRI, SSTD ��� SPAY, �� ���� 55a �����
        # �������������� ������ � ������ � (��� ������ E07).
        {
            if   => ['match', '23B', '^(SPRI|SSTD|SPAY)\s*$'],
            must => ['not_exists', '55[^A]?'],
            err  => 'E07',
        },

        # �9. ���� � ��������� ������������ ���� 56�, �� ������ �������������� ����� � ���� 57�
        # (��� ������ �81).
        {
            if   => ['exists', '56[ACD]'],
            must => ['exists', '57[ABCD]'],
            err  => 'C81',
        },

        # C10. ���� ���� 23� �������� ��� SPRI, �� ���� 56� �� ������ ��������������
        # (��� ������ �16). ���� ���� 23� �������� ���� �� ����� SSTD ��� SPAY, �� ���� 56a �����
        # �������������� ���� � ������ �, ���� � ������ �. ���� ������������ ����� �, �� � ���� ����
        # ������ ���� ������ ����������� ���. (��� ������ E17).
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

        # �11. ���� ���� 23� �������� ��� SPRI, SSTD ��� SPAY, �� ���� 57a ����� �������������� �
        # ������ �, � ������ � ��� � ������ D. ��� ������������� ����� D � ���� 57a ������
        # �������������� ������� 1 �������������� �������� (��� ������ E09).
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

        # �12. ���� ���� 23� �������� ���� �� ����� SPRI, SSTD ��� SPAY, �� � ���� 59�
        # �������-���������� ����������� ������ �������������� ������� 1 ����� (��� ������ E10).
        {
            if   => ['match', '23B', '^(SPRI|SSTD|SPAY)\s*$'],
            must => ['match', '59A?', '^/\S+'],
            err  => 'E10',
        },

        # �13. ���� �����-���� �� ����� 23� �������� ��� CHQB, ������� 1 ����� � ���� 59�
        # �������-���������� �� ������������ (��� ������ E18).
        {
            if   => ['match_any', '23E', '^CHQB'],
            must => ['not_match', '59A?', '^/\S+'],
            err => 'E18'
        },

        # �14. ���� 70 � 77T �������� ������������������. (��� ������ E12).
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

        # �15. ���� ���� 71A �������� ��� �OUR�, �� ���� 71F �� ������ ��������������, � ���� 71G
        # �������������� (��� ������ E13).
        {
            if   => ['match', '71A', '^OUR\s*$'],
            must => ['not_exists', '71F'],
            err  => 'E13',
        },
        # ���� ���� 71� �������� ��� �SHA�, �� ���� (����) 71F ��������������, � ���� 71G
        # �� ������ �������������� (��� ������ D50).
        {
            if   => ['match', '71A', '^SHA\s*$'],
            must => ['not_exists', '71G'],
            err  => 'D50',
        },
        # ���� ���� 71� �������� ��� �BEN�, �� ����������� ������ �������������� ���� �� ����
        # ���� 71F, � ���� 71G �� ������������ (��� ������ E15).
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

        # �16. ���� � ��������� ������������ ���� ���� 71F (���� �� ���� ���), ���� ���� 71G, ��
        # ���� 33� �������� ������������, � ��������� ������� ���� 33� ��������������
        # (��� ������ D51).
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

        # �17. ���� ���� 56� �����������, �� ���� �� ����� 23� �� ������ ��������� ���� TELI ���
        # PHOI (��� ������ �44).
        {
            if   => ['not_exists', '56[ACD]'],
            must => ['not_match', '23E', '^(TELI|PHOI)'],
            err  => 'E44'
        },

        # �18. ���� ���� 57� �����������, �� ���� �� ����� 23� �� ������ ��������� ���� TELE ���
        # PHON (��� ������ �45).
        {
            if   => ['not_exists', '57[ABCD]'],
            must => ['not_match', '23E', '^(TELE|PHON)'],
            err  => 'E45'
        },

        # �19. ��� ������ � ����� 71G � 32� ������ ���� ���������� (��� ������ �02).
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