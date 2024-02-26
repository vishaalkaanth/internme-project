<!DOCTYPE html>
<html>

<?php
require_once(APPPATH . "Views/Common/head.php");
$session = session();
// print_r($_SESSION);
// print_r($applied_internship_list);
use App\Models\Candidate_model;

$Candidate_model = new Candidate_model();
$login = $session->get('isLoggedIn');
//$this->load->view('common/head'); 
$userid    =    $session->get('userid');

?>

<body class="stickyFoot <?php if (!isset($login) && $login == '') {
                            echo 'resTop';
                        } ?>">

    <?php
    if (isset($login) && $login != '') {
        require_once(APPPATH . "Views/Common/header.php");
        require_once(APPPATH . "Views/Common/error_page.php");
    } else {
    ?>

        <header>
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    <div class="container d-flex flex-wrap flex-sm-nowrap justify-content-center">
                        <a class="navbar-brand py-0 mb-sm-0 mb-4" href="<?= base_url(); ?>/"><img src="<?= base_url(); ?>/public/assets/img/logo_blue.svg" alt="Logo" class="img-fluid" width="200"></a>
                        <!-- <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button> -->
                        <div class="navbar-collapse" id="navbarSupportedContent">
                            <ul class="menu navbar-nav me-auto mb-lg-0 justify-content-sm-end justify-content-center align-items-center w-100 d-flex flex-wrap gap-3 gap-md-0" id="mainNav">
                                <!-- <li class="nav-item">
                                <a class="nav-link text-white active" href="#home">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="#service">Features</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="#about">Customers</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="#contact">Testi</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="#contact">Why Us</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="#contact">Contact Us</a>
                            </li> -->
                                <li class="dropdown me-md-3">
                                    <div class="input-group searchField headSearchAln headResSearch me-md-3 me-1">

                                        <input type="search" class="form-control rounded" placeholder="Search Internship" onkeypress="enterpress_search(event, this)" id="search_value" value="<?php
                                                                                                                                                                                                //show when search with keyword
                                                                                                                                                                                                $session = session();
                                                                                                                                                                                                $searched_keyword = $session->get('searched_keyword');
                                                                                                                                                                                                if (isset($searched_keyword)) {

                                                                                                                                                                                                    echo $searched_keyword;
                                                                                                                                                                                                }
                                                                                                                                                                                                ?>">
                                        <button class="btn btn-prim px-3" type="button" id="button-addon2" onclick="search_keyword(4)"><i class="fa fa-search" aria-hidden="true"></i></button>
                                        <!-- <span class="input-group-text">
                                            <img src="<?= base_url(); ?>/public/assets/img/search.svg" alt="Search" width="14">
                                        </span> -->
                                        <span id="search_alert" style="color: red;position: absolute;font-size: 10px;top: 36px;"></span>
                                    </div>
                                </li>
                                <li class="dropdown me-md-3 me-1">
                                    <a href="#" class="btn-outlined-blue dropdown-toggle px-md-3 px-2" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">Login</a>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li><a class="dropdown-item" href="<?= base_url(); ?>/main_login/1">Candidate</a></li>
                                        <li><a class="dropdown-item" href="<?= base_url(); ?>/main_login/2">Employer</a></li>
                                        <li><a class="dropdown-item" href="<?= base_url(); ?>/facultylogin">Faculty</a></li>
                                    </ul>
                                </li>
                                <li class="dropdown">
                                    <a href="<?= base_url(); ?>/register/candidate" class="btn-prim dropdown-toggle py-2 px-md-3 px-2" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">Register</a>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li><a class="dropdown-item" href="<?= base_url(); ?>/register/candidate">Candidate</a></li>
                                        <li><a class="dropdown-item" href="<?= base_url(); ?>/register/employer">Employer</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>
            <!-- <div class="container bannerSec d-flex mt-5">
            <div class="col-md-7 bannerLt">
                <h1 class="text-white mb-4">We are The Brilliants In Terms of <span class="text-yellow">Connecting Internships!</span></h1>
                <p class="text-white">Hyperlocal platform connecting College students and MSME's with meaningful Internships.</p>
                <a href="#" class="btn-primary fw-medium mt-3 d-inline-block">Take Internship</a>
            </div>
            <div class="col-md-5">
                <img src="<?= base_url(); ?>/public/assets/img/bannerImg.png" alt="banner" class="img-fluid">
            </div>
        </div> -->
        </header>
    <?php
    } ?>
    <!----- Form ------>
    <section class="viewEmpProf py-4 mt-0 pt-0">
        <div class="container mt-lg-5 mt-4">
            <?php
            $current_plan = $current_plan_premium = $current_plan_plus = '';
            if (isset($login) && $login != '') {
                $where = array('status' => '1', 'userid' => $userid);
                $payment_status_details = $Candidate_model->get_data_common_row('can_personal_details', ('id,payment_package_type,payment_status,payment_expiry_date,can_profile_complete_status'), $where);
                if ($payment_status_details->payment_package_type == 2 && !empty($payment_status_details->payment_expiry_date) && $payment_status_details->payment_expiry_date > date('Y-m-d')) {
                    $current_plan_premium = 'current_plan';
                } elseif ($payment_status_details->payment_package_type == 1 && !empty($payment_status_details->payment_expiry_date) && $payment_status_details->payment_expiry_date > date('Y-m-d')) {
                    $current_plan_plus = 'current_plan';
                } else {
                    $current_plan = 'current_plan';
                }
            }
            ?>

            <div class="text-center">
                <h1 class="fw-bold text-blue mb-2 pe-0 pe-md-4 fs-2 lh-base">Internme <span class="text-olive">subscription plan</span></h1>
            </div>
            <div class="col-12 row d-flex flex-wrap mt-lg-5 mt-4 mx-0">
                <div class="col-12 col-lg-4 px-md-4 px-0 d-lg-flex mb-4 mb-lg-0">
                    <div class="ps-3 text-center py-4 d-flex justify-content-between flex-column subs_plan_cards  flex-grow-1 <?php //echo $current_plan; 
                                                                                                                                ?>">
                        <div>
                            <h3 class="text-blue text-start fw-semibold mb-4"><img src="<?= base_url(); ?>/public/assets/img/subs_basic.svg" width="35" alt="" class="img-fluid mb-1 me-2">Basic</h3>
                            <h3 class="text-blue text-start fw-semibold">₹ <span style="color: #9EA0A2; text-decoration:line-through;">599</span> Free<span class="fs-4"><small> / Year</small></span></h3>
                            <ul class="px-3 py-3 subad_ul text-start">
                                <li>Unlimited Internship (Regular) </li>
                                <li>Chat with employer</li>
                                <li>Certificate</li>
                                <!-- <li>Work logbook -Approved by Universities</li> -->
                            </ul>
                        </div>
                        <?php if (isset($login) && $login != '') {
                            if ($payment_status_details->can_profile_complete_status == 1) {
                                if ($payment_status_details->payment_package_type < 1 && !empty($payment_status_details->payment_expiry_date) && $payment_status_details->payment_expiry_date > date('Y-m-d')) { ?>
                        <a class="btn btn-sub-outline fw-medium me-4 px-5 mb-2 py-2 align-self-center">Current Plan</a>
                        <?php } } }?>
                    </div>
                </div>
                <div class="col-12 col-lg-4 px-md-4 px-0 d-lg-flex mb-4 mb-lg-0">
                    <div class="ps-3 text-center py-4 d-flex justify-content-between flex-column subs_plan_cards <?php //echo $current_plan_plus; 
                                                                                                                    ?>">
                        <div>
                            <h3 class="text-blue text-start fw-semibold mb-4"><img src="<?= base_url(); ?>/public/assets/img/intern_pluss.svg" width="35" alt="" class="img-fluid mb-1 me-2">Intern+</h3>
                            <h3 class="text-blue text-start fw-semibold">₹ <span style="color: #9EA0A2; text-decoration:line-through;">899</span> 499<span class="fs-4"><small> / Year</small></span></h3>
                            <ul class="px-3 py-3 subad_ul text-start">
                                <li>Unlimited Internship (Regular)</li>
                                <li>Chat with employer</li>
                                <li>Certificate</li>
                                <li>Work logbook -Approved by Universities</li>
                                <li>Maximum of 2 premium internships per year.</li>
                            </ul>
                        </div>
                        <?php if (isset($login) && $login != '') {
                            // $where = array('status' => '1', 'userid' => $userid);
                            // $payment_status_details = $Candidate_model->get_data_common_row('can_personal_details', ('id,payment_package_type,payment_status,payment_expiry_date,can_profile_complete_status'), $where);
                            if ($payment_status_details->can_profile_complete_status == 1) {
                                if ($payment_status_details->payment_package_type == 2 && !empty($payment_status_details->payment_expiry_date) && $payment_status_details->payment_expiry_date > date('Y-m-d')) { ?>
                                <?php  } elseif ($payment_status_details->payment_package_type == 1 && !empty($payment_status_details->payment_expiry_date) && $payment_status_details->payment_expiry_date > date('Y-m-d')) { ?>
                                    <a class="btn btn-sub-outline fw-medium me-4 px-5 mb-2 py-2 align-self-center">Current Plan</a>
                                <?php } else {
                                ?>
                                    <a href="<?php echo base_url('/phonepe-candidate-payment/1'); ?>" class="btn btn-sub-outline fw-medium me-4 px-5 mb-2 py-2 align-self-center">Buy Now</a>
                                <?php }
                            } else { ?>

                                <a href="<?php echo base_url('/personal-details'); ?>" class="btn btn-sub-outline fw-medium me-4 px-5 mb-2 py-2 align-self-center">Buy Now</a>
                            <?php  }
                        } else { ?>
                            <!-- <a href="<?php echo base_url('pricing_plan_login'); ?>" class="btn btn-sub-outline fw-medium me-4 px-5 mb-2 py-2 align-self-center">Buy Now</a> -->
                            <a onclick="pricing_plan_login()" class="btn btn-sub-outline fw-medium me-4 px-5 mb-2 py-2 align-self-center">Buy Now</a>
                        <?php } ?>
                    </div>
                </div>
                <div class="col-12 col-lg-4 px-md-4 px-0 d-lg-flex mb-4 mb-lg-0">
                    <div class="ps-3 text-center py-4 d-flex justify-content-between flex-column subs_plan_cards <?php //echo $current_plan_premium; 
                                                                                                                    ?>">
                        <div>
                            <h3 class="text-blue text-start fw-semibold mb-4"><img src="<?= base_url(); ?>/public/assets/img/subs_intern_perimum.svg" width="35" alt="" class="img-fluid mb-1 me-2">Intern premium</h3>
                            <h3 class="text-blue text-start fw-semibold">₹ <span style="color: #9EA0A2; text-decoration:line-through;">1299</span> 999<span class="fs-4"><small> / Year</small></span></h3>
                            <ul class="px-3 py-3 subad_ul text-start">
                                <li>Unlimited Internship (Regular)</li>
                                <li>Chat with employer</li>
                                <li>Certificate</li>
                                <li>Work logbook -Approved by Universities</li>
                                <li>Unlimited access to premium Internships</li>
                                <li>Mentor connect access</li>
                                <li>Resume building session</li>
                            </ul>
                        </div>
                        <?php if (isset($login) && $login != '') {
                            // $where = array('status' => '1', 'userid' => $userid);
                            // $payment_status_details = $Candidate_model->get_data_common_row('can_personal_details', ('id,payment_package_type,payment_status,payment_expiry_date,can_profile_complete_status'), $where);
                            if ($payment_status_details->can_profile_complete_status == 1) {
                                if ($payment_status_details->payment_package_type == 2 && !empty($payment_status_details->payment_expiry_date) && $payment_status_details->payment_expiry_date > date('Y-m-d')) { ?>
<a class="btn btn-sub-outline fw-medium me-4 px-5 mb-2 py-2 align-self-center">Current Plan</a>
                                <?php  } elseif ($payment_status_details->payment_package_type == 1 && !empty($payment_status_details->payment_expiry_date) && $payment_status_details->payment_expiry_date > date('Y-m-d')) { ?>
                                    <a href="<?php echo base_url('/phonepe-candidate-payment/2'); ?>" class="btn btn-sub-outline fw-medium me-4 px-5 mb-2 py-2 align-self-center">Buy Now</a>
                                <?php } else {
                                ?>
                                    <a href="<?php echo base_url('/phonepe-candidate-payment/2'); ?>" class="btn btn-sub-outline fw-medium me-4 px-5 mb-2 py-2 align-self-center">Buy Now</a>
                                <?php }
                            } else { ?>
                                <a href="<?php echo base_url('/personal-details'); ?>" class="btn btn-sub-outline fw-medium me-4 px-5 mb-2 py-2 align-self-center">Buy Now</a>
                            <?php  }
                        } else { ?>
                            <!-- <a href="<?php echo base_url('pricing_plan_login'); ?>" class="btn btn-sub-outline fw-medium me-4 px-5 mb-2 py-2 align-self-center">Buy Now</a> -->
                            <a onclick="pricing_plan_login()" class="btn btn-sub-outline fw-medium me-4 px-5 mb-2 py-2 align-self-center">Buy Now</a>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- <?php //require_once(APPPATH . "Views/design/common/script.php"); 
            ?> -->
    <?php require_once(APPPATH . "Views/Common/script.php"); ?>
    <script>
        function pricing_plan_login() {
            swal({
                title: "You are not logged in",
                text: "Please login to access this page.",
                type: "info",
                showCancelButton: true,
                confirmButtonClass: "btn-primary",
                confirmButtonText: "Proceed",
                cancelButtonText: "Cancel",
                closeOnConfirm: false,
                closeOnCancel: false
            }, function(isConfirm) {

                if (isConfirm) {
                    window.location.href = "<?php echo base_url('pricing_plan_login'); ?>";
                } else {
                    location.reload();
                }
            });
        }
    </script>

</body>

</html>