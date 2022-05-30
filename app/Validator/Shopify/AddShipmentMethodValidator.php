<?php

namespace App\Validator\Shopify;

use App\Validator\Validator;

class AddShipmentMethodValidator extends Validator
{
    public function getRules(): array
    {
        return [
            'title' => ['required'],
            'price' => ['required'],
        ];
    }

    public function getMessages(): array
    {
        return [];
    }
}
