<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 5/7/2019
 * Time: 4:37 AM
 */
class ProductModel extends CI_Model
{
    public function __construct()
    {
        $this->load->library('doctrine');
        $this->repo = $this->doctrine->em->getRepository("Entity\Product");
    }

    public function findAll($limit = null, $offset = null)
    {
        // findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
        $order = array('created_at' => 'DESC');
        return $this->repo->findBy(array(), $order, $limit, $offset);
    }

    public function newArrival($limit = null, $offset = null)
    {
        // findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
        $order = array('created_at' => 'DESC');
        return $this->repo->findBy(array('is_new_arrival' => 1), $order, $limit, $offset);
    }

    public function featured($limit = null, $offset = null)
    {
        $order = array('created_at' => 'DESC');
        return $this->repo->findBy(array('is_featured' => 1), $order, $limit, $offset);
    }

    public function special($limit = null, $offset = null)
    {
        $order = array('created_at' => 'DESC');
        return $this->repo->findBy(array('is_special' => 1), $order, $limit, $offset);
    }

    public function findBySlug($slug)
    {
        return $this->repo->findOneBySlug($slug);
    }
    
     public function getReview($id)
    {
        //   $q = $this->db->select('*')
        //          ->from('reviews')
        //          ->get();
        //            echo "df"; die;
        // return $q->result();

        
         $q = $this->db->select('users.*,reviews.*')
                 ->from('reviews')
                 ->where('product_id',$id)
                 ->join('users','users.id = reviews.user_id')
                
                 ->get();
       // print_r($q->result()); echo"bub"; die;
        return $q->result();
       
    }

    public function gallery($id)
    {
        return $this->doctrine->em->getRepository("Entity\Gallery")->findBy(array(
            'module_id' => $id
        ));
    }

    function deleterecord($id){
        foreach($id as $p_id){
            $this->db->select('products.image');
            $this->db->from('products');
            $this->db->where('id', $p_id);
            $query = $this->db->get();

            if ( $query->num_rows() > 0 )
            {
                $this->db->select('galleries.image');
            $this->db->from('galleries');
            $this->db->where('module_id', $p_id);
            $get_gallery = $this->db->get();

            if ( $get_gallery->num_rows() > 0 )
            {
               $galleries = $get_gallery->result();
               foreach($galleries as $getimage){

                if(file_exists(FCPATH ."assets/uploads/product/gallery/".$getimage->image)){
                    unlink( FCPATH ."assets/uploads/product/gallery/".$getimage->image);
                }
               }
               
               
            }

            $image = $query->row();
               if(file_exists(FCPATH ."assets/uploads/product/thumbnail/".$image->image)){
                unlink( FCPATH ."assets/uploads/product/thumbnail/".$image->image);
                }
               $this->db->delete('products', array('id' => $p_id));
                $this->db->delete('galleries', array('module_id' => $p_id));
            }
           
        }
    }
    
}