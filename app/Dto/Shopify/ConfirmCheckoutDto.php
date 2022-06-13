<?php

namespace App\Dto\Shopify;

use App\Dto\AbstractDto;

class ConfirmCheckoutDto extends AbstractDto
{
    public string $email;
    public string $phone;
    public string $firstName;
    public string $lastName;
    public string $country;
    public string $city;
    public string $street;
    public int $postalCode;
    public int $shippingMethodId;

    public static function createFromData(array $request)
    {
        $city = preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/', function ($match) {
            return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
        }, (string)$request['city']);

        $street = preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/', function ($match) {
            return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
        }, (string)$request['street']);

        $firstName = preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/', function ($match) {
            return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
        }, (string)$request['firstName']);

        $lastName = preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/', function ($match) {
            return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
        }, (string)$request['lastName']);

        return new self(
            [
                'email' => (string)$request['email'],
                'phone' => (string)preg_replace('/\D+/', '', $request['phone']),
                'firstName' => $firstName,
                'lastName' => $lastName,
                'country' => (string)$request['country'],
                'city' => $city,
                'street' => $street,
                'postalCode' => (int)$request['postalCode'],
                'shippingMethodId' => (int)$request['shippingMethodId'],
            ]
        );
    }
}
