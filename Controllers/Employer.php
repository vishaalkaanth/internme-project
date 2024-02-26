<?php

namespace App\Controllers;

use App\Models\Employer_model;
use App\Models\LoginModel;
use App\Models\Common_model;

use CodeIgniter\Files\File;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Supervisor;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$this->Employer_model = new Employer_model();
$this->Login_model = new LoginModel();


class Employer extends BaseController
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
        // if (strpos($current, 'applied-candidates') == false) {
        //     $session = session();
        //     $ses_data = [
        //         'applied_candidate_showing_limit',
        //     ];
        //     $session->remove($ses_data);
        // }

        if (strpos($current, 'view-candidate-logsheet') == false) {
            $session = session();
            $ses_data = [
                'emp_work_report_showing_limit',
            ];
            $session->remove($ses_data);
        }
        if (strpos($current, 'search-candidates') == false) {
            $session = session();
            $ses_data = [
                'search_candidate_showing_limit',
                // 'searched_keyword_candidates',
                // 'searched_keyword_folder'
            ];
            $session->remove($ses_data);
        }

        if (strpos($current, 'web-search-folder') == false) {
            $session = session();
            $ses_data = [
                'searched_keyword_search_folder',

            ];
            $session->remove($ses_data);
        }
        // if (strpos($current, 'web-applied-candidate') == false) {
        //     $session = session();
        //     $ses_data = [
        //         'searched_keyword',

        //     ];
        //     $session->remove($ses_data);
        // }


        // if (strpos($current, 'search-candidates') == false) {
        //     $session = session();
        //     $ses_data = [
        //         'profile_district1',
        //         'education_skills1',
        //         'education_background1',
        //         'education_college1',
        //         'gender1',
        //     ];
        //     $session->remove($ses_data);
        // }
    }

    public function emp_dashboard()
    {
        $session = session();
        $Common_model = new Common_model();
        $Employer_model = new Employer_model();
        //To get company id of the Corporate Admins
        $usertype = $session->get('usertype');
        if ($usertype == 2) {
            $company_id = $session->get('userid');
        } else {
            $where = array('userid' => $session->get('userid'));
            $admin_profile = $Employer_model->fetch_table_row('emp_manage_admins', $where);
            $company_id = $admin_profile->emp_user_id;
        }



        $where = array('status' => '1', 'company_id' => $company_id);
        $order_by = "";
        $data['list_internship'] = $Employer_model->fetch_table_data_for_all('employer_post_internship', $where, $order_by);
        $data['post_internship'] = 0;
        if (isset($data['list_internship']) && !empty($data['list_internship'])) {
            return redirect()->to('employer-dashboard-analysis');
            // return view('employer/emp_dashboard');
        } else {
            return view('employer/emp_dashboard');
        }
    }

    //----------------------------------------------------------------------------
    // ---------Employer analysis dashbaord starts here ------------------------
    //----------Main dashboard---------------------------------------------------
    public function employer_dashboard_analysis()
    {
        $session = session();
        $Common_model = new Common_model();
        $Employer_model = new Employer_model();
        //To get company id of the Corporate Admins
        $usertype = $session->get('usertype');
        if ($usertype == 2) {
            $company_id = $session->get('userid');
        } else {
            $where = array('userid' => $session->get('userid'));
            $admin_profile = $Employer_model->fetch_table_row('emp_manage_admins', $where);
            $company_id = $admin_profile->emp_user_id;
        }



        //To get posted internship count
        $where1 =  array('employer_post_internship.company_id' => $company_id);
        $data['posted_internship'] = $Common_model->data_count_fetch('employer_post_internship', $where1);
        $posted_internship = $Common_model->data_count_fetch('employer_post_internship', $where1);

        //To get open internship count
        $where2 = array('employer_post_internship.company_id' => $company_id, 'active_status' => '1', 'internship_candidate_lastdate >=' => date('Y-m-d'));
        $data['posted_internship_open'] = $Common_model->data_count_fetch('employer_post_internship', $where2);

        //To get closed internship count
        $posted_internship_open = $Common_model->data_count_fetch('employer_post_internship', $where2);
        $posted_internship_closed = $posted_internship - $posted_internship_open;
        $data['posted_internship_closed'] = $posted_internship_closed;


        //To get applied, shortlisted and hired candidates
        //echo $company_id; exit();
        $where3 = array('employer_post_internship.company_id' => $company_id, 'can_applied_internship.active_status' => '1');
        $total_applications = $Employer_model->get_application_analysis('can_applied_internship', $where3);

        $total = 0;
        $shortlisted = 0;
        $hired = 0;
        $offer_accepted = 0;
        if (!empty($total_applications)) {
            foreach ($total_applications as $key) {
                $total = $total + 1;
                if ($key->application_status == 1) {
                    $shortlisted = $shortlisted + 1;
                }
                if ($key->application_status == 2) {
                    $hired = $hired + 1;
                }
                if ($key->hiring_status == 1) {
                    $offer_accepted = $offer_accepted + 1;
                }
            }
        }
        $data['total'] = $total;
        $data['shortlisted'] = $shortlisted;
        $data['hired'] = $hired;
        $data['offer_accepted'] = $offer_accepted;

        //To get folder count
        $where4 =  array('employer_folder.employer_id' => $company_id);
        $data['folders'] = $Common_model->data_count_fetch('employer_folder', $where4);



        $where = array('status' => '1', 'company_id' => $company_id);
        $order_by = array('ordercolumn' => 'internship_candidate_lastdate', 'ordertype' => 'desc');
        $data['list_internship'] = $Employer_model->fetch_table_data_for_all('employer_post_internship', $where, $order_by);
        // $data['post_internship']=count($data['list_internship']);
        $data['post_internship'] = 0;
        if (isset($data['list_internship']) && !empty($data['list_internship'])) {
            $data['post_internship'] = count($data['list_internship']);
        }

        $where2 = array('emp_user_id' => $company_id, 'emp_type' => '1', 'active_status' => '1');
        $admin_profile1 = $Employer_model->fetch_table_data_for_all('emp_manage_admins', $where2);
        $where3 = array('userid' => $company_id);
        $admin_profile2 = $Employer_model->fetch_table_data_for_all('profile_completion_form', $where3);
        if (!empty($admin_profile1)) {
            $data['admin_profile'] = array_merge($admin_profile1, $admin_profile2);
        } else {
            $data['admin_profile'] = $admin_profile2;
        }
        return view('employer/emp_dashboard_analysis', $data);
    }

    //----------Applied candiate list - drill down report-----------------------------------------
    public function emp_dash_all_application($id = NULL)
    {

        helper(['form']);
        $Employer_model = new Employer_model();
        $session = session();
        $ses_data = [
            'show' => 0,
        ];
        $data['session_id'] = $id;

        $candidate_status = 0;
        if ($id == 1) {
            $candidate_status = 0;
        } else if ($id == 2) {
            $candidate_status = 1;
        } else if ($id == 3) {
            $candidate_status = 2;
        }

        $session->set($ses_data);
        $userid    =    $session->get('userid');
        $usertype    =    $session->get('usertype');
        if ($usertype == 2) {
            $company_id = $session->get('userid');
        } else {
            $where = array('userid' => $session->get('userid'));
            $admin_profile = $Employer_model->fetch_table_row('emp_manage_admins', $where);
            $company_id = $admin_profile->emp_user_id;
        }
        $data['company_id'] = $company_id;

        //To get applied candidate list




        $data['total_applications'] = $Employer_model->get_application_details($company_id);

        $data['total_applications_count'] = $Employer_model->get_application_details_all($company_id);
        if ($id != 1 && $id != 2 && $id != 3) {
            return view('Common/404');
        }

        //print_r($data['total_applications_count']); exit();
        return view('employer/emp_dash_all_application', $data);
    }

    // ---------Employer analysis dashbaord ends here ---------------------
    //----------------------------------------------------------------------------
    public function employer_dashboard_applications_offer()
    {
        $session = session();
        helper(['form']);
        $Employer_model = new Employer_model();

        $userid    =    $session->get('userid');
        $usertype    =    $session->get('usertype');
        if ($usertype == 2) {
            $company_id = $session->get('userid');
        } else {
            $where = array('userid' => $session->get('userid'));
            $admin_profile = $Employer_model->fetch_table_row('emp_manage_admins', $where);
            $company_id = $admin_profile->emp_user_id;
        }
        $data['company_id'] = $company_id;

        //To get applied candidate list

        $data['total_applications'] = $Employer_model->get_application_details_offer($company_id);
        //  $where = array('userid' => $session->get('userid'));
        $data['total_count'] = $Employer_model->get_application_details_offer1($company_id);

        return view('employer/employer_dashboard_applications_offer', $data);
    }


    //--------------------------------------------------------------
    //-------------number converstion function start----------------
    function number_conversition($val)
    {
        $num = $val;
        $ext = ""; //thousand,lac, crore
        $number_of_digits = $this->count_digit($num); //this is call :)
        if ($number_of_digits > 3) {
            if ($number_of_digits % 2 != 0)
                $divider = $this->divider($number_of_digits - 1);
            else
                $divider = $this->divider($number_of_digits);
        } else
            $divider = 1;

        $fraction = $num / $divider;
        $fraction = number_format($fraction);
        if ($number_of_digits == 4 || $number_of_digits == 5)
            $ext = "k";
        if ($number_of_digits == 6 || $number_of_digits == 7)
            $ext = "Lac";
        if ($number_of_digits == 8 || $number_of_digits == 9)
            $ext = "Cr";
        return $fraction . " " . $ext;
    }
    function count_digit($number)
    {
        return strlen($number);
    }

    function divider($number_of_digits)
    {
        $tens = "1";

        if ($number_of_digits > 8)
            return 10000000;

        while (($number_of_digits - 1) > 0) {
            $tens .= "0";
            $number_of_digits--;
        }
        return $tens;
    }


    //---------------number converstion function end------------------
    //------------------------------------------------------------------


    public function employer_assignment_sent($id)
    {
        $session = session();
        $Employer_model = new Employer_model();

        $userid    =    $session->get('userid');
        $usertype    =    $session->get('usertype');
        $where = array('status' => '1', 'internship_id' => $id);
        $order_by = array('ordercolumn' => 'id', 'ordertype' => 'desc');
        $data['internship_details'] = $Employer_model->fetch_table_data_for_all('employer_post_internship', $where, $order_by);

        $where = array('chat.sender_id' => $userid, 'chat.type' => '2', 'can_applied_internship.internship_id' => $id);
        $order_by = array('ordercolumn' => 'chat.id', 'ordertype' => 'desc');
        $data['assignment_details'] = $Employer_model->assignment_sent_for_candidate('chat', $where, $order_by);

        return view('employer/employer_assignment_sent', $data);
    }
    public function employer_interview_sent($id, $type = NULL)
    {
        $session = session();
        $Employer_model = new Employer_model();

        $userid    =    $session->get('userid');
        $usertype    =    $session->get('usertype');

        $where = array('status' => '1', 'internship_id' => $id);
        $order_by = array('ordercolumn' => 'id', 'ordertype' => 'desc');
        $data['internship_details'] = $Employer_model->fetch_table_data_for_all('employer_post_internship', $where, $order_by);


        // $where = array('receiver_id' => $userid,'assignment_id!=' => '');
        if (isset($type) && (!empty($type))) {
            $data['type'] = $type;
            if ($type = 2) {
                $where = array('chat.sender_id' => $userid, 'chat.type' => '3', 'can_applied_internship.internship_id' => $id, 'chat.interview_date <' => date('Y-m-d'));
            }
        } else {
            $data['type'] = '';
            $where = array('chat.sender_id' => $userid, 'chat.type' => '3', 'can_applied_internship.internship_id' => $id, 'chat.interview_date >' => date('Y-m-d'));
        }
        $order_by = array('ordercolumn' => 'chat.id', 'ordertype' => 'desc');
        $data['interview_details'] = $Employer_model->assignment_sent_for_candidate('chat', $where, $order_by);
        $session = session();
        $userid    =    $session->get('userid');
        $usertype    =    $session->get('usertype');
        if ($usertype == 2) {
            $company_id = $session->get('userid');
        } else {
            $where = array('userid' => $session->get('userid'));
            $admin_profile = $Employer_model->fetch_table_row('emp_manage_admins', $where);
            $company_id = $admin_profile->emp_user_id;
        }

        $where = array('status' => '1', 'company_id' => $company_id);
        $order_by = array('ordercolumn' => 'id', 'ordertype' => 'desc');
        $data['list_internship'] = $Employer_model->fetch_table_data_for_all('employer_post_internship', $where, $order_by);
        return view('employer/employer_interview_sent', $data);
    }

    public function emp_post_internship()
    {
        $session = session();
        $usertype    =    $session->get('usertype');
        helper(['form']);
        $Employer_model = new Employer_model();
        $userid    =    $session->get('userid');
        $where = array('userid' => $userid);
        $Employer_model = new Employer_model();
        if ($usertype == 2) {
            $available_data = $Employer_model->fetch_table_data_for_all('profile_completion_form', $where);
            if ($available_data[0]->profile_phone_no != ""  && $available_data[0]->location_name != "" && $available_data[0]->profile_company_description != "") {
                helper(['form']);
                $where = array('status' => '1', 'active_status' => '1');
                $order_by = array('ordercolumn' => 'profile', 'ordertype' => 'asc');
                $data['profile'] = $Employer_model->fetch_table_data_for_all('master_profile', $where, $order_by);
                $where1 = array('status' => '1');
                $order_by = array('ordercolumn' => 'name', 'ordertype' => 'asc');
                $data['master_specialization'] = $Employer_model->fetch_table_data_for_all('master_academic_branch', $where1, $order_by);
                $where = array('status' => '1', 'active_status' => '0');
                $order_by = array('ordercolumn' => 'profile', 'ordertype' => 'asc');
                $data['profile1'] = $Employer_model->fetch_table_data_for_all('master_profile', $where, $order_by);
                $where1 = array('status' => '1');
                $data['skills'] = $Employer_model->fetch_table_data_for_all('master_skills', $where1);
                $where2 = array('status' => '1');
                $order_by1 = array('ordercolumn' => 'city', 'ordertype' => 'asc');
                $data['location'] = $Employer_model->fetch_table_data_for_all('master_city', $where2, $order_by1);
                $where4 = array('status' => '1');
                $order_by2 = array('ordercolumn' => 'priority,name', 'ordertype' => 'asc');
                $data['master_academic_courses'] = $Employer_model->fetch_table_data_for_all_priority('master_academic_courses', $where4, $order_by2);
                $where5 = array('active_status' => '1', 'status' => '1');
                // $order_by5 = array('ordercolumn' => 'name', 'ordertype' => 'asc');
                $data['master_perks'] = $Employer_model->fetch_table_data_for_all('master_perks', $where5);
                // print_r($data['profile']);
                $where = array();
                $order_by11 = array('ordercolumn' => 'priority,dist_name', 'ordertype' => 'asc');
                $data['master_location'] = $Employer_model->fetch_table_data_for_all_priority('master_district', $where, $order_by11);
                return view('employer/post_internship', $data);
            } else {
                // print_r($available_data);exit;
                if (empty($available_data[0]->profile_phone_no) || empty($available_data[0]->location_name)) {
                    $ses_data = ['redirect' => 'postinternship',];
                    $session->set($ses_data);
                    return redirect()->to('organization-details');
                } elseif (empty($available_data[0]->profile_company_description)) {
                    $ses_data = ['redirect' => 'postinternship',];
                    $session->set($ses_data);
                    return redirect()->to('other-info');
                }
            }
        } else {
            $where = array('status' => '1', 'active_status' => '1');
            $order_by = array('ordercolumn' => 'id', 'ordertype' => 'desc');
            $data['profile'] = $Employer_model->fetch_table_data_for_all('master_profile', $where, $order_by);
            $where = array('status' => '1', 'active_status' => '0');
            $order_by = array('ordercolumn' => 'id', 'ordertype' => 'desc');
            $data['profile1'] = $Employer_model->fetch_table_data_for_all('master_profile', $where, $order_by);
            $where1 = array('status' => '1');
            $data['skills'] = $Employer_model->fetch_table_data_for_all('master_skills', $where1);
            $where2 = array('status' => '1');
            $order_by1 = array('ordercolumn' => 'city', 'ordertype' => 'asc');
            $data['location'] = $Employer_model->fetch_table_data_for_all('master_city', $where2, $order_by1);
            $where4 = array('status' => '1');
            $order_by2 = array('ordercolumn' => 'priority,name', 'ordertype' => 'asc');
                $data['master_academic_courses'] = $Employer_model->fetch_table_data_for_all_priority('master_academic_courses', $where4, $order_by2);
            $where5 = array('active_status' => '1', 'status' => '1');
            // $order_by5 = array('ordercolumn' => 'name', 'ordertype' => 'asc');
            $data['master_perks'] = $Employer_model->fetch_table_data_for_all('master_perks', $where5);
            // print_r($data['profile']);
            $where = array();
            $order_by11 = array('ordercolumn' => 'priority,dist_name', 'ordertype' => 'asc');
            $data['master_location'] = $Employer_model->fetch_table_data_for_all_priority('master_district', $where, $order_by11);
            $order_by = array('ordercolumn' => 'name', 'ordertype' => 'asc');
            $where1 = array('status' => '1');
                $data['master_specialization'] = $Employer_model->fetch_table_data_for_all('master_academic_branch', $where1, $order_by);
            return view('employer/post_internship', $data);
        }
    }


    public function add_internship()
    {
        $session = session();
        $Employer_model = new Employer_model();
        extract($_REQUEST);
        $userModel = new LoginModel();
        $current_datetime = $userModel->current_datetime();
        $usertype = $session->get('usertype');
        if ($this->request->getMethod() == 'post') {


            $validation =  \Config\Services::validation();
            $isValidated = $this->validate([
                'internship_profile_hidden' => ['label'  => 'profile', 'rules'  => 'required',],
                'internship_type_hidden' => ['label'  => 'Internship Type', 'rules'  => 'required',],
                'education_pre' => ['label'  => 'Preferred Education Qualification', 'rules'  => 'required',],
                'num_opening' => ['label'  => 'Number of openings', 'rules'  => 'required|max_length[3]',],
                'parttime_hidden' => ['label'  => ' Part time ', 'rules'  => 'required',],
                'internship_startdate' => ['label'  => ' Internship Start Date ', 'rules'  => 'required',],
                'internship_lastdate' => ['label'  => ' Last Date for Apply ', 'rules'  => 'required',],
                'internship_duration' => ['label'  => ' Internship Duration ', 'rules'  => 'required',],
                'stipend_hidden' => ['label'  => ' Stipend & Perks ', 'rules'  => 'required',],
                'responsibilities' => ['label'  => ' Intern Responsibilities ', 'rules'  => 'required',],

            ]);
            if ($this->request->getVar('internship_profile_hidden') == 0) {
                $isValidated = $this->validate([
                    'other_profile' => ['label'  => 'Other profile Name', 'rules'  => 'required',],

                ]);
            }
            if ($this->request->getVar('internship_type_hidden') == 1) {
                $isValidated = $this->validate([
                    'work_location' => ['label'  => 'Work Location', 'rules'  => 'required',],

                ]);
            }
            if ($this->request->getVar('stipend_hidden') == 2) {
                $isValidated = $this->validate([
                    'amount1' => ['label'  => 'Minimum Amount', 'rules'  => 'required|max_length[6]',],

                ]);
            }
            if ($this->request->getVar('stipend_hidden') == 3) {
                $isValidated = $this->validate([
                    'amount1' => ['label'  => 'Minimum Amount', 'rules'  => 'required|max_length[6]',],
                    'amount1' => ['label'  => 'Maximum Amount', 'rules'  => 'required|max_length[6]',],

                ]);
            }
            //if not validated 
            if (!$isValidated) {
                $session->setFlashdata('error_status', '3');
                $session->setFlashdata('error_msg', $validation->getErrors());
                return redirect()->to('post-internship');
            } else {
                if ($usertype == '2') {
                    $sub_admin_company_id = $session->get('userid');
                } else {
                    $where_sub_admin = array('userid' => $session->get('userid'));
                    $sub_admin_profile = $Employer_model->fetch_table_row('emp_manage_admins', $where_sub_admin);
                    $sub_admin_company_id = $sub_admin_profile->emp_user_id;
                }
                // print_r($_POST);exit;
                $internshipid = '5' . date('ymdhis') . rand(11111, 99999);
                if (isset($work_location)) {
                    $map_id2 = $this->request->getVar('work_location');
                    // print_r($map_id2);exit();
                    // $commonarr = implode(",", $map_id2);

                    if (isset($map_id2) && !empty($map_id2)) {
                        $data_worklocation = array();
                        if (!empty($map_id2)) {
                            foreach ($map_id2 as $key => $value) {
                                $where = array('dist_name' => $value);
                                $master_location = $Employer_model->fetch_table_row('master_district', $where);
                                //print_r($key);
                                $data_worklocation[]  = array('user_id' => $sub_admin_company_id, 'internship_id' => $internshipid, 'g_location_id'  => $master_location->dist_id, 'g_location_name'  => $master_location->dist_name, 'location_district'  => $master_location->dist_name, 'location_state'  => $master_location->state_name, 'status'  => '1');
                            }
                        }

                        if (count($data_worklocation) > 0) {
                            $data_worklocation =  $Employer_model->insertBatch1('emp_worklocation_multiple', $data_worklocation);
                        }
                    }
                } else {
                    $commonarr = '';
                }

                if (isset($selected_skills)) {
                    $selected_skills = $this->request->getVar('selected_skills');
                    // $selected_skillsarr = implode(",", $selected_skills);
                    if (isset($selected_skills) && !empty($selected_skills)) {
                        $data_selected_skills = array();
                        if (!empty($selected_skills)) {
                            foreach ($selected_skills as $key) {
                                $data_selected_skills[]  = array('user_id' => $sub_admin_company_id, 'internship_id' => $internshipid, 'selected_skills'  => $key, 'status'  => '1');
                            }
                        }

                        if (count($data_selected_skills) > 0) {
                            $data_selected_skills =  $Employer_model->insertBatch1('emp_selected_skills_multiple', $data_selected_skills);
                        }
                    }
                } else {
                    $selected_skillsarr = '';
                }
                if (isset($education_pre)) {
                    $education_pre = $this->request->getVar('education_pre');
                    // $education_prearr = implode(",", $education_pre);
                    if (isset($education_pre) && !empty($education_pre)) {
                        $data_education_pre = array();
                        if (!empty($education_pre)) {
                            foreach ($education_pre as $key) {
                                $data_education_pre[]  = array('user_id' => $sub_admin_company_id, 'internship_id' => $internshipid, 'education'  => $key, 'status'  => '1');
                            }
                        }

                        if (count($data_education_pre) > 0) {
                            $data_education_pre =  $Employer_model->insertBatch1('emp_selected_education_multiple', $data_education_pre);
                        }
                    }
                } else {
                    $education_prearr = '';
                }
                if (isset($specialization_pre)) {
                    $specialization_pre = $this->request->getVar('specialization_pre');
                    // $education_prearr = implode(",", $education_pre);
                    if (isset($specialization_pre) && !empty($specialization_pre)) {
                        $data_specialization_pre = array();
                        if (!empty($specialization_pre)) {
                            foreach ($specialization_pre as $key) {
                                $data_specialization_pre[]  = array('user_id' => $sub_admin_company_id, 'internship_id' => $internshipid, 'specialization'  => $key, 'status'  => '1');
                            }
                        }

                        if (count($data_specialization_pre) > 0) {
                            $data_specialization_pre =  $Employer_model->insertBatch1('emp_selected_specialization_multiple', $data_specialization_pre);
                        }
                    }
                } else {
                    $specialization_prearr = '';
                }

                if (isset($perks)) {
                    $perks = $this->request->getVar('perks');
                    // $education_prearr = implode(",", $education_pre);
                    if (isset($perks) && !empty($perks)) {
                        $data_perks = array();
                        if (!empty($perks)) {
                            foreach ($perks as $key) {
                                $data_perks[]  = array('user_id' => $sub_admin_company_id, 'internship_id' => $internshipid, 'perks'  => $key, 'status'  => '1');
                            }
                        }

                        if (count($data_perks) > 0) {
                            $data_perks =  $Employer_model->insertBatch1('emp_selected_perks_multiple', $data_perks);
                        }
                    }
                } else {
                    $data_perks = '';
                }
                $duration = $this->request->getVar('internship_duration');

                if ($duration == 1) {
                    $duration1 = $this->request->getVar('internship_duration2');
                    $duration_days = $duration1 * 7;
                } else {
                    $duration1 = $this->request->getVar('internship_duration1');
                    $duration_days = $duration1 * 30;
                }
                $usertype = $session->get('usertype');
                if ($usertype == 2) {
                    $company_id = $session->get('userid');
                } else {
                    $where = array('userid' => $session->get('userid'));
                    $admin_profile = $Employer_model->fetch_table_row('emp_manage_admins', $where);
                    $company_id = $admin_profile->emp_user_id;
                }
                if($this->request->getVar('amount1')!=""){
                    $premium_status='1';
                }else{
                    $premium_status='0';
                }

                $data = [
                    'user_id' => $session->get('userid'),
                    'company_id' => $company_id,
                    'internship_id' => $internshipid,
                    'profile' => $this->request->getVar('internship_profile_hidden'),
                    'other_profile' => $this->c_trim($this->request->getVar('other_profile')),
                    'internship_type' => $this->request->getVar('internship_type_hidden'),
                    // 'city' => $commonarr,
                    'number_opening' => $this->c_trim($this->request->getVar('num_opening')),
                    'partime' => $this->request->getVar('parttime_hidden'),
                    // 'prefer_worklocation' => $this->request->getVar('prefer_ca_work_location_hidden'),
                    'prefer_gender' => $this->request->getVar('gender'),
                    'pre_placement_offer' => $this->request->getVar('pre_placement_offer_hidden'),
                    'internship_startdate' => $this->request->getVar('internship_startdate'),
                    'internship_duration_type' => $this->request->getVar('internship_duration'),
                    'internship_duration' => $duration1,
                    'stipend' => $this->request->getVar('stipend_hidden'),
                    'interns_responsibilities' => $this->c_trim($this->request->getVar('responsibilities')),
                    'about_internship' => $this->c_trim($this->request->getVar('about_internship')),
                    'amount_from' => $this->c_trim($this->request->getVar('amount1')),
                    'amount_to' => $this->c_trim($this->request->getVar('amount2')),
                    'internship_candidate_lastdate' => $this->request->getVar('internship_lastdate'),
                    'duration_days' => $duration_days,
                    'assigned_to' => $session->get('userid'),
                    'created_at' => $current_datetime,
                    'premium_status' => $premium_status,
                ];
                $result = $Employer_model->insert_commen('employer_post_internship', $data);



                if ($result) {

                    $ses_data = ['internship_repost'];
                    $session->remove($ses_data);
                    //get employer data          
                    $employer_data = $Employer_model->get_employer_data($session->get('userid'));
                    if (!empty($employer_data)) {
                        if ($employer_data[0]["profile_official_email"] != '') {
                            //get internship name
                            if ($this->request->getVar('internship_profile_hidden') == 0) {
                                $internship_name = $this->request->getVar('other_profile');
                            } else {
                                //get profile name      
                                $get_profile_name = $Employer_model->get_profile_name($this->request->getVar('internship_profile_hidden'));
                                if (!empty($get_profile_name)) {
                                    $internship_name = $get_profile_name[0]['profile'];
                                } else {
                                    $internship_name = '';
                                }
                            }
                            $current_year = date('Y');
                            //sent email -Gmail

                            // $msg_data['msg_data'] = array('intership' => $internship_name, 'name' => $employer_data[0]["profile_name"]); //dynamic contents for template
                            // $message     = view('email_template/internship_live', $msg_data);

                            $message = '{ "intership" : "' . $internship_name . '", "name" : "' . $employer_data[0]["profile_name"] . '","year" : ' . $current_year . ' }'; //dynamic contents for template
                            $subject      = 'Internme - Internship';
                            $to_email     =  $employer_data[0]["profile_official_email"];
                            $from_content = 'Internme - Internship';
                            $template_key = '2d6f.456f260c51ab9602.k1.9d8b9880-a7a4-11ed-bfa0-525400fcd3f1.18630ceb208';
                            $this->email_send($message, $subject, $to_email, $from_content, $template_key);

                            // $this->email_send($message, $subject, $to_email, $from_content);

                            //internal mail start
                            if ($this->request->getVar('stipend_hidden') != '1') {
                                if (!empty($this->request->getVar('amount1')) && $this->request->getVar('amount1') != '0') {
                                    $stipend= '₹ '.$this->request->getVar('amount1');
                                }  
                                 if (!empty($this->request->getVar('amount2')) && $this->request->getVar('amount2') != '0') {
                                    $stipend= '₹ ' . $this->request->getVar('amount1') .' - ' . $this->request->getVar('amount2');
                                        }  } else {
                                            $stipend= "Unpaid";
                                        } 
                                        $start_date= date("d M Y", strtotime($this->request->getVar('internship_startdate')));
                                        $last_date=date("d-m-Y", strtotime($this->request->getVar('internship_lastdate')));

                                        if ($this->request->getVar('internship_type_hidden') == 1) {
                                            $in_type= "Regular (In-office)";
                                        } else {
                                            $in_type= "Work From Home";
                                        } 
                                        $openings=$this->request->getVar('num_opening');
                                        if(base_url()=='https://internme.app'){
                            // $current_year = date('Y');
                            $message = '{"internship_name" : "' . $internship_name . '","org_name" : "' . $employer_data[0]["profile_company_name"] . '","year" : "' . $current_year . '","stipend" : "' . $stipend . '","start_date" : "' . $start_date . '","last_date" : "' . $last_date . '","type" : "' . $in_type . '","openings" : "' . $openings . '", "name" : "' . $employer_data[0]["profile_name"] . '", "phone" : "' . $employer_data[0]["profile_phone_no"] . '", "email" : "' . $employer_data[0]["profile_official_email"] . '"  }'; //dynamic contents for template
    
                            $subject  = 'Internme - New corporate -  ' . $internship_name . ' | ' . $employer_data[0]["profile_company_name"] . '';
                            $to_email  = 'mk@in22labs.com';
    
                            $from_content = 'Internme - New corporate -  ' . $internship_name . ' | ' . $employer_data[0]["profile_company_name"] . '';
    
                            $template_key = '2d6f.456f260c51ab9602.k1.9c4fe480-4010-11ee-8f95-52540064429e.18a17b822c8';
    
                            $this->email_send($message, $subject, $to_email, $from_content, $template_key);
    
                            // $current_year = date('Y');
                            $message = '{"internship_name" : "' . $internship_name . '","org_name" : "' . $employer_data[0]["profile_company_name"] . '","year" : "' . $current_year . '","stipend" : "' . $stipend . '","start_date" : "' . $start_date . '","last_date" : "' . $last_date . '","type" : "' . $in_type . '","openings" : "' . $openings . '", "name" : "' . $employer_data[0]["profile_name"] . '", "phone" : "' . $employer_data[0]["profile_phone_no"] . '", "email" : "' . $employer_data[0]["profile_official_email"] . '"  }'; //dynamic contents for template
    
                            $subject  = 'Internme - New corporate -  ' . $internship_name . ' | ' . $employer_data[0]["profile_company_name"] . '';
                            $to_email  = 'thanuj@launchpadllc.in';
    
                            $from_content = 'Internme - New corporate -  ' . $internship_name . ' | ' . $employer_data[0]["profile_company_name"] . '';
    
                            $template_key = '2d6f.456f260c51ab9602.k1.9c4fe480-4010-11ee-8f95-52540064429e.18a17b822c8';
    
                            $this->email_send($message, $subject, $to_email, $from_content, $template_key);

                            //internal mail end

                                        }
                        }
                    }
                    $where = array('id' => $result);
                    $shortlist_internship_id = $Employer_model->fetch_table_row('employer_post_internship', $where);

                    $ses_data = [
                        'show' => 1,
                        'shortlist_internship_id' => $shortlist_internship_id->internship_id,

                    ];
                    $session->set($ses_data);
                    session()->setTempdata('success', 'Internship Posted successfully', 2);
                    return redirect()->to('post-internship');
                }
            }
        }
    }


    public function get_skills_by_profile()
    {
        // $model = new Employer_model();
        $profile_id = $this->request->getVar('profile');
        $selceted = $this->request->getVar('selected');
        // print_r($selceted);
        if ($selceted != '') {
            $split = explode(',', $selceted);
        } else {
            $split = array();
        }
        // print_r($split);
        $Employer_model = new Employer_model();
        $where = "FIND_IN_SET('" . $profile_id . "', profile_id)";
        // $where = array('profile_id' => $profile_id, 'status' => '1');
        $order_by = array('ordercolumn' => 'skill_name', 'ordertype' => 'asc');
        $profile = $Employer_model->fetch_table_data_for_all('master_skills', $where, $order_by);

        $getdates = '';

        if (!empty($profile)) {
            foreach ($profile as $as) {
                if (!in_array($as->id, $split)) {
                    $getdates = $getdates . "<li class='me-3' onclick='possition_change(" . $as->id . ");' id='position_" . $as->id . "'><label for='checkboxOne' class='text-gray'>" . $as->skill_name . "</label></li>";
                }
            }
        }

        echo csrf_hash() . '^' . $getdates;
        // echo $getdates;
    }

    public function get_skills_by_profile_edit()
    {
        $Employer_model = new Employer_model();

        $interenship_id = $this->request->getVar('interenship_id');
        //    print_r($interenship_id);
        $profile_id = $this->request->getVar('profile');
        $selceted = $this->request->getVar('selected');
        // print_r($selceted);
        if ($selceted != '') {
            $split = explode(',', $selceted);
        } else {
            $split = array();
        }
        $where2 = array('status' => '1', 'internship_id' => $interenship_id);
        $skills1 = $Employer_model->fetch_table_data_for_all('emp_selected_skills_multiple', $where2);
        $selected_skills = array();
        if (!empty($skills1)) {
            foreach ($skills1 as $sk) {
                $selected_skills[] = $sk->selected_skills;
            }
        }
        //    print_r($skills1);

        $where = "FIND_IN_SET('" . $profile_id . "', profile_id)";
        // $where = array('profile_id' => $profile_id, 'status' => '1');
        $order_by = array('ordercolumn' => 'skill_name', 'ordertype' => 'asc');
        $profile = $Employer_model->fetch_table_data_for_all('master_skills', $where, $order_by);

        $getdates = '';

        if (!empty($profile)) {
            foreach ($profile as $as) {
                if (!in_array($as->id, $split)) {
                    if (!in_array($as->id, $selected_skills)) {
                        $getdates = $getdates . "<li class='me-3' onclick='possition_change(" . $as->id . ");' id='position_" . $as->id . "'><label for='checkboxOne' class='text-gray'>" . $as->skill_name . "</label></li>";
                    }
                }
            }
        }

        echo csrf_hash() . '^' . $getdates;
        // echo $getdates;
    }
    public function get_skills_by_profile1()
    {
        // echo "hi";exit;
        // $model = new Employer_model();
        $Employer_model = new Employer_model();
        $profile_name = $this->request->getVar('profile');
        $where1 = array('profile' => $profile_name);
        $profile_id = $Employer_model->get_master_commen_for_all('master_profile', $where1, 'id');
        $selceted = $this->request->getVar('selected');
        if ($selceted != '') {
            $split = explode(',', $selceted);
        } else {
            $split = array();
        }
        $where = "FIND_IN_SET('" . $profile_id . "', profile_id)";
        $order_by = array('ordercolumn' => 'skill_name', 'ordertype' => 'asc');
        // $where = array('profile_id' => $profile_id, 'status' => '1');
        $profile = $Employer_model->fetch_table_data_for_all('master_skills', $where, $order_by);

        $getdates = '';
        $dates    = '';
        if (!empty($profile)) {
            foreach ($profile as $as) {
                if (!in_array($as->id, $split)) {
                    $getdates = $getdates . "<li class='me-3' onclick='possition_change(" . $as->id . ");' id='position_" . $as->id . "'><label for='checkboxOne' class='text-gray'>" . $as->skill_name . "</label></li>";
                }
            }
            //   print_r($getdates);exit;
        }

        echo csrf_hash() . '^' . $getdates . '^' . $profile_id;
        // echo $getdates;
    }
    public function get_skills_all()
    {
        // $model = new Employer_model();
        $profile_id = $this->request->getVar('profile');
        $Employer_model = new Employer_model();
        $order_by = array('ordercolumn' => 'skill_name', 'ordertype' => 'asc');
        $where = "NOT FIND_IN_SET('" . $profile_id . "', profile_id)";
        // $where = array('profile_id !=' => $profile_id, 'status' => '1');
        $profile = $Employer_model->fetch_table_data_for_all('master_skills', $where, $order_by);
        $getdates = '';
        //  $getdates ='<option value="" style="color:#bfbfbf;" >--Select Skills--</option>';
        if (!empty($profile)) {
            foreach ($profile as $ps) {
                $getdates = $getdates . "<option value='" . $ps->id . "'  id='positionn_" . $ps->id . "' >" . $ps->skill_name . "</option>";
            }
        }
        echo  csrf_hash() . '^' . $getdates;
        // echo view('crud/user_table', $data);
    }
    public function get_skills_all1()
    {
        // $model = new Employer_model();
        $Employer_model = new Employer_model();
        $profile_name = $this->request->getVar('profile');
        $where1 = array('profile' => $profile_name);
        $profile_id = $Employer_model->get_master_commen_for_all('master_profile', $where1, 'id');
        $where = "NOT FIND_IN_SET('" . $profile_id . "', profile_id)";
        // $where = array('profile_id !=' => $profile_id, 'status' => '1');
        $order_by = array('ordercolumn' => 'skill_name', 'ordertype' => 'asc');
        $profile = $Employer_model->fetch_table_data_for_all('master_skills', $where, $order_by);
        $getdates = '';
        //  $getdates ='<option value="" style="color:#bfbfbf;" >--Select Skills--</option>';
        if (!empty($profile)) {
            foreach ($profile as $ps) {
                $getdates = $getdates . "<option value='" . $ps->id . "'  id='positionn_" . $ps->id . "' >" . $ps->skill_name . "</option>";
            }
        }
        echo  csrf_hash() . '^' . $getdates;
        // echo view('crud/user_table', $data);
    }
    public function get_skills_all_default()
    {
        // $model = new Employer_model();
        // $profile_id = $this->request->getVar('profile');
        $Employer_model = new Employer_model();
        $where = array('status' => '1');
        $order_by = array('ordercolumn' => 'skill_name', 'ordertype' => 'asc');
        $profile = $Employer_model->fetch_table_data_for_all('master_skills', $where, $order_by);
        $getdates = '';
        $getdates = '<option value="" style="color:#bfbfbf;" disabled>--Select Skills--</option>';
        if (!empty($profile)) {
            foreach ($profile as $ps) {
                $getdates = $getdates . "<option value='" . $ps->id . "'  id='positionn_" . $ps->id . "' >" . $ps->skill_name . "</option>";
            }
        }
        echo  csrf_hash() . '^' . $getdates;
        // echo view('crud/user_table', $data);
    }

    public function internship_list($type = NULL)
    {
        helper(['form']);
        $Employer_model = new Employer_model();
        $session = session();
        $ses_data = [
            'show' => 0,

        ];
        $session->set($ses_data);
        $userid    =    $session->get('userid');
        $usertype    =    $session->get('usertype');
        if ($usertype == 2) {
            $company_id = $session->get('userid');
        } else {
            $where = array('userid' => $session->get('userid'));
            $admin_profile = $Employer_model->fetch_table_row('emp_manage_admins', $where);
            $company_id = $admin_profile->emp_user_id;
        }

        $where = array('status' => '1', 'company_id' => $company_id);
        $order_by = array('ordercolumn' => 'internship_candidate_lastdate', 'ordertype' => 'desc');
        $data['list_internship'] = $Employer_model->fetch_table_data_for_all('employer_post_internship', $where, $order_by);
        // $data['post_internship']=count($data['list_internship']);
        if (isset($data['list_internship']) && !empty($data['list_internship'])) {
            $data['post_internship'] = count($data['list_internship']);
        } else {
            $data['post_internship'] = 0;
        }

        $today_date = date('Y-m-d');
        $where_open = '(date(internship_candidate_lastdate) >= "' . $today_date . '" AND active_status = 1) AND status = "1" AND  company_id = "' . $company_id . '"';
        $order_by = array('ordercolumn' => 'internship_candidate_lastdate', 'ordertype' => 'desc');
        // $where_open = array('active_status' =>'1','internship_candidate_lastdate >=' => date('Y-m-d'),'status' => '1', 'company_id' => $company_id);
        $list_internship_open = $Employer_model->fetch_table_data_for_all('employer_post_internship', $where_open, $order_by);
        //   print_r($data['list_internship_open']);exit;
        if (isset($list_internship_open) && !empty($list_internship_open)) {
            $list_internship_open = $list_internship_open;
        } else {
            $list_internship_open = array();
        }
        $where_closed = '(date(internship_candidate_lastdate) < "' . $today_date . '" OR active_status = 0) AND status = "1" AND  company_id = "' . $company_id . '"';
        $order_by = array('ordercolumn' => 'internship_candidate_lastdate', 'ordertype' => 'desc');
        // $where_closed = array('active_status' =>'1','internship_candidate_lastdate <' => date('Y-m-d'),'status' => '1', 'company_id' => $company_id);
        $list_internship_closed = $Employer_model->fetch_table_data_for_all('employer_post_internship', $where_closed, $order_by);
        // print_r($data['list_internship_closed']);exit;
        if (isset($list_internship_closed) && !empty($list_internship_closed)) {
            $list_internship_closed = $list_internship_closed;
        } else {
            $list_internship_closed = array();
        }
        $where_open1 = '(date(internship_candidate_lastdate) >= "' . $today_date . '" AND active_status = 1) AND status = "1" AND  company_id = "' . $company_id . '" AND assigned_to = "' . $userid . '"';
        $order_by = array('ordercolumn' => 'internship_candidate_lastdate', 'ordertype' => 'desc');
        $list_internship_hr_open = $Employer_model->fetch_table_data_for_all('employer_post_internship', $where_open1, $order_by);
        // print_r($list_internship_open);exit;
        // $list_internship_count = count($list_internship_hr_open);
        if (!empty($list_internship_hr_open)) {
            $data['list_internship_count'] = count($list_internship_hr_open);
        } else {
            $data['list_internship_count'] = 0;
        }
        //   print_r($list_internship_count);exit;
        $where_closed1 = '(date(internship_candidate_lastdate) < "' . $today_date . '" OR active_status = 0) AND status = "1" AND  company_id = "' . $company_id . '" AND assigned_to = "' . $userid . '"';
        $order_by = array('ordercolumn' => 'internship_candidate_lastdate', 'ordertype' => 'desc');
        $list_internship_hr_close = $Employer_model->fetch_table_data_for_all('employer_post_internship', $where_closed1, $order_by);

        if (!empty($list_internship_hr_close)) {
            $data['list_internship_count1'] = count($list_internship_hr_close);
        } else {
            $data['list_internship_count1'] = 0;
        }
        //  echo($list_internship_count1);exit;

        if (isset($type) && !empty($type)) {

            if ($type == '2' || $type == '7') {
                $ses_data = [
                    'active_session' => 2,

                ];
                $session->set($ses_data);
                $data['list_internship_asc'] = $Employer_model->fetch_table_data_for_all('employer_post_internship', $where_open, $order_by);

                // if(!empty($data['list_internship_asc'])){
                //     $data['list_internship_count_all_open'] = count($data['list_internship_asc']);
                //       }else{
                //         $data['list_internship_count_all_open'] =0;
                //       }
            } elseif ($type == '3' || $type == '8') {
                $ses_data = [
                    'active_session' => 3,

                ];
                $session->set($ses_data);
                $data['list_internship_asc'] = $Employer_model->fetch_table_data_for_all('employer_post_internship', $where_closed, $order_by);
            } else {
                $ses_data = [
                    'active_session' => 1,

                ];
                $session->set($ses_data);
                $data['list_internship_asc'] = array_merge($list_internship_open, $list_internship_closed);
            }
        } else {
            $ses_data = [
                'active_session' => 0,

            ];
            $session->set($ses_data);
            $data['list_internship_asc'] = array_merge($list_internship_open, $list_internship_closed);
        }
        // $data['list_internship_asc'] = array_merge($list_internship_open, $list_internship_closed);
        // print_r($data['list_internship_asc']);exit;

        $where = array('active_status' => '1', 'internship_candidate_lastdate >=' => date('Y-m-d'), 'status' => '1', 'company_id' => $company_id);
        $data['list_internship_count_open'] = $Employer_model->data_count_fetch1('employer_post_internship', $where);

        $data['posted_internship_closed'] = $data['post_internship'] - $data['list_internship_count_open'];

        // // if(!empty($data['list_internship'])){
        // //     $data['list_internship_count'] = count($data['list_internship']);
        // //   }else{
        // //      $data['list_internship_count'] =0;
        // //   }
        $where2 = array('emp_user_id' => $company_id, 'emp_type' => '1', 'active_status' => '1');
        $admin_profile1 = $Employer_model->fetch_table_data_for_all('emp_manage_admins', $where2);
        $where3 = array('userid' => $company_id);
        $admin_profile2 = $Employer_model->fetch_table_data_for_all('profile_completion_form', $where3);
        if (!empty($admin_profile1)) {
            $data['admin_profile'] = array_merge($admin_profile1, $admin_profile2);
        } else {
            $data['admin_profile'] = $admin_profile2;
        }

        // print_r($data['admin_profile']);exit;
        $ses_data = [
            'profile_state',
            'profile_district',
            'education_skills',
            'education_background',
            // 'graduation_year',
            'application_status',
            'gender',

        ];

        $session->remove($ses_data);
        if ($type == 5 || $type == 6 || $type == 7 || $type == 8) {
            return view('employer/emp_intern_list_all', $data);
        } else {
            return view('employer/emp_intern_list', $data);
        }
    }




    public function internship_single($id)
    {
        helper(['form']);
        $session = session();
        $ses_data = [
            'show_edit' => 0,

        ];
        $session->set($ses_data);
        $Employer_model = new Employer_model();
        $where = array('status' => '1', 'internship_id' => $id);
        $data['internship_details'] = $Employer_model->fetch_table_data_for_all('employer_post_internship', $where);
        $internship_details = $data['internship_details'];
        // print_r($internship_details);exit;
        if (isset($internship_details) && !empty($internship_details)) {
            $where1 = array('status' => '1', 'internship_id' => $id);
            $data['location'] = $Employer_model->fetch_table_data_for_all('emp_worklocation_multiple', $where1);
            $where2 = array('status' => '1', 'internship_id' => $id);
            $data['skills'] = $Employer_model->fetch_table_data_for_all('emp_selected_skills_multiple', $where2);
            $where3 = array('status' => '1', 'internship_id' => $id);
            $data['education'] = $Employer_model->fetch_table_data_for_all('emp_selected_education_multiple', $where3);
            $where6 = array('status' => '1', 'internship_id' => $id);
            $data['received'] = $Employer_model->data_count_fetch('can_applied_internship', $where6);
            $where7 = array('status' => '1', 'internship_id' => $id);
            $data['perks'] = $Employer_model->fetch_table_data_for_all('emp_selected_perks_multiple', $where7);
            $where8 = array('status' => '1', 'internship_id' => $id);
            $data['specialization'] = $Employer_model->fetch_table_data_for_all('emp_selected_specialization_multiple', $where8);
            // $where7 = array('status' => '1', 'internship_id' => $id, 'application_status' => 1);

            return view('employer/emp_intern_single', $data);
        } else {
            return view('Common/404');
        }
    }

    public function emp_profile_step1()
    {
        helper(['form']);
        $session = session();
        $Employer_model = new Employer_model();
        $userid    =    $session->get('userid');
        $usertype    =    $session->get('usertype');
        if ($usertype == 2) {
            $where = array('userid' => $userid);
            $data['emp_profile'] = $Employer_model->fetch_table_data_for_all('profile_completion_form', $where);
        } else {
            $where = array('userid' => $userid);
            $data['emp_profile'] = $Employer_model->fetch_table_data_for_all('emp_manage_admins', $where);
            $where = array('userid' => $data['emp_profile'][0]->emp_user_id);
            $data['company_emp_profile'] = $Employer_model->fetch_table_data_for_all('profile_completion_form', $where);
        }

        $where = array();
        $order_by11 = array('ordercolumn' => 'dist_name', 'ordertype' => 'asc');
        $data['master_location'] = $Employer_model->fetch_table_data_for_all('master_district', $where, $order_by11);
        return view('employer/emp_profile', $data);
    }
    public function emp_profile_step2()
    {
        helper(['form']);
        $session = session();
        $Employer_model = new Employer_model();
        $userid    =    $session->get('userid');
        $where = array('userid' => $userid);
        $data['emp_profile'] = $Employer_model->fetch_table_data_for_all('profile_completion_form', $where);
        $emp_profile1 = $Employer_model->fetch_table_data_for_all('profile_completion_form', $where);
        $where1 = array('status' => '1');
        $order_by = array('ordercolumn' => 'name', 'ordertype' => 'asc');
        $data['state'] = $Employer_model->fetch_table_data_for_all('master_state', $where1, $order_by);
        $where2 = array('status' => '1', 'state_id' => $emp_profile1[0]->profile_address_state);
        $order_by1 = array('ordercolumn' => 'dist_name', 'ordertype' => 'asc');
        $data['get_district'] = $Employer_model->fetch_table_data_for_all('master_district', $where2, $order_by1);

        return view('employer/emp_profile1', $data);
    }
    public function emp_profile_step3()
    {
        helper(['form']);
        $session = session();
        $Employer_model = new Employer_model();
        $userid    =    $session->get('userid');
        $usertype    =    $session->get('usertype');
        if ($usertype == 2) {
            $where = array('userid' => $userid);
            $data['emp_profile'] = $Employer_model->fetch_table_data_for_all('profile_completion_form', $where);
        } else {
            $where = array('userid' => $userid);
            $data['emp_profile1'] = $Employer_model->fetch_table_data_for_all('emp_manage_admins', $where);
            $where = array('userid' => $data['emp_profile1'][0]->emp_user_id);
            $data['emp_profile'] = $Employer_model->fetch_table_data_for_all('profile_completion_form', $where);
        }


        return view('employer/emp_profile2', $data);
    }

    public function emp_profile_mobile_otp_send()
    {
        $otp = mt_rand(100000, 999999);
        // $model = new Employer_model();

        $user_id = $this->request->getVar('user_id');
        $mobile = $this->request->getVar('mobile');
        $session        = session();
        $usertype    =    $session->get('usertype');
        $Employer_model = new Employer_model();

        //check duplicate 
        if ($usertype == 2) {
            $duplicate_data = $Employer_model->duplicate_number($mobile, $user_id);
        } else {
            $duplicate_data = $Employer_model->duplicate_number_sub($mobile, $user_id);
        }
        //print_r($duplicate_data);

        if (empty($duplicate_data)) {
            //check otp count
            $otp_count = $Employer_model->otp_count_check($mobile, $usertype);

            //allow only 5 attempt
            if ($otp_count < 5) {
                $otp_count_new  = $otp_count + 1;

                $data = array(
                    'phone_number' => $mobile,
                    'otp_count'    => $otp_count_new,
                    'otp_number'   => $otp,
                    'user_type'    => $usertype,
                );

                $update_otp = $Employer_model->otp_count_save($data);
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
            $where = array('userid' => $user_id);
            if ($usertype == 2) {
                $available_data = $Employer_model->fetch_table_data_for_all('profile_completion_form', $where);
                $otp_count = ($available_data[0]->profile_otp_count + 1);

                $data = ['profile_otp' => $otp, 'profile_otp_count' => $otp_count, 'otp_status' => 1];
                $update_otp = $Employer_model->update_commen('profile_completion_form', $where, $data);
            } else {
                $available_data = $Employer_model->fetch_table_data_for_all('emp_manage_admins', $where);
                $otp_count = ($available_data[0]->profile_otp_count + 1);

                $data = ['profile_otp' => $otp, 'profile_otp_count' => $otp_count, 'otp_status' => 1];
                $update_otp = $Employer_model->update_commen('emp_manage_admins', $where, $data);
            }
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
    public function emp_profile_logo()
    {
        //print_r($_FILES['file']['name']);
        $model = new Employer_model();
        $user_id = $this->request->getVar('user_id');
        $profile_logo = $this->request->getFile('profile_logo');
        $newName = $profile_logo->getRandomName();
        $profile_logo->move('public/assets/docs/uploads/emp_profile/', $newName);

        $session = session();
        $Employer_model = new Employer_model();
        $where = array('userid' => $user_id);

        $data = ['profile_company_logo' => $newName];
        //print_r($data);
        $update_logo = $Employer_model->update_commen('profile_completion_form', $where, $data);
        if ($update_logo) {

            echo csrf_hash() . '^' . $newName;
        } else {
            echo csrf_hash() . '^' . '0';
        }
    }
    public function mobile_otp_verify()
    {
        // $model = new Employer_model();
        $user_id = $this->request->getVar('user_id');
        $user_otp = $this->request->getVar('user_otp');
        $mobile = $this->request->getVar('mobile');
        $session = session();
        $usertype    =    $session->get('usertype');
        $Employer_model = new Employer_model();
        // $where = array('userid' => $user_id, 'profile_otp' => $user_otp, 'profile_phone_no' => $mobile);
        // $available_data = $Employer_model->fetch_table_data_for_all('profile_completion_form', $where);
        if ($usertype == '2') {
            $where = array('otp_number' => $user_otp, 'user_type' => $usertype, 'phone_number' => $mobile);
        } else {
            $where = array('otp_number' => $user_otp, 'user_type' => $usertype, 'phone_number' => $mobile);
        }
        $available_data = $Employer_model->fetch_table_data('user_otp', $where);
        if ($available_data) {
            $otp_count = 0;
            if ($usertype == '2') {
                $data = ['profile_phone_no' => $mobile, 'otp_status' => 1];
                $data_user = ['mobile' => $mobile];
                $where1 = array('userid' => $user_id, 'profile_otp' => $user_otp);
                $where_user = array('userid' => $user_id);
                $update_otp = $Employer_model->update_commen('profile_completion_form', $where1, $data);
                $update_user = $Employer_model->update_commen('userlogin', $where_user, $data_user);
            } else {
                $data = ['emp_mobile' => $mobile, 'otp_status' => 1];
                $data_user = ['mobile' => $mobile];
                $where1 = array('userid' => $user_id, 'profile_otp' => $user_otp);
                $where_user = array('userid' => $user_id);
                $update_otp = $Employer_model->update_commen('emp_manage_admins', $where1, $data);
                $update_user = $Employer_model->update_commen('userlogin', $where_user, $data_user);
            }
            echo csrf_hash() . '^' . '1';
        } else {
            echo csrf_hash() . '^' . '0';
        }
    }

    public function update_employer_org_details()
    {

        $session = session();

        $Employer_model = new Employer_model();
        $validation =  \Config\Services::validation();
        $session = session();
        $usertype    =    $session->get('usertype');
        $isValidated = $this->validate([
            'profile_name' => ['label'  => 'Full name', 'rules'  => 'required',],
            'profile_mail' => ['label'  => 'Official mail id', 'rules'  => 'required|valid_email',],
            'profile_company_name' => ['label'  => 'Company Name', 'rules'  => 'required',],
            // 'profile_company_name_gst' => ['label'  => 'Company Name (As Per GST Document) ', 'rules'  => 'required',],
            'profile_mobile' => ['label'  => 'Mobile number', 'rules'  => 'required|max_length[10]',],
            // 'profile_logo_view' => ['label'  => 'Company logo ', 'rules'  => 'required',],
            // 'profile_gst' => ['label'  => ' GST number ', 'rules'  => 'required|max_length[15]',],

            'location_full_name'      => ['label'  => ' location', 'rules'  => 'required',], //validate location
        ]);


        //if not validated 
        if (!$isValidated) {
            $session->setFlashdata('error_status', '3');
            $session->setFlashdata('error_msg', $validation->getErrors());
            return redirect()->to('organization-details');
        } else {
            $user_id = $this->request->getVar('user_id');
            $where = array('userid' => $user_id);
            if ($usertype == '2') {
                if (isset($_FILES['profile_logo']['name']) && $_FILES['profile_logo']['name'] != "") {
                    $profile_logo = $this->request->getFile('profile_logo');
                    $newName = $profile_logo->getRandomName();
                    $profile_logo->move('public/assets/docs/uploads/emp_profile/', $newName);
                } else {
                    $newName = $this->request->getVar('profile_logo_view');
                }
                $location_full_name = $this->request->getVar('location_full_name');
                $loacation_data = (explode(",", $location_full_name));
                $data = [
                    'profile_name' => $this->c_trim($this->request->getVar('profile_name')),
                    'profile_official_email' => $this->c_trim($this->request->getVar('profile_mail')),
                    'profile_company_name' => $this->c_trim($this->request->getVar('profile_company_name')),
                    'profile_company_name_gst' => $this->c_trim($this->request->getVar('profile_company_name_gst')),
                    'profile_phone_no' => $this->c_trim($this->request->getVar('profile_mobile')),
                    'profile_company_logo' => $newName,
                    'profile_gst_no' => $this->c_trim($this->request->getVar('profile_gst')),
                    'location_id'    =>  $loacation_data[0],
                    'location_name'  =>  $loacation_data[1],
                    'location_district'  =>  $loacation_data[1],
                    'location_state'  =>  $loacation_data[2],
                    'gst_verification_status'  => $this->request->getVar('gst_verification_status'),

                ];
                // print_r($data);exit;
                $update_org_data = $Employer_model->update_commen('profile_completion_form', $where, $data);
                $domain = explode('@', $this->request->getVar('profile_mail'));

                $data1 = [
                    'candidate_firstname' => $this->c_trim($this->request->getVar('profile_name')),
                    'name' => $this->c_trim($this->request->getVar('profile_name')),
                    'username' => $this->c_trim($this->request->getVar('profile_name')),
                    'email' => $this->c_trim($this->request->getVar('profile_mail')),
                    'industry_name' => $this->c_trim($this->request->getVar('profile_company_name')),
                    'email_domain' => $domain[1],

                ];
                $update_org_data = $Employer_model->update_commen('userlogin', $where, $data1);
            } else {
                $data = [
                    'emp_name' => $this->c_trim($this->request->getVar('profile_name')),
                    'emp_mobile' => $this->c_trim($this->request->getVar('profile_mobile')),
                    'emp_official_email' => $this->c_trim($this->request->getVar('profile_mail'))

                ];
                // print_r($data);exit;
                $update_org_data = $Employer_model->update_commen('emp_manage_admins', $where, $data);
                $domain = explode('@', $this->request->getVar('profile_mail'));
                $data1 = [
                    'mobile' => $this->c_trim($this->request->getVar('profile_mobile')),
                    'name' => $this->c_trim($this->request->getVar('profile_name')),
                    'username' => $this->c_trim($this->request->getVar('profile_name')),
                    'email' => $this->c_trim($this->request->getVar('profile_mail')),
                    'email_domain' => $domain[1],

                ];
                $update_org_data = $Employer_model->update_commen('userlogin', $where, $data1);
                // print_r($domain);exit();
            }

            if ($update_org_data) {
                $session->setFlashdata('error_status', '2');
                $session->setFlashdata('error_msg', 'Organization details Updated successfully');
                // session()->setTempdata('success', 'Organization details Updated successfully', 2);

                return redirect()->to('other-info');
            } else {
                return redirect()->to('other-info');
            }
        }
    }

    public function get_state_by_district()
    {
        // $model = new Employer_model();
        $state_id = $this->request->getVar('state_id');

        $Employer_model = new Employer_model();
        $where = array('state_id' => $state_id, 'status' => '1');
        $order_by1 = array('ordercolumn' => 'dist_name', 'ordertype' => 'asc');
        $profile = $Employer_model->fetch_table_data_for_all('master_district', $where, $order_by1);

        // print_r($profile);exit;
        $getdates = '';
        $dates    = '';
        if (!empty($profile)) {
            foreach ($profile as $as) {
                $getdates = $getdates . "<option value='" . $as->dist_id . "' >" . $as->dist_name . "</option>";
            }
        }
        $dates = "<select name='profile_district' id='profile_district' class='js-states selectSearch form-control'>
                      <option value='' style='color:#bfbfbf;' >--Select District--</option>
                                    " . $getdates . "                           
                            </select> ";
        echo csrf_hash() . '^' . $dates;
    }
    public function update_employer_address_details()
    {
        // print_r($_POST);exit;
        $session = session();
        $Employer_model = new Employer_model();
        $validation =  \Config\Services::validation();
        $isValidated = $this->validate([
            'profile_addres1' => ['label'  => 'Address line 1', 'rules'  => 'required',],
            'profile_state' => ['label'  => 'State', 'rules'  => 'required',],
            'profile_district' => ['label'  => 'District', 'rules'  => 'required',],
            'profile_pincode' => ['label'  => 'Pincode', 'rules'  => 'required|min_length[6]',],

        ]);
        //if not validated 
        if (!$isValidated) {
            $session->setFlashdata('error_status', '3');
            $session->setFlashdata('error_msg', $validation->getErrors());
            return redirect()->to('other-info');
        } else {
            $user_id = $this->request->getVar('user_id');
            $where = array('userid' => $user_id);

            $data = [
                'profile_address1' => $this->request->getVar('profile_addres1'),
                'profile_address2' => $this->request->getVar('profile_addres2'),
                'profile_address_state' => $this->request->getVar('profile_state'),
                'profile_address_city' => $this->request->getVar('profile_district'),
                'profile_pincode' => $this->request->getVar('profile_pincode'),

            ];
            $update_org_data = $Employer_model->update_commen('profile_completion_form', $where, $data);
            if ($update_org_data) {
                $session->setFlashdata('error_status', '2');
                $session->setFlashdata('error_msg', 'Address details Updated successfully');
                // session()->setTempdata('success', 'Address details Updated successfully', 2);
                return redirect()->to('other-info');
            } else {
                return redirect()->to('other-info');
            }
        }
    }
    public function update_employer_other_details()
    {
        // print_r($_POST);exit;
        $session = session();

        $Employer_model = new Employer_model();
        $validation =  \Config\Services::validation();
        $isValidated = $this->validate([
            'abt_org' => ['label'  => 'About your organization', 'rules'  => 'required',],
        ]);
        //if not validated 
        if (!$isValidated) {
            $session->setFlashdata('error_status', '3');
            $session->setFlashdata('error_msg', $validation->getErrors());
            return redirect()->to('other-info');
        } else {
            $user_id = $this->request->getVar('user_id');
            $where = array('userid' => $user_id);

            $data = [
                'profile_company_description' => $this->c_trim($this->request->getVar('abt_org')),
                'profile_website_details' => $this->c_trim($this->request->getVar('website_url')),
                'profile_linked_in' => $this->c_trim($this->request->getVar('social_url')),
                'profile_review_url' => $this->c_trim($this->request->getVar('review_url')),

            ];
            $update_org_data = $Employer_model->update_commen('profile_completion_form', $where, $data);

            if ($update_org_data) {
                $data_com = [
                    'completed_status' => 1,
                ];
                // print_r($data_com);exit;
                $update_org_data_com = $Employer_model->update_commen('profile_completion_form', $where, $data_com);

                $session->setFlashdata('error_status', '2');
                $session->setFlashdata('error_msg', 'Profile Updated successfully');
                // session()->setTempdata('success', 'Profile Updated successfully', 2);
                $redirect    =    $session->get('redirect');
                if ($redirect != "postinternship") {

                    return redirect()->to('other-info');
                } else {

                    return redirect()->to('post-internship');
                }
            } else {
                $redirect    =    $session->get('redirect');
                if ($redirect != "postinternship") {
                    $session->setFlashdata('error_status', '2');
                    $session->setFlashdata('error_msg', 'Profile Updated successfully');
                    return redirect()->to('other-info');
                } else {

                    return redirect()->to('post-internship');
                }
            }
        }
    }

    public function emp_applied_candidate_showing($showing_result, $internship_id)
    {
        $session = session();
        $ses_data = [
            'applied_candidate_showing_limit' => $showing_result
        ];
        $session->set($ses_data);
        return redirect()->to('applied-candidates/' . $internship_id);
    }

    public function emp_applied_candidate($id)
    {

        helper(['form']);
        $session = session();
        $Employer_model = new Employer_model();

        $userid    =    $session->get('userid');
        $filter_profile_district = $session->get('profile_district');
        $filter_education_skills = $session->get('education_skills');
        $filter_education_background = $session->get('education_background');
        $filter_education_specialization = $session->get('education_specialization');
        $filter_education_college = $session->get('education_college');
        $filter_internship_lable = $session->get('internship_lable');
        $filter_application_status = $session->get('application_status');
        $applied_candidate_showing_limit = $session->get('applied_candidate_showing_limit');
        $filter_graduation_year = $session->get('graduation_year');

        $filter_gender = $session->get('gender');
        $where = array('status' => '1', 'internship_id' => $id);
        $order_by = array('ordercolumn' => 'id', 'ordertype' => 'desc');
        $data['internship_details'] = $Employer_model->fetch_table_data_for_all('employer_post_internship', $where, $order_by);

        $where_label = array('status' => '1', 'internship_id' => $id, 'employee_user_id' => $userid);
        $order_by = array('ordercolumn' => 'id', 'ordertype' => 'desc');
        $data['master_label'] = $Employer_model->fetch_table_data_for_all('employer_label', $where_label, $order_by);

        if (isset($data['internship_details']) && !empty($data['internship_details'])) {

            if (!empty($filter_application_status)) {
                if ($filter_application_status == '4') {
                    $where1 = array('can_applied_internship.status' => '1', 'can_applied_internship.internship_id' => $id, 'can_applied_internship.assignment_status' => '1', 'can_education_details.status' => '1');
                } elseif ($filter_application_status == '5') {
                    $where1 = array('can_applied_internship.status' => '1', 'can_applied_internship.internship_id' => $id, 'can_applied_internship.interview_status' => '1', 'can_education_details.status' => '1');
                } elseif ($filter_application_status == '0') {
                    // $where1 = array('can_applied_internship.status' => '1', 'can_applied_internship.internship_id' => $id, 'can_applied_internship.application_status' => '0');
                    $where1 = array('can_applied_internship.status' => '1', 'can_applied_internship.internship_id' => $id, 'can_education_details.status' => '1');
                } else {
                    $where1 = array('can_applied_internship.status' => '1', 'can_applied_internship.internship_id' => $id, 'can_applied_internship.application_status' => $filter_application_status, 'can_education_details.status' => '1');
                }
            } else {
                $where1 = array('can_applied_internship.status' => '1', 'can_applied_internship.internship_id' => $id, 'can_education_details.status' => '1');
            }

            $pager = service('pager');
            $keyword_search = $session->get('searched_keyword');
            if (isset($keyword_search)) {
                $start_id = 0;
                $limit = 1000;
                $data_applied_can = $Employer_model->fetch_candidate_data('can_applied_internship', $where1, $filter_profile_district, $filter_education_skills, $filter_education_background,$filter_education_specialization, $filter_gender, $filter_education_college, $filter_internship_lable, $filter_graduation_year, $limit, $start_id, $keyword_search);

                // $keyword_search=$keyword_search;
            } else {
                $keyword_search = '';
            }
            if (isset($data_applied_can)) {
                $limit = config('Pager')->perPage;
            } else {
                $limit = config('Pager')->perPage; // see Config/Pager.php
            }


            $page = (int) $this->request->getGet('page'); // 
            // $limit = config('Pager')->perPage; // see Config/Pager.php
            if (isset($applied_candidate_showing_limit)) {
                $limit = $applied_candidate_showing_limit;
            } else {
                $limit = config('Pager')->perPage; // see Config/Pager.php
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

            $data_applied = $Employer_model->fetch_candidate_data('can_applied_internship', $where1, $filter_profile_district, $filter_education_skills, $filter_education_background,$filter_education_specialization, $filter_gender, $filter_education_college, $filter_internship_lable, $filter_graduation_year, $keyword_search);
            // $data_applied = $Employer_model->fetch_candidate_data('can_applied_internship', $where1, $filter_profile_district, $filter_education_skills, $filter_education_background, $filter_gender);

            $data['applied_details_count'] = $data_applied;
            if (isset($data_applied_can)) {
                if (!empty($data_applied_can)) {
                    $total = count($data_applied_can);
                } else {
                    $total = 0;
                }
            } else {
                if (!empty($data_applied)) {
                    $total   = count($data_applied);
                } else {
                    $total   = 0;
                }
            }
            $pager_links = $pager->makeLinks($page, $limit, $total, 'custom_pagination');
            // print_r($offset);exit;
            $data['pager_links'] = $pager_links;
            //ci4 pagination end
            // print_r($pager_links);
            $data['applied_details'] = $Employer_model->fetch_candidate_data('can_applied_internship', $where1, $filter_profile_district, $filter_education_skills, $filter_education_background,$filter_education_specialization, $filter_gender, $filter_education_college, $filter_internship_lable, $filter_graduation_year, $limit, $start_id, $keyword_search);
            // $data['applied_details'] = $Employer_model->fetch_candidate_data('can_applied_internship', $where1, $filter_profile_district, $filter_education_skills, $filter_education_background, $filter_gender, $limit, $start_id);

            // print_r($data['applied_details']);
            // $where6 = array('status' => '1', 'internship_id' => $id, 'application_status' => 0);
            $where6 = array('status' => '1', 'internship_id' => $id);
            $data['received'] = $Employer_model->data_count_fetch('can_applied_internship', $where6);
            $where7 = array('status' => '1', 'internship_id' => $id, 'application_status' => 1);
            $data['shortlisted'] = $Employer_model->data_count_fetch('can_applied_internship', $where7);
            // print_r($data['shortlisted']);exit;
            $where8 = array('status' => '1', 'internship_id' => $id, 'application_status' => 2);
            $data['hired'] = $Employer_model->data_count_fetch('can_applied_internship', $where8);
            $where9 = array('status' => '1', 'internship_id' => $id, 'application_status' => 3);
            $data['notinterested'] = $Employer_model->data_count_fetch('can_applied_internship', $where9);
            $where10 = array('status' => '1', 'internship_id' => $id, 'assignment_status' => 1);
            $data['assignment'] = $Employer_model->data_count_fetch('can_applied_internship', $where10);
            $where11 = array('status' => '1', 'internship_id' => $id, 'interview_status' => 1);
            $data['interview'] = $Employer_model->data_count_fetch('can_applied_internship', $where11);

            // $where12 = array('sender_id' => $userid);
            // $group_by = array('ordercolumn' => 'receiver_id');
            // $data['chat_count'] = $Employer_model->fetch_table_data_group_by('chat', $where12,$group_by);
            $data['chat_count'] = $Employer_model->fetch_table_data_group_by_chat($id);

            $where_filter = array('can_applied_internship.status' => '1', 'can_applied_internship.internship_id' => $id);
            $data['applied_details_filter'] = $Employer_model->fetch_candidate_data('can_applied_internship', $where_filter);
            $folder_can_id = array();
            $folder_can_id_arr = '';
            if (isset($data['applied_details_filter']) && !empty($data['applied_details_filter'])) {
                foreach ($data['applied_details_filter'] as $can_data) {
                    $folder_can_id[] = $can_data->candidate_id;
                }
                $folder_can_id_arr = implode(',', $folder_can_id);
            }
            // print_r($folder_can_id_arr);exit();
            $data['state'] = $Employer_model->can_location_all($folder_can_id_arr);
            $data['graduation_year'] = $Employer_model->can_education_year_all($folder_can_id_arr);
            $data['skills'] = $Employer_model->can_skills_all($folder_can_id_arr);
            $data['master_college'] = $Employer_model->can_college_all($folder_can_id_arr);
            $data['master_academic_courses'] = $Employer_model->can_academin_background_all($folder_can_id_arr);
            $data['master_academic_specialization'] = $Employer_model->can_academin_specialization_all($folder_can_id_arr);
            $data['master_label_all'] = $Employer_model->can_label_all($id, $folder_can_id_arr);
            // print_r($data['chat_count']);exit;
            return view('employer/emp_applied_can', $data);
        } else {
            return view('Common/404');
        }
    }
    public function get_state_by_district_multiple()
    {
        // $model = new Employer_model();
        $state_id = $this->request->getVar('state_id');

        $Employer_model = new Employer_model();
        $where = array('state_id' => $state_id, 'status' => '1');
        $order_by1 = array('ordercolumn' => 'dist_name', 'ordertype' => 'asc');
        $profile = $Employer_model->fetch_table_data_for_all('master_district', $where, $order_by1);

        // print_r($profile);exit;
        $getdates = '';
        $dates    = '';
        if (!empty($profile)) {
            foreach ($profile as $as) {
                $getdates = $getdates . "<option value='" . $as->dist_id . "' >" . $as->dist_name . "</option>";
            }
        }
        $dates = "<select name='profile_district' id='profile_district' class='form-control f-14 border-left-0'>
                      <option value='' style='color:#bfbfbf;' >--Select District--</option>
                                    " . $getdates . "                           
                            </select> ";
        echo csrf_hash() . '^' . $dates;
    }
    public function set_employer_filters()
    {
        $session = session();
        $application_status = $this->request->getVar('application_status');
        $profile_district = $this->request->getVar('profile_district');
        $education_skills = $this->request->getVar('education_skills');
        $education_background = $this->request->getVar('education_background');
        $education_specialization = $this->request->getVar('education_specialization');
        $education_college = $this->request->getVar('education_college');
        $internship_lable = $this->request->getVar('internship_lable');
        $gender = $this->request->getVar('gender');
        $graduation_year = $this->request->getVar('graduation_year');

        // $preffered_location = $this->request->getVar('preffered_location');

        // if (!empty($preffered_location)) {
        //     $preffered_location_arr = explode(',', $preffered_location);
        //     $session->set('preffered_location', $preffered_location_arr);
        // } else {
        //     $session->set('preffered_location', '');
        // }

        if (!empty($profile_district)) {
            $profile_district_arr = explode(',', $profile_district);
            $session->set('profile_district', $profile_district_arr);
        } else {
            $session->set('profile_district', '');
        }
        if (!empty($education_skills)) {
            $education_skills_arr = explode(',', $education_skills);
            $session->set('education_skills', $education_skills_arr);
        } else {
            $session->set('education_skills', '');
        }
        if (!empty($education_background)) {
            $education_background_arr = explode(',', $education_background);
            $session->set('education_background', $education_background_arr);
        } else {
            $session->set('education_background', '');
        }
        if (!empty($education_specialization)) {
            $education_specialization_arr = explode(',', $education_specialization);
            $session->set('education_specialization', $education_specialization_arr);
        } else {
            $session->set('education_specialization', '');
        }

        if (!empty($education_college)) {
            $education_college_arr = explode(',', $education_college);
            $session->set('education_college', $education_college_arr);
        } else {
            $session->set('education_college', '');
        }
        if (!empty($internship_lable)) {
            $internship_lable_arr = explode(',', $internship_lable);
            $session->set('internship_lable', $internship_lable_arr);
        } else {
            $session->set('internship_lable', '');
        }

        if (!empty($graduation_year)) {
            $internship_year_arr = explode(',', $graduation_year);
            $session->set('graduation_year', $internship_year_arr);
        } else {
            $session->set('graduation_year', '');
        }

        if (!empty($gender)) {
            $session->set('gender', $gender);
        } else {
            $session->set('gender', '');
        }
        if (!empty($application_status)) {
            $session->set('application_status', $application_status);
        } else {
            $session->set('application_status', 0);
        }

        echo csrf_hash() . '^' . 1;
    }
    public function unset_employer_filters($id)
    {
        $session = session();

        $ses_data = [
            // 'profile_state',
            'profile_district',
            'education_skills',
            'education_background',
            'education_specialization',
            'education_college',
            'internship_lable',
            'application_status',
            'gender',
            'graduation_year',

        ];

        $session->remove($ses_data);
        return redirect()->to('applied-candidates/' . $id);
    }
    public function update_aplication_status_all()
    {
        // $model = new Employer_model();
        $candidate_id1 = $this->request->getVar('candidate_id');
        $candidate_id = explode(",", $candidate_id1);
        $interenship_id = $this->request->getVar('interenship_id');
        $application_status = $this->request->getVar('application_status');
        if ($application_status == 2) {
            $offer_viewed_status = 1;
        } else {
            $offer_viewed_status = 0;
        }
        $session = session();
        $Employer_model = new Employer_model();
        // print_r($candidate_id);exit();
        $userModel = new LoginModel();
        $current_datetime = $userModel->current_datetime();
        $data_candidate_id  = array('application_status' => $application_status, 'offer_viewed_status' => $offer_viewed_status);
        if (!empty($candidate_id)) {
            foreach ($candidate_id as $key) {
                $where_not_hire = array('candidate_id' => $key, 'internship_id' => $interenship_id, 'application_status!=' => '2');
                $can_details_not_hiring = $Employer_model->fetch_table_row('can_applied_internship', $where_not_hire);
                $update_application_status = '';
                if (isset($can_details_not_hiring) && !empty($can_details_not_hiring)) {
                    $where = array('candidate_id' => $can_details_not_hiring->candidate_id, 'internship_id' => $interenship_id);
                    $update_application_status = $Employer_model->update_commen('can_applied_internship', $where, $data_candidate_id);
                    if ($update_application_status) {
                        if ($application_status == 2) {
                            $can_details_app = $Employer_model->fetch_table_row('can_applied_internship', $where);
                            if ($can_details_app->application_type == 1) {
                                $where_status = array('status' => '1', 'userid' => $can_details_not_hiring->candidate_id);
                                $data_status = [
                                    'can_offer_status' => 1
                                ];
                                $update_status = $Employer_model->update_commen('can_personal_details', $where_status, $data_status);
                            }
                        }
                    }
                    $usertype = $session->get('usertype');
                    if ($usertype == 2) {
                        $where = array('userid' => $session->get('userid'));
                        $emp_profile = $Employer_model->fetch_table_row('profile_completion_form', $where);
                        $company_id = $session->get('userid');
                        $emp_name = $emp_profile->profile_name;
                        $emp_mobile = $emp_profile->profile_phone_no;
                        $emp_official_email = $emp_profile->profile_official_email;
                    } else {
                        $where = array('userid' => $session->get('userid'));
                        $admin_profile = $Employer_model->fetch_table_row('emp_manage_admins', $where);
                        $company_id = $admin_profile->emp_user_id;
                        $emp_name = $admin_profile->emp_name;
                        $emp_mobile = $admin_profile->emp_mobile;
                        $emp_official_email = $admin_profile->emp_official_email;
                    }
                    $data = [
                        'user_id' => $session->get('userid'),
                        'company_id' => $company_id,
                        'internship_id' => $interenship_id,
                        'candidate_id' => $can_details_not_hiring->candidate_id,
                        'process_type' => $application_status,
                        'status' => '1',
                        'created_at' => $current_datetime,
                    ];
                    $result = $Employer_model->insert_commen('emp_hiring_log', $data);
                    if ($update_application_status) {
                        if ($application_status == 2) {
                            $where_can = array('userid' => $can_details_not_hiring->candidate_id, 'status' => '1');
                            $can_details = $Employer_model->fetch_table_row('can_personal_details', $where_can);

                            $where = array('internship_id' => $interenship_id, 'status' => '1');
                            $internship_details = $Employer_model->fetch_table_row('employer_post_internship', $where);

                            if (isset($internship_details->profile) && $internship_details->profile != '0') {
                                $profile = $Employer_model->get_master_name('master_profile', $internship_details->profile, 'profile');
                            } else {
                                $profile =  $internship_details->other_profile;
                            }

                            if (isset($internship_details->internship_duration)) {
                                $duration_count = $internship_details->internship_duration;
                            }
                            if (isset($internship_details->internship_duration_type)) {
                                if ($internship_details->internship_duration_type == 1) {
                                    // echo "Week";
                                    if ($internship_details->internship_duration == 1) {
                                        $duration_type = "Week";
                                    } else {
                                        $duration_type = "Weeks";
                                    }
                                } elseif ($internship_details->internship_duration_type == 2) {
                                    // echo "Months";
                                    if ($internship_details->internship_duration == 1) {
                                        $duration_type = "Month";
                                    } else {
                                        $duration_type = "Months";
                                    }
                                }
                            }
                            $duration_of_internship = $duration_count . ' ' . $duration_type;

                            $userid    =    $session->get('userid');
                            if ($usertype == 3 || $usertype == 4) {
                                $where_sub = array('userid' => $userid, 'status' => '1');
                                $sub_admin_data = $Employer_model->fetch_table_row('emp_manage_admins', $where_sub);
                                $where_com = array('userid' => $sub_admin_data->emp_user_id, 'status' => '1');
                                $Company_data = $Employer_model->fetch_table_row('profile_completion_form', $where_com);

                                $emp_company_name    = $Company_data->profile_company_name;
                                // $industry_name='';
                            } else {
                                $emp_company_name    =    $session->get('emp_company_name');
                            }
                            $current_year = date('Y');
                            //sent email otp - Gmail
                            // $msg_data['msg_data'] = array(
                            //     'emp_company_name' => $emp_company_name,
                            //     'profile' => $profile,
                            //     'name' => $can_details->profile_full_name,
                            //     'emp_name' => $emp_name,
                            //     'emp_mobile' => $emp_mobile,
                            //     'emp_official_email' => $emp_official_email
                            // ); //dynamic contents for template
                            // $message     = view('email_template/candidate_hired', $msg_data);
                            $message = '{ "emp_company_name" : "' . $emp_company_name . '","name" : "' . $can_details->profile_full_name . '","profile" : ' . $profile . ',"emp_name" : "' . $emp_name . '","emp_mobile" : "' . $emp_mobile . '","emp_official_email" : "' . $emp_official_email . '","year" : ' . $current_year . '  }'; //dynamic contents for template
                            $subject      = 'Congrats you have been hired';
                            $to_email     =  $can_details->profile_email;
                            $from_content = 'Congrats you have been hired';
                            $template_key = '2d6f.456f260c51ab9602.k1.d9717ba0-a7ac-11ed-8222-525400fcd3f1.1863104a85a';
                            if (!empty($can_details->profile_email)) {
                                $this->email_send($message, $subject, $to_email, $from_content, $template_key);
                            }

                            // $this->email_send($message, $subject, $to_email, $from_content);

                            $emp_str_length = strlen($emp_company_name);
                            $pro_str_length = strlen($profile);

                            if ($emp_str_length > 30) {
                                $emp_company_name = mb_strimwidth($emp_company_name, 0, 28, "..");
                            }
                            if ($pro_str_length > 30) {
                                $profile = mb_strimwidth($profile, 0, 28, "..");
                            }
                            $link = base_url() . '/internship-details/' . $interenship_id;
                            // $message = rawurlencode('Congratulation ' . $can_details->profile_full_name . ', You have been hired by ' . $emp_company_name . ' for ' . $profile . ' internship.' . $link . ' - Team InternMe.');
                            $message = rawurlencode('Congratulations! You have been Hired for ' . $profile . ' at ' . $emp_company_name . ' for the duration of ' . $duration_of_internship . ' starting on ' . $internship_details->internship_startdate . ' - InternMe Team');

                            // $message = rawurlencode('Congratulation '.$can_details->profile_full_name.', You`ve been hired by '.$emp_company_name.' for '.$profile.' internship - Team InternMe.');
                            $this->sms_send($can_details->profile_phone_number, $message);
                        }
                    }
                }
            }
        }


        if ($update_application_status) {

            echo csrf_hash() . '^' . 1;
        } else {
            echo csrf_hash() . '^' . 1;
        }
    }
    public function update_aplication_status()
    {
        // $model = new Employer_model();
        $userModel = new LoginModel();
        $current_datetime = $userModel->current_datetime();
        $candidate_id = $this->request->getVar('candidate_id');
        $interenship_id = $this->request->getVar('interenship_id');
        $application_status = $this->request->getVar('application_status');
        if ($application_status == 2) {
            $offer_viewed_status = 1;
        } else {
            $offer_viewed_status = 0;
        }
        $session = session();
        $Employer_model = new Employer_model();
        $where = array('candidate_id' => $candidate_id, 'internship_id' => $interenship_id);
        $data = ['application_status' => $application_status, 'offer_viewed_status' => $offer_viewed_status];
        $update_application_status = $Employer_model->update_commen('can_applied_internship', $where, $data);
        if ($update_application_status) {
            if ($application_status == 2) {
                $can_details_app = $Employer_model->fetch_table_row('can_applied_internship', $where);
                if ($can_details_app->application_type == 1) {
                    $where_status = array('status' => '1', 'userid' => $candidate_id);
                    $data_status = [
                        'can_offer_status' => 1
                    ];
                    $update_status = $Employer_model->update_commen('can_personal_details', $where_status, $data_status);
                }
            }
        }
        $usertype = $session->get('usertype');
        if ($usertype == 2) {
            $where = array('userid' => $session->get('userid'));
            $emp_profile = $Employer_model->fetch_table_row('profile_completion_form', $where);
            $company_id = $session->get('userid');
            $emp_name = $emp_profile->profile_name;
            $emp_mobile = $emp_profile->profile_phone_no;
            $emp_official_email = $emp_profile->profile_official_email;
        } else {
            $where = array('userid' => $session->get('userid'));
            $admin_profile = $Employer_model->fetch_table_row('emp_manage_admins', $where);
            $company_id = $admin_profile->emp_user_id;
            $emp_name = $admin_profile->emp_name;
            $emp_mobile = $admin_profile->emp_mobile;
            $emp_official_email = $admin_profile->emp_official_email;
        }
        $data = [
            'user_id' => $session->get('userid'),
            'company_id' => $company_id,
            'internship_id' => $interenship_id,
            'candidate_id' => $candidate_id,
            'process_type' => $application_status,
            'status' => '1',
            'created_at' => $current_datetime,
        ];
        $result = $Employer_model->insert_commen('emp_hiring_log', $data);
        if ($application_status == 2) {
            $where_can = array('userid' => $candidate_id, 'status' => '1');
            $can_details = $Employer_model->fetch_table_row('can_personal_details', $where_can);

            $where = array('internship_id' => $interenship_id, 'status' => '1');
            $internship_details = $Employer_model->fetch_table_row('employer_post_internship', $where);

            if (isset($internship_details->profile) && $internship_details->profile != '0') {
                $profile = $Employer_model->get_master_name('master_profile', $internship_details->profile, 'profile');
            } else {
                $profile =  $internship_details->other_profile;
            }

            $userid    =    $session->get('userid');
            if ($usertype == 3 || $usertype == 4) {
                $where_sub = array('userid' => $userid, 'status' => '1');
                $sub_admin_data = $Employer_model->fetch_table_row('emp_manage_admins', $where_sub);
                $where_com = array('userid' => $sub_admin_data->emp_user_id, 'status' => '1');
                $Company_data = $Employer_model->fetch_table_row('profile_completion_form', $where_com);

                $emp_company_name    = $Company_data->profile_company_name;
                // $industry_name='';
            } else {
                $emp_company_name    =    $session->get('emp_company_name');
            }

            //sent email otp - Gmail
            // $msg_data['msg_data'] = array(
            //     'emp_company_name' => $emp_company_name,
            //     'profile' => $profile,
            //     'name' => $can_details->profile_full_name,
            //     'emp_name' => $emp_name,
            //     'emp_mobile' => $emp_mobile,
            //     'emp_official_email' => $emp_official_email
            // ); //dynamic contents for template
            // $message     = view('email_template/candidate_hired', $msg_data);

            $current_year = date('Y');
            $message = '{ "emp_company_name" : "' . $emp_company_name . '","name" : "' . $can_details->profile_full_name . '","profile" : ' . $profile . ',"emp_name" : "' . $emp_name . '","emp_mobile" : "' . $emp_mobile . '","emp_official_email" : "' . $emp_official_email . '","year" : ' . $current_year . '  }'; //dynamic contents for template
            $subject      = 'Congrats you have been hired';
            $to_email     =  $can_details->profile_email;
            $from_content = 'Congrats you have been hired';
            $template_key = '2d6f.456f260c51ab9602.k1.d9717ba0-a7ac-11ed-8222-525400fcd3f1.1863104a85a';
            if (!empty($can_details->profile_email)) {
                $this->email_send($message, $subject, $to_email, $from_content, $template_key);
            }
            // $this->email_send($message, $subject, $to_email, $from_content);

            $emp_str_length = strlen($emp_company_name);
            $pro_str_length = strlen($profile);

            if ($emp_str_length > 30) {
                $emp_company_name = mb_strimwidth($emp_company_name, 0, 28, "..");
            }
            if ($pro_str_length > 30) {
                $profile = mb_strimwidth($profile, 0, 28, "..");
            }
            if (isset($internship_details->internship_duration)) {
                $duration_count = $internship_details->internship_duration;
            }
            if (isset($internship_details->internship_duration_type)) {
                if ($internship_details->internship_duration_type == 1) {
                    // echo "Week";
                    if ($internship_details->internship_duration == 1) {
                        $duration_type = "Week";
                    } else {
                        $duration_type = "Weeks";
                    }
                } elseif ($internship_details->internship_duration_type == 2) {
                    // echo "Months";
                    if ($internship_details->internship_duration == 1) {
                        $duration_type = "Month";
                    } else {
                        $duration_type = "Months";
                    }
                }
            }
            $duration_of_internship = $duration_count . ' ' . $duration_type;

            $link = base_url() . '/internship-details/' . $interenship_id;
            // $message = rawurlencode('Congratulation ' . $can_details->profile_full_name . ', You have been hired by ' . $emp_company_name . ' for ' . $profile . ' internship.' . $link . ' - Team InternMe.');
            $message = rawurlencode('Congratulations! You have been Hired for ' . $profile . ' at ' . $emp_company_name . ' for the duration of ' . $duration_of_internship . ' starting on ' . $internship_details->internship_startdate . ' - InternMe Team');
            $this->sms_send($can_details->profile_phone_number, $message);
        }
        if ($update_application_status) {
            echo csrf_hash() . '^' . 1;
        } else {
            echo csrf_hash() . '^' . 1;
        }
    }
    public function candidate_details($candidate_id, $interenship_id)
    {
        $session = session();
        $Employer_model = new Employer_model();

        $where = array('status' => '1', 'userid' => $candidate_id);
        $data['profile_personal'] = $Employer_model->fetch_table_row('can_personal_details', $where);

        $where_int = array('status' => '1', 'internship_id' => $interenship_id);
        $data['internship_details'] = $Employer_model->fetch_table_row('employer_post_internship', $where_int);

        if (isset($data['profile_personal']) && isset($data['internship_details'])) {
            $data['education_details'] = $Employer_model->fetch_table_data('can_education_details', $where);
            $data['address_details'] = $Employer_model->fetch_table_row('can_address_details', $where);
            $data['experience_details'] = $Employer_model->fetch_table_data('can_experience_details', $where);
            $data['skill_details'] = $Employer_model->fetch_table_data('can_skills_details', $where);
            $data['work_sample'] = $Employer_model->fetch_table_row('can_work_sample', $where);

            $where_int1 = array('status' => '1', 'internship_id' => $interenship_id, 'candidate_id' => $candidate_id);
            $data['applied_details'] = $Employer_model->fetch_table_row('can_applied_internship', $where_int1);
            // print_r($interenship_id);exit;
            $data['candidate_id'] = $candidate_id;
            $data['internship_id'] = $interenship_id;
            return view('employer/candidate_details', $data);
        } else {
            return view('Common/404');
        }
    }

    public function get_gst_details()
    {
        // $model = new Employer_model();
        $profile_mail = $this->request->getVar('profile_mail');
        $profile_gst = $this->request->getVar('profile_gst');


        //$query2="33AABCU6021D1ZX"; //// gstin /////33AABCU6021D1ZX  // 33AAGCC7144L6ZE

        $url = 'https://api.mastergst.com/public/search?email=internme.app%40gmail.com&gstin=' . $profile_gst;
        $curl = curl_init("$url");


        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Accept: application/json',
            'content-type:application/json;charset=UTF-8',
            'client_id: GSP6997fb4e-7df8-4487-8d77-6c204ccc1d55',
            'client_secret:  GSP14107aaf-5b63-4c1c-8905-3548768e5223'
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $resp_array = json_decode($response);
        if (isset($resp_array->status_cd) && $resp_array->status_cd == 0) {
            echo json_encode(array('csrf' => csrf_hash(), 'gst_verify' => 0));
        } else {
            $company_name = $resp_array->data->lgnm;
            echo json_encode(array('csrf' => csrf_hash(), 'gst_verify' => $company_name));
        }
    }

    public function update_internship_status()
    {
        // $model = new Employer_model();
        $interenship_id = $this->request->getVar('interenship_id');
        $status = $this->request->getVar('status');
        $session = session();
        $Employer_model = new Employer_model();
        $where = array('internship_id' => $interenship_id);
        $data = ['active_status' => $status];
        $update_application_status = $Employer_model->update_commen('employer_post_internship', $where, $data);
        if ($update_application_status) {

            echo csrf_hash() . '^' . 1;
        } else {
            echo csrf_hash() . '^' . 0;
        }
    }
    function c_trim($var)
    {
        $var = ltrim($var);
        $var = rtrim($var);
        return $var;
    }
    public function internship_edit($id)
    {
        helper(['form']);
        $Employer_model = new Employer_model();
        $where = array('status' => '1', 'internship_id' => $id);
        $data['internship_details'] = $Employer_model->fetch_table_data_for_all('employer_post_internship', $where);
        $internship_details = $Employer_model->fetch_table_data_for_all('employer_post_internship', $where);
        if (isset($internship_details) && !empty($internship_details)) {
            $where1 = array('status' => '1', 'internship_id' => $id);
            $data['location'] = $Employer_model->fetch_table_data_for_all('emp_worklocation_multiple', $where1);
            $where2 = array('status' => '1', 'internship_id' => $id);
            $data['skills1'] = $Employer_model->fetch_table_data_for_all('emp_selected_skills_multiple', $where2);
            $where3 = array('status' => '1', 'internship_id' => $id);
            $data['education'] = $Employer_model->fetch_table_data_for_all('emp_selected_education_multiple', $where3);

            $where31 = array('status' => '1', 'internship_id' => $id);
            $data['specialization'] = $Employer_model->fetch_table_data_for_all('emp_selected_specialization_multiple', $where31);
            $where5 = array('active_status' => '1', 'status' => '1');
            // $order_by5 = array('ordercolumn' => 'name', 'ordertype' => 'asc');
            $data['master_perks'] = $Employer_model->fetch_table_data_for_all('master_perks', $where5);
            // print_r($data['education'][]->education);exit;
            $where6 = array('status' => '1', 'internship_id' => $id);
            $data['perks'] = $Employer_model->fetch_table_data_for_all('emp_selected_perks_multiple', $where6);
            $where = array('status' => '1', 'active_status' => '1', 'id!=' => $internship_details[0]->profile);
            $order_by = array('ordercolumn' => 'profile', 'ordertype' => 'asc');
            $profile = $Employer_model->fetch_table_data_for_all('master_profile', $where, $order_by);
            $where11 = array('status' => '1', 'id' => $internship_details[0]->profile);
            $order_by11 = array('ordercolumn' => 'profile', 'ordertype' => 'asc');
            $profile11 = $Employer_model->fetch_table_data_for_all('master_profile', $where11, $order_by11);

            $dataprofile = array_merge($profile11, $profile);
            $data['profile'] = $dataprofile;
            // echo"<pre>";print_r($data['profile']);exit;
            $where = array('status' => '1', 'active_status' => '0', 'id!=' => $internship_details[0]->profile);
            $order_by = array('ordercolumn' => 'profile', 'ordertype' => 'asc');
            $data['profile1'] = $Employer_model->fetch_table_data_for_all('master_profile', $where, $order_by);
            $where1 = array('status' => '1');
            $data['skills'] = $Employer_model->fetch_table_data_for_all('master_skills', $where1);
            // $where2 = array('status' => '1');
            // $order_by1 = array('ordercolumn' => 'city', 'ordertype' => 'asc');
            // $data['location'] = $Employer_model->fetch_table_data_for_all('master_city', $where2, $order_by1);
            $where4 = array('status' => '1');
            $order_by2 = array('ordercolumn' => 'priority,name', 'ordertype' => 'asc');
            $data['master_academic_courses'] = $Employer_model->fetch_table_data_for_all_priority('master_academic_courses', $where4, $order_by2);
            $where = array();
            $order_by11 = array('ordercolumn' => 'priority,dist_name', 'ordertype' => 'asc');
                $data['master_location'] = $Employer_model->fetch_table_data_for_all_priority('master_district', $where, $order_by11);

            $order_by = array('ordercolumn' => 'name', 'ordertype' => 'asc');
            $where1 = array('status' => '1');
                $data['master_specialization'] = $Employer_model->fetch_table_data_for_all('master_academic_branch', $where1, $order_by);
            return view('employer/internship_edit', $data);
        } else {
            return view('Common/404');
        }
    }
    public function update_internship()
    {
        // echo"<pre>"; print_r($_POST);exit; echo"</pre>";
        $internshipid = $this->request->getVar('internship_numer');
        $session = session();
        $Employer_model = new Employer_model();
        extract($_REQUEST);
        $userModel = new LoginModel();
        $usertype = $session->get('usertype');

        if ($this->request->getMethod() == 'post') {


            $validation =  \Config\Services::validation();
            $isValidated = $this->validate([
                'internship_profile_hidden' => ['label'  => 'profile', 'rules'  => 'required',],
                'internship_type_hidden' => ['label'  => 'Internship Type', 'rules'  => 'required',],
                // 'education_pre' => ['label'  => 'Preferred Education Qualification', 'rules'  => 'required',],
                'num_opening' => ['label'  => 'Number of openings', 'rules'  => 'required|max_length[3]',],
                'parttime_hidden' => ['label'  => ' Part time ', 'rules'  => 'required',],
                // 'internship_startdate' => ['label'  => ' Internship Start Date ', 'rules'  => 'required',],
                'internship_lastdate' => ['label'  => ' Last Date for Apply ', 'rules'  => 'required',],
                // 'internship_duration' => ['label'  => ' Internship Duration ', 'rules'  => 'required',],
                'stipend_hidden' => ['label'  => ' Stipend & Perks ', 'rules'  => 'required',],
                'responsibilities' => ['label'  => ' Intern Responsibilities ', 'rules'  => 'required',],

            ]);
            if ($this->request->getVar('internship_profile_hidden') == 0) {
                $isValidated = $this->validate([
                    'other_profile' => ['label'  => 'Other profile Name', 'rules'  => 'required',],

                ]);
            }
            if ($this->request->getVar('internship_type_hidden') == 1) {
                $isValidated = $this->validate([
                    'work_location' => ['label'  => 'Work Location', 'rules'  => 'required',],

                ]);
            }
            if ($this->request->getVar('stipend_hidden') == 2) {
                $isValidated = $this->validate([
                    'amount1' => ['label'  => 'Minimum Amount', 'rules'  => 'required|max_length[6]',],

                ]);
            }
            if ($this->request->getVar('stipend_hidden') == 3) {
                $isValidated = $this->validate([
                    'amount1' => ['label'  => 'Minimum Amount', 'rules'  => 'required|max_length[6]',],
                    'amount1' => ['label'  => 'Maximum Amount', 'rules'  => 'required|max_length[6]',],

                ]);
            }
            //if not validated 
            if (!$isValidated) {
                $session->setFlashdata('error_status', '3');
                $session->setFlashdata('error_msg', $validation->getErrors());
                return redirect()->to('internship-edit/' . $internshipid);
            } else {
                if ($usertype == '2') {
                    $sub_admin_company_id = $session->get('userid');
                } else {
                    $where_sub_admin = array('userid' => $session->get('userid'));
                    $sub_admin_profile = $Employer_model->fetch_table_row('emp_manage_admins', $where_sub_admin);
                    $sub_admin_company_id = $sub_admin_profile->emp_user_id;
                }


                if (isset($work_location) && !empty($work_location) && $this->request->getVar('internship_type_hidden') == '1') {
                    $wheredel = array('internship_id' => $internshipid);
                    $result = $Employer_model->delete_commen('emp_worklocation_multiple', $wheredel);
                    $map_id2 = $this->request->getVar('work_location');
                    //print_r($map_id2);exit();
                    // $commonarr = implode(",", $map_id2);

                    if (isset($map_id2) && !empty($map_id2)) {
                        $data_worklocation = array();
                        if (!empty($map_id2)) {
                            foreach ($map_id2 as $key => $value) {
                                $where = array('dist_name' => $value);
                                $master_location = $Employer_model->fetch_table_row('master_district', $where);
                                //print_r($key);
                                $data_worklocation[]  = array('user_id' => $sub_admin_company_id, 'internship_id' => $internshipid, 'g_location_id'  => $master_location->dist_id, 'g_location_name'  => $master_location->dist_name, 'location_district'  => $master_location->dist_name, 'location_state'  => $master_location->state_name, 'status'  => '1');
                                // $data_worklocation[]  = array('user_id' => $sub_admin_company_id, 'internship_id' => $internshipid, 'g_location_id'  => $this->request->getVar('location_id')[$key], 'g_location_name'  => $this->request->getVar('location_name')[$key], 'location_district'  => $this->request->getVar('location_district')[$key], 'location_state'  => $this->request->getVar('location_state')[$key], 'status'  => '1');
                            }
                        }

                        if (count($data_worklocation) > 0) {
                            $data_worklocation =  $Employer_model->insertBatch1('emp_worklocation_multiple', $data_worklocation);
                        }
                    }
                } else {
                    $wheredel = array('internship_id' => $internshipid);
                    $result = $Employer_model->delete_commen('emp_worklocation_multiple', $wheredel);
                }

                if (isset($selected_skills) && !empty($selected_skills)) {
                    $wheredel = array('internship_id' => $internshipid);
                    $result = $Employer_model->delete_commen('emp_selected_skills_multiple', $wheredel);
                    $selected_skills = $this->request->getVar('selected_skills');
                    // $selected_skillsarr = implode(",", $selected_skills);
                    if (isset($selected_skills) && !empty($selected_skills)) {
                        $data_selected_skills = array();
                        if (!empty($selected_skills)) {
                            foreach ($selected_skills as $key) {
                                $data_selected_skills[]  = array('user_id' => $sub_admin_company_id, 'internship_id' => $internshipid, 'selected_skills'  => $key, 'status'  => '1');
                            }
                        }

                        if (count($data_selected_skills) > 0) {
                            $data_selected_skills =  $Employer_model->insertBatch1('emp_selected_skills_multiple', $data_selected_skills);
                        }
                    }
                } else {
                    $wheredel = array('internship_id' => $internshipid);
                    $result = $Employer_model->delete_commen('emp_selected_skills_multiple', $wheredel);
                }

                if (isset($education_pre) && !empty($education_pre)) {
                    $wheredel = array('internship_id' => $internshipid);
                    $result = $Employer_model->delete_commen('emp_selected_education_multiple', $wheredel);
                    $education_pre = $this->request->getVar('education_pre');
                    // $education_prearr = implode(",", $education_pre);
                    if (isset($education_pre) && !empty($education_pre)) {
                        $data_education_pre = array();
                        if (!empty($education_pre)) {
                            foreach ($education_pre as $key) {
                                $data_education_pre[]  = array('user_id' => $sub_admin_company_id, 'internship_id' => $internshipid, 'education'  => $key, 'status'  => '1');
                            }
                        }

                        if (count($data_education_pre) > 0) {
                            $data_education_pre =  $Employer_model->insertBatch1('emp_selected_education_multiple', $data_education_pre);
                        }
                    }
                } else {
                    $education_prearr = '';
                }

                if (isset($specialization_pre)&& !empty($specialization_pre)) {
                    $wheredel = array('internship_id' => $internshipid);
                    $result = $Employer_model->delete_commen('emp_selected_specialization_multiple', $wheredel);
                    $specialization_pre = $this->request->getVar('specialization_pre');
                    // $education_prearr = implode(",", $education_pre);
                    if (isset($specialization_pre) && !empty($specialization_pre)) {
                        $data_specialization_pre = array();
                        if (!empty($specialization_pre)) {
                            foreach ($specialization_pre as $key) {
                                $data_specialization_pre[]  = array('user_id' => $sub_admin_company_id, 'internship_id' => $internshipid, 'specialization'  => $key, 'status'  => '1');
                            }
                        }

                        if (count($data_specialization_pre) > 0) {
                            $data_specialization_pre =  $Employer_model->insertBatch1('emp_selected_specialization_multiple', $data_specialization_pre);
                        }
                    }
                } else {
                    $specialization_prearr = '';
                }

                if (isset($perks) && !empty($perks)) {
                    $wheredel = array('internship_id' => $internshipid);
                    $result = $Employer_model->delete_commen('emp_selected_perks_multiple', $wheredel);
                    $perks = $this->request->getVar('perks');
                    // $education_prearr = implode(",", $education_pre);
                    if (isset($perks) && !empty($perks)) {
                        $data_perks = array();
                        if (!empty($perks)) {
                            foreach ($perks as $key) {
                                $data_perks[]  = array('user_id' => $sub_admin_company_id, 'internship_id' => $internshipid, 'perks'  => $key, 'status'  => '1');
                            }
                        }

                        if (count($data_perks) > 0) {
                            $data_perks =  $Employer_model->insertBatch1('emp_selected_perks_multiple', $data_perks);
                        }
                    }
                } else {
                    $wheredel = array('internship_id' => $internshipid);
                    $result = $Employer_model->delete_commen('emp_selected_perks_multiple', $wheredel);
                }

                if (!empty($this->request->getVar('internship_startdate'))) {
                    $start_date = $this->request->getVar('internship_startdate');
                } else {
                    $start_date = $this->request->getVar('internship_startdate1');
                }
                if (!empty($this->request->getVar('internship_duration'))) {
                    $internship_duration_type = $this->request->getVar('internship_duration');
                    $duration = $this->request->getVar('internship_duration');

                    if ($duration == 1) {
                        $duration1 = $this->request->getVar('internship_duration2');
                        $duration_days = $duration1 * 7;
                    } else {
                        $duration1 = $this->request->getVar('internship_duration1');
                        $duration_days = $duration1 * 30;
                    }
                } else {
                    $internship_duration_type = $this->request->getVar('internship_duration_edit');
                    $duration1 = $this->request->getVar('internship_duration1_edit');
                    $duration_days = $this->request->getVar('internship_durationdays_edit');

                    // if($duration==1){
                    //     $duration1=$this->request->getVar('internship_duration2');
                    //     $duration_days=$duration1*7;
                    // }else{
                    //     $duration1=$this->request->getVar('internship_duration1');
                    //     $duration_days=$duration1*30;
                    // }
                }
                $data = [
                    'profile' => $this->request->getVar('internship_profile_hidden'),
                    'other_profile' => $this->c_trim($this->request->getVar('other_profile')),
                    'internship_type' => $this->request->getVar('internship_type_hidden'),
                    // 'city' => $commonarr,
                    'number_opening' => $this->c_trim($this->request->getVar('num_opening')),
                    'partime' => $this->request->getVar('parttime_hidden'),
                    // 'prefer_worklocation' => $this->request->getVar('prefer_ca_work_location_hidden'),
                    'pre_placement_offer' => $this->request->getVar('pre_placement_offer_hidden'),
                    'prefer_gender' => $this->request->getVar('gender'),
                    'internship_startdate' => $start_date,
                    'internship_duration_type' => $internship_duration_type,
                    'internship_duration' => $duration1,
                    'stipend' => $this->request->getVar('stipend_hidden'),
                    'interns_responsibilities' => $this->c_trim($this->request->getVar('responsibilities')),
                    'about_internship' => $this->c_trim($this->request->getVar('about_internship')),
                    'amount_from' => $this->c_trim($this->request->getVar('amount1')),
                    'amount_to' => $this->c_trim($this->request->getVar('amount2')),
                    'internship_candidate_lastdate' => $this->request->getVar('internship_lastdate'),
                    'duration_days' => $duration_days,

                ];
                // print_r($data);exit;
                $where1 = array('internship_id' => $internshipid);
                $result = $Employer_model->update_commen('employer_post_internship', $where1, $data);



                if ($result) {

                    $ses_data = [
                        'show_edit' => 1,

                    ];
                    $session->set($ses_data);
                    session()->setTempdata('success', 'Internship Updated successfully', 2);
                    return redirect()->to('internship-edit/' . $internshipid);
                } else {
                    $ses_data = [
                        'show_edit' => 1,

                    ];
                    $session->set($ses_data);
                    return redirect()->to('internship-edit/' . $internshipid);
                }
            }
        }
    }
    public function emp_manage_admin()
    {
        $session = session();
        $userid    =    $session->get('userid');
        $Employer_model = new Employer_model();

        $usertype = $session->get('usertype');
        if ($usertype == 2) {
            $where = array('userid' => $userid);
            $available_data = $Employer_model->fetch_table_data_for_all('profile_completion_form', $where);
            if ($available_data[0]->profile_phone_no != ""  && $available_data[0]->location_name != "" && $available_data[0]->profile_company_description != "") {

                if ($usertype == 2) {
                    $where = array('status' => '1', 'emp_user_id' => $userid);
                } else {
                    $where_emp = array('status' => '1', 'active_status' => '1', 'userid' => $userid);

                    $admin_profile = $Employer_model->fetch_table_row('emp_manage_admins', $where_emp);
                    $where = array('status' => '1', 'active_status' => '1', 'emp_user_id' => $admin_profile->emp_user_id, 'emp_type' => '2');
                }

                $order_by = array('ordercolumn' => 'id', 'ordertype' => 'asc');
                $data['manage_admins_details'] = $Employer_model->fetch_table_data_for_all('emp_manage_admins', $where, $order_by);
                return view('employer/emp_manage_admin', $data);
            } else {
                // print_r($available_data);exit;
                if (empty($available_data[0]->profile_phone_no) || empty($available_data[0]->location_name)) {
                    $ses_data = ['redirect' => 'postinternship',];
                    $session->set($ses_data);
                    return redirect()->to('organization-details');
                } elseif (empty($available_data[0]->profile_company_description)) {
                    $ses_data = ['redirect' => 'postinternship',];
                    $session->set($ses_data);
                    return redirect()->to('other-info');
                }
            }
        } else {
            if ($usertype == 2) {
                $where = array('status' => '1', 'emp_user_id' => $userid);
            } else {
                $where_emp = array('status' => '1', 'active_status' => '1', 'userid' => $userid);

                $admin_profile = $Employer_model->fetch_table_row('emp_manage_admins', $where_emp);
                $where = array('status' => '1', 'active_status' => '1', 'emp_user_id' => $admin_profile->emp_user_id, 'emp_type' => '2');
            }

            $order_by = array('ordercolumn' => 'id', 'ordertype' => 'asc');
            $data['manage_admins_details'] = $Employer_model->fetch_table_data_for_all('emp_manage_admins', $where, $order_by);
            return view('employer/emp_manage_admin', $data);
        }
    }

    public function add_emp_manage_admins()
    {
        // print_r($_REQUEST);
        helper(['form']);
        $validation =  \Config\Services::validation();
        $session = session();
        $Employer_model = new Employer_model();
        $userModel = new LoginModel();
        $current_datetime = $userModel->current_datetime();
        $usertype_all = $session->get('usertype');
        if ($usertype_all == '3') {
            $where_adm = array('userid' => $session->get('userid'));
            $admin_profile = $Employer_model->fetch_table_row('emp_manage_admins', $where_adm);
            $emp_user_id = $admin_profile->emp_user_id;

            $where_pro = array('userid' => $emp_user_id);
            $profile_data = $Employer_model->fetch_table_row('profile_completion_form', $where_pro);
            $company_userid = $profile_data->userid;
            $add_userid  =    $session->get('userid');
        } else {
            $company_userid    =    $session->get('userid');
            $add_userid  =    $session->get('userid');
        }

        // Human Resources or Supervisor

        $isValidated = $this->validate([
            'add_employee_type_value' => ['label'  => 'Human Resource', 'rules'  => 'required'],
            'add_employee_full_name' => ['label'  => 'Full Name', 'rules'  => 'required'],
            'add_employee_official_email' => ['label'  => 'Official Email ID', 'rules'  => 'required|valid_email'],
            'add_employee_id' => ['label'  => 'Employee ID', 'rules'  => 'required'],
            // 'add_employee_moblile_number' => ['label'  => 'Moblie Number', 'rules'  => 'required|numeric|max_length[10]'],
        ]);
        if (!$isValidated) {
            $session->setFlashdata('error_status', '3');
            $session->setFlashdata('error_msg', $validation->getErrors());
            return redirect()->to('employee-list');
        } else {
            $ad_type = $this->request->getVar('add_employee_type_value');
            if ($ad_type == '1') {
                $usertype = '3';
                $rand = rand(11, 99);
                $hrs_userid = '3' . date('ymdhis') . $rand;
            } else {
                $usertype = '4';
                $rand = rand(11, 99);
                $hrs_userid = '4' . date('ymdhis') . $rand;
            }
            $data = [
                'userid' => $hrs_userid,
                'emp_user_id' => $company_userid,
                'add_user_id' => $add_userid,
                'emp_type' => $this->request->getVar('add_employee_type_value'),
                'emp_name' => $this->request->getVar('add_employee_full_name'),
                'emp_id' => $this->request->getVar('add_employee_id'),
                // 'emp_mobile' => $this->request->getVar('add_employee_moblile_number'),
                'emp_official_email' => $this->request->getVar('add_employee_official_email'),
                'created_at' => $current_datetime,

            ];
            $insert_data = $Employer_model->insert_commen('emp_manage_admins', $data);
            if (!empty($this->request->getVar('add_employee_official_email'))) {
                $domain = explode('@', $this->request->getVar('add_employee_official_email'));
                $com_domain_name = $domain[1];
            } else {
                $com_domain_name = '';
            }
            $data1 = [
                'userid' => $hrs_userid,
                'company_id' => $company_userid,
                'usertype' => $usertype,
                'name' => $this->request->getVar('add_employee_full_name'),
                'username' => $this->request->getVar('add_employee_full_name'),
                'email' => $this->request->getVar('add_employee_official_email'),
                'email_domain' => $com_domain_name,
                'created_at' => $current_datetime,

            ];
            // print_r($data1);exit;
            $insert_data1 = $Employer_model->insert_commen('userlogin', $data1);
            if ($insert_data) {
                $url = "https://internme.app/hr-register/" . $usertype . "/" . $hrs_userid;
                $where = array('userid' => $company_userid);
                $admin_profile_form = $Employer_model->fetch_table_row('profile_completion_form', $where);
                // print_r($admin_profile_form);exit;
                $company_name = $admin_profile_form->profile_company_name;
                $name = $this->request->getVar('add_employee_full_name');
                // $msg_data['msg_data'] = array('name' => $name, 'usertype' => $usertype, 'company_name' => $company_name, 'url' => $url); //dynamic contents for template
                // $message     = view('email_template/invitation_for_registration', $msg_data);
                $current_year = date('Y');
                if ($usertype == 3) {
                    $usertype_name = "Human Resource";
                } else {
                    $usertype_name = "Supervisor";
                }
                $message = '{ "name" : "' . $name . '", "usertype" : "' . $usertype_name . '","company_name" : "' . $company_name . '","url" : "' . $url . '", "year" : "' . $current_year . '" }'; //dynamic contents for template
                $subject      = 'Internme - Invitation for Registration';
                $to_email     =  $this->request->getVar('add_employee_official_email');
                $from_content = 'Internme - Invitation for Registration';
                $template_key = '2d6f.456f260c51ab9602.k1.1674b330-a845-11ed-9c3c-5254004d4100.18634ea5de3';
                $this->email_send($message, $subject, $to_email, $from_content, $template_key);

                // $this->email_send($message, $subject, $to_email, $from_content);

                $session->setFlashdata('error_msg', 'Employee Details Added Successfully <br> Verification Link Sent to Given Mail ID');
                $session->setFlashdata('error_status', '2');
                return redirect()->to('employee-list');
            } else {
                return redirect()->to('employee-list');
            }
        }
    }
    //common function for send email - Gmail
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

    //common function for send email - Zoho zeptomail
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

    public function emp_admin_single($hrs_userid)
    {
        $session = session();
        $userid    =    $session->get('userid');
        $usertype    =    $session->get('usertype');

        $Employer_model = new Employer_model();
        if ($usertype == 2) {
            $company_id = $session->get('userid');
        } else {
            $where = array('userid' => $session->get('userid'));
            $admin_profile = $Employer_model->fetch_table_row('emp_manage_admins', $where);
            $company_id = $admin_profile->emp_user_id;
        }

        $where = array('status' => '1', 'userid' => $hrs_userid);
        $data['manage_admins'] = $Employer_model->fetch_table_row('emp_manage_admins', $where);
        $manage_admins = $data['manage_admins'];
        if (isset($manage_admins)) {
            // print_r($data['manage_admins']->emp_type);exit;
            if ($data['manage_admins']->emp_type == '2') {
                $data['list_internship1'] = $Employer_model->supervisior_internship_data($hrs_userid);
            } else {
                $where1 = array('status' => '1', 'assigned_to' => $hrs_userid);
                $order_by1 = array('ordercolumn' => 'id', 'ordertype' => 'desc');
                $data['list_internship1'] = $Employer_model->fetch_table_data_for_all('employer_post_internship', $where1, $order_by1);
            }
            // print_r($data['list_internship1']);exit;


            $where2 = array('emp_user_id' => $company_id, 'emp_type' => '1', 'active_status' => '1');

            $admin_profile1 = $Employer_model->fetch_table_data_for_all('emp_manage_admins', $where2);
            $where3 = array('userid' => $company_id);
            $admin_profile2 = $Employer_model->fetch_table_data_for_all('profile_completion_form', $where3);
            if (!empty($admin_profile1)) {
                $data['admin_profile'] = array_merge($admin_profile1, $admin_profile2);
            } else {
                $data['admin_profile'] = $admin_profile2;
            }
            //    print_r($data['list_internship']);exit;
            return view('employer/emp_admin_single', $data);
        } else {
            return view('Common/404');
        }
    }

    public function edit_emp_manage_admins()
    {
        // print_r($_REQUEST);
        helper(['form']);
        $validation =  \Config\Services::validation();
        $session = session();
        $Employer_model = new Employer_model();
        $userModel = new LoginModel();
        // $current_datetime = $userModel->current_datetime();
        $userid    =    $session->get('userid');
        $edit_id = $this->request->getVar('edit_id');
        $hrs_user_id = $this->request->getVar('hrs_user_id');
        $emp_internship_id_edit = $this->request->getVar('emp_internship_id_edit');
        $isValidated = $this->validate([
            'edit_employee_type_value' => ['label'  => 'Human Resource', 'rules'  => 'required'],
            'edit_employee_full_name' => ['label'  => 'Full Name', 'rules'  => 'required'],
            'edit_employee_official_email' => ['label'  => 'Official Email ID', 'rules'  => 'required|valid_email'],
            'edit_employee_id' => ['label'  => 'Employee ID', 'rules'  => 'required'],
            // 'edit_employee_moblile_number' => ['label'  => 'Moblie Number', 'rules'  => 'required|numeric|max_length[10]'],
        ]);
        if (!$isValidated) {
            $session->setFlashdata('error_status', '3');
            $session->setFlashdata('error_msg', $validation->getErrors());
            return redirect()->to('view-employee-details/' . $hrs_user_id);
        } else {

            $data = [
                'emp_type' => $this->request->getVar('edit_employee_type_value'),
                'emp_name' => $this->request->getVar('edit_employee_full_name'),
                'emp_id' => $this->request->getVar('edit_employee_id'),
                // 'emp_mobile' => $this->request->getVar('edit_employee_moblile_number'),
                'emp_official_email' => $this->request->getVar('edit_employee_official_email'),
            ];

            $where = array('id' => $edit_id);
            $update_data = $Employer_model->update_commen('emp_manage_admins', $where, $data);

            if ($update_data) {
                $ad_type = $this->request->getVar('edit_employee_type_value');
                if ($ad_type == '1') {
                    $usertype = '3';
                    //  $hrs_userid ='3'.date('ymdhis');
                } else {
                    $usertype = '4';
                    //  $hrs_userid ='4'.date('ymdhis');
                }
                $data1 = [
                    'usertype' => $usertype,
                    'name' => $this->request->getVar('edit_employee_full_name'),
                    'username' => $this->request->getVar('edit_employee_full_name'),
                    // 'email' => $this->request->getVar('edit_employee_official_email'),

                ];

                $where1 = array('userid' => $hrs_user_id);
                $insert_data1 = $Employer_model->update_commen('userlogin', $where1, $data1);
                $session->setFlashdata('error_msg', 'Employee Details Updated Successfully');
                $session->setFlashdata('error_status', '2');
                if (isset($emp_internship_id_edit) && !empty($emp_internship_id_edit)) {
                    return redirect()->to('accepted-candidate-list-supervisior/' . $emp_internship_id_edit . '/' . $hrs_user_id);
                }
                return redirect()->to('view-employee-details/' . $hrs_user_id);
            } else {
                if (isset($emp_internship_id_edit) && !empty($emp_internship_id_edit)) {
                    return redirect()->to('accepted-candidate-list-supervisior/' . $emp_internship_id_edit . '/' . $hrs_user_id);
                }
                return redirect()->to('view-employee-details/' . $hrs_user_id);
            }
        }
    }

    public function delete_emp_subadmin_details($id, $userid, $internship_id)
    {

        $session = session();
        $Employer_model = new Employer_model();
        // $userid    =    $session->get('userid');
        $userModel = new LoginModel();
        $current_datetime = $userModel->current_datetime();
        $where = array('id' => $id);
        $data = [
            'status' => '0',
        ];
        $hrs_data = $Employer_model->fetch_table_row('emp_manage_admins', $where);
        // print_r($hrs_data);exit;
        $data1 = [
            'userid' => $hrs_data->userid,
            'emp_user_id' => $hrs_data->emp_user_id,
            'add_user_id' => $hrs_data->add_user_id,
            'emp_type' => $hrs_data->emp_type,
            'emp_name' => $hrs_data->emp_name,
            'emp_id' => $hrs_data->emp_id,
            'emp_mobile' => $hrs_data->emp_mobile,
            'emp_official_email' => $hrs_data->emp_official_email,
            'created_at' => $current_datetime,

        ];
        $insert_data = $Employer_model->insert_commen('emp_removed_hr', $data1);


        if ($insert_data) {
            $delete_data = $Employer_model->delete_commen('emp_manage_admins', $where);

            if ($delete_data) {
                $where_user = array('userid' => $userid);
                $delete_data1 = $Employer_model->delete_commen('userlogin', $where_user);
                if ($delete_data1) {
                    $session->setFlashdata('error_msg', 'Deleted Successfully');
                    $session->setFlashdata('error_status', '1');
                    if (!empty($internship_id) && $internship_id != 0) {
                        return redirect()->to('accepted-candidate-list-supervisior/' . $internship_id . '/' . $userid);
                    }
                    return redirect()->to('employee-list');
                }
            } else {
                if (!empty($internship_id) && $internship_id != 0) {
                    return redirect()->to('accepted-candidate-list-supervisior/' . $internship_id . '/' . $userid);
                }
                return redirect()->to('employee-list');
            }
        } else {
            if (!empty($internship_id) && $internship_id != 0) {
                return redirect()->to('accepted-candidate-list-supervisior/' . $internship_id . '/' . $userid);
            }
            return redirect()->to('employee-list');
        }
    }
    public function activate_emp_subadmin_details($id, $userid, $internship_id = NULL)
    {

        $session = session();
        $Employer_model = new Employer_model();
        $where = array('id' => $id);
        $data = [
            'active_status' => '1',
        ];
        $update_data = $Employer_model->update_commen('emp_manage_admins', $where, $data);
        if ($update_data) {
            $where = array('userid' => $userid);
            $data_user = [
                'status' => '1',
            ];
            $update_data1 = $Employer_model->update_commen('userlogin', $where, $data_user);
            if ($update_data1) {
                $session->setFlashdata('error_msg', 'Activated Successfully');
                $session->setFlashdata('error_status', '2');
                if (!empty($internship_id) && $internship_id != 0) {
                    return redirect()->to('accepted-candidate-list-supervisior/' . $internship_id . '/' . $userid);
                }
                return redirect()->to('employee-list');
            } else {
                if (!empty($internship_id) && $internship_id != 0) {
                    return redirect()->to('accepted-candidate-list-supervisior/' . $internship_id . '/' . $userid);
                }
                return redirect()->to('employee-list');
            }
        } else {
            if (!empty($internship_id) && $internship_id != 0) {
                return redirect()->to('accepted-candidate-list-supervisior/' . $internship_id . '/' . $userid);
            }
            return redirect()->to('employee-list');
        }
    }

    public function deactivate_emp_subadmin_details($id, $userid, $internship_id = NULL)
    {

        $session = session();
        $Employer_model = new Employer_model();
        $where = array('id' => $id);
        $data = [
            'active_status' => '0',
        ];
        $update_data = $Employer_model->update_commen('emp_manage_admins', $where, $data);
        if ($update_data) {
            $where = array('userid' => $userid);
            $data_user = [
                'status' => '0',
            ];
            $update_data1 = $Employer_model->update_commen('userlogin', $where, $data_user);
            if ($update_data1) {
                $session->setFlashdata('error_msg', 'Deactivated Successfully');
                $session->setFlashdata('error_status', '1');
                if (!empty($internship_id) && $internship_id != 0) {
                    return redirect()->to('accepted-candidate-list-supervisior/' . $internship_id . '/' . $userid);
                }
                return redirect()->to('employee-list');
            } else {
                if (!empty($internship_id) && $internship_id != 0) {
                    return redirect()->to('accepted-candidate-list-supervisior/' . $internship_id . '/' . $userid);
                }
                return redirect()->to('employee-list');
            }
        } else {
            if (!empty($internship_id) && $internship_id != 0) {
                return redirect()->to('accepted-candidate-list-supervisior/' . $internship_id . '/' . $userid);
            }
            return redirect()->to('employee-list');
        }
    }

    public function check_duplicatecheck_email()
    {
        $Employer_model = new Employer_model();
        $val = $this->request->getVar('val');
        $table = $this->request->getVar('table');
        $column = $this->request->getVar('column');
        $status_colmn = $this->request->getVar('status_colmn');

        // $val = ltrim( $val, "0"); 
        $duplicate  = $Employer_model->check_duplicatecheck($val, $column, $table, $status_colmn);

        if ($duplicate) {
            echo csrf_hash() . '^' . '1';
        } else {
            echo csrf_hash() . '^' . '0';
        }
    }
    public function check_duplicatecheck_emp_id()
    {
        $Employer_model = new Employer_model();
        $val = $this->request->getVar('val');
        $table = $this->request->getVar('table');
        $column = $this->request->getVar('column');
        // $status_colmn = $this->request->getVar('status_colmn');
        $session = session();
        $userid    =    $session->get('userid');
        // $val = ltrim( $val, "0"); 
        $duplicate  = $Employer_model->check_duplicatecheck1($val, $column, $table, $userid);

        if ($duplicate) {
            echo csrf_hash() . '^' . '1';
        } else {
            echo csrf_hash() . '^' . '0';
        }
    }

    public function check_duplicatecheck_email_edit()
    {
        $Employer_model = new Employer_model();
        $val = $this->request->getVar('val');
        $table = $this->request->getVar('table');
        $column = $this->request->getVar('column');
        $notval = $this->request->getVar('notval');
        $noval_colm = $this->request->getVar('notval_colm');
        // $status_colmn = $this->input->post('status_colmn');

        // $val = ltrim( $val, "0"); 
        $duplicate  = $Employer_model->edit_check_duplicatecheck($notval, $val, $column, $table, $noval_colm);

        if ($duplicate) {
            echo csrf_hash() . '^' . '1';
        } else {
            echo csrf_hash() . '^' . '0';
        }
    }
    public function check_duplicatecheck_emp_id_edit()
    {
        $Employer_model = new Employer_model();
        $val = $this->request->getVar('val');
        $table = $this->request->getVar('table');
        $column = $this->request->getVar('column');
        $notval = $this->request->getVar('notval');
        $noval_colm = $this->request->getVar('notval_colm');
        $session = session();
        $userid    =    $session->get('userid');
        // $val = ltrim( $val, "0"); 
        $duplicate  = $Employer_model->edit_check_duplicatecheck1($notval, $val, $column, $table, $noval_colm, $userid);

        if ($duplicate) {
            echo csrf_hash() . '^' . '1';
        } else {
            echo csrf_hash() . '^' . '0';
        }
    }
    //gst duplicate check
    public function gst_duplicate_check()
    {
        $session        = session();
        $userid         = $session->get('userid');
        $gst_number     = $this->request->getVar('gst_number');
        $Employer_model = new Employer_model();
        $where          = array('profile_gst_no' => $gst_number, 'userid !=' => $userid);
        $duplicate = $Employer_model->gst_duplicate_check($where);
        //print_r($duplicate);
        if (!empty($duplicate)) {
            echo csrf_hash() . '^' . 1;
        } else {
            echo csrf_hash() . '^' . 0;
        }
    }
    public function get_can_mobile_email_edit_emp()
    {
        $session        = session();
        $usertype    =    $session->get('usertype');
        // $model = new Employer_model();
        $userid = $this->request->getVar('userid');

        $Employer_model = new Employer_model();
        $where = array('userid' => $userid, 'status' => '1');
        if ($usertype == 2) {
            $profile = $Employer_model->fetch_table_row('profile_completion_form', $where);
            echo $profile->profile_phone_no . '^' . $profile->profile_official_email . '^' . csrf_hash();
        } else {
            $profile = $Employer_model->fetch_table_row('emp_manage_admins', $where);
            echo $profile->emp_mobile . '^' . $profile->emp_official_email . '^' . csrf_hash();
        }
    }

    public function emp_profile_email_otp()
    {
        $otp = mt_rand(100000, 999999);
        // $model = new Employer_model();
        $user_id = $this->request->getVar('user_id');
        $email = $this->request->getVar('email');
        $session        = session();
        $usertype    =    $session->get('usertype');
        $Employer_model = new Employer_model();

        //check duplicate 

        if ($usertype == 2) {
            $duplicate_data = $Employer_model->duplicate_email($email, $user_id);
        } else {
            $duplicate_data = $Employer_model->duplicate_email_sub($email, $user_id);
        }
        // print_r($duplicate_data);exit();

        if (empty($duplicate_data)) {
            //check otp count
            $otp_count = $Employer_model->otp_count_check_email($email, $usertype);

            //allow only 5 attempt
            if ($otp_count < 5) {

                $otp_count_new  = $otp_count + 1;

                $data1 = array(
                    'email_id'     => $email,
                    'otp_count'    => $otp_count_new,
                    'otp_number'   => $otp,
                    'user_type'    => $usertype,
                );

                $update_otp = $Employer_model->otp_count_save_email($data1, $usertype);

                $to_email = $email;
                //sent email otp
                // $message = 'Hi,<br>
                //                             To Authenticate your new email id, please use the following One Time Password: ' . $otp . '.<br><br>
                //                             <i>NOTE: This email is automatically generated by Internme, please do not reply to this message.</i>';

                // $subject = 'Internme';

                // $email = \Config\Services::email();
                // $email->setTo($to_email);
                // $email->setFrom('internme.app@gmail.com', 'NoReply', 'Internme - OTP Verification');
                // $email->setSubject($subject);
                // $email->setMessage($message);
                // $email->send();
                $where = array('userid' => $user_id);
                if ($usertype == 2) {
                    $available_data = $Employer_model->fetch_table_row('profile_completion_form', $where);
                    $otp_count = $available_data->profile_otp_count + 1;

                    $data2 = array('profile_otp' => $otp, 'profile_otp_count' => $otp_count, 'otp_status' => 1);
                    $update_otp = $Employer_model->update_commen('profile_completion_form', $where, $data2);
                    $employer_name = $available_data->profile_name;
                    //  print_r($update_otp);
                } else {
                    $available_data = $Employer_model->fetch_table_row('emp_manage_admins', $where);
                    $otp_count = $available_data->profile_otp_count + 1;
                    // print_r($otp_count);
                    $data2 = array('profile_otp' => $otp, 'profile_otp_count' => $otp_count, 'otp_status' => 1);
                    $update_otp = $Employer_model->update_commen('emp_manage_admins', $where, $data2);
                    $employer_name = $available_data->emp_name;
                }
                $current_year = date('Y');
                // $msg_data['msg_data'] = array('otp' => $otp, 'name' => $employer_name, 'email_status' => 2); //dynamic contents for template
                // $message     = view('email_template/verification_of_email', $msg_data);
                $message = '{ "otp" : "' . $otp . '", "name" : "' . $employer_name . '", "title" : "OTP Verification" ,"year" : ' . $current_year . '}'; //dynamic contents for template
                $subject  = 'Internme - OTP Verification';
                $to_email  = $email;
                $from_content = 'Internme - OTP Verification';
                $template_key = '2d6f.456f260c51ab9602.k1.31740a80-a789-11ed-bfa0-525400fcd3f1.186301afb28';
                $this->email_send($message, $subject, $to_email, $from_content, $template_key);
                // $this->email_send($message, $subject, $to_mail, $from_sub);


                $msg = 'We have sent an OTP to your registered Email ID';
                $otp_status = 0;


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
            echo '2' . '^' . csrf_hash() . '^' . $msg . '^' . $otp_status;
        }
    }

    public function email_otp_verify_edit()
    {
        // $model = new Employer_model();
        $user_id = $this->request->getVar('user_id');
        $user_otp = $this->request->getVar('user_otp');
        $email = $this->request->getVar('email');
        $session = session();
        $usertype    =    $session->get('usertype');
        $Employer_model = new Employer_model();
        // $where = array('userid' => $user_id, 'profile_otp' => $user_otp, 'profile_phone_no' => $mobile);
        // $available_data = $Employer_model->fetch_table_data_for_all('profile_completion_form', $where);
        $where = array('otp_number' => $user_otp, 'user_type' => $usertype, 'email_id' => $email);
        // print_r($where);
        $available_data = $Employer_model->fetch_table_data('user_otp', $where);
        if ($available_data) {
            $otp_count = 0;
            if ($usertype == '2') {
                $data = ['profile_official_email' => $email, 'official_email_verify_status' => 1];
                $domain = explode('@', $email);
                $data_user = ['email' => $email, 'email_domain' => $domain[1]];
                $where1 = array('userid' => $user_id);
                $update_profile = $Employer_model->update_commen('profile_completion_form', $where1, $data);
                $update_user = $Employer_model->update_commen('userlogin', $where1, $data_user);
            } else {
                $data = ['emp_official_email' => $email, 'otp_status' => 1];
                $domain = explode('@', $email);
                $data_user = ['email' => $email, 'email_domain' => $domain[1]];
                $where1 = array('userid' => $user_id);
                $update_profile = $Employer_model->update_commen('emp_manage_admins', $where1, $data);
                $update_user = $Employer_model->update_commen('userlogin', $where1, $data_user);
            }
            echo csrf_hash() . '^' . '1';
        } else {
            echo csrf_hash() . '^' . '0';
        }
    }
    public function assign_hr()
    {
        // $model = new Employer_model();
        $selected_hr = $this->request->getVar('selected_hr');
        $id = $this->request->getVar('id');

        $session = session();
        $Employer_model = new Employer_model();
        $where = array('id' => $id);
        $data = ['assigned_to' => $selected_hr];
        $update_application_status = $Employer_model->update_commen('employer_post_internship', $where, $data);
        if ($update_application_status) {

            $session->setFlashdata('error_msg', 'HR Assigned Successfully');
            $session->setFlashdata('error_status', '2');
?> <script>
                window.history.back();
            </script> <?php
                    } else {
                        $session->setFlashdata('error_msg', 'HR Assigned Successfully');
                        $session->setFlashdata('error_status', '2');
                        ?> <script>
                window.history.back();
            </script> <?php
                    }
                }

                public function emp_hired_interns_list($id)
                {
                    $session = session();
                    $Employer_model = new Employer_model();
                    $where = array('status' => '1', 'internship_id' => $id);
                    $data['internship_details'] = $Employer_model->fetch_table_data_for_all('employer_post_internship', $where);
                    if (isset($data['internship_details']) && !empty($data['internship_details'])) {
                        $userid    =    $session->get('userid');
                        $usertype    =    $session->get('usertype');
                        if ($usertype == 4) {
                            $where1 = array('internship_id' => $id, 'hiring_status' => '1', 'emp_supervisor' => $userid);
                        } else {
                            $where1 = array('internship_id' => $id, 'hiring_status' => '1');
                        }

                        $data['interns_details'] = $Employer_model->fetch_table_data_for_all('can_applied_internship', $where1);

                        $where2 = array('emp_user_id' => $data['internship_details'][0]->company_id, 'emp_type' => '2', 'status' => '1', 'active_status' => '1');

                        $data['admin_profile'] = $Employer_model->fetch_table_data_for_all('emp_manage_admins', $where2);

                        $where = array('status' => '1', 'company_id' => $data['internship_details'][0]->company_id);
                        $order_by = array('ordercolumn' => 'id', 'ordertype' => 'desc');
                        $data['certificate_details'] = $Employer_model->fetch_table_data_for_all('emp_certificate_details', $where, $order_by);

                        $where3 = array('status' => '1', 'userid' => $data['internship_details'][0]->company_id);
                        $data['company_details'] = $Employer_model->fetch_table_row('profile_completion_form', $where3);
                        // print_r($data['admin_profile']);exit;
                        return view('employer/emp_hired_interns', $data);
                    } else {
                        return view('Common/404');
                    }
                }

                public function emp_hired_interns_list_supervisior($id, $userid)
                {
                    $session = session();
                    $Employer_model = new Employer_model();
                    $where = array('status' => '1', 'internship_id' => $id);
                    $data['internship_details'] = $Employer_model->fetch_table_data_for_all('employer_post_internship', $where);

                    $where1 = array('internship_id' => $id, 'hiring_status' => '1', 'emp_supervisor' => $userid);

                    $data['interns_details'] = $Employer_model->fetch_table_data_for_all('can_applied_internship', $where1);

                    $where_app = array('internship_id' => $id, 'emp_supervisor' => $userid);
                    $interns_details = $Employer_model->fetch_table_data_for_all('can_applied_internship', $where_app);
                    $internship_details = $data['internship_details'];
                    if (isset($internship_details) && !empty($internship_details) && isset($interns_details) && !empty($interns_details)) {
                        $where2 = array('userid' => $userid, 'status' => '1');

                        $data['admin_profile'] = $Employer_model->fetch_table_data_for_all('emp_manage_admins', $where2);
                        $where = array('status' => '1', 'company_id' => $data['internship_details'][0]->company_id);
                        $order_by = array('ordercolumn' => 'id', 'ordertype' => 'desc');
                        $data['certificate_details'] = $Employer_model->fetch_table_data_for_all('emp_certificate_details', $where, $order_by);

                        $where2 = array('emp_user_id' => $data['internship_details'][0]->company_id, 'status' => '1', 'active_status' => '1', 'emp_type' => 2);
                        $data['admin_assign'] = $Employer_model->fetch_table_data_for_all('emp_manage_admins', $where2);
                        // print_r($data['admin_assign']);
                        $where3 = array('status' => '1', 'userid' => $data['internship_details'][0]->company_id);
                        $data['company_details'] = $Employer_model->fetch_table_row('profile_completion_form', $where3);
                        $data['list_internship1'] = $Employer_model->supervisior_internship_data($userid);
                        // print_r($data['admin_profile']);exit;
                        return view('employer/emp_hired_interns_supervisior', $data);
                    } else {
                        return view('Common/404');
                    }
                }


                public function assign_supervisor()
                {
                    // $model = new Employer_model();
                    $Employer_model = new Employer_model();
                    $internship_id = $this->request->getVar('internship_id');
                    $selected_supervisor = $this->request->getVar('selected_supervisor');
                    $session = session();
                    $candidate_id1 = $this->request->getVar('cann_id');
                    // $candidate_id = explode(",", $candidate_id1);

                    $data  = array('emp_supervisor' => $selected_supervisor);
                    if (!empty($candidate_id1)) {
                        foreach ($candidate_id1 as $key) {

                            $where = array('candidate_id' => $key, 'internship_id' => $internship_id);
                            $update_application_status = $Employer_model->update_commen('can_applied_internship', $where, $data);
                        }
                    }


                    $session->setFlashdata('error_msg', 'Supervisor Assigned Successfully');
                    $session->setFlashdata('error_status', '2');
                    return redirect()->to('accepted-candidate-list/' . $internship_id);
                }

                public function emp_internship_multi_list($type = NULL)
                {
                    $session = session();
                    $userid    =    $session->get('userid');
                    $usertype    =    $session->get('usertype');

                    $Employer_model = new Employer_model();
                    // $where = array('emp_supervisor' => $userid);
                    // $order_by = array('ordercolumn' => 'id', 'ordertype' => 'desc');
                    // $group_by = array('ordercolumn' => 'internship_id');
                    // $data['list_internship'] = $Employer_model->fetch_table_data_group_by('can_applied_internship', $where, $group_by, $order_by);
                    if ($usertype == 2) {
                        $company_id = $session->get('userid');
                    } else {
                        $where = array('userid' => $session->get('userid'));
                        $admin_profile = $Employer_model->fetch_table_row('emp_manage_admins', $where);
                        $company_id = $admin_profile->emp_user_id;
                    }
                    $where = array('status' => '1', 'company_id' => $company_id);
                    $order_by = array('ordercolumn' => 'id', 'ordertype' => 'desc');
                    $data['list_internship'] = $Employer_model->fetch_table_data_for_all('employer_post_internship', $where, $order_by);

                    if (isset($data['list_internship']) && !empty($data['post_internship'])) {
                        $data['post_internship'] = count($data['list_internship']);
                    } else {
                        $data['post_internship'] = 0;
                    }

                    $today_date = date('Y-m-d');
                    $where_open = '(date(internship_candidate_lastdate) >= "' . $today_date . '" AND active_status = 1) AND status = "1" AND  company_id = "' . $company_id . '"';
                    $order_by = array('ordercolumn' => 'internship_candidate_lastdate', 'ordertype' => 'desc');
                    // $where_open = array('active_status' =>'1','internship_candidate_lastdate >=' => date('Y-m-d'),'status' => '1', 'company_id' => $company_id);
                    $list_internship_open = $Employer_model->fetch_table_data_for_all('employer_post_internship', $where_open, $order_by);
                    //   print_r($list_internship_open);exit;
                    if (isset($list_internship_open) && !empty($list_internship_open)) {
                        $data['list_internship_open'] = $list_internship_open;
                    } else {
                        $data['list_internship_open'] = array();
                    }


                    $where_closed = '(date(internship_candidate_lastdate) < "' . $today_date . '" OR active_status = 0) AND status = "1" AND  company_id = "' . $company_id . '"';
                    $order_by = array('ordercolumn' => 'internship_candidate_lastdate', 'ordertype' => 'desc');
                    // $where_closed = array('active_status' =>'1','internship_candidate_lastdate <' => date('Y-m-d'),'status' => '1', 'company_id' => $company_id);
                    $list_internship_closed = $Employer_model->fetch_table_data_for_all('employer_post_internship', $where_closed, $order_by);
                    // print_r($list_internship_closed);exit;
                    if (isset($list_internship_closed) && !empty($list_internship_closed)) {
                        $data['list_internship_closed'] = $list_internship_closed;
                    } else {
                        $data['list_internship_closed'] = array();
                    }

                    $data['list_internship_asc'] = array_merge($data['list_internship_open'], $data['list_internship_closed']);
                    if (isset($type) && !empty($type)) {

                        if ($type == '2') {
                            $ses_data = [
                                'active_session' => 2,

                            ];
                            $session->set($ses_data);
                            $data['list_internship_asc'] = $Employer_model->fetch_table_data_for_all('employer_post_internship', $where_open, $order_by);

                            // if(!empty($data['list_internship_asc'])){
                            //     $data['list_internship_count_all_open'] = count($data['list_internship_asc']);
                            //       }else{
                            //         $data['list_internship_count_all_open'] =0;
                            //       }
                        } elseif ($type == '3') {
                            $ses_data = [
                                'active_session' => 3,

                            ];
                            $session->set($ses_data);
                            $data['list_internship_asc'] = $Employer_model->fetch_table_data_for_all('employer_post_internship', $where_closed, $order_by);
                        } else {
                            $ses_data = [
                                'active_session' => 1,

                            ];
                            $session->set($ses_data);
                            $data['list_internship_asc'] = array_merge($data['list_internship_open'], $data['list_internship_closed']);
                        }
                    } else {
                        $ses_data = [
                            'active_session' => 0,

                        ];
                        $session->set($ses_data);

                        $data['list_internship_asc'] = array_merge($data['list_internship_open'], $data['list_internship_closed']);
                    }

                    // print_r($data['list_internship_asc']);exit();

                    return view('employer/emp_internship_multi_list', $data);
                }

                public function description_geting()
                {
                    $url = $this->request->getVar('website');
                    $tags = get_meta_tags($url);
                    $data = @($tags['description'] ? $tags['description'] : 0);
                    echo $data . '^' . csrf_hash();
                }


                public function emp_search_candidate_showing($showing_result, $folder = NULL)
                {
                    $session = session();
                    // $showing_result = $this->request->getVar('showing_result');
                    $ses_data = [
                        'search_candidate_showing_limit' => $showing_result,
                    ];
                    $session->set($ses_data);
                    if (isset($folder) && $folder != '0') {
                        return redirect()->to('search-candidates/' . $folder);
                    } else {
                        return redirect()->to('search-candidates');
                    }
                }

                public function emp_search_candidate($folder = NULL)
                {


                    helper(['form']);
                    $session = session();
                    $Employer_model = new Employer_model();
                    // $Employer_model = new Employer_model();
                    $userid    =    $session->get('userid');
                    $usertype    =    $session->get('usertype');
                    $search_candidate_showing_limit = $session->get('search_candidate_showing_limit');
                    if ($usertype == 2) {

                        $where = array('userid' => $userid);

                        $available_data = $Employer_model->fetch_table_data_for_all('profile_completion_form', $where);
                        if ($available_data[0]->profile_phone_no != ""  && $available_data[0]->location_name != "" && $available_data[0]->profile_company_description != "") {
                            $filter_profile_district = $session->get('profile_district1');
                            $filter_education_skills = $session->get('education_skills1');
                            $filter_education_background = $session->get('education_background1');
                            $filter_education_specialization = $session->get('education_specialization1');
                            $filter_education_college = $session->get('education_college1');
                            $filter_gender = $session->get('gender1');
                            $filter_preffered_location = $session->get('preffered_location1');
                            $filter_graduation_year = $session->get('graduation_year1');


                            // print_r($data['master_academic_courses']);exit();
                            $userid    =    $session->get('userid');
                            $usertype    =    $session->get('usertype');
                            if ($usertype == 2) {
                                $company_id = $session->get('userid');
                            } else {
                                $where = array('userid' => $session->get('userid'));
                                $admin_profile = $Employer_model->fetch_table_row('emp_manage_admins', $where);
                                $company_id = $admin_profile->emp_user_id;
                            }
                            $where1 = array('status' => '1', 'active_status' => '1', 'company_id' => $company_id, 'internship_candidate_lastdate >=' => date('Y-m-d'));
                            $order_by1 = array('ordercolumn' => 'id', 'ordertype' => 'desc');
                            $data['list_internship'] = $Employer_model->fetch_table_data_for_all('employer_post_internship', $where1, $order_by1);

                            if (empty($folder)) {

                                // $where1 = array('status' => '1');
                                // $order_by = array('ordercolumn' => 'name', 'ordertype' => 'asc');
                                $data['state'] = $Employer_model->can_location_all();
                                $data['graduation_year'] = $Employer_model->can_education_year_all();
                                $data['preffered_location'] = $Employer_model->can_preffered_location_all();
                                // $where1 = array('status' => '1');
                                // $order_by1 = array('ordercolumn' => 'skill_name', 'ordertype' => 'asc');
                                // $data['skills'] = $Employer_model->fetch_table_data_for_all('master_skills', $where1, $order_by1);
                                $data['skills'] = $Employer_model->can_skills_all();
                                // $where_coll = array('status' => '1');
                                // $order_by_coll = array('ordercolumn' => 'college_name', 'ordertype' => 'asc');
                                // $data['master_college'] = $Employer_model->fetch_table_data_for_all('master_college', $where_coll, $order_by_coll);
                                $data['master_college'] = $Employer_model->can_college_all();
                                // $where4 = array('status' => '1');
                                // $order_by2 = array('ordercolumn' => 'name', 'ordertype' => 'asc');
                                // $data['master_academic_courses'] = $Employer_model->fetch_table_data_for_all('master_academic_courses', $where4, $order_by2);
                                $data['master_academic_courses'] = $Employer_model->can_academin_background_all();
                                $data['master_academic_specialization'] = $Employer_model->can_academin_specialization_all();
                                $data['master_gender'] = $Employer_model->can_gender_all();
                                // $where1 = array('can_personal_details.status' => '1', 'can_personal_details.g_location_name!=' => '', 'can_education_details.userid!=' => '');
                                $where1 = array('can_personal_details.status' => '1', 'can_education_details.status' => '1', 'can_personal_details.can_profile_complete_status' => '1', 'can_education_details.userid!=' => '');
                                // $data['all_candidate_count'] = $Employer_model->fetch_candidate_data_all('can_personal_details', $where1);
                                $pager = service('pager');
                                $keyword_search = $session->get('searched_keyword_candidates');
                                if (isset($keyword_search)) {
                                    $start_id = 0;
                                    $limit = 1000;
                                    $all_candidate_search = $Employer_model->fetch_candidate_data_all('can_personal_details', $where1, $filter_profile_district, $filter_education_skills, $filter_education_background,$filter_education_specialization, $filter_gender, $filter_education_college, $filter_preffered_location, $filter_graduation_year, $limit, $start_id, $keyword_search);
                                    // $keyword_search=$keyword_search;
                                } else {
                                    $keyword_search = '';
                                }
                                if (isset($all_candidate_search)) {
                                    $limit = config('Pager')->perPage;
                                } else {
                                    $limit = config('Pager')->perPage; // see Config/Pager.php
                                }

                                $page = (int) $this->request->getGet('page'); // 
                                if (isset($search_candidate_showing_limit)) {
                                    $limit = $search_candidate_showing_limit;
                                } else {
                                    $limit = config('Pager')->perPage;
                                }
                                // see Config/Pager.php

                                if (!isset($page) || $page === 0 || $page === 1) {
                                    $page = 1;
                                    $start_id = 0;
                                } else {
                                    $start_id = ($page - 1) * $limit;
                                    $page = $page;
                                }


                                $data['page_start_id'] = $start_id;
                                $data['page_default_limit'] = $limit;

                                $all_candidate = $Employer_model->fetch_candidate_data_all('can_personal_details', $where1, $filter_profile_district, $filter_education_skills, $filter_education_background,$filter_education_specialization, $filter_gender, $filter_education_college, $filter_preffered_location, $filter_graduation_year, $keyword_search);
                                $data['all_candidate_count'] = $all_candidate;
                                if (isset($all_candidate_search)) {
                                    if (!empty($all_candidate_search)) {
                                        $total = count($all_candidate_search);
                                    } else {
                                        $total = 0;
                                    }
                                } else {
                                    if (!empty($all_candidate)) {
                                        $total   = count($all_candidate);
                                    } else {
                                        $total   = 0;
                                    }
                                }
                                $pager_links = $pager->makeLinks($page, $limit, $total, 'custom_pagination');
                                // print_r($offset);exit;
                                $data['pager_links'] = $pager_links;
                                //ci4 pagination end
                                // print_r($pager_links);
                                $data['all_candidate'] = $Employer_model->fetch_candidate_data_all('can_personal_details', $where1, $filter_profile_district, $filter_education_skills, $filter_education_background,$filter_education_specialization, $filter_gender, $filter_education_college, $filter_preffered_location, $filter_graduation_year, $limit, $start_id, $keyword_search);
                                // print_r($data['applied_details']);
                                $data['folder_id_new'] = '';
                                $where21 = array('employer_id' => $company_id);
                                $order_by1 = array('ordercolumn' => 'id', 'ordertype' => 'desc');
                                $data['folder'] = $Employer_model->fetch_table_data_for_all('employer_folder', $where21, $order_by1);
                                $where211 = array('user_id' => $userid);
                                $order_by1 = array('ordercolumn' => 'id', 'ordertype' => 'desc');
                                $data['search_save'] = $Employer_model->fetch_table_data_for_all('emp_save_search', $where211, $order_by1);
                                $where = array();
                                $order_by11 = array('ordercolumn' => 'dist_name', 'ordertype' => 'asc');
                                $data['master_location'] = $Employer_model->fetch_table_data_for_all('master_district', $where, $order_by11);
                                //echo "asf"; exit();
                                return view('employer/emp_search_candidate', $data);
                            } else {

                                $where1 = array('employer_folder_data.folder_id' => $folder, 'can_education_details.status' => '1', 'employer_folder_data.trash_flag' => '0');
                                // $data['all_candidate_count'] = $Employer_model->fetch_candidate_data_all_folder('employer_folder_data', $where1);
                                $pager = service('pager');

                                $keyword_search = $session->get('searched_keyword_folder');
                                if (isset($keyword_search)) {
                                    $start_id = 0;
                                    $limit = 1000;
                                    $all_candidate_folder = $Employer_model->fetch_candidate_data_all_folder('employer_folder_data', $where1, $filter_profile_district, $filter_education_skills, $filter_education_background,$filter_education_specialization, $filter_gender, $filter_education_college, $filter_preffered_location, $filter_graduation_year, $limit, $start_id, $keyword_search);

                                    // $keyword_search=$keyword_search;
                                } else {
                                    $keyword_search = '';
                                }
                                if (isset($all_candidate_folder)) {
                                    $limit = config('Pager')->perPage;
                                } else {
                                    $limit = config('Pager')->perPage; // see Config/Pager.php
                                }


                                $page = (int) $this->request->getGet('page'); // 
                                if (isset($search_candidate_showing_limit)) {
                                    $limit = $search_candidate_showing_limit;
                                } else {
                                    $limit = config('Pager')->perPage; // see Config/Pager.php
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

                                $all_candidate = $Employer_model->fetch_candidate_data_all_folder('employer_folder_data', $where1, $filter_profile_district, $filter_education_skills, $filter_education_background,$filter_education_specialization, $filter_gender, $filter_education_college, $filter_preffered_location, $filter_graduation_year, $keyword_search);
                                $data['all_candidate_count'] = $all_candidate;
                                if (isset($all_candidate_folder)) {
                                    if (!empty($all_candidate_folder)) {
                                        $total = count($all_candidate_folder);
                                    } else {
                                        $total = 0;
                                    }
                                } else {
                                    if (!empty($all_candidate)) {
                                        $total   = count($all_candidate);
                                    } else {
                                        $total   = 0;
                                    }
                                }
                                $pager_links = $pager->makeLinks($page, $limit, $total, 'custom_pagination');
                                // print_r($offset);exit;
                                $data['pager_links'] = $pager_links;
                                //ci4 pagination end
                                // print_r($pager_links);
                                $data['all_candidate'] = $Employer_model->fetch_candidate_data_all_folder('employer_folder_data', $where1, $filter_profile_district, $filter_education_skills, $filter_education_background,$filter_education_specialization, $filter_gender, $filter_education_college, $filter_preffered_location, $filter_graduation_year, $limit, $start_id, $keyword_search);
                                $data['folder_id_new'] = $folder;

                                $folder_can_id = array();
                                $folder_can_id_arr = '';
                                if (isset($all_candidate) && !empty($all_candidate)) {
                                    foreach ($all_candidate as $can_data) {
                                        $folder_can_id[] = $can_data->candidate_id;
                                    }
                                    $folder_can_id_arr = implode(',', $folder_can_id);
                                }


                                $all_candidate2 = $Employer_model->fetch_candidate_data_all_folder2('employer_folder_data', $where1, $filter_profile_district, $filter_education_skills, $filter_education_background,$filter_education_specialization, $filter_gender, $filter_education_college, $filter_preffered_location, $filter_graduation_year, $keyword_search);

                                $folder_can_id2 = array();
                                $folder_can_id_arr2 = '';
                                if (isset($all_candidate2) && !empty($all_candidate2)) {
                                    foreach ($all_candidate2 as $can_data2) {
                                        $folder_can_id2[] = $can_data2->candidate_id;
                                    }
                                    $folder_can_id_arr2 = implode(',', $folder_can_id2);
                                }



                                $data['state'] = $Employer_model->can_location_all($folder_can_id_arr);
                                $data['graduation_year'] = $Employer_model->can_education_year_all($folder_can_id_arr2);


                                $data['skills'] = $Employer_model->can_skills_all($folder_can_id_arr);
                                $data['master_college'] = $Employer_model->can_college_all($folder_can_id_arr);
                                $data['master_academic_courses'] = $Employer_model->can_academin_background_all($folder_can_id_arr);
                                $data['master_academic_specialization'] = $Employer_model->can_academin_specialization_all($folder_can_id_arr);
                                $data['master_gender'] = $Employer_model->can_gender_all($folder_can_id_arr);
                                $data['preffered_location'] = $Employer_model->can_preffered_location_all($folder_can_id_arr);

                                $where21 = array('employer_id' => $company_id);
                                $order_by1 = array('ordercolumn' => 'id', 'ordertype' => 'desc');
                                $data['folder'] = $Employer_model->fetch_table_data_for_all('employer_folder', $where21, $order_by1);

                                $where_fol = array('id' => $folder, 'employer_id' => $company_id);
                                $folder_data = $Employer_model->fetch_table_data_for_all('employer_folder', $where_fol);
                                $data['chat_count'] = $Employer_model->fetch_table_data_group_by_chat_folder($folder);
                                if (isset($folder_data) && !empty($folder_data)) {

                                    return view('employer/emp_search_candidate', $data);
                                } else {
                                    return view('Common/404');
                                }
                            }
                        } else {

                            // print_r($available_data);exit;
                            if (empty($available_data[0]->profile_phone_no) || empty($available_data[0]->location_name)) {
                                $ses_data = ['redirect' => 'postinternship',];
                                $session->set($ses_data);
                                return redirect()->to('organization-details');
                            } elseif (empty($available_data[0]->profile_company_description)) {
                                $ses_data = ['redirect' => 'postinternship',];
                                $session->set($ses_data);
                                return redirect()->to('other-info');
                            }
                        }
                    } else {

                        $filter_profile_district = $session->get('profile_district1');
                        $filter_education_skills = $session->get('education_skills1');
                        $filter_education_background = $session->get('education_background1');
                        $filter_education_specialization = $session->get('education_specialization1');
                        $filter_gender = $session->get('gender1');
                        $filter_education_college = $session->get('education_college1');
                        $filter_preffered_location = $session->get('preffered_location1');
                        $filter_graduation_year = $session->get('graduation_year1');
                        // $where1 = array('status' => '1');
                        // $order_by = array('ordercolumn' => 'name', 'ordertype' => 'asc');
                        // $data['state'] = $Employer_model->can_location_all();
                        // $where1 = array('status' => '1');
                        // $order_by1 = array('ordercolumn' => 'skill_name', 'ordertype' => 'asc');
                        // $data['skills'] = $Employer_model->fetch_table_data_for_all('master_skills', $where1, $order_by1);
                        // $where4 = array('status' => '1');
                        // $order_by2 = array('ordercolumn' => 'name', 'ordertype' => 'asc');
                        // $data['master_academic_courses'] = $Employer_model->fetch_table_data_for_all('master_academic_courses', $where4, $order_by2);
                        $data['state'] = $Employer_model->can_location_all();
                        $data['graduation_year'] = $Employer_model->can_education_year_all();
                        $data['skills'] = $Employer_model->can_skills_all();
                        $data['master_college'] = $Employer_model->can_college_all();
                        $data['master_academic_courses'] = $Employer_model->can_academin_background_all();
                        $data['master_academic_specialization'] = $Employer_model->can_academin_specialization_all();
                        $data['master_gender'] = $Employer_model->can_gender_all();
                        $data['preffered_location'] = $Employer_model->can_preffered_location_all();

                        $userid    =    $session->get('userid');
                        $usertype    =    $session->get('usertype');
                        if ($usertype == 2) {
                            $company_id = $session->get('userid');
                        } else {
                            $where = array('userid' => $session->get('userid'));
                            $admin_profile = $Employer_model->fetch_table_row('emp_manage_admins', $where);
                            $company_id = $admin_profile->emp_user_id;
                        }
                        $where1 = array('status' => '1', 'active_status' => '1', 'company_id' => $company_id, 'internship_candidate_lastdate >=' => date('Y-m-d'));
                        $order_by1 = array('ordercolumn' => 'id', 'ordertype' => 'desc');
                        $data['list_internship'] = $Employer_model->fetch_table_data_for_all('employer_post_internship', $where1, $order_by1);
                        if (empty($folder)) {

                            $data['state'] = $Employer_model->can_location_all();
                            $data['graduation_year'] = $Employer_model->can_education_year_all();
                            $data['skills'] = $Employer_model->can_skills_all();
                            $data['master_college'] = $Employer_model->can_college_all();
                            $data['master_academic_courses'] = $Employer_model->can_academin_background_all();
                            $data['master_academic_specialization'] = $Employer_model->can_academin_specialization_all();
                            $data['master_gender'] = $Employer_model->can_gender_all();
                            $data['preffered_location'] = $Employer_model->can_preffered_location_all();

                            // $where1 = array('can_personal_details.status' => '1', 'can_personal_details.g_location_name!=' => '', 'can_education_details.userid!=' => '');
                            $where1 = array('can_personal_details.status' => '1', 'can_education_details.status' => '1', 'can_personal_details.can_profile_complete_status' => '1', 'can_education_details.userid!=' => '');
                            // $data['all_candidate_count'] = $Employer_model->fetch_candidate_data_all('can_personal_details', $where1);
                            $pager = service('pager');

                            $keyword_search = $session->get('searched_keyword_candidates');
                            if (isset($keyword_search)) {
                                $start_id = 0;
                                $limit = 1000;
                                $all_candidate_search = $Employer_model->fetch_candidate_data_all('can_personal_details', $where1, $filter_profile_district, $filter_education_skills, $filter_education_background,$filter_education_specialization, $filter_gender, $filter_education_college, $filter_preffered_location, $filter_graduation_year, $limit, $start_id, $keyword_search);
                                // $keyword_search=$keyword_search;
                            } else {
                                $keyword_search = '';
                            }
                            if (isset($all_candidate_search)) {
                                $limit = config('Pager')->perPage;
                            } else {
                                $limit = config('Pager')->perPage; // see Config/Pager.php
                            }

                            $page = (int) $this->request->getGet('page'); // 
                            //$limit = config('Pager')->perPage; // see Config/Pager.php
                            if (isset($search_candidate_showing_limit)) {
                                $limit = $search_candidate_showing_limit;
                            } else {
                                $limit = config('Pager')->perPage;
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

                            $all_candidate = $Employer_model->fetch_candidate_data_all('can_personal_details', $where1, $filter_profile_district, $filter_education_skills, $filter_education_background,$filter_education_specialization, $filter_gender, $filter_education_college, $filter_preffered_location, $filter_graduation_year, $keyword_search);
                            $data['all_candidate_count'] = $all_candidate;
                            if (isset($all_candidate_search)) {
                                if (!empty($all_candidate_search)) {
                                    $total = count($all_candidate_search);
                                } else {
                                    $total = 0;
                                }
                            } else {
                                if (!empty($all_candidate)) {
                                    $total   = count($all_candidate);
                                } else {
                                    $total   = 0;
                                }
                            }
                            $pager_links = $pager->makeLinks($page, $limit, $total, 'custom_pagination');
                            // print_r($offset);exit;
                            $data['pager_links'] = $pager_links;
                            //ci4 pagination end
                            // print_r($pager_links);
                            $data['all_candidate'] = $Employer_model->fetch_candidate_data_all('can_personal_details', $where1, $filter_profile_district, $filter_education_skills, $filter_education_background,$filter_education_specialization, $filter_gender, $filter_education_college, $filter_preffered_location, $filter_graduation_year, $limit, $start_id, $keyword_search);
                            // print_r($data['all_candidate']);
                            $data['folder_id_new'] = '';
                            $where21 = array('employer_id' => $company_id);
                            $order_by1 = array('ordercolumn' => 'id', 'ordertype' => 'desc');
                            $data['folder'] = $Employer_model->fetch_table_data_for_all('employer_folder', $where21, $order_by1);
                            return view('employer/emp_search_candidate', $data);
                        } else {
                            $where1 = array('employer_folder_data.folder_id' => $folder, 'can_education_details.status' => '1', 'employer_folder_data.trash_flag' => '0');
                            // $data['all_candidate_count'] = $Employer_model->fetch_candidate_data_all_folder('employer_folder_data', $where1);
                            $pager = service('pager');

                            $keyword_search = $session->get('searched_keyword_folder');
                            if (isset($keyword_search)) {
                                $start_id = 0;
                                $limit = 1000;

                                $all_candidate_folder = $Employer_model->fetch_candidate_data_all_folder('employer_folder_data', $where1, $filter_profile_district, $filter_education_skills, $filter_education_background,$filter_education_specialization, $filter_preffered_location, $filter_gender, $filter_graduation_year, $limit, $start_id, $keyword_search);
                                // $keyword_search=$keyword_search;
                            } else {
                                $keyword_search = '';
                            }

                            if (isset($all_candidate_folder)) {
                                $limit = config('Pager')->perPage;
                            } else {
                                $limit = config('Pager')->perPage; // see Config/Pager.php
                            }

                            $page = (int) $this->request->getGet('page'); // 
                            // $limit = config('Pager')->perPage; // see Config/Pager.php
                            if (isset($search_candidate_showing_limit)) {
                                $limit = $search_candidate_showing_limit;
                            } else {
                                $limit = config('Pager')->perPage;
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

                            $all_candidate = $Employer_model->fetch_candidate_data_all_folder('employer_folder_data', $where1, $filter_profile_district, $filter_education_skills, $filter_education_background,$filter_education_specialization, $filter_preffered_location, $filter_graduation_year, $filter_gender);

                            $data['all_candidate_count'] = $all_candidate;
                            if (isset($all_candidate_folder)) {
                                if (!empty($all_candidate_folder)) {
                                    $total = count($all_candidate_folder);
                                } else {
                                    $total = 0;
                                }
                            } else {
                                if (!empty($all_candidate)) {
                                    $total   = count($all_candidate);
                                } else {
                                    $total   = 0;
                                }
                            }
                            $pager_links = $pager->makeLinks($page, $limit, $total, 'custom_pagination');

                            $data['pager_links'] = $pager_links;

                            //ci4 pagination end
                            // print_r($pager_links);
                            if (!empty($keyword_search)) {
                                $data['all_candidate'] = $Employer_model->fetch_candidate_data_all_folder('employer_folder_data', $where1, $filter_profile_district, $filter_education_skills, $filter_education_background,$filter_education_specialization, $filter_preffered_location, $filter_gender, $filter_graduation_year, $limit, $start_id, $keyword_search);
                            } else {
                                $data['all_candidate'] = $Employer_model->fetch_candidate_data_all_folder('employer_folder_data', $where1, $filter_profile_district, $filter_education_skills, $filter_education_background,$filter_education_specialization, $filter_preffered_location, $filter_gender, $filter_graduation_year, $limit, $start_id);
                            }

                            $data['folder_id_new'] = $folder;
                            $folder_can_id = array();
                            $folder_can_id_arr = '';
                            if (isset($all_candidate) && !empty($all_candidate)) {
                                foreach ($all_candidate as $can_data) {
                                    $folder_can_id[] = $can_data->candidate_id;
                                }
                                $folder_can_id_arr = implode(',', $folder_can_id);
                            }
                            // print_r($folder_can_id_arr);exit();
                            $data['state'] = $Employer_model->can_location_all($folder_can_id_arr);
                            $data['graduation_year'] = $Employer_model->can_education_year_all($folder_can_id_arr);
                            $data['skills'] = $Employer_model->can_skills_all($folder_can_id_arr);
                            $data['master_college'] = $Employer_model->can_college_all($folder_can_id_arr);
                            $data['master_academic_courses'] = $Employer_model->can_academin_background_all($folder_can_id_arr);
                            $data['master_academic_specialization'] = $Employer_model->can_academin_specialization_all($folder_can_id_arr);
                            $data['master_gender'] = $Employer_model->can_gender_all($folder_can_id_arr);
                            $data['preffered_location'] = $Employer_model->can_preffered_location_all($folder_can_id_arr);



                            $where_fol = array('id' => $folder, 'employer_id' => $company_id);
                            $folder_data = $Employer_model->fetch_table_data_for_all('employer_folder', $where_fol);
                            if (isset($folder_data) && !empty($folder_data)) {
                                return view('employer/emp_search_candidate', $data);
                            } else {
                                return view('Common/404');
                            }
                        }
                        // $where21 = array('employer_id' => $company_id);
                        // $order_by1 = array('ordercolumn' => 'id', 'ordertype' => 'desc');
                        // $data['folder'] = $Employer_model->fetch_table_data_for_all('employer_folder', $where21, $order_by1);
                        // if(isset($data['all_candidate']) && !empty($data['all_candidate'])){
                        //     return view('employer/emp_search_candidate', $data);
                        //     }else{
                        //     return view('Common/404');
                        //     }
                    }
                }


                public function set_candidate_filters()
                {
                    $session = session();
                    $profile_district = $this->request->getVar('profile_district');
                    $education_skills = $this->request->getVar('education_skills');
                    $education_background = $this->request->getVar('education_background');
                    $education_specialization = $this->request->getVar('education_specialization');
                    $education_college = $this->request->getVar('education_college');

                    $gender = $this->request->getVar('gender');
                    $preffered_location = $this->request->getVar('preffered_location');
                    $graduation_year = $this->request->getVar('graduation_year');

                    if (!empty($preffered_location)) {
                        $preffered_location_arr = explode(',', $preffered_location);
                        $session->set('preffered_location1', $preffered_location_arr);
                    } else {
                        $session->set('preffered_location1', '');
                    }
                    print_r($education_specialization);
                    // exit;

                    if (!empty($profile_district)) {
                        $profile_district_arr = explode(',', $profile_district);
                        $session->set('profile_district1', $profile_district_arr);
                    } else {
                        $session->set('profile_district1', '');
                    }
                    if (!empty($education_skills)) {
                        $education_skills_arr = explode(',', $education_skills);
                        $session->set('education_skills1', $education_skills_arr);
                    } else {
                        $session->set('education_skills1', '');
                    }
                    if (!empty($education_background)) {
                        $education_background_arr = explode(',', $education_background);
                        $session->set('education_background1', $education_background_arr);
                    } else {
                        $session->set('education_background1', '');
                    }

                    if (!empty($education_specialization)) {
                        $education_specialization_arr = explode(',', $education_specialization);
                        $session->set('education_specialization1', $education_specialization_arr);
                    } else {
                        $session->set('education_specialization1', '');
                    }
                    if (!empty($education_college)) {
                        $education_college_arr = explode(',', $education_college);
                        $session->set('education_college1', $education_college_arr);
                    } else {
                        $session->set('education_college1', '');
                    }

                    if (!empty($graduation_year)) {
                        $internship_year_arr = explode(',', $graduation_year);
                        $session->set('graduation_year1', $internship_year_arr);
                    } else {
                        $session->set('graduation_year1', '');
                    }

                    if (!empty($gender)) {
                        $session->set('gender1', $gender);
                    } else {
                        $session->set('gender1', '');
                    }

                    echo csrf_hash() . '^' . 1;
                }
                public function emp_unset_candidate_filters($folder_id)
                {
                    $session = session();

                    $ses_data = [
                        'profile_district1',
                        'education_skills1',
                        'education_background1',
                        'education_specialization1',
                        'education_college1',
                        'gender1',
                        'preffered_location1',
                        'graduation_year1',
                    ];

                    $session->remove($ses_data);
                    if ($folder_id == '0') {
                        return redirect()->to('search-candidates');
                    } else {
                        return redirect()->to('search-candidates/' . $folder_id);
                    }
                }


                public function update_shortlist_status_all()
                {
                    $session = session();
                    $Employer_model = new Employer_model();
                    $shortlist_internship_id = $session->get('shortlist_internship_id');
                    $func_session_post_internship_shortlist_id = $session->get('func_session_post_internship_shortlist_id');
                    if (isset($shortlist_internship_id) && !empty($shortlist_internship_id)) {
                        $interenship_id = $shortlist_internship_id;
                    } else {
                        $interenship_id = $this->request->getVar('interenship_id');
                    }
                    if (isset($func_session_post_internship_shortlist_id) && !empty($func_session_post_internship_shortlist_id)) {
                        $candidate_id1 = $func_session_post_internship_shortlist_id;
                        $application_status = 1;
                    } else {
                        $candidate_id1 = $this->request->getVar('candidate_id');
                        $application_status = $this->request->getVar('application_status');
                    }
                    // $candidate_id1 = $this->request->getVar('candidate_id');
                    $candidate_id = explode(",", $candidate_id1);
                    // print_r($candidate_id);exit;
                    // $interenship_id = $this->request->getVar('interenship_id');


                    // print_r($candidate_id1);exit();
                    $userModel = new LoginModel();
                    $current_datetime = $userModel->current_datetime();
                    $can_count = count($candidate_id);
                    $alert_status = '1';
                    if (!empty($candidate_id)) {
                        foreach ($candidate_id as $key) {
                            $where = array('candidate_id' => $key, 'internship_id' => $interenship_id);
                            $check_applied = $Employer_model->fetch_table_row('can_applied_internship', $where);
                            if (!empty($check_applied)) {
                                if ($check_applied->application_status == 0) {
                                    $data_candidate_id  = array('application_status' => 1);
                                    $where = array('candidate_id' => $key, 'internship_id' => $interenship_id);
                                    $update_application_status = $Employer_model->update_commen('can_applied_internship', $where, $data_candidate_id);
                                } elseif ($check_applied->application_status == 3) {
                                    $data_candidate_id  = array('application_status' => 1);
                                    $where = array('candidate_id' => $key, 'internship_id' => $interenship_id);
                                    $update_application_status = $Employer_model->update_commen('can_applied_internship', $where, $data_candidate_id);
                                } else {
                                    if ($can_count == 1) {
                                        $where = array('status' => '1', 'userid' => $key);
                                        $cand_det = $Employer_model->fetch_table_row('can_personal_details', $where);
                                        $alert_status = '2';
                                    } else {
                                        $alert_status = '1';
                                    }
                                }
                            } else {
                                $data = [
                                    'candidate_id' => $key,
                                    'internship_id' => $interenship_id,
                                    'application_status' => $application_status,
                                    'application_type' => '1',
                                    'created_at' => $current_datetime,

                                ];
                                $insert_data = $Employer_model->insert_commen('can_applied_internship', $data);
                            }
                            if ($can_count == 1) {
                                $where = array('status' => '1', 'userid' => $key);
                                $cand_det = $Employer_model->fetch_table_row('can_personal_details', $where);
                                $alert_message = $cand_det->profile_full_name . " Shortlisted Successfully";
                            } else {
                                $alert_message = $can_count . " Candidates Shortlisted Successfully";
                            }
                        }
                    }


                    if (isset($shortlist_internship_id) && !empty($shortlist_internship_id)) {
                        $func_session_post_internship_folder_id = $session->get('func_session_post_internship_folder_id');
                        if ($func_session_post_internship_folder_id == 0) {
                            $session->setFlashdata('error_msg', $alert_message);
                            $session->setFlashdata('error_status', '2');
                            $ses_data_remove = ['func_session_post_internship_shortlist_id', 'shortlist_internship_id'];
                            $session->remove($ses_data_remove);
                            return redirect()->to('search-candidates');
                        } else {
                            $session->setFlashdata('error_msg', $alert_message);
                            $session->setFlashdata('error_status', '2');
                            $ses_data_remove = ['func_session_post_internship_shortlist_id', 'shortlist_internship_id'];
                            $session->remove($ses_data_remove);
                            return redirect()->to('search-candidates/' . $func_session_post_internship_folder_id);
                        }
                    } else {
                        if ($alert_status == 1) {
                            $session->setFlashdata('error_msg', $alert_message);
                            $session->setFlashdata('error_status', '2');
                            echo csrf_hash() . '^' . 1;
                        } else {

                            echo csrf_hash() . '^' . 11 . '^' . $check_applied->application_status . '^' . $cand_det->profile_full_name;
                        }
                    }
                }

                public function candidate_profile($candidate_id)
                {
                    $session = session();
                    $Employer_model = new Employer_model();

                    $where = array('status' => '1', 'userid' => $candidate_id);
                    $data['profile_personal'] = $Employer_model->fetch_table_row('can_personal_details', $where);
                    $data['education_details'] = $Employer_model->fetch_table_data('can_education_details', $where);
                    $data['address_details'] = $Employer_model->fetch_table_row('can_address_details', $where);
                    $data['experience_details'] = $Employer_model->fetch_table_data('can_experience_details', $where);
                    $data['skill_details'] = $Employer_model->fetch_table_data('can_skills_details', $where);
                    $data['work_sample'] = $Employer_model->fetch_table_row('can_work_sample', $where);

                    $data['candidate_id'] = $candidate_id;
                    $userid    =    $session->get('userid');
                    $usertype    =    $session->get('usertype');
                    if ($usertype == 2) {
                        $company_id = $session->get('userid');
                    } else {
                        $where = array('userid' => $session->get('userid'));
                        $admin_profile = $Employer_model->fetch_table_row('emp_manage_admins', $where);
                        $company_id = $admin_profile->emp_user_id;
                    }
                    $where1 = array('status' => '1', 'active_status' => '1', 'company_id' => $company_id, 'internship_candidate_lastdate >=' => date('Y-m-d'));
                    $order_by1 = array('ordercolumn' => 'id', 'ordertype' => 'desc');
                    $data['list_internship'] = $Employer_model->fetch_table_data_for_all('employer_post_internship', $where1, $order_by1);

                    $where21 = array('employer_id' => $company_id);
                    $order_by1 = array('ordercolumn' => 'id', 'ordertype' => 'desc');
                    $data['folder'] = $Employer_model->fetch_table_data_for_all('employer_folder', $where21, $order_by1);
                    $profile_personal = $data['profile_personal'];
                    if (isset($profile_personal)) {

                        $current_datetime = $Employer_model->current_datetime();
                        $data_i = [
                            'user_id' => $company_id,
                            'candidate_id' => $candidate_id,
                            'created_at' =>  $current_datetime,
                        ];
                        $insert_data = $Employer_model->insert_commen('emp_candidate_profile_log', $data_i);

                        return view('employer/candidate_profile', $data);
                    } else {
                        return view('Common/404');
                    }
                }
                public function emp_folder()
                {

                    $Employer_model = new Employer_model();
                    // print_r($candidate_id);exit();
                    $userModel = new LoginModel();
                    $current_datetime = $userModel->current_datetime();
                    $session = session();
                    $userid    =    $session->get('userid');
                    $usertype    =    $session->get('usertype');
                    if ($usertype == 2) {
                        $company_id = $session->get('userid');
                    } else {
                        $where = array('userid' => $session->get('userid'));
                        $admin_profile = $Employer_model->fetch_table_row('emp_manage_admins', $where);
                        $company_id = $admin_profile->emp_user_id;
                    }


                    $pager = service('pager');
                    $page = (int) $this->request->getGet('page'); // 

                    $limit = 1000; // see Config/Pager.php
                    $keyword_search = $session->get('searched_keyword_search_folder');
                    if (isset($keyword_search)) {
                        $where = array('employer_id' => $company_id);
                        $order_by1 = array('ordercolumn' => 'id', 'ordertype' => 'desc');
                        $start_id = 0;
                        // $keyword_search = $keyword_search;
                        $data_search_folder = $Employer_model->fetch_table_data_for_folder('employer_folder', $where, $order_by1, $limit, $start_id, $keyword_search);
                    } else {
                        $keyword_search = '';
                    }
                    if (isset($data_search_folder)) {
                        $limit = config('Pager')->perPage_folder;
                    } else {
                        $limit = config('Pager')->perPage_folder; // see Config/Pager.php
                    }
                    // echo $limit;exit;

                    if (!isset($page) || $page === 0 || $page === 1) {
                        $page = 1;
                        $start_id = 0;
                    } else {
                        $start_id = ($page - 1) * $limit;
                        $page = $page;
                    }
                    // echo $limit;exit;
                    if ($usertype == 2) {
                        $where = array('userid' => $userid);
                        $available_data = $Employer_model->fetch_table_data_for_all('profile_completion_form', $where);
                        if ($available_data[0]->profile_phone_no != ""  && $available_data[0]->location_name != "" && $available_data[0]->profile_company_description != "") {

                            $where = array('employer_id' => $company_id);
                            $order_by1 = array('ordercolumn' => 'id', 'ordertype' => 'desc');
                            // $data['folder'] = $Employer_model->fetch_table_data_for_all('employer_folder', $where, $order_by1);
                            $data_folder = $Employer_model->fetch_table_data_for_folder('employer_folder', $where, $order_by1);
                            // print_r($data_search_folder);exit;
                            if (isset($data_search_folder)) {
                                if (!empty($data_search_folder)) {
                                    $total = count($data_search_folder);
                                } else {
                                    $total = 0;
                                }
                                $pager_links = $pager->makeLinks($page, $limit, $total, 'custom_pagination');
                                $data['pager_links'] = $pager_links;
                                //  print_r($pager_links);exit;
                            } else {
                                if (!empty($data_folder)) {
                                    $total   = count($data_folder);
                                } else {
                                    $total   = 0;
                                }
                                $pager_links = $pager->makeLinks($page, $limit, $total, 'custom_pagination');
                                $data['pager_links'] = $pager_links;
                                // print_r($pager_links);exit;
                                $previous = '';
                                // $previous = "javascript:history.go(-1)";
                                if (isset($_SERVER['HTTP_REFERER'])) {
                                    $previous = $_SERVER['HTTP_REFERER'];
                                    // echo $previous;
                                    // exit();
                                }
                            }


                            $data['folder'] = $Employer_model->fetch_table_data_for_folder('employer_folder', $where, $order_by1, $limit, $start_id, $keyword_search);
                            return view('employer/emp_folder', $data);
                        } else {
                            // print_r($available_data);exit;
                            if (empty($available_data[0]->profile_phone_no) || empty($available_data[0]->location_name)) {
                                $ses_data = ['redirect' => 'postinternship',];
                                $session->set($ses_data);
                                return redirect()->to('organization-details');
                            } elseif (empty($available_data[0]->profile_company_description)) {
                                $ses_data = ['redirect' => 'postinternship',];
                                $session->set($ses_data);
                                return redirect()->to('other-info');
                            }
                        }
                    } else {
                        if ($usertype == 2) {
                            $company_id = $session->get('userid');
                        } else {
                            $where = array('userid' => $session->get('userid'));
                            $admin_profile = $Employer_model->fetch_table_row('emp_manage_admins', $where);
                            $company_id = $admin_profile->emp_user_id;
                        }
                        $where = array('employer_id' => $company_id);
                        $order_by1 = array('ordercolumn' => 'id', 'ordertype' => 'desc');
                        $data_folder = $Employer_model->fetch_table_data_for_folder('employer_folder', $where, $order_by1);
                        if (isset($data_search_folder)) {
                            if (!empty($data_search_folder)) {
                                $total = count($data_search_folder);
                            } else {
                                $total = 0;
                            }
                            $pager_links = $pager->makeLinks($page, $limit, $total, 'custom_pagination');
                            $data['pager_links'] = $pager_links;
                            //  print_r($pager_links);exit;
                        } else {
                            if (!empty($data_folder)) {
                                $total   = count($data_folder);
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
                        }
                        $data['folder'] = $Employer_model->fetch_table_data_for_folder('employer_folder', $where, $order_by1, $limit, $start_id, $keyword_search);

                        return view('employer/emp_folder', $data);
                    }
                }
                public function emp_create_floder()
                {
                    $Employer_model = new Employer_model();
                    // print_r($candidate_id);exit();
                    $userModel = new LoginModel();
                    $current_datetime = $userModel->current_datetime();
                    $session = session();
                    $userid    =    $session->get('userid');
                    $usertype    =    $session->get('usertype');
                    $folder_name = $this->request->getVar('folder_name');
                    if ($usertype == 2) {
                        $company_id = $session->get('userid');
                    } else {
                        $where = array('userid' => $session->get('userid'));
                        $admin_profile = $Employer_model->fetch_table_row('emp_manage_admins', $where);
                        $company_id = $admin_profile->emp_user_id;
                    }
                    $data = [
                        'employer_id' => $company_id,
                        'folder_name' => $folder_name,
                        'created_by' => $userid,
                        'status' => '1',
                        'created_at' => $current_datetime,
                    ];
                    $where = array('employer_id' => $company_id, 'folder_name' => $folder_name);
                    $check_folder = $Employer_model->fetch_table_row('employer_folder', $where);
                    if (empty($check_folder)) {
                        $result = $Employer_model->insert_commen('employer_folder', $data);
                        $session->setFlashdata('error_status', '2');
                        $session->setFlashdata('error_msg', 'Folder Created Successfully');
                    } else {
                        $session->setFlashdata('error_status', '1');
                        $session->setFlashdata('error_msg', 'Folder Already Exists');
                    }
                    return redirect()->to('my-folder');
                }

                public function emp_work_report_showing($showing_result, $internship_id, $candidate_id)
                {
                    $session = session();
                    $ses_data = [
                        'emp_work_report_showing_limit' => $showing_result
                    ];
                    $session->set($ses_data);
                    return redirect()->to('view-candidate-logsheet/' . $internship_id . '/' . $candidate_id);
                }

                public function emp_logsheet($internship_id, $can_userid)
                {
                    $session = session();
                    $Employer_model = new Employer_model();
                    // $userid    =    $session->get('userid');
                    $emp_work_report_showing_limit = $session->get('emp_work_report_showing_limit');
                    $where = array('status' => '1', 'internship_id' => $internship_id);
                    $data['internship_details'] = $Employer_model->fetch_table_row('employer_post_internship', $where);

                    $where_cou = array('status' => '1', 'candidate_id' => $can_userid, 'internship_id' => $internship_id);
                    $data['internship_applied_list'] = $Employer_model->fetch_table_row('can_applied_internship', $where_cou);
                    if (isset($data['internship_details']) && isset($data['internship_applied_list'])) {
                        $order_by = array('ordercolumn' => 'log_date', 'ordertype' => 'desc');
                        $where_log = array('status' => '1', 'user_id' => $can_userid, 'internship_id' => $internship_id, 'company_id' => $data['internship_details']->company_id);

                        $pager = service('pager');
                        $page = (int) $this->request->getGet('page'); // 

                        if (isset($emp_work_report_showing_limit)) {
                            $limit = $emp_work_report_showing_limit;
                        } else {
                            $limit = config('Pager')->perPage_can_log; // see Config/Pager.php
                        }
                        // $limit = config('Pager')->perPage_can_log; // see Config/Pager.php
                        if (!isset($page) || $page === 0 || $page === 1) {
                            $page = 1;
                            $start_id = 0;
                        } else {
                            $start_id = ($page - 1) * $limit;
                            $page = $page;
                        }
                        $data['page_start_id'] = $start_id;
                        $data['page_default_limit'] = $limit;

                        $log_sheet_detail = $Employer_model->fetch_table_data_for_log('can_log_sheet', $where_log, $order_by);
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
                        $data['log_sheet_details'] = $Employer_model->fetch_table_data_for_log('can_log_sheet', $where_log, $order_by, $limit, $start_id);
                        $data['log_sheet_details_style'] = $log_sheet_detail;
                        // print_r($data['log_sheet_details']);exit;
                        return view('employer/emp_logsheet', $data);
                    } else {
                        return view('Common/404');
                    }
                }

                public function update_approve_log_status()
                {
                    // $model = new Employer_model();
                    $userModel = new LoginModel();
                    $current_datetime = $userModel->current_datetime();
                    $session = session();
                    $id = $this->request->getVar('id');
                    $userid    =    $session->get('userid');
                    $Employer_model = new Employer_model();
                    $where = array('id' => $id, 'status' => '1');
                    $data = ['approved_status' => '1', 'approved_by' => $userid, 'approved_date' => $current_datetime];
                    $update_log_sheet = $Employer_model->update_commen('can_log_sheet', $where, $data);
                    if ($update_log_sheet) {
                        echo csrf_hash() . '^' . 1;
                    }
                }

                public function update_approve_log_status_all()
                {
                    // $model = new Employer_model();
                    $session = session();
                    $log_id1 = $this->request->getVar('log_id');
                    $candidate_id = $this->request->getVar('candidate_id');
                    $log_id = explode(",", $log_id1);
                    $interenship_id = $this->request->getVar('interenship_id');

                    $userid    =    $session->get('userid');
                    // $session = session();
                    $Employer_model = new Employer_model();
                    // print_r($candidate_id);exit();
                    $userModel = new LoginModel();
                    $current_datetime = $userModel->current_datetime();
                    $data = ['approved_status' => '1', 'approved_by' => $userid, 'approved_date' => $current_datetime];
                    // $data_candidate_id  = array('application_status' => $application_status);
                    if (!empty($log_id)) {
                        foreach ($log_id as $key) {

                            $where = array('id' => $key, 'internship_id' => $interenship_id, 'user_id' => $candidate_id);
                            $update_application_status = $Employer_model->update_commen('can_log_sheet', $where, $data);
                        }
                    }
                    if ($update_application_status) {

                        echo csrf_hash() . '^' . 1;
                    } else {
                        echo csrf_hash() . '^' . 1;
                    }
                }

                public function emp_certificate()
                {
                    $Employer_model = new Employer_model();
                    $session = session();
                    $userid    =    $session->get('userid');
                    $usertype    =    $session->get('usertype');
                    if ($usertype == 2) {
                        $company_id = $userid;
                    } else {
                        $where_user = array('status' => '1', 'userid' => $userid);
                        $profile_personal = $Employer_model->fetch_table_row('emp_manage_admins', $where_user);
                        $company_id = $profile_personal->emp_user_id;
                    }
                    $where = array('status' => '1', 'company_id' => $company_id);
                    $order_by = array('ordercolumn' => 'id', 'ordertype' => 'desc');
                    $data['certificate_details'] = $Employer_model->fetch_table_data_for_all('emp_certificate_details', $where, $order_by);

                    $where3 = array('status' => '1', 'userid' => $company_id);
                    $data['company_details'] = $Employer_model->fetch_table_row('profile_completion_form', $where3);


                    return view('employer/emp_certificate', $data);
                }

                public function add_certificate_details()
                {
                    $Employer_model = new Employer_model();
                    $userModel = new LoginModel();
                    $current_datetime = $userModel->current_datetime();
                    $session = session();
                    $userid    =    $session->get('userid');
                    $usertype    =    $session->get('usertype');

                    if ($usertype == 2) {
                        $company_id = $userid;
                    } else {
                        $where_user = array('status' => '1', 'userid' => $userid);
                        $profile_personal = $Employer_model->fetch_table_row('emp_manage_admins', $where_user);
                        $company_id = $profile_personal->emp_user_id;
                    }
                    $where = array('status' => '1', 'company_id' => $company_id);
                    $order_by = array('ordercolumn' => 'id', 'ordertype' => 'desc');
                    $certificate_details = $Employer_model->fetch_table_data_for_all('emp_certificate_details', $where, $order_by);

                    $where3 = array('status' => '1', 'userid' => $company_id);
                    $company_details = $Employer_model->fetch_table_row('profile_completion_form', $where3);

                    if (!empty($certificate_details)) {
                        $version = ($certificate_details[0]->version + 1);
                    } else {
                        $version = '1';
                    }

                    $add_certificate_logo = $this->request->getFile('add_certificate_logo');
                    // print_r();
                    if (!empty($add_certificate_logo->getName())) {
                        $certificate_logo = $add_certificate_logo->getRandomName();
                        $add_certificate_logo->move('public/assets/docs/uploads/emp_profile/', $certificate_logo);
                    } else {
                        if (isset($certificate_details[0]->comany_logo)) {
                            $certificate_logo = $certificate_details[0]->comany_logo;
                        } else {
                            $certificate_logo = $company_details->profile_company_logo;
                        }
                    }
                    $add_certificate_signature = $this->request->getFile('add_certificate_signature');
                    if (!empty($add_certificate_signature->getName())) {
                        $certificate_signature = $add_certificate_signature->getRandomName();
                        $add_certificate_signature->move('public/assets/docs/uploads/emp_profile/', $certificate_signature);
                    } else {
                        if (isset($certificate_details[0]->comany_logo)) {
                            $certificate_signature = $certificate_details[0]->emp_signature;
                        } else {
                            $certificate_signature = '';
                        }
                    }




                    $data = [
                        'company_id' => $company_id,
                        'add_user_id' => $userid,
                        'version' => $version,
                        'comany_logo' => $certificate_logo,
                        'emp_signature' => $certificate_signature,
                        'created_at' => $current_datetime
                    ];
                    //print_r($data);
                    $insert_logo = $Employer_model->insert_commen('emp_certificate_details', $data);
                    // echo $insert_logo;
                    if ($insert_logo) {
                        $session->setFlashdata('error_status', '2');
                        $session->setFlashdata('error_msg', 'Certificate details updated successfully');
                        return redirect()->to('employer-certificate');
                    } else {
                        $session->setFlashdata('error_status', '1');
                        $session->setFlashdata('error_msg', 'Failed Try Again');
                        return redirect()->to('employer-certificate');
                    }
                    // return view('employer/emp_certificate');
                }

                public function issue_certificate_candidate()
                {
                    $session = session();
                    $Employer_model = new Employer_model();
                    $date = date('Y-m-d');
                    $candidate_id = $this->request->getVar('cer_candidate_id');
                    $internship_id = $this->request->getVar('emp_internship_id');
                    $emp_certificate_logo = $this->request->getVar('emp_certificate_logo');
                    $emp_certificate_sign = $this->request->getVar('emp_certificate_sign');
                    $usertype    =    $session->get('usertype');
                    $userid    =    $session->get('userid');
                    // echo $candidate_id;exit();
                    $where_can = array('internship_id' => $internship_id, 'candidate_id' => $candidate_id);
                    $can_apply_details = $Employer_model->fetch_table_row('can_applied_internship', $where_can);

                    $data = [
                        'certificate_issue_status' => 1,
                        'certificate_issue_date' => $date,
                        'certificate_issued_by' => $userid,
                        'certificate_issued_logo' => $emp_certificate_logo,
                        'certificate_issued_sign' => $emp_certificate_sign,
                        'certificate_issued_id' => $can_apply_details->id,
                    ];
                    $where = array('internship_id' => $internship_id, 'candidate_id' => $candidate_id);
                    $update_certificate_status = $Employer_model->update_commen('can_applied_internship', $where, $data);
                    //echo $update_certificate_status;exit();
                    if ($update_certificate_status) {

                        $session->setFlashdata('error_msg', 'Certificate Issued Successfully');
                        $session->setFlashdata('error_status', '2');
                        if ($usertype == 4) {
                            return redirect()->to('accepted-candidate-list-supervisior/' . $internship_id . '/' . $userid);
                        } else {
                            return redirect()->to('accepted-candidate-list/' . $internship_id);
                        }
                    } else {
                        $session->setFlashdata('error_msg', 'Failed Try Again');
                        $session->setFlashdata('error_status', '1');
                        if ($usertype == 4) {
                            return redirect()->to('accepted-candidate-list-supervisior/' . $internship_id . '/' . $userid);
                        } else {
                            return redirect()->to('accepted-candidate-list/' . $internship_id);
                        }
                    }
                }


                public function create_folder()
                {

                    $Employer_model = new Employer_model();
                    // print_r($candidate_id);exit();
                    $userModel = new LoginModel();
                    $current_datetime = $userModel->current_datetime();
                    $session = session();
                    $userid    =    $session->get('userid');
                    $usertype    =    $session->get('usertype');
                    $folder_name = $this->request->getVar('folder_name');
                    $candidate_id1 = $this->request->getVar('candidate_id');
                    $candidate_id = explode(",", $candidate_id1);
                    if ($usertype == 2) {
                        $company_id = $session->get('userid');
                    } else {
                        $where = array('userid' => $session->get('userid'));
                        $admin_profile = $Employer_model->fetch_table_row('emp_manage_admins', $where);
                        $company_id = $admin_profile->emp_user_id;
                    }
                    $data = [
                        'employer_id' => $company_id,
                        'folder_name' => $folder_name,
                        'created_by' => $userid,
                        'status' => '1',
                        'created_at' => $current_datetime,
                    ];
                    $where = array('employer_id' => $company_id, 'folder_name' => $folder_name);
                    $check_folder = $Employer_model->fetch_table_row('employer_folder', $where);
                    if (empty($check_folder)) {
                        $result = $Employer_model->insert_commen('employer_folder', $data);
                        if ($result) {
                            foreach ($candidate_id as $key) {
                                $data = [
                                    'candidate_id' => $key,
                                    'folder_id' => $result,
                                    'employer_id' => $company_id,
                                    'created_by' => $userid,
                                    'created_at' => $current_datetime,

                                ];
                                $insert_data = $Employer_model->insert_commen('employer_folder_data', $data);
                            }
                            echo  csrf_hash() . '^' . 1;
                            $session->setFlashdata('error_status', '2');
                            $session->setFlashdata('error_msg', 'Folder created and candidate moved successfully');
                        }

                        // $where2 = array('employer_id' => $company_id);
                        // $order_by = array('ordercolumn' => 'id', 'ordertype' => 'desc');
                        // $folder = $Employer_model->fetch_table_data_for_all('employer_folder', $where2, $order_by);
                        // $getdates = '';
                        // $getdates = '<option value="" style="color:#bfbfbf;" >--Select Folder--</option>';
                        // if (!empty($folder)) {
                        //     foreach ($folder as $ps) {
                        //         $getdates = $getdates . "<option value='" . $ps->id . "'>" . $ps->folder_name . "</option>";
                        //     }
                        // }
                        // echo  csrf_hash() . '^' . $getdates;
                    } else {
                        echo  csrf_hash() . '^' . '0';
                    }
                }


                public function move_folder_candidate()
                {
                    $candidate_id1 = $this->request->getVar('candidate_id');
                    $candidate_id = explode(",", $candidate_id1);
                    $folder_id = $this->request->getVar('folder_id');
                    $ex_folder_id = $this->request->getVar('ex_folder_id');

                    $session = session();
                    $Employer_model = new Employer_model();
                    // print_r($candidate_id1);exit();
                    $userModel = new LoginModel();
                    $current_datetime = $userModel->current_datetime();
                    $userid    =    $session->get('userid');
                    $usertype    =    $session->get('usertype');
                    if ($usertype == 2) {
                        $company_id = $session->get('userid');
                    } else {
                        $where = array('userid' => $session->get('userid'));
                        $admin_profile = $Employer_model->fetch_table_row('emp_manage_admins', $where);
                        $company_id = $admin_profile->emp_user_id;
                    }

                    if (empty($ex_folder_id)) {
                        $can_count = count($candidate_id);
                        $alert_status = '1';
                        if (!empty($candidate_id)) {
                            foreach ($candidate_id as $key) {
                                $where = array('candidate_id' => $key, 'folder_id' => $folder_id);
                                $check_applied = $Employer_model->fetch_table_row('employer_folder_data', $where);
                                if (!empty($check_applied)) {
                                    if ($can_count == 1) {
                                        $where = array('status' => '1', 'userid' => $key);
                                        $cand_det = $Employer_model->fetch_table_row('can_personal_details', $where);
                                        $alert_status = '2';
                                    } else {
                                        $alert_status = '1';
                                    }
                                } else {
                                    $data = [
                                        'candidate_id' => $key,
                                        'folder_id' => $folder_id,
                                        'employer_id' => $company_id,
                                        'created_by' => $userid,
                                        'created_at' => $current_datetime,

                                    ];
                                    $insert_data = $Employer_model->insert_commen('employer_folder_data', $data);
                                }
                            }
                        }
                    } else {
                        $can_count = count($candidate_id);
                        $alert_status = '1';
                        if (!empty($candidate_id)) {
                            foreach ($candidate_id as $key) {
                                $where = array('candidate_id' => $key, 'folder_id' => $folder_id);
                                $check_applied1 = $Employer_model->fetch_table_row('employer_folder_data', $where);
                                if (!empty($check_applied1)) {
                                    if ($can_count == 1) {
                                        $where = array('status' => '1', 'userid' => $key);
                                        $cand_det = $Employer_model->fetch_table_row('can_personal_details', $where);
                                        $alert_status = '2';
                                    } else {
                                        $alert_status = '1';
                                    }
                                } else {
                                    $data = ['folder_id' => $folder_id];
                                    $where = array('candidate_id' => $key, 'folder_id' => $ex_folder_id);
                                    $update_application_status = $Employer_model->update_commen('employer_folder_data', $where, $data);
                                    $alert_status = '1';
                                }
                            }
                        }
                    }
                    // print_r($alert_status);exit();
                    if ($alert_status == 1) {
                        echo csrf_hash() . '^' . 1;
                        // if($insert_data || $update_application_status){
                        $session->setFlashdata('error_status', '2');
                        $session->setFlashdata('error_msg', 'Candidate Moved to the Folder');
                        // }
                    } else {
                        echo csrf_hash() . '^' . 11 . '^' . $cand_det->profile_full_name;
                    }
                }
                public function check_download_candidate_pdf()
                {

                    $company_id = $this->request->getVar('company_id');
                    $session = session();
                    $Employer_model = new Employer_model();
                    $userModel = new LoginModel();
                    $where = array('status' => '1', 'company_id' => $company_id);
                    $download_details = $Employer_model->fetch_table_data('emp_downloaded_details', $where);
                    if(!empty($download_details))
                    {
                        $total_download = count($download_details);
                    }
                    else
                    {
                        $total_download = 0;
                    }
                    echo csrf_hash() . '^' . '1'. '^' .$total_download;
                }


                function html_to_pdf($candidate_id)
                {
                    $session = session();
                    $Employer_model = new Employer_model();
                    // print_r($candidate_id1);exit();
                    $userModel = new LoginModel();
                     $usertype = $session->get('usertype'); 
                   
                        if ($usertype == 2) {
                            $company_id = $session->get('userid');
                        } else {
                            $where = array('userid' => $session->get('userid'));
                            $admin_profile = $Employer_model->fetch_table_row('emp_manage_admins', $where);
                            $company_id = (!empty($admin_profile->emp_user_id))?$admin_profile->emp_user_id:"";
                        }
                        $current_datetime = $userModel->current_datetime();
                        if ($usertype == 2) {
                            $where = array('status' => '1', 'company_id' => $company_id,'candidate_id' => $candidate_id);
                            $download_details = $Employer_model->fetch_table_data('emp_downloaded_details', $where);
                            if(empty($download_details))
                            {
                                $data = [
                                    'company_id' => $company_id,
                                    'candidate_id' => $candidate_id,
                                    'download_type' => '2',
                                    'created_at' => $current_datetime,
                                ];
                                $cand_download_insert = $Employer_model->insert_commen('emp_downloaded_details', $data);
                            }
                        }
                 
                    $where = array('status' => '1', 'userid' => $candidate_id);
                    $data['profile_personal'] = $Employer_model->fetch_table_row('can_personal_details', $where);
                    $data['education_details'] = $Employer_model->fetch_table_data('can_education_details', $where);
                    $data['address_details'] = $Employer_model->fetch_table_row('can_address_details', $where);
                    $data['experience_details'] = $Employer_model->fetch_table_data('can_experience_details', $where);
                    $data['skill_details'] = $Employer_model->fetch_table_data('can_skills_details', $where);
                    $data['work_sample'] = $Employer_model->fetch_table_row('can_work_sample', $where);

                    $data['candidate_id'] = $candidate_id;
                    $candidate_name = $data['profile_personal']->profile_full_name;
                    $dompdf = new \Dompdf\Dompdf(array('enable_remote' => true));

                    $dompdf->loadHtml(view('employer/candidate_resume', $data));
                    $dompdf->setPaper('A4', 'portrait');
                    $dompdf->render();

                    $dompdf->stream($candidate_name . '_' . $candidate_id . ".pdf");

                    // return view('employer/candidate_resume',$data);
                }



                function sms_send($number, $message)
                {
                    $apiKey = urlencode('EM5b4IXHyls-hDU0Ja8rBGILMJ7RKiJEtcXakV8kke');
                    $sender = urlencode('INTNME');

                    // Prepare data for POST request
                    $data = array('apikey' => $apiKey, 'numbers' => $number, "sender" => $sender, "message" => $message);

                    // Send the POST request with cURL
                    $ch = curl_init('https://api.textlocal.in/send/');
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    $response = curl_exec($ch);
                    curl_close($ch);
                    return true;
                }

                public function download_candidate_excel()
                {

                    // $model = new Employer_model();
                    $candidate_id1 = $this->request->getVar('candidate_id');
                    $candidate_id = explode(",", $candidate_id1);
                    $internship_id = $this->request->getVar('internship_id');

                    $session = session();
                    $Employer_model = new Employer_model();
                    // print_r($candidate_id1);exit();
                    $userModel = new LoginModel();




                    $usertype = $session->get('usertype');
                   
                    if ($usertype == 2) {
                        $company_id = $session->get('userid');
                    } else {
                        $where = array('userid' => $session->get('userid'));
                        $admin_profile = $Employer_model->fetch_table_row('emp_manage_admins', $where);
                        $company_id = $admin_profile->emp_user_id;
                    }


                    $current_datetime = $userModel->current_datetime();
                    






                    $spreadsheet = new Spreadsheet();

                    $sheet = $spreadsheet->getActiveSheet();
                    $sheet->setCellValue('A1', 'S.No');
                    $sheet->setCellValue('B1', 'Name');

                    $sheet->setCellValue('C1', 'Location');

                    $sheet->setCellValue('D1', 'Mobile No.');

                    $sheet->setCellValue('E1', 'Email');
                    $sheet->setCellValue('F1', 'Gender');
                    $sheet->setCellValue('G1', 'Course');
                    $sheet->setCellValue('H1', 'Specialization');
                    $sheet->setCellValue('I1', 'College');
                    $sheet->setCellValue('J1', 'Passout Year');

                    $sheet->setCellValue('K1', 'Percentage');
                    $sheet->setCellValue('L1', 'Skills');
                    $sheet->setCellValue('M1', 'Blog Link');
                    $sheet->setCellValue('N1', 'Git hub profile link');
                    $sheet->setCellValue('O1', 'Playstore Developer A/c');
                    $sheet->setCellValue('P1', 'Other Work Sample');
                    $sheet->setCellValue('Q1', 'Applied on');

                    $count = 2;
                    $i = 1;
                    if (!empty($candidate_id)) {
                        foreach ($candidate_id as $key) {
                            if (!empty($key)) {
                                /*downloaded table insert start*/    
                                if ($usertype == 2) {
                                    $where = array('status' => '1', 'company_id' => $company_id,'candidate_id' => $key);
                                    $download_details = $Employer_model->fetch_table_data('emp_downloaded_details', $where);
                                    if(empty($download_details))
                                    {
                                        $data = [
                                            'company_id' => $company_id,
                                            'candidate_id' => $key,
                                            'download_type' => '1',
                                            'created_at' => $current_datetime,
                                        ];
                                        $cand_download_insert = $Employer_model->insert_commen('emp_downloaded_details', $data);
                                    }
                                }
                                /*downloaded table insert end*/


                                $where = array('status' => '1', 'userid' => $key);
                                $profile_personal = $Employer_model->fetch_table_row('can_personal_details', $where);
                                $gender = $Employer_model->get_master_name_emp('master_gender', $profile_personal->profile_gender, 'gender_type');
                                // if ($profile_personal->profile_gender == '1') {
                                //     $gender = "Male";
                                // } elseif ($profile_personal->profile_gender == '2') {
                                //     $gender = "Female";
                                // } elseif ($profile_personal->profile_gender == '3') {
                                //     $gender = "Transgender";
                                // }
                                $where4 = array('status' => '1', 'userid' => $key);
                                $order_by = array('ordercolumn' => 'education_end_year', 'ordertype' => 'desc');
                                $education_details = $Employer_model->fetch_table_data_for_all_limit('can_education_details', $where4, $order_by);
                                if (!empty($education_details[0]->education_end_year)) {
                                    $education_end_year = $education_details[0]->education_end_year;
                                } else {
                                    $education_end_year = '';
                                }


                                if ($education_details[0]->education_college_name != 0) {
                                    $where1 = array('id' => $education_details[0]->education_college_name);
                                    $education_college_name = $Employer_model->get_master_commen_for_all('master_college', $where1, 'college_name');
                                } else {
                                    $education_college_name = $education_details[0]->education_college_name_other;
                                }
                                if ($education_details[0]->education_course != 0) {
                                    $where1 = array('id' => $education_details[0]->education_course);
                                    $education_course = $Employer_model->get_master_commen_for_all('master_academic_courses', $where1, 'name');
                                } else {
                                    $education_course = $education_details[0]->education_course_other;
                                }
                                if ($education_details[0]->education_specialization != 0) {
                                    $where1 = array('id' => $education_details[0]->education_specialization);
                                    $education_specialization = $Employer_model->get_master_commen_for_all('master_academic_branch', $where1, 'name');
                                } else {
                                    $education_specialization = $education_details[0]->education_specialization_other;
                                }
                                $where5 = array('status' => '1', 'userid' => $key);
                                $skill_details = $Employer_model->fetch_table_data_for_all('can_skills_details', $where5);
                                $skill_details_s = array();
                                if (!empty($skill_details)) {
                                    foreach ($skill_details as $sk) {
                                        $skill_details_s[] =  $Employer_model->get_master_commen('master_skills', $sk->skills, 'skill_name');
                                    }
                                }
                                $where6 = array('status' => '1', 'userid' => $key);
                                $work_sample = $Employer_model->fetch_table_row('can_work_sample', $where6);
                                if (!empty($work_sample->blog_link)) {
                                    $blog_link = $work_sample->blog_link;
                                } else {
                                    $blog_link = '';
                                }
                                if (!empty($work_sample->github_profile)) {
                                    $github_profile = $work_sample->github_profile;
                                } else {
                                    $github_profile = '';
                                }
                                if (!empty($work_sample->play_store_developer)) {
                                    $play_store_developer = $work_sample->play_store_developer;
                                } else {
                                    $play_store_developer = '';
                                }
                                if (!empty($work_sample->other_work_sample_link)) {
                                    $other_work_sample_link = $work_sample->other_work_sample_link;
                                } else {
                                    $other_work_sample_link = '';
                                }
                                if (!empty($education_details[0]->education_performance_optional)) {
                                    $percentage = $education_details[0]->education_performance_optional;
                                } else {
                                    $percentage = '';
                                }
                                $select = ('id,created_at');
                                $where = array('status' => '1', 'candidate_id' => $key, 'internship_id' => $internship_id);
                                $can_apply = $Employer_model->fetch_table_row_col($select, 'can_applied_internship', $where);
                                
                                //   $skills=  implode(",", $skill_details_s);
                                $sheet->setCellValue('A' . $count, $i);
                                $sheet->setCellValue('B' . $count, $profile_personal->profile_full_name);
                                

                                $sheet->setCellValue('C' . $count, $profile_personal->g_location_name);

                                $sheet->setCellValue('D' . $count, $profile_personal->profile_phone_number);

                                $sheet->setCellValue('E' . $count, $profile_personal->profile_email);
                                $sheet->setCellValue('F' . $count, $gender);
                                $sheet->setCellValue('G' . $count, $education_course);
                                $sheet->setCellValue('H' . $count, $education_specialization);
                                $sheet->setCellValue('I' . $count, $education_college_name);
                                $sheet->setCellValue('J' . $count, $education_end_year);
                                

                                $sheet->setCellValue('K' . $count, $percentage);
                                $sheet->setCellValue('L' . $count, implode(",", $skill_details_s));
                                $sheet->setCellValue('M' . $count, $blog_link);
                                $sheet->setCellValue('N' . $count, $github_profile);
                                $sheet->setCellValue('O' . $count, $play_store_developer);
                                $sheet->setCellValue('P' . $count, $other_work_sample_link);
                                //echo "asdf"; exit();
                                if(!empty($can_apply))
                                {
                                    $sheet->setCellValue('Q' . $count, date("d-m-Y", strtotime($can_apply->created_at)));
                                }
                                $count++;
                                $i++;
                            }
                        }
                    }
                    $rand = rand(11111, 99999);
                    $writer = new Xlsx($spreadsheet);
                    $writer->save('public/Candidate_data' . $rand . '.xlsx');
                    //  $this->response->download('Candidate_data'.time().'.xlsx')->setFileName('Candidate_Data.xlsx');
                    echo csrf_hash() . '^' . 'Candidate_data' . $rand . '.xlsx';
                }
                function delete_candidate_excel()
                {
                    $path = $this->request->getVar('path');
                    unlink('public/' . $path);
                    echo csrf_hash() . '^' . 1;
                }
                public function emp_rating_candidate()
                {
                    extract($_REQUEST);
                    // print_r($_REQUEST);
                    // exit();
                    $session         = session();
                    $Employer_model = new Employer_model();
                    $userid          = $session->get('userid');
                    $rating_internship_id = $this->request->getVar('rating_internship_id');
                    $page_redirect = $this->request->getVar('page_redirect');
                    $result          = $Employer_model->emp_rating_candidate($userid);
                    if ($result) {
                        $session->setFlashdata('error_msg', 'Ratings And Feedback Given Successfully');
                        $session->setFlashdata('error_status', '2');
                        if ($page_redirect == 2) {
                            $supervisor_id = $this->request->getVar('supervisor_id');
                            return redirect()->to('/accepted-candidate-list-supervisior/' . $rating_internship_id . '/' . $supervisor_id);
                        } else {
                            return redirect()->to('/accepted-candidate-list/' . $rating_internship_id);
                        }
                    } else {
                        if ($page_redirect == 2) {
                            $supervisor_id = $this->request->getVar('supervisor_id');
                            return redirect()->to('/accepted-candidate-list-supervisior/' . $rating_internship_id . '/' . $supervisor_id);
                        } else {
                            return redirect()->to('/accepted-candidate-list/' . $rating_internship_id);
                        }
                    }
                }



                public function employer_details($company_id = NULL)
                {
                    $session         = session();
                    $Employer_model = new Employer_model();

                    if (!isset($company_id)) {
                        $usertype = $session->get('usertype');

                        if ($usertype == 2) {
                            $company_id = $session->get('userid');
                        } else {
                            $where = array('userid' => $session->get('userid'));
                            $admin_profile = $Employer_model->fetch_table_row('emp_manage_admins', $where);
                            $company_id = $admin_profile->emp_user_id;
                        }
                    }


                    $where = array('userid' => $company_id);
                    $data['employer_details'] = $Employer_model->fetch_table_row('profile_completion_form', $where);
                    $employer_details = $data['employer_details'];
                    if (isset($employer_details)) {
                        $pager = service('pager');
                        $page = (int) $this->request->getGet('page'); // 

                        $order_by = array('ordercolumn' => 'id', 'ordertype' => 'desc');
                        $where_int = array('company_id' => $company_id, 'view_status' => '1');
                        $limit = config('Pager')->perPage_emp_int; // see Config/Pager.php
                        if (!isset($page) || $page === 0 || $page === 1) {
                            $page = 1;
                            $start_id = 0;
                        } else {
                            $start_id = ($page - 1) * $limit;
                            $page = $page;
                        }

                        $internship_list_details = $Employer_model->fetch_single_employer_internship('employer_post_internship', $where_int, $order_by);
                        if (!empty($internship_list_details)) {
                            $total   = count($internship_list_details);
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
                        $data['internship_list'] = $Employer_model->fetch_single_employer_internship('employer_post_internship', $where_int, $order_by, $limit, $start_id);


                        // $data['internship_list'] = $Employer_model->fetch_table_data_for_all('employer_post_internship', $where, $order_by);

                        $where_rat = array('employer_post_internship.company_id' => $company_id, 'can_applied_internship.rating_status' => '1');
                        $rating_data = $Employer_model->fetch_rating_data('employer_post_internship', $where_rat);
                        if (!empty($rating_data[0]->count)) {
                            $data['rating'] = round($rating_data[0]->rating / $rating_data[0]->count);
                        } else {
                            $data['rating'] = '0';
                        }

                        return view('employer/employer_details', $data);
                    } else {
                        return view('Common/404');
                    }
                }
                public function candidate_move_to_trash($id)
                {

                    $session = session();
                    $Employer_model = new Employer_model();
                    $where = array('id' => $id);
                    $folder_data = $Employer_model->fetch_table_row('employer_folder_data', $where);


                    $data = [
                        'trash_flag' => '1',
                    ];

                    $update_data = $Employer_model->update_commen('employer_folder_data', $where, $data);

                    if ($update_data) {
                        $session->setFlashdata('error_msg', 'Candidate Removed Successfully');
                        $session->setFlashdata('error_status', '2');
                        return redirect()->to('search-candidates/' . $folder_data->folder_id);
                    } else {
                        return redirect()->to('search-candidates/' . $folder_data->folder_id);
                    }
                }
                public function emp_trash_folder($folder = NULL)
                {
                    // print_r($folder);exit;
                    helper(['form']);
                    $session = session();
                    $Employer_model = new Employer_model();

                    $filter_profile_district = $session->get('profile_district1');
                    $filter_education_skills = $session->get('education_skills1');
                    $filter_education_background = $session->get('education_background1');
                    $filter_gender = $session->get('gender1');

                    $where1 = array('status' => '1');
                    $order_by = array('ordercolumn' => 'name', 'ordertype' => 'asc');
                    $data['state'] = $Employer_model->can_location_all();
                    $where1 = array('status' => '1');
                    $order_by1 = array('ordercolumn' => 'skill_name', 'ordertype' => 'asc');
                    $data['skills'] = $Employer_model->fetch_table_data_for_all('master_skills', $where1, $order_by1);
                    $where4 = array('status' => '1');
                    $order_by2 = array('ordercolumn' => 'name', 'ordertype' => 'asc');
                    $data['master_academic_courses'] = $Employer_model->fetch_table_data_for_all('master_academic_courses', $where4, $order_by2);
                    $userid    =    $session->get('userid');
                    $usertype    =    $session->get('usertype');
                    if ($usertype == 2) {
                        $company_id = $session->get('userid');
                    } else {
                        $where = array('userid' => $session->get('userid'));
                        $admin_profile = $Employer_model->fetch_table_row('emp_manage_admins', $where);
                        $company_id = $admin_profile->emp_user_id;
                    }
                    $where1 = array('status' => '1', 'active_status' => '1', 'company_id' => $company_id, 'internship_candidate_lastdate >=' => date('Y-m-d'));
                    $order_by1 = array('ordercolumn' => 'id', 'ordertype' => 'desc');
                    $data['list_internship'] = $Employer_model->fetch_table_data_for_all('employer_post_internship', $where1, $order_by1);

                    $where1 = array('employer_folder_data.trash_flag' => '1', 'employer_id' => $company_id);

                    $pager = service('pager');

                    $page = (int) $this->request->getGet('page'); // 
                    $limit = config('Pager')->perPage; // see Config/Pager.php

                    if (!isset($page) || $page === 0 || $page === 1) {
                        $page = 1;
                        $start_id = 0;
                    } else {
                        $start_id = ($page - 1) * $limit;
                        $page = $page;
                    }

                    $all_candidate = $Employer_model->fetch_candidate_data_all_folder('employer_folder_data', $where1, $filter_profile_district, $filter_education_skills, $filter_education_background, $filter_gender);

                    if (!empty($all_candidate)) {
                        $total   = count($all_candidate);
                    } else {
                        $total   = 0;
                    }
                    $pager_links = $pager->makeLinks($page, $limit, $total, 'custom_pagination');
                    // print_r($offset);exit;
                    $data['pager_links'] = $pager_links;
                    //ci4 pagination end
                    // print_r($pager_links);
                    $data['all_candidate'] = $Employer_model->fetch_candidate_data_all_folder('employer_folder_data', $where1, $filter_profile_district, $filter_education_skills, $filter_education_background, $filter_gender, $limit, $start_id);
                    $data['folder_id_new'] = $folder;

                    $where21 = array('employer_id' => $company_id);
                    $order_by1 = array('ordercolumn' => 'id', 'ordertype' => 'desc');
                    $data['folder'] = $Employer_model->fetch_table_data_for_all('employer_folder', $where21, $order_by1);
                    return view('employer/emp_trash_folder', $data);
                }
                public function candidate_profile_trash($candidate_id)
                {
                    $session = session();
                    $Employer_model = new Employer_model();

                    $where = array('status' => '1', 'userid' => $candidate_id);
                    $data['profile_personal'] = $Employer_model->fetch_table_row('can_personal_details', $where);
                    $data['education_details'] = $Employer_model->fetch_table_data('can_education_details', $where);
                    $data['address_details'] = $Employer_model->fetch_table_row('can_address_details', $where);
                    $data['experience_details'] = $Employer_model->fetch_table_data('can_experience_details', $where);
                    $data['skill_details'] = $Employer_model->fetch_table_data('can_skills_details', $where);
                    $data['work_sample'] = $Employer_model->fetch_table_row('can_work_sample', $where);

                    $data['candidate_id'] = $candidate_id;
                    $userid    =    $session->get('userid');
                    $usertype    =    $session->get('usertype');
                    if ($usertype == 2) {
                        $company_id = $session->get('userid');
                    } else {
                        $where = array('userid' => $session->get('userid'));
                        $admin_profile = $Employer_model->fetch_table_row('emp_manage_admins', $where);
                        $company_id = $admin_profile->emp_user_id;
                    }
                    $where1 = array('status' => '1', 'active_status' => '1', 'company_id' => $company_id, 'internship_candidate_lastdate >=' => date('Y-m-d'));
                    $order_by1 = array('ordercolumn' => 'id', 'ordertype' => 'desc');
                    $data['list_internship'] = $Employer_model->fetch_table_data_for_all('employer_post_internship', $where1, $order_by1);

                    $where21 = array('employer_id' => $company_id);
                    $order_by1 = array('ordercolumn' => 'id', 'ordertype' => 'desc');
                    $data['folder'] = $Employer_model->fetch_table_data_for_all('employer_folder', $where21, $order_by1);
                    return view('employer/candidate_profile_trash', $data);
                }
                public function candidate_move_to_restore($id)
                {

                    $session = session();
                    $Employer_model = new Employer_model();
                    $where = array('id' => $id);
                    $folder_data = $Employer_model->fetch_table_row('employer_folder_data', $where);


                    $data = [
                        'trash_flag' => '0',
                    ];

                    $update_data = $Employer_model->update_commen('employer_folder_data', $where, $data);

                    if ($update_data) {
                        $session->setFlashdata('error_msg', 'Restored Successfully');
                        $session->setFlashdata('error_status', '2');
                        return redirect()->to('emp_trash_folder');
                    } else {
                        return redirect()->to('emp_trash_folder');
                    }
                }

                public function registered_employers()
                {
                    $session = session();
                    $Employer_model = new Employer_model();

                    $where = array('status' => '1', 'completed_status' => '1');
                    $order_by = array('ordercolumn' => 'id', 'ordertype' => 'DESC');

                    $pager = service('pager');
                    $page = (int) $this->request->getGet('page'); // 

                    $limit = config('Pager')->perPage_employer; // see Config/Pager.php
                    if (!isset($page) || $page === 0 || $page === 1) {
                        $page = 1;
                        $start_id = 0;
                    } else {
                        $start_id = ($page - 1) * $limit;
                        $page = $page;
                    }

                    $emp_detail = $Employer_model->fetch_table_data_for_pagination('profile_completion_form', $where, $order_by);
                    if (!empty($emp_detail)) {
                        $total   = count($emp_detail);
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
                    $data['emp_profile'] = $Employer_model->fetch_table_data_for_pagination('profile_completion_form', $where, $order_by, $limit, $start_id);

                    // $data['emp_profile'] = $Employer_model->fetch_table_data_for_all('profile_completion_form', $where);
                    return view('employer/registered_employers', $data);
                }

                public function registered_employers_side()
                {

                    $Employer_model = new Employer_model();
                    $company_id = $this->request->getVar('company_id');
                    $where = array('userid' => $company_id);
                    $emp_details = $Employer_model->fetch_table_row('profile_completion_form', $where);

                    echo csrf_hash() . '^' . $emp_details->profile_company_logo . '^' . base64_encode($emp_details->profile_company_name) . '^' . $emp_details->location_name . '^' . $emp_details->userid . '^' . base64_encode($emp_details->profile_company_description) . '^' . base64_encode($emp_details->profile_website_details);
                }
                public function update_candidate_restore()
                {
                    // echo 'afrf';
                    // echo csrf_hash() . '^' . 1;
                    $session = session();
                    $Employer_model = new Employer_model();
                    // $where = array('id' => $id);
                    // $folder_data = $Employer_model->fetch_table_row('employer_folder_data', $where);
                    $candidate_id1 = $this->request->getVar('cann_id');
                    //   print_r($candidate_id1);exit;
                    $candidate_id = explode(",", $candidate_id1);
                    $restore_id = (array_filter($candidate_id));

                    $data = [
                        'trash_flag' => '0',
                    ];
                    if (!empty($restore_id)) {
                        foreach ($restore_id as $key) {
                            $key_value = explode("^", $key);
                            $where = array('candidate_id' => $key_value[0], 'folder_id' => $key_value[1]);
                            $update_data = $Employer_model->update_commen('employer_folder_data', $where, $data);
                        }



                        if ($update_data) {
                            $session->setFlashdata('error_msg', 'Restored Successfully');
                            $session->setFlashdata('error_status', '2');
                            echo csrf_hash() . '^' . 1;
                            //return redirect()->to('emp_trash_folder');

                        } else {
                            echo csrf_hash() . '^' . 1;
                            //return redirect()->to('emp_trash_folder');
                        }
                    }
                }

                public function emp_certificates_session($url, $internship_id, $supervisor_id = NULL)
                {
                    $session = session();
                    $ses_data = [
                        'certificate_redirect' => $url,
                        'certificate_internship_id' => $internship_id,
                        'certificate_supervisor_id' => $supervisor_id,
                    ];
                    $session->set($ses_data);
                    return redirect()->to('employer-certificate');
                }


                public function emp_chat($type, $id = NULL, $can_id = NULL) //Employee Chats View Page
                {
                    // print_r($can_id);
                    helper(['form']);
                    $Employer_model = new Employer_model();
                    $userModel = new LoginModel();
                    $current_datetime = $userModel->current_datetime();
                    $session = session();
                    $userid    =    $session->get('userid');
                    $usertype    =    $session->get('usertype');
                    if ($usertype == 2) {
                        $company_id = $session->get('userid');
                    } else {
                        $where = array('userid' => $session->get('userid'));
                        $admin_profile = $Employer_model->fetch_table_row('emp_manage_admins', $where);
                        $company_id = $admin_profile->emp_user_id;
                    }

                    $where = array('status' => '1', 'company_id' => $company_id);
                    $order_by = array('ordercolumn' => 'id', 'ordertype' => 'desc');
                    $data['list_internship'] = $Employer_model->fetch_table_data_for_all('employer_post_internship', $where, $order_by);
                    $where21 = array('employer_id' => $company_id);
                    $order_by1 = array('ordercolumn' => 'id', 'ordertype' => 'desc');
                    $data['folder'] = $Employer_model->fetch_table_data_for_all('employer_folder', $where21, $order_by1);
                    if ($type == 1) {
                        $data['chat_chandidate'] = $Employer_model->fetch_table_data_group_by_chat_folder($id);
                        // $data['chat_chandidate_unread'] = $Employer_model->fetch_table_data_group_by_chat_folder_unread($id);
                        if (empty($can_id)) {
                            if (!empty($data['chat_chandidate'][0]->receiver_id)) {
                                $data['candidate_id'] = $data['chat_chandidate'][0]->receiver_id;
                            } else {
                                $data['candidate_id'] = '';
                            }
                        } else {
                            $data['candidate_id'] = $can_id;
                        }
                        $data['folder_id'] = $id;
                    } elseif ($type == 2) {

                        $data['chat_chandidate'] = $Employer_model->fetch_table_data_group_by_chat($id);
                        // $data['chat_chandidate_unread'] = $Employer_model->fetch_table_data_group_by_chat_unread($id);
                        if (empty($can_id)) {
                            if (!empty($data['chat_chandidate'][0]->receiver_id)) {
                                $data['candidate_id'] = $data['chat_chandidate'][0]->receiver_id;
                            } else {
                                $data['candidate_id'] = '';
                            }
                        } else {
                            $data['candidate_id'] = $can_id;
                        }
                        $data['internship_id'] = $id;
                    } elseif ($type == 3) {


                        $where3 = array('sender_id' => $userid);
                        $group_by = array('ordercolumn' => 'receiver_id');
                        $order_by = array('ordercolumn' => 'id', 'ordertype' => 'desc');
                        $data['chat_chandidate'] = $Employer_model->fetch_table_data_group_by('chat', $where3, $group_by, $order_by);
                        // $where31 = array('receiver_id' => $userid,'message_status' => '1');
                        // $group_by1 = array('ordercolumn' => 'receiver_id');
                        // $order_by1 = array('ordercolumn' => 'id', 'ordertype' => 'desc');
                        // $data['chat_chandidate_unread'] = $Employer_model->fetch_table_data_group_by('chat', $where31, $group_by1, $order_by1);

                        if (!empty($id)) {
                            $data['candidate_id'] = $id;
                        } else {
                            if (!empty($data['chat_chandidate'][0]->receiver_id)) {
                                $data['candidate_id'] = $data['chat_chandidate'][0]->receiver_id;
                            } else {
                                $data['candidate_id'] = '';
                            }
                        }
                    }
                    // echo "<pre>";print_r($data['chat_chandidate_unread']);exit;
                    $data['type'] = $type;
                    return view('employer/emp_chat', $data);
                }
                public function send_message() //Function For Send Messages Common (AJEX)
                {

                    $Employer_model = new Employer_model();
                    $session = session();
                    $current_datetime = $Employer_model->current_datetime();
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

                    $result = $Employer_model->insert_commen('chat', $data);
                    if ($result) {
                        echo csrf_hash() . '^' . '1';
                    } else {
                        echo csrf_hash() . '^' . '0';
                    }
                } //Function For Send Messages Common (AJEX)

                // --- COMMON FUNCTIONS --- //
                public function get_chat_history() //Function For Getting Messages Common (AJEX)
                {
                    $session = session();
                    $Employer_model = new Employer_model();

                    $receiver_id = $this->request->getVar('receiver_id');
                    $userid = $session->get('userid');
                    $data_user_state = array('message_status' => '2');
                    $update_message_status = $Employer_model->update_message_status('chat', $receiver_id, $userid, $data_user_state);
                    $history = $Employer_model->fetch_chat_data('chat', $receiver_id, $userid);


                    $where = array('sender_id' => $receiver_id, 'receiver_id' => $userid, 'type' => '4', 'evaluated_status' => '0');
                    $evaluatedata = $Employer_model->fetch_table_data('chat', $where);

                    if (!empty($history)) { //IF DATA IN TABLE 
                        $previous_date = null;
                        foreach ($history as $chat) {
                            $id = $chat->id;
                            $sender_id = $chat->sender_id;
                            $receiver_id = $chat->receiver_id;
                            $internship_id = $chat->internship_id;
                            $type = $chat->type;
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
                            $assignment_id = $chat->assignment_id;
                            $status = $chat->message_status;
                            $evaluated_status = $chat->evaluated_status;
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

                        <ul class="ps-0">
                            <?php if ($type == '1') { ?>
                                <li class="sender">
                                    <?php if (!empty($messageBody)) { ?>
                                        <div class="msgCnt">
                                            <p> <?= $messageBody; ?></p>
                                        </div>
                                        <sub class="time w-100 d-inline-block text-start ms-2"><?= $newTime = date("h:i a", strtotime($date)); ?> </sub>

                                    <?php  } else { ?>
                                        <div class="d-flex justify-content-start align-items-center">
                                            <?php if ($attachment_ext == "png" || $attachment_ext == "PNG" || $attachment_ext == "jpg" || $attachment_ext == "JPG"  || $attachment_ext == "jpeg" || $attachment_ext == "JPEG" || $attachment_ext == "tif" || $attachment_ext == "TIF" || $attachment_ext == "tiff" || $attachment_ext == "TIFF") { ?>
                                                <a download href="<?= base_url(); ?>/public/assets/docs/uploads/attachment/<?= $attachment; ?>" title="ImageName"> <img src="<?= base_url(); ?>/public/assets/docs/uploads/attachment/<?= $attachment; ?>" alt="" class="me-1" style="object-fit:fill; max-height: 100%; width:200px;"> </a>
                                            <?php } elseif ($attachment_ext == 'pdf' || $attachment_ext == 'PDF') { ?>
                                                <div class="assignmentPdf bg-white rounded d-flex">
                                                    <div class="d-flex p-2">
                                                        <img src="<?= base_url(); ?>/public/assets/img/pdf1.svg" witdh="30" alt="" class="img-fluid me-2" style="width: 30px;">
                                                        <div class="d-flex flex-column">
                                                            <h6 class="text-dark fs-6"> <?= $attachment_filename; ?></h6>
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
                                                            <h6 class="text-dark fs-6"> <?= $attachment_filename; ?></h6>
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
                                                            <h6 class="text-dark fs-6"> <?= $attachment_filename; ?></h6>
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
                                                            <h6 class="text-dark fs-6"> <?= $attachment_filename; ?></h6>
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
                                                            <h6 class="text-dark fs-6"> <?= $attachment_filename; ?></h6>
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
                                                            <h6 class="text-dark fs-6"> <?= $attachment_filename; ?></h6>
                                                            <span class="text-gray1" style="font-size: 11px;"> <?php echo $this->formatSizeUnits($attachment_filesize); ?></span>
                                                        </div>
                                                    </div>
                                                    <a download="<?= $attachment_filename; ?>" href="<?= base_url(); ?>/public/assets/docs/uploads/attachment/<?= $attachment; ?>" class="text-blue assignDownload p-3 ms-3"><img src="<?= base_url(); ?>/public/assets/img/download_red.svg" alt="" class="me-1 mb-1" width="13"></a>
                                                </div>
                                            <?php } ?>

                                        </div>
                                        <sub class="time w-100 d-inline-block text-start"><?= $newTime = date("h:i a", strtotime($date)); ?> </sub>
                                </li>
                            <?php } ?>

                        <?php  } elseif ($type == '4') { ?>
                            <a href="#sample_scroll<?= $assignment_id; ?>" id="sample_scrollnew<?= $id; ?>">
                                <li class="sender">
                                    <div class="msgCnt py-2">

                                        <div style="background:#d8e2f4; border-radius:8px; border-left: 3px solid #95a9cc;" class="p-1 ps-2">
                                            <small>
                                                <p class="text-dark text-start mb-1"><img src="<?= base_url(); ?>/public/assets/img/chat_assign1.svg" alt="" class="me-1"> Assignment Received</p>
                                            </small>
                                            <p class="fw-medium text-start" style="color:#00000070;"><?= $title; ?></p>
                                        </div>
                                        <p class="text-start text-dark mb-3 mt-3"><?= $file_name; ?></p>
                                        <?php if ($type == '4' && $link != '') { ?>
                                            <div class="assignmentLink text-start d-flex mb-3">
                                                <img src="<?= base_url(); ?>/public/assets/img/assignment_link.svg" alt="" class=" align-self-start me-2" width="14">
                                                <a target="_blank" href="<?= $link; ?>" class="text-blue" style="line-height: 16px;"><?= $link; ?></a>
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
                                                            <h6 class="text-dark fs-6"> <?= $attachment_filename; ?></h6>
                                                            <span class="text-gray1" style="font-size: 11px;"><?php echo $this->formatSizeUnits($attachment_filesize); ?></span>
                                                        </div>
                                                    </div>
                                                    <a download="<?= $attachment_filename; ?>" href="<?= base_url(); ?>/public/assets/docs/uploads/attachment/<?= $attachment; ?>" class="text-blue assignDownload p-3 ms-3"><img src="<?= base_url(); ?>/public/assets/img/download_red.svg" alt="" class="me-1 mb-1" width="13"></a>
                                                </div>
                                            </div> <?php } ?>
                                        <?php if ($evaluated_status == '0') { ?>
                                            <button onclick="evaluated_status('<?php echo $id; ?>','1')" class="btn btn-outlined-blue mt-3 px-2 py-1"><img src="<?= base_url(); ?>/public/assets/img/icon_confirm.svg" alt="" width="17" class="mb-1 me-1 filterWhite"> Mark As Evaluated</button>
                                        <?php } else { ?>
                                            <button onclick="evaluated_status('<?php echo $id; ?>','0')" class="btn btn-prim mt-3 px-2 py-1"><img src="<?= base_url(); ?>/public/assets/img/icon_confirm.svg" alt="" width="17" class="mb-1 me-1 filterWhite"> Marked As Evaluated</button>
                                        <?php  } ?>
                                        <button type="button" class="btn btn-prim rounded border_extra px-2 py-1 ms-2 me-2 mt-3" data-bs-toggle="modal" data-bs-target="#assignment_feedback" onclick="accept('<?php echo $id; ?>')">Feedback</button>
                                    </div>
                                    <sub class="time w-100 d-inline-block"><?= $newTime = date("h:i a", strtotime($date)); ?> </sub>
                                </li>
                            </a>

                        <?php } elseif ($type == '5') {
                                    if (!empty($assignment_id)) {
                                        $where = array('id' => $assignment_id);
                                        $replay_details = $Employer_model->fetch_table_row('chat', $where);
                                    }

                        ?>
                            <a href="#sample_scroll<?= $assignment_id; ?>">
                                <li class="sender">
                                    <div class="msgCnt">
                                        <div style="background:#d8e2f4; border-radius:8px; border-left: 3px solid #95a9cc;" class="p-1 ps-2">
                                            <small>
                                                <p class="text-dark text-start mb-1"> <?= $replay_details->title; ?></p>
                                            </small>

                                        </div>
                                        <p class="text-start"><?= $messageBody; ?></p>

                                    </div> <sub class="time w-100 d-inline-block"><?= $newTime = date("h:i a", strtotime($date)); ?> </sub>
                                </li>
                            </a>
                        <?php } ?>

                        </ul>
                    </div>

                <?php } else { ?>
                    <div class="msg-body">
                        <ul class="ps-0 d-flex flex-column">
                            <?php if ($type == '1') { ?>
                                <li class="reply">
                                    <?php if (!empty($messageBody)) { ?>
                                        <div class="msgCnt">
                                            <p> <?= $messageBody; ?></p>
                                        </div>
                                        <sub class="time w-100 d-inline-block text-end"><?= $newTime = date("h:i a", strtotime($date)); ?> <?php if ($status == '1') { ?><img src="<?= base_url(); ?>/public/assets/img/chat_tick_g.svg" alt="read" class="ms-1" width="16"><?php } elseif ($status == '2') { ?> <img src="<?= base_url(); ?>/public/assets/img/chat_tick_b.svg" alt="read" class="ms-1" width="16"> <?php } ?></sub>
                                    <?php } else { ?>
                                        <div class="d-flex justify-content-end align-items-center">
                                            <?php if ($attachment_ext == "png" || $attachment_ext == "PNG" || $attachment_ext == "jpg" || $attachment_ext == "JPG"  || $attachment_ext == "jpeg" || $attachment_ext == "JPEG" || $attachment_ext == "tif" || $attachment_ext == "TIF" || $attachment_ext == "tiff" || $attachment_ext == "TIFF") { ?>
                                                <a download href="<?= base_url(); ?>/public/assets/docs/uploads/attachment/<?= $attachment; ?>" title="ImageName"> <img src="<?= base_url(); ?>/public/assets/docs/uploads/attachment/<?= $attachment; ?>" alt="" class="me-1" style="object-fit:fill; max-height: 100%; width:200px;"> </a>
                                            <?php } elseif ($attachment_ext == 'pdf' || $attachment_ext == 'PDF') { ?>
                                                <div class="assignmentPdf bg-white rounded d-flex">
                                                    <div class="d-flex p-2">
                                                        <img src="<?= base_url(); ?>/public/assets/img/pdf1.svg" witdh="30" alt="" class="img-fluid me-2" style="width: 30px;">
                                                        <div class="d-flex flex-column text-start">
                                                            <h6 class="text-dark fs-6"> <?= $attachment_filename; ?></h6>
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
                                                            <h6 class="text-dark fs-6"> <?= $attachment_filename; ?></h6>
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
                                                            <h6 class="text-dark fs-6"> <?= $attachment_filename; ?></h6>
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
                                                            <h6 class="text-dark fs-6"> <?= $attachment_filename; ?></h6>
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
                                                            <h6 class="text-dark fs-6"> <?= $attachment_filename; ?></h6>
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
                                                            <h6 class="text-dark fs-6"> <?= $attachment_filename; ?></h6>
                                                            <span class="text-gray1" style="font-size: 11px;"> <?php echo $this->formatSizeUnits($attachment_filesize); ?></span>
                                                        </div>
                                                    </div>
                                                    <a download="<?= $attachment_filename; ?>" href="<?= base_url(); ?>/public/assets/docs/uploads/attachment/<?= $attachment; ?>" class="text-blue assignDownload p-3 ms-3"><img src="<?= base_url(); ?>/public/assets/img/download_red.svg" alt="" class="me-1 mb-1" width="13"></a>
                                                </div>
                                            <?php } ?>


                                        </div>
                                        <sub class="time w-100 d-inline-block text-end "><?= $newTime = date("h:i a", strtotime($date)); ?> <?php if ($status == '1') { ?><img src="<?= base_url(); ?>/public/assets/img/chat_tick_g.svg" alt="read" class="ms-1" width="16"><?php } elseif ($status == '2') { ?> <img src="<?= base_url(); ?>/public/assets/img/chat_tick_b.svg" alt="read" class="ms-1" width="16"> <?php } ?></sub>
                                    <?php } ?>
                                </li>
                            <?php  } elseif ($type == '2') {
                                    $where = array('internship_id' => $chat->internship_id);
                                    $internship_details = $Employer_model->fetch_table_row('employer_post_internship', $where);
                                    if ($internship_details->profile != '0') {
                                        $internship_name = $Employer_model->get_master_name('master_profile', $internship_details->profile);
                                    } else {
                                        $internship_name = $internship_details->other_profile;
                                    }
                            ?>
                                <li class="reply" id="sample_scroll<?= $id; ?>">
                                    <div class="assignment">
                                        <div class="cardBg1 p-2">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <div class="d-flex justify-content-start align-items-start flex-wrap">
                                                    <h4 class="text-center fs-4 mb-0" style="color: #00000080;"><img src="<?= base_url(); ?>/public/assets/img/chat_assign1.svg" alt="" class="me-1">Assignment&nbsp;:&nbsp;</h4>
                                                    <h4 class="fw-medium fs-4 text-start mb-0" style="color:#2B366D;"><?= $title; ?></h4>
                                                </div>
                                            </div>

                                            <div class="d-flex justify-content-start align-items-center gap-2 mb-4">
                                                <div class="bdr_teak align-self-start px-2 ms-0">
                                                    <span class="teak_light f-12">Internship :</span>
                                                    <span class="teak_light fw-semibold f-12 ps-0"><?= $internship_name; ?>
                                                    </span>
                                                </div>
                                                <div class="bdr_red align-self-start px-2">
                                                    <span class="red_light f-12">Last Date :</span>
                                                    <span class="red_light f-12 ps-0">
                                                        <?= $newTime1 = date("d", strtotime($last_date_sub)); ?><sup>th</sup> <?= $newTime2 = date("M Y", strtotime($last_date_sub)); ?>
                                                    </span>
                                                </div>

                                            </div>


                                            <!-- <h5 class="fw-medium text-start" style="color:#2B366D;"><?= $title; ?></h5> -->
                                            <p class="text-start mb-0"><?= $file_name; ?></p>



                                            <?php if ($type == '2' && $link != '') { ?>
                                                <div class="assignmentLink text-start d-flex mt-2">
                                                    <img src="<?= base_url(); ?>/public/assets/img/assignment_link.svg" alt="" class=" align-self-start me-2" width="14">
                                                    <a target="_blank" href="<?= $link; ?>" class="text-blue" style="line-height: 16px;"><?= $link; ?></a>
                                                </div>
                                            <?php } ?>

                                            <?php if (!empty($attachment_filename)) { ?>
                                                <div class="d-flex justify-content-end align-items-center mt-3">
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
                                                                <img src="<?= base_url(); ?>/public/assets/img/pdf_red.svg" witdh="40" alt="" class="img-fluid me-2" style="width: 40px;">
                                                            <?php } ?>
                                                            <!-- <img src="<?= base_url(); ?>/public/assets/img/chat_pdf.svg" alt="" class="me-2"> -->
                                                            <div class="d-flex flex-column text-start">
                                                                <h6 class="text-dark fs-6"> <?= $attachment_filename; ?></h6>
                                                                <span class="text-gray1" style="font-size: 11px;"><?php echo $this->formatSizeUnits($attachment_filesize); ?></span>
                                                            </div>
                                                        </div>
                                                        <a download="<?= $attachment_filename; ?>" href="<?= base_url(); ?>/public/assets/docs/uploads/attachment/<?= $attachment; ?>" class="text-blue assignDownload p-3 ms-3"><img src="<?= base_url(); ?>/public/assets/img/download_red.svg" alt="" class="me-1 mb-1" width="13"></a>
                                                    </div>
                                                </div> <?php } ?>
                                        </div>

                                    </div>
                                    <p class="w-100 rounded-0 px-0"> <sub class="time w-100 d-inline-block text-end"><?= $newTime = date("h:i a", strtotime($date)); ?> <?php if ($status == '1') { ?><img src="<?= base_url(); ?>/public/assets/img/chat_tick_g.svg" alt="read" class="ms-1" width="16"><?php } elseif ($status == '2') { ?> <img src="<?= base_url(); ?>/public/assets/img/chat_tick_b.svg" alt="read" class="ms-1" width="16"> <?php } ?></sub></p>
                                </li>
                            <?php } elseif ($type == '3') {
                                    $where = array('internship_id' => $chat->internship_id);
                                    $internship_details = $Employer_model->fetch_table_row('employer_post_internship', $where);
                                    if ($internship_details->profile != '0') {
                                        $internship_name = $Employer_model->get_master_name('master_profile', $internship_details->profile);
                                    } else {
                                        $internship_name = $internship_details->other_profile;
                                    } ?>
                                <li class="reply" id="sample_scroll<?= $id; ?>">
                                    <div class="assignment interviewTemp p-2 pb-3">
                                        <div class="internview p-2 mb-2">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <div class="d-flex justify-content-start align-items-start flex-wrap">
                                                    <h4 class="text-center fs-4" style="color: #00000080;"><img src="<?= base_url(); ?>/public/assets/img/chat_schedule.svg" alt="" class="me-1"> Interview&nbsp;:&nbsp;</h4>
                                                    <h4 class="fw-medium fs-4 text-start mb-0" style="color:#2B366D;"><?= $title; ?></h4>
                                                </div>
                                                <!-- <span class="badge badge-reschedule align-self-center py-2 fw-normal"><?= $title; ?></span> -->
                                            </div>
                                            <div class="d-flex justify-content-start align-items-center gap-2 mb-4 flex-wrap">
                                                <div class="bdr_teak align-self-start px-2 text-start ms-0">
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
                                                        <a target="_blank" href="<?= $link; ?>" class="text-blue"><?= $link; ?></a>
                                                    </div>
                                                <?php } ?>
                                                <p class="w-100 mt-3"> <?= $interview_description; ?> </p>
                                            </div>

                                            <div class="d-flex flex-wrap justify-content-between align-items-start row">
                                                <div class="col-md-6 lastDate mb-2 mb-md-0">
                                                    <div class="intLastDate text-center py-2 px-3">
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
                                                <div class="col-md-6 lastDate  mb-2 mb-md-0">
                                                    <div class="intLastDate text-center py-2 px-3">
                                                        <p class="text-blue bg-transparent mb-0"><?= $newTime1 = date("h:i a", strtotime($interview_time)); ?> - <?= $end_time1 ?><br><img src="<?= base_url(); ?>/public/assets/img/time.svg" alt="" class="me-2 mb-1" width="19"><b class="d-inline-block text-blue">Time</b></p>
                                                    </div>
                                                </div>


                                            </div>

                                        </div>


                                        <!-- <div class="bdr_teak align-self-start px-2 ms-0">
                                                    <span class="teak_light f-12">Internship :</span>
                                                    <span class="teak_light fw-semibold f-12 ps-0"><?= $internship_name; ?>
                                                    </span>
                                                </div> -->

                                        <?php if ($interview_status == '1') { ?>
                                            <div class="bdr_navy align-self-start py-1 px-2 ms-0 d-inline">
                                                <span class="navy_light f-12">Invitation Accepted</span>
                                            </div>
                                            <button type="button" class="btn btn-danger rounded px-2 py-1 ms-2 me-2" data-bs-toggle="modal" data-bs-target="#cancel" onclick="accept('<?php echo $id; ?>')">Cancel</button>
                                        <?php } elseif ($interview_status == '2') { ?>
                                            <div class="bdr_navy align-self-start py-1 px-2 ms-0 d-inline">
                                                <span class="navy_light f-12">Invitation Declined</span>
                                            </div>
                                            <span class="badge badge-reject fw-normal"></span>
                                        <?php } elseif ($interview_status == '3') { ?>
                                            <div class="bdr_navy align-self-start py-1 px-2 ms-0 d-inline">
                                                <span class="navy_light f-12">Request for Reschedule</span>
                                            </div>
                                        <?php } elseif ($interview_status == '4') { ?>
                                            <div class="bdr_navy align-self-start py-1 px-2 ms-0 d-inline">
                                                <span class="navy_light f-12">Canceled</span>
                                            </div>
                                        <?php } else { ?>
                                            <div class="bdr_navy align-self-start py-1 px-2 ms-0 d-inline mt-2 mt-md-0">
                                                <span class="navy_light f-12">Awaiting Confirmation</span>
                                            </div>
                                            <!-- <span class="badge badge-reschedule fw-normal">Awaiting Confirmation</span> -->
                                            <button type="button" class="btn btn-danger rounded px-2 py-1 ms-2 me-2" data-bs-toggle="modal" data-bs-target="#cancel" onclick="accept('<?php echo $id; ?>')">Cancel</button>
                                        <?php } ?>



                                        <!-- <div>
                                            <div class="d-flex flex-wrap justify-content-between align-items-start">
                                                <div class="col-md-6 lastDate">
                                                    <p class="text-blue bg-transparent"><img src="<?= base_url(); ?>/public/assets/img/chat_lastdate.svg" alt="" class="me-2 mb-1" width=""><b class="d-inline-block text-blue">Date : </b>30<sup>th</sup> Nov 2022</p>
                                                </div>
                                                <div class="col-md-6 lastDate">
                                                    <p class="text-blue bg-transparent"><img src="<?= base_url(); ?>/public/assets/img/time.svg" alt="" class="me-2 mb-1" width="19"><b class="d-inline-block text-blue">Time :</b> 10:00</p>
                                                </div>
                                            </div>
                                            <p class="w-100 rounded-0 pb-0 px-0"> Hi, Thanks for scheduling the interview. I am unavailable on the specified date & time. Could you please reschedule this interview?</p>
                                        </div> -->

                                    </div>
                                    <p class="px-0"><sub class="time w-100 d-inline-block text-end "><?= $newTime = date("h:i a", strtotime($date)); ?> <?php if ($status == '1') { ?><img src="<?= base_url(); ?>/public/assets/img/chat_tick_g.svg" alt="read" class="ms-1" width="16"><?php } elseif ($status == '2') { ?> <img src="<?= base_url(); ?>/public/assets/img/chat_tick_b.svg" alt="read" class="" width="16"> <?php } ?></sub></p>
                                </li>
                            <?php } elseif ($type == '6') {
                                    if (!empty($assignment_id)) {
                                        $where = array('id' => $assignment_id);
                                        $replay_details = $Employer_model->fetch_table_row('chat', $where);
                                    }

                            ?>

                                <li class="reply">
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

                                        </div> <sub class="time w-100 d-inline-block"><?= $newTime = date("h:i a", strtotime($date)); ?> <?php if ($status == '1') { ?><img src="<?= base_url(); ?>/public/assets/img/chat_tick_g.svg" alt="read" class="ms-1" width="16"><?php } elseif ($status == '2') { ?> <img src="<?= base_url(); ?>/public/assets/img/chat_tick_b.svg" alt="read" class="ms-1" width="16"> <?php } ?></sub>
                                    </a>
                                </li>

                            <?php }  ?>

                        </ul>

                    </div>


            <?php }
                        } ?>

        <?php  } else { ?>


            <div class="text-muted mx-auto text-center d-block" style="bottom:0px">
                <p>No Conversation Yet</p>
            </div>




        <?php
                    } ?>

        <?php if (!empty($evaluatedata)) { ?>
            <a href="#sample_scrollnew<?= $evaluatedata[0]->id; ?>" class="floated_ico_aep">
                Assignment evaluation pending&nbsp;&nbsp;<i class="fa fa-chevron-up" aria-hidden="true"></i>
            </a>
        <?php } ?>
    <?php


                } //Function For Getting Messages Common (AJEX)

                public function sent_assignment() //Function For Send Files Common (AJEX)
                {
                    $Employer_model = new Employer_model();
                    $session = session();
                    $files = $this->request->getFile('files');
                    $assignment_title = $this->request->getVar('assignment_title');
                    $assignment_description = $this->request->getVar('assignment_description');
                    $assignment_date = $this->request->getVar('assignment_date');
                    $assignment_link = $this->request->getVar('assignment_link');
                    $assignment_internship = $this->request->getVar('assignment_internship');
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

                    $current_datetime = $Employer_model->current_datetime();
                    $receiver_id = $this->request->getVar('receiver_id');
                    $userid = $session->get('userid');
                    $data = [
                        'sender_id' => $userid,
                        'receiver_id' => $receiver_id,
                        'type' => '2',
                        'message' => '',
                        'attachment_name' => $newName,
                        'attachment_ext' => $ext,
                        'attachment_filename' => $base_name,
                        'attachment_filesize' => $size,
                        'title' => $assignment_title,
                        'file_name' => $assignment_description,
                        'internship_id' => $assignment_internship,
                        'link' => $assignment_link,
                        'last_date_sub' => $assignment_date,
                        'created_at' => $current_datetime,
                        'message_status' => '1',
                    ];
                    $result = $Employer_model->insert_commen('chat', $data);
                    if ($result) {
                        $usertype    =    $session->get('usertype');
                        if ($usertype == 3 || $usertype == 4) {
                            $where_sub = array('userid' => $userid, 'status' => '1');
                            $sub_admin_data = $Employer_model->fetch_table_row('emp_manage_admins', $where_sub);
                            $where_com = array('userid' => $sub_admin_data->emp_user_id, 'status' => '1');
                            $Company_data = $Employer_model->fetch_table_row('profile_completion_form', $where_com);

                            $emp_company_name    = $Company_data->profile_company_name;
                            // $industry_name='';
                        } else {
                            $emp_company_name    =    $session->get('emp_company_name');
                        }
                        if (!empty($assignment_link) && isset($assignment_link)) {
                            $link = $assignment_link;
                            $link_content = 'Reference Link :';
                        } else {
                            $link = '';
                            $link_content = '';
                        }

                        $where = array('userid' => $receiver_id);
                        $can_details = $Employer_model->fetch_table_row('can_personal_details', $where);
                        $current_year = date('Y');
                        $assignment_date = date("d-m-Y", strtotime($assignment_date));
                        // $msg_data['msg_data'] = array('name' => $can_details->profile_full_name, 'company_name' => $emp_company_name, 'assignment_date' => $assignment_date, 'title' => $assignment_title, 'link' => $assignment_link, 'assignment_description' => $assignment_description); //dynamic contents for template
                        // $message     = view('email_template/assignment_received', $msg_data);
                        // $message = '{ "company_name" : "'.$emp_company_name.'", "name" : "'.$can_details->profile_full_name.'","assignment_date" : '.$assignment_date.',"title" : '.$assignment_title.',"link" : '.$link.',"assignment_description" : '.$assignment_description.',"link_content" : '.$link_content.',"year" : '.$current_year.' }'; //dynamic contents for template
                        $message = '{"company_name" : "' . $emp_company_name . '", "name" : "' . $can_details->profile_full_name . '","assignment_date" : ' . $assignment_date . ',"title" : "' . $assignment_title . '","year" : "' . $current_year . '","assignment_description" : "' . $assignment_description . '","link_content" : "' . $link_content . '","link" : "' . $link . '"}'; //dynamic contents for template

                        $subject      = 'New Assignment From ' . $emp_company_name;
                        $to_email     =  $can_details->profile_email;
                        $from_content = 'New Assignment From ' . $emp_company_name;

                        $template_key = '2d6f.456f260c51ab9602.k1.1fcc81b0-a836-11ed-9c3c-5254004d4100.18634884d4b';
                        if (!empty($can_details->profile_email)) {
                            $this->email_send($message, $subject, $to_email, $from_content, $template_key);
                        }

                        $emp_str_length = strlen($emp_company_name);
                        if ($emp_str_length > 30) {
                            $emp_company_name = mb_strimwidth($emp_company_name, 0, 28, "..");
                        }


                        // $message = rawurlencode('You have received an assignment from '.$emp_company_name.', with the submission deadline on '.$assignment_date.' -Team Internme');
                        $message = rawurlencode('You have received an assignment from ' . $emp_company_name . ', with the submission deadline on ' . $assignment_date . ' - Internme Team.');


                        $this->sms_send($can_details->profile_phone_number, $message);


                        echo csrf_hash() . '^' . '1';
                    } else {
                        echo csrf_hash() . '^' . '0';
                    }
                } //Function For Send Files Common (AJEX)


                public function sent_interview() //Function For Send Files Common (AJEX)
                {
                    $Employer_model = new Employer_model();
                    $session = session();
                    $title = $this->request->getVar('title');
                    $interview_date = $this->request->getVar('interview_date');
                    $interview_time = $this->request->getVar('interview_time');
                    $interview_duration = $this->request->getVar('interview_duration');
                    $interview_mode = $this->request->getVar('interview_mode');
                    $interview_internship = $this->request->getVar('interview_internship');
                    $link = $this->request->getVar('link');
                    $interview_description = $this->request->getVar('interview_description');
                    $current_datetime = $Employer_model->current_datetime();
                    $receiver_id = $this->request->getVar('receiver_id');
                    $userid = $session->get('userid');
                    $data = [
                        'sender_id' => $userid,
                        'receiver_id' => $receiver_id,
                        'type' => '3',
                        'title' => $title,
                        'internship_id' => $interview_internship,
                        'interview_date' => $interview_date,
                        'interview_time' => $interview_time,
                        'interview_duration' => $interview_duration,
                        'interview_mode' => $interview_mode,
                        'link' => $link,
                        'interview_description' => $interview_description,
                        'created_at' => $current_datetime,
                        'message_status' => '1',
                    ];
                    $result = $Employer_model->insert_commen('chat', $data);
                    if ($result) {
                        $usertype    =    $session->get('usertype');
                        if ($usertype == 3 || $usertype == 4) {
                            $where_sub = array('userid' => $userid, 'status' => '1');
                            $sub_admin_data = $Employer_model->fetch_table_row('emp_manage_admins', $where_sub);
                            $where_com = array('userid' => $sub_admin_data->emp_user_id, 'status' => '1');
                            $Company_data = $Employer_model->fetch_table_row('profile_completion_form', $where_com);

                            $emp_company_name    = $Company_data->profile_company_name;
                            // $industry_name='';
                        } else {
                            $emp_company_name    =    $session->get('emp_company_name');
                        }
                        $where = array('userid' => $receiver_id);
                        $can_details = $Employer_model->fetch_table_row('can_personal_details', $where);
                        if ($interview_duration == '15') {
                            $endTime = strtotime("+15 minutes", strtotime($interview_time));
                            $end_time1 = date('h:i a', $endTime);
                        } elseif ($interview_duration == '30') {
                            $endTime = strtotime("+30 minutes", strtotime($interview_time));
                            $end_time1 = date('h:i a', $endTime);
                        } elseif ($interview_duration == '60') {
                            $endTime = strtotime("+60 minutes", strtotime($interview_time));
                            $end_time1 = date('h:i a', $endTime);
                        }
                        $start_time = strtotime("+0 minutes", strtotime($interview_time));
                        $int_start_time = date('h:i a', $start_time);
                        $interview_slot = $int_start_time . ' - ' . $end_time1;

                        if ($interview_mode == 1) {
                            $interview_mode_type = 'Video Call';
                            $video_content = 'Join by video link :';
                            $link = $link;
                        } elseif ($interview_mode == 2) {
                            $interview_mode_type = 'Phone';
                            $video_content = '';
                            $link = '';
                        } else {
                            $interview_mode_type = 'In-office';
                            $video_content = '';
                            $link = '';
                        }

                        $interview_date = date("d-m-Y", strtotime($interview_date));
                        $current_year = date('Y');

                        // $msg_data['msg_data'] = array('name' => $can_details->profile_full_name, 'company_name' => $emp_company_name, 'interview_date' => $interview_date, 'interview_time' => $interview_slot, 'title' => $title, 'link' => $link, 'interview_mode' => $interview_mode_type, 'interview_description' => $interview_description); //dynamic contents for template
                        // $message     = view('email_template/interview_scheduled', $msg_data);
                        // $message = '{ "company_name" : "'.$emp_company_name.'", "name" : "'.$can_details->profile_full_name.'", "interview_date" : "'.$interview_date.'" , "interview_time" : "'.$interview_slot.'" , "title" : "'.$title.'" , "link" : "'.$link.'" , "interview_mode" : "'.$interview_mode_type.'" , "interview_description" : "'.$interview_description.'","year" : '.$current_year.'}';
                        $message = '{ "company_name" : "' . $emp_company_name . '", "name" : "' . $can_details->profile_full_name . '", "interview_date" : "' . $interview_date . '" , "interview_time" : "' . $interview_slot . '" , "title" : "' . $title . '" , "link" : "' . $link . '" , "interview_mode" : "' . $interview_mode_type . '" , "video_content" : "' . $video_content . '", "interview_description" : "' . $interview_description . '","year" : ' . $current_year . '}';
                        $subject      = 'Upcoming Interview With ' . $emp_company_name;
                        $to_email     =  $can_details->profile_email;
                        $from_content = 'Upcoming Interview With ' . $emp_company_name;

                        $template_key = '2d6f.456f260c51ab9602.k1.61d369f0-a83d-11ed-9c3c-5254004d4100.18634b7de0f';
                        if (!empty($can_details->profile_email)) {
                            $return_status = $this->email_send($message, $subject, $to_email, $from_content, $template_key);
                        }
                        $emp_str_length = strlen($emp_company_name);


                        if ($emp_str_length > 30) {
                            $emp_company_name = mb_strimwidth($emp_company_name, 0, 28, "..");
                        }

                        // $message = rawurlencode('Your Interview with '.$emp_company_name.' is scheduled on '.$interview_date.' at '.$interview_slot.', All the best! -Team Internme');
                        $message = rawurlencode('Your Interview with ' . $emp_company_name . ' is scheduled on ' . $interview_date . ' at ' . $interview_slot . ', All the best! - Internme Team.');

                        $this->sms_send($can_details->profile_phone_number, $message);


                        echo csrf_hash() . '^' . '1';
                    } else {
                        echo csrf_hash() . '^' . '0';
                    }
                } //Function For Send Files Common (AJEX)
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
                public function check_duplicatecheck_domain()
                {
                    $session = session();
                    $email = $this->request->getVar('email');
                    //  echo $email;
                    $domain1 = explode('@', $email);
                    $Employer_model = new Employer_model();
                    $usertype = $session->get('usertype');
                    if ($usertype == 2) {
                        $company_id = $session->get('userid');
                    } else {
                        $where = array('userid' => $session->get('userid'));
                        $admin_profile = $Employer_model->fetch_table_row('emp_manage_admins', $where);
                        $company_id = $admin_profile->emp_user_id;
                    }
                    $where = array('email_domain' => $domain1[1], 'company_id' => $company_id);
                    $user_domain = $Employer_model->fetch_table_data('userlogin', $where);
                    $list_of_user_domain = array();
                    if (!empty($user_domain)) {
                        foreach ($user_domain as $domain) {
                            $list_of_user_domain[] = $domain->email_domain;
                        }
                        $domain = substr(strrchr(strtolower($email), '@'), 1);

                        if (in_array($domain, $list_of_user_domain)) {

                            echo csrf_hash() . '^' . '0';
                        }
                    } else {
                        $where = array('email_domain' => $domain1[1], 'company_id!=' => $company_id);
                        $user_domain = $Employer_model->fetch_table_row('userlogin', $where);
                        if ($user_domain) {
                            echo csrf_hash() . '^' . '1';
                        } else {
                            echo csrf_hash() . '^' . '0';
                        }
                    }
                }




                public function get_chandidate_profile() //Function For Getting Messages Common (AJEX)
                {
                    $session = session();
                    $Employer_model = new Employer_model();

                    $receiver_id = $this->request->getVar('receiver_id');
                    $userid = $session->get('userid');
                    $where = array('status' => '1', 'userid' => $receiver_id);
                    $profile_personal = $Employer_model->fetch_table_row('can_personal_details', $where);
                    $education_details = $Employer_model->fetch_table_data('can_education_details', $where);
                    // $data['address_details'] = $Employer_model->fetch_table_row('can_address_details', $where);
                    // $data['experience_details'] = $Employer_model->fetch_table_data('can_experience_details', $where);
                    $skill_details = $Employer_model->fetch_table_data('can_skills_details', $where);
                    // $data['work_sample'] = $Employer_model->fetch_table_row('can_work_sample', $where);
                    $history = $Employer_model->fetch_chat_data('chat', $receiver_id, $userid);
                    if (!empty($profile_personal->profile_full_name)) {
                        $firstStringCharacter = strtoupper(substr($profile_personal->profile_full_name, 0, 1));
                        if ($education_details[0]->education_college_name != 0) {
                            $where1 = array('id' => $education_details[0]->education_college_name);
                            $education_college_name = $Employer_model->get_master_commen_for_all('master_college', $where1, 'college_name');
                        } else {
                            $education_college_name = $education_details[0]->education_college_name_other;
                        }
                        if ($education_details[0]->education_course != 0) {
                            $where1 = array('id' => $education_details[0]->education_course);
                            $education_course = $Employer_model->get_master_commen_for_all('master_academic_courses', $where1, 'name');
                        } else {
                            $education_course = $education_details[0]->education_course_other;
                        }
                        if ($education_details[0]->education_specialization != 0) {
                            $where1 = array('id' => $education_details[0]->education_specialization);
                            $education_specialization = $Employer_model->get_master_commen_for_all('master_academic_branch', $where1, 'name');
                        } else {
                            $education_specialization = $education_details[0]->education_specialization_other;
                        }
                    }
    ?>


        <div class="profSnip mb-4">
            <div class="mb-3">
                <div class="d-flex align-items-center mb-3">
                    <div class="flex-shrink-0 bg-white rounded-50 border-blue text-blue fw-bold fs-6 ms-0">
                        <span><?php echo $firstStringCharacter; ?></span>
                    </div>
                    <div class="flex-grow-1 ms-2">
                        <h3 class="mb-0 fs-5"><?php echo $profile_personal->profile_full_name; ?></h3>
                    </div>
                </div>
                <ul class="list-unstyled ps-0 d-flex flex-column border-bottom-blue pb-3">
                    <li class="mb-2">
                        <p class="mb-0"><img src="<?= base_url(); ?>/public/assets/img/mobile_m.svg" alt="Mobile" class="me-2 mb-1" width="18"><?php echo $profile_personal->profile_phone_number; ?></p>
                    </li>
                    <?php if (!empty($profile_personal->profile_email)) { ?>
                        <li>
                            <p class="mb-0"><img src="<?= base_url(); ?>/public/assets/img/email_m.svg" alt="Email" class="me-2 mb-1" width="16"><?php if (!empty($profile_personal->profile_email)) {
                                                                                                                                                        echo $profile_personal->profile_email;
                                                                                                                                                    } ?></p>
                        </li>
                    <?php } ?>
                </ul>
            </div>
            <?php if (!empty($skill_details)) { ?>
                <h6 class="text-blue mt-4">Skill Set</h6>
                <ul class="d-flex flex-wrap ps-0 list-unstyled mb-3 border-bottom-blue pb-3">


                    <?php
                        foreach ($skill_details as $sk) { ?>
                        <li class="d-flex align-items-center me-5">
                            <span class="fw-normal f-13 pt-1 me-2"><?php echo $Employer_model->get_master_skill('master_skills', $sk->skills); ?></span>
                            <?php if ($sk->skill_level == 1) { ?>
                                <i class="fa fa-star me-1 text-yellow" aria-hidden="true"></i>
                                <i class="fa fa-star-o me-1 text-gray" aria-hidden="true"></i>
                                <i class="fa fa-star-o text-gray" aria-hidden="true"></i>
                            <?php } elseif ($sk->skill_level == 2) { ?>
                                <i class="fa fa-star me-1 text-yellow" aria-hidden="true"></i>
                                <i class="fa fa-star me-1 text-yellow" aria-hidden="true"></i>
                                <i class="fa fa-star-o text-gray" aria-hidden="true"></i>

                            <?php } elseif ($sk->skill_level == 3) { ?>
                                <i class="fa fa-star me-1 text-yellow" aria-hidden="true"></i>
                                <i class="fa fa-star me-1 text-yellow" aria-hidden="true"></i>
                                <i class="fa fa-star me-1 text-yellow" aria-hidden="true"></i>
                            <?php  } ?>


                        </li>
                    <?php } ?>
                </ul>
            <?php } ?>
            <h6 class="text-blue mt-4 mb-3">Education</h6>
            <h6 class="fw-normal f-14"><?php echo $education_course; ?> ( <?php echo $education_specialization; ?> )</h6>
            <h6 class="text-muted fw-normal f-14"><?php echo $education_college_name; ?></h6>
            <ul class="d-flex flex-wrap ps-0 mb-1">
                <li class="me-5 text-muted list-unstyled"><?php if (isset($education_details[0]->education_start_year)) {
                                                                $years_edu = $education_details[0]->education_end_year - $education_details[0]->education_start_year;
                                                                if ($years_edu == '1') {
                                                                    echo  $years_edu . " year ";
                                                                } else {
                                                                    echo  $years_edu . " years";
                                                                }
                                                            } ?></li>
                <li class="text-muted"><?php if (isset($education_details[0]->education_start_year)) {
                                            echo $education_details[0]->education_start_year;
                                        } ?> - <?php if (isset($education_details[0]->education_end_year)) {
                                                    echo $education_details[0]->education_end_year;
                                                } ?></li>
            </ul>
        </div>

        <?php
                }

                public function sent_bulk_chat() //Function For Send bulk chat
                {
                    $Employer_model = new Employer_model();
                    $session = session();
                    $userid = $session->get('userid');
                    $chat_type = $this->request->getVar('chat_type');
                    $current_datetime = $Employer_model->current_datetime();
                    $receiver_id1 = $this->request->getVar('receiver_id');
                    $receiver_id = explode(",", $receiver_id1);
                    if ($chat_type == 1) {
                        $message = $this->request->getVar('message');
                        if (!empty($receiver_id)) {
                            foreach ($receiver_id as $key) {
                                $data = [
                                    'sender_id' => $userid,
                                    'receiver_id' => $key,
                                    'type' => '1',
                                    'message' => $message,
                                    'created_at' => $current_datetime,
                                    'message_status' => '1',
                                ];
                                $result = $Employer_model->insert_commen('chat', $data);
                            }
                        }
                    } else {
                        $files = $this->request->getFile('files');
                        $assignment_description = $this->request->getVar('assignment_description');
                        $assignment_date = $this->request->getVar('assignment_date');
                        $assignment_link = $this->request->getVar('assignment_link');
                        $assignment_internship = $this->request->getVar('assignment_internship');
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
                        if (!empty($receiver_id)) {
                            foreach ($receiver_id as $key) {
                                $data = [
                                    'sender_id' => $userid,
                                    'receiver_id' => $key,
                                    'type' => '2',
                                    'message' => '',
                                    'attachment_name' => $newName,
                                    'attachment_ext' => $ext,
                                    'attachment_filename' => $base_name,
                                    'attachment_filesize' => $size,
                                    'file_name' => $assignment_description,
                                    'internship_id' => $assignment_internship,
                                    'link' => $assignment_link,
                                    'last_date_sub' => $assignment_date,
                                    'created_at' => $current_datetime,
                                    'message_status' => '1',
                                ];
                                $result = $Employer_model->insert_commen('chat', $data);
                            }
                        }
                    }


                    if ($result) {
                        echo csrf_hash() . '^' . '1';
                    } else {
                        echo csrf_hash() . '^' . '0';
                    }
                } //Function For Send Files Common (AJEX)

                public function dependency_academic_background_filter()
                {

                    $Employer_model = new Employer_model();
                    $session = session();
                    $internship_id = $this->request->getVar('internship_id');
                    $education_background = $this->request->getVar('education_background');
                    $education_specialization = $this->request->getVar('education_specialization');
                    $profile_district_val = $this->request->getVar('profile_district');
                    $filter_gender = $session->get('gender');
                    $profile_district = array();


                    if (!empty($profile_district_val)) {
                        $profile_district_val_arr = explode(',', $profile_district_val);
                        for ($x = 0; $x < count($profile_district_val_arr); $x++) {
                            $profile_district[] = "'" . $profile_district_val_arr[$x] . "'";
                        }
                    }
                    // print_r($profile_district);
                    // exit;

                    $education_skills = $this->request->getVar('education_skills');
                    $gender_val = $this->request->getVar('gender');
                    $education_college = $this->request->getVar('education_college');
                    $internship_lable = $this->request->getVar('internship_lable');
                    $graduation_year = $this->request->getVar('graduation_year');


                    $where_can = array('can_applied_internship.status' => '1', 'can_applied_internship.internship_id' => $internship_id);
                    $data['applied_details_filter'] = $Employer_model->fetch_candidate_data_depen('can_applied_internship', $where_can, $profile_district, $education_skills, $education_background,$education_specialization, $gender_val, $education_college, $internship_lable, $graduation_year);
                    // print_r($data['applied_details_filter']);

                    $folder_can_id = array();
                    $folder_can_id_arr = '';
                    if (isset($data['applied_details_filter']) && !empty($data['applied_details_filter'])) {
                        foreach ($data['applied_details_filter'] as $can_data) {
                            $folder_can_id[] = $can_data->candidate_id;
                        }
                        $folder_can_id_arr = implode(',', $folder_can_id);
                    }
                    $data_location = $Employer_model->can_location_all($folder_can_id_arr);

                    $getlocation = '';
                    // print_r($data_location);
                    if (!empty($data_location)) {
                        foreach ($data_location as $location) {
                            $getlocation = $getlocation . "<option value='" . $location->g_location_id . "'>" . $location->g_location_name . "</option>";
                        }
                    }

                    $data_graduation_year = $Employer_model->can_education_year_all($folder_can_id_arr);

                    $getyear = '';
                    if (!empty($data_graduation_year)) {
                        foreach ($data_graduation_year as $year) {
                            $getyear = $getyear . "<option value='" . $year->education_end_year . "'>" . $year->education_end_year . "</option>";
                        }
                    }



                    $data_skill = $Employer_model->can_skills_all($folder_can_id_arr);

                    $getskill = '';
                    // print_r($data_location);
                    if (!empty($data_skill)) {
                        foreach ($data_skill as $skill) {
                            $getskill = $getskill . "<option value='" . $skill->id . "'>" . $skill->skill_name . "</option>";
                        }
                    }

                    $data_gender = $Employer_model->can_gender_all($folder_can_id_arr);

                    $getgender = '';
                    // print_r($data_location);
                    if (!empty($data_gender)) {
                        foreach ($data_gender as $gender) {
                            if ($gender->id == $filter_gender) {
                                $getgender = $getgender . "<option value='" . $gender->id . "' selected >" . $gender->gender_type . "</option>";
                            } else {
                                $getgender = $getgender . "<option value='" . $gender->id . "'>" . $gender->gender_type . "</option>";
                            }
                        }
                    }

                    $data_college = $Employer_model->can_college_all($folder_can_id_arr);

                    $getcollege = '';

                    if (!empty($data_college)) {
                        foreach ($data_college as $college) {
                            $getcollege = $getcollege . "<option value='" . $college->id . "'>" . $college->college_name . "</option>";
                        }
                    }

                    $data_academin_background = $Employer_model->can_academin_background_all($folder_can_id_arr);

                    $getacademin_background = '';
                    // print_r($data_academin_background);
                    if (!empty($data_academin_background)) {
                        foreach ($data_academin_background as $academin_background) {
                            $getacademin_background = $getacademin_background . "<option value='" . $academin_background->id . "'>" . $academin_background->name . "</option>";
                        }
                    }

                    $data_academin_specialization = $Employer_model->can_academin_specialization_all($folder_can_id_arr);

                    $getacademin_specialization = '';
                    // print_r($data_academin_background);
                    if (!empty($data_academin_specialization)) {
                        foreach ($data_academin_specialization as $academin_specialization) {
                            $getacademin_specialization = $getacademin_specialization . "<option value='" . $academin_specialization->id . "'>" . $academin_specialization->name . "</option>";
                        }
                    }

                    $data_internship_label = $Employer_model->can_label_all($internship_id, $folder_can_id_arr);
                    $get_internship_label = '';
                    // print_r($data_location);
                    if (!empty($data_internship_label)) {
                        foreach ($data_internship_label as $internship_label) {
                            $get_internship_label = $get_internship_label . "<option value='" . $internship_label->id . "'>" . $internship_label->label_name . "</option>";
                        }
                    }
                    // print_r($getcollege);exit();


                    echo csrf_hash() . '^' . $getlocation . '^' . $getskill . '^' . $getgender . '^' . $getcollege . '^' . $getacademin_background . '^' . $get_internship_label . '^' . $getyear. '^' . $getacademin_specialization;


                    // $data['master_college'] = $Employer_model->can_college_all($folder_can_id_arr);
                    // $data['master_academic_courses'] = $Employer_model->can_academin_background_all($folder_can_id_arr);
                    // $data['master_gender'] = $Employer_model->can_gender_all($folder_can_id_arr);


                }



                public function dependency_search_candidate_filter()
                {

                    $Employer_model = new Employer_model();
                    $session = session();
                    $education_background = $this->request->getVar('education_background');
                    $education_specialization = $this->request->getVar('education_specialization');
                    $profile_district_val = $this->request->getVar('profile_district');
                    $folder_id = $this->request->getVar('folder_id');
                    $preffered_location_val = $this->request->getVar('preffered_location');


                    $preffered_location = array();


                    if (!empty($preffered_location_val)) {
                        $preffered_location_val_arr = explode(',', $preffered_location_val);
                        for ($k = 0; $k < count($preffered_location_val_arr); $k++) {
                            $preffered_location[] = "'" . $preffered_location_val_arr[$k] . "'";
                        }
                    }
                    // print_r($preffered_location);

                    $profile_district = array();


                    if (!empty($profile_district_val)) {
                        $profile_district_val_arr = explode(',', $profile_district_val);
                        for ($x = 0; $x < count($profile_district_val_arr); $x++) {
                            $profile_district[] = "'" . $profile_district_val_arr[$x] . "'";
                        }
                    }

                    // exit;

                    $education_skills = $this->request->getVar('education_skills');
                    $gender_val = $this->request->getVar('gender');
                    $education_college = $this->request->getVar('education_college');
                    $graduation_year = $this->request->getVar('graduation_year');
                    // print_r($data['applied_details_filter']);
                    if (isset($folder_id) && !empty($folder_id)) {
                        $where_can = array('employer_folder_data.status' => '1', 'employer_folder_data.folder_id' => $folder_id);
                        $data['applied_details_filter'] = $Employer_model->fetch_candidate_data_depen_folder('employer_folder_data', $where_can, $profile_district, $education_skills, $education_background,$education_specialization, $gender_val, $education_college, $preffered_location, $graduation_year);
                    } else {
                        $where_can = array('can_personal_details.status' => '1', 'can_personal_details.can_profile_complete_status' => '1');
                        $data['applied_details_filter'] = $Employer_model->fetch_candidate_data_depen_search('can_personal_details', $where_can, $profile_district, $education_skills, $education_background,$education_specialization, $gender_val, $education_college, $preffered_location, $graduation_year);
                    }
                    // print_r($preffered_location);

                    $folder_can_id = array();
                    $folder_can_id_arr = '';
                    if (isset($data['applied_details_filter']) && !empty($data['applied_details_filter'])) {
                        foreach ($data['applied_details_filter'] as $can_data) {
                            $folder_can_id[] = $can_data->candidate_id;
                        }
                        $folder_can_id_arr = implode(',', $folder_can_id);
                    }

                    $data_preffered_location = $Employer_model->can_preffered_location_all($folder_can_id_arr);

                    $getpreffered_location = '';
                    // print_r($data_preffered_location);exit();
                    if (!empty($data_preffered_location)) {
                        foreach ($data_preffered_location as $preffered_location) {
                            $getpreffered_location = $getpreffered_location . "<option value='" . $preffered_location->g_location_id . "'>" . $preffered_location->g_location_name . "</option>";
                        }
                    }

                    $data_location = $Employer_model->can_location_all($folder_can_id_arr);

                    $getlocation = '';
                    // print_r($data_location);exit();
                    if (!empty($data_location)) {
                        foreach ($data_location as $location) {
                            $getlocation = $getlocation . "<option value='" . $location->g_location_id . "'>" . $location->g_location_name . "</option>";
                        }
                    }




                    $data_graduation_year = $Employer_model->can_education_year_all($folder_can_id_arr);

                    $getyear = '';
                    if (!empty($data_graduation_year)) {
                        foreach ($data_graduation_year as $year) {
                            $getyear = $getyear . "<option value='" . $year->education_end_year . "'>" . $year->education_end_year . "</option>";
                        }
                    }

                    $data_skill = $Employer_model->can_skills_all($folder_can_id_arr);

                    $getskill = '';
                    // print_r($data_location);
                    if (!empty($data_skill)) {
                        foreach ($data_skill as $skill) {
                            $getskill = $getskill . "<option value='" . $skill->id . "'>" . $skill->skill_name . "</option>";
                        }
                    }

                    $data_gender = $Employer_model->can_gender_all($folder_can_id_arr);

                    $getgender = '';
                    // print_r($data_location);
                    // $filter_gender = $session->get('gender1');
                    $filter_gender = $session->get('gender1');
                    if (!empty($data_gender)) {
                        foreach ($data_gender as $gender) {
                            if ($gender->id == $filter_gender) {
                                $getgender = $getgender . "<option value='" . $gender->id . "' selected >" . $gender->gender_type . "</option>";
                            } else {
                                $getgender = $getgender . "<option value='" . $gender->id . "'>" . $gender->gender_type . "</option>";
                            }
                        }
                    }

                    $data_college = $Employer_model->can_college_all($folder_can_id_arr);

                    $getcollege = '';
                    // print_r($data_location);
                    if (!empty($data_college)) {
                        foreach ($data_college as $college) {
                            $getcollege = $getcollege . "<option value='" . $college->id . "'>" . $college->college_name . "</option>";
                        }
                    }

                    $data_academin_background = $Employer_model->can_academin_background_all($folder_can_id_arr);

                    $getacademin_background = '';
                    // print_r($data_location);
                    if (!empty($data_academin_background)) {
                        foreach ($data_academin_background as $academin_background) {
                            $getacademin_background = $getacademin_background . "<option value='" . $academin_background->id . "'>" . $academin_background->name . "</option>";
                        }
                    }
                    
                    $data_academin_specialization = $Employer_model->can_academin_specialization_all($folder_can_id_arr);

                    $getacademin_specialization = '';
                    // print_r($data_location);
                    if (!empty($data_academin_specialization)) {
                        foreach ($data_academin_specialization as $academin_specialization) {
                            $getacademin_specialization = $getacademin_specialization . "<option value='" . $academin_specialization->id . "'>" . $academin_specialization->name . "</option>";
                        }
                    }

                    echo csrf_hash() . '^' . $getlocation . '^' . $getskill . '^' . $getgender . '^' . $getcollege . '^' . $getacademin_background . '^' . $getpreffered_location . '^' . $getyear. '^' . $getacademin_specialization;


                    // $data['master_college'] = $Employer_model->can_college_all($folder_can_id_arr);
                    // $data['master_academic_courses'] = $Employer_model->can_academin_background_all($folder_can_id_arr);
                    // $data['master_gender'] = $Employer_model->can_gender_all($folder_can_id_arr);


                }
                public function new_message_cheack() //Function For New Messages Cheack For Candidate (AJEX)
                {
                    extract($_REQUEST);
                    $session = session();
                    $Employer_model = new Employer_model();
                    // $rec_id1='1221008050109,122121203341138,122121211573881,122071010000724,122112510160094,122071010000749,122071010000741,1221008055252,122071010000723,122071010000742,122071010000740,122071010000739,122071010000738,122071010000737,122071010000736,122071010000735,122071010000734,122071010000733,122071010000732,122071010000731,122071010000729,122071010000728,122071010000727,122071010000726,122071010000725,1221110104648,1221008053714,1221008060158,1221008054546,1221008041056,1221008044540,1221008050514';
                    // $receiver_id = explode(',' , $rec_id1);
                    $useridsess = $session->get('userid');
                    $msg_status = $Employer_model->msg_status('chat', $useridsess);
                    // echo json_encode(array('csrf' => csrf_hash(), 'data_msg' => $msg_status));
                    echo json_encode(array('data_msg' => $msg_status));
                } //Function For New Messages Cheack For Candidate (AJEX)

                public function internship_repost($internship_id)
                {
                    $session = session();
                    $ses_data = ['internship_repost' => 1];
                    $session->set($ses_data);
                    return redirect()->to('internship-edit/' . $internship_id);
                }

                public function cancel_interview() //Function For accept interview
                {
                    $session = session();
                    $Employer_model = new Employer_model();
                    $id = $this->request->getVar('id');
                    $messageTxt = $this->request->getVar('cancel_description');
                    $current_datetime = $Employer_model->current_datetime();
                    $receiver_id = $this->request->getVar('receiver_id');
                    $userid = $session->get('userid');
                    $data = [
                        'sender_id' => $userid,
                        'receiver_id' => $receiver_id,
                        'type' => '1',
                        'message' => $messageTxt,
                        'created_at' => $current_datetime,
                        'message_status' => '1',
                    ];
                    $result = $Employer_model->insert_commen('chat', $data);
                    $where = array('id' => $id);
                    $data = [
                        'interview_status' => '4',
                    ];
                    $update_data = $Employer_model->update_commen('chat', $where, $data);
                    if ($result) {
                        echo csrf_hash() . '^' . '1';
                    } else {
                        echo csrf_hash() . '^' . '0';
                    }
                }
                public function assignment_feedback() //Function For accept interview
                {
                    $session = session();
                    $Employer_model = new Employer_model();
                    $id = $this->request->getVar('id');
                    $messageTxt = $this->request->getVar('assignment_feedback_dec');
                    $current_datetime = $Employer_model->current_datetime();
                    $receiver_id = $this->request->getVar('receiver_id');
                    $userid = $session->get('userid');
                    $data = [
                        'sender_id' => $userid,
                        'receiver_id' => $receiver_id,
                        'type' => '6',
                        'message' => $messageTxt,
                        'assignment_id' => $id,
                        'created_at' => $current_datetime,
                        'message_status' => '1',
                    ];
                    $result = $Employer_model->insert_commen('chat', $data);
                    // $where = array('id' => $id);
                    // $data = [
                    //     'assignment_status' => '2',
                    // ];
                    // $update_data = $Employer_model->update_commen('chat', $where, $data);
                    if ($result) {
                        echo csrf_hash() . '^' . '1';
                    } else {
                        echo csrf_hash() . '^' . '0';
                    }
                }

                public function employer_create_label()
                {

                    $Employer_model = new Employer_model();
                    // print_r($candidate_id);exit();
                    $userModel = new LoginModel();
                    $current_datetime = $userModel->current_datetime();
                    $session = session();
                    $userid    =    $session->get('userid');
                    $usertype    =    $session->get('usertype');
                    $add_label_name = $this->request->getVar('add_label_name');
                    $add_label_name_color = $this->request->getVar('add_label_name_color');
                    $internship_id = $this->request->getVar('internship_id');
                    $candidate_id1 = $this->request->getVar('candidate_id');
                    $candidate_id = explode(",", $candidate_id1);
                    if ($usertype == 2) {
                        $company_id = $session->get('userid');
                    } else {
                        $where = array('userid' => $session->get('userid'));
                        $admin_profile = $Employer_model->fetch_table_row('emp_manage_admins', $where);
                        $company_id = $admin_profile->emp_user_id;
                    }
                    $data = [
                        'employer_id' => $company_id,
                        'employee_user_id' => $userid,
                        'internship_id' => $internship_id,
                        'label_name' => $add_label_name,
                        'label_color' => $add_label_name_color,
                        'created_by' => $userid,
                        'status' => '1',
                        'created_at' => $current_datetime,
                    ];
                    $where = array('employee_user_id' => $userid, 'internship_id' => $internship_id, 'label_name' => $add_label_name, 'status' => '1');
                    $check_label = $Employer_model->fetch_table_row('employer_label', $where);
                    if (empty($check_label)) {
                        $result = $Employer_model->insert_commen('employer_label', $data);
                    } else {
                        $result = $check_label->id;
                    }
                    if ($result) {
                        // if(!empty($candidate_id[0])){
                        foreach ($candidate_id as $key) {
                            $data_label = [
                                'candidate_id' => $key,
                                'label_id' => $result,
                                'employer_id' => $company_id,
                                'employee_user_id' => $userid,
                                'internship_id' => $internship_id,
                                'label_name' => $add_label_name,
                                'created_by' => $userid,
                                'created_at' => $current_datetime,

                            ];
                            $where = array('internship_id' => $internship_id, 'candidate_id' => $key, 'employee_user_id' => $userid, 'status' => '1');
                            $check_label_data = $Employer_model->fetch_table_data_for_all('employer_label_data', $where);
                            if (isset($check_label_data) && !empty($check_label_data)) {
                                $check_label_data_count = count($check_label_data);
                            } else {
                                $check_label_data_count = 0;
                            }
                            if ($check_label_data_count < 3) {
                                if (!empty($key)) {
                                    $insert_data = $Employer_model->insert_commen('employer_label_data', $data_label);
                                } else {
                                    $insert_data = '';
                                }
                            }
                        }
                        if (!empty($check_label)) {
                            echo  csrf_hash() . '^' . 0;
                        } else {
                            echo  csrf_hash() . '^' . 1;
                        }

                        // }else{
                        // echo  csrf_hash() . '^' . 4;
                        // }


                    }
                }

                public function employer_select_label()
                {

                    $Employer_model = new Employer_model();
                    // print_r($candidate_id);exit();
                    $userModel = new LoginModel();
                    $current_datetime = $userModel->current_datetime();
                    $session = session();
                    $userid    =    $session->get('userid');
                    $usertype    =    $session->get('usertype');
                    $add_label_name = $this->request->getVar('add_label_name');
                    $add_label_name_color = $this->request->getVar('add_label_name_color');
                    $internship_id = $this->request->getVar('internship_id');
                    $candidate_id1 = $this->request->getVar('candidate_id');
                    $candidate_id = explode(",", $candidate_id1);
                    if ($usertype == 2) {
                        $company_id = $session->get('userid');
                    } else {
                        $where = array('userid' => $session->get('userid'));
                        $admin_profile = $Employer_model->fetch_table_row('emp_manage_admins', $where);
                        $company_id = $admin_profile->emp_user_id;
                    }
                    $where = array('employee_user_id' => $userid, 'internship_id' => $internship_id, 'label_name' => $add_label_name, 'status' => '1');
                    $check_label = $Employer_model->fetch_table_row('employer_label', $where);
                    $result = $check_label->id;
                    if ($result) {
                        foreach ($candidate_id as $key) {
                            $data_label = [
                                'candidate_id' => $key,
                                'label_id' => $result,
                                'employer_id' => $company_id,
                                'employee_user_id' => $userid,
                                'internship_id' => $internship_id,
                                'label_name' => $add_label_name,
                                'created_by' => $userid,
                                'created_at' => $current_datetime,

                            ];
                            $where = array('internship_id' => $internship_id, 'candidate_id' => $key, 'employee_user_id' => $userid, 'status' => '1');
                            $check_label_data = $Employer_model->fetch_table_data_for_all('employer_label_data', $where);
                            if (isset($check_label_data) && !empty($check_label_data)) {
                                $check_label_data_count = count($check_label_data);
                            } else {
                                $check_label_data_count = 0;
                            }
                            if ($check_label_data_count < 3) {
                                if (!empty($key)) {
                                    $insert_data = $Employer_model->insert_commen('employer_label_data', $data_label);
                                    echo  csrf_hash() . '^' . 1;
                                }
                            } else {
                                echo  csrf_hash() . '^' . 0;
                            }
                        }
                    }
                }

                public function employer_create_label_single()
                {

                    $Employer_model = new Employer_model();

                    $userModel = new LoginModel();
                    $current_datetime = $userModel->current_datetime();
                    $session = session();
                    $userid    =    $session->get('userid');
                    $usertype    =    $session->get('usertype');
                    $add_label_name = $this->request->getVar('add_label_name');
                    $add_label_name_color = $this->request->getVar('add_label_name_color');
                    $internship_id = $this->request->getVar('internship_id');
                    $candidate_id = $this->request->getVar('candidate_id');
                    // $candidate_id = explode(",", $candidate_id1);

                    if ($usertype == 2) {
                        $company_id = $session->get('userid');
                    } else {
                        $where = array('userid' => $session->get('userid'));
                        $admin_profile = $Employer_model->fetch_table_row('emp_manage_admins', $where);
                        $company_id = $admin_profile->emp_user_id;
                    }
                    $data = [
                        'employer_id' => $company_id,
                        'employee_user_id' => $userid,
                        'internship_id' => $internship_id,
                        'label_name' => $add_label_name,
                        'label_color' => $add_label_name_color,
                        'created_by' => $userid,
                        'status' => '1',
                        'created_at' => $current_datetime,
                    ];
                    $where = array('employee_user_id' => $userid, 'internship_id' => $internship_id, 'label_name' => $add_label_name, 'status' => '1');

                    $check_label = $Employer_model->fetch_table_row('employer_label', $where);

                    if (empty($check_label)) {
                        $result = $Employer_model->insert_commen('employer_label', $data);
                    } else {
                        $result = $check_label->id;
                    }
                    $where = array('candidate_id' => $candidate_id, 'employee_user_id' => $userid, 'label_id' => $result, 'status' => '1');
                    $check_label_data = $Employer_model->fetch_table_row('employer_label_data', $where);

                    if ($check_label_data) {
                        echo  csrf_hash() . '^' . 0 . '^' . 0 . '^' . 0;
                    } else {
                        if ($result) {
                            if (!empty($candidate_id)) {
                                // foreach ($candidate_id as $key) {
                                $data_label = [
                                    'candidate_id' => $candidate_id,
                                    'label_id' => $result,
                                    'employer_id' => $company_id,
                                    'employee_user_id' => $userid,
                                    'internship_id' => $internship_id,
                                    'label_name' => $add_label_name,
                                    'created_by' => $userid,
                                    'created_at' => $current_datetime,

                                ];

                                $insert_data = $Employer_model->insert_commen('employer_label_data', $data_label);
                                $where_res = array('id' => $result, 'status' => '1');
                                $check_label_res = $Employer_model->fetch_table_row('employer_label', $where_res);
                                echo  csrf_hash() . '^' . 1 . '^' . 1 . '^' . 1;
                                // echo  csrf_hash() . '^' . 1 . '^'. $check_label_res->label_name. '^'. $check_label_res->label_color;
                                // }
                            }
                        }
                    }
                }

                public function employer_select_label_single()
                {

                    $Employer_model = new Employer_model();

                    $userModel = new LoginModel();
                    $current_datetime = $userModel->current_datetime();
                    $session = session();
                    $userid    =    $session->get('userid');
                    $usertype    =    $session->get('usertype');
                    $add_label_name = $this->request->getVar('add_label_name');
                    $label_checked_status = $this->request->getVar('label_checked_status');
                    $add_label_name_color = $this->request->getVar('add_label_name_color');
                    $internship_id = $this->request->getVar('internship_id');
                    $candidate_id = $this->request->getVar('candidate_id');
                    // $candidate_id = explode(",", $candidate_id1);
                    $where_count = array('candidate_id' => $candidate_id, 'employee_user_id' => $userid, 'status' => '1', 'internship_id' => $internship_id);
                    $check_label_data_co = $Employer_model->fetch_table_data('employer_label_data', $where_count);

                    if (isset($check_label_data_co) && !empty($check_label_data_co)) {
                        $check_label_data_count = count($check_label_data_co);
                    } else {
                        $check_label_data_count = '0';
                    }


                    if ($usertype == 2) {
                        $company_id = $session->get('userid');
                    } else {
                        $where = array('userid' => $session->get('userid'));
                        $admin_profile = $Employer_model->fetch_table_row('emp_manage_admins', $where);
                        $company_id = $admin_profile->emp_user_id;
                    }
                    $where = array('employee_user_id' => $userid, 'internship_id' => $internship_id, 'label_name' => $add_label_name, 'status' => '1');

                    $check_label = $Employer_model->fetch_table_row('employer_label', $where);
                    $result = $check_label->id;
                    $where_lab = array('candidate_id' => $candidate_id, 'employee_user_id' => $userid, 'label_id' => $result);
                    $check_label_data = $Employer_model->fetch_table_row('employer_label_data', $where_lab);

                    // echo  csrf_hash() . '^' . 1 . '^'. 0 . '^'. 0  .'^'. 0;
                    if ($check_label_data) {

                        $data_label = [
                            'status' => $label_checked_status
                        ];

                        if ($check_label_data_count < 3) {

                            $where_select = array('candidate_id' => $candidate_id, 'employee_user_id' => $userid, 'label_id' => $result);
                            $update_data = $Employer_model->update_commen('employer_label_data', $where_select, $data_label);
                            $where_res = array('id' => $result, 'status' => '1');
                            $check_label_res = $Employer_model->fetch_table_row('employer_label', $where_res);

                            $where_count_up = array('candidate_id' => $candidate_id, 'employee_user_id' => $userid, 'status' => '1', 'internship_id' => $internship_id);
                            $check_label_data_co_update = $Employer_model->fetch_table_data('employer_label_data', $where_count_up);

                            if (isset($check_label_data_co_update) && !empty($check_label_data_co_update)) {
                                $check_label_data_count_update = count($check_label_data_co_update);
                            } else {
                                $check_label_data_count_update = '0';
                            }
                            if ($label_checked_status == '0') {
                                echo  csrf_hash() . '^' . 3 . '^' . $check_label_res->label_name . '^' . $check_label_res->label_color . '^' . $check_label_data_count_update . '^' . $check_label_res->id;
                            } else {
                                echo  csrf_hash() . '^' . 1 . '^' . $check_label_res->label_name . '^' . $check_label_res->label_color . '^' . $check_label_data_count_update . '^' . $check_label_res->id;
                            }
                        } else {
                            echo  csrf_hash() . '^' . 1 . '^' . 0 . '^' . 0  . '^' . 0 . '^' . 0;
                        }
                    } else {
                        if ($result) {

                            if (!empty($candidate_id)) {
                                // foreach ($candidate_id as $key) {
                                $data_label = [
                                    'candidate_id' => $candidate_id,
                                    'label_id' => $result,
                                    'employer_id' => $company_id,
                                    'employee_user_id' => $userid,
                                    'internship_id' => $internship_id,
                                    'label_name' => $add_label_name,
                                    'created_by' => $userid,
                                    'created_at' => $current_datetime,

                                ];
                                if ($check_label_data_count < 3) {
                                    $insert_data = $Employer_model->insert_commen('employer_label_data', $data_label);
                                    $where_count_up = array('candidate_id' => $candidate_id, 'employee_user_id' => $userid, 'status' => '1', 'internship_id' => $internship_id);
                                    $check_label_data_co_update = $Employer_model->fetch_table_data('employer_label_data', $where_count_up);

                                    if (isset($check_label_data_co_update) && !empty($check_label_data_co_update)) {
                                        $check_label_data_count_update = count($check_label_data_co_update);
                                    } else {
                                        $check_label_data_count_update = '0';
                                    }
                                    $where_res = array('id' => $result, 'status' => '1');
                                    $check_label_res = $Employer_model->fetch_table_row('employer_label', $where_res);
                                    echo  csrf_hash() . '^' . 1 . '^' . $check_label_res->label_name . '^' . $check_label_res->label_color . '^' . $check_label_data_count_update;
                                } else {
                                    echo  csrf_hash() . '^' . 1 . '^' . 0 . '^' . 0  . '^' . 0;
                                }
                                // }
                            }



                            //     // echo  csrf_hash() . '^' . 1;

                        }
                    }
                }

                public function delete_label_details()
                {

                    $Employer_model = new Employer_model();
                    $session = session();
                    $userid    =    $session->get('userid');
                    $usertype    =    $session->get('usertype');
                    $label_name = $this->request->getVar('label_name');
                    // $label_name_color = $this->request->getVar('label_name_color');
                    $internship_id = $this->request->getVar('internship_id');
                    $data = [
                        'status' => '0',
                    ];
                    $where = array('employee_user_id' => $userid, 'internship_id' => $internship_id, 'label_name' => $label_name, 'status' => '1');
                    $check_label = $Employer_model->fetch_table_row('employer_label', $where);
                    if (!empty($check_label)) {
                        $result = $Employer_model->update_commen('employer_label', $where, $data);
                        if ($result) {
                            $where_label = array('employee_user_id' => $userid, 'internship_id' => $internship_id, 'label_id' => $check_label->id, 'label_name' => $label_name, 'status' => '1');
                            $delete_data = $Employer_model->update_commen('employer_label_data', $where_label, $data);
                        }
                        echo  csrf_hash() . '^' . 1;
                        // $session->setFlashdata('error_status', '1');
                        // $session->setFlashdata('error_msg', 'Label Deleted successfully');
                    } else {
                        echo  csrf_hash() . '^' . 0;
                        // $session->setFlashdata('error_status', '1');
                        // $session->setFlashdata('error_msg', 'Failed Try Again !');
                    }
                }

                public function remove_label_candidate()
                {

                    $Employer_model = new Employer_model();
                    $session = session();
                    $userid    =    $session->get('userid');
                    $label_name = $this->request->getVar('label_name');
                    $label_id = $this->request->getVar('label_id');
                    $internship_id = $this->request->getVar('internship_id');
                    $candidate_id = $this->request->getVar('candidate_id');
                    $data = [
                        'status' => '0',
                    ];
                    $where_label = array('candidate_id' => $candidate_id, 'employee_user_id' => $userid, 'internship_id' => $internship_id, 'label_id' => $label_id, 'label_name' => $label_name, 'status' => '1');
                    $delete_data = $Employer_model->update_commen('employer_label_data', $where_label, $data);
                    if (!empty($delete_data)) {
                        echo  csrf_hash() . '^' . 1;
                    } else {
                        echo  csrf_hash() . '^' . 0;
                    }
                }




                public function get_unread_chat_user() //Function For Getting Messages Common (AJEX)
                {
                    $session = session();
                    $Employer_model = new Employer_model();
                    $userid1 = $session->get('userid');
                    $where31 = array('receiver_id' => $userid1, 'message_status' => '1');
                    // $group_by1 = array('ordercolumn' => 'receiver_id');
                    $order_by1 = array('ordercolumn' => 'id', 'ordertype' => 'desc');
                    $chat_chandidate_unread = $Employer_model->fetch_table_data_group_by_unread('chat', $where31, $order_by1);
                    if (!empty($chat_chandidate_unread)) {
                        //    echo "<pre>"; print_r($chat_chandidate[0]->receiver_id);
                        foreach ($chat_chandidate_unread as $candidate) {
                            $useridsess = $session->get('userid');
                            $userid = $candidate->sender_id;
                            $msg_status = $Employer_model->msg_status_unread('chat', $useridsess, $userid);


                            $where = array('status' => '1', 'userid' => $userid);
                            $can_name = $Employer_model->candidate_names('can_personal_details', $where);
                            $username = $can_name->profile_full_name;

                            $where4 = array('status' => '1', 'userid' => $userid);
                            $order_by = array('ordercolumn' => 'education_end_year', 'ordertype' => 'desc');
                            $education_details = $Employer_model->fetch_table_data_for_all_limit('can_education_details', $where4);
                            if ($education_details[0]->education_college_name != 0) {
                                $where1 = array('id' => $education_details[0]->education_college_name);
                                $education_college_name = $Employer_model->get_master_commen_for_all('master_college', $where1, 'college_name');
                            } else {
                                $education_college_name = $education_details[0]->education_college_name_other;
                            }

                            if (!empty($can_name->profile_full_name)) {
                                $firstStringCharacter = strtoupper(substr($can_name->profile_full_name, 0, 1));
                            }

        ?>
                <div class="chat-list">
                    <input type="hidden" class="receiver_id_all" value="<?= $userid ?>">
                    <a id="<?= $userid ?>" title="<?= $can_name->profile_full_name; ?>" logoname="<?= $firstStringCharacter; ?>" collegename="<?= $education_college_name; ?>" class="selectuser d-flex align-items-start position-relative px-2 py-3" onclick="unread('<?= $userid ?>','<?= $can_name->profile_full_name; ?>','<?= $firstStringCharacter; ?>','<?= $education_college_name; ?>');">
                        <div class="flex-shrink-0 bg-white rounded-50 border-blue text-blue fw-bold fs-6 ms-0 position-relative">
                            <span><?php echo $firstStringCharacter; ?></span>
                            <!-- <span class="active"></span> -->
                        </div>
                        <?php
                            // $useridsess    =    $session->get('userid');
                            // $msg_status = $Employer_model->msg_status('chat', $candidate->receiver_id, $useridsess); 
                        ?>
                        <div class="read flex-grow-1 ms-3">
                            <div class="d-flex flex-column">
                                <div class="d-flex justify-content-between">
                                    <h3 class="text-dark fw-medium overflow-anywhere mb-1"><?= $can_name->profile_full_name; ?></h3>

                                    <span class="badge  fw-normal unreadCount d-flex justify-content-center align-items-center align-self-start ms-2 count2" id="msg_status<?= $userid ?>">
                                        <?= $msg_status ?>
                                    </span>

                                </div>
                                <!-- <span class="chatOn text-muted f-11">10:30 pm</span> -->
                                <p class="f-12 lh-base mb-0"><?php echo $education_college_name; ?></p>
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
                public function roles_responsibility()
                {
                    return view('employer/roles_responsibility');
                }

                public function update_evaluated_status() //Function For accept interview
                {
                    $session = session();
                    $Employer_model = new Employer_model();
                    $id = $this->request->getVar('id');
                    $val = $this->request->getVar('val');

                    $where = array('id' => $id);
                    $data = [
                        'evaluated_status' => $val,
                    ];
                    $update_data = $Employer_model->update_commen('chat', $where, $data);
                    if ($update_data) {
                        echo csrf_hash() . '^' . '1';
                    } else {
                        echo csrf_hash() . '^' . '0';
                    }
                }
                public function update_block_user_emp() //Function For accept interview
                {
                    $session = session();
                    $Employer_model = new Employer_model();
                    $receiver_id = $this->request->getVar('receiver_id');
                    $val = $this->request->getVar('val');
                    $sender_id = $session->get('userid');
                    if ($val == '1') {
                        $data = [
                            'sender_id' => $session->get('userid'),
                            'receiver_id' => $receiver_id,

                        ];
                        $result = $Employer_model->insert_commen('chat_blocked_data', $data);
                    } else {
                        $where = array('sender_id' => $sender_id, 'receiver_id' => $receiver_id);
                        $result = $Employer_model->delete_commen('chat_blocked_data', $where);
                    }

                    if ($result) {
                        echo csrf_hash() . '^' . '1';
                    } else {
                        echo csrf_hash() . '^' . '0';
                    }
                }
                public function check_block_status() //Function For accept interview
                {
                    $session = session();

                    $Employer_model = new Employer_model();
                    $receiver_id = $this->request->getVar('receiver_id');
                    $sender_id = $session->get('userid');
                    $where = array('sender_id' => $sender_id, 'receiver_id' => $receiver_id);
                    $emp_block = $Employer_model->fetch_table_row('chat_blocked_data', $where);
                    $where1 = array('sender_id' => $receiver_id, 'receiver_id' => $sender_id);
                    $can_block = $Employer_model->fetch_table_row('chat_blocked_data', $where1);

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

                //                 public function update_location_emp_profile() //Function For accept interview
                //                 {
                //                     // $Employer_model = new Employer_model();
                //                     // $where = array();
                //                     // $location_data= $Employer_model->fetch_table_data('profile_completion_form', $where);
                //                     // if (!empty($location_data)) {
                //                     //     foreach ($location_data as $key) {

                //                             <input type="text" id="autocomplete" value="chennai">
                //                             <script src="https://maps.google.com/maps/api/js?key=AIzaSyC-OrXEcjcGO7-JvhwPS5MCPDcf2EfqDjs&libraries=places&callback=initMap" type="text/javascript"    ></script>
                //                         <script type="text/javascript">
                // // alert();
                // //set lag log here
                // const center = { lat: 39, lng: -112 };
                // //Create a bounding box with sides ~10km away from the center point
                // const defaultBounds = {
                //   north: center.lat + 0.001,
                //   south: center.lat - 0.001,
                //   east: center.lng + 0.001,
                //   west: center.lng - 0.001,
                // };
                // //get input value
                // const input = document.getElementById("autocomplete");
                // // const input = 'chennai';
                // const options = {
                //                    bounds: defaultBounds,
                //                   componentRestrictions: { country: "in" },//result wise country
                //                   fields: ["address_components", "geometry",  "place_id", "name"],  //output
                //                   types: ["locality"],  //showing result

                //                 };
                // const autocomplete = new google.maps.places.Autocomplete(input, options);
                // // alert(autocomplete.addListener());
                // autocomplete.addListener('place_changed', function() 
                //                                                    {
                //      const place = autocomplete.getPlace();
                //      alert(place.name);

                //                                                    });
                // // $.each(autocomplete.addListener(), function (key, value)
                // //      { 
                // //       alert(key)


                // //    });
                // // alert(autocomplete);
                // //if place choosed
                // // var auto= autocomplete.addListener('place_changed', function() 
                // //                                                    {
                // //      const place = autocomplete.getPlace();
                // //      alert(place.place_id);
                // //    return place.place_id;

                // //                                                     });


                // </script>
                //                             <?php
                //                     //         $data  = array('location_district' => '','location_state' => '');
                //                     //         $where = array('id' => $key->id);
                //                     //         $update_application_status = $Employer_model->update_commen('profile_completion_form', $where, $data);
                //                     //     }
                //                     // }



                //                 }



                public function func_session_post_internship($folder_id, $candidate_id)
                {
                    $session = session();
                    $ses_data = [
                        'func_session_post_internship_folder' => 1,
                        'func_session_post_internship_folder_id' => $folder_id,
                        'func_session_post_internship_shortlist_id' => $candidate_id,

                    ];
                    $session->set($ses_data);
                    return redirect()->to('post-internship');
                }

                public function save_search_filters() //Function For accept interview
                {
                    $session = session();
                    $Employer_model = new Employer_model();
                    $savesearch_name = $this->request->getVar('savesearch_name');
                    $current_datetime = $Employer_model->current_datetime();
                    $userid = $session->get('userid');
                    $filter_profile_district1 = $session->get('profile_district1');
                    $filter_education_skills1 = $session->get('education_skills1');
                    $filter_education_background1 = $session->get('education_background1');
                    $filter_education_specialization1 = $session->get('education_specialization1');
                    $filter_gender1 = $session->get('gender1');
                    $filter_education_college1 = $session->get('education_college1');
                    $preffered_location1 = $session->get('preffered_location1');
                    $graduation_year1 = $session->get('graduation_year1');

                    if (!empty($preffered_location1)) {
                        $filter_profile_preffered_location = implode(',', $preffered_location1);
                    } else {
                        $filter_profile_preffered_location = '';
                    }
                    if (!empty($graduation_year1)) {
                        $filter_profile_graduation_year = implode(',', $graduation_year1);
                    } else {
                        $filter_profile_graduation_year = '';
                    }
                    if (!empty($filter_profile_district1)) {
                        $filter_profile_district = implode(',', $filter_profile_district1);
                    } else {
                        $filter_profile_district = '';
                    }
                    if (!empty($filter_education_skills1)) {
                        $filter_education_skills = implode(',', $filter_education_skills1);
                    } else {
                        $filter_education_skills = '';
                    }
                    if (!empty($filter_education_background1)) {
                        $filter_education_background = implode(',', $filter_education_background1);
                    } else {
                        $filter_education_background = '';
                    }

                    if (!empty($filter_education_specialization1)) {
                        $filter_education_specialization = implode(',', $filter_education_specialization1);
                    } else {
                        $filter_education_specialization = '';
                    }

                    if (!empty($filter_education_college1)) {
                        $filter_education_college = implode(',', $filter_education_college1);
                    } else {
                        $filter_education_college = '';
                    }

                    if (!empty($filter_gender1)) {
                        $filter_gender = $filter_gender1;
                    } else {
                        $filter_gender = '';
                    }

                    $data = [
                        'user_id' => $userid,
                        'savesearch_name' => $savesearch_name,
                        'academic_background' => $filter_education_background,
                        'academic_specialization' => $filter_education_specialization,
                        'skills' => $filter_education_skills,
                        'location' => $filter_profile_district,
                        'gender' => $filter_gender,
                        'college' => $filter_education_college,
                        'preffered_location' => $filter_profile_preffered_location,
                        'graduation_year' => $filter_profile_graduation_year,
                        'created_at' => $current_datetime,
                    ];
                    $result = $Employer_model->insert_commen('emp_save_search', $data);
                    if ($result) {
                        $session->setFlashdata('error_status', '2');
                        $session->setFlashdata('error_msg', 'Search Saved successfully');
                        // session()->setTempdata('success', 'Organization details Updated successfully', 2);

                        return redirect()->to('search-candidates');
                    } else {
                        return redirect()->to('search-candidates');
                    }
                }
                public function save_search_set()
                {
                    $session = session();
                    $Employer_model = new Employer_model();
                    $saved_search = $this->request->getVar('saved_search');
                    $where1 = array('id' => $saved_search);
                    $fiter_data = $Employer_model->fetch_table_row('emp_save_search', $where1);


                    if (!empty($fiter_data->preffered_location)) {
                        $profile_preffered_location_arr = explode(',', $fiter_data->preffered_location);
                        $session->set('preffered_location1', $profile_preffered_location_arr);
                    } else {
                        $session->set('preffered_location1', '');
                    }
                    if (!empty($fiter_data->location)) {
                        $profile_district_arr = explode(',', $fiter_data->location);
                        $session->set('profile_district1', $profile_district_arr);
                    } else {
                        $session->set('profile_district1', '');
                    }
                    if (!empty($fiter_data->skills)) {
                        $education_skills_arr = explode(',', $fiter_data->skills);
                        $session->set('education_skills1', $education_skills_arr);
                    } else {
                        $session->set('education_skills1', '');
                    }
                    if (!empty($fiter_data->academic_background)) {
                        $education_background_arr = explode(',', $fiter_data->academic_background);
                        $session->set('education_background1', $education_background_arr);
                    } else {
                        $session->set('education_background1', '');
                    }

                    if (!empty($fiter_data->academic_specialization)) {
                        $education_specialization_arr = explode(',', $fiter_data->academic_specialization);
                        $session->set('education_specialization1', $education_specialization_arr);
                    } else {
                        $session->set('education_specialization1', '');
                    }

                    if (!empty($fiter_data->college)) {
                        $education_college_arr = explode(',', $fiter_data->college);
                        $session->set('education_college1', $education_college_arr);
                    } else {
                        $session->set('education_college1', '');
                    }

                    if (!empty($fiter_data->gender)) {
                        $session->set('gender1', $fiter_data->gender);
                    } else {
                        $session->set('gender1', '');
                    }
                    if (!empty($fiter_data->graduation_year)) {
                        $graduation_arr = explode(',', $fiter_data->graduation_year);
                        $session->set('graduation_year1', $graduation_arr);
                    } else {
                        $session->set('graduation_year1', '');
                    }
                    echo csrf_hash() . '^' . 1;
                }

                public function save_search_view()
                {
                    $session = session();
                    $Employer_model = new Employer_model();
                    $user_id = $session->get('userid');
                    $where1 = array('user_id' => $user_id);
                    $data['save_search_data'] = $Employer_model->fetch_table_data('emp_save_search', $where1);
                    // $data['state'] = $Employer_model->can_location_all();
                    // $data['preffered_location'] = $Employer_model->can_preffered_location_all();

                    // $data['skills'] = $Employer_model->can_skills_all();

                    // $data['master_college'] = $Employer_model->can_college_all();

                    // $data['master_academic_courses'] = $Employer_model->can_academin_background_all();
                    // $data['master_gender'] = $Employer_model->can_gender_all();
                    return view('employer/save_search_view', $data);
                }

                public function delete_saved_search($id)
                {

                    $session = session();
                    $Employer_model = new Employer_model();
                    $wheredel = array('id' => $id);
                    $result = $Employer_model->delete_commen('emp_save_search', $wheredel);
                    if ($result) {
                        $session->setFlashdata('error_msg', 'Saved Search Has Been Removed Successfully');
                        $session->setFlashdata('error_status', '1');
                        return redirect()->to('save-search-view');
                    } else {
                        return redirect()->to('save-search-view');
                    }
                }

                public function paid_internships()
                {
                    $session         = session();
                    $Employer_model = new Employer_model();
                        $pager = service('pager');
                        $page = (int) $this->request->getGet('page'); // 
                        $current_date =date("Y-m-d");

                        $order_by = array('ordercolumn' => 'id', 'ordertype' => 'desc');
                        $where_int = array('status' => 1,'view_status' => 1,'active_status'=>1,'premium_status'=>1,'internship_candidate_lastdate >=' => $current_date);
                        $limit = config('Pager')->perPage_emp_int; // see Config/Pager.php
                        if (!isset($page) || $page === 0 || $page === 1) {
                            $page = 1;
                            $start_id = 0;
                        } else {
                            $start_id = ($page - 1) * $limit;
                            $page = $page;
                        }

                        $internship_list_details = $Employer_model->fetch_single_employer_internship('employer_post_internship', $where_int, $order_by);
                        if (!empty($internship_list_details)) {
                            $total   = count($internship_list_details);
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
                        $data['internship_list'] = $Employer_model->fetch_single_employer_internship('employer_post_internship', $where_int, $order_by, $limit, $start_id);
                        return view('employer/paid_internships', $data);
                    
                }

                public function emp_session_display_for_power_bi()
                {
                    
                 return view('employer/emp_session_display_for_power_bi');
                  
                }
            }
