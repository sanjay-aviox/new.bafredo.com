<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('CategoryModel', 'category');
        $this->load->model('ProductModel', 'product');
    }

    public function detail($slug)
	{
         //print_r("hh"); die;
        $product = $this->product->findBySlug($slug);
       
        $review =$this->product->getReview($product->getId());
	    $gallery = $this->product->gallery($product->getCategoryId());
     
        $subCategory = $this->category->getOneById($product->getCategoryId());
      //print_r($subCategory); die;
        $mainCategory = '';//$this->category->getMainCategoryById($subCategory->getCategoryId());
        $user = $this->session->userdata("user"); 
      
        $this->twig->display('product/detail', compact('product', 'gallery', 'subCategory' ,'mainCategory','review','user'));
	}

	public function all()
    {
        $parent_slug = 'all';
        $subCategories = array();
        $products = $this->product->findAll();

        $this->twig->display('category', compact('products', 'subCategories', 'parent_slug'));
    }

    public function sales()
    {
        $parent_slug = 'sales';
        $subCategories = array();
        $products = $this->product->findAll();

        $this->twig->display('category', compact('products', 'subCategories', 'parent_slug'));
    }

    public function newArrival()
    {
        $parent_slug = 'new';
        $subCategories = array();
        $products = $this->product->newArrival();

        $this->twig->display('category', compact('products', 'subCategories', 'parent_slug'));
    }
    
    public function addReviews()
    {
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
    redirect(base_url()."/product/detail/".$this->input->post('product_slug'));
        
    }
}
