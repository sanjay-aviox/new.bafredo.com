<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Paymentgateways extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        if (!authCheck("admin")) {
            redirect("admin/login");
        }
        $this->load->model('PaymentGatewaysModel',"PGM");
    }
    public function pesapal()
    {
        $post = $this->input->post();
        $data = $this->PGM->getpaymentgateway("pesapal");
        $data["credentials"] = json_decode($data["credentials"],true);
        $uri_string = uri_string();
        if(!empty($post))
        {
           $credentials  = array();
           $i = 0;
           foreach ($post["credentials"] as $credential)
           {
               $credentials[$post["credentials_keys"][$i]]=$credential;
               $i = $i+1;
           }
           $sotre_array["credentials"]  = json_encode((object)$credentials);
           if(!empty($post["status_data"]))
           {
               $store_array["status"]  = 1;
           }else{
               $store_array["status"]  = 0;
           }
           $data = $this->PGM->update_settings($store_array,"pesapal");
           $data["success"] = 1;
           $data["credentials"] = json_decode($data["credentials"],true);

        }
        $this->twig->display('admin/paymentgateway', compact( 'data','uri_string'));

    }
    public function tigopesa(){

    }
}
