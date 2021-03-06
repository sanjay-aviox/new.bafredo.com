<?php use Entity\User;

defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: masoodurrehman42@gmail.com
 * Date: 4/25/2019
 * Time: 10:08 PM
 */

class Account extends MY_Controller
{
    public $data;

    public function __construct()
    {
        parent::__construct();

        if (! authCheck("user")) {
            redirect("login");
        }

        $this->load->library('doctrine');
        $this->load->model("AccountModel","AM");
        $this->load->model('ProductModel', 'product');
        $this->load->model("CategoryModel","category");

        $this->data['active_link'] = $this->uri->segment(2);
    }

    public function index()
    {
        $post = $this->input->post();
        $current_user = getAuthUser('user');
        $this->data['productMenu']  = $this->product->newArrival(5);    
        $this->data['verfiy'] = $this->AM->verfiymail($current_user->getId());
        $this->data['user'] = $current_user;
            if(!empty($post))
            {
                if($current_user->getEmail() != $this->input->post('email') ){
                    $post['is_verified'] = '0';
                }

                $this->data['user'] = $this->AM->update_account($post,$current_user->getId());
                $em = $this->doctrine->em;
                $user = $em->getRepository("Entity\User")->findOneByEmail($this->input->post('email'));
                $this->session->set_userdata("user", $user);
                $this->data['user'] = $user;
                $this->session->set_flashdata('msg', "Information  Updated Successfully");
                $this->data['success'] = "done";
            }

        $this->data['page'] = 'account';
        $this->data['msg'] = $this->session->flashdata('msg');
        $this->twig->display('user/profile', $this->data ,compact('productMenu'));
    }
    public function order_history()
    {
        $this->data['user'] = getAuthUser('user');

        $this->data['page'] = 'order_history';
        $this->load->model('OrderModel');
        $segments = $this->uri->segment_array();
        $this->data['productMenu'] = $this->product->newArrival(5);
        if(!empty($segments[3]))
        {
        	$this->session->set_flashdata('msg', "Order Deleted Successfully");
            $this->OrderModel->update_record($segments[3]);
            $this->data['success'] = "done";
            unset($segments[3]);
            redirect(base_url(implode("/",$segments)));

        }

        $this->data['orders'] = $this->OrderModel->allByUserId($this->data['user']->getId());
     //echo "<pre>"; print_r( $this->data['orders']); die;
        $this->data['msg'] = $this->session->flashdata('msg');
        $this->twig->display('user/profile', $this->data,compact('productMenu'));
    }
    public function delete_history_record()
    {

    }
    public function remove_history()
    {   
        $ids =$this->input->post('id');
        $searchForValue = ',';
        $this->data['user'] = getAuthUser('user');
        

        if( strpos($ids, $searchForValue) !== false ) {
        	
            $arr_id =  explode(",",$ids);
        
            foreach ($arr_id as $id){

                $this->AM->remove_whislist($id ,$this->data['user']->getId());
            }
        }else{
   //echo $ids;
 //die;           
  $this->AM->remove_whislist($ids,$this->data['user']->getId());
        } 
 
        redirect(base_url('account/wish_list'));
    }
    public function upload_profile()
    {

        $uesr = getAuthUser('user');
        $image_path = FCPATH."assets/front/image/users/";
        $filename = $_FILES["file"]["name"];
        $this->load->library('upload');

        $config['upload_path'] = $image_path ;
        $config['allowed_types'] = 'png|jpg|gif';
        $config['max_size'] = 2048;
        $config['encrypt_name'] = TRUE;
        $this->upload->initialize($config);


        if (!empty($filename)) {
                if (!$this->upload->do_upload('file')) {
                    $data['errors'] = $this->upload->display_errors();
                    echo  json_encode(array("status"=>"errors","name"=>$data['errors']));
                } else {
                    $favicon = $this->upload->data("file_name");
                    if(!empty($uesr->getUserImg()))
                    {
                        if(file_exists($image_path.$uesr->getUserImg()))
                        {
                            unlink($image_path.$uesr->getUserImg());

                        }
                    }
                    $post_array = array("user_img"=>$favicon);
                    $this->AM->update_account_profile($post_array,$uesr->getId());
                    $uesr->setUserImg($favicon);
                    $this->session->set_userdata("user", $uesr);
                    echo  json_encode(array('status'=>"success","name"=>$favicon));
                }
        }
    }

    public function address_book()
    {

        $this->data['user'] = getAuthUser('user');
        $this->data['page'] = 'address_book';
        $this->data['addressBook'] = $this->AM->get_user_address_book($this->data['user']->getId());
         $this->data['homeAddress'] = $this->AM->get_user_home_address_book($this->data['user']->getId());
        $post = $this->input->post();
      
        $this->data['productMenu'] = $this->product->newArrival(5);
        if(!empty($post))
        {
          
            $this->data['user'] = $this->AM->update_address_book($post,$this->data['user']->getId());
            $data['success'] ="done";
            $this->session->set_flashdata('msg', "Address Updated Successfully");
            redirect("account/address_book");
        }

        $this->data['distircts'] = $this->AM->get_region();
        $this->data['msg'] = $this->session->flashdata('msg');
        $this->twig->display('user/profile', $this->data, compact('data','productMenu'));
    }

    public function home_book()
    {

        $this->data['user'] = getAuthUser('user');
        $this->data['page'] = 'address_book';
        $this->data['homeAddress'] = $this->AM->get_user_home_address_book($this->data['user']->getId());
        $post = $this->input->post();
        $this->data['productMenu'] = $this->product->newArrival(5);
        if(!empty($post))
        {
            // print_r($post); die;
            $this->data['user'] = $this->AM->update_home_address_book($post,$this->data['user']->getId());
            $data['success'] ="done";
            $this->session->set_flashdata('msg', "Home Address Updated Successfully");
            redirect("account/address_book");
        }

        $this->data['distircts'] = $this->AM->get_region();
        $this->data['msg'] = $this->session->flashdata('msg');
        $this->twig->display('user/profile', $this->data, compact('data','productMenu'));
    }
    public function home_to_shipping_address()
    {

        $this->data['user'] = getAuthUser('user');
        $this->data['page'] = 'address_book';
        $home = $this->AM->get_user_home_address_book($this->data['user']->getId());
      //  echo "<pre>"; print_r($home->id); die;
        $post=[
               
                'first_name' => $home->first_name,
                'last_name' => $home->last_name,
                'email' => $home->email,
                'phone'=> $home->phone,
                'address' => $home->address,
                'city' => $home->city,
                'country' => $home->country,
                'region' => $home->region,
                'postal_code' => $home->postal_code

        ];
        $this->data['user'] = $this->AM->update_address_book($post,$this->data['user']->getId());
        echo  json_encode(array('status'=>"success"));
    }

    public  function getregion(){
        $cityname = $this->input->post('name');
        echo $this->AM->getcityregion($cityname);
    }
    public function wish_list()
    {
        $this->data['user'] = getAuthUser('user');
        $this->data['page'] = 'wish_list';
        $this->data['result'] = $this->AM->get_wish_list($this->data['user']->getId());
        $this->data['productMenu'] = $this->product->newArrival(5);
     //   print_r($this->data['productMenu']);
        $this->twig->display('partials/user/wish_list', $this->data,compact('productMenu'));
    }

    public function changePassword()
    {
        $this->data['user'] = getAuthUser('user');
        $this->data['page'] = 'password';

        $password = $this->session->flashdata('wrongpassword');
         $this->data['productMenu'] = $this->product->newArrival(5);
        $redirectAfterLogin = $this->input->get('href');
        $this->data['msg'] = $this->session->flashdata('msg');
        $this->data['error'] = $this->session->flashdata('error');
        $this->twig->display('user/profile', $this->data, compact('redirectAfterLogin','password','productMenu'));
    }
    public function changePasswordProcess()
    {
        $this->data['user'] = getAuthUser('user');
        $this->data['page'] = 'password';
       
        $user = getAuthUser('user');
        $old_pass= $this->input->post('oldpassword'); 

         $redirectAfterLogin = "?href=".urlencode($redirectAfterLogin);
        
        if (password_verify($old_pass, $user->getPassword())) {

            $data=array(
                'password' =>  password_hash($this->input->post('newpassword'),PASSWORD_DEFAULT),
                'oldpass'  =>  password_hash($this->input->post('oldpassword'),PASSWORD_DEFAULT)
            );
            $this->AM->update_password($data,$user->getId());
              $this->session->set_flashdata('msg', "Password Updated Successfully");
            redirect('account/changePassword');
          
        }else{
            $this->session->set_flashdata('error', 'Please Enter correct current password');
            redirect('account/changePassword');

        }
    }

    public function verifyemail(){

        $data = $this->input->post();  
        
            $otp = rand(10000,100000);
            $this->session->set_userdata("otp", $otp);
            $current_user = getAuthUser('user');
           // print_r($current_user->getid());
            $post['is_verified'] = '1';
               
                $config = Array(
                    'protocol' => 'sendmail',
                    'smtp_host' => 'smtp.gmail.com',
                    'smtp_port' => 465,
                    'smtp_user' => 'redexsolutionspvtlmt@gmail.com',
                    'smtp_pass' => 'rajinder@1995',
                    // 'mailtype'  => 'html',
                    // 'charset'   => 'iso-8859-1'
                );
                $this->load->library('email', $config);
                $this->email->from('info@bafredo.com', 'Bafredo');
                //$this->email->to("monuthakur1217@gmail.com");
                $this->email->to($data['email']);
    
                $this->email->subject('Verify your email');
                $this->email->message("Your verification code ".$otp);
                if($this->email->send()){

	                $arr = array('stattus'=>'success','message'=>'Verification Code is send on yoyur email');
	                echo json_encode($arr);
                }else{
                    $arr = array('stattus'=>'error','message'=>'Something went wrong');
                    echo json_encode($arr);
                }
    
                
       }
    public function confirmOTP(){
        $data = $this->input->post();  
        $current_user = getAuthUser('user');
        $post['is_verified'] = '1';
        if($this->session->userdata('otp') == $data['otp']){
             
            $this->AM->update_account($post,$current_user->getId());
             $arr = array('stattus'=>'success','message'=>'OTP verified.');
             
        }else{
              $arr = array('stattus'=>'failure','message'=>'OTP not verified.');
        } 
       
        echo json_encode($arr);         
    }

    public function resetPassword()
    {     
        
        $this->twig->display('user/resetpassword');
    }
    public function resetPasswordProcess()
    { 
        //echo$this->input->post('password'); die;
        $current_user = getAuthUser('user');
        $post = $this->input->post('password');
        $pass = password_hash($post,PASSWORD_DEFAULT);
       //echo $pass; die;
        $data=array(
         'password' => $pass
        );
        
        $this->data['user'] = $this->AM->update_password($data,$current_user['id']);
        
        $em = $this->doctrine->em;
        $user = $em->getRepository("Entity\User")->findOneByEmail($current_user['email']);
       // print_r($user); die;
        $this->session->set_userdata("user", $user);
        $this->data['user'] = $user;
        redirect("account");
    }

    public function test()
    {
        $productMenu = $this->product->newArrival(4);
        $this->twig->display("page/how_to_buy" ,compact('productMenu'));
    }

}