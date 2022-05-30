<?php

namespace App\Utils\Shopify;

use Illuminate\Http\Request;

class ShopSessionResolver
{
    public function resolve(Request $request)
    {
        $shopName = $request->get('shop');
        $sessionShop = $request->session()->get('shop');

        if (is_null($sessionShop)) {
            session()->put(['shop' => $shopName]);

            return;
        }

        if (!is_null($shopName) && $sessionShop !== $shopName) {
            $request->session()->forget('shop');
            $request->session()->flush();
            $request->session()->save();

            session()->put(['shop' => $shopName]);

            return;
        }

        if ($sessionShop) {
            return;
        }
    }
}
