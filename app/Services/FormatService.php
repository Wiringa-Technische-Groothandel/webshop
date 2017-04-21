<?php

namespace App\Services;

/**
 * Format service
 *
 * @author  Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class FormatService extends Service
{
    /**
     * Number formatter.
     *
     * @param  float  $number
     * @param  int  $decimals
     * @param  string  $decimal_point
     * @param  string  $thousand_separator
     * @return string
     */
    public function number($number, $decimals = 0, $decimal_point = ',', $thousand_separator = '.')
    {
        return number_format($number, $decimals, $decimal_point, $thousand_separator);
    }

    /**
     * Format a price
     *
     * @param  float  $number
     * @return string
     */
    public function price($number)
    {
        return number_format($number, 2, ',', '.');
    }

    /**
     * Uppercase the full user name.
     *
     * @param  string  $name
     * @return string
     */
    public function name($name)
    {
        return ucwords(strtolower($name));
    }

    /**
     * Convert bytes to a human readable size.
     *
     * @param  float  $bytes
     * @return string
     */
    public function bytes($bytes)
    {
        $type = ['', 'kilo', 'mega', 'giga', 'tera', 'peta', 'exa', 'zetta', 'yotta'];
        $index = 0;

        while ($bytes >= 1024) {
            $bytes /= 1024;

            $index++;
        }

        return round($bytes, 2).' '.$type[$index].'bytes';
    }
}
