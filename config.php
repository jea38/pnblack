
<?php
// Database hostname, don't change this unless your hostname is different
define('db_host','pnblack-server.mysql.database.azure.com:3306');
// Database username
define('db_user','npjqjzlquh');
// Database password
define('db_pass','mk9e22L4PcfzXV$U');
// Database name
define('db_name','pnblack-database');
// This will change the title on the website
define('site_name','PnBlack');
// Currency code, default is USD, you can view the list here: http://cactus.io/resources/toolbox/html-currency-symbol-codes
define('currency_code','&dollar;');
// Default payment status
define('default_payment_status','Pending');
// Account required for checkout?
define('account_required',false);
// The from email that will appear on the customer's order details email
define('mail_from','noreply@pnblack.com');
// Send mail to the customers, etc?
define('mail_enabled',true);
// Your email
define('email','admin@pnblack.com');
// Receive email notifications?
define('email_notifications',true);
// Rewrite URL?
define('rewrite_url',true);

/* Pay on Delivery */
define('pay_on_delivery_enabled',false);

/* PayPal */
// Accept payments with PayPal?
define('paypal_enabled',false);
// Your business email account, this is where you'll receive the money
define('paypal_email','payments@yourwebsite.com');
// If the test mode is set to true it will use the PayPal sandbox website, which is used for testing purposes.
// Read more about PayPal sandbox here: https://developer.paypal.com/developer/accounts/
// Set this to false when you're ready to start accepting payments on your business account
define('paypal_testmode',true);
// Currency to use with PayPal, default is USD
define('paypal_currency','USD');
// PayPal IPN url, this should point to the IPN file located in the "ipn" directory
define('paypal_ipn_url','https://pnblack.azurewebsites.net/ipn/paypal.php');
// PayPal cancel URl, the page the customer returns to when they cancel the payment
define('paypal_cancel_url','https://pnblack.azurewebsites.net/cart');
// PayPal return URL, the page the customer returns to after the payment has been made:
define('paypal_return_url','https://pnblack.azurewebsites.net/placeorder');

/* Stripe */
// Accept payments with Stripe?
define('stripe_enabled',true);
// Stripe Secret API Key
define('stripe_secret_key','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');
// Stripe Publishable API Key
define('stripe_publish_key','pk_live_51MX41kLXJjQ70SJcieNlYlqFpNVoSvYwNS5OJI8ZBtBXDzIBX9N0jS1s1vb7eiFQxzcIp7YJZX3lSyJL1ZBBeylE00pvfnTnKN');
// Stripe currency
define('stripe_currency','USD');
// Stripe IPN url, this should point to the IPN file located in the "ipn" directory
define('stripe_ipn_url','https://pnblack.azurewebsites.net/ipn/stripe.php');
// Stripe cancel URl, the page the customer returns to when they cancel the payment
define('stripe_cancel_url','https://pnblack.azurewebsites.net/cart');
// Stripe return URL, the page the customer returns to after the payment has been made
define('stripe_return_url','https://pnblack.azurewebsites.net/placeorder');
// Stripe webhook secret, this is used to verify the webhook request
define('stripe_webhook_secret','whsec_5MD3OxwjGCrm0uACFOnDdSBi8TWLLvC5');

/* Coinbase */
// Create a new webhook endpoint in the coinbase commerce dashboard and add the full url to the IPN file along with the key parameter
// Webhook endpoint URL example: https://yourwebsite.com/pnblack/ipn/coinbase.php?key=SAME_AS_COINBASE_SECRET
// Accept payments with coinbase?
define('coinbase_enabled',true);
// Coinbase API Key
define('coinbase_key','1c7a03e8-a73d-4bf6-8e19-c7f4fcec1692');
// Coinbase Secret
define('coinbase_secret','d1d443c2-008f-4f96-9be9-0cce22282315');
// Coinbase currency
define('coinbase_currency','USD');
// Coinbase cancel URl, the page the customer returns to when they cancel the payment
define('coinbase_cancel_url','https://pnblack.azurewebsites.net/cart');
// Coinbase return URL, the page the customer returns to after the payment has been made
define('coinbase_return_url','https://pnblack.azurewebsites.net/placeorder');
?>