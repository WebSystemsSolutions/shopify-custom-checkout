<?php

namespace App\Repository\Shopify\Http;

use App\Infrastructure\Shopify\Client;
use GuzzleHttp\Exception\ClientException;

class ShopifyRepository
{
    private Client $httpClient;

    public function __construct(Client $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function createOrder(array $order): array
    {
        return $this->httpClient->rest(
            'post',
            'orders.json',
            $order
        );
    }

    public function getShippingZones(): array
    {
        return $this->httpClient->json(
            'get',
            'api/2022-04/shipping_zones.json'
        );
    }

    public function getAllShippingZones(): array
    {
        $shippingZones = $this->httpClient->graph(
            'query {
                __type(name: "CountryCode") {
                   states: enumValues {
                       name
                       description
                   }
                }
            }'
        );

        return $shippingZones['data']['__type']['states'];
    }

    public function getVariants($checkoutItemId): array
    {
        try {
            $shopifyVariants = $this->httpClient->json(
                'get',
                'api/2022-04/variants/'. $checkoutItemId .'.json'
            );
        } catch (ClientException $exception) {
            throw new \DomainException('One of product variant not found in the store');
        }

        return $shopifyVariants;
    }

    public function getProduct($productId): array
    {
        try {
            $shopifyProduct = $this->httpClient->json(
                'get',
                'products/'. $productId .'.json'
            );
        } catch (ClientException $exception) {
            throw new \DomainException('One of products not found in the store');
        }

        return $shopifyProduct;
    }

    public function getImages($productId): array
    {
        return $this->httpClient->json(
            'get',
            'api/2022-04/products/'. $productId .'/images.json'
        );
    }
}
