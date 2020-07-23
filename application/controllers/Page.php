<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: masoodurrehman42@gmail.com
 * Date: 4/25/2019
 * Time: 10:08 PM
 */

class Page extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->library('doctrine');
        $this->em = $this->doctrine->em;
    }

    public function index($slug)
    {
        $this->load->Model('PageModel','PM');
        $data = array();
        if ($slug == 'contact-us') {
            $post = $this->input->post();
            $productMenu = $this->product->newArrival(4);
            if(!empty($post))
            {   
                //print_r($post); die;
                $this->PM->insert_contact_us($post);
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
                $this->email->from($this->input->post('email'), $this->input->post('name'));
                $this->email->to('info@bafredo.com');
                $this->email->subject($this->input->post('subject'));
                $this->email->message($this->input->post('message'));
                $this->email->send();
                
                $this->load->library('email', $config);
                $this->email->from('info@bafredo.com','Vafredo');
                $this->email->to($this->input->post('email'));
                $this->email->subject($this->input->post('subject'));
                $this->email->message($this->input->post('message'));
                $this->email->send();
    
                $data["success"] = true;
            }
            $this->twig->display("page/contact-us",compact('data','productMenu'));

        } else {
          
            $page = $this->doctrine->em->getRepository("Entity\Page")->findOneBySlug($slug);
            if($slug== "about-us"){
                $this->twig->display("page/about-us", compact('page'));

            }else{
                $this->twig->display("page/common", compact('page'));
            }
            
        }
    }

    public function template($page)
    {
        $productMenu = $this->product->newArrival(4);
        $this->twig->display("template/{$page}" ,compact('productMenu'));
    }
}