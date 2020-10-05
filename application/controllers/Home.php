
<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller
{
    public function __construct()
    {
     
        parent::__construct();
        $this->load->library('doctrine');
        $this->load->model('ProductModel', 'product');
        $this->load->model('BlogModel', 'blog');
        $this->load->model('SettingModel', 'sm');
        $this->load->model('DashboardModel', 'dbm');
        $this->load->model('VisitorModel', 'VM');
        $this->load->library("pagination");

    }

    public function index()
    {
 
       // $exist_ip = $this->VM->ip_exists($_SERVER['REMOTE_ADDR']);
       //  if($exist_ip == 0){
       //  $data = array(
    //             'visitor_count' => 1,
    //             'ip_address' => $_SERVER['REMOTE_ADDR'],
    //             'created_at' =>date("d/m/Y")
    //         );
    //     $this->VM->insertVisitor($data);
       //  }
       
        $productMenu = $this->product->newArrival(5);
        $new_arrival_products = $this->product->newArrival(4);
        $featured_products = $this->product->featured(4);
        $specail_products = $this->product->special(4);
        $blogs = $this->blog->getLatest(array('id' => 'DESC'), 3);
//print_r($new_arrival_products); die;
        $this->twig->display('home', compact(
            'new_arrival_products',
            'featured_products',
            'specail_products',
            'blogs',
            'productMenu'
        ));

            //$this->dbm->updatesitorcount();
    }
     public function feature()
    {
        $productMenu = $this->product->newArrival(4);
        $featured_product = $this->product->featured($config["per_page"], $page);
        
        $config = array();
        $config["base_url"] = base_url() . "feature-product";
        $config["total_rows"] = count($this->product->featured());
        $config["per_page"] = 4;
        $config["uri_segment"] = 3;

        $this->pagination->initialize($config);

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        $links = $this->pagination->create_links();

        $featured_product = $this->product->featured($config["per_page"], $page);

        $this->twig->display('partials/home/feature_product', compact('featured_product','productMenu','links'));

            //$this->dbm->updatesitorcount();
    }

    public function prefetch()
    {
        $result = $this->db->select('id, name, slug, description, CONCAT("'.asset('uploads/product/thumbnail/').'", image) AS thumbnail, price, currency, sku, brand')
            ->limit(10)
            ->get("products")
            ->result();

        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($result));
    }
    public function subscribe()
    {
        $response = $this->sm->subscribe_email($this->input->post("email"));
        echo  $response;
    }
    public function typeahead()
    {
        $q = $this->input->get("q", true);
     
        $result = $this->db->select('id, name, slug, description, CONCAT("'.asset('uploads/product/thumbnail/').'", image) AS thumbnail, price, currency, sku, brand')
            ->like("name", $q)
            ->get("products")
            ->result();
         print_r($result); die;
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($result));
    }
    
    public function searchresult()
    {
        $q = $this->input->get();  
        $result = $this->db->select('id, name, slug, description, CONCAT("'.asset('uploads/product/thumbnail/').'", image) AS thumbnail, price, currency, sku, brand')
            ->like("name", $q['x'])
            ->get("products")
            ->result();
            
        $option=array();
      if(count($result)>0){
        foreach ($result as $res){
            array_push($option,'<div class="col-12 col-lg-3 col-md-4 col-sm-6">
                    <div class="view-products-single-box">
                        <div class="view-products-thumb">
                            <img src="'.$res->thumbnail.'">
                            <div class="view-products-hover-info">
                                <button class="icon-link-btn" onclick="openNav()"><i class="fa fa-shopping-cart pr-2"></i> Add to cart</button>
                            </div>
                        </div>
                        <div class="view-products-info">                            
                            <div class="view-products-title">
                                <h3><a href="#">'.$res->name.'</a></h3>
                                <span class="view-products-price">'.$res->currency.'&nbsp'.$res->price.'</span>
                            </div>
                        </div>
                        <div class="hover-icon-link-box">                            
                            <a href="#" class="hover-icon-link" data-toggle="tooltip" data-placement="left" title="Login to use whishlist"><i class="fa fa-heart-o"></i></a>
                            <a href="#" class="hover-icon-link" data-toggle="tooltip" data-placement="left" title="Compare"><i class="fa fa-refresh"></i></a>
                            <a href="#" class="hover-icon-link" data-toggle="tooltip" data-placement="left" title="Quick view"><i class="fa fa-search-plus" data-toggle="modal" data-target="#quickViewModal"></i></a>
                        </div>
                    </div>
                </div>');
         }
     }else{
        array_push($option,'</div><div class="row noRecords" style=" background:white;height:50px;margin-left:40px;"><h3 style="margin-left:10px;">No Results Found..</h3></div>');
     }

         $arr = array('data'=>$option,'view'=>'search');
                echo json_encode($arr);
        
    }
    
    public function filters()
    {
        $q = $this->input->get(); 
        //print_r($q); die;
          if($q['val'] == 'hightolow'){
              $result = $this->db->select('id, name, slug, description, CONCAT("'.asset('uploads/product/thumbnail/').'", image) AS thumbnail, price, currency, sku, brand')
                     ->like("name", $q['x'])
                    ->order_by("price", "desc")
                    ->get("products")
                    ->result();
              
              
          }else{
                $result = $this->db->select('id, name, slug, description, CONCAT("'.asset('uploads/product/thumbnail/').'", image) AS thumbnail, price, currency, sku, brand')
                     ->like("name", $q['x'])
                    ->order_by("price", "asc")
                    ->get("products")
                    ->result();
          }
          $option=array();
       array_push($option,'<section class="filter-product-section view-products-section py-4"><div class="container"><div class="row"><div class="col-12 col-md-12"><div class="product-select-box mb-4"><ul class="d-flex justify-content-between align-items-center mb-0"><li><p class="mb-0">Filter</p></li><li><select class="form-control mr-sm-2" id="filter"><option selected="">Most Popular</option><option value="1">Select Option</option><option value="lowtohigh">Price: Low to High</option><option value="hightolow">Price: High to Low</option></select></li></ul></div></div></div><div class="row">');
                // <div class="productList">
                // <ul class="simpleMode">');
       if(count($result)>0){
        foreach ($result as $res){
            array_push($option,'
                    <div class="col-12 col-lg-3 col-md-4 col-sm-6">
                    <div class="view-products-single-box">
                        <div class="view-products-thumb">
                            <img src="'.$res->thumbnail.'">
                            <div class="view-products-hover-info">
                                <button class="icon-link-btn" onclick="openNav()"><i class="fa fa-shopping-cart pr-2"></i> Add to cart</button>
                            </div>
                        </div>
                        <div class="view-products-info">                            
                            <div class="view-products-title">
                                <h3><a href="#">'.$res->name.'</a></h3>
                                <span class="view-products-price">'.$res->currency.'&nbsp'.$res->price.'</span>
                            </div>
                        </div>
                        <div class="hover-icon-link-box">                            
                            <a href="#" class="hover-icon-link" data-toggle="tooltip" data-placement="left" title="Login to use whishlist"><i class="fa fa-heart-o"></i></a>
                            <a href="#" class="hover-icon-link" data-toggle="tooltip" data-placement="left" title="Compare"><i class="fa fa-refresh"></i></a>
                            <a href="#" class="hover-icon-link" data-toggle="tooltip" data-placement="left" title="Quick view"><i class="fa fa-search-plus" data-toggle="modal" data-target="#quickViewModal"></i></a>
                        </div>
                    </div>
                </div>
                   ');
         }

     }else{
        array_push($option,'<div class="row noRecords" style=" background:white;height:50px;margin-left:40px;"><h3 style="margin-left:10px;">No Results Found..</h3></div></div>');
     }
     array_push($option,'</div>');
         
         // array_push($option,'</ul>
         //        </div>
         //    </div>');
         
            $countss = count($result);
         $arr = array('data'=>$option,'view'=>'search');
                echo json_encode($arr);
    }

 
         
         
    public function search()
    {
        $slug = $this->input->get("slug", true);
        $productMenu = $this->product->newArrival(4);
        $this->load->model('CategoryModel', 'category');
        $this->load->model('ProductModel', 'product');

        $product = $this->product->findBySlug($slug);
        $gallery = $this->product->gallery($product->getId());
        //echo"<pre>";print_r($product); print_r($product->getCategoryId()); die;
        //if(!empty($product->getCategoryId())){
            $subCategory = $this->category->getOneById($product->getCategoryId());
       // }
     
        // if(!empty($subCategory->getCategoryId() !=){
        //      $mainCategory = $this->category->getMainCategoryById($subCategory->getCategoryId());
        // }
        $this->twig->display('product/detail', compact('product', 'gallery', 'mainCategory','productMenu'));
    }
}
