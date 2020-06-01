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
}