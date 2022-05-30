<?php

namespace App\Utils\Helpers;

class ShopDomainHelper
{
    public static function getShopDomain(): string
    {
        return sprintf("https://%s", session()->get('shop'));
    }
}
