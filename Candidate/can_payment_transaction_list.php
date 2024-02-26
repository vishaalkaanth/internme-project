<!DOCTYPE html>
<html>

<?php
$session = session();
$userid    =    $session->get('userid');
$active_sort_my_application    =    $session->get('active_sort_my_application');
$application_offers_received    =    $session->get('application_offers_received');
// print_r($_SESSION);
// print_r($applied_internship_list);
use App\Models\Candidate_model;

$Candidate_model = new Candidate_model();
//$this->load->view('common/head'); 
require_once(APPPATH . "Views/Common/head.php");

?>

<body class="stickyFoot">

    <?php require_once(APPPATH . "Views/Common/header.php");
    require_once(APPPATH . "Views/Common/error_page.php");

    ?>
    <!----- Form ------>
    <section class="container filterable my-4">
        <div class="d-flex justify-content-between flex-wrap align-items-center mb-4">
            <?php if ($application_offers_received == '1') { ?>
                <h2 class="page_title mb-sm-0 mb-3">Offers Received</h2>
            <?php  } else { ?>
                <h2 class="page_title mb-sm-0 mb-3">My Transactions</h2>
                <!-- <a href="#" class="text-blue backBtn me-3" onclick="previous()"><i class="fa fa-long-arrow-left me-1" aria-hidden="true"></i> Back</a> -->
            <?php } ?>
        </div>


        <div class="card p-4">
            <?php if ($application_offers_received != '1') { ?>
                <!-- <div class="d-flex justify-content-between flex-wrap">
                
                <div class="col-md-4 col-lg-2 col-12 form-group mt-3">
                    
                    <select name="internship_duration" id="internship_duration" onchange="my_application_list(this.value)" class="js-states selectSearch filterby form-control align-self-end">
                        <option value="1" <?php if (isset($active_sort_my_application)) {
                                                if ($active_sort_my_application == '1') {
                                                    echo 'selected';
                                                }
                                            } ?>>All</option>
                        <option value="2" <?php if (isset($active_sort_my_application)) {
                                                if ($active_sort_my_application == '2') {
                                                    echo 'selected';
                                                }
                                            } ?>>Offer accepted</option>
                        <option value="3" <?php if (isset($active_sort_my_application)) {
                                                if ($active_sort_my_application == '3') {
                                                    echo 'selected';
                                                }
                                            } ?>>Offer declined</option>
                        <option value="4" <?php if (isset($active_sort_my_application)) {
                                                if ($active_sort_my_application == '4') {
                                                    echo 'selected';
                                                }
                                            } ?>>Dropped</option>
                        <option value="5" <?php if (isset($active_sort_my_application)) {
                                                if ($active_sort_my_application == '5') {
                                                    echo 'selected';
                                                }
                                            } ?>>Completed</option>
                        <option value="6" <?php if (isset($active_sort_my_application)) {
                                                if ($active_sort_my_application == '6') {
                                                    echo 'selected';
                                                }
                                            } ?>>Under review</option>
                        <option value="7" <?php if (isset($active_sort_my_application)) {
                                                if ($active_sort_my_application == '7') {
                                                    echo 'selected';
                                                }
                                            } ?>>Hired</option>
                        <option value="8" <?php if (isset($active_sort_my_application)) {
                                                if ($active_sort_my_application == '8') {
                                                    echo 'selected';
                                                }
                                            } ?>>Not Qualified</option>
                        <option value="9" <?php if (isset($active_sort_my_application)) {
                                                if ($active_sort_my_application == '9') {
                                                    echo 'selected';
                                                }
                                            } ?>>Ongoing</option>
                        <option value="10" <?php if (isset($active_sort_my_application)) {
                                                if ($active_sort_my_application == '10') {
                                                    echo 'selected';
                                                }
                                            } ?>>Offer expired</option>
                        <option value="11" <?php if (isset($active_sort_my_application)) {
                                                if ($active_sort_my_application == '11') {
                                                    echo 'selected';
                                                }
                                            } ?>>Under Consideration</option>

                    </select>
                </div>
            </div>-->
            <?php } ?>
            <div class="pgContent mt-2">
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="myapp" role="tabpanel" aria-labelledby="myapp-tab">
                        <div class="table-responsive">
                            <table class="table" id="example">
                                <thead>
                                    <tr class="filters">
                                        <th scope="col">S.No</th>
                                        <th scope="col">Invoice No</th>
                                        <!-- <th scope="col">Transaction ID</th> -->
                                        <!-- <th scope="col">Order ID</th> -->
                                        <th scope="col">Payment ID</th>
                                        <th scope="col">Payment Date</th>
                                        <th scope="col">Payment Amount</th>
                                        <!--<th scope="col">Refund Amount</th>
                                        <th scope="col">Refund Date</th>
                                        <th scope="col">Error Code</th>
                                        <th scope="col">Error Description</th>-->
                                        <th scope="col" class="text-center">Payment Status</th>
                                        <th scope="col" class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (isset($my_transaction_list) && !empty($my_transaction_list)) {
                                        $i = 1;
                                        foreach ($my_transaction_list as $transaction_list) {
                                    ?>
                                            <tr>
                                                <td scope="row"><?php echo $i; ?></td>
                                                <!-- <td class="overflow-anywhere"><?php echo (!empty($transaction_list->invoice_no)) ? "<a href=" . base_url() . "/candidate-payment-receipt/" . $transaction_list->invoice_no . " data-bs-toggle='tooltip' title='Download Receipt' >" . $transaction_list->invoice_no . "</a>" : "-"; ?></td> -->
                                                <td class="overflow-anywhere"><?php echo (!empty($transaction_list->invoice_no)) ? $transaction_list->invoice_no : "-"; ?></td>
                                                <!-- <td class="overflow-anywhere"><?php // echo $transaction_list->merchant_txn_id; ?></td> -->
                                                <!-- <td class="overflow-anywhere"><?php //echo $transaction_list->order_id; ?></td> -->
                                                <td><?php echo (!empty($transaction_list->payment_id)) ? $transaction_list->payment_id : "-"; ?></td>
                                                <td><?php echo (!empty($transaction_list->payment_date)) ? $transaction_list->payment_date : "-"; ?></td>
                                                <td class="text-center" ><?php echo (!empty($transaction_list->payment_amount)) ? $transaction_list->payment_amount : "-"; ?></td>
                                                <!--<td><?php echo (!empty($transaction_list->refund_amount)) ? $transaction_list->refund_amount : "-"; ?></td>
                                                    <td><?php echo (!empty($transaction_list->refund_date)) ? $transaction_list->refund_date : "-"; ?></td>
                                                    <td><?php echo (!empty($transaction_list->error_code)) ? $transaction_list->error_code : "-"; ?></td>
                                                    <td><?php echo (!empty($transaction_list->error_description)) ? $transaction_list->error_description : "-"; ?></td>-->
                                                <td class="text-center">
                                                    <?php
                                                    if (!empty($transaction_list->payment_status)) {
                                                        if ($transaction_list->payment_status == "initiated" || $transaction_list->payment_status == "PAYMENT_INITIATED") {
                                                    ?>
                                                            <span class="tooltip_hide" data-bs-toggle="tooltip" data-bs-placement="top" title="Payment Initiated"> <?php echo "<span class='badge badge-gray fw-normal me-2'>Initiated</span>"; ?></span>
                                                        <?php
                                                        } else if ($transaction_list->payment_status == "captured" || $transaction_list->payment_status == "PAYMENT_SUCCESS") {
                                                        ?>
                                                            <span class="tooltip_hide" data-bs-toggle="tooltip" data-bs-placement="top" title="Payment Completed"> <?php echo "<span class='badge badge-completed fw-normal'>Completed</span>"; ?></span>
                                                        <?php
                                                        } elseif ($transaction_list->payment_status == "failed" || $transaction_list->payment_status == "PAYMENT_ERROR") { ?>
                                                            <span class="tooltip_hide" data-bs-toggle="tooltip" data-bs-placement="top" title="Payment Failed">
                                                                <?php echo "<span class='badge badge-red fw-normal'>Failed</span>"; ?></span><?php
                                                                                                                                            } elseif ($transaction_list->payment_status == "created") { ?>
                                                            <span class="badge badge-gray fw-normal me-2">Created</span>
                                                        <?php
                                                                                                                                            } elseif ($transaction_list->payment_status == "refunded" || $transaction_list->payment_status == "PAYMENT_PENDING") { ?>
                                                            <span class="me-3 badge badge-blue fw-normal " style="color: #fff;background-color: #528ff0;">Refunded </span><?php
                                                                                                                                                                        }
                                                                                                                                                                    } else {
                                                                                                                                                                        echo "-";
                                                                                                                                                                    }
                                                                                                                                                                            ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php if ((!empty($transaction_list->invoice_no))) { ?>
                                                        <a href="<?= base_url(); ?>/candidate-payment-receipt/<?php echo $transaction_list->invoice_no; ?>" data-bs-toggle="tooltip" title="Download Receipt" class="">
                                                            <img src="<?= base_url(); ?>/public/assets/img/down_pdf.svg" alt="report" width="18">
                                                        </a>
                                                    <?php } else {
                                                        echo "-";
                                                    }  ?>
                                                </td>
                                            </tr>
                                    <?php $i++;
                                        }
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
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

        $('[data-bs-toggle="tooltip"]').tooltip({
            trigger: 'hover'
        })
        $(document).mousedown(function(e) {
            $('.tooltip_hide').click(function() {
                $('[data-bs-toggle="tooltip"]').tooltip('hide');
            });
        });

        function previous() {
            window.history.go(-1);
        }
    </script>
</body>

</html>