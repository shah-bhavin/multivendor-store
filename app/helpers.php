<?php

if (! function_exists('currency')) {

    function currency($amount)
    {
        /*
        |--------------------------------------------------------------------------
        | Current Currency
        |--------------------------------------------------------------------------
        */

        $currency = session(

            'currency',

            config(
                'currency.default'
            )
        );

        /*
        |--------------------------------------------------------------------------
        | Currency Data
        |--------------------------------------------------------------------------
        */

        $data = config(

            "currency.supported.$currency"
        );

        /*
        |--------------------------------------------------------------------------
        | Convert Amount
        |--------------------------------------------------------------------------
        */

        $converted =
            $amount * $data['rate'];

        return $data['symbol'].number_format($converted, 2);
    }
}