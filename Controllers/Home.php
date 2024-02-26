<?php

namespace App\Controllers;
use App\Models\Candidate_model;
$this->Candidate_model = new Candidate_model();
use App\Models\Common_model;
class Home extends BaseController
{
    protected $session;
     function __construct()
      {

        $this->session = \Config\Services::session();
        $this->session->start();
        date_default_timezone_set('Asia/Kolkata');
        
        $current  = '';
        
          if (isset($_SERVER['REQUEST_URI']))
          {
            $current = $_SERVER['REQUEST_URI'];             
          }

         // echo $current;exit();
          //print_r($previous);
        if ( strpos($current, 'web-search-internship') == false) 
        {
            $session = session();
             $ses_data = [
                'candidate_footer_category',
                'candidate_footer_city',
                'candidate_filter_category',
                'candidate_filter_city',
                'candidate_filter_emp',
                'candidate_filter_start_date',
                'candidate_filter_internship_duration_one',
                'candidate_filter_internship_duration_two',
                'candidate_filter_parttime',
                // 'candidate_filter_fulltime',
                'candidate_filter_work_from_home',
                'candidate_filter_job_offer',
                'candidate_filter_stipend',
                'searched_keyword',
                'search_internship_showing_limit',
            ];
            $session->remove($ses_data);
        }
    }
    public function index()
    {
        
        $Common_model = new Common_model();
        $where1 = array();
        $data['total_candidate'] = $Common_model->data_count_fetch('can_personal_details', $where1);
        $where2 = array();
        $data['employers'] = $Common_model->data_count_fetch('profile_completion_form', $where2);
        $where3 = array();
        $data['posted_internship'] = $Common_model->data_count_fetch('employer_post_internship', $where3);
        $posted_internship = $Common_model->data_count_fetch('employer_post_internship', $where3);
        $where4 = array('active_status' =>'1','internship_candidate_lastdate >=' => date('Y-m-d'));
        $data['posted_internship_open'] =$Common_model->data_count_fetch('employer_post_internship', $where4);
        $posted_internship_open = $Common_model->data_count_fetch('employer_post_internship', $where4);
        // $where5 = array('active_status' =>'0');
       
        $posted_internship_closed=$posted_internship-$posted_internship_open;
        $data['posted_internship_closed'] = $posted_internship_closed;
        $session = session();

        /**Load Candidate model */
        $Candidate_model = new Candidate_model();

        /**Get User id Session */
        $userid          = $session->get('userid');
        $data['data'] = '';
        $where_status1 = array('status' => '1', 'userid' => $userid);
        $data['profile_personal'] = $Candidate_model->fetch_table_row('can_personal_details', $where_status1);
        return view('home',$data);
    }
    public function privacy_policy()
    {
        return view('privacy_policy');
    }
  //view internship
    public function view_internship()
    {
    	helper(['form']);
        $session = session();
        $Candidate_model = new Candidate_model();
        $userid    =    $session->get('userid');
        $search_internship_showing_limit = $session->get('search_internship_showing_limit');
        // $current_date =date("Y-m-d");
        // $where1 = array('status'=> '1','internship_startdate >'=> $current_date);
        $where = array('status'=> '1');
        $order_by = array('ordercolumn' => 'city', 'ordertype' => 'asc');
        $order_by_profile = array('ordercolumn' => 'profile', 'ordertype' => 'asc');
        $data['category_list'] = $Candidate_model->fetch_table_data_for_all('master_profile',$where,$order_by_profile);
        // $data['city_list'] = $Candidate_model->fetch_table_data_for_all('master_city',$where,$order_by);
        $data['city_list'] = $Candidate_model->emp_location();
        $data['city_list_all'] = $Candidate_model->all_district();

        $data['emp_list'] = $Candidate_model->emp_list();
        //print_r($data['city_list']);exit();
        $filter_category = $session->get('candidate_filter_category');
        $filter_city = $session->get('candidate_filter_city');
        $filter_emp = $session->get('candidate_filter_emp');
        $filter_start_date = $session->get('candidate_filter_start_date');
        $filter_internship_duration_one = $session->get('candidate_filter_internship_duration_one');
        $filter_internship_duration_two = $session->get('candidate_filter_internship_duration_two');
        $filter_parttime = $session->get('candidate_filter_parttime');
        // $filter_fulltime = $session->get('candidate_filter_fulltime');
        $filter_work_from_home = $session->get('candidate_filter_work_from_home');
        $filter_job_offer = $session->get('candidate_filter_job_offer');
        $filter_stipend = $session->get('candidate_filter_stipend');
        //search with keyword
        $keyword_search = $session->get('searched_keyword');
        if (isset($keyword_search)) 
        {
           $keyword_search=$keyword_search;
        }else
        {
            $keyword_search='';
        }
        //$data_internship1 = $Candidate_model->fetch_table_data_filter($where, $filter_category, $filter_city, $filter_emp, $filter_start_date, $filter_internship_duration_one, $filter_parttime, $filter_work_from_home, $filter_job_offer,$filter_stipend);

        $data_internship = $Candidate_model->fetch_table_data_filter_closed($where, $filter_category, $filter_city, $filter_emp, $filter_start_date, $filter_internship_duration_one, $filter_parttime, $filter_work_from_home, $filter_job_offer,$filter_stipend);

        //$data_internship = array_merge($data_internship1,$data_internship2);
        
        if (!empty($data_internship)) {
            $total   = count($data_internship);
        } else {
            $total   = 0;
        }
        $pager = service('pager');
        $page = (int) $this->request->getGet('page'); // 
        // $limit = config('Pager')->perPage_can; // see Config/Pager.php
        if(isset($search_internship_showing_limit)){
            $limit = $search_internship_showing_limit;
        }else{
            $limit = config('Pager')->perPage_can; // see Config/Pager.php
        }

        if (!isset($page) || $page === 0 || $page === 1) {
            $page = 1;
            $start_id = 0;
        } else {
            $start_id = ($page - 1) * $limit;
            $page = $page;
        }

        $data['page_start_id']=$start_id;
        $data['page_default_limit']=$limit;
        //$data['internship_list_count'] = $Candidate_model->fetch_table_data_filter($where);
        $data['internship_list_count'] = $Candidate_model->fetch_table_data_filter_closed($where, $filter_category, $filter_city, $filter_emp, $filter_start_date, $filter_internship_duration_one, $filter_parttime, $filter_work_from_home, $filter_job_offer,$filter_stipend,null,null, $keyword_search);
        if (!empty($data_internship)) {
            $total   = count($data_internship);
        } else {
            $total   = 0;
        }
       
       
       // $data['internship_list_open'] = $Candidate_model->fetch_table_data_filter( $where, $filter_category, $filter_city, $filter_emp, $filter_start_date, $filter_internship_duration_one, $filter_parttime, $filter_work_from_home, $filter_job_offer,$filter_stipend, $limit, $start_id,$keyword_search);

       
        $data['internship_list'] = $Candidate_model->fetch_table_data_filter_closed( $where, $filter_category, $filter_city, $filter_emp, $filter_start_date, $filter_internship_duration_one, $filter_parttime, $filter_work_from_home, $filter_job_offer,$filter_stipend, $limit, $start_id,$keyword_search);

        $pager_links = $pager->makeLinks($page, $limit, $total, 'custom_pagination');
        $data['pager_links'] = $pager_links;

        $where_can = array('status' => '1','userid' => $userid);
        $data['education_details'] = $Candidate_model->fetch_table_data('can_education_details', $where_can);
        $data['profile_personal'] = $Candidate_model->fetch_table_row('can_personal_details', $where_can);
        $data['address_details'] = $Candidate_model->fetch_table_row('can_address_details', $where_can);
        return view('Candidate/can_intern_list',$data);
    }

     public function internship_filters()
	{
          $session = session();
        $category = $this->request->getVar('category');
        $footer_type = $this->request->getVar('footer_type');
        
        $city = $this->request->getVar('city');
        $company = $this->request->getVar('company');
        $start_date = $this->request->getVar('start_date');
        $internship_duration_one = $this->request->getVar('internship_duration_one');
        $internship_duration_two = $this->request->getVar('internship_duration_two');
        $parttime = $this->request->getVar('parttime');
        // $fulltime = $this->request->getVar('fulltime');
        $work_from_home = $this->request->getVar('work_from_home');
        $job_offer = $this->request->getVar('job_offer');
        $stipend = $this->request->getVar('stipend');

        if (!empty($footer_type)) {
            //$category_arr = explode(',', $category);
            if($footer_type==1)
            {
                $_SESSION['candidate_footer_city']=$city;
            }
            elseif($footer_type==2)
            {
                $_SESSION['candidate_footer_category']=$category;
            }
            //$session->set('candidate_filter_category', $category);
        } 

        if (!empty($category)) {
            //$category_arr = explode(',', $category);
            $_SESSION['candidate_filter_category']=$category;
            //$session->set('candidate_filter_category', $category);
        } else {
            $_SESSION['candidate_filter_category']='';
            //$session->set('candidate_filter_category', '');
        }
        if (!empty($city)) {
           // $city_arr = explode(',', $city);
            //$session->set('candidate_filter_city', $city);
            $_SESSION['candidate_filter_city']=$city;
        } else {
            //$session->set('candidate_filter_city', '');
            $_SESSION['candidate_filter_city']='';
        }
        if (!empty($company)) {
            // $city_arr = explode(',', $city);
             //$session->set('candidate_filter_city', $city);
             $_SESSION['candidate_filter_emp']=$company;
         } else {
             //$session->set('candidate_filter_city', '');
             $_SESSION['candidate_filter_emp']='';
         }
        if (!empty($start_date)) {
            $_SESSION['candidate_filter_start_date']=$start_date;
           // $session->set('candidate_filter_start_date', $start_date);
        }
        if (!empty($internship_duration_one)) {
            $_SESSION['candidate_filter_internship_duration_one']=$internship_duration_one;
            //$session->set('candidate_filter_internship_duration_one', $internship_duration_one);
        }
        if (!empty($internship_duration_two)) {
             $_SESSION['candidate_filter_internship_duration_two']=$internship_duration_two;
            //$session->set('candidate_filter_internship_duration_two', $internship_duration_two);
        }
        if (!empty($parttime)) {
             $_SESSION['candidate_filter_parttime']=$parttime;
            //$session->set('candidate_filter_parttime', $parttime);
        }
        // if(!empty($fulltime)){
        //     $session->set('candidate_filter_fulltime' , $fulltime);
        // }
        if (!empty($work_from_home)) {
             $_SESSION['candidate_filter_work_from_home']=$work_from_home;
            //$session->set('candidate_filter_work_from_home', $work_from_home);
        }
        if (!empty($job_offer)) {
             $_SESSION['candidate_filter_job_offer']=$job_offer;
          //  $session->set('candidate_filter_job_offer', $job_offer);
        }
        if (!empty($stipend)) {
             $_SESSION['candidate_filter_stipend']=$stipend;
           // $session->set('candidate_filter_stipend', $stipend);
        }
         echo json_encode(csrf_hash());
	}

	 public function unset_internship_filters()
	{
        $session = session();

        $ses_data = [
            'candidate_footer_category',
            'candidate_footer_city',
            'candidate_filter_category',
            'candidate_filter_city',
            'candidate_filter_emp',
            'candidate_filter_start_date',
            'candidate_filter_internship_duration_one',
            'candidate_filter_internship_duration_two',
            'candidate_filter_parttime',
            // 'candidate_filter_fulltime',
            'candidate_filter_work_from_home',
            'candidate_filter_job_offer',
            'candidate_filter_stipend',
        ];
       
        $session->remove($ses_data);
        return redirect()->to('/web-search-internship');
	}

	 public function details_view_internship($internship_id)
    {
        helper(['form']);
        $session = session();
        $Candidate_model = new Candidate_model();
        $userid    =    $session->get('userid');
        $where = array('status' => '1', 'internship_id' => $internship_id);
        $data['internship_details'] = $Candidate_model->fetch_table_row('employer_post_internship', $where);
        $internship_details = $data['internship_details'];
        if(isset($internship_details)){
        // echo $internship_details->user_id;exit();
        $where_emp = array('status' => '1', 'userid' => $internship_details->company_id);
        $data['emp_profile_details'] = $Candidate_model->fetch_table_row('profile_completion_form', $where_emp);

        $where_city = array('status' => '1', 'user_id' => $internship_details->company_id, 'internship_id' => $internship_details->internship_id);
        $data['int_city'] = $Candidate_model->fetch_table_data('emp_worklocation_multiple', $where_city);

        $where_skill = array('status' => '1', 'user_id' => $internship_details->company_id, 'internship_id' => $internship_details->internship_id);
        $data['int_skill'] = $Candidate_model->fetch_table_data('emp_selected_skills_multiple', $where_skill);
        $where7 = array('status' => '1', 'internship_id' => $internship_id);
        $data['perks'] = $Candidate_model->fetch_table_data('emp_selected_perks_multiple', $where7);
        $where_edu = array('status' => '1', 'user_id' => $internship_details->company_id, 'internship_id' => $internship_details->internship_id);
        $data['int_edu'] = $Candidate_model->fetch_table_data('emp_selected_education_multiple', $where_edu);
        $where_spe = array('status' => '1', 'user_id' => $internship_details->company_id, 'internship_id' => $internship_details->internship_id);
            $data['int_spe'] = $Candidate_model->fetch_table_data('emp_selected_specialization_multiple', $where_spe);
        $current_date = date("Y-m-d");
        // $where_related = array('status' => '1', 'active_status' => '1', 'profile' => $internship_details->profile, 'internship_id !=' => $internship_details->internship_id, 'internship_startdate >' => $current_date);

        $city_arr = array();
        $int_city=$data['int_city'];
        if(!empty($int_city)){
        foreach ($int_city as $city) {
            $city_arr[] = $city->g_location_name;
        }
        }
        // print_r($city_arr);exit();
        // $where_city_int = array('status' => '1', 'internship_id !=' => $internship_details->internship_id);
        // $city_val = $Candidate_model->fetch_table_multidata('emp_worklocation_multiple', $where_city_int, $city_arr);

        // // echo "<pre>";print_r($city_val); echo "</pre>";exit();

        // $city_int = array();
        // foreach ($city_val as $city_v) {
        //     $city_int[] = $city_v->internship_id;
        // }
        $data['int_related_details'] = $Candidate_model->fetch_related_internship_data($internship_details->internship_id,$internship_details->profile, $city_arr);
        $where_can = array('status' => '1', 'userid' => $userid);
        $data['education_details'] = $Candidate_model->fetch_table_data('can_education_details', $where_can);
        $data['profile_personal'] = $Candidate_model->fetch_table_row('can_personal_details', $where_can);
        $data['address_details'] = $Candidate_model->fetch_table_row('can_address_details', $where_can);
        $where_count = array('status' => '1', 'internship_id' => $internship_details->internship_id);
        $data['applicant_count'] = $Candidate_model->data_count_fetch('can_applied_internship', $where_count);
        $where_rat = array('can_applied_internship.internship_id' => $internship_id,'can_applied_internship.rating_status' =>'1');
        $rating_data = $Candidate_model->fetch_rating_data('can_applied_internship', $where_rat);
        if(!empty($rating_data[0]->count)){
            $data['rating']=round($rating_data[0]->rating/$rating_data[0]->count);
        }else{
            $data['rating']='0';
        }
        return view('Candidate/can_intern_single',$data);
        }else{
        return view('Common/404');
        }
    }
    public function login_web($internship_id)
    {
    	 helper(['form']);
        $session = session(); 
        $session->set(array('internship_numer'=>$internship_id));
        $logged_in=$session->get('isLoggedIn');
        if($logged_in){           
                
                return redirect()->to('/view-internship-details/'.$internship_id);    

        }else{
            $session->set('usertype', '1');
            $session->set('login_usertype', 'candidate');
         return view('Auth/login');
           // return redirect()->to('/main_login/1');    
        }
    }

     //search with keyword
     public function keyword_search_public()
        {
            //print_r($_REQUEST);
            $session = session();
            $searched_keyword = $this->request->getVar('searched_keyword');        

            if (!empty($searched_keyword)) { 
                $_SESSION['searched_keyword']=$searched_keyword; 
            } else {
                $_SESSION['searched_keyword']=''; 
            }
            
             echo json_encode(csrf_hash());
        }

        public function keyword_search_candidate_public()
        {
            //print_r($_REQUEST);
            $session = session();
            $searched_keyword = $this->request->getVar('searched_keyword');        

            if (!empty($searched_keyword)) { 
                $_SESSION['searched_keyword']=$searched_keyword; 
            } else {
                $_SESSION['searched_keyword']=''; 
            }
            
             echo json_encode(csrf_hash());
        }
  //clear search filter
  public function clear_search_filter()
    {
        $session = session();

        $ses_data = [
            'searched_keyword', 
        ];

        $session->remove($ses_data);
        return redirect()->to('/web-search-internship');
    }
    public function clear_search_folder()
    {
        $session = session();

        $ses_data = [
            'searched_keyword', 
            'searched_keyword_candidates',
            'searched_keyword_folder',
            'searched_keyword_search_folder'
        ];

        $session->remove($ses_data);
        return redirect()->to('/web-search-folder');
    }

    public function clear_search_user()
    {
        $session = session();

        $ses_data = [
            'searched_keyword', 
            'searched_keyword_candidates',
            'searched_keyword_folder',
            'searched_keyword_search_folder'
        ];

        $session->remove($ses_data);
        return redirect()->to('/web-search-candidate');
    }
    public function clear_search_candidate($folder_id_new=NULL)
    {
        $session = session();

        $ses_data = [
            'searched_keyword',
            'searched_keyword_candidates',
            'searched_keyword_folder',
            'searched_keyword_search_folder' 
        ];

        $session->remove($ses_data);
        if($folder_id_new){
        return redirect()->to('/search-candidates/'.$folder_id_new);
        }else{
            return redirect()->to('/search-candidates');    
        }
    }
    public function clear_applied_candidate()
    {
        $session = session();

        $ses_data = [
            'searched_keyword', 
        ];

        $session->remove($ses_data);
        return redirect()->to('/web-applied-candidate');
    }
    public function clear_user_candidate($inte_id)
    {
        $session = session();

        $ses_data = [
            'searched_keyword', 
        ];

        $session->remove($ses_data);
        return redirect()->to('/web-applied-candidate/'.$inte_id);
    }
    public function payment_policy()
    {
        return view('payment_policy');
    }
    public function about()
    {
        return view('about');
    }
    

    public function login_gmetrix()
    {
    	 helper(['form']);
        $session = session(); 
        $session->set(array('gmetrix'=>'12345'));
        $logged_in=$session->get('isLoggedIn');
        if($logged_in){           
                
                return redirect()->to('gmetrix-view');    

        }else{
            $session->set('usertype', '1');
            $session->set('login_usertype', 'candidate');
         return view('Auth/login');
           // return redirect()->to('/main_login/1');    
        }
    }

    public function gmetrix_web()
    {
        $session = session();

        /**Load Candidate model */
        $Candidate_model = new Candidate_model();

        /**Get User id Session */
        $userid          = $session->get('userid');
        $where_status = array('status' => '1', 'userid' => $userid);
        $gmetrix_detail = $Candidate_model->fetch_table_row('gmetrix_candidate_data', $where_status);
        $where_status1 = array('userid' => $userid);
        $gmetrix_detail1 = $Candidate_model->fetch_table_row('gmetrix_course_data', $where_status1);
        if(!empty($gmetrix_detail)){
        $dataToSend = [
            'Key' => '0BE6177D3019',
            'StudentId' => $gmetrix_detail->StudentId,
        ];

        // API endpoint URL
        $apiUrl = 'https://managementservices.gmetrix.net/api/StudentServices/GetDeepLinkSignOnInfoByStudentId';

        // Create an instance of the HTTP client
        $httpClient = \Config\Services::curlrequest();

        // Send a POST request with the data
        $response = $httpClient->post($apiUrl, [
            'json' => $dataToSend,
            'verify' => false,
        ]);
        // echo"<pre>";print_r($response);exit;
        // Check for a successful API request
        if ($response->getStatusCode() === 200) {
            $apiResponse = json_decode($response->getBody());
            $count=count($apiResponse);
            if($count==1){
                $data['gmatrix_data'] = $apiResponse;
            }else{
                $data['gmatrix_data'] = '';
            }
            
            //  echo"<pre>";print_r($apiResponse);exit;
            // Process the API response as needed
            // return $this->response->setJSON($apiResponse);
        } else {
            return $this->response->setStatusCode(500)->setJSON(['error' => 'API request failed']);
        }
       
    }else{
        $data['gmatrix_data'] = '';
    }
    $where_status1 = array('status' => '1', 'userid' => $userid);
    $data['profile_personal'] = $Candidate_model->fetch_table_row('can_personal_details', $where_status1);
        return view('Candidate/gmetrix',$data);
    }

    public function blog()
    {
        $Common_model = new Common_model();
        $where_status1 = array('status' => '1','exclusive_status' => '1');
        $data['exclusive'] = $Common_model->fetch_table_data('admin_blog', $where_status1);

        $where_status2 = array('status' => '1','exclusive_status' => '0');
        $data['all_blog'] = $Common_model->fetch_table_data('admin_blog', $where_status2);
        return view('blog_list',$data);
    }

}


