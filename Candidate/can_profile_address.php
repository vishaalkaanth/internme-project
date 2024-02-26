<!DOCTYPE html>
<html>

<?php
//$this->load->view('common/head'); 
require_once(APPPATH . "Views/Common/head.php");
?>

<body class="">

    <?php require_once(APPPATH . "Views/Common/header.php");
    $session = session();
    $userid    =    $session->get('userid');
    ?>

    <!----- Form ------>
    <?php //print_r($address_details);
    ?>
    <section class="empProfile">
        <div class="d-flex flex-wrap">
            <?php require_once(APPPATH . "Views/Common/profile_side.php"); ?>
            <div class="col-12 col-lg-9 profileRt d-flex justify-content-center p-lg-5 py-5 px-4">
                 <!----- start Session Alert ------>
                    <?php require_once(APPPATH . "Views/Common/error_page.php"); ?>
                <!----- End Session Alert ------>
                <div class="col-12 col-lg-10 align-self-start">
                <form action="<?= base_url(); ?>/update_can_address_details" method="post" accept-charset="utf-8" class="" enctype="multipart/form-data">
                    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" class="csrf" />
                    <input type="hidden" id="userid" name="userid" value="<?php if (isset($userid)) {
                                                                                echo $userid;
                                                                            } ?>">
                   <h2 class="fs-title text-blue fw-medium text-center mb-5">Permanent Address</h2>
                        <div class="card p-4">
                            
                            <div class="d-flex flex-wrap row">
                                <div class="col-md-6 form-group">
                                    <label for="" class="form-label">Address Line 1</label>
                                    <div class="input-group mb-4">
                                        <span class="input-group-text fillBg border-0">
                                            <img src="<?= base_url(); ?>/public/assets/img/icon_address.svg" alt="Name" width="14">
                                        </span>
                                        <input type="text" id="add_permanent_address_line1" name="add_permanent_address_line1" value="<?php if (isset($address_details->permanent_address_line1)) { echo $address_details->permanent_address_line1;  } ?>" class="form-control filledBox border-0 py-2 f-14" placeholder="Enter address line 1">
                                    </div>
                                    <font style="color:#dd4b39;">
                                        <div id="add_permanent_address_line1_alert"></div>
                                    </font>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="" class="form-label">Address Line 2 </label>
                                    <div class="input-group mb-4">
                                        <span class="input-group-text fillBg border-0">
                                            <img src="<?= base_url(); ?>/public/assets/img/icon_address.svg" alt="Name" width="14">
                                        </span>
                                        <input type="text" id="add_permanent_address_line2" name="add_permanent_address_line2" value="<?php if (isset($address_details->permanent_address_line2)) {echo $address_details->permanent_address_line2;  } ?>" class="form-control filledBox border-0 py-2 f-14" placeholder="Enter address line 2">
                                    </div>
                                    <font style="color:#dd4b39;">
                                        <div id="add_permanent_address_line2_alert"></div>
                                    </font>
                                </div>
                                <div class="col-md-4 form-group selectField">
                                    <label for="" class="form-label">State</label>
                                    <div class="input-group mb-4">

                                        <select name="add_permanent_address_state" id="add_permanent_address_state" class="form-control filledBox border-0 f-14" onchange="get_state_by_district();">
                                            <option value="">Select state</option>
                                            <?php foreach ($state as $st) { ?>
                                                <option value="<?php echo $st->id; ?>" <?php if (isset($address_details->permanent_state)) {
                                                                                            if ($address_details->permanent_state == $st->id) {
                                                                                                echo 'selected';
                                                                                            }
                                                                                        } ?>><?php echo $st->name; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <font style="color:#dd4b39;">
                                        <div id="add_permanent_address_state_alert"></div>
                                    </font>
                                </div>
                                <div class="col-md-4 form-group selectField">
                                    <label for="" class="form-label">District</label>
                                    <div class="input-group mb-4">

                                        <select name="add_permanent_address_district" id="add_permanent_address_district" class="form-control filledBox border-0 f-14">
                                            <option value="">Select District</option>
                                            <?php if (!empty($get_district)) {
                                                foreach ($get_district as $dt) { ?>
                                                    <option value="<?php echo $dt->dist_id; ?>" <?php if (isset($address_details->permanent_district)) {
                                                                                                    if ($address_details->permanent_district == $dt->dist_id) {
                                                                                                        echo 'selected';
                                                                                                    }
                                                                                                } ?>><?php echo $dt->dist_name; ?></option>
                                            <?php }
                                            } ?>
                                        </select>
                                    </div>
                                    <font style="color:#dd4b39;">
                                        <div id="add_permanent_address_district_alert"></div>
                                    </font>
                                </div>
                                <div class="col-md-4 form-group">
                                    <label for="" class="form-label">Pincode</label>
                                    <div class="input-group mb-4">
                                        <span class="input-group-text fillBg border-0">
                                            <img src="<?= base_url(); ?>/public/assets/img/icon_pincode.svg" alt="Pincode" width="14">
                                        </span>
                                        <input type="text" maxlength="6" id="add_permanent_address_pincode" name="add_permanent_address_pincode" value="<?php if (isset($address_details->permanent_pincode)) {
                                                                                                                                                            echo $address_details->permanent_pincode;
                                                                                                                                                        } ?>" class="form-control filledBox border-0 py-2 f-14" placeholder="Enter pincode">
                                    </div>
                                    <font style="color:#dd4b39;">
                                        <div id="add_permanent_address_pincode_alert"></div>
                                    </font>
                                </div>
                            </div>
                        </div>
                            <h2 class="fs-title text-blue fw-medium text-center my-5">Communication Address</h2>
                            <div class="card p-4">
                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" name="add_same_as_permanent" id="add_same_as_permanent" value="<?php if (isset($address_details->communication_address_same_permanent_address)) {
                                                                                                                                                    echo $address_details->communication_address_same_permanent_address;
                                                                                                                                                } ?>" <?php if (isset($address_details->communication_address_same_permanent_address)) {
                                                                                                                                                                                                                                                                                                            if ($address_details->communication_address_same_permanent_address == 1) {
                                                                                                                                                                                                                                                                                                                echo 'checked';
                                                                                                                                                                                                                                                                                                            }
                                                                                                                                                                                                                                                                                                        } ?>>
                                <label class="form-check-label f-14" for="add_same_as_permanent">Same as permanent address</label>
                            </div>
                            <div class="d-flex flex-wrap row">
                                <div class="col-md-6 form-group">
                                    <label for="" class="form-label">Address Line 1</label>
                                    <div class="input-group mb-4">
                                        <span class="input-group-text fillBg border-0">
                                            <img src="<?= base_url(); ?>/public/assets/img/icon_address.svg" alt="Name" width="14">
                                        </span>
                                        <input type="text" id="add_communication_address_line1" name="add_communication_address_line1" value="<?php if (isset($address_details->communication_address_line1)) {
                                                                                                                                                    echo $address_details->communication_address_line1;
                                                                                                                                                } ?>" class="form-control filledBox border-0 py-2 f-14" placeholder="Enter address line 1">
                                    </div>
                                    <font style="color:#dd4b39;">
                                        <div id="add_communication_address_line1_alert"></div>
                                    </font>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="" class="form-label">Address Line 2 </label>
                                    <div class="input-group mb-4">
                                        <span class="input-group-text fillBg border-0">
                                            <img src="<?= base_url(); ?>/public/assets/img/icon_address.svg" alt="Name" width="14">
                                        </span>
                                        <input type="text" id="add_communication_address_line2" name="add_communication_address_line2" value="<?php if (isset($address_details->communication_address_line2)) {
                                                                                                                                                    echo $address_details->communication_address_line2;
                                                                                                                                                } ?>" class="form-control filledBox border-0 py-2 f-14" placeholder="Enter address line 2">
                                    </div>
                                    <font style="color:#dd4b39;">
                                        <div id="add_communication_address_line2_alert"></div>
                                    </font>
                                </div>
                                <div class="col-md-4 form-group selectField">
                                    <label for="" class="form-label">State</label>
                                    <div class="input-group mb-4">
                                        <select name="add_communication_address_state" id="add_communication_address_state" class="form-control filledBox border-0 f-14" onchange="get_state_by_district_com();">
                                            <option value="">Select state</option>
                                            <?php foreach ($state as $st) { ?>
                                                <option value="<?php echo $st->id; ?>" <?php if (isset($address_details->communication_state)) {
                                                                                            if ($address_details->communication_state == $st->id) {
                                                                                                echo 'selected';
                                                                                            }
                                                                                        } ?>><?php echo $st->name; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <font style="color:#dd4b39;">
                                        <div id="add_communication_address_state_alert"></div>
                                    </font>
                                </div>
                                <div class="col-md-4 form-group selectField">
                                    <label for="" class="form-label">District</label>
                                    <div class="input-group mb-4">
                                        <select name="add_communication_address_district" id="add_communication_address_district" class="form-control filledBox border-0 f-14">
                                            <option value="">Select District</option>
                                            <?php if (!empty($get_district)) {
                                                foreach ($get_district as $dt) { ?>
                                                    <option value="<?php echo $dt->dist_id; ?>" <?php if (isset($address_details->communication_district)) {
                                                                                                    if ($address_details->communication_district == $dt->dist_id) {
                                                                                                        echo 'selected';
                                                                                                    }
                                                                                                } ?>><?php echo $dt->dist_name; ?></option>
                                            <?php }
                                            } ?>
                                        </select>
                                    </div>
                                    <font style="color:#dd4b39;">
                                        <div id="add_communication_address_district_alert"></div>
                                    </font>
                                </div>
                                <div class="col-md-4 form-group">
                                    <label for="" class="form-label">Pincode</label>
                                    <div class="input-group mb-4">
                                        <span class="input-group-text fillBg border-0">
                                            <img src="<?= base_url(); ?>/public/assets/img/icon_pincode.svg" alt="Pincode" width="14">
                                        </span>
                                        <input type="text" maxlength="6" id="add_communication_address_pincode" name="add_communication_address_pincode" value="<?php if (isset($address_details->communication_pincode)) {
                                                                                                                                                                    echo $address_details->communication_pincode;
                                                                                                                                                                } ?>" class="form-control filledBox border-0 py-2 f-14" placeholder="Enter pincode">
                                    </div>
                                    <font style="color:#dd4b39;">
                                        <div id="add_communication_address_pincode_alert"></div>
                                    </font>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between flex-wrap mt-4">
                            <a href="<?= base_url(); ?>/education-details" class="btn btn-prev me-2">Previous</a>
                            <div class=""><input type="submit" class="btn btn-prim float-end" id="can_address_submit" value="Next" /></div>
                        </div>
                        </form>
                    </div>
                
            </div>
        </div>
    </section>


    <?php require_once(APPPATH . "Views/Common/script.php"); ?>
    <script>
        $('#add_same_as_permanent').click(function() {
            if ($('#add_same_as_permanent').prop('checked') == true) {

                var add_permanent_address_line1 = $("#add_permanent_address_line1").val();
                var add_permanent_address_line2 = $("#add_permanent_address_line2").val();
                var add_permanent_address_state = $("#add_permanent_address_state").val();
                var add_permanent_address_district = $("#add_permanent_address_district").val();
                var add_permanent_address_pincode = $("#add_permanent_address_pincode").val();

                $("#add_communication_address_line1").val(add_permanent_address_line1);
                $("#add_communication_address_line2").val(add_permanent_address_line2);
                $("#add_communication_address_state").val(add_permanent_address_state);
                $("#add_communication_address_district").val(add_permanent_address_district);
                $("#add_communication_address_pincode").val(add_permanent_address_pincode);

                $('#add_same_as_permanent').val('1');
            } else {
                $("#add_communication_address_line1").val('');
                $("#add_communication_address_line2").val('');
                $("#add_communication_address_state").val('');
                $("#add_communication_address_district").val('');
                $("#add_communication_address_pincode").val('');

                $('#add_same_as_permanent').val('0');
            }
        });

        function get_state_by_district() {

            var csrftokenname = "csrf_test_name=";
            var csrftokenhash = $(".csrf").val();
            var state_id = $("#add_permanent_address_state").val();
            $.ajax({
                type: "POST",
                url: "<?php echo base_url('get_state_by_district_can'); ?>",
                data: "&state_id=" + encodeURIComponent(state_id) + "&" + csrftokenname + csrftokenhash,
                success: function(resp) {
                    var splitted_data = resp.split("^");
                    $(".csrf").val(splitted_data[1]);
                    // alert(resp);   
                    document.getElementById("add_permanent_address_district").innerHTML = splitted_data[0];
                    return true;

                },

            });
        }

        function get_state_by_district_com() {
            var csrftokenname = "csrf_test_name=";
            var csrftokenhash = $(".csrf").val();
            var state_id = $("#add_communication_address_state").val();
            $.ajax({
                type: "POST",
                url: "<?php echo base_url('get_state_by_district_can_com'); ?>",
                data: "&state_id=" + encodeURIComponent(state_id) + "&" + csrftokenname + csrftokenhash,
                success: function(resp) {
                    // alert(resp);  
                    var splitted_data = resp.split("^");
                    $(".csrf").val(splitted_data[1]);
                    document.getElementById("add_communication_address_district").innerHTML = splitted_data[0];
                    return true;

                },

            });
        }


        $(document).ready(function() {
            $("#add_communication_address_line1").keyup(function() {
                return validatetext_style('add_communication_address_line1', 'add_communication_address_line1_alert', 'Address Line 1');
            });
            $("#add_communication_address_district").change(function() {
                return validatetext_style('add_communication_address_district', 'add_communication_address_district_alert', 'District');
            });
            $("#add_communication_address_state").change(function() {
                return validatetext_style('add_communication_address_state', 'add_communication_address_state_alert', 'State');
            });
            $("#add_communication_address_pincode").keyup(function() {
                return validpincode_style('add_communication_address_pincode', 'add_communication_address_pincode_alert', 'Pincode');
            });

            $("#add_permanent_address_line1").keyup(function() {
                return validatetext_style('add_permanent_address_line1', 'add_permanent_address_line1_alert', 'Address Line 1');
            });
            $("#add_permanent_address_district").change(function() {
                return validatetext_style('add_permanent_address_district', 'add_permanent_address_district_alert', 'District');
            });
            $("#add_permanent_address_state").change(function() {
                return validatetext_style('add_permanent_address_state', 'add_permanent_address_state_alert', 'State');
            });
            $("#add_permanent_address_pincode").keyup(function() {
                return validpincode_style('add_permanent_address_pincode', 'add_permanent_address_pincode_alert', 'Pincode');
            });
        });



        $(document).ready(function() {

            $("#can_address_submit").click(function() {

                var add_communication_address_pincode = validpincode_style('add_communication_address_pincode', 'add_communication_address_pincode_alert', 'Pincode');
                var add_communication_address_district = validatetext_style('add_communication_address_district', 'add_communication_address_district_alert', 'District');
                var add_communication_address_state = validatetext_style('add_communication_address_state', 'add_communication_address_state_alert', 'State');
                var add_communication_address_line1 = validatetext_style('add_communication_address_line1', 'add_communication_address_line1_alert', 'Address Line 1');


                var add_permanent_address_pincode = validpincode_style('add_permanent_address_pincode', 'add_permanent_address_pincode_alert', 'Pincode');
                var add_permanent_address_district = validatetext_style('add_permanent_address_district', 'add_permanent_address_district_alert', 'District');
                var add_permanent_address_state = validatetext_style('add_permanent_address_state', 'add_permanent_address_state_alert', 'State');
                var add_permanent_address_line1 = validatetext_style('add_permanent_address_line1', 'add_permanent_address_line1_alert', 'Address Line 1');


                if (add_permanent_address_line1 == 0 || add_permanent_address_state == 0 || add_permanent_address_district == 0 || add_permanent_address_pincode == 0 || add_communication_address_line1 == 0 || add_communication_address_state == 0 || add_communication_address_district == 0 || add_communication_address_pincode == 0) {
                    return false;
                }
            });
        });
    </script>
</body>

</html>