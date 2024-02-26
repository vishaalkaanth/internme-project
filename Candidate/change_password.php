<!DOCTYPE html>
<html>

<?php
//print_r($_SESSION['search_status']);
require_once(APPPATH . "Views/Common/head.php");
 $session = session();
     $login=$session->get('isLoggedIn');
?>

<body class="stickyFoot <?php if (!isset($login)&& $login=='') { echo 'resTop'; } ?>">

    <?php
 
    use App\Models\Candidate_model;

     
   
    if (isset($login)&& $login!='') 
    {
        require_once(APPPATH . "Views/Common/header.php");
        require_once(APPPATH . "Views/Common/error_page.php"); 
    }else 
    {
        ?>
         <header  >
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <div class="container d-flex flex-wrap flex-sm-nowrap justify-content-center">
                    <a class="navbar-brand py-0 mb-sm-0 mb-4" href="<?= base_url(); ?>/"><img src="<?= base_url(); ?>/public/assets/img/logo_blue.svg" alt="Logo" class="img-fluid" width="200"></a>
                    <!-- <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button> -->
                    <div class="navbar-collapse" id="navbarSupportedContent">
                        <ul class="menu navbar-nav me-auto mb-lg-0 justify-content-sm-end justify-content-center align-items-center w-100" id="mainNav">
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
                    
                                        <input type="search" class="form-control rounded" placeholder="Search"  onkeypress="enterpress_search(event, this)" id="search_value"   value="<?php 
                                    //show when search with keyword
                                    $session = session();
                                    $searched_keyword=$session->get('searched_keyword');
                                    if (isset($searched_keyword))
                                     {
                                         
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
    }
   ?>
    <!----- Form ------>
    <section class="container my-4 <?php  if (!isset($login)&& $login=='') { echo 'pt-5 pt-sm-0'; } ?>">
        <div class="d-flex flex-wrap row align-items-center changePass">
            <center>
             <div class="col-12 col-md-8 col-lg-6 col-xl-4 formSec align-self-center px-4 px-md-5 pt-4">

                <h4 class="text-blue fw-semibold text-center mb-4">Change Password</h4>
                   
                <form action="<?php echo base_url('Save-Changed-Password'); ?>" method="post" autocomplete="off">
                      <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" class="csrf"/>
                  
                    <div class="input-group mb-4 mobilenumber_div">
                        <span class="input-group-text">
                            <img src="<?php echo base_url('public/assets/img/icon_password.svg'); ?>" alt="Mobile" width="14">
                        </span>
                        <input type="password" maxlength="12" autocomplete="off" class="form-control border-right-0" id="old_password" name="old_password" placeholder="Enter Your Current Password" onchange="validate_current_password()" >
                        <span class="input-group-text">
                            <i class="fa fa-eye text-blue" id="password_icon_hide_old" style="cursor: pointer;" onclick="show_password_old()" aria-hidden="true"></i>
                        </span>
                        
                    </div>
                    <font style="color:#dd4b39;"><div id="old_password_alert"></div></font>
                      <div class="input-group mb-4">
                        <span class="input-group-text">
                            <img src="<?php echo base_url('public/assets/img/icon_password.svg'); ?>" alt="Password" width="16">
                        </span>
                        <input type="password" maxlength="12" class="form-control border-right-0 pr-password" id="new_password" name="new_password" placeholder="Enter Your New Password" onkeyup="password_match()">
                        <span class="input-group-text">
                            <i class="fa fa-eye text-blue" id="password_icon_hide" style="cursor: pointer;" onclick="show_password()" aria-hidden="true"></i>
                        </span>
                    </div>
                     <font style="color:#dd4b39;"><div id="new_password_alert"></div></font>
                    <div class="input-group mb-4">
                        <span class="input-group-text">
                            <img src="<?php echo base_url('public/assets/img/icon_password.svg'); ?>" alt="Password" width="16">
                        </span>
                        <input type="password" maxlength="12" class="form-control border-right-0 " id="confirm_password" name="confirm_password" placeholder="Confirm Password" onchange="password_match()">
                        <span class="input-group-text">
                            <i class="fa fa-eye text-blue" id="password_icon_hide_confirm" style="cursor: pointer;" onclick="show_password_confirm()" aria-hidden="true"></i>
                        </span>
                    </div>
                    <font style="color:#dd4b39;"><div id="confirm_password_alert"></div></font>
                   
                    <div class="d-flex justify-content-center">
                        <input type="submit" class="btn btn-prim" id="user_login_submit" value="Submit">
                    </div>
                </form><br><br>

               
            </div>
            </center>
        </div>
    </section>
    <?php require_once(APPPATH . "Views/Common/footer.php"); ?>

    <?php
    require_once(APPPATH . "Views/Common/script.php"); ?>
<script type="text/javascript">
 //password eye control
    function show_password() {
        var x = document.getElementById("new_password");
        if (x.type === "password") {
            $('#password_icon_hide').addClass('fa-eye-slash');
            $('#password_icon_hide').removeClass('fa-eye');
            x.type = "text";
        } else {
            x.type = "password";
            $('#password_icon_hide').removeClass('fa-eye-slash');
            $('#password_icon_hide').addClass('fa-eye');
        }
        }
          function show_password_confirm() {
        var x = document.getElementById("confirm_password");
        if (x.type === "password") {
            $('#password_icon_hide_confirm').addClass('fa-eye-slash');
            $('#password_icon_hide_confirm').removeClass('fa-eye');
            x.type = "text";
        } else {
            x.type = "password";
            $('#password_icon_hide_confirm').removeClass('fa-eye-slash');
            $('#password_icon_hide_confirm').addClass('fa-eye');
        }
        }

        function show_password_old() {
        var x = document.getElementById("old_password");
        if (x.type === "password") {
            $('#password_icon_hide_old').addClass('fa-eye-slash');
            $('#password_icon_hide_old').removeClass('fa-eye');
            x.type = "text";
        } else {
            x.type = "password";
            $('#password_icon_hide_old').removeClass('fa-eye-slash');
            $('#password_icon_hide_old').addClass('fa-eye');
        }
        }
//password validation
      $(function(){
            $(".pr-password").passwordRequirements();
        });
        $(".pr-password").passwordRequirements({
            numCharacters: 8,
            useLowercase:true,
            useUppercase:true,
            useNumbers:true,
            useSpecial:true
            });

//match with confirm password
function password_match()
{
    var new_password     = $('#new_password').val();
    var confirm_password = $('#confirm_password').val();
    if (new_password!='' && confirm_password!='')
     {
        if (new_password != confirm_password)
             {
                $('#confirm_password_alert').html('Password Not Matching');
                $("#confirm_password_alert").addClass('alertMsg');
                return false;   
             }else{
                $('#confirm_password_alert').html('');
             }
     }
}
//validate when submit

 $(document).ready(function()
  {
    $("#user_login_submit").click(function() 
    {
        var new_password = $('#new_password').val();
        var confirm_password  = $('#confirm_password').val();
        var old_password = validatetext_style('old_password', 'old_password_alert', 'Current Password');
        var user_create_password = validatepwdparam_alfa_numeric('new_password', 'new_password_alert');
        var confirm_password1 = validatetext_style('confirm_password', 'confirm_password_alert', 'Confirm Password');
        if (confirm_password1==0 || old_password == 0 || user_create_password == 0) {
            return false;
        } else {
            if (new_password != confirm_password) {
               
                $('#confirm_password_alert').html("Password Not Matching");
                $("#confirm_password_alert").addClass('alertMsg');
                return false;
                
            }else 
            {
                return true;
            }
        }
    });
});            
//validate current password

function validate_current_password()
{
        var csrftokenname = "csrf_test_name=";
        var csrftokenhash = $(".csrf").val(); 
        var old_password  = $('#old_password').val(); 
         
          $.ajax({
                  type: "POST",
                  url: "<?php echo base_url('User-Current-Password-Check'); ?>",
                  data:"old_password=" + encodeURIComponent(old_password)+ "&" + csrftokenname + csrftokenhash,
                  success: function(resp){
                     
                    var splitted_data = resp.split("^");
                    $(".csrf").val(splitted_data[1]); 
                    if(splitted_data[0]=='0')
                    {
                        $('#old_password_alert').html("Invalid Current Password");
                        $("#old_password_alert").addClass('alertMsg');
                        
                    }  else 
                    {
                        $('#old_password_alert').html("");
                    }
                    },
                  error: function(e){ 
                 
                  alert('Error: ' + e.responseText);
                  return false;  

                  }
                  });
}
</script>
</body>

</html>