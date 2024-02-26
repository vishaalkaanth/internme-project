<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (is_file(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override(function() {
	return view('Common/404');
});
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
//$routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');
$routes->get('privacy-policy', 'Home::privacy_policy');
$routes->get('payment-policy', 'Home::payment_policy');
$routes->get('about', 'Home::about');
$routes->get('login', 'Login::userlogin');
$routes->get('userdevicedata', 'Login::userdevicedata');
$routes->post('loginAuth', 'Login::loginAuth');
$routes->get('adminlogin', 'Login::admin_login');
$routes->post('admin-loginAuth', 'Login::admin_loginAuth');
$routes->get('facultylogin', 'Login::faculty_login');
$routes->post('faculty-loginAuth', 'Login::faculty_loginAuth');
// $routes->get('dashboard', 'Login::dashboard');
$routes->get('register/(:any)', 'Login::emp_register/$1');
$routes->get('register-otp', 'Login::emp_register_otp');
$routes->get('set-password', 'Login::emp_set_password');

$routes->get('hr-register/(:any)/(:any)', 'Login::hr_register/$1/$2');
$routes->get('hr-register-otp', 'Login::hr_register_otp');
$routes->get('hr-set-password', 'Login::hr_set_password');


$routes->get('faculty-register-otp', 'Login::faculty_register_otp');
$routes->get('faculty-set-password', 'Login::faculty_set_password');


$routes->get('faculty-register/(:any)', 'Login::faculty_register/$1');
$routes->get('faculty-register-otp', 'Login::faculty_register_otp');
$routes->get('faculty-set-password', 'Login::faculty_set_password');

$routes->get('forgot-password/(:any)', 'Login::forgot_password/$1');
$routes->get('forgot-password-otp', 'Login::forgot_password_otp');
$routes->get('reset-password', 'Login::reset_password');
$routes->post('user_retrieve_password', 'Login::user_retrieve_password');
$routes->post('validate_user_otp/(:any)', 'Login::validate_user_otp/$1');
$routes->post('validate_user_otp_hr', 'Login::validate_user_otp_hr');
$routes->post('validate_user_otp_faculty', 'Login::validate_user_otp_faculty');
$routes->post('change_user_password/(:any)', 'Login::change_user_password/$1');
$routes->post('change_user_password_hr', 'Login::change_user_password_hr');
$routes->post('change_user_password_faculty', 'Login::change_user_password_faculty');
$routes->post('add_registration', 'Login::add_registration');
$routes->post('add_registration_hr', 'Login::add_registration_hr');
$routes->post('add_registration_faculty', 'Login::add_registration_faculty');
$routes->get('logout', 'Login::userlogout');
$routes->post('get_bussinuss_email_check', 'Login::get_bussinuss_email_check');
$routes->add('Resend-Otp/(:any)', 'Login::resend_otp/$1');
$routes->add('hr_supervisor_resend_otp/(:any)', 'Login::hr_supervisor_resend_otp/$1');
$routes->add('faculty_supervisor_resend_otp/(:any)', 'Login::faculty_supervisor_resend_otp/$1');
$routes->post('Check-User-OTP', 'Login::check_user_otp');
$routes->post('Forgot-Send-OTP', 'Login::forgot_send_otp');
$routes->get('main_login/(:any)', 'Login::main_login/$1');
$routes->get('succesfull-message', 'Login::succesfull_message');
$routes->get('insights-api', 'Api::insights_api');
$routes->get('getCandidate-appliedData/(:any)', 'Api::getCandidateData/$1');

$routes->get('store-candidate-assessment-result', 'Api::candidate_assessment_result');
$routes->get('candidate-assessment-result', 'Api::candidate_assessment_result_page');

// Candidate
$routes->get('search-internship', 'Candidate::can_intern_list');
$routes->post('search-internship', 'Candidate::can_intern_list');
$routes->post('set_candidate_filters', 'Candidate::set_candidate_filters');
$routes->get('unset_candidate_filters', 'Candidate::unset_candidate_filters');
$routes->get('internship-details/(:num)', 'Candidate::can_intern_single/$1');
$routes->get('personal-details', 'Candidate::can_profile_personal');
$routes->get('education-details', 'Candidate::can_profile_education');
$routes->get('can-profile-address', 'Candidate::can_profile_address');
$routes->get('experience-details', 'Candidate::can_profile_experience');
$routes->get('skill-details', 'Candidate::can_profile_skills');
$routes->get('work-sample-details', 'Candidate::can_profile_work');
$routes->post('update_can_personal_details', 'Candidate::update_can_personal_details');
$routes->post('can_mobile_send_otp', 'Candidate::can_profile_mobile_otp_send');
$routes->post('can_mobile_otp_verify', 'Candidate::can_mobile_otp_verify');
$routes->post('add_can_educational_details', 'Candidate::add_can_educational_details');
$routes->post('edit_can_educationa_details', 'Candidate::edit_can_educationa_details');
$routes->get('delete_can_educationa_details/(:num)', 'Candidate::delete_can_educationa_details/$1');
$routes->post('get_state_by_district_can', 'Candidate::get_state_by_district_can');
$routes->post('get_state_by_district_can_com', 'Candidate::get_state_by_district_can_com');
$routes->post('update_can_address_details', 'Candidate::update_can_address_details');
$routes->post('update_can_work_sample', 'Candidate::update_can_work_sample');
$routes->post('add_can_experience', 'Candidate::add_can_experience');
$routes->post('edit_can_experience', 'Candidate::edit_can_experience');
$routes->get('delete_common/(:num)/(:any)/(:any)', 'Candidate::delete_common/$1/$2/$3');
$routes->post('add_can_skills', 'Candidate::add_can_skills');
$routes->post('get_spec_by_courses', 'Candidate::get_spec_by_courses');
$routes->post('get_spec_by_courses_edit', 'Candidate::get_spec_by_courses_edit');
$routes->get('can-apply-for-internship/(:any)', 'Candidate::can_apply_for_internship/$1');
$routes->get('can-proceeds-apply/(:any)', 'Candidate::can_proceeds_apply/$1');
$routes->post('can_apply_internship', 'Candidate::can_apply_internship');
$routes->get('my-applications', 'Candidate::can_applied_intern_list');
$routes->get('my-applications/(:num)', 'Candidate::can_applied_intern_list/$1');
$routes->get('direct-corporate-offers', 'Candidate::can_offered_intern_list');
$routes->get('direct-corporate-offers/(:num)', 'Candidate::can_offered_intern_list/$1');
$routes->get('my-internships', 'Candidate::can_my_intern_list');
$routes->get('my-internships/(:num)', 'Candidate::can_my_intern_list/$1');
$routes->get('can_profile_edit/(:num)/(:any)/(:any)/(:any)/(:any)', 'Candidate::can_profile_edit/$1/$2/$3/$4/$5');
$routes->get('profile-details', 'Candidate::can_view_profile');
$routes->get('can_profile_remove_sess/(:num)', 'Candidate::can_profile_remove_sess/$1');
$routes->get('can_apply_intern_session/(:num)/(:any)/(:any)/(:any)/(:any)', 'Candidate::can_apply_intern_session/$1/$2/$3/$4/$5');
$routes->get('can_intership_bookmark_single/(:num)/(:any)/(:any)/(:any)/(:any)', 'Candidate::can_intership_bookmark_single/$1/$2/$3/$4/$5');
$routes->post('can_intership_bookmark', 'Candidate::can_intership_bookmark');
$routes->get('bookmark', 'Candidate::can_bookmark_list');
$routes->get('can_apply_before_intern_session/(:num)', 'Candidate::can_apply_before_intern_session/$1');
$routes->post('Keyword-Search', 'Candidate::keyword_search');
$routes->post('Keyword-Search-Candidate', 'Candidate::keyword_search_candidate');
$routes->add('Clear-Search-Filter', 'Candidate::clear_search_filter');
$routes->post('Accept-Hiring/', 'Candidate::accept_hiring/');
$routes->post('Reject-Hiring', 'Candidate::reject_hiring');
$routes->post('add_under_consideration', 'Candidate::add_under_consideration');
$routes->post('Check-Already-Confirmed', 'Candidate::check_already_confirmed');
$routes->post('get_can_mobile_email_edit', 'Candidate::get_can_mobile_email_edit');
$routes->add('gst-verification', 'Candidate::gst_verification');
$routes->get('change-password', 'Candidate::change_password');
$routes->post('User-Current-Password-Check', 'Candidate::user_current_password_check');
$routes->post('Save-Changed-Password', 'Candidate::save_changed_password');

$routes->post('can_profile_mobile_otp', 'Candidate::can_profile_mobile_otp');
$routes->post('mobile_otp_verify_edit', 'Candidate::mobile_otp_verify_edit');
$routes->post('update_candidate_work_location', 'Candidate::update_candidate_work_location');

$routes->add('gst-verification', 'Candidate::gst_verification');

$routes->add('Email-html-Test', 'Candidate::email_html_test');
$routes->get('change-password-success', 'Candidate::change_password_success');
$routes->get('dashboard', 'Candidate::can_dashboard');
$routes->get('candidate-logsheet/(:num)', 'Candidate::can_logsheet/$1');
$routes->post('add_log_sheet_details', 'Candidate::add_log_sheet_details');
$routes->post('edit_log_sheet_details', 'Candidate::edit_log_sheet_details');
$routes->post('complete-internship', 'Candidate::complete_internship');
$routes->get('candidate-certificate/(:num)', 'Candidate::candidate_certificate/$1');
$routes->post('save-cartificate', 'Candidate::save_cartificate');
$routes->get('view-certificate/(:num)', 'Candidate::view_certificate/$1');

$routes->post('verify-certificate', 'Candidate::verify_certificate');
$routes->get('verify', 'Candidate::verify_user_certificate');
$routes->get('log-work-report/(:num)', 'Candidate::work_report/$1');
$routes->post('log-work-report', 'Candidate::work_report');

$routes->post('candidate-chat', 'Candidate::candidate_chat');
$routes->post('candidate-chat/(:num)', 'Candidate::candidate_chat/$1');
$routes->post('can-send-message', 'Candidate::can_send_message'); //Function For Send Messages Common (AJEX)
$routes->post('can-get-chat-history', 'Candidate::can_get_chat_history'); //Function For Getting Messages Common (AJEX)
$routes->get('emp_search_internship_showing/(:num)', 'Candidate::emp_search_internship_showing/$1');
$routes->get('can_work_report_showing/(:num)/(:num)', 'Candidate::can_work_report_showing/$1/$2');
$routes->post('get-corporate-profile', 'Candidate::get_corporate_profile');
$routes->post('accept-interview', 'Candidate::accept_interview');
$routes->post('decline-interview', 'Candidate::decline_interview');
$routes->post('reschedule-interview', 'Candidate::reschedule_interview');
$routes->post('can-new-message-cheack', 'Candidate::can_new_message_cheack');
$routes->add('submit-assignment', 'Candidate::submit_assignment');
// $routes->get('email_send', 'Candidate::email_send');
$routes->post('get-unread-chat-user-can', 'Candidate::get_unread_chat_user_can');
$routes->post('update-block-user-can', 'Candidate::update_block_user_can');
$routes->post('check_block_status1', 'Candidate::check_block_status1');

$routes->post('submit-assignment', 'Candidate::submit_assignment');
$routes->get('offers-received', 'Candidate::application_offers_received');
$routes->get('candidate-payment/(:num)', 'Candidate::candidate_payment/$1');
$routes->post('razorpay-success', 'Candidate::success');
$routes->post('razorpay-failed', 'Candidate::failed');
$routes->post('razorpay-callback', 'Candidate::callback');

$routes->get('my-transactions', 'Candidate::can_transactions_list');
$routes->get('candidate-payment-receipt/(:any)', 'Candidate::candidate_payment_recipt_download/$1');
//$routes->get('pricing-plan', 'Candidate::subscription_plan');
$routes->get('pricing-plan', 'Candidate::subscription_plan_new');
$routes->get('pricing_plan_login', 'Candidate::pricing_plan_login');
$routes->get('gmetrix-view', 'Candidate::gmetrix_view');
$routes->get('gmetrix-data', 'Candidate::gmetrix_data');
$routes->get('gmetrix-status', 'Candidate::gmetrix_status');
$routes->get('phonepe-candidate-payment/(:any)', 'Candidate::phonepe_candidate_payment/$1');
$routes->get('phonepe-payment', 'Candidate::phonepe_payment');
$routes->get('phonepe-callback', 'Candidate::phonepe_callback');
$routes->get('phonepe-success', 'Candidate::phonepe_success');
$routes->get('phonepe-failed', 'Candidate::phonepe_failed');

$routes->get('profile-viewed-employers', 'Candidate::emp_viewed_candidate');
$routes->post('set-session-internship-id', 'Candidate::set_session_internship_id');
$routes->get('my-courses', 'Candidate::my_courses');
$routes->get('candidate-open-assessment', 'Candidate::can_open_assessment');
$routes->get('assessment-data/(:any)', 'Candidate::assessment_data/$1');
$routes->get('assessment-details/(:any)', 'Candidate::assessment_details/$1');
//sms

$routes->get('send_sms_user', 'Sms_user::send_sms_user');

//cron

$routes->get('send-interview-email', 'Cron::send_interview_email');

//test mail
// $routes->get('email_send_test', 'Candidate::email_send_test');


//employer
$routes->get('organization-details', 'Employer::emp_profile_step1');
$routes->get('emp-profile-step2', 'Employer::emp_profile_step2');
$routes->get('other-info', 'Employer::emp_profile_step3');
$routes->post('update_employer_org_details', 'Employer::update_employer_org_details');
$routes->post('mobile_send_otp', 'Employer::emp_profile_mobile_otp_send');
$routes->post('emp_profile_logo', 'Employer::emp_profile_logo');
$routes->post('mobile_otp_verify', 'Employer::mobile_otp_verify');
$routes->post('get_state_by_district', 'Employer::get_state_by_district');
$routes->post('update_employer_address_details', 'Employer::update_employer_address_details');
$routes->post('update_employer_other_details', 'Employer::update_employer_other_details');
$routes->post('applied-candidates/(:num)', 'Employer::emp_applied_candidate/$1');
$routes->post('get_state_by_district_multiple', 'Employer::get_state_by_district_multiple');
$routes->post('set_employer_filters', 'Employer::set_employer_filters');
$routes->get('unset_employer_filters/(:num)', 'Employer::unset_employer_filters/$1');
$routes->post('get_skills_by_profile1', 'Employer::get_skills_by_profile1');
$routes->get('post-internship', 'Employer::emp_post_internship');
$routes->post('add_internship', 'Employer::add_internship');
$routes->post('get_skills_by_profile', 'Employer::get_skills_by_profile');
$routes->post('get_skills_all', 'Employer::get_skills_all');
$routes->post('get_skills_all_default', 'Employer::get_skills_all_default');
$routes->get('internship-list', 'Employer::internship_list');
$routes->get('internship-list/(:num)', 'Employer::internship_list/$1');
$routes->get('internship-single/(:num)', 'Employer::internship_single/$1');
$routes->post('update_aplication_status', 'Employer::update_aplication_status');
$routes->post('update_aplication_status_all', 'Employer::update_aplication_status_all');
$routes->post('get_skills_all1', 'Employer::get_skills_all1');
$routes->post('candidate-details/(:num)/(:num)', 'Employer::candidate_details/$1/$2');
$routes->post('get_gst_details', 'Employer::get_gst_details');
$routes->post('update_internship_status', 'Employer::update_internship_status');
$routes->get('internship-edit/(:num)', 'Employer::internship_edit/$1');
$routes->get('internship-repost/(:num)', 'Employer::internship_repost/$1');
$routes->post('get_skills_by_profile_edit', 'Employer::get_skills_by_profile_edit');
$routes->get('employee-list', 'Employer::emp_manage_admin');
$routes->post('add_emp_manage_admins', 'Employer::add_emp_manage_admins');
$routes->post('update_internship', 'Employer::update_internship');
$routes->get('view-employee-details/(:num)', 'Employer::emp_admin_single/$1');
$routes->post('edit_emp_manage_admins', 'Employer::edit_emp_manage_admins');
$routes->get('delete_emp_subadmin_details/(:num)/(:num)/(:num)', 'Employer::delete_emp_subadmin_details/$1/$2/$3');
$routes->get('activate_emp_subadmin_details/(:num)/(:num)/(:num)', 'Employer::activate_emp_subadmin_details/$1/$2/$3');
$routes->get('deactivate_emp_subadmin_details/(:num)/(:num)/(:num)', 'Employer::deactivate_emp_subadmin_details/$1/$2/$3');
$routes->post('check_duplicatecheck_email', 'Employer::check_duplicatecheck_email');
$routes->post('check_duplicatecheck_emp_id', 'Employer::check_duplicatecheck_emp_id');
$routes->post('check_duplicatecheck_email_edit', 'Employer::check_duplicatecheck_email_edit');
$routes->post('check_duplicatecheck_emp_id_edit', 'Employer::check_duplicatecheck_emp_id_edit');
$routes->post('get_can_mobile_email_edit_emp', 'Employer::get_can_mobile_email_edit_emp');
$routes->post('emp_profile_email_otp', 'Employer::emp_profile_email_otp');
$routes->post('email_otp_verify_edit', 'Employer::email_otp_verify_edit');
$routes->get('employer-dashboard', 'Employer::emp_dashboard');
$routes->get('assign-hr', 'Employer::assign_hr');
$routes->get('accepted-candidate-list/(:num)', 'Employer::emp_hired_interns_list/$1');
$routes->get('accepted-candidate-list-supervisior/(:num)/(:num)', 'Employer::emp_hired_interns_list_supervisior/$1/$2');
$routes->get('assign-supervisor', 'Employer::assign_supervisor');
$routes->get('assigned-internship-list', 'Employer::emp_internship_multi_list');
$routes->get('assigned-internship-list/(:num)', 'Employer::emp_internship_multi_list/$1');
$routes->add('GST-Duplicate-Check', 'Employer::gst_duplicate_check');
$routes->post('description_geting', 'Employer::description_geting');
$routes->post('search-candidates/(:any)', 'Employer::emp_search_candidate/$1');
$routes->post('search-candidates', 'Employer::emp_search_candidate');
$routes->post('set-candidate-filters', 'Employer::set_candidate_filters');
$routes->post('emp-unset-candidate-filters/(:num)', 'Employer::emp_unset_candidate_filters/$1');
$routes->post('update-shortlist-status-all', 'Employer::update_shortlist_status_all');
$routes->post('candidate-profile/(:any)', 'Employer::candidate_profile/$1');
$routes->post('candidate-profile-trash/(:any)', 'Employer::candidate_profile_trash/$1');
$routes->post('my-folder', 'Employer::emp_folder');
$routes->post('emp-create-floder', 'Employer::emp_create_floder');
$routes->post('create-folder', 'Employer::create_folder');
$routes->post('move-folder-candidate', 'Employer::move_folder_candidate');
$routes->get('candidate_move_to_trash/(:num)', 'Employer::candidate_move_to_trash/$1');
$routes->get('candidate_move_to_restore/(:num)', 'Employer::candidate_move_to_restore/$1');
$routes->post('update_candidate_restore', 'Employer::update_candidate_restore');
$routes->get('emp_trash_folder', 'Employer::emp_trash_folder');

$routes->get('emp_certificates_session/(:any)/(:num)/(:num)', 'Employer::emp_certificates_session/$1/$2/$3');
//employer
$routes->get('view-candidate-logsheet/(:num)/(:any)', 'Employer::emp_logsheet/$1/$2');
$routes->post('update_approve_log_status', 'Employer::update_approve_log_status');
$routes->post('update_approve_log_status_all', 'Employer::update_approve_log_status_all');
$routes->get('employer-certificate', 'Employer::emp_certificate');
$routes->post('add_certificate_details', 'Employer::add_certificate_details');
$routes->post('issue_certificate_candidate', 'Employer::issue_certificate_candidate');
$routes->post('html-pdf/(:num)', 'Employer::html_to_pdf/$1');
$routes->post('download-candidate-excel', 'Employer::download_candidate_excel');
$routes->post('check-download-candidate-pdf', 'Employer::check_download_candidate_pdf');
$routes->add('delete-candidate-excel', 'Employer::delete_candidate_excel');
$routes->post('emp_rating_candidate', 'Employer::emp_rating_candidate');
$routes->post('send-message', 'Employer::send_message'); //Function For Send Messages Common (AJEX)
$routes->post('get-chat-history', 'Employer::get_chat_history'); //Function For Getting Messages Common (AJEX)

$routes->get('employer-details', 'Employer::employer_details');
$routes->get('employer-details/(:num)', 'Employer::employer_details/$1');
$routes->get('registered-employers', 'Employer::registered_employers');
$routes->post('registered_employers_side', 'Employer::registered_employers_side');

$routes->post('emp-chat/(:num)/(:any)', 'Employer::emp_chat/$1/$2');
$routes->post('emp-chat/(:num)', 'Employer::emp_chat/$1');
$routes->post('sent-assignment', 'Employer::sent_assignment');
$routes->post('sent-interview', 'Employer::sent_interview');
$routes->post('check_duplicatecheck_domain', 'Employer::check_duplicatecheck_domain');
$routes->get('emp_search_candidate_showing/(:num)/(:num)', 'Employer::emp_search_candidate_showing/$1/$2');
$routes->get('emp_work_report_showing/(:num)/(:num)/(:num)', 'Employer::emp_work_report_showing/$1/$2/$3');
$routes->get('emp_applied_candidate_showing/(:num)/(:num)', 'Employer::emp_applied_candidate_showing/$1/$2');
$routes->post('get-chandidate-profile', 'Employer::get_chandidate_profile');
$routes->post('sent-bulk-chat', 'Employer::sent_bulk_chat');
$routes->post('dependency_academic_background_filter', 'Employer::dependency_academic_background_filter');
$routes->post('dependency_search_candidate_filter', 'Employer::dependency_search_candidate_filter');
$routes->post('new-message-cheack', 'Employer::new_message_cheack'); //Function For New Messages Cheack For Candidate (AJEX)
$routes->post('cancel-interview', 'Employer::cancel_interview');
$routes->post('employer_create_label', 'Employer::employer_create_label');
$routes->post('employer_create_label_single', 'Employer::employer_create_label_single');
$routes->post('delete_label_details', 'Employer::delete_label_details');
$routes->post('remove_label_candidate', 'Employer::remove_label_candidate');
$routes->post('employer_select_label', 'Employer::employer_select_label');
$routes->post('employer_select_label_single', 'Employer::employer_select_label_single');
$routes->post('get-unread-chat-user', 'Employer::get_unread_chat_user');
$routes->get('roles-responsibility', 'Employer::roles_responsibility');
$routes->post('update-evaluated-status', 'Employer::update_evaluated_status');
$routes->post('update-block-user-emp', 'Employer::update_block_user_emp');
$routes->post('check_block_status', 'Employer::check_block_status');
$routes->add('update_location_emp_profile', 'Employer::update_location_emp_profile');
$routes->get('func_session_post_internship/(:num)/(:any)', 'Employer::func_session_post_internship/$1/$2');
$routes->get('employer-dashboard-analysis', 'Employer::employer_dashboard_analysis');
$routes->get('employer-dashboard-applications/(:num)', 'Employer::emp_dash_all_application/$1');
$routes->get('employer-dashboard-applications-offer', 'Employer::employer_dashboard_applications_offer');
$routes->get('employer-assignment-sent/(:num)', 'Employer::employer_assignment_sent/$1');
$routes->get('employer-interview-sent/(:num)/(:num)', 'Employer::employer_interview_sent/$1/$2');
$routes->post('save-search-filters', 'Employer::save_search_filters');
$routes->post('save-search-set', 'Employer::save_search_set');
$routes->get('save-search-view', 'Employer::save_search_view');
$routes->post('delete-saved-search', 'Employer::delete_saved_search');
$routes->post('assignment-feedback', 'Employer::assignment_feedback');
$routes->get('paid-internships', 'Employer::paid_internships');
//employer 
//faculty
$routes->get('faculty-dashboard', 'Faculty::faculty_dashboard');
$routes->get('faculty-profile', 'Faculty::faculty_profile');
$routes->post('applied-candidate-list/(:num)', 'Faculty::applied_candidate_list/$1');
$routes->post('faculty-candidate-list/(:num)', 'Faculty::faculty_candidate_list/$1');
$routes->post('schedule-visit', 'Faculty::schedule_visit');
$routes->post('update-schedule-visit', 'Faculty::update_schedule_visit');
$routes->post('get_can_mobile_email_edit_fac', 'Faculty::get_can_mobile_email_edit_fac');
$routes->post('mobile_send_otp_fac', 'Faculty::fac_profile_mobile_otp_send');
$routes->post('mobile_otp_verify_fac', 'Faculty::mobile_otp_verify_fac');
$routes->post('fac_profile_email_otp', 'Faculty::fac_profile_email_otp');
$routes->post('fac_email_otp_verify_edit', 'Faculty::fac_email_otp_verify_edit');
$routes->post('update_faculty_details', 'Faculty::update_faculty_details');
$routes->post('fac_images', 'Faculty::fac_images');
$routes->post('delete_img', 'Faculty::delete_img');
$routes->post('fac_images1', 'Faculty::fac_images1');
$routes->get('fac-log-work-report/(:num)/(:num)', 'Faculty::work_report/$1/$2');
$routes->get('fac-candidate-certificate/(:num)/(:num)', 'Faculty::candidate_certificate/$1/$2');
$routes->post('date-duplicate-check', 'Faculty::date_duplicate_check');
$routes->get('view-visit-report/(:num)', 'Faculty::view_visit_report/$1');
$routes->get('view_visit_report_download/(:num)', 'Faculty::view_visit_report_download/$1');
$routes->get('faculty-visit-log', 'Faculty::faculty_visit_log');
$routes->get('faculty-candidate-list-all', 'Faculty::faculty_candidate_list_all');
$routes->get('faculty-candidate-list-ongoing', 'Faculty::faculty_candidate_list_ongoing');
$routes->get('faculty-candidate-list-completed', 'Faculty::faculty_candidate_list_completed');
$routes->get('faculty-candidate-list-dropped', 'Faculty::faculty_candidate_list_dropped');
$routes->get('faculty-internship-list-all', 'Faculty::faculty_internship_list_all');
$routes->get('faculty-internship-list-visit-completed', 'Faculty::faculty_internship_list_visit_completed');
$routes->get('faculty-internship-list-visit-pending', 'Faculty::faculty_internship_list_visit_pending');
$routes->get('edit-visit-report/(:num)', 'Faculty::edit_visit_report/$1');
$routes->post('update-visit-status', 'Faculty::update_visit_status');
//Admin

$routes->get('admin-dashboard', 'Admin::admin_dashboard');
$routes->get('bi-dashboard', 'Admin::bi_dashboard');
// $routes->get('dashborard-report-details-list/(:num)', 'Admin::dashboard_report_details_list/$1');
$routes->get('admin-registered-candidates/(:num)', 'Admin::dashboard_register_candidate/$1');
$routes->get('profile-completed-candidates/(:num)', 'Admin::dashboard_candidate_profile_completion/$1');
$routes->get('admin-posted-internships/(:num)/(:num)', 'Admin::dashboard_post_internship/$1/$2');
$routes->get('admin-employer-list/(:num)', 'Admin::dashboard_no_of_employer/$1');
$routes->get('admin-active-candidates/(:num)', 'Admin::dashboard_candidates_active/$1');
$routes->get('admin-idle-candidates/(:num)', 'Admin::dashboard_candidates_idle/$1');
$routes->get('admin-inactive-candidates/(:num)', 'Admin::dashboard_candidates_inactive/$1');
$routes->get('admin-active-employers/(:num)', 'Admin::dashboard_employer_active/$1');
$routes->get('admin-idle-employers/(:num)', 'Admin::dashboard_employer_idle/$1');
$routes->get('admin-inactive-employers/(:num)', 'Admin::dashboard_employer_inactive/$1');
$routes->add('admin-college-details', 'Admin::admin_college_details');
$routes->get('admin-course-details', 'Admin::admin_course_details');
$routes->get('admin-specialization-details', 'Admin::admin_specialization_details');
$routes->get('college_details', 'Admin::college_details');
$routes->add('college_course', 'Admin::college_course');
$routes->add('college_specialization', 'Admin::college_specialization');
$routes->post('download_employers_details_excel', 'Admin::download_employers_details_excel');
$routes->post('download_candidate_details_excel', 'Admin::download_candidate_details_excel');
$routes->post('download_candidate_details_dist_excel', 'Admin::download_candidate_details_dist_excel');
$routes->post('download_college_details_state_district_excel', 'Admin::download_college_details_state_district_excel');
$routes->post('dependency_search_reg_candidate_college_filter', 'Admin::dependency_search_reg_candidate_college_filter');
$routes->get('admin-dashboard-college-state', 'Admin::admin_dashboard_college_state');
$routes->post('set_college_state_filters', 'Admin::set_college_state_filters');
$routes->get('unset_college_state_filters', 'Admin::unset_college_state_filters');
$routes->get('collegewise-candidate-list/(:num)', 'Admin::collegewise_candidate_list/$1');
$routes->get('admin-registered-internship-hired/(:num)', 'Admin::registered_candidate_internship_hired/$1');
$routes->get('admin-completed-internship-hired/(:num)', 'Admin::completed_candidate_internship_hired/$1');
$routes->get('all-transactions', 'Admin::all_transactions_list');
$routes->get('all_phonepe_transactions', 'Admin::all_phonepe_transactions_list');
$routes->get('transactions_status', 'Admin::transaction_status_update');
$routes->get('payment-status-update', 'Admin::payment_status_update');
$routes->get('rating-approval', 'Admin::rating_approval');
$routes->post('update_approve_rating_status', 'Admin::update_approve_rating_status');
$routes->post('get_internship_by_company_all', 'Admin::get_internship_by_company');

$routes->get('employer-approval', 'Admin::employer_approval');
$routes->post('update_approve_employer_status', 'Admin::update_approve_employer_status');
$routes->post('update_activate_employer_status', 'Admin::update_activate_employer_status');
$routes->post('update_feature_employer_status', 'Admin::update_feature_employer_status');


$routes->get('admin-view-emp-profile/(:num)', 'Admin::admin_view_emp_profile/$1');
$routes->get('can_payment_details_upload', 'Admin::can_payment_details_upload');
$routes->post('can_payment_details_upload_excel', 'Admin::can_payment_details_upload_excel');
$routes->post('view-bulk-payment-details/(:num)', 'Admin::view_bulk_payment_details/$1');
$routes->get('phonepe_transactions_status', 'Admin::phonepe_transaction_status_update');
$routes->get('phonepe-clear-transaction-status-filter', 'Admin::phonepe_clear_transaction_status_search_filter');
$routes->post('phonepe-set-transaction-status-filter', 'Admin::phonepe_set_transaction_status_search_filter');
$routes->get('phonepe-payment-status-update', 'Admin::phonepe_payment_status_update');

$routes->get('admin-blog', 'Admin::admin_blog');
$routes->post('add-blog', 'Admin::add_blog');
$routes->post('blog-list', 'Admin::blog_list');
$routes->get('blog-preview/(:num)', 'Admin::blog_preview/$1');
$routes->add('blog-edit/(:num)', 'Admin::blog_edit/$1');
$routes->post('update-blog', 'Admin::update_blog');
$routes->post('update_blog_status', 'Admin::update_blog_status');
$routes->post('update_exclusive_status', 'Admin::update_exclusive_status');
//Cron Payment
$routes->get('razorpay-status-update', 'CronPayment::razorpay_status_update');
$routes->get('failed-status-update', 'CronPayment::failed_status_update');
$routes->get('refund-status-update', 'CronPayment::refund_status_update');
$routes->get('razorpay-webhook-update', 'CronPayment::razorpay_webhook_update');
$routes->get('phonepe-webhook-update', 'CronPayment::phonepe_webhook_update');
$routes->get('phonepe-status-update', 'CronPayment::phonepe_status_update');
$routes->get('phonepe-refund-status-update', 'CronPayment::phonepe_refund_status_update');
//Webhook
$routes->get('webhookpayment', 'PaymentWebhook::webhook_payment_posting');
$routes->get('s2s-callback', 'Candidate::s2s_callback');
//Admin


// Design

$routes->get('emp_login1', 'Design::emp_login1');
$routes->get('emp_register1', 'Design::emp_register1');
$routes->get('emp_forgot_pass', 'Design::emp_forgot_pass');
$routes->get('emp_forgot_otp', 'Design::emp_forgot_otp');
$routes->get('emp_reset_password', 'Design::emp_reset_password');
$routes->get('post_internship', 'Design::post_internship');
$routes->get('emp_intern_single', 'Design::emp_intern_single');
$routes->get('emp_intern_list', 'Design::emp_intern_list');
$routes->get('emp_profile1', 'Design::emp_profile1');
$routes->get('emp_profile2', 'Design::emp_profile2');
$routes->get('emp_profile3', 'Design::emp_profile3');
$routes->get('can_intern_list', 'Design::can_intern_list');
$routes->get('can_intern_single', 'Design::can_intern_single');
$routes->get('emp_applied_can', 'Design::emp_applied_can');
$routes->get('can_applied_intern_list', 'Design::can_applied_intern_list');
$routes->get('can_apply_for_intern', 'Design::can_apply_for_intern');
$routes->get('can_proceed_apply', 'Design::can_proceed_apply');
$routes->get('can_profile_single', 'Design::can_profile_single');
$routes->get('can_applied_intern_review', 'Design::can_applied_intern_review');
$routes->get('emp_view_can_profile', 'Design::emp_view_can_profile');
$routes->get('emp_pricing_plan', 'Design::emp_pricing_plan');
$routes->get('can_profile_personal1', 'Design::can_profile_personal1');
$routes->get('can_profile_education1', 'Design::can_profile_education1');
$routes->get('can_profile_address1', 'Design::can_profile_address1');
$routes->get('can_profile_experience1', 'Design::can_profile_experience1');
$routes->get('can_profile_skills1', 'Design::can_profile_skills1');
$routes->get('can_profile_work1', 'Design::can_profile_work1');
$routes->get('emp_dashboard', 'Design::emp_dashboard');
$routes->get('emp-manage-admin', 'Design::emp_manage_admin');
$routes->get('emp-admin-single', 'Design::emp_admin_single');
$routes->get('link-expired', 'Design::link_expired');
$routes->get('emp-employee-list', 'Design::emp_employee_list');
$routes->get('change-password-success1', 'Design::change_password_success1');
$routes->get('can-dashboard', 'Design::can_dashboard');
$routes->get('emp-hired-interns', 'Design::emp_hired_interns');
$routes->get('admin-dashboard-design', 'Design::admin_dashboard');
$routes->get('emp-access-db', 'Design::emp_access_db');
$routes->get('redirect-invitation', 'Design::redirect_invitation');
$routes->get('folder', 'Design::folder');
$routes->get('can-logsheet', 'Design::can_logsheet');
$routes->get('emp-logsheet', 'Design::emp_logsheet');
$routes->get('certificate', 'Design::certificate');
$routes->get('emp-certificate', 'Design::emp_certificate');
$routes->get('chat', 'Design::chat');
$routes->get('resume', 'Design::resume');
$routes->get('work-report', 'Design::work_report');
$routes->get('merge-college', 'Design::admin_merge_college');
$routes->get('emp-profile', 'Design::emp_profile');
$routes->get('companies', 'Design::companies');
$routes->get('404', 'Design::nopage');
$routes->get('roles-responsibility', 'Design::roles_responsibility');
$routes->get('employer-dashboard-new', 'Design::employer_dashboard_new');
$routes->get('teacher-dashboard-design', 'Design::teacher_dashboard');
$routes->get('teacher-profile-design', 'Design::teacher_profile_single');
$routes->get('teacher-candidate-list', 'Design::teacher_candidate_list');
$routes->get('teacher-work-report', 'Design::faculty_work_report');
$routes->get('teacher-work-upload', 'Design::faculty_work_upload');
$routes->get('visit-log-design', 'Design::visit_log');
$routes->get('emp-assignment-sent', 'Design::employer_assignment_sent');
$routes->get('emp-interview-scheduled', 'Design::employer_interview_scheduled');
$routes->get('can-subscription', 'Design::can_subscription');
$routes->get('can-recipt', 'Design::can_recipt');
$routes->get('transaction-ui', 'Design::transaction_ui');
//$routes->get('verification-of-email', 'Design::verification_of_email');
$routes->get('subscription-plan', 'Design::subscription_plan');

$routes->get('can-email-verify', 'Design::verification_of_email_candidate');
$routes->get('gmetrix-ui', 'Design::gmetrix_ui');
$routes->get('assessment-ui', 'Design::assessment_ui');
$routes->get('assessment-score', 'Design::assessment_score');
$routes->get('pricing-plan-ui', 'Design::pricing_plan_ui');
$routes->get('blog-ui', 'Design::blog_ui');
$routes->get('blog-single-ui', 'Design::blog_single_ui');
$routes->get('can_viewed_emp', 'Design::can_viewed_emp');
$routes->get('admin_form', 'Design::admin_form');
$routes->get('admin_listing_form', 'Design::admin_listing_form');
$routes->get('admin_preview_form', 'Design::admin_preview_form');
$routes->get('design_home', 'Design::design_home');



//web page
$routes->add('web-search-internship', 'Home::view_internship');
$routes->add('Internship-Filters', 'Home::internship_filters');
$routes->add('Unset-Internship-Filters', 'Home::unset_internship_filters');
$routes->add('view-internship-details/(:num)', 'Home::details_view_internship/$1');
$routes->add('Login-Web/(:any)', 'Home::login_web/$1');
$routes->add('gmetrix-web', 'Home::gmetrix_web');
$routes->add('login-gmetrix', 'Home::login_gmetrix');
$routes->add('Keyword-Search-Public', 'Home::keyword_search_public');
$routes->add('Keyword-Search-Candidate-Public', 'Home::keyword_search_candidate_public');
$routes->add('Clear-Web-Search-Filter', 'Home::clear_search_filter');
$routes->add('Clear-Web-Search-Folder', 'Home::clear_search_folder');
$routes->add('Clear-Web-Search-User', 'Home::clear_search_user');
$routes->add('Clear-Web-Search-User/(:any)', 'Home::clear_search_user/$1');
$routes->add('Clear-Web-Search-Candidate/(:any)', 'Home::clear_search_candidate/$1');
$routes->add('Clear-Web-Search-Candidate', 'Home::clear_search_candidate');
$routes->add('blog', 'Home::blog');

$routes->add('web-search-folder', 'Employer::emp_folder');
$routes->add('web-search-candidate', 'Employer::emp_search_candidate');
$routes->add('web-search-candidate-folder/(:any)', 'Employer::emp_search_candidate/$1');
$routes->add('web-applied-candidate/(:num)', 'Employer::emp_applied_candidate/$1');
$routes->add('Clear-Search-applied-Candidate/(:num)', 'Home::clear_applied_candidate/$1');
$routes->add('Clear-Search-applied-User/(:num)', 'Home::clear_user_candidate/$1');
/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
//login check
// $routes->add('dashboard', 'Login::dashboard',['filter'=>'isLoggedIn']);
$routes->group('', ['filter' => 'isLoggedIn'], static function ($routes) {
$routes->add('personal-details', 'Candidate::can_profile_personal');
$routes->add('education-details', 'Candidate::can_profile_education');
$routes->add('can-profile-address', 'Candidate::can_profile_address');
$routes->add('experience-details', 'Candidate::can_profile_experience');
$routes->add('skill-details', 'Candidate::can_profile_skills');
$routes->add('work-sample-details', 'Candidate::can_profile_work');
$routes->add('search-internship', 'Candidate::can_intern_list');
$routes->add('set_candidate_filters', 'Candidate::set_candidate_filters');
$routes->add('unset_candidate_filters', 'Candidate::unset_candidate_filters');
$routes->add('internship-details/(:num)', 'Candidate::can_intern_single/$1');
$routes->add('update_can_personal_details', 'Candidate::update_can_personal_details');
$routes->add('can_mobile_send_otp', 'Candidate::can_profile_mobile_otp_send');
$routes->add('can_mobile_otp_verify', 'Candidate::can_mobile_otp_verify');
$routes->add('add_can_educational_details', 'Candidate::add_can_educational_details');
$routes->add('edit_can_educationa_details', 'Candidate::edit_can_educationa_details');
$routes->add('delete_can_educationa_details/(:num)', 'Candidate::delete_can_educationa_details/$1');
$routes->add('get_state_by_district_can', 'Candidate::get_state_by_district_can');
$routes->add('get_state_by_district_can_com', 'Candidate::get_state_by_district_can_com');
$routes->add('update_can_address_details', 'Candidate::update_can_address_details');
$routes->add('update_can_work_sample', 'Candidate::update_can_work_sample');
$routes->add('add_can_experience', 'Candidate::add_can_experience');
$routes->add('edit_can_experience', 'Candidate::edit_can_experience');
$routes->add('delete_common/(:num)/(:any)/(:any)', 'Candidate::delete_common/$1/$2/$3');
$routes->add('add_can_skills', 'Candidate::add_can_skills');
$routes->add('get_spec_by_courses', 'Candidate::get_spec_by_courses');
$routes->add('get_spec_by_courses_edit', 'Candidate::get_spec_by_courses_edit');
$routes->add('can-apply-for-internship/(:any)', 'Candidate::can_apply_for_internship/$1');
$routes->add('can-proceeds-apply/(:any)', 'Candidate::can_proceeds_apply/$1');
$routes->add('my-applications', 'Candidate::can_applied_intern_list');
$routes->add('direct-corporate-offers', 'Candidate::can_offered_intern_list');
$routes->add('my-internships', 'Candidate::can_my_intern_list');
$routes->add('profile-details', 'Candidate::can_view_profile');
$routes->add('bookmark', 'Candidate::can_bookmark_list');
$routes->add('change-password', 'Candidate::change_password');
$routes->add('change-password-success', 'Candidate::change_password_success');
$routes->add('candidate-certificate/(:num)', 'Candidate::candidate_certificate/$1');
$routes->add('candidate-chat', 'Candidate::candidate_chat');
$routes->add('candidate-chat/(:num)', 'Candidate::candidate_chat/$1');
$routes->add('can-send-message', 'Candidate::can_send_message'); //Function For Send Messages Common (AJEX)
$routes->add('can-get-chat-history', 'Candidate::can_get_chat_history'); //Function For Getting Messages Common (AJEX)

$routes->add('log-work-report/(:num)', 'Candidate::work_report/$1');
$routes->add('get-corporate-profile', 'Candidate::get_corporate_profile');
$routes->add('accept-interview', 'Candidate::accept_interview');
$routes->add('decline-interview', 'Candidate::decline_interview');
$routes->add('reschedule-interview', 'Candidate::reschedule_interview');
$routes->add('can-new-message-cheack', 'Candidate::can_new_message_cheack');
$routes->add('submit-assignment', 'Candidate::submit_assignment');
$routes->add('get-unread-chat-user-can', 'Candidate::get_unread_chat_user_can');
$routes->add('update-block-user-can', 'Candidate::update_block_user_can');
$routes->add('check_block_status1', 'Candidate::check_block_status1');
$routes->add('offers-received', 'Candidate::application_offers_received');
$routes->add('candidate-payment', 'Candidate::candidate_payment');
$routes->add('razorpay-success', 'Candidate::success');
$routes->add('razorpay-failed', 'Candidate::failed');
$routes->add('razorpay-callback', 'Candidate::callback');
$routes->add('my-transactions', 'Candidate::can_transactions_list');
$routes->add('candidate-payment-receipt/(:any)', 'Candidate::candidate_payment_recipt_download/$1');
$routes->add('gmetrix-view', 'Candidate::gmetrix_view');
$routes->add('gmetrix-data', 'Candidate::gmetrix_data');
$routes->add('gmetrix-status', 'Candidate::gmetrix_status');
$routes->add('phonepe-candidate-payment/(:any)', 'Candidate::phonepe_candidate_payment/$1');
$routes->add('phonepe-payment', 'Candidate::phonepe_payment');
$routes->add('phonepe-callback', 'Candidate::phonepe_callback');
$routes->add('phonepe-success', 'Candidate::phonepe_success');
$routes->add('phonepe-failed', 'Candidate::phonepe_failed');
$routes->add('profile-viewed-employers', 'Candidate::emp_viewed_candidate');
$routes->add('set-session-internship-id', 'Candidate::set_session_internship_id');
$routes->add('my-courses', 'Candidate::my_courses');
$routes->add('save-cartificate', 'Candidate::save_cartificate');
$routes->add('candidate-open-assessment', 'Candidate::can_open_assessment');
$routes->add('assessment-data/(:any)', 'Candidate::assessment_data/$1');
$routes->get('assessment-details/(:any)', 'Candidate::assessment_details/$1');
// Employer
$routes->add('organization-details', 'Employer::emp_profile_step1');
$routes->add('emp-profile-step2', 'Employer::emp_profile_step2');
$routes->add('other-info', 'Employer::emp_profile_step3');
$routes->add('update_employer_org_details', 'Employer::update_employer_org_details');
$routes->add('mobile_send_otp', 'Employer::emp_profile_mobile_otp_send');
$routes->add('mobile_otp_verify', 'Employer::mobile_otp_verify');
$routes->add('get_state_by_district', 'Employer::get_state_by_district');
$routes->add('update_employer_address_details', 'Employer::update_employer_address_details');
$routes->add('update_employer_other_details', 'Employer::update_employer_other_details');
$routes->add('applied-candidates/(:num)', 'Employer::emp_applied_candidate/$1');
$routes->add('get_state_by_district_multiple', 'Employer::get_state_by_district_multiple');
$routes->add('set_employer_filters', 'Employer::set_employer_filters');
$routes->add('unset_employer_filters/(:num)', 'Employer::unset_employer_filters/$1');
$routes->add('get_skills_by_profile1', 'Employer::get_skills_by_profile1');
$routes->add('post-internship', 'Employer::emp_post_internship');
$routes->add('add_internship', 'Employer::add_internship');
$routes->add('get_skills_by_profile', 'Employer::get_skills_by_profile');
$routes->add('get_skills_all', 'Employer::get_skills_all');
$routes->add('get_skills_all_default', 'Employer::get_skills_all_default');
$routes->add('internship-list', 'Employer::internship_list');
$routes->add('internship-list/(:num)', 'Employer::internship_list/$1');
$routes->add('internship-single/(:num)', 'Employer::internship_single/$1');
$routes->add('update_aplication_status', 'Employer::update_aplication_status');
$routes->add('update_aplication_status_all', 'Employer::update_aplication_status_all');
$routes->add('get_skills_all1', 'Employer::get_skills_all1');
$routes->add('candidate-details/(:num)/(:num)', 'Employer::candidate_details/$1/$2');
$routes->add('get_gst_details', 'Employer::get_gst_details');
$routes->add('emp_profile_logo', 'Employer::emp_profile_logo');
$routes->add('update_internship_status', 'Employer::update_internship_status');
$routes->add('internship-edit/(:num)', 'Employer::internship_edit/$1');
$routes->add('get_skills_by_profile_edit', 'Employer::get_skills_by_profile_edit');
$routes->add('update_internship', 'Employer::update_internship');
$routes->add('employee-list', 'Employer::emp_manage_admin');
$routes->add('view-employee-details/(:num)', 'Employer::emp_admin_single/$1');
$routes->add('check_duplicatecheck_email', 'Employer::check_duplicatecheck_email');
$routes->add('check_duplicatecheck_emp_id', 'Employer::check_duplicatecheck_emp_id');
$routes->add('check_duplicatecheck_email_edit', 'Employer::check_duplicatecheck_email_edit');
$routes->add('check_duplicatecheck_emp_id_edit', 'Employer::check_duplicatecheck_emp_id_edit');
$routes->add('employer-dashboard', 'Employer::emp_dashboard');
$routes->add('assign-hr', 'Employer::assign_hr');
$routes->add('accepted-candidate-list/(:num)', 'Employer::emp_hired_interns_list/$1');
$routes->add('accepted-candidate-list-supervisior/(:num)/(:num)', 'Employer::emp_hired_interns_list_supervisior/$1/$2');
$routes->post('Accept-Hiring/', 'Candidate::accept_hiring/');
$routes->add('assign-supervisor', 'Employer::assign_supervisor');
$routes->add('assigned-internship-list', 'Employer::emp_internship_multi_list');
$routes->add('assigned-internship-list/(:num)', 'Employer::emp_internship_multi_list/$1');
// $routes->add('search-candidates', 'Employer::emp_search_candidate');
$routes->add('set-candidate-filters', 'Employer::set_candidate_filters');
$routes->add('emp-unset-candidate-filters/(:num)', 'Employer::emp_unset_candidate_filters/$1');
$routes->add('update-shortlist-status-all', 'Employer::update_shortlist_status_all');
$routes->add('candidate-profile/(:any)', 'Employer::candidate_profile/$1');
$routes->add('candidate-profile-trash/(:any)', 'Employer::candidate_profile_trash/$1');
$routes->add('my-folder', 'Employer::emp_folder');
$routes->add('emp-create-floder', 'Employer::emp_create_floder');
$routes->add('view-candidate-logsheet/(:num)/(:any)', 'Employer::emp_logsheet/$1/$2');
$routes->add('employer-certificate', 'Employer::emp_certificate');
$routes->add('create-folder', 'Employer::create_folder');
$routes->add('move-folder-candidate', 'Employer::move_folder_candidate');
$routes->add('search-candidates/(:any)', 'Employer::emp_search_candidate/$1');
$routes->add('search-candidates', 'Employer::emp_search_candidate');
$routes->add('html-pdf/(:num)', 'Employer::html_to_pdf/$1');
$routes->add('download-candidate-excel', 'Employer::download_candidate_excel');
$routes->add('delete-candidate-excel', 'Employer::delete_candidate_excel');
$routes->add('emp-chat/(:num)/(:any)', 'Employer::emp_chat/$1/$2');
$routes->add('emp-chat/(:num)', 'Employer::emp_chat/$1');
$routes->add('send-message', 'Employer::send_message'); //Function For Send Messages Common (AJEX)
$routes->add('get-chat-history', 'Employer::get_chat_history'); //Function For Getting Messages Common (AJEX)
$routes->add('candidate_move_to_trash/(:num)', 'Employer::candidate_move_to_trash/$1');
$routes->add('candidate_move_to_restore/(:num)', 'Employer::candidate_move_to_restore/$1');
$routes->add('emp_trash_folder', 'Employer::emp_trash_folder');
$routes->add('sent-assignment', 'Employer::sent_assignment');
$routes->add('sent-interview', 'Employer::sent_interview');
$routes->add('check_duplicatecheck_domain', 'Employer::check_duplicatecheck_domain');
$routes->add('get-chandidate-profile', 'Employer::get_chandidate_profile');
$routes->add('sent-bulk-chat', 'Employer::sent_bulk_chat');
$routes->add('new-message-cheack', 'Employer::new_message_cheack'); //Function For New Messages Cheack For Candidate (AJEX)
$routes->add('cancel-interview', 'Employer::cancel_interview');
$routes->add('get-unread-chat-user', 'Employer::get_unread_chat_user');
$routes->add('roles-responsibility', 'Employer::roles_responsibility');
$routes->add('update-evaluated-status', 'Employer::update_evaluated_status');
$routes->add('update-block-user-emp', 'Employer::update_block_user_emp');
$routes->add('check_block_status', 'Employer::check_block_status');
$routes->add('employer-dashboard-analysis', 'Employer::employer_dashboard_analysis');
$routes->add('employer-dashboard-applications/(:num)', 'Employer::emp_dash_all_application/$1');
$routes->add('employer-dashboard-applications-offer', 'Employer::employer_dashboard_applications_offer');
$routes->add('employer-assignment-sent/(:num)', 'Employer::employer_assignment_sent/$1');
$routes->add('employer-interview-sent/(:num)', 'Employer::employer_interview_sent/$1');
$routes->add('save-search-filters', 'Employer::save_search_filters');
$routes->add('save-search-set', 'Employer::save_search_set');
$routes->add('save-search-view', 'Employer::save_search_view');
$routes->add('delete-saved-search/(:num)', 'Employer::delete_saved_search/$1');
$routes->add('assignment-feedback', 'Employer::assignment_feedback');
$routes->add('adb656000a9c70e76bc800fca7358c4c', 'Employer::emp_session_display_for_power_bi');
// employer

//faculty

$routes->add('faculty-dashboard', 'Faculty::faculty_dashboard');
$routes->add('faculty-profile', 'Faculty::faculty_profile');
$routes->add('applied-candidate-list/(:num)', 'Faculty::applied_candidate_list/$1');
$routes->add('faculty-candidate-list/(:num)', 'Faculty::faculty_candidate_list/$1');
$routes->add('schedule-visit', 'Faculty::schedule_visit');
$routes->add('update-schedule-visit', 'Faculty::update_schedule_visit');
$routes->add('get_can_mobile_email_edit_fac', 'Faculty::get_can_mobile_email_edit_fac');
$routes->add('mobile_send_otp_fac', 'Faculty::fac_profile_mobile_otp_send');
$routes->add('mobile_otp_verify_fac', 'Faculty::mobile_otp_verify_fac');
$routes->add('fac_profile_email_otp', 'Faculty::fac_profile_email_otp');
$routes->add('fac_email_otp_verify_edit', 'Faculty::fac_email_otp_verify_edit');
$routes->add('update_faculty_details', 'Faculty::update_faculty_details');
$routes->add('fac_images', 'Faculty::fac_images');
$routes->add('delete_img', 'Faculty::delete_img');
$routes->add('fac_images1', 'Faculty::fac_images1');
$routes->add('fac-log-work-report/(:num)/(:num)', 'Faculty::work_report/$1/$2');
$routes->add('fac-candidate-certificate/(:num)/(:num)', 'Faculty::candidate_certificate/$1/$2');
$routes->add('date-duplicate-check', 'Faculty::date_duplicate_check');
$routes->add('view-visit-report/(:num)', 'Faculty::view_visit_report/$1');
$routes->add('view_visit_report_download/(:num)', 'Faculty::view_visit_report_download/$1');
$routes->add('faculty-visit-log', 'Faculty::faculty_visit_log');
$routes->add('faculty-candidate-list-all', 'Faculty::faculty_candidate_list_all');
$routes->add('faculty-candidate-list-ongoing', 'Faculty::faculty_candidate_list_ongoing');
$routes->add('faculty-candidate-list-completed', 'Faculty::faculty_candidate_list_completed');
$routes->add('faculty-candidate-list-dropped', 'Faculty::faculty_candidate_list_dropped');
$routes->add('faculty-internship-list-all', 'Faculty::faculty_internship_list_all');
$routes->add('faculty-internship-list-visit-completed', 'Faculty::faculty_internship_list_visit_completed');
$routes->add('faculty-internship-list-visit-pending', 'Faculty::faculty_internship_list_visit_pending');
$routes->add('edit-visit-report/(:num)', 'Faculty::edit_visit_report/$1');
$routes->add('update-visit-status', 'Faculty::update_visit_status');

//admin

$routes->add('admin-dashboard', 'Admin::admin_dashboard');
$routes->add('bi-dashboard', 'Admin::bi_dashboard');
$routes->add('dashboard', 'Candidate::can_dashboard');
$routes->add('candidate-logsheet/(:num)', 'Candidate::can_logsheet/$1');
$routes->add('admin-registered-candidates/(:num)', 'Admin::dashboard_register_candidate/$1');
$routes->add('admin-registered-internship-hired/(:num)', 'Admin::registered_candidate_internship_hired/$1');
$routes->add('admin-completed-internship-hired/(:num)', 'Admin::completed_candidate_internship_hired/$1');
$routes->add('profile-completed-candidates/(:num)', 'Admin::dashboard_candidate_profile_completion/$1');
$routes->add('admin-posted-internships/(:num)', 'Admin::dashboard_post_internship/$1');
$routes->add('admin-employer-list/(:num)', 'Admin::dashboard_no_of_employer/$1');
$routes->add('admin-active-candidates/(:num)', 'Admin::dashboard_candidates_active/$1');
$routes->add('admin-idle-candidates/(:num)', 'Admin::dashboard_candidates_idle/$1');
$routes->add('admin-inactive-candidates/(:num)', 'Admin::dashboard_candidates_inactive/$1');
$routes->add('admin-active-employers/(:num)', 'Admin::dashboard_employer_active/$1');
$routes->add('admin-idle-employers/(:num)', 'Admin::dashboard_employer_idle/$1');
$routes->add('admin-inactive-employers/(:num)', 'Admin::dashboard_employer_inactive/$1');
$routes->add('admin-college-details', 'Admin::admin_college_details');
$routes->add('admin-course-details', 'Admin::admin_course_details');
$routes->add('admin-specialization-details', 'Admin::admin_specialization_details');
$routes->add('college_details', 'Admin::college_details');
$routes->add('college_course', 'Admin::college_course');
$routes->add('college_specialization', 'Admin::college_specialization');
$routes->add('admin-dashboard-college-state', 'Admin::admin_dashboard_college_state');
$routes->add('collegewise-candidate-list/(:num)', 'Admin::collegewise_candidate_list/$1');
$routes->add('all-transactions', 'Admin::all_transactions_list');
$routes->add('all_phonepe_transactions', 'Admin::all_phonepe_transactions_list');
$routes->add('transactions_status', 'Admin::transaction_status_update');
$routes->add('clear-transaction-status-filter', 'Admin::clear_transaction_status_search_filter');
$routes->add('set-transaction-status-filter', 'Admin::set_transaction_status_search_filter');
$routes->add('payment-status-update', 'Admin::payment_status_update');
$routes->post('set-transaction-status-filter', 'Admin::set_transaction_status_search_filter');


$routes->add('phonepe_transactions_status', 'Admin::phonepe_transaction_status_update');
$routes->add('phonepe-clear-transaction-status-filter', 'Admin::phonepe_clear_transaction_status_search_filter');
$routes->add('phonepe-set-transaction-status-filter', 'Admin::phonepe_set_transaction_status_search_filter');
$routes->add('phonepe-payment-status-update', 'Admin::phonepe_payment_status_update');
$routes->add('rating-approval', 'Admin::rating_approval');
$routes->add('update_approve_rating_status', 'Admin::update_approve_rating_status');
$routes->add('get_internship_by_company_all', 'Admin::get_internship_by_company');


$routes->add('employer-approval', 'Admin::employer_approval');
$routes->add('update_approve_employer_status', 'Admin::update_approve_employer_status');
$routes->add('update_activate_employer_status', 'Admin::update_activate_employer_status');
$routes->add('update_feature_employer_status', 'Admin::update_feature_employer_status');
$routes->add('can_payment_details_upload', 'Admin::can_payment_details_upload');
$routes->add('can_payment_details_upload_excel', 'Admin::can_payment_details_upload_excel');
$routes->add('view-bulk-payment-details/(:num)', 'Admin::view_bulk_payment_details/$1');
$routes->add('bi_url', 'Admin::bi_url');

$routes->add('admin-blog', 'Admin::admin_blog');
$routes->add('add-blog', 'Admin::add_blog');
$routes->add('blog-list', 'Admin::blog_list');
$routes->add('blog-preview/(:num)', 'Admin::blog_preview/$1');
$routes->add('blog-edit/(:num)', 'Admin::blog_edit/$1');
$routes->add('update-blog', 'Admin::update_blog');
$routes->add('update_blog_status', 'Admin::update_blog_status');
$routes->add('update_exclusive_status', 'Admin::update_exclusive_status');
// $routes->add('admin-view-emp-profile', 'Admin::admin_view_emp_profile');
});
