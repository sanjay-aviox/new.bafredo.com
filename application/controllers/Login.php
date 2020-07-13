<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->library('doctrine');
        $this->load->model("AccountModel","AM");
        
    }

    public function index()
    {
        $url = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH);
       // echo $url;
        $parsed = parse_url($url);
        $path = $parsed['path'];
        $path_parts = explode('/', $path);
       // print_r($path_parts); die;
       // $desired_output = $path_parts[3];

    if($path_parts[3] == 'detail'){
        $this->session->set_userdata( "previousUrl" , $_SERVER['HTTP_REFERER'] );
    }

        $error = $this->session->flashdata('item');
        //print_r($error);die;
        $redirectAfterLogin = $this->input->get('href');

        $this->twig->display('login', compact('redirectAfterLogin','error'));
    }

  
    public function process()
    {
        $email = $this->input->post('email');
        $redirectAfterLogin = $this->input->post('redirectAfterLogin');
       
        $em = $this->doctrine->em;
        if(!preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^",$email)){ 
            $user = $em->getRepository("Entity\User")->findOneByName($email);
        }else{ 
            $user = $em->getRepository("Entity\User")->findOneByEmail($email);
        } 
        
     
        //$user = $this->AM->loginProcess($email);
        // echo "<pre>";
         // print_r($user); die;

        if (! empty($user)) {
            $password = $this->input->post('password');
                // match password
                if (password_verify($password, $user->getPassword())) {
                    $this->session->set_userdata("isUserLoggedin", 1);
                    $this->session->set_userdata("user", $user);
                   // $this->session->set_userdata("userids", $wishlistCount);
 
                    // if (!empty($redirectAfterLogin)) {
                    //     redirect($redirectAfterLogin);
                    // } else {
                    //     redirect("account");
                    // }
                  // echo $this->session->userdata('previousUrl'); die;
                     if (!empty($this->session->userdata('previousUrl'))) {
                        redirect($this->session->userdata('previousUrl'));
                    } else {
                        redirect("account");
                    }
                }else{
                    $this->session->set_flashdata('item','Please confirm password type.');
                }

            $redirectAfterLogin = "?href=".urlencode($redirectAfterLogin);
        }else{
            $this->session->set_flashdata('item','Incorrect Email or username or password.');
        }
       // $this->session->set_userdata('error', 'username or email or password is incorrect.');

        redirect("login".$redirectAfterLogin);
    }

    public function LoginprocesswithOldpassword()
    {
         
        $email = $this->input->post('email');
        
        $em = $this->doctrine->em;
        if(!preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^",$email)){ 
            $user = $em->getRepository("Entity\User")->findOneByName($email);
        }else{ 
            $user = $em->getRepository("Entity\User")->findOneByEmail($email);
        } 
       
        
        $user = $this->AM->loginProcess($email);
       
        if (! empty($user)) {
            $password = $this->input->post('password');

                if (password_verify($password, $user['oldpass'])) {

                    $this->session->set_userdata("isUserLoggedin", 1);
                    $this->session->set_userdata("user", $user);
                   // $this->session->set_userdata("userids", $wishlistCount);

                    if (!empty($redirectAfterLogin)) {
                        redirect($redirectAfterLogin);
                    } else {
                        redirect("reset-password");
                    }
                }else{
                    $this->session->set_flashdata('password','Please confirm password.');
                     redirect('forgot-password');
                }

            $redirectAfterLogin = "?href=".urlencode($redirectAfterLogin);
        }else{

           $this->session->set_flashdata('item','No user exist with this email/username');
            redirect('forgot-password');
        }
    }



    
    // public function forgotview()
    // {
    //     $this->twig->display('auth/forgot');
    // }

    /**
     * Social login handling.
     */
    public function facebook()
    {
        $name = $this->input->post('userName');
        $email = $this->input->post('email');
        $password = "demouser";
        $href = $this->input->get('href');

        $em = $this->doctrine->em;
        $user = $em->getRepository("Entity\User")->findOneByEmail($email);

        $response = array();
        if (! empty($user))
        {
            // match password
            if (password_verify($password, $user->getPassword()))
            {
                $this->session->set_userdata("isUserLoggedin", 1);
                $this->session->set_userdata("user", $user);
                $response = array(
                    "status" => "success", 
                    "message" => "successfully loggedin",
                    "href" => $href
                );
            }
            else
            {
                $this->update($user->id, $password);

                $this->session->set_userdata("isUserLoggedin", 1);
                $this->session->set_userdata("user", $user);

                $response = array(
                    "status" => "success", 
                    "message" => "successfully update password",
                    "href" => $href
                );
            }
        }
        else
        {
            $this->register($name, $email, $password);
            $response = array(
                "status" => "success", 
                "message" => "successfully create account",
                "href" => $href
            );
        }

        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($response));
    }

    public function register($name, $email, $password)
    {
        $em = $this->doctrine->em;

        $user = new Entity\User();
        $user->setName($name);
        $user->setEmail($email);
        $user->setPassword($password);

        $em->persist($user);
        $em->flush();

        $this->session->set_userdata("isUserLoggedin", 1);
        $this->session->set_userdata("user", $user);
    }

    public function update($id, $password)
    {
        $em = $this->doctrine->em;

        $user = new Entity\User();
        $user->setId($id);
        $user->setPassword($password);

        $em->merge($user);
        $em->flush();
    }
    public function forgotpassword(){

        $userexist = $this->session->flashdata('item');
        $password = $this->session->flashdata('password');
       
        $redirectAfterLogin = $this->input->get('href');

       
        $this->twig->display('user/forget',compact('redirectAfterLogin','userexist','password'));
    }
    public function send(){
         $sender = 'monuaviox@gmail.com';
        // $recipient = 'you@yourdomain.tld';

        // $subject = "php mail test";
        // $message = "php test message";
        // $headers = 'From:' . $sender;

        // if (mail($recipient, $subject, $message, $headers))
        // {
        //     echo "Message accepted";
        // }
        // else
        // {
        //     echo "Error: Message not accepted";
        // }
                 $config = Array(
                'protocol' => 'smtp',
                'smtp_host' => 'smtp.gmail.com',
                'smtp_port' => 25,
                'smtp_user' => 'redexsolutionspvtlmt@gmail.com',
                'smtp_pass' => 'rajinder@1995',
                'mailtype'  => 'html',
                'charset'   => 'iso-8859-1'
            );

           
            $this->load->library('email', $config);
            $this->email->from('info@bafredo.com', 'Bafredo');
            $this->email->to($sender);

            $this->email->subject('Forgot Password BAFREDO Account');
            $this->email->message("Your New Password ".$password);

            $this->email->send();
      
    }
    public function forgotpasswordProcess(){
        $this->load->model('AccountModel');
        $mail = $this->input->post('email');
        $check = $this->AccountModel->forgotpassword($mail);
        if(!empty($check)){
           $password = rand();
           $newpassword =  password_hash($password, PASSWORD_DEFAULT);
           $this->AccountModel->updatepassword($mail,$newpassword,$password);

             $config = Array(
                'protocol' => 'smtp',
                'smtp_host' => 'smtp.gmail.com',
                'smtp_port' => 567,
                'smtp_user' => 'redexsolutionspvtlmt@gmail.com',
                'smtp_pass' => 'rajinder@1995',
                'mailtype'  => 'html',
                'charset'   => 'iso-8859-1'
            );

           
            $this->load->library('email', $config);
            $this->email->from('info@bafredo.com', 'Bafredo');
            $this->email->to($mail);

            $this->email->subject('Forgot Password BAFREDO Account');
            $this->email->message("Your New Password ".$password);

            $this->email->send();

           


             $this->session->set_flashdata('item','Please check your Email to retrieve the password.');
             redirect("login");
         }else{
            $this->session->set_flashdata('item','Sorry, the Email you have entered does not exist!');
            redirect("login");
        }
    }
    public function confirmPhone(){
        $this->twig->display('user/confirm');
    }
    public function getSecurityQuetion(){
        $this->load->model('AccountModel');
        $phone = $this->input->get('phone');
        
        $questions = $this->AccountModel->getQuestion($phone);

        $option=array();

        foreach ($questions as $val){
           
              array_push($option,'<label data-error="wrong" data-success="right" for="orangeForm-name">'.$val->question.'</label><input type="text" id="answer" required name="answer['.$val->id.']" data-num = "'.$val->user_id.'" data-id="'.$val->id.'" class="form-control validate answer">');
        
             
        }
         $arr = array('data'=>$option,'msg'=>'200');
                echo json_encode($arr);

       // $this->twig->display('user/question', compact('questions'));
    }
    public function matchQuestion(){
        $answers = $this->input->post();
        $this->load->model('AccountModel');
        $questions = $this->AccountModel->matchQues($answers['id']);
        $i=0;
        foreach ($questions as $key => $value) {
            if($answers['answer'][$value->id] != $value->answer){
              $i++;
            }
       }
      // echo $i; die;
       if($i==0){
           $detail = $this->AccountModel->getDetail($answers['id']);
         // print_r($detail); die;
            $arr = array('data'=>$option,'msg'=>'200');
                echo json_encode($arr);

           //$this->twig->display('user/resetpassword');
       }else{

       // $this->load->model('AccountModel');
       // $phone = $this->input->post('phone');
        //echo $phone; die;
       // $questions = $this->AccountModel->getQuestion($phone);

        $this->twig->display('user/question', compact('questions'));
       }
    }

    
    
}