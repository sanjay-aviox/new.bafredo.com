<?php

class DashboardModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        
    }

    /**Order Count */
    public function numoforder(){
    $numoforder = $this->db->count_all_results('orders');
    return $numoforder;

    }

    /*Number of User Count */
    public function numberofusers(){
        $numberofusers = $this->db->count_all_results('users');
    return $numberofusers; 
    }

    /*Number of User Count */
    public function numberofsubscribe(){
        $numberofsubscribe = $this->db->count_all_results('subscribe');
    return $numberofsubscribe; 
    }

    /*Number of User Count */
    public function numberofvisitor(){
        
        // $this->db->select('visitor_count');
        // $numberofvisitor = $this->db->get('visitor')->result();
        
        $numberofvisitor = $this->db->count_all_results('visitor');

        //echo $numberofvisitor; die;
        return $numberofvisitor; 
    }

    /*Visitor Count Store In database */
    // public function updatesitorcount(){
    //     $this->db->where('id','1');
    //     $this->db->select('visitor_count');
    //     $count = $this->db->get('visitor')->row();

    //     $this->db->where('id', '1');
    //     $this->db->set('visitor_count', ($count->visitor_count + 1));
    //     $this->db->update('visitor');
    // }
}