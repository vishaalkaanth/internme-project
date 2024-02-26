<?php

namespace App\Controllers;
// require_once(APPPATH . "vendor/autoload.php");
// require 'vendor/autoload.php';
use App\Models\Candidate_model;
use App\Models\Common_model;
use GuzzleHttp\Client;

require_once(APPPATH . "Libraries/razorpay/razorpay-php/Razorpay.php");

use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

$this->Candidate_model = new Candidate_model();

class Candidate extends BaseController
{
    protected $session;
    function __construct()
    {
        $this->session = \Config\Services::session();
        $this->session->start();
        date_default_timezone_set('Asia/Kolkata');

        $current  = '';

        if (isset($_SERVER['REQUEST_URI'])) {
            $current = $_SERVER['REQUEST_URI'];
        }
        //print_r($previous);
        if (strpos($current, 'search-internship') == false) {
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
        if (strpos($current, 'candidate-logsheet') == false) {
            $session = session();
            $ses_data = [
                'can_work_report_showing_limit',
            ];
            $session->remove($ses_data);
        }
        if (strpos($current, 'my-applications/7') == false) {
            $session = session();
            $ses_data = [
                'application_offers_received',
            ];
            $session->remove($ses_data);
        }
    }

    public function delete_common($id, $tablename, $redirect)
    {

        $session = session();
        $Candidate_model = new Candidate_model();
        $where = array('id' => $id);
        $data = [
            'status' => '0',
        ];
        $update_data = $Candidate_model->update_commen($tablename, $where, $data);
        if ($update_data) {
            $session->setFlashdata('error_msg', 'Deleted successfully');
            $session->setFlashdata('error_status', '1');
            return redirect()->to($redirect);
        } else {
            return redirect()->to($redirect);
        }
    }

    public function emp_search_internship_showing($showing_result)
    {
        $session = session();
        $ses_data = [
            'search_internship_showing_limit' => $showing_result,
        ];
        $session->set($ses_data);
        $login = $session->get('isLoggedIn');
        if (isset($login) && $login != '') {
            return redirect()->to('search-internship');
        } else {
            return redirect()->to('web-search-internship');
        }
    }


    public function can_intern_list()
    {
        //print_r($_SESSION); exit();
        helper(['form']);
        $session = session();
        $Candidate_model = new Candidate_model();
        $userid    =    $session->get('userid');
        $search_internship_showing_limit = $session->get('search_internship_showing_limit');
        // $current_date =date("Y-m-d");
        // $where1 = array('status'=> '1','internship_startdate >'=> $current_date);
        $where = array('status' => '1');
        $order_by = array('ordercolumn' => 'city', 'ordertype' => 'asc');
        $order_by_profile = array('ordercolumn' => 'profile', 'ordertype' => 'asc');
        $data['category_list'] = $Candidate_model->fetch_table_data_for_all('master_profile', $where, $order_by_profile);
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
        //    echo $keyword_search;
        // foreach ($keyword_search as $key) {
        if (!empty($keyword_search)) {
            $where_key = array('search_key' => $keyword_search, 'candidate_id'  => $userid);
            $search_based = $Candidate_model->fetch_table_row('can_search_keyword', $where_key);

            if ($search_based) {
                $count = $search_based->count + 1;
                $data = [
                    'count' => $count
                ];
                $insert_data = $Candidate_model->update_commen('can_search_keyword', $where_key, $data);
            } else {
                $count = 1;
                $data = [
                    'search_key' => $keyword_search,
                    'candidate_id'  => $userid,
                    'count' => $count
                ];
                $insert_data = $Candidate_model->insert_commen('can_search_keyword', $data);
            }
        }

        if (isset($keyword_search)) {
            // $keyword_search = $keyword_search;
            $start_id = 0;
            $limit = 1000;
            $data_internship_search = $Candidate_model->fetch_table_data_filter_closed($where, $filter_category, $filter_city, $filter_emp, $filter_start_date, $filter_internship_duration_one, $filter_parttime, $filter_work_from_home, $filter_job_offer, $filter_stipend, $limit, $start_id, $keyword_search);
        } else {
            $keyword_search = '';
        }
        $pager = service('pager');
        $page = (int) $this->request->getGet('page'); // 
        if (isset($search_internship_showing_limit)) {
            $limit = $search_internship_showing_limit;
        } else {
            $limit = config('Pager')->perPage_can; // see Config/Pager.php
        }


        if (!isset($page) || $page === 0 || $page === 1) {
            $page = 1;
            $start_id = 0;
        } else {
            $start_id = ($page - 1) * $limit;
            $page = $page;
        }

        $data['page_start_id'] = $start_id;
        $data['page_default_limit'] = $limit;
        $data_internship = $Candidate_model->fetch_table_data_filter_closed($where, $filter_category, $filter_city, $filter_emp, $filter_start_date, $filter_internship_duration_one, $filter_parttime, $filter_work_from_home, $filter_job_offer, $filter_stipend, null, null, $keyword_search);
        $data['internship_list_count'] = $data_internship;
        if (isset($data_internship_search)) {
            if (!empty($data_internship_search)) {
                $total = count($data_internship_search);
            } else {
                $total = 0;
            }

            // $pager_links = $pager->makeLinks($page, $limit, $total, 'custom_pagination');
            // $data['pager_links'] = $pager_links;
            //  print_r($pager_links);exit;
        } else {


            if (!empty($data_internship)) {
                $total   = count($data_internship);
            } else {
                $total   = 0;
            }
        }
        //  echo $total;exit;

        $pager_links = $pager->makeLinks($page, $limit, $total, 'custom_pagination');
        $data['pager_links'] = $pager_links;
        $previous = '';
        // $previous = "javascript:history.go(-1)";
        if (isset($_SERVER['HTTP_REFERER'])) {
            $previous = $_SERVER['HTTP_REFERER'];
            // echo $previous;
            // exit();
        }


        //  echo "if";
        // exit();
        $data['internship_list'] = $Candidate_model->fetch_table_data_filter_closed($where, $filter_category, $filter_city, $filter_emp, $filter_start_date, $filter_internship_duration_one, $filter_parttime, $filter_work_from_home, $filter_job_offer, $filter_stipend, $limit, $start_id, $keyword_search);

        // echo "else";
        // exit();
        // $ses_data = [
        //     'candidate_filter_category',
        //     'candidate_filter_city',
        //     'candidate_filter_start_date',
        //     'candidate_filter_internship_duration_one',
        //     'candidate_filter_internship_duration_two',
        //     'candidate_filter_parttime',
        //     // 'candidate_filter_fulltime',
        //     'candidate_filter_work_from_home',
        //     'candidate_filter_job_offer',
        //     'candidate_filter_stipend',
        // ];

        // $session->remove($ses_data);
        // $filter_category = '';
        // $filter_city = '';
        // $filter_start_date = '';
        // $filter_internship_duration_one = '';
        // $filter_internship_duration_two = '';
        // $filter_parttime = '';
        // // $filter_fulltime = $session->get('candidate_filter_fulltime');
        // $filter_work_from_home = '';
        // $filter_job_offer = '';
        // $filter_stipend = '';
        // $data['internship_list'] = $Candidate_model->fetch_table_data_filter($where, $filter_category, $filter_city, $filter_start_date, $filter_internship_duration_one, $filter_parttime, $filter_work_from_home, $filter_job_offer, $filter_stipend, $limit, $start_id);


        $where_can = array('status' => '1', 'userid' => $userid);
        $data['education_details'] = $Candidate_model->fetch_table_data('can_education_details', $where_can);
        $data['profile_personal'] = $Candidate_model->fetch_table_row('can_personal_details', $where_can);
        $data['address_details'] = $Candidate_model->fetch_table_row('can_address_details', $where_can);
        return view('Candidate/can_intern_list', $data);
    }

    public function set_candidate_filters()
    {
        //print_r($_REQUEST);
        $session = session();
        $Candidate_model = new Candidate_model();
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
            if ($footer_type == 1) {
                $_SESSION['candidate_footer_city'] = $city;
            } elseif ($footer_type == 2) {
                $_SESSION['candidate_footer_category'] = $category;
            }
            //$session->set('candidate_filter_category', $category);
        }

        if (!empty($category)) {
            //$category_arr = explode(',', $category);
            $_SESSION['candidate_filter_category'] = $category;
            //$session->set('candidate_filter_category', $category);
        } else {
            $_SESSION['candidate_filter_category'] = '';
            //$session->set('candidate_filter_category', '');
        }
        if (!empty($city)) {
            $_SESSION['candidate_filter_city'] = $city;
            //$city_arr = explode(',', $city);
            //$session->set('candidate_filter_city', $city);

        } else {
            //$session->set('candidate_filter_city', '');
            $_SESSION['candidate_filter_city'] = '';
        }
        if (!empty($company)) {
            $_SESSION['candidate_filter_emp'] = $company;
            //$city_arr = explode(',', $city);
            //$session->set('candidate_filter_city', $city);

        } else {
            //$session->set('candidate_filter_city', '');
            $_SESSION['candidate_filter_emp'] = '';
        }
        if (!empty($start_date)) {
            $_SESSION['candidate_filter_start_date'] = $start_date;
            // $session->set('candidate_filter_start_date', $start_date);
        }
        if (!empty($internship_duration_one)) {
            $_SESSION['candidate_filter_internship_duration_one'] = $internship_duration_one;
            //$session->set('candidate_filter_internship_duration_one', $internship_duration_one);
        }
        if (!empty($internship_duration_two)) {
            $_SESSION['candidate_filter_internship_duration_two'] = $internship_duration_two;
            //$session->set('candidate_filter_internship_duration_two', $internship_duration_two);
        }
        if (!empty($parttime)) {
            $_SESSION['candidate_filter_parttime'] = $parttime;
            //$session->set('candidate_filter_parttime', $parttime);
        }
        // if(!empty($fulltime)){
        //     $session->set('candidate_filter_fulltime' , $fulltime);
        // }
        if (!empty($work_from_home)) {
            $_SESSION['candidate_filter_work_from_home'] = $work_from_home;
            //$session->set('candidate_filter_work_from_home', $work_from_home);
        }
        if (!empty($job_offer)) {
            $_SESSION['candidate_filter_job_offer'] = $job_offer;
            //  $session->set('candidate_filter_job_offer', $job_offer);
        }
        if (!empty($stipend)) {
            $_SESSION['candidate_filter_stipend'] = $stipend;
            // $session->set('candidate_filter_stipend', $stipend);
        }
        echo json_encode(csrf_hash());
    }

    public function unset_candidate_filters()
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
        return redirect()->to('/search-internship');
    }

    public function can_intern_single($internship_id)
    {
        helper(['form']);
        $session = session();
        $Candidate_model = new Candidate_model();
        $userid    =    $session->get('userid');
        $where = array('status' => '1', 'internship_id' => $internship_id);
        $data['internship_details'] = $Candidate_model->fetch_table_row('employer_post_internship', $where);
        $internship_details = $data['internship_details'];
        if (isset($internship_details)) {
            // echo $internship_details->user_id;exit();
            $where_emp = array('status' => '1', 'userid' => $internship_details->company_id);
            $data['emp_profile_details'] = $Candidate_model->fetch_table_row('profile_completion_form', $where_emp);

            $where_city = array('status' => '1', 'user_id' => $internship_details->company_id, 'internship_id' => $internship_details->internship_id);
            $data['int_city'] = $Candidate_model->fetch_table_data('emp_worklocation_multiple', $where_city);

            $where_skill = array('status' => '1', 'user_id' => $internship_details->company_id, 'internship_id' => $internship_details->internship_id);
            $data['int_skill'] = $Candidate_model->fetch_table_data('emp_selected_skills_multiple', $where_skill);

            $where_edu = array('status' => '1', 'user_id' => $internship_details->company_id, 'internship_id' => $internship_details->internship_id);
            $data['int_edu'] = $Candidate_model->fetch_table_data('emp_selected_education_multiple', $where_edu);
            $where_spe = array('status' => '1', 'user_id' => $internship_details->company_id, 'internship_id' => $internship_details->internship_id);
            $data['int_spe'] = $Candidate_model->fetch_table_data('emp_selected_specialization_multiple', $where_spe);
            // print_r($data['int_spe']);exit;
            $current_date = date("Y-m-d");
            // $where_related = array('status' => '1', 'active_status' => '1', 'profile' => $internship_details->profile, 'internship_id !=' => $internship_details->internship_id, 'internship_startdate >' => $current_date);

            $city_arr = array();
            $int_city = $data['int_city'];
            if (!empty($int_city)) {
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
            $data['int_related_details'] = $Candidate_model->fetch_related_internship_data($internship_details->internship_id, $internship_details->profile, $city_arr);
            $where_can = array('status' => '1', 'userid' => $userid);
            $data['education_details'] = $Candidate_model->fetch_table_data('can_education_details', $where_can);
            $data['profile_personal'] = $Candidate_model->fetch_table_row('can_personal_details', $where_can);
            $data['address_details'] = $Candidate_model->fetch_table_row('can_address_details', $where_can);
            $where_count = array('status' => '1', 'internship_id' => $internship_details->internship_id);
            $data['applicant_count'] = $Candidate_model->data_count_fetch('can_applied_internship', $where_count);
            $view_count = ($internship_details->view_count + 1);
            $data1 = [
                'view_count' => $view_count,
            ];
            $update_data = $Candidate_model->update_commen('employer_post_internship', $where, $data1);
            $where7 = array('status' => '1', 'internship_id' => $internship_id);
            $data['perks'] = $Candidate_model->fetch_table_data('emp_selected_perks_multiple', $where7);

            $where_rat = array('can_applied_internship.internship_id' => $internship_id, 'can_applied_internship.rating_status' => '1');
            $rating_data = $Candidate_model->fetch_rating_data('can_applied_internship', $where_rat);
            if (!empty($rating_data[0]->count)) {
                $data['rating'] = round($rating_data[0]->rating / $rating_data[0]->count);
            } else {
                $data['rating'] = '0';
            }
            return view('Candidate/can_intern_single', $data);
        } else {
            return view('Common/404');
        }
    }


    public function can_profile_personal()
    {
        helper(['form']);
        $session = session();
        $Candidate_model = new Candidate_model();
        $userid    =    $session->get('userid');
        $where = array('status' => '1', 'userid' => $userid);
        $data['profile_personal'] = $Candidate_model->fetch_table_row('can_personal_details', $where);
        $data['user_profile_personal'] = $Candidate_model->fetch_table_row('userlogin', $where);
        $where = array();
        $order_by11 = array('ordercolumn' => 'dist_name', 'ordertype' => 'asc');
        $data['master_location'] = $Candidate_model->fetch_table_data_for_all('master_district', $where, $order_by11);
        $where1 = array('status' => '1', 'user_id' => $userid);
        $data['location'] = $Candidate_model->fetch_table_data_for_all('can_worklocation_multiple', $where1);
        return view('Candidate/can_profile_personal', $data);
    }
    public function can_profile_education()
    {
        helper(['form']);
        $session = session();
        $Candidate_model = new Candidate_model();
        $userid    =    $session->get('userid');
        $where = array('status' => '1', 'userid' => $userid);
        $data['education_details'] = $Candidate_model->fetch_table_data('can_education_details', $where);
        $where1 = array('status' => '1');
        $data['master_college'] = $Candidate_model->fetch_table_data_for_all('master_college', $where1);
        $data['master_courses'] = $Candidate_model->fetch_table_data_for_all('master_academic_courses', $where1);
        $data['master_specialization'] = $Candidate_model->fetch_table_data_for_all('master_academic_branch', $where1);
        return view('Candidate/can_profile_education', $data);
    }
    public function can_profile_address()
    {
        helper(['form']);
        $session = session();
        $Candidate_model = new Candidate_model();
        $userid    =    $session->get('userid');
        $where = array('status' => '1', 'userid' => $userid);
        $data['address_details'] = $Candidate_model->fetch_table_row('can_address_details', $where);
        $where1 = array('status' => '1');
        $data['state'] = $Candidate_model->fetch_table_data_state('master_state', $where1);
        $data['get_district'] = $Candidate_model->fetch_table_data1('master_district', $where1);
        return view('Candidate/can_profile_address', $data);
    }
    public function can_profile_experience()
    {
        helper(['form']);
        $session = session();
        $Candidate_model = new Candidate_model();
        $userid    =    $session->get('userid');
        $where = array('status' => '1', 'userid' => $userid);
        $data['experience_details'] = $Candidate_model->fetch_table_data('can_experience_details', $where);
        $where1 = array('status' => '1');
        $data['city_list'] = $Candidate_model->fetch_table_data('master_city', $where1);
        $where2 = array('id!=' => '0');
        $data['profile_data'] = $Candidate_model->fetch_table_data('master_profile', $where2);
        return view('Candidate/can_profile_experience', $data);
    }
    public function can_profile_skills()
    {
        helper(['form']);
        $session = session();
        $Candidate_model = new Candidate_model();
        $userid    =    $session->get('userid');
        $where = array('status' => '1', 'userid' => $userid);
        $data['skill_details'] = $Candidate_model->fetch_table_data('can_skills_details', $where);
        $where1 = array('status' => '1');
        $order_by = array('ordercolumn' => 'skill_name', 'ordertype' => 'ASC');
        $data['master_skills'] = $Candidate_model->fetch_table_data_for_all('master_skills', $where1, $order_by);
        return view('Candidate/can_profile_skills', $data);
    }
    public function can_profile_work()
    {
        helper(['form']);
        $session = session();
        $Candidate_model = new Candidate_model();
        $userid    =    $session->get('userid');
        $where = array('status' => '1', 'userid' => $userid);
        $data['work_sample'] = $Candidate_model->fetch_table_row('can_work_sample', $where);
        return view('Candidate/can_profile_work', $data);
    }

    public function update_can_personal_details()
    {
        $validation =  \Config\Services::validation();

        $session = session();
        $Candidate_model = new Candidate_model();
        $userid = $this->request->getVar('userid');
        $where = array('status' => '1', 'userid' => $userid);
        $profile_page_view    =    $session->get('profile_page_view');
        $intership_id = $session->get('intership_number');
        $isValidated = $this->validate([
            // 'add_profile_full_name' => ['label'  => 'Full Name', 'rules'  => 'required'],
            'add_profile_first_name' => ['label'  => 'First Name', 'rules'  => 'required'],
            'add_profile_last_name' => ['label'  => 'Last Name', 'rules'  => 'required'],
            'add_profile_phone_number' => ['label'  => 'Mobile Number', 'rules'  => 'required|numeric|max_length[10]'],
            // 'add_profile_dob' => ['label'  => 'Date of Birth', 'rules'  => 'required'],
            'add_profile_gender' => ['label'  => 'Gender', 'rules'  => 'required'],
            // 'add_profile_email' => ['label'  => 'Email ID', 'rules'  => 'required|valid_email'],
            'location_full_name' => ['label'  => 'Location', 'rules'  => 'required'],
        ]);
        if (!$isValidated) {
            $session->setFlashdata('error_status', '3');
            $session->setFlashdata('error_msg', $validation->getErrors());
            return redirect()->to('personal-details');
            // return view('Candidate/can_profile_personal',$data);
        } else {

            // if(isset($_FILES['add_profile_photo']['name']) && $_FILES['add_profile_photo']['name']!=""){
            //     $profile_photo = $this->request->getFile('add_profile_photo');
            //     $newName = $profile_photo->getRandomName();
            //     $profile_photo->move('public/assets/docs/uploads/can_profile_photo/',$newName);
            // }else{
            //     $newName = $this->request->getVar('profile_photo_view');
            // }
            if (!empty($this->request->getVar('add_profile_email'))) {
                $verify_status = 1;
            } else {
                $verify_status = 0;
            }
            $location_full_name = $this->request->getVar('location_full_name');
            $loacation_data = (explode(",", $location_full_name));
            $data = [
                'profile_full_name' => $this->c_trim($this->request->getVar('add_profile_first_name')) . ' ' . $this->c_trim($this->request->getVar('add_profile_last_name')),
                'profile_first_name' => $this->c_trim($this->request->getVar('add_profile_first_name')),
                'profile_last_name' => $this->c_trim($this->request->getVar('add_profile_last_name')),
                'profile_phone_number' => $this->c_trim($this->request->getVar('add_profile_phone_number')),
                'profile_email' => $this->c_trim($this->request->getVar('add_profile_email')),
                'profile_gender' => $this->request->getVar('add_profile_gender'),
                'profile_linked_in' => $this->request->getVar('add_profile_linked_in'),
                'g_location_id'   => $loacation_data[0],
                'g_location_name' => $loacation_data[1],
                'location_district'  => $loacation_data[1],
                'location_state'  => $loacation_data[2],
                'email_verify_status'  => $verify_status,

            ];
            $update_data = $Candidate_model->update_commen('can_personal_details', $where, $data);
            $data1 = [
                'name' => $this->c_trim($this->request->getVar('add_profile_first_name')) . ' ' . $this->c_trim($this->request->getVar('add_profile_last_name')),
                'candidate_firstname' => $this->request->getVar('add_profile_first_name'),
                'candidate_lastname' => $this->request->getVar('add_profile_last_name'),
                'username' => $this->request->getVar('add_profile_first_name'),
                'mobile' => $this->request->getVar('add_profile_phone_number'),

            ];

            $update_user_data = $Candidate_model->update_commen('userlogin', $where, $data1);
            $profile_complete_status = $session->get('profile_complete_status');
            $next_but_status = $session->get('next_but_status');
            // echo $next_but_status;exit;
            // $intership_id=$session->get('intership_number');
            //     $ses_data = [
            //         'profile_complete_status',
            //         'company_logo',
            //         'company_name',
            //         'intership_profile',
            //         'intership_number'
            //     ];

            $where1 = array('status' => '1', 'user_id' => $userid);
            $data['location'] = $Candidate_model->fetch_table_data_for_all('can_worklocation_multiple', $where1);
            // print_r($data['location']);exit;




            $work_location = $this->request->getVar('work_location');
            if (isset($work_location)) {
                $wheredel = array('user_id' => $userid);
                $result = $Candidate_model->delete_commen('can_worklocation_multiple', $wheredel);
                $map_id2 = $this->request->getVar('work_location');
                //print_r($map_id2);exit();
                // $commonarr = implode(",", $map_id2);

                if (isset($map_id2) && !empty($map_id2)) {
                    $data_worklocation = array();
                    if (!empty($map_id2)) {
                        foreach ($map_id2 as $key => $value) {
                            $where = array('dist_name' => $value);
                            $master_location = $Candidate_model->fetch_table_row('master_district', $where);
                            //print_r($key);
                            $data_worklocation[]  = array('user_id' => $userid, 'internship_id' => $intership_id, 'g_location_id'  => $master_location->dist_id, 'g_location_name'  => $master_location->dist_name, 'location_district'  => $master_location->dist_name, 'location_state'  => $master_location->state_name, 'status'  => '1');
                        }
                    }

                    if (count($data_worklocation) > 0) {
                        $data_worklocation =  $Candidate_model->insertBatch1('can_worklocation_multiple', $data_worklocation);
                    }
                }
            }

            // }
            if ($update_data) {
                // echo 1;exit;
                $ses_data1 = [
                    'candidate_email',
                ];
                $session->remove($ses_data1);
                $ses_data_name = [
                    'name' => $this->c_trim($this->request->getVar('add_profile_first_name')),
                ];
                $session->set($ses_data_name);
                $session->setFlashdata('error_msg', 'Personal Details Has Been Updated Successfully');
                $session->setFlashdata('error_status', '2');
                // if(isset($profile_complete_status) && ($profile_complete_status==1)){

                //     $session->remove($ses_data);
                //     // print_r($_SESSION);exit();
                //     return redirect()->to('can-apply-for-internship/'.$intership_id);
                // }
                // else{
                if (isset($next_but_status) && ($next_but_status == 1)) {

                    // $ses_data12 = [
                    //     'next_but_status',
                    // ];
                    // //  echo 1;exit;
                    // $session->remove($ses_data12);
                    return redirect()->to('education-details');
                } elseif ($profile_page_view == 1) {

                    $ses_data1 = [
                        'profile_page_view',
                    ];
                    // echo 2;exit;
                    $session->remove($ses_data1);
                    return redirect()->to('profile-details');
                } elseif (isset($profile_complete_status) && ($profile_complete_status == 1)) {
                    $ses_data = [
                        'profile_complete_status',
                        'company_logo',
                        'company_name',
                        'intership_profile',
                        'intership_number'
                    ];
                    // echo 3;exit;
                    $session->remove($ses_data);
                    return redirect()->to('can-apply-for-internship/' . $intership_id);
                } else {
                    if (isset($next_but_status) && ($next_but_status == 1)) {

                        // $ses_data12 = [
                        //     'next_but_status',
                        // ];
                        // //  echo 1;exit;
                        // $session->remove($ses_data12);
                        return redirect()->to('education-details');
                    } elseif ($profile_page_view == 1) {

                        $ses_data1 = [
                            'profile_page_view',
                        ];
                        $session->remove($ses_data1);
                        return redirect()->to('profile-details');
                    } elseif ($next_but_status == 1) {

                        // $ses_data12 = [
                        //     'next_but_status',
                        // ];
                        // $session->remove($ses_data12);
                        return redirect()->to('education-details');
                    } else {
                        return redirect()->to('education-details');
                    }
                }

                // }

            } else {
                // if(isset($profile_complete_status) && ($profile_complete_status==1)){
                //     // $session->remove($ses_data);
                //     return redirect()->to('can-apply-for-internship/'.$intership_id);
                // }
                // else{
                if (isset($next_but_status) && ($next_but_status == 1)) {

                    // $ses_data12 = [
                    //     'next_but_status',
                    // ];
                    // //  echo 1;exit;
                    // $session->remove($ses_data12);
                    return redirect()->to('education-details');
                } else if (isset($profile_complete_status) && ($profile_complete_status == 1)) {
                    $ses_data = [
                        'profile_complete_status',
                        'company_logo',
                        'company_name',
                        'intership_profile',
                        'intership_number'
                    ];
                    $session->remove($ses_data);
                    return redirect()->to('can-apply-for-internship/' . $intership_id);
                } elseif ($profile_page_view == 1) {

                    $ses_data1 = [
                        'profile_page_view',
                    ];
                    $session->remove($ses_data1);
                    return redirect()->to('profile-details');
                } else {
                    return redirect()->to('education-details');
                }
                // }
            }
        }
    }

    public function can_profile_mobile_otp_send()
    {
        $userid = $this->request->getVar('userid');
        $email = $this->request->getVar('email');
        $number = $this->request->getVar('number');
        $domain = explode("@", $email);

        $CheckMXNew =  $this->CheckMX($domain[1]);
        if ($CheckMXNew != '0') {
            $otp = mt_rand(100000, 999999);
            //echo $userid;
            $session = session();
            $Candidate_model = new Candidate_model();

            //check duplicate 
            $duplicate_data = $Candidate_model->duplicate_email($email, $userid);
            //print_r($duplicate_data);

            if (empty($duplicate_data)) {

                //check otp count
                $otp_count = $Candidate_model->otp_count_check($email);

                //allow only 3 attempt
                if ($otp_count < 3) {
                    $otp_count_new  = $otp_count + 1;

                    $data = array(
                        'email_id'     => $email,
                        'otp_count'    => $otp_count_new,
                        'otp_number'   => $otp,
                        'user_type'    => 1,
                    );

                    $update_otp = $Candidate_model->otp_count_save($data);

                    $where = array('userid' => $userid);
                    $can_details = $Candidate_model->fetch_table_row('can_personal_details', $where);
                    //gmail sent email otp
                    // $msg_data['msg_data'] = array('otp' => $otp, 'name' => $can_details->profile_full_name, 'email_status' => $can_details->email_verify_status); //dynamic contents for template
                    // $message     = view('email_template/verification_of_email_candidate', $msg_data);

                    // Zoho mail
                    $current_year = date('Y');
                    if ($can_details->email_verify_status == 1) {
                        $title = 'OTP Verification';
                    } else {
                        $title = 'Verification of Email';
                    }
                    $message = '{ "otp" : "' . $otp . '", "name" : "' . $can_details->profile_full_name . '", "title" : "' . $title . '","year" : "' . $current_year . '"  }'; //dynamic contents for template
                    $subject      = 'Internme - OTP Verification';
                    $to_email     =  $email;
                    $from_content = 'Internme - OTP Verification';
                    $template_key = '2d6f.456f260c51ab9602.k1.ce775890-a6db-11ed-8189-525400fcd3f1.1862baaac99';
                    $this->email_send($message, $subject, $to_email, $from_content, $template_key);


                    $msg = 'We have sent an OTP to your registered Email ID';
                    $otp_status = 0;

                    //    $where = array('userid' => $userid);
                    //     $available_data = $Candidate_model->fetch_table_row('can_personal_details',$where);

                    //      $otp_count = $available_data->email_otp_count+1;
                    //     // print_r($otp_count);
                    //     $data2 =array('email_otp' => $otp,'email_otp_count' => $otp_count,'otp_status' => 1);
                    //     $update_otp = $Candidate_model->update_commen('can_personal_details',$where,$data2);

                    if ($update_otp) {


                        echo '1' . '^' . csrf_hash() . '^' . $msg . '^' . $otp_status;
                    } else {


                        echo '0' . '^' . csrf_hash() . '^' . $msg . '^' . $otp_status;
                    }
                } else {
                    $msg = 'OTP Limit Exceeded, Please Try After 24 Hours';
                    $otp_status = 1;
                    echo '1' . '^' . csrf_hash() . '^' . $msg . '^' . $otp_status;
                }
            } else {
                $msg = 'Email ID Duplicate';
                $otp_status = 2;
                echo '1' . '^' . csrf_hash() . '^' . $msg . '^' . $otp_status;
            }
        } else {
            $msg = 'Please enter valid email id';
            $otp_status = 5;
            echo '0' . '^' . csrf_hash() . '^' . $msg . '^' . $otp_status;
        }
    }

    public function can_mobile_otp_verify()
    {
        // $model = new Employer_model();
        $userid = $this->request->getVar('userid');
        $user_otp = $this->request->getVar('user_otp');
        $email = $this->request->getVar('email');
        $session = session();
        $Candidate_model = new Candidate_model();

        $where = array('user_type' => 1, 'otp_number' => $user_otp, 'email_id' => $email);
        $available_data = $Candidate_model->fetch_table_data('user_otp', $where);
        if ($available_data) {
            // $data = ['otp_status' => 1];
            $data = array('profile_email' => $email, 'otp_status' => 1, 'email_verify_status' => 1);
            $where1 = array('userid' => $userid);
            $update_otp = $Candidate_model->update_commen('can_personal_details', $where1, $data);
            echo '1' . '^' . csrf_hash();
        } else {
            echo '0' . '^' . csrf_hash();
        }






        //   $where = array('userid' => $userid,'email_otp' => $user_otp,'profile_email' => $email);
        //   $available_data = $Candidate_model->fetch_table_data('can_personal_details',$where);
        // if($available_data){
        //   $data = ['otp_status' => 1];
        //   $update_otp = $Candidate_model->update_commen('can_personal_details',$where,$data);
        //   echo '1'. '^' . csrf_hash();
        //   }else{
        //   echo '0'. '^' . csrf_hash(); 
        //   }

    }

    public function add_can_educational_details()
    {
        $validation =  \Config\Services::validation();
        $session = session();
        $Candidate_model = new Candidate_model();
        $current_datetime = $Candidate_model->current_datetime();
        $userid = $this->request->getVar('userid');
        $isValidated = $this->validate([
            'add_education_college_name' => ['label'  => 'College Name', 'rules'  => 'required'],
            'add_education_course' => ['label'  => 'Course', 'rules'  => 'required'],
            'add_education_specialization' => ['label'  => 'Specialization', 'rules'  => 'required'],
            'add_education_start_year' => ['label'  => 'From', 'rules'  => 'required'],
            'add_education_end_year' =>  ['label'  => 'Till', 'rules'  => 'required'],
        ]);
        if (!$isValidated) {
            $session->setFlashdata('error_status', '3');
            $session->setFlashdata('error_msg', $validation->getErrors());
            return redirect()->to('education-details');
        } else {
            $add_education_college_name = $this->request->getVar('add_education_college_name');
            $where = array('college_name' => $add_education_college_name);
            $college_id = $Candidate_model->fetch_table_row('master_college', $where);

            if (!empty($college_id)) {
                $edu_college = $college_id->id;
                $college_name = '';
            } else {
                $edu_college = 0;
                $college_name = $this->request->getVar('add_education_college_name');
            }
            $add_education_course = $this->request->getVar('add_education_course');
            $where = array('name' => $add_education_course);
            $courses_id = $Candidate_model->fetch_table_row('master_academic_courses', $where);

            if (!empty($courses_id)) {
                $edu_course = $courses_id->id;
                $course_name = '';
            } else {
                $edu_course = 0;
                $course_name = $this->request->getVar('add_education_course');
            }
            $add_education_specialization = $this->request->getVar('add_education_specialization');
            $where = array('name' => $add_education_specialization);
            $specialization_id = $Candidate_model->fetch_table_row('master_academic_branch', $where);

            if (!empty($specialization_id)) {
                $edu_specialization = $specialization_id->id;
                $specialization_name = '';
            } else {
                $edu_specialization = 0;
                $specialization_name = $this->request->getVar('add_education_specialization');
            }
            $add_education_performance_scale = $this->request->getVar('add_education_performance_scale');
            if ($add_education_performance_scale == 1) {
                $add_education_performance = $this->request->getVar('add_education_performance_per');
            } elseif ($add_education_performance_scale == 2) {
                $add_education_performance = $this->request->getVar('add_education_performance_cgpa');
            } else {
                $add_education_performance = '';
            }

            $data = [
                'userid' => $userid,
                'education_college_name' => $edu_college,
                'education_college_name_other' => $college_name,

                'education_course' => $edu_course,
                'education_course_other' => $course_name,
                'education_specialization' => $edu_specialization,
                'education_specialization_other' => $specialization_name,
                'education_start_year' => $this->request->getVar('add_education_start_year'),
                'education_end_year' => $this->request->getVar('add_education_end_year'),
                'education_performance_scale_optional' => $this->request->getVar('add_education_performance_scale'),
                'education_performance_optional' => $this->c_trim($add_education_performance),
                'created_at' => $current_datetime,

            ];
            $insert_data = $Candidate_model->insert_commen('can_education_details', $data);
            if ($insert_data) {
                $data = ['can_profile_complete_status' => 1];
                $where_pro = array('userid' => $userid);
                $update_pro = $Candidate_model->update_commen('can_personal_details', $where_pro, $data);

                $ses_data = [
                    'candidate_education_status',
                ];
                $session->remove($ses_data);
                $session->setFlashdata('error_msg', 'Educational Details Has Been Added Successfully');
                $session->setFlashdata('error_status', '2');
                return redirect()->to('education-details');
            } else {
                return redirect()->to('education-details');
            }
        }
    }

    public function edit_can_educationa_details()
    {

        $validation =  \Config\Services::validation();
        $session = session();
        $Candidate_model = new Candidate_model();
        $userid = $this->request->getVar('userid');
        $editid = $this->request->getVar('editid');
        $where = array('status' => '1', 'id' => $editid, 'userid' => $userid);
        $isValidated = $this->validate([
            'edit_education_college_name' => ['label'  => 'College Name', 'rules'  => 'required'],
            'edit_education_course' => ['label'  => 'Course', 'rules'  => 'required'],
            'edit_education_specialization' => ['label'  => 'Specialization', 'rules'  => 'required'],
            'edit_education_start_year' => ['label'  => 'From', 'rules'  => 'required'],
            'edit_education_end_year' => ['label'  => 'Till', 'rules'  => 'required'],
        ]);
        if (!$isValidated) {
            $session->setFlashdata('error_status', '3');
            $session->setFlashdata('error_msg', $validation->getErrors());
            return redirect()->to('education-details');
        } else {
            $add_education_college_name = $this->request->getVar('edit_education_college_name');
            $where1 = array('college_name' => $add_education_college_name);
            $college_id = $Candidate_model->fetch_table_row('master_college', $where1);
            // print_r($college_id->id);exit;
            if (!empty($college_id)) {
                $edu_college = $college_id->id;
                $college_name = '';
            } else {
                $edu_college = 0;
                $college_name = $this->request->getVar('edit_education_college_name');
            }

            $add_education_course = $this->request->getVar('edit_education_course');
            $where2 = array('name' => $add_education_course);
            $courses_id = $Candidate_model->fetch_table_row('master_academic_courses', $where2);

            if (!empty($courses_id)) {
                $edu_course = $courses_id->id;
                $course_name = '';
            } else {
                $edu_course = 0;
                $course_name = $this->request->getVar('edit_education_course');
            }
            $add_education_specialization = $this->request->getVar('edit_education_specialization');
            $where3 = array('name' => $add_education_specialization);
            $specialization_id = $Candidate_model->fetch_table_row('master_academic_branch', $where3);

            if (!empty($specialization_id)) {
                $edu_specialization = $specialization_id->id;
                $specialization_name = '';
            } else {
                $edu_specialization = 0;
                $specialization_name = $this->request->getVar('edit_education_specialization');
            }
            $edit_education_performance_scale = $this->request->getVar('edit_education_performance_scale');
            if ($edit_education_performance_scale == 1) {
                $edit_education_performance = $this->request->getVar('edit_education_performance_per');
            } elseif ($edit_education_performance_scale == 2) {
                $edit_education_performance = $this->request->getVar('edit_education_performance_cgpa');
            } else {
                $edit_education_performance = '';
            }
            $data = [
                'education_college_name' => $edu_college,
                'education_college_name_other' => $college_name,
                'education_course' => $edu_course,
                'education_course_other' => $course_name,
                'education_specialization' => $edu_specialization,
                'education_specialization_other' => $specialization_name,
                'education_start_year' => $this->request->getVar('edit_education_start_year'),
                'education_end_year' => $this->request->getVar('edit_education_end_year'),
                'education_performance_scale_optional' => $this->request->getVar('edit_education_performance_scale'),
                'education_performance_optional' => $this->c_trim($edit_education_performance),

            ];
            $update_data = $Candidate_model->update_commen('can_education_details', $where, $data);
            if ($update_data) {
                $session->setFlashdata('error_msg', 'Educational Details Has Been Updated Successfully');
                $session->setFlashdata('error_status', '2');
                return redirect()->to('education-details');
            } else {
                return redirect()->to('education-details');
            }
        }
    }

    public function delete_can_educationa_details($id)
    {

        $session = session();
        $Candidate_model = new Candidate_model();
        $where = array('id' => $id);
        $data = [
            'status' => '0',
        ];
        $update_data = $Candidate_model->update_commen('can_education_details', $where, $data);
        if ($update_data) {
            $session->setFlashdata('error_msg', 'Educational Details Has Been Removed Successfully');
            $session->setFlashdata('error_status', '1');
            return redirect()->to('education-details');
        } else {
            return redirect()->to('education-details');
        }
    }

    public function get_state_by_district_can()
    {
        // $model = new Employer_model();
        $state_id = $this->request->getVar('state_id');

        $Candidate_model = new Candidate_model();
        $where = array('state_id' => $state_id, 'status' => '1');
        $profile = $Candidate_model->fetch_table_data1('master_district', $where);
        // print_r($profile);exit;
        $getdates = '';
        $dates    = '';
        foreach ($profile as $as) {
            $getdates = $getdates . "<option value='" . $as->dist_id . "' >" . $as->dist_name . "</option>";
        }
        $dates = "<select name='add_permanent_address_district' id='add_permanent_address_district' class='form-control f-14 border-left-0'>
                      <option value='' style='color:#bfbfbf;' >--Select District--</option>
                                    " . $getdates . "                           
                            </select> ";
        echo $dates . '^' . csrf_hash();
    }

    public function get_state_by_district_can_com()
    {
        // $model = new Employer_model();
        $state_id = $this->request->getVar('state_id');

        $Candidate_model = new Candidate_model();
        $where = array('state_id' => $state_id, 'status' => '1');
        $profile = $Candidate_model->fetch_table_data1('master_district', $where);
        // print_r($profile);exit;
        $getdates = '';
        $dates    = '';
        foreach ($profile as $as) {
            $getdates = $getdates . "<option value='" . $as->dist_id . "' >" . $as->dist_name . "</option>";
        }
        $dates = "<select name='add_communication_address_district' id='add_communication_address_district' class='form-control f-14 border-left-0'>
                      <option value='' style='color:#bfbfbf;' >--Select District--</option>
                                    " . $getdates . "                           
                            </select> ";
        echo $dates . '^' . csrf_hash();
    }

    public function update_can_address_details()
    {
        $validation =  \Config\Services::validation();
        $session = session();
        $Candidate_model = new Candidate_model();
        $current_datetime = $Candidate_model->current_datetime();
        $userid = $this->request->getVar('userid');
        $where = array('status' => '1', 'userid' => $userid);
        $isValidated = $this->validate([
            'add_permanent_address_line1' =>  ['label'  => 'Address Line 1', 'rules'  => 'required'],
            'add_permanent_address_state' =>  ['label'  => 'State', 'rules'  => 'required'],
            'add_permanent_address_district' => ['label'  => 'District', 'rules'  => 'required'],
            'add_permanent_address_pincode' => ['label'  => 'District', 'rules'  => 'required|numeric|max_length[6]'],
            'add_communication_address_line1' =>  ['label'  => 'Address Line 1', 'rules'  => 'required'],
            'add_communication_address_state' =>  ['label'  => 'State', 'rules'  => 'required'],
            'add_communication_address_district' => ['label'  => 'District', 'rules'  => 'required'],
            'add_communication_address_pincode' => ['label'  => 'District', 'rules'  => 'required|numeric|max_length[6]'],
        ]);
        if (!$isValidated) {
            $session->setFlashdata('error_status', '3');
            $session->setFlashdata('error_msg', $validation->getErrors());
            return redirect()->to('can-profile-address');
        } else {
            $data = [
                'userid' => $userid,
                'permanent_address_line1' => $this->request->getVar('add_permanent_address_line1'),
                'permanent_address_line2' => $this->request->getVar('add_permanent_address_line2'),
                'permanent_state' => $this->request->getVar('add_permanent_address_state'),
                'permanent_district' => $this->request->getVar('add_permanent_address_district'),
                'permanent_pincode' => $this->request->getVar('add_permanent_address_pincode'),
                'communication_address_same_permanent_address' => $this->request->getVar('add_same_as_permanent'),
                'communication_address_line1' => $this->request->getVar('add_communication_address_line1'),
                'communication_address_line2' => $this->request->getVar('add_communication_address_line2'),
                'communication_state' => $this->request->getVar('add_communication_address_state'),
                'communication_district' => $this->request->getVar('add_communication_address_district'),
                'communication_pincode' => $this->request->getVar('add_communication_address_pincode'),
                'created_at' => $current_datetime,

            ];
            $available_data = $Candidate_model->fetch_table_data('can_address_details', $where);
            if ($available_data) {
                $update_data = $Candidate_model->update_commen('can_address_details', $where, $data);
            } else {
                $update_data = $Candidate_model->insert_commen('can_address_details', $data);
            }

            if ($update_data) {

                $session->setFlashdata('error_msg', 'Address Details Has Been Updated Successfully');
                $session->setFlashdata('error_status', '2');
                return redirect()->to('experience-details');
            } else {
                return redirect()->to('experience-details');
            }
        }
    }

    public function update_can_work_sample()
    {
        $validation =  \Config\Services::validation();
        $session = session();
        $Candidate_model = new Candidate_model();
        $current_datetime = $Candidate_model->current_datetime();
        $userid = $this->request->getVar('userid');
        $where = array('status' => '1', 'userid' => $userid);
        $profile_complete_status = $session->get('profile_complete_status');
        $intership_id = $session->get('intership_number');
        $profile_page_view = $session->get('profile_page_view');
        // $isValidated = $this->validate([
        //     'add_blog_link' => ['label'  => 'Blog link', 'rules'  => 'valid_url'],
        //     'add_github' => ['label'  => 'GitHub profile', 'rules'  => 'valid_url'],
        //     'add_play_store' =>['label'  => 'Play store developer A/c ', 'rules'  => 'valid_url'],
        //     'add_behance_portfolio' => ['label'  => 'Behance portfolio link', 'rules'  => 'valid_url'],
        //     'add_other_work_sample' =>  ['label'  => 'Other work sample link', 'rules'  => 'valid_url'],
        // ]);
        // if (!$isValidated) {
        //     $session->setFlashdata('error_status', '1');
        //     $session->setFlashdata('error_msg', $validation->getErrors());
        //     return redirect()->to('work-sample-details');
        // }
        // else{
        $data = [
            'userid' => $userid,
            'blog_link' => $this->c_trim($this->request->getVar('add_blog_link')),
            'github_profile' => $this->c_trim($this->request->getVar('add_github')),
            'play_store_developer' => $this->c_trim($this->request->getVar('add_play_store')),
            'behance_portfolio_link' => $this->c_trim($this->request->getVar('add_behance_portfolio')),
            'kaggle_link' => $this->c_trim($this->request->getVar('add_kaggle_link')),
            'other_work_sample_link' => $this->c_trim($this->request->getVar('add_other_work_sample')),
            'additional_details' => $this->c_trim($this->request->getVar('add_additional_details')),
            'created_at' => $current_datetime,

        ];
        $available_data = $Candidate_model->fetch_table_data('can_work_sample', $where);
        if ($available_data) {
            $update_data = $Candidate_model->update_commen('can_work_sample', $where, $data);
        } else {
            $update_data = $Candidate_model->insert_commen('can_work_sample', $data);
        }

        if ($update_data) {
            $session->setFlashdata('error_msg', 'Work Sample Details Has Been Updated Successfully');
            $session->setFlashdata('error_status', '2');
            if (isset($profile_complete_status) && ($profile_complete_status == 1)) {
                $ses_data = [
                    'profile_complete_status',
                    'company_logo',
                    'company_name',
                    'intership_profile',
                    'intership_number',
                    'edit_profile'
                ];
                $session->remove($ses_data);
                return redirect()->to('can-apply-for-internship/' . $intership_id);
            } elseif (isset($profile_page_view) && ($profile_page_view == 1)) {
                return redirect()->to('profile-details');
            } else {
                return redirect()->to('profile-details');
            }
        } else {
            if (isset($profile_complete_status) && ($profile_complete_status == 1)) {
                return redirect()->to('can-apply-for-internship/' . $intership_id);
            } elseif (isset($profile_page_view) && ($profile_page_view == 1)) {
                return redirect()->to('profile-details');
            } else {
                return redirect()->to('profile-details');
            }
        }
        // }
    }

    public function add_can_experience()
    {
        // print_r($_POST);exit;
        $validation =  \Config\Services::validation();
        $session = session();
        $Candidate_model = new Candidate_model();
        $current_datetime = $Candidate_model->current_datetime();
        $userid = $this->request->getVar('userid');
        $experience_type = $this->request->getVar('experience_type');
        if ($experience_type == '3') {
            $add_experience_organization_online = $this->request->getVar('add_experience_organization_online3_value');
            $add_training_duration = $this->request->getVar('add_training_duration');
            $add_training_duration_type = $this->request->getVar('add_training_duration_type');
        } else {
            $add_experience_organization_online = '';
            $add_training_duration = '';
            $add_training_duration_type = '';
        }
        if ($experience_type == '4' || $experience_type == '1') {
            $add_experience_project_link = $this->request->getVar('add_experience_project_link');
            $add_experience_currently_working = '';
        } else {
            $add_experience_project_link = '';
            $add_experience_currently_working = $this->request->getVar('add_experience_currently_working');
            $isValidated = $this->validate([
                'add_experience_organization' => ['label'  => 'Organization', 'rules'  => 'required'],
                // 'internship_location_id' =>['label'  => 'Location', 'rules'  => 'required'],

            ]);
        }
        $isValidated = $this->validate([
            'add_experience_profile' => ['label'  => 'Profile', 'rules'  => 'required'],
        ]);
        if (!$isValidated) {
            $session->setFlashdata('error_status', '3');
            $session->setFlashdata('error_msg', $validation->getErrors());
            return redirect()->to('experience-details');
        } else {

            if ($experience_type == '1' || $experience_type == '2') {
                $add_experience_profile = $this->request->getVar('add_experience_profile');
                $where1 = array('profile' => $add_experience_profile);
                $profile_id = $Candidate_model->fetch_table_row('master_profile', $where1);

                if (!empty($profile_id)) {
                    $profile_name = $profile_id->id;
                    $profile_other = '';
                } else {
                    $profile_name = 0;
                    $profile_other = $this->c_trim($this->request->getVar('add_experience_profile'));
                }
            } else {
                $profile_name =  $this->c_trim($this->request->getVar('add_experience_profile'));
                $profile_other = '';
            }


            $data = [
                'userid' => $userid,
                'experience_type' => $experience_type,
                'project_title' => $profile_name,
                'profile_other' => $profile_other,
                'project_organization' => $this->c_trim($this->request->getVar('add_experience_organization')),
                'project_organization_online' => $add_experience_organization_online,
                // 'g_location_name' => $this->request->getVar('internship_location_name'),
                // 'g_location_id' => $this->request->getVar('internship_location_id'),
                'project_start_year' => $this->request->getVar('add_experience_start_year'),
                'project_end_year' => $this->request->getVar('add_experience_end_year'),
                'project_currently_ongoing' => $add_experience_currently_working,
                'project_description' => $this->c_trim($this->request->getVar('add_experience_description')),
                'project_link' => $add_experience_project_link,
                'project_duration' => $add_training_duration,
                'project_duration_type' => $add_training_duration_type,
                'created_at' => $current_datetime,

            ];
            $insert_data = $Candidate_model->insert_commen('can_experience_details', $data);
            if ($insert_data) {
                $session->setFlashdata('error_msg', 'Experience Details Has Been Added Successfully');
                $session->setFlashdata('error_status', '2');
                return redirect()->to('experience-details');
            } else {
                return redirect()->to('experience-details');
            }
        }
    }

    public function edit_can_experience()
    {
        // print_r($_POST);exit;
        $validation =  \Config\Services::validation();
        $session = session();
        $Candidate_model = new Candidate_model();
        $userid = $this->request->getVar('userid');
        $editid = $this->request->getVar('editid');
        $where = array('status' => '1', 'id' => $editid, 'userid' => $userid);
        $experience_type = $this->request->getVar('experience_type');
        if ($experience_type == '3') {
            $edit_experience_organization_online = $this->request->getVar('edit_experience_organization_online3_value');
            $edit_training_duration = $this->request->getVar('edit_training_duration');
            $edit_training_duration_type = $this->request->getVar('edit_training_duration_type');
        } else {
            $edit_experience_organization_online = '0';
            $edit_training_duration = '';
            $edit_training_duration_type = '';
        }
        if ($experience_type == '4' || $experience_type == '1') {
            $edit_experience_project_link = $this->request->getVar('edit_experience_project_link');
            $edit_experience_currently_working = '';
        } else {
            $edit_experience_project_link = '';
            $edit_experience_currently_working = $this->request->getVar('edit_experience_currently_working');
            $isValidated = $this->validate([
                'edit_experience_organization' => ['label'  => 'Organization', 'rules'  => 'required'],
                //'edit_experience_location' => ['label'  => 'Location', 'rules'  => 'required'],

            ]);
        }
        $isValidated = $this->validate([
            'edit_experience_profile' => ['label'  => 'Profile', 'rules'  => 'required'],
        ]);
        if (!$isValidated) {
            $session->setFlashdata('error_status', '3');
            $session->setFlashdata('error_msg', $validation->getErrors());
            return redirect()->to('experience-details');
        } else {
            if ($experience_type == '1' || $experience_type == '2') {
                $edit_experience_profile = $this->request->getVar('edit_experience_profile');
                $where1 = array('profile' => $edit_experience_profile);
                $profile_id = $Candidate_model->fetch_table_row('master_profile', $where1);

                if (!empty($profile_id)) {
                    $profile_name = $profile_id->id;
                    $profile_other = '';
                } else {
                    $profile_name = 0;
                    $profile_other = $this->c_trim($this->request->getVar('edit_experience_profile'));
                }
            } else {
                $profile_name =  $this->c_trim($this->request->getVar('edit_experience_profile'));
                $profile_other = '';
            }

            $data = [
                'project_title' => $profile_name,
                'profile_other' => $profile_other,
                'project_organization' => $this->c_trim($this->request->getVar('edit_experience_organization')),
                'project_organization_online' => $edit_experience_organization_online,
                //'project_location' => $this->request->getVar('edit_experience_location'),
                'project_start_year' => $this->request->getVar('edit_experience_start_year'),
                'project_end_year' => $this->request->getVar('edit_experience_end_year'),
                'project_currently_ongoing' => $edit_experience_currently_working,
                'project_description' => $this->c_trim($this->request->getVar('edit_experience_description')),
                'project_link' => $edit_experience_project_link,
                'project_duration' => $edit_training_duration,
                'project_duration_type' => $edit_training_duration_type,

            ];
            $update_data = $Candidate_model->update_commen('can_experience_details', $where, $data);
            if ($update_data) {
                $session->setFlashdata('error_msg', 'Experience Details Has Been Updated Successfully');
                $session->setFlashdata('error_status', '2');
                return redirect()->to('experience-details');
            } else {
                return redirect()->to('experience-details');
            }
        }
    }

    public function add_can_skills()
    {
        $validation =  \Config\Services::validation();
        $session = session();
        $Candidate_model = new Candidate_model();
        $current_datetime = $Candidate_model->current_datetime();
        $userid = $this->request->getVar('userid');
        $isValidated = $this->validate([
            'add_skill' => ['label'  => 'Skill', 'rules'  => 'required'],
            'add_skill_level' => ['label'  => 'Level', 'rules'  => 'required'],
        ]);
        if (!$isValidated) {
            $session->setFlashdata('error_status', '3');
            $session->setFlashdata('error_msg', $validation->getErrors());
            return redirect()->to('skill-details');
        } else {
            $data = [
                'userid' => $userid,
                'skills' => $this->request->getVar('add_skill'),
                'skill_level' => $this->request->getVar('add_skill_level'),
                'created_at' => $current_datetime,

            ];
            $insert_data = $Candidate_model->insert_commen('can_skills_details', $data);
            if ($insert_data) {
                $session->setFlashdata('error_msg', 'Skills Added Successfully');
                $session->setFlashdata('error_status', '2');
                return redirect()->to('skill-details');
            } else {
                return redirect()->to('skill-details');
            }
        }
    }

    public function get_spec_by_courses()
    {
        // $model = new Employer_model();
        $courses = $this->request->getVar('courses');

        $Candidate_model = new Candidate_model();
        $where = array('course_id' => $courses, 'status' => '1');
        $profile = $Candidate_model->fetch_table_data('master_academic_branch', $where);
        $getdates = '';
        $dates    = '';
        foreach ($profile as $as) {
            $getdates = $getdates . "<option value='" . $as->id . "' >" . $as->name . "</option>";
        }
        $dates = "<select name='add_education_specialization' id='add_education_specialization' class=' filledBox form-control f-14 border-0 mb-4'>
                      <option value='' style='color:#bfbfbf;' >--Select Specialization--</option>
                                    " . $getdates . "                           
                            </select> ";
        echo $dates . '^' . csrf_hash();
    }

    public function get_spec_by_courses_edit()
    {
        // $model = new Employer_model();
        $courses = $this->request->getVar('courses');

        $Candidate_model = new Candidate_model();
        $where = array('course_id' => $courses, 'status' => '1');
        $profile = $Candidate_model->fetch_table_data('master_academic_branch', $where);
        $getdates = '';
        $dates    = '';
        foreach ($profile as $as) {
            $getdates = $getdates . "<option value='" . $as->id . "' >" . $as->name . "</option>";
        }
        $dates = "<select name='edit_education_specialization' id='edit_education_specialization' class='form-control f-14 border-left-0'>
                      <option value='' style='color:#bfbfbf;' >--Select Specialization--</option>
                                    " . $getdates . "                           
                            </select> ";
        echo $dates . '^' . csrf_hash();
    }

    public function can_apply_for_internship($internship_id)
    {
        $session = session();

        //         $previousURL = service('request')->getServer('HTTP_REFERER');
        //         $uri = current_url(true);
        //         print_r($previousURL);
        // // Get the last segment
        //         $lastSegment = $uri->getSegment($uri->getTotalSegments());
        //         print_r($lastSegment);
        $internshipValue    =    $session->get('internshipValue');
        // $sessionValue = session()->get('internshipValue');
        // print_r($internshipValue);exit;

        if (!empty($internshipValue) && $internshipValue == $internship_id) {


            $Candidate_model = new Candidate_model();
            $userid    =    $session->get('userid');
            $where = array('status' => '1', 'userid' => $userid);
            $data['profile_personal'] = $Candidate_model->fetch_table_row('can_personal_details', $where);
            $data['education_details'] = $Candidate_model->fetch_table_data('can_education_details', $where);
            $data['address_details'] = $Candidate_model->fetch_table_row('can_address_details', $where);
            $data['experience_details'] = $Candidate_model->fetch_table_data('can_experience_details', $where);
            $data['skill_details'] = $Candidate_model->fetch_table_data('can_skills_details', $where);
            $data['work_sample'] = $Candidate_model->fetch_table_row('can_work_sample', $where);
            $where_int = array('status' => '1', 'internship_id' => $internship_id);
            $data['internship_details'] = $Candidate_model->fetch_table_row('employer_post_internship', $where_int);
            $internship_details = $data['internship_details'];
            $where_emp = array('status' => '1', 'userid' => $internship_details->company_id);
            $data['emp_profile_details'] = $Candidate_model->fetch_table_row('profile_completion_form', $where_emp);
            $where_apply = array('status' => '1', 'internship_id' => $internship_id, 'candidate_id' => $userid);
            $data['apply_internship_details'] = $Candidate_model->fetch_table_row('can_applied_internship', $where_apply);
            return view('Candidate/can_apply_for_intern', $data);
        } else {
            return redirect()->to('internship-details/' . $internship_id);
        }
    }

    public function can_proceeds_apply($internship_id)
    {
        $session = session();
        $Candidate_model = new Candidate_model();
        $userid    =    $session->get('userid');
        $where_int = array('status' => '1', 'internship_id' => $internship_id);
        $data['internship_details'] = $Candidate_model->fetch_table_row('employer_post_internship', $where_int);
        $internship_details = $data['internship_details'];
        $where_emp = array('status' => '1', 'userid' => $internship_details->company_id);
        $data['emp_profile_details'] = $Candidate_model->fetch_table_row('profile_completion_form', $where_emp);
        return view('Candidate/can-apply-for-internship', $data);
    }

    public function can_apply_internship()
    {

        $session = session();
        $Candidate_model = new Candidate_model();
        $current_datetime = $Candidate_model->current_datetime();
        $userid = $this->request->getVar('userid');
        $internship_id = $this->request->getVar('internship_id');
        // $input = $this->validate([
        //     'add_hired_role_reason' => 'required',
        //     'add_availablity' => 'required',
        // ]);
        // if (!$input) {
        //     return redirect()->to('can-apply-for-internship/'.$internship_id);
        // }
        // else{

        //check first applay            
        $first_appay = $Candidate_model->check_first_intership($userid);
        if (empty($first_appay)) {
            //get candidate data           
            $candidate_data = $Candidate_model->get_candidate_data($userid);
            if (!empty($candidate_data)) {
                if ($candidate_data[0]["profile_email"] != '') {
                    // gmail send email
                    // $msg_data['msg_data'] = array('name' => $session->get('name')); //dynamic contents for template
                    // $message     = view('email_template/candidate_first_application', $msg_data);
                    $current_year = date('Y');
                    $message = '{ "name" : "' . $candidate_data[0]["profile_full_name"] . '","year" : "' . $current_year . '"  }'; //dynamic contents for template
                    $subject      = 'Internme - First Application';
                    $to_email     =  $candidate_data[0]["profile_email"];
                    $from_content = 'Internme - First Application';
                    $template_key = '2d6f.456f260c51ab9602.k1.5dc62780-a76b-11ed-bfa0-525400fcd3f1.1862f5781f8';
                    $this->email_send($message, $subject, $to_email, $from_content, $template_key);
                    // $this->email_send($message, $subject, $to_email, $from_content);
                }
            }
        }
        $order_by_loc = array('ordercolumn' => 'g_location_name', 'ordertype' => 'asc');
        $where_loc = array('status' => '1', 'internship_id' => $internship_id);
        $can_work_location = $Candidate_model->fetch_table_data_for_all('emp_worklocation_multiple', $where_loc, $order_by_loc);
        $work_location = '';
        $work_location_name = '';
        if (isset($can_work_location) && !empty($can_work_location)) {
            if (count($can_work_location) == '1') {
                $work_location = $can_work_location[0]->g_location_id;
                $work_location_name = $can_work_location[0]->g_location_name;
            }
        }


        $data = [
            'candidate_id' => $userid,
            'internship_id' => $this->request->getVar('internship_id'),
            'created_at' => $current_datetime,
            'work_location_name' => $work_location_name,
            'work_location' => $work_location,
        ];
        $insert_data = $Candidate_model->insert_commen('can_applied_internship', $data);
        $ses_data = [
            'profile_complete_status',
            'company_logo',
            'company_name',
            'intership_profile',
            'intership_number',
            'edit_profile'
        ];
        if ($insert_data) {
            $session->set('complete_popup', 1);
            session()->setTempdata('success', 'Added Successfully', 2);
            $session->remove($ses_data);

            return redirect()->to('can-apply-for-internship/' . $internship_id);
        } else {
            $session->remove($ses_data);
            return redirect()->to('can-apply-for-internship/' . $internship_id);
        }
        // }
    }

    public function can_applied_intern_list($type = NULL)
    {
        $session = session();
        $Candidate_model = new Candidate_model();
        $userid    =    $session->get('userid');
        $current_date = date("Y-m-d");
        $where = array('candidate_id' => $userid, 'application_type' => 0, 'offer_viewed_status' => 1);
        $data = ['offer_viewed_status' => 2];
        $update_application_status = $Candidate_model->update_commen('can_applied_internship', $where, $data);
        $order_by = array('ordercolumn' => 'id', 'ordertype' => 'desc');
        if (!empty($type)) {
            if ($type == 8) {
                $ses_data = [
                    'active_sort_my_application' => 8,
                ];
                $session->set($ses_data);
                $where = array('status' => '1', 'candidate_id' => $userid, 'application_type' => '0', 'application_status' => '3');
                // $where = "status = '1' AND candidate_id = $userid AND application_status='3'";
            } else if ($type == 7) {
                $ses_data = [
                    'active_sort_my_application' => 7,
                ];
                $session->set($ses_data);
                // $where = array('status' => '1', 'candidate_id' => $userid, 'application_type' => '0', 'application_status' => '2', 'complete_status' => '0', 'hiring_status' => '0');
                $order_by = array('ordercolumn' => 'can_applied_internship.id', 'ordertype' => 'desc');
                $where = "can_applied_internship.status = '1' AND can_applied_internship.candidate_id = $userid AND can_applied_internship.application_type = '0' AND can_applied_internship.application_status = '2' AND can_applied_internship.hiring_status='0' AND can_applied_internship.complete_status='0'  AND employer_post_internship.internship_startdate>='$current_date'";
            } else if ($type == 10) {
                $ses_data = [
                    'active_sort_my_application' => 10,
                ];
                $session->set($ses_data);
                //$where = array('status' => '1', 'candidate_id' => $userid, 'application_type' => '0', 'application_status' => '2', 'complete_status' => '0', 'hiring_status' => '0');
                $order_by = array('ordercolumn' => 'can_applied_internship.id', 'ordertype' => 'desc');
                $where = "can_applied_internship.status = '1' AND can_applied_internship.candidate_id = $userid AND can_applied_internship.application_type = '0' AND can_applied_internship.application_status = '2' AND (((can_applied_internship.hiring_status='0') AND (can_applied_internship.complete_status='0')) OR ((can_applied_internship.hiring_status='0') AND (employer_post_internship.internship_startdate<='$current_date')))";
            } else if ($type == 2) {
                $ses_data = [
                    'active_sort_my_application' => 2,
                ];
                $session->set($ses_data);
                $order_by = array('ordercolumn' => 'can_applied_internship.id', 'ordertype' => 'desc');
                $where = array('can_applied_internship.status' => '1', 'can_applied_internship.candidate_id' => $userid, 'can_applied_internship.application_type' => '0', 'can_applied_internship.hiring_status' => '1', 'employer_post_internship.internship_startdate >=' => $current_date);

                // $where = array('status' => '1', 'candidate_id' => $userid, 'application_type' =>'0', 'hiring_status' =>'1');
            } else if ($type == 3) {
                $ses_data = [
                    'active_sort_my_application' => 3,
                ];
                $session->set($ses_data);
                $order_by = array('ordercolumn' => 'can_applied_internship.id', 'ordertype' => 'desc');
                // $where = array('status' => '1', 'candidate_id' => $userid, 'application_type' => '0', 'hiring_status' => '2');
                $where = "can_applied_internship.status = '1' AND can_applied_internship.candidate_id = $userid AND can_applied_internship.application_type = '0' AND (((can_applied_internship.hiring_status='2')) OR ((can_applied_internship.hiring_status='4') AND (can_applied_internship.application_status='2') AND (employer_post_internship.internship_startdate<='$current_date')))";
            } else if ($type == 4) {
                $ses_data = [
                    'active_sort_my_application' => 4,
                ];
                $session->set($ses_data);
                $where = array('status' => '1', 'candidate_id' => $userid, 'application_type' => '0', 'complete_status' => '1', 'complete_type!=' => '1');
                // $where = array('can_applied_internship.status' => '1', 'can_applied_internship.candidate_id' => $userid, 'can_applied_internship.application_type' => '0', 'can_applied_internship.hiring_status' => '4', 'employer_post_internship.internship_startdate <=' => $current_date);

            } else if ($type == 5) {
                $ses_data = [
                    'active_sort_my_application' => 5,
                ];
                $session->set($ses_data);
                $where = array('status' => '1', 'candidate_id' => $userid, 'application_type' => '0', 'complete_status' => '1', 'complete_type' => '1');
            } else if ($type == 6) {
                $ses_data = [
                    'active_sort_my_application' => 6,
                ];
                $session->set($ses_data);
                // $where = array('status' => '1', 'candidate_id' => $userid, 'application_type' =>'0', 'application_status' =>'1', 'complete_type' =>'1');
                $where = "status = '1' AND candidate_id = $userid AND application_type = '0' AND ((application_status='1') OR (application_status='0'))";
            } else if ($type == 9) {
                $ses_data = [
                    'active_sort_my_application' => 9,
                ];
                $session->set($ses_data);
                $order_by = array('ordercolumn' => 'can_applied_internship.id', 'ordertype' => 'desc');
                $where = array('can_applied_internship.status' => '1', 'can_applied_internship.candidate_id' => $userid, 'can_applied_internship.application_type' => '0', 'can_applied_internship.hiring_status' => '1', 'employer_post_internship.internship_startdate <=' => $current_date);
            } else if ($type == 11) {
                $ses_data = [
                    'active_sort_my_application' => 11,
                ];
                $session->set($ses_data);
                $order_by = array('ordercolumn' => 'can_applied_internship.id', 'ordertype' => 'desc');
                $where = array('can_applied_internship.status' => '1', 'can_applied_internship.candidate_id' => $userid, 'can_applied_internship.application_type' => '0', 'can_applied_internship.hiring_status' => '4', 'employer_post_internship.internship_startdate >=' => $current_date);
            } else {
                $ses_data = [
                    'active_sort_my_application' => 1,
                ];
                $session->set($ses_data);
                $where = array('status' => '1', 'candidate_id' => $userid, 'application_type' => '0');
            }
        } else {
            $ses_data = [
                'active_sort_my_application' => 1,
            ];
            $session->set($ses_data);
            $where = array('status' => '1', 'candidate_id' => $userid, 'application_type' => '0');
        }
        if ($type == 9 || $type == 2 || $type == 11 || $type == 10 || $type == 3 || $type == 7) {
            $data['applied_internship_list'] = $Candidate_model->fetch_table_data_ongoing('can_applied_internship', $where, $order_by);
        } else {
            $data['applied_internship_list'] = $Candidate_model->fetch_table_data_for_all('can_applied_internship', $where, $order_by);
        }
        $order_by_reason = array('ordercolumn' => 'order', 'ordertype' => 'asc');
        $where = array('status' => '1');
        $data['complete_reason'] = $Candidate_model->fetch_table_data_for_all('master_complete_reason', $where, $order_by_reason);
        $where_status = array('status' => '1', 'userid' => $userid);
        $data['personal_details'] = $Candidate_model->fetch_table_row('can_personal_details', $where_status);

        $order_by_off = array('ordercolumn' => 'id', 'ordertype' => 'desc');
        $where_off = array('status' => '1', 'candidate_id' => $userid, 'application_type' => '1', 'application_status' => '2');
        $data['offer_count_applied_internship_list'] = $Candidate_model->fetch_table_data_for_all('can_applied_internship', $where_off, $order_by_off);

        $order_by_myint = array('ordercolumn' => 'hiring_date', 'ordertype' => 'desc');
        $where_myint = "status = '1' AND candidate_id = $userid AND (hiring_status='1' OR hiring_status='3')";
        // $where_myint = "status = '1' AND candidate_id = $userid AND  application_status ='2' AND ((hiring_status='1') OR (hiring_status='3' AND complete_type='1'))";

        $data['my_count_applied_internship_list'] = $Candidate_model->fetch_table_data_for_all('can_applied_internship', $where_myint, $order_by_myint);

        return view('Candidate/can_applied_intern_list', $data);
    }
    public function can_offered_intern_list($type = NULL)
    {
        $session = session();
        $Candidate_model = new Candidate_model();
        $userid    =    $session->get('userid');
        $current_date = date("Y-m-d");
        $where = array('candidate_id' => $userid, 'application_type' => 1, 'offer_viewed_status' => 1);
        $data = ['offer_viewed_status' => 2];
        $update_application_status = $Candidate_model->update_commen('can_applied_internship', $where, $data);
        $order_by = array('ordercolumn' => 'id', 'ordertype' => 'desc');
        if (!empty($type)) {
            if ($type == 7) {
                $ses_data = [
                    'active_sort_my_offers' => 7,
                ];
                $session->set($ses_data);
                $where = array('status' => '1', 'candidate_id' => $userid, 'application_type' => '1', 'application_status' => '2', 'complete_status' => '0', 'hiring_status' => '0');
            } else if ($type == 10) {
                $ses_data = [
                    'active_sort_my_offers' => 10,
                ];
                $session->set($ses_data);
                // $where = array('status' => '1', 'candidate_id' => $userid, 'application_type' => '1', 'application_status' => '2', 'complete_status' => '0', 'hiring_status' => '0');
                $order_by = array('ordercolumn' => 'can_applied_internship.id', 'ordertype' => 'desc');
                $where = "can_applied_internship.status = '1' AND can_applied_internship.candidate_id = $userid AND can_applied_internship.application_type = '1' AND can_applied_internship.application_status = '2' AND (((can_applied_internship.hiring_status='0') AND (can_applied_internship.complete_status='0')) OR ((can_applied_internship.hiring_status='0') AND (employer_post_internship.internship_startdate<='$current_date')))";
            } else if ($type == 2) {
                $ses_data = [
                    'active_sort_my_offers' => 2,
                ];
                $session->set($ses_data);
                // $order_by = array('ordercolumn' => 'can_applied_internship.id', 'ordertype' => 'desc');
                // $where = array('can_applied_internship.status' => '1', 'can_applied_internship.candidate_id' => $userid, 'can_applied_internship.application_type' => '1', 'can_applied_internship.application_status' => '2', 'can_applied_internship.hiring_status' => '1', 'employer_post_internship.internship_startdate <=' => $current_date);

                $order_by = array('ordercolumn' => 'can_applied_internship.id', 'ordertype' => 'desc');
                $where = array('can_applied_internship.status' => '1', 'can_applied_internship.candidate_id' => $userid, 'can_applied_internship.application_type' => '1', 'can_applied_internship.hiring_status' => '1', 'employer_post_internship.internship_startdate >=' => $current_date);
                // $where = array('status' => '1', 'candidate_id' => $userid, 'application_type' =>'1', 'application_status' =>'2', 'hiring_status' =>'1');
            } else if ($type == 3) {
                $ses_data = [
                    'active_sort_my_offers' => 3,
                ];
                $session->set($ses_data);
                // $where = array('status' => '1', 'candidate_id' => $userid, 'application_type' => '1', 'application_status' => '2', 'hiring_status' => '2');
                $order_by = array('ordercolumn' => 'can_applied_internship.id', 'ordertype' => 'desc');
                // $where = array('status' => '1', 'candidate_id' => $userid, 'application_type' => '0', 'hiring_status' => '2');
                $where = "can_applied_internship.status = '1' AND can_applied_internship.candidate_id = $userid AND can_applied_internship.application_type = '1' AND (((can_applied_internship.hiring_status='2')) OR ((can_applied_internship.hiring_status='4') AND (can_applied_internship.application_status='2') AND (employer_post_internship.internship_startdate<='$current_date')))";
            } else if ($type == 4) {
                $ses_data = [
                    'active_sort_my_offers' => 4,
                ];
                $session->set($ses_data);
                $where = array('status' => '1', 'candidate_id' => $userid, 'application_type' => '1', 'application_status' => '2', 'complete_status' => '1', 'complete_type!=' => '1');
            } else if ($type == 5) {
                $ses_data = [
                    'active_sort_my_offers' => 5,
                ];
                $session->set($ses_data);
                $where = array('status' => '1', 'candidate_id' => $userid, 'application_type' => '1', 'application_status' => '2', 'complete_status' => '1', 'complete_type' => '1');
            } else if ($type == 9) {
                $ses_data = [
                    'active_sort_my_offers' => 9,
                ];
                $session->set($ses_data);
                // $order_by = array('ordercolumn' => 'can_applied_internship.id', 'ordertype' => 'desc');
                // // $where = array('status' => '1', 'candidate_id' => $userid, 'application_type' =>'1', 'application_status' =>'2', 'hiring_status' =>'1');
                // $where = array('can_applied_internship.status' => '1', 'can_applied_internship.candidate_id' => $userid, 'can_applied_internship.application_type' => '2', 'can_applied_internship.application_status' => '2', 'can_applied_internship.hiring_status' => '1', 'employer_post_internship.internship_startdate >=' => $current_date);

                $order_by = array('ordercolumn' => 'can_applied_internship.id', 'ordertype' => 'desc');
                $where = array('can_applied_internship.status' => '1', 'can_applied_internship.candidate_id' => $userid, 'can_applied_internship.application_type' => '1', 'can_applied_internship.hiring_status' => '1', 'employer_post_internship.internship_startdate <=' => $current_date);
            } else if ($type == 11) {
                $ses_data = [
                    'active_sort_my_offers' => 11,
                ];
                $session->set($ses_data);
                $order_by = array('ordercolumn' => 'can_applied_internship.id', 'ordertype' => 'desc');
                $where = array('can_applied_internship.status' => '1', 'can_applied_internship.candidate_id' => $userid, 'can_applied_internship.application_type' => '1', 'can_applied_internship.hiring_status' => '4', 'employer_post_internship.internship_startdate >=' => $current_date);
            } else {
                $ses_data = [
                    'active_sort_my_offers' => 1,
                ];
                $session->set($ses_data);
                $where = array('status' => '1', 'candidate_id' => $userid, 'application_type' => '1', 'application_status' => '2');
            }
        } else {
            $ses_data = [
                'active_sort_my_offers' => 1,
            ];
            $session->set($ses_data);
            $where = array('status' => '1', 'candidate_id' => $userid, 'application_type' => '1', 'application_status' => '2');
        }
        if ($type == 9 || $type == 2 || $type == 11 || $type == 10 || $type == 3) {
            $data['applied_internship_list'] = $Candidate_model->fetch_table_data_ongoing('can_applied_internship', $where, $order_by);
        } else {
            $data['applied_internship_list'] = $Candidate_model->fetch_table_data_for_all('can_applied_internship', $where, $order_by);
        }
        $where_status = array('status' => '1', 'userid' => $userid);
        $data_status = [
            'can_offer_status' => 0
        ];
        $order_by_reason = array('ordercolumn' => 'order', 'ordertype' => 'asc');
        $where = array('status' => '1');
        $data['complete_reason'] = $Candidate_model->fetch_table_data_for_all('master_complete_reason', $where, $order_by_reason);
        $update_status = $Candidate_model->update_commen('can_personal_details', $where_status, $data_status);
        $where_status = array('status' => '1', 'userid' => $userid);
        $data['personal_details'] = $Candidate_model->fetch_table_row('can_personal_details', $where_status);

        $order_by_apply = array('ordercolumn' => 'id', 'ordertype' => 'desc');
        // $where = array('status' => '1', 'candidate_id' => $userid, 'application_type' =>'0');
        $where_apply = array('status' => '1', 'candidate_id' => $userid, 'application_type' => '0');
        $data['application_count_applied_internship_list'] = $Candidate_model->fetch_table_data_for_all('can_applied_internship', $where_apply, $order_by_apply);

        $order_by_myint = array('ordercolumn' => 'hiring_date', 'ordertype' => 'desc');
        // $where_myint = "status = '1' AND candidate_id = $userid AND (hiring_status='1' OR hiring_status='3')";
        $where_myint = "status = '1' AND candidate_id = $userid AND (hiring_status='1' OR hiring_status='3')";
        // $where_myint = "status = '1' AND candidate_id = $userid AND  application_status ='2' AND ((hiring_status='1') OR (hiring_status='3' AND complete_type='1'))";
        $data['my_count_applied_internship_list'] = $Candidate_model->fetch_table_data_for_all('can_applied_internship', $where_myint, $order_by_myint);
        return view('Candidate/can_offered_intern_list', $data);
    }
    public function can_my_intern_list($type = NULL)
    {
        $session = session();
        $Candidate_model = new Candidate_model();
        $userid    =    $session->get('userid');
        $current_date = date("Y-m-d");
        $order_by = array('ordercolumn' => 'hiring_date', 'ordertype' => 'desc');
        if (!empty($type)) {
            if ($type == 7) {
                $ses_data = [
                    'active_sort_my_intern' => 7,
                ];
                $session->set($ses_data);

                //    $where = "status = '1' AND candidate_id = $userid AND application_status = '2' AND complete_status='0' AND  hiring_status='1'";
                $order_by = array('ordercolumn' => 'can_applied_internship.id', 'ordertype' => 'desc');
                $where = array('can_applied_internship.status' => '1', 'can_applied_internship.candidate_id' => $userid, 'can_applied_internship.complete_status' => '0', 'can_applied_internship.application_status' => '2', 'can_applied_internship.hiring_status' => '1', 'employer_post_internship.internship_startdate >=' => $current_date);
            } else if ($type == 5) {
                $ses_data = [
                    'active_sort_my_intern' => 5,
                ];
                $session->set($ses_data);
                $where = "status = '1' AND candidate_id = $userid AND application_status = '2' AND complete_status='1' AND complete_type='1'";
                //    $where = array('status' => '1', 'candidate_id' => $userid, 'application_type' =>'1', 'application_status' =>'2', 'complete_status' =>'1', 'complete_type' =>'1');
            } else if ($type == 9) {
                $ses_data = [
                    'active_sort_my_intern' => 9,
                ];
                $session->set($ses_data);
                $order_by = array('ordercolumn' => 'can_applied_internship.id', 'ordertype' => 'desc');
                // $where = "status = '1' AND candidate_id = $userid AND application_status = '2' AND complete_status='0' AND  hiring_status='1'";
                $where = array('can_applied_internship.status' => '1', 'can_applied_internship.candidate_id' => $userid, 'can_applied_internship.complete_status' => '0', 'can_applied_internship.application_status' => '2', 'can_applied_internship.hiring_status' => '1', 'employer_post_internship.internship_startdate <=' => $current_date);
            } else if ($type == 4) {
                $ses_data = [
                    'active_sort_my_intern' => 4,
                ];
                $session->set($ses_data);
                $where = array('status' => '1', 'candidate_id' => $userid, 'application_status' => '2', 'complete_status' => '1', 'complete_type!=' => '1');
            } else {
                $ses_data = [
                    'active_sort_my_intern' => 1,
                ];
                $session->set($ses_data);
                $where = "status = '1' AND candidate_id = $userid AND (hiring_status='1' OR hiring_status='3')";

                //$where = "status = '1' AND candidate_id = $userid AND  application_status ='2' AND ((hiring_status='1') OR (hiring_status='3' AND complete_type='1'))";
            }
        } else {
            $ses_data = [
                'active_sort_my_intern' => 1,
            ];
            $session->set($ses_data);
            $where = "status = '1' AND candidate_id = $userid AND (hiring_status='1' OR hiring_status='3')";

            //$where = "status = '1' AND candidate_id = $userid AND  application_status ='2' AND ((hiring_status='1') OR (hiring_status='3'))";
        }

        // $where = array('status' => '1', 'candidate_id' => $userid,$whereor);
        if ($type == 9 || $type == 7) {
            $data['applied_internship_list'] = $Candidate_model->fetch_table_data_ongoing('can_applied_internship', $where, $order_by);
        } else {
            $data['applied_internship_list'] = $Candidate_model->fetch_table_data_for_all('can_applied_internship', $where, $order_by);
        }
        $order_by_reason = array('ordercolumn' => 'order', 'ordertype' => 'asc');
        $where = array('status' => '1');
        $data['complete_reason'] = $Candidate_model->fetch_table_data_for_all('master_complete_reason', $where, $order_by_reason);
        $where_status = array('status' => '1', 'userid' => $userid);
        $data['personal_details'] = $Candidate_model->fetch_table_row('can_personal_details', $where_status);

        $order_by_apply = array('ordercolumn' => 'id', 'ordertype' => 'desc');
        $where_apply = array('status' => '1', 'candidate_id' => $userid, 'application_type' => '0');
        $data['application_count_applied_internship_list'] = $Candidate_model->fetch_table_data_for_all('can_applied_internship', $where_apply, $order_by_apply);

        $order_by_off = array('ordercolumn' => 'id', 'ordertype' => 'desc');
        $where_off = array('status' => '1', 'candidate_id' => $userid, 'application_type' => '1', 'application_status' => '2');
        $data['offer_count_applied_internship_list'] = $Candidate_model->fetch_table_data_for_all('can_applied_internship', $where_off, $order_by_off);



        return view('Candidate/can_my_intern_list', $data);
    }

    public function application_offers_received()
    {
        $session = session();
        $ses_data = [
            'application_offers_received' => 1,
        ];
        $session->set($ses_data);
        return redirect()->to('/my-applications/7');
    }
    public function can_profile_edit($url, $company_logo, $company_name, $intership_profile, $intership_number)
    {
        $session = session();
        $Candidate_model = new Candidate_model();
        $ses_data = [
            'profile_complete_status' => 1,
            'company_logo' => $company_logo,
            'company_name' => $company_name,
            'intership_profile' => $intership_profile,
            'intership_number' => $intership_number,
            'edit_profile' => 1
        ];
        $session->set($ses_data);
        if ($url == 1) {
            return redirect()->to('personal-details');
        }
        if ($url == 2) {
            return redirect()->to('experience-details');
        }
        if ($url == 3) {
            return redirect()->to('education-details');
        }
        if ($url == 4) {
            return redirect()->to('skill-details');
        }
        if ($url == 5) {
            return redirect()->to('work-sample-details');
        }
    }

    function c_trim($var)
    {
        $var = ltrim($var);
        $var = rtrim($var);
        return $var;
    }
    public function can_view_profile()
    {
        $session = session();
        $Candidate_model = new Candidate_model();
        $userid    =    $session->get('userid');
        $where = array('status' => '1', 'userid' => $userid);
        $data['profile_personal'] = $Candidate_model->fetch_table_row('can_personal_details', $where);
        $data['education_details'] = $Candidate_model->fetch_table_data('can_education_details', $where);
        $data['address_details'] = $Candidate_model->fetch_table_row('can_address_details', $where);
        $data['experience_details'] = $Candidate_model->fetch_table_data('can_experience_details', $where);
        $data['skill_details'] = $Candidate_model->fetch_table_data('can_skills_details', $where);
        $data['work_sample'] = $Candidate_model->fetch_table_row('can_work_sample', $where);
        // $where_int = array('status' => '1', 'internship_id' => $internship_id);
        // $data['internship_details'] = $Candidate_model->fetch_table_row('employer_post_internship', $where_int);
        // $internship_details=$data['internship_details'];
        // $where_emp = array('status' => '1', 'userid' => $internship_details->user_id);
        // $data['emp_profile_details'] = $Candidate_model->fetch_table_row('profile_completion_form', $where_emp);
        // $where_apply = array('status' => '1', 'internship_id' => $internship_id,'candidate_id' => $userid);
        // $data['apply_internship_details'] = $Candidate_model->fetch_table_row('can_applied_internship', $where_apply);
        $end_date = date("Y-m-d");
        $start_date = date('Y-m-d', strtotime('-7 days', strtotime($end_date)));
        $where1 = array('emp_candidate_profile_log.status' => '1', 'emp_candidate_profile_log.candidate_id' => $userid);
        $data['view_profile_emp'] = $Candidate_model->view_profile_emp('emp_candidate_profile_log', $where1, $start_date, $end_date);

        return view('Candidate/can_view_profile', $data);
    }

    public function can_profile_remove_sess($url)
    {
        $session = session();
        $Candidate_model = new Candidate_model();
        $ses_data = [
            'profile_complete_status',
            'company_logo',
            'company_name',
            'intership_profile',
            'intership_number',
            'edit_profile'
        ];
        $session->remove($ses_data);
        if ($url == 1) {
            return redirect()->to('personal-details');
        }
        if ($url == 2) {
            return redirect()->to('education-details');
        }
        if ($url == 3) {
            return redirect()->to('profile-details');
        }
    }

    public function can_apply_intern_session($url, $company_logo, $company_name, $intership_profile, $intership_number)
    {
        $session = session();
        $ses_data = [
            'profile_complete_status' => 1,
            'company_logo' => $company_logo,
            'company_name' => $company_name,
            'intership_profile' => $intership_profile,
            'intership_number' => $intership_number,
            'edit_profile' => 1
        ];
        $session->set($ses_data);
        if ($url == 1) {
            return redirect()->to('personal-details');
        }
        if ($url == 2) {
            return redirect()->to('education-details');
        }
    }

    public function can_intership_bookmark_single($type, $internship_id, $emp_user_id, $profile, $redirect)
    {
        $session = session();
        $userid    =    $session->get('userid');
        $Candidate_model = new Candidate_model();
        $current_datetime = $Candidate_model->current_datetime();
        if ($type == 1) {
            $where_book = array('bookmark_status' => '0', 'status' => '1', 'internship_id' => $internship_id, 'can_user_id' => $userid, 'emp_user_id' => $emp_user_id);
            $bookmark_details = $Candidate_model->fetch_table_row('can_bookmark_details', $where_book);
            if ($bookmark_details) {
                $where = array(
                    'can_user_id' => $userid,
                    'internship_id' => $internship_id,
                    'emp_user_id' => $emp_user_id,
                );
                $data = [
                    'bookmark_status' => '1',
                ];
                $insert_data = $Candidate_model->update_commen('can_bookmark_details', $where, $data);
            } else {
                $data = [
                    'can_user_id' => $userid,
                    'internship_id' => $internship_id,
                    'emp_user_id' => $emp_user_id,
                    'profile' => $profile,
                    'created_at' => $current_datetime,
                ];
                $insert_data = $Candidate_model->insert_commen('can_bookmark_details', $data);
            }
        } else {
            $where = array(
                'can_user_id' => $userid,
                'internship_id' => $internship_id,
                'emp_user_id' => $emp_user_id,
            );
            $data = [
                'bookmark_status' => '0',
            ];
            $insert_data = $Candidate_model->update_commen('can_bookmark_details', $where, $data);
        }
        if ($insert_data) {
            if ($type == 3) {
                $session->setFlashdata('error_msg', 'Bookmark Removed');
                $session->setFlashdata('error_status', '1');
                return redirect()->to('bookmark');
            } else {
                if ($type == 1) {
                    $session->setFlashdata('error_msg', 'Bookmark Added');
                    $session->setFlashdata('error_status', '2');
                } else {
                    $session->setFlashdata('error_msg', 'Bookmark Removed');
                    $session->setFlashdata('error_status', '1');
                }
                if ($redirect == '1') {
                    return redirect()->to('internship-details/' . $internship_id);
                } elseif ($redirect == '3') {
                    return redirect()->to('search-internship');
                } else {
                    return redirect()->to('dashboard');
                }
            }
        }
    }

    public function can_intership_bookmark()
    {
        $session = session();
        $userid    =    $session->get('userid');
        $type    = $this->request->getVar('type');
        $internship_id    = $this->request->getVar('internship_id');
        $emp_user_id    = $this->request->getVar('emp_user_id');
        $profile    = $this->request->getVar('profile');
        $redirect    = $this->request->getVar('redirect');
        $Candidate_model = new Candidate_model();
        $current_datetime = $Candidate_model->current_datetime();

        if ($type == '1') {
            // echo csrf_hash() . '^' . $internship_id;
            $where_book = array('bookmark_status' => '0', 'status' => '1', 'internship_id' => $internship_id, 'can_user_id' => $userid, 'emp_user_id' => $emp_user_id);
            $bookmark_details = $Candidate_model->fetch_table_row('can_bookmark_details', $where_book);
            if ($bookmark_details) {
                $where = array(
                    'can_user_id' => $userid,
                    'internship_id' => $internship_id,
                    'emp_user_id' => $emp_user_id,
                );
                $data = [
                    'bookmark_status' => '1',
                ];
                $insert_data = $Candidate_model->update_commen('can_bookmark_details', $where, $data);
            } else {
                $data = [
                    'can_user_id' => $userid,
                    'internship_id' => $internship_id,
                    'emp_user_id' => $emp_user_id,
                    'profile' => $profile,
                    'created_at' => $current_datetime,
                ];
                $insert_data = $Candidate_model->insert_commen('can_bookmark_details', $data);
            }
        } else {
            $where = array(
                'can_user_id' => $userid,
                'internship_id' => $internship_id,
                'emp_user_id' => $emp_user_id,
            );
            $data = [
                'bookmark_status' => '0',
            ];
            $insert_data = $Candidate_model->update_commen('can_bookmark_details', $where, $data);
        }
        if ($insert_data) {
            if ($type == '3') {
                if ($redirect == '2') {
                    $session->setFlashdata('error_msg', 'Bookmark Removed');
                    $session->setFlashdata('error_status', '1');
                    echo csrf_hash() . '^' . 2;
                } else {
                    echo csrf_hash() . '^' . 2;
                }


                // return redirect()->to('bookmark');
            } else {
                if ($type == '1') {
                    if ($redirect == '2') {
                        $session->setFlashdata('error_msg', 'Bookmark Added');
                        $session->setFlashdata('error_status', '2');
                        echo csrf_hash() . '^' . 1;
                    } else {
                        echo csrf_hash() . '^' . 1;
                    }
                } else {
                    if ($redirect == '2') {
                        $session->setFlashdata('error_msg', 'Bookmark Removed');
                        $session->setFlashdata('error_status', '1');
                        echo csrf_hash() . '^' . 2;
                    } else {
                        echo csrf_hash() . '^' . 2;
                    }
                }
                // if($redirect=='1'){
                //     return redirect()->to('internship-details/' . $internship_id);
                // }elseif($redirect=='3'){
                //     return redirect()->to('search-internship');
                // }
                // else{
                //     return redirect()->to('dashboard');
                // }
            }
        } else {
            echo csrf_hash() . '^' . 0;
        }
    }

    public function can_bookmark_list()
    {
        $session = session();
        $Candidate_model = new Candidate_model();
        $userid    =    $session->get('userid');
        // $order_by = array('ordercolumn' => 'id', 'ordertype' => 'desc');
        // $where = array('status' => '1', 'bookmark_status' => '1', 'can_user_id' => $userid);
        // $data['can_bookmark_list'] = $Candidate_model->fetch_table_data_for_all('can_bookmark_details', $where, $order_by);

        $data['can_bookmark_list'] = $Candidate_model->fetch_table_data_bookmark($userid);
        return view('Candidate/can_bookmark_list', $data);
    }

    public function can_apply_before_intern_session($url)
    {
        $session = session();
        $ses_data = [
            'edit_profile' => 1,
            'next_but_status' => 1
        ];
        $session->set($ses_data);
        if ($url == 1) {
            // $ses_data11 = [
            //     'next_but_status'=> 1
            // ];
            // $session->set($ses_data11);
            return redirect()->to('personal-details');
        }
        if ($url == 2) {
            return redirect()->to('education-details');
        }
    }

    //search with keyword
    public function keyword_search()
    {
        //print_r($_REQUEST);
        $session = session();
        $searched_keyword = $this->request->getVar('searched_keyword');
        $usertype = $this->request->getVar('usertype');


        if (!empty($searched_keyword)) {
            if ($usertype == 3) {
                $_SESSION['searched_keyword_candidates'] = $searched_keyword;
            } elseif ($usertype == 1) {
                $_SESSION['searched_keyword_folder'] = $searched_keyword;
            } elseif ($usertype == 2) {
                $_SESSION['searched_keyword_search_folder'] = $searched_keyword;
            } else {
                $_SESSION['searched_keyword'] = $searched_keyword;
            }
        } else {
            $_SESSION['searched_keyword'] = '';
        }

        echo json_encode(csrf_hash());
    }
    public function keyword_search_candidate()
    {
        //print_r($_REQUEST);
        $session = session();
        $searched_keyword = $this->request->getVar('searched_keyword');
        $usertype = $this->request->getVar('usertype');

        if (!empty($searched_keyword)) {
            if ($usertype == 3) {
                $_SESSION['searched_keyword_candidates'] = $searched_keyword;
            } elseif ($usertype == 1) {
                $_SESSION['searched_keyword_folder'] = $searched_keyword;
            } elseif ($usertype == 2) {
                $_SESSION['searched_keyword_search_folder'] = $searched_keyword;
            } else {
                $_SESSION['searched_keyword'] = $searched_keyword;
            }
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
        return redirect()->to('/search-internship');
    }
    //common function for send email

    // function email_send($message, $subject, $to_email, $from_content)
    // {
    //     $email = \Config\Services::email();
    //     $email->setTo($to_email);
    //     $email->setFrom('internme.app@gmail.com', 'NoReply', $from_content);
    //     $email->setSubject($subject);
    //     $email->setMessage($message);
    //     if ($email->send()) {
    //         return true;
    //     } else {
    //         return false;
    //     }
    // }
    function email_send($message, $subject, $to_email, $from_content, $template_key)
    {
        // echo $message;
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.zeptomail.com/v1.1/email/template",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1_2,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => '{
         "template_key": "' . $template_key . '",
        "bounce_address":"donotreply@notification.internme.app",
        "from": { "address": "noreply@internme.app"},
        "to": [{"email_address": {"address": "' . $to_email . '","name": "' . $from_content . '"}}],
        "subject":"' . $subject . '",
        "merge_info": ' . $message . ' }',
            CURLOPT_HTTPHEADER => array(
                "accept: application/json",
                "authorization: Zoho-enczapikey wSsVR61/80GiCqgslDWkLrs+mV0GUlzxEU0sjgem6SD6H6/F98c9wkPOU1KiGvcZE2FvEDoaobl6zk8I12EO2YgqzFEFDiiF9mqRe1U4J3x17qnvhDzPV25dlBONKYkAwwhtnmJlEcsk+g==",
                "cache-control: no-cache",
                "content-type: application/json",
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            // echo "cURL Error #:" . $err;
            return false;
        } else {
            // echo $response;
            return true;
        }
    }
    // function email_send_test(){
    //     $curl = curl_init();
    //     // print_r($curl);exit();

    //     curl_setopt_array($curl, array(
    //         CURLOPT_URL => "https://api.zeptomail.com/v1.1/email",
    //         CURLOPT_RETURNTRANSFER => true,
    //         CURLOPT_ENCODING => "",
    //         CURLOPT_MAXREDIRS => 10,
    //         CURLOPT_TIMEOUT => 30,
    //         CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1_2,
    //         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //         CURLOPT_CUSTOMREQUEST => "POST",
    //         CURLOPT_POSTFIELDS => '{
    //     "bounce_address":"donotreply@notification.internme.app",
    //     "from": { "address": "noreply@internme.app"},
    //     "to": [{"email_address": {"address": "tech.sethu@in22labs.com","name": "Information"}}],
    //     "subject":"Test Email",
    //     "htmlbody":"<div><b> Test email sent successfully. </b></div>",
    //     }
    //       ]
    //     }',
    //         CURLOPT_HTTPHEADER => array(
    //             "accept: application/json",
    //             "authorization: Zoho-enczapikey wSsVR61/80GiCqgslDWkLrs+mV0GUlzxEU0sjgem6SD6H6/F98c9wkPOU1KiGvcZE2FvEDoaobl6zk8I12EO2YgqzFEFDiiF9mqRe1U4J3x17qnvhDzPV25dlBONKYkAwwhtnmJlEcsk+g==",
    //             "cache-control: no-cache",
    //             "content-type: application/json",
    //         ),
    //     ));

    //     $response = curl_exec($curl);
    //     $err = curl_error($curl);

    //     curl_close($curl);

    //     if ($err) {
    //         echo "cURL Error #:" . $err;
    //     } else {
    //         echo $response;
    //     }
    // }
    //accept hiring
    public function accept_hiring()
    {
        $session         = session();
        $Candidate_model = new Candidate_model();
        $current_datetime = $Candidate_model->current_datetime();
        $userid          = $session->get('userid');
        $faculty    = $this->request->getVar('faculty');
        $college_id    = $this->request->getVar('college_id');
        $college_name    = $this->request->getVar('college_name');
        $id    = $this->request->getVar('id');
        $ca_type    = $this->request->getVar('ca_type');
        $fa_type    = $this->request->getVar('fa_type');
        $can_reg_number    = $this->request->getVar('can_reg_number');
        $faculty_name    = $this->request->getVar('faculty_name');
        $faculty_email    = $this->request->getVar('faculty_email');
        $internship_id    = $this->request->getVar('internship_id11');

        $where_com = array('internship_id ' => $internship_id);
        $internship_data = $Candidate_model->fetch_table_row('employer_post_internship', $where_com);
        $where = array('userid' => $userid);
        $can_details = $Candidate_model->fetch_table_row('can_personal_details', $where);
        $where3 = array('status' => '1', 'userid' => $internship_data->company_id);
        $company_details = $Candidate_model->fetch_table_row('profile_completion_form', $where3);
        if (isset($internship_data->profile) && $internship_data->profile != '0') {
            $profile = $Candidate_model->get_master_name('master_profile', $internship_data->profile, 'profile');
        } else {
            $profile =  $internship_data->other_profile;
        }
        $emp_company_name = $company_details->profile_company_name;

        if (isset($internship_data->internship_duration)) {
            $duration_count = $internship_data->internship_duration;
        }
        if (isset($internship_data->internship_duration_type)) {
            if ($internship_data->internship_duration_type == 1) {
                // echo "Week";
                if ($internship_data->internship_duration == 1) {
                    $duration_type = "Week";
                } else {
                    $duration_type = "Weeks";
                }
            } elseif ($internship_data->internship_duration_type == 2) {
                // echo "Months";
                if ($internship_data->internship_duration == 1) {
                    $duration_type = "Month";
                } else {
                    $duration_type = "Months";
                }
            }
        }
        $duration_of_internship = $duration_count . ' ' . $duration_type;
        $start_date = date("d-M-Y", strtotime($internship_data->internship_startdate));
        $current_year = date('Y');

        if ($faculty == 1) {
            $where = array('email' => $faculty_email, 'usertype' => '6');
            $exisCheck = $Candidate_model->exisCheck('userlogin', $where);
            if (empty($exisCheck)) {
                $rand = rand(11, 99);
                $facultyuserid = '6' . date('ymdhis') . $rand;

                $data = [
                    'userid' => $facultyuserid,
                    'faculty_name' => $this->request->getVar('faculty_name'),
                    'faculty_email' => $this->request->getVar('faculty_email'),
                    // 'faculty_mobile' => $this->request->getVar('add_employee_id'),
                    'faculty_college' => $this->request->getVar('college_id'),
                    'faculty_college_other' => $this->request->getVar('college_name'),
                    'created_at' => $current_datetime,

                ];
                $insert_data = $Candidate_model->insert_commen('faculty_reg_data', $data);
                $result          = $Candidate_model->accept_hiring1($internship_id, $userid, $facultyuserid, $can_reg_number);
                $data1 = [
                    'userid' => $facultyuserid,
                    'usertype' => '6',
                    'name' => $this->request->getVar('faculty_name'),
                    'username' => $this->request->getVar('faculty_name'),
                    'email' => $this->request->getVar('faculty_email'),
                    'created_at' => $current_datetime,

                ];
                // print_r($data1);exit;
                $insert_data1 = $Candidate_model->insert_commen('userlogin', $data1);

                $url = "https://internme.app/faculty-register/" . $facultyuserid;

                $message = '{ "name" : "' . $faculty_name . '","link" : "' . $url . '", "year" : "' . $current_year . '", "candidate_name" : "' . $can_details->profile_full_name . '", "company_name" : "' . $emp_company_name . '", "start_date" : "' . $start_date . '", "internship_type" : "' . $profile . '", "internship_duration" : "' . $duration_of_internship . '", "reg_number" : "' . $can_reg_number . '", "candidate_email" : "' . $can_details->profile_email . '", "candidate_mobile" : "' . $can_details->profile_phone_number . '"}'; //dynamic contents for template
                $subject      = 'Internme - Invitation for Registration';
                $to_email     =  $this->request->getVar('faculty_email');
                $from_content = 'Invitation for registration as teacher';
                $template_key = '2d6f.456f260c51ab9602.k1.015dbe80-af87-11ed-a550-525400e3c1b1.186647a6a68';
                // echo $to_email;exit;
                $this->email_send($message, $subject, $to_email, $from_content, $template_key);
            } else {

                if (!empty($exisCheck->password)) {
                    $facultyuserid =  $exisCheck->userid;
                    $result          = $Candidate_model->accept_hiring1($internship_id, $userid, $facultyuserid, $can_reg_number);
                    $message = '{ "name" : "' . $faculty_name . '", "year" : "' . $current_year . '", "candidate_name" : "' . $can_details->profile_full_name . '", "company_name" : "' . $emp_company_name . '", "start_date" : "' . $start_date . '", "internship_type" : "' . $profile . '", "internship_duration" : "' . $duration_of_internship . '", "reg_number" : "' . $can_reg_number . '", "candidate_email" : "' . $can_details->profile_email . '", "candidate_mobile" : "' . $can_details->profile_phone_number . '"}'; //dynamic contents for template
                    $subject      = 'Assigned as a college supervisor for ' . $profile . ' internship';
                    $to_email     =  $this->request->getVar('faculty_email');
                    $from_content = 'Assigned as a college supervisor for ' . $profile . ' internship';
                    $template_key = '2d6f.456f260c51ab9602.k1.4e8a5420-b103-11ed-a8c3-525400d4bb1c.1866e36c262';
                    // echo $to_email;exit;
                    $this->email_send($message, $subject, $to_email, $from_content, $template_key);
                } else {
                    $facultyuserid =  $exisCheck->userid;
                    $result          = $Candidate_model->accept_hiring1($internship_id, $userid, $facultyuserid, $can_reg_number);
                    $url = "https://internme.app/faculty-register/" . $facultyuserid;

                    $message = '{ "name" : "' . $faculty_name . '","link" : "' . $url . '", "year" : "' . $current_year . '", "candidate_name" : "' . $can_details->profile_full_name . '", "company_name" : "' . $emp_company_name . '", "start_date" : "' . $start_date . '", "internship_type" : "' . $profile . '", "internship_duration" : "' . $duration_of_internship . '", "reg_number" : "' . $can_reg_number . '", "candidate_email" : "' . $can_details->profile_email . '", "candidate_mobile" : "' . $can_details->profile_phone_number . '"}'; //dynamic contents for template
                    $subject      = 'Internme - Invitation for Registration';
                    $to_email     =  $this->request->getVar('faculty_email');
                    $from_content = 'Invitation for registration as teacher';
                    $template_key = '2d6f.456f260c51ab9602.k1.015dbe80-af87-11ed-a550-525400e3c1b1.186647a6a68';
                    // echo $to_email;exit;
                    $this->email_send($message, $subject, $to_email, $from_content, $template_key);
                }
            }
        } else {
            $result          = $Candidate_model->accept_hiring($internship_id, $userid);
        }


        if ($result) {
            if ($fa_type == '0') {
                $session->setFlashdata('error_msg', 'Offer Accepted Successfully');
                $session->setFlashdata('error_status', '2');
            } else {
                $session->setFlashdata('error_msg', 'Updated Successfully');
                $session->setFlashdata('error_status', '2');
            }
        } else {
            $session->setFlashdata('error_msg', 'Failed Try Again');
            $session->setFlashdata('error_status', '1');
        }
        if ($ca_type == 1) {
            return redirect()->to('/my-applications');
        } elseif ($ca_type == 2) {
            return redirect()->to('/direct-corporate-offers');
        } else {
            return redirect()->to('/my-internships');
        }
    }
    //reject hiring
    public function reject_hiring()
    {
        extract($_REQUEST);
        $session         = session();
        $Candidate_model = new Candidate_model();
        $userid          = $session->get('userid');
        $result          = $Candidate_model->reject_hiring($reject_id, $userid);
        if ($result) {
            $session->setFlashdata('error_msg', 'Cancelled Successfully');
            $session->setFlashdata('error_status', '1');
            // return redirect()->to('/my-applications');
        } else {
            $session->setFlashdata('error_msg', 'Failed Try Again');
            $session->setFlashdata('error_status', '1');
            // return redirect()->to('/my-applications');
        }
        if ($redirect_url == 1) {
            return redirect()->to('/my-applications');
        } elseif ($redirect_url == 2) {
            return redirect()->to('/direct-corporate-offers');
        } else {
            return redirect()->to('/my-internships');
        }
    }

    public function add_under_consideration()
    {
        extract($_REQUEST);
        $session         = session();
        $Candidate_model = new Candidate_model();
        $userid          = $session->get('userid');
        $result          = $Candidate_model->add_under_consideration($under_cons_id, $userid);
        if ($result) {
            $session->setFlashdata('error_msg', 'Under Consideration Added Successfully');
            $session->setFlashdata('error_status', '2');
            // return redirect()->to('/my-applications');
        } else {
            $session->setFlashdata('error_msg', 'Failed Try Again');
            $session->setFlashdata('error_status', '1');
            // return redirect()->to('/my-applications');
        }
        if ($redirect_url == 1) {
            return redirect()->to('/my-applications');
        } elseif ($redirect_url == 2) {
            return redirect()->to('/direct-corporate-offers');
        } else {
            return redirect()->to('/my-internships');
        }
    }
    //check already confirmed
    public function check_already_confirmed()
    {
        extract($_REQUEST);
        $session         = session();
        $Candidate_model = new Candidate_model();
        $userid          = $session->get('userid');
        $result          = $Candidate_model->check_already_confirmed($userid);
        if (!empty($result)) {
            echo json_encode(array('result' => 1, 'csrf' => csrf_hash()));
        } else {
            echo json_encode(array('result' => 0, 'csrf' => csrf_hash()));
        }
    }

    public function get_can_mobile_email_edit()
    {
        // $model = new Employer_model();
        $userid = $this->request->getVar('userid');

        $Candidate_model = new Candidate_model();
        $where = array('userid' => $userid, 'status' => '1');
        $profile = $Candidate_model->fetch_table_row('can_personal_details', $where);
        echo $profile->profile_phone_number . '^' . $profile->profile_email . '^' . csrf_hash();
    }


    //user password change
    public function change_password()
    {
        $data['title'] = 'change password';
        return view('Candidate/change_password', $data);
    }
    //user current password check
    public function user_current_password_check()
    {
        $Candidate_model = new Candidate_model();
        $old_password    = $this->request->getVar('old_password');
        $profile = $Candidate_model->current_password_check($old_password);
        if (!empty($profile)) {
            echo '1' . '^' . csrf_hash();
        } else {
            echo '0' . '^' . csrf_hash();
        }
    }
    //save changed password

    function save_changed_password()
    {
        $session         = session();
        $Candidate_model = new Candidate_model();
        //generate sha password
        $new_password    = $this->request->getVar('new_password');
        $salt            = $Candidate_model->RandomString();
        $enc_password    = hash("sha256", $new_password . $salt);

        $result          = $Candidate_model->save_changed_password($enc_password, $salt, $new_password);
        if ($result) {
            $session->setFlashdata('error_msg', 'Password Changed Successfully');
            $session->setFlashdata('error_status', '2');
            return redirect()->to('/change-password-success');
        } else {
            $session->setFlashdata('error_msg', 'Failed Try Again');
            $session->setFlashdata('error_status', '1');
            return redirect()->to('/change-password');
        }
    }

    public function can_profile_mobile_otp()
    {
        $otp = mt_rand(100000, 999999);
        // $model = new Employer_model();
        $user_id = $this->request->getVar('user_id');
        $mobile = $this->request->getVar('mobile');
        $session = session();
        $Candidate_model = new Candidate_model();

        //check duplicate 
        $duplicate_data = $Candidate_model->duplicate_number($mobile, $user_id);
        // print_r($duplicate_data);

        if (empty($duplicate_data)) {
            //check otp count
            $otp_count = $Candidate_model->otp_count_check_mobile($mobile);
            // print_r($otp_count);
            //allow only 5 attempt
            if ($otp_count < 5) {
                // print_r($otp_count);      
                $otp_count_new  = $otp_count + 1;

                $data1 = array(
                    'phone_number' => $mobile,
                    'otp_count'    => $otp_count_new,
                    'otp_number'   => $otp,
                    'user_type'    => 1,
                );

                $update_otp = $Candidate_model->otp_count_save_mobile($data1);
                //  print_r($update_otp);
                //otp sms 
                $apiKey = urlencode('EM5b4IXHyls-hDU0Ja8rBGILMJ7RKiJEtcXakV8kke');
                // Message details
                $numbers = $mobile;
                $sender = urlencode('INTNME');
                $message = rawurlencode('Dear User, Your OTP for mobile number verification is: ' . $otp . ' - InternMe Team.');

                // Prepare data for POST request
                $data = array('apikey' => $apiKey, 'numbers' => $numbers, "sender" => $sender, "message" => $message);

                // Send the POST request with cURL
                $ch = curl_init('https://api.textlocal.in/send/');
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($ch);
                curl_close($ch);


                $msg = 'We have sent an OTP to your registered Mobile Number';
                $otp_status = 0;
            } else {
                $msg = 'OTP Limit Exceeded, Please Try After 24 Hours';
                $otp_status = 1;
            }




            // $where = array('profile_phone_no' => $mobile, 'otp_status' => 1);
            // $checkMobile = $Employer_model->fetch_table_data_for_all('profile_completion_form', $where);
            // if(empty($checkMobile)){
            // $where = array('userid' => $user_id);
            // // print_r($where);
            // $available_data = $Candidate_model->fetch_table_data_for_all('can_personal_details', $where);
            // // print_r($available_data);
            // $otp_count = ($available_data[0]->email_otp_count + 1);

            // $data3 = ['otp_status' => 1,'email_otp'   => $otp,'email_otp_count' => $otp_count];
            // $update_otp = $Candidate_model->update_commen('can_personal_details', $where, $data3);

            if ($update_otp) {
                echo '1' . '^' . csrf_hash() . '^' . $msg . '^' . $otp_status;
            } else {
                echo '0' . '^' . csrf_hash() . '^' . $msg . '^' . $otp_status;
            }
        } else {
            $msg = 'Duplicate Entry';
            $otp_status = 2;
            echo '2' . '^' . csrf_hash() . '^' . $msg . '^' . $otp_status;
        }
    }

    public function mobile_otp_verify_edit()
    {
        // $model = new Employer_model();
        $user_id = $this->request->getVar('user_id');
        $user_otp = $this->request->getVar('user_otp');
        $mobile = $this->request->getVar('mobile');
        $session = session();
        $Candidate_model = new Candidate_model();
        // $where = array('userid' => $user_id, 'profile_otp' => $user_otp, 'profile_phone_no' => $mobile);
        // $available_data = $Employer_model->fetch_table_data_for_all('profile_completion_form', $where);
        $where = array('otp_number' => $user_otp, 'user_type' => 1, 'phone_number' => $mobile);
        $available_data = $Candidate_model->fetch_table_data('user_otp', $where);
        if ($available_data) {
            $otp_count = 0;
            $data = ['profile_phone_number' => $mobile, 'otp_status' => 1, 'mobile_verify_status' => 1];
            $data_user = ['mobile' => $mobile];
            $where1 = array('userid' => $user_id);
            $update_otp = $Candidate_model->update_commen('can_personal_details', $where1, $data);
            $update_user = $Candidate_model->update_commen('userlogin', $where1, $data_user);
            echo csrf_hash() . '^' . '1';
        } else {
            echo csrf_hash() . '^' . '0';
        }
    }

    function email_html_test()
    {
        $file_name = base_url() . '/public/email_template_images/banner.png';
        $attach = base_url('public/email_template_images/banner.png');
        $email = \Config\Services::email();
        //$cid = $email->attach($file_name,'inline');
        // $data['cid']= $email->attach($attach, "inline");

        $message = view('email_template/test');

        // print_r($message);
        $email->setTo('tech.john@in22labs.com');
        $email->setFrom('internme.app@gmail.com', 'NoReply', 'jhgj');
        $email->setSubject('test');
        $email->setMessage($message);
        if ($email->send()) {
            return true;
        } else {
            return false;
        }
    }
    public function change_password_success()
    {
        return view('Candidate/change_password_success');
    }

    public function can_dashboard()
    {
        $session = session();
        $Candidate_model = new Candidate_model();
        $userid    =    $session->get('userid');
        $current_date = date("Y-m-d");
        $data['internship_list_preferred_location'] = $Candidate_model->fetch_table_data_filter_preffered_location();
        $data['can_bookmark_list'] = $Candidate_model->fetch_table_data_bookmark($userid);
        $order_by = array('ordercolumn' => 'id', 'ordertype' => 'desc');
        $where = array('status' => '1', 'candidate_id' => $userid, 'application_type' => '0');
        $data['applied_internship_list'] = $Candidate_model->fetch_table_data_for_all('can_applied_internship', $where, $order_by);

        $where_cou = array('status' => '1', 'candidate_id' => $userid, 'application_type' => '1', 'application_status' => '2');
        $data['internship_offers_list'] = $Candidate_model->fetch_table_data_for_all('can_applied_internship', $where_cou, $order_by);

        $where_log = array('status' => '1', 'candidate_id' => $userid, 'application_status' => '2', 'hiring_status' => '1');
        $data['internship_offers_log'] = $Candidate_model->fetch_table_data_for_all('can_applied_internship', $where_log, $order_by);

        $where_apply = array('status' => '1', 'candidate_id' => $userid, 'application_type' => '1');
        $data['application_count_applied_internship_list'] = $Candidate_model->fetch_table_data_for_all_count('can_applied_internship', $where_apply);

        $where_myint = "status = '1' AND candidate_id = $userid AND (hiring_status='1' OR hiring_status='3')";

        $data['my_count_applied_internship_list'] = $Candidate_model->fetch_table_data_for_all_count('can_applied_internship', $where_myint);

        $order_by = array('ordercolumn' => 'can_applied_internship.id', 'ordertype' => 'desc');
        $where = "can_applied_internship.status = '1' AND can_applied_internship.candidate_id = $userid AND can_applied_internship.application_type = '0' AND can_applied_internship.application_status = '2' AND can_applied_internship.hiring_status='0' AND can_applied_internship.complete_status='0'  AND employer_post_internship.internship_startdate>='$current_date'";

        $data['applied_internship_list_hired_count'] = $Candidate_model->fetch_table_data_ongoing_count('can_applied_internship', $where, $order_by);

        $data['internship_list'] = $Candidate_model->fetch_table_data_popular_internship();
        $data['internship_list_current_location'] = $Candidate_model->fetch_table_data_filter_location();



        $data['internship_list_search_key'] = $Candidate_model->fetch_table_data_filter_search_keyword();
        //  echo '<pre>'; print_r($data['internship_list_search_key']);exit;
        return view('Candidate/can_dashboard', $data);
    }

    public function can_work_report_showing($showing_result, $internship_id)
    {
        $session = session();
        $ses_data = [
            'can_work_report_showing_limit' => $showing_result
        ];
        $session->set($ses_data);
        return redirect()->to('candidate-logsheet/' . $internship_id);
    }

    public function can_logsheet($internship_id)
    {
        $session = session();
        $Candidate_model = new Candidate_model();
        $userid    =    $session->get('userid');
        $can_work_report_showing_limit = $session->get('can_work_report_showing_limit');
        $where = array('status' => '1', 'internship_id' => $internship_id);
        $data['internship_details'] = $Candidate_model->fetch_table_row('employer_post_internship', $where);
        if (isset($data['internship_details'])) {
            $where_cou = array('status' => '1', 'candidate_id' => $userid, 'internship_id' => $internship_id);
            $data['internship_applied_list'] = $Candidate_model->fetch_table_row('can_applied_internship', $where_cou);
            $order_by = array('ordercolumn' => 'log_date', 'ordertype' => 'desc');
            $where_log = array('status' => '1', 'user_id' => $userid, 'internship_id' => $internship_id, 'company_id' => $data['internship_details']->company_id);

            $pager = service('pager');
            $page = (int) $this->request->getGet('page'); // 



            if (isset($can_work_report_showing_limit)) {
                $limit = $can_work_report_showing_limit;
            } else {
                $limit = config('Pager')->perPage_can_log; // see Config/Pager.php
            }

            if (!isset($page) || $page === 0 || $page === 1) {
                $page = 1;
                $start_id = 0;
            } else {
                $start_id = ($page - 1) * $limit;
                $page = $page;
            }
            $data['page_start_id'] = $start_id;
            $data['page_default_limit'] = $limit;

            $log_sheet_detail = $Candidate_model->fetch_table_data_for_log('can_log_sheet', $where_log, $order_by);
            if (!empty($log_sheet_detail)) {
                $total   = count($log_sheet_detail);
            } else {
                $total   = 0;
            }
            $pager_links = $pager->makeLinks($page, $limit, $total, 'custom_pagination');
            $data['pager_links'] = $pager_links;
            $previous = '';
            // $previous = "javascript:history.go(-1)";
            if (isset($_SERVER['HTTP_REFERER'])) {
                $previous = $_SERVER['HTTP_REFERER'];
                // echo $previous;
                // exit();
            }
            $data['log_sheet_details'] = $Candidate_model->fetch_table_data_for_log('can_log_sheet', $where_log, $order_by, $limit, $start_id);
            $data['log_sheet_details_style'] = $log_sheet_detail;
            // print_r($data['log_sheet_details']);exit;
            return view('Candidate/can_logsheet', $data);
        } else {
            return view('Common/404');
        }
    }

    public function add_log_sheet_details()
    {
        $validation =  \Config\Services::validation();
        $session = session();
        $Candidate_model = new Candidate_model();
        $current_datetime = $Candidate_model->current_datetime();
        $userid    =    $session->get('userid');
        $internship_id = $this->request->getVar('internship_id');
        $add_internship_location = $this->request->getVar('add_internship_location');

        $isValidated = $this->validate([
            'add_log_date' => ['label'  => 'Log Date', 'rules'  => 'required'],
            'add_worked_hours' => ['label'  => 'Work Hours', 'rules'  => 'required'],
            'add_log_description' => ['label'  => 'Description', 'rules'  => 'required'],
        ]);
        if (!$isValidated) {
            $session->setFlashdata('error_status', '3');
            $session->setFlashdata('error_msg', $validation->getErrors());
            return redirect()->to('education-details');
        } else {
            $data = [
                'company_id' => $this->request->getVar('company_id'),
                'user_id' => $userid,
                'internship_id' => $this->request->getVar('internship_id'),
                'log_date' => $this->request->getVar('add_log_date'),
                'worked_hours' => $this->request->getVar('add_worked_hours'),
                'description' => $this->c_trim($this->request->getVar('add_log_description')),
                'created_at' => $current_datetime,

            ];
            $insert_data = $Candidate_model->insert_commen('can_log_sheet', $data);
            if ($insert_data) {

                $where_loc = array('internship_id' => $internship_id);
                $internship = $Candidate_model->fetch_table_row('employer_post_internship', $where_loc);

                if ($internship->internship_type == '1') {
                    $order_by_loc = array('ordercolumn' => 'g_location_name', 'ordertype' => 'asc');
                    $where_loc = array('status' => '1', 'internship_id' => $internship->internship_id);
                    $can_work_location_multi = $Candidate_model->fetch_table_data_for_all('emp_worklocation_multiple', $where_loc, $order_by_loc);
                    if (count($can_work_location_multi) > '1') {
                        $where_loc = array('status' => '1', 'internship_id' => $internship_id, 'g_location_id' => $add_internship_location);
                        $can_work_location = $Candidate_model->fetch_table_row('emp_worklocation_multiple', $where_loc);
                        $where_app = array('candidate_id' => $userid, 'internship_id' => $internship_id);

                        $data_app = [
                            'work_location' => $add_internship_location,
                            'work_location_name' => $can_work_location->g_location_name,
                        ];
                        $update_data = $Candidate_model->update_commen('can_applied_internship', $where_app, $data_app);
                    }
                }
                $session->setFlashdata('error_msg', 'Log Sheet Has Been Added Successfully');
                $session->setFlashdata('error_status', '2');
                return redirect()->to('candidate-logsheet/' . $internship_id);
            } else {
                return redirect()->to('candidate-logsheet/' . $internship_id);
            }
        }
    }

    public function edit_log_sheet_details()
    {
        $validation =  \Config\Services::validation();
        $session = session();
        $Candidate_model = new Candidate_model();
        $userid    =    $session->get('userid');
        $edit_id = $this->request->getVar('edit_id');
        $internship_id = $this->request->getVar('edit_internship_id');
        $edited_user_id = $this->request->getVar('edited_user_id');
        $can_user_id = $this->request->getVar('can_user_id');
        if (isset($edited_user_id)) {
            $edited_user = $edited_user_id;
        } else {
            $edited_user = '';
        }
        $isValidated = $this->validate([
            'edit_log_date' => ['label'  => 'Log Date', 'rules'  => 'required'],
            'edit_worked_hours' => ['label'  => 'Work Hours', 'rules'  => 'required'],
            'edit_log_description' => ['label'  => 'Description', 'rules'  => 'required'],
        ]);
        if (!$isValidated) {
            $session->setFlashdata('error_status', '3');
            $session->setFlashdata('error_msg', $validation->getErrors());
            return redirect()->to('education-details');
        } else {
            $data = [
                'log_date' => $this->request->getVar('edit_log_date'),
                'worked_hours' => $this->request->getVar('edit_worked_hours'),
                'description' => $this->c_trim($this->request->getVar('edit_log_description')),
                'log_edit_user' => $edited_user,

            ];
            $where = array('id' => $edit_id);
            $update_data = $Candidate_model->update_commen('can_log_sheet', $where, $data);
            //if ($update_data) {
            $session->setFlashdata('error_msg', 'Log Sheet Has Been Updated Successfully');
            $session->setFlashdata('error_status', '2');
            if (isset($edited_user_id)) {
                return redirect()->to('view-candidate-logsheet/' . $internship_id . '/' . $can_user_id);
            } else {

                $where_loc = array('internship_id' => $internship_id);
                $internship = $Candidate_model->fetch_table_row('employer_post_internship', $where_loc);

                if ($internship->internship_type == '1') {
                    $edit_internship_location = $this->request->getVar('edit_internship_location');
                    $order_by_loc = array('ordercolumn' => 'g_location_name', 'ordertype' => 'asc');
                    $where_loc = array('status' => '1', 'internship_id' => $internship->internship_id);
                    $can_work_location_multi = $Candidate_model->fetch_table_data_for_all('emp_worklocation_multiple', $where_loc, $order_by_loc);
                    if (count($can_work_location_multi) > '1') {
                        $where_loc = array('status' => '1', 'internship_id' => $internship_id, 'g_location_id' => $edit_internship_location);
                        $can_work_location = $Candidate_model->fetch_table_row('emp_worklocation_multiple', $where_loc);
                        $where_app = array('candidate_id' => $userid, 'internship_id' => $internship_id);

                        $data_app = [
                            'work_location' => $edit_internship_location,
                            'work_location_name' => $can_work_location->g_location_name,
                        ];
                        $update_data = $Candidate_model->update_commen('can_applied_internship', $where_app, $data_app);
                    }
                }

                //     $edit_internship_location =$this->request->getVar('edit_internship_location');
                //     $where_loc = array('status' => '1', 'internship_id' => $internship_id, 'g_location_id' => $edit_internship_location);
                //     $can_work_location = $Candidate_model->fetch_table_row('emp_worklocation_multiple', $where_loc);
                //     $where_app = array('candidate_id' => $userid,'internship_id'=> $internship_id);
                //     $data_app = [
                //             'work_location' => $edit_internship_location,
                //             'work_location_name' =>$can_work_location->g_location_name,
                //         ];
                // $update_data = $Candidate_model->update_commen('can_applied_internship', $where_app, $data_app);
                return redirect()->to('candidate-logsheet/' . $internship_id);
            }
            // } else {
            //     if(isset($edited_user_id)){
            //         return redirect()->to('view-candidate-logsheet/'.$internship_id.'/'.$can_user_id);
            //     }else{
            //         return redirect()->to('candidate-logsheet/'.$internship_id);
            //     }
            // }
        }
    }

    public function complete_internship()
    {
        extract($_REQUEST);
        // print_r($_REQUEST);
        // exit();
        $session         = session();
        $Candidate_model = new Candidate_model();
        $userid          = $session->get('userid');
        $result          = $Candidate_model->complete_internship($complete_id, $userid);
        if ($result) {
            if ($complete_type == 1) {
                $session->setFlashdata('error_msg', ' Internship completed successfully');
            } else {
                $session->setFlashdata('error_msg', ' Internship Dropped successfully');
            }

            $session->setFlashdata('error_status', '2');
            if ($page_redirect == 1) {
                return redirect()->to('/my-applications');
            } else if ($page_redirect == 3) {
                return redirect()->to('/my-internships');
            } else if ($page_redirect == 2) {
                return redirect()->to('/direct-corporate-offers');
            } else {
                return redirect()->to('/my-applications');
            }
        } else {
            $session->setFlashdata('error_msg', 'Failed Try Again');
            $session->setFlashdata('error_status', '1');
            if ($page_redirect == 1) {
                return redirect()->to('/my-applications');
            } else if ($page_redirect == 3) {
                return redirect()->to('/my-internships');
            } else if ($page_redirect == 2) {
                return redirect()->to('/direct-corporate-offers');
            } else {
                return redirect()->to('/my-applications');
            }
        }
    }

    public function candidate_certificate($internship_id)
    {
        $session         = session();
        $Candidate_model = new Candidate_model();
        $userid          = $session->get('userid');

        $where = array('status' => '1', 'userid' => $userid);
        $data['profile_personal'] = $Candidate_model->fetch_table_row('can_personal_details', $where);
        $where = array('status' => '1', 'internship_id' => $internship_id);
        $data['internship_details'] = $Candidate_model->fetch_table_data_for_all('employer_post_internship', $where);

        $where = array('status' => '1', 'company_id' => $data['internship_details'][0]->company_id);
        $order_by = array('ordercolumn' => 'id', 'ordertype' => 'desc');
        $data['certificate_details'] = $Candidate_model->fetch_table_data_for_all('emp_certificate_details', $where, $order_by);

        $where_apply = array('status' => '1', 'internship_id' => $internship_id, 'candidate_id' => $userid);
        $data['apply_internship_details'] = $Candidate_model->fetch_table_row('can_applied_internship', $where_apply);

        $where3 = array('status' => '1', 'userid' => $data['internship_details'][0]->company_id);
        $data['company_details'] = $Candidate_model->fetch_table_row('profile_completion_form', $where3);



        return view('Candidate/can_certificate', $data);
    }

    public function verify_certificate()
    {
        $session         = session();
        $Candidate_model = new Candidate_model();
        $validation =  \Config\Services::validation();
        $id = $this->request->getVar('add_certificate_id');

        $isValidated = $this->validate([
            'add_certificate_id' => ['label'  => 'Certificate ID', 'rules'  => 'required|numeric|'],
        ]);
        if (!$isValidated) {
            $session->setFlashdata('error_status', '3');
            $session->setFlashdata('error_msg', $validation->getErrors());
            return redirect()->to('verify');
        } else {
            $where_apply = array('status' => '1', 'certificate_issued_id' => $id);
            $data['apply_internship_details'] = $Candidate_model->fetch_table_row('can_applied_internship', $where_apply);

            if (isset($data['apply_internship_details'])) {
                $internship_id   = $data['apply_internship_details']->internship_id;
                $userid          = $data['apply_internship_details']->candidate_id;
                $where_edu = array('status' => '1', 'userid' => $userid);
                $order_by = array('ordercolumn' => 'education_end_year', 'ordertype' => 'desc');
                $data['education_details'] = $Candidate_model->fetch_table_data_for_all_limit('can_education_details', $where_edu);

                $where_pro = array('status' => '1', 'userid' => $userid);
                $data['profile_personal'] = $Candidate_model->fetch_table_row('can_personal_details', $where_pro);
                $where_in = array('status' => '1', 'internship_id' => $internship_id);
                $data['internship_details'] = $Candidate_model->fetch_table_data_for_all('employer_post_internship', $where_in);

                $where = array('status' => '1', 'company_id' => $data['internship_details'][0]->company_id);
                $order_by = array('ordercolumn' => 'id', 'ordertype' => 'desc');
                $data['certificate_details'] = $Candidate_model->fetch_table_data_for_all('emp_certificate_details', $where, $order_by);

                $where3 = array('status' => '1', 'userid' => $data['internship_details'][0]->company_id);
                $data['company_details'] = $Candidate_model->fetch_table_row('profile_completion_form', $where3);

                return view('Candidate/verify_certificate', $data);
            } else {
                $session->setFlashdata('error_msg', 'Invalid Certificate ID');
                $session->setFlashdata('error_status', '1');
                return redirect()->to('verify');
            }
        }
    }

    public function verify_user_certificate()
    {
        $session         = session();
        // $Candidate_model = new Candidate_model();
        return view('Candidate/verify_user_certificate');
    }

    // chat process

    public function candidate_chat($id = NULL) //Employee Chats View Page
    {
        helper(['form']);
        $Candidate_model = new Candidate_model();
        $current_datetime = $Candidate_model->current_datetime();
        $session = session();
        $userid    =    $session->get('userid');
        $usertype    =    $session->get('usertype');
        $where3 = array('receiver_id' => $userid);
        $group_by = array('ordercolumn' => 'sender_id');
        $order_by = array('ordercolumn' => 'id', 'ordertype' => 'desc');
        $data['chat_employee'] = $Candidate_model->fetch_table_data_group_by('chat', $where3, $group_by, $order_by);
        if (!empty($id)) {
            $data['emp_id'] = $id;
        } else {

            if (!empty($data['chat_employee'][0]->sender_id)) {
                $data['emp_id'] = $data['chat_employee'][0]->sender_id;
            } else {
                $data['emp_id'] = '';
            }
        }
        // $where3 = array('receiver_id' => $userid);
        // $group_by = array('ordercolumn' => 'sender_id');
        // $order_by = array('ordercolumn' => 'id', 'ordertype' => 'desc');
        // $data['chat_employee'] = $Candidate_model->fetch_table_data_group_by('chat', $where3, $group_by, $order_by);
        // if(!empty($data['chat_employee'][0]->sender_id)){
        // $data['emp_id'] = $data['chat_employee'][0]->sender_id;
        // }else{
        //     $data['emp_id'] ='';
        // }
        // print_r($data['chat_employee']);
        // $data['chatTitle'] = 'SELECT CANDIDATE';
        return view('Candidate/can_chat', $data);
    }
    public function can_send_message() //Function For Send Messages Common (AJEX)
    {
        $Candidate_model = new Candidate_model();
        $session = session();
        $current_datetime = $Candidate_model->current_datetime();
        $receiver_id = $this->request->getVar('receiver_id');
        $userid = $session->get('userid');
        $files = $this->request->getFile('files');
        if (!empty($files)) {
            $attachment_name = $files->getRandomName();
            $files->move('public/assets/docs/uploads/attachment/', $attachment_name);
            $ext = $files->getClientExtension();
            $base_name = $files->getClientName();
            $size = $files->getSize();
        } else {
            $attachment_name = '';
            $ext = '';
            $base_name = '';
            $size = '';
        }

        $messageTxt = $this->request->getVar('message');
        $data = array();
        $data = [
            'sender_id' => $userid,
            'receiver_id' => $receiver_id,
            'type' => '1',
            'message' => $messageTxt,
            'attachment_name' => $attachment_name,
            'attachment_ext' => $ext,
            'attachment_filename' => $base_name,
            'attachment_filesize' => $size,
            'created_at' => $current_datetime,
            'message_status' => '1',
        ];
        $result = $Candidate_model->insert_commen('chat', $data);
        if ($result) {
            echo csrf_hash() . '^' . '1';
        } else {
            echo 0;
        }
    } //Function For Send Messages Common (AJEX)

    // --- COMMON FUNCTIONS --- //
    public function can_get_chat_history() //Function For Getting Messages Common (AJEX)
    {
        $session = session();
        $Candidate_model = new Candidate_model();

        $receiver_id = $this->request->getVar('receiver_id');
        $userid = $session->get('userid');
        $data_user_state = array('message_status' => '2');
        $update_message_status = $Candidate_model->update_message_status('chat', $receiver_id, $userid, $data_user_state);
        $history = $Candidate_model->fetch_chat_data('chat', $receiver_id, $userid);

        if ($history) { //IF DATA IN TABLE 
            $previous_date = null;
            foreach ($history as $chat) {
                $id = $chat->id;
                $sender_id = $chat->sender_id;
                $receiver_id = $chat->receiver_id;
                $type = $chat->type;
                $internship_id = $chat->internship_id;
                $messageBody = nl2br($chat->message);
                $attachment = $chat->attachment_name;
                $file_name = nl2br($chat->file_name);
                $last_date_sub = $chat->last_date_sub;
                $date = $chat->created_at;
                $title = $chat->title;
                $interview_date = $chat->interview_date;
                $interview_time = $chat->interview_time;
                $interview_duration = $chat->interview_duration;
                $interview_mode = $chat->interview_mode;
                $link = $chat->link;
                $attachment_filename = $chat->attachment_filename;
                $attachment_filesize = $chat->attachment_filesize;
                $attachment_ext = $chat->attachment_ext;
                $interview_description = nl2br($chat->interview_description);
                $interview_status = $chat->interview_status;
                $assignment_status = $chat->assignment_status;
                $assignment_id = $chat->assignment_id;
                $status = $chat->message_status;
                $newdate = date("Y-m-d", strtotime($date));
?>
                <?php if ($newdate != $previous_date) {
                    $previous_date = $newdate;
                    $now = time(); // or your date as well
                    $your_date = strtotime($previous_date);
                    $datediff = $now - $your_date;
                    $post_days = floor($datediff / (60 * 60 * 24));
                ?>
                    <li class="msgDate w-100 d-inline-block">
                        <div class="text-center">
                            <h6 class="fw-normal mb-3"><?php if ($post_days == 0) {
                                                            echo 'Today';
                                                        } elseif ($post_days == '1') {
                                                            echo 'Yesterday';
                                                        } else {
                                                            echo date("d-m-Y", strtotime($previous_date));
                                                        } ?></h6>
                        </div>
                    </li>

                <?php } ?>
                <?php if ($sender_id != $userid) { ?>
                    <!-- CHAT BOX CONTENTE FOR RECIVED MESSAGES -->
                    <div class="msg-body">
                        <ul class="ps-0 d-flex flex-column">
                            <?php if ($type == '1') { ?>
                                <li class="sender">
                                    <?php if (!empty($messageBody)) { ?>
                                        <div class="msgCnt">
                                            <p> <?= $messageBody; ?></p>
                                        </div>
                                        <sub class="time w-100 d-inline-block "><?= $newTime = date("h:i a", strtotime($date)); ?> </sub>

                                    <?php } else { ?>

                                        <div class="d-flex justify-content-start align-items-center">
                                            <?php if ($attachment_ext == "png" || $attachment_ext == "PNG" || $attachment_ext == "jpg" || $attachment_ext == "JPG"  || $attachment_ext == "jpeg" || $attachment_ext == "JPEG" || $attachment_ext == "tif" || $attachment_ext == "TIF" || $attachment_ext == "tiff" || $attachment_ext == "TIFF") { ?>
                                                <a download href="<?= base_url(); ?>/public/assets/docs/uploads/attachment/<?= $attachment; ?>" title="ImageName"> <img src="<?= base_url(); ?>/public/assets/docs/uploads/attachment/<?= $attachment; ?>" alt="" class="me-1" style="object-fit:fill; max-height: 100%; width:200px;"> </a>
                                            <?php } elseif ($attachment_ext == 'pdf' || $attachment_ext == 'PDF') { ?>
                                                <div class="assignmentPdf bg-white rounded d-flex">
                                                    <div class="d-flex p-2">
                                                        <img src="<?= base_url(); ?>/public/assets/img/pdf1.svg" witdh="30" alt="" class="img-fluid me-2" style="width: 30px;">
                                                        <div class="d-flex flex-column">
                                                            <h6 class="text-dark fs-6" style="overflow-wrap: anywhere!important;"> <?= $attachment_filename; ?></h6>
                                                            <span class="text-gray1" style="font-size: 11px;"> <?php echo $this->formatSizeUnits($attachment_filesize); ?></span>
                                                        </div>
                                                    </div>
                                                    <a download="<?= $attachment_filename; ?>" href="<?= base_url(); ?>/public/assets/docs/uploads/attachment/<?= $attachment; ?>" class="text-blue assignDownload p-3 ms-3"><img src="<?= base_url(); ?>/public/assets/img/download_red.svg" alt="" class="me-1 mb-1" width="13"></a>
                                                </div>
                                            <?php } elseif ($attachment_ext == "xls" || $attachment_ext == "XLS" || $attachment_ext == "xlsx" || $attachment_ext == "XLSX"  ||  $attachment_ext == "csv" ||  $attachment_ext == "CSV") { ?>
                                                <div class="assignmentPdf bg-white rounded d-flex">
                                                    <div class="d-flex p-2">
                                                        <img src="<?= base_url(); ?>/public/assets/img/xl.svg" witdh="30" alt="" class="img-fluid me-2" style="width: 30px;">
                                                        <div class="d-flex flex-column">
                                                            <h6 class="text-dark fs-6" style="overflow-wrap: anywhere!important;"> <?= $attachment_filename; ?></h6>
                                                            <span class="text-gray1" style="font-size: 11px;"> <?php echo $this->formatSizeUnits($attachment_filesize); ?></span>
                                                        </div>
                                                    </div>
                                                    <a download="<?= $attachment_filename; ?>" href="<?= base_url(); ?>/public/assets/docs/uploads/attachment/<?= $attachment; ?>" class="text-blue assignDownload p-3 ms-3"><img src="<?= base_url(); ?>/public/assets/img/download_red.svg" alt="" class="me-1 mb-1" width="13"></a>
                                                </div>
                                            <?php } elseif ($attachment_ext == "m4a" || $attachment_ext == "M4A" || $attachment_ext == "mp3" || $attachment_ext == "MP3" || $attachment_ext == "wav" || $attachment_ext == "WAV") { ?>
                                                <div class="assignmentPdf bg-white rounded d-flex">
                                                    <div class="d-flex p-2">
                                                        <img src="<?= base_url(); ?>/public/assets/img/audio.svg" witdh="30" alt="" class="img-fluid me-2" style="width: 30px;">
                                                        <div class="d-flex flex-column">
                                                            <h6 class="text-dark fs-6" style="overflow-wrap: anywhere!important;"> <?= $attachment_filename; ?></h6>
                                                            <span class="text-gray1" style="font-size: 11px;"> <?php echo $this->formatSizeUnits($attachment_filesize); ?></span>
                                                        </div>
                                                    </div>
                                                    <a download="<?= $attachment_filename; ?>" href="<?= base_url(); ?>/public/assets/docs/uploads/attachment/<?= $attachment; ?>" class="text-blue assignDownload p-3 ms-3"><img src="<?= base_url(); ?>/public/assets/img/download_red.svg" alt="" class="me-1 mb-1" width="13"></a>
                                                </div>
                                            <?php } elseif ($attachment_ext == 'doc' || $attachment_ext == 'docx') { ?>
                                                <div class="assignmentPdf bg-white rounded d-flex">
                                                    <div class="d-flex p-2">
                                                        <img src="<?= base_url(); ?>/public/assets/img/doc.svg" witdh="30" alt="" class="img-fluid me-2" style="width: 30px;">
                                                        <div class="d-flex flex-column">
                                                            <h6 class="text-dark fs-6" style="overflow-wrap: anywhere!important;"> <?= $attachment_filename; ?></h6>
                                                            <span class="text-gray1" style="font-size: 11px;"> <?php echo $this->formatSizeUnits($attachment_filesize); ?></span>
                                                        </div>
                                                    </div>
                                                    <a download="<?= $attachment_filename; ?>" href="<?= base_url(); ?>/public/assets/docs/uploads/attachment/<?= $attachment; ?>" class="text-blue assignDownload p-3 ms-3"><img src="<?= base_url(); ?>/public/assets/img/download_red.svg" alt="" class="me-1 mb-1" width="13"></a>
                                                </div>
                                            <?php } elseif ($attachment_ext == "pptx" || $attachment_ext == "PPTX" || $attachment_ext == "ppt" || $attachment_ext == "PPT") { ?>
                                                <div class="assignmentPdf bg-white rounded d-flex">
                                                    <div class="d-flex p-2">
                                                        <img src="<?= base_url(); ?>/public/assets/img/ppt.svg" witdh="30" alt="" class="img-fluid me-2" style="width: 30px;">
                                                        <div class="d-flex flex-column">
                                                            <h6 class="text-dark fs-6" style="overflow-wrap: anywhere!important;"> <?= $attachment_filename; ?></h6>
                                                            <span class="text-gray1" style="font-size: 11px;"> <?php echo $this->formatSizeUnits($attachment_filesize); ?></span>
                                                        </div>
                                                    </div>
                                                    <a download="<?= $attachment_filename; ?>" href="<?= base_url(); ?>/public/assets/docs/uploads/attachment/<?= $attachment; ?>" class="text-blue assignDownload p-3 ms-3"><img src="<?= base_url(); ?>/public/assets/img/download_red.svg" alt="" class="me-1 mb-1" width="13"></a>
                                                </div>
                                            <?php } elseif ($attachment_ext == "TXT" || $attachment_ext == "txt") { ?>
                                                <div class="assignmentPdf bg-white rounded d-flex">
                                                    <div class="d-flex p-2">
                                                        <img src="<?= base_url(); ?>/public/assets/img/note1.svg" witdh="30" alt="" class="img-fluid me-2" style="width: 30px;">
                                                        <div class="d-flex flex-column">
                                                            <h6 class="text-dark fs-6" style="overflow-wrap: anywhere!important;"> <?= $attachment_filename; ?></h6>
                                                            <span class="text-gray1" style="font-size: 11px;"> <?php echo $this->formatSizeUnits($attachment_filesize); ?></span>
                                                        </div>
                                                    </div>
                                                    <a download="<?= $attachment_filename; ?>" href="<?= base_url(); ?>/public/assets/docs/uploads/attachment/<?= $attachment; ?>" class="text-blue assignDownload p-3 ms-3"><img src="<?= base_url(); ?>/public/assets/img/download_red.svg" alt="" class="me-1 mb-1" width="13"></a>
                                                </div>
                                            <?php } ?>

                                        </div>
                                        <sub class="time w-100 d-inline-block"><?= $newTime = date("h:i a", strtotime($date)); ?> </sub>
                                    <?php } ?>
                                </li>
                            <?php  } elseif ($type == '2') {
                                $where = array('internship_id' => $chat->internship_id);
                                $internship_details = $Candidate_model->fetch_table_row('employer_post_internship', $where);
                                if ($internship_details->profile != '0') {
                                    $internship_name = $Candidate_model->get_master_name('master_profile', $internship_details->profile, 'profile');
                                } else {
                                    $internship_name = $internship_details->other_profile;
                                } ?>
                                <li class="sender" id="sample_scroll<?= $id; ?>">
                                    <div class="assignment">
                                        <div class="cardBg1 p-2">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <div class="d-flex justify-content-start align-items-center flex-wrap">
                                                    <h4 class="text-center fs-sm-6 fs-4" style="color: #00000080;"><img src="<?= base_url(); ?>/public/assets/img/chat_assign1.svg" alt="" class="me-1">Assignment&nbsp;:&nbsp;</h4>
                                                    <h4 class="fw-medium fs-4 fs-sm-6 text-start" style="color:#2B366D;"><?= $title; ?></h4>
                                                </div>
                                            </div>

                                            <div class="d-flex justify-content-start align-items-center gap-2 mb-4 flex-wrap">
                                                <div class="bdr_teak align-self-start px-3 py-0 flex-wrap">
                                                    <span class="teak_light f-12">Internship :</span>
                                                    <span class="teak_light fw-semibold f-12 ps-0"><?= $internship_name; ?>
                                                    </span>
                                                </div>
                                                <div class="bdr_red align-self-start px-3 py-0">
                                                    <span class="red_light f-12">Last Date :</span>
                                                    <span class="red_light fw-semibold f-12 ps-0">
                                                        <?= $newTime1 = date("d", strtotime($last_date_sub)); ?><sup>th</sup> <?= $newTime2 = date("M Y", strtotime($last_date_sub)); ?>
                                                    </span>
                                                </div>
                                            </div>
                                            <!-- <h5 class="fw-medium text-start mt-3" style="color:#2B366D;"><?= $title; ?></h5> -->
                                            <p class="mb-0"><?= $file_name; ?></p>
                                            <?php if ($type == '2' && $link != '') { ?>
                                                <div class="assignmentLink text-start d-flex mt-2">
                                                    <img src="<?= base_url(); ?>/public/assets/img/assignment_link.svg" alt="" class=" align-self-start me-2" width="14">
                                                    <a target="_blank" href="<?= $link; ?>" class="text-blue" style="line-height: 16px; overflow-wrap:anywhere;"><?= $link; ?></a>
                                                </div>
                                            <?php } ?>

                                            <?php if (!empty($attachment_filename)) { ?>
                                                <div class="d-flex justify-content-start align-items-center mt-3">
                                                    <div class="assignmentPdf bg-white rounded d-flex">
                                                        <div class="d-flex p-2">

                                                            <?php if ($attachment_ext == "png" || $attachment_ext == "PNG" || $attachment_ext == "jpg" || $attachment_ext == "JPG"  || $attachment_ext == "jpeg" || $attachment_ext == "JPEG" || $attachment_ext == "tif" || $attachment_ext == "TIF" || $attachment_ext == "tiff" || $attachment_ext == "TIFF") { ?>
                                                                <img src="<?= base_url(); ?>/public/assets/img/image.svg" witdh="40" alt="" class="img-fluid me-2" style="width: 40px;">
                                                            <?php } elseif ($attachment_ext == 'pdf' || $attachment_ext == 'PDF') { ?>
                                                                <img src="<?= base_url(); ?>/public/assets/img/pdf_red.svg" witdh="40" alt="" class="img-fluid me-2" style="width: 40px;">
                                                            <?php  } elseif ($attachment_ext == "xls" || $attachment_ext == "XLS" || $attachment_ext == "xlsx" || $attachment_ext == "XLSX"  ||  $attachment_ext == "csv" ||  $attachment_ext == "CSV") { ?>
                                                                <img src="<?= base_url(); ?>/public/assets/img/xl.svg" witdh="40" alt="" class="img-fluid me-2" style="width: 40px;">
                                                            <?php } elseif ($attachment_ext == "m4a" || $attachment_ext == "M4A" || $attachment_ext == "mp3" || $attachment_ext == "MP3" || $attachment_ext == "wav" || $attachment_ext == "WAV") { ?>
                                                                <img src="<?= base_url(); ?>/public/assets/img/audio.svg" witdh="40" alt="" class="img-fluid me-2" style="width: 40px;">
                                                            <?php } elseif ($attachment_ext == 'doc' || $attachment_ext == 'docx') { ?>
                                                                <img src="<?= base_url(); ?>/public/assets/img/doc.svg" witdh="40" alt="" class="img-fluid me-2" style="width: 40px;">
                                                            <?php } elseif ($attachment_ext == "pptx" || $attachment_ext == "PPTX" || $attachment_ext == "ppt" || $attachment_ext == "PPT") { ?>
                                                                <img src="<?= base_url(); ?>/public/assets/img/ppt.svg" witdh="40" alt="" class="img-fluid me-2" style="width: 40px;">
                                                            <?php } elseif ($attachment_ext == "TXT" || $attachment_ext == "txt") { ?>
                                                                <img src="<?= base_url(); ?>/public/assets/img/note1.svg" witdh="40" alt="" class="img-fluid me-2" style="width: 40px;">
                                                            <?php } ?>
                                                            <!-- <img src="<?= base_url(); ?>/public/assets/img/chat_pdf.svg" alt="" class="me-2"> -->
                                                            <div class="d-flex flex-column">
                                                                <h6 class="text-dark fs-6" style="overflow-wrap:anywhere;"> <?= $attachment_filename; ?></h6>
                                                                <span class="text-gray1" style="font-size: 11px;"><?php echo $this->formatSizeUnits($attachment_filesize); ?></span>
                                                            </div>
                                                        </div>

                                                        <a download="<?= $attachment_filename; ?>" href="<?= base_url(); ?>/public/assets/docs/uploads/attachment/<?= $attachment; ?>" class="text-blue assignDownload p-3 ms-3"><img src="<?= base_url(); ?>/public/assets/img/download_red.svg" alt="" class="me-1 mb-1" width="13"></a>
                                                    </div>

                                                </div>
                                            <?php } ?>
                                            <?php if ($assignment_status == '0') { ?>
                                                <ul class="d-flex justify-content-end ps-0 my-2 mt-3">

                                                    <li><button type="button" class="btn btn-reschedule px-2 py-1 ms-0" data-bs-toggle="modal" data-bs-target="#assignment_submit" onclick="accept1('<?php echo $id; ?>','<?php echo $title; ?>')">Submit Assignment</button></li>
                                                </ul>
                                            <?php } else { ?>
                                                <!-- <span class="badge badge-accept fw-normal">Assignment Submited</span> -->
                                                <button type="button" class="btn btn-green rounded px-2 py-1 ms-0 mt-3 btn-done">Assignment Submited&emsp;<i class="fa fa-check-circle-o fa-lg"></i></button>
                                            <?php } ?>
                                        </div>

                                    </div>
                                    <sub class="time w-100 d-inline-block"><?= $newTime = date("h:i a", strtotime($date)); ?> </sub>
                                </li>
                            <?php } elseif ($type == '3') {
                                $where = array('internship_id' => $chat->internship_id);
                                $internship_details = $Candidate_model->fetch_table_row('employer_post_internship', $where);
                                if ($internship_details->profile != '0') {
                                    $internship_name = $Candidate_model->get_master_name('master_profile', $internship_details->profile, 'profile');
                                } else {
                                    $internship_name = $internship_details->other_profile;
                                } ?>
                                <li class="sender" id="sample_scroll<?= $id; ?>">
                                    <div class="assignment interviewTemp p-2 mb-2">
                                        <div class="internview p-2">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <div class="d-flex justify-content-start align-items-center flex-wrap">
                                                    <h4 class="text-center fs-4 fs-sm-6" style="color: #00000080;"><img src="<?= base_url(); ?>/public/assets/img/chat_schedule.svg" alt="" class="me-1">Interview&nbsp;:&nbsp;</h4>
                                                    <h4 class="fw-medium fs-4 fs-sm-6 text-start" style="color:#2B366D;"><?= $title; ?></h4>
                                                    <!-- 2B366D -->
                                                </div>
                                                <!-- <span class="badge badge-reschedule align-self-center py-2 fw-normal"><?= $title; ?></span> -->
                                            </div>
                                            <div class="d-flex flex-wrap justify-content-start align-items-center gap-2 mb-4">
                                                <div class="bdr_teak align-self-start px-2 ms-0">
                                                    <span class="teak_light f-12">Internship :</span>
                                                    <span class="teak_light fw-semibold f-12 ps-0"><?= $internship_name; ?>
                                                    </span>
                                                </div>
                                                <div class="bdr_red align-self-start px-2">
                                                    <span class="red_light f-12">Mode of Interview :</span>
                                                    <span class="red_light fw-semibold f-12 ps-0">
                                                        <?php if ($interview_mode == 1) {
                                                            echo "Video Call";
                                                        } elseif ($interview_mode == 2) {
                                                            echo "Phone";
                                                        } else {
                                                            echo "In-office";
                                                        } ?>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="text-start">
                                                <?php if ($type == '3' && $link != '') { ?>
                                                    <div class="assignmentLink mt-2">
                                                        <img src="<?= base_url(); ?>/public/assets/img/assignment_link.svg" alt="" class="me-2 mb-1" width="14">
                                                        <a target="_blank" href="<?= $link; ?>" class="text-blue" style="overflow-wrap:anywhere;"><?= $link; ?></a>
                                                    </div>
                                                <?php } ?>
                                                <p class="w-100 mt-0 mt-md-3 text-start mb-0 mb-md-3" style="overflow-wrap: anywhere!important;"> <?= $interview_description; ?> </p>
                                            </div>
                                            <div class="d-flex flex-wrap justify-content-between align-items-start row">
                                                <div class="col-md-6 lastDate mb-1">
                                                    <div class="intLastDate  text-center py-2 px-3">
                                                        <p class="text-blue bg-transparent mb-0"><?= $newTime2 = date("d", strtotime($interview_date)); ?><sup>th</sup> <?= $newTime1 = date("M Y", strtotime($interview_date)); ?><br><img src="<?= base_url(); ?>/public/assets/img/chat_lastdate.svg" alt="" class="me-2 mb-1" width=""><b class="d-inline-block text-blue">Interview Date</b></p>
                                                    </div>
                                                </div>
                                                <?php if ($interview_duration == '15') {
                                                    $endTime = strtotime("+15 minutes", strtotime($interview_time));
                                                    $end_time1 = date('h:i a', $endTime);
                                                } elseif ($interview_duration == '30') {
                                                    $endTime = strtotime("+30 minutes", strtotime($interview_time));
                                                    $end_time1 = date('h:i a', $endTime);
                                                } elseif ($interview_duration == '60') {
                                                    $endTime = strtotime("+60 minutes", strtotime($interview_time));
                                                    $end_time1 = date('h:i a', $endTime);
                                                }

                                                ?>
                                                <div class="col-md-6 lastDate mb-1">
                                                    <div class="intLastDate text-center py-2 px-3">
                                                        <p class="text-blue bg-transparent mb-0"><?= $newTime1 = date("h:i a", strtotime($interview_time)); ?> - <?= $end_time1 ?><br><img src="<?= base_url(); ?>/public/assets/img/time.svg" alt="" class="me-2 mb-1" width="19"><b class="d-inline-block text-blue">Time</b></p>
                                                    </div>
                                                </div>


                                            </div>
                                        </div>

                                        <?php if ($interview_status == '0') { ?>
                                            <ul class="d-flex justify-content-end ps-0 my-3 pe-2">
                                                <li><button type="button" class="btn btn-green rounded px-2 py-1 ms-2" data-bs-toggle="modal" data-bs-target="#accept" onclick="accept('<?php echo $id; ?>')">Accept</button></li>
                                                <li><button type="button" class="btn btn-danger rounded px-2 py-1 ms-2" data-bs-toggle="modal" data-bs-target="#reject" onclick="accept('<?php echo $id; ?>')">Decline</button></li>
                                                <li><button type="button" class="btn btn-reschedule px-2 py-1 ms-2" data-bs-toggle="modal" data-bs-target="#reschedule" onclick="accept('<?php echo $id; ?>')">Reschedule</button></li>
                                            </ul>
                                        <?php } elseif ($interview_status == '4') { ?>
                                            <span class="badge badge-reject fw-normal">Interview Invitation Canceled</span>
                                        <?php  } ?>
                                    </div>
                                </li>
                            <?php } elseif ($type == '6') {
                                if (!empty($assignment_id)) {
                                    $where = array('id' => $assignment_id);
                                    $replay_details = $Candidate_model->fetch_table_row('chat', $where);
                                }

                            ?>

                                <li class="sender">
                                    <a href="#sample_scrollnew<?= $assignment_id; ?>">
                                        <div class="msgCnt">
                                            <div style="background:#d8e2f4; border-radius:8px; border-left: 3px solid #95a9cc;" class="p-1 ps-2">
                                                <small>
                                                    <p class="text-dark text-start mb-1"><img src="<?= base_url(); ?>/public/assets/img/chat_assign1.svg" alt="" class="me-1"> Assignment Feedback</p>
                                                </small>
                                                <small>
                                                    <p class="text-dark text-start mb-1"> <?= $replay_details->title; ?></p>
                                                </small>

                                            </div>
                                            <p class="text-start text-dark"><?= $messageBody; ?></p>

                                        </div> <sub class="time w-100 d-inline-block"><?= $newTime = date("h:i a", strtotime($date)); ?> </sub>
                                    </a>
                                </li>

                            <?php }  ?>


                        </ul>
                    </div>

                    <!--^^ CHAT BOX CONTENTE FOR RECIVED MESSAGES ^^-->
                <?php } else { ?>
                    <div class="msg-body">
                        <ul class="ps-0 d-flex flex-column">
                            <?php if ($type == '1') { ?>
                                <li class="reply">
                                    <?php if (!empty($messageBody)) { ?>
                                        <div class="msgCnt">
                                            <p class="text-start"> <?= $messageBody; ?></p>
                                        </div> <sub class="time w-100 d-inline-block"><?= $newTime = date("h:i a", strtotime($date)); ?> <?php if ($status == '1') { ?><img src="<?= base_url(); ?>/public/assets/img/chat_tick_g.svg" alt="read" class="ms-1" width="16"><?php } elseif ($status == '2') { ?> <img src="<?= base_url(); ?>/public/assets/img/chat_tick_b.svg" alt="read" class="ms-1" width="16"> <?php } ?></sub>

                                    <?php  } else { ?>

                                        <div class="d-flex justify-content-end align-items-center">
                                            <?php if ($attachment_ext == "png" || $attachment_ext == "PNG" || $attachment_ext == "jpg" || $attachment_ext == "JPG"  || $attachment_ext == "jpeg" || $attachment_ext == "JPEG" || $attachment_ext == "tif" || $attachment_ext == "TIF" || $attachment_ext == "tiff" || $attachment_ext == "TIFF") { ?>
                                                <a download href="<?= base_url(); ?>/public/assets/docs/uploads/attachment/<?= $attachment; ?>" title="ImageName"> <img src="<?= base_url(); ?>/public/assets/docs/uploads/attachment/<?= $attachment; ?>" alt="" class="me-1" style="object-fit:fill; max-height: 100%; width:200px;"> </a>
                                            <?php } elseif ($attachment_ext == 'pdf' || $attachment_ext == 'PDF') { ?>
                                                <div class="assignmentPdf bg-white rounded d-flex">
                                                    <div class="d-flex p-2">
                                                        <img src="<?= base_url(); ?>/public/assets/img/pdf1.svg" witdh="30" alt="" class="img-fluid me-2" style="width: 30px;">
                                                        <div class="d-flex flex-column text-start">
                                                            <h6 class="text-dark fs-6" style="overflow-wrap: anywhere!important;"> <?= $attachment_filename; ?></h6>
                                                            <span class="text-gray1" style="font-size: 11px;"> <?php echo $this->formatSizeUnits($attachment_filesize); ?></span>
                                                        </div>
                                                    </div>
                                                    <a download="<?= $attachment_filename; ?>" href="<?= base_url(); ?>/public/assets/docs/uploads/attachment/<?= $attachment; ?>" class="text-blue assignDownload p-3 ms-3"><img src="<?= base_url(); ?>/public/assets/img/download_red.svg" alt="" class="me-1 mb-1" width="13"></a>
                                                </div>
                                            <?php } elseif ($attachment_ext == "xls" || $attachment_ext == "XLS" || $attachment_ext == "xlsx" || $attachment_ext == "XLSX"  ||  $attachment_ext == "csv" ||  $attachment_ext == "CSV") { ?>
                                                <div class="assignmentPdf bg-white rounded d-flex">
                                                    <div class="d-flex p-2">
                                                        <img src="<?= base_url(); ?>/public/assets/img/xl.svg" witdh="30" alt="" class="img-fluid me-2" style="width: 30px;">
                                                        <div class="d-flex flex-column text-start">
                                                            <h6 class="text-dark fs-6" style="overflow-wrap: anywhere!important;"> <?= $attachment_filename; ?></h6>
                                                            <span class="text-gray1" style="font-size: 11px;"> <?php echo $this->formatSizeUnits($attachment_filesize); ?></span>
                                                        </div>
                                                    </div>
                                                    <a download="<?= $attachment_filename; ?>" href="<?= base_url(); ?>/public/assets/docs/uploads/attachment/<?= $attachment; ?>" class="text-blue assignDownload p-3 ms-3"><img src="<?= base_url(); ?>/public/assets/img/download_red.svg" alt="" class="me-1 mb-1" width="13"></a>
                                                </div>
                                            <?php } elseif ($attachment_ext == "m4a" || $attachment_ext == "M4A" || $attachment_ext == "mp3" || $attachment_ext == "MP3" || $attachment_ext == "wav" || $attachment_ext == "WAV") { ?>
                                                <div class="assignmentPdf bg-white rounded d-flex">
                                                    <div class="d-flex p-2">
                                                        <img src="<?= base_url(); ?>/public/assets/img/audio.svg" witdh="30" alt="" class="img-fluid me-2" style="width: 30px;">
                                                        <div class="d-flex flex-column text-start">
                                                            <h6 class="text-dark fs-6" style="overflow-wrap: anywhere!important;"> <?= $attachment_filename; ?></h6>
                                                            <span class="text-gray1" style="font-size: 11px;"> <?php echo $this->formatSizeUnits($attachment_filesize); ?></span>
                                                        </div>
                                                    </div>
                                                    <a download="<?= $attachment_filename; ?>" href="<?= base_url(); ?>/public/assets/docs/uploads/attachment/<?= $attachment; ?>" class="text-blue assignDownload p-3 ms-3"><img src="<?= base_url(); ?>/public/assets/img/download_red.svg" alt="" class="me-1 mb-1" width="13"></a>
                                                </div>
                                            <?php } elseif ($attachment_ext == 'doc' || $attachment_ext == 'docx') { ?>
                                                <div class="assignmentPdf bg-white rounded d-flex">
                                                    <div class="d-flex p-2">
                                                        <img src="<?= base_url(); ?>/public/assets/img/doc.svg" witdh="30" alt="" class="img-fluid me-2" style="width: 30px;">
                                                        <div class="d-flex flex-column text-start">
                                                            <h6 class="text-dark fs-6" style="overflow-wrap: anywhere!important;"> <?= $attachment_filename; ?></h6>
                                                            <span class="text-gray1" style="font-size: 11px;"> <?php echo $this->formatSizeUnits($attachment_filesize); ?></span>
                                                        </div>
                                                    </div>
                                                    <a download="<?= $attachment_filename; ?>" href="<?= base_url(); ?>/public/assets/docs/uploads/attachment/<?= $attachment; ?>" class="text-blue assignDownload p-3 ms-3"><img src="<?= base_url(); ?>/public/assets/img/download_red.svg" alt="" class="me-1 mb-1" width="13"></a>
                                                </div>
                                            <?php } elseif ($attachment_ext == "pptx" || $attachment_ext == "PPTX" || $attachment_ext == "ppt" || $attachment_ext == "PPT") { ?>
                                                <div class="assignmentPdf bg-white rounded d-flex">
                                                    <div class="d-flex p-2">
                                                        <img src="<?= base_url(); ?>/public/assets/img/ppt.svg" witdh="30" alt="" class="img-fluid me-2" style="width: 30px;">
                                                        <div class="d-flex flex-column text-start">
                                                            <h6 class="text-dark fs-6" style="overflow-wrap: anywhere!important;"> <?= $attachment_filename; ?></h6>
                                                            <span class="text-gray1" style="font-size: 11px;"> <?php echo $this->formatSizeUnits($attachment_filesize); ?></span>
                                                        </div>
                                                    </div>
                                                    <a download="<?= $attachment_filename; ?>" href="<?= base_url(); ?>/public/assets/docs/uploads/attachment/<?= $attachment; ?>" class="text-blue assignDownload p-3 ms-3"><img src="<?= base_url(); ?>/public/assets/img/download_red.svg" alt="" class="me-1 mb-1" width="13"></a>
                                                </div>
                                            <?php } elseif ($attachment_ext == "TXT" || $attachment_ext == "txt") { ?>
                                                <div class="assignmentPdf bg-white rounded d-flex">
                                                    <div class="d-flex p-2">
                                                        <img src="<?= base_url(); ?>/public/assets/img/note1.svg" witdh="30" alt="" class="img-fluid me-2" style="width: 30px;">
                                                        <div class="d-flex flex-column text-start">
                                                            <h6 class="text-dark fs-6" style="overflow-wrap: anywhere!important;"> <?= $attachment_filename; ?></h6>
                                                            <span class="text-gray1" style="font-size: 11px;"> <?php echo $this->formatSizeUnits($attachment_filesize); ?></span>
                                                        </div>
                                                    </div>
                                                    <a download="<?= $attachment_filename; ?>" href="<?= base_url(); ?>/public/assets/docs/uploads/attachment/<?= $attachment; ?>" class="text-blue assignDownload p-3 ms-3"><img src="<?= base_url(); ?>/public/assets/img/download_red.svg" alt="" class="me-1 mb-1" width="13"></a>
                                                </div>
                                            <?php } ?>

                                        </div>
                                        <sub class="time w-100 d-inline-block"><?= $newTime = date("h:i a", strtotime($date)); ?> <?php if ($status == '1') { ?><img src="<?= base_url(); ?>/public/assets/img/chat_tick_g.svg" alt="read" class="ms-1" width="16"><?php } elseif ($status == '2') { ?> <img src="<?= base_url(); ?>/public/assets/img/chat_tick_b.svg" alt="read" class="ms-1" width="16"> <?php } ?></sub>
                                </li>
                            <?php } ?>
                        <?php  } elseif ($type == '4') { ?>
                            <li class="reply" id="sample_scrollnew<?= $id; ?>">
                                <a href="#sample_scroll<?= $assignment_id; ?>">
                                    <div class="msgCnt py-3 px-3">
                                        <div style="background:#c5dbff; border-radius:8px; border-left: 3px solid #24337D;" class="p-1 ps-2">
                                            <small>
                                                <p class="text-dark text-start mb-1"><img src="<?= base_url(); ?>/public/assets/img/chat_assign1.svg" alt="" class="me-1"> Assignment Received</p>
                                            </small>
                                            <p class="fw-medium text-start p-0" style="color:#00000060;"><?= $title; ?></p>
                                        </div>
                                        <p class="text-start mb-3 mt-3 text-dark"><?= $file_name; ?></p>
                                        <?php if ($type == '4' && $link != '') { ?>
                                            <div class="assignmentLink text-start d-flex mb-3">
                                                <img src="<?= base_url(); ?>/public/assets/img/assignment_link.svg" alt="" class=" align-self-start me-2" width="14">
                                                <a target="_blank" href="<?= $link; ?>" class="text-blue" style="line-height: 16px; overflow-wrap:anywhere;"><?= $link; ?></a>
                                            </div>
                                        <?php } ?>
                                        <?php if (!empty($attachment_filename)) { ?>
                                            <div class="d-flex justify-content-end align-items-center mt-3">
                                                <div class="assignmentPdf bg-white rounded d-flex">
                                                    <div class="d-flex p-2">
                                                        <?php if ($attachment_ext == "png" || $attachment_ext == "PNG" || $attachment_ext == "jpg" || $attachment_ext == "JPG"  || $attachment_ext == "jpeg" || $attachment_ext == "JPEG" || $attachment_ext == "tif" || $attachment_ext == "TIF" || $attachment_ext == "tiff" || $attachment_ext == "TIFF") { ?>
                                                            <img src="<?= base_url(); ?>/public/assets/img/image.svg" witdh="40" alt="" class="img-fluid me-2" style="width: 30px;">
                                                        <?php } elseif ($attachment_ext == 'pdf' || $attachment_ext == 'PDF') { ?>
                                                            <img src="<?= base_url(); ?>/public/assets/img/pdf_red.svg" witdh="40" alt="" class="img-fluid me-2" style="width: 30px;">
                                                        <?php  } elseif ($attachment_ext == "xls" || $attachment_ext == "XLS" || $attachment_ext == "xlsx" || $attachment_ext == "XLSX"  ||  $attachment_ext == "csv" ||  $attachment_ext == "CSV") { ?>
                                                            <img src="<?= base_url(); ?>/public/assets/img/xl.svg" witdh="40" alt="" class="img-fluid me-2" style="width: 30px;">
                                                        <?php } elseif ($attachment_ext == "m4a" || $attachment_ext == "M4A" || $attachment_ext == "mp3" || $attachment_ext == "MP3" || $attachment_ext == "wav" || $attachment_ext == "WAV") { ?>
                                                            <img src="<?= base_url(); ?>/public/assets/img/audio.svg" witdh="40" alt="" class="img-fluid me-2" style="width: 30px;">
                                                        <?php } elseif ($attachment_ext == 'doc' || $attachment_ext == 'docx') { ?>
                                                            <img src="<?= base_url(); ?>/public/assets/img/doc.svg" witdh="40" alt="" class="img-fluid me-2" style="width: 30px;">
                                                        <?php } elseif ($attachment_ext == "pptx" || $attachment_ext == "PPTX" || $attachment_ext == "ppt" || $attachment_ext == "PPT") { ?>
                                                            <img src="<?= base_url(); ?>/public/assets/img/ppt.svg" witdh="40" alt="" class="img-fluid me-2" style="width: 30px;">
                                                        <?php } elseif ($attachment_ext == "TXT" || $attachment_ext == "txt") { ?>
                                                            <img src="<?= base_url(); ?>/public/assets/img/pdf_red.svg" witdh="40" alt="" class="img-fluid me-2" style="width: 30px;">
                                                        <?php } ?>
                                                        <!-- <img src="<?= base_url(); ?>/public/assets/img/chat_pdf.svg" alt="" class="me-2"> -->
                                                        <div class="d-flex flex-column text-start">
                                                            <h6 class="text-dark fs-6" style="overflow-wrap: anywhere;"> <?= $attachment_filename; ?></h6>
                                                            <span class="text-gray1" style="font-size: 11px;"><?php echo $this->formatSizeUnits($attachment_filesize); ?></span>
                                                        </div>
                                                    </div>
                                                    <a download="<?= $attachment_filename; ?>" href="<?= base_url(); ?>/public/assets/docs/uploads/attachment/<?= $attachment; ?>" class="text-blue assignDownload p-3 ms-3"><img src="<?= base_url(); ?>/public/assets/img/download_red.svg" alt="" class="me-1 mb-1" width="13"></a>
                                                </div>
                                            </div> <?php } ?>
                                        <!-- <a href="#" class="btn btn-outlined-blue mt-3 px-2 py-1"><img src="<?= base_url(); ?>/public/assets/img/icon_confirm.svg" alt="" width="17" class="mb-1 me-1 filterWhite"> Mark As Evaluated</a>
                                                        <a href="#" class="btn btn-prim mt-3 px-2 py-1"><img src="<?= base_url(); ?>/public/assets/img/icon_confirm.svg" alt="" width="17" class="mb-1 me-1 filterWhite"> Marked As Evaluated</a> -->
                                    </div>
                                    <sub class="time w-100 d-inline-block"><?= $newTime = date("h:i a", strtotime($date)); ?> <?php if ($status == '1') { ?><img src="<?= base_url(); ?>/public/assets/img/chat_tick_g.svg" alt="read" class="ms-1" width="16"><?php } elseif ($status == '2') { ?> <img src="<?= base_url(); ?>/public/assets/img/chat_tick_b.svg" alt="read" class="ms-1" width="16"> <?php } ?></sub>
                                </a>
                            </li>

                        <?php } elseif ($type == '5') {
                                if (!empty($assignment_id)) {
                                    $where = array('id' => $assignment_id);
                                    $replay_details = $Candidate_model->fetch_table_row('chat', $where);
                                }

                        ?>

                            <li class="reply">
                                <a href="#sample_scroll<?= $assignment_id; ?>">
                                    <div class="msgCnt">
                                        <div style="background:#d8e2f4; border-radius:8px; border-left: 3px solid #95a9cc;" class="p-1 ps-2">
                                            <small>
                                                <p class="text-dark text-start mb-1"> <?= $replay_details->title; ?></p>
                                            </small>

                                        </div>
                                        <p class="text-start text-dark"><?= $messageBody; ?></p>

                                    </div> <sub class="time w-100 d-inline-block"><?= $newTime = date("h:i a", strtotime($date)); ?> <?php if ($status == '1') { ?><img src="<?= base_url(); ?>/public/assets/img/chat_tick_g.svg" alt="read" class="ms-1" width="16"><?php } elseif ($status == '2') { ?> <img src="<?= base_url(); ?>/public/assets/img/chat_tick_b.svg" alt="read" class="ms-1" width="16"> <?php } ?></sub>
                                </a>
                            </li>

                        <?php } ?>

                        </ul>
                    </div>
                    <!-- CHAT BOX CONTENTE FOR SENDED MESSAGES -->

                    <!--^^ CHAT BOX CONTENTE FOR SENDED MESSAGES ^^-->
            <?php }
            }
        } else { ?> <!-- IF NO DATA IN TABLE -->

            <div class="text-muted mx-auto text-center d-block" style="bottom:0px">
                <p>No Conversation Yet</p>
            </div>

        <?php
        }
    } //Function For Getting Messages Common (AJEX)

    public function get_corporate_profile() //Function For Getting profile
    {
        $session = session();
        $Candidate_model = new Candidate_model();

        $receiver_id = $this->request->getVar('receiver_id');
        $userid = $session->get('userid');
        $where = array('status' => '1', 'userid' => $receiver_id);
        $emp_name = $Candidate_model->emp_names('userlogin', $where);
        //  $username = $emp_name->username;
        if ($emp_name->usertype == '2') {
            $sub_admin_company_id = $receiver_id;
        } else {
            $where_sub_admin = array('userid' => $receiver_id);
            $sub_admin_profile = $Candidate_model->fetch_table_row('emp_manage_admins', $where_sub_admin);
            $sub_admin_company_id = $sub_admin_profile->emp_user_id;
        }
        $where_com = array('userid' => $sub_admin_company_id);
        $Company_data = $Candidate_model->fetch_table_row('profile_completion_form', $where_com);


        ?>



        <div class="profSnip mb-4">
            <div class="mb-3">
                <div class="d-flex align-items-center mb-3">
                    <?php if (isset($Company_data->profile_company_logo) && !empty($Company_data->profile_company_logo)) { ?>
                        <?php $check = file_exists(FCPATH . "public/assets/docs/uploads/emp_profile/" . $Company_data->profile_company_logo);
                        ?>
                        <?php if ($check) { ?>
                            <div class="flex-shrink-0 bg-gray rounded-50 text-blue fw-bold fs-6 ms-0">

                                <img class="img-fluid" src="<?= base_url(); ?>/public/assets/docs/uploads/emp_profile/<?php echo $Company_data->profile_company_logo; ?>" alt="">
                            </div>
                        <?php } else { ?>
                            <span class="flex-shrink-0 bg-white rounded-50 border-blue text-blue fw-bold fs-6 ms-0">
                                <span><?php if (!empty($Company_data->profile_company_name)) {
                                            echo $firstStringCharacter = substr($Company_data->profile_company_name, 0, 1);
                                        } ?></span>
                            </span>
                        <?php } ?>
                    <?php } else { ?>

                        <span><?php if (!empty($Company_data->profile_company_name)) {
                                    echo $firstStringCharacter = substr($Company_data->profile_company_name, 0, 1);
                                } ?></span>
                    <?php } ?>
                    <div class="flex-grow-1 ms-3">
                        <h3 class="mb-0 fs-5"><?php echo $Company_data->profile_company_name; ?></h3>
                    </div>
                </div>
            </div>
            <ul class="list-unstyled ps-0 d-flex flex-wrap border-bottom-blue pb-3 flex-column">
                <li class="mb-2">
                    <p class="text-dark mb-2"><img src="<?= base_url(); ?>/public/assets/img/location.svg" alt="Location" class="me-2 mb-1" width="13">
                        <?php if (isset($Company_data->location_name)) {
                            echo $Company_data->location_name;
                        } ?>
                    </p>
                </li>

                <li>
                    <?php if (!empty($Company_data->profile_website_details)) { ?>
                        <p class="text-blue mb-0"><img src="<?= base_url(); ?>/public/assets/img/web.svg" alt="website" class="me-2" width="17"><a class="text-blue" href="<?php echo $Company_data->profile_website_details; ?>" target="_blank"><?php echo $Company_data->profile_website_details; ?></a></p>
                    <?php } ?>
                </li>
            </ul>
        </div>

        <h5 class="text-blue fw-semibold mb-3">About <?php echo $Company_data->profile_company_name; ?></h5>
        <p class="mb-0 f-13"><?php echo $Company_data->profile_company_description; ?></p>



        <?php
    }
    public function work_report($internship_id = NULL)
    {
        if (empty($internship_id)) {
            $internship_id = $this->request->getVar('internship_id_rating');
            $rating_status = $this->request->getVar('rating_status');
            $data['rating'] = $rating_status;
        } else {
            $data['rating'] = '0';
        }
        $session = session();
        $Candidate_model = new Candidate_model();
        $userid    =    $session->get('userid');
        $where = array('status' => '1', 'internship_id' => $internship_id);
        $data['internship_details'] = $Candidate_model->fetch_table_row('employer_post_internship', $where);

        $where_can = array('status' => '1', 'userid' => $userid);
        $data['candidate_details'] = $Candidate_model->fetch_table_row('can_personal_details', $where_can);

        $where_edu = array('status' => '1', 'userid' => $userid);
        $data['candidate_educational_details'] = $Candidate_model->fetch_table_row('can_education_details', $where_edu);

        $where_cou = array('status' => '1', 'candidate_id' => $userid, 'internship_id' => $internship_id);
        $data['internship_applied_list'] = $Candidate_model->fetch_table_row('can_applied_internship', $where_cou);

        $order_by = array('ordercolumn' => 'log_date', 'ordertype' => 'asc');
        $where_log = array('status' => '1', 'user_id' => $userid, 'internship_id' => $internship_id, 'company_id' => $data['internship_details']->company_id);
        $data['log_sheet_details'] = $Candidate_model->fetch_table_data_for_log('can_log_sheet', $where_log, $order_by);

        $where_empl = array('status' => '1', 'userid' => $data['internship_details']->company_id);
        $data['view_profile_details'] = $Candidate_model->fetch_table_row('profile_completion_form', $where_empl);

        $data['candidate_id'] = $userid;

        $dompdf = new \Dompdf\Dompdf(array('enable_remote' => true));

        $dompdf->loadHtml(view('Candidate/work_report', $data));
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        //$dompdf = new Dompdf(array('enable_remote' => true));
        $dompdf->stream("Internship Report.pdf");

        // return view('Candidate/work_report',$data);
    }
    public function accept_interview() //Function For accept interview
    {
        $session = session();
        $Candidate_model = new Candidate_model();
        $id = $this->request->getVar('id');
        $messageTxt = $this->request->getVar('accept_description');
        $current_datetime = $Candidate_model->current_datetime();
        $receiver_id = $this->request->getVar('receiver_id');
        $userid = $session->get('userid');
        $data = [
            'sender_id' => $userid,
            'receiver_id' => $receiver_id,
            'type' => '5',
            'message' => $messageTxt,
            'assignment_id' => $id,
            'created_at' => $current_datetime,
            'message_status' => '1',
        ];
        $result = $Candidate_model->insert_commen('chat', $data);
        $where = array('id' => $id);
        $data = [
            'interview_status' => '1',
        ];
        $update_data = $Candidate_model->update_commen('chat', $where, $data);
        if ($result) {
            echo csrf_hash() . '^' . '1';
        } else {
            echo csrf_hash() . '^' . '0';
        }
    }
    public function decline_interview() //Function For accept interview
    {
        $session = session();
        $Candidate_model = new Candidate_model();
        $id = $this->request->getVar('id');
        $messageTxt = $this->request->getVar('decline_description');
        $current_datetime = $Candidate_model->current_datetime();
        $receiver_id = $this->request->getVar('receiver_id');
        $userid = $session->get('userid');
        $data = [
            'sender_id' => $userid,
            'receiver_id' => $receiver_id,
            'type' => '5',
            'message' => $messageTxt,
            'assignment_id' => $id,
            'created_at' => $current_datetime,
            'message_status' => '1',
        ];
        $result = $Candidate_model->insert_commen('chat', $data);
        $where = array('id' => $id);
        $data = [
            'interview_status' => '2',
        ];
        $update_data = $Candidate_model->update_commen('chat', $where, $data);
        if ($result) {
            $where = array('userid' => $userid);
            $can_details = $Candidate_model->fetch_table_row('can_personal_details', $where);
            $where1 = array('status' => '1', 'userid' => $receiver_id);
            $emp_name = $Candidate_model->fetch_table_row('userlogin', $where1);
            $where = array('id' => $id);
            $interview_details = $Candidate_model->fetch_table_row('chat', $where);
            if ($interview_details->interview_duration == '15') {
                $endTime = strtotime("+15 minutes", strtotime($interview_details->interview_time));
                $end_time1 = date('h:i a', $endTime);
            } elseif ($interview_details->interview_duration == '30') {
                $endTime = strtotime("+30 minutes", strtotime($interview_details->interview_time));
                $end_time1 = date('h:i a', $endTime);
            } elseif ($interview_details->interview_duration == '60') {
                $endTime = strtotime("+60 minutes", strtotime($interview_details->interview_time));
                $end_time1 = date('h:i a', $endTime);
            }
            $start_time = strtotime("+0 minutes", strtotime($interview_details->interview_time));
            $int_start_time = date('h:i a', $start_time);
            $interview_slot = $int_start_time . ' - ' . $end_time1;

            if ($interview_details->interview_mode == 1) {
                $interview_mode_type = 'Video Call';
            } elseif ($interview_details->interview_mode == 2) {
                $interview_mode_type = 'Phone';
            } else {
                $interview_mode_type = 'In-office';
            }
            $interview_date = date("d-m-Y", strtotime($interview_details->interview_date));
            $current_year = date('Y');
            $message = '{ "emp_name" : "' . $emp_name->name . '", "name" : "' . $can_details->profile_full_name . '", "decline_description" : "' . $messageTxt . '", "interview_time" : "' . $interview_slot . '", "interview_mode" : "' . $interview_mode_type . '", "interview_date" : "' . $interview_date . '","year" : ' . $current_year . ' }'; //dynamic contents for template
            // $message = '{ "emp_name" : "'.$emp_name->name.'"}'; //dynamic contents for template
            $subject      = 'Interview Invitation Declined';
            $to_email     =  $emp_name->email;
            $from_content = 'Interview Invitation Declined';
            $template_key = '2d6f.456f260c51ab9602.k1.6780fb90-a772-11ed-bfa0-525400fcd3f1.1862f85a1c9';
            $this->email_send($message, $subject, $to_email, $from_content, $template_key);

            echo csrf_hash() . '^' . '1';
            // $msg_data['msg_data'] = array(
            //     'name' => $can_details->profile_full_name,
            //     'emp_name' => $emp_name->name,
            //     'decline_description' => $messageTxt,
            //     'interview_date' => $interview_details->interview_date,
            //     'interview_time' => $interview_slot,
            //     'interview_mode' => $interview_mode_type
            // ); //dynamic contents for template
            // $message     = view('email_template/interview_decline_can', $msg_data);


            // $this->email_send($message, $subject, $to_email, $from_content);
        } else {
            echo csrf_hash() . '^' . '0';
        }
    }
    public function reschedule_interview() //Function For accept interview
    {
        $session = session();
        $Candidate_model = new Candidate_model();
        $id = $this->request->getVar('id');
        $interview_date = $this->request->getVar('interview_date');

        $interview_time = $this->request->getVar('interview_time');
        $interview_description = $this->request->getVar('interview_description');
        $current_datetime = $Candidate_model->current_datetime();
        $receiver_id = $this->request->getVar('receiver_id');
        $userid = $session->get('userid');
        $newdate2 = date("d", strtotime($interview_date));
        $newdate1 = date("M Y", strtotime($interview_date));
        $newTime1 = date("h:i a", strtotime($interview_time));
        $messageTxt = $interview_description . "<br/>Requested date and time<br/>Date : " . $newdate2 . "<sup>th</sup>" . $newdate1 . "<br/>Time : " . $newTime1 . "<br/>";
        $data = [
            'sender_id' => $userid,
            'receiver_id' => $receiver_id,
            'type' => '5',
            'message' => $messageTxt,
            'assignment_id' => $id,
            'created_at' => $current_datetime,
            'message_status' => '1',
        ];
        $result = $Candidate_model->insert_commen('chat', $data);
        $where = array('id' => $id);
        $data = [
            'interview_status' => '3',
        ];
        $update_data = $Candidate_model->update_commen('chat', $where, $data);
        if ($result) {
            echo csrf_hash() . '^' . '1';
        } else {
            echo csrf_hash() . '^' . '0';
        }
    }
    function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            $bytes = $bytes . ' bytes';
        } elseif ($bytes == 1) {
            $bytes = $bytes . ' byte';
        } else {
            $bytes = '0 bytes';
        }

        return $bytes;
    }

    public function update_candidate_work_location() //Function For accept interview
    {
        $session = session();
        $Candidate_model = new Candidate_model();
        $location_id = $this->request->getVar('location_id');
        $candidate_userid = $this->request->getVar('candidate_userid');
        $internship_id = $this->request->getVar('internship_id');

        $where = array('candidate_id' => $candidate_userid, 'internship_id' => $internship_id);
        $data = [
            'work_location' => $location_id,
        ];
        $update_data = $Candidate_model->update_commen('can_applied_internship', $where, $data);

        if ($update_data) {
            echo csrf_hash() . '^' . '1';
        } else {
            echo csrf_hash() . '^' . '1';
        }
    }

    public function can_new_message_cheack() //Function For New Messages Cheack For Candidate (AJEX)
    {
        extract($_REQUEST);
        $session = session();
        $Candidate_model = new Candidate_model();
        // $rec_id1='1221008050109,122121203341138,122121211573881,122071010000724,122112510160094,122071010000749,122071010000741,1221008055252,122071010000723,122071010000742,122071010000740,122071010000739,122071010000738,122071010000737,122071010000736,122071010000735,122071010000734,122071010000733,122071010000732,122071010000731,122071010000729,122071010000728,122071010000727,122071010000726,122071010000725,1221110104648,1221008053714,1221008060158,1221008054546,1221008041056,1221008044540,1221008050514';
        // $receiver_id = explode(',' , $rec_id1);
        $useridsess = $session->get('userid');
        $msg_status = $Candidate_model->msg_status('chat', $useridsess);
        // echo json_encode(array('csrf' => csrf_hash(), 'data_msg' => $msg_status));
        echo json_encode(array('data_msg' => $msg_status));
    } //Function For New Me

    public function submit_assignment() //Function For submit assignment
    {
        $session = session();
        $Candidate_model = new Candidate_model();
        $id = $this->request->getVar('id');
        $files = $this->request->getFile('files');
        $assignment_title = $this->request->getVar('assignment_title');
        $assignment_description = $this->request->getVar('assignment_description');
        $assignment_link = $this->request->getVar('assignment_link');
        if (!empty($files)) {
            $newName = $files->getRandomName();
            $files->move('public/assets/docs/uploads/attachment/', $newName);
            $ext = $files->getClientExtension();
            $base_name = $files->getClientName();
            $size = $files->getSize();
        } else {
            $newName = '';
            $ext = '';
            $base_name = '';
            $size = '';
        }

        $current_datetime = $Candidate_model->current_datetime();
        $receiver_id = $this->request->getVar('receiver_id');
        $userid = $session->get('userid');

        $data = [
            'sender_id' => $userid,
            'receiver_id' => $receiver_id,
            'type' => '4',
            'message' => '',
            'attachment_name' => $newName,
            'attachment_ext' => $ext,
            'attachment_filename' => $base_name,
            'attachment_filesize' => $size,
            'title' => $assignment_title,
            'file_name' => $assignment_description,
            'link' => $assignment_link,
            'assignment_id' => $id,
            'created_at' => $current_datetime,
            'message_status' => '1',
        ];
        $result = $Candidate_model->insert_commen('chat', $data);
        $where = array('id' => $id);
        $data = [
            'assignment_status' => '1',
        ];
        $update_data = $Candidate_model->update_commen('chat', $where, $data);
        if ($result) {
            echo csrf_hash() . '^' . '1';
        } else {
            echo csrf_hash() . '^' . '0';
        }
    }


    public function get_unread_chat_user_can() //Function For Getting Messages Common (AJEX)
    {
        $session = session();
        $Candidate_model = new Candidate_model();
        $userid1 = $session->get('userid');
        $where31 = array('receiver_id' => $userid1, 'message_status' => '1');
        // $group_by1 = array('ordercolumn' => 'receiver_id');
        $order_by1 = array('ordercolumn' => 'id', 'ordertype' => 'desc');
        $chat_chandidate_unread = $Candidate_model->fetch_table_data_group_by_unread('chat', $where31, $order_by1);
        if (!empty($chat_chandidate_unread)) {
            //    echo "<pre>"; print_r($chat_chandidate[0]->receiver_id);
            foreach ($chat_chandidate_unread as $employee) {
                $useridsess = $session->get('userid');
                $userid = $employee->sender_id;
                $msg_status = $Candidate_model->msg_status_unread('chat', $useridsess, $userid);


                $userid = $employee->sender_id;
                $where = array('status' => '1', 'userid' => $userid);
                $emp_name = $Candidate_model->emp_names('userlogin', $where);
                $username = $emp_name->username;
                if ($emp_name->usertype == '2') {
                    $sub_admin_company_id = $userid;
                } else {
                    $where_sub_admin = array('userid' => $userid);
                    $sub_admin_profile = $Candidate_model->fetch_table_row('emp_manage_admins', $where_sub_admin);
                    $sub_admin_company_id = $sub_admin_profile->emp_user_id;
                }
                $where_com = array('userid' => $sub_admin_company_id);
                $Company_data = $Candidate_model->fetch_table_row('profile_completion_form', $where_com);

                $emp_company_name    = $Company_data->profile_company_name;
                if (!empty($username)) {
                    $firstStringCharacter = strtoupper(substr($username, 0, 1));
                }

        ?>
                <div class="chat-list">
                    <input type="hidden" class="receiver_id_all" value="<?= $userid ?>">
                    <a id="<?= $userid ?>" title="<?= $emp_name->username; ?>" companyname="<?= $emp_company_name; ?>" logoname="<?= $firstStringCharacter; ?>" class="selectuser d-flex align-items-start position-relative px-2 py-3" onclick="unread('<?= $userid ?>','<?= $emp_name->username; ?>','<?= $firstStringCharacter; ?>','<?= $emp_company_name; ?>');">
                        <div class="flex-shrink-0 bg-gray rounded-50 text-blue fw-bold fs-6 ms-0 position-relative">
                            <span><?php echo $firstStringCharacter; ?></span>
                            <!-- <span class="active"></span> -->
                        </div>
                        <div class="read flex-grow-1 ms-3">
                            <div class="d-flex justify-content-between">
                                <h3 class="text-dark fw-medium"><?= $emp_company_name; ?></h3>
                                <span class="badge fw-normal unreadCount d-flex justify-content-center align-items-center align-self-start ms-2 count1" id="msg_status<?= $userid ?>">
                                    <?= $msg_status ?>
                                </span>
                                <!-- <span class="chatOn text-muted f-11">10:30 pm</span> -->
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <p class="f-13 mb-0"><?= $emp_name->username; ?></p>

                            </div>
                        </div>
                    </a>
                </div>
            <?php
            }
        } else { ?>
            <div class="text-muted mx-auto text-center d-block mt-4" style="bottom:0px">
                <p>No Unread Chat</p>
            </div>
        <?php } ?>





<?php
    }


    public function update_block_user_can() //Function For accept interview
    {
        $session = session();
        $Candidate_model = new Candidate_model();
        $receiver_id = $this->request->getVar('receiver_id');
        $val = $this->request->getVar('val');
        $sender_id = $session->get('userid');
        if ($val == '1') {
            $data = [
                'sender_id' => $session->get('userid'),
                'receiver_id' => $receiver_id,

            ];
            $result = $Candidate_model->insert_commen('chat_blocked_data', $data);
        } else {
            $where = array('sender_id' => $sender_id, 'receiver_id' => $receiver_id);
            $result = $Candidate_model->delete_commen('chat_blocked_data', $where);
        }

        if ($result) {
            echo csrf_hash() . '^' . '1';
        } else {
            echo csrf_hash() . '^' . '0';
        }
    }
    public function check_block_status1() //Function For accept interview
    {
        $session = session();

        $Candidate_model = new Candidate_model();
        $receiver_id = $this->request->getVar('receiver_id');
        $sender_id = $session->get('userid');
        $where = array('sender_id' => $sender_id, 'receiver_id' => $receiver_id);
        $emp_block = $Candidate_model->fetch_table_row('chat_blocked_data', $where);
        $where1 = array('sender_id' => $receiver_id, 'receiver_id' => $sender_id);
        $can_block = $Candidate_model->fetch_table_row('chat_blocked_data', $where1);

        if ($emp_block != '' && $can_block != '') {
            $block_status = '3'; //both block

        }
        if ($emp_block == '' && $can_block != '') {
            $block_status = '2'; //can block
        }
        if ($emp_block != '' && $can_block == '') {
            $block_status = '3'; //emp block
        }
        if ($emp_block == '' && $can_block == '') {
            $block_status = '4'; //no block

        }
        echo csrf_hash() . '^' . $block_status;
    }


    //Payment code start
    public function candidate_payment_curl()
    {
        $data = [];
        $data['title'] = 'Checkout payment | internme.app';
        $data['callback_url'] = base_url() . '/razorpay-callback';
        $data['surl'] = base_url() . '/razorpay-success';
        $data['furl'] = base_url() . '/razorpay-failed';
        $data['currency_code'] = 'INR';
        $order_id = $this->get_merchant_order_id();
        $transaction_id = $this->get_merchant_txn_id();

        $data['merchant_order_id'] = $order_id;
        $data['merchant_txn_id'] = $transaction_id;

        $session = session();
        $Candidate_model = new Candidate_model();
        $userid = $session->get('userid');
        $where_can = array('status' => '1', 'userid' => $userid);
        $data['profile_personal'] = $Candidate_model->fetch_table_row('can_personal_details', $where_can);

        $where = array('can_profile_complete_status' => '1', 'status' => '1', 'userid' => $userid);
        $data_ins = [
            'merchant_order_id' => $order_id,
            'merchant_txn_id' => $transaction_id,
        ];
        $update_data = $Candidate_model->update_commen('can_personal_details', $where, $data_ins);


        return view('Candidate/candidate_payment', $data);
        // $this->load->view("razorpay", $data);
    }

    public function candidate_payment($payment_plan)
    {
        $session = session();
        $previousUrl = $_SESSION['_ci_previous_url'];
        if (strpos($previousUrl, "razorpay-success") !== false) {
            // return view('Candidate/candidate_payment', $data);
            return redirect()->to('my-transactions');
        }
        $data = [];
        $data['title'] = 'Checkout payment | internme.app';
        $data['callback_url'] = base_url() . '/razorpay-callback';
        $data['surl'] = base_url() . '/razorpay-success';
        $data['furl'] = base_url() . '/razorpay-failed';
        $data['currency_code'] = 'INR';
        $order_id = $this->get_merchant_order_id();
        $transaction_id = $this->get_merchant_txn_id();

        $data['merchant_order_id'] = $order_id;
        $data['merchant_txn_id'] = $transaction_id;

        $ses_data = ['pricing_plan_redirect'];
        $session->remove($ses_data);
        $Candidate_model = new Candidate_model();
        $current_datetime = $Candidate_model->current_datetime();
        $userid = $session->get('userid');
        $where_can = array('status' => '1', 'userid' => $userid);
        $data['profile_personal'] = $Candidate_model->fetch_table_row('can_personal_details', $where_can);
        $profile_personal = $Candidate_model->fetch_table_row('can_personal_details', $where_can);
        $data['paid_amount'] = '';
        if ($payment_plan == 1) {
            $data['paid_amount'] = 799;
            // $data['paid_amount'] = 1; //test
        }
        if ($payment_plan == 2) {
            $data['paid_amount'] = 899;
            // $data['paid_amount'] = 2; //test
        }
        if ($payment_plan == 3) {
            $data['paid_amount'] = 2999;
            // $data['paid_amount'] = 2; //test
        }

        $amountIncludingGST = $data['paid_amount']; // Amount including GST
        $percentage = 18; // GST percentage

        // Calculate the GST amount
        $gstAmount = ($amountIncludingGST * $percentage) / (100 + $percentage);

        // Calculate the amount excluding GST
        $amountExcludingGST = $amountIncludingGST - $gstAmount;

        $data['sgstAmount'] = number_format(($gstAmount / 2), 2);
        $data['cgstAmount'] = number_format(($gstAmount / 2), 2);
        $data['amountExcludingGST'] = number_format($amountExcludingGST, 2);
        $api = new Api("rzp_live_U5JHzi0BdrUlj9", "QyXs5C16bbcrQeYbA07QZgVF"); //Live

        // $api = new Api("rzp_test_apAaBShG1heg3Y", "QS5IZZhFWsu1OjCkGdtBBwNj"); //Test
        $razorpayOrder = $api->order->create(array(
            'receipt'         => rand(),
            'amount'          => $data['paid_amount'] * 100, // 1 rupees in paise
            'currency'        => 'INR',
            'payment_capture' => 1 // auto capture
        ));

        $amount = $razorpayOrder['amount'];
        $razorpayOrderId = $razorpayOrder['id'];
        $_SESSION['razorpay_order_id'] = $razorpayOrderId;
        $data['order_id'] = $razorpayOrderId;
        $data['current_date'] = $current_datetime;
        $where = array('can_profile_complete_status' => '1', 'status' => '1', 'userid' => $userid);
        $data_ins = [
            'merchant_order_id' => $order_id,
            'merchant_txn_id' => $transaction_id,
            'order_id' => $razorpayOrderId,
        ];
        $update_data = $Candidate_model->update_commen('can_personal_details', $where, $data_ins);

        $data_his = [
            'userid' => $userid,
            'merchant_order_id' => $order_id,
            'merchant_txn_id'  => $transaction_id,
            'payment_status'  => 'initiated',
            'order_id' => $razorpayOrderId,
            'created_at' => $current_datetime
        ];
        $insert_data = $Candidate_model->insert_commen('can_payment_details_history', $data_his);


        //$paymentId = "pay_LVHvYzzXa7jkjm";
        //$paymentId = "order_LUso6poj2zfkUq";
        //$all = $api->order->fetch($paymentId)->payments();
        //$all = $api->payment->fetch($paymentId);
        //echo "<pre>";
        //print_r($all);
        //exit();

        $data_raz = array(
            //"key" => "rzp_test_apAaBShG1heg3Y", //Test
            "key" => "rzp_live_U5JHzi0BdrUlj9", //Live
            "amount" => $amount,
            "name" => $profile_personal->profile_full_name,
            "description" => "Internme Subscription",
            "currency" => "INR",
            "prefill" => array(
                "name"  => $profile_personal->profile_full_name,
                "email"  => $profile_personal->profile_email,
                "contact" => $profile_personal->profile_phone_number
            ),
            "notes"  => array(
                "address"  => "Chennai",
                "merchant_order_id" => $order_id,
                "merchant_trans_id"  => $transaction_id,
                "user_id"  => $profile_personal->userid
            ),
            "theme"  => array("color"  => "#24337D"),
            "order_id" => $razorpayOrderId
        );
        $data['razpay'] = $data_raz;
        // echo "Order ID: " . $session->get('merchant_order_id');


        // Check if "razorpay-success" is present in $previousUrl
        return view('Candidate/candidate_payment', $data);
        // }


    }

    public function callback()
    {

        // $api = new Api("rzp_test_apAaBShG1heg3Y", "QS5IZZhFWsu1OjCkGdtBBwNj"); //Test
        $api = new Api("rzp_live_U5JHzi0BdrUlj9", "QyXs5C16bbcrQeYbA07QZgVF"); //Live
        $success = true;
        $error = "payment_failed";
        if (empty($_POST['razorpay_payment_id']) === false) {
            $api = new Api("rzp_live_U5JHzi0BdrUlj9", "QyXs5C16bbcrQeYbA07QZgVF"); //Live
            // $api = new Api("rzp_test_apAaBShG1heg3Y", "QS5IZZhFWsu1OjCkGdtBBwNj"); //Test
            try {
                $attributes = array(
                    'razorpay_order_id' => $_SESSION['razorpay_order_id'],
                    'razorpay_payment_id' => $_POST['razorpay_payment_id'],
                    'razorpay_signature' => $_POST['razorpay_signature']
                );
                $api->utility->verifyPaymentSignature($attributes);
            } catch (SignatureVerificationError $e) {
                $success = false;
                $error = 'Razorpay_Error : ' . $e->getMessage();
                $_SESSION['razorpay_error'] = $error;
            }
        }

        if ($success === true) {
            $orderId = $_SESSION['razorpay_order_id'];
            $allOrders = $api->order->fetch($orderId)->payments();
            $Candidate_model = new Candidate_model();
            $current_datetime = $Candidate_model->current_datetime();
            $invoice_no = $this->get_invoice_no();
            //echo "<pre>";
            //print_r($allOrders['items']); exit();
            $allOrders = array_reverse($allOrders['items'], true);
            foreach ($allOrders as $order) {

                $userid = $order['notes']->user_id;
                $payment_id = $order->id;
                $p_dates = $order->created_at;
                $payment_date = date("d-m-Y h:i:sa", $p_dates);
                $payment_amount = $order->amount / 100;
                $payment_ex_date = date("Y-m-d", $p_dates);
                $expiry_date = date("Y-m-d", strtotime(($payment_ex_date) . " + 1 year"));
                $paid_amount = '';
                if ($payment_amount == '799') {
                    // if ($payment_amount == '1') {
                    $payment_package_type = 1;
                }
                if ($payment_amount == '899') {
                    // if ($payment_amount == '2') {
                    $payment_package_type = 2;
                }
                if ($payment_amount == '2999') {
                    // if ($payment_amount == '2') {
                    $payment_package_type = 3;
                }

                $where = array('userid' => $userid);
                $data_ins = [
                    'payment_status' => "1",
                    'payment_amount' => $payment_amount,
                    'payment_date' => $payment_date,
                    'payment_expiry_date' => date("Y-m-d", strtotime($expiry_date . " - 1 day")),
                    'payment_id' => $payment_id,
                    'payment_package_type' => $payment_package_type
                ];
                $Candidate_model->update_commen('can_personal_details', $where, $data_ins);

                $where_history = array('userid' => $userid, 'order_id' => $order->order_id);
                $data_history = [
                    'payment_id' => $payment_id,
                    'payment_date' => $payment_date,
                    'payment_amount' => $payment_amount,
                    'payment_status' => $order->status,
                    'payment_method' => $order->method,
                    'card_id' => $order->card_id,
                    'bank' => $order->bank,
                    'wallet' => $order->wallet,
                    'vpa' => $order->vpa,
                    'captured' => $order->captured,
                    'error_code' => $order->error_code,
                    'error_description' => $order->error_description,
                    'error_source' => $order->error_source,
                    'error_reason' => $order->error_reason,
                    'invoice_no' => $invoice_no,
                    //'created_at' => $current_datetime
                ];
                $Candidate_model->update_commen('can_payment_details_history', $where_history, $data_history);

                $data_inv = [
                    'userid' => $userid,
                    'invoice_no' => $invoice_no,
                    'merchant_order_id' => $order['notes']->merchant_order_id,
                    'merchant_txn_id' => $order['notes']->merchant_trans_id,
                    'order_id' => $order->order_id,
                    'payment_id' => $payment_id,
                    'payment_date' => $payment_date,
                    'payment_amount' => $payment_amount,
                    'payment_status' => $order->status,
                    'payment_method' => $order->method,
                    'payment_wallet' => $order->wallet,
                    'created_at' => $current_datetime
                ];
                $Candidate_model->insert_commen('invoice_details', $data_inv);
            }
            return redirect()->to($this->request->getVar('merchant_surl_id'));
        } else {
            $orderId = $_SESSION['razorpay_order_id'];
            $allOrders = $api->order->fetch($orderId)->payments();
            $Candidate_model = new Candidate_model();
            $allOrders = array_reverse($allOrders['items'], true);
            foreach ($allOrders as $order) {
                $userid = $order['notes']->user_id;
                $payment_id = $order->id;
                $p_dates = $order->created_at;
                $payment_date = date("d-m-Y h:i:sa", $p_dates);
                $payment_amount = $order->amount / 100;

                $where_history = array('userid' => $userid, 'order_id' => $order->order_id);
                $data_history = [
                    'payment_id' => $payment_id,
                    'payment_date' => $payment_date,
                    'payment_amount' => $payment_amount,
                    'payment_status' => $order->status,
                    'payment_method' => $order->method,
                    'card_id' => $order->card_id,
                    'bank' => $order->bank,
                    'wallet' => $order->wallet,
                    'vpa' => $order->vpa,
                    'captured' => $order->captured,
                    'error_code' => $order->error_code,
                    'error_description' => $order->error_description,
                    'error_source' => $order->error_source,
                    'error_reason' => $order->error_reason
                ];
                $update_data_history = $Candidate_model->update_commen('can_payment_details_history', $where_history, $data_history);
            }
            return redirect()->to($this->request->getVar('merchant_furl_id'));
        }
    }
    public function success()
    {
        $session = session();
        $Candidate_model = new Candidate_model();
        $userid = $session->get('userid');
        $where_can = array('userid' => $userid);
        $profile_personal = $Candidate_model->fetch_table_row('can_personal_details', $where_can);
        // echo "Transaction ID: " . $session->get('razorpay_payment_id');
        // echo "Order ID: " . $session->get('merchant_order_id');
        $data['orderID'] = $_SESSION['razorpay_order_id'];
        $data['paymentID'] = $profile_personal->payment_id;
        $data['paymentDate'] = $profile_personal->payment_date;
        $data['paymentAmount'] = $profile_personal->payment_amount;
        return view('Candidate/can_payment_success', $data);
    }
    public function failed()
    {
        $session = session();
        // echo "Transaction ID: " . $session->get('razorpay_payment_id');
        // echo "Order ID: " .$session->get('merchant_order_id');
        $data['orderID'] = $_SESSION['razorpay_order_id'];
        $data['raz_error'] = $_SESSION['razorpay_error'];
        return view('Candidate/can_payment_failed', $data);
    }
    // initialized cURL Request
    private function curl_handler($payment_id, $amount)
    {
        $url = 'https://api.razorpay.com/v1/payments/' . $payment_id . '/capture';
        $key_id = "rzp_live_U5JHzi0BdrUlj9"; //live
        // $key_id = "rzp_test_apAaBShG1heg3Y"; //demo
        $key_secret = "QyXs5C16bbcrQeYbA07QZgVF"; //live
        // $key_secret = "QS5IZZhFWsu1OjCkGdtBBwNj"; //demo
        //$params = http_build_query($data);
        $fields_string = "amount=$amount";
        //cURL Request
        $ch = curl_init();
        //set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERPWD, $key_id . ':' . $key_secret);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . '/ca-bundle.crt');
        //print_r($fields_string);
        return $ch;
    }
    // callback method

    public function callback_curl()
    {
        $session = session();

        if (!empty($this->request->getVar('razorpay_payment_id')) && !empty($this->request->getVar('merchant_order_id'))) {
            $razorpay_payment_id = $this->request->getVar('razorpay_payment_id');
            $merchant_order_id = $this->request->getVar('merchant_order_id');
            $session = session();
            $session->set('razorpay_payment_id', $razorpay_payment_id);
            $session->set('merchant_order_id', $merchant_order_id);

            $currency_code = 'INR';
            $amount = $this->request->getVar('merchant_total');

            $success = false;
            $error = '';
            try {
                $ch = $this->curl_handler($razorpay_payment_id, $amount);
                //execute post
                $result = curl_exec($ch);
                $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                if ($result === false) {
                    $success = false;
                    $error = 'Curl error: ' . curl_error($ch);
                } else {
                    $response_array = json_decode($result, true);
                    //Check success response
                    if ($http_status === 200 and isset($response_array['error']) === false) {
                        $success = true;
                    } else {
                        $success = false;
                        if (!empty($response_array['error']['code'])) {
                            $error = $response_array['error']['code'] . ':' . $response_array['error']['description'];
                        } else {
                            $error = 'RAZORPAY_ERROR:Invalid Response <br/>' . $result;
                        }
                    }
                }
                //close curl connection
                curl_close($ch);
            } catch (Exception $e) {
                $success = false;
                $error = 'Request to Razorpay Failed';
            }
            if ($success === true) {
                if (!empty($session->get('ci_subscription_keys'))) {
                    $session->unset('ci_subscription_keys');
                }
                // if (!$order_info['order_status_id'])
                // {
                return redirect()->to($this->request->getVar('merchant_surl_id'));
                // return redirect($this->request->getVar('merchant_surl_id'));
                // }
                // else
                // {
                //     return redirect($this->request->getVar('merchant_surl_id'));
                // }
            } else {

                return redirect()->to($this->request->getVar('merchant_furl_id'));
            }
        } else {
            echo 'An error occured. Contact site administrator, please!';
        }
    }

    public function can_transactions_list()
    {
        $session = session();
        $Candidate_model = new Candidate_model();
        $userid    =    $session->get('userid');

        $order_by_reason = array('ordercolumn' => 'id', 'ordertype' => 'desc');
        $where = array('status' => '1', 'userid' => $userid, 'payment_status!=' => 'initiated');
        $my_transaction_list_raz = $Candidate_model->fetch_table_data_for_all('can_payment_details_history', $where, $order_by_reason);

        $order_by_reason = array('ordercolumn' => 'id', 'ordertype' => 'desc');
        $where = array('status' => '1', 'userid' => $userid, 'payment_status!=' => 'PAYMENT_INITIATED');
        $my_transaction_list_phonepe = $Candidate_model->fetch_table_data_for_all('can_payment_details_history_phonepe', $where, $order_by_reason);

        // Check if both results are arrays before merging
        if (is_array($my_transaction_list_raz) && is_array($my_transaction_list_phonepe)) {
            $data['my_transaction_list'] = array_merge($my_transaction_list_raz, $my_transaction_list_phonepe);
        } else {
            // Handle the case where one of the results is not an array
            // You can add error handling or do something else as needed
            if (!empty($my_transaction_list_raz) && empty($my_transaction_list_phonepe)) {
                $data['my_transaction_list'] = $my_transaction_list_raz;
            } elseif (empty($my_transaction_list_raz) && !empty($my_transaction_list_phonepe)) {
                $data['my_transaction_list'] = $my_transaction_list_phonepe;
            } else {
                $data['my_transaction_list'] = [];
            }
        }

        $where_status = array('status' => '1', 'userid' => $userid);
        $data['personal_details'] = $Candidate_model->fetch_table_row('can_personal_details', $where_status);
        return view('Candidate/can_payment_transaction_list', $data);
    }

    public function candidate_payment_recipt_download($invoice_id)
    {
        helper(['form']);
        $session = session();
        $userid    =    $session->get('userid');
        $Candidate_model = new Candidate_model();
        $order_by_reason = array('ordercolumn' => 'id', 'ordertype' => 'desc');
        $where = array('status' => '1', 'invoice_no' => $invoice_id);
        $data['invoice_details'] = $Candidate_model->fetch_table_row_order_by('invoice_details', $where, $order_by_reason);

        $where_status = array('status' => '1', 'userid' => $userid);
        $data['personal_details'] = $Candidate_model->fetch_table_row('can_personal_details', $where_status);

        $dompdf = new \Dompdf\Dompdf(array('enable_remote' => true));
        $dompdf->loadHtml(view('Candidate/can_payment_recipt', $data));
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        //$dompdf = new Dompdf(array('enable_remote' => true));
        $dompdf->stream("Internship Payment Receipt.pdf");
        //return view('Candidate/can_payment_recipt',$data);
    }

    function get_merchant_order_id()
    {
        $date = date("dmy");
        $pass = substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 3);
        $merchant_order_id = "INT-MER-" . $date . $pass . time();
        return $merchant_order_id;
    }
    function get_merchant_txn_id()
    {
        $date = date("YmdHis");
        $pass = substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 3);
        $merchant_txn_id = "INT-TXN-" . $date . $pass;
        return $merchant_txn_id;
    }
    function get_invoice_no()
    {
        $Common_model = new Common_model();
        $getInvoiceid = $Common_model->get_invoice_id();
        $month = date("m");
        $year = date("y");
        if (empty($getInvoiceid)) {
            $fin_no = sprintf("%07d", 1);
            $invoice_no = "INTERN" . $month . $year . $fin_no;
            return $invoice_no;
        } else {
            $fin_no = sprintf("%07d", $getInvoiceid[0]->id + 1);
            $invoice_no = "INTERN" . $month . $year . $fin_no;
            return $invoice_no;
        }
    }



    /** This function retrieves the pricing plan for a candidate and displays it on the pricing_plan view.
     * @return \CodeIgniter\HTTP\RedirectResponse|\CodeIgniter\View\View
     */

    public function subscription_plan()
    {
        /**Load Session */
        $session = session();

        /**Load Candidate model */
        $Candidate_model = new Candidate_model();

        /**Get User id Session */
        $userid          = $session->get('userid');
        $data['data'] = '';
        return view('Candidate/subscription_plan', $data);
        // return view('design/subscription_plan');
    }
    public function subscription_plan_new()
    {
        /**Load Session */
        $session = session();

        /**Load Candidate model */
        $Candidate_model = new Candidate_model();

        /**Get User id Session */
        $userid          = $session->get('userid');
        $data['data'] = '';
        $where_status1 = array('status' => '1', 'userid' => $userid);
        $data['profile_personal'] = $Candidate_model->fetch_table_row('can_personal_details', $where_status1);
        if (!empty($userid)) {
            $current_datetime = $Candidate_model->current_datetime();
            $data1 = [
                'user_id' => $userid,
                'user_type' => 1,
                'created_at' =>  $current_datetime,
            ];
            $insert_data = $Candidate_model->insert_commen('subscription_plan_log', $data1);
        }
        // print_r($data);exit;
        return view('Candidate/subscription_plan_new', $data);
        // return view('design/subscription_plan');
    }


    /** This function set the session for before login.
     * @return redirect to login page
     */

    public function pricing_plan_login()
    {
        /**Load Session */
        $session = session();

        /**Load Candidate model */
        $Candidate_model = new Candidate_model();

        /**Set Session for pricing plan redirect */
        $ses_data = ['pricing_plan_redirect' => 'pricing-plan'];
        $session->set($ses_data);

        return redirect()->to('login');
    }

    //payment code end


    function CheckMX($domain)
    {
        $arr = dns_get_record($domain, DNS_MX);
        if (!empty($arr)) {
            if ($arr[0]['host'] == $domain && !empty($arr[0]['target'])) {
                return $arr[0]['target'];
            }
        } else {
            return 0;
        }
    }


    public function gmetrix_view()
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
        // print_r($gmetrix_detail);exit;
        if (!empty($gmetrix_detail)) {

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

                $count = count($apiResponse);
                // echo"<pre>";print_r($apiResponse);exit;
                if ($count == 1) {
                    $data['gmatrix_data'] = $apiResponse;
                } else {
                    $data['gmatrix_data'] = '';
                }

                //  echo"<pre>";print_r($apiResponse);exit;
                // Process the API response as needed
                // return $this->response->setJSON($apiResponse);
            } else {
                return $this->response->setStatusCode(500)->setJSON(['error' => 'API request failed']);
            }
        } else {
            $data['gmatrix_data'] = '';
        }
        $where_status1 = array('status' => '1', 'userid' => $userid);
        $data['profile_personal'] = $Candidate_model->fetch_table_row('can_personal_details', $where_status1);
        return view('Candidate/gmetrix', $data);
    }

    public function gmetrix_data()
    {
        $session = session();

        /**Load Candidate model */
        $Candidate_model = new Candidate_model();

        /**Get User id Session */
        $userid          = $session->get('userid');
        $where_status = array('status' => '1', 'userid' => $userid);
        $gmetrix_detail = $Candidate_model->fetch_table_row('gmetrix_candidate_data', $where_status);
        if (empty($gmetrix_detail)) {
            $dataToSend1 = [
                'Key' => '0BE6177D3019',
                'CustomerId' => '280923',
                'NumberOfCodesToCreate' => '1',
                'LicenseId' => '1452',
                'orderId' => '',
                'Identifier' => 'INTNME',
                'CourseCategories' => ["INTNME"],
                'CourseCount' => '1',
            ];

            // API endpoint URL
            $apiUrl = 'https://managementservices.gmetrix.net/api/AdminServices/CreateComboAccessCode';

            // Create an instance of the HTTP client
            $httpClient = \Config\Services::curlrequest();

            // Send a POST request with the data
            $response = $httpClient->post($apiUrl, [
                'json' => $dataToSend1,
                'verify' => false,
            ]);
            // echo"<pre>";print_r($response);exit;
            // Check for a successful API request
            if ($response->getStatusCode() === 200) {
                $apiResponse = json_decode($response->getBody());
                // echo"<pre>";print_r($apiResponse);exit;
                $current_datetime = $Candidate_model->current_datetime();
                $data = [
                    'userid' => $userid,
                    'TransactionId' => $apiResponse->TransactionId,
                    'AccessCodes' => $apiResponse->AccessCodes,
                    'created_at' =>  $current_datetime,
                ];
                $insert_data = $Candidate_model->insert_commen('gmetrix_candidate_data', $data);
                if ($insert_data) {
                    $where_status = array('status' => '1', 'userid' => $userid);
                    $gmetrix_detail = $Candidate_model->fetch_table_row('gmetrix_candidate_data', $where_status);

                    $where_status1 = array('status' => '1', 'userid' => $userid);
                    $candidate_detail = $Candidate_model->fetch_table_row('can_personal_details', $where_status1);
                    // Data to send to the API
                    if (!empty($candidate_detail->profile_email)) {
                        $contact_det = $candidate_detail->profile_email;
                    } else {
                        $contact_det = $candidate_detail->profile_phone_number;
                    }
                    $dataToSend = [
                        'Key' => '0BE6177D3019',
                        'UserName' => $contact_det,
                        'EmailAddress' => $contact_det,
                        'FirstName' => $candidate_detail->profile_first_name,
                        'LastName' => $candidate_detail->profile_last_name,
                        'AccessCode' => $gmetrix_detail->AccessCodes,
                        'StudentNumber' => $userid,
                    ];

                    // API endpoint URL
                    $apiUrl = 'https://managementservices.gmetrix.net/api/StudentServices/CreateStudent';

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
                        $where = array('userid' => $userid);
                        $data = [
                            'StudentId' => $apiResponse->StudentId,
                        ];
                        $update_data = $Candidate_model->update_commen('gmetrix_candidate_data', $where, $data);
                        return redirect()->to($apiResponse->StudentPortalSingleSignOnURL);
                        //  echo"<pre>";print_r($apiResponse);exit;
                        // Process the API response as needed
                        // return $this->response->setJSON($apiResponse);
                    } else {
                        return $this->response->setStatusCode(500)->setJSON(['error' => 'API request failed']);
                    }
                }
            } else {
                return $this->response->setStatusCode(500)->setJSON(['error' => 'API request failed']);
            }
        } else {
            $where_status = array('status' => '1', 'userid' => $userid);
            $gmetrix_detail = $Candidate_model->fetch_table_row('gmetrix_candidate_data', $where_status);

            $where_status1 = array('status' => '1', 'userid' => $userid);
            $candidate_detail = $Candidate_model->fetch_table_row('can_personal_details', $where_status1);
            // Data to send to the API
            if (!empty($candidate_detail->profile_email)) {
                $contact_det = $candidate_detail->profile_email;
            } else {
                $contact_det = $candidate_detail->profile_phone_number;
            }
            $dataToSend = [
                'Key' => '0BE6177D3019',
                'UserName' => $contact_det,
                'EmailAddress' => $contact_det,
                'FirstName' => $candidate_detail->profile_first_name,
                'LastName' => $candidate_detail->profile_last_name,
                'AccessCode' => $gmetrix_detail->AccessCodes,
                'StudentNumber' => $userid,
            ];

            // API endpoint URL
            $apiUrl = 'https://managementservices.gmetrix.net/api/StudentServices/CreateStudent';

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
                // echo"<pre>";print_r($response);exit;
                $where = array('userid' => $userid);
                $data = [
                    'StudentId' => $apiResponse->StudentId,
                ];
                $update_data = $Candidate_model->update_commen('gmetrix_candidate_data', $where, $data);

                $this->gmetrix_status();
                return redirect()->to($apiResponse->StudentPortalSingleSignOnURL);
                //  echo"<pre>";print_r($apiResponse);exit;
                // Process the API response as needed
                // return $this->response->setJSON($apiResponse);
            } else {
                return $this->response->setStatusCode(500)->setJSON(['error' => 'API request failed']);
            }
        }
    }

    public function gmetrix_status()
    {
        // return 1;exit;
        $session = session();

        /**Load Candidate model */
        $Candidate_model = new Candidate_model();

        /**Get User id Session */
        $userid          = $session->get('userid');
        $where_status = array('status' => '1', 'userid' => $userid);
        $gmetrix_detail = $Candidate_model->fetch_table_row('gmetrix_candidate_data', $where_status);
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

        // Check for a successful API request
        if ($response->getStatusCode() === 200) {
            $apiResponse = json_decode($response->getBody());
            // echo"<pre>";print_r($apiResponse);exit;
            $where_status = array('CourseID' => $apiResponse[0]->CourseID, 'userid' => $userid);
            $course_detail = $Candidate_model->fetch_table_row('gmetrix_course_data', $where_status);
            if (empty($course_detail)) {
                $current_datetime = $Candidate_model->current_datetime();
                $data = [
                    'userid' => $userid,
                    'StudentId' => $apiResponse[0]->StudentId,
                    'CourseName' => $apiResponse[0]->CourseName,
                    'ASPNetUserID' => $apiResponse[0]->ASPNetUserID,
                    'UserName' => $apiResponse[0]->UserName,
                    'CourseID' => $apiResponse[0]->CourseID,
                    'CourseVendor' => $apiResponse[0]->CourseVendor,
                    'CourseType' => $apiResponse[0]->CourseType,
                    'CourseControl' => $apiResponse[0]->CourseControl,
                    'CourseTypeID' => $apiResponse[0]->CourseTypeID,
                    'CourseStudentID' => $apiResponse[0]->CourseStudentID,
                    'Progress' => $apiResponse[0]->Progress,
                    'SecondsSpent' => $apiResponse[0]->SecondsSpent,
                    'HMSTimeSpent' => $apiResponse[0]->HMSTimeSpent,
                    'EnrollmentDate' => $apiResponse[0]->EnrollmentDate,
                    'StartDate' => $apiResponse[0]->StartDate,
                    'DueDate' => $apiResponse[0]->DueDate,
                    'ExpirationDate' => $apiResponse[0]->ExpirationDate,
                    'CompletionDate' => $apiResponse[0]->CompletionDate,
                    'Successful' => $apiResponse[0]->Successful,
                    'MsgSuccess' => $apiResponse[0]->MsgSuccess,
                    'StudentPortalSingleSignOnURL' => $apiResponse[0]->StudentPortalSingleSignOnURL,

                    'created_at' =>  $current_datetime,
                ];
                $insert_data = $Candidate_model->insert_commen('gmetrix_course_data', $data);
            } else {
                $where = array('CourseID' => $apiResponse[0]->CourseID, 'userid' => $userid);
                $data = [
                    'StudentId' => $apiResponse[0]->StudentId,
                    'CourseName' => $apiResponse[0]->CourseName,
                    'ASPNetUserID' => $apiResponse[0]->ASPNetUserID,
                    'UserName' => $apiResponse[0]->UserName,
                    'CourseID' => $apiResponse[0]->CourseID,
                    'CourseVendor' => $apiResponse[0]->CourseVendor,
                    'CourseType' => $apiResponse[0]->CourseType,
                    'CourseControl' => $apiResponse[0]->CourseControl,
                    'CourseTypeID' => $apiResponse[0]->CourseTypeID,
                    'CourseStudentID' => $apiResponse[0]->CourseStudentID,
                    'Progress' => $apiResponse[0]->Progress,
                    'SecondsSpent' => $apiResponse[0]->SecondsSpent,
                    'HMSTimeSpent' => $apiResponse[0]->HMSTimeSpent,
                    'EnrollmentDate' => $apiResponse[0]->EnrollmentDate,
                    'StartDate' => $apiResponse[0]->StartDate,
                    'DueDate' => $apiResponse[0]->DueDate,
                    'ExpirationDate' => $apiResponse[0]->ExpirationDate,
                    'CompletionDate' => $apiResponse[0]->CompletionDate,
                    'Successful' => $apiResponse[0]->Successful,
                    'MsgSuccess' => $apiResponse[0]->MsgSuccess,
                    'StudentPortalSingleSignOnURL' => $apiResponse[0]->StudentPortalSingleSignOnURL,

                ];
                $update_data = $Candidate_model->update_commen('gmetrix_course_data', $where, $data);
            }
        } else {
            return $this->response->setStatusCode(500)->setJSON(['error' => 'API request failed']);
        }
    }



    public function phonepe_candidate_payment($payment_plan)
    {
        $session = session();
        $Candidate_model = new Candidate_model();
        $order_id = $this->get_merchant_order_id();
        // $transaction_id = $this->get_merchant_txn_id();
        $current_datetime = $Candidate_model->current_datetime();
        $data['order_id'] = $order_id;
        // $data['merchant_txn_id'] = $transaction_id;
        $data['current_date'] = $current_datetime;
        if ($payment_plan == 1) {
            $data['paid_amount'] = 799;
            // $data['paid_amount'] = 1; //test
        }
        if ($payment_plan == 2) {
            $data['paid_amount'] = 899;
            // $data['paid_amount'] = 2; //test
        }
        if ($payment_plan == 3) {
            $data['paid_amount'] = 2999;
            // $data['paid_amount'] = 2; //test
        }

        $amountIncludingGST = $data['paid_amount']; // Amount including GST
        $percentage = 18; // GST percentage

        // Calculate the GST amount
        $gstAmount = ($amountIncludingGST * $percentage) / (100 + $percentage);

        // Calculate the amount excluding GST
        $amountExcludingGST = $amountIncludingGST - $gstAmount;

        $data['sgstAmount'] = number_format(($gstAmount / 2), 3);
        $data['cgstAmount'] = number_format(($gstAmount / 2), 3);
        $data['amountExcludingGST'] = number_format($amountExcludingGST, 2);
        $data['callback_url'] = base_url() . '/phonepe-payment';
        $session->set('merchant_order_id', $order_id);
        return view('Candidate/phonepe_candidate_payment', $data);
    }
    public function phonepe_payment()
    {
        $session = session();

        $merchant_order_id = $this->request->getVar('merchant_order_id');
        $merchant_amount = $this->request->getVar('merchant_amount');
        /**Load Candidate model */
        $Candidate_model = new Candidate_model();
        $current_datetime = $Candidate_model->current_datetime();
        /**Get User id Session */
        $userid          = $session->get('userid');
        $where_status1 = array('status' => '1', 'userid' => $userid);
        $profile_personal = $Candidate_model->fetch_table_row('can_personal_details', $where_status1);
        $client = new Client();

        // Define the request data as an associative array
        $requestData = [
            // 'merchantId' => 'PGTESTPAYUAT93',  // Replace with your actual request data testing
            'merchantId' => 'UNWINDONLINE',  // Replace with your actual request data live
            // 'merchantId' => 'UNWINDONLINEUAT',  // Replace with your actual request data staging

            'merchantTransactionId' => $merchant_order_id,
            'merchantUserId' => 'M1WPJTNIJA5F',  // Replace with your actual request data
            'amount' => $merchant_amount * 100,
            'redirectUrl' => base_url() . '/phonepe-callback',  // Replace with your actual request data
            'redirectMode' => 'REDIRECT',
            'callbackUrl' => base_url() . '/s2scallback.php',  // Replace with your actual request data
            'mobileNumber' => $profile_personal->profile_phone_number,
            'paymentInstrument' => array('type' => "PAY_PAGE"),  // Replace with your actual request data
            'param1' => $userid,
            // Add other necessary data
        ];
        // print_r($requestData);exit;
        $jsonData = json_encode($requestData);

        // Encode the JSON data as base64
        $base64EncodedPayload = base64_encode($jsonData);

        // Set the $payload variable
        $payload = $base64EncodedPayload;

        // $saltKey = '875126e4-5a13-4dae-ad60-5b8c8b629035'; // Replace with your actual salt key testing
        $saltKey = '1d8fd59d-5d46-4d01-8303-f274e1b7799d'; // Replace with your actual salt key live
        // $saltKey = 'c6cae9ff-9bfe-407f-9efb-31904cea5afa'; // Replace with your actual salt key staging

        $saltIndex = '1'; // Replace with your actual salt index

        // Concatenate the components
        $concatenatedString = $payload . '/pg/v1/pay' . $saltKey;

        // Calculate the SHA-256 hash
        $sha256Hash = hash('sha256', $concatenatedString);

        // Combine the hash with "###" and salt index
        $finalString = $sha256Hash . '###' . $saltIndex;

        // Now $finalString contains the desired result
        // live https://api.phonepe.com/apis/hermes/pg/v1/pay
        //testing https://api-preprod.phonepe.com/apis/pg-sandbox/pg/v1/pay
        $response = $client->request('POST', 'https://api.phonepe.com/apis/hermes/pg/v1/pay', [
            'body' => json_encode(['request' => $base64EncodedPayload]),
            'headers' => [
                'Content-Type' => 'application/json',
                'X-VERIFY' => $finalString,
                'Accept' => 'application/json',
                // Replace with your actual API key or authentication token
                // Add any other headers required by the API
            ],
            // 'json' => $requestData, // Encode the data as JSON and send it in the request body
            // 'verify' => false,

        ]);


        // Handle the response
        $statusCode = $response->getStatusCode();
        $responseBody = $response->getBody()->getContents();

        // You can now process the response as needed
        // For example, you can parse the JSON response if it's in JSON format
        $responseData = json_decode($responseBody, true);
        //    print_r($responseData['param1']);exit;
        // Perform further actions based on the response data and status code
        if ($statusCode === 200) {
            $where = array('can_profile_complete_status' => '1', 'status' => '1', 'userid' => $userid);
            $data_ins = [
                'merchant_order_id' => $merchant_order_id,
                'order_id' => $merchant_order_id,
            ];
            $update_data = $Candidate_model->update_commen('can_personal_details', $where, $data_ins);
            $data_his = [
                'userid' => $userid,
                'order_id' => $merchant_order_id,
                'payment_status'  => 'PAYMENT_INITIATED',
                'created_at' => $current_datetime
            ];
            $insert_data = $Candidate_model->insert_commen('can_payment_details_history_phonepe', $data_his);
            $url = $responseData['data']['instrumentResponse']['redirectInfo']['url'];
            // print_r($url);exit;
            return redirect()->to($url);

            // Success: Handle the successful response
            // You can access specific data using $responseData array
        } else {
            // Handle errors or non-200 status codes here
            // You can log the error, throw an exception, or take appropriate actions
        }
    }
    public function phonepe_callback()
    {

        $client = new Client();

        $merchant_order_id = $_SESSION['merchant_order_id'];
        // $merchantId = 'PGTESTPAYUAT93'; //testing
        $merchantId = 'UNWINDONLINE'; //live
        // $merchantId = 'UNWINDONLINEUAT'; //staging

        // print_r($merchant_order_id);

        //    $saltKey = '875126e4-5a13-4dae-ad60-5b8c8b629035'; // Replace with your actual salt key testing
        $saltKey = '1d8fd59d-5d46-4d01-8303-f274e1b7799d'; // Replace with your actual salt key live
        //    $saltKey = 'c6cae9ff-9bfe-407f-9efb-31904cea5afa'; // Replace with your actual salt key staging
        $saltIndex = '1';

        // Construct the string to be hashed
        $stringToHash = "/pg/v1/status/" . $merchantId . "/" . $merchant_order_id . $saltKey;

        // Calculate the SHA-256 hash
        $sha256Hash = hash('sha256', $stringToHash);

        // Combine the hash with "###" and salt index
        $finalString = $sha256Hash . '###' . $saltIndex;
        // print_r($finalString);
        $response = $client->request('GET', 'https://api.phonepe.com/apis/hermes/pg/v1/status/' . $merchantId . '/' . $merchant_order_id, [
            'headers' => [
                'Content-Type' => 'application/json',
                'X-VERIFY' => $finalString,
                'X-MERCHANT-ID' => $merchantId,
                'Accept' => 'application/json',
            ],
            // 'verify' => false,
        ]);
        $responseBody = $response->getBody()->getContents();

        $responseData = json_decode($responseBody, true);
        // print_r($_SESSION);
        // print_r($responseData);exit;
        $session = session();
        $userid          = $session->get('userid');
        $_SESSION['phonepe_error'] = $responseData['message'];
        if ($responseData['success'] == '1') {
            // echo "hi";exit;
            $orderId = $_SESSION['merchant_order_id'];
            $Candidate_model = new Candidate_model();
            $current_datetime = $Candidate_model->current_datetime();
            $invoice_no = $this->get_invoice_no();

            $userid = $userid;
            $payment_id = $responseData['data']['transactionId'];
            $p_dates = strtotime($current_datetime);
            $payment_date = date("d-m-Y h:i:sa", $p_dates);
            $payment_amount = $responseData['data']['amount'] / 100;
            $payment_ex_date = date("Y-m-d", $p_dates);
            $expiry_date = date("Y-m-d", strtotime(($payment_ex_date) . " + 1 year"));
            $paid_amount = '';
            if ($payment_amount == '799') {
                // if ($payment_amount == '1') {
                $payment_package_type = 1;
            }
            if ($payment_amount == '899') {
                // if ($payment_amount == '2') {
                $payment_package_type = 2;
            }
            if ($payment_amount == '2999') {
                // if ($payment_amount == '2') {
                $payment_package_type = 3;
            }

            $where = array('userid' => $userid);
            $data_ins = [
                'payment_status' => "1",
                'payment_amount' => $payment_amount,
                'payment_date' => $payment_date,
                'payment_expiry_date' => date("Y-m-d", strtotime($expiry_date . " - 1 day")),
                'payment_id' => $payment_id,
                'payment_package_type' => $payment_package_type
            ];
            $pro_result = $Candidate_model->update_commen('can_personal_details', $where, $data_ins);
            // echo"<pre>";print_r($pro_result);
            $where_history = array('userid' => $userid, 'order_id' => $responseData['data']['merchantTransactionId']);
            $data_history = [
                'payment_id' => $payment_id,
                'payment_date' => $payment_date,
                'payment_amount' => $payment_amount,
                'payment_status' => $responseData['code'],
                'payment_method' => $responseData['data']['paymentInstrument']['type'],
                'utr' => (!empty($responseData['data']['paymentInstrument']['utr'])) ? $responseData['data']['paymentInstrument']['utr'] : "",
                'cardType' => (!empty($responseData['data']['paymentInstrument']['cardType'])) ? $responseData['data']['paymentInstrument']['cardType'] : "",
                'pgTransactionId' => (!empty($responseData['data']['paymentInstrument']['pgTransactionId'])) ? $responseData['data']['paymentInstrument']['pgTransactionId'] : "",
                'bankTransactionId' => (!empty($responseData['data']['paymentInstrument']['bankTransactionId'])) ? $responseData['data']['paymentInstrument']['bankTransactionId'] : "",
                'pgAuthorizationCode' => (!empty($responseData['data']['paymentInstrument']['pgAuthorizationCode'])) ? $responseData['data']['paymentInstrument']['pgAuthorizationCode'] : "",
                'arn' => (!empty($responseData['data']['paymentInstrument']['arn'])) ? $responseData['data']['paymentInstrument']['arn'] : "",
                'bankId' => (!empty($responseData['data']['paymentInstrument']['bankId'])) ? $responseData['data']['paymentInstrument']['bankId'] : "",
                'pgServiceTransactionId' => (!empty($responseData['data']['paymentInstrument']['pgServiceTransactionId'])) ? $responseData['data']['paymentInstrument']['pgServiceTransactionId'] : "",
                'message' => $responseData['message'],
                'state' => $responseData['data']['state'],
                'responseCode' => $responseData['data']['responseCode'],
                'create_mode' => 1,
                'invoice_no' => $invoice_no,
            ];
            $his_result = $Candidate_model->update_commen('can_payment_details_history_phonepe', $where_history, $data_history);
            // echo"<pre>";print_r($his_result);
            $data_inv = [
                'userid' => $userid,
                'invoice_no' => $invoice_no,
                'merchant_order_id' => $responseData['data']['merchantTransactionId'],
                'merchant_txn_id' => $payment_id,
                'order_id' => $responseData['data']['merchantTransactionId'],
                'payment_id' => $payment_id,
                'payment_date' => $payment_date,
                'payment_amount' => $payment_amount,
                'payment_status' => $responseData['code'],
                'payment_method' =>  $responseData['data']['paymentInstrument']['type'],
                // 'payment_wallet' => $order->wallet,
                'created_at' => $current_datetime
            ];
            $inv_result = $Candidate_model->insert_commen('invoice_details', $data_inv);
            // echo"<pre>";print_r($inv_result);
            return redirect()->to('phonepe-success');
        } else {
            $orderId = $_SESSION['merchant_order_id'];
            $Candidate_model = new Candidate_model();
            $current_datetime = $Candidate_model->current_datetime();
            $invoice_no = $this->get_invoice_no();

            $userid = $userid;
            $payment_id = $responseData['data']['transactionId'];
            $p_dates = strtotime($current_datetime);
            $payment_date = date("d-m-Y h:i:sa", $p_dates);
            $payment_amount = $responseData['data']['amount'] / 100;
            $payment_ex_date = date("Y-m-d", $p_dates);
            $expiry_date = date("Y-m-d", strtotime(($payment_ex_date) . " + 1 year"));
            $paid_amount = '';
            if ($payment_amount == '799') {
                // if ($payment_amount == '1') {
                $payment_package_type = 1;
            }
            if ($payment_amount == '899') {
                // if ($payment_amount == '2') {
                $payment_package_type = 2;
            }
            if ($payment_amount == '2999') {
                // if ($payment_amount == '2') {
                $payment_package_type = 3;
            }
            $where_history = array('userid' => $userid, 'order_id' => $responseData['data']['merchantTransactionId']);

            $data_history = [
                'payment_id' => $payment_id,
                'payment_date' => $payment_date,
                'payment_amount' => $payment_amount,
                'payment_status' => $responseData['code'],
                'payment_method' => $responseData['data']['paymentInstrument']['type'],
                'utr' => (!empty($responseData['data']['paymentInstrument']['utr'])) ? $responseData['data']['paymentInstrument']['utr'] : "",
                'cardType' => (!empty($responseData['data']['paymentInstrument']['cardType'])) ? $responseData['data']['paymentInstrument']['cardType'] : "",
                'pgTransactionId' => (!empty($responseData['data']['paymentInstrument']['pgTransactionId'])) ? $responseData['data']['paymentInstrument']['pgTransactionId'] : "",
                'bankTransactionId' => (!empty($responseData['data']['paymentInstrument']['bankTransactionId'])) ? $responseData['data']['paymentInstrument']['bankTransactionId'] : "",
                'pgAuthorizationCode' => (!empty($responseData['data']['paymentInstrument']['pgAuthorizationCode'])) ? $responseData['data']['paymentInstrument']['pgAuthorizationCode'] : "",
                'arn' => (!empty($responseData['data']['paymentInstrument']['arn'])) ? $responseData['data']['paymentInstrument']['arn'] : "",
                'bankId' => (!empty($responseData['data']['paymentInstrument']['bankId'])) ? $responseData['data']['paymentInstrument']['bankId'] : "",
                'pgServiceTransactionId' => (!empty($responseData['data']['paymentInstrument']['pgServiceTransactionId'])) ? $responseData['data']['paymentInstrument']['pgServiceTransactionId'] : "",
                'message' => $responseData['message'],
                'state' => $responseData['data']['state'],
                'responseCode' => $responseData['data']['responseCode'],
                'create_mode' => 1,
                'invoice_no' => $invoice_no,
                //'created_at' => $current_datetime
            ];
            $Candidate_model->update_commen('can_payment_details_history_phonepe', $where_history, $data_history);
            return redirect()->to('phonepe-failed');
        }
    }
    public function phonepe_success()
    {
        $session = session();
        $Candidate_model = new Candidate_model();
        $userid = $session->get('userid');
        $where_can = array('userid' => $userid);
        $profile_personal = $Candidate_model->fetch_table_row('can_personal_details', $where_can);
        // echo "Transaction ID: " . $session->get('razorpay_payment_id');
        // echo "Order ID: " . $session->get('merchant_order_id');
        $data['orderID'] = $_SESSION['merchant_order_id'];
        $data['paymentID'] = $profile_personal->payment_id;
        $data['paymentDate'] = $profile_personal->payment_date;
        $data['paymentAmount'] = $profile_personal->payment_amount;
        return view('Candidate/can_payment_success', $data);
        // echo "text";
        //    exit;
    }
    public function phonepe_failed()
    {
        $session = session();
        // echo "Transaction ID: " . $session->get('razorpay_payment_id');
        // echo "Order ID: " .$session->get('merchant_order_id');
        $data['orderID'] = $_SESSION['merchant_order_id'];
        $data['raz_error'] = $_SESSION['phonepe_error'];
        return view('Candidate/can_payment_failed', $data);
    }


    public function emp_viewed_candidate()
    {
        $session = session();
        $Candidate_model = new Candidate_model();
        $userid    =    $session->get('userid');
        $where = array('status' => '1', 'userid' => $userid);
        $data['profile_personal'] = $Candidate_model->fetch_table_row('can_personal_details', $where);
        if ($data['profile_personal']->payment_package_type == 2 || $data['profile_personal']->payment_package_type == 3 || $data['profile_personal']->payment_package_type == 1) {
            if (!empty($data['profile_personal']->payment_expiry_date) && $data['profile_personal']->payment_expiry_date > date('Y-m-d')) {
                $data['education_details'] = $Candidate_model->fetch_table_data('can_education_details', $where);
                $data['address_details'] = $Candidate_model->fetch_table_row('can_address_details', $where);
                $data['experience_details'] = $Candidate_model->fetch_table_data('can_experience_details', $where);
                $data['skill_details'] = $Candidate_model->fetch_table_data('can_skills_details', $where);
                $data['work_sample'] = $Candidate_model->fetch_table_row('can_work_sample', $where);
                $end_date = date("Y-m-d");
                $start_date = date('Y-m-d', strtotime('-7 days', strtotime($end_date)));
                $where1 = array('emp_candidate_profile_log.status' => '1', 'emp_candidate_profile_log.candidate_id' => $userid);
                $data['view_profile_emp'] = $Candidate_model->view_profile_emp('emp_candidate_profile_log', $where1, $start_date, $end_date);

                return view('Candidate/emp_viewed_candidate', $data);
            } else {
                return redirect()->to('pricing-plan');
            }
        } else {
            return redirect()->to('pricing-plan');
        }
    }


    public function set_session_internship_id()
    {
        // print_r($_POST);exit;
        $session = session();
        $internship_id = $this->request->getVar('internship_id');
        $ses_data = [
            'internshipValue' => $internship_id,
        ];
        $session->set($ses_data);
        echo csrf_hash() . '^' . 1;
    }
    public function my_courses()
    {
        $session = session();

        /**Load Candidate model */
        $Candidate_model = new Candidate_model();

        /**Get User id Session */
        $userid          = $session->get('userid');
        $where_status = array('status' => '1', 'userid' => $userid);
        $gmetrix_detail = $Candidate_model->fetch_table_row('gmetrix_candidate_data', $where_status);
        $where_status1 = array('userid' => $userid);
        $data['course_details'] = $Candidate_model->fetch_table_row('gmetrix_course_data', $where_status1);
        // print_r($data['course_details']);
        return view('Candidate/my_courses', $data);
    }


    public function save_cartificate()
    {
        // echo "hai";exit;
        // print_r('dfg');exit;
        // Assuming 'img_files' is the name of your file input field
        $base64Image = $this->request->getPost('img_files');

        // print_r($base64Image);exit;

        // Decode the base64-encoded image data
        $decodedImage = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64Image));

        // Specify the directory to save the image
        $uploadPath = 'public/assets/docs/uploads/candidate_certificate/';

        // Generate a unique filename or use the original filename
        $filename = 'C' . uniqid() . '.png';

        // Specify the full path to save the image
        $filePath = $uploadPath . $filename;

        $file_url = base_url() . '/public/assets/docs/uploads/candidate_certificate/' . $filename;

        // Save the decoded image to the specified path
        if (file_put_contents($filePath, $decodedImage)) {
            echo csrf_hash() . '^' . $file_url;
            // Image saved successfully
            //    echo "ADD saving the image.";
        } else {
            // Failed to save the image
            http_response_code(500);
            echo "Error saving the image.";
        }
    }

    public function view_certificate($id)
    {
        $session         = session();
        $Candidate_model = new Candidate_model();


        $where_apply = array('status' => '1', 'certificate_issued_id' => $id);
        $data['apply_internship_details'] = $Candidate_model->fetch_table_row('can_applied_internship', $where_apply);

        if (isset($data['apply_internship_details'])) {
            $internship_id   = $data['apply_internship_details']->internship_id;
            $userid          = $data['apply_internship_details']->candidate_id;
            $where_edu = array('status' => '1', 'userid' => $userid);
            $order_by = array('ordercolumn' => 'education_end_year', 'ordertype' => 'desc');
            $data['education_details'] = $Candidate_model->fetch_table_data_for_all_limit('can_education_details', $where_edu);

            $where_pro = array('status' => '1', 'userid' => $userid);
            $data['profile_personal'] = $Candidate_model->fetch_table_row('can_personal_details', $where_pro);
            $where_in = array('status' => '1', 'internship_id' => $internship_id);
            $data['internship_details'] = $Candidate_model->fetch_table_data_for_all('employer_post_internship', $where_in);

            $where = array('status' => '1', 'company_id' => $data['internship_details'][0]->company_id);
            $order_by = array('ordercolumn' => 'id', 'ordertype' => 'desc');
            $data['certificate_details'] = $Candidate_model->fetch_table_data_for_all('emp_certificate_details', $where, $order_by);

            $where3 = array('status' => '1', 'userid' => $data['internship_details'][0]->company_id);
            $data['company_details'] = $Candidate_model->fetch_table_row('profile_completion_form', $where3);

            return view('Candidate/view_certificate', $data);
        }
    }

    public function can_open_assessment()
    {
        $session         = session();
        $Candidate_model = new Candidate_model();




        // API endpoint URL
        $apiUrl = 'http://localhost:8080/api/assessments/group/5';

        // Create an instance of the HTTP client
        $httpClient = \Config\Services::curlrequest();

        // Send a GET request (assuming you are fetching data) with the data
        $response = $httpClient->get($apiUrl);

        // Check for a successful API request
        if ($response->getStatusCode() === 200) {
            // Decode the JSON response
            $apiResponse = json_decode($response->getBody());

            // Process the API response as needed
            $data['assessment_list'] = $apiResponse;

            // Output the processed data for testing
            // echo "<pre>";
            // print_r($data['assessment_list']);exit;
        } else {
            // Handle the case where the API request failed
            echo "API request failed. HTTP Status Code: " . $response->getStatusCode();
        }


        return view('Candidate/can_open_assessment',$data);
    }


    public function assessment_data($assessment_id)
    {
        $session = session();

        /**Load Candidate model */
        $Candidate_model = new Candidate_model();

        /**Get User id Session */
        $userid = $session->get('userid');

// Ensure $userid is not empty before proceeding
if (empty($userid)) {
    return $this->response->setStatusCode(400)->setJSON(['error' => 'User ID is missing']);
}

$where_status = ['userid' => $userid];
$candidate_detail = $Candidate_model->fetch_table_row('can_personal_details', $where_status);

if (empty($candidate_detail->assessment_userid)) {
    $dataToSend1 = [
        'user_name'                => $candidate_detail->profile_full_name,
        'user_mobile'              => $candidate_detail->profile_phone_number,
        'user_email'               => $candidate_detail->profile_email,
        'user_account'             => 'internme',
        'user_account_group'       => '5',
        'user_account_callbackURL' => base_url().'/assessment-details/'.$assessment_id,
        'meta_key'=>['candidate_id' => $userid]
    ];

    // API endpoint URL
    $apiUrl = 'http://localhost:8080/api/sign-up';

    // Create an instance of the HTTP client
    $httpClient = \Config\Services::curlrequest();

    try {
        // Send a POST request with the data
        $response = $httpClient->post($apiUrl, [
            'json'   => $dataToSend1,
            'verify' => false,
        ]);
        // echo "<pre>";
        // print_r($response);
        // exit;
        // Check for a successful API request
        if ($response->getStatusCode() === 200) {
            $apiResponse = json_decode($response->getBody());
            //         echo "<pre>";
            // print_r($apiResponse->result);
            // exit;
            $where = array('userid' => $userid);
            $data = [
                'assessment_userid' => $apiResponse->result->user_account_key,
            ];
            $update_data = $Candidate_model->update_commen('can_personal_details', $where, $data);


            $current_datetime = $Candidate_model->current_datetime();
            $data1 = [
                'userid' => $userid,
                'assessment_userid' => $apiResponse->result->user_account_key,
                'assessment_id' => $assessment_id,
                'created_at' => $current_datetime,
            ];
            $insert_id = $Candidate_model->insert_commen('candidate_open_assessment', $data1);


            $dataToSend = [
                'user_account' => 'internme',
                // 'candidate_id' => $userid,
                'user_account_key' => $apiResponse->result->user_account_key,
                'assessment_id' => $assessment_id,
                'user_account_callbackURL' => base_url().'/assessment-details/'.$assessment_id,
                'user_active' => '1',
                'meta_key' => ['candidate_id' => $userid, 'open_assessment_id' => $insert_id],
            ];

            // API endpoint URL
            $apiUrl = 'http://localhost:8080/api/assessment-validate';

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
            //     echo "<pre>";
            // print_r($apiResponse);
            // exit;
            $where_as = array('id' => $insert_id);
            $data_as = [
                'result_assessment_key' => $apiResponse->result_assessment_key,
            ];
            $update_assessment_key = $Candidate_model->update_commen('candidate_open_assessment', $where_as, $data_as);
                return redirect()->to($apiResponse->assessment_url);
            } else {
                return $this->response->setStatusCode(500)->setJSON(['error' => 'API request failed']);
            }
            
        } else {
            return $this->response->setStatusCode(500)->setJSON(['error' => 'API request failed']);
        }
    } catch (\Exception $e) {
        // Log or display the exception message for debugging
        return $this->response->setStatusCode(500)->setJSON(['error' => $e->getMessage()]);
    }
}else{
    $current_datetime = $Candidate_model->current_datetime();
    $data1 = [
        'userid' => $userid,
        'assessment_userid' => $candidate_detail->assessment_userid,
        'assessment_id' => $assessment_id,
        'created_at' => $current_datetime,
    ];
    $insert_id = $Candidate_model->insert_commen('candidate_open_assessment', $data1);

    $dataToSend = [
        'user_account' => 'internme',
        // 'candidate_id' => $userid,
        'user_account_key' => $candidate_detail->assessment_userid,
        'assessment_id' => $assessment_id,
        'user_account_callbackURL' => base_url().'/assessment-details/'.$assessment_id,
        'user_active' => '1',
        'meta_key' => ['candidate_id' => $userid, 'open_assessment_id' => $insert_id],
    ];

    // API endpoint URL
    $apiUrl = 'http://localhost:8080/api/assessment-validate';

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
        //  echo"<pre>";print_r($apiResponse);exit;
         $where_as = array('id' => $insert_id);
         $data_as = [
             'result_assessment_key' => $apiResponse->result_assessment_key,
         ];
         $update_assessment_key = $Candidate_model->update_commen('candidate_open_assessment', $where_as, $data_as);
        return redirect()->to($apiResponse->assessment_url);
    } else {
        return $this->response->setStatusCode(500)->setJSON(['error' => 'API request failed']);
    }

}

    }

    public function assessment_details($assessment_id)
    {
        $session         = session();
        $Candidate_model = new Candidate_model();
        $userid = $session->get('userid');
        $order_by = array('ordercolumn' => 'id', 'ordertype' => 'desc');
        $where = array('result_status!=' => '', 'userid' => $userid, 'assessment_id' => $assessment_id);
        $data['assessment_score'] = $Candidate_model->fetch_table_data_for_all('candidate_open_assessment', $where,$order_by);



        // API endpoint URL
        $apiUrl = 'http://localhost:8080//api/assessments/get-assessment/'.$assessment_id;

        // Create an instance of the HTTP client
        $httpClient = \Config\Services::curlrequest();

        // Send a GET request (assuming you are fetching data) with the data
        $response = $httpClient->get($apiUrl);

        // Check for a successful API request
        if ($response->getStatusCode() === 200) {
            // Decode the JSON response
            $apiResponse = json_decode($response->getBody());

            // Process the API response as needed
            $data['assessment_data'] = $apiResponse->assessment->assessment;

            // Output the processed data for testing
            // echo "<pre>";
            // print_r($data['assessment_data']);exit;
        } else {
            // Handle the case where the API request failed
            echo "API request failed. HTTP Status Code: " . $response->getStatusCode();
        }


        return view('Candidate/candidate_assessment_score',$data);
    }
}
