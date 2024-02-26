<?php

namespace App\Controllers;

class Design extends BaseController
{
    public function emp_login()
    {
        return view('Auth/emp_login');
    }
    public function emp_register1()
    {
        return view('Auth/emp_register');
    }
    public function emp_register_otp()
    {
        return view('Auth/emp_register_otp');
    }
    public function emp_set_password()
    {
        return view('Auth/emp_set_password');
    }
    public function emp_forgot_pass()
    {
        return view('Auth/emp_forgot_pass');
    }
    public function emp_forgot_otp()
    {
        return view('Auth/emp_forgot_otp');
    }
    public function emp_reset_password()
    {
        return view('Auth/emp_reset_password');
    }
    public function emp_login1()
    {
        return view('Design/emp_login');
    }
    public function post_internship()
    {
        return view('design/post_internship');
    }
    public function emp_intern_single()
    {
        return view('design/emp_intern_single');
    }
    public function emp_intern_list()
    {
        return view('design/emp_intern_list');
    }
    public function emp_profile1()
    {
        return view('design/emp_profile1');
    }
    public function emp_profile2()
    {
        return view('design/emp_profile2');
    }
    public function emp_profile3()
    {
        return view('design/emp_profile3');
    }
    public function can_intern_list()
    {
        return view('design/can_intern_list');
    }
    public function can_intern_single()
    {
        return view('design/can_intern_single');
    }
    public function emp_applied_can()
    {
        return view('design/emp_applied_can');
    }
    public function can_profile_personal1()
    {
        return view('design/can_profile_personal');
    }
    public function can_profile_education1()
    {
        return view('design/can_profile_education');
    }
    public function can_profile_address1()
    {
        return view('design/can_profile_address');
    }
    public function can_profile_experience1()
    {
        return view('design/can_profile_experience');
    }
    public function can_profile_skills1()
    {
        return view('design/can_profile_skills');
    }
    public function can_profile_work1()
    {
        return view('design/can_profile_work');
    }
    public function can_applied_intern_list()
    {
        return view('design/can_applied_intern_list');
    }
    public function can_apply_for_intern()
    {
        return view('design/can_apply_for_intern');
    }
    public function can_proceed_apply()
    {
        return view('design/can_proceed_apply');
    }
    public function can_profile_single()
    {
        return view('design/can_profile_single');
    }
    public function can_applied_intern_review()
    {
        return view('design/can_applied_intern_review');
    }
    public function emp_view_can_profile()
    {
        return view('design/emp_view_can_profile');
    }
    public function emp_pricing_plan()
    {
        return view('design/emp_pricing_plan');
    }
    public function emp_dashboard()
    {
        return view('design/emp_dashboard');
    }
    public function emp_manage_admin()
    {
        return view('design/emp_manage_admin');
    }
    public function emp_admin_single()
    {
        return view('design/emp_admin_single');
    }
    public function link_expired()
    {
        return view('design/link_expired');
    }
    public function emp_employee_list()
    {
        return view('design/emp_employee_list');
    }
    public function change_password_success1()
    {
        return view('design/change_password_success');
    }
    public function can_dashboard()
    {
        return view('design/can_dashboard');
    }
    public function emp_hired_interns()
    {
        return view('design/emp_hired_interns');
    }
    public function admin_dashboard()
    {
        return view('design/admin_dashboard');
    }
    public function emp_access_db()
    {
        return view('design/emp_access_db');
    }
    public function redirect_invitation()
    {
        return view('design/redirect_invitation');
    }
    public function folder()
    {
        return view('design/folder');
    }
    public function can_logsheet()
    {
        return view('design/can_logsheet');
    }
    public function emp_logsheet()
    {
        return view('design/emp_logsheet');
    }
    public function certificate()
    {
        return view('design/certificate');
    }
    public function emp_certificate()
    {
        return view('design/emp_certificate');
    }
    public function chat()
    {
        return view('design/chat');
    }
    public function resume()
    {
        return view('design/resume');
    }
    public function work_report()
    {
        return view('design/work_report');
    }
    public function admin_merge_college()
    {
        return view('design/admin_merge_college');
    }
    public function emp_profile()
    {
        return view('design/emp_profile');
    }
    public function companies()
    {
        return view('design/companies');
    }
    public function nopage()
    {
        return view('design/404');
    }
    public function roles_responsibility()
    {
        return view('design/roles_responsibility');
    }
    public function employer_dashboard_new()
    {
        return view('design/employer_dashboard_new');
    }
    public function teacher_dashboard()
    {
        return view('design/teacher_dashboard');
    }
    public function teacher_profile_single()
    {
        return view('design/teacher_profile_single');
    }
    public function verification_of_email_candidate()
    {
        return view('email_template/verification_of_email_candidate');
    }
    public function teacher_candidate_list()
    {
        return view('design/teacher_candidate_list');
    }
    public function faculty_work_report()
    {
        return view('design/faculty_work_report');
    }
    public function faculty_work_upload()
    {
        return view('design/faculty_work_upload');
    }
    public function visit_log()
    {
        return view('design/visit_log');
    }
    public function employer_assignment_sent()
    {
        return view('design/employer_assignment_sent');
    }
    public function employer_interview_scheduled()
    {
        return view('design/employer_interview_scheduled');
    }
    public function can_subscription()
    {
        return view('design/can_subscription');
    }
    public function can_recipt()
	{
		return view('design/can_recipt');
	}
    public function transaction_ui()
	{
		return view('design/transaction_ui');
	}
    public function paid_internships()
	{
		return view('design/paid_internships');
	}
    public function subscription_plan()
	{
		return view('design/subscription_plan');
	}
    public function assessment_ui()
	{
		return view('design/assessment_ui');
	}

    public function assessment_score()
	{
		return view('design/assessment_score');
	}


    public function gmetrix_ui()
	{
		return view('design/gmetrix');
	}

    public function pricing_plan_ui()
    {
        return view('design/pricing_plan');
    }
    public function blog_ui()
    {
        return view('design/blog'); 
    }
    public function blog_single_ui()
    {
        return view('design/blog_single');
    }
    public function can_viewed_emp()
    {
        return view('design/can_viewed_emp');
    }
    public function admin_form()
    {
        return view('design/admin_form');
    }
    public function admin_listing_form()
    {
        return view('design/admin_listing_form');
    }
    public function admin_preview_form()
    {
        return view('design/admin_preview_form');
    }
    public function design_home()
    {
        return view('design/design_home');
    }

    
    
}
