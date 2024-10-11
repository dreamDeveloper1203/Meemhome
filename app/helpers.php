<?php

if (!function_exists('usd_money_format')) {
    /**
     * format number.
     *
     * @param  float  $number
     * @return string
     */
    function usd_money_format($number): string
    {
        try {
            $currency = config('app.currency');
            $formattedNumber = str_replace('.00', '', number_format($number, 2));
            return  "{$currency}{$formattedNumber}";
        } catch (\Throwable $th) {
            return "error";
        }
    }

    function api_post($url, $data, $authorization){


        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            "Authorization: $authorization"
        ));

        $response = curl_exec($ch);

        if ($response === FALSE) {
            // Handle error
            echo 'Curl error: ' . curl_error($ch);
        }

        curl_close($ch);
        return json_decode($response, true);

    }
}
