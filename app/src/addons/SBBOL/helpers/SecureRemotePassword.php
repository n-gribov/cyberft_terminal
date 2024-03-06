<?php

namespace addons\SBBOL\helpers;

class SecureRemotePassword
{
    private $A;
    private $K;
    private $login;
    private $S;

    const N_HEX = '0115B8B692E0E045692CF280B436735C77A5A9E8A9E7ED56C965F87DB5B2A2ECE3';
    const G_HEX = '02';
    const K_HEX = 'DBE5DFE0704FEE4C85FF106ECD38117D33BCFE50';

    public function __construct($login, $password, $saltHex, $BHex)
    {
        $this->login = $login;
        $this->calculateParams($password, $saltHex, $BHex);
    }

    public function getA()
    {
        return $this->A;
    }

    public function getK()
    {
        return $this->K;
    }

    public function getS()
    {
        return $this->S;
    }

    // See 1 часть Документация по API УПШ 29.pdf 2.8.1.7.
    public function calculateNewPasswordVerifier($newPassword, $newSalt)
    {
        $N = gmp_init(self::N_HEX, 16);
        $g = gmp_init(self::G_HEX, 16);

        $HpNew = sha1("{$this->login}:$newPassword");
        $xNew = sha1($newSalt . hex2bin($HpNew));

        // v_new = g ^ x_new mod N
        $VNew = gmp_powm($g, gmp_init($xNew, 16), $N);
        return static::gmpToBin($VNew);
    }

    // See 1 часть Документация по API УПШ 29.pdf 2.8.1.6., https://tools.ietf.org/html/rfc2945
    private function calculateParams($password, $salt, $BBin)
    {
        $N = gmp_init(self::N_HEX, 16);
        $g = gmp_init(self::G_HEX, 16);
        $k = gmp_init(self::K_HEX, 16);

        $B = static::binToGmp($BBin);
        $Hp = sha1("{$this->login}:$password");

        $a = gmp_random_bits(256);

        // A = g ^ a mod N
        $A = gmp_powm($g, $a, $N);

        $xHex = sha1($salt . hex2bin($Hp));
        $x = gmp_init($xHex, 16);

        $NLength = strlen(self::N_HEX) / 2;
        $uHex = sha1(
            str_pad(static::gmpToBin($A), $NLength, hex2bin('00'), STR_PAD_LEFT)
            . str_pad(static::gmpToBin($B), $NLength, hex2bin('00'), STR_PAD_LEFT)
        );
        $u = gmp_init($uHex, 16);

        // Base = B – k * ((g ^ x) mod N)
        $Base = gmp_sub(
            $B,
            gmp_mul(
                $k,
                gmp_powm($g, $x, $N)
            )
        );

        // If Base is negative, Base = k * N + B – k * ((g ^ x) mod N)
        if (gmp_cmp($Base, gmp_init(0)) < 0) {
            $Base = gmp_sub(
                gmp_add(
                    gmp_mul($k, $N),
                    $B
                ),
                gmp_mul(
                    $k,
                    gmp_powm($g, $x, $N)
                )
            );
        }

        // S = (Base ^ (a + u * x)) mod N
        $S = gmp_powm(
            $Base,
            gmp_add($a, gmp_mul($u, $x)),
            $N
        );

        $K = sha1(static::gmpToBin($S));

        $this->K = hex2bin($K);
        $this->A = static::gmpToBin($A);
        $this->S = static::gmpToBin($S);
    }

    private static function gmpToBin($gmpNumber)
    {
        $hex = gmp_strval($gmpNumber, 16);
        if (strlen($hex) % 2 === 1) {
            $hex = "0$hex";
        }
        return hex2bin($hex);
    }

    private static function binToGmp($bin)
    {
        $hex = bin2hex($bin);
        return gmp_init($hex, 16);
    }

}
