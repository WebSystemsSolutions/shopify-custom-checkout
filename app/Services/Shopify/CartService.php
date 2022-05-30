<?php

namespace App\Services\Shopify;

use App\Repository\Shopify\Http\ShopifyRepository;

class CartService
{
    private ShopifyRepository $shopifyRepository;

    public function __construct(ShopifyRepository $shopifyRepository)
    {
        $this->shopifyRepository = $shopifyRepository;
    }

    public function calculateCartWithShippingMethod(array $cart): array
    {
        $cartCost = 0;
        $shipmentCost = 0;
        $total = 0;

        if ($cart['checkout']) {
            foreach($cart['checkout'] as $item) {;
                $cartCost += $item['price'] * $item['quantity'];
            }
        }

        if ($cart['shippingMethod']) {
            foreach($cart['shippingMethod'] as $item) {
                $shipmentCost += $item['price'];
            }
        }

        return [
            'cartCost' => $cartCost,
            'shipmentCost' => $shipmentCost,
            'total' => $cartCost + $shipmentCost,
        ];
    }

    public function calculateCart(array $cart): float
    {
        $cartCost = 0;

        if ($cart) {
            foreach($cart as $item) {
                $cartCost += $item['price'] * $item['quantity'];
            }
        }

        return $cartCost;
    }

    public function getCheckoutItems($cartItems): array
    {
        $checkoutItems = [];
        $checkoutItemsCounter = 0;

        foreach ($cartItems as $product) {
            $parts = explode(':', $product);

            $checkoutItems[$checkoutItemsCounter]['id'] = $parts[0];
            $checkoutItems[$checkoutItemsCounter]['quantity'] = $parts[1];

            $checkoutItemsCounter++;
        }

        foreach ($checkoutItems as $key => $checkoutItem) {
            $shopifyVariant = $this->shopifyRepository->getVariants($checkoutItem['id']);
            $productId = $shopifyVariant['variant']['product_id'];

            $shopifyProduct = $this->shopifyRepository->getProduct($productId);

            $images = $this->shopifyRepository->getImages($productId);

            foreach ($images['images'] as $image) {
                if (!empty($image['variant_ids']) && in_array($checkoutItem['id'], $image['variant_ids'])) {
                    $checkoutItems[$key]['image'] = $image['src'];
                }
            }

            $checkoutItems[$key]['title'] = $shopifyProduct['product']['title'];
            $checkoutItems[$key]['price'] = $shopifyVariant['variant']['price'];
        }

        return $checkoutItems;
    }

    public function getShippingZoneNames()
    {
        $shippingZonesNames = [];

        $shippingZones = $this->shopifyRepository->getShippingZones();

        foreach($shippingZones['shipping_zones'] as $shippingZone) {
            $countries = $shippingZone['countries'];

            foreach ($countries as $country) {
                $shippingZonesNames[] = $country['name'];
            }
        }

        return $shippingZonesNames;
    }

    public function getShippingZoneMethods($countryName)
    {
        $shippingZoneName = [];
        $shippingZonesMethods = [];
        $shippingZones = $this->shopifyRepository->getShippingZones();

        foreach($shippingZones['shipping_zones'] as $shippingZone) {
            $countries = $shippingZone['countries'];

            foreach ($countries as $country) {
                if ($country['name'] === $countryName) {
                    $shippingZoneName = $shippingZone['price_based_shipping_rates'];
                }
            }
        }

        foreach ($shippingZoneName as $key => $method) {
            $shippingZonesMethods[$key]['id'] = $method['id'];
            $shippingZonesMethods[$key]['name'] = $method['name'];
            $shippingZonesMethods[$key]['price'] = $method['price'];
        }

        return $shippingZonesMethods;
    }
}
