<?php namespace Laratalks\ParsiPayment;

use SoapClient;
/**
 * File Name: AbstractSoapProcessor.php
 * User: morilog
 * Date: 1/13/15
 * Time: 4:08 PM
 */
abstract class AbstractSoapProcessor extends AbstractProcessor {

    /**
     * @var SoapClient
     */
    protected $client;

    public function __construct(array $configs)
    {
        parent::__construct($configs);
        $this->client = $this->getSoapClient();
    }

    protected function getSoapClient()
    {
        $wsdl = $this->configs['wsdl'];
        $options = ['encoding' => 'UTF-8'];

        $client = new SoapClient($wsdl, $options);

        return $client;
    }
}