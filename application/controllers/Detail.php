<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Detail extends MY_Controller
{
    public $fe;

    public function __construct()
    {
        parent::__construct();

        $this->fe = $this->loadFrontend();
    }

    public function index()
	{   
        $productMenu = $this->product->newArrival(4);
        $this->twig->display('detail','productMenu');
	}
}
