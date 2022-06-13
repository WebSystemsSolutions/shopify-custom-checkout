<?php

namespace App\Validator\Shopify;

use App\Validator\Validator;

class ConfirmCheckoutValidator extends Validator
{
    public function getRules(): array
    {
        return [
            'email' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:55', 'regex:/^(9[976]\d|8[987530]\d|6[987]\d|5[90]\d|42\d|3[875]\d|2[98654321]\d|9[8543210]|8[6421]|6[6543210]|5[87654321]|4[987654310]|3[9643210]|2[70]|7|1)\W*\d\W*\d\W*\d\W*\d\W*\d\W*\d\W*\d\W*\d\W*(\d{1,2})$/'],
            'firstName' => ['required', 'string', 'max:255'],
            'lastName' => ['required', 'string', 'max:255'],
            'country' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'street' => ['required', 'string', 'max:255'],
            'postalCode' => ['required', 'string', 'max:10'],
            'shippingMethodId' => ['required', 'string', 'max:55'],
        ];
    }

    public function getMessages(): array
    {
        return [
            'city.regex' => 'City name must contain only latin letters',
            'street.regex' => 'Street name can contain only latin letters, numbers and commas',
        ];
    }
}
