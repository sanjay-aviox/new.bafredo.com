<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 5/12/2019
 * Time: 3:47 AM
 */
class PaymentGatewaysModel extends CI_Model
{
    public function __construct()
    {

    }

    public function getpaymentgateway($name)
    {
        $this->db->where('name',$name);
        return $this->db->get('payment_gateways')->result_array()[0];
    }
    public function update_settings($data,$name)
    {
        $this->db->where('name',$name);
        $this->db->update("payment_gateways",$data);
        return $this->getpaymentgateway($name);

    }
}