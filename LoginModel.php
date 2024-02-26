<?php

namespace App\Models;

use \CodeIgniter\Model;

class LoginModel extends Model
{

    protected $table = 'userlogin';

    protected $allowedFields = [
        'name',
        'email',
        'ref',
        'created_at'
    ];

    public function current_datetime()
    {
        date_default_timezone_set('Asia/Kolkata');
        $date = date('Y-m-d');
        $time = date('H:i:s');
        $datetime = $date . " " . $time;
        return $datetime;
    }

        	

    public function userlogin($email = NULL, $mobile = NULL, $user_type)
    {
        $builder = $this->db->table('userlogin');
        $builder->select("*");
        if (isset($email) && $user_type == '2') {
            $builder->where('email', $email);
            $builder->where('usertype', '2');
        }
        if (isset($email) && $user_type == '3') {
            $builder->where('email', $email);
            $builder->where('usertype', '3');
        }
        if (isset($email) && $user_type == '4') {
            $builder->where('email', $email);
            $builder->where('usertype', '4');
        }
        if (isset($mobile) && $user_type == '1') {
            $builder->where('mobile', $mobile);
            $builder->where('usertype', '1');
        }if (isset($email) && $user_type == '5') {
            $builder->where('email', $email);
            $builder->where('usertype', '5');
        }if (isset($email) && $user_type == '6') {
            $builder->where('email', $email);
            $builder->where('usertype', '6');
        }
            $builder->where('status', '1');

        $query = $builder->get();
        if (count($query->getResultArray()) == 1) {
            return $query->getRow();
        }
        return false;
    }

    public function exisCheck($tablename, $where)
    {
        $builder = $this->db->table($tablename);
        $builder->select("*");
        $builder->where($where);
        $query = $builder->get();
        if (count($query->getResultArray()) == 1) {
            return $query->getRow();
        }
    }
    public function exisCheck1($tablename, $where)
    {
        $usertype=array('4','2','3');
        $builder = $this->db->table($tablename);
        $builder->select("id");
        $builder->where($where);
        $builder->whereIn('usertype',$usertype);
        $query = $builder->get();
        if (count($query->getResultArray()) == 1) {
            return $query->getRow();
        }
    }

    public function fetch_table_data($tablename, $where)
    {
        $builder = $this->db->table($tablename);
        $builder->select("*");
        $builder->where($where);
        $query = $builder->get();
        return $query->getRow();
        
    }

    public function update_commen($tablename, $where, $data)
    {
        $builder = $this->db->table($tablename);
        $builder->where($where);
        $builder->update($data);
        //echo "<br>". $this->db->getLastQuery(); die;
        if ($this->db->affectedRows() == 1) {
            return true;
        }
        return false;
    }
    public function insert_commen($tablename, $data)
    {
        $builder = $this->db->table($tablename);
        $builder->insert($data);
        if ($this->db->affectedRows() == 1) {
            //echo "<br>". $this->db->getLastQuery(); die;
            return $this->db->insertID();
        } else {
            //echo "<br>". $this->db->getLastQuery(); die;
            return false;
        }
    }

    public function restricted_email_domains()
    {
        $builder = $this->db->table('master_restricted_domains');
        $builder->select("domain_name");
        $builder->where('status', '1');
        $query = $builder->get();
        return $query->getResult();
        //  echo "<br>". $this->db->getLastQuery(); die;
    }
    //check otp count
    public function otp_count_check($phone_number, $email, $user_type)
    {
        if ($user_type == '1') {

            $builder = $this->db->table('user_otp');
            $builder->select("otp_count");
            $builder->where('phone_number', $phone_number)->where('user_type', 1)->like('date_time', date('Y-m-d'));
            $query  = $builder->get();
            $result = $query->getResultArray();
            if (!empty($result)) {
                return $result[0]['otp_count'];
            } else {
                return 0;
            }
        } elseif($user_type == '2') {
            $builder = $this->db->table('user_otp');
            $builder->select("otp_count");
            $builder->where('email_id', $email)->where('user_type', )->like('date_time', date('Y-m-d'));
            $query  = $builder->get();
            $result = $query->getResultArray();
            if (!empty($result)) {
                return $result[0]['otp_count'];
            } else {
                return 0;
            }
        }elseif ($user_type == '3') {

            $builder = $this->db->table('user_otp');
            $builder->select("otp_count");
            $builder->where('email_id', $email)->where('user_type', 3)->like('date_time', date('Y-m-d'));
            $query  = $builder->get();
            $result = $query->getResultArray();
            if (!empty($result)) {
                return $result[0]['otp_count'];
            } else {
                return 0;
            }
        }elseif ($user_type == '4') {

            $builder = $this->db->table('user_otp');
            $builder->select("otp_count");
            $builder->where('email_id', $email)->where('user_type', 4)->like('date_time', date('Y-m-d'));
            $query  = $builder->get();
            $result = $query->getResultArray();
            if (!empty($result)) {
                return $result[0]['otp_count'];
            } else {
                return 0;
            }
        }elseif ($user_type == '6') {

            $builder = $this->db->table('user_otp');
            $builder->select("otp_count");
            $builder->where('phone_number', $phone_number)->where('user_type', 6)->like('date_time', date('Y-m-d'));
            $query  = $builder->get();
            $result = $query->getResultArray();
            if (!empty($result)) {
                return $result[0]['otp_count'];
            } else {
                return 0;
            }
        }
    }

    public function otp_count_check_hr_supervisor($email, $user_type)
    {
        if($user_type == '2') {
            $builder = $this->db->table('user_otp');
            $builder->select("otp_count");
            $builder->where('email_id', $email)->where('user_type',2 )->like('date_time', date('Y-m-d'));
            $query  = $builder->get();
            $result = $query->getResultArray();
            if (!empty($result)) {
                return $result[0]['otp_count'];
            } else {
                return 0;
            }
        }elseif ($user_type == '3') {

            $builder = $this->db->table('user_otp');
            $builder->select("otp_count");
            $builder->where('email_id', $email)->where('user_type', 3)->like('date_time', date('Y-m-d'));
            $query  = $builder->get();
            $result = $query->getResultArray();
            // echo "<br>". $this->db->getLastQuery();
            if (!empty($result)) {
                return $result[0]['otp_count'];
            } else {
                return 0;
            }
        }elseif ($user_type == '4') {

            $builder = $this->db->table('user_otp');
            $builder->select("otp_count");
            $builder->where('email_id', $email)->where('user_type', 4)->like('date_time', date('Y-m-d'));
            $query  = $builder->get();
            $result = $query->getResultArray();
            if (!empty($result)) {
                return $result[0]['otp_count'];
            } else {
                return 0;
            }
        }
    }

    //save otp
    public function otp_count_save($data)
    {
        if ($data['user_type'] == '1') {
            $data = array('phone_number' => $data['phone_number'], 'otp_count' => $data['otp_count'], 'otp_number' => $data['otp_number'], 'user_type' => 1);

            $builder = $this->db->table('user_otp');
            $builder->select("otp_count");
            $builder->where('phone_number', $data['phone_number'])->where('user_type', 1);
            $query  = $builder->get();
            $result = $query->getResultArray();

            if (!empty($result)) {
                //print_r($data['otp_count']);exit();
                $builder_update = $this->db->table('user_otp');
                $builder_update->where('phone_number', $data['phone_number'])->where('user_type', 1)->set(array('otp_count' => $data['otp_count'], 'otp_number' => $data['otp_number']))->update();
            } else {
                // print_r($data);exit();
                $builder_insert = $this->db->table('user_otp');
                $builder_insert->insert($data);
            }
        } elseif($data['user_type'] == '2') {
            $data = array('email_id' => $data['email_id'], 'otp_count' => $data['otp_count'], 'otp_number' => $data['otp_number'], 'user_type' => 2);
            $builder = $this->db->table('user_otp');
            $builder->select("otp_count");
            $builder->where('email_id', $data['email_id'])->where('user_type', 2);
            $query  = $builder->get();
            $result = $query->getResultArray();

            if (!empty($result)) {
                //print_r($data['otp_count']);exit();
                $builder_update = $this->db->table('user_otp');
                $builder_update->where('email_id', $data['email_id'])->where('user_type', 2)->set(array('otp_count' => $data['otp_count'], 'otp_number' => $data['otp_number']))->update();
            } else {

                $builder_insert = $this->db->table('user_otp');
                $builder_insert->insert($data);
            }
        }elseif($data['user_type'] == '3' &&  $data['email_id'] != '') {
            $data = array('email_id' => $data['email_id'], 'otp_count' => $data['otp_count'], 'otp_number' => $data['otp_number'], 'user_type' => 3);
            $builder = $this->db->table('user_otp');
            $builder->select("otp_count");
            $builder->where('email_id', $data['email_id'])->where('user_type', 3);
            $query  = $builder->get();
            $result = $query->getResultArray();

            if (!empty($result)) {
                //print_r($data['otp_count']);exit();
                $builder_update = $this->db->table('user_otp');
                $builder_update->where('email_id', $data['email_id'])->where('user_type', 3)->set(array('otp_count' => $data['otp_count'], 'otp_number' => $data['otp_number']))->update();
            } else {

                $builder_insert = $this->db->table('user_otp');
                $builder_insert->insert($data);
            }
        }
        elseif($data['user_type'] == '4' &&  $data['email_id'] != '') {
            $data = array('email_id' => $data['email_id'], 'otp_count' => $data['otp_count'], 'otp_number' => $data['otp_number'], 'user_type' => 4);
            $builder = $this->db->table('user_otp');
            $builder->select("otp_count");
            $builder->where('email_id', $data['email_id'])->where('user_type', 4);
            $query  = $builder->get();
            $result = $query->getResultArray();

            if (!empty($result)) {
                //print_r($data['otp_count']);exit();
                $builder_update = $this->db->table('user_otp');
                $builder_update->where('email_id', $data['email_id'])->where('user_type', 4)->set(array('otp_count' => $data['otp_count'], 'otp_number' => $data['otp_number']))->update();
            } else {

                $builder_insert = $this->db->table('user_otp');
                $builder_insert->insert($data);
            }
        }
        elseif($data['user_type'] == '3') {
            $data = array('phone_number' => $data['phone_number'], 'otp_count' => $data['otp_count'], 'otp_number' => $data['otp_number'], 'user_type' => 3);

            $builder = $this->db->table('user_otp');
            $builder->select("otp_count");
            $builder->where('phone_number', $data['phone_number'])->where('user_type', 3);
            $query  = $builder->get();
            $result = $query->getResultArray();

            if (!empty($result)) {
                //print_r($data['otp_count']);exit();
                $builder_update = $this->db->table('user_otp');
                $builder_update->where('phone_number', $data['phone_number'])->where('user_type', 3)->set(array('otp_count' => $data['otp_count'], 'otp_number' => $data['otp_number']))->update();
            } else {
                // print_r($data);exit();
                $builder_insert = $this->db->table('user_otp');
                $builder_insert->insert($data);
            }
        }elseif($data['user_type'] == '4') {
            $data = array('phone_number' => $data['phone_number'], 'otp_count' => $data['otp_count'], 'otp_number' => $data['otp_number'], 'user_type' => 4);

            $builder = $this->db->table('user_otp');
            $builder->select("otp_count");
            $builder->where('phone_number', $data['phone_number'])->where('user_type', 4);
            $query  = $builder->get();
            $result = $query->getResultArray();

            if (!empty($result)) {
                //print_r($data['otp_count']);exit();
                $builder_update = $this->db->table('user_otp');
                $builder_update->where('phone_number', $data['phone_number'])->where('user_type', 4)->set(array('otp_count' => $data['otp_count'], 'otp_number' => $data['otp_number']))->update();
            } else {
                // print_r($data);exit();
                $builder_insert = $this->db->table('user_otp');
                $builder_insert->insert($data);
            }
        }elseif($data['user_type'] == '6' && $data['phone_number'] != '') {
            $data = array('phone_number' => $data['phone_number'], 'otp_count' => $data['otp_count'], 'otp_number' => $data['otp_number'], 'user_type' => 6);

            $builder = $this->db->table('user_otp');
            $builder->select("otp_count");
            $builder->where('phone_number', $data['phone_number'])->where('user_type', 6);
            $query  = $builder->get();
            $result = $query->getResultArray();

            if (!empty($result)) {
                //print_r($data['otp_count']);exit();
                $builder_update = $this->db->table('user_otp');
                $builder_update->where('phone_number', $data['phone_number'])->where('user_type', 6)->set(array('otp_count' => $data['otp_count'], 'otp_number' => $data['otp_number']))->update();
            } else {
                // print_r($data);exit();
                $builder_insert = $this->db->table('user_otp');
                $builder_insert->insert($data);
            }
        }elseif($data['user_type'] == '6' && $data['email_id'] != '') {
            $data = array('email_id' => $data['email_id'], 'otp_count' => $data['otp_count'], 'otp_number' => $data['otp_number'], 'user_type' => 6);
            $builder = $this->db->table('user_otp');
            $builder->select("otp_count");
            $builder->where('email_id', $data['email_id'])->where('user_type', 6);
            $query  = $builder->get();
            $result = $query->getResultArray();

            if (!empty($result)) {
                //print_r($data['otp_count']);exit();
                $builder_update = $this->db->table('user_otp');
                $builder_update->where('email_id', $data['email_id'])->where('user_type', 2)->set(array('otp_count' => $data['otp_count'], 'otp_number' => $data['otp_number']))->update();
            } else {

                $builder_insert = $this->db->table('user_otp');
                $builder_insert->insert($data);
            }
        }
        return true;
    }
    //check with user otp
    public function check_with_user_otp($user_otp, $phone_number, $usertype)
    {
        $builder = $this->db->table('user_otp');
        $builder->select("id");
        if ($usertype == 1) {
            $builder->where('phone_number', $phone_number)->where('user_type', 1);
        } elseif($usertype == 2) {
            $builder->where('email_id', $phone_number)->where('user_type', 2);
        }else if ($usertype == 3) {
            $builder->where('phone_number', $phone_number)->where('user_type', 3);
        }else if ($usertype == 4) {
            $builder->where('phone_number', $phone_number)->where('user_type', 4);
        }else if ($usertype == 6) {
            $builder->where('phone_number', $phone_number)->where('user_type', 6);
        }

        $builder->where('otp_number', $user_otp);
        $query  = $builder->get();
        $result = $query->getResultArray();
        if (!empty($result)) {
            return 1;
        } else {
            return 0;
        }
    }
    //otp count check number
    public function otp_count_check_number($phone_number)
    {

        $builder = $this->db->table('user_otp');
        $builder->select("otp_count");
        $builder->where('phone_number', $phone_number)->where('user_type', 1)->like('date_time', date('Y-m-d'));
        $query  = $builder->get();
        $result = $query->getResultArray();
        if (!empty($result)) {
            return $result[0]['otp_count'];
        } else {
            return 0;
        }
    }
    //otp count check number
    public function otp_count_check_email($email)
    {
        $where = '(usertype = "2" OR usertype = "3" OR usertype = "4" )';
        $builder = $this->db->table('user_otp');
        $builder->select("otp_count");
        $builder->where('email_id', $email)->like('date_time', date('Y-m-d'));
        $builder->where($where);
        $query  = $builder->get();
        $result = $query->getResultArray();
        if (!empty($result)) {
            return $result[0]['otp_count'];
        } else {
            return 0;
        }
    }
    //otp count check number hr
    public function otp_count_check_number_hr($phone_number,$login_usertype)
    {

        $builder = $this->db->table('user_otp');
        $builder->select("otp_count");
        $builder->where('phone_number', $phone_number)->where('user_type', $login_usertype)->like('date_time', date('Y-m-d'));
        $query  = $builder->get();
        $result = $query->getResultArray();
        if (!empty($result)) {
            return $result[0]['otp_count'];
        } else {
            return 0;
        }
    }

    public function otp_count_check_number_faculty($phone_number,$login_usertype)
    {

        $builder = $this->db->table('user_otp');
        $builder->select("otp_count");
        $builder->where('phone_number', $phone_number)->where('user_type', $login_usertype)->like('date_time', date('Y-m-d'));
        $query  = $builder->get();
        $result = $query->getResultArray();
        if (!empty($result)) {
            return $result[0]['otp_count'];
        } else {
            return 0;
        }
    }
    // //otp count check number supervisor
    // public function otp_count_check_number_supervisor($phone_number)
    // {

    //     $builder = $this->db->table('user_otp');
    //     $builder->select("otp_count");
    //     $builder->where('phone_number', $phone_number)->where('user_type', 4)->like('date_time', date('Y-m-d'));
    //     $query  = $builder->get();
    //     $result = $query->getResultArray();
    //     if (!empty($result)) {
    //         return $result[0]['otp_count'];
    //     } else {
    //         return 0;
    //     }
    // }
    


    public function fetch_table_row($tablename, $where)
    {
        $builder = $this->db->table($tablename);
        $builder->select("*");
        $builder->where($where);
        $result = $builder->get();
        // echo "<br>". $this->db->getLastQuery(); die;
        if (count(array($result->getRowArray())) > 0) {
            return $result->getRow();
        } else {
            return false;
        }
    }
    public function RandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    //save otp reset
    public function otp_count_reset($data, $user_type)
    {
        if ($user_type == 1) {
            //print_r($data['otp_count']);exit();
            $builder_update = $this->db->table('user_otp');
            $builder_update->where('phone_number', $data)->set(array('otp_count' => 0))->update();
        } else {
            //print_r($data['otp_count']);exit();
            $builder_update = $this->db->table('user_otp');
            $builder_update->where('email_id', $data)->set(array('otp_count' => 0))->update();
        }
        return true;
    }

    public function fetch_table_data_userlogin($tablename,$where)
    {
        $builder = $this->db->table($tablename);
        $builder->select("userid");
        $builder->where($where);
        $result = $builder->get();
        //echo "<br>". $this->db->getLastQuery(); die;
        if(count(array($result->getResultArray()))>0){
            return $result->getResult();
        }
        else{
            return false;
        }
   
    }

    public function exisCheck_userlogin($tablename, $where,$usertypeIn=NULL)
    {
        
        $builder = $this->db->table($tablename);
        $builder->select("id,userid,company_id,name,usertype,status,password,active_status");
        $builder->where($where);
        if(!empty($usertypeIn))
        {
            $builder->whereIn('usertype',$usertypeIn);
        }
        $query = $builder->get();
                
        if (count($query->getResultArray()) == 1) {
            return $query->getRow();
        }
    }

    public function exisCheck_common($tablename, $where)
    {
        $builder = $this->db->table($tablename);
        $builder->select("id");
        $builder->where($where);
        $query = $builder->get();
        if (count($query->getResultArray()) == 1) {
            return $query->getRow();
        }
    }

    public function fetch_table_row_loginAuth($tablename, $where)
    {
        $builder = $this->db->table($tablename);
        $builder->select("id,userid,email,active_status,status");
        $builder->where($where);
        $result = $builder->get();
        // echo "<br>". $this->db->getLastQuery(); die;
        if (count(array($result->getRowArray())) > 0) {
            return $result->getRow();
        } else {
            return false;
        }
    }

    public function userlogin_loginAuth($email = NULL, $mobile = NULL, $user_type)
    {
        $builder = $this->db->table('userlogin');
        $builder->select("id,userid,company_id,usertype,name,username,password,candidate_firstname,candidate_lastname,industry_name,email,mobile,active_status,status,ref,salt_code");
        if (isset($email) && $user_type == '2') {
            $builder->where('email', $email);
            $builder->where('usertype', '2');
        }
        if (isset($email) && $user_type == '3') {
            $builder->where('email', $email);
            $builder->where('usertype', '3');
        }
        if (isset($email) && $user_type == '4') {
            $builder->where('email', $email);
            $builder->where('usertype', '4');
        }
        if (isset($mobile) && $user_type == '1') {
            $builder->where('mobile', $mobile);
            $builder->where('usertype', '1');
        }if (isset($email) && $user_type == '5') {
            $builder->where('email', $email);
            $builder->where('usertype', '5');
        }
            $builder->where('status', '1');

        $query = $builder->get();
        if (count($query->getResultArray()) == 1) {
            return $query->getRow();
        }
        return false;
    }

    public function table_row_common_userlogin($tablename, $where)
    {
        $builder = $this->db->table($tablename);
        $builder->select("id,userid,usertype,name,candidate_firstname,candidate_lastname,email,mobile,active_status,status,otp_count,otp,email_domain,company_id,industry_name");
        $builder->where($where);
        $query = $builder->get();
        if (count($query->getResultArray()) == 1) {
            return $query->getRow();
        }
    }
    public function fetch_emp_manage_admins($tablename, $where)
    {
        $builder = $this->db->table($tablename);
        $builder->select("id,userid,emp_user_id,add_user_id,emp_type,emp_name,emp_id,emp_mobile,emp_official_email,active_status,status");
        $builder->where($where);
        $query = $builder->get();
        if (count($query->getResultArray()) == 1) {
            return $query->getRow();
        }
    }
    

    
}
