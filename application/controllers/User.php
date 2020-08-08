<?php defined('BASEPATH') OR exit('No direct script access allowed');

class User extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        if (! authCheck("user")) {
            redirect("login");
        }

        $this->load->library('doctrine');
        $this->load->model('ProductModel', 'product');
    }

    public function profile()
	{
//        $user = $this->doctrine->em->getRepository("Entity\User")->findAll();
        $user = getAuthUser("user");
        $productMenu = $this->product->newArrival(4);
        $this->twig->display('user/profile', compact('user','productMenu'));
    }

    public function logout()
    {
        $this->session->unset_userdata("isUserLoggedin");
        $this->session->unset_userdata("user");

        redirect(base_url("home"));
    }
    
    public function userlogout()
    {
        $this->session->unset_userdata("isUserLoggedin");
        $this->session->unset_userdata("user");

        $this->output->set_content_type("application/json");
        $this->output->set_output(json_encode(array(
            "status" => "success"
        )));
    }
    public function getFile()
    {
        $this->data['user'] = getAuthUser('user');
        $date= explode("-",$this->input->get()['x']);
        
        $this->load->model('OrderModel');
        $orders = $this->OrderModel->allByDate($this->data['user']->getId(),$date);
        
          $option=array();
     
        foreach ($orders as $order){
           
              array_push($option,'<tr>
                   <td>'.$order->id.'</td>
                   <td>'.$order->order_status.'</td>
                   <td>'.$order->currency.'&nbsp'.$order->total.'</td>
                   <td>'.$order->created_at.'</td></tr><br>');
        
             
        }
    
        $this->output->set_content_type("application/json");
        $this->output->set_output(json_encode(array(
            "status" => "success",
            "data"  => $option
        )));

}
 
}