<!DOCTYPE html>
<html>

<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css"> -->
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css"> -->
<style>
    @font-face {
        font-family: 'Roboto';
        font-style: normal;
        src: local('Roboto Regular'), local('Roboto-Regular'), url(http://themes.googleusercontent.com/static/fonts/roboto/v9/zN7GBFwfMP4uA6AR0HCoLQ.ttf) format('truetype');
    }

    * {
        font-family: 'Roboto' !important;
        box-sizing: border-box;
    }

    body {
        margin: 10px;
        padding-top: 0px;
        overflow-x: hidden;
    }


    /******************* From CSS ***********/
    :root {
        --bs-body-font-size: 0.9rem;
    }


    @media (min-width: 768px) {}
</style>

<body>
    <table width="90%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff" align="center">
        <tbody>
            <tr>
                <td>
                    <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                        <tbody>
                            <tr>
                                <td valign="middle" align="left">
                                    <b>
                                        PAYMENT RECEIPT
                                    </b>
                                </td>
                                <td>
                                    <img src="https://internme.app/public/assets/img/logo_blue.svg" width="200" style="    float: right;" />
                                </td>
                            </tr>



                        </tbody>
                    </table>
                    <br>


                    <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#000000" align="center">
                        <tbody>
                            <tr bgcolor="#000000">
                                <td>
                                    <table width="100%" border="0" cellspacing="3" cellpadding="0" align="center" bgcolor="#000000">
                                        <tbody>
                                            <tr bgcolor="#ffffff">
                                                <td valign="top" colspan="2">
                                                    <table width="100%" border="0" cellspacing="0" cellpadding="5">
                                                        <tbody>
                                                            <tr bgcolor="#ffffff">
                                                                <td>
                                                                    <b class="sans">
                                                                        <center>
                                                                            Payment on <?php echo $invoice_details->payment_date; ?>
                                                                        </center>
                                                                    </b>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td bgcolor="#ffffff" valign="top" colspan="2">
                                                    <table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                        <tbody>
                                                            <tr valign="top">
                                                                <td width="100%">
                                                                    <table border="0" cellspacing="0" cellpadding="2" align="right">
                                                                        <tbody>
                                                                            <tr valign="top">
                                                                                <td align="right">
                                                                                    &nbsp;
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                    <table border="0" cellspacing="2" cellpadding="0" width="100%">
                                                                        <tbody>
                                                                            <tr valign="top">
                                                                                <td valign="top" align="left">
                                                                                    <b>
                                                                                        Invoice No :
                                                                                    </b>
                                                                                    <?php echo $invoice_details->invoice_no; ?><br>

                                                                                    <b>
                                                                                        Order Placed :
                                                                                    </b>
                                                                                    <?php echo date("Y-m-d", strtotime($invoice_details->payment_date)); ?><br>
                                                                                    <b>
                                                                                        Valid Till :
                                                                                    </b>
                                                                                    <?php echo date("Y-m-d", strtotime($personal_details->payment_expiry_date)); ?><br>
                                                                                    <b>Order Number :</b>
                                                                                    <?php echo $invoice_details->order_id; ?><br>
                                                                                    <b>Payment Detail :
                                                                                        <span style="text-decoration: inherit; white-space: nowrap;"><span>Rs. </span><?php echo $invoice_details->payment_amount; ?></span></b>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                    <br>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td bgcolor="#ffffff" valign="top">
                                                    <table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                        <tbody>
                                                            <tr>
                                                                <td>
                                                                    <b>
                                                                        Details:
                                                                    </b>
                                                                    <br>
                                                                    <div>
                                                                        <ul>
                                                                            <li><?php echo ucfirst($personal_details->profile_full_name) ?></li>
                                                                            <li><?php echo (!empty($personal_details->g_location_name)) ? ucfirst($personal_details->g_location_name) : "-"; ?></li>
                                                                            <li><?php echo (!empty($personal_details->location_state)) ? ucfirst($personal_details->location_state) : "-"; ?></li>
                                                                            <li>India</li>
                                                                            <li>Email: <?php echo (!empty($personal_details->profile_email)) ? $personal_details->profile_email : "-"; ?></li>
                                                                            <li>Mobile: <?php echo (!empty($personal_details->profile_phone_number)) ? $personal_details->profile_phone_number : "-"; ?></li>
                                                                        </ul>
                                                                    </div>
                                                                </td>
                                                                <td align="right">
                                                                    <table border="0" cellpadding="0" cellspacing="1">
                                                                    </table>

                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                                <td bgcolor="#ffffff" valign="top">
                                                    <table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                        <tbody>
                                                            <tr>
                                                                <td>
                                                                    <b>
                                                                        Address:
                                                                    </b>
                                                                    <br>
                                                                    <div>
                                                                        <ul>
                                                                            <li><?php echo ucfirst($personal_details->profile_full_name) ?></li>
                                                                            <li><?php echo (!empty($personal_details->g_location_name)) ? ucfirst($personal_details->g_location_name) : "-"; ?></li>
                                                                            <li><?php echo (!empty($personal_details->location_state)) ? ucfirst($personal_details->location_state) : "-"; ?></li>
                                                                            <li>India</li>
                                                                            <li>Email: <?php echo (!empty($personal_details->profile_email)) ? $personal_details->profile_email : "-"; ?></li>
                                                                            <li>Mobile: <?php echo (!empty($personal_details->profile_phone_number)) ? $personal_details->profile_phone_number : "-"; ?></li>
                                                                        </ul>
                                                                    </div>
                                                                </td>
                                                                <td align="right">
                                                                    <table border="0" cellpadding="0" cellspacing="1">
                                                                    </table>

                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>

                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <br>

                    <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#000000" align="center">
                        <tbody>
                            <tr bgcolor="#000000">
                                <td>
                                    <table width="100%" border="0" cellspacing="3" cellpadding="0" align="center" bgcolor="#000000">
                                        <tbody>
                                            <tr bgcolor="#ffffff">
                                                <td valign="top" colspan="2">
                                                    <table width="100%" border="0" cellspacing="0" cellpadding="5">
                                                        <tbody>
                                                            <tr bgcolor="#ffffff">
                                                                <td>
                                                                    <b class="sans">
                                                                        <center>Payment information</center>
                                                                    </b>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td bgcolor="#ffffff" valign="top" colspan="2">
                                                    <table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                        <tbody>
                                                            <tr valign="top">
                                                                <td width="100%">
                                                                    <table border="0" cellspacing="0" cellpadding="2" align="right">
                                                                        <tbody>
                                                                            <tr valign="top">
                                                                                <td align="right">
                                                                                    <table border="0" cellpadding="0" cellspacing="1">
                                                                                        <?php
                                                                                        $amountIncludingGST = $invoice_details->payment_amount; // Amount including GST
                                                                                        $percentage = 18; // GST percentage

                                                                                        // Calculate the GST amount
                                                                                        $gstAmount = ($amountIncludingGST * $percentage) / (100 + $percentage);

                                                                                        // Calculate the amount excluding GST
                                                                                        $amountExcludingGST = $amountIncludingGST - $gstAmount;
                                                                                        ?>
                                                                                        <tbody>
                                                                                            <tr valign="top">
                                                                                                <td nowrap="nowrap" align="right">Package Amount :</td>
                                                                                                <td nowrap="nowrap" align="right"><span style="text-decoration: inherit; white-space: nowrap;"><span class="currencyINR">&nbsp;&nbsp;</span><span class="currencyINRFallback" style="display:none">Rs. </span><?php echo number_format($amountExcludingGST, 2); ?></span></td>
                                                                                            </tr>

                                                                                            <tr valign="top">
                                                                                                <td nowrap="nowrap" align="right">CGST:</td>
                                                                                                <td nowrap="nowrap" align="right"><span style="text-decoration: inherit; white-space: nowrap;"><span class="currencyINR">&nbsp;&nbsp;</span><span class="currencyINRFallback" style="display:none">Rs. </span><?php echo  number_format(($gstAmount / 2), 2); ?></span></td>
                                                                                            </tr>
                                                                                            <tr valign="top">
                                                                                                <td nowrap="nowrap" align="right">SGST:</td>
                                                                                                <td nowrap="nowrap" align="right"><span style="text-decoration: inherit; white-space: nowrap;"><span class="currencyINR">&nbsp;&nbsp;</span><span class="currencyINRFallback" style="display:none">Rs. </span><?php echo  number_format(($gstAmount / 2), 2); ?></span></td>
                                                                                            </tr>

                                                                                            <!-- <tr valign="top">
                                                                                                <td nowrap="nowrap" align="right">&nbsp;</td>
                                                                                                <td nowrap="nowrap" align="right">-----</td>
                                                                                            </tr>

                                                                                            <tr valign="top">
                                                                                                <td nowrap="nowrap" align="right">Total:</td>
                                                                                                <td nowrap="nowrap" align="right"><span style="text-decoration: inherit; white-space: nowrap;"><span class="currencyINR">&nbsp;&nbsp;</span><span class="currencyINRFallback" style="display:none">Rs. </span><?php echo $invoice_details->payment_amount; ?>.00</span></td>
                                                                                            </tr> -->

                                                                                            <tr valign="top">
                                                                                                <td nowrap="nowrap" align="right">&nbsp;</td>
                                                                                                <td nowrap="nowrap" align="right">-----------</td>
                                                                                            </tr> 

                                                                                            <tr valign="top">
                                                                                                <td nowrap="nowrap" align="right"><b>Grand Total:</b></td>
                                                                                                <td nowrap="nowrap" align="right"><b><span style="text-decoration: inherit; white-space: nowrap;"><span class="currencyINR">&nbsp;&nbsp;</span><span class="currencyINRFallback" style="display:none">Rs. </span><?php echo $invoice_details->payment_amount; ?>.00</span></b></td>
                                                                                            </tr>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>

                                                                    <b>Payment Method: </b>
                                                                    <br>
                                                                    <div>
                                                                        <ul>
                                                                            <li><?php echo $invoice_details->payment_method; ?></li>
                                                                            <?php if (!empty($invoice_details->payment_wallet)) { ?>
                                                                                <li><?php echo $invoice_details->payment_wallet; ?></li>
                                                                            <?php } ?>
                                                                        </ul>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <center>
        <!-- <p><b>Please note:</b> this is not a GST invoice.</p> -->
    </center>
</body>

</html>