<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Registration extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->library('doctrine');
        $this->load->Model('PageModel','PM');
        
        $this->load->helper('url');
    }

    public function index()
	{

    $cap =  $this->initCaptcha();

        $questions = $this->PM->getsecurity();
        $emailexit = $this->session->flashdata('emailexit');
        $name = $this->session->flashdata('name');
        $email = $this->session->flashdata('email');
        $password = $this->session->flashdata('password');
        $confirm_password= $this->session->flashdata('confirm_password');


        $this->twig->display('registration',compact('emailexit','name','email','password','confirm_password','questions','cap'));
    }

    public function process()
    {
      // print_r($this->input->post('answer')[0]); die;
        if($this->session->userdata('captchaWord') == $this->input->post('captcha')){
       
    
                $this->load->library('form_validation');
                $name = $this->input->post('name');
                $email = $this->input->post('email');
                $telephone = $this->input->post('phone');
                $agree = $this->input->post('agree');
                //$email = 'usamam744@gmail.com';
                $password = $this->input->post('password');
                $confirm_password = $this->input->post('confirm_password');
                $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]');
                $this->form_validation->set_rules('password', 'Password', 'required');
                $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[password]');
        
    
                if ($agree == 'on') {
                    $config = Array(
                        'protocol' => 'smtp',
                        'smtp_host' => 'bafredo.com',
                        'smtp_port' => 2525,
                        'smtp_user' => 'bafredo123@bafredo.com',
                        'smtp_pass' => 'Jf,WWM2o&5{*',
                        'mailtype' => 'html',
                        'charset' => 'iso-8859-1'
                    );
    
    
                    if ($this->form_validation->run() == True) {
                        $this->load->library('email', $config);
                        $secret = "35onoi2=-7#%g03kl";
                        $emails = $email;
                        $hash = MD5($email . $secret);
                        $link = base_url() . 'registration/checkmail?email=' . $emails . '&code=' . $hash;
                        $login = base_url() . 'login';
                        $faqs = base_url() . 'page/faqs';
                        $contact = base_url() . 'page/contact-us';
                        $buy = base_url() . 'page/how-to-buy';
                        $privacy = base_url() . 'page/privacy-policy';
                        $trem = base_url() . 'page/terms-conditions';
        
        
                        $em = $this->doctrine->em;
                        $user = new Entity\User();
                        $user->setName($name);
                        $user->setEmail($email);
                        $user->setPassword($password);
                        $user->setTelephone($telephone);
                        $em->persist($user);
                        $em->flush();
                         
                          foreach($this->input->post('question') as $key => $val){
                               $data[] = array(
                                    'user_id' => $user->getId(),
                                    'question_id'=>$val,
                                    'answer'=> $this->input->post('answer')[$key],
                                );
                          }
                   
                    $security = $this->PM->adduserSecurity($data);
                    
                    
    
                   // $this->load->model('AccountModel');
    
                   // $data = array(
                     //   'name' => $name,
                       // 'email' => $email,
                        //'password' => password_hash($password, PASSWORD_DEFAULT),
                        //'code' => $hash
                    //);
                    //$this->AccountModel->form_insert($data);
    
                    $this->session->unset_userdata('emailexit');
                    $this->session->unset_userdata('password');
    
    
                    //$this->email->from('info@bafredo.com', 'Bafredo');
                    //$this->email->to($email);
    
                    //$this->email->subject('Verify your new BAFREDO Account');
                    //$this->email->message("To verify your Email address, please <a href='$link'>Click Here</a>");
    
                    //$this->email->send();
                    $body = '';
                    $body = 'Dear our esteemed customer,<br><br><br>';
                    $body .= 'We would like to extend our sincere gratitude for your interest to join the BAFREDO Electronics family. You are such an important person that we feel privileged to serve. To us, everyone is someone special and valuable.<br><br><br>';
                    $body .= "This BAFREDO Electronics marketplace allows you to select desirable electronics products and purchase them through popular payment methods, such as Tigo Pesa and M-Pesa. Creating an account with us gives you even more advantages: tracking previous orders, editing shopping cart, adding shipping address, and marking products of interest for future consideration, among others. You can <a href='$login'>login </a> to your personal account to familiarize yourself on various features of the account.<br><br><br>";
                    $body .= "Our website contains <a href='$faqs'> Frequently Asked Questions </a> (FAQs) that you may find useful. If your query is uncovered in the FAQs, please <a href='$contact'>Contact Us</a> directly. We have, in addition, included detailed instructions on <a href='$buy'> How to Purchase products </a> in our website. Should you find the process of purchasing the products relatively challenging, kindly follow the instructions or <a href='$contact'> Contact Us </a> for further clarifications. Lastly, read carefully and understand our <a href='$privacy'> Privacy Policy </a> plus <a href='$trem'>Terms & Conditions </a> before executing any activity in the website.<br><br><br>";
                    $body .= "Please note that we do not monitor this mailbox. All queries should be sent through our <a href='$contact'>Contact Us</a> form or through the contact details displayed on the website.<br><br>";
                    $body .= "BAFREDO Electronics sent this Email to $name registered in our database. We are highly committed to your privacy. You can learn more on our <a href='$privacy'> Privacy Policy </a> and <a href='$trem'>Terms & Conditions </a>.<br><br>";
                    $body .= "©2015-2020 BAFREDO Electronics Inc., Sam Nujoma Road, Near Double D Bar, Sinza C, Ubungo.";
    
                    $this->email->from('info@bafredo.com', 'Bafredo');
                    $this->email->to($email);
    
                    $this->email->subject(''.$name.', welcome to BAFREDO Electronics marketplace!');
                    $this->email->message($body);
    
                    $this->email->send();
    
                    $this->session->set_userdata("isUserLoggedin", 1);
                    $this->session->set_userdata("user", $user);
    
                    $this->session->set_flashdata('emailexit', 'Please check your Email to verify the account you have created. You may also check the spam folder for the Email. Did not receive an Email? <a type="button" class="forgot-pwd-btn chkBike" data-toggle="modal" data-target="#myModal">Resend</a>');
                    redirect("home");
                }
                $this->session->set_flashdata('name', $name);
                $this->session->set_flashdata('email', $email);
                $this->session->set_flashdata('password', $password);
                $this->session->set_flashdata('confirm_password', $confirm_password);
                $this->session->set_flashdata('emailexit', validation_errors());
                //$this->session->set_flashdata('password','Password not Confirmed.');
                redirect("registration");
            }else{
               // print_r("djhgf"); die;
                $this->session->set_flashdata('emailexit', 'Please accept Privacy Policy and Terms & Conditions ');
                $this->session->set_flashdata('name', $name);
                $this->session->set_flashdata('email', $email);
                $this->session->set_flashdata('password', $password);
                $this->session->set_flashdata('confirm_password', $confirm_password);
                redirect("registration");
            }
        }else{
          //  $this->session->set_flashdata('message', "SUCCESS_MESSAGE_HERE"); 
            redirect("registration");
        }
    }

    public function checkmail(){
        $this->load->model('AccountModel');
        $email = $this->input->get('email');
        $code = $this->input->get('code');
        return $this->AccountModel->confirmaccount($email,$code);

    }

    public function resend(){
        $this->load->model('AccountModel');
        $mail = $this->input->post('email');
        $check = $this->AccountModel->resendmail($mail);
        if(!empty($check)){
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
            $emails = $mail;
            $hash = $check->code;
            $link = base_url().'registration/checkmail?email='.$emails.'&code='.$hash;
            $this->email->from('info@bafredo.com', 'Bafredo');
            $this->email->to($mail);

            $this->email->subject('Verify your new BAFREDO Account');
            $this->email->message("To verify your Email address, please <a href='$link'>Click Here</a>");

            $this->email->send();

            $this->session->set_flashdata('emailexit','Please check your Email to verify the account you have created. You may also check the spam folder for the Email.');
            redirect("registration");
        }else{
            $this->session->set_flashdata('emailexit','Sorry, the Email you have entered does not exist!');
            redirect("registration");
        }
    }
    
   public function refreshCaptcha()
   {
      
       $data=$this->initCaptcha();
       echo $data['image'];
   }
   public function initCaptcha(){
       
    	$this->load->helper('captcha');
	    $original_string = array_merge(range(0,9), range('a','z'), range('A', 'Z'));
        $original_string = implode("", $original_string);
        $captcha = substr(str_shuffle($original_string), 0, 6);

	    $vals = array(
                'word'          => $captcha,
         'img_path' => './assets/img/captcha/',
                'img_url'       =>  base_url().'assets/img/captcha/',
                'font_path'     => './assets/fonts(2)/texb.ttf',
                'img_width'     => '150',
                'img_height'    => 50,
                'expiration'    => 82000,
                'word_length'   => 9,
                'font_size'     => 17,
                //'img_id'        => 'Imageid',
                 'pool'          => '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',

        // White background and border, black text and red grid
            'colors'        => array(
                'background' => array(79, 170, 230),
                'border' => array(255, 255, 255),
                'text' => array(0, 1, 0)
                //'grid' => array(255, 40, 40)
             )
            );
            $cap = create_captcha($vals);
            $this->session->set_userdata('captchaWord',$cap['word']);
            return $cap;
   }
 
}