<?php
/**
 * File Name: payment.php
 * User: morilog
 * Date: 1/6/15
 * Time: 10:59 PM
 */
return [

    /**
     * Supported Gateways
     * Zarinpal
     */
    'default' => 'Zarinpal',

    'gateways' => [

        /**
         * Payline payment gateway
         */
        'Payline' => [],

        /**
         * JahanPay Payement gateway
         */
        'JahanPay' => [],


        /**
         * zarinpal payment gateway
         */
        'Zarinpal' => [

            /**
             * Amount of payment(Toman)
             */
            'min_amount' => 1000,

            'merchant_code' => 'MERCHANT_CODE_HERE',

            /**
             * Gateway URL
             * url: ‫‪https://www.zarinpal.com/pg/StartPay/{Authority‬‬}
             */
            'web_gateway_url' => '‫‪https://www.zarinpal.com/pg/StartPay/',

            'callback_url' => 'CALLBACK_URL_HERE',

            /**
             *   WSDL URL
             *   https://ir.zarinpal.com/pg/services/WebGate/wsdl
             *      OR
             *   https://de.zarinpal.com/pg/services/WebGate/wsdl
             */
            'wsdl' => 'https://ir.zarinpal.com/pg/services/WebGate/wsdl'

        ]

    ]


];