<?php

namespace App\Dto\Shopify;

use App\Dto\AbstractDto;

class CartDto extends AbstractDto
{
    public string $cart;
    public string $shop;

    public static function createFromData(array $request)
    {
        return new self(
            [
                'cart' => (string)$request['cart'],
                'shop' => (string)$request['shop'],
            ]
        );
    }
}
