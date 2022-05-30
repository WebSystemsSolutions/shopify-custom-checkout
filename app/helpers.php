<?php

if (! function_exists('shopify_route')) {
    /**
     * Generate the URL to a named route.
     *
     * @param  array|string  $name
     * @param  mixed  $parameters
     * @param  bool  $absolute
     * @return string
     */
    function shopify_route($name, $parameters = [], $absolute = true)
    {
        $parameters['shop'] = config('shopify-app.app_name');

        return app('url')->route($name, $parameters, $absolute);
    }
}
