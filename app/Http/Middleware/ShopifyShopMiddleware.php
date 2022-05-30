<?php

namespace App\Http\Middleware;

use App\Utils\Shopify\ShopSessionResolver;
use Closure;

class ShopifyShopMiddleware
{
    private ShopSessionResolver $resolver;

    /**
     * ShopifyShopMiddleware constructor.
     */
    public function __construct(ShopSessionResolver $resolver)
    {
        $this->resolver = $resolver;
    }

    public function handle($request, Closure $next, $guard = null)
    {
        $this->resolver->resolve($request);

        return $next($request);
    }
}
