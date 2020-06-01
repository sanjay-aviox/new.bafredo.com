<?php

use Entity\User;

/**
 * Created by PhpStorm.
 * User: dell
 * Date: 5/12/2019
 * Time: 3:47 AM
 */
class VisitorModel extends CI_Model
{
    public function __construct()
    {
      $this->load->Model('VisitorModel','VM');
    }

    public function insertVisitor($data)
    {
        $this->db->insert('visitor',$data);
    }
    function ip_exists($ip)
    {
      
        $this->db->where('ip_address',$ip);
        $query = $this->db->get('visitor');
        
        if ($query->num_rows() > 0){
            return $query->num_rows();
        }
        else{
            return false;
        }
    }

    
}