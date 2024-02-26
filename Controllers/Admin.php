<?php

namespace App\Controllers;

use App\Models\Employer_model;
use App\Models\LoginModel;
use App\Models\Common_model;

require_once(APPPATH . "Libraries/razorpay/razorpay-php/Razorpay.php");

use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;
use Razorpay\Api\Errors;
use Razorpay\Api\Request;
use GuzzleHttp\Client;
use App\Models\Candidate_model;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Supervisor;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$this->Candidate_model = new Candidate_model();


class Admin extends BaseController
{
    public function __construct()
    {
        date_default_timezone_set('Asia/Kolkata');
    }
    public function admin_dashboard()
    {
        $Common_model = new Common_model();
        $where1 = array();
        $data['total_candidate'] = $Common_model->data_count_fetch('can_personal_details', $where1);

        $where1 = array('date(created_at)' => date('Y-m-d'));
        $data['today_registered_candidate'] = $Common_model->data_count_fetch('can_personal_details', $where1);

        $where1 = array('date(created_at)' => date('Y-m-d'));
        $data['today_registered_employer'] = $Common_model->data_count_fetch('profile_completion_form', $where1);
        // echo $data['today_registered_candidate'];exit();
        // $where2 = array();
        $order_by = array('ordercolumn' => 'id', 'ordertype' => 'desc');
        $where_completed = array('can_education_details.status' => '1', 'can_profile_complete_status' => '1', 'date(can_personal_details.created_at)' => date('Y-m-d'));
        $data['today_profile_completed'] = $Common_model->candidate_completion_profile_count('can_personal_details', $where_completed);
        $order_by = array('ordercolumn' => 'can_personal_details.id', 'ordertype' => 'desc');
        //$where = array('can_education_details.status' => '1','g_location_id!='=>'');
        $where = array('can_education_details.status' => '1', 'can_personal_details.can_profile_complete_status' => '1');
        $data['list_internship_can'] = $Common_model->candidate_completion_profile('can_personal_details', $where, $order_by);
        //  echo count($data['list_internship_can']);exit;
        $data['total_candidate_completed'] = $Common_model->candidate_completion_profile_count('can_personal_details', $where, $order_by);


        $where3 = array();
        $data['posted_internship'] = $this->number_conversition($Common_model->data_count_fetch('employer_post_internship', $where3));
        $posted_internship = $Common_model->data_count_fetch('employer_post_internship', $where3);
        $where4 = array('active_status' => '1', 'internship_candidate_lastdate >=' => date('Y-m-d'));
        $data['posted_internship_open'] = $this->number_conversition($Common_model->data_count_fetch('employer_post_internship', $where4));
        $posted_internship_open = $Common_model->data_count_fetch('employer_post_internship', $where4);
        // $where5 = array('active_status' =>'0');

        $posted_internship_closed = $posted_internship - $posted_internship_open;
        $data['posted_internship_closed'] = $this->number_conversition($posted_internship_closed);
        $where6 = array();
        $data['employers'] = $Common_model->data_count_fetch('profile_completion_form', $where6);
        $no_of_employer_total = $data['employers'];
        $where_completed = array('completed_status' => '1');
        $data['profile_completed'] = $Common_model->data_count_fetch('profile_completion_form', $where_completed);
        // $emp_profile_completed=$data['profile_completed'];
        // $emp_profile_not_completed=$no_of_employer_total-$emp_profile_completed;

        //     $prev_date = date('Y-m-d', strtotime(' -15 day'));
        //     $today_date = date('Y-m-d');

        //     $where7 = array('date(updated_at) >='=>$prev_date,'date(updated_at) <='=>$today_date);
        //     $active_candidate = $Common_model->data_count_fetch('can_profile_log', $where7);
        //     $data['active_candidate'] = $Common_model->data_count_fetch('can_profile_log', $where7);

        // //    print_r($active_candidate);
        //     $where9 = array('usertype'=>'1');
        //     $total_candidate = $Common_model->data_count_fetch('userlogin', $where9);
        //     // print_r($total_candidate);
        //     $prev_date = date('Y-m-d', strtotime(' -15 day'));
        //     $today_date = date('Y-m-d');
        //      $where8 = array('date(logged_in) >='=>$prev_date,'date(logged_in) <='=>$today_date,'usertype'=>'1');
        //      $idle_candidate = $Common_model->data_count_fetch('userlogin', $where8);

        //      $idle_tot_candidate = $idle_candidate-$active_candidate;
        //     //  print_r($idle_tot_candidate);
        //      $data['idle_candidate'] = $idle_tot_candidate;
        //      $inactive_tot_candidate = $total_candidate-$active_candidate-$idle_tot_candidate;
        //     //  print_r($inactive_tot_candidate);
        //      $data['inactive_candidate'] = $inactive_tot_candidate;


        //      $where9 = array('date(created_at) >='=>$prev_date,'date(created_at) <='=>$today_date);
        //      $emp_post_data = $Common_model->emp_data_for_log('employer_post_internship', $where9,'company_id');
        //      $where10 = array('date(created_at) >='=>$prev_date,'date(created_at) <='=>$today_date);
        //      $emp_hiring_data = $Common_model->emp_data_for_log('emp_hiring_log', $where10,'company_id');
        //      $emp_array_uni=array_unique(array_merge($emp_post_data,$emp_hiring_data), SORT_REGULAR);
        //      $data['emp_active'] = count($emp_array_uni);
        //      $emp_active = count($emp_array_uni);
        //      $where11 = array('date(logged_in) >='=>$prev_date,'date(logged_in) <='=>$today_date,'usertype'=>'2');
        //      $emp_idle = $Common_model->data_count_fetch('userlogin', $where11);
        //      $emp_idle_emp = $emp_active-$emp_idle;
        //      $data['idle_emp'] = $emp_idle_emp;
        //      $where12 = array('usertype'=>'2');
        //      $total_emp = $Common_model->data_count_fetch('userlogin', $where12);
        //      $inactive_tot_emp = $total_emp-$emp_active-$emp_idle_emp;
        //      $data['inactive_emp'] = $inactive_tot_emp;

        // print_r($inactive_tot_emp);

        $prev_date = date('Y-m-d', strtotime(' -15 day'));
        $today_date = date('Y-m-d');

        // print_r($today_date);exit;
        $where7 = array('date(userlogin.logged_in) >=' => $prev_date, 'date(can_profile_log.updated_at) >=' => $prev_date, 'userlogin.usertype' => '1');
        // $data['list_internship_can'] = $Common_model->candidate_active_new('userlogin',$where7);

        if (!empty($data['list_internship_can'])) {
            $data['can_highly_active'] = $Common_model->candidate_active_new_count('userlogin', $where7);
        } else {
            $data['can_highly_active'] = 0;
        }
        // echo count($data['list_internship_can']);exit;
        $where8 = array('date(userlogin.logged_in) >=' => $prev_date, 'usertype' => '1', 'date(can_profile_log.updated_at) <' => $prev_date);
        // print_r($where);exit;
        // $data['list_internship_can'] = $Common_model->candidate_idle('userlogin',$where8);


        if (!empty($data['list_internship_can'])) {
            $data['idle'] = $Common_model->candidate_idle_count('userlogin', $where8);
        } else {
            $data['idle'] = 0;
        }


        $where8 = '(date(userlogin.logged_in) < "' . $prev_date . '" OR userlogin.logged_in IS NULL)';
        // $data['list_internship_can'] = $Common_model->candidate_inactive('userlogin',$where8);
        //    print_r($data['list_internship_can']);exit;
        if (!empty($data['list_internship_can'])) {
            $data['inactive'] = $Common_model->candidate_inactive_count('userlogin', $where8);
        } else {
            $data['inactive'] = 0;
        }

        $prev_date = date('Y-m-d', strtotime(' -15 day'));
        $today_date = date('Y-m-d');


        $where = '(date(employer_post_internship.created_at) > "' . $prev_date . '" OR date(employer_post_internship.updated_at) > "' . $prev_date . '" OR date(emp_hiring_log.created_at) > "' . $prev_date . '" OR date(emp_hiring_log.updated_at) > "' . $prev_date . '") AND userlogin.usertype = "2" AND  date(userlogin.logged_in)  >= "' . $prev_date . '"';



        $data['no_of_employers'] = $Common_model->employer_active('userlogin', $where);

        if (!empty($data['no_of_employers'])) {
            $data['emp_highly_active'] = count($data['no_of_employers']);
        } else {
            $data['emp_highly_active'] = 0;
        }
        $prev_date = date('Y-m-d', strtotime(' -15 day'));
        $today_date = date('Y-m-d');
        // $where = 'userlogin.usertype = "2" AND (date(userlogin.logged_in) >="'.$prev_date.'") AND (date(employer_post_internship.created_at) <= "'.$prev_date.'" OR date(employer_post_internship.updated_at) <= "'.$prev_date.'" OR date(emp_hiring_log.created_at) <= "'.$prev_date.'" OR date(emp_hiring_log.updated_at) <= "'.$prev_date.'")' ;
        // $data['no_of_employers'] = $Common_model->employer_report_idle('userlogin',$where);

        //     $where = '( date(userlogin.logged_in) < "'.$prev_date.'" OR userlogin`.`logged_in IS NULL )';
        //     $no_of_employers_inactive= $Common_model->employer_inactive('userlogin',$where8);
        //     $inactive_arr=array();
        //     if(!empty($no_of_employers_inactive)){
        //     foreach ($no_of_employers_inactive as $inactive) {
        //         $inactive_arr[]=$inactive->userid;
        //         // echo $inactive->userid;
        //     }
        //     }
        //     // print_r($inactive_arr);
        //     $where = '(date(employer_post_internship.created_at) > "'.$prev_date.'" OR date(employer_post_internship.updated_at) > "'.$prev_date.'" OR date(emp_hiring_log.created_at) > "'.$prev_date.'" OR date(emp_hiring_log.updated_at) > "'.$prev_date.'") AND userlogin.usertype = "2" AND  date(userlogin.logged_in)  >= "'.$prev_date.'"';

        //     $no_of_employers_active = $Common_model->employer_active('userlogin',$where);
        //     $active_arr=array();
        //     //   print_r($no_of_employers_active);exit;
        //   if(!empty($no_of_employers_active)){
        //     foreach ($no_of_employers_active as $active) {
        //         $active_arr[]=$active->userid;
        //         //  echo $active->userid;
        //     }
        //     }
        // //     //   print_r($inactive_arr);

        //      $idle_arr=array_merge($inactive_arr,$active_arr);
        //      $idle_unique_id=array_unique($idle_arr);





        //       $data['no_of_employers1'] = $Common_model->employer_report_idle_new('profile_completion_form',$idle_unique_id);
        // $where = '(date(employer_post_internship.created_at) < "'.$prev_date.'" AND date(employer_post_internship.updated_at) < "'.$prev_date.'" AND date(emp_hiring_log.created_at) < "'.$prev_date.'" AND date(emp_hiring_log.updated_at) < "'.$prev_date.'") AND userlogin.usertype = "2" AND  date(userlogin.logged_in)  > "'.$prev_date.'"';

        $data['no_of_employers1'] = $Common_model->employer_report_idle('userlogin', $prev_date);

        if (!empty($data['no_of_employers1'])) {
            $data['no_of_employers'] = $data['no_of_employers1'];
        } else {
            $data['no_of_employers'] = 0;
        }


        if (!empty($data['no_of_employers'])) {
            $data['emp_idle'] = count($data['no_of_employers']);
        } else {
            $data['emp_idle'] = 0;
        }

        $prev_date = date('Y-m-d', strtotime(' -15 day'));
        $where = '( date(userlogin.logged_in) < "' . $prev_date . '" OR userlogin`.`logged_in IS NULL )';

        $data['no_of_employers'] = $Common_model->employer_inactive('userlogin', $where);
        if (!empty($data['no_of_employers'])) {
            $data['emp_inactive'] = count($data['no_of_employers']);
        } else {
            $data['emp_inactive'] = 0;
        }


        $data['master_college_filter_value'] = $Common_model->can_college_all_value();

        if (!empty($data['master_college_filter_value'])) {
            $data['master_college_details_count']  = count($data['master_college_filter_value']);
        } else {
            $data['master_college_details_count'] = 0;
        }

        $data['master_state_filter'] = $Common_model->can_state_all();

        if (!empty($data['master_state_filter'])) {
            $data['master_state_count']  = count($data['master_state_filter']);
        } else {
            $data['master_state_count'] = 0;
        }


        return view('admin/admin_dashboard', $data);
    }

    public function bi_dashboard()
    {
        return view('admin/bi_dashboard');
    }

    public function dashboard_register_candidate($type)
    {
        $Common_model = new Common_model();
        $data['type'] = 1;

        $college_filter_form = $this->request->getVar('college_filter_form');
        if (!empty($_POST) && !empty($college_filter_form)) {

            $start_date = $this->request->getVar('college_filter_start_date');
            $end_date = $this->request->getVar('college_filter_end_date');
            $college_list = $this->request->getVar('college_filter_college_id[]');
            $college_details_list = '';
            if (!empty($college_list)) {
                $i = 1;
                foreach ($college_list as $key) {
                    if ($i == 1) {
                        $college_details_list = $college_details_list . "'" . $key . "'";
                    } else {
                        $college_details_list = $college_details_list . ",'" . $key . "'";
                    }

                    $i++;
                }
            } else {
                $college_list = array();
            }
            $data['start_date_selected'] = $start_date;
            $data['end_date_selected'] = $end_date;
            $data['college_id_selected'] = $college_list;
            $data['list_internship_can'] = $Common_model->get_college_list_by_filter($start_date, $end_date, $college_details_list);
            $data['list_internship_can_all_filter'] = $Common_model->get_college_list_by_filter_all($start_date, $end_date, $college_details_list);
            $data['list_internship_candidate'] = $Common_model->get_college_list_by_filter_count($start_date, $end_date, $college_details_list);
            if (!empty($data['list_internship_candidate'])) {
                $data['list_internship_can_count']  = count($data['list_internship_candidate']);
            } else {
                $data['list_internship_can_count'] = 0;
            }
        } else {
            $data['start_date_selected'] = '';
            $data['end_date_selected'] = '';
            $data['college_id_selected'] = array();

            $order_by = array('ordercolumn' => 'id', 'ordertype' => 'desc');
            $where = array('status' => '1');
            $data['list_internship_can'] = $Common_model->dashboard_registration_details('can_personal_details', $where, $order_by);
            $data['list_internship_can_count']  = '';
            // if(!empty($data['list_internship_can'])){
            //     $data['list_internship_can_count']  = count($data['list_internship_can']);
            //  }else{
            //     $data['list_internship_can_count'] =0;
            //  }

        }
        // exit;

        $where1 = array('status' => '1');
        // $data['master_college'] = $Common_model->fetch_table_data_for_all('master_college', $where1); 

        $where = array('status' => '1');
        $all_candidate = $Common_model->dashboard_registration_details('can_personal_details', $where);

        $folder_can_id = array();
        $folder_can_id_arr = '';
        if (isset($all_candidate) && !empty($all_candidate)) {
            foreach ($all_candidate as $can_data) {
                $folder_can_id[] = $can_data->userid;
            }
            $folder_can_id_arr = implode(',', $folder_can_id);
        }
        // print_r($folder_can_id_arr);
        // exit();
        $data['master_college_filter'] = $Common_model->can_college_all();

        $where1 = array('date(created_at)' => date('Y-m-d'));
        $data['today_registered_candidate'] = $Common_model->data_count_fetch('can_personal_details', $where1);
        $where_count = array('status' => '1');
        $data['list_can_count'] = $Common_model->fetch_table_data_for_all_count('can_personal_details', $where_count);
        if (!empty($data['list_can_count'])) {
            $data['list_internship_can_count_total']  = $data['list_can_count'];
        } else {
            $data['list_internship_can_count_total'] = 0;
        }

        //   $where_all = array('status' => '1'); 
        //   $data['list_internship_can_all'] = $Common_model->fetch_table_data_for_all('can_personal_details', $where_all);
        $order_by = array('ordercolumn' => 'id', 'ordertype' => 'desc');
        $where = array('can_education_details.status' => '1');
        // $data['list_internship_can_all'] = $Common_model->candidate_completion_profile_total('can_personal_details', $where,$order_by);
        $data['total_applications'] = $Common_model->get_application_details();


        // print_r($data['master_college_filter']);
        // exit();
        return view('admin/dashboard_report_details_list', $data);
    }
    public function registered_candidate_internship_hired($type)
    {
        $Common_model = new Common_model();
        $data['type'] = 1;
        $data['typen'] = $type;
        $college_filter_form = $this->request->getVar('college_filter_form');
        if (!empty($_POST) && !empty($college_filter_form)) {

            $start_date = $this->request->getVar('college_filter_start_date');
            $end_date = $this->request->getVar('college_filter_end_date');
            $college_list = $this->request->getVar('college_filter_college_id[]');
            $college_details_list = '';
            if (!empty($college_list)) {
                $i = 1;
                foreach ($college_list as $key) {
                    if ($i == 1) {
                        $college_details_list = $college_details_list . "'" . $key . "'";
                    } else {
                        $college_details_list = $college_details_list . ",'" . $key . "'";
                    }

                    $i++;
                }
            } else {
                $college_list = array();
            }
            $data['start_date_selected'] = $start_date;
            $data['end_date_selected'] = $end_date;
            $data['college_id_selected'] = $college_list;
            $where = array();
            $data['list_internship_can'] = $Common_model->get_internship_hired_list_by_filter($start_date, $end_date, $college_details_list, $where, $type);
            $data['list_internship_can_all_filter'] = $Common_model->get_internship_hired_list_by_filter_all($start_date, $end_date, $college_details_list);



            $data['list_internship_candidate'] = $Common_model->get_college_list_by_filter_count($start_date, $end_date, $college_details_list);
            if (!empty($data['list_internship_candidate'])) {
                $data['list_internship_can_count']  = count($data['list_internship_candidate']);
            } else {
                $data['list_internship_can_count'] = 0;
            }
        } else {
            $data['start_date_selected'] = '';
            $data['end_date_selected'] = '';
            $data['college_id_selected'] = array();


            $order_by = array('ordercolumn' => 'can_personal_details.id', 'ordertype' => 'desc');
            $where = array('can_personal_details.status' => '1');
            if ($type == 3) {
                $data['list_internship_can'] = $Common_model->dashboard_registration_hired_details('can_personal_details', $where, $order_by);
            } else {

                $data['list_internship_can'] = $Common_model->dashboard_registration_intenship_completed_details('can_personal_details', $where, $order_by);
            }

            $data['list_internship_can_count']  = '';
            // if(!empty($data['list_internship_can'])){
            //     $data['list_internship_can_count']  = count($data['list_internship_can']);
            //  }else{
            //     $data['list_internship_can_count'] =0;
            //  }

        }
        // exit;

        $where1 = array('status' => '1');
        // $data['master_college'] = $Common_model->fetch_table_data_for_all('master_college', $where1); 

        $where = array('status' => '1');
        $all_candidate = $Common_model->dashboard_registration_details('can_personal_details', $where);

        $folder_can_id = array();
        $folder_can_id_arr = '';
        if (isset($all_candidate) && !empty($all_candidate)) {
            foreach ($all_candidate as $can_data) {
                $folder_can_id[] = $can_data->userid;
            }
            $folder_can_id_arr = implode(',', $folder_can_id);
        }
        // print_r($folder_can_id_arr);
        // exit();
        $data['master_college_filter'] = $Common_model->can_college_all();

        $where1 = array('date(created_at)' => date('Y-m-d'));
        $data['today_registered_candidate'] = $Common_model->data_count_fetch('can_personal_details', $where1);
        $where_count = array('status' => '1');
        $data['list_can_count'] = $Common_model->fetch_table_data_for_all_count('can_personal_details', $where_count);
        /*if(!empty($data['list_can_count'])){
                 $data['list_internship_can_count_total']  = $data['list_can_count'];
              }else{
                 $data['list_internship_can_count_total'] =0;
              }
              */

        if ($type == 3) {
            $order_by = array('ordercolumn' => 'can_personal_details.id', 'ordertype' => 'desc');
            $where = array('can_personal_details.status' => '1');
            $data['list_internship_can_count_total'] = $Common_model->dashboard_registration_hired_details('can_personal_details', $where, $order_by);
        } else {
            $order_by = array('ordercolumn' => 'can_personal_details.id', 'ordertype' => 'desc');
            $where = array('can_personal_details.status' => '1');
            $data['list_internship_can_count_total'] = $Common_model->dashboard_registration_intenship_completed_details('can_personal_details', $where, $order_by);
        }




        //   $where_all = array('status' => '1'); 
        //   $data['list_internship_can_all'] = $Common_model->fetch_table_data_for_all('can_personal_details', $where_all);
        $order_by = array('ordercolumn' => 'id', 'ordertype' => 'desc');
        $where = array('can_education_details.status' => '1');
        // $data['list_internship_can_all'] = $Common_model->candidate_completion_profile_total('can_personal_details', $where,$order_by);
        $data['total_applications'] = $Common_model->get_application_details();


        // print_r($data['master_college_filter']);
        // exit();
        return view('admin/admin_registered_internship_hired_details', $data);
    }

    public function dashboard_candidate_profile_completion($type)
    {
        $Common_model = new Common_model();
        $data['type'] = 2;
        $college_filter_form = $this->request->getVar('college_filter_form');
        if (!empty($_POST) && !empty($college_filter_form)) {

            $start_date = $this->request->getVar('college_filter_start_date');
            $end_date = $this->request->getVar('college_filter_end_date');
            $college_list = $this->request->getVar('college_filter_college_id[]');
            //    print_r($college_list);exit;
            $state_list = $this->request->getVar('college_filter_state_id');
            //print_r($state_list);exit;
            if (!empty($state_list)) {
                $data['college_filter_state_val'] = $state_list;
                if (!empty($college_list[0])) {
                    $college_list = $college_list;
                } else {
                    $college_list = array();
                }

                $where = array('status' => '1', 'state_id' => $state_list);
                $master_college_filter = $Common_model->fetch_table_data_for_all('master_college', $where);
                // print_r($master_college_filter);exit;
                $college_state_list = array();
                foreach ($master_college_filter as $college) {
                    $college_state_list[] = $college->id;
                }
                if (!empty($college_list[0])) {
                    $college_details_list = '';
                    if (!empty($college_list)) {
                        $i = 1;
                        foreach ($college_list as $key) {
                            if ($i == 1) {
                                $college_details_list = $college_details_list . "'" . $key . "'";
                            } else {
                                $college_details_list = $college_details_list . ",'" . $key . "'";
                            }

                            $i++;
                        }
                    } else {
                        $college_list = array();
                    }
                } else {
                    $college_details_list = '';
                    if (!empty($college_state_list)) {
                        $i = 1;
                        foreach ($college_state_list as $key) {
                            if ($i == 1) {
                                $college_details_list = $college_details_list . "'" . $key . "'";
                            } else {
                                $college_details_list = $college_details_list . ",'" . $key . "'";
                            }

                            $i++;
                        }
                    } else {
                        $college_state_list = array();
                        if (!empty($college_list[0])) {
                            $college_list = $college_list;
                        } else {
                            $college_list = array();
                        }
                    }
                }
                // print_r($college_state_list);exit;
            } else {
                $data['college_filter_state_val'] = '';
                $college_details_list = '';
                if (!empty($college_list)) {
                    $i = 1;
                    foreach ($college_list as $key) {
                        if ($i == 1) {
                            $college_details_list = $college_details_list . "'" . $key . "'";
                        } else {
                            $college_details_list = $college_details_list . ",'" . $key . "'";
                        }

                        $i++;
                    }
                } else {
                    $college_list = array();
                }
            }
            //    print_r($college_details_list);exit;
            $where = array('can_profile_complete_status' => '1');
            $data['start_date_selected'] = $start_date;
            $data['end_date_selected'] = $end_date;
            $data['college_id_selected'] = $college_list;
            $data['list_internship_can'] = $Common_model->get_college_list_by_filter_complete($start_date, $end_date, $college_details_list, $where);
            $data['list_internship_can_all_filter'] = $Common_model->get_college_list_by_filter_all($start_date, $end_date, $college_details_list, $where);
            $data['list_internship_candidate'] = $Common_model->get_college_list_by_filter_complete_count($start_date, $end_date, $college_details_list, $where);
            if (!empty($data['list_internship_candidate'])) {
                $data['list_internship_can_count']  = count($data['list_internship_candidate']);
            } else {
                $data['list_internship_can_count'] = 0;
            }
        } else {
            $data['start_date_selected'] = '';
            $data['end_date_selected'] = '';

            $data['college_id_selected'] = array();
            $data['college_filter_state_val'] = array();
            $order_by = array('ordercolumn' => 'can_personal_details.id', 'ordertype' => 'desc');
            // $where = array('can_education_details.status' => '1','g_location_id!='=>'');

            $where = array('can_education_details.status' => '1', 'can_personal_details.can_profile_complete_status' => '1');
            $data['list_internship_can'] = $Common_model->candidate_completion_profile('can_personal_details', $where, $order_by);
            // if(!empty($data['list_internship_can'])){
            //     $data['list_internship_can_pro_count']  = count($data['list_internship_can']);
            //  }else{
            //     $data['list_internship_can_pro_count'] =0;
            //  }
            $data['list_internship_can_count']  = '';
        }

        $folder_can_id = array();
        //    $folder_can_id_arr = '';
        if (empty($college_list[0])) {
            if (isset($data['list_internship_can']) && !empty($data['list_internship_can'])) {
                foreach ($data['list_internship_can'] as $can_data) {
                    $folder_can_id[] = $can_data->userid;
                }
                //    $folder_can_id_arr = implode(',', $folder_can_id);
            }
        }
        //    print_r($folder_can_id_arr);
        //    exit;
        $data['master_college_filter'] = $Common_model->can_college_all($folder_can_id);
        //    $where_completed = array('can_education_details.status' => '1','can_profile_complete_status'=>'1');
        //     $data['today_profile_completed'] = $Common_model->data_count_fetch('can_personal_details', $where_completed);
        $where_completed = array('can_education_details.status' => '1', 'can_profile_complete_status' => '1', 'date(can_personal_details.created_at)' => date('Y-m-d'));
        $data['today_profile_completed'] = $Common_model->candidate_completion_profile_count('can_personal_details', $where_completed);
        $where_count = array('can_education_details.status' => '1', 'can_profile_complete_status' => '1');

        $data['list_can_count'] = $Common_model->candidate_completion_profile_count('can_personal_details', $where_count);
        if (!empty($data['list_can_count'])) {
            $data['list_internship_can_count_total']  = $data['list_can_count'];
        } else {
            $data['list_internship_can_count_total'] = 0;
        }
        $order_by = array('ordercolumn' => 'id', 'ordertype' => 'desc');
        $where = array('can_education_details.status' => '1', 'can_personal_details.can_profile_complete_status' => '1');
        // $data['list_internship_can_all'] = $Common_model->candidate_completion_profile_total('can_personal_details', $where,$order_by);
        $data['total_applications'] = $Common_model->get_application_details();
        $data['master_state_filter'] = $Common_model->can_state_all();

        return view('admin/dashboard_report_details_list', $data);
    }
    public function completed_candidate_internship_hired($type)
    {
        $Common_model = new Common_model();
        $data['type'] = 2;
        $data['typen'] = $type;

        $college_filter_form = $this->request->getVar('college_filter_form');
        if (!empty($_POST) && !empty($college_filter_form)) {

            $start_date = $this->request->getVar('college_filter_start_date');
            $end_date = $this->request->getVar('college_filter_end_date');
            $college_list = $this->request->getVar('college_filter_college_id[]');
            //    print_r($college_list);exit;
            $state_list = $this->request->getVar('college_filter_state_id');
            //print_r($state_list);exit;
            if (!empty($state_list)) {
                $data['college_filter_state_val'] = $state_list;
                if (!empty($college_list[0])) {
                    $college_list = $college_list;
                } else {
                    $college_list = array();
                }

                $where = array('status' => '1', 'state_id' => $state_list);
                $master_college_filter = $Common_model->fetch_table_data_for_all('master_college', $where);
                // print_r($master_college_filter);exit;
                $college_state_list = array();
                foreach ($master_college_filter as $college) {
                    $college_state_list[] = $college->id;
                }
                if (!empty($college_list[0])) {
                    $college_details_list = '';
                    if (!empty($college_list)) {
                        $i = 1;
                        foreach ($college_list as $key) {
                            if ($i == 1) {
                                $college_details_list = $college_details_list . "'" . $key . "'";
                            } else {
                                $college_details_list = $college_details_list . ",'" . $key . "'";
                            }

                            $i++;
                        }
                    } else {
                        $college_list = array();
                    }
                } else {
                    $college_details_list = '';
                    if (!empty($college_state_list)) {
                        $i = 1;
                        foreach ($college_state_list as $key) {
                            if ($i == 1) {
                                $college_details_list = $college_details_list . "'" . $key . "'";
                            } else {
                                $college_details_list = $college_details_list . ",'" . $key . "'";
                            }

                            $i++;
                        }
                    } else {
                        $college_state_list = array();
                        if (!empty($college_list[0])) {
                            $college_list = $college_list;
                        } else {
                            $college_list = array();
                        }
                    }
                }
            } else {
                $data['college_filter_state_val'] = '';
                $college_details_list = '';
                if (!empty($college_list)) {
                    $i = 1;
                    foreach ($college_list as $key) {
                        if ($i == 1) {
                            $college_details_list = $college_details_list . "'" . $key . "'";
                        } else {
                            $college_details_list = $college_details_list . ",'" . $key . "'";
                        }

                        $i++;
                    }
                } else {
                    $college_list = array();
                }
            }
            //    print_r($college_details_list);exit;
            $where = array('can_profile_complete_status' => '1');
            $data['start_date_selected'] = $start_date;
            $data['end_date_selected'] = $end_date;
            $data['college_id_selected'] = $college_list;

            $data['list_internship_can'] = $Common_model->get_internship_hired_list_by_filter_complete($start_date, $end_date, $college_details_list, $where, $type);
            $data['list_internship_can_all_filter'] = $Common_model->get_internship_hired_list_by_filter_complete_all($start_date, $end_date, $college_details_list, $where);
            $data['list_internship_candidate'] = $Common_model->get_college_list_by_filter_complete_count($start_date, $end_date, $college_details_list, $where);
            if (!empty($data['list_internship_candidate'])) {
                $data['list_internship_can_count']  = count($data['list_internship_candidate']);
            } else {
                $data['list_internship_can_count'] = 0;
            }
        } else {
            $data['start_date_selected'] = '';
            $data['end_date_selected'] = '';

            $data['college_id_selected'] = array();
            $data['college_filter_state_val'] = array();
            $order_by = array('ordercolumn' => 'can_personal_details.id', 'ordertype' => 'desc');
            // $where = array('can_education_details.status' => '1','g_location_id!='=>'');

            $where = array('can_education_details.status' => '1', 'can_personal_details.can_profile_complete_status' => '1');
            if ($type == 3) {
                $data['list_internship_can'] = $Common_model->candidate_hired_completion_profile('can_personal_details', $where, $order_by);
            } else {
                $data['list_internship_can'] = $Common_model->candidate_intenship_completed_details('can_personal_details', $where, $order_by);
            }



            // if(!empty($data['list_internship_can'])){
            //     $data['list_internship_can_pro_count']  = count($data['list_internship_can']);
            //  }else{
            //     $data['list_internship_can_pro_count'] =0;
            //  }
            $data['list_internship_can_count']  = '';
        }

        $order_by = array('ordercolumn' => 'can_personal_details.id', 'ordertype' => 'desc');
        // $where = array('can_education_details.status' => '1','g_location_id!='=>'');

        $where = array('can_education_details.status' => '1', 'can_personal_details.can_profile_complete_status' => '1');
        if ($type == 3) {
            $data['list_internship_can_count_total'] = count($Common_model->candidate_hired_completion_profile('can_personal_details', $where, $order_by));
        } else {
            $data['list_internship_can_count_total'] = count($Common_model->candidate_intenship_completed_details('can_personal_details', $where, $order_by));
        }




        $folder_can_id = array();
        //    $folder_can_id_arr = '';
        if (empty($college_list[0])) {
            if (isset($data['list_internship_can']) && !empty($data['list_internship_can'])) {
                foreach ($data['list_internship_can'] as $can_data) {
                    $folder_can_id[] = $can_data->userid;
                }
                //    $folder_can_id_arr = implode(',', $folder_can_id);
            }
        }
        //    print_r($folder_can_id_arr);
        //    exit;
        $data['master_college_filter'] = $Common_model->can_college_all($folder_can_id);
        //    $where_completed = array('can_education_details.status' => '1','can_profile_complete_status'=>'1');
        //     $data['today_profile_completed'] = $Common_model->data_count_fetch('can_personal_details', $where_completed);
        $where_completed = array('can_education_details.status' => '1', 'can_profile_complete_status' => '1', 'date(can_personal_details.created_at)' => date('Y-m-d'));
        $data['today_profile_completed'] = $Common_model->candidate_completion_profile_count('can_personal_details', $where_completed);
        $where_count = array('can_education_details.status' => '1', 'can_profile_complete_status' => '1');

        $data['list_can_count'] = $Common_model->candidate_completion_profile_count('can_personal_details', $where_count);
        //if(!empty($data['list_can_count'])){
        //     $data['list_internship_can_count_total']  = $data['list_can_count'];
        // }else{
        //  $data['list_internship_can_count_total'] =0;
        //}
        $order_by = array('ordercolumn' => 'id', 'ordertype' => 'desc');
        $where = array('can_education_details.status' => '1', 'can_personal_details.can_profile_complete_status' => '1');
        // $data['list_internship_can_all'] = $Common_model->candidate_completion_profile_total('can_personal_details', $where,$order_by);
        $data['total_applications'] = $Common_model->get_application_details();
        $data['master_state_filter'] = $Common_model->can_state_all();

        return view('admin/admin_completed_internship_hired_details', $data);
    }
    public function dashboard_post_internship($type, $emp_id = NULL)
    {
        $Common_model = new Common_model();
        $data['type'] = 3;
        $order_by = array('ordercolumn' => 'id', 'ordertype' => 'desc');
        $where = array('status' => '1');
        $data['post_intership_all'] = $Common_model->fetch_table_data_for_all('employer_post_internship', $where, $order_by);
        $data['post_intership_count_all']  = $Common_model->fetch_table_data_for_all_count('employer_post_internship', $where);
        if (!empty($data['post_intership'])) {
            $data['post_intership_count']  = $Common_model->fetch_table_data_for_all_count('employer_post_internship', $where);
        } else {
            $data['post_intership_count'] = 0;
        }
        $where3 = array();
        $data['posted_internship'] = $this->number_conversition($Common_model->data_count_fetch('employer_post_internship', $where3));
        $posted_internship = $Common_model->data_count_fetch('employer_post_internship', $where3);
        $where4 = array('active_status' => '1', 'internship_candidate_lastdate >=' => date('Y-m-d'));
        $data['posted_internship_open'] = $this->number_conversition($Common_model->data_count_fetch('employer_post_internship', $where4));
        $posted_internship_open = $Common_model->data_count_fetch('employer_post_internship', $where4);
        // $where5 = array('active_status' =>'0');

        $posted_internship_closed = $posted_internship - $posted_internship_open;
        $data['posted_internship_closed'] = $this->number_conversition($posted_internship_closed);
        $where = array('status' => '1');

        $company_filter_form = $this->request->getVar('company_filter_form');
        $order_by = array('ordercolumn' => 'id', 'ordertype' => 'desc');
        $data['company_name'] = $Common_model->fetch_table_data_for_all_groupby('profile_completion_form', $where, $order_by);

        if ((!empty($_POST) && !empty($company_filter_form)) || !empty($emp_id)) {
            $company_list = $this->request->getVar('company_filter_college_id[]');

            $where = array('status' => '1');
            // print_r($company_list);exit;
            if (!empty($emp_id)) {
                $data['company_id_selected'] = array($emp_id);
                $company_list = array($emp_id);
            } else {
                $data['company_id_selected'] = $company_list;
            }

            $order_by = array('ordercolumn' => 'id', 'ordertype' => 'desc');
            $data['post_intership'] = $Common_model->get_company_list_by_filter($company_list, $where);
            if (!empty($data['post_intership'])) {
                $data['post_intership_count']  = count($data['post_intership']);
            } else {
                $data['post_intership_count'] = 0;
            }
        } else {
            $order_by = array('ordercolumn' => 'id', 'ordertype' => 'desc');
            $where = array('status' => '1');
            $data['post_intership'] = $Common_model->fetch_table_data_for_all('employer_post_internship', $where, $order_by);


            $data['company_id_selected'] = array();
            $data['post_intership_count'] = '';
        }
        return view('admin/dashboard_report_details_list', $data);
    }
    public function dashboard_no_of_employer($type)
    {

        $Common_model = new Common_model();
        $data['type'] = 4;
        $order_by = array('ordercolumn' => 'id', 'ordertype' => 'desc');
        $where = array('status' => '1');
        $data['no_of_employers'] = $Common_model->fetch_table_data_for_all('profile_completion_form', $where, $order_by);
        if (!empty($data['no_of_employers'])) {
            $data['no_of_employers_count']  = count($data['no_of_employers']);
        } else {
            $data['no_of_employers_count'] = 0;
        }
        $where1 = array('date(created_at)' => date('Y-m-d'));
        $data['today_registered_employer'] = $Common_model->data_count_fetch('profile_completion_form', $where1);

        return view('admin/dashboard_report_details_list', $data);
    }
    public function dashboard_candidates_active($type)
    {

        $Common_model = new Common_model();
        $data['type'] = 5;
        $prev_date = date('Y-m-d', strtotime(' -15 day'));
        $college_filter_form = $this->request->getVar('college_filter_form');
        if (!empty($_POST) && !empty($college_filter_form)) {

            $start_date = $this->request->getVar('college_filter_start_date');
            $end_date = $this->request->getVar('college_filter_end_date');
            $college_list = $this->request->getVar('college_filter_college_id[]');
            $college_details_list = '';
            if (!empty($college_list)) {
                $i = 1;
                foreach ($college_list as $key) {
                    if ($i == 1) {
                        $college_details_list = $college_details_list . "'" . $key . "'";
                    } else {
                        $college_details_list = $college_details_list . ",'" . $key . "'";
                    }

                    $i++;
                }
            } else {
                $college_list = array();
            }
            //    $prev_date = date('Y-m-d', strtotime(' -15 day'));
            $where = array('date(userlogin.logged_in) >=' => $prev_date, 'date(can_profile_log.updated_at) >=' => $prev_date, 'userlogin.usertype' => '1');
            $data['start_date_selected'] = $start_date;
            $data['end_date_selected'] = $end_date;
            $data['college_id_selected'] = $college_list;
            $data['list_internship_can'] = $Common_model->get_college_list_by_filter($start_date, $end_date, $college_details_list, $where);
            if (!empty($data['list_internship_can'])) {
                $data['list_internship_can_count']  = count($data['list_internship_can']);
            } else {
                $data['list_internship_can_count'] = 0;
            }
        } else {
            $data['start_date_selected'] = '';
            $data['end_date_selected'] = '';
            $data['college_id_selected'] = array();

            //    $prev_date = date('Y-m-d', strtotime(' -15 day'));
            $where7 = array('date(userlogin.logged_in) >=' => $prev_date, 'date(can_profile_log.updated_at) >=' => $prev_date, 'userlogin.usertype' => '1');

            $data['list_internship_can'] = $Common_model->candidate_active_new('userlogin', $where7);
            $data['list_internship_can_count'] = '';
        }
        $folder_can_id = array();
        //    $folder_can_id_arr = '';
        if (isset($data['list_internship_can']) && !empty($data['list_internship_can'])) {
            foreach ($data['list_internship_can'] as $can_data) {
                $folder_can_id[] = $can_data->userid;
            }
            //    $folder_can_id_arr = implode(',', $folder_can_id);
            $data['master_college_filter'] = $Common_model->can_college_all($folder_can_id);
        } else {
            $data['master_college_filter'] = '';
        }

        $where_count = array('date(userlogin.logged_in) >=' => $prev_date, 'date(can_profile_log.updated_at) >=' => $prev_date, 'userlogin.usertype' => '1');
        $data['list_internship_can_count_total'] = $Common_model->candidate_active_new_count('userlogin', $where_count);
        // print_r($data['list_internship_can']);exit;
        return view('admin/dashboard_report_details_list', $data);
    }
    public function dashboard_candidates_idle($type)
    {

        $Common_model = new Common_model();
        $data['type'] = 6;
        $prev_date = date('Y-m-d', strtotime(' -15 day'));
        $college_filter_form = $this->request->getVar('college_filter_form');
        if (!empty($_POST) && !empty($college_filter_form)) {

            $start_date = $this->request->getVar('college_filter_start_date');
            $end_date = $this->request->getVar('college_filter_end_date');
            $college_list = $this->request->getVar('college_filter_college_id[]');
            $college_details_list = '';
            if (!empty($college_list)) {
                $i = 1;
                foreach ($college_list as $key) {
                    if ($i == 1) {
                        $college_details_list = $college_details_list . "'" . $key . "'";
                    } else {
                        $college_details_list = $college_details_list . ",'" . $key . "'";
                    }

                    $i++;
                }
            } else {
                $college_list = array();
            }
            //    $prev_date = date('Y-m-d', strtotime(' -15 day'));
            $where = array('date(userlogin.logged_in) >' => $prev_date, 'usertype' => '1', 'date(can_profile_log.updated_at) <' => $prev_date);
            $data['start_date_selected'] = $start_date;
            $data['end_date_selected'] = $end_date;
            $data['college_id_selected'] = $college_list;
            $data['list_internship_can'] = $Common_model->get_college_list_by_filter($start_date, $end_date, $college_details_list, $where);
            if (!empty($data['list_internship_can'])) {
                $data['list_internship_can_count']  = count($data['list_internship_can']);
            } else {
                $data['list_internship_can_count'] = 0;
            }
        } else {
            $data['start_date_selected'] = '';
            $data['end_date_selected'] = '';
            $data['college_id_selected'] = array();

            //    $prev_date = date('Y-m-d', strtotime(' -15 day'));
            $where8 = array('date(userlogin.logged_in) >' => $prev_date, 'usertype' => '1', 'date(can_profile_log.updated_at) <' => $prev_date);
            $data['list_internship_can'] = $Common_model->candidate_idle('userlogin', $where8);
            $data['list_internship_can_count'] = '';
        }
        $folder_can_id = array();
        //    $folder_can_id_arr = '';
        if (isset($data['list_internship_can']) && !empty($data['list_internship_can'])) {
            foreach ($data['list_internship_can'] as $can_data) {
                $folder_can_id[] = $can_data->userid;
            }
            //    $folder_can_id_arr = implode(',', $folder_can_id);
            $data['master_college_filter'] = $Common_model->can_college_all($folder_can_id);
        } else {
            $data['master_college_filter'] = '';
        }

        $where_count = array('date(userlogin.logged_in) >' => $prev_date, 'usertype' => '1', 'date(can_profile_log.updated_at) <' => $prev_date);
        $data['list_internship_can_count_total'] = $Common_model->candidate_idle_count('userlogin', $where_count);
        return view('admin/dashboard_report_details_list', $data);
    }
    public function dashboard_candidates_inactive($type)
    {

        $Common_model = new Common_model();
        $data['type'] = 7;
        $prev_date = date('Y-m-d', strtotime(' -15 day'));
        $college_filter_form = $this->request->getVar('college_filter_form');
        if (!empty($_POST) && !empty($college_filter_form)) {
            $start_date = $this->request->getVar('college_filter_start_date');
            $end_date = $this->request->getVar('college_filter_end_date');
            $college_list = $this->request->getVar('college_filter_college_id[]');
            $college_details_list = '';
            if (!empty($college_list)) {
                $i = 1;
                foreach ($college_list as $key) {
                    if ($i == 1) {
                        $college_details_list = $college_details_list . "'" . $key . "'";
                    } else {
                        $college_details_list = $college_details_list . ",'" . $key . "'";
                    }

                    $i++;
                }
            } else {
                $college_list = array();
            }
            //    $prev_date = date('Y-m-d', strtotime(' -15 day'));
            $where = '( date(userlogin.logged_in) < "' . $prev_date . '" OR userlogin`.`logged_in IS NULL )';
            $data['start_date_selected'] = $start_date;
            $data['end_date_selected'] = $end_date;
            $data['college_id_selected'] = $college_list;
            $data['list_internship_can'] = $Common_model->get_college_list_by_filter($start_date, $end_date, $college_details_list, $where);
            if (!empty($data['list_internship_can'])) {
                $data['list_internship_can_count']  = count($data['list_internship_can']);
            } else {
                $data['list_internship_can_count'] = 0;
            }
        } else {
            $data['start_date_selected'] = '';
            $data['end_date_selected'] = '';
            $data['college_id_selected'] = array();

            //    $prev_date = date('Y-m-d', strtotime(' -15 day'));
            $where8 = '( date(userlogin.logged_in) < "' . $prev_date . '" OR userlogin`.`logged_in IS NULL )';
            $data['list_internship_can'] = $Common_model->candidate_inactive('userlogin', $where8);
            $data['list_internship_can_count'] = '';
        }
        $folder_can_id = array();
        //    $folder_can_id_arr = '';
        if (isset($data['list_internship_can']) && !empty($data['list_internship_can'])) {
            foreach ($data['list_internship_can'] as $can_data) {
                $folder_can_id[] = $can_data->userid;
            }
            //    $folder_can_id_arr = implode(',', $folder_can_id);
            $data['master_college_filter'] = $Common_model->can_college_all($folder_can_id);
        } else {
            $data['master_college_filter'] = '';
        }
        $where_count = '( date(userlogin.logged_in) < "' . $prev_date . '" OR userlogin`.`logged_in IS NULL )';
        $data['list_internship_can_count_total'] = $Common_model->candidate_inactive_count('userlogin', $where_count);
        return view('admin/dashboard_report_details_list', $data);
    }
    public function dashboard_employer_active($type)
    {

        $Common_model = new Common_model();
        $data['type'] = 8;
        $order_by = array('ordercolumn' => 'id', 'ordertype' => 'desc');
        $prev_date = date('Y-m-d', strtotime(' -15 day'));
        $today_date = date('Y-m-d');


        $where = '(date(employer_post_internship.created_at) > "' . $prev_date . '" OR date(employer_post_internship.updated_at) > "' . $prev_date . '" OR date(emp_hiring_log.created_at) > "' . $prev_date . '" OR date(emp_hiring_log.updated_at) > "' . $prev_date . '") AND userlogin.usertype = "2" AND  date(userlogin.logged_in)  >= "' . $prev_date . '"';

        $data['no_of_employers'] = $Common_model->employer_active('userlogin', $where);

        return view('admin/dashboard_report_details_list', $data);
    }
    public function dashboard_employer_idle($type)
    {
        $Common_model = new Common_model();
        $data['type'] = 9;
        $order_by = array('ordercolumn' => 'id', 'ordertype' => 'desc');
        $prev_date = date('Y-m-d', strtotime(' -15 day'));
        $today_date = date('Y-m-d');
        //     $where8 = '( date(userlogin.logged_in) < "'.$prev_date.'" OR userlogin`.`logged_in IS NULL )';
        //     $no_of_employers_inactive= $Common_model->employer_inactive('userlogin',$where8);
        //     $inactive_arr=array();
        //     if(!empty($no_of_employers_inactive)){
        //     foreach ($no_of_employers_inactive as $inactive) {
        //         $inactive_arr[]=$inactive->userid;
        //         // echo $inactive->userid;
        //     }
        //     }
        //     // print_r($inactive_arr);
        //     $where = '(date(employer_post_internship.created_at) > "'.$prev_date.'" OR date(employer_post_internship.updated_at) > "'.$prev_date.'" OR date(emp_hiring_log.created_at) > "'.$prev_date.'" OR date(emp_hiring_log.updated_at) > "'.$prev_date.'") AND userlogin.usertype = "2" AND  date(userlogin.logged_in)  >= "'.$prev_date.'"';

        //     $no_of_employers_active = $Common_model->employer_active('userlogin',$where);
        //     $active_arr=array();
        //     //   print_r($no_of_employers_active);exit;
        //   if(!empty($no_of_employers_active)){
        //     foreach ($no_of_employers_active as $active) {
        //         $active_arr[]=$active->userid;
        //         //  echo $active->userid;
        //     }
        //     }
        // //     //   print_r($inactive_arr);

        //      $idle_arr=array_merge($inactive_arr,$active_arr);
        //      $idle_unique_id=array_unique($idle_arr);





        //       $data['no_of_employers'] = $Common_model->employer_report_idle_new('profile_completion_form',$idle_unique_id);
        // //      if(!empty($data['no_of_employers1'])){
        //      $data['no_of_employers'] =$data['no_of_employers1'];
        //  }
        //  else{
        //      $data['no_of_employers'] = 0;
        //  }

        //  $where = '(date(employer_post_internship.created_at) < "'.$prev_date.'" AND date(employer_post_internship.updated_at) < "'.$prev_date.'" AND date(emp_hiring_log.created_at) < "'.$prev_date.'" AND date(emp_hiring_log.updated_at) < "'.$prev_date.'") AND userlogin.usertype = "2" AND  date(userlogin.logged_in)  > "'.$prev_date.'"';

        $data['no_of_employers'] = $Common_model->employer_report_idle('userlogin', $prev_date);


        return view('admin/dashboard_report_details_list', $data);
    }
    public function dashboard_employer_inactive($type)
    {

        $Common_model = new Common_model();
        $data['type'] = 10;
        $order_by = array('ordercolumn' => 'id', 'ordertype' => 'desc');
        $prev_date = date('Y-m-d', strtotime(' -15 day'));
        $where = '( date(userlogin.logged_in) < "' . $prev_date . '" OR userlogin`.`logged_in IS NULL )';
        $data['no_of_employers'] = $Common_model->employer_inactive('userlogin', $where);
        // print_r($data['no_of_employers']);exit;
        return view('admin/dashboard_report_details_list', $data);
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
    //function call

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
    public function admin_college_details()
    {
        $session = session();
        $Common_model = new Common_model();
        $where = array();
        $data['college_details'] = $Common_model->college_name_correction('can_personal_details', $where);

        $data['total_college'] = $Common_model->college_name_correction_count('can_personal_details', $where);

        // $data['total_college'] = count($data['college_details']);
        $data['course_details'] = $Common_model->college_course('can_personal_details', $where);

        $data['total_course'] = $Common_model->college_course_count('can_personal_details', $where);

        // $data['total_course'] = count($data['course_details']);
        $data['specialization_details'] = $Common_model->college_specialization('can_personal_details', $where);

        $data['total_specialization'] = $Common_model->college_specialization_count('can_personal_details', $where);

        // $data['total_specialization'] = count($data['specialization_details']);
        $order_by = array('ordercolumn' => 'college_name', 'ordertype' => 'ASC');
        $where1 = array('status' => '1');
        $data['master_college'] = $Common_model->fetch_table_data_for_all_college('master_college', $where1, $order_by);
        // $data['master_courses'] = $Common_model->fetch_table_data_for_all('master_academic_courses', $where1);
        // $data['master_specialization'] = $Common_model->fetch_table_data_for_all('master_academic_branch', $where1);
        return view('admin/admin_college_details', $data);
    }
    public function admin_course_details()
    {
        $session = session();
        $Common_model = new Common_model();
        $where = array();
        $data['college_details'] = $Common_model->college_name_correction('can_personal_details', $where);

        $data['total_college'] = $Common_model->college_name_correction_count('can_personal_details', $where);

        // $data['total_college'] = count($data['college_details']);
        $data['course_details'] = $Common_model->college_course('can_personal_details', $where);

        $data['total_course'] = $Common_model->college_course_count('can_personal_details', $where);

        // $data['total_course'] = count($data['course_details']);
        $data['specialization_details'] = $Common_model->college_specialization('can_personal_details', $where);

        $data['total_specialization'] = $Common_model->college_specialization_count('can_personal_details', $where);
        $order_by = array('ordercolumn' => 'name', 'ordertype' => 'ASC');
        $where1 = array('status' => '1');
        $data['master_courses'] = $Common_model->fetch_table_data_for_all('master_academic_courses', $where1, $order_by);
        return view('admin/admin_course_details', $data);
    }
    public function admin_specialization_details()
    {
        $session = session();
        $Common_model = new Common_model();
        $where = array();
        $data['college_details'] = $Common_model->college_name_correction('can_personal_details', $where);

        $data['total_college'] = $Common_model->college_name_correction_count('can_personal_details', $where);

        // $data['total_college'] = count($data['college_details']);
        $data['course_details'] = $Common_model->college_course('can_personal_details', $where);

        $data['total_course'] = $Common_model->college_course_count('can_personal_details', $where);

        // $data['total_course'] = count($data['course_details']);
        $data['specialization_details'] = $Common_model->college_specialization('can_personal_details', $where);

        $data['total_specialization'] = $Common_model->college_specialization_count('can_personal_details', $where);
        $order_by = array('ordercolumn' => 'name', 'ordertype' => 'ASC');
        $where1 = array('status' => '1');
        $data['master_specialization'] = $Common_model->fetch_table_data_for_all('master_academic_branch', $where1, $order_by);
        return view('admin/admin_specialization_details', $data);
    }

    public function college_details()
    {

        $session         = session();
        $Common_model = new Common_model();
        $candidate_id1 = $this->request->getVar('candidate_college_id');
        $candidate_id = explode(",", $candidate_id1);
        $college_id = $this->request->getVar('add_college_details');
        $data = array('active_status' => '1', 'education_college_name' => $college_id);

        if (!empty($candidate_id)) {
            foreach ($candidate_id as $key) {

                $where = array('id' => $key);
                $update_status = $Common_model->update_commen('can_education_details', $where, $data);
            }

            if ($update_status) {
                $session->setFlashdata('error_status', '2');
                $session->setFlashdata('error_msg', 'College Details Updated successfully');
                return redirect()->to('admin-college-details');
            } else {

                return redirect()->to('admin-college-details');
            }
        } else {

            return redirect()->to('admin-college-details');
        }
    }


    public function college_course()
    {

        $session         = session();
        $Common_model = new Common_model();
        $candidate_id1 = $this->request->getVar('candidate_course_id');
        $candidate_id = explode(",", $candidate_id1);
        $course_id = $this->request->getVar('add_course_details');
        $data = array('active_status' => '1', 'education_course' => $course_id);

        if (!empty($candidate_id)) {
            foreach ($candidate_id as $key) {

                $where = array('id' => $key);
                $update_status = $Common_model->update_commen('can_education_details', $where, $data);
            }

            if ($update_status) {
                $session->setFlashdata('error_status', '2');
                $session->setFlashdata('error_msg', 'Course Details Updated successfully');
                return redirect()->to('admin-course-details');
            } else {

                return redirect()->to('admin-course-details');
            }
        } else {

            return redirect()->to('admin-course-details');
        }
    }
    public function college_specialization()
    {

        $session         = session();
        $Common_model = new Common_model();
        $candidate_id1 = $this->request->getVar('candidate_spl_id');
        //  print_r($candidate_id1);exit;
        $candidate_id = explode(",", $candidate_id1);
        $specialization_id = $this->request->getVar('add_specialization_details');
        $data = array('active_status' => '1', 'education_specialization' => $specialization_id);

        if (!empty($candidate_id)) {
            foreach ($candidate_id as $key) {

                $where = array('id' => $key);
                $update_status = $Common_model->update_commen('can_education_details', $where, $data);
            }

            if ($update_status) {
                $session->setFlashdata('error_status', '2');
                $session->setFlashdata('error_msg', 'Specialization Details Updated successfully');
                return redirect()->to('admin-specialization-details');
            } else {

                return redirect()->to('admin-specialization-details');
            }
        } else {

            return redirect()->to('admin-specialization-details');
        }
    }

    public function download_employers_details_excel()
    {

        // $model = new Employer_model();
        $session = session();
        $Common_model = new Common_model();

        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'S.No');
        $sheet->setCellValue('B1', 'Company Name');
        $sheet->setCellValue('C1', 'POC Name');
        $sheet->setCellValue('D1', 'POC Mail ID');
        $sheet->setCellValue('E1', 'Mobile Number');
        $sheet->setCellValue('F1', 'Internships Posted');
        $sheet->setCellValue('G1', 'Location');
        $sheet->setCellValue('H1', 'Profile Status');
        $sheet->setCellValue('I1', 'Registered Date');

        $count = 2;
        $i = 1;

        $type = $this->request->getVar('type');
        if ($type == 8) {
            $prev_date = date('Y-m-d', strtotime(' -15 day'));
            $where = '(date(employer_post_internship.created_at) > "' . $prev_date . '" OR date(employer_post_internship.updated_at) > "' . $prev_date . '" OR date(emp_hiring_log.created_at) > "' . $prev_date . '" OR date(emp_hiring_log.updated_at) > "' . $prev_date . '") AND userlogin.usertype = "2" AND  date(userlogin.logged_in)  >= "' . $prev_date . '"';
            $employer_details = $Common_model->employer_active('userlogin', $where);
        }
        if ($type == 9) {
            $prev_date = date('Y-m-d', strtotime(' -15 day'));
            $employer_details = $Common_model->employer_report_idle('userlogin', $prev_date);
        }
        if ($type == 10) {
            $prev_date = date('Y-m-d', strtotime(' -15 day'));
            $where = '( date(userlogin.logged_in) < "' . $prev_date . '" OR userlogin`.`logged_in IS NULL )';
            $employer_details = $Common_model->employer_inactive('userlogin', $where);
        }
        if ($type == 4) {
            $order_by = array('ordercolumn' => 'id', 'ordertype' => 'desc');
            $where = array('status' => '1');
            $employer_details = $Common_model->fetch_table_data_for_all('profile_completion_form', $where, $order_by);
        }

        if (!empty($employer_details)) {
            $employer_details_count = count($employer_details);
        } else {
            $employer_details_count = 0;
        }
        // echo $candidate_details_count;
        //exit;
        if ($employer_details_count > 1000) {
            echo csrf_hash() . '^' . 0;
        } elseif ($employer_details_count == 0) {
            echo csrf_hash() . '^' . 1;
        } else {



            if (!empty($employer_details)) {
                foreach ($employer_details as $emp_details) {
                    if ($emp_details->completed_status == 1) {
                        $status = 'Completed';
                    } else {
                        $status = 'Not Completed';
                    }

                    $where = array('company_id' => $emp_details->userid);
                    $internship_posted = $Common_model->data_count_fetch('employer_post_internship', $where);

                    $sheet->setCellValue('A' . $count, $i);
                    $sheet->setCellValue('B' . $count, $emp_details->profile_company_name);

                    $sheet->setCellValue('C' . $count, $emp_details->profile_name);

                    $sheet->setCellValue('D' . $count, $emp_details->profile_official_email);

                    $sheet->setCellValue('E' . $count, $emp_details->profile_phone_no);
                    $sheet->setCellValue('F' . $count, $internship_posted);
                    $sheet->setCellValue('G' . $count, $emp_details->location_name);
                    $sheet->setCellValue('H' . $count, $status);
                    $sheet->setCellValue('I' . $count, date("d-M-Y", strtotime($emp_details->created_at)));

                    $count++;
                    $i++;
                }
            }
            $time =  time();
            $writer = new Xlsx($spreadsheet);
            $writer->save('public/employer_data' . $time . '.xlsx');
            echo csrf_hash() . '^' . 'employer_data' . $time . '.xlsx';
        }
    }

    public function download_candidate_details_excel()
    {

        // $model = new Employer_model();
        $session = session();
        $Common_model = new Common_model();

        $spreadsheet = new Spreadsheet();
        $type = $this->request->getVar('type');
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'S.No');
        $sheet->setCellValue('B1', 'Candidate Name');
        $sheet->setCellValue('C1', 'Preferred Location');
        $sheet->setCellValue('D1', 'Mobile Number');
        $sheet->setCellValue('E1', 'Email ID');
        $sheet->setCellValue('F1', 'Gender');
        if ($type == '2') {
            $sheet->setCellValue('G1', 'College State');
            $sheet->setCellValue('H1', 'Degree');
            $sheet->setCellValue('I1', 'Current Year');
            $sheet->setCellValue('J1', 'College Name');
            $sheet->setCellValue('K1', 'Internships Applied');
            $sheet->setCellValue('L1', 'Hired');
            $sheet->setCellValue('M1', 'Internships Completed');
            $sheet->setCellValue('N1', 'Registered Date');
        } else {
            $sheet->setCellValue('G1', 'College Name');
            $sheet->setCellValue('H1', 'Internships Applied');
            $sheet->setCellValue('I1', 'Hired');
            $sheet->setCellValue('J1', 'Internships Completed');
            $sheet->setCellValue('K1', 'Registered Date');
        }

        $count = 2;
        $i = 1;


        $add_search_start_date = $this->request->getVar('add_search_start_date');
        $add_search_end_date = $this->request->getVar('add_search_end_date');
        $college_list = $this->request->getVar('college_filter_college_id');
        if (isset($college_list) && !empty($college_list)) {
            $college_list = $this->request->getVar('college_filter_college_id');
        } else {
            $college_list = '';
        }

        if ($type == 1) {
            if (!empty($add_search_start_date) && !empty($add_search_end_date)) {
                $order_by = array('ordercolumn' => 'id', 'ordertype' => 'desc');
                $where = array('date(can_personal_details.created_at) >=' => $add_search_start_date, 'date(can_personal_details.created_at) <=' => $add_search_end_date, 'can_personal_details.status' => '1');
                // $candidate_details = $Common_model->fetch_table_data_for_all('can_personal_details', $where,$order_by);
                $candidate_details = $Common_model->get_college_list_by_filter($add_search_start_date, $add_search_end_date, $college_list, $where);
            } else {
                $order_by = array('ordercolumn' => 'id', 'ordertype' => 'desc');
                $where = array('status' => '1');
                $candidate_details = $Common_model->fetch_table_data_for_all('can_personal_details', $where, $order_by);
            }
        }
        if ($type == 2) {
            if (!empty($add_search_start_date) && !empty($add_search_end_date)) {
                $order_by = array('ordercolumn' => 'can_personal_details.id', 'ordertype' => 'desc');
                $where = array('date(can_personal_details.created_at) >=' => $add_search_start_date, 'date(can_personal_details.created_at) <=' => $add_search_end_date, 'can_personal_details.can_profile_complete_status' => '1');
                // $candidate_details = $Common_model->candidate_completion_profile('can_personal_details', $where,$order_by);
                $candidate_details = $Common_model->get_college_list_by_filter_download($add_search_start_date, $add_search_end_date, $college_list, $where);
            } else {
                $order_by = array('ordercolumn' => 'can_personal_details.id', 'ordertype' => 'desc');
                $where = array('can_education_details.status' => '1', 'can_personal_details.can_profile_complete_status' => '1');
                $candidate_details = $Common_model->candidate_completion_profile('can_personal_details', $where, $order_by);
            }
        }
        if ($type == 5) {
            if (!empty($add_search_start_date) && !empty($add_search_end_date)) {
                $order_by = array('ordercolumn' => 'id', 'ordertype' => 'desc');
                $prev_date = date('Y-m-d', strtotime(' -15 day'));
                $where = array('date(can_personal_details.created_at) >=' => $add_search_start_date, 'date(can_personal_details.created_at) <=' => $add_search_end_date, 'date(userlogin.logged_in) >=' => $prev_date, 'date(can_profile_log.updated_at) >=' => $prev_date, 'userlogin.usertype' => '1');

                // $candidate_details = $Common_model->candidate_active_new('userlogin',$where7);
                $candidate_details = $Common_model->get_college_list_by_filter($add_search_start_date, $add_search_end_date, $college_list, $where);
            } else {
                $prev_date = date('Y-m-d', strtotime(' -15 day'));
                $where7 = array('date(userlogin.logged_in) >=' => $prev_date, 'date(can_profile_log.updated_at) >=' => $prev_date, 'userlogin.usertype' => '1');
                $candidate_details = $Common_model->candidate_active_new('userlogin', $where7);
            }
        }
        if ($type == 6) {
            if (!empty($add_search_start_date) && !empty($add_search_end_date)) {

                $prev_date = date('Y-m-d', strtotime(' -15 day'));
                $where = array('date(can_personal_details.created_at) >=' => $add_search_start_date, 'date(can_personal_details.created_at) <=' => $add_search_end_date, 'date(userlogin.logged_in) >' => $prev_date, 'usertype' => '1', 'date(can_profile_log.updated_at) <' => $prev_date);
                // $candidate_details = $Common_model->candidate_idle('userlogin',$where8);
                $candidate_details = $Common_model->get_college_list_by_filter($add_search_start_date, $add_search_end_date, $college_list, $where);
            } else {
                $prev_date = date('Y-m-d', strtotime(' -15 day'));
                $where8 = array('date(userlogin.logged_in) >' => $prev_date, 'usertype' => '1', 'date(can_profile_log.updated_at) <' => $prev_date);
                $candidate_details = $Common_model->candidate_idle('userlogin', $where8);
            }
        }
        if ($type == 7) {
            if (!empty($add_search_start_date) && !empty($add_search_end_date)) {
                $prev_date = date('Y-m-d', strtotime(' -15 day'));
                $where = '(date(userlogin.logged_in) < "' . $prev_date . '" OR userlogin`.`logged_in IS NULL )';
                // $candidate_details = $Common_model->candidate_inactive_report('userlogin',$where9,$add_search_start_date,$add_search_end_date);
                $candidate_details = $Common_model->get_college_list_by_filter($add_search_start_date, $add_search_end_date, $college_list, $where);
            } else {
                $prev_date = date('Y-m-d', strtotime(' -15 day'));
                $where8 = '( date(userlogin.logged_in) < "' . $prev_date . '" OR userlogin`.`logged_in IS NULL )';
                $candidate_details  = $Common_model->candidate_inactive('userlogin', $where8);
            }
        }



        if (!empty($candidate_details)) {
            $candidate_details_count = count($candidate_details);
        } else {
            $candidate_details_count = 0;
        }
        // echo $candidate_details_count;
        //exit;
        if ($candidate_details_count > 1000) {
            echo csrf_hash() . '^' . 0;
        } elseif ($candidate_details_count == 0) {
            echo csrf_hash() . '^' . 1;
        } else {


            if (!empty($candidate_details)) {
                // print_r($candidate_details);exit;
                foreach ($candidate_details as $can_details) {
                    $gender = '';
                    if ($can_details->profile_gender == 1) {
                        $gender = "Male";
                    } elseif ($can_details->profile_gender == 2) {
                        $gender = "Female";
                    } elseif ($can_details->profile_gender == 3) {
                        $gender = "Transgender";
                    }

                    $where4 = array('status' => '1', 'userid' => $can_details->userid);
                    $order_by = array('ordercolumn' => 'education_end_year', 'ordertype' => 'desc');
                    $education_details = $Common_model->fetch_table_data_for_all('can_education_details', $where4, $order_by);

                    if (!empty($education_details[0])) {
                        if ($education_details[0]->education_college_name != 0) {
                            $where1 = array('id' => $education_details[0]->education_college_name);
                            $education_college_name = $Common_model->get_master_commen_for_all('master_college', $where1, 'college_name');
                        } elseif ($education_details[0]->education_college_name_other != '') {
                            $education_college_name = $education_details[0]->education_college_name_other;
                        } else {
                            $education_college_name = '';
                        }
                    } else {
                        $education_college_name = '';
                    }
                    $where = array('status' => '1', 'candidate_id' => $can_details->userid);
                    $applied_count = $Common_model->data_count_fetch('can_applied_internship', $where);
                    // echo $applied_count; 
                    $where = array('status' => '1', 'candidate_id' => $can_details->userid, 'application_status' => 2);
                    $hired_count = $Common_model->data_count_fetch('can_applied_internship', $where);
                    // echo $hired_count; 

                    if ($type == '2') {
                        $where1 = array('id' => $education_details[0]->education_college_name);
                        $master_state = $Common_model->get_master_commen_for_all('master_college', $where1, 'state_id');
                        // echo $master_state;
                        $where1 = array('state_id' => $master_state);
                        $master_state_value = $Common_model->get_master_commen_for_all('master_district', $where1, 'state_name');
                        // echo $master_state_value;

                        if (isset($can_details->education_course) && $can_details->education_course != 0) {
                            $education_degree = $Common_model->get_master_name('master_academic_courses', $can_details->education_course, 'name');
                        } else {
                            $education_degree = $can_details->education_course_other;
                        }

                        if (!empty($can_details->education_course)) {
                            $final_study_year = 'Passed Out';
                            $where_can = array('status' => '1', 'id' => $can_details->education_course);
                            $academic_courses_details = $Common_model->fetch_table_row('master_academic_courses', $where_can);
                            if ($academic_courses_details->course_duration != 0) {
                                if (date('m') > 5) {
                                    $can_details_start_year_fin = $can_details->education_start_year + 1;
                                } else {
                                    $can_details_start_year_fin = $can_details->education_start_year;
                                }
                                $student_study_year = $can_details_start_year_fin + $academic_courses_details->course_duration;
                                // echo $student_study_year;


                                if (date('m') > 5) {
                                    $current_year = date('Y') + 1;
                                } else {
                                    $current_year = date('Y');
                                }
                                // echo $current_year;
                                if ($student_study_year >= $current_year) {
                                    $study_year = $current_year - $can_details->education_start_year;

                                    if ($study_year == 1) {
                                        $final_study_year = "1st Year";
                                    } elseif ($study_year == 2) {
                                        $final_study_year = "2nd Year";
                                    } elseif ($study_year == 3) {
                                        $final_study_year = "3rd Year";
                                    } elseif ($study_year == 4) {
                                        $final_study_year = "4th Year";
                                    }
                                }
                            }
                        }
                    }

                    $where_com = array('status' => '1', 'candidate_id' => $can_details->userid, 'complete_status' => '1', 'complete_type' => '1');
                    $completed_count = $Common_model->data_count_fetch('can_applied_internship', $where_com);

                    $sheet->setCellValue('A' . $count, $i);
                    $sheet->setCellValue('B' . $count, $can_details->profile_full_name);

                    $sheet->setCellValue('C' . $count, $can_details->g_location_name);

                    $sheet->setCellValue('D' . $count, $can_details->profile_phone_number);

                    $sheet->setCellValue('E' . $count, $can_details->profile_email);
                    $sheet->setCellValue('F' . $count, $gender);
                    if ($type == '2') {
                        $sheet->setCellValue('G' . $count, $master_state_value);
                        $sheet->setCellValue('H' . $count, $education_degree);
                        $sheet->setCellValue('I' . $count, $final_study_year);
                        // $sheet->setCellValue('G' . $count, '');
                        // $sheet->setCellValue('H' . $count, '');
                        // $sheet->setCellValue('I' . $count, '');
                        $sheet->setCellValue('J' . $count, $education_college_name);
                        $sheet->setCellValue('K' . $count, $applied_count);
                        $sheet->setCellValue('L' . $count, $hired_count);
                        $sheet->setCellValue('M' . $count, $completed_count);
                        $sheet->setCellValue('N' . $count, date("d-M-Y", strtotime($can_details->created_at)));
                    } else {
                        $sheet->setCellValue('G' . $count, $education_college_name);
                        $sheet->setCellValue('H' . $count, $applied_count);
                        $sheet->setCellValue('I' . $count, $hired_count);
                        $sheet->setCellValue('J' . $count, $completed_count);
                        $sheet->setCellValue('K' . $count, date("d-M-Y", strtotime($can_details->created_at)));
                    }
                    $count++;
                    $i++;
                }
            }
            $time = time();
            $writer = new Xlsx($spreadsheet);
            $writer->save('public/candidate_report_data' . $time . '.xlsx');
            echo csrf_hash() . '^' . 'candidate_report_data' . $time . '.xlsx';
        }
    }
    public function admin_dashboard_college_state()
    {
        $Common_model = new Common_model();
        $session = session();
        $order_by = array('ordercolumn' => 'id', 'ordertype' => 'desc');
        $college_filter_college = $session->get('college_filter_college');
        $college_filter_state = $session->get('college_filter_state');
        $data['master_college_filter_value_count_all'] = $Common_model->can_college_all_value_count();
        $where = array('status' => '1');
        if (!empty($college_filter_college) || !empty($college_filter_state)) {

            $college_list = $college_filter_college;
            $state_list = $college_filter_state;
            //echo $college_filter_state;
            //print_r($_SESSION); exit();
            if (!empty($state_list)) {
                $folder_can_id_arr = implode(',', $state_list);
                $data['master_college_filter']  = $Common_model->can_college_all_state_district($folder_can_id_arr);
            } else {
                $data['master_college_filter'] = $Common_model->can_college_all();
            }
            $college_details_list = '';

            $data['college_id_selected'] = $college_list;
            $data['state_id_selected'] = $state_list;



            //    $data['master_college_filter_value'] = $Common_model->get_college_list_by_filter_state($college_details_list);
            // print_r($data['list_internship_can']);exit();
            // $data['master_college_filter'] = $Common_model->can_college_all();
            $data['master_college_filter_value'] = $Common_model->can_college_all_value($college_list, $state_list);

            if (!empty($data['master_college_filter_value'])) {
                $data['master_college_filter_value_count'] = count($data['master_college_filter_value']);
            } else {
                $data['master_college_filter_value_count'] = 0;
            }
        } else {

            $data['college_id_selected'] = array();
            $data['state_id_selected'] = array();

            $order_by = array('ordercolumn' => 'id', 'ordertype' => 'desc');
            $where = array('status' => '1');
            //$data['master_college_filter'] = $Common_model->fetch_table_data_for_all('can_personal_details', $where,$order_by);
            $data['master_college_filter'] = $Common_model->can_college_all();
            $data['master_college_filter_value'] = $Common_model->can_college_all_value();
            $data['master_college_filter_value_count'] = '';
        }
        // exit;

        $where1 = array('status' => '1');
        $data['master_college'] = $Common_model->fetch_table_data_for_all_college('master_college', $where1);
        $where = array('status' => '1');
        $all_candidate = $Common_model->fetch_table_data_for_all('can_personal_details', $where);

        $folder_can_id = array();
        $folder_can_id_arr = '';
        if (isset($all_candidate) && !empty($all_candidate)) {
            foreach ($all_candidate as $can_data) {
                $folder_can_id[] = $can_data->userid;
            }
            $folder_can_id_arr = implode(',', $folder_can_id);
        }
        // print_r($folder_can_id_arr);
        // exit();

        $data['master_state_filter'] = $Common_model->can_state_all();


        if (!empty($data['master_state_filter'])) {
            $data['master_state_count']  = count($data['master_state_filter']);
        } else {
            $data['master_state_count'] = 0;
        }
        // $data['list_internship_can'] = $Common_model->fetch_table_data_for_all('can_personal_details', $where,$order_by); 
        return view('admin/admin_dashboard_dist_state', $data);
    }
    public function collegewise_candidate_list($college_id)
    {
        $Common_model = new Common_model();
        $order_by = array('ordercolumn' => 'id', 'ordertype' => 'desc');
        $where = array('education_college_name' => $college_id);
        $data['reg_candidate_list_count'] = $Common_model->fetch_table_data_for_all_count('can_education_details', $where, $order_by);
        $data['reg_candidate_list_all'] = $Common_model->fetch_table_data_for_all('can_education_details', $where, $order_by);
        $where = array('education_college_name' => $college_id);
        $data['reg_candidate_list_count'] = $Common_model->fetch_table_data_for_all_count('can_education_details', $where, $order_by);

        $where1 = array('can_education_details.education_college_name' => $college_id, 'date(can_personal_details.created_at)' => date('Y-m-d'));
        $data['today_registered_candidate'] = $Common_model->candidate_completion_profile_count('can_personal_details', $where1);
        if (!empty($_POST)) {

            $start_date = $this->request->getVar('college_filter_start_date');
            $end_date = $this->request->getVar('college_filter_end_date');

            $data['start_date_selected'] = $start_date;
            $data['end_date_selected'] = $end_date;
            $where = array('education_college_name' => $college_id);

            $data['reg_candidate_list'] = $Common_model->get_candidate_list_by_filter($start_date, $end_date, $where);
            if (!empty($data['reg_candidate_list'])) {
                $data['list_internship_can_count']  = count($data['reg_candidate_list']);
            } else {
                $data['list_internship_can_count'] = 0;
            }
        } else {
            $data['start_date_selected'] = '';
            $data['end_date_selected'] = '';
            $data['list_internship_can_count'] = '';

            $order_by = array('ordercolumn' => 'id', 'ordertype' => 'desc');
            $where = array('education_college_name' => $college_id);
            $data['reg_candidate_list'] = $Common_model->fetch_table_data_for_all('can_education_details', $where, $order_by);
        }
        $data['district_wise_college_id'] = $college_id;
        return view('admin/admin_dashboard_candidate_list', $data);
    }

    public function download_candidate_details_dist_excel()
    {

        // $model = new Employer_model();
        $session = session();
        $Common_model = new Common_model();

        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'S.No');
        $sheet->setCellValue('B1', 'Candidate Name');
        $sheet->setCellValue('C1', 'Preferred Location');
        $sheet->setCellValue('D1', 'Mobile Number');
        $sheet->setCellValue('E1', 'Email ID');
        $sheet->setCellValue('F1', 'Gender');
        $sheet->setCellValue('G1', 'Degree');
        $sheet->setCellValue('H1', 'Current Year');
        $sheet->setCellValue('I1', 'Internships Applied');
        $sheet->setCellValue('J1', 'Hired');
        $sheet->setCellValue('K1', 'Registered Date');


        $count = 2;
        $i = 1;




        $college_id = $this->request->getVar('college_id');
        $start_date = $this->request->getVar('college_filter_start_date');
        $end_date = $this->request->getVar('college_filter_end_date');

        if (!empty($start_date) && !empty($end_date)) {



            $data['start_date_selected'] = $start_date;
            $data['end_date_selected'] = $end_date;
            $where = array('education_college_name' => $college_id);

            $data['reg_candidate_list'] = $Common_model->get_candidate_list_by_filter($start_date, $end_date, $where);
        } else {
            $data['start_date_selected'] = '';
            $data['end_date_selected'] = '';


            $order_by = array('ordercolumn' => 'id', 'ordertype' => 'desc');
            $where = array('education_college_name' => $college_id);
            $data['reg_candidate_list'] = $Common_model->fetch_table_data_for_all('can_education_details', $where, $order_by);
            // print_r($data['reg_candidate_list']);
            // exit();
        }
        //  print_r($data['reg_candidate_list']);
        $candidate_details = $data['reg_candidate_list'];

        if (!empty($candidate_details)) {
            $candidate_details_count = count($candidate_details);
        } else {
            $candidate_details_count = 0;
        }
        // echo $candidate_details_count;
        //exit;
        if ($candidate_details_count > 1000) {
            echo csrf_hash() . '^' . 0;
        } else if ($candidate_details_count == 0) {
            echo csrf_hash() . '^' . 1;
        } else {

            if (!empty($candidate_details)) {

                foreach ($candidate_details as $cand_details) {
                    $where = array('can_personal_details.status' => '1', 'can_personal_details.userid' => $cand_details->userid);
                    $can_details = $Common_model->candidate_details_profile_education('can_personal_details', $where);
                    $gender = '';
                    if ($can_details->profile_gender == 1) {
                        $gender = "Male";
                    } elseif ($can_details->profile_gender == 2) {
                        $gender = "Female";
                    } elseif ($can_details->profile_gender == 3) {
                        $gender = "Transgender";
                    }

                    $where = array('status' => '1', 'candidate_id' => $can_details->userid);
                    $applied_count = $Common_model->data_count_fetch('can_applied_internship', $where);
                    // echo $applied_count; 
                    $where = array('status' => '1', 'candidate_id' => $can_details->userid, 'application_status' => 2);
                    $hired_count = $Common_model->data_count_fetch('can_applied_internship', $where);
                    // echo $hired_count; 
                    $education_degree = '';
                    if (isset($can_details->education_course) && $can_details->education_course != 0) {
                        $education_degree = $Common_model->get_master_name('master_academic_courses', $can_details->education_course, 'name');
                    } else {
                        $education_degree = $can_details->education_course_other;
                    }

                    if (!empty($can_details->education_course)) {
                        $final_study_year = 'Passed Out';
                        $where_can = array('status' => '1', 'id' => $can_details->education_course);
                        $academic_courses_details = $Common_model->fetch_table_row('master_academic_courses', $where_can);
                        if ($academic_courses_details->course_duration != 0) {
                            if (date('m') > 5) {
                                $can_details_start_year_fin = $can_details->education_start_year + 1;
                            } else {
                                $can_details_start_year_fin = $can_details->education_start_year;
                            }
                            $student_study_year = $can_details_start_year_fin + $academic_courses_details->course_duration;
                            // echo $student_study_year;


                            if (date('m') > 5) {
                                $current_year = date('Y') + 1;
                            } else {
                                $current_year = date('Y');
                            }
                            // echo $current_year;
                            if ($student_study_year >= $current_year) {
                                $study_year = $current_year - $can_details->education_start_year;
                                if ($study_year == 1) {
                                    $final_study_year = "1st Year";
                                } elseif ($study_year == 2) {
                                    $final_study_year = "2nd Year";
                                } elseif ($study_year == 3) {
                                    $final_study_year = "3rd Year";
                                } elseif ($study_year == 4) {
                                    $final_study_year = "4th Year";
                                }
                            }
                        }
                    }


                    $sheet->setCellValue('A' . $count, $i);
                    $sheet->setCellValue('B' . $count, $can_details->profile_full_name);

                    $sheet->setCellValue('C' . $count, $can_details->g_location_name);

                    $sheet->setCellValue('D' . $count, $can_details->profile_phone_number);

                    $sheet->setCellValue('E' . $count, $can_details->profile_email);
                    $sheet->setCellValue('F' . $count, $gender);
                    $sheet->setCellValue('G' . $count, $education_degree);
                    $sheet->setCellValue('H' . $count, $final_study_year);
                    $sheet->setCellValue('I' . $count, $applied_count);
                    $sheet->setCellValue('J' . $count, $hired_count);
                    $sheet->setCellValue('K' . $count, date("d-M-Y", strtotime($can_details->created_at)));
                    $count++;
                    $i++;
                }
                // print_r($candidate_details);exit;
            }
            $time = time();
            $writer = new Xlsx($spreadsheet);
            $writer->save('public/candidate_report_data' . $time . '.xlsx');
            echo csrf_hash() . '^' . 'candidate_report_data' . $time . '.xlsx';
        }
    }

    public function download_college_details_state_district_excel()
    {

        // $model = new Employer_model();
        $session = session();
        $Common_model = new Common_model();

        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'S.No');
        $sheet->setCellValue('B1', 'College Name');
        $sheet->setCellValue('C1', 'State');
        $sheet->setCellValue('D1', 'District');
        $sheet->setCellValue('E1', 'Register Count');


        $count = 2;
        $i = 1;
        $college_filter_college = $session->get('college_filter_college');
        $college_filter_state = $session->get('college_filter_state');
        if (!empty($college_filter_college) || !empty($college_filter_state)) {

            $college_list = $college_filter_college;
            $state_list = $college_filter_state;
            $data['master_college_filter'] = $Common_model->can_college_all_value($college_list, $state_list);
        } else {


            $data['master_college_filter'] = $Common_model->can_college_all();
        }

        //   $where = array('date(can_education_details.created_at) >='=>$add_search_start_date,'date(can_education_details.created_at) <='=>$add_search_end_date,'can_education_details.status' => '1');
        //   $data['master_college_filter'] = $Common_model->can_college_all_state_district('can_education_details', $where);

        //   print_r($data['master_college_filter']);
        $college_details = $data['master_college_filter'];
        if (!empty($college_details)) {
            $college_details_count = count($college_details);
        } else {
            $college_details_count = 0;
        }
        // echo $candidate_details_count;
        //exit;
        if ($college_details_count > 1000) {
            echo csrf_hash() . '^' . 0;
        } else {


            if (!empty($college_details)) {


                foreach ($college_details as $col_details) {


                    $master_state = '';
                    $where1 = array('state_id' => $col_details->state_id);
                    $master_state = $Common_model->get_master_commen_for_all('master_district', $where1, 'state_name');
                    $where1 = array('dist_id' => $col_details->district_id);
                    $master_dist = $Common_model->get_master_commen_for_all('master_district', $where1, 'dist_name');
                    if ($master_dist == FALSE) {
                        $master_dist = '';
                    }
                    $where1 = array('education_college_name' => $col_details->id);
                    $order_by = array('ordercolumn' => 'id', 'ordertype' => 'desc');
                    $register_count = $Common_model->fetch_table_data_for_all('can_education_details', $where1, $order_by);
                    // print_r($register_count);
                    if (!empty($register_count)) {
                        $can_register_count = count($register_count);
                    } else {
                        $can_register_count = 0;
                    }



                    $sheet->setCellValue('A' . $count, $i);
                    $sheet->setCellValue('B' . $count, $col_details->college_name);

                    $sheet->setCellValue('C' . $count, $master_state);

                    $sheet->setCellValue('D' . $count, $master_dist);

                    $sheet->setCellValue('E' . $count, $can_register_count);

                    $count++;
                    $i++;
                }
            }
            $time = time();
            $writer = new Xlsx($spreadsheet);
            $writer->save('public/collegewise_registered_candidate_list' . $time . '.xlsx');
            echo csrf_hash() . '^' . 'collegewise_registered_candidate_list' . $time . '.xlsx';
        }
    }


    public function dependency_search_reg_candidate_college_filter()
    {

        $Common_model = new Common_model();
        $session = session();
        $college_filter_state_name = $this->request->getVar('college_filter_state');
        $college_filter_college_name = $this->request->getVar('college_filter_college');

        //  echo $college_filter_college_name;

        if (isset($college_filter_state_name) && !empty($college_filter_state_name)) {
            $college_filter_state = $college_filter_state_name;
        } else {
            $college_filter_state = '';
        }


        $data_location = $Common_model->can_college_all_state_district($college_filter_state);
        // print_r($data_location);

        $state_location = '';
        // print_r($data_location);exit();
        if (!empty($data_location)) {
            foreach ($data_location as $location) {
                $state_location = $state_location . "<option value='" . $location->id . "'>" . $location->college_name . "</option>";
            }
        }




        if (isset($college_filter_college_name) && !empty($college_filter_college_name)) {
            $college_filter_college = $college_filter_college_name;
        } else {
            $college_filter_college = '';
        }
        // echo $college_filter_college;

        $collegedata_location = $Common_model->candidate_college_state_details($college_filter_college);

        $college_location = '';
        // print_r($data_location);exit();
        if (!empty($collegedata_location)) {
            foreach ($collegedata_location as $data_location) {
                $college_location = $college_location . "<option value='" . $data_location->state_id . "'>" . $data_location->name . "</option>";
            }
        }

        echo csrf_hash() . '^' . $state_location . '^' . $college_location;
    }

    public function set_college_state_filters()
    {
        $session = session();
        $college_filter_state = $this->request->getVar('college_filter_state_id_value');
        $college_filter_college = $this->request->getVar('college_filter_college_id_value');

        if (!empty($college_filter_state)) {
            $college_filter_state_arr = explode(',', $college_filter_state);
            $session->set('college_filter_state', $college_filter_state_arr);
        } else {
            $session->set('college_filter_state', '');
        }
        if (!empty($college_filter_college)) {
            $college_filter_college_arr = explode(',', $college_filter_college);
            $session->set('college_filter_college', $college_filter_college_arr);
        } else {
            $session->set('college_filter_college', '');
        }

        echo csrf_hash() . '^' . 1;
    }

    public function unset_college_state_filters()
    {
        $session = session();

        $ses_data = [
            // 'profile_state',
            'college_filter_state',
            'college_filter_college',

        ];

        $session->remove($ses_data);
        return redirect()->to('admin-dashboard-college-state');
    }

    public function all_transactions_list()
    {
        $Common_model = new Common_model();
        $data['type'] = 2;

        $where_all_count = array('status' => '1');
        $data['list_all_count'] = $Common_model->data_count_fetch('can_payment_details_history', $where_all_count);
        $data['list_all_transaction_count']  = (!empty($data['list_all_count'])) ? $data['list_all_count'] : 0;

        $where_all_count = array('status' => '1', 'payment_status' => 'created');
        $data['list_all_created_count'] = $Common_model->data_count_fetch('can_payment_details_history', $where_all_count);
        $data['list_created_transaction_count']  = (!empty($data['list_all_created_count'])) ? $data['list_all_created_count'] : 0;

        $where_all_count = array('status' => '1', 'payment_status' => 'captured');
        $data['list_all_captured_count'] = $Common_model->data_count_fetch('can_payment_details_history', $where_all_count);
        $data['list_captured_transaction_count']  = (!empty($data['list_all_captured_count'])) ? $data['list_all_captured_count'] : 0;

        $where_all_count = array('status' => '1', 'payment_status' => 'failed');
        $data['list_all_failed_count'] = $Common_model->data_count_fetch('can_payment_details_history', $where_all_count);
        $data['list_failed_transaction_count']  = (!empty($data['list_all_failed_count'])) ? $data['list_all_failed_count'] : 0;

        $where_all_count = array('status' => '1', 'payment_status' => 'pending');
        $data['list_all_pending_count'] = $Common_model->data_count_fetch('can_payment_details_history', $where_all_count);
        $data['list_pending_transaction_count']  = (!empty($data['list_all_pending_count'])) ? $data['list_all_pending_count'] : 0;

        $where_all_count = array('status' => '1', 'payment_status' => 'refunded');
        $data['list_all_refunded_count'] = $Common_model->data_count_fetch('can_payment_details_history', $where_all_count);
        $data['list_refunded_transaction_count']  = (!empty($data['list_all_refunded_count'])) ? $data['list_all_refunded_count'] : 0;


        $start_date = $this->request->getVar('transaction_filter_start_date');
        $end_date = $this->request->getVar('transaction_filter_end_date');

        if (!empty($_POST) && !empty($start_date) && !empty($end_date)) {

            $payment_status = $this->request->getVar('transaction_filter_status');
            $where_filter_count = array('status' => '1', 'payment_status' => $payment_status);
            $data['list_all_count_filter'] = $Common_model->data_count_fetch('can_payment_details_history', $where_filter_count);
            $data['list_all_transaction_count_filter']  = (!empty($data['list_all_count_filter'])) ? $data['list_all_count_filter'] : 0;
            $data['start_date_selected'] = $start_date;
            $data['end_date_selected'] = $end_date;
            $data['payment_status_selected'] = $payment_status;
            $where = array('status' => '1', 'payment_status!=' => 'initiated');
            $data['all_transaction_list'] = $Common_model->get_transacion_list_by_filter($start_date, $end_date, $payment_status, $where);
        } else {
            $data['start_date_selected'] = '';
            $data['end_date_selected'] = '';
            $data['payment_status_selected'] = '';
            $order_by_reason = array('ordercolumn' => 'id', 'ordertype' => 'desc');
            $where = array('status' => '1', 'payment_status!=' => 'initiated');
            $data['all_transaction_list'] = $Common_model->fetch_table_data_for_all('can_payment_details_history', $where, $order_by_reason);
        }


        return view('admin/all_transactions_list', $data);
    }




    public function transaction_status_update()
    {
        $Common_model = new Common_model();
        $session = session();



        if (!empty($_SESSION['razorpay_order_id'])) {
            if ($_POST) {
                $order_id = $this->request->getVar('search_order_id');
            } else {
                $order_id = $_SESSION['razorpay_order_id'];
            }
        } else {
            $order_id = $this->request->getVar('search_order_id');
        }

        $data[] = "";

        //if(!empty($_POST) && !empty($order_id)){
        if (!empty($_SESSION['razorpay_order_id'])) {
            $data['filter_order_id'] = $order_id;
            $data['transaction_status_detail'] = $Common_model->get_transacion_status_by_filter($order_id);

            // $api = new Api("rzp_test_apAaBShG1heg3Y", "QS5IZZhFWsu1OjCkGdtBBwNj"); //Test
            $api = new Api("rzp_live_U5JHzi0BdrUlj9", "QyXs5C16bbcrQeYbA07QZgVF"); //Live
            //$paymentId = "pay_LVHvYzzXa7jkjm";
            //$paymentId = "order_LUvBOdophRkOKB";

            // $method = "GET";
            //$url = 'https://api.razorpay.com/v1/orders/' . $order_id;   
            // $re_model = new Request();
            //$all22 = $re_model->request($method, $url, $data = array());
            if (!empty($data['transaction_status_detail'])) {
                $all = $api->order->fetch($order_id)->payments();

                //echo "<pre>";
                //$all2 = $api->webhook->all($options);
                //$optionsid = "Lej16Q07X9tj06";
                //$all3 = $api->webhook->fetch($optionsid);                                          
                //print_r($all3);
                //print_r($all2['items']); exit();    



                $data['raz_transaction_status_detail'] = $all['items'];
            } else {
                $data['raz_transaction_status_detail'] = "";
            }
        }
        return view('admin/transaction_status_details', $data);
    }
    public function set_transaction_status_search_filter()
    {
        $session = session();
        $search_order_id = $this->request->getVar('search_order_id');
        $session->set('razorpay_order_id', $search_order_id);
        echo csrf_hash() . '^' . 1;
    }
    public function clear_transaction_status_search_filter()
    {
        $session = session();
        $ses_data = ['razorpay_order_id'];
        $session->remove($ses_data);
        return redirect()->to('/transactions_status');
    }
    public function payment_status_update()
    {

        $Common_model = new Common_model();
        $current_datetime = $Common_model->current_datetime();
        $order_id = $this->request->getVar('payment_order_id');
        $paymentId = $this->request->getVar('payment_payment_id');
        $payment_status = $this->request->getVar('payment_status');
        $data[] = "";

        //$order_id = "order_LY2Dl33wNYoAbu";
        //$payment_status = "refund";
        //$paymentId = "pay_LY2DqtlbvwfQa4";

        if ($payment_status == "refund") {
            // $api = new Api("rzp_test_apAaBShG1heg3Y", "QS5IZZhFWsu1OjCkGdtBBwNj"); //Test
            $api = new Api("rzp_live_U5JHzi0BdrUlj9", "QyXs5C16bbcrQeYbA07QZgVF"); //Live
            $paymentid_arr = array('payment_id' => $paymentId);
            $all = $api->payment->refund($paymentid_arr);
            $all_orders = $api->order->fetch($order_id)->payments();
            foreach ($all_orders['items'] as $orders_detail) {
                if ($orders_detail['status'] == "refunded") {
                    $allrefund = $api->payment->refunds($orders_detail['id']);
                    $where_history = array('payment_id' => $orders_detail['id'], 'order_id' => $orders_detail['order_id']);

                    $data_history = [
                        //'payment_id' => $payment_id,
                        //'payment_date' => $payment_date,
                        //'payment_amount' => $payment_amount,
                        'payment_status' => $orders_detail['status'],
                        'refund_id' => $allrefund['items'][0]['id'],
                        'refund_amount' => $orders_detail['amount_refunded'] / 100,
                        'refund_date' => $current_datetime,
                        'refund_ref_no' => $orders_detail['refund_status'],
                        //'created_at' => $current_datetime
                    ];
                    $Common_model->update_commen('can_payment_details_history', $where_history, $data_history);
                }
            }

            //return view('admin/transaction_status_details',$data);  
            return redirect()->to('transactions_status');
        } else {

            // $api = new Api("rzp_test_apAaBShG1heg3Y", "QS5IZZhFWsu1OjCkGdtBBwNj"); //Test
            $api = new Api("rzp_live_U5JHzi0BdrUlj9", "QyXs5C16bbcrQeYbA07QZgVF"); //Live
            $paymentid_arr = array('payment_id' => $paymentId);
            $all_orders = $api->order->fetch($order_id)->payments();
            $Common_model = new Common_model();
            foreach ($all_orders['items'] as $orders_detail) {

                if ($orders_detail['status'] == "captured") {

                    $userid = $orders_detail['notes']->user_id;
                    $payment_id = $orders_detail['id'];
                    $p_dates = $orders_detail['created_at'];
                    $payment_date = date("d-m-Y h:i:sa", $p_dates);
                    $payment_amount = $orders_detail['amount'] / 100;
                    $payment_ex_date = date("Y-m-d", $p_dates);
                    $expiry_date = date("Y-m-d", strtotime(($payment_ex_date) . " + 1 year"));

                    $invoice_no = $this->get_invoice_no();

                    $where = array('userid' => $userid);
                    $data_ins = [
                        'payment_status' => "1",
                        'payment_amount' => $payment_amount,
                        'payment_date' => $payment_date,
                        'payment_expiry_date' => date("Y-m-d", strtotime($expiry_date . " - 1 day")),
                        'payment_id' => $payment_id
                    ];
                    $Common_model->update_commen('can_personal_details', $where, $data_ins);

                    $where_history = array('userid' => $userid, 'order_id' => $orders_detail['order_id']);
                    $data_history = [
                        'payment_id' => $payment_id,
                        'payment_date' => $payment_date,
                        'payment_amount' => $payment_amount,
                        'payment_status' => $orders_detail['status'],
                        'payment_method' => $orders_detail['method'],
                        'card_id' => $orders_detail['card_id'],
                        'bank' => $orders_detail['bank'],
                        'wallet' => $orders_detail['wallet'],
                        'vpa' => $orders_detail['vpa'],
                        'captured' => $orders_detail['captured'],
                        'error_code' => $orders_detail['error_code'],
                        'error_description' => $orders_detail['error_description'],
                        'error_source' => $orders_detail['error_source'],
                        'error_reason' => $orders_detail['error_reason'],
                        'invoice_no' => $invoice_no,
                        //'created_at' => $current_datetime
                    ];

                    $Common_model->update_commen('can_payment_details_history', $where_history, $data_history);

                    $data_inv = [
                        'userid' => $userid,
                        'invoice_no' => $invoice_no,
                        'merchant_order_id' => $orders_detail['notes']->merchant_order_id,
                        'merchant_txn_id' => $orders_detail['notes']->merchant_trans_id,
                        'order_id' => $orders_detail['order_id'],
                        'payment_id' => $payment_id,
                        'payment_date' => $payment_date,
                        'payment_amount' => $payment_amount,
                        'payment_status' => $orders_detail['status'],
                        'payment_method' => $orders_detail['method'],
                        'payment_wallet' => $orders_detail['wallet'],
                        'created_at' => $current_datetime
                    ];

                    $Common_model->insert_commen('invoice_details', $data_inv);


                    return redirect()->to('transactions_status');
                }
            }
        }

        return view('admin/transaction_status_details', $data);
    }




    public function rating_approval()
    {
        $Common_model = new Common_model();
        $start_date = $this->request->getVar('transaction_filter_start_date');
        $end_date = $this->request->getVar('transaction_filter_end_date');
        $company = $this->request->getVar('company');
        $internship = $this->request->getVar('internship');
        $ratings = $this->request->getVar('ratings');






        if (!empty($_POST) && !empty($start_date) && !empty($end_date)) {


            $data['start_date_selected'] = $start_date;
            $data['end_date_selected'] = $end_date;
            $data['company_selected'] = $company;
            $data['internship_selected'] = $internship;
            $data['ratings_selected'] = $ratings;


            if ((!empty($company)) || (!empty($internship))) {
                $wherei = array('company_id' => $company, 'status' => '1');
                $order_by1 = array('ordercolumn' => 'created_at', 'ordertype' => 'desc');
                $internship_arr = $Common_model->fetch_table_data_for_company('employer_post_internship', $wherei, $order_by1);
                $data['internship'] = $internship_arr;
            }


            $pager = service('pager');
            $page = (int) $this->request->getGet('page'); // 
            //$limit = 1000; // see Config/Pager.php
            $limit = config('Pager')->perPage_rating; // see Config/Pager.php
            if (!isset($page) || $page === 0 || $page === 1) {
                $page = 1;
                $start_id = 0;
            } else {
                $start_id = ($page - 1) * $limit;
                $page = $page;
            }

            $where_rat = array('can_applied_internship.can_ratings!=' => '0');
            $all_rating_data = $Common_model->fetch_rating_data_all('can_applied_internship', $where_rat, $start_date, $end_date, $company, $internship, $ratings);

            if (!empty($all_rating_data)) {
                $total   = count($all_rating_data);
            } else {
                $total   = 0;
            }

            $pager_links = $pager->makeLinks($page, $limit, $total, 'custom_pagination');
            $data['pager_links'] = $pager_links;
            $previous = '';
            if (isset($_SERVER['HTTP_REFERER'])) {
                $previous = $_SERVER['HTTP_REFERER'];
            }

            $where_rat = array('can_applied_internship.can_ratings!=' => '0');
            $data['rating_data'] = $Common_model->fetch_rating_data('can_applied_internship', $where_rat, $limit, $start_id, $start_date, $end_date, $company, $internship, $ratings);
        } else {
            $data['start_date_selected'] = '';
            $data['end_date_selected'] = '';
            $data['company_selected'] = '';
            $data['internship_selected'] = '';
            $data['ratings_selected'] = '';



            $where_rat = array('can_applied_internship.can_ratings!=' => '0');
            $all_rating_data =  $Common_model->fetch_rating_data('can_applied_internship', $where_rat);

            $pager = service('pager');
            $page = (int) $this->request->getGet('page'); // 
            $limit = config('Pager')->perPage_rating; // see Config/Pager.php
            if (!isset($page) || $page === 0 || $page === 1) {
                $page = 1;
                $start_id = 0;
            } else {
                $start_id = ($page - 1) * $limit;
                $page = $page;
            }

            if (!empty($all_rating_data)) {
                $total   = count($all_rating_data);
            } else {
                $total   = 0;
            }
            $pager_links = $pager->makeLinks($page, $limit, $total, 'custom_pagination');
            $data['pager_links'] = $pager_links;
            $previous = '';
            if (isset($_SERVER['HTTP_REFERER'])) {
                $previous = $_SERVER['HTTP_REFERER'];
            }

            $where_rat = array('can_applied_internship.can_ratings!=' => '0');
            $data['rating_data'] = $Common_model->fetch_rating_data('can_applied_internship', $where_rat, $limit, $start_id);
        }
        $where = array('can_applied_internship.can_ratings!=' => '0');
        $data['company_name'] = $Common_model->fetch_table_data_rating_company('can_applied_internship', $where);


        //echo "<pre>";
        //print_r($data['company_name']); exit();
        return view('admin/rating_approval', $data);
    }

    public function update_approve_rating_status()
    {


        $id = $this->request->getVar('id');
        $Common_model = new Common_model();
        $where = array('id' => $id);
        $data = ['rating_status' => '1'];
        $update_rating_status = $Common_model->update_commen('can_applied_internship', $where, $data);
        if ($update_rating_status) {
            echo csrf_hash() . '^' . 1;
        }
    }


    public function get_internship_by_company()
    {
        // $model = new Employer_model();
        $company_id = $this->request->getVar('company_id');

        $Common_model = new Common_model();
        $where = array('company_id' => $company_id, 'status' => '1');
        $order_by1 = array('ordercolumn' => 'created_at', 'ordertype' => 'desc');
        $internship = $Common_model->fetch_table_data_for_company('employer_post_internship', $where, $order_by1);

        //print_r($internship);exit;
        $getdates = '';
        $dates    = '';
        if (!empty($internship)) {

            foreach ($internship as $as) {

                if (isset($as->profile) && $as->profile != '0') {
                    $profile = $Common_model->get_master_name('master_profile', $as->profile, 'profile');
                } else {
                    $profile =  $as->other_profile;
                }
                // echo $profile; 
                $getdates = $getdates . "<option value='" . $as->internship_id . "' >" . $profile . "</option>";
            }
            //exit();
        }
        $dates = "<select name='internship' id='internship' class='form-control f-14 border-left-0'>
                      <option value='' style='color:#bfbfbf;' >--Select Internship--</option>
                                    " . $getdates . "                           
                            </select> ";
        echo csrf_hash() . '^' . $dates;
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


    public function employer_approval()
    {
        $Common_model = new Common_model();
        $where4 = array('usertype' => '2');
        $order_by = array('ordercolumn' => 'created_at', 'ordertype' => 'desc');
        $data['employer_data'] = $Common_model->fetch_table_data_for_all('userlogin', $where4, $order_by);
        return view('admin/employer_approval', $data);
    }

    public function update_approve_employer_status()
    {
        $session         = session();

        $id = $this->request->getVar('id');
        $status = $this->request->getVar('status');
        $Common_model = new Common_model();
        $where = array('id' => $id);
        $data = ['status' => $status];
        $update_employer_status = $Common_model->update_commen('userlogin', $where, $data);
        $where = array('id' => $id);
        $available_data = $Common_model->table_row_common_userlogin('userlogin', $where);
        if ($update_employer_status) {
            if ($status == 1) {
                $current_year = date('Y');
                $message = '{"name" : "' . $available_data->candidate_firstname . '", "user_type" : "employer" ,"year" : "' . $current_year . '"  }'; //dynamic contents for template
                $subject  = 'Welcome to Internme - Your Account has been Confirmed!';
                $to_email  = $available_data->email;
                $from_content = 'Welcome to Internme - Your Account has been Confirmed!';
                $template_key = '2d6f.456f260c51ab9602.k1.a2f3c2d0-a780-11ed-bfa0-525400fcd3f1.1862fe2e77d';
                $this->email_send($message, $subject, $to_email, $from_content, $template_key);
                $session->setFlashdata('error_status', '2');
                $session->setFlashdata('error_msg', 'Approved successfully');
            } else {
                $current_year = date('Y');
                $message = '{"name" : "' . $available_data->candidate_firstname . '", "user_type" : "employer" ,"year" : "' . $current_year . '"  }'; //dynamic contents for template
                $subject  = 'Account Rejection Notification';
                $to_email  = $available_data->email;
                $from_content = 'Account Rejection Notification';
                $template_key = '2d6f.456f260c51ab9602.k1.e24ca6a0-15a7-11ee-9654-525400d4bb1c.18901c97e0a';
                $this->email_send($message, $subject, $to_email, $from_content, $template_key);
                $session->setFlashdata('error_status', '2');
                $session->setFlashdata('error_msg', 'Rejected successfully');
            }
            echo csrf_hash() . '^' . 1;
        }
    }

    public function update_activate_employer_status()
    {

        $session         = session();
        $id = $this->request->getVar('id');
        $status = $this->request->getVar('status');
        $Common_model = new Common_model();
        $where = array('id' => $id);
        $data = ['active_status' => $status];
        $update_employer_status = $Common_model->update_commen('userlogin', $where, $data);
        $where = array('id' => $id);
        $available_data = $Common_model->table_row_common_userlogin('userlogin', $where);

        $where = array('company_id' => $available_data->company_id);
        $data = ['view_status' => $status];
        $update_post_status = $Common_model->update_commen('employer_post_internship', $where, $data);
        if ($update_employer_status) {
            if ($status == 1) {
                $current_year = date('Y');
                $message = '{"name" : "' . $available_data->candidate_firstname . '", "user_type" : "employer" ,"year" : "' . $current_year . '"  }'; //dynamic contents for template
                $subject  = 'Account Reactivation Notification - Welcome Back!';
                $to_email  = $available_data->email;
                $from_content = 'Account Reactivation Notification - Welcome Back!';
                $template_key = '2d6f.456f260c51ab9602.k1.24b97da0-15a4-11ee-9654-525400d4bb1c.18901b0fa7a';
                $this->email_send($message, $subject, $to_email, $from_content, $template_key);
                $session->setFlashdata('error_status', '2');
                $session->setFlashdata('error_msg', 'Activated successfully');
            } else {
                $current_year = date('Y');
                $message = '{"name" : "' . $available_data->candidate_firstname . '", "user_type" : "employer" ,"year" : "' . $current_year . '"  }'; //dynamic contents for template
                $subject  = 'Account Deactivation Notification';
                $to_email  = $available_data->email;
                $from_content = 'Account Deactivation Notification';
                $template_key = '2d6f.456f260c51ab9602.k1.08eb3970-15a3-11ee-9654-525400d4bb1c.18901a9b687';
                $this->email_send($message, $subject, $to_email, $from_content, $template_key);
                $session->setFlashdata('error_status', '2');
                $session->setFlashdata('error_msg', 'Deactivated successfully');
            }
            echo csrf_hash() . '^' . 1;
        }
    }


    public function admin_view_emp_profile($emp_id)
    {
        $Common_model = new Common_model();
        $where = array('userid' => $emp_id);
        $data['emp_profile'] = $Common_model->fetch_table_row('profile_completion_form', $where);
        if (!empty($data['emp_profile'])) {
            return view('admin/admin_view_emp_profile', $data);
        } else {
            return view('Common/404');
        }
    }


    public function can_payment_details_upload()
    {
        $Common_model = new Common_model();
        $where = array();
        $data['bulk_data'] = $Common_model->fetch_table_data('can_bulk_payment_details', $where);
        return view('admin/can_payment_details_upload', $data);
    }

    public function can_payment_details_upload_excel()
    {
        extract($_REQUEST);
        $Common_model = new Common_model();
        $current_datetime = $Common_model->current_datetime();
        $file_name         = $this->request->getFile('file');


        $reader     = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet     = $reader->load($file_name);
        $sheet_data     = $spreadsheet->getActiveSheet()->toArray();
        $temp_id = date('ymdhis') . rand(111, 999);
        $i = 0;
        $row = 1;
        // echo "<pre>";print_r($sheet_data);exit;
        foreach ($sheet_data as  $key => $count_table_detail) {

            if ($row > 1) {
                if (!empty($count_table_detail[1])) {
                    $total_row_arr[] = $row;
                }
            }
            $row++;
        }
        $total_filed_cell = (!empty($total_filed_row)) ? count($total_filed_row) : 0;
        $data_history_ins = [
            'payment_upload_id' => $temp_id,
            'can_count' => count($total_row_arr),
            'created_at' => $current_datetime
        ];
        $Common_model->insert_commen('can_bulk_payment_details', $data_history_ins);

        foreach ($sheet_data as  $key => $table_detail) {
            if ($i > 0) {
                if (!empty($table_detail[1])) {
                    $p_dates = $table_detail[4];
                    $payment_date = date("d-m-Y h:i:sa", strtotime($p_dates));
                    $payment_ex_date = date("Y-m-d", strtotime($p_dates));
                    $expiry_date = date("Y-m-d", strtotime(($payment_ex_date) . " + 1 year"));


                    $where_can = array('usertype' => '1', 'mobile' => $table_detail[1]);
                    $user_data = $Common_model->fetch_table_row('userlogin', $where_can);
                    // print_r($user_data);exit;
                    $invoice_no = $this->get_invoice_no();
                    $where = array('userid' => $user_data->userid);
                    $data_ins = [
                        'payment_status' => "1",
                        'payment_amount' => $table_detail[3],
                        'payment_date' => $payment_date,
                        'payment_expiry_date' => date("Y-m-d", strtotime($expiry_date . " - 1 day")),
                        'payment_package_type' => $table_detail[2],
                        'payment_id' => $table_detail[5]
                    ];
                    $Common_model->update_commen('can_personal_details', $where, $data_ins);

                    $data_inv = [
                        'userid' => $user_data->userid,
                        'invoice_no' => $invoice_no,
                        'merchant_order_id' => $table_detail[5],
                        'merchant_txn_id' => $table_detail[5],
                        'order_id' => $table_detail[5],
                        'payment_id' => $table_detail[5],
                        'payment_date' => $payment_date,
                        'payment_amount' => $table_detail[3],
                        'payment_status' => 'captured',
                        'payment_method' => 'offline',
                        'payment_upload_id' => $temp_id,
                        'created_at' => $table_detail[4]
                    ];
                    $Common_model->insert_commen('invoice_details', $data_inv);


                    $data_history_ins = [
                        'userid' => $user_data->userid,
                        'merchant_order_id' => $table_detail[5],
                        'merchant_txn_id' => $table_detail[5],
                        'order_id' => $table_detail[5],
                        'payment_id' => $table_detail[5],
                        'payment_date' => $payment_date,
                        'payment_amount' => $table_detail[3],
                        'payment_status' => 'captured',
                        'payment_method' => 'offline',
                        'captured' => '1',
                        'invoice_no' => $invoice_no,
                        'create_mode' => "4",
                        'payment_upload_id' => $temp_id,
                        'created_at' => $table_detail[4]
                    ];
                    $Common_model->insert_commen('can_payment_details_history', $data_history_ins);
                }
            }
            $i++;
        }

        return redirect()->to('can_payment_details_upload');
    }

    public function view_bulk_payment_details($id)
    {
        $Common_model = new Common_model();

        $order_by_reason = array('ordercolumn' => 'id', 'ordertype' => 'desc');
        $where = array('create_mode' => '4', 'payment_upload_id' => $id);
        $data['all_transaction_list'] = $Common_model->fetch_table_data_for_all('can_payment_details_history', $where, $order_by_reason);

        return view('admin/bulk_transactions_list', $data);
    }
    public function bi_url()
    {

        return view('admin/bi_url');
    }

    // common function for send email - Zoho
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



    public function phonepe_transaction_status_update()
    {
        $Common_model = new Common_model();
        $client = new Client();
        $session = session();



        if (!empty($_SESSION['razorpay_order_id'])) {
            if ($_POST) {
                $order_id = $this->request->getVar('search_order_id');
            } else {
                $order_id = $_SESSION['razorpay_order_id'];
            }
        } else {
            $order_id = $this->request->getVar('search_order_id');
        }

        $data[] = "";

        //if(!empty($_POST) && !empty($order_id)){
        if (!empty($_SESSION['razorpay_order_id'])) {
            $data['filter_order_id'] = $order_id;
            $data['transaction_status_detail'] = $Common_model->phonepe_get_transacion_status_by_filter($order_id);
            // print_r($data['transaction_status_detail']);exit;
            if (!empty($data['transaction_status_detail'])) {
                // $merchantId = 'PGTESTPAYUAT93'; //testing
                $merchantId = 'UNWINDONLINE'; //live
                // $merchantId = 'UNWINDONLINEUAT'; //staging



                //    $saltKey = '875126e4-5a13-4dae-ad60-5b8c8b629035'; // Replace with your actual salt key testing
                $saltKey = '1d8fd59d-5d46-4d01-8303-f274e1b7799d'; // Replace with your actual salt key live
                // $saltKey = 'c6cae9ff-9bfe-407f-9efb-31904cea5afa'; // Replace with your actual salt key staging
                $saltIndex = '1';

                // Construct the string to be hashed
                $stringToHash = "/pg/v1/status/" . $merchantId . "/" . $order_id . $saltKey;

                // Calculate the SHA-256 hash
                $sha256Hash = hash('sha256', $stringToHash);

                // Combine the hash with "###" and salt index
                $finalString = $sha256Hash . '###' . $saltIndex;
                $response = $client->request('GET', 'https://api.phonepe.com/apis/hermes/pg/v1/status/' . $merchantId . '/' . $order_id, [
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
                $data['raz_transaction_status_detail'] = $responseData;
                // print_r($data['raz_transaction_status_detail']);exit;
            } else {
                $data['raz_transaction_status_detail'] = "";
            }
        }
        return view('admin/phonepe_transaction_status_details', $data);
    }
    public function phonepe_set_transaction_status_search_filter()
    {
        $session = session();
        $search_order_id = $this->request->getVar('search_order_id');
        $session->set('razorpay_order_id', $search_order_id);
        echo csrf_hash() . '^' . 1;
    }
    public function phonepe_clear_transaction_status_search_filter()
    {
        $session = session();
        $ses_data = ['razorpay_order_id'];
        $session->remove($ses_data);
        return redirect()->to('/phonepe_transactions_status');
    }


    public function phonepe_payment_status_update()
    {

        $Common_model = new Common_model();
        $current_datetime = $Common_model->current_datetime();
        $order_id = $this->request->getVar('payment_order_id');
        $paymentId = $this->request->getVar('payment_payment_id');
        $payment_status = $this->request->getVar('payment_status');
        $payment_amount = $this->request->getVar('amount');
        $data[] = "";

        //$order_id = "order_LY2Dl33wNYoAbu";
        //$payment_status = "refund";
        //$paymentId = "pay_LY2DqtlbvwfQa4";
        $rorder_id = $this->get_merchant_refund_order_id();
        if ($payment_status == "PAYMENT_REFUND") {

            $client = new Client();
            // Define the request data as an associative array
            $requestData = [
                // 'merchantId' => 'PGTESTPAYUAT93',  // Replace with your actual request data testing
                'merchantId' => 'UNWINDONLINE',  // Replace with your actual request data live
                // 'merchantId' => 'UNWINDONLINEUAT',  // Replace with your actual request data staging

                'originalTransactionId' => $order_id,
                'merchantTransactionId' => $rorder_id,
                'merchantUserId' => 'M1WPJTNIJA5F',  // Replace with your actual request data
                'amount' => $payment_amount * 100,

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
            $concatenatedString = $payload . '/pg/v1/refund' . $saltKey;

            // Calculate the SHA-256 hash
            $sha256Hash = hash('sha256', $concatenatedString);

            // Combine the hash with "###" and salt index
            $finalString = $sha256Hash . '###' . $saltIndex;

            // Now $finalString contains the desired result
            // live https://api.phonepe.com/apis/hermes/pg/v1/pay
            //testing https://api-preprod.phonepe.com/apis/pg-sandbox/pg/v1/pay
            $response = $client->request('POST', 'https://api.phonepe.com/apis/hermes/pg/v1/refund', [
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
            $responseData = json_decode($responseBody, true);
            // print_r($responseBody);exit;
            $payment_amount = $responseData['data']['amount'] / 100;
            $where_history = array('order_id' => $order_id);
            $data_history = [
                'refund_payment_id' => $responseData['data']['transactionId'],
                'refund_order_id' => $responseData['data']['merchantTransactionId'],
                'refund_amound' => $payment_amount,
                'refund_status' => $responseData['code'],
                'refund_message' => $responseData['message'],
                'refund_state' => $responseData['data']['state'],
                'refund_responseCode' => $responseData['data']['responseCode'],
            ];
            $his_result = $Common_model->update_commen('can_payment_details_history_phonepe', $where_history, $data_history);
            return redirect()->to('phonepe_transactions_status');
        } else {
        }

        return view('admin/transaction_status_details', $data);
    }

    function get_merchant_refund_order_id()
    {
        $date = date("dmy");
        $pass = substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 3);
        $merchant_order_id = "RINT-MER-" . $date . $pass . time();
        return $merchant_order_id;
    }

    public function all_phonepe_transactions_list()
    {
        $Common_model = new Common_model();
        $data['type'] = 2;

        $where_all_count = array('status' => '1', 'payment_status!=' => 'PAYMENT_INITIATED');
        $data['list_all_count'] = $Common_model->data_count_fetch('can_payment_details_history_phonepe', $where_all_count);
        $data['list_all_transaction_count']  = (!empty($data['list_all_count'])) ? $data['list_all_count'] : 0;

        $where_all_count = array('status' => '1', 'payment_status' => 'PAYMENT_SUCCESS');
        $data['list_all_captured_count'] = $Common_model->data_count_fetch('can_payment_details_history_phonepe', $where_all_count);
        $data['list_captured_transaction_count']  = (!empty($data['list_all_captured_count'])) ? $data['list_all_captured_count'] : 0;

        $where_all_count = array('status' => '1', 'payment_status' => 'PAYMENT_ERROR');
        $data['list_all_failed_count'] = $Common_model->data_count_fetch('can_payment_details_history_phonepe', $where_all_count);
        $data['list_failed_transaction_count']  = (!empty($data['list_all_failed_count'])) ? $data['list_all_failed_count'] : 0;

        $where_all_count = array('status' => '1', 'refund_status' => 'PAYMENT_SUCCESS');
        $data['list_all_refunded_count'] = $Common_model->data_count_fetch('can_payment_details_history_phonepe', $where_all_count);
        $data['list_refunded_transaction_count']  = (!empty($data['list_all_refunded_count'])) ? $data['list_all_refunded_count'] : 0;


        $start_date = $this->request->getVar('transaction_filter_start_date');
        $end_date = $this->request->getVar('transaction_filter_end_date');

        if (!empty($_POST) && !empty($start_date) && !empty($end_date)) {

            $payment_status = $this->request->getVar('transaction_filter_status');
            $where_filter_count = array('status' => '1', 'payment_status' => $payment_status);
            $data['list_all_count_filter'] = $Common_model->data_count_fetch('can_payment_details_history_phonepe', $where_filter_count);
            $data['list_all_transaction_count_filter']  = (!empty($data['list_all_count_filter'])) ? $data['list_all_count_filter'] : 0;
            $data['start_date_selected'] = $start_date;
            $data['end_date_selected'] = $end_date;
            $data['payment_status_selected'] = $payment_status;
            $where = array('status' => '1', 'payment_status!=' => 'PAYMENT_INITIATED');
            $data['all_transaction_list'] = $Common_model->get_phonepe_transacion_list_by_filter($start_date, $end_date, $payment_status, $where);
        } else {
            $data['start_date_selected'] = '';
            $data['end_date_selected'] = '';
            $data['payment_status_selected'] = '';
            $order_by_reason = array('ordercolumn' => 'id', 'ordertype' => 'desc');
            $where = array('status' => '1', 'payment_status!=' => 'PAYMENT_INITIATED');
            $data['all_transaction_list'] = $Common_model->fetch_table_data_for_all('can_payment_details_history_phonepe', $where, $order_by_reason);
        }


        return view('admin/all_phonepe_transactions_list', $data);
    }

    public function update_feature_employer_status()
    {

        $session         = session();
        $id = $this->request->getVar('id');
        $status = $this->request->getVar('status');
        $Common_model = new Common_model();
        $where = array('userid' => $id);
        $data = ['featured_status' => $status];
        $update_post_status = $Common_model->update_commen('profile_completion_form', $where, $data);
        if ($update_post_status) {

            echo csrf_hash() . '^' . 1;
        }
    }

    public function admin_blog()
    {
        $Common_model = new Common_model();
        $where = array('status' => '1');
        $order_by = array('ordercolumn' => 'name', 'ordertype' => 'ASC');
        $data['blog_category'] = $Common_model->fetch_table_data_for_all('master_blog_category', $where, $order_by);
        return view('admin/admin_blog',$data);
    }
    public function add_blog()
    {

        // print_r($_POST);exit;
        $session         = session();
        $Common_model = new Common_model();

        if (isset($_FILES['banner_image']['name']) && $_FILES['banner_image']['name'] != "") {
            $images = $this->request->getFile('banner_image');
            $image_name = $images->getRandomName();
            $images->move('public/assets/docs/uploads/banner_image/', $image_name);
        } else {
            $image_name = '';
        }

        if (isset($_FILES['cover_image']['name']) && $_FILES['cover_image']['name'] != "") {
            $images1 = $this->request->getFile('cover_image');
            $image_name1 = $images1->getRandomName();
            $images1->move('public/assets/docs/uploads/cover_image/', $image_name1);
        } else {
            $image_name1 = '';
        }
        $data = [
            'userid' => $session->get('userid'),
            'blog_title' => $this->request->getVar('blog_title'),
            'blog_category' => $this->request->getVar('blog_category'),
            'author_name' => $this->request->getVar('author_name'),
            'published_date' => $this->request->getVar('published_date'),
            'short_description' => $this->request->getVar('short_description'),
            'blog_content' => $this->request->getVar('blog_content'),
            'banner_image' => $image_name,
            'cover_image' => $image_name1,
            'created_at' => date('Y-m-d H:i:s')

        ];
        $result = $Common_model->insert_commen('admin_blog', $data);
        if($result){
            $session->setFlashdata('error_status', '2');
            $session->setFlashdata('error_msg', 'Blog Details Added Successfully');
            }
        return redirect()->to('blog-list');
    }
    public function blog_list()
    {
        $session         = session();
        $Common_model = new Common_model();
        $userid = $session->get('userid');
        $search_blog = $this->request->getVar('search_blog');

        $pager = service('pager');
        $page = (int) $this->request->getGet('page'); // 
        //$limit = 1000; // see Config/Pager.php
        $limit = config('Pager')->perPage_blog; // see Config/Pager.php
        if (!isset($page) || $page === 0 || $page === 1) {
            $page = 1;
            $start_id = 0;
        } else {
            $start_id = ($page - 1) * $limit;
            $page = $page;
        }
        $where = array('status' => '1','userid' => $userid);
        $order_by = array('ordercolumn' => 'id', 'ordertype' => 'desc');
        $blog_list = $Common_model->fetch_table_blog_data('admin_blog', $where, $order_by,$search_blog);
        if (!empty($blog_list)) {
            $total   = count($blog_list);
        } else {
            $total   = 0;
        }

        $pager_links = $pager->makeLinks($page, $limit, $total, 'custom_pagination');
        $data['pager_links'] = $pager_links;

        $where = array('status' => '1','userid' => $userid);
        $order_by = array('ordercolumn' => 'id', 'ordertype' => 'desc');
        $data['blog_list'] = $Common_model->fetch_table_blog_data('admin_blog', $where, $order_by,$search_blog, $limit, $start_id);
        if($search_blog!=''){
            $data['blog_search'] =$search_blog;
        }else{
            $data['blog_search'] ='';
        }
       
        return view('admin/blog_list',$data);
    }

    public function blog_preview($id)
    {
        $session         = session();
        $Common_model = new Common_model();
        $userid = $session->get('userid');
       

        $where = array('status' => '1','id' => $id);
        $data['blog_list'] = $Common_model->fetch_table_data_for_all('admin_blog', $where);

        return view('admin/blog_preview',$data);
    }

    public function blog_edit($id)
    {
        $session         = session();
        $Common_model = new Common_model();
        $userid = $session->get('userid');
       

        $where = array('status' => '1','id' => $id);
        $data['blog_list'] = $Common_model->fetch_table_data_for_all('admin_blog', $where);
        $where = array('status' => '1');
        $order_by = array('ordercolumn' => 'name', 'ordertype' => 'ASC');
        $data['blog_category'] = $Common_model->fetch_table_data_for_all('master_blog_category', $where, $order_by);
        return view('admin/blog_edit',$data);
    }

    public function update_blog()
    {

        // print_r($_POST);exit;
        $session         = session();
        $Common_model = new Common_model();
        $blog_id = $this->request->getVar('blog_id');

        if (isset($_FILES['banner_image']['name']) && $_FILES['banner_image']['name'] != "") {
            $images = $this->request->getFile('banner_image');
            $image_name = $images->getRandomName();
            $images->move('public/assets/docs/uploads/banner_image/', $image_name);
        } else {
            $image_name = $this->request->getVar('banner_image_hidden');
        }

        if (isset($_FILES['cover_image']['name']) && $_FILES['cover_image']['name'] != "") {
            $images1 = $this->request->getFile('cover_image');
            $image_name1 = $images1->getRandomName();
            $images1->move('public/assets/docs/uploads/cover_image/', $image_name1);
        } else {
            $image_name1 = $this->request->getVar('cover_image_hidden');
        }
        $data = [
           
            'blog_title' => $this->request->getVar('blog_title'),
            'blog_category' => $this->request->getVar('blog_category'),
            'author_name' => $this->request->getVar('author_name'),
            'published_date' => $this->request->getVar('published_date'),
            'short_description' => $this->request->getVar('short_description'),
            'blog_content' => $this->request->getVar('blog_content'),
            'banner_image' => $image_name,
            'cover_image' => $image_name1,
          

        ];
        $where = array('id' => $blog_id);
        $update_status = $Common_model->update_commen('admin_blog', $where, $data);
        if($update_status){
        $session->setFlashdata('error_status', '2');
        $session->setFlashdata('error_msg', 'Blog Details Updated Successfully');
        }
        // $result = $Common_model->insert_commen('admin_blog', $data);
        return redirect()->to('blog-preview/'.$blog_id);
    }


    public function update_blog_status()
    {
        $session         = session();

        $id = $this->request->getVar('id');
        $status = $this->request->getVar('status');
        $Common_model = new Common_model();

        if($status==2){
            $where = array('id' => $id);
            $blog_list = $Common_model->fetch_table_data_for_all('admin_blog', $where);

            unlink('public/assets/docs/uploads/banner_image/'.$blog_list[0]->banner_image);
            unlink('public/assets/docs/uploads/cover_image/'.$blog_list[0]->cover_image);

            $wheredel = array('id' => $id);
            $result = $Common_model->delete_commen('admin_blog', $wheredel);
            $session->setFlashdata('error_status', '2');
            $session->setFlashdata('error_msg', 'Deleted successfully');
        }else if($status==1){
            $where = array('id' => $id);
            $data = ['active_status' => $status];
            $update_employer_status = $Common_model->update_commen('admin_blog', $where, $data);
            $session->setFlashdata('error_status', '2');
            $session->setFlashdata('error_msg', 'Activated successfully');
        }else if($status==0){
            $where = array('id' => $id);
            $data = ['active_status' => $status];
            $update_employer_status = $Common_model->update_commen('admin_blog', $where, $data);
            $session->setFlashdata('error_status', '2');
            $session->setFlashdata('error_msg', 'Deactivated successfully');
        }
        echo csrf_hash() . '^' . 1;
        }

        public function update_exclusive_status()
    {
        $session         = session();

        $id = $this->request->getVar('id');
        $status = $this->request->getVar('status');
        $Common_model = new Common_model();
        $where = array();
        $data = ['exclusive_status' =>'0'];
        $update_employer_status1 = $Common_model->update_commen('admin_blog', $where, $data);
        
            $where = array('id' => $id);
            $data = ['exclusive_status' => $status];
            $update_employer_status1 = $Common_model->update_commen('admin_blog', $where, $data);
            $session->setFlashdata('error_status', '2');
            $session->setFlashdata('error_msg', 'Marked as Exclusive');

        echo csrf_hash() . '^' . 1;
        }
    
}
