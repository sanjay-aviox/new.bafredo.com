<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends MY_Controller
{
    protected $em;

    public function __construct()
    {
        parent::__construct();

        $this->load->library('doctrine');
        $this->em = $this->doctrine->em;
    }

    public function index()
	{
        $xcrud = xcrud_get_instance();
        $xcrud->table('products');
//        $load_scripts = Xcrud::load_js();
//        $products = $this->em->getRepository(\Entity\Products::class)->findAll();
        $this->twig->display('xcrud', compact('xcrud'));
	}

	public function create()
    {
        $product = new Entity\Product();
        $product->setName("Apple iPhone 7 plus");
        $product->setPrice(30000);
        $product->setDescription("I love this phone");

        $this->em->persist($product);
        $this->em->flush();
    }
}
