<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 6/24/2019
 * Time: 12:44 AM
 */
class Wishlist extends MY_Controller
{

       public function __construct()
    {
        parent::__construct();

      
        $this->load->model("AccountModel","AM");
        
    }
    public function add()
    {
        
        $current_user = getAuthUser('user');

        if(isset($current_user)){
        
            $wishlist =$this->AM->get_wish_list( $current_user->getId());
            $this->session->set_userdata("wishlist", $wishlist);
            $product_id = $this->input->post('product_id');
            $dataAdapter = $this->db->where("product_id", $product_id)
                                ->where("user_id", $current_user->getId())
                                ->get("wishlist");
            if ($dataAdapter->num_rows() <= 0) {
                $this->db->insert("wishlist", array(
                    "user_id" => $current_user->getId(),
                    "product_id" => $product_id
                ));
            
        
            $this->output->set_content_type('application/json');
            $this->output->set_output(json_encode(array(
                'status' => 'success',
                'message' => 'Product added to Wishlist'
            )));
        }else{
             $this->output->set_content_type('application/json');
            $this->output->set_output(json_encode(array(
                'status' => 'success',
                'message' => 'Already Added'
            )));
        }
    }else{
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode(array(
            'status' => 'cancel',
            'message' => 'Login'
        )));

    }
}


      public function load()
    {
         $current_user = getAuthUser('user');
        
         $wishlist =$this->AM->get_wish_list( $current_user->getId());
        $this->output->set_content_type("application/json");
        $this->output->set_output(json_encode(array(
            "status" => "success",
            "data" => $wishlist
        )));
    }

}