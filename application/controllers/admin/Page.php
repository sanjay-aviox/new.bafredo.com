<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 5/22/2019
 * Time: 6:45 AM
 */
class Page extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        if (!authCheck("admin")) {
            redirect("admin/login");
        }
    }

    public function index()
    {
        $xcrud = xcrud_get_instance();
        $xcrud->table('pages');
        $xcrud->before_insert('insert_before');
        $xcrud->before_update('insert_before');
        $xcrud->fields(array('slug','created_at'), true);
        $xcrud->columns(array('created_at'), true);

        $menu = $this->loadMenu("page");
        $del_url = base_url("admin/page/deletepage");
        $this->twig->display('admin/xcrud', compact('xcrud', 'menu','del_url'));
    }

    function deletepage(){
        $this->load->model('PageModel');
        $id = $this->input->post('primery_keys');
        $this->PageModel->deleterecord($id);
       
    }
}