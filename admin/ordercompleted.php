
<!doctype html>
<html lang="en-US">

<head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
    <meta name="viewport" content="width=device-width,minimum-scale=1">
    <title>Your Order Details</title>
    <meta name="description" content="Password Reset Code">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <style type="text/css">
        a:hover {
            text-decoration: underline !important;
        }


        .order-table {
            border: 1px solid #ccc;
            border-collapse: collapse;
            margin: 0;
            padding: 0;
            width: 100%;
            table-layout: fixed;
        }

            .order-table caption {
                color: #bf5fff;
                font-size: 20px;
                margin: .5em 0 .75em;
            }

            .order-table tr {
                background-color: #f8f8f8;
                border: 1px solid #ddd;
                padding: .35em;
            }

            .order-table th,
            .order-table td {
                padding: .625em;
                text-align: center;
            }

            .order-table th {
                font-size: .85em;
                letter-spacing: .1em;
                text-transform: uppercase;
            }

        @media screen and (max-width: 600px) {
            .order-table {
                border: 0;
            }

                .order-table caption {
                    font-size: 20px;
                }

                .order-table thead {
                    border: none;
                    clip: rect(0 0 0 0);
                    height: 1px;
                    margin: -1px;
                    overflow: hidden;
                    padding: 0;
                    position: absolute;
                    width: 1px;
                }

                .order-table tr {
                    border-bottom: 3px solid #ddd;
                    display: block;
                    margin-bottom: .625em;
                }

                .order-table td {
                    border-bottom: 1px solid #ddd;
                    display: block;
                    font-size: .8em;
                    text-align: right;
                }

                    .order-table td::before {
                        /*
    * aria-label has no advantage, it won't be read inside a table
    content: attr(aria-label);
    */
                        content: attr(data-label);
                        float: left;
                        font-weight: bold;
                        text-transform: uppercase;
                    }

                    .order-table td:last-child {
                        border-bottom: 0;
                    }
        }
    </style>
</head>

<body marginheight="0" topmargin="0" marginwidth="0" style="margin: 0px; background-color: #000;" leftmargin="0">
    <!-- 100% body table -->
    <table cellspacing="0" border="0" cellpadding="0" width="100%" bgcolor="#000"
           style=" font-family: 'Poppins'; font-size: 22px;">
        <tr>
            <td>
                <table style="background-color: #000; max-width:670px; margin:0 auto;" width="100%" border="0"
                       align="center" cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="height:80px;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td style="text-align:center;">
                            <a href="https://rakeshmandal.com" title="logo" target="_blank">
                                <img width="60" src="https://i.ibb.co/hL4XZp2/android-chrome-192x192.png" title="logo" alt="logo">
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td style="height:20px;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td>
                            <table width="95%" border="0" align="center" cellpadding="0" cellspacing="0"
                                   style="max-width: 670px; background: linear-gradient(180deg, #171717, #171717); border-radius: 10px; text-align: center; -webkit-box-shadow: 0 6px 18px 0 rgba(0,0,0,.06); -moz-box-shadow: 0 6px 18px 0 rgba(0,0,0,.06); box-shadow: 0 6px 18px 0 rgba(0,0,0,.06);">
                                <tr>
                                    <td style="height:40px;">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td style="padding:0 35px;">
                                        <h1 style="color: #bf5fff; font-weight: 500; margin: 0; font-size: 32px;">
                                            Order Completed
                                        </h1>

                                        <p style="font-size: 15px; text-align: left; color: #aab8c2; margin: 8px 0 0; line-height: 24px;">
                                        Order #<?=$order['id']?><br>
                                            Your package has been delivered if you're reciving this email.
                                            If not, contact support.
                                            Your tracking number is  <b><?=htmlspecialchars($order['tracking_number'], ENT_QUOTES)?></b>
                                            and the carrier is <b><?=htmlspecialchars($order['carrier'], ENT_QUOTES)?>.</b>
                                        </p>

                                        <p style="text-align: left;">
                                            <a href="http://localhost/pxh/pnblack/index.php?page=myaccount"
                                               style="background: #bf5fff; text-decoration: none !important; font-weight: 500; margin-top: 25px; color: #fff; text-transform: uppercase; font-size: 14px; padding: 10px 24px; display: inline-block; border-radius: 50px;">
                                                The website
                                            </a>
                                            <a href="javascript:void(0);"
                                               style="background: #bf5fff; text-decoration: none !important; font-weight: 500; margin-top: 25px; color: #fff; text-transform: uppercase; font-size: 14px; padding: 10px 24px; display: inline-block; border-radius: 50px;">
                                                Contact Support
                                            </a>
                                        </p>



                                        <table class="order-table">
                                            <caption>Order Summary</caption>
                                            <thead>
                                                <tr>
                                                    <th scope="col">Product</th>
                                                    <th scope="col">Price</th>
                                                    <th scope="col">Quantity</th>
                                                    <th scope="col">Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php foreach($order_items as $item): ?>
                                                <tr>
                                                    <td data-label="Product"><?=$item['name'] ? htmlspecialchars($item['name'], ENT_QUOTES) : '(Product ' . $item['item_id'] . ')'?>
                                                    <br>
                                                    <?=$item['item_options'] ? htmlspecialchars(str_replace(',', ', ', $item['item_options']), ENT_QUOTES) : '--'?></td>
                                                    <td data-label="Price"><?=currency_code?><?=number_format($item['item_price'], 2)?></td>
                                                    <td data-label="Quantity"><?=$item['item_quantity']?></td>
                                                    <td data-label="Total"><?=currency_code?><?=number_format($item['item_price']*$item['item_quantity'], 2)?></td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                        <p style="font-size:17px;text-decoration:underline; text-align: left; color: #aab8c2; margin: 8px 0 0;">
                                           <b> Subtotal = <span><?=currency_code?><?=number_format($order['payment_amount'], 2)?></span> (Includes Shipping)</b>
                                        </p>
                                        <p style="font-size:18px; text-align: left; color: #aab8c2; margin: 8px 0 0;">
                                            <b style="color: #bf5fff;">Your Details:</b><br>
                                          <?=htmlspecialchars($order['a_first_name'], ENT_QUOTES)?> <?=htmlspecialchars($order['a_last_name'], ENT_QUOTES)?>
                                          <br>
				                            <?=htmlspecialchars($order['a_address_street'], ENT_QUOTES)?><br>
                                            <?=htmlspecialchars($order['a_address_city'], ENT_QUOTES)?><br>
                                            <?=htmlspecialchars($order['a_address_state'], ENT_QUOTES)?><br>
                                            <?=htmlspecialchars($order['a_address_zip'], ENT_QUOTES)?><br>
                                            <?=htmlspecialchars($order['a_address_country'], ENT_QUOTES)?>
                                        </p>
                                        <span style="display:inline-block; vertical-align:middle; margin:29px 0 26px; border-bottom:1px solid #cecece; width:100px;"></span>
                                        <p style="color: #81dbdb; font-weight: 700; font-size: 30px; margin-bottom: 0; margin-top: 0; text-align: center; letter-spacing: 14px; ">Spread the word.</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="height:40px;">&nbsp;</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="height:20px;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td style="text-align:center;">
                            <p style="font-size:14px; color:rgba(69, 80, 86, 0.7411764705882353); line-height:18px; margin:0 0 0;">&copy; <strong>www.rakeshmandal.com</strong> </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="height:80px;">&nbsp;</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <!--/100% body table-->
</body>

</html>