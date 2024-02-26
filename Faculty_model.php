<?php
namespace App\Models;
use \CodeIgniter\Model;
use CodeIgniter\Database\BaseBuilder;

class Faculty_model extends Model {

    public function fetch_data_for_faculty_profile($tablename,$where)
    {
      $builder = $this->db->table($tablename);
          $builder->select("id,userid,faculty_name,faculty_email,faculty_mobile,faculty_college,faculty_college_other,email_otp_status,mobile_otp_status");
          $builder->where($where);
          $result = $builder->get();
          if(count($result->getResultArray())>0){
              return $result->getResult();
          }
          else{
              return false;
          }
     
    }

    public function data_count_fetch($tablename,$where)
    {
      $builder = $this->db->table($tablename);
      $builder->select("id");
      $builder->where($where);
      return $builder->countAllResults();
   
    }
    public function fetch_table_data_ongoing($tablename,$where,$order_by=NULL)
    {
          $current_date =date("Y-m-d");
          $builder = $this->db->table($tablename);
          $builder->select("can_applied_internship.id,can_personal_details.profile_full_name,can_applied_internship.payment_package_type,can_applied_internship.internship_id,can_applied_internship.college_reg_number,profile_completion_form.profile_company_name,employer_post_internship.profile,employer_post_internship.other_profile");
          $builder->join('employer_post_internship', 'employer_post_internship.internship_id = can_applied_internship.internship_id','left');
          $builder->join('can_personal_details', 'can_applied_internship.candidate_id = can_personal_details.userid','left');
          $builder->join('profile_completion_form', 'employer_post_internship.company_id = profile_completion_form.userid','left');
          $builder->where($where);
            $result = $builder->get();
            // echo "<br>". $this->db->getLastQuery(); die;
            return $result->getResult();
   
    }
    public function data_count_fetch_groupby($tablename,$where)
    {
      $builder = $this->db->table($tablename);
      $builder->select("id");
      $builder->where($where);
      $builder->groupBy('internship_id');
      return $builder->countAllResults();
   
    }

    public function get_master_name_profile($tablename,$id,$col_name)
    {
        $builder = $this->db->table($tablename);
        $builder->select($col_name);
        $builder->where('id',$id);
        $builder->where('status',1);
        $result = $builder->get();
        if(count(array($result->getResultArray()))>0){
            return $result->getRow($col_name);
        }
        else{
            return false;
        }
   
    }
    public function fetch_table_data_ongoing_internship($tablename,$where,$order_by=NULL)
    {
          $current_date =date("Y-m-d");
          $builder = $this->db->table($tablename);
          $builder->select("can_applied_internship.internship_id,profile_completion_form.profile_company_name,employer_post_internship.profile,employer_post_internship.other_profile,employer_post_internship.internship_startdate,employer_post_internship.internship_duration_type,employer_post_internship.internship_duration,COUNT(can_applied_internship.id)as candidate_count");
          $builder->join('employer_post_internship', 'employer_post_internship.internship_id = can_applied_internship.internship_id','left');
          $builder->join('profile_completion_form', 'employer_post_internship.company_id = profile_completion_form.userid','left');
          $builder->where($where);
          $builder->groupBy('can_applied_internship.internship_id');
            $result = $builder->get();
            // echo "<br>". $this->db->getLastQuery(); die;
            return $result->getResult();
   
    }
    public function fetch_table_data_ongoing_internship_candidate($tablename,$where,$order_by=NULL)
    {
          $current_date =date("Y-m-d");
          $builder = $this->db->table($tablename);
          $builder->select("can_applied_internship.id,can_applied_internship.candidate_id,can_personal_details.profile_full_name,can_applied_internship.internship_id,can_applied_internship.college_reg_number,can_applied_internship.certificate_issue_status,can_applied_internship.certificate_issued_sign,can_applied_internship.certificate_issue_date,profile_completion_form.profile_company_name,employer_post_internship.profile,employer_post_internship.other_profile,can_personal_details.profile_email,can_personal_details.profile_phone_number");
          $builder->join('employer_post_internship', 'employer_post_internship.internship_id = can_applied_internship.internship_id','left');
          $builder->join('can_personal_details', 'can_applied_internship.candidate_id = can_personal_details.userid','left');
          $builder->join('profile_completion_form', 'employer_post_internship.company_id = profile_completion_form.userid','left');
          $builder->where($where);
            $result = $builder->get();
            // echo "<br>". $this->db->getLastQuery(); die;
            return $result->getResult();
   
    }

    public function current_datetime()
    {
        date_default_timezone_set('Asia/Kolkata');
		$date = date('Y-m-d');
		$time = date('H:i:s');
		$datetime = $date . " " . $time;
        return $datetime;
    }

    public function insert_commen($tablename,$data)
    {
        $builder = $this->db->table($tablename);
        $builder->insert($data);
        if($this->db->affectedRows()==1)
        {
            //  echo "<br>". $this->db->getLastQuery(); die;
            return $this->db->insertID();
        }
        else
        {
            // echo "<br>". $this->db->getLastQuery(); die;
            return false;
        }
        
    }

    public function fetch_table_row($tablename,$where)
    {
        $builder = $this->db->table($tablename);
        $builder->select("*");
        $builder->where($where);
        $result = $builder->get();
        // echo "<br>". $this->db->getLastQuery(); die;
        if(count(array($result->getRowArray()))>0){
            return $result->getRow();
        }
        else{
            return false;
        }
   
    }

    public function fetch_table_data_for_all_limit($tablename,$where,$order_by=NULL)
    {
      $builder = $this->db->table($tablename);
          $builder->select("*");
          $builder->where($where);
          if($order_by!=NULL && isset($order_by['ordercolumn']) && isset($order_by['ordertype']))
          {
          $builder->orderBy($order_by['ordercolumn'],$order_by['ordertype'])->limit('1');
          // $builder->orderBy('can_education_details.education_end_year','desc')->limit('1');
          }
          // $builder->orderBy($order_by);
          $result = $builder->get();
          // echo "<br>". $this->db->getLastQuery(); die;
          if(count($result->getResultArray())>0){
              return $result->getResult();
          }
          else{
              return false;
          }
     
    }

    public function get_master_commen_for_all($tablename,$where,$col_name)
    {
        $builder = $this->db->table($tablename);
        $builder->select($col_name);
        $builder->where($where);
        $result = $builder->get();
        if(count($result->getResultArray())>0){
            return $result->getRow($col_name);
        }
        else{
            return false;
        }
   
    }
    public function fetch_table_data_for_all($tablename,$where,$order_by=NULL)
  {
    $builder = $this->db->table($tablename);
        $builder->select("*");
        $builder->where($where);
        if($order_by!=NULL && isset($order_by['ordercolumn']) && isset($order_by['ordertype']))
        {
        $builder->orderBy($order_by['ordercolumn'],$order_by['ordertype']);
        }
        // $builder->orderBy($order_by);
        $result = $builder->get();
        // echo "<br>". $this->db->getLastQuery(); die;
        if(count($result->getResultArray())>0){
            return $result->getResult();
        }
        else{
            return false;
        }
   
  }
    public function get_master_name1($tablename,$id,$col_name)
    {
        $builder = $this->db->table($tablename);
        $builder->select($col_name);
        $builder->where('id',$id);
        $builder->where('status',1);
        $result = $builder->get();
        if(count(array($result->getResultArray()))>0){
            return $result->getRow($col_name);
        }
        else{
            return false;
        }
   
    }
    public function fetch_table_data_for_log($tablename,$where,$order_by=NULL,$total=NULL,$start_id=NULL)
    {
      $builder = $this->db->table($tablename);
          $builder->select("*");
          $builder->where($where);
          if($order_by!=NULL && isset($order_by['ordercolumn']) && isset($order_by['ordertype']))
          {
          $builder->orderBy($order_by['ordercolumn'],$order_by['ordertype']);
          }
          if(isset($total) && isset($start_id)){
            $builder->limit($total,$start_id);
        }
          $result = $builder->get();
        //   echo "<br>". $this->db->getLastQuery(); die;
          if(count($result->getResultArray())>0){
              return $result->getResult();
          }
          else{
              return false;
          }
     
    }
      //duplicate number
      function duplicate_number($number,$user_id)
      {
           $builder = $this->db->table('faculty_reg_data');
           $builder->select('faculty_reg_data.faculty_mobile,userlogin.usertype'); 
           $builder->join('userlogin', 'userlogin.userid = faculty_reg_data.userid','left');
           $builder->where('faculty_reg_data.faculty_mobile',$number); 
           $builder->where('faculty_reg_data.userid !=',$user_id); 
           $builder->where('userlogin.usertype','6'); 
           $result = $builder->get();
          return $result->getResultArray();
      }

            //check otp count
     public function otp_count_check($email,$usertype)
     {
        
         $builder = $this->db->table('user_otp');
         $builder->select("otp_count");
         $builder->where('phone_number',$email)->where('user_type',$usertype)->like('date_time',date('Y-m-d'));
         $query  = $builder->get();
        //  echo "<br>". $this->db->getLastQuery(); die;
         $result = $query->getResultArray();
         if (!empty($result))
          {
            return $result[0]['otp_count'];
          }else
          {
             return 0;
          }

     }

      
    //save otp
    public function otp_count_save($data)
    { 

            $builder = $this->db->table('user_otp');
            $builder->select("otp_count");
            $builder->where('phone_number',$data['phone_number']);
            $builder->where('user_type',$data['user_type']);
            $query  = $builder->get();
            $result = $query->getResultArray();

            if (!empty($result))
             {
               //print_r($data['otp_count']);exit();
               $builder_update = $this->db->table('user_otp');
               $builder_update->where('phone_number',$data['phone_number'])->where('user_type',$data['user_type'])->set(array('otp_count'=>$data['otp_count'],'otp_number'=>$data['otp_number']))->update();
             }else
             {
               // print_r($data);exit();
                $builder_insert = $this->db->table('user_otp');
                $builder_insert->insert($data);
             } 
             
            
            return true;
        
    }
    public function fetch_table_data_col($tablename,$where,$col_data)
    {
      $builder = $this->db->table($tablename);
          $builder->select($col_data);
          $builder->where($where);
        //   $builder->orderBy("id", "desc");
          $result = $builder->get();
        //   echo "<br>". $this->db->getLastQuery(); die;
          if(count($result->getResultArray())>0){
              return $result->getResult();
          }
          else{
              return false;
          }
     
    }
    public function fetch_table_data_col_where_in($tablename,$where,$candidate_id,$col_data)
    {
      $candidate = "FIND_IN_SET('" . $candidate_id . "', candidate_id)";
      $builder = $this->db->table($tablename);
          $builder->select($col_data);
          $builder->where($where);
          $builder->where($candidate);
        //   $builder->orderBy("id", "desc");
          $result = $builder->get();
          // echo "<br>". $this->db->getLastQuery(); die;
          if(count($result->getResultArray())>0){
              return $result->getResult();
          }
          else{
              return false;
          }
     
    }


    public function fetch_table_row_col($tablename,$where,$col_data)
    {
        $builder = $this->db->table($tablename);
        $builder->select($col_data);
        $builder->where($where);
        $result = $builder->get();
        // echo "<br>". $this->db->getLastQuery(); die;
        if(count(array($result->getRowArray()))>0){
            return $result->getRow();
        }
        else{
            return false;
        }
   
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

         //duplicate email
         function duplicate_email($email,$user_id)
         {
              $builder = $this->db->table('faculty_reg_data');
              $builder->select('faculty_reg_data.faculty_email ,userlogin.usertype'); 
              $builder->join('userlogin', 'userlogin.userid = faculty_reg_data.userid','left');
              $builder->where('faculty_reg_data.faculty_email ',$email); 
              $builder->where('faculty_reg_data.userid !=',$user_id); 
              $builder->where('userlogin.usertype','6'); 
              $result = $builder->get();
             return $result->getResultArray();
         }

         public function otp_count_check_email($email,$usertype)
         {
            
             $builder = $this->db->table('user_otp');
             $builder->select("otp_count");
             $builder->where('email_id',$email)->where('user_type',$usertype)->like('date_time',date('Y-m-d'));
             $query  = $builder->get();
             $result = $query->getResultArray();
             if (!empty($result))
              {
                return $result[0]['otp_count'];
              }else
              {
                 return 0;
              }
           
         }

               //save otp
    public function otp_count_save_email($data,$usertype)
    { 

            $builder = $this->db->table('user_otp');
            $builder->select("otp_count");
            $builder->where('email_id',$data['email_id'])->where('user_type',$usertype);
            $query  = $builder->get();
            $result = $query->getResultArray();

            if (!empty($result))
             {
               //print_r($data['otp_count']);exit();
               $builder_update = $this->db->table('user_otp');
               $builder_update->where('email_id',$data['email_id'])->where('user_type',$usertype)->set(array('otp_count'=>$data['otp_count'],'otp_number'=>$data['otp_number']))->update();
             }else
             {
               // print_r($data);exit();
                $builder_insert = $this->db->table('user_otp');
                $builder_insert->insert($data);
             } 
             
            
            return true;
            }  

            public function delete_commen($tablename,$where){
                $builder = $this->db->table($tablename);
                $builder->where($where);
                $builder->delete();
                // echo "<br>". $this->db->getLastQuery(); die;
                if($this->db->affectedRows()==1)
                {
                    return true;
                }
                    return false;
            }

            public function fetch_table_data_visited_internship($tablename,$where,$order_by=NULL)
            {
                 
                  $builder = $this->db->table($tablename);
                  $builder->select("faculty_visited_data_final.*,profile_completion_form.profile_company_name,employer_post_internship.profile,employer_post_internship.other_profile,profile_completion_form.profile_company_logo,employer_post_internship.internship_duration_type,employer_post_internship.internship_duration,employer_post_internship.assigned_to,faculty_reg_data.faculty_name,faculty_reg_data.faculty_college_other");
                  $builder->join('employer_post_internship', 'employer_post_internship.internship_id = faculty_visited_data_final.internship_id','left');
                  $builder->join('profile_completion_form', 'employer_post_internship.company_id = profile_completion_form.userid','left');
                  $builder->join('faculty_reg_data', 'faculty_visited_data_final.faculty_id = faculty_reg_data.userid','left');
                  $builder->where($where);
                  $result = $builder->get();
                    // echo "<br>". $this->db->getLastQuery(); die;
                    return $result->getResult();
           
            }
            public function fetch_table_data_visited_log($tablename,$where,$order_by=NULL)
            {
                 
                  $builder = $this->db->table($tablename);
                  $builder->select("faculty_visited_data_final.internship_id,faculty_visited_data_final.faculty_id,profile_completion_form.profile_company_name,employer_post_internship.profile,employer_post_internship.other_profile,profile_completion_form.profile_company_logo,employer_post_internship.internship_startdate,employer_post_internship.internship_duration,employer_post_internship.internship_duration_type");
                  $builder->join('employer_post_internship', 'employer_post_internship.internship_id = faculty_visited_data_final.internship_id','left');
                  $builder->join('profile_completion_form', 'employer_post_internship.company_id = profile_completion_form.userid','left');
                 
                  $builder->where($where);
                  $builder->groupBy('faculty_visited_data_final.internship_id');
                  $result = $builder->get();
                    // echo "<br>". $this->db->getLastQuery(); die;
                    return $result->getResult();
           
            }
            public function get_view_log_data($tablename,$where,$order_by=NULL)
            {
                 
                  $builder = $this->db->table($tablename);
                  $builder->select("id,visited_date,candidate_id");
                
                  $builder->where($where);
                 $result = $builder->get();
                    // echo "<br>". $this->db->getLastQuery(); die;
                    return $result->getResult();
           
            }

            public function fetch_table_data_visit_pending($tablename,$where)
            {
                // $where="select can_applied_internship.internship_id from can_applied_internship join faculty_visited_data_final on can_applied_internship.internship_id != faculty_visited_data_final.internship_id where can_applied_internship.faculty_id = '623022208004319' and ((can_applied_internship.complete_type=0 or can_applied_internship.complete_type=1) or alkdfjaslkfjaslfjd ) group by can_applied_internship.internship_id;";
                  $current_date =date("Y-m-d");
                  $builder = $this->db->table($tablename);
                  $builder->select("can_applied_internship.internship_id");
                  //$builder->join('faculty_visited_data_final', 'can_applied_internship.internship_id != faculty_visited_data_final.internship_id','left');
                  $builder->join('employer_post_internship', 'can_applied_internship.internship_id = employer_post_internship.internship_id','left');
                
                  $builder->where($where);
                  $builder->whereNotIn('can_applied_internship.internship_id', function(BaseBuilder $builder) {
          return $builder->select('faculty_visited_data_final.internship_id', false)->from('faculty_visited_data_final');
      });
                //   $builder->where('employer_post_internship.internship_startdate >', $current_date);
                  $builder->groupBy('can_applied_internship.internship_id');
                  // echo "<br>". $this->db->getLastQuery(); die;
      // return $builder->countAllResults();
                    $result = $builder->get();
                 //    echo "<br>". $this->db->getLastQuery(); die;
                    return $result->getResult();
           
            }
            public function fetch_table_data_ongoing_internship_visited($tablename,$where,$order_by=NULL)
            {
                  $current_date =date("Y-m-d");
                  $builder = $this->db->table($tablename);
                  $builder->select("faculty_visited_data_final.internship_id,faculty_visited_data_final.visited_date,profile_completion_form.profile_company_name,employer_post_internship.profile,employer_post_internship.other_profile,employer_post_internship.internship_startdate,employer_post_internship.internship_duration_type,employer_post_internship.internship_duration");
                  $builder->join('employer_post_internship', 'employer_post_internship.internship_id = faculty_visited_data_final.internship_id','left');
                  $builder->join('profile_completion_form', 'employer_post_internship.company_id = profile_completion_form.userid','left');
                  $builder->where($where);
                  $builder->groupBy('faculty_visited_data_final.internship_id');
                    $result = $builder->get();
                    // echo "<br>". $this->db->getLastQuery(); die;
                    return $result->getResult();
           
            }

            public function fetch_table_data_visit_pending1($tablename,$where,$order_by=NULL)
            {
                // $where="select can_applied_internship.internship_id from can_applied_internship join faculty_visited_data_final on can_applied_internship.internship_id != faculty_visited_data_final.internship_id where can_applied_internship.faculty_id = '623022208004319' and ((can_applied_internship.complete_type=0 or can_applied_internship.complete_type=1) or alkdfjaslkfjaslfjd ) group by can_applied_internship.internship_id;";
                  $current_date =date("Y-m-d");
                  $builder = $this->db->table($tablename);
                  $builder->select("can_applied_internship.internship_id,profile_completion_form.profile_company_name,employer_post_internship.profile,employer_post_internship.other_profile,employer_post_internship.internship_startdate,employer_post_internship.internship_duration_type,employer_post_internship.internship_duration");
                  //$builder->join('faculty_visited_data_final', 'can_applied_internship.internship_id != faculty_visited_data_final.internship_id','left');
                  $builder->join('employer_post_internship', 'can_applied_internship.internship_id = employer_post_internship.internship_id');
                  $builder->join('profile_completion_form', 'employer_post_internship.company_id = profile_completion_form.userid','left');
                  $builder->where($where);
                           $builder->whereNotIn('can_applied_internship.internship_id', function(BaseBuilder $builder) {
          return $builder->select('faculty_visited_data_final.internship_id', false)->from('faculty_visited_data_final');
      });
               
                  $builder->groupBy('can_applied_internship.internship_id');

                    $result = $builder->get();
                    // echo "<br>". $this->db->getLastQuery(); die;
                    return $result->getResult();
           
            }
            public function fetch_table_data_col_where_in_limit($tablename,$where,$candidate_id,$col_data)
            {
              $candidate = "FIND_IN_SET('" . $candidate_id . "', candidate_id)";
              $builder = $this->db->table($tablename);
                  $builder->select($col_data);
                  $builder->where($where);
                  $builder->where($candidate);
                  $builder->orderBy("visited_date", "desc");
                  $builder->limit(1);
                  $result = $builder->get();
                //   echo "<br>". $this->db->getLastQuery(); die;
                  if(count($result->getResultArray())>0){
                      return $result->getResult();
                  }
                  else{
                      return false;
                  }
             
            }
}




