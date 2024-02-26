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
class Candidate_model extends Model {

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

    public function update_commen($tablename,$where,$data){
        $builder = $this->db->table($tablename);
        $builder->where($where);
        $builder->update($data);
        // return "<br>". $this->db->getLastQuery();die;
        if($this->db->affectedRows()==1)
        {
            return true;
        }
            return false;
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

    public function fetch_table_data_filter(  $where=NULL, $filter_category=NULL, $filter_city=NULL, $filter_emp=NULL, $filter_start_date=NULL, $filter_internship_duration_one=NULL, $filter_parttime=NULL, $filter_work_from_home=NULL, $filter_job_offer=NULL, $filter_stipend=NULL,$total=NULL,$start_id=NULL,$keyword_search=null)
    {
        //print_r($keyword_search);exit();
        $builder = $this->db->table('employer_post_internship');
        $builder->select("'open' flag,employer_post_internship.*,emp_worklocation_multiple.g_location_id,emp_worklocation_multiple.g_location_name");
        $builder->join('emp_worklocation_multiple', 'emp_worklocation_multiple.internship_id = employer_post_internship.internship_id','left');
        $builder->join('master_profile', 'master_profile.id = employer_post_internship.profile','left');
        $builder->join('profile_completion_form', 'profile_completion_form.userid = employer_post_internship.user_id','left'); 
        
        $current_date =date("Y-m-d");
       // print_r($filter_city);
        // if(isset($filter_city) && !empty($filter_city))
        // {
        //     foreach($filter_city as $city){

                $builder->whereIn('emp_worklocation_multiple.g_location_id',$filter_city);
        //     }
            
        // }
        if(isset($filter_category) && !empty($filter_category))
        {
            $builder->whereIn('employer_post_internship.profile',$filter_category);
        }
        if(isset($filter_emp) && !empty($filter_emp))
        {
            $builder->whereIn('employer_post_internship.company_id',$filter_emp);
        }
        if(isset($filter_start_date) && !empty($filter_start_date))
        {
            $builder->where('employer_post_internship.internship_startdate >=',$filter_start_date);
        }
        if(isset($filter_internship_duration_one) && !empty($filter_internship_duration_one))
        {
            $builder->where('employer_post_internship.duration_days<=',$filter_internship_duration_one);
        }
        // if(isset($filter_internship_duration_two) && !empty($filter_internship_duration_two))
        // {
        //     $builder->where('employer_post_internship.internship_duration',$filter_internship_duration_two);
        // }
        if(isset($filter_parttime) && !empty($filter_parttime))
        {
            if($filter_parttime==1){
                $builder->where('employer_post_internship.partime','1');
            }
            if($filter_parttime==2){
                $builder->where('employer_post_internship.partime','2');
            }
            
        }
        // if(isset($filter_fulltime) && !empty($filter_fulltime))
        // {
        //     $builder->where('partime','2');
        // }
        if(isset($filter_work_from_home) && !empty($filter_work_from_home))
        {
            if($filter_work_from_home==1){
                $builder->where('employer_post_internship.internship_type','1');
            }
            if($filter_work_from_home==2){
                $builder->where('employer_post_internship.internship_type','2');
            }
        }
        if(isset($filter_job_offer) && !empty($filter_job_offer))
        {
            $builder->where('employer_post_internship.pre_placement_offer','1');
        }
        if(isset($filter_stipend) && !empty($filter_stipend))
        {
            // $builder->where('amount_from >=',$filter_stipend);
            // $builder->orWhere('amount_to >=',$filter_stipend);
            $builder->where("employer_post_internship.amount_from BETWEEN '$filter_stipend' AND '1000000'");
        }
        if(isset($total) && isset($start_id)){
            $builder->limit($total,$start_id);
        }
        

        $builder->where('employer_post_internship.status',1);
        $builder->where('employer_post_internship.active_status',1);
        $builder->where('employer_post_internship.view_status',1);
        $builder->where('employer_post_internship.internship_startdate >=',$current_date);
        $builder->where('employer_post_internship.internship_candidate_lastdate >=',$current_date);
        //search with keyword
         if(isset($keyword_search) && !empty($keyword_search))
            {
                $builder->groupStart();
                $builder->like('emp_worklocation_multiple.g_location_name',$keyword_search);
                $builder->orLike('master_profile.profile',$keyword_search);
                $builder->orLike('profile_completion_form.profile_company_name',$keyword_search);
                $builder->orLike('employer_post_internship.other_profile',$keyword_search);
                $builder->groupEnd();
            }
        $builder->orderBy('employer_post_internship.id','DESC');
        $builder->groupBy('employer_post_internship.id');
        $result = $builder->get();
        
        //if(count($result->getResultArray())>0){
            //   echo "<br>". $this->db->getLastQuery(); die;
            return $result->getResult();
        // }
        // else{
            // echo "<br>". $this->db->getLastQuery(); die;
           // return false;
        //}
   
    }

    public function fetch_table_data_filter_closed(  $where=NULL, $filter_category=NULL, $filter_city=NULL, $filter_emp=NULL, $filter_start_date=NULL, $filter_internship_duration_one=NULL, $filter_parttime=NULL, $filter_work_from_home=NULL, $filter_job_offer=NULL, $filter_stipend=NULL,$total=NULL,$start_id=NULL,$keyword_search=null)
    {
        //print_r($keyword_search);exit();
        $builder = $this->db->table('employer_post_internship');
        $builder->select("'closed' flag,employer_post_internship.*,emp_worklocation_multiple.g_location_id,emp_worklocation_multiple.g_location_name");
        $builder->join('emp_worklocation_multiple', 'emp_worklocation_multiple.internship_id = employer_post_internship.internship_id','left');
        $builder->join('master_profile', 'master_profile.id = employer_post_internship.profile','left');
        $builder->join('profile_completion_form', 'profile_completion_form.userid = employer_post_internship.user_id','left'); 
        
        $current_date =date("Y-m-d");
       // print_r($filter_city);
        // if(isset($filter_city) && !empty($filter_city))
        // {
        //     foreach($filter_city as $city){

                $builder->whereIn('emp_worklocation_multiple.g_location_id',$filter_city);
        //     }
            
        // }
        if(isset($filter_category) && !empty($filter_category))
        {
            $builder->whereIn('employer_post_internship.profile',$filter_category);
        }
        if(isset($filter_emp) && !empty($filter_emp))
        {
            $builder->whereIn('employer_post_internship.company_id',$filter_emp);
        }
        if(isset($filter_start_date) && !empty($filter_start_date))
        {
            $builder->where('employer_post_internship.internship_startdate >=',$filter_start_date);
        }
        if(isset($filter_internship_duration_one) && !empty($filter_internship_duration_one))
        {
            $builder->where('employer_post_internship.duration_days<=',$filter_internship_duration_one);
        }
        // if(isset($filter_internship_duration_two) && !empty($filter_internship_duration_two))
        // {
        //     $builder->where('employer_post_internship.internship_duration',$filter_internship_duration_two);
        // }
        if(isset($filter_parttime) && !empty($filter_parttime))
        {
            if($filter_parttime==1){
                $builder->where('employer_post_internship.partime','1');
            }
            if($filter_parttime==2){
                $builder->where('employer_post_internship.partime','2');
            }
            
        }
        // if(isset($filter_fulltime) && !empty($filter_fulltime))
        // {
        //     $builder->where('partime','2');
        // }
        if(isset($filter_work_from_home) && !empty($filter_work_from_home))
        {
            if($filter_work_from_home==1){
                $builder->where('employer_post_internship.internship_type','1');
            }
            if($filter_work_from_home==2){
                $builder->where('employer_post_internship.internship_type','2');
            }
        }
        if(isset($filter_job_offer) && !empty($filter_job_offer))
        {
            $builder->where('employer_post_internship.pre_placement_offer','1');
        }
        if(isset($filter_stipend) && !empty($filter_stipend))
        {
            // $builder->where('amount_from >=',$filter_stipend);
            // $builder->orWhere('amount_to >=',$filter_stipend);
            $builder->where("employer_post_internship.amount_from BETWEEN '$filter_stipend' AND '1000000'");
        }
        if(isset($total) && isset($start_id)){
            $builder->limit($total,$start_id);
        }
        

      
        //$builder->where('employer_post_internship.active_status',1);
        $builder->where('employer_post_internship.view_status',1);
        $builder->where('employer_post_internship.status',1);

        /*
        $builder->groupStart();
        $builder->where('employer_post_internship.active_status',0);
        $builder->orwhere('employer_post_internship.internship_startdate <',$current_date);
        $builder->orwhere('employer_post_internship.internship_candidate_lastdate <',$current_date);
        $builder->groupEnd();
        */
        //search with keyword
         if(isset($keyword_search) && !empty($keyword_search))
            {
                $builder->groupStart();
                $builder->like('emp_worklocation_multiple.g_location_name',$keyword_search);
                $builder->orLike('master_profile.profile',$keyword_search);
                $builder->orLike('profile_completion_form.profile_company_name',$keyword_search);
                $builder->orLike('employer_post_internship.other_profile',$keyword_search);
                $builder->groupEnd();
            }

        $order_by11 = array('ordercolumn' => 'employer_post_internship.active_status DESC, employer_post_internship.internship_candidate_lastdate DESC');

        $builder->orderBy($order_by11['ordercolumn']);
        $builder->groupBy('employer_post_internship.id');
        $result = $builder->get();
        
        //if(count($result->getResultArray())>0){
               //echo "<br>". $this->db->getLastQuery(); die;
            return $result->getResult();
        // }
        // else{
            // echo "<br>". $this->db->getLastQuery(); die;
           // return false;
        //}
   
    }

    public function filter_search_keyword()
    {
    $session = session();
    $userid    =    $session->get('userid');
    $keyword = $this->db->table('can_search_keyword');
    $keyword->select("can_search_keyword.search_key");
    $keyword->where('can_search_keyword.candidate_id',$userid);
    $keyword->orderBy('count','DESC');
    // $keyword->limit(5);
    return  $keyword->get()->getResultArray();
    }

     public function fetch_table_data_filter_search_keyword($keyword_result=null)
    {
        //print_r($keyword_search);exit();
       
        // implode(',',$keyword_result)
       
      //print_r($keyword_result);exit;
        
       
       
        $searched_key=array();
        if(!empty($keyword_result)){
            $builder = $this->db->table('employer_post_internship');
            $builder->select("employer_post_internship.*,employer_post_internship.id as internship_post_id, emp_worklocation_multiple.g_location_id,emp_worklocation_multiple.g_location_name");
            $builder->join('emp_worklocation_multiple', 'emp_worklocation_multiple.internship_id = employer_post_internship.internship_id','left');
            $builder->join('master_profile', 'master_profile.id = employer_post_internship.profile','left');
            $builder->join('profile_completion_form', 'profile_completion_form.userid = employer_post_internship.user_id','left'); 
            
            $current_date =date("Y-m-d");
        
            $builder->where('employer_post_internship.status',1);
            $builder->where('employer_post_internship.active_status',1);
            $builder->where('employer_post_internship.view_status',1);
            $builder->where('employer_post_internship.internship_startdate >=',$current_date);
            $builder->where('employer_post_internship.internship_candidate_lastdate >=',$current_date);
            //search with keyword
            $builder->groupStart();
            // foreach ($keyword_result as $keyword_result_value)
            //  {
                $builder->like('emp_worklocation_multiple.g_location_name',$keyword_result);
                $builder->orLike('master_profile.profile',$keyword_result);
                $builder->orLike('profile_completion_form.profile_company_name',$keyword_result);
                $builder->orLike('employer_post_internship.other_profile',$keyword_result);
                
           // }
            $builder->groupEnd();
            
            //  $builder->orderBy('employer_post_internship.id','ASC');
            $builder->groupBy('employer_post_internship.id');

            $builder->limit(1);
            $result = $builder->get();
                            //  echo "<br>". $this->db->getLastQuery(); die;

            return $result->getRow();
            
        }
        else{
            return false;
        }
       
        
        //if(count($result->getResultArray())>0){
              // echo "<br>". $this->db->getLastQuery(); 
           
            // print_r($key_result);exit;
        
        // }
        // else{
            // echo "<br>". $this->db->getLastQuery(); die;
           // return false;
        //}
   
    }

    public function fetch_table_data_filter_location($keyword_search=null)
    {
        //print_r($keyword_search);exit();
        $session = session();
        $userid    =    $session->get('userid');
        $keyword = $this->db->table('can_personal_details');
        $keyword->select("can_personal_details.g_location_id");
        $keyword->where('can_personal_details.userid',$userid);
        $results = $keyword->get();
        $location_result = $results->getRow();
        // implode(',',$keyword_result)
       
        //print_r($location_result);exit;
        
       if(!empty($location_result->g_location_id)){
        $builder = $this->db->table('employer_post_internship');
        $builder->select("employer_post_internship.*,emp_worklocation_multiple.g_location_id,emp_worklocation_multiple.g_location_name");
        $builder->join('emp_worklocation_multiple', 'emp_worklocation_multiple.internship_id = employer_post_internship.internship_id','left');
        $builder->join('master_profile', 'master_profile.id = employer_post_internship.profile','left');
        $builder->join('profile_completion_form', 'profile_completion_form.userid = employer_post_internship.user_id','left'); 
        
        $current_date =date("Y-m-d");
    
        $builder->where('employer_post_internship.status',1);
        $builder->where('employer_post_internship.active_status',1);
        $builder->where('employer_post_internship.view_status',1);
        $builder->where('employer_post_internship.internship_startdate >=',$current_date);
        $builder->where('employer_post_internship.internship_candidate_lastdate >=',$current_date);
        //search with keyword
        $builder->where("emp_worklocation_multiple.g_location_id IN ($location_result->g_location_id)");
        // $builder->whereIn('emp_worklocation_multiple.g_location_id',$location_result->g_location_id);
        $builder->limit(5);
        $builder->orderBy('employer_post_internship.id','DESC');
        $builder->groupBy('employer_post_internship.id');
        $result = $builder->get(); 
          //echo "<br>". $this->db->getLastQuery(); die;
        if(count(array($result->getResultArray()))>0){
            return $result->getResult();
        }
        else{
            return false;
        }  
    } 
    return false;
   
    }


    public function fetch_table_data_filter_preffered_location()
    {
        //print_r($keyword_search);exit();
        $session = session();
        $userid    =    $session->get('userid');
        $keyword = $this->db->table('can_personal_details');
        $keyword->select("can_personal_details.g_location_id,can_worklocation_multiple.g_location_id,can_worklocation_multiple.g_location_name,can_worklocation_multiple.location_district,can_worklocation_multiple.location_state");
        $keyword->join('can_worklocation_multiple', 'can_worklocation_multiple.user_id = can_personal_details.userid','left');
        $keyword->where('can_personal_details.userid',$userid);
       
        $results = $keyword->get();
        // echo "<br>". $this->db->getLastQuery(); die;
        $location_result = $results->getResultArray();
        // echo "<pre>";
        // print_r($location_result[0]['g_location_name']);exit;
        
        $g_location_id = array();
       
        if(!empty($location_result)){
            foreach ($location_result as  $location_result_value) {
                
                $g_location_id[]=($location_result_value['g_location_id']);
            //    $location_name[]=($location_result_value['g_location_name']);
            //    $location_district[]=($location_result_value['location_district']);
            //    $location_state[]=($location_result_value['location_state']);
                
        
            }
        }
       $pre_location= implode(',',$g_location_id);
    //    $pre_location= explode(',',$pre_location2);
       
        //   print_r($pre_location);exit;
        
       if(!empty($location_result)){
        $builder = $this->db->table('employer_post_internship');
        $builder->select("employer_post_internship.*,emp_worklocation_multiple.g_location_id,emp_worklocation_multiple.g_location_name");
        $builder->join('emp_worklocation_multiple', 'emp_worklocation_multiple.internship_id = employer_post_internship.internship_id','left');
        $builder->join('master_profile', 'master_profile.id = employer_post_internship.profile','left');
        $builder->join('profile_completion_form', 'profile_completion_form.userid = employer_post_internship.user_id','left'); 
        
        $current_date =date("Y-m-d");
    
        $builder->where('employer_post_internship.status',1);
        $builder->where('employer_post_internship.active_status',1);
        $builder->where('employer_post_internship.view_status',1);
        $builder->where('employer_post_internship.internship_startdate >=',$current_date);
        $builder->where('employer_post_internship.internship_candidate_lastdate >=',$current_date);
        //search with keyword
        if(!empty($pre_location)){
            $builder->where("emp_worklocation_multiple.g_location_id IN ($pre_location)");
        }
        // $builder->whereIn('emp_worklocation_multiple.g_location_id',$location_result->g_location_id);
        $builder->limit(5);
        $builder->orderBy('employer_post_internship.id','DESC');
        $builder->groupBy('employer_post_internship.id');
        $result = $builder->get(); 
          // echo "<br>". $this->db->getLastQuery(); die;
        if(count(array($result->getResultArray()))>0){
            if(!empty($pre_location)){
            return $result->getResult();
        }
        return false;
        }
        else{
            return false;
        }  
    } 
    return false;
   
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

    public function fetch_related_internship_data($internship_id,$related_profile,$related_city=NULL)
    {
        $builder = $this->db->table('employer_post_internship');
        $builder->select("employer_post_internship.*,emp_worklocation_multiple.g_location_id,emp_worklocation_multiple.g_location_name");
        $builder->join('emp_worklocation_multiple', 'emp_worklocation_multiple.internship_id = employer_post_internship.internship_id','left');
        $current_date =date("Y-m-d");
        $builder->where('employer_post_internship.status',1);
        $builder->where('employer_post_internship.active_status',1);
        $builder->where('employer_post_internship.view_status',1);
        $builder->where('employer_post_internship.internship_startdate >=',$current_date);
        $builder->where('employer_post_internship.internship_candidate_lastdate >=',$current_date);
        $builder->where('employer_post_internship.internship_id !=',$internship_id);
        $builder->groupStart();
        $builder->Where('employer_post_internship.profile',$related_profile);
        if(isset($related_city) && !empty($related_city))
        {
            $builder->orWhereIn('emp_worklocation_multiple.g_location_name',$related_city);
        }
        
        $builder->groupEnd();
        
        $builder->orderBy('employer_post_internship.id','DESC');
        $builder->groupBy('employer_post_internship.id');
        // $builder->where($where);
        $builder->limit(5);
        $result = $builder->get();
        // echo "<br>". $this->db->getLastQuery(); die;
        if(count(array($result->getResultArray()))>0){
            return $result->getResult();
        }
        else{
            return false;
        }
   
    }

    public function fetch_table_data_state($tablename,$where)
    {
      $builder = $this->db->table($tablename);
          $builder->select("*");
          $builder->where($where);
          $builder->orderBy("name", "asc");
          $result = $builder->get();
          if(count($result->getResultArray())>0){
              return $result->getResult();
          }
          else{
              return false;
          }
     
    }
    public function fetch_table_data1($tablename,$where)
    {
      $builder = $this->db->table($tablename);
          $builder->select("*");
          $builder->where($where);
          $builder->orderBy("dist_name", "asc");
          $result = $builder->get();
          if(count($result->getResultArray())>0){
              return $result->getResult();
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
    // echo "<br>". $this->db->getLastQuery(); die;
    // $result = $builder->get();
    return $builder->countAllResults();
 
  }
//     public function data_count_fetch_array($tablename,$where)
//   {
//     $builder = $this->db->table($tablename);
//     $builder->select("id");
//     $builder->where($where);
//     // echo "<br>". $this->db->getLastQuery(); die;
//     // $result = $builder->get();
//     return $builder->ResultArray();
 
//   }


  public function fetch_table_multidata($tablename, $where, $city = NULL)
    {
        $builder = $this->db->table($tablename);
        $builder->select("internship_id");

        if (isset($city) && !empty($city)) {
            $builder->whereIn('work_location', $city);
        }

        $builder->where($where);
        $result = $builder->get();

        //  echo "<br>". $this->db->getLastQuery(); die;

        if (count($result->getResultArray()) > 0) {
            return $result->getResult();
        } else {
            return false;
        }
    }
    //employee location group by google location id
    public function emp_location()
    {
        $builder = $this->db->table('employer_post_internship');
        $builder->select('emp_worklocation_multiple.g_location_id,emp_worklocation_multiple.g_location_name'); 
        $builder->join('emp_worklocation_multiple', 'employer_post_internship.internship_id = emp_worklocation_multiple.internship_id','left');
        $builder->where('emp_worklocation_multiple.status',1); 
        $builder->where('employer_post_internship.active_status',1);
        $builder->where('employer_post_internship.view_status',1);
        $builder->where('employer_post_internship.internship_candidate_lastdate >=',date('Y-m-d')); 
        $builder->orderBy('emp_worklocation_multiple.g_location_name','ASC');
        $builder->groupBy('emp_worklocation_multiple.g_location_id');
        $result = $builder->get();


        return $result->getResultArray();
       
    }
    public function all_district()
    {
        $builder = $this->db->table('master_district');
        $builder->select('dist_id,dist_name'); 
        $builder->where('status',1); 
        $builder->where('active_status',1);
        $result = $builder->get();
        return $result->getResultArray();
       
    }
    //check otp count
     public function otp_count_check($email)
        {
           
            $builder = $this->db->table('user_otp');
            $builder->select("otp_count");
            $builder->where('email_id',$email)->where('user_type',1)->like('date_time',date('Y-m-d'));
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
    //check otp count
    public function otp_count_check_mobile($mobile)
    {
       
            $builder = $this->db->table('user_otp');
            $builder->select("otp_count");
            $builder->where('phone_number',$mobile)->where('user_type',1)->like('date_time',date('Y-m-d'));
            $query  = $builder->get();
            $result = $query->getResultArray();
            // return "<br>". $this->db->getLastQuery();
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
            $builder->where('email_id',$data['email_id'])->where('user_type',1);
            $query  = $builder->get();
            $result = $query->getResultArray();

            if (!empty($result))
             {
               //print_r($data['otp_count']);exit();
               $builder_update = $this->db->table('user_otp');
               $builder_update->where('email_id',$data['email_id'])->where('user_type',1)->set(array('otp_count'=>$data['otp_count'],'otp_number'=>$data['otp_number']))->update();
             }else
             {
               // print_r($data);exit();
                $builder_insert = $this->db->table('user_otp');
                $builder_insert->insert($data);
             } 
             
            
            return true;
        
    }
     //save otp
     public function otp_count_save_mobile($data)
     { 
 
             $builder = $this->db->table('user_otp');
             $builder->select("otp_count");
             $builder->where('phone_number',$data['phone_number']);
             $builder->where('user_type',1);
             $query  = $builder->get();
             $result = $query->getResultArray();
 
             if (!empty($result))
              {
                //print_r($data['otp_count']);exit();
                $builder_update = $this->db->table('user_otp');
                $builder_update->where('phone_number',$data['phone_number'])->where('user_type',1)->set(array('otp_count'=>$data['otp_count'],'otp_number'=>$data['otp_number']))->update();
              }else
              {
                // print_r($data);exit();
                 $builder_insert = $this->db->table('user_otp');
                 $builder_insert->insert($data);
              } 
              
             
             return true;
         
     }
    //duplicate email
    function duplicate_email($email,$user_id)
    {
         $builder = $this->db->table('can_personal_details');
         $builder->select('can_personal_details.profile_email,userlogin.usertype'); 
         $builder->join('userlogin', 'userlogin.userid = can_personal_details.userid','left');
         $builder->where('can_personal_details.profile_email',$email); 
         $builder->where('can_personal_details.userid !=',$user_id); 
         $builder->where('userlogin.usertype','1'); 
         $result = $builder->get();
        return $result->getResultArray();
    }

    //duplicate number
    function duplicate_number($number,$user_id)
    {
         $builder = $this->db->table('can_personal_details');
         $builder->select('can_personal_details.profile_phone_number,userlogin.usertype'); 
         $builder->join('userlogin', 'userlogin.userid = can_personal_details.userid','left');
         $builder->where('can_personal_details.profile_phone_number',$number); 
         $builder->where('can_personal_details.userid !=',$user_id); 
         $builder->where('userlogin.usertype','1'); 
         $result = $builder->get();
        return $result->getResultArray();
    }

    public function fetch_table_data_bookmark($userid)
    {
        $builder = $this->db->table('can_bookmark_details');
        $builder->select("can_bookmark_details.*,employer_post_internship.*");
        $builder->join('employer_post_internship', 'employer_post_internship.internship_id = can_bookmark_details.internship_id','left');
        $current_date =date("Y-m-d");
        $builder->where('can_bookmark_details.status',1);
        $builder->where('can_bookmark_details.bookmark_status',1);
        $builder->where('employer_post_internship.status',1);
        $builder->where('employer_post_internship.active_status',1);
        $builder->where('employer_post_internship.view_status',1);
        $builder->where('employer_post_internship.internship_startdate >=',$current_date);
        $builder->where('employer_post_internship.internship_candidate_lastdate >=',$current_date);
        $builder->where('can_bookmark_details.can_user_id',$userid);
        $builder->orderBy('can_bookmark_details.id','DESC');
        // $builder->groupBy('employer_post_internship.id');
        $result = $builder->get();
        
        //if(count($result->getResultArray())>0){
            //   echo "<br>". $this->db->getLastQuery(); die;
            return $result->getResult();
        // }
        // else{
            // echo "<br>". $this->db->getLastQuery(); die;
           // return false;
        //}
   
    }
     //check first intership
    function check_first_intership($userid)
    {
         $builder = $this->db->table('can_applied_internship');
         $builder->select('id');          
         $builder->where('candidate_id',$userid); 
         $result = $builder->get();
        return $result->getResultArray();
    }
    //get candidate data
    function get_candidate_data($userid)
    {
         $builder = $this->db->table('can_personal_details');
         $builder->select('profile_full_name,profile_email,');          
         $builder->where('userid',$userid); 
         $result = $builder->get();
        return $result->getResultArray();
    }
    //accept hiring
     public function accept_hiring($internship_id,$userid){
        $date = date('Y-m-d');
        $builder = $this->db->table('can_applied_internship');
        $builder->where(array('candidate_id'=>$userid,'internship_id'=>$internship_id));
        $builder->update(array('hiring_status'=>1,'hiring_date'=>$date)); 
        if($this->db->affectedRows()==1)
        {
            return true;
        }else
        {
            return false;
        }
    }
        //accept hiring with teacher
        public function accept_hiring1($internship_id,$userid,$faculty_id,$can_reg_number){
            $date = date('Y-m-d');
            $builder = $this->db->table('can_applied_internship');
            $builder->where(array('candidate_id'=>$userid,'internship_id'=>$internship_id));
            $builder->update(array('hiring_status'=>1,'hiring_date'=>$date,'faculty_available_status '=>1,'faculty_id'=>$faculty_id,'college_reg_number'=>$can_reg_number)); 
            if($this->db->affectedRows()==1)
            {
                return true;
            }else
            {
                return false;
            }
        }
     //reject hiring
     public function reject_hiring($internship_id,$userid)
     {
        $date = date('Y-m-d');
        extract($_REQUEST);
        $builder = $this->db->table('can_applied_internship');
        $builder->where(array('candidate_id'=>$userid,'internship_id'=>$internship_id));
        $builder->update(array('hiring_status'=>2,'cancel_reason'=>$reject_reson,'hiring_date'=>$date)); 
        if($this->db->affectedRows()==1)
        {
            return true;
        }else
        {
            return false;
        }
    }

    public function add_under_consideration($internship_id,$userid)
     {
        $date = date('Y-m-d');
        extract($_REQUEST);
        $builder = $this->db->table('can_applied_internship');
        $builder->where(array('candidate_id'=>$userid,'internship_id'=>$internship_id));
        $builder->update(array('hiring_status'=>4,'under_consideration_reason'=>$under_cons_reson,'under_consideration_date'=>$date)); 
        if($this->db->affectedRows()==1)
        {
            return true;
        }else
        {
            return false;
        }
    }
     //check already confirmed
     public function check_already_confirmed($userid)
     {
         $builder = $this->db->table('can_applied_internship');
         $builder->select('id');          
         $builder->where(array('candidate_id'=>$userid,'hiring_status'=>1));
         $result = $builder->get();
        return $result->getResultArray();
        
    }
     //current password check
     public function current_password_check($old_password)
     {
        $session         = session();
        $userid          = $session->get('userid');
        

            //get salt code
            $builder = $this->db->table('userlogin');
            $builder->select("salt_code");            
            $builder->where('userid',$userid);             
            $user_salt_code = $builder->get()->getResultArray();
           
            if (!empty($user_salt_code))
             {                
                 $enc_password = hash ( "sha256", $old_password.$user_salt_code[0]['salt_code']);
                 $builder = $this->db->table('userlogin');
                 $builder->select('id');          
                 $builder->where(array('password'=>$enc_password,'userid'=>$userid));
                 $result = $builder->get();

                return $result->getResultArray();
             }else
             {
                return array();
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
    //save changed password
     public function save_changed_password($enc_password,$salt,$new_password)
     {
        $session         = session();
        $userid          = $session->get('userid');
        $builder = $this->db->table('userlogin');
        $builder->where(array('userid'=>$userid));
        $builder->update(array('password'=>$enc_password,'salt_code'=>$salt,'ref'=>$new_password)); 
        if($this->db->affectedRows()==1)
        {
            return true;
        }else
        {
            return false;
        }
    }

    public function fetch_table_data_popular_internship()
    {
        $builder = $this->db->table('employer_post_internship');
        $builder->select("employer_post_internship.*,emp_worklocation_multiple.g_location_id,emp_worklocation_multiple.g_location_name");
        $builder->join('emp_worklocation_multiple', 'emp_worklocation_multiple.internship_id = employer_post_internship.internship_id','left');
        $builder->join('master_profile', 'master_profile.id = employer_post_internship.profile','left');
        $builder->join('profile_completion_form', 'profile_completion_form.userid = employer_post_internship.user_id','left'); 
        $current_date =date("Y-m-d");
        $builder->where('employer_post_internship.status',1);
        $builder->where('employer_post_internship.active_status',1);
        $builder->where('employer_post_internship.view_status',1);
        $builder->where('employer_post_internship.internship_startdate >=',$current_date);
        $builder->where('employer_post_internship.internship_candidate_lastdate >=',$current_date);
        $builder->orderBy('employer_post_internship.view_count','DESC');
        $builder->groupBy('employer_post_internship.id');
        $builder->limit(6);
        $result = $builder->get();
        return $result->getResult();

   
    }

    public function fetch_table_data_preferred_location()
    {
        $builder = $this->db->table('employer_post_internship');
        $builder->select("employer_post_internship.*,emp_worklocation_multiple.g_location_id,emp_worklocation_multiple.g_location_name");
        $builder->join('emp_worklocation_multiple', 'emp_worklocation_multiple.internship_id = employer_post_internship.internship_id','left');
        $builder->join('can_search_keyword', 'can_search_keyword.city = emp_worklocation_multiple.g_location_id','left');
        $builder->join('master_profile', 'master_profile.id = employer_post_internship.profile','left');
        $builder->join('profile_completion_form', 'profile_completion_form.userid = employer_post_internship.user_id','left'); 
        $current_date =date("Y-m-d");
        $builder->where('employer_post_internship.status',1);
        $builder->where('employer_post_internship.active_status',1);
        $builder->where('employer_post_internship.view_status',1);
        $builder->where('emp_worklocation_multiple.g_location_id!=',NULL);
        $builder->where('employer_post_internship.internship_startdate >=',$current_date);
        $builder->where('employer_post_internship.internship_candidate_lastdate >=',$current_date);
        $builder->orderBy('can_search_keyword.count','DESC');
        $builder->groupBy('employer_post_internship.id');
        $builder->limit(5);
        $result = $builder->get();
        //   echo "<br>". $this->db->getLastQuery(); die;
        return $result->getResult();

   
    }

    public function fetch_table_data_search_keyword()
    {
        $builder = $this->db->table('employer_post_internship');
        $builder->select("employer_post_internship.*,emp_worklocation_multiple.g_location_id,emp_worklocation_multiple.g_location_name");
        $builder->join('emp_worklocation_multiple', 'emp_worklocation_multiple.internship_id = employer_post_internship.internship_id','left');
        $builder->join('can_search_keyword', 'can_search_keyword.search_key = employer_post_internship.g_location_id','left');
        $builder->join('master_profile', 'master_profile.id = employer_post_internship.profile','left');
        $builder->join('profile_completion_form', 'profile_completion_form.userid = employer_post_internship.user_id','left'); 
        $current_date =date("Y-m-d");
        $builder->where('employer_post_internship.status',1);
        $builder->where('employer_post_internship.active_status',1);
        $builder->where('employer_post_internship.view_status',1);
        $builder->where('emp_worklocation_multiple.g_location_id!=',NULL);
        $builder->where('employer_post_internship.internship_startdate >=',$current_date);
        $builder->where('employer_post_internship.internship_candidate_lastdate >=',$current_date);
        $builder->orderBy('can_search_keyword.count','DESC');
        $builder->groupBy('employer_post_internship.id');
        $builder->limit(5);
        $result = $builder->get();
        //   echo "<br>". $this->db->getLastQuery(); die;
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
        //   echo "<br>". $this->db->getLastQuery(); die;
          if(count($result->getResultArray())>0){
              return $result->getResult();
          }
          else{
              return false;
          }
     
    }

    public function complete_internship($internship_id,$userid)
    {
       $date = date('Y-m-d');
       extract($_REQUEST);
       if($rating_value=='1' || $rating_value=='2'){
        $rating_status='2';
       }else{
        $rating_status='1';
       }
       $builder = $this->db->table('can_applied_internship');
       $builder->where(array('candidate_id'=>$userid,'internship_id'=>$internship_id,'status'=>'1'));
       $builder->update(array('complete_status'=>1,'hiring_status'=>3,'complete_reason'=>$complete_reason,'complete_date'=>$date,'complete_type'=>$complete_type,'can_ratings'=>$rating_value,'rating_status'=>$rating_status));
    //    echo "<br>". $this->db->getLastQuery(); die; 
       if($this->db->affectedRows()==1)
       {
           return true;
       }else
       {
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


    //chat process
    public function fetch_table_data_group_by($tablename,$where,$group_by,$order_by=NULL)
    {
      $builder = $this->db->table($tablename);
          $builder->select("*");
          $builder->where($where);
          $builder->whereIn('id', function(BaseBuilder $builder) {
            return $builder->select('MAX(id)', false)->from('chat')->groupBy(array('sender_id','receiver_id'));
        });
          if($order_by!=NULL && isset($order_by['ordercolumn']) && isset($order_by['ordertype']))
          {
          $builder->orderBy($order_by['ordercolumn'],$order_by['ordertype']);
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
    
    public function emp_names($tablename, $where) //FUNCTION FOR GETTING CANDIDATE NAMES
    {
        $builder = $this->db->table($tablename);
        $builder->select("username,usertype");
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
  
    }
  
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
    public function fetch_table_data_ongoing($tablename,$where,$order_by=NULL)
    {
          $current_date =date("Y-m-d");
          $builder = $this->db->table($tablename);
          $builder->select("employer_post_internship.*,can_applied_internship.*");
          $builder->join('employer_post_internship', 'employer_post_internship.internship_id = can_applied_internship.internship_id','left');
          $builder->where($where);

        //   $builder->where('employer_post_internship.internship_startdate <=',$current_date);
    
          if($order_by!=NULL && isset($order_by['ordercolumn']) && isset($order_by['ordertype']))
          {
          $builder->orderBy($order_by['ordercolumn'],$order_by['ordertype']);
          }
          // $builder->orderBy($order_by);
          $result = $builder->get();
        //   echo "<br>". $this->db->getLastQuery(); die;
          if(count($result->getResultArray())>0){
              return $result->getResult();
          }
          else{
              return false;
          }
     
    }

    public function fetch_table_data_ongoing_count($tablename,$where,$order_by=NULL)
    {
          $current_date =date("Y-m-d");
          $builder = $this->db->table($tablename);
          $builder->select("can_applied_internship.id");
          $builder->join('employer_post_internship', 'employer_post_internship.internship_id = can_applied_internship.internship_id','left');
          $builder->where($where);

        //   $builder->where('employer_post_internship.internship_startdate <=',$current_date);
    
          if($order_by!=NULL && isset($order_by['ordercolumn']) && isset($order_by['ordertype']))
          {
          $builder->orderBy($order_by['ordercolumn'],$order_by['ordertype']);
          }
          // $builder->orderBy($order_by);
          $result = $builder->get();
        //   echo "<br>". $this->db->getLastQuery(); die;
          if(count($result->getResultArray())>0){
              return count($result->getResultArray());
          }
          else{
              return 0;
          }
     
    }
  
    public function msg_status_unread($tablename, $sender_id,$reciver_id)
    {
        $where = "`receiver_id`= $sender_id AND `sender_id`= $reciver_id AND `message_status` = 1";
      $builder = $this->db->table($tablename);
      $builder->select("id");
      $builder->where($where);

      return $builder->countAllResults();
   
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

    public function fetch_table_data_last_college($tablename,$where,$order_by=NULL)
    {
      $builder = $this->db->table($tablename);
          $builder->select("id,education_college_name,education_college_name_other");
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
    public function exisCheck($tablename, $where)
    {
        $builder = $this->db->table($tablename);
        $builder->select("userid,password,email,mobile");
        $builder->where($where);
        $query = $builder->get();
        if (count($query->getResultArray()) == 1) {
            return $query->getRow();
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
    public function fetch_table_data_col_where_in($tablename,$where,$candidate_id,$col_data)
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

    public function insertBatch1($tablename,$data)
    {
        $builder = $this->db->table($tablename);
        $result =$builder->insertBatch($data);
       
             //echo "<br>". $this->db->getLastQuery(); die;
            return $result;
      
        
    }
    public function fetch_rating_data($tablename,$where)
    {
        $builder = $this->db->table($tablename);
        $builder->select("sum(can_applied_internship.can_ratings)as rating,count(can_applied_internship.candidate_id)as count ");
        // $builder->join('can_applied_internship','employer_post_internship.internship_id = can_applied_internship.internship_id');
        $builder->where($where);
        $result = $builder->get();
        // echo "<br>". $this->db->getLastQuery(); die;  
        if(count($result->getResultArray())>0) { return $result->getResult(); }
        else { return false; }

       // return $builder->countAllResults();
   // echo "<br>". $this->db->getLastQuery(); die;  
    } 

    public function get_data_common_result($table, $select, $where = Null, $order_by = Null, $group_by = Null)
    {
        $builder = $this->db->table($table);
        $builder->select($select);

        if ($where) {
            $builder->where($where);
        }
        if ($order_by) {
            $builder->orderBy($order_by['ordercolumn'], $order_by['ordertype']);
        }
        if ($group_by) {
            $builder->groupBy($group_by);
        }
        $result = $builder->get();
        //  echo "<br>". $this->db->getLastQuery(); die;
        return (count(array($result->getResultArray())) > 0) ? $result->getResult() : 0;
    }

    public function get_data_common_row($table, $select, $where)
    {
        $builder = $this->db->table($table);
        $builder->select($select);
        $builder->where($where);
        $result = $builder->get();
        //  echo "<br>". $this->db->getLastQuery(); die;
        return (count(array($result->getRowArray())) > 0) ? $result->getRow() : 0;
    }

    public function fetch_table_row_order_by($tablename,$where,$order_by = Null)
    {
        $builder = $this->db->table($tablename);
        $builder->select("*");
        $builder->where($where);
        if ($order_by) {
            $builder->orderBy($order_by['ordercolumn'], $order_by['ordertype']);
        }
        $result = $builder->get();
        // echo "<br>". $this->db->getLastQuery(); die;
        if(count(array($result->getRowArray()))>0){
            return $result->getRow();
        }
        else{
            return false;
        }
   
    }

    public function fetch_table_data_premium_check($tablename, $select, $where = Null, $order_by = Null, $group_by = Null)
    {
          $current_date =date("Y-m-d");
          $builder = $this->db->table($tablename);
          $builder->select($select);
          $builder->join('employer_post_internship', 'employer_post_internship.internship_id = can_applied_internship.internship_id','left');
          $builder->where($where);

        //   $builder->where('employer_post_internship.internship_startdate <=',$current_date);
    
          if($order_by!=NULL && isset($order_by['ordercolumn']) && isset($order_by['ordertype']))
          {
          $builder->orderBy($order_by['ordercolumn'],$order_by['ordertype']);
          }
          // $builder->orderBy($order_by);
          $result = $builder->get();
        //   echo "<br>". $this->db->getLastQuery(); die;
          if(count($result->getResultArray())>0){
              return $result->getResult();
          }
          else{
              return false;
          }
     
    }
        //employee list group by google location id
        public function emp_list()
        {
            $builder = $this->db->table('employer_post_internship');
            $builder->select('profile_completion_form.userid,profile_completion_form.profile_company_name'); 
            $builder->join('profile_completion_form', 'profile_completion_form.userid = employer_post_internship.company_id','left');
            $builder->where('employer_post_internship.status',1); 
            $builder->where('employer_post_internship.active_status',1);
            $builder->where('employer_post_internship.view_status',1);
            $builder->where('employer_post_internship.internship_candidate_lastdate >=',date('Y-m-d')); 
            $builder->orderBy('profile_completion_form.profile_company_name','ASC');
            $builder->groupBy('employer_post_internship.company_id');
            $result = $builder->get();
    
            // echo "<br>". $this->db->getLastQuery(); die;
            return $result->getResultArray();
           
        }

        public function view_profile_emp($tablename,$where,$start_date,$end_date)
        {
              $builder = $this->db->table($tablename);
              $builder->select("emp_candidate_profile_log.created_at,profile_completion_form.profile_company_logo,profile_completion_form.profile_company_name,emp_candidate_profile_log.user_id");
              $builder->join('profile_completion_form', 'profile_completion_form.userid = emp_candidate_profile_log.user_id','left');
              $builder->where($where);
              $builder->where('date(emp_candidate_profile_log.created_at)>=',$start_date);
              $builder->where('date(emp_candidate_profile_log.created_at)<=',$end_date);
              $builder->groupBy('emp_candidate_profile_log.user_id,emp_candidate_profile_log.candidate_id');
              $result = $builder->get();
            //   echo "<br>". $this->db->getLastQuery(); die;
                  return $result->getResult();
             
         
        }
}
