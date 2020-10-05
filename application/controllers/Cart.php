<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: MasoodUrReh
 * Date: 5/28/2019
 * Time: 10:02 AM
 */

include_once(__DIR__.'/../libraries/OAuth.php');

class Cart extends MY_Controller
{
    private $pesapal_consumer_key;
    private $pesapal_consumer_secret;

    public function __construct()
    {
        parent::__construct();

        // sandbox: https://demo.pesapal.com/api/PostPesapalDirectOrderV4
        // live: https://www.pesapal.com/API/PostPesapalDirectOrderV4
        $this->pesapal_endpoint = 'https://www.pesapal.com/API/PostPesapalDirectOrderV4';
        $this->pesapal_consumer_key = 'gJcjkKhFNaW6t9d/sEZddn4k7Kq+0YR9';
        $this->pesapal_consumer_secret = '2hcYdLOXZ/C8ATS8uDTEb1MjpGk=';
        $this->load->model("AccountModel","AM");
        $this->load->model('ProductModel', 'product');
        $this->load->model("CategoryModel","category");


    }

    public function index()
    {
        $productMenu = $this->product->newArrival(5);
        $this->twig->display('cart',compact('productMenu'));
    }

    public function add()
    {
        $cart = $this->input->post();
      //  print_r($cart); print_r($this->session->userdata('cart')); die;
        $this->session->set_userdata("cart", $cart);
        $this->output->set_content_type("application/json");
        $this->output->set_output(json_encode(array(
            "status" => "success",
            "message" => "Item added into cart."
        )));
    }
    public function addCart()
    {
        $cart = $this->input->get();
        $this->db->where('id', $cart['id']);
        $order = $this->db->get('orders')->row();

         $val = json_decode($order->items);
            $carts =$val->cart;
            $record =array();
          foreach ($carts as $value){
              $record['cart'][]= (array)$value;
          }
        
     //   print_r($record); die;
         if($this->session->userdata("cart") == $record){
           // print_r("check"); die;
            $this->output->set_output(json_encode(array(
                 "status" => "300",
                 "message" => "Item added into cart."
             )));
             
         }else{
            // //  $diff= 
            //   print_r(array_diff_assoc($this->session->userdata("cart") , $record)); die;
              
              
            $this->session->set_userdata("cart", $record);
       
            $this->output->set_output(json_encode(array(
                "status" => "success",
                "message" => "Item added into cart."
            )));
         }
    }
    public function addCartitem()
    {
       
        
        $wish_ids= $this->input->get();
        foreach($wish_ids['ids'] as $wish_id){
            $product_ids[] = $this->AM->get_wishlist($wish_id);
        }
        foreach($product_ids as $val){

            $products[] = $this->category->getProductById($val->product_id);
        } 
    
         foreach ($products as $value){
              $record['cart'][]= $value;
          }
      $this->session->set_userdata("cart", $record);
           // print_r("check"); die;
            $this->output->set_output(json_encode(array(
                 "status" => "success",
                 "message" => "Item added into cart."
             )));
     

    }
    
    public function deletedCart()
    {
        $items = $this->input->get();
        //print_r($items['item']); die;
        
        // $selected= array();
        // $i=0;
        // foreach ($items['item'] as $key => $item){
        //     print_r($item); die;
        //     array_push($selected, $item[$i]);
        //     $i++;
        // } 
     //   print_r($selected); die; 
        // $searchForValue = ',';
        // $carts = $this->session->userdata("cart");


        // if( strpos($items, $searchForValue) !== false ) {
        //     $arr_id =  explode(",",$items);
           
        // }else{
        //     $this->AM->remove_whislist($ids);
        // } 



        $carts = $this->session->userdata("cart");
        
        foreach ($carts['cart'] as $key => $val){
          if (in_array($val['id'],$items['item']))
              {
                  unset($carts['cart'][$key]);
              }
        }
        $newArray['cart']=array_values($carts['cart']);
     //  print_r($newArray); die;
        $this->session->set_userdata("cart", $newArray);
         $this->output->set_output(json_encode(array(
                "status" => "success",
                "message" => "Item removed into cart."
            )));

      
         
    }

    public function load()
    {
        $cart = $this->session->userdata("cart");

        $this->output->set_content_type("application/json");
        $this->output->set_output(json_encode(array(
            "status" => "success",
            "data" => $cart
        )));
    }

    public function flushUserCart($user_id)
    {
        $this->db->where('user_id', $user_id)->delete('carts');
    }

    public function checkout()
    {
        if (! authCheck("user")) {
            redirect("login?href=".urlencode(base_url('cart/checkout')));
        }

        $user = getAuthUser('user');
        $this->flushUserCart($user->getId());

        $cart = $this->session->userdata("cart");
    
      // echo "<pre>"; print_r($cart); die;
       if(isset($cart['cart_checkout'])){
        $cart_checkout = (isset($cart['cart_checkout'])) ? $cart['cart_checkout'] : array();
       }else{
           $cart_checkout = (isset($cart['cart'])) ? $cart['cart'] : array();
           
       }
         // echo "<pre>"; print_r($cart_checkout); die;

        $this->db->insert('carts', array(
            'user_id' => $user->getId(),
            'items' => json_encode(array('cart' => $cart_checkout))
        ));

        // Get shipping address
        $shippingAddress = $this->db->where('user_id', $user->getId())
            ->get('address_books')
            ->row();

        $cities = array(
            'Select City' => 'Select City',
            'Arusha' => 'Arusha',
            'Kilimanjaro' => 'Kilimanjaro',
            'Dar es Salaam' => 'Dar es Salaam',
            'Dodoma' => 'Dodoma',
            'Geita' => 'Geita',
            'Iringa' => 'Iringa',
            'Kagera' => 'Kagera',
            'Katavi' => 'Katavi',
            'Kigoma' => 'Kigoma',
            'Kusini Pemba' => 'Kusini Pemba',
            'Kusini Unguja' => 'Kusini Unguja',
            'Lindi' => 'Lindi',
            'Manyara' => 'Manyara',
            'Mara' => 'Mara',
            'Mbeya' => 'Mbeya',
            'Mjini Magharibi' => 'Mjini Magharibi',
            'Morogoro' => 'Morogoro',
            'Mtwara' => 'Mtwara',
            'Mwanza' => 'Mwanza',
            'Njombe' => 'Njombe',
            'Pwani' => 'Pwani',
            'Rukwa' => 'Rukwa',
            'Ruvuma' => 'Ruvuma',
            'Shinyanga' => 'Shinyanga',
            'Simiyu' => 'Simiyu',
            'Singida' => 'Singida',
            'Songwe' => 'Songwe',
            'Tabora' => 'Tabora',
            'Tanga' => 'Tanga'
        );

        $distircts = $this->AM->get_region();
        $productMenu = $this->product->newArrival(5);
        $this->twig->display('cart/checkout', compact('shippingAddress', 'cities','distircts','cart_checkout','productMenu'));
    }

    public function get_regions($city)
    {
        $city = urldecode($city);
        $regions = json_decode(file_get_contents("assets/json_cache/regions.json"), true);
        $regions = (isset($regions[$city])) ? current($regions[$city]) : array();
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($regions));
    }

    public function save_order($payload)
    {
       // print_r($payload); die;
          if (! authCheck("user")) {
            redirect("login");
        }

        $user = getAuthUser('user');

        // Save shipping address in address book.
        if (isset($payload['save_address']) && $payload['save_address'] == 'Yes') {
            $this->save_shipping_address($user->getId(), $payload['shippingAddress']);
        }

        $this->db->where('user_id', $user->getId());
        $cart = $this->db->get('carts')->row();

        if (! empty($cart)) // Shopping cart session
        {
            $items = json_decode($cart->items);
            $total = 0;
            foreach ($items->cart as $_cart) {
                $total += ($_cart->price * $_cart->quantity);
            }
            $currency = $this->session->userdata('currency');
           $this->load->model('OrderModel');
           $last =  $this->OrderModel->lastOrder();
// print_r($last->invoice); die;
if(isset($last->invoice)){
            $invoice = $last->invoice+1;
        }else{
             $invoice = 1;
        }
            $order = array(
                'user_id' => $user->getId(),
                'items' => $cart->items,
                'total' => $total,
                'currency' => $currency,
                'payment_method' => $payload['payment_method'],
                'order_notes' => $payload['order_notes'],
                'shipping_method' => $payload['shipping_method'],
                'shipping_address' => json_encode($payload['shippingAddress']),
                'order_status' => 'Pending',
                'invoice'=> $invoice,
                'created_at' => date('Y-m-d H:i:s')
            );
            $this->db->insert('orders', $order);

            $order_number = $this->db->insert_id();
            $order['id'] = $order_number;

            // Flush user cart
            $this->flushUserCart($user->getId());
            return (object) $order;

        } else {

            $this->db->where('user_id', $user->getId());
            $this->db->order_by('id', 'DESC')->limit(1);
            $order = $this->db->get('orders')->row();

            return $order;
        }
    }

    public function save_shipping_address($user_id, $shippingAddress)
    {
        $dataAdapter = $this->db->where('user_id', $user_id)->get('address_books');
        if ($dataAdapter->num_rows() > 0) {
            // Update
            $this->db->where('user_id', $user_id);
            $this->db->update('address_books', $shippingAddress);
        } else {
            // Create
            $shippingAddress['user_id'] = $user_id;
            $this->db->insert('address_books', $shippingAddress);
        }
    }

    public function confirm()
    {
        if (empty($_POST)) {
            redirect('cart/checkout');
        }


        $payload = $this->input->post(); //echo"<pre>"; print_r($payload); die;
        $productMenu = $this->product->newArrival(5);
     
        $this->session->set_userdata('formdata', $payload);
        // Save order
        $order = $this->save_order($payload);

        if($order->shipping_method == 'Cash_On_Delivery'){
            $charge = 10000;
        }else  if($order->shipping_method == 'BAFREDO_Savings'){
            $charge = 15000;
        }else  if($order->shipping_method == 'Self_Collection'){
            $charge = 0;
        }else {
            $charge = 0;
        }

        if ($order->payment_method == "tigopesa") {
            $this->twig->display('cart/gateway/tigopesa', compact('order','charge','productMenu'));
        } else if ($order->payment_method == "bank_transfer") {
            $this->twig->display('cart/gateway/bank_transfer', compact('order','charge','productMenu'));
        } else if ($order->payment_method == "pesapal") {
            
            $this->twig->display('cart/gateway/pesapal', compact('order','charge','productMenu'));
        }
    }

    public function invoice($order_number)
    {
        //$this->load->view('invoice');
        $this->db->where('id', $order_number);
        $productMenu = $this->product->newArrival(5);
        $order = $this->db->get('orders')->row();
        $order->invoice =  date("Ymd", strtotime($order->created_at))."00".$order->invoice;
        
        
        if($order->shipping_method == 'Cash_On_Delivery'){
            $charge = 10000;
        }else  if($order->shipping_method == 'BAFREDO_Savings'){
            $charge = 15000;
        }else  if($order->shipping_method == 'Self_Collection'){
            $charge = 0;
        }else {
            $charge = 0;
        }
        if ($order->payment_method == "tigopesa") {
            //$this->twig->display('cart/gateway/tigopesa', compact('order','charge'));
            $val = json_decode($order->items);
            $cart =$val->cart;
            $address= json_decode($order->shipping_address);
            $user = $this->session->userdata("user"); 
            $useraddress = $this->AM->get_user_address_book($user->getId());

            $Shipping_address= json_decode($order->shipping_address);
            $this->twig->display('invoice', compact('order','cart','useraddress','user','address','charge'));
        } else if ($order->payment_method == "bank_transfer") {
         
            $val = json_decode($order->items);
            $cart =$val->cart;
            $address= json_decode($order->shipping_address);
            $user = $this->session->userdata("user"); 
            $useraddress = $this->AM->get_user_address_book($user->getId());

            $Shipping_address= json_decode($order->shipping_address);
            $this->twig->display('invoice', compact('order','cart','useraddress','user','address','charge'));
        } else if ($order->payment_method == "pesapal") {
             $val = json_decode($order->items);
            $cart =$val->cart;
            $address= json_decode($order->shipping_address);
            $user = $this->session->userdata("user"); 
            $useraddress = $this->AM->get_user_address_book($user->getId());

            $Shipping_address= json_decode($order->shipping_address);
            $this->twig->display('invoice', compact('order','cart','useraddress','user','address','charge','productMenu'));
    //        $this->twig->display('cart/gateway/pesapal', compact('order','charge'));
        }
    }

    public function pay()
    {
        if (empty($_POST)) {
            redirect('cart/checkout');
        }

        $order_number = $this->input->post('order_number');
        $customer_tigo_account = $this->input->post('account_number');

        // Load order
        $this->db->where('id', $order_number);
        $order = $this->db->get('orders')->row();

        // Get user
        $user = getAuthUser('user');

        // Load Tigo library
        $this->load->library('Tigo');
        $tigo = new Tigo();

        if($order->shipping_method == 'Cash_On_Delivery'){
            $charge = 10000;
        }else  if($order->shipping_method == 'BAFREDO_Savings'){
            $charge = 15000;
        }else  if($order->shipping_method == 'Self_Collection'){
            $charge = 0;
        }else {
            $charge = 0;
        }
        try {
            $accessToken = $tigo->genAccessToken();
            $payload = array(
                "transactionRefId" => "BFD{$order_number}",
                "MasterMerchant" => array(
                    "account" => "25565000242",
                    "pin" => "2010",
                    "id" => "BAFREDO"
                ),
                "Subscriber" => array(
                    "account" => $customer_tigo_account, // String - MFS Account number (msisdn) of the paying subscriber (account to debit)
                    "countryCode" => "255", // String - Country code dialing prefix
                    "country" => "TZA", // String - Three letter country code
                    "firstName" => $user->getName(), // String - First name of the subscriber
                    "lastName" => "N/A", // String - Last name of the subscriber
                    "emailId"  => $user->getEmail() // String - [optional] Email address
                ),
                "redirectUri" => base_url('cart/tigopesaCallback'), // String - Redirection URI to redirect after completing the payment
                "language" => "eng",
                "originPayment" => array(
                    "amount" => $order->total + $charge, // Decimal - Total amount in the currency of the original merchant payment
                    "currencyCode" => $order->currency, // String - Currency code of the payment
                    "tax" => "0.00", // Decimal - Tax for the transaction in the origin currency
                    "fee" => "0.00" // Decimal - Fee applied by the Master Merchant for the transaction in the origin currency. This fee is charged from the subscriber and will be shown to the subscriber. If no fee has been applied the field can be set to 0
                ),
                "exchangeRate" => "1", // Decimal - [optional] Exchange rate between the origin currency (currency of the sending country) and local currency (currency of the receiving country)
                "LocalPayment" => array(
                    "amount" => $order->total + $charge, // Decimal - Total amount in the local currency of the paying subscriber
                    "currencyCode" => $order->currency // String - Currency code of the MFS account of the paying subscriber (local currency)
                )
            );
            $resp = $tigo->pay($payload, $accessToken);

            file_put_contents("application/logs/tigo-".date("Y-m-d").".txt", json_encode($payload) . PHP_EOL, FILE_APPEND);
            // Redirect to Tigopesa
            redirect($resp->redirectUrl);

        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function tigopesaCallback()
    {
        $user = getAuthUser('user');
        $productMenu = $this->product->newArrival(5);
        if (empty($user)) {
            show_error("No active user session found", 402, "Authentication Error");
        }

        $payload = $this->input->get();
        file_put_contents("application/logs/tigo-callback-".date("Y-m-d").".txt", json_encode($payload) . PHP_EOL, FILE_APPEND);
        if ($payload['trans_status'] == 'success') {
            $order_number = str_replace('BFD', '', $payload['transaction_ref_id']);
            $this->db->set('order_status', 'Confirm');
            $this->db->set('callback_log', json_encode($payload));
            $this->db->where('id', $order_number);
            $this->db->update('orders');
            // Flush user cart
            $this->flushUserCart($user->getId());

            $this->twig->display('cart/success', compact('payload','productMenu'));
        } else {
            // Fail.
            $this->twig->display('cart/fail', compact('payload','productMenu'));
        }
    }

    public function pesapal()
    {
        if (empty($_POST)) {
            redirect('cart/checkout');
        }
        
        $productMenu = $this->product->newArrival(5);
        $order_number = $this->input->post('order_number');

        // Load order
        $this->db->where('id', $order_number);
        $order = $this->db->get('orders')->row();
        $shippingAddress = json_decode($order->shipping_address);

        if($order->shipping_method == 'Cash_On_Delivery'){
            $charge = 10000;
        }else  if($order->shipping_method == 'BAFREDO_Savings'){
            $charge = 15000;
        }else  if($order->shipping_method == 'Self_Collection'){
            $charge = 0;
        }else {
            $charge = 0;
        }
        // Assign Variables
        $token = $params = NULL;
        $consumer_key = $this->pesapal_consumer_key;//Register a merchant account on
        //demo.pesapal.com and use the merchant key for testing.
        //When you are ready to go live make sure you change the key to the live account
        //registered on www.pesapal.com!
        $consumer_secret = $this->pesapal_consumer_secret;// Use the secret from your test
        //account on demo.pesapal.com. When you are ready to go live make sure you
        //change the secret to the live account registered on www.pesapal.com!
        $signature_method = new OAuthSignatureMethod_HMAC_SHA1();
        $iframelink = $this->pesapal_endpoint;

        // Assign Form Details
        $_POST['amount'] = $order->total+$charge;
        $_POST['description'] = 'Description: '.$order->order_notes;
        $_POST['type'] = 'MERCHANT';
        $_POST['reference'] = $order->id;
        $_POST['first_name'] = $shippingAddress->first_name;
        $_POST['last_name'] = $shippingAddress->last_name;
        $_POST['email'] = $shippingAddress->email;
        $_POST['phonenumber'] = $shippingAddress->phone;

        $amount = number_format($_POST['amount'], 2);//format amount to 2 decimal places
        $desc = $_POST['description']; // required.
        $type = "MERCHANT"; //default value = MERCHANT
        $reference = $_POST['reference'];//unique order id of the transaction, generated by merchant
        $first_name = $_POST['first_name']; //[optional]
        $last_name = $_POST['last_name']; //[optional]
        $email = $_POST['email']; //ONE of email or phonenumber is required
        $phonenumber = $_POST['phonenumber']; //ONE of email or phonenumber is required
        $currency = $order->currency;

        // Construct the post_xml variable
        $callback_url = base_url("cart/pesapalCallback/{$reference}");//redirect url, the page that will handle the response from pesapal.
        $post_xml = '<?xml version="1.0" encoding="utf-8"?>
            <PesapalDirectOrderInfo 
                xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" 
                xmlns:xsd="http://www.w3.org/2001/XMLSchema" 
                xmlns="http://www.pesapal.com"  
                Currency="'.$currency.'" 
                Amount="'.$amount.'" 
                Description="'.$desc.'" 
                Type="'.$type.'" 
                Reference="'.$reference.'" 
                FirstName="'.$first_name.'" 
                LastName="'.$last_name.'" 
                Email="'.$email.'" 
                PhoneNumber="'.$phonenumber.'">
            </PesapalDirectOrderInfo>';
        file_put_contents("application/logs/pesapal-".date("Y-m-d").".txt", $post_xml . PHP_EOL, FILE_APPEND);
        $post_xml = htmlentities($post_xml);
        
        // Construct the OAuth Request url
        $consumer = new OAuthConsumer($consumer_key, $consumer_secret);
        //post transaction to pesapal
        $iframe_src = OAuthRequest::from_consumer_and_token($consumer, $token, "GET", $iframelink, $params);
        $iframe_src->set_parameter("oauth_callback", $callback_url);
        $iframe_src->set_parameter("pesapal_request_data", $post_xml);
        $iframe_src->sign_request($signature_method, $consumer, $token);
        // Display the iframe
        $this->twig->display('cart/gateway/pesapal_frame', compact('iframe_src','productMenu'));
    }

    public function pesapalCallback($reference)
    {
        file_put_contents("application/logs/pesapal-callback-".date("Y-m-d").".txt", $reference . PHP_EOL, FILE_APPEND);
        file_put_contents("application/logs/pesapal-callback-".date("Y-m-d").".txt", http_build_query($_GET) . PHP_EOL, FILE_APPEND);
        
        $_GET['pesapal_notification_type'] = "CHANGE";
        redirect("cart/pesapalIpn/{$reference}?".http_build_query($_GET));
    }

    public function pesapalIpn($reference = NULL)
    {
        file_put_contents('application/logs/pesapal-ipn.json', json_encode($_GET) . PHP_EOL, FILE_APPEND);
        $consumer_key = $this->pesapal_consumer_key;//Register a merchant account on
        //demo.pesapal.com and use the merchant key for testing.
        //When you are ready to go live make sure you change the key to the live account
        //registered on www.pesapal.com!
        $consumer_secret = $this->pesapal_consumer_secret;// Use the secret from your test
        //account on demo.pesapal.com. When you are ready to go live make sure you
        //change the secret to the live account registered on www.pesapal.com!
        $statusrequestAPI = 'https://www.pesapal.com/api/querypaymentstatus';//change to
        //https://www.pesapal.com/api/querypaymentstatus' when you are ready to go live!

        // Parameters sent to you by PesaPal IPN
        $pesapalNotification=$_GET['pesapal_notification_type'];
        $pesapalTrackingId=$_GET['pesapal_transaction_tracking_id'];
        $pesapal_merchant_reference=$_GET['pesapal_merchant_reference'];

        /**
         * The status of the payment is returned as follows:

            pesapal_response_data =<PENDING|COMPLETED|FAILED|INVALID>
            A return value of INVALID indicates that the transaction with the Reference you provided could not be found. If you receive a return value of PENDING, you will have to query PesaPal again, until you receive COMPLETED or FAILED as the response.
         */
        if($pesapalNotification=="CHANGE" && $pesapalTrackingId!='') 
        {
            $token = $params = NULL;
            $consumer = new OAuthConsumer($consumer_key, $consumer_secret);
            $signature_method = new OAuthSignatureMethod_HMAC_SHA1();

            //get transaction status
            $request_status = OAuthRequest::from_consumer_and_token($consumer, $token, "GET", $statusrequestAPI, $params);
            $request_status->set_parameter("pesapal_merchant_reference", $pesapal_merchant_reference);
            $request_status->set_parameter("pesapal_transaction_tracking_id",$pesapalTrackingId);
            $request_status->sign_request($signature_method, $consumer, $token);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $request_status);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HEADER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            if(defined('CURL_PROXY_REQUIRED')) if (CURL_PROXY_REQUIRED == 'True')
            {
                $proxy_tunnel_flag = (defined('CURL_PROXY_TUNNEL_FLAG') && strtoupper(CURL_PROXY_TUNNEL_FLAG) == 'FALSE') ? false : true;
                curl_setopt ($ch, CURLOPT_HTTPPROXYTUNNEL, $proxy_tunnel_flag);
                curl_setopt ($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
                curl_setopt ($ch, CURLOPT_PROXY, CURL_PROXY_SERVER_DETAILS);
            }

            $response = curl_exec($ch);
            file_put_contents('application/logs/pesapal-ipn-rsp.json', json_encode($response) . PHP_EOL, FILE_APPEND);
            
            $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            $raw_header  = substr($response, 0, $header_size - 4);
            $headerArray = explode("\r\n\r\n", $raw_header);
            $header      = $headerArray[count($headerArray) - 1];

            //transaction status
            $elements = preg_split("/=/",substr($response, $header_size));
            $status = $elements[1];

            curl_close ($ch);
file_put_contents('application/logs/pesapal-ipn-rsp.json', $status . PHP_EOL, FILE_APPEND);
            // UPDATE YOUR DB TABLE WITH NEW STATUS FOR TRANSACTION WITH pesapal_transaction_tracking_id $pesapalTrackingId
            $productMenu = $this->product->newArrival(5);
            if($status == "COMPLETED")
            {
                $this->db->set("order_status", "Confirm");
                $this->db->where("id", $reference);
                $this->db->update("orders");
                // Success
                $payload["transaction_ref_id"] = $pesapalTrackingId;
                $payload["verification_code"] = $reference;
                $this->twig->display('cart/success', compact('payload'));
            
                
            } else if ($status == "PENDING") {
                
                $callback = 1;
                if (isset($_GET["cb"]) && !empty($_GET["cb"])) {
                    $callback = (int) $_GET["cb"];
                    $callback += 1;
                }
                $_GET["cb"] = $callback;
                
                if ($callback >= 5) 
                {
                    $payload["transaction_ref_id"] = $pesapalTrackingId;
                    $payload["verification_code"] = $reference;
                    $payload["check_status"] = base_url("cart/pesapalIpn/{$reference}?".http_build_query($_GET));
                   
                    $this->twig->display('cart/pending', compact('payload','productMenu'));
                    
                } else {
                    
                    redirect(base_url("cart/pesapalIpn/{$reference}?".http_build_query($_GET)));    
                }
                
            } else {
                
                // Fail.
                $payload["error_code"] = "";
                $this->twig->display('cart/fail', compact('payload','productMenu'));
            }
        }
    }
}