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
//        $products = $this->em->getRepository(\Entity\Products::class)->findAll();
      //  $error = $this->session->userdata('error');
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
        $user = $em->getRepository("Entity\User")->findOneByEmail($email);
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

                    if (!empty($redirectAfterLogin)) {
                        redirect($redirectAfterLogin);
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
    
    public function forgotview()
    {
        $this->twig->display('auth/forgot');
    }

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
        $this->load->model('AccountModel');
        $mail = $this->input->post('email');
        $check = $this->AccountModel->forgotpassword($mail);
        if(!empty($check)){
            $password = rand();
           $newpassword =  password_hash($password, PASSWORD_DEFAULT);
            $this->AccountModel->updatepassword($mail,$newpassword);

            $config = Array(
                'protocol' => 'smtp',
                'smtp_host' => 'bafredo.com',
                'smtp_port' => 2525,
                'smtp_user' => 'bafredo123@bafredo.com',
                'smtp_pass' => 'Jf,WWM2o&5{*',
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
    
}