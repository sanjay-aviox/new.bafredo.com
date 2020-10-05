<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('CategoryModel', 'category');
        $this->load->model('ProductModel', 'product');
        $this->load->library("pagination");
    }

    public function detail($slug)
    {
        $productMenu = $this->product->newArrival(5);
        $product = $this->product->findBySlug($slug);
        $review =$this->product->getReview($product->getId());
        $gallery = $this->product->gallery($product->getCategoryId());
     
        $subCategory = $this->category->getOneById($product->getCategoryId());
      //print_r($subCategory); die;
        $mainCategory = '';//$this->category->getMainCategoryById($subCategory->getCategoryId());
        $user = $this->session->userdata("user"); 
       // print_r($product); die;

         $msg = $this->session->flashdata('msg');

        $this->twig->display('product/detail', compact('product', 'gallery', 'subCategory' ,'mainCategory','review','user','productMenu','msg'));
    }

    public function all()
    {
           
        $parent_slug = 'all';
        $subCategories = array();
       
        $productMenu = $this->product->newArrival(5);
        
        $config = array();
        $config["base_url"] = base_url() . "product/all";
        $config["total_rows"] = count($this->product->findAll());
        $config["per_page"] = 5;
        $config["uri_segment"] = 3;

        $this->pagination->initialize($config);

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        $links = $this->pagination->create_links();
        
        $products = $this->product->findAll($config["per_page"], $page);

        //echo count($products); die;
        $this->twig->display('product', compact('products', 'subCategories', 'parent_slug','productMenu','links'));

    }

    public function sales()
    {
        $parent_slug = 'sales';
        $subCategories = array();
        $products = $this->product->findAll();
        $productMenu = $this->product->newArrival(5);

        $this->twig->display('category', compact('products', 'subCategories', 'parent_slug','productMenu'));
    }

    public function newArrival()
    {
        $parent_slug = 'new';
        $productMenu = $this->product->newArrival(5);
        $subCategories = array();
        $products = $this->product->newArrival();

        $this->twig->display('category', compact('products', 'subCategories', 'parent_slug','productMenu'));
    }
    
    public function addReviews()
    { 
        $this->session->unset_userdata('msg');
        $user = $this->session->userdata("user"); 
     
        $this->load->Model('PageModel','PM');
        $data = array(
                'review'=> $this->input->post('rating'),
                'comment' => $this->input->post('comment'),
                'product_id'=> $this->input->post('product_id'),
                'user_id'=>$user->getId()
            );
          //  print_r($data); die
        $template =  $this->PM->insertReview($data);
     $this->session->set_flashdata('msg', "Review Added");
    redirect(base_url()."/product/detail/".$this->input->post('product_slug'));
        
    }
}
