<?php

const ENV_LOCAL = 'local';
const ENV_TESTING = 'testing';
const ENV_STAGING = 'staging';
const ENV_PROD = 'production';

if (! function_exists('block')) {
    /**
     * Find a block by name.
     *
     * @param  string  $blockName
     * @return string|\Illuminate\View\View
     */
    function block(string $blockName)
    {
        $block = \WTG\Models\Block::where('name', $blockName)->first();

        if ($block === null) {
            return "Block '$blockName' not found";
        }

        return view('block', compact('block'));
    }
}

if (! function_exists('format_price')) {
    /**
     * Format a float into a price.
     *
     * @param  float  $price
     * @return string
     */
    function format_price(float $price): string
    {
        return number_format($price, 2, ',', '.');
    }
}

if (! function_exists('unit_to_str')) {
    /**
     * Turn a unit into the full string.
     *
     * Example: STK to stuks/stuk
     *
     * @param  string  $unit
     * @param  bool  $plural
     * @return string
     */
    function unit_to_str(string $unit, bool $plural = true): string
    {
        switch ($unit) {
            case 'STK':
                return $plural ? __('stuks') : __('stuk');
            case 'LGT':
                return $plural ? __('lengten') : __('lengte');
            case 'ROL':
                return $plural ? __('rollen') : __('rol');
            case 'SET':
                return $plural ? __('sets') : __('set');
            case 'DS':
            case 'BOX':
                return $plural ? __('dozen') : __('doos');
            case 'ZAK':
                return $plural ? __('zakken') : __('zak');
            case 'M2':
                return __('vierkante meter');
            case 'MTR':
                return __('meter');
            case 'TB':
                return $plural ? __('tubes') : __('tube');
            case 'PL':
                return $plural ? __('platen') : __('plaat');
            case 'NVP':
                return __('niet verpakt');
            case 'BND':
                return $plural ? __('bundels') : __('bundel');
            case 'BLI':
            case 'BLK2':
                return $plural ? __('blikken') : __('blik');
            case 'KRF':
                return __('krimpfolie');
            case 'JER':
            case 'JER2':
                return $plural ? __('jerrycans') : __('jerrycan');
            case 'EM':
                return $plural ? __('emmers') : __('emmer');
            case 'SPB':
                return $plural ? __('spuitbussen') : __('spuitbus');
            case 'FLES':
                return $plural ? __('flessen') : __('fles');
            case 'DAG':
                return $plural ? __('dagen') : __('dag');
            default:
                return $unit;
        }
    }
}