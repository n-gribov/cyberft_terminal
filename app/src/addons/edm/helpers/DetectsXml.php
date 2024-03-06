<?php

namespace addons\edm\helpers;

trait DetectsXml
{
    protected static function isXml(string $string): bool
    {
        $stringWithoutBom = self::removeUtf8Bom($string);
        return $stringWithoutBom[0] === '<';
    }

    private static function removeUtf8Bom(string $string): string
    {
        $bom = pack('H*', 'EFBBBF');
        return preg_replace("/^$bom/", '', $string);
    }
}