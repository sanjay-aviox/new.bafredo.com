<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Category extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('MenuModel', 'menu');
        $this->load->model('ProductModel', 'product');
    }

    public function index($parent_slug, $child_slug = null)
    {


        $mainCategoryId = 0;
        $products = array();
        $subCategories = array();
        $productMenu = $this->product->newArrival(4);
       
        if (empty($child_slug)) {
            $category = $this->menu->getMainCategoryBySlug($parent_slug);
            $mainCategoryId = $category->getId();
            $products = $this->menu->getMainMenuProductsById($category->getId());
    
        } else {
        //    print_r($child_slug); 
            $category = $this->menu->getSubCategoryBySlug($child_slug);
           
            // print_r($category); die;

            $mainCategoryId = $category->getCategoryId();
            $products = $this->menu->getSubMenuProductsById($category->getId());
        }

        if (! empty($mainCategoryId)) {

            $subCategories = $this->menu->getSubCategoriesByParentId($mainCategoryId);
        }

        $this->twig->display('category', compact('products', 'subCategories', 'category','parent_slug','productMenu'));
    }
}