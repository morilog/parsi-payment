<?php namespace Laratalks\ParsiPayment\PaymentProcessors;
use Laratalks\ParsiPayment\AbstractProcessor;
use Laratalks\ParsiPayment\AbstractSoapProcessor;
use Laratalks\ParsiPayment\PaymentProcessorInterface;
/**
 * File Name: Zarinpal.php
 * User: morilog
 * Date: 1/6/15
 * Time: 9:00 PM
 */
class Zarinpal extends AbstractSoapProcessor implements PaymentProcessorInterface {


    /**
     * @var
     */
    protected $description;

    /**
     * @var null
     */
    protected $paymentRequestData = null;

    /**
     * @var null
     */
    protected $authority = null;
    

    /**
     * @param $description
     * @return $this
     * @throws \Exception
     */
    public function setDescription($description)
    {
        if(empty($description) || strlen($description) < 1)
        {
            throw new \Exception('Description must have at least 1 charechters');
        }

        $this->description = $description;
        return $this;
    }

    /**
     * @param string $authority
     */
    public function setAuthority($authority)
    {
        $this->authority = $authority;
    }

    /**
     * @return string
     */
    public function getAuthority()
    {
        return $this->authority;
    }

    /**
     * @param $amount
     * @param $description
     * @param string $email
     * @param string $mobile
     * @return $this|string
     */
    public function setPaymentRequestData($amount, $description, $email = '', $mobile = '')
    {
        try
        {
            $this->setAmount($amount);
            $this->setDescription($description);

            $this->paymentRequestData =  [
                'MerchantID' 	=> $this->configs['merchant_code'],
                'Amount' 	=> $this->amount,
                'Description' 	=> $this->description,
                'Email' 	=> $email,
                'Mobile' 	=> $mobile,
                'CallbackURL' 	=> $this->configs['callback_url']
            ];

            return $this;
        }
        catch(\Exception $ex)
        {
            return $ex->getMessage();
        }
    }

    /**
     * @return null
     * @throws \Exception
     */
    public function getPaymentRequestData()
    {
        if(is_null($this->paymentRequestData))
        {
            throw new \Exception("there is no valid data");
        }

        return $this->paymentRequestData;
    }

    /**
     * @param $response
     * @return $this
     * @throws \Exception
     */
    public function handleRequestResponse($response)
    {
        if($response->Status === 100)
        {
            $this->setAuthority($response->Authority);
        }
        else
        {
            throw new \Exception($response->Status);
        }

        return $this;
    }

    /**
     * @param $response
     * @return int RefID
     * @throws \Exception
     */
    public function handleVerifyResponse($response)
    {
        if($response->Status === 100)
        {
            return $this->RefID;
        }
        else
        {
            throw new \Exception($response->Status);
        }
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getPaymentUrl()
    {
        $gatewayUrl = $this->configs['web_gate_way_url'];

        if(filter_var($gatewayUrl, FILTER_VALIDATE_URL) === false)
        {
            throw new \Exception('web gate url is not valid');
        }

        $authority = $this->getAuthority();

        return $gatewayUrl.$authority;
    }




    /**
     * @return Zarinpal|string
     */
    public function requestPayment()
    {

        try
        {
            $data = $this->getPaymentRequestData();
            $results = $this->client->PaymentRequest($data);

            return $this->handleRequestResponse($results);
        }
        catch(\Exception $ex)
        {
            return $ex->getMessage();
        }
    }

    /**
     * @param array $verifyData
     * @return int|string
     */
    public function doVerify(array $verifyData)
    {
        $wsdl = $this->configs['wsdl'];

        try
        {
            $client = $this->client->SoapClient($wsdl, ['encoding' => 'UTF-8'] );

            $data = [
                'MerchantID' => $this->configs['merchant_code'],
                'amount' => $verifyData['amount'],
                'authority' => $verifyData['authority']
            ];

            $response = $client->PaymentVerfication($data);
            return $this->handleVerifyResponse($response);
        }
        catch(\Exception $ex)
        {
            return $ex->getMessage();
        }
    }
}