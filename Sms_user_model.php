<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models;
use \CodeIgniter\Model;
/**
 * Description of RegisterModel
 *
 * @author ram
 */
class Sms_user_model extends Model {



 public function fetch_table_data($tablename,$where)
    {
        $builder = $this->db->table($tablename);
            $builder->select("*");
            $builder->where($where);
            $builder->limit(100);
            // $builder->where('id >=', $minvalue);
            // $builder->where('id <=', $maxvalue);
            $result = $builder->get();
            if(count($result->getResultArray())>0){
                return $result->getResult();
            }
            else{
                return false;
            }
   
    }
    public function update_commen($tablename,$where,$data){
        $builder = $this->db->table($tablename);
        $builder->where($where);
        $builder->update($data);
        // echo "<br>". $this->db->getLastQuery(); die;
        if($this->db->affectedRows()==1)
        {
            return true;
        }
            return false;
    }
}
