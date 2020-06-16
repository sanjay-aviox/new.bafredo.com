<?php

use Entity\User;

/**
 * Created by PhpStorm.
 * User: dell
 * Date: 5/12/2019
 * Time: 3:47 AM
 */
class PageModel extends CI_Model
{
    public function __construct()
    {
      $this->load->Model('PageModel','PM');
    }

    public function insert_contact_us($data)
    {
        $this->db->insert('contact_us',$data);
    }

    public function deleterecord($id){
        foreach($id as $page_id){
            $this->db->delete('pages', array('id' => $page_id));
        }
    }
    
    public function subscribe()
    {
       
        $query=$this->db->query("select * from subscribe");
	    return $query->result();
    }
    
    public function getEmailRecord()
    {
            
        $query =$this->db->query("SELECT email.* , COUNT(email_subscriber.subscribe_id) as subscriber FROM `email` INNER JOIN email_subscriber ON email.id=email_subscriber.email_id GROUP BY email_subscriber.email_id ");
        return $query->result();
    
    }
    public function insert_template($data)
    {
        
        $this->db->insert('email',$data);
        $insert_id = $this->db->insert_id();
        return  $insert_id;
    }
    public function insert_subscriber($data)
    {
        
        foreach($data as $val){
            $this->db->insert('email_subscriber',$val);
        }
    }
    public function getEmailById($id)
    {
        $q = $this->db->select('*')
                 ->from('email')
                 ->where('id',$id)
                 ->get();
        return $q->row_array();
    }
    public function getsubscriberById($id)
    {
        $q = $this->db->select('*')
                 ->from('subscribe')
                 ->where('id',$id)
                 ->get();
        return $q->row_array();
    }
    public function getContactByEmail($id)
    {
        $this->db->select('*');
        $this->db->from('email_subscriber');
        $this->db->join('subscribe', 'email_subscriber.subscribe_id = subscribe.id');
        $this->db->where('email_subscriber.email_id', $id);
        $query = $this->db->get();

        return $query->result();
    }
    public function getsecurity()
    {
        $q = $this->db->select('*')
                 ->from('securities')
                 ->get();
               
        return $q->result();
    }
    public function adduserSecurity($data)
    {
        foreach($data as $val){
            $this->db->insert('user_securities',$val);
        }
    }
    public function insertReview($data)
    {
        $this->db->insert('reviews',$data);
    
    }
    
}