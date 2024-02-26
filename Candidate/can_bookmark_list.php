<!DOCTYPE html>
<html>

<?php
$session = session();
// print_r($_SESSION);
// print_r($applied_internship_list);
use App\Models\Candidate_model;
$Candidate_model = new Candidate_model();
//$this->load->view('common/head'); 
$userid    =    $session->get('userid');
require_once(APPPATH . "Views/Common/head.php");
?>

<body class="stickyFoot">

    <?php require_once(APPPATH . "Views/Common/header.php"); ?>

    <!----- Form ------>
    <section class="container filterable my-4">
        <div class="d-flex justify-content-between flex-wrap align-items-center mb-4">
            <h2 class="page_title mb-sm-0 mb-3">My Bookmarks</h2>
            <a href="#" class="text-blue backBtn me-3" onclick="previous()"><i class="fa fa-long-arrow-left me-1" aria-hidden="true"></i> Back</a>
        </div>
        <?php require_once(APPPATH . "Views/Common/error_page.php"); ?>
        <div class="card p-4">

            <div class="pgContent">
                <div class="table-responsive hideBr">
                    <table class="table" id="example">
                        <thead>
                            <tr class="filters">
                                <th scope="col">S.No</th>
                                <th scope="col">Company</th>
                                <th scope="col">Profile</th>
                                 <th class="text-center" scope="col">Number Of Applicants</th> 
                                 <th scope="col">Last Date To Apply</th>
                                 <th scope="col">Duration</th>
                                <th scope="col">Status</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if (isset($can_bookmark_list) && !empty($can_bookmark_list)) {
                            $i=1;
                             foreach ($can_bookmark_list as $internship_list) {

                                $where = array('status' => '1','internship_id' => $internship_list->internship_id);
                                $internship_details = $Candidate_model->fetch_table_row('employer_post_internship', $where);
                                $where_emp = array('status' => '1','userid' => $internship_list->emp_user_id);
                                $employer_details = $Candidate_model->fetch_table_row('profile_completion_form', $where_emp);
                                $where_count = array('status' => '1', 'active_status' => '1', 'internship_id' => $internship_details->internship_id);
                                $applicant_count = $Candidate_model->data_count_fetch('can_applied_internship', $where_count);
                                // print_r($applicant_count);exit();
                                //applid intership status
                                $where2 = array('status' => '1','internship_id' => $internship_list->internship_id,'candidate_id' => $userid);
                                $internship_applied_details = $Candidate_model->fetch_table_row('can_applied_internship', $where2);
                                // print_r($internship_applied_details);
                                ?>
                                <BR>
                            <tr>
                                <td scope="row"><?php echo $i;?></td>
                                <td class="overflow-anywhere"><?php if (isset($employer_details->profile_company_name)) { echo $employer_details->profile_company_name;}?></td>
                               

                                <td><a href="<?php echo base_url('internship-details'); ?>/<?php echo $internship_list->internship_id; ?>" class="text-blue1"><?php if (isset($internship_details->profile) && $internship_details->profile != '0') { echo $Candidate_model->get_master_name('master_profile', $internship_details->profile, 'profile');} else { echo $internship_details->other_profile; } ?></a></td>
                                <td class="text-center"><?php  echo $applicant_count; ?></td>
                                 <td><?php if (!empty($internship_details)) { echo date('d-m-Y',strtotime($internship_details->internship_candidate_lastdate)); }?></td>
                                <td><?php if (isset($internship_details->internship_duration)) {
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
                                                                                            } ?></td>
                                 <td>
                                    
                                        <?php 
                                        if (!empty($internship_applied_details)) 
                                        {

                                            if ($internship_applied_details->hiring_status==1) 
                                            {
                                             echo "<span class='badge badge-completed fw-normal'>Offer accepted</span>";
                                            }
                                             elseif ($internship_applied_details->hiring_status==2) 
                                             {
                                                 echo "<span class='badge badge-red fw-normal'>Offer declined</span>";
                                              }
                                              elseif($internship_applied_details->complete_status==1)
                                                    {
                                                        if($internship_applied_details->complete_type != 1){
                                                            echo "<span class='badge badge-red fw-normal'>Dropped</span>";
                                                        } else{
                                                            echo "<span class='badge badge-red fw-normal'>Completed</span>";
                                                        }
                                                        
                                                    }  
                                         else{
                                           
                                        if($internship_applied_details->application_status==0)
                                        {
                                            echo "<span class='badge badge-ongoing fw-normal'>Under Review</span>";
                                        }
                                         elseif($internship_applied_details->application_status==1)
                                            {
                                                echo "<span class='badge badge-ongoing fw-normal'>Under Review</span>";
                                            } 
                                            elseif($internship_applied_details->application_status==2)
                                                {
                                                    echo "<span class='badge badge-completed fw-normal'>Hired</span>";
                                                } 
                                                elseif($internship_applied_details->application_status==3)
                                                    {
                                                        echo "<span class='badge badge-red fw-normal'>Not Qualified</span>";
                                                    } 
                                                    
                                                // }
                                            }
                                         } else
                                            {
                                                ?>
                                                <span class="badge badge-gray fw-normal">
                                                 Not Applied</span>
                                                 <?php
                                            }

                                                    ?>
                                   
                                </td>
                                <!-- <td><?php echo $newDate = date("d-M-Y", strtotime($internship_list->created_at));?></td> -->
                                <td scope="row"><a onclick="func_can_bookmark('3','<?php echo $internship_list->internship_id;?>','<?php echo $internship_list->emp_user_id;?>','<?php echo $internship_details->profile;?>')" class="align-self-end mt-2 mt-md-0 px-4"><i class="fa fa-trash-o text-blue me-2" aria-hidden="true"></i></a></td>
                            </tr>
                        <?php $i++; } }?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <?php require_once(APPPATH . "Views/Common/footer.php"); ?>
    <?php require_once(APPPATH . "Views/Common/script.php"); ?>
    <script>
        $(document).ready(function() {
            $('#example').DataTable();
        });

        function func_can_bookmark(val,internship_id,emp_user_id,profile) {
            // swal({
            //     title: "Alert",
            //     text: "Bookmark Removed",
            //     type: "info",
            //     showCancelButton: true,
            //     confirmButtonClass: "btn-primary",
            //     confirmButtonText: "Proceed",
            //     cancelButtonText: "Cancel",
            //     closeOnConfirm: false,
            //     closeOnCancel: false
            // }, function(isConfirm) {

            //     if (isConfirm) {
                        window.location.href = '<?= base_url(); ?>/can_intership_bookmark_single/'+val+'/'+internship_id+'/'+emp_user_id+'/'+profile+'/16';
            //     } else {
            //         swal("Cancelled", "You Have Cancelled", "error");
            //     }
            // })
            // swal("Please Complete Your Profile", "You clicked the button!", "success");
        }
        function previous() {
            window.history.go(-1);
        }
    </script>
</body>

</html>