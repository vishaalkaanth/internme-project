<!DOCTYPE html>
<html>
<?php
$session = session();
//print_r($_SESSION);
use App\Models\Candidate_model;

$Candidate_model = new Candidate_model();
?>

<head>
    <!-- <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css"> -->

    <style>
        /* @font-face {
            font-family: 'soraregular';
            src: url('https://internme-new.devops-in22labs.com/public/assets/fonts/sora-regular-webfont.woff2.woff2') format('woff2'),
                url('https://internme-new.devops-in22labs.com/public/assets/fonts/sora-regular-webfont.woff') format('woff');
            font-weight: normal;
            font-style: normal;

        } */

        @font-face {
            font-family: 'Roboto';
            font-style: normal;
            font-weight: 400;
            src: local('Roboto Regular'), local('Roboto-Regular'), url(http://themes.googleusercontent.com/static/fonts/roboto/v9/zN7GBFwfMP4uA6AR0HCoLQ.ttf) format('truetype');
        }

        * {
            /* font-family: 'soraregular'; */
            font-family: 'Roboto' !important;
            box-sizing: border-box;
        }

        .w-100 {
            width: 100%;
        }

        .pb-20 {
            padding-bottom: 20px;
        }

        .pdfTitle {
            border-bottom: 3px solid #24337D;
            text-transform: uppercase;
            color: #353444;
            padding-bottom: 7px;
            width: auto;
            display: inline-block;
            font-family: 'Roboto';
        }

        /* .tdBdr {
            border-bottom: 1px solid #f2f2f2; padding-bottom: 25px;
        } */
        .reportLine {
            padding-left: 0px;
            margin-bottom: 0px;
            margin-top: 0;
            list-style: none;
        }

        .reportLine div {
            color: #6c757d;
            font-size: 14px;
            width: 100%;
            display: inline-block;
            padding-bottom: 10px;
        }

        .reportLine div.approved {
            color: #8CC63E;
            padding-bottom: 0;
        }

        .reportLine div.pending {
            color: #F1A94D;
            /* padding-bottom: 0; */
        }

        .reportLine .circle_ico {
            width: 24px;
            height: 24px;
            border: 1px solid #0A2051;
            border-radius: 100%;
            position: relative;
            top: 10px;
            right: 10px;
        }

        .reportLine div.line .circle_ico::before {
            content: '';
            width: 2px;
            height: 13px;
            display: inline-block;
            background: #24337D;
            position: absolute;
            bottom: -13px;
            left: 11px;
        }


        .pe-60 {
            padding-right: 60px;
        }

        .canName {
            color: #24337D;
            margin-bottom: 0;
            margin-top: 0;
            /* font-weight: bold; */
            font-size: 18px;
            font-family: 'Roboto';
        }

        .canName img,
        .compName img {
            float: left;
            margin-right: 5px;
        }

        .compName {
            margin-bottom: 0;
            margin-top: 7px;
        }

        .topDetails {
            color: #24337D;
            margin-bottom: 0;
            margin-top: 0;
            font-size: 15px;
        }

        .topTtl {
            margin-bottom: 0;
            margin-top: 7px;
            color: #505050;
            font-size: 14px;
        }

        .topTtl img {
            width: 20px;
            float: left;
            margin-right: 5px;
        }

        .topTtl span {
            position: relative;
            top: -7px;
        }

        /* .trBdr {
            border-bottom: 1px solid #f2f2f2; display: inline-block; width: 100%; padding-bottom: 15px; margin-bottom: 15px;
        } */
        .workDet {
            color: #6c757d;
            margin: 0;
            font-size: 14px;
            line-height: 20px;
        }

        .circle_ico {
            margin: 0 !important;
            padding: 0 !important;
            text-align: center !important;
        }

        .circle_ico img {
            margin: 0 !important;
            padding: 0 !important;
            margin-top: 6px !important;
        }

        .ico_label_pack span {
            /* margin-bottom: 5px!important; */
        }
    </style>
</head>

<body>
    <table class="w-100">
        <tr>
            <td style="text-align: center;">
                <p class="pdfTitle">Work Report</p>
            </td>
        </tr>
        <tr>
            <td>
                <table style="width: 100%;">
                    <td style="width: 350px; float:left;" valign="top">
                        <p style="border-radius: 0 30px 30px 0px; display:inline-block; padding: 4px 20px 3px 15px; font-size: 1rem; border: 1px solid #7c8bd0; border-left: 4px solid #24337D; color: #24337D; position: relative; margin-right: auto; margin-left:0; margin-bottom: 0;"><span style="position: absolute; top: -15px; font-size: 13px; left: 13px; color: #212529; background: #FFF; padding: 1px 3px; ">Name</span><img src="<?= base_url(); ?>/public/assets/img/candidate.svg" alt="Profile" style="margin-right: 10px;" width="11"><?php if (isset($candidate_details->profile_full_name)) {
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            echo ucfirst($candidate_details->profile_full_name);
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        } ?>
                        </p>
                        <p style="font-size: 12px; color: #505050; margin-bottom: 0;"><?php if (isset($candidate_educational_details->education_course) && $candidate_educational_details->education_course != 0) {
                                                                                            echo ucfirst($Candidate_model->get_master_name('master_academic_courses', $candidate_educational_details->education_course, 'name'));
                                                                                        } else {
                                                                                            echo ucfirst($candidate_educational_details->education_course_other);
                                                                                        } ?> ( <?php if (isset($candidate_educational_details->education_course) && $candidate_educational_details->education_specialization != 0) {
                                                                            echo ucfirst($Candidate_model->get_master_name('master_academic_branch', $candidate_educational_details->education_specialization, 'name'));
                                                                        } else {
                                                                            echo ucfirst($candidate_educational_details->education_specialization_other);
                                                                        } ?> )</p>
                        <p style="font-size: 12px; color: #505050; margin-top:0;">
                            <?php if (!empty($candidate_educational_details->education_college_name) && $candidate_educational_details->education_college_name != 0) {
                                echo $Candidate_model->get_master_name('master_college', $candidate_educational_details->education_college_name, 'college_name');
                            } else {
                                echo $candidate_educational_details->education_college_name_other;
                            } ?>
                        </p>
                    </td>
                    <td style="width: 350px; float:right; text-align:right;">
                        <?php

                        if (isset($view_profile_details->profile_company_name)) { ?>
                            <p class="compName" style="float: right; height: 50px; position: relative; text-align: right;">
                                <span style="float: right;">
                                    <span style="height:50px; margin-right: 10px;">
                                        <img src="<?= base_url(); ?>/public/assets/docs/uploads/emp_profile/<?php echo $view_profile_details->profile_company_logo; ?>" alt="" style="height: 30px;">
                                    </span>
                                    <span style="position: relative; top: 0px;"><?php echo $view_profile_details->profile_company_name; ?></span>
                                </span><br>
                                <span style="position: relative; font-size: 12px; color: #505050;">Work Location : <?php if ($internship_details->internship_type == '1') {
                                                                                                                        echo $internship_applied_list->work_location_name;
                                                                                                                    } else {
                                                                                                                        echo "Work From Home";
                                                                                                                    } ?></span>
                            </p>
                        <?php } ?>
                    </td>
                </table>
            </td>
        </tr>
        <tr>
            <td class="tdBdr" style="border-bottom: 1px solid #f2f2f2; margin-right: auto; margin-left: 0; width: 100%; padding-bottom: 20px; display: block;">
                <table>
                    <tr>
                        <td class="" style="margin-bottom: 15px; clear: both; padding-right: 40px;">
                            <p class="topDetails"><?php if (isset($internship_details->profile) && $internship_details->profile != '0') {
                                                        echo $Candidate_model->get_master_name('master_profile', $internship_details->profile, 'profile');
                                                    } else {
                                                        echo $internship_details->other_profile;
                                                    } ?></p>
                            <p class="topTtl"><img src="<?= base_url(); ?>/public/assets/img/icon_intern.svg" alt="" style="width:18px;"><span>Internship</span></p>
                        </td>
                        <?php if (!empty($internship_applied_list->emp_supervisor)) {
                            $where_sup = array('userid' => $internship_applied_list->emp_supervisor);
                            $user_details_sup = $Candidate_model->fetch_table_row('userlogin', $where_sup); ?>
                        <td style="padding-right: 40px;">
                            <p class="topDetails">
                            <?php echo $user_details_sup->name; ?>
                            </p>
                            <p class="topTtl"><img src="<?= base_url(); ?>/public/assets/img/admin.svg" alt="" style="width:16px;"><span>supervisor</span></p>
                        </td>
                        <?php } ?>
                        <?php if (!empty($internship_details->internship_startdate)) { ?>
                        <td style="padding-right: 40px;">
                            <p class="topDetails">
                                <?php echo date("d-m-Y", strtotime($internship_details->internship_startdate)); ?>
                            </p>
                            <p class="topTtl"><img src="<?= base_url(); ?>/public/assets/img/calendar.svg" alt="" style="width:16px;"><span>Start Date</span></p>
                        </td>
                        <?php } ?>
                        <td style="padding-right: 40px;">
                            <p class="topDetails"><?php if (isset($internship_details->internship_duration)) {
                                                        echo $internship_details->internship_duration;
                                                    } ?> <?php if (isset($internship_details->internship_duration_type)) {
                                                                if ($internship_details->internship_duration_type == 1) {
                                                                    // echo "Week";
                                                                    if ($internship_details->internship_duration == 1) {
                                                                        echo "Week";
                                                                    } else {
                                                                        echo "Weeks";
                                                                    }
                                                                } elseif ($internship_details->internship_duration_type == 2) {
                                                                    // echo "Months";
                                                                    if ($internship_details->internship_duration == 1) {
                                                                        echo "Month";
                                                                    } else {
                                                                        echo "Months";
                                                                    }
                                                                }
                                                            } ?></p>
                            <p class="topTtl"><img src="<?= base_url(); ?>/public/assets/img/dur.svg" alt="" style="width:18px;"><span>Duration</span></p>
                        </td>
                        <?php if (!empty($internship_applied_list->faculty_id)) {
                            $where_sup = array('userid' => $internship_applied_list->faculty_id);
                            $user_details_faculty = $Candidate_model->fetch_table_row('userlogin', $where_sup);
                           if (!empty($user_details_faculty)) {
                            ?>
                        <td style="padding-right: 40px;">
                            <p class="topDetails"><?php echo $user_details_faculty->username; ?></p>
                            <p class="topTtl"><img src="<?= base_url(); ?>/public/assets/img/icon_apply2.svg" alt="" style="width:20px;"><span>Faculty Incharge</span></p>
                        </td>
                        <?php } }?>
                        <?php if (!empty($internship_applied_list->faculty_id)) {
                            $where_sup = array('userid' => $internship_applied_list->faculty_id);
                            $user_details_faculty = $Candidate_model->fetch_table_row('userlogin', $where_sup);
                           if (!empty($user_details_faculty)) {
                            $where1 = array('internship_id' => $internship_applied_list->internship_id,'faculty_id' => $internship_applied_list->faculty_id);
            // $wherein1 = array('candidate_id' => $ongoing->candidate_id);
            $col_data = 'id,visited_date';
            $chek_update = $Candidate_model->fetch_table_data_col_where_in('faculty_visited_data_final', $where1,$internship_applied_list->candidate_id,$col_data);
            if (!empty($chek_update)) {
                            ?>
                        <td>
                            <p class="topDetails"><?php echo date("d-m-Y", strtotime($chek_update[0]->visited_date)); ?></p>
                            <p class="topTtl"><img src="<?= base_url(); ?>/public/assets/img/calendar.svg" alt="" style="width:18px;"><span>Visit Date</span></p>
                        </td>
                        <?php } } }?>
                        <!-- <td class="pe-60">
                            <p class="topDetails">
                                <?php
                                $total_hours_sum = 0;
                                if (!empty($log_sheet_details)) {
                                    foreach ($log_sheet_details as $sheet_details) {
                                        $total_hours_sum += $sheet_details->worked_hours;
                                    }

                                    if ($total_hours_sum == 1) {
                                        echo $total_hours_sum . ' Hour';
                                    } else {
                                        echo $total_hours_sum . ' Hours';
                                    }
                                }


                                ?>

                            </p>
                            <p class="topTtl"><img src="<?= base_url(); ?>/public/assets/img/chat_schedule.svg" alt="" style="width:18px;"><span>No. of Hours</span></p>
                        </td>
                        <td class="pe-60">
                            <p class="topDetails">
                                <?php if (count($log_sheet_details) == 1) {
                                    echo count($log_sheet_details) . ' Day';
                                } else {
                                    echo count($log_sheet_details) . ' Days';
                                }  ?>
                            </p>
                            <p class="topTtl"><img src="<?= base_url(); ?>/public/assets/img/calendar.svg" alt="" style="width:16px;"><span>No. of Days</span></p>
                        </td> -->
                    </tr>
                    <!-- <tr>
                        <td class="pe-60" style="width: 120px;">
                            <p class="topDetails"><?php if (isset($internship_details->internship_startdate)) {
                                                        echo date("d-M-Y", strtotime($internship_details->internship_startdate));
                                                    } ?></p>
                            <p class="topTtl"><img src="<?= base_url(); ?>/public/assets/img/calendar2.svg" alt="">From</p>
                        </td>
                        
                        <td class="pe-60">
                            <p class="topDetails"><?php if (isset($internship_details->assigned_to)) {
                                                        $where_hr = array('userid' => $internship_details->assigned_to);
                                                        $user_details_hr = $Candidate_model->fetch_table_row('userlogin', $where_hr);
                                                        echo $user_details_hr->name;
                                                    } ?></p>
                            <p class="topTtl"><img src="<?= base_url(); ?>/public/assets/img/admin.svg" alt="" style="width: 15px;">HR</p>
                        </td>
                        <?php if (!empty($internship_applied_list->emp_supervisor)) {
                            $where_sup = array('userid' => $internship_applied_list->emp_supervisor);
                            $user_details_sup = $Candidate_model->fetch_table_row('userlogin', $where_sup); ?>
                            <td>
                                <p class="topDetails"><?php echo $user_details_sup->name; ?></p>
                                <p class="topTtl"><img src="<?= base_url(); ?>/public/assets/img/admin.svg" alt="" style="width: 15px;">Supervisor</p>
                            </td>
                        <?php } ?>
                    </tr> -->
                </table>
            </td>
        </tr>
    </table>
    <?php
    // $total_hours_sum = 0;
    if (!empty($log_sheet_details)) {
        foreach ($log_sheet_details as $sheet_details) {
            $date = date_create($sheet_details->log_date);
    ?>
            <table class="w-100" style="border-bottom: 1px solid #f2f2f2; width: 100%; margin-right: auto; margin-left: 0; padding-bottom: 10px; margin-top: 15px;">

                <tr class="trBdr">
                    <td valign="top" style="width: 130px;">
                        <div class="reportLine">
                            <div class="line" style="padding-bottom: 0; width: 100%; margin-right: auto; margin-left: 0; ">
                                <div style="clear:both;">
                                    <div class="circle_ico" style="margin-top: 10px;"><img src="<?= base_url(); ?>/public/assets/img/pdf_date1.png" width="12" alt=""></div>
                                    <label style="margin-top:0px; padding-top:0!important;"><?php echo date_format($date, "d M Y"); ?></label>
                                </div>
                            </div>
                            <div class="line" style="padding-bottom: 0; width: 100%; margin-right: auto; margin-left: 0;">
                                <div style="clear:both;">
                                    <div class="circle_ico"><img src="<?= base_url(); ?>/public/assets/img/pdf_time1.png" width="12" alt="" style="max-width:100%;"></div>
                                    <label style="margin-top:0px; padding-top:0!important;"><?php
                                                                                            if ($sheet_details->worked_hours == '1') {
                                                                                                echo $sheet_details->worked_hours . ' Hour';
                                                                                            } else {
                                                                                                echo $sheet_details->worked_hours . ' Hours';
                                                                                            }
                                                                                            // $total_hours_sum+= $sheet_details->worked_hours;
                                                                                            ?></label>
                                </div>
                            </div>
                            <?php if ($sheet_details->approved_status == '1') { ?>
                                <div class="approved" style="padding-bottom: 0; width: 100%; margin-right: auto; margin-left: 0;">
                                    <div style="clear:both;">
                                        <div class="circle_ico" style="margin-top: 10px;"><img src="<?= base_url(); ?>/public/assets/img/pdf_approve1.png" width="14" alt="" style="max-width:100%;"></div>
                                        Approved
                                    </div>
                                </div>
                            <?php } else { ?>
                                <div class="pending" style="padding-bottom: 0; width: 100%; margin-right: auto; margin-left: 0;">
                                    <div style="clear:both;">
                                        <div class="circle_ico" style="margin-top: 10px;"><img src="<?= base_url(); ?>/public/assets/img/pdf_pending1.png" width="14" alt="" style=" max-width:100%;"></div>
                                        Pending
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </td>

                    <td valign="top" style="padding-left: 50px;">
                        <p class="workDet"><?php echo nl2br($sheet_details->description); ?></p>
                    </td>

                </tr>
            </table>
          
    <?php }
    }
    // echo $total_hours_sum;
    ?>
  <?php if ($rating == '1') { ?>
            <table class="w-100;" style="margin-top:1rem">
            <tr>
                    <td>
                        <div class="text-blue btm_line w-100">Employer&nbsp;Rating/Feedback</div>
                    </td>
                </tr>
                <tr>
                    <td style="width: 120px; border:none; outline:0; padding-top:1rem;">
                        <?php if ($internship_applied_list->emp_ratings == '1') { ?>
                            <img src="<?= base_url(); ?>/public/assets/img/star_f_blue.png" width="14" alt="">
                            <img src="<?= base_url(); ?>/public/assets/img/star_o_blue.png" width="14" alt="">
                            <img src="<?= base_url(); ?>/public/assets/img/star_o_blue.png" width="14" alt="">
                            <img src="<?= base_url(); ?>/public/assets/img/star_o_blue.png" width="14" alt="">
                            <img src="<?= base_url(); ?>/public/assets/img/star_o_blue.png" width="14" alt="">
                        <?php } elseif ($internship_applied_list->emp_ratings == '2') { ?>
                            <img src="<?= base_url(); ?>/public/assets/img/star_f_blue.png" width="14" alt="">
                            <img src="<?= base_url(); ?>/public/assets/img/star_f_blue.png" width="14" alt="">
                            <img src="<?= base_url(); ?>/public/assets/img/star_o_blue.png" width="14" alt="">
                            <img src="<?= base_url(); ?>/public/assets/img/star_o_blue.png" width="14" alt="">
                            <img src="<?= base_url(); ?>/public/assets/img/star_o_blue.png" width="14" alt="">
                        <?php } elseif ($internship_applied_list->emp_ratings == '3') { ?>
                            <img src="<?= base_url(); ?>/public/assets/img/star_f_blue.png" width="14" alt="">
                            <img src="<?= base_url(); ?>/public/assets/img/star_f_blue.png" width="14" alt="">
                            <img src="<?= base_url(); ?>/public/assets/img/star_f_blue.png" width="14" alt="">
                            <img src="<?= base_url(); ?>/public/assets/img/star_o_blue.png" width="14" alt="">
                            <img src="<?= base_url(); ?>/public/assets/img/star_o_blue.png" width="14" alt="">
                        <?php } elseif ($internship_applied_list->emp_ratings == '4') { ?>
                            <img src="<?= base_url(); ?>/public/assets/img/star_f_blue.png" width="14" alt="">
                            <img src="<?= base_url(); ?>/public/assets/img/star_f_blue.png" width="14" alt="">
                            <img src="<?= base_url(); ?>/public/assets/img/star_f_blue.png" width="14" alt="">
                            <img src="<?= base_url(); ?>/public/assets/img/star_f_blue.png" width="14" alt="">
                            <img src="<?= base_url(); ?>/public/assets/img/star_o_blue.png" width="14" alt="">
                        <?php } elseif ($internship_applied_list->emp_ratings == '5') { ?>
                            <img src="<?= base_url(); ?>/public/assets/img/star_f_blue.png" width="14" alt="">
                            <img src="<?= base_url(); ?>/public/assets/img/star_f_blue.png" width="14" alt="">
                            <img src="<?= base_url(); ?>/public/assets/img/star_f_blue.png" width="14" alt="">
                            <img src="<?= base_url(); ?>/public/assets/img/star_f_blue.png" width="14" alt="">
                            <img src="<?= base_url(); ?>/public/assets/img/star_f_blue.png" width="14" alt="">
                        <?php } elseif ($internship_applied_list->emp_ratings == '0') { ?>
                            <img src="<?= base_url(); ?>/public/assets/img/star_o_blue.png" width="14" alt="">
                            <img src="<?= base_url(); ?>/public/assets/img/star_o_blue.png" width="14" alt="">
                            <img src="<?= base_url(); ?>/public/assets/img/star_o_blue.png" width="14" alt="">
                            <img src="<?= base_url(); ?>/public/assets/img/star_o_blue.png" width="14" alt="">
                            <img src="<?= base_url(); ?>/public/assets/img/star_o_blue.png" width="14" alt="">
                        <?php } ?>
                    </td>
                </tr>
           
                <tr>
                    <td>
                        <p style="font-size:12px; line-height:2px"><?php echo $internship_applied_list->emp_feedback; ?></p>
                    </td>
                </tr>
            </table>
            <?php } ?>


</body>

</html>