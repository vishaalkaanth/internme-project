<?php
namespace App\Models;
use \CodeIgniter\Model;
use CodeIgniter\Database\BaseBuilder;

class Common_model extends Model {
    
      public function current_datetime()
    {
        date_default_timezone_set('Asia/Kolkata');
		$date = date('Y-m-d');
		$time = date('H:i:s');
		$datetime = $date . " " . $time;
        return $datetime;
    }

    function get_allmenus()
    {
        $session = session();
        $usertype =$session->get('usertype');
        $builder = $this->db->table('admin_menu');
        $builder->select('*');
        $builder->like('usertype',$usertype); 
        $builder->where('parentid','0');
        $builder->where('status','1');
        $query = $builder->get();
        //echo "<br>". $this->db->getLastQuery(); die;
        return $query->getResult();
    }
    function get_allsubmenus($menu_id)
    {
        $session = session();
        $usertype =$session->get('usertype');
        $builder = $this->db->table('admin_menu');
        $builder->select('*');
        $builder->like('usertype',$usertype);
        $builder->where('parentid',$menu_id);
        $builder->where('status','1');
        $query = $builder->get();
        //echo "<br>". $this->db->getLastQuery(); die;
        return $query->getResult();
    }
    public function data_count_fetch($tablename,$where)
    {
      $builder = $this->db->table($tablename);
      $builder->select("id");
      $builder->where($where);
    //   echo "<br>". $this->db->getLastQuery(); die;
      return $builder->countAllResults();
   
    }
    
    
    public function data_count_fetch1($tablename,$where)
    {
      $builder = $this->db->table($tablename);
      $builder->select("id");
      $builder->where($where);
      $builder->groupBy('userid');
    //   echo "<br>". $this->db->getLastQuery(); 
      // $result = $builder->get();
      return $builder->countAllResults();
   
    }
    public function emp_data_for_log($tablename,$where,$col_name)
    {
        $builder = $this->db->table($tablename);
        $builder->select($col_name);
        $builder->where($where);
        $builder->groupBy('company_id');
        $builder->orderBy('created_at','desc');
        $result = $builder->get();
        // echo "<br>". $this->db->getLastQuery();
       
            return $result->getResult();
     
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
           $builder->limit(1000);
          // $builder->orderBy($order_by);
          $result = $builder->get();
        //    echo "<br>". $this->db->getLastQuery(); die;
        
          if(count($result->getResultArray())>0){
              return $result->getResult();
          }
          else{
              return false;
          }
     
    }

    public function dashboard_registration_details($tablename,$where,$order_by=NULL)
    {
      
      $builder = $this->db->table($tablename);
          $builder->select("userid,profile_full_name,profile_phone_number,profile_email,profile_gender,g_location_name,created_at");
          $builder->where($where);
          if($order_by!=NULL && isset($order_by['ordercolumn']) && isset($order_by['ordertype']))
          {
          $builder->orderBy($order_by['ordercolumn'],$order_by['ordertype']);
          }
           $builder->limit(1000);
          // $builder->orderBy($order_by);
          $result = $builder->get();
        //    echo "<br>". $this->db->getLastQuery(); die;
        
          if(count($result->getResultArray())>0){
              return $result->getResult();
          }
          else{
              return false;
          }
     
    }
    public function dashboard_registration_hired_details($tablename,$where,$order_by=NULL)
    {
      
      $builder = $this->db->table($tablename);
          $builder->select("can_personal_details.userid,can_personal_details.profile_full_name,can_personal_details.profile_phone_number,can_personal_details.profile_email,can_personal_details.profile_gender,can_personal_details.g_location_name,can_personal_details.created_at");
          $builder->join('can_applied_internship', 'can_applied_internship.candidate_id = can_personal_details.userid');
          $builder->where($where);
          $builder->where('can_applied_internship.application_status',2);
          
          
          if($order_by!=NULL && isset($order_by['ordercolumn']) && isset($order_by['ordertype']))
          {
          $builder->orderBy($order_by['ordercolumn'],$order_by['ordertype']);
          }
           $builder->limit(1000);
          // $builder->orderBy($order_by);
          $builder->groupBy('can_personal_details.userid');
          $result = $builder->get();
            //echo "<br>". $this->db->getLastQuery(); die;
        
          if(count($result->getResultArray())>0){
              return $result->getResult();
          }
          else{
              return false;
          }
     
    }
       public function dashboard_registration_intenship_completed_details($tablename,$where,$order_by=NULL)
    {
      
      $builder = $this->db->table($tablename);
          $builder->select("can_personal_details.userid,can_personal_details.profile_full_name,can_personal_details.profile_phone_number,can_personal_details.profile_email,can_personal_details.profile_gender,can_personal_details.g_location_name,can_personal_details.created_at");
          $builder->join('can_applied_internship', 'can_applied_internship.candidate_id = can_personal_details.userid');
          $builder->where($where);
          $builder->where('can_applied_internship.complete_status',1);
          $builder->where('can_applied_internship.complete_type',1);
          if($order_by!=NULL && isset($order_by['ordercolumn']) && isset($order_by['ordertype']))
          {
            $builder->orderBy($order_by['ordercolumn'],$order_by['ordertype']);
          }
           $builder->limit(1000);
          // $builder->orderBy($order_by);
          $builder->groupBy('can_personal_details.userid');
          $result = $builder->get();
            //echo "<br>". $this->db->getLastQuery(); die;
        
          if(count($result->getResultArray())>0){
              return $result->getResult();
          }
          else{
              return false;
          }
     
    }
    
    public function get_internship_hired_list_by_filter($start_date,$end_date,$college_list=NULL,$where=NULL,$type=NULL)
{  
    
    $builder = $this->db->table('can_personal_details');
    $builder->select("can_personal_details.*");
    if(isset($college_list) && !empty($college_list)){
        $builder->join('can_education_details', 'can_personal_details.userid = can_education_details.userid');
    }
    $builder->join('can_applied_internship', 'can_applied_internship.candidate_id = can_personal_details.userid');
    $builder->join('can_profile_log', 'can_profile_log.candidate_id = can_personal_details.userid');
    $builder->join('userlogin', 'userlogin.userid = can_personal_details.userid');
    $builder->where('date(can_personal_details.created_at)>=',$start_date);
    $builder->where('date(can_personal_details.created_at)<=',$end_date);
    if($type==3)
    {
       $builder->where('can_applied_internship.application_status',2);
    }
    elseif($type==4)
    {
         $builder->where('can_applied_internship.complete_status',1);
          $builder->where('can_applied_internship.complete_type',1);
    }       
    
    $builder->where('can_personal_details.status',1);
    if(isset($where) && !empty($where)){
        $builder->where($where);
    }
    if(isset($college_list) && !empty($college_list)){
        
        
        //$w = "can_education_details.education_end_year in (SELECT MAX(education_end_year) FROM `can_education_details` WHERE `education_college_name` IN ($college_list) AND `status` = 1 GROUP BY `userid`)";
        //$w = "can_education_details.userid='122121211573881' ORDER by can_education_details.education_end_year DESC limit 1";
        
        //$builder->where($w);
        //$builder->whereIn("SELECT MAX(education_end_year) FROM `can_education_details` WHERE `education_college_name` IN ($college_list) AND `status` = 1 GROUP BY `userid`");
        
        /*$builder->whereIn('can_education_details.education_end_year', function(BaseBuilder $builder) {
//
echo "adsf".$college_list;
            return $builder->select('MAX(education_end_year)', false)->from('can_education_details')->where("education_college_name IN (792)" )->where('status',1)->groupBy('userid');
        });
        */
   
        $builder->where("can_education_details.education_college_name IN ($college_list) " );
        $builder->where("can_education_details.status",1);
    }
    $builder->groupBy('can_personal_details.userid');
    $builder->orderBy('can_personal_details.id','DESC');
    $builder->limit(1000);
    $result = $builder->get();
      //echo "<br>". $this->db->getLastQuery(); die;
    if(count($result->getResultArray())>0){
        return $result->getResult();
     }
     else{
        return false;
     }
}
public function get_internship_hired_list_by_filter_all($start_date,$end_date,$college_list=NULL,$where=NULL)
{  
    
    $builder = $this->db->table('can_personal_details');
    $builder->select("can_personal_details.*");
    if(isset($college_list) && !empty($college_list)){
        $builder->join('can_education_details', 'can_personal_details.userid = can_education_details.userid');
    }
    
    $builder->join('can_profile_log', 'can_profile_log.candidate_id = can_personal_details.userid');
    $builder->join('userlogin', 'userlogin.userid = can_personal_details.userid');
    $builder->where('date(can_personal_details.created_at)>=',$start_date);
    $builder->where('date(can_personal_details.created_at)<=',$end_date);
    if(isset($college_list) && !empty($college_list)){
        $builder->where("can_education_details.education_college_name IN ($college_list)" );
        $builder->where("can_education_details.status",1);
    }
    $builder->where('can_personal_details.status',1);
    if(isset($where) && !empty($where)){
        $builder->where($where);
    }
    $builder->groupBy('can_personal_details.userid');
    $builder->orderBy('can_personal_details.id','DESC');
    // $builder->limit(1000);
    $result = $builder->get();
    //  echo "<br>". $this->db->getLastQuery(); die;
    if(count($result->getResultArray())>0){
        return $result->getResult();
     }
     else{
        return false;
     }
}

    

    public function fetch_table_data_for_all_groupby($tablename,$where,$order_by=NULL)
    {
      
      $builder = $this->db->table($tablename);
          $builder->select("*");
          $builder->where($where);
          if($order_by!=NULL && isset($order_by['ordercolumn']) && isset($order_by['ordertype']))
          {
          $builder->orderBy($order_by['ordercolumn'],$order_by['ordertype']);
          }
          $builder->groupBy('profile_completion_form.profile_company_name');
        //    $builder->limit(1000);
          // $builder->orderBy($order_by);
          $result = $builder->get();
        //    echo "<br>". $this->db->getLastQuery(); die;
        
          if(count($result->getResultArray())>0){
              return $result->getResult();
          }
          else{
              return false;
          }
     
    }

    public function fetch_table_data_for_all_details($tablename,$where,$order_by=NULL)
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
        //    echo "<br>". $this->db->getLastQuery(); die;
        
          if(count($result->getResultArray())>0){
              return $result->getResult();
          }
          else{
              return false;
          }
     
    }

    public function fetch_table_data_for_all_college($tablename,$where,$order_by=NULL)
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
        //    echo "<br>". $this->db->getLastQuery(); die;
        
          if(count($result->getResultArray())>0){
              return $result->getResult();
          }
          else{
              return false;
          }
     
    }

    public function fetch_table_data_for_all_count($tablename,$where)
    {
      
      $builder = $this->db->table($tablename);
          $builder->select("id");
          $builder->where($where);
          $result = $builder->get();
        //   echo "<br>". $this->db->getLastQuery(); die;
          if(count($result->getResultArray())>0){
              return count($result->getResultArray());
          }
          else{
              return 0;
          }
     
    }
    public function candidate_completion_profile($tablename,$where,$order_by=NULL)
    {    
       $builder = $this->db->table($tablename);
       $builder->select("can_personal_details.userid, can_personal_details.profile_full_name,can_personal_details.profile_phone_number,can_personal_details.profile_email,can_personal_details.profile_gender,can_personal_details.g_location_name,can_personal_details.created_at,can_education_details.education_course_other");
       $builder->join('can_education_details', 'can_education_details.userid = can_personal_details.userid','left');
       $builder->where($where);
       if($order_by!=NULL && isset($order_by['ordercolumn']) && isset($order_by['ordertype']))
          {
          $builder->orderBy($order_by['ordercolumn'],$order_by['ordertype']);
          }
    //    $builder->where('status',1);
       $builder->groupBy('can_education_details.userid');
       $builder->limit(1000);
       $result = $builder->get();
       //  return $result->getResult();
        //   echo "<br>". $this->db->getLastQuery(); die;
       if(count($result->getResultArray())>0){
           return $result->getResult();
       }
       else{
           return false;
       }
   }
   
   
   
   
   public function candidate_hired_completion_profile($tablename,$where,$order_by=NULL)
    {    
       $builder = $this->db->table($tablename);
       $builder->select("can_personal_details.userid, can_personal_details.profile_full_name,can_personal_details.profile_phone_number,can_personal_details.profile_email,can_personal_details.profile_gender,can_personal_details.g_location_name,can_personal_details.created_at,can_education_details.education_course_other");
       $builder->join('can_education_details', 'can_education_details.userid = can_personal_details.userid','left');
       $builder->join('can_applied_internship', 'can_applied_internship.candidate_id = can_personal_details.userid');
       $builder->where($where);
       $builder->where('can_applied_internship.application_status',2);
       if($order_by!=NULL && isset($order_by['ordercolumn']) && isset($order_by['ordertype']))
          {
          $builder->orderBy($order_by['ordercolumn'],$order_by['ordertype']);
          }
    //    $builder->where('status',1);
       $builder->groupBy('can_education_details.userid');
       $builder->limit(1000);
       $result = $builder->get();
       //  return $result->getResult();
        //   echo "<br>". $this->db->getLastQuery(); die;
       if(count($result->getResultArray())>0){
           return $result->getResult();
       }
       else{
           return false;
       }
   }
    public function candidate_intenship_completed_details($tablename,$where,$order_by=NULL)
    {    
       $builder = $this->db->table($tablename);
       $builder->select("can_personal_details.userid, can_personal_details.profile_full_name,can_personal_details.profile_phone_number,can_personal_details.profile_email,can_personal_details.profile_gender,can_personal_details.g_location_name,can_personal_details.created_at,can_education_details.education_course_other");
       $builder->join('can_education_details', 'can_education_details.userid = can_personal_details.userid','left');
       $builder->join('can_applied_internship', 'can_applied_internship.candidate_id = can_personal_details.userid');
       $builder->where($where);
       $builder->where('can_applied_internship.complete_status',1);
          $builder->where('can_applied_internship.complete_type',1);
       if($order_by!=NULL && isset($order_by['ordercolumn']) && isset($order_by['ordertype']))
          {
          $builder->orderBy($order_by['ordercolumn'],$order_by['ordertype']);
          }
    //    $builder->where('status',1);
       $builder->groupBy('can_education_details.userid');
       $builder->limit(1000);
       $result = $builder->get();
       //  return $result->getResult();
        //   echo "<br>". $this->db->getLastQuery(); die;
       if(count($result->getResultArray())>0){
           return $result->getResult();
       }
       else{
           return false;
       }
   }

   public function candidate_completion_profile_total($tablename,$where,$order_by=NULL)
    {    
       $builder = $this->db->table($tablename);
       $builder->select("can_personal_details.id,can_education_details.userid");
       $builder->join('can_education_details', 'can_education_details.userid = can_personal_details.userid','left');
       $builder->where($where);
       if($order_by!=NULL && isset($order_by['ordercolumn']) && isset($order_by['ordertype']))
          {
          $builder->orderBy($order_by['ordercolumn'],$order_by['ordertype']);
          }
    //    $builder->where('status',1);
       $builder->groupBy('can_education_details.userid');
       $result = $builder->get();
       //  return $result->getResult();
        //   echo "<br>". $this->db->getLastQuery(); die;
       if(count($result->getResultArray())>0){
           return $result->getResult();
       }
       else{
           return false;
       }
   }

   //------------------------------------------------------------------------------
   //Admin dashboard analysis - all applications - candidate list - start
    public function get_application_details()
        {
            $builder = $this->db->table("can_applied_internship");
            $builder->select("can_applied_internship.application_status,can_applied_internship.complete_status,can_applied_internship.complete_type");
            // $builder->join("can_personal_details","can_applied_internship.candidate_id = can_personal_details.userid","left"); 
            // $builder->join("employer_post_internship","can_applied_internship.internship_id = employer_post_internship.internship_id","left");           
            // $builder->orderBy("can_applied_internship.id", "desc");
            // $builder->limit(1000);
            $result = $builder->get();
            return $result->getResult();
        }
   //Employer dashboard analysis - all applications - candidate list - end
  //------------------------------------------------------------------------------
   

   public function candidate_completion_profile_count($tablename,$where)
    {    
       $builder = $this->db->table($tablename);
       $builder->select("can_education_details.userid,can_education_details.userid");
       $builder->join('can_education_details', 'can_education_details.userid = can_personal_details.userid','left');
       $builder->where($where);
       $builder->groupBy('can_education_details.userid');
       $result = $builder->get();
       if(count($result->getResultArray())>0){
            return count($result->getResultArray());
       }
       else{
           return 0;
       }
   }
    public function get_master_name1($tablename,$id)
    {
        $builder = $this->db->table($tablename);
        $builder->select('profile');
        $builder->where('id',$id);
        $builder->where('status',1);
        $result = $builder->get();
        // echo "<br>". $this->db->getLastQuery(); die;
        if(count($result->getResultArray())>0){
            return $result->getRow('profile');
        }
        else{
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
    public function fetch_table_data($tablename,$where)
    {
        $builder = $this->db->table($tablename);
        $builder->select("*");
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
    public function get_master_name($tablename,$id,$col_name)
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
    public function get_master_college_name($tablename,$id)
    {
        $builder = $this->db->table($tablename);
        $builder->select('college_name');
        $builder->where('id',$id);
        $builder->where('status',1);
        $result = $builder->get();
        // echo "<br>". $this->db->getLastQuery(); die;
        if(count($result->getResultArray())>0){
            return $result->getRow('college_name');
        }
        else{
            return false;
        }
   
  }

  public function candidate_active_new($tablename,$where)
 {    
    $builder = $this->db->table($tablename);
    $builder->select("userlogin.*,can_profile_log.candidate_id,can_personal_details.*");
    $builder->join('can_profile_log', 'can_profile_log.candidate_id = userlogin.userid');
    $builder->join('can_personal_details', 'can_personal_details.userid = userlogin.userid');
    $builder->where($where);
    $builder->groupBy('can_profile_log.candidate_id');
    $builder->orderBy('can_profile_log.id','DESC');
    $builder->limit(1000);
    $result = $builder->get();
    //   return $result->getResult();
    //   echo "<br>". $this->db->getLastQuery(); die;
    if(count($result->getResultArray())>0){
        return $result->getResult();
    }
    else{
        return false;
    }
}

public function candidate_active_new_count($tablename,$where)
{    
   $builder = $this->db->table($tablename);
   $builder->select("userlogin.userid,can_profile_log.candidate_id,can_personal_details.userid");
   $builder->join('can_profile_log', 'can_profile_log.candidate_id = userlogin.userid');
   $builder->join('can_personal_details', 'can_personal_details.userid = userlogin.userid');
   $builder->where($where);
   $builder->groupBy('can_profile_log.candidate_id');
   $builder->orderBy('can_profile_log.id','DESC');
   $result = $builder->get();
   if(count($result->getResultArray())>0){
       return count($result->getResultArray());
   }
   else{
       return 0;
   }
}

  public function candidate_active($tablename,$where)
 {    
    $builder = $this->db->table($tablename);
    $builder->select("can_personal_details.*,can_profile_log.candidate_id,userlogin.userid");
    $builder->join('can_profile_log', 'can_profile_log.candidate_id = can_personal_details.userid');
    $builder->join('userlogin', 'userlogin.userid = can_personal_details.userid');
    $builder->where($where);
    $builder->groupBy('can_profile_log.candidate_id');
    $result = $builder->get();
    //   return $result->getResult();
    //   echo "<br>". $this->db->getLastQuery(); die;
    if(count($result->getResultArray())>0){
        return $result->getResult();
    }
    else{
        return false;
    }
}
public function candidate_idle($tablename,$where)
{    
    $builder = $this->db->table($tablename);
    $builder->select("can_personal_details.*,userlogin.*");
    $builder->join('can_personal_details', 'userlogin.userid = can_personal_details.userid');
    $builder->join('can_profile_log', 'can_profile_log.candidate_id = userlogin.userid');
    $builder->where($where);
    $builder->groupBy('can_profile_log.candidate_id');
    $builder->orderBy('can_profile_log.id','DESC');
    $builder->limit(1000);
    $result = $builder->get();
    //  return $result->getResult();
        // echo "<br>". $this->db->getLastQuery(); die;
    if(count($result->getResultArray())>0){
    return $result->getResult();
    }
    else{
    return false;
    }
    
}

public function candidate_idle_count($tablename,$where)
{    
    $builder = $this->db->table($tablename);
    $builder->select("can_personal_details.userid,userlogin.userid");
    $builder->join('can_personal_details', 'userlogin.userid = can_personal_details.userid');
    $builder->join('can_profile_log', 'can_profile_log.candidate_id = userlogin.userid');
    $builder->where($where);
    $builder->groupBy('can_profile_log.candidate_id');
    $builder->orderBy('can_profile_log.id','DESC');
    $result = $builder->get();
    if(count($result->getResultArray())>0){
    return count($result->getResultArray());
    }
    else{
    return 0;
    }
    
}
public function candidate_inactive($tablename,$where)
{  
    
 date_default_timezone_set('Asia/Kolkata');
$prev_date = date('Y-m-d', strtotime(' -15 day'));  
$builder = $this->db->table($tablename);
$builder->select("userlogin.*,can_personal_details.*");
$builder->join('can_personal_details', 'userlogin.userid = can_personal_details.userid','left');
$builder->where('userlogin.usertype',1);
$builder->where($where);
$builder->groupBy('can_personal_details.userid');
$builder->orderBy('can_personal_details.id','DESC');
$builder->limit(1000);
$result = $builder->get();
 if(count($result->getResultArray())>0){
   return $result->getResult();
}
else{
   return false;
}
}

public function candidate_inactive_count($tablename,$where)
{  
    
 date_default_timezone_set('Asia/Kolkata');  
$builder = $this->db->table($tablename);
$builder->select("userlogin.userid,can_personal_details.userid");
$builder->join('can_personal_details', 'userlogin.userid = can_personal_details.userid','left');
$builder->where('userlogin.usertype',1);
$builder->where($where);
$builder->groupBy('can_personal_details.userid');
$builder->orderBy('can_personal_details.id','DESC');
$result = $builder->get();
 if(count($result->getResultArray())>0){
   return count($result->getResultArray());
}
else{
   return 0;
}
}

public function employer_active($tablename,$where)
 {    
        date_default_timezone_set('Asia/Kolkata');
        $prev_date = date('Y-m-d', strtotime(' -15 day'));
        // $builder = $this->db->table($tablename);
        // $builder->select("userlogin.userid,employer_post_internship.*,emp_hiring_log.*,profile_completion_form.*");
        // $builder->join('profile_completion_form', 'userlogin.userid = profile_completion_form.userid');
        // $builder->join('employer_post_internship', 'userlogin.userid = employer_post_internship.company_id');
        // $builder->join('emp_hiring_log', 'emp_hiring_log.company_id = userlogin.userid','left');
        // $builder->Where($where);
        // $builder->groupBy('emp_hiring_log.company_id');
        // $result = $builder->get();
        //  return $result->getResult();
            //   echo "<br>". $this->db->getLastQuery(); die;

        $where_post = "(employer_post_internship.updated_at> $prev_date OR employer_post_internship.created_at> $prev_date)";
        $builder = $this->db->table($tablename);
        $builder->select("userlogin.userid");
        $builder->join('employer_post_internship', 'userlogin.userid = employer_post_internship.company_id','left');
        $builder->where('userlogin.logged_in>', $prev_date);
        $builder->where($where_post);
        $builder->groupBy('userlogin.userid');
        $employer_post_internship =    $builder->getCompiledSelect(); 
        
        $where_emp = "(emp_hiring_log.updated_at> $prev_date OR emp_hiring_log.created_at> $prev_date)";
    
        $builder = $this->db->table($tablename);
        $builder->select("userlogin.userid");
        $builder->join('emp_hiring_log', 'userlogin.userid = emp_hiring_log.company_id','left');
        $builder->where('userlogin.logged_in>', $prev_date);
        $builder->where($where_emp);
        $builder->groupBy('userlogin.userid');
        $emp_hiring_log =    $builder->getCompiledSelect();
    
        $builder = $this->db->table($tablename);
        $builder->select("userlogin.userid,profile_completion_form.*");
        $builder->join('profile_completion_form', 'userlogin.userid = profile_completion_form.userid','left');
        $builder->where("userlogin.userid IN ($emp_hiring_log) OR userlogin.userid IN ($employer_post_internship)");
        $builder->where('userlogin.logged_in>', $prev_date);
        $builder->where('userlogin.usertype', '2');
        $builder->groupBy('userlogin.userid');
        $builder->orderBy('userlogin.id','DESC');
        // $builder->limit(1000);
        $result = $builder->get();
        if(count($result->getResultArray())>0){
            return $result->getResult();
        }
        else{
            return false;
        }
  
}



public function employer_report_idle($tablename,$prev_date)
{   
    
    $where_post = "(employer_post_internship.updated_at> $prev_date OR employer_post_internship.created_at> $prev_date)";
    $builder = $this->db->table($tablename);
    $builder->select("userlogin.userid");
    $builder->join('employer_post_internship', 'userlogin.userid = employer_post_internship.company_id','left');
        $builder->where('userlogin.logged_in>', $prev_date);
        $builder->where($where_post);
        $builder->groupBy('userlogin.userid');
    $employer_post_internship =    $builder->getCompiledSelect(); 
    
    $where_emp = "(emp_hiring_log.updated_at> $prev_date OR emp_hiring_log.created_at> $prev_date)";

    $builder = $this->db->table($tablename);
    $builder->select("userlogin.userid");
    $builder->join('emp_hiring_log', 'userlogin.userid = emp_hiring_log.company_id','left');
    $builder->where('userlogin.logged_in>', $prev_date);
        $builder->where($where_emp);
        $builder->groupBy('userlogin.userid');
    $emp_hiring_log =    $builder->getCompiledSelect();

    $builder = $this->db->table($tablename);
    $builder->select("userlogin.userid,profile_completion_form.*");
    $builder->join('profile_completion_form', 'userlogin.userid = profile_completion_form.userid','left');
    $builder->where("userlogin.userid NOT IN ($emp_hiring_log) AND userlogin.userid NOT IN ($employer_post_internship)");
    $builder->where('userlogin.logged_in>', $prev_date);
    $builder->where('userlogin.usertype', '2');
    $builder->groupBy('userlogin.userid');
    $builder->orderBy('userlogin.id','DESC');
    // $builder->limit(1000);
    $result = $builder->get();
    //  return $result->getResult();
    //    echo "<br>". $this->db->getLastQuery(); die;
    if(count($result->getResultArray())>0){
    return $result->getResult();
    }
    else{
    return false;
    }
    
}
// public function employer_report_idle_new($tablename,$idle_unique_id)
// {    
//     $builder = $this->db->table($tablename);
//     $builder->select("*");
//     // $builder->where($where);
//     $builder->whereNotIn('userid',$idle_unique_id);
//     $result = $builder->get();
//     //  return $result->getResult();
//     //   echo "<br>". $this->db->getLastQuery(); die;
//     if(count($result->getResultArray())>0){
//     return $result->getResult();
//     }
//     else{
//     return false;
//     }
    
// }

public function employer_inactive($tablename,$where)
{    
    date_default_timezone_set('Asia/Kolkata');
    $prev_date = date('Y-m-d', strtotime(' -15 day'));
    
    $builder = $this->db->table($tablename);
    $builder->select("userlogin.*,profile_completion_form.*");
    $builder->join('profile_completion_form', 'userlogin.userid = profile_completion_form.userid','left');
    // $builder->join('employer_post_internship', 'userlogin.userid = employer_post_internship.company_id','left');
    // $builder->join('emp_hiring_log', 'emp_hiring_log.company_id = employer_post_internship.company_id','left');
	$builder->where('userlogin.usertype',2);
    $builder->where($where);
	$builder->groupBy('profile_completion_form.userid');
    $builder->orderBy('profile_completion_form.id','DESC');
    // $builder->limit(1000);
    $result = $builder->get();
    //  return $result->getResult();
        //   echo "<br>". $this->db->getLastQuery(); die;
        if(count($result->getResultArray())>0){
        return $result->getResult();
        }
        else{
        return false;
        }
       
}
public function college_name_correction($tablename,$where)
{    
    
    $builder = $this->db->table($tablename);
    $builder->select("can_personal_details.*,can_education_details.*");
    $builder->join('can_education_details', 'can_personal_details.userid = can_education_details.userid','left');
    $builder->where('can_education_details.education_college_name','0');
    // $builder->where('can_education_details.education_college_name_other!=','');
    //  $builder->where('can_education_details.education_college_name_other IS NOT NULL', null, false);
    $builder->limit(3000);
    $result = $builder->get();
    //  return $result->getResult();
    //    echo "<br>". $this->db->getLastQuery(); die;
        if(count($result->getResultArray())>0){
        return $result->getResult();
        }
        else{
        return false;
        }
       
}

public function college_name_correction_count($tablename,$where)
{    
    
    $builder = $this->db->table($tablename);
    $builder->select("can_personal_details.userid,can_education_details.userid");
    $builder->join('can_education_details', 'can_personal_details.userid = can_education_details.userid','left');
    $builder->where('can_education_details.education_college_name','0');
    // $builder->where('can_education_details.education_college_name_other!=','');
    //  $builder->where('can_education_details.education_college_name_other IS NOT NULL', null, false);
   
    $result = $builder->get();
    //  return $result->getResult();
    //    echo "<br>". $this->db->getLastQuery(); die;
        if(count($result->getResultArray())>0){
        return count($result->getResultArray());
        }
        else{
        return 0;
        }
       
}
public function college_course($tablename,$where)
{    
    
    $builder = $this->db->table($tablename);
    $builder->select("can_personal_details.*,can_education_details.*");
    $builder->join('can_education_details', 'can_personal_details.userid = can_education_details.userid','left');
    $builder->where('can_education_details.education_course','0');
    // $builder->where('can_education_details.education_course_other!=',NULL);
    // $builder->where('can_education_details.education_course_other!=','');
    $builder->limit(3000);
    $result = $builder->get();
    //  return $result->getResult();
        //   echo "<br>". $this->db->getLastQuery(); die;
        if(count($result->getResultArray())>0){
        return $result->getResult();
        }
        else{
        return false;
        }
       
}
public function college_course_count($tablename,$where)
{    
    
    $builder = $this->db->table($tablename);
    $builder->select("can_personal_details.userid,can_education_details.userid");
    $builder->join('can_education_details', 'can_personal_details.userid = can_education_details.userid','left');
    $builder->where('can_education_details.education_course','0');
    // $builder->where('can_education_details.education_course_other!=',NULL);
    // $builder->where('can_education_details.education_course_other!=','');
    
    $result = $builder->get();
    //  return $result->getResult();
        //   echo "<br>". $this->db->getLastQuery(); die;
        if(count($result->getResultArray())>0){
            return count($result->getResultArray());
            }
            else{
            return 0;
            }
       
}
public function college_specialization($tablename,$where)
{    
    
    $builder = $this->db->table($tablename);
    $builder->select("can_personal_details.*,can_education_details.*");
    $builder->join('can_education_details', 'can_personal_details.userid = can_education_details.userid','left');
    $builder->where('can_education_details.education_specialization','0');
    // $builder->where('can_education_details.education_specialization_other!=',NULL);
    // $builder->where('can_education_details.education_specialization_other!=','');
    $builder->limit(3000);
    $result = $builder->get();
    //  return $result->getResult();
        //   echo "<br>". $this->db->getLastQuery(); die;
        if(count($result->getResultArray())>0){
        return $result->getResult();
        }
        else{
        return false;
        }
       
}
public function college_specialization_count($tablename,$where)
{    
    
    $builder = $this->db->table($tablename);
    $builder->select("can_personal_details.*,can_education_details.*");
    $builder->join('can_education_details', 'can_personal_details.userid = can_education_details.userid','left');
    $builder->where('can_education_details.education_specialization','0');
    // $builder->where('can_education_details.education_specialization_other!=',NULL);
    // $builder->where('can_education_details.education_specialization_other!=','');
   
    $result = $builder->get();
    //  return $result->getResult();
        //   echo "<br>". $this->db->getLastQuery(); die;
        if(count($result->getResultArray())>0){
            return count($result->getResultArray());
            }
            else{
            return 0;
            }
       
       
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
public function update_commen($tablename,$where,$data)
{
    $builder = $this->db->table($tablename);
    $builder->where($where);
    $builder->update($data);
    // return "<br>". $this->db->getLastQuery();
    if($this->db->affectedRows()==1)
    {
        return true;
    }
        return false;
}

// public function employer_report_idle_user($tablename,$where)
// {    
//     $builder = $this->db->table($tablename);
//     $builder->select("userlogin.userid");
//     $builder->where($where);
//     $result = $builder->get();
//     //  return $result->getResult();
//     //    echo "<br>". $this->db->getLastQuery(); die;
//     if(count($result->getResultArray())>0){
//     return $result->getResult();
//     }
//     else{
//     return false;
//     }
    
// }

// public function employer_report_idle_post($tablename,$where)
// {    
//     $builder = $this->db->table($tablename);
//     $builder->select("*");
//     $builder->where($where);
//     $result = $builder->get();
//     //  return $result->getResult();
//        echo "<br>". $this->db->getLastQuery(); die;
//     if(count($result->getResultArray())>0){
//     return $result->getResult();
//     }
//     else{
//     return false;
//     }
    
// }
public function check_msg_status($tablename,$sender_id)
{
    $where = "`receiver_id`= $sender_id  AND `message_status` = 1";
    $builder = $this->db->table($tablename);
    $builder->select("id");
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

public function candidate_inactive_report($tablename,$where,$add_search_start_date,$add_search_end_date)
{  
    
 date_default_timezone_set('Asia/Kolkata');
$prev_date = date('Y-m-d', strtotime(' -15 day'));  
$builder = $this->db->table($tablename);
$builder->select("userlogin.*,can_personal_details.*");
$builder->join('can_personal_details', 'userlogin.userid = can_personal_details.userid','left');
$builder->where('userlogin.usertype',1);
$builder->where('date(can_personal_details.created_at) >=',$add_search_start_date);
$builder->where('date(can_personal_details.created_at) <=',$add_search_end_date);
$builder->where($where);
$builder->where($where);
$builder->groupBy('can_personal_details.userid');
$builder->orderBy('can_personal_details.id','DESC');
$result = $builder->get();
 if(count($result->getResultArray())>0){
   return $result->getResult();
}
else{
   return false;
}
}

public function get_college_list_by_filter($start_date,$end_date,$college_list=NULL,$where=NULL)
{  
    
    $builder = $this->db->table('can_personal_details');
    $builder->select("can_personal_details.*");
    if(isset($college_list) && !empty($college_list)){
        $builder->join('can_education_details', 'can_personal_details.userid = can_education_details.userid');
    }
    
    $builder->join('can_profile_log', 'can_profile_log.candidate_id = can_personal_details.userid');
    $builder->join('userlogin', 'userlogin.userid = can_personal_details.userid');
    $builder->where('date(can_personal_details.created_at)>=',$start_date);
    $builder->where('date(can_personal_details.created_at)<=',$end_date);
    if(isset($college_list) && !empty($college_list)){
        $builder->where("can_education_details.education_college_name IN ($college_list)" );
    }
    $builder->where('can_personal_details.status',1);
    if(isset($where) && !empty($where)){
        $builder->where($where);
    }
    $builder->groupBy('can_personal_details.userid');
    $builder->orderBy('can_personal_details.id','DESC');
    $builder->limit(1000);
    $result = $builder->get();
    //  echo "<br>". $this->db->getLastQuery(); die;
    if(count($result->getResultArray())>0){
        return $result->getResult();
     }
     else{
        return false;
     }
}

public function get_college_list_by_filter_all($start_date,$end_date,$college_list=NULL,$where=NULL)
{  
    
    $builder = $this->db->table('can_personal_details');
    $builder->select("can_personal_details.*");
    if(isset($college_list) && !empty($college_list)){
        $builder->join('can_education_details', 'can_personal_details.userid = can_education_details.userid');
    }
    
    $builder->join('can_profile_log', 'can_profile_log.candidate_id = can_personal_details.userid');
    $builder->join('userlogin', 'userlogin.userid = can_personal_details.userid');
    $builder->where('date(can_personal_details.created_at)>=',$start_date);
    $builder->where('date(can_personal_details.created_at)<=',$end_date);
    if(isset($college_list) && !empty($college_list)){
        $builder->where("can_education_details.education_college_name IN ($college_list)" );
    }
    $builder->where('can_personal_details.status',1);
    if(isset($where) && !empty($where)){
        $builder->where($where);
    }
    $builder->groupBy('can_personal_details.userid');
    $builder->orderBy('can_personal_details.id','DESC');
    // $builder->limit(1000);
    $result = $builder->get();
    //  echo "<br>". $this->db->getLastQuery(); die;
    if(count($result->getResultArray())>0){
        return $result->getResult();
     }
     else{
        return false;
     }
}
public function get_internship_hired_list_by_filter_complete_all($start_date,$end_date,$college_list=NULL,$where=NULL)
{  
    
    $builder = $this->db->table('can_personal_details');
    $builder->select("can_personal_details.*");
    if(isset($college_list) && !empty($college_list)){
        $builder->join('can_education_details', 'can_personal_details.userid = can_education_details.userid');
    }
    
    $builder->join('can_profile_log', 'can_profile_log.candidate_id = can_personal_details.userid');
    $builder->join('userlogin', 'userlogin.userid = can_personal_details.userid');
    $builder->where('date(can_personal_details.created_at)>=',$start_date);
    $builder->where('date(can_personal_details.created_at)<=',$end_date);
    if(isset($college_list) && !empty($college_list)){
        $builder->where("can_education_details.education_college_name IN ($college_list)" );
        $builder->where('can_education_details.status',1);        
    }
    $builder->where('can_personal_details.status',1);
    if(isset($where) && !empty($where)){
        $builder->where($where);
    }
    $builder->groupBy('can_personal_details.userid');
    $builder->orderBy('can_personal_details.id','DESC');
    // $builder->limit(1000);
    $result = $builder->get();
    //  echo "<br>". $this->db->getLastQuery(); die;
    if(count($result->getResultArray())>0){
        return $result->getResult();
     }
     else{
        return false;
     }
}

public function get_college_list_by_filter_download($start_date,$end_date,$college_list=NULL,$where=NULL)
{  
    
    $builder = $this->db->table('can_personal_details');
    $builder->select("can_personal_details.*,can_education_details.*");
   
    $builder->join('can_education_details', 'can_personal_details.userid = can_education_details.userid');
   
    $builder->join('can_profile_log', 'can_profile_log.candidate_id = can_personal_details.userid');
    $builder->join('userlogin', 'userlogin.userid = can_personal_details.userid');
    $builder->where('date(can_personal_details.created_at)>=',$start_date);
    $builder->where('date(can_personal_details.created_at)<=',$end_date);
    if(isset($college_list) && !empty($college_list)){
        $builder->where("can_education_details.education_college_name IN ($college_list)" );
    }
    $builder->where('can_personal_details.status',1);
    if(isset($where) && !empty($where)){
        $builder->where($where);
    }
    $builder->groupBy('can_personal_details.userid');
    $builder->orderBy('can_personal_details.id','DESC');
    $builder->limit(1000);
    $result = $builder->get();
    //  echo "<br>". $this->db->getLastQuery(); die;
    if(count($result->getResultArray())>0){
        return $result->getResult();
     }
     else{
        return false;
     }
}

public function get_college_list_by_filter_count($start_date,$end_date,$college_list=NULL,$where=NULL)
{  
    $builder = $this->db->table('can_personal_details');
    $builder->select("can_personal_details.id");
    if(isset($college_list) && !empty($college_list)){
        $builder->join('can_education_details', 'can_personal_details.userid = can_education_details.userid');
    }
    
    $builder->join('can_profile_log', 'can_profile_log.candidate_id = can_personal_details.userid');
    $builder->join('userlogin', 'userlogin.userid = can_personal_details.userid');
    $builder->where('date(can_personal_details.created_at)>=',$start_date);
    $builder->where('date(can_personal_details.created_at)<=',$end_date);
    if(isset($college_list) && !empty($college_list)){
        $builder->where("can_education_details.education_college_name IN ($college_list)" );
    }
    $builder->where('can_personal_details.status',1);
    if(isset($where) && !empty($where)){
        $builder->where($where);
    }
    $builder->groupBy('can_personal_details.userid');
    $builder->orderBy('can_personal_details.id','DESC');
    $result = $builder->get();
    //  echo "<br>". $this->db->getLastQuery(); die;
    if(count($result->getResultArray())>0){
        return $result->getResult();
     }
     else{
        return false;
     }
}
public function get_college_list_by_filter_complete($start_date,$end_date,$college_list=NULL,$where=NULL)
{  
    $builder = $this->db->table('can_personal_details');
    $builder->select("can_personal_details.*,can_education_details.*");
    $builder->join('can_education_details', 'can_personal_details.userid = can_education_details.userid');
    $builder->join('can_profile_log', 'can_profile_log.candidate_id = can_personal_details.userid');
    $builder->join('userlogin', 'userlogin.userid = can_personal_details.userid');
    $builder->where('date(can_personal_details.created_at)>=',$start_date);
    $builder->where('date(can_personal_details.created_at)<=',$end_date);
    if(isset($college_list) && !empty($college_list)){
        $builder->where("can_education_details.education_college_name IN ($college_list)" );
    }
    $builder->where('can_personal_details.status',1);
    if(isset($where) && !empty($where)){
        $builder->where($where);
    }
    $builder->groupBy('can_personal_details.userid');
    $builder->orderBy('can_personal_details.id','DESC');
    $builder->limit(1000);
    $result = $builder->get();
    // echo "<br>". $this->db->getLastQuery(); die;
    if(count($result->getResultArray())>0){
        return $result->getResult();
     }
     else{
        return false;
     }
}
public function get_internship_hired_list_by_filter_complete($start_date,$end_date,$college_list=NULL,$where=NULL,$type)
{  
    $builder = $this->db->table('can_personal_details');
    $builder->select("can_personal_details.*,can_education_details.*");
    $builder->join('can_education_details', 'can_personal_details.userid = can_education_details.userid');
    $builder->join('can_profile_log', 'can_profile_log.candidate_id = can_personal_details.userid');
    $builder->join('can_applied_internship', 'can_applied_internship.candidate_id = can_personal_details.userid');
    $builder->join('userlogin', 'userlogin.userid = can_personal_details.userid');
    $builder->where('date(can_personal_details.created_at)>=',$start_date);
    $builder->where('date(can_personal_details.created_at)<=',$end_date);
    if($type==3)
    {
        $builder->where('can_applied_internship.application_status',2);
    }
     elseif($type==4)
    {
         $builder->where('can_applied_internship.complete_status',1);
          $builder->where('can_applied_internship.complete_type',1);
    } 
    
    if(isset($college_list) && !empty($college_list)){
        $builder->where("can_education_details.education_college_name IN ($college_list)" );
        $builder->where('can_education_details.status',1);
    }
    $builder->where('can_personal_details.status',1);
    if(isset($where) && !empty($where)){
        $builder->where($where);
    }
    $builder->groupBy('can_personal_details.userid');
    $builder->orderBy('can_personal_details.id','DESC');
    $builder->limit(1000);
    $result = $builder->get();
    // echo "<br>". $this->db->getLastQuery(); die;
    if(count($result->getResultArray())>0){
        return $result->getResult();
     }
     else{
        return false;
     }
}

public function get_college_list_by_filter_complete_count($start_date,$end_date,$college_list=NULL,$where=NULL)
{  
    $builder = $this->db->table('can_personal_details');
    $builder->select("can_personal_details.id,can_education_details.id");
    $builder->join('can_education_details', 'can_personal_details.userid = can_education_details.userid');
    $builder->join('can_profile_log', 'can_profile_log.candidate_id = can_personal_details.userid');
    $builder->join('userlogin', 'userlogin.userid = can_personal_details.userid');
    $builder->where('date(can_personal_details.created_at)>=',$start_date);
    $builder->where('date(can_personal_details.created_at)<=',$end_date);
    if(isset($college_list) && !empty($college_list)){
        $builder->where("can_education_details.education_college_name IN ($college_list)" );
    }
    $builder->where('can_personal_details.status',1);
    if(isset($where) && !empty($where)){
        $builder->where($where);
    }
    $builder->groupBy('can_personal_details.userid');
    $builder->orderBy('can_personal_details.id','DESC');
    $result = $builder->get();
    // echo "<br>". $this->db->getLastQuery(); die;
    if(count($result->getResultArray())>0){
        return $result->getResult();
     }
     else{
        return false;
     }
}

public function get_candidate_list_by_filter($start_date,$end_date,$where)
{
    $builder = $this->db->table('can_personal_details');
    $builder->select("can_personal_details.*,can_education_details.*");
    $builder->join('can_education_details', 'can_personal_details.userid = can_education_details.userid');
    $builder->where('date(can_personal_details.created_at)>=',$start_date);
    $builder->where('date(can_personal_details.created_at)<=',$end_date);
    $builder->where($where);
    $builder->groupBy('can_personal_details.userid');
    $builder->orderBy('can_personal_details.id','DESC');
    
    $result = $builder->get();
    //  echo "<br>". $this->db->getLastQuery(); die;
    if(count($result->getResultArray())>0){
        return $result->getResult();
     }
     else{
        return false;
     }
}

public function get_company_list_by_filter($company_list,$where)
{
    $builder = $this->db->table('employer_post_internship');
    // $builder->select("profile_completion_form.*,can_applied_internship.*");
    $builder->select("employer_post_internship.*");
    // $builder->join('can_applied_internship', 'profile_completion_form.userid = can_applied_internship.userid');
    
    $builder->where($where);
    if(isset($company_list) && !empty($company_list)){
        // $builder->where("can_applied_internship.profile_company_name IN ($company_list)" );
        $builder->whereIn('employer_post_internship.company_id', $company_list);
    }
    // $builder->where('profile_completion_form.status',1);
    if(isset($where) && !empty($where)){
        $builder->where($where);
    }
    // $builder->groupBy('can_applied_internship.userid');
    $builder->orderBy('employer_post_internship.id','DESC');
    
    $result = $builder->get();
    //   echo "<br>". $this->db->getLastQuery(); die;
    if(count($result->getResultArray())>0){
        return $result->getResult();
     }
     else{
        return false;
     }
}
public function can_college_all($candidate_ids=NULL)
{
        // print_r($candidate_ids);
        // exit;
    $builder = $this->db->table('can_education_details');
    $builder->select('master_college.id,master_college.state_id,master_college.district_id,can_education_details.education_college_name,master_college.college_name');
    $builder->join('master_college', 'master_college.id = can_education_details.education_college_name','left'); 
    $builder->where('master_college.status',1);
    $builder->where('can_education_details.status',1);
    if (isset($candidate_ids) && !empty($candidate_ids)) {
        $builder->whereIn('can_education_details.userid', $candidate_ids);
        // $builder->where("can_education_details.userid IN ($candidate_ids)");
    }
    $builder->orderBy('master_college.college_name','asc');
    $builder->groupBy('can_education_details.education_college_name');
    $result = $builder->get();
    //  echo "<br>". $this->db->getLastQuery(); die;
    return $result->getResult();
   
}
public function can_state_all()
{
    $builder = $this->db->table('can_education_details');
    $builder->select('master_college.id,master_college.state_id,master_college.district_id,can_education_details.education_college_name,master_college.college_name');
    $builder->join('master_college', 'master_college.id = can_education_details.education_college_name','left'); 
    $builder->where('master_college.status',1);
    $builder->where('can_education_details.status',1);
    // if (isset($candidate_ids) && !empty($candidate_ids)) {
    //     // $builder->whereIn('can_personal_details.userid', '$candidate_ids');
    //     $builder->where("can_education_details.userid IN ($candidate_ids)");
    // }
    $builder->orderBy('master_college.college_name','asc');
    $builder->groupBy('master_college.state_id');
    $result = $builder->get();
    // echo "<br>". $this->db->getLastQuery(); die;
    return $result->getResult();
   
}

public function candidate_details_state_district_wise($tablename,$where,$order_by=NULL)
{    
   $builder = $this->db->table($tablename);
   $builder->select("can_personal_details.*,can_education_details.userid");
   $builder->join('can_education_details', 'can_education_details.userid = can_personal_details.userid','left');
   $builder->where($where);
   if($order_by!=NULL && isset($order_by['ordercolumn']) && isset($order_by['ordertype']))
      {
      $builder->orderBy($order_by['ordercolumn'],$order_by['ordertype']);
      }
//    $builder->where('status',1);
   $builder->groupBy('can_education_details.userid');
   $result = $builder->get();
   //  return $result->getResult();
    //   echo "<br>". $this->db->getLastQuery(); die;
   if(count($result->getResultArray())>0){
       return $result->getResult();
   }
   else{
       return false;
   }
}
public function candidate_details_profile_education($tablename,$where)
{    
   $builder = $this->db->table($tablename);
   $builder->select("can_personal_details.id,can_personal_details.userid,can_personal_details.profile_full_name,can_personal_details.g_location_name,can_personal_details.profile_phone_number,can_personal_details.profile_email,can_personal_details.profile_gender,can_personal_details.status,can_personal_details.created_at,can_education_details.id,can_education_details.education_college_name,can_education_details.education_college_name_other,can_education_details.education_course,can_education_details.education_course_other,can_education_details.education_start_year,can_education_details.education_end_year,can_education_details.status");
   $builder->join('can_education_details', 'can_education_details.userid = can_personal_details.userid','left');
   $builder->where($where);
   
   $builder->groupBy('can_education_details.userid');
   $result = $builder->get();
   //  return $result->getResult();
    //   echo "<br>". $this->db->getLastQuery(); die;
    if(count(array($result->getRowArray()))>0){
        return $result->getRow();
    }
    else{
        return false;
    }
}

public function can_college_all_state_district($state_id = NULL)
{
    $builder = $this->db->table('can_education_details');
    $builder->select('master_college.id,master_college.state_id,master_college.district_id,can_education_details.education_college_name,master_college.college_name');
    $builder->join('master_college', 'master_college.id = can_education_details.education_college_name','left'); 
    $builder->where('master_college.status',1);
    $builder->where('can_education_details.status',1);
    if (isset($state_id) && !empty($state_id)) {
         //$builder->whereIn('master_college.state_id', $state_id);
        $builder->where("master_college.state_id IN ($state_id)");
    }
    $builder->orderBy('master_college.college_name','asc');
    $builder->groupBy('can_education_details.education_college_name');
    $result = $builder->get();
    //   echo "<br>". $this->db->getLastQuery(); die;
    return $result->getResult();
   
}

public function candidate_college_state_details($id = NULL)
{
       
    $builder = $this->db->table('can_education_details');
    $builder->select('master_college.id,master_college.state_id,master_college.district_id,can_education_details.education_college_name,master_college.college_name,master_state.id,master_state.name');
    $builder->join('master_college', 'master_college.id = can_education_details.education_college_name','left'); 
    $builder->join('master_state', 'master_college.state_id = master_state.id','left'); 
    $builder->where('master_college.status',1);
    $builder->where('can_education_details.status',1);
    if (isset($id) && !empty($id)) {
        //  $builder->whereIn('can_personal_details.userid', '$candidate_ids');
        $builder->where("master_college.id IN ($id)");
    }
    $builder->orderBy('master_college.college_name','asc');
    $builder->groupBy('can_education_details.education_college_name');
    $result = $builder->get();
    //    echo "<br>". $this->db->getLastQuery(); die;
    return $result->getResult();
   
}

public function get_college_state_list_by_filter($college_list)
{  
    $builder = $this->db->table('can_education_details');
    $builder->select('master_college.id,master_college.state_id,master_college.district_id,can_education_details.education_college_name,master_college.college_name');
    $builder->join('master_college', 'master_college.id = can_education_details.education_college_name','left'); 
    $builder->where('master_college.status',1);
    $builder->where('can_education_details.status',1);
    // if (isset($candidate_ids) && !empty($candidate_ids)) {
    //     // $builder->whereIn('can_personal_details.userid', '$candidate_ids');
    //     $builder->where("can_education_details.userid IN ($candidate_ids)");
    // }
    $builder->orderBy('master_college.college_name','asc');
    $builder->groupBy('can_education_details.education_college_name');
    $result = $builder->get();
    // echo "<br>". $this->db->getLastQuery(); die;
    return $result->getResult();
}

public function get_college_list_by_filter_state($college_list=NULL)
{  
    $builder = $this->db->table('can_personal_details');
    $builder->select("can_personal_details.*,can_education_details.*");
    $builder->join('can_education_details', 'can_personal_details.userid = can_education_details.userid');
    if(isset($college_list) && !empty($college_list)){
        $builder->where("can_education_details.education_college_name IN ($college_list)" );
    }
    
    $builder->where('can_personal_details.status',1);
    $builder->groupBy('can_personal_details.userid');
    $builder->orderBy('can_personal_details.id','DESC');
    $result = $builder->get();
    // echo "<br>". $this->db->getLastQuery(); die;
    if(count($result->getResultArray())>0){
        return $result->getResult();
     }
     else{
        return false;
     }
}

public function can_college_all_value($college_list=NULL,$state_list=NULL)
{
       
    $builder = $this->db->table('can_education_details');
    $builder->select('master_college.id,master_college.state_id,master_college.district_id,can_education_details.education_college_name,master_college.college_name');
    $builder->join('master_college', 'master_college.id = can_education_details.education_college_name','left'); 
    $builder->where('master_college.status',1);
    $builder->where('can_education_details.status',1);
    if (isset($college_list) && !empty($college_list)) {
        // $builder->where("master_college.id IN ($college_list)");
        $builder->whereIn('master_college.id',$college_list);
    }
    if (isset($state_list) && !empty($state_list)) {
        // $builder->where("master_college.state_id IN ($state_list)");
        $builder->whereIn('master_college.state_id',$state_list);
    }
    $builder->orderBy('master_college.college_name','asc');
    $builder->groupBy('can_education_details.education_college_name');
    $result = $builder->get();
    // echo "<br>". $this->db->getLastQuery(); die;
    return $result->getResult();
   
}

public function can_college_all_value_count($college_list=NULL,$state_list=NULL)
{
       
    $builder = $this->db->table('can_education_details');
    $builder->select('master_college.id');
    $builder->join('master_college', 'master_college.id = can_education_details.education_college_name','left'); 
    $builder->where('master_college.status',1);
    $builder->where('can_education_details.status',1);
    if (isset($college_list) && !empty($college_list)) {
        // $builder->where("master_college.id IN ($college_list)");
        $builder->whereIn('master_college.id',$college_list);
    }
    if (isset($state_list) && !empty($state_list)) {
        // $builder->where("master_college.state_id IN ($state_list)");
        $builder->whereIn('master_college.state_id',$state_list);
    }
    $builder->orderBy('master_college.college_name','asc');
    $builder->groupBy('can_education_details.education_college_name');
    $result = $builder->get();
    // echo "<br>". $this->db->getLastQuery(); die;
    return count($result->getResultArray());
   
}

public function get_college_list_by_filter_profile_completed($start_date,$end_date,$college_list=NULL,$where=NULL)
    {    
       $builder = $this->db->table('can_personal_details');
       $builder->select("can_personal_details.*,can_education_details.userid");
       $builder->join('can_education_details', 'can_education_details.userid = can_personal_details.userid','left');
       $builder->where($where);
       $builder->where('date(can_personal_details.created_at)>=',$start_date);
       $builder->where('date(can_personal_details.created_at)<=',$end_date);
       if(isset($college_list) && !empty($college_list)){
           $builder->where("can_education_details.education_college_name IN ($college_list)" );
       }
       $builder->where('can_personal_details.status',1);
       if(isset($where) && !empty($where)){
           $builder->where($where);
       }
       $builder->groupBy('can_education_details.userid');
       $result = $builder->get();
       //  return $result->getResult();
        //   echo "<br>". $this->db->getLastQuery(); die;
       if(count($result->getResultArray())>0){
           return $result->getResult();
       }
       else{
           return false;
       }
   }
   
   public function get_transacion_list_by_filter($start_date,$end_date,$payment_status=NULL,$where=NULL)
{  
    $builder = $this->db->table('can_payment_details_history');
    $builder->select("can_payment_details_history.*");
    $builder->where('date(can_payment_details_history.created_at)>=',$start_date);
    $builder->where('date(can_payment_details_history.created_at)<=',$end_date);
    if(isset($payment_status) && !empty($payment_status)){
        $builder->where('can_payment_details_history.payment_status',$payment_status);
    }
    
    if(isset($where) && !empty($where)){
        $builder->where($where);
    }
    $builder->orderBy('can_payment_details_history.id','DESC');
    $result = $builder->get();
     //echo "<br>". $this->db->getLastQuery(); die;
    if(count($result->getResultArray())>0){
        return $result->getResult();
     }
     else{
        return false;
     }
}
 public function get_transacion_status_by_filter($order_id)
{  
    $builder = $this->db->table('can_payment_details_history');
    $builder->select("can_payment_details_history.*");
    if(isset($order_id) && !empty($order_id)){
        $builder->where('can_payment_details_history.order_id',$order_id);
    }
    
    $builder->where('can_payment_details_history.status','1');
   
    $builder->orderBy('can_payment_details_history.id','DESC');
    $result = $builder->get();
     //echo "<br>". $this->db->getLastQuery(); die;
     if(count(array($result->getRowArray()))>0){
        //return $result->getResult();
        return $result->getRow();
     }
     else{
        return false;
     }
}
   
    public function get_invoice_id()
    {    
       $builder = $this->db->table("invoice_details");
       $builder->select("id");
       $builder->orderBy('id','DESC');
       $builder->limit(1);
       $result = $builder->get();
         
       //echo "<br>". $this->db->getLastQuery(); die;
       return $result->getResult();
    }
    public function fetch_rating_data($tablename,$where,$limit=NULL,$start_id=NULL,$start_date=NULL,$end_date=NULL,$company=NULL,$internship=NULL,$ratings=NULL)
    {
        $builder = $this->db->table($tablename);
          $builder->select("can_applied_internship.id,can_personal_details.profile_full_name,can_applied_internship.internship_id,can_applied_internship.can_ratings,can_applied_internship.complete_reason,can_applied_internship.rating_status,profile_completion_form.profile_company_name,employer_post_internship.profile,employer_post_internship.other_profile");
          $builder->join('employer_post_internship', 'employer_post_internship.internship_id = can_applied_internship.internship_id','left');
          $builder->join('can_personal_details', 'can_applied_internship.candidate_id = can_personal_details.userid','left');
          $builder->join('profile_completion_form', 'employer_post_internship.company_id = profile_completion_form.userid','left');
        $builder->where($where);
        if(!empty($start_date) && !empty($end_date)){
        $builder->where('date(can_applied_internship.complete_date)>=',$start_date);
        $builder->where('date(can_applied_internship.complete_date)<=',$end_date);
        }
        if(!empty($company)){
            $builder->where('employer_post_internship.company_id',$company);
           
            }
            if(!empty($internship)){
                $builder->where('employer_post_internship.internship_id',$internship);
               
                }

                if(!empty($ratings)){
                    $builder->where('can_applied_internship.can_ratings',$ratings);
                   
                    }
                    
        $builder->limit($limit,$start_id);
        $result = $builder->get();
        // echo "<br>". $this->db->getLastQuery(); die;  
        if(count($result->getResultArray())>0) { return $result->getResult(); }
        else { return false; }

       // return $builder->countAllResults();
   // echo "<br>". $this->db->getLastQuery(); die;  
    } 
    public function fetch_rating_data_all($tablename,$where,$start_date=NULL,$end_date=NULL,$company=NULL,$internship=NULL,$ratings=NULL)
    {
        $builder = $this->db->table($tablename);
          $builder->select("can_applied_internship.id,can_personal_details.profile_full_name,can_applied_internship.internship_id,can_applied_internship.can_ratings,can_applied_internship.complete_reason,can_applied_internship.rating_status,profile_completion_form.profile_company_name,employer_post_internship.profile,employer_post_internship.other_profile");
          $builder->join('employer_post_internship', 'employer_post_internship.internship_id = can_applied_internship.internship_id','left');
          $builder->join('can_personal_details', 'can_applied_internship.candidate_id = can_personal_details.userid','left');
          $builder->join('profile_completion_form', 'employer_post_internship.company_id = profile_completion_form.userid','left');
        $builder->where($where);
        if(!empty($start_date) && !empty($end_date)){
        $builder->where('date(can_applied_internship.complete_date)>=',$start_date);
        $builder->where('date(can_applied_internship.complete_date)<=',$end_date);
        }
        if(!empty($company)){
            $builder->where('employer_post_internship.company_id',$company);
           
            }
            if(!empty($internship)){
                $builder->where('employer_post_internship.internship_id',$internship);
               
                }

                if(!empty($ratings)){
                    $builder->where('can_applied_internship.can_ratings',$ratings);
                   
                    }
                    
        $builder->limit(1000);
        $result = $builder->get();
        // echo "<br>". $this->db->getLastQuery(); die;  
        if(count($result->getResultArray())>0) { return $result->getResult(); }
        else { return false; }

       // return $builder->countAllResults();
   // echo "<br>". $this->db->getLastQuery(); die;  
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

    public function fetch_table_data_for_company($tablename,$where,$order_by=NULL)
    {
      
      $builder = $this->db->table($tablename);
          $builder->select("*");
          $builder->where($where);
          if($order_by!=NULL && isset($order_by['ordercolumn']) && isset($order_by['ordertype']))
          {
          $builder->orderBy($order_by['ordercolumn'],$order_by['ordertype']);
          }
           $builder->limit(1000);
          // $builder->orderBy($order_by);
          $result = $builder->get();
        //    echo "<br>". $this->db->getLastQuery(); die;
        
          if(count($result->getResultArray())>0){
              return $result->getResult();
          }
          else{
              return false;
          }
     
    }

    public function fetch_table_data_rating_company($tablename,$where)
    {
        $builder = $this->db->table($tablename);
          $builder->select("profile_completion_form.userid,can_applied_internship.internship_id,can_applied_internship.can_ratings,can_applied_internship.complete_reason,can_applied_internship.rating_status,profile_completion_form.profile_company_name,employer_post_internship.profile,employer_post_internship.other_profile");
          $builder->join('employer_post_internship', 'employer_post_internship.internship_id = can_applied_internship.internship_id','left');
         
          $builder->join('profile_completion_form', 'employer_post_internship.company_id = profile_completion_form.userid','left');
        $builder->where($where);
        //$builder->groupBy('can_applied_internship.internship_id');
        $builder->groupBy('profile_completion_form.profile_company_name');
        $result = $builder->get();
        // echo "<br>". $this->db->getLastQuery(); die;  
        if(count($result->getResultArray())>0) { return $result->getResult(); }
        else { return false; }

       // return $builder->countAllResults();
   // echo "<br>". $this->db->getLastQuery(); die;  
    } 
     public function fetch_data_for_transaciton_status($tablename,$start_date,$end_date)
    {
      
         $builder = $this->db->table($tablename);
          $builder->select("order_id");
          $builder->where('order_id is not',NULL);
          //$builder->where('order_id',"order_Lbx9UcGBL5AWzs");
          $builder->where('date(created_at)>=',$start_date);
          $builder->where('date(created_at)<=',$end_date);
          $builder->where("(payment_status!='captured' AND  payment_status!='refunded')" );
          $builder->where('status','1');
          $builder->where('create_mode','1');
          $builder->limit(1000);
          $result = $builder->get();
          //echo "<br>". $this->db->getLastQuery(); die;
          if(count($result->getResultArray())>0){
              return $result->getResult();
          }
          else{
              return false;
          }
     
    }
     public function fetch_data_for_failed_transaciton_status($tablename,$start_date,$end_date)
    {
          $builder = $this->db->table($tablename);
          $builder->select("order_id");
          $builder->where('order_id is not',NULL);
          //$builder->where('order_id',"order_Lbx9UcGBL5AWzs");
          $builder->where('date(created_at)>=',$start_date);
          $builder->where('date(created_at)<=',$end_date);
          $builder->where('payment_status','failed');
          $builder->where('status','1');
          $builder->where('create_mode','1');
          $builder->limit(1000);
          $result = $builder->get();
          //echo "<br>". $this->db->getLastQuery(); die;
        
          if(count($result->getResultArray())>0){
              return $result->getResult();
          }
          else{
              return false;
          }
     
    }
     public function fetch_data_for_refund_transaciton_status($tablename,$start_date,$end_date)
    {
          $builder = $this->db->table($tablename);
          $builder->select("payment_id,order_id");
          $builder->where('payment_id is not',NULL);
          //$builder->where('order_id',"order_Lbx9UcGBL5AWzs");
          $builder->where('date(created_at)>=',$start_date);
          $builder->where('date(created_at)<=',$end_date);
          $builder->where('payment_status','captured');
          $builder->where('refund_initiate','1');
          $builder->where('status','1');
          $builder->where('create_mode','1');
          $builder->limit(1000);
          $result = $builder->get();
          //echo "<br>". $this->db->getLastQuery(); die;
        
          if(count($result->getResultArray())>0){
              return $result->getResult();
          }
          else{
              return false;
          }
     
    }
    public function fetch_data_for_razorpay_webhook_transactions($tablename,$start_date,$end_date)
    {
      
          $builder = $this->db->table($tablename);
          $builder->select("*");
          //$builder->where('order_id is not',NULL);
          //$builder->where('order_id',"order_Lbx9UcGBL5AWzs");
          $builder->where('date(created_at)>=',$start_date);
          $builder->where('date(created_at)<=',$end_date);
          //$builder->where("(payment_status!='captured' AND  payment_status!='refunded')" );
          $builder->where('status','1');
          $builder->where('raz_status','0');
          $builder->where('create_mode','1');
          $builder->limit(1000);
          $result = $builder->get();
          //echo "<br>". $this->db->getLastQuery(); die;
          if(count($result->getResultArray())>0){
              return $result->getResult();
          }
          else{
              return false;
          }
     
    }
    public function table_row_common_userlogin($tablename, $where)
    {
        $builder = $this->db->table($tablename);
        $builder->select("id,userid,usertype,name,candidate_firstname,candidate_lastname,email,mobile,active_status,status,otp_count,otp,email_domain,company_id");
        $builder->where($where);
        $query = $builder->get();
        if (count($query->getResultArray()) == 1) {
            return $query->getRow();
        }
    }

    public function fetch_table_data_applied_candidate_api($tablename,$where,$order_by=NULL)
    {
          $current_date =date("Y-m-d");
          $builder = $this->db->table($tablename);
          $builder->select("employer_post_internship.profile as pro,employer_post_internship.other_profile,master_profile.profile,employer_post_internship.internship_startdate,profile_completion_form.profile_company_name,employer_post_internship.stipend,employer_post_internship.amount_from,employer_post_internship.amount_to,can_applied_internship.hiring_status,can_applied_internship.application_status,can_applied_internship.complete_status,can_applied_internship.complete_type,profile_completion_form.profile_company_logo");
          $builder->join('employer_post_internship', 'employer_post_internship.internship_id = can_applied_internship.internship_id','left');
          $builder->join('profile_completion_form', 'employer_post_internship.company_id = profile_completion_form.userid','left');
          $builder->join('master_profile', 'employer_post_internship.profile = master_profile.id','left');
          $builder->where($where);
          if($order_by!=NULL && isset($order_by['ordercolumn']) && isset($order_by['ordertype']))
          {
          $builder->orderBy($order_by['ordercolumn'],$order_by['ordertype']);
          }
          // $builder->orderBy($order_by);
          $result = $builder->get();
        //   echo "<br>". $this->db->getLastQuery(); die;
          if(count($result->getResult())>0){
              return $result->getResult();
          }
          else{
              return false;
          }
     
    }


    public function fetch_data_for_transaciton_status_phonepe($tablename,$start_date,$end_date)
    {
      
         $builder = $this->db->table($tablename);
          $builder->select("order_id,userid");
          $builder->where('order_id is not',NULL);
          //$builder->where('order_id',"order_Lbx9UcGBL5AWzs");
          $builder->where('date(created_at)>=',$start_date);
          $builder->where('date(created_at)<=',$end_date);
          $builder->where("(payment_status!='PAYMENT_SUCCESS')" );
          $builder->where('status','1');
          $builder->where('create_mode','1');
        //   $builder->limit(1000);
          $result = $builder->get();
          //echo "<br>". $this->db->getLastQuery(); die;
          if(count($result->getResultArray())>0){
              return $result->getResult();
          }
          else{
              return false;
          }
     
    }

    public function phonepe_get_transacion_status_by_filter($order_id)
    {  
        $builder = $this->db->table('can_payment_details_history_phonepe');
        $builder->select("can_payment_details_history_phonepe.*");
        if(isset($order_id) && !empty($order_id)){
            $builder->where('can_payment_details_history_phonepe.order_id',$order_id);
        }
        
        $builder->where('can_payment_details_history_phonepe.status','1');
       
        $builder->orderBy('can_payment_details_history_phonepe.id','DESC');
        $result = $builder->get();
         //echo "<br>". $this->db->getLastQuery(); die;
         if(count(array($result->getRowArray()))>0){
            //return $result->getResult();
            return $result->getRow();
         }
         else{
            return false;
         }
    }

    public function fetch_data_for_refund_transaciton_status_phonepe($tablename,$start_date,$end_date)
    {
          $builder = $this->db->table($tablename);
          $builder->select("refund_payment_id,refund_order_id,userid");
          $builder->where('refund_payment_id is not',NULL);
          //$builder->where('order_id',"order_Lbx9UcGBL5AWzs");
          $builder->where('date(created_at)>=',$start_date);
          $builder->where('date(created_at)<=',$end_date);
          $builder->where('payment_status','PAYMENT_SUCCESS');
          $builder->where('refund_status','PAYMENT_PENDING');
          $builder->where('status','1');
          $builder->where('create_mode','1');
        //   $builder->limit(1000);
          $result = $builder->get();
          //echo "<br>". $this->db->getLastQuery(); die;
        
          if(count($result->getResultArray())>0){
              return $result->getResult();
          }
          else{
              return false;
          }
     
    }

    public function get_phonepe_transacion_list_by_filter($start_date,$end_date,$payment_status=NULL,$where=NULL)
    {  
        $builder = $this->db->table('can_payment_details_history_phonepe');
        $builder->select("can_payment_details_history_phonepe.*");
        $builder->where('date(can_payment_details_history_phonepe.created_at)>=',$start_date);
        $builder->where('date(can_payment_details_history_phonepe.created_at)<=',$end_date);
        if(isset($payment_status) && !empty($payment_status)){
            if($payment_status!="refunded"){
                $builder->where('can_payment_details_history_phonepe.payment_status',$payment_status);
            }else{
                $builder->where('can_payment_details_history_phonepe.refund_status','PAYMENT_SUCCESS');
            }
            
        }
        
        if(isset($where) && !empty($where)){
            $builder->where($where);
        }
        $builder->orderBy('can_payment_details_history_phonepe.id','DESC');
        $result = $builder->get();
         //echo "<br>". $this->db->getLastQuery(); die;
        if(count($result->getResultArray())>0){
            return $result->getResult();
         }
         else{
            return false;
         }
    }

    public function fetch_table_blog_data($tablename,$where,$order_by=NULL,$search_blog=NULL, $limit=NULL, $start_id=NULL)
    {
      
      $builder = $this->db->table($tablename);
          $builder->select("*");
          $builder->where($where);
          if($order_by!=NULL && isset($order_by['ordercolumn']) && isset($order_by['ordertype']))
          {
          $builder->orderBy($order_by['ordercolumn'],$order_by['ordertype']);
          }

          if($search_blog!=NULL && isset($search_blog))
          {
          $builder->like('blog_title',$search_blog);
          $builder->orLike('blog_category',$search_blog); 
          $builder->orLike('author_name',$search_blog);
          $builder->orLike('published_date',$search_blog);
          }
          $builder->limit($limit,$start_id);
          // $builder->orderBy($order_by);
          $result = $builder->get();
        //    echo "<br>". $this->db->getLastQuery(); die;
        
          if(count($result->getResultArray())>0){
              return $result->getResult();
          }
          else{
              return false;
          }
     
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
}




