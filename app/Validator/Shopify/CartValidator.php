<?php

namespace App\Validator\Shopify;

use App\Validator\Rules\CartRule;
use App\Validator\Validator;

class CartValidator extends Validator
{
    public function getRules(): array
    {
        return [
            'cart' => ['required', new CartRule()],
        ];
    }

    public function getMessages(): array
    {
        return [];
    }
}
