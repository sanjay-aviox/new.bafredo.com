<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        if (!authCheck("admin")) {
            redirect("admin/login");
        }
        $this->load->model('DashboardModel');
    }
    
    public function index()
    {
        $menu = $this->loadMenu("dashboard");
       $data['orders'] = $this->DashboardModel->numoforder();
        $data['numberofusers'] = $this->DashboardModel->numberofusers();
        $data['numberofsubscribe'] = $this->DashboardModel->numberofsubscribe();
        $data['numberofvisitor'] = $this->DashboardModel->numberofvisitor();

        $xcrud = xcrud_get_instance();
        $xcrud->table('orders');
        $xcrud->join('user_id', 'users', 'id', [],"not_insert");
        $xcrud->columns('users.name,users.email,total,order_status,currency,payment_method,order_status,shipping_method');
        $xcrud->fields('users.name,users.email,total,order_status,currency,payment_method,order_status,shipping_method');

        
        $this->twig->display('admin/dashboard', compact('menu','data','xcrud'));
    }

    public function settings()
    {
        $this->load->library('upload');
        $this->load->Model('SettingModel', 'SM');
        $this->load->helper(array('form', 'url'));
        $post = $this->input->post();
        $image_path = FCPATH."/assets/front/image/";
        $data["result"] = $this->SM->get_settings();
        if (!empty($post)) {
            $config['upload_path'] = $image_path ;
            $config['allowed_types'] = 'png|jpg|gif';
            $config['max_size'] = 2048;
            $config['encrypt_name'] = TRUE;
            $this->upload->initialize($config);
            if (!empty($_FILES["favicon"]["name"])) {
                if (!empty($_FILES['favicon']['name'])) {
                    if (!$this->upload->do_upload('favicon')) {
                        $data['errors'] = $this->upload->display_errors();
                        $error = true;
                    } else {
                        $favicon = $this->upload->data("file_name");
                        if(file_exists($image_path.$data["result"]["favicon"] ))
                        {
                            unlink($image_path.$data["result"]["favicon"] );

                        }
                        $post["favicon"] = $favicon;

                    }
                }
            }

            if (!empty($_FILES['logo']['name'])) {
                if (!$this->upload->do_upload('logo')) {
                    $errors = $this->upload->display_errors();
                    $data['errors'] = $errors;
                    $error = true;
                } else {
                    $logo = $this->upload->data("file_name");
                    if(file_exists($image_path.$data["result"]["logo"]))
                    {
                        unlink($image_path.$data["result"]["logo"]);
                    }
                    $post["logo"] = $logo;
                }
            }
            if (empty($error)) {
                $this->SM->update_settings($post);
                $data["success"] = "Update Successfully";
                $data["result"] = $this->SM->get_settings();
            }
            $menu = $this->loadMenu("General Settings");
            $this->twig->display('admin/settings', compact('menu', 'data'));

        } else {
            $menu = $this->loadMenu("General Settings");
            $this->twig->display('admin/settings', compact('menu','data'));
        }
    }

    public function subscribe()
    {
        $xcrud = xcrud_get_instance();
        $xcrud->table('subscribe');
        $this->twig->display('admin/xcrud', compact('xcrud'));
    }
    public function orders()
    {
        $xcrud = xcrud_get_instance();
        $xcrud->table('orders');
        $xcrud->join('user_id', 'users', 'id', [],"not_insert");
        $xcrud->columns('users.name,users.email,total,order_status,currency,payment_method,order_status,shipping_method');
        $xcrud->fields('users.name,users.email,total,order_status,currency,payment_method,order_status,shipping_method');
        $del_url = base_url("admin/dashboard/deleteorder");
        $this->twig->display('admin/xcrud', compact('xcrud','del_url'));
    }
    public function contact_us()
    {
        $xcrud = xcrud_get_instance();
        $xcrud->table('contact_us');
        $this->twig->display('admin/xcrud', compact('xcrud'));
    }

   public function deleteorder(){
       $this->load->model('OrderModel');
       $order_id = $this->input->post('primery_keys');
       $this->OrderModel->orderdel($order_id);
   }

}
