<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Email extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        if (!authCheck("admin")) {
            redirect("admin/login");
        }
        $this->load->Model('PageModel','PM');
    }
    public function index()
    {
        $emailrecord = $this->PM->getEmailRecord(); 
  //print_r($emailrecords); die;
        $this->twig->display('admin/email', compact('emailrecord'));
    }
    public function mail()
    {
        $subscribe = $this->PM->subscribe();
        $this->twig->display('admin/emailTemplate', compact('subscribe'));
    }
    public function addEmail()
    {
        $email = array(
                'title' => $this->input->post('title'),
                'subject' => $this->input->post('subject'),
                'description' => $this->input->post('description'),
                'content' => $this->input->post('content')
            );
        $id = $this->PM->insert_template($email);
          
        
        $config = Array(
                    'protocol' => 'smtp',
                    'smtp_host' => 'bafredo.com',
                    'smtp_port' => 2525,
                    'smtp_user' => 'bafredo123@bafredo.com',
                    'smtp_pass' => 'Jf,WWM2o&5{*',
                    'mailtype'  => 'html',
                    'charset'   => 'iso-8859-1'
                );

        foreach($this->input->post('email') as $emails){
            $data[] = array(
                'email_id' => $id,
                'subscribe_id'=>$emails
            );
           $subscribe = $this->PM->getsubscriberById($emails);
  
                $this->load->library('email', $subscribe['email']);
                $this->email->from('info@bafredo.com','Vafredo');
                $this->email->to($subscribe['email']);
                $this->email->subject($this->input->post('subject'));
                $this->email->message($this->input->post('description'));
                $this->email->send();
 
        }
        $id = $this->PM->insert_subscriber($data);
        
     
redirect('admin/email');
    }
    public function view($id)
    {
        
        $subscribe = $this->PM->getContactByEmail($id);
        $template = $this->PM->getEmailById($id);
        
        $this->twig->display('admin/view', compact('subscribe','template'));
    }
}
