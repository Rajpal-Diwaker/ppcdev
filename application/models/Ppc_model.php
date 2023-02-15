<?php
class Ppc_model extends CI_Model
{
    function insert_data($data){
        //print_r($data);die;
            $this->db->insert('quote',$data);
            return 1;
        }
}