
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
       
        $new_arrival_products = $this->product->newArrival(4);
        $featured_products = $this->product->featured(4);
        $specail_products = $this->product->special(4);
        $blogs = $this->blog->getLatest(array('id' => 'DESC'), 3);
//print_r($new_arrival_products); die;
        $this->twig->display('home', compact(
            'new_arrival_products',
            'featured_products',
            'specail_products',
            'blogs'
        ));

            //$this->dbm->updatesitorcount();
    }
     public function feature()
    {

        $featured_product = $this->product->featured(4);
      

        $this->twig->display('partials/home/feature_product', compact('featured_product'));

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
        //array_push($option,'  <div class="container">
            // <div class="row hidden-xs pc"><br>
            //         <div data-pc="Latest" class="productTitle featured" style="border-bottom: 2px solid green;">
            //         <b>Filter</b> 
            //         <div class="sort-section" style="float:right;">
            //         <label>Sort</label>
            //         <select class="form-control" id="filter">
            //         <option>Select Filter</option>
            //         <option value="hightolow">Price: High to Low</option>
            //         <option value="lowtohigh">Price: Low to High</option>
            //         </select>
            //         </div>
            //         </div><br>
            //         <div class="row">');
                
      if(count($result)>0){
        foreach ($result as $res){
            array_push($option,'
                    <div class="item" style="float:left;margin-left:40px;" >
                        <a title="'.$res->name.'" href="https://new.bafredo.com/product/detail/'.$res->slug.'">
                        <div class="img">
                            <img src="'.$res->thumbnail.'" alt="'.$res->name.'" title="'.$res->name.'" class="filter-img" width="100%">
                        </div>
                        </a> 
                        <div class="title">
                             <a title="'.$res->name.'" href="https://new.bafredo.com/product/detail/'.$res->name.'">'.$res->name.'</a>
                        </div> 
                        <div class="btns">
                             <b class="price">'.$res->currency.'&nbsp'.$res->price.'</b> 
                             <span>
                             <button type="button"><i class="iconfont icon-cart"></i></button>
                             </span>
                        </div></div></div>'
                  );
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
       array_push($option,'<div class="row hidden-xs pc">
                    <div data-pc="Latest" class="productTitle featured" style="border-bottom: 2px solid green;">
                    <b>Filter</b> 
                    <div class="sort-section" style="float:right;">
                    <label>Sort</label>
                    <select class="form-control" id="filter">
                    <option>Select Filter</option>
                    <option value="hightolow">Price: High to Low</option>
                    <option value="lowtohigh">Price: Low to High</option>
                    </select>
                    </div>
                    </div><br>');
                // <div class="productList">
                // <ul class="simpleMode">');
       if(count($result)>0){
        foreach ($result as $res){
            array_push($option,'
                    <div class="item" style="float:left;margin-left:40px;" >
                        <a title="'.$res->name.'" href="https://new.bafredo.com/product/detail/'.$res->slug.'">
                        <div class="img">
                            <img src="'.$res->thumbnail.'" alt="'.$res->name.'" title="'.$res->name.'" class="filter-img" width="100%">
                        </div>
                        </a> 
                        <div class="title">
                             <a title="'.$res->name.'" href="https://new.bafredo.com/product/detail/'.$res->name.'">'.$res->name.'</a>
                        </div> 
                        <div class="btns">
                             <b class="price">'.$res->currency.'&nbsp'.$res->price.'</b> 
                             <span>
                             <button type="button"><i class="iconfont icon-cart"></i></button>
                             </span>
                        </div></div> 
                   ');
         }
     }else{
        array_push($option,'<div class="row noRecords" style=" background:white;height:50px;margin-left:40px;"><h3 style="margin-left:10px;">No Results Found..</h3></div>');
     }
         
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

        $this->load->model('CategoryModel', 'category');
        $this->load->model('ProductModel', 'product');

        $product = $this->product->findBySlug($slug);
        $gallery = $this->product->gallery($product->getId());
        $subCategory = $this->category->getOneById($product->getCategoryId());
        $mainCategory = $this->category->getMainCategoryById($subCategory->getCategoryId());

        $this->twig->display('product/detail', compact('product', 'gallery', 'mainCategory'));
    }
}
