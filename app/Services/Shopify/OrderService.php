<?php

namespace App\Services\Shopify;

use App\Dto\Shopify\ConfirmCheckoutDto;
use App\Repository\Shopify\Http\ShopifyRepository;
use App\Repository\Shopify\Session\CartRepository;

class OrderService
{
    private ShopifyRepository $shopifyRepository;
    private CartRepository $cartRepository;

    public function __construct(ShopifyRepository $shopifyRepository, CartRepository $cartRepository)
    {
        $this->shopifyRepository = $shopifyRepository;
        $this->cartRepository = $cartRepository;
    }

    public function createOrder(ConfirmCheckoutDto $dto)
    {
        $shippingRate = [];
        $shippingZones = $this->shopifyRepository->getShippingZones();
        $cartItems = $this->cartRepository->getCart()['checkout'];

        foreach($shippingZones['shipping_zones'] as $shippingZone) {
            $rates = $shippingZone['price_based_shipping_rates'];
            foreach ($rates as $rate) {
                if ($rate['id'] === $dto->shippingMethodId) {
                    $shippingRate['title'] = $rate['name'];
                    $shippingRate['price'] = $rate['price'];
                }
            }
        }

        $order = [
            'order' => [
                'line_items' => $this->getProducts($cartItems),
                'email' => $dto->email,
                'phone' => $dto->phone,
                'shipping_address' => [
                    'first_name' => $dto->firstName,
                    'last_name' => $dto->lastName,
                    'address1' => $dto->street,
                    'phone' => $dto->phone,
                    'city' => $dto->city,
                    'country' => $dto->country,
                    'zip' => $dto->postalCode,
                ],
                'shipping_lines' => [
                    [
                        'title' => $shippingRate['title'],
                        'price' => $shippingRate['price'],
                    ],
                ],
                'financial_status' => 'paid',
            ],

        ];

        $response = $this->shopifyRepository->createOrder($order);

        if ($response) {
            $this->cartRepository->flushCart();
        }
    }

    private function getProducts(array $items)
    {
        $itemsPayload = [];

        foreach ($items as $key => $item) {
            $itemsPayload[$key] = [
                'variant_id' => $item['id'],
                'quantity' => $item['quantity'],
            ];
        }

        return $itemsPayload;
    }
}
