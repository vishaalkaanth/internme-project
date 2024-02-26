<?php

namespace App\Controllers;

use App\Models\Employer_model;
use App\Models\LoginModel;
use App\Models\Common_model;
use App\Models\Candidate_model;

$this->Candidate_model = new Candidate_model();

use CodeIgniter\API\ResponseTrait;

class Api extends BaseController
{
    use ResponseTrait;
    public function __construct()
    {
        date_default_timezone_set('Asia/Kolkata');
    }


    public function insights_api()
    {
        $Common_model = new Common_model();
        $where1 = array();
        $total_candidate = $Common_model->data_count_fetch('can_personal_details', $where1);


        $where3 = array();
        $posted_internship = $Common_model->data_count_fetch('employer_post_internship', $where3);

        $where6 = array();
        $employers = $Common_model->data_count_fetch('profile_completion_form', $where6);
        $arr = array('total_candidates' => $total_candidate, 'total_internships' => $posted_internship, 'total_employers' => $employers);
        header('Content-Type: application/json');
        echo json_encode($arr);
    }

    public function getCandidateData($mobileNumber)
    {
        $response = [];
        //   print_r($mobileNumber);exit;
        // Validate that the mobile number is provided
        if (empty($mobileNumber)) {
            $response['status'] = '400';
            $response['message'] = 'Mobile number is required';
            // Set HTTP status code 400 (Bad Request)
            return $this->respond($response, 400);
        }

        // Load the Common_model (make sure it's properly defined in app/Models)
        $commonModel = new Common_model();

        // Fetch candidate details based on the mobile number
        $whereStatus = ['profile_phone_number' => $mobileNumber];
        $personalDetails = $commonModel->fetch_table_data('can_personal_details', $whereStatus);

        // Check if candidate details were found
        if ($personalDetails) {
            // Fetch applied internship data based on candidate ID
            $where = ['can_applied_internship.candidate_id' => $personalDetails[0]->userid];
            $orderBy = ['ordercolumn' => 'can_applied_internship.id', 'ordertype' => 'desc'];
            $appliedInternshipList = $commonModel->fetch_table_data_applied_candidate_api('can_applied_internship', $where, $orderBy);
            // print_r($appliedInternshipList);exit;
            // Construct the candidate data array
            $current_date = date("Y-m-d");
            $rearrangedData = [];

            if (isset($appliedInternshipList) && !empty($appliedInternshipList)) {
                foreach ($appliedInternshipList as $internship_list) {
                    if ($internship_list->pro == '0') {
                        $profile = $internship_list->other_profile;
                    } else {
                        $profile = $internship_list->profile;
                    }

                    if ($internship_list->stipend != '1') {
                        if (!empty($internship_list->amount_from) && $internship_list->amount_from != '0') {
                            $stipend = $internship_list->amount_from;
                        }
                        if (!empty($internship_list->amount_to) && $internship_list->amount_to != '0') {
                            $stipend =$internship_list->amount_from . ' - ' . $internship_list->amount_to;
                        }
                    } else {
                        $stipend = "Unpaid";
                    }

                    if ($internship_list->hiring_status == 1) {
                        if ($internship_list->internship_startdate <= $current_date) {

                            $status = 'Ongoing';
                        } else {
                            $status = 'Offer accepted';
                        }
                    } elseif ($internship_list->hiring_status == 2) {
                        $status = 'Offer declined';
                    } elseif ($internship_list->complete_status == 1) {
                        if ($internship_list->complete_type != 1) {
                            $status = 'Dropped';
                        } else {
                            $status = 'Completed';
                        }
                    } else {
                        if ($internship_list->application_status == 0) {
                            $status = 'Under review';
                        } elseif ($internship_list->application_status == 1) {
                            $status = 'Under review';
                        } elseif ($internship_list->application_status == 2) {
                            $status = 'Hired';
                        } elseif ($internship_list->application_status == 3) {
                            $status = 'Not Qualified';
                        }
                    }

                    $entry = [
                        'internship_name' => $profile,
                        'internship_startdate' => $internship_list->internship_startdate,
                        'company_name' => $internship_list->profile_company_name,
                        // 'location' => $internship_list->profile_company_name,
                        'stipend' => $stipend,
                        'internship_status' => $status,
                        'logo' => base_url().'/public/assets/docs/uploads/emp_profile/'.$internship_list->profile_company_logo,
                        // Add more candidate data fields as needed
                    ];

                    // Append the entry to the rearranged data array
                    $rearrangedData[] = $entry;
                }
            }

            // Send the candidate data as a JSON response
            $response['status'] = '200';
            $response['message'] = 'Success';
            $response['data'] = $rearrangedData;
            return $this->respond($response, 200);
        } else {
            $response['status'] = '404';
        $response['message'] = 'Candidate not found';

        // Send an error response with HTTP status code 404 (Not Found)
        return $this->respond($response, 404);
        }
    }

        public function candidate_assessment_result()
    {

        // Get JSON data from the request
        $data = $this->request->getJSON();
        // echo"<pre>";print_r($data);exit;
        $commonModel = new Common_model();
        $where = array('result_assessment_key' => $data->result_assessment_key);

            $in_data = [
                'result_assessment'   => $data->result_assessment,
                // 'result_assessment_key'  => $data->result_assessment_key,
                'result_user' => $data->result_user,
                'result_status' => $data->result_status,
                'result_start_date'  => $data->result_start_date,
                'result_end_date'  => $data->result_end_date,
                'result_categories'  => $data->result_categories,
                'result_questions'  => $data->result_questions,
                'result_question_time_spent'  => $data->result_question_time_spent,
                'result_assessment_time_spent'  => $data->result_assessment_time_spent,
                'result_question_score'  => $data->result_question_score,
                'result_assessment_score'  => $data->result_assessment_score,
                'result_assessment_percentage'  => $data->result_assessment_percentage,
            ];
            $assessment_id = $commonModel->update_commen('candidate_open_assessment', $where, $in_data);

            if(isset($assessment_id)) {


                $message = "Assessment Result has been Updated";
                $status = 200;
                $response = array('status' => $status, "message" => $message);

                return $this->respond($response);

            } else {

                $message = "Error on Updating the result to the user";
                $status = 400;
                $response = array('status' => $status, "message" => $message);
                return $this->respond($response);
            }
    }

    public function candidate_assessment_result_page()
    {
        return redirect()->to('candidate-open-assessment');
    }

}
