<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 5/7/2019
 * Time: 4:37 AM
 */
class OrderModel extends CI_Model
{
    public function allByUserId($user_id)
    {
        return $this->db->where('user_id', $user_id)->where("order_status !=","Cancel")->order_by('created_at', 'desc')->get('orders')->result();
    }
    public function update_record($order_id)
    {
        $this->db->where('id',$order_id);
        $data = array("order_status"=>"Cancel");
        $this->db->update("orders",$data);
    }

    public function orderdel($order_id){
        foreach($order_id as $id_ord){
            $this->db->delete('orders', array('id' => $id_ord));
        }
    }

    public function lastOrder(){

       $last_record = $this->db->order_by('id',"desc")
            ->where('date(created_at)', '=', date('Y-m-d'))
            ->get('orders')
            ->row();
       return $last_record;
    }
    public function allByDate($user_id, $data)
    {
       
        $start_date =date_format(date_create($data[0]),"Y-m-d");
        $end_date =date_format(date_create($data[1]),"Y-m-d");
 // echo $start_date;   echo $end_date; die;
        return $this->db->where('user_id', $user_id)
             ->where("order_status !=","Cancel")
             //->order_by('created_at', 'desc')
             ->where('date(created_at) >=', $start_date)
              ->where('date(created_at) <=', $end_date)
             ->get('orders')->result();
    }

}