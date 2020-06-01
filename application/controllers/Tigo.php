<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: masoodurrehman42@gmail.com
 * Date: 6/12/2019
 * Time: 08:39 PM
 */

class Tigo extends MY_Controller
{
    const URL = "https://secure.tigo.com";
    const VERSION = "v1";
    const PUBLIC_KEY = "QokTxmDIcx2QAgpwplrkJxA1OYhw1A5L";
    const SECRET_KEY = "wAfFtt2jT5ELSgqh";

    public function __construct()
    {
        parent::__construct();
    }
 
    public function index()
    {
        try {
            $accessToken = $this->genAccessToken();
        
            $resp = $this->pay([  
                "MasterMerchant" =>  [ // The person whos account to be credit (client account detail)
                    "account" =>"25565000242",   
                    "pin" =>"2010",   
                    "id" =>"BAFREDO"
                ], 
                "Subscriber" =>  [ // The person who do order whose account to be debited (customer account detail)
                    "account" =>"0713123085",
                    "countryCode" => "255",   
                    "country" =>"TZA",
                    "firstName" =>"John",   
                    "lastName" =>"Doe",   
                    "emailId"  => "johndoe@mail.com"
                ],  
                "redirectUri" =>"https://www.shop.bafredo.com/tigo/payment_callback", // This callback will be called when user do payment.
                "language" =>"eng",  
                "originPayment" =>  [
                    "amount" =>"75.00",   
                    "currencyCode" =>"USD",   
                    "tax" =>"0.00",   
                    "fee" =>"25.00"  
                ],
                "LocalPayment" =>  [
                    "amount" =>"218223.00",
                    "currencyCode" =>"TZS"
                ],
                "transactionRefId" => 1
            ], $accessToken);
        
            echo '<pre>';
            print_r($resp);
        
        } catch (Exception $e) {
            
            echo $e->getMessage();
        }
    }
    
    public function genAccessToken()
    {
        $header = array('Content-Type: application/x-www-form-urlencoded');
        $payload = http_build_query(array(
            "client_id" => self::PUBLIC_KEY,
            "client_secret" => self::SECRET_KEY
        ));

        $response = $this->callService("POST", "oauth/generate/accesstoken-test-2018?grant_type=client_credentials", $payload, $header);

        return $response->accessToken;
    }

    public function pay($payload, $accessToken)
    {
        if (empty($payload)) {
            throw new Exception("Request payload is required");
        }

        $header = array('Content-Type: application/json', "accessToken:{$accessToken}");
        $payload = json_encode($payload);

        $response = $this->callService("POST", "tigo/payment-auth-test-2018/authorize", $payload, $header);

        return $response;
    }
    
    public function callService($method = "GET", $service, $payload, array $header)
    {
        if (empty($service)) {
            throw new Exception("Service uri is required");
        }

        if (empty($payload)) {
            throw new Exception("Request payload is required");
        }
        file_put_contents("application/logs/tigo.txt", $payload);
        $url = self::URL.'/'.self::VERSION.'/'.$service;

        // Get cURL resource
        $curl = curl_init();
        $configs = array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url,
            CURLOPT_USERAGENT => 'shop[dot]bafredo[dot]com',
        );

        if (strtoupper($method) == "POST") {
            $configs[CURLOPT_POST] = 1;
            $configs[CURLOPT_POSTFIELDS] = $payload;
            $configs[CURLOPT_HTTPHEADER] = $header;
        }
        
        // Set some options - we are passing in a useragent too here
        curl_setopt_array($curl, $configs);
        
        // Send the request & save response to $resp
        $resp = json_decode(curl_exec($curl));

        if( ! $resp ) {
            throw new Exception('Error: "' . curl_error($curl) . '" - Code: ' . curl_errno($curl));
        }

        // Close request to clear up some resources
        curl_close($curl);

        return $resp;
    }
}