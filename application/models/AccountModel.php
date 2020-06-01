<?php

use Entity\User;

/**
 * Created by PhpStorm.
 * User: dell
 * Date: 5/12/2019
 * Time: 3:47 AM
 */
class AccountModel extends CI_Model
{
    public function __construct()
    {

    }
    
    public function loginProcess($email){
        $this->db->select('*')
                  ->from('users')
                  ->where('email =', $email)
                  ->or_where('name =', $email); 

        $query = $this->db->get();

        if($query->num_rows() == 1){
          return $query->row_array();
        }else{
          return false;
        }
  }

    public function update_account($data,$id)
    {
        $this->db->where("id",$id);
        $this->db->update('users',$data);
        return $this->get_user($id);
    }

    public function verfiymail($id){
        $this->db->where("id",$id);
        return $this->db->get('users')->row();
    }
    public function update_account_profile($data,$id)
    {
        $this->db->where("id",$id);
        $this->db->update('users',$data);
        return $this->get_user($id);
    }
    public function update_address_book($data,$id)
    {
        $check = $this->get_user_address_book($id);
       if(empty($check)){
           $sss = array('user_id'=>$id);
           $add = array_merge($sss,$data);
           $this->db->insert('address_books', $add);
           return $this->get_user_address_book($id);
       }else {
           $this->db->where("user_id", $id);
           $this->db->update('address_books', $data);
           return $this->get_user_address_book($id);
       }

    }

    public function get_region(){
        $this->db->select('name');
        $this->db->order_by('name', 'ASC');
        return $this->db->get("regions")->result();
    }

    public function get_districts(){
        $this->db->select('name');
        $this->db->order_by('name', 'ASC');
        return $this->db->get("distircts")->result();
    }

    public function remove_whislist($id)
    {
        $this->db->where("id",$id);
        $this->db->delete('wishlist');
    }

    public function get_wish_list($id)
    {
        $this->db->select('wishlist.*,products.name');
        $this->db->where('user_id',$id);
        $this->db->join('products', 'products.id = wishlist.product_id');
        return $this->db->get("wishlist")->result();
    }
    public function get_user($user_id)
    {
        $this->db->where('id',$user_id);
        $db_user = $this->db->get("users")->row();
        $user = new Entity\User();
        $user->setName($db_user->name);
        $user->setEmail($db_user->email);
        $user->setPassword("");
        $user->setTelephone($db_user->telephone);
        $user->setUserImg($db_user->user_img);
        return $user;
    }
    public function get_user_address_book($user_id)
    {
        $this->db->where('user_id',$user_id);
        return $this->db->get("address_books")->row();
    }
    public  function form_insert($data){
        $this->db->insert('users', $data);
    }
    public function confirmaccount($mail,$code){
        $this->db->where('email',$mail);
        $this->db->where('code',$code);
        $result = $this->db->get('users');
        if(!empty($result->row())){
            $data = [
                'code' => '0',
            ];
            $this->db->where('email',$mail);
            $this->db->update('users', $data);
            redirect('login');
        }else{
            redirect('home');
        }
//        dd($result->row());

    }


    public function checkaccount($mail){
        $this->db->where('email',$mail);
        $this->db->where('code','0');
        $result = $this->db->get('users');
        return $result->row();
    }


     public function getcityregion($name){
        $this->db->where('name',$name);
        $re = $this->db->get('regions')->row();
       if(!empty($re->id)){
           $this->db->where('region_id',$re->id);
           $result = $this->db->get('distircts')->result();
           return json_encode($result);
       }
     }

     public function resendmail($mail){
         $this->db->where("email",$mail);
         return $this->db->get('users')->row();
     }
     public function forgotpassword($mail){
         $this->db->where("email",$mail);
         return $this->db->get('users')->row();
     }

     public function updatepassword($mail,$password){
         $this->db->where('email',$mail);
         $this->db->update('users',array('password' => $password));
         return true;
     }
}