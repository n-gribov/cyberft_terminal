<?php
namespace common\modules\certManager\components\ssl;

interface DriverInterface
{
    public function verify( $data ,  $signature , $pubKey);
}