<?php

namespace App\Repository\Shopify\Session;

use Illuminate\Support\Facades\Session;

class CartRepository
{
    public function putItems(array $items)
    {
        if (!is_null(session()->get('checkout'))) {
            return;
        }

        session()->put('checkout', $items);
    }

    public function putShippingMethod(array $method)
    {
        if (!is_null(session()->get('shippingMethod'))) {
            session()->forget('shippingMethod');
            session()->save();
        }

        session()->push('shippingMethod', $method);
    }

    public function flushShippingMethod()
    {
        session()->forget('shippingMethod');
        session()->save();
    }

    public function getCart()
    {
        $cart = [
            'checkout' => session()->get('checkout'),
            'shippingMethod' => session()->get('shippingMethod'),
        ];

        return $cart;
    }

    public function flushCart()
    {
        session()->forget('checkout');
        session()->forget('shippingMethod');
        session()->save();
    }
}
