<?php

/**
 * Created by PhpStorm.
 * User: dell
 * Date: 5/12/2019
 * Time: 3:47 AM
 */

class BlogModel extends CI_Model
{

    public function __construct()
    {
        $this->load->library('doctrine');
        $this->repo = $this->doctrine->em->getRepository("Entity\Blog");
    }

    public function getLatest($orderBy = null, $limit = null, $offset = null)
    {
        // findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
        return $this->repo->findBy(array(), $orderBy, $limit, $offset);
    }
    public function getBlog($slug)
    {
        $this->db->select("blogs.*");
        $this->db->where("slug",$slug);
        return $this->db->get('blogs')->row();
    }
    public function getCategories()
    {
        $this->db->select("id,slug");
       
        return $this->db->get('blog_categories')->result_array();
    }
    public function getcetagory($id)
    {
        $this->db->select("blog_categories.*");
        $this->db->where("id",$id);
        return $this->db->get('blog_categories')->row();
    }
    public function getBlogCategory($id)
    {


            $this->db->select('blogs.*,blog_categories.category_name');
            $this->db->from('blogs');
            $this->db->where("blogs.category_id",$id);
            
            $this->db->join('blog_categories','blog_categories.id = blogs.category_id');
            $query = $this->db->get();
            return $query->result();
    }
}