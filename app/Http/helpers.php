<?php

if (! function_exists('routeIf')) {
    /**
     * Generate the URL to a named route if it exists.
     *
     * @param  string  $name
     * @param  array   $parameters
     * @param  bool    $absolute
     * @return string
     */
    function routeIf($name, $parameters = [], $absolute = true)
    {
        try {
            return app('url')->route($name, $parameters, $absolute);
        } catch (\Exception $e) {
            app('log')->warning($e->getMessage());
        }

        return '#';
    }
}