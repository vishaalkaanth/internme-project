<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models;
use \CodeIgniter\Model;
use CodeIgniter\Database\BaseBuilder;
/**
 * Description of RegisterModel
 *
 * @author ram
 */
class Employer_model extends Model {

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

        public function fetch_table_data($tablename,$where)
  {
    $builder = $this->db->table($tablename);
        $builder->select("*");
        $builder->where($where);
        $builder->orderBy("id", "desc");
        $result = $builder->get();
        if(count($result->getResultArray())>0){
            return $result->getResult();
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
    //   echo "<br>". $this->db->getLastQuery(); die;
      if(count(array($result->getRowArray()))>0){
          return $result->getRow();
      }
      else{
          return false;
      }
 
  }

  public function fetch_table_row_col($select,$tablename,$where)
  {
      $builder = $this->db->table($tablename);
      $builder->select($select);
      $builder->where($where);
      $result = $builder->get();
    //   echo "<br>". $this->db->getLastQuery(); die;
      if(count(array($result->getRowArray()))>0){
          return $result->getRow();
      }
      else{
          return false;
      }
 
  }

  public function get_master_name_emp($tablename,$id,$col_name)
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
  

   public function get_master_name($tablename,$id)
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
   public function get_master_skill($tablename,$id)
    {
        $builder = $this->db->table($tablename);
        $builder->select('skill_name');
        $builder->where('id',$id);
        $builder->where('status',1);
        $result = $builder->get();
        if(count($result->getResultArray())>0){
            return $result->getRow('skill_name');
        }
        else{
            return false;
        }
   
  }
  public function get_master_location($tablename,$id)
    {
        $builder = $this->db->table($tablename);
        $builder->select('city');
        $builder->where('id',$id);
        $builder->where('status',1);
        $result = $builder->get();
        if(count($result->getResultArray())>0){
            return $result->getRow('city');
        }
        else{
            return false;
        }
   
  }
    public function insertBatch1($tablename,$data)
    {
        $builder = $this->db->table($tablename);
        $result =$builder->insertBatch($data);
       
             //echo "<br>". $this->db->getLastQuery(); die;
            return $result;
      
        
    }
    public function get_master_commen($tablename,$id,$col_name)
    {
        $builder = $this->db->table($tablename);
        $builder->select($col_name);
        $builder->where('id',$id);
        $builder->where('status',1);
        $result = $builder->get();
        if(count($result->getResultArray())>0){
            return $result->getRow($col_name);
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
            // print_r($this->db->affectedRows());die;
    }


        public function fetch_candidate_data($tablename,$where,$filter_profile_district=NULL,$filter_education_skills=NULL,$filter_education_background=NULL,$filter_education_specialization=NULL,$filter_gender=NULL,$filter_education_college=NULL,$filter_internship_lable=NULL,$filter_graduation_year=NULL,$total=NULL,$start_id=NULL,$keyword_search=NULL)
        // public function fetch_candidate_data($tablename,$where,$filter_profile_district=NULL,$filter_education_skills=NULL,$filter_education_background=NULL,$filter_gender=NULL,$total=NULL,$start_id=NULL)
 
        {
    $builder = $this->db->table($tablename);
    if(isset($keyword_search) && !empty($keyword_search))
          {
             
              $builder->like('profile_full_name',$keyword_search);
              
          }
  $builder->select('can_applied_internship.*,can_personal_details.profile_full_name,can_personal_details.g_location_id,can_personal_details.g_location_name,can_address_details.communication_district,can_address_details.communication_state');
  $builder->join('can_personal_details', 'can_applied_internship.candidate_id = can_personal_details.userid','left');
  $builder->join('can_address_details', 'can_applied_internship.candidate_id = can_address_details.userid','left');
  $builder->join('can_education_details', 'can_applied_internship.candidate_id = can_education_details.userid','left');
  $builder->join('can_skills_details', 'can_applied_internship.candidate_id = can_skills_details.userid','left');
  $builder->join('employer_label_data', 'can_applied_internship.candidate_id = employer_label_data.candidate_id','left');
    $builder->where($where);
    if(isset($filter_gender) && !empty($filter_gender))
    {
        $builder->where('can_personal_details.profile_gender',$filter_gender);
    }
    if(isset($filter_profile_district) && !empty($filter_profile_district))
    {
        $builder->whereIn('can_personal_details.g_location_id',$filter_profile_district);
    }
    if(isset($filter_education_background) && !empty($filter_education_background))
    {
        
        $builder->whereIn('can_education_details.education_course',$filter_education_background);
        $builder->where('can_education_details.status','1');
    // $builder->orderBy('can_education_details.education_end_year','desc')->limit('1');
    }
    if(isset($filter_education_specialization) && !empty($filter_education_specialization))
    {
        
        $builder->whereIn('can_education_details.education_specialization',$filter_education_specialization);
        $builder->where('can_education_details.status','1');
    // $builder->orderBy('can_education_details.education_end_year','desc')->limit('1');
    }

    if(isset($filter_education_college) && !empty($filter_education_college))
    {
        
        $builder->whereIn('can_education_details.education_college_name',$filter_education_college);
        $builder->where('can_education_details.status','1');
    // $builder->orderBy('can_education_details.education_end_year','desc')->limit('1');
    }
     if(isset($filter_graduation_year) && !empty($filter_graduation_year))
    {
        
        $builder->whereIn('can_education_details.education_end_year',$filter_graduation_year);
        $builder->where('can_education_details.status','1');
    // $builder->orderBy('can_education_details.education_end_year','desc')->limit('1');
    }
    if(isset($filter_education_skills) && !empty($filter_education_skills))
    {
         
  
        $builder->whereIn('can_skills_details.skills',$filter_education_skills);
    // $builder->orderBy('can_education_details.education_end_year','desc')->limit('1');
    }
    if(isset($filter_internship_lable) && !empty($filter_internship_lable))
    {
        $builder->whereIn('employer_label_data.label_id',$filter_internship_lable);
        $builder->where('employer_label_data.status','1');
    }
    $builder->groupBy('can_education_details.userid');
    $builder->orderBy('can_applied_internship.created_at','desc');
    if(isset($total) && isset($start_id)){
        $builder->limit($total,$start_id);
    }
  $result = $builder->get();
 //echo "<br>". $this->db->getLastQuery(); die;
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
  public function get_master_name_dist($tablename,$id,$col_name)
  {
      $builder = $this->db->table($tablename);
      $builder->select($col_name);
      $builder->where('dist_id',$id);
      $builder->where('status',1);
      $result = $builder->get();
      if(count(array($result->getResultArray()))>0){
          return $result->getRow($col_name);
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
         //echo "<br>". $this->db->getLastQuery(); die;
        //if(count($result->getResultArray())>0){
            return $result->getResult();
        //}
        //else{
          //  return false;
        //}
   
  }
  public function fetch_table_data_for_all_priority($tablename,$where,$order_by=NULL)
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
         //echo "<br>". $this->db->getLastQuery(); die;
        //if(count($result->getResultArray())>0){
            return $result->getResult();
        //}
        //else{
          //  return false;
        //}
   
  }
  public function data_count_fetch($tablename,$where)
  {
    $builder = $this->db->table($tablename);
    $builder->select("id");
    $builder->where($where);
    $builder->groupBy('candidate_id');
    // echo "<br>". $this->db->getLastQuery(); 
    // $result = $builder->get();
    return $builder->countAllResults();
 
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
  //candidate location group by google id
   public function can_location()
    {
        $builder = $this->db->table('can_personal_details');
        $builder->select('can_personal_details.g_location_id,can_personal_details.g_location_name');
        $builder->join('can_applied_internship', 'can_applied_internship.candidate_id = can_personal_details.userid','left'); 
        $builder->join('employer_post_internship', 'employer_post_internship.internship_id = can_applied_internship.internship_id','left');
        $builder->where('can_personal_details.status',1);
        $builder->where('employer_post_internship.user_id',session()->get('userid'));
        // $builder->where('can_personal_details.active_status',0);
        $builder->orderBy('can_personal_details.g_location_name','asc');
        $builder->groupBy('can_personal_details.g_location_id');
        $result = $builder->get();
        return $result->getResultArray();
       
    }
    public function can_location_all($candidate_ids=NULL)
    {
        // echo $candidate_ids;exit();
        
        $builder = $this->db->table('can_personal_details');
        $builder->select('can_personal_details.userid,can_personal_details.g_location_id,can_personal_details.g_location_name');
        // $builder->join('can_applied_internship', 'can_applied_internship.candidate_id = can_personal_details.userid','left'); 
        // $builder->join('employer_post_internship', 'employer_post_internship.internship_id = can_applied_internship.internship_id','left');
        if (isset($candidate_ids) && !empty($candidate_ids)) {
            $can_id = explode(",",$candidate_ids);
            $builder->whereIn('can_personal_details.userid', $can_id);
            // $builder->where("can_personal_details.userid IN ($candidate_ids)");
        }
        $builder->where('can_personal_details.status',1);
        // $builder->where('employer_post_internship.user_id',session()->get('userid'));
        // $builder->where('can_personal_details.active_status',0);
        
        $builder->orderBy('can_personal_details.g_location_name','asc');
        $builder->groupBy('can_personal_details.g_location_id');
        $result = $builder->get();
        // echo "<br>". $this->db->getLastQuery(); die;
        return $result->getResult();
       
    }
    public function can_education_year_all($candidate_ids=NULL)
    {
        // echo $candidate_ids;exit();
        
        $builder = $this->db->table('can_education_details');
        $builder->select('can_education_details.education_end_year');
        if (isset($candidate_ids) && !empty($candidate_ids)) {
            $can_id = explode(",",$candidate_ids);
            $builder->whereIn('can_education_details.userid', $can_id);
            // $builder->where("can_education_details.userid IN ($candidate_ids)");
        }
        $builder->where('can_education_details.status',1);
        
          $builder->whereIn('can_education_details.education_end_year', function(BaseBuilder $builder) {
        return $builder->select('MAX(education_end_year)', false)->from('can_education_details')->groupBy('education_end_year');
    });
    
        
        $builder->orderBy('can_education_details.education_end_year','desc');
        $builder->groupBy('can_education_details.education_end_year');
        $result = $builder->get();
        // echo "<br>". $this->db->getLastQuery(); die;
        return $result->getResult();
       
    }
    

    public function can_preffered_location_all($candidate_ids=NULL)
    {
        // echo $candidate_ids;exit();
        
        $builder = $this->db->table('can_personal_details');
        $builder->select('can_personal_details.userid,can_worklocation_multiple.g_location_id,can_worklocation_multiple.g_location_name,can_worklocation_multiple.user_id,can_worklocation_multiple.location_district,can_worklocation_multiple.location_state');
        $builder->join('can_worklocation_multiple', 'can_worklocation_multiple.user_id = can_personal_details.userid','left'); 
        // $builder->join('employer_post_internship', 'employer_post_internship.internship_id = can_applied_internship.internship_id','left');
        if (isset($candidate_ids) && !empty($candidate_ids)) {
            $can_id = explode(",",$candidate_ids);
            $builder->whereIn('can_personal_details.userid', $can_id);
            // $builder->where("can_personal_details.userid IN ($candidate_ids)");
        }
        $builder->where('can_personal_details.status',1);
        $builder->where('can_personal_details.g_location_id !=',NULL);
        $builder->Where('can_worklocation_multiple.g_location_id !=',NULL);
        // $builder->where('employer_post_internship.user_id',session()->get('userid'));
        // $builder->where('can_personal_details.active_status',0);
        
        $builder->orderBy('can_worklocation_multiple.g_location_name','asc');
        $builder->groupBy('can_worklocation_multiple.g_location_id');
        $result = $builder->get();
        // echo "<br>". $this->db->getLastQuery(); die;
        return $result->getResult();
       
    }


    public function can_gender_all($candidate_ids=NULL)
    {
        $builder = $this->db->table('can_personal_details');
        $builder->select('master_gender.id,can_personal_details.profile_gender,master_gender.gender_type');
        $builder->join('master_gender', 'master_gender.id = can_personal_details.profile_gender','left'); 
        $builder->where('master_gender.status',1);
        $builder->where('can_personal_details.status',1);
        if (isset($candidate_ids) && !empty($candidate_ids)) {
            $can_id = explode(",",$candidate_ids);
            $builder->whereIn('can_personal_details.userid', $can_id);
            // $builder->where("can_personal_details.userid IN ($candidate_ids)");
        }
        $builder->orderBy('master_gender.id','asc');
        $builder->groupBy('can_personal_details.profile_gender');
        $result = $builder->get();
        // echo "<br>". $this->db->getLastQuery(); die;
        return $result->getResult();
       
    }

    public function can_academin_background_all($candidate_ids=NULL)
    {
        $builder = $this->db->table('can_education_details');
        $builder->select('master_academic_courses.id,can_education_details.education_course,master_academic_courses.name');
        $builder->join('master_academic_courses', 'master_academic_courses.id = can_education_details.education_course','left'); 
        $builder->where('master_academic_courses.status',1);
        $builder->where('can_education_details.status',1);
        if (isset($candidate_ids) && !empty($candidate_ids)) {
            $can_id = explode(",",$candidate_ids);
            $builder->whereIn('can_education_details.userid', $can_id);
            // $builder->where("can_education_details.userid IN ($candidate_ids)");
        }
        $builder->orderBy('master_academic_courses.name','asc');
        $builder->groupBy('can_education_details.education_course');
        $result = $builder->get();
        // echo "<br>". $this->db->getLastQuery(); die;
        return $result->getResult();
       
    }

    public function can_academin_specialization_all($candidate_ids=NULL)
    {
        $builder = $this->db->table('can_education_details');
        $builder->select('master_academic_branch.id,can_education_details.education_specialization,master_academic_branch.name');
        $builder->join('master_academic_branch', 'master_academic_branch.id = can_education_details.education_specialization','left'); 
        $builder->where('master_academic_branch.status',1);
        $builder->where('can_education_details.status',1);
        if (isset($candidate_ids) && !empty($candidate_ids)) {
            $can_id = explode(",",$candidate_ids);
            $builder->whereIn('can_education_details.userid', $can_id);
            // $builder->where("can_education_details.userid IN ($candidate_ids)");
        }
        $builder->orderBy('master_academic_branch.name','asc');
        $builder->groupBy('can_education_details.education_specialization');
        $result = $builder->get();
        // echo "<br>". $this->db->getLastQuery(); die;
        return $result->getResult();
       
    }

    public function can_skills_all($candidate_ids=NULL)
    {
        $builder = $this->db->table('can_skills_details');
        $builder->select('master_skills.id,can_skills_details.skills,master_skills.skill_name');
        $builder->join('master_skills', 'master_skills.id = can_skills_details.skills','left'); 
        $builder->where('master_skills.status',1);
        $builder->where('can_skills_details.status',1);
        if (isset($candidate_ids) && !empty($candidate_ids)) {
            $can_id = explode(",",$candidate_ids);
            $builder->whereIn('can_skills_details.userid', $can_id);
            // $builder->where("can_skills_details.userid IN ($candidate_ids)");
        }
        $builder->orderBy('master_skills.skill_name','asc');
        $builder->groupBy('can_skills_details.skills');
        $result = $builder->get();
        // echo "<br>". $this->db->getLastQuery(); die;
        return $result->getResult();
       
    }

    public function can_college_all($candidate_ids=NULL)
    {
        $builder = $this->db->table('can_education_details');
        $builder->select('master_college.id,can_education_details.education_college_name,master_college.college_name');
        $builder->join('master_college', 'master_college.id = can_education_details.education_college_name','left'); 
        $builder->where('master_college.status',1);
        $builder->where('can_education_details.status',1);
        if (isset($candidate_ids) && !empty($candidate_ids)) {
            $can_id = explode(",",$candidate_ids);
            $builder->whereIn('can_education_details.userid', $can_id);
            // $builder->where("can_education_details.userid IN ($candidate_ids)");
        }
        $builder->orderBy('master_college.college_name','asc');
        $builder->groupBy('can_education_details.education_college_name');
        $result = $builder->get();
        // echo "<br>". $this->db->getLastQuery(); die;
        return $result->getResult();
       
    }

    public function can_label_all($internship_id,$candidate_ids=NULL)
    {
        $builder = $this->db->table('employer_label_data');
        $builder->select('employer_label.id,employer_label_data.label_name,employer_label.label_color');
        $builder->join('employer_label', 'employer_label.id = employer_label_data.label_id','left'); 
        $builder->where('employer_label.status',1);
        $builder->where('employer_label_data.status',1);
        if (isset($candidate_ids) && !empty($candidate_ids)) {
            $can_id = explode(",",$candidate_ids);
            $builder->whereIn('employer_label_data.candidate_id', $can_id);
            // $builder->where("employer_label_data.candidate_id IN ($candidate_ids)");
        }
        if (isset($internship_id) && !empty($internship_id)) {
            // $builder->whereIn('can_personal_details.userid', '$candidate_ids');
            $builder->where("employer_label.internship_id",$internship_id);
        }
        $builder->orderBy('employer_label.label_name','asc');
        $builder->groupBy('employer_label_data.label_name');
        $result = $builder->get();
        // echo "<br>". $this->db->getLastQuery(); die;
        return $result->getResult();
       
    }
      //check otp count
     public function otp_count_check($email,$usertype)
        {
           
            $builder = $this->db->table('user_otp');
            $builder->select("otp_count");
            $builder->where('phone_number',$email)->where('user_type',$usertype)->like('date_time',date('Y-m-d'));
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
    //duplicate number
    function duplicate_number($number,$user_id)
    {
         $builder = $this->db->table('profile_completion_form');
         $builder->select('profile_completion_form.profile_phone_no,userlogin.usertype'); 
         $builder->join('userlogin', 'userlogin.userid = profile_completion_form.userid','left');
         $builder->where('profile_completion_form.profile_phone_no',$number); 
         $builder->where('profile_completion_form.userid !=',$user_id); 
         $builder->where('userlogin.usertype','2'); 
         $result = $builder->get();
        return $result->getResultArray();
    }

     //duplicate number
     function duplicate_number_sub($number,$user_id)
     {
          $builder = $this->db->table('emp_manage_admins');
          $builder->select('emp_manage_admins.emp_mobile,userlogin.usertype'); 
          $builder->join('userlogin', 'userlogin.userid = emp_manage_admins.userid','left');
          $builder->where('emp_manage_admins.emp_mobile',$number); 
          $builder->where('emp_manage_admins.userid !=',$user_id); 
          $builder->where('userlogin.usertype!=','1'); 
          $result = $builder->get();
         return $result->getResultArray();
     }

      //duplicate email
      function duplicate_email_sub($email,$user_id)
      {
           $builder = $this->db->table('emp_manage_admins');
           $builder->select('emp_manage_admins.emp_official_email,userlogin.usertype'); 
           $builder->join('userlogin', 'userlogin.userid = emp_manage_admins.userid','left');
           $builder->where('emp_manage_admins.emp_official_email',$email); 
           $builder->where('emp_manage_admins.userid !=',$user_id); 
           $builder->where('userlogin.usertype!=','1'); 
           $result = $builder->get();
          return $result->getResultArray();
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
     //get employer data
    function get_employer_data($userid)
    {
         $builder = $this->db->table('profile_completion_form');
         $builder->select('profile_official_email,profile_name,profile_company_name,profile_phone_no');          
         $builder->where('userid',$userid); 
         $result = $builder->get();
        return $result->getResultArray();
    }
     //get profile name
    function get_profile_name($profile_id)
    {
         $builder = $this->db->table('master_profile');
         $builder->select('profile');          
         $builder->where('id',$profile_id); 
         $result = $builder->get();
        return $result->getResultArray();
    }
    public function check_duplicatecheck($val,$column,$table)
    {
        $usertype=array('2','3','4');
         $builder = $this->db->table($table);
          $builder->select("*");
          $builder->where($column,$val);
          $builder->whereIn('usertype',$usertype);
          $result = $builder->get();
          return $result->getResult();
     
    }
    public function check_duplicatecheck1($val,$column,$table,$emp_user_id)
    {
        // $usertype=array('2','3','4');
         $builder = $this->db->table($table);
          $builder->select("*");
          $builder->where($column,$val);
          $builder->where('emp_user_id',$emp_user_id);
        //   $builder->whereIn('usertype',$usertype);
          $result = $builder->get();
          return $result->getResult();
     
    }
    public function edit_check_duplicatecheck($notval,$val,$column,$table,$noval_colm)
    {
        $usertype=array('2','3','4');
         $builder = $this->db->table($table);
          $builder->select("*");
          $builder->where($column,$val);
          $builder->where($noval_colm .'!=',$notval);
          $builder->whereIn('usertype',$usertype);
          $result = $builder->get();
          return $result->getResult();
     
    }
    public function edit_check_duplicatecheck1($notval,$val,$column,$table,$noval_colm,$emp_user_id)
    {
        // $usertype=array('2','3','4');
         $builder = $this->db->table($table);
          $builder->select("*");
          $builder->where($column,$val);
          $builder->where($noval_colm .'!=',$notval);
          $builder->where('emp_user_id',$emp_user_id);
        //   $builder->whereIn('usertype',$usertype);
          $result = $builder->get();
          return $result->getResult();
     
    }
 

        //duplicate email
        function duplicate_email($email,$user_id)
        {
             $builder = $this->db->table('profile_completion_form');
             $builder->select('profile_completion_form.profile_official_email,userlogin.usertype'); 
             $builder->join('userlogin', 'userlogin.userid = profile_completion_form.userid','left');
             $builder->where('profile_completion_form.profile_official_email',$email); 
             $builder->where('profile_completion_form.userid !=',$user_id); 
             $builder->where('userlogin.usertype','2'); 
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
      //gst duplicate check
    function gst_duplicate_check($where)
    {
         $builder = $this->db->table('profile_completion_form');
         $builder->select('id');          
         $builder->where($where); 
         $result = $builder->get();
        return $result->getResultArray();
    }

    public function fetch_table_data_group_by($tablename,$where,$group_by,$order_by=NULL)
  {
    $builder = $this->db->table($tablename);
    $builder->select('*');
    $builder->where($where);
    $builder->whereIn('id', function(BaseBuilder $builder) {
        return $builder->select('MAX(id)', false)->from('chat')->groupBy('receiver_id');
    });
    if($order_by!=NULL && isset($order_by['ordercolumn']) && isset($order_by['ordertype']))
        {
        $builder->orderBy($order_by['ordercolumn'],$order_by['ordertype']);
        }
    $query = $builder->get()->getResult();
    // echo "<br>". $this->db->getLastQuery(); die;
    return $query; 
    // $builder = $this->db->table($tablename);
    //     $builder->select("*");
    //     $builder->where($where);
    //     if($order_by!=NULL && isset($order_by['ordercolumn']) && isset($order_by['ordertype']))
    //     {
    //     $builder->orderBy($order_by['ordercolumn'],$order_by['ordertype']);
    //     }
    //     $builder->groupBy($group_by['ordercolumn']);
    
    //     $result = $builder->get();
    //     // echo "<br>". $this->db->getLastQuery(); die;
    //     if(count($result->getResultArray())>0){
    //         return $result->getResult();
    //     }
    //     else{
    //         return false;
    //     }
   
  }

  public function fetch_table_data_group_by_new($tablename,$where,$group_by,$order_by=NULL)
  {
    $builder = $this->db->table($tablename);
        $builder->select("*");
        $builder->where($where);
        if($order_by!=NULL && isset($order_by['ordercolumn']) && isset($order_by['ordertype']))
        {
        $builder->orderBy($order_by['ordercolumn'],$order_by['ordertype']);
        }
        $builder->groupBy($group_by['ordercolumn']);
    
        $result = $builder->get();
        // echo "<br>". $this->db->getLastQuery(); die;
        if(count($result->getResultArray())>0){
            return $result->getResult();
        }
        else{
            return false;
        }
   
  }
  public function fetch_candidate_data_all($tablename,$where,$filter_profile_district=NULL,$filter_education_skills=NULL,$filter_education_background=NULL,$filter_education_specialization=NULL,$filter_gender=NULL,$filter_education_college=NULL,$filter_preffered_location=NULL,$filter_graduation_year=NULL,$total=NULL,$start_id=NULL,$keyword_search=NULL)
  {
    $builder = $this->db->table($tablename);
    if(isset($keyword_search) && !empty($keyword_search))
          {
             
              $builder->like('profile_full_name',$keyword_search);
              
          }
  $builder->select('can_personal_details.*,can_personal_details.profile_full_name,can_personal_details.g_location_id,can_personal_details.g_location_name,can_education_details.userid,can_education_details.education_course');
   $builder->join('can_address_details', 'can_personal_details.userid = can_address_details.userid','left');
  $builder->join('can_education_details', 'can_personal_details.userid = can_education_details.userid','left');
  $builder->join('can_skills_details', 'can_personal_details.userid = can_skills_details.userid','left');
  $builder->join('can_worklocation_multiple', 'can_personal_details.userid = can_worklocation_multiple.user_id','left');

    $builder->where($where);
    if(isset($filter_gender) && !empty($filter_gender))
    {
        $builder->where('can_personal_details.profile_gender',$filter_gender);
    }
    if(isset($filter_profile_district) && !empty($filter_profile_district))
    {
        $builder->whereIn('can_personal_details.g_location_id',$filter_profile_district);
    }
    if(isset($filter_preffered_location) && !empty($filter_preffered_location))
    {
        $builder->whereIn('can_worklocation_multiple.g_location_id',$filter_preffered_location);
    }
    if(isset($filter_education_background) && !empty($filter_education_background))
    {
        
        $builder->whereIn('can_education_details.education_course',$filter_education_background);
        $builder->where('can_education_details.status','1');
 
    }
    if(isset($filter_education_specialization) && !empty($filter_education_specialization))
    {
        
        $builder->whereIn('can_education_details.education_specialization',$filter_education_specialization);
        $builder->where('can_education_details.status','1');
 
    }
    if(isset($filter_education_college) && !empty($filter_education_college))
    {
        
        $builder->whereIn('can_education_details.education_college_name',$filter_education_college);
        $builder->where('can_education_details.status','1');
 
    }
    if(isset($filter_graduation_year) && !empty($filter_graduation_year))
    {
        
        $builder->whereIn('can_education_details.education_end_year',$filter_graduation_year);
        $builder->where('can_education_details.status','1');
 
    }
    if(isset($filter_education_skills) && !empty($filter_education_skills))
    {
         
  
        $builder->whereIn('can_skills_details.skills',$filter_education_skills);

    }

    $builder->orderBy('can_personal_details.id','desc');
    $builder->groupBy('can_education_details.userid');

    if(isset($total) && isset($start_id)){
        $builder->limit($total,$start_id);
    }
  $result = $builder->get();
//  echo "<br>". $this->db->getLastQuery(); die;
  if(count($result->getResultArray())>0){
      return $result->getResult();
  }
  else{
      return false;
  }
   
  }
  function supervisior_internship_data($hrs_userid)
    {
         $builder = $this->db->table('can_applied_internship');
         $builder->select('employer_post_internship.*'); 
         $builder->join('employer_post_internship', 'can_applied_internship.internship_id = employer_post_internship.internship_id','left');
         $builder->where('can_applied_internship.emp_supervisor',$hrs_userid); 
         $builder->groupBy('can_applied_internship.internship_id');
         $result = $builder->get();
        //  echo "<br>". $this->db->getLastQuery(); die;
        return $result->getResult();
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
          // echo "<br>". $this->db->getLastQuery(); die;
          if(count($result->getResultArray())>0){
              return $result->getResult();
          }
          else{
              return false;
          }
     
    }
    public function fetch_candidate_data_all_folder($tablename,$where,$filter_profile_district=NULL,$filter_education_skills=NULL,$filter_education_background=NULL,$filter_education_specialization=NULL,$filter_gender=NULL,$filter_education_college=NULL,$filter_preffered_location=NULL,$filter_graduation_year=NULL,$total=NULL,$start_id=NULL,$keyword_search=NULL)
  {
    $builder = $this->db->table($tablename);
    if(isset($keyword_search) && !empty($keyword_search))
          {
             
              $builder->like('profile_full_name',$keyword_search);
              
          }
  $builder->select('employer_folder_data.*,can_personal_details.userid,can_personal_details.profile_full_name,can_personal_details.g_location_id,can_personal_details.g_location_name,can_education_details.userid,can_address_details.communication_state');
  $builder->join('can_personal_details', 'employer_folder_data.candidate_id = can_personal_details.userid','left');
   $builder->join('can_address_details', 'employer_folder_data.candidate_id = can_address_details.userid','left');
  $builder->join('can_education_details', 'employer_folder_data.candidate_id = can_education_details.userid','left');
  $builder->join('can_skills_details', 'employer_folder_data.candidate_id = can_skills_details.userid','left');
  $builder->join('can_worklocation_multiple', 'can_personal_details.userid = can_worklocation_multiple.user_id','left');

    $builder->where($where);
    if(isset($filter_gender) && !empty($filter_gender))
    {
        $builder->where('can_personal_details.profile_gender',$filter_gender);
    }
    if(isset($filter_profile_district) && !empty($filter_profile_district))
    {
        $builder->whereIn('can_personal_details.g_location_id',$filter_profile_district);
    }
    if(isset($filter_preffered_location) && !empty($filter_preffered_location))
    {
        $builder->whereIn('can_worklocation_multiple.g_location_id',$filter_preffered_location);
    }
    if(isset($filter_education_background) && !empty($filter_education_background))
    {
        
        $builder->whereIn('can_education_details.education_course',$filter_education_background);
        $builder->where('can_education_details.status','1');
    }
    if(isset($filter_education_specialization) && !empty($filter_education_specialization))
    {
        
        $builder->whereIn('can_education_details.education_specialization',$filter_education_specialization);
        $builder->where('can_education_details.status','1');
    }
    if(isset($filter_education_college) && !empty($filter_education_college))
    {
        
        $builder->whereIn('can_education_details.education_college_name',$filter_education_college);
        $builder->where('can_education_details.status','1');
 
    }
     if(isset($filter_graduation_year) && !empty($filter_graduation_year))
    {
        
        $builder->whereIn('can_education_details.education_end_year',$filter_graduation_year);
        $builder->where('can_education_details.status','1');
    // $builder->orderBy('can_education_details.education_end_year','desc')->limit('1');
    }            
    if(isset($filter_education_skills) && !empty($filter_education_skills))
    {
         
  
        $builder->whereIn('can_skills_details.skills',$filter_education_skills);

    }

    $builder->groupBy('can_education_details.userid');
    if(!empty($total) && !empty($start_id)){
        $builder->limit($total,$start_id);
    }
  $result = $builder->get();
//  echo "<br>". $this->db->getLastQuery(); die;
  if(count($result->getResultArray())>0){
      return $result->getResult();
  }
  else{
      return false;
  }
   
  }
  
   public function fetch_candidate_data_all_folder2($tablename,$where,$filter_profile_district=NULL,$filter_education_skills=NULL,$filter_education_background=NULL,$filter_gender=NULL,$filter_education_college=NULL,$filter_preffered_location=NULL,$filter_graduation_year=NULL,$total=NULL,$start_id=NULL,$keyword_search=NULL)
  {
    $builder = $this->db->table($tablename);
    if(isset($keyword_search) && !empty($keyword_search))
          {
             
              $builder->like('profile_full_name',$keyword_search);
              
          }
  $builder->select('employer_folder_data.*,can_personal_details.userid,can_personal_details.profile_full_name,can_personal_details.g_location_id,can_personal_details.g_location_name,can_education_details.userid,can_address_details.communication_state');
  $builder->join('can_personal_details', 'employer_folder_data.candidate_id = can_personal_details.userid','left');
   $builder->join('can_address_details', 'employer_folder_data.candidate_id = can_address_details.userid','left');
  $builder->join('can_education_details', 'employer_folder_data.candidate_id = can_education_details.userid','left');
  $builder->join('can_skills_details', 'employer_folder_data.candidate_id = can_skills_details.userid','left');
  $builder->join('can_worklocation_multiple', 'can_personal_details.userid = can_worklocation_multiple.user_id','left');

    $builder->where($where);
    if(isset($filter_gender) && !empty($filter_gender))
    {
        $builder->where('can_personal_details.profile_gender',$filter_gender);
    }
    if(isset($filter_profile_district) && !empty($filter_profile_district))
    {
        $builder->whereIn('can_personal_details.g_location_id',$filter_profile_district);
    }
    if(isset($filter_preffered_location) && !empty($filter_preffered_location))
    {
        $builder->whereIn('can_worklocation_multiple.g_location_id',$filter_preffered_location);
    }
    if(isset($filter_education_background) && !empty($filter_education_background))
    {
        
        $builder->whereIn('can_education_details.education_course',$filter_education_background);
        $builder->where('can_education_details.status','1');
    }
    if(isset($filter_education_college) && !empty($filter_education_college))
    {
        
        $builder->whereIn('can_education_details.education_college_name',$filter_education_college);
        $builder->where('can_education_details.status','1');
 
    }
             
    if(isset($filter_education_skills) && !empty($filter_education_skills))
    {
         
  
        $builder->whereIn('can_skills_details.skills',$filter_education_skills);

    }

    $builder->groupBy('can_education_details.userid');
    if(!empty($total) && !empty($start_id)){
        $builder->limit($total,$start_id);
    }
  $result = $builder->get();
//  echo "<br>". $this->db->getLastQuery(); die;
  if(count($result->getResultArray())>0){
      return $result->getResult();
  }
  else{
      return false;
  }
   
  }

  public function emp_rating_candidate($userid)
    {
       $date = date('Y-m-d');
       extract($_REQUEST);
       $builder = $this->db->table('can_applied_internship');
       $builder->where(array('id'=>$edit_can_id,'status'=>'1'));
       $builder->update(array('emp_ratings'=>$rating_value,'emp_feedback'=>$add_can_feedback,'ratings_given_by'=>$userid,'ratings_given_date'=>$date)); 
       if($this->db->affectedRows()==1)
       {
           return true;
       }else
       {
           return false;
       }
    }

  //chat process

  public function current_datetime() // COMMON DAte TIME FUNCTION //
  {
      date_default_timezone_set('Asia/Kolkata');
      $date = date('Y-m-d');
      $time = date('H:i:s');
      $datetime = $date . " " . $time;
      return $datetime;
  } // COMMON DAte TIME FUNCTION //

  public function candidate_names($tablename, $where) //FUNCTION FOR GETTING CANDIDATE NAMES
  {
      $builder = $this->db->table($tablename);
      $builder->select("profile_full_name");
      $builder->where($where);
      $result = $builder->get();
      if (count($result->getResultArray()) > 0) {
          return $result->getRow();
      } else {
          return false;
      }
  } //FUNCTION FOR GETTING CANDIDATE NAMES



  public function update_message_status($tablename, $receiver_id, $sender_id, $data) // FUNCTION FOR UPDATE THE MESSAGE STATUS 
  {
      $where = "`sender_id`= '$receiver_id' AND `receiver_id` = '$sender_id'";
      $builder = $this->db->table($tablename);
      $builder->where($where);
      $builder->update($data);
      if ($this->db->affectedRows() == 1) {
          return true;
      }
      return false;
  } // FUNCTION FOR UPDATE THE MESSAGE STATUS 

  public function msg_status($tablename, $sender_id) // FUNCTION FOR GETTING THE MESSAGE STATUS
  {
      $where = "`receiver_id`= $sender_id  AND `message_status` = 1";

      $builder = $this->db->table($tablename);
      $builder->select("id,sender_id,count(id) as msg_count");
      $builder->where($where);
    //   $builder->whereIn(implode(',',$receiver_id));
      $builder->groupBy('sender_id');
      $result = $builder->get();
    //   echo "<br>". $this->db->getLastQuery();die;
      return $result->getResult();

//     $builder = $this->db->table($tablename);
//     $builder->select('receiver_id,id'); 
   
//     $builder->where($where); 
//     // $builder->whereIn(implode(',',$receiver_id));
//     $builder->groupBy('receiver_id');
//     $builder->whereIn('id', function(BaseBuilder $builder) {
//        return $builder->select('COUNT(id)', false)->from('chat')->groupBy('receiver_id');
//    });
  
//     $result = $builder->get();
//     echo "<br>". $this->db->getLastQuery(); die;
//    return $result->getResult();
  } // FUNCTION FOR GETTING THE MESSAGE STATUS

  public function fetch_chat_data($tablename, $receiver_id, $sender_id) // FUNCTION FOR GETTING CHATS COMMON
  {
      $where = "`sender_id`= '$sender_id' AND `receiver_id` = '$receiver_id' OR `sender_id`= '$receiver_id' AND `receiver_id` = '$sender_id'";
      $builder = $this->db->table($tablename);
      $builder->select("*");
      $builder->where($where);
      $builder->orderBy("id", "asc");
      $result = $builder->get();
      if (count($result->getResultArray()) > 0) {
          return $result->getResult();
      } else {
          return false;
      }
  } // FUNCTION FOR GETTING CHATS COMMON


  public function fetch_master_table_data($tablename, $where) // FUNCTION FOR GETTING MASTER TABLE DATAS FOR EMPLOYEE
  {
      $builder = $this->db->table($tablename);
      $builder->select("profile");
      $builder->where($where);
      $result = $builder->get();
      if (count($result->getResultArray()) > 0) {
          return $result->getResult();
      } else {
          return false;
      }
  } // FUNCTION FOR GETTING MASTER TABLE DATAS FOR EMPLOYEE

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

    public function fetch_single_employer_internship($tablename,$where,$order_by=NULL,$total=NULL,$start_id=NULL)
    {
      $builder = $this->db->table($tablename);
          $builder->select("*");
          $builder->where($where);
          if($order_by!=NULL && isset($order_by['ordercolumn']) && isset($order_by['ordertype']))
          {
          $builder->orderBy($order_by['ordercolumn'],$order_by['ordertype']);
          }
          // $builder->orderBy($order_by);
          if(isset($total) && isset($start_id)){
            $builder->limit($total,$start_id);
          }
          $result = $builder->get();
          // echo "<br>". $this->db->getLastQuery(); die;
          if(count($result->getResultArray())>0){
              return $result->getResult();
          }
          else{
              return false;
          }
     
    }

    public function fetch_table_data_for_pagination($tablename,$where,$order_by=NULL,$total=NULL,$start_id=NULL)
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

    public function fetch_table_data_for_home_logo($tablename,$where,$order_by=NULL)
    {
      $builder = $this->db->table($tablename);
          $builder->select("profile_completion_form.id,profile_completion_form.userid,profile_completion_form.profile_company_name,profile_completion_form.profile_company_logo");
          $builder->join('userlogin', 'profile_completion_form.userid =  userlogin.userid','left');
          $builder->where($where);
          if($order_by!=NULL && isset($order_by['ordercolumn']) && isset($order_by['ordertype']))
          {
          $builder->orderBy($order_by['ordercolumn'],$order_by['ordertype']);
          }
        //   $builder->limit(17);
          $result = $builder->get();
        //   echo "<br>". $this->db->getLastQuery(); die;
          if(count($result->getResultArray())>0){
              return $result->getResult();
          }
          else{
              return false;
          }
     
    }

    public function fetch_table_data_for_folder($tablename,$where,$order_by=NULL,$total=NULL,$start_id=NULL,$keyword_search=NULL)
    {
        // $keyword_search='can';
      $builder = $this->db->table($tablename);
          $builder->select("*");
          if(isset($keyword_search) && !empty($keyword_search))
          {
             
              $builder->like('folder_name',$keyword_search);
              
          }
          $builder->where($where);
          if($order_by!=NULL && isset($order_by['ordercolumn']) && isset($order_by['ordertype']))
          {
          $builder->orderBy($order_by['ordercolumn'],$order_by['ordertype']);
          }
          if(isset($total) && isset($start_id)){
            $builder->limit($total,$start_id);
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
    public function data_count_fetch1($tablename,$where)
    {
      $builder = $this->db->table($tablename);
      $builder->select("id");
      $builder->where($where);
    
      return $builder->countAllResults();
   
    }
    function fetch_table_data_group_by_chat($internship_id)
    {
        $session = session();
        $userid    =    $session->get('userid');
         $builder = $this->db->table('chat');
         $builder->select('chat.receiver_id,chat.id'); 
         $builder->join('can_applied_internship', 'can_applied_internship.candidate_id = chat.receiver_id','left');
         $builder->where('can_applied_internship.internship_id',$internship_id); 
         $builder->where('chat.sender_id',$userid); 
         $builder->whereIn('chat.id', function(BaseBuilder $builder) {
            return $builder->select('MAX(chat.id)', false)->from('chat')->groupBy('chat.receiver_id');
        });
        $builder->orderBy('chat.id','desc');
        //  $builder->groupBy('chat.receiver_id');
         $result = $builder->get();
        //  echo "<br>". $this->db->getLastQuery(); die;
        return $result->getResult();
    }

    function fetch_table_data_group_by_chat_unread($internship_id)
    {
        $session = session();
        $userid    =    $session->get('userid');
         $builder = $this->db->table('chat');
         $builder->select('chat.receiver_id,chat.id'); 
         $builder->join('can_applied_internship', 'can_applied_internship.candidate_id = chat.receiver_id','left');
         $builder->where('can_applied_internship.internship_id',$internship_id); 
         $builder->where('chat.receiver_id',$userid); 
         $builder->where('chat.message_status','1'); 
         $builder->whereIn('chat.id', function(BaseBuilder $builder) {
            return $builder->select('MAX(chat.id)', false)->from('chat')->groupBy('chat.receiver_id');
        });
        $builder->orderBy('chat.id','desc');
        //  $builder->groupBy('chat.receiver_id');
         $result = $builder->get();
        //  echo "<br>". $this->db->getLastQuery(); die;
        return $result->getResult();
    }

    public function fetch_candidate_data_depen($tablename,$where,$filter_profile_district=NULL,$filter_education_skills=NULL,$filter_education_background=NULL,$filter_education_specialization=NULL,$filter_gender=NULL,$filter_education_college=NULL,$filter_internship_lable=NULL,$filter_graduation_year=NULL ,$total=NULL,$start_id=NULL)
    // public function fetch_candidate_data($tablename,$where,$filter_profile_district=NULL,$filter_education_skills=NULL,$filter_education_background=NULL,$filter_gender=NULL,$total=NULL,$start_id=NULL)

    {
        // print_r($filter_internship_lable);exit;
        $builder = $this->db->table($tablename);
        $builder->select('can_applied_internship.*,can_personal_details.profile_full_name,can_personal_details.g_location_id,can_personal_details.g_location_name,can_address_details.communication_district,can_address_details.communication_state');
        $builder->join('can_personal_details', 'can_applied_internship.candidate_id = can_personal_details.userid','left');
        $builder->join('can_address_details', 'can_applied_internship.candidate_id = can_address_details.userid','left');
        $builder->join('can_education_details', 'can_applied_internship.candidate_id = can_education_details.userid','left');
        $builder->join('can_skills_details', 'can_applied_internship.candidate_id = can_skills_details.userid','left');
        $builder->join('employer_label_data', 'can_applied_internship.candidate_id = employer_label_data.candidate_id','left');
        $builder->where($where);
        if(isset($filter_gender) && !empty($filter_gender))
        {
            $builder->where('can_personal_details.profile_gender',$filter_gender);
        }
        if(isset($filter_profile_district) && !empty($filter_profile_district))
        {
            $filter_profile_district_arr = implode(',', $filter_profile_district);
            $builder->where("can_personal_details.g_location_id IN ($filter_profile_district_arr)");
            // $builder->whereIn('can_personal_details.g_location_id',$filter_profile_district);
        }
        if(isset($filter_education_background) && !empty($filter_education_background))
        {
            $builder->where("can_education_details.education_course IN ($filter_education_background)");
            // $builder->whereIn('can_education_details.education_course',$filter_education_background);
            $builder->where('can_education_details.status','1');
        }
        if(isset($filter_education_specialization) && !empty($filter_education_specialization))
        {
            $builder->where("can_education_details.education_specialization IN ($filter_education_specialization)");
            // $builder->whereIn('can_education_details.education_course',$filter_education_background);
            $builder->where('can_education_details.status','1');
        }
        if(isset($filter_education_college) && !empty($filter_education_college))
        {
            
            // $builder->whereIn('can_education_details.education_college_name',$filter_education_college);
            $builder->where("can_education_details.education_college_name IN ($filter_education_college)");
            $builder->where('can_education_details.status','1');
        }
        if(isset($filter_education_skills) && !empty($filter_education_skills))
        {
            $builder->where("can_skills_details.skills IN ($filter_education_skills)");
            $builder->where('can_skills_details.status','1');
            // $builder->whereIn('can_skills_details.skills',$filter_education_skills);
        }
        if(isset($filter_internship_lable) && !empty($filter_internship_lable))
        {
            $builder->where("employer_label_data.label_id IN ($filter_internship_lable)");
            $builder->where('employer_label_data.status','1');
        }
         if(isset($filter_graduation_year) && !empty($filter_graduation_year))
        {
            $builder->where("can_education_details.education_end_year IN ($filter_graduation_year)");
            
           // $builder->whereIn('can_education_details.education_end_year', function(BaseBuilder $builder) {
        //return $builder->select('MAX(education_end_year)', false)->from('can_education_details')->whereIn('education_end_year',$filter_graduation_year);
    //});
            
            $builder->where('can_education_details.status','1');
        }
        
        $builder->groupBy('can_education_details.userid');
        if(isset($total) && isset($start_id)){
            $builder->limit($total,$start_id);
        }
        $result = $builder->get();
           //echo "<br>". $this->db->getLastQuery(); die;
        if(count($result->getResultArray())>0){
        return $result->getResult();
        }
        else{
        return false;
        }

    }

    public function fetch_candidate_data_depen_search($tablename,$where,$filter_profile_district=NULL,$filter_education_skills=NULL,$filter_education_background=NULL,$filter_education_specialization=NULL,$filter_gender=NULL,$filter_education_college=NULL,$filter_preffered_location=NULL,$filter_graduation_year=NULL,$total=NULL,$start_id=NULL)
    // public function fetch_candidate_data($tablename,$where,$filter_profile_district=NULL,$filter_education_skills=NULL,$filter_education_background=NULL,$filter_gender=NULL,$total=NULL,$start_id=NULL)

    {
        // print_r($filter_profile_district);exit;
        $builder = $this->db->table($tablename);
        $builder->select('can_personal_details.userid as candidate_id,can_personal_details.profile_full_name,can_personal_details.g_location_id,can_personal_details.g_location_name,can_address_details.communication_district,can_address_details.communication_state,can_worklocation_multiple.user_id');
        // $builder->join('can_personal_details', 'can_personal_details.candidate_id = can_personal_details.userid','left');
        $builder->join('can_address_details', 'can_personal_details.userid = can_address_details.userid','left');
        $builder->join('can_education_details', 'can_personal_details.userid = can_education_details.userid','left');
        $builder->join('can_skills_details', 'can_personal_details.userid = can_skills_details.userid','left');
        $builder->join('can_worklocation_multiple', 'can_personal_details.userid = can_worklocation_multiple.user_id','left');
        $builder->where($where);
        if(isset($filter_gender) && !empty($filter_gender))
        {
            $builder->where('can_personal_details.profile_gender',$filter_gender);
        }
        if(isset($filter_profile_district) && !empty($filter_profile_district))
        {
            $filter_profile_district_arr = implode(',', $filter_profile_district);
            $builder->where("can_personal_details.g_location_id IN ($filter_profile_district_arr)");
            // $builder->whereIn('can_personal_details.g_location_id',$filter_profile_district);
        }
        if(isset($filter_preffered_location) && !empty($filter_preffered_location))
        {
            $filter_preffered_location_arr = implode(',', $filter_preffered_location);
            $builder->where("can_worklocation_multiple.g_location_id IN ($filter_preffered_location_arr)");
            // $builder->whereIn('can_personal_details.g_location_id',$filter_profile_district);
        }
        if(isset($filter_education_background) && !empty($filter_education_background))
        {
            $builder->where("can_education_details.education_course IN ($filter_education_background)");
            // $builder->whereIn('can_education_details.education_course',$filter_education_background);
            $builder->where('can_education_details.status','1');
        }
        if(isset($filter_education_specialization) && !empty($filter_education_specialization))
        {
            $builder->where("can_education_details.education_specialization IN ($filter_education_specialization)");
            // $builder->whereIn('can_education_details.education_course',$filter_education_background);
            $builder->where('can_education_details.status','1');
        }
        
        if(isset($filter_education_college) && !empty($filter_education_college))
        {
            
            // $builder->whereIn('can_education_details.education_college_name',$filter_education_college);
            $builder->where("can_education_details.education_college_name IN ($filter_education_college)");
            $builder->where('can_education_details.status','1');
        }
        if(isset($filter_education_skills) && !empty($filter_education_skills))
        {
            $builder->where("can_skills_details.skills IN ($filter_education_skills)");
            $builder->where('can_skills_details.status','1');
            // $builder->whereIn('can_skills_details.skills',$filter_education_skills);
        }
        if(isset($filter_graduation_year) && !empty($filter_graduation_year))
        {
            $builder->where("can_education_details.education_end_year IN ($filter_graduation_year)");
            
           // $builder->whereIn('can_education_details.education_end_year', function(BaseBuilder $builder) {
        //return $builder->select('MAX(education_end_year)', false)->from('can_education_details')->whereIn('education_end_year',$filter_graduation_year);
    //});
            
            $builder->where('can_education_details.status','1');
        }
        $builder->groupBy('can_education_details.userid');
        if(isset($total) && isset($start_id)){
            $builder->limit($total,$start_id);
        }
        $result = $builder->get();
          //echo "<br>". $this->db->getLastQuery();
        if(count($result->getResultArray())>0){
        return $result->getResult();
        }
        else{
        return false;
        }

    }
    function fetch_table_data_group_by_chat_folder($folder_id)
    {
        $session = session();
        $userid    =    $session->get('userid');
         $builder = $this->db->table('chat');
         $builder->select('chat.receiver_id,chat.id'); 
         $builder->join('employer_folder_data', 'employer_folder_data.candidate_id = chat.receiver_id','left');
         $builder->where('employer_folder_data.folder_id ',$folder_id); 
         $builder->where('chat.sender_id',$userid); 
         $builder->groupBy('chat.receiver_id');
         $builder->whereIn('chat.id', function(BaseBuilder $builder) {
            return $builder->select('MAX(chat.id)', false)->from('chat')->groupBy('chat.receiver_id');
        });
        $builder->orderBy('chat.id','desc');
        //  $builder->groupBy('chat.receiver_id');
         $result = $builder->get();
        //  echo "<br>". $this->db->getLastQuery(); die;
        return $result->getResult();
    }
    function fetch_table_data_group_by_chat_folder_unread($folder_id)
    {
        $session = session();
        $userid    =    $session->get('userid');
         $builder = $this->db->table('chat');
         $builder->select('chat.receiver_id,chat.id'); 
         $builder->join('employer_folder_data', 'employer_folder_data.candidate_id = chat.receiver_id','left');
         $builder->where('employer_folder_data.folder_id ',$folder_id); 
         $builder->where('chat.receiver_id',$userid);
         $builder->where('chat.message_status','1');  
         $builder->groupBy('chat.receiver_id');
         $builder->whereIn('chat.id', function(BaseBuilder $builder) {
            return $builder->select('MAX(chat.id)', false)->from('chat')->groupBy('chat.receiver_id');
        });
        $builder->orderBy('chat.id','desc');
        //  $builder->groupBy('chat.receiver_id');
         $result = $builder->get();
        //  echo "<br>". $this->db->getLastQuery(); die;
        return $result->getResult();
    }

    public function fetch_candidate_data_depen_folder($tablename,$where,$filter_profile_district=NULL,$filter_education_skills=NULL,$filter_education_background=NULL,$filter_education_specialization=NULL,$filter_gender=NULL,$filter_education_college=NULL,$filter_preffered_location=NULL,$filter_graduation_year=NULL,$total=NULL,$start_id=NULL)
    // public function fetch_candidate_data($tablename,$where,$filter_profile_district=NULL,$filter_education_skills=NULL,$filter_education_background=NULL,$filter_gender=NULL,$total=NULL,$start_id=NULL)

    {
        // print_r($filter_profile_district);exit;
        $builder = $this->db->table($tablename);
        $builder->select('employer_folder_data.*,can_personal_details.profile_full_name,can_personal_details.g_location_id,can_personal_details.g_location_name,can_address_details.communication_district,can_address_details.communication_state,can_worklocation_multiple.user_id');
        $builder->join('can_personal_details', 'employer_folder_data.candidate_id = can_personal_details.userid','left');
        $builder->join('can_address_details', 'employer_folder_data.candidate_id = can_address_details.userid','left');
        $builder->join('can_education_details', 'employer_folder_data.candidate_id = can_education_details.userid','left');
        $builder->join('can_skills_details', 'employer_folder_data.candidate_id = can_skills_details.userid','left');
        $builder->join('can_worklocation_multiple', 'employer_folder_data.candidate_id = can_worklocation_multiple.user_id','left');
        $builder->where($where);
        if(isset($filter_gender) && !empty($filter_gender))
        {
            $builder->where('can_personal_details.profile_gender',$filter_gender);
        }
        if(isset($filter_profile_district) && !empty($filter_profile_district))
        {
            $filter_profile_district_arr = implode(',', $filter_profile_district);
            $builder->where("can_personal_details.g_location_id IN ($filter_profile_district_arr)");
            // $builder->whereIn('can_personal_details.g_location_id',$filter_profile_district);
        }
        if(isset($filter_preffered_location) && !empty($filter_preffered_location))
        {
            $filter_preffered_location_arr = implode(',', $filter_preffered_location);
            $builder->where("can_worklocation_multiple.g_location_id IN ($filter_preffered_location_arr)");
            // $builder->whereIn('can_personal_details.g_location_id',$filter_profile_district);
        }
        if(isset($filter_education_background) && !empty($filter_education_background))
        {
            $builder->where("can_education_details.education_course IN ($filter_education_background)");
            // $builder->whereIn('can_education_details.education_course',$filter_education_background);
            $builder->where('can_education_details.status','1');
        }
        if(isset($filter_education_specialization) && !empty($filter_education_specialization))
        {
            $builder->where("can_education_details.education_specialization IN ($filter_education_specialization)");
            // $builder->whereIn('can_education_details.education_course',$filter_education_background);
            $builder->where('can_education_details.status','1');
        }
        if(isset($filter_education_college) && !empty($filter_education_college))
        {
            
            // $builder->whereIn('can_education_details.education_college_name',$filter_education_college);
            $builder->where("can_education_details.education_college_name IN ($filter_education_college)");
            $builder->where('can_education_details.status','1');
        }
        if(isset($filter_education_skills) && !empty($filter_education_skills))
        {
            $builder->where("can_skills_details.skills IN ($filter_education_skills)");
            $builder->where('can_skills_details.status','1');
            // $builder->whereIn('can_skills_details.skills',$filter_education_skills);
        }
         if(isset($filter_graduation_year) && !empty($filter_graduation_year))
        {
            $builder->where("can_education_details.education_end_year IN ($filter_graduation_year)");
            
           // $builder->whereIn('can_education_details.education_end_year', function(BaseBuilder $builder) {
        //return $builder->select('MAX(education_end_year)', false)->from('can_education_details')->whereIn('education_end_year',$filter_graduation_year);
    //});
            
            $builder->where('can_education_details.status','1');
        }
        $builder->groupBy('can_education_details.userid');
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

    public function fetch_table_data_group_by_unread($tablename,$where,$order_by=NULL)
    {
      $builder = $this->db->table($tablename);
      $builder->select('*');
      $builder->where($where);
      $builder->whereIn('id', function(BaseBuilder $builder) {
          return $builder->select('MAX(id)', false)->from('chat')->groupBy('sender_id');
      });
      if($order_by!=NULL && isset($order_by['ordercolumn']) && isset($order_by['ordertype']))
          {
          $builder->orderBy($order_by['ordercolumn'],$order_by['ordertype']);
          }
      $query = $builder->get()->getResult();
      // echo "<br>". $this->db->getLastQuery(); die;
      return $query; 

     
    }




    public function msg_status_unread($tablename, $sender_id,$reciver_id)
    {
        $where = "`receiver_id`= $sender_id AND `sender_id`= $reciver_id AND `message_status` = 1";
      $builder = $this->db->table($tablename);
      $builder->select("id");
      $builder->where($where);

      return $builder->countAllResults();
   
    }

  //------------------------------------------------------------------------------
   //Employer dashboard analysis - total application, shortlisted and hired count - start
    public function get_application_analysis($tablename,$where)
        {
            $builder = $this->db->table($tablename);
            $builder->select("can_applied_internship.id,can_applied_internship.internship_id,can_applied_internship.application_status,can_applied_internship.hiring_status");
            $builder->join('employer_post_internship','can_applied_internship.internship_id = employer_post_internship.internship_id');
            $builder->where($where);
            $result = $builder->get();

            if(count($result->getResultArray())>0) { return $result->getResult(); }
            else { return false; }

           // return $builder->countAllResults();
       // echo "<br>". $this->db->getLastQuery(); die;  
        } 
   //Employer dashboard analysis - total application, shortlisted and hired count - end
   //------------------------------------------------------------------------------

  //------------------------------------------------------------------------------
   //Employer dashboard analysis - all applications - candidate list - start
    public function get_application_details($company_id)
        {
            $builder = $this->db->table("can_applied_internship");
            $builder->select("can_personal_details.userid,can_personal_details.profile_full_name,can_personal_details.profile_phone_number,can_personal_details.profile_email,can_applied_internship.application_status,can_applied_internship.created_at,can_applied_internship.internship_id, can_personal_details.profile_gender");
            $builder->join("can_personal_details","can_applied_internship.candidate_id = can_personal_details.userid","left"); 
            $builder->join("employer_post_internship","can_applied_internship.internship_id = employer_post_internship.internship_id","left");              
            $builder->where('employer_post_internship.company_id',$company_id);
            $builder->orderBy("can_applied_internship.id", "desc");
            $builder->limit(1000);
            $result = $builder->get();
            return $result->getResult();
        }
          public function get_application_details_all($company_id)
        {
            $builder = $this->db->table("can_applied_internship");
            $builder->select("can_applied_internship.id,can_applied_internship.application_status");
            $builder->join("employer_post_internship","can_applied_internship.internship_id = employer_post_internship.internship_id","left");              
            $builder->where('employer_post_internship.company_id',$company_id);
            $builder->orderBy("can_applied_internship.id", "desc");
            //$builder->limit(1000);
            $result = $builder->get();
            return $result->getResult();
            //return $builder->countAllResults();
        }
        
   //Employer dashboard analysis - all applications - candidate list - end
  //------------------------------------------------------------------------------
  public function get_application_details_offer($company_id)
  {
      $builder = $this->db->table("can_applied_internship");
      $builder->select("can_personal_details.userid,can_personal_details.profile_full_name,can_personal_details.profile_phone_number,can_personal_details.profile_email,can_applied_internship.application_status,can_applied_internship.created_at,can_applied_internship.internship_id, can_personal_details.profile_gender");
      $builder->join("can_personal_details","can_applied_internship.candidate_id = can_personal_details.userid","left"); 
      $builder->join("employer_post_internship","can_applied_internship.internship_id = employer_post_internship.internship_id","left");              
      $builder->where('employer_post_internship.company_id',$company_id);
      $builder->where('can_applied_internship.hiring_status','1');
      $builder->orderBy("can_applied_internship.id", "desc");
      $builder->limit(1000);
      $result = $builder->get();
      return $result->getResult();
  }
  public function get_application_details_offer1($company_id)
  {
      $builder = $this->db->table("can_applied_internship");
      $builder->select("can_applied_internship.id");
    $builder->join("employer_post_internship","can_applied_internship.internship_id = employer_post_internship.internship_id","left");              
      $builder->where('employer_post_internship.company_id',$company_id);
      $builder->where('can_applied_internship.hiring_status','1');
      $builder->orderBy("can_applied_internship.id", "desc");
  return $builder->countAllResults();
  }
   //------------------------------------------------------------------------------
   //Employer dashboard analysis - get specific data like gender, candidate name etc - start
    public function getspecificdata($tablename, $where, $columnname)
    {
      $builder = $this->db->table($tablename);
      $builder->select($columnname);
      $builder->where($where);
      $result = $builder->get();
    //   echo "<br>". $this->db->getLastQuery(); die;
      return $result->getResult();
   
    }
    //Employer dashboard analysis - get specific data like gender, candidate name etc - end
    //------------------------------------------------------------------------------

     //------------------------------------------------------------------------------
   //Employer dashboard analysis - get profile name - start
    public function get_profile_name_from_internshipid($internship_id)
    {
      $builder = $this->db->table('employer_post_internship');
      $builder->select('master_profile.profile,employer_post_internship.other_profile');
      $builder->join("master_profile","employer_post_internship.profile = master_profile.id","left");
      $builder->where('employer_post_internship.internship_id',$internship_id);
      $result = $builder->get();   
      return $result->getResult();
    }
    //Employer dashboard analysis - get profile name - end
    //------------------------------------------------------------------------------

    public function get_application_details_badges($where)
        {
            $builder = $this->db->table("can_applied_internship");
            $builder->select("can_applied_internship.internship_id,can_applied_internship.created_at,can_applied_internship.candidate_id,can_applied_internship.hiring_date,can_applied_internship.created_at,can_applied_internship.application_status");
            $builder->where($where);
            $builder->orderBy("can_applied_internship.created_at", "desc");
            $result = $builder->get();
            //echo "<br>". $this->db->getLastQuery(); die;
            return $result->getResult();
        }

        public function get_application_details_badges_folder($where)
        {
            $builder = $this->db->table("employer_folder_data");
            $builder->select("employer_folder_data.candidate_id,employer_folder_data.folder_id,employer_folder_data.created_at");
            $builder->where($where);
            $builder->orderBy("employer_folder_data.created_at", "desc");
            $result = $builder->get();
            //echo "<br>". $this->db->getLastQuery(); die;
            return $result->getResult();
        }

        public function get_folder_name_from_folderid($folder_id)
        {
          $builder = $this->db->table('employer_folder_data');
          $builder->select('employer_folder.folder_name,employer_folder_data.folder_id');
          $builder->join("employer_folder","employer_folder_data.folder_id = employer_folder.id","left");
          $builder->where('employer_folder_data.folder_id',$folder_id);
          $result = $builder->get();   
          return $result->getResult();
        }


        function assignment_sent_for_candidate($table,$where,$order_by=NULL)
    {
         $builder = $this->db->table('chat');
         $builder->select('can_applied_internship.internship_id,can_applied_internship.candidate_id,chat.sender_id,chat.receiver_id,chat.type,chat.title,chat.internship_id,chat.last_date_sub,chat.interview_date,chat.interview_time,chat.evaluated_status,chat.link,chat.interview_status,chat.interview_duration,chat.interview_mode,chat.id,chat.evaluated_status,chat.attachment_filename,chat.attachment_name'); 
         $builder->join('can_applied_internship', 'can_applied_internship.candidate_id = chat.receiver_id','left');
         $builder->where($where);
            // print_r($where);die;
          $builder->orderBy("chat.id", "desc");
        //  $builder->groupBy('can_applied_internship.internship_id');
         $result = $builder->get();
        //   echo "<br>". $this->db->getLastQuery(); die;
        return $result->getResult();
    }

    public function fetch_rating_data($tablename,$where)
    {
        $builder = $this->db->table($tablename);
        $builder->select("sum(can_applied_internship.can_ratings)as rating,count(can_applied_internship.candidate_id)as count ");
        $builder->join('can_applied_internship','employer_post_internship.internship_id = can_applied_internship.internship_id');
        $builder->where($where);
        $result = $builder->get();
        // echo "<br>". $this->db->getLastQuery(); die;  
        if(count($result->getResultArray())>0) { return $result->getResult(); }
        else { return false; }

       // return $builder->countAllResults();
   // echo "<br>". $this->db->getLastQuery(); die;  
    } 

}
