<?php

namespace App\Dto\Shopify;

use App\Dto\AbstractDto;

class ShipmentsDto extends AbstractDto
{
    public string $country;

    public static function createFromData(array $request)
    {
        return new self(
            [
                'country' => (string)$request['country'],
            ]
        );
    }
}
