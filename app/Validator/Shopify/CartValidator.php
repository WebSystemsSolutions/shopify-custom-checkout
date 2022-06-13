<?php

namespace App\Validator\Shopify;

use App\Validator\Validator;

class CartValidator extends Validator
{
    public function getRules(): array
    {
        return [
            'cart' => [
                'required',
                'string',
                'regex:/^[\d+:\d+,]+$/'
            ],
            'shop' => [
                'required',
                'string'
            ],
        ];
    }

    public function getMessages(): array
    {
        return [
            'cart.regex' => 'Wrong cart format',
        ];
    }
}
