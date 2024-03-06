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
        # �������������� (��� ������ D49).
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

        # �3. ���� ���� 23� �������� ��� SPRI, �� � ���� 23� ����� �������������� ������ ����
        # SDVA, INTC (��� ������ E01). ���� ���� 23� �������� ��� SSTD ��� SPAY, �� ���� 23�
        # �� ������ �������������� (��� ������ E02).
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

        # C4. ���� � ��������� ������������ ���� 55A, �� ������ ����� �������������� ��� ���� 53A,
        # ��� � ���� 54A (��� ������ �06).
        {
            if   => ['exists', '55A'],
            must => [
                ['exists', '53A'],
                ['exists', '54A'],
            ],
            err  => 'E06',
        },

        # �5. ���� � ��������� ������������ ���� 56A, �� ������ �������������� ����� � ���� 57A
        # (��� ������ �81).
        {
            if   => ['exists', '56A'],
            must => ['exists', '57A'],
            err  => 'C81',
        },

        # C6. ���� ���� 23� �������� ��� SPRI, �� ���� 56A �� ������ ��������������
        # (��� ������ �16).
        {
            if   => ['match', '23B', '^SPRI\s*$'],
            must => ['not_exists', '56A'],
            err  => 'E16',
        },

        # �7. ���� ���� 71A �������� ��� �OUR�, �� ���� 71F �� ������ ��������������, � ���� 71G
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

        # �8. ���� � ��������� ������������ ���� ���� 71F (���� �� ���� ���), ���� ���� 71G, ��
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

        # �9. ��� ������ � ����� 71G � 32� ������ ���� ���������� (��� ������ �02).
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
        # ���� ���� ����� � ����� BIC ����������� � ���������� ������ � ��������� ��������
        # ����� �����: AD, AT, BE, BV, BG, CH, CY, CZ, DE, DK, EE, ES, FI, FR, GB, GF, GI, GP,
        # GR, HU, IE, IS, IL, IT, LI, LT, LU, LV, MC, MQ, MT, NL, NO, PL, PM, PT, RE, RO, SE,
        # SI, SJ, SK, SM, TF � VA, - �� ������ ����������� ��������� �������:
        # - ���� ����������� ���� 57�, �� � ������� ����� ���� 59� �����������
        # ������ ���� ������ ������������� ����� ����������� ����� IBAN
        # (�������� ISO-13616) (��� ������ D19).
        # - ���� ���� 57� ������������, � ���� ��������� � ��� ��� BIC �������� ��� ������,
        # �������� � ����������� ���� ��������, �� � ������� ����� ���� 59� �����������
        # ������ ���� ������ ������������� ����� ����������� ����� IBAN
        # (�������� ISO-13616) (��� ������ D19).
        # �� ���� ��������� ������� ������������� ������ ����� IBAN (�������� ISO-13616) ��
        # �����������, � ������ ������� ����� � ���� 59� �� ����������� �������� SWIFT.
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