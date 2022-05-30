<?php

namespace App\Dto\Shopify;

use App\Dto\AbstractDto;

class AddShipmentMethodData extends AbstractDto
{
    public string $title;
    public float $price;

    public static function createFromData(array $request)
    {
        return new self(
            [
                'title' => (string)$request['title'],
                'price' => (float)$request['price'],
            ]
        );
    }
}
