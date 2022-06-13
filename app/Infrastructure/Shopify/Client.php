<?php

namespace App\Infrastructure\Shopify;

use App\Models\User;
use App\Repository\Shopify\UserRepository;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\HandlerStack;

class Client
{
    private HttpClient $httpClient;

    private UserRepository $userRepository;

    public function __construct(HttpClient $httpClient, UserRepository $userRepository)
    {
        $this->httpClient = $httpClient;
        $this->userRepository = $userRepository;
    }

    public function rest($httpMethod, $resource, $attributes = [])
    {
        /** @var User $shop */
        $shop = $this->userRepository->where('name', '=', session()->get('shop'))->first();

        if (is_null($shop)) {
            throw new \DomainException('App is not installed in store');
        }

        $shopifyApiVersion = config('shopify-app.api_version');

        $url = 'https://' . $shop->getDomain()->toNative() . '/admin/api/'. $shopifyApiVersion .'/' . $resource;

        $stack = HandlerStack::create();

        $parameters['handler'] = $stack;
        $parameters['headers'] = [
            'Accept'       => 'application/json',
            'Content-Type' => 'application/json',
            'X-Shopify-Access-Token' => $shop->getAccessToken()->toNative(),
        ];
        $parameters['body'] = json_encode($attributes);

        $response = $this->httpClient->request($httpMethod, $url ,$parameters);

        return json_decode($response->getBody(),true);
    }

    public function json($httpMethod, $resource)
    {
        /** @var User $shop */
        $shop = $this->userRepository->where('name', '=', session()->get('shop'))->first();

        if (is_null($shop)) {
            throw new \DomainException('App is not installed in store');
        }

        $url = 'https://' . $shop->getDomain()->toNative() . '/admin/' . $resource;

        $stack = HandlerStack::create();

        $parameters['handler'] = $stack;
        $parameters['headers'] = [
            'X-Shopify-Access-Token' => $shop->getAccessToken()->toNative(),
        ];

        $response = $this->httpClient->request($httpMethod, $url ,$parameters);

        return json_decode($response->getBody(),true);
    }

    public function graph($query)
    {
        /** @var User $shop */
        $shop = $this->userRepository->where('name', '=', session()->get('shop'))->first();

        if (is_null($shop)) {
            throw new \DomainException('App is not installed in store');
        }

        $shopifyApiVersion = config('shopify-app.api_version');

        $url = 'https://' . $shop->getDomain()->toNative() . '/admin/api/'. $shopifyApiVersion .'/graphql.json';

        $request = ['query' => $query];

        $req = json_encode($request);

        $stack = HandlerStack::create();

        $parameters['body'] = $req;
        $parameters['handler'] = $stack;
        $parameters['headers'] = [
            'Accept'       => 'application/json',
            'Content-Type' => 'application/json',
            'X-Shopify-Access-Token' => $shop->getAccessToken()->toNative(),
        ];

        $response = $this->httpClient->request('post', $url ,$parameters);

        return json_decode($response->getBody(),true);
    }
}
