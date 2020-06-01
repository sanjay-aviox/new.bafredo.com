<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Blog extends MY_Controller
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
        $xcrud->table('blogs');
        $xcrud->relation('category_id', 'blog_categories', 'id','category_name');
        $xcrud->label(array('category_id' => 'Category','blog_name'=>'Name'));
        $xcrud->fields(array('slug'), true);
        $xcrud->columns(array("image","blog_name","category_id"));
        $xcrud->change_type('image', 'image', '', array('width' => 300, 'path' => '../../assets/front/uploads/blog'));
        $xcrud->before_insert ('blog_insert_before');
        $xcrud->before_update('blog_insert_before');
        $menu = $this->loadMenu("blog");
        $xcrud->column_class('image', 'zoom_img');
        $this->twig->display('admin/xcrud', compact('xcrud', 'menu'));

    }
    public function categories()
	{
        $xcrud = xcrud_get_instance();
        $xcrud->table('blog_categories');
        $xcrud->fields(array("slug"), true);
        $xcrud->columns(array("slug"), true);
        $xcrud->label(array('category_name'=>'Name'));
        $xcrud->before_insert ("blog_category_insert_before");
        $xcrud->before_update ("blog_category_insert_before");
        $menu = $this->loadMenu("category");
        $this->twig->display('admin/xcrud', compact('xcrud', 'menu'));
	}
}
