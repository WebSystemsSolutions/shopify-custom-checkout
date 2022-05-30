<?php

namespace App\Validator\Shopify;

use App\Validator\Validator;

class ShipmentValidator extends Validator
{
    public function getRules(): array
    {
        return [
            'country' => ['required'],
        ];
    }

    public function getMessages(): array
    {
        return [];
    }
}
