<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: masoodurrehman42@gmail.com
 * Date: 4/25/2019
 * Time: 10:08 PM
 */

class Blog extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('BlogModel','BM');
    }

    public function index()
    {
        $this->load->library('doctrine');
        $blogs = $this->doctrine->em->getRepository('Entity\Blog')->findAll(array(), array('created_at', 'DESC'));
        //$categories = $this->BM->getCategories();
        $categories = $this->doctrine->em->getRepository('Entity\BlogCategory')->findAll(array(), array('created_at', 'DESC'));
        $productMenu = $this->product->newArrival(4);
    
            // foreach( $categories as $key => $cat) {

            //    $category[$cat['id']] = $cat['category_name'];
            // }
  
// echo"<pre>";
//      print_r($category); die;
//        $qb = $this->doctrine->em->createQueryBuilder();
//        $qb->select('b, c')
//            ->from('Entity\Blog', 'b')
//            ->join('Entity\BlogCategory', 'c');
        $this->twig->display('blogs/blogs', compact('blogs', 'category','productMenu'));
    }
    public function detail()
    {
        $segments = $this->uri->segment_array();
        $blog_detail = $this->BM->getBlog($segments[3]);
        //print_r($blog_detail);   
        $category = $this->BM->getcetagory($blog_detail->category_id);
        $relatedBlog = $this->BM->getBlogCategory($blog_detail->category_id);
        $productMenu = $this->product->newArrival(4);
       // print_r($relatedBlog); die;
       
        $this->twig->display('blogs/blog_detail', compact('blog_detail','category','relatedBlog','productMenu'));

    }
}