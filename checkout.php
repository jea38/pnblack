<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
?>

<?php
// Prevent direct access to file
defined('pnblack') or exit;
// Default values for the input form elements
$account = [
    'first_name' => '',
    'last_name' => '',
    'address_street' => '',
    'address_city' => '',
    'address_state' => '',
    'address_zip' => '',
    'address_country' => 'United States',
    'role' => 'Member'
];
// Error array, output errors on the form
$errors = [];
// Redirect the user if the shopping cart is empty
if (empty($_SESSION['cart'])) {
    header('Location: ' . url('index.php?page=cart'));
    exit;
}
// Check if user is logged in
if (isset($_SESSION['account_loggedin'])) {
    $stmt = $pdo->prepare('SELECT * FROM accounts WHERE id = ?');
    $stmt->execute([ $_SESSION['account_id'] ]);
    // Fetch the account from the database and return the result as an Array
    $account = $stmt->fetch(PDO::FETCH_ASSOC);
}
// Update discount code
if (isset($_POST['discount_code']) && !empty($_POST['discount_code'])) {
    $_SESSION['discount'] = $_POST['discount_code'];
} else if (isset($_POST['discount_code']) && empty($_POST['discount_code']) && isset($_SESSION['discount'])) {
    unset($_SESSION['discount']);
}
// Variables
$products_in_cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$subtotal = 0.00;
$shippingtotal = 0.00;
$discounttotal = 0.00;
$taxtotal = 0.00;
$weighttotal = 0;
$selected_country = isset($_POST['address_country']) ? $_POST['address_country'] : $account['address_country'];
$selected_shipping_method = isset($_POST['shipping_method']) ? $_POST['shipping_method'] : null;
$selected_shipping_method_name = '';
$shipping_methods_available = [];
// If there are products in cart
if ($products_in_cart) {
    // There are products in the cart so we need to select those products from the database
    // Products in cart array to question mark string array, we need the SQL statement to include: IN (?,?,?,...etc)
    $array_to_question_marks = implode(',', array_fill(0, count($products_in_cart), '?'));
    $stmt = $pdo->prepare('SELECT p.*, (SELECT m.full_path FROM products_media pm JOIN media m ON m.id = pm.media_id WHERE pm.product_id = p.id ORDER BY pm.position ASC LIMIT 1) AS img, (SELECT GROUP_CONCAT(pc.category_id) FROM products_categories pc WHERE pc.product_id = p.id) AS categories FROM products p WHERE p.id IN (' . $array_to_question_marks . ')');
    // We use the array_column to retrieve only the id's of the products
    $stmt->execute(array_column($products_in_cart, 'id'));
    // Fetch the products from the database and return the result as an Array
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // Retrieve the discount code
    if (isset($_SESSION['discount'])) {
        $stmt = $pdo->prepare('SELECT * FROM discounts WHERE discount_code = ?');
        $stmt->execute([ $_SESSION['discount'] ]);
        $discount = $stmt->fetch(PDO::FETCH_ASSOC);
    }
    // Get tax
    $stmt = $pdo->prepare('SELECT * FROM taxes WHERE country = ?');
    $stmt->execute([ isset($_POST['address_country']) ? $_POST['address_country'] : $account['address_country'] ]);
    $tax = $stmt->fetch(PDO::FETCH_ASSOC);
    $tax_rate = $tax ? $tax['rate'] : 0.00;
    // Get the current date
    $current_date = strtotime((new DateTime())->format('Y-m-d H:i:s'));
    // Retrieve shipping methods
    $stmt = $pdo->query('SELECT * FROM shipping');
    $shipping_methods = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // Iterate the products in cart and add the meta data (product name, desc, etc)
    foreach ($products_in_cart as &$cart_product) {
        foreach ($products as $product) {
            if ($cart_product['id'] == $product['id']) {
                // If product no longer in stock, prepare for removal
                if ((int)$product['quantity'] === 0) {
                    $cart_product['remove'] = 1;
                } else {
                    $cart_product['meta'] = $product;
                    // Prevent the cart quantity exceeding the product quantity
                    $cart_product['quantity'] = $cart_product['quantity'] > $product['quantity'] && $product['quantity'] !== -1 ? $product['quantity'] : $cart_product['quantity'];
                    $product_weight = $cart_product['options_weight'];
                    $weighttotal += $product_weight;
                    // Calculate the subtotal
                    $product_price = (float)$cart_product['options_price'];
                    $subtotal += $product_price * (int)$cart_product['quantity'];
                    // Calculate the final price, which includes tax
                    $cart_product['final_price'] = $product_price + (($tax_rate / 100) * $product_price);
                    $taxtotal += (($tax_rate / 100) * $product_price) * (int)$cart_product['quantity'];
                    // Check which products are eligible for a discount
                    if (isset($discount) && $discount && $current_date >= strtotime($discount['start_date']) && $current_date <= strtotime($discount['end_date'])) {
                        // Check whether product list is empty or if product id is whitelisted
                        if (empty($discount['product_ids']) || in_array($product['id'], explode(',', $discount['product_ids']))) {
                            // Check whether category list is empty or if category id is whitelisted
                            if (empty($discount['category_ids']) || array_intersect(explode(',', $product['categories']), explode(',', $discount['category_ids']))) {
                                $cart_product['discounted'] = true;
                            }
                        }
                    }
                }
            }
        }
    }
    // Remove products that are out of stock
    for ($i = 0; $i < count($products_in_cart); $i++) {
        if (isset($products_in_cart[$i]['remove'])) {
            unset($_SESSION['cart'][$i]);
            unset($products_in_cart[$i]);
        }
    }
    $_SESSION['cart'] = array_values($_SESSION['cart']);
    $products_in_cart = array_values($products_in_cart);
    // Redirect the user if the shopping cart is empty
    if (empty($products_in_cart)) {
        header('Location: ' . url('index.php?page=cart'));
        exit;
    }
    // Calculate the shipping
    foreach ($products_in_cart as &$cart_product) {
        foreach ($shipping_methods as $shipping_method) {
            // Product weight
            $product_weight = $cart_product['options_weight'] ? $cart_product['options_weight'] : $weighttotal;
            // Determine the price
            $product_price = $shipping_method['type'] == 'Single Product' ? (float)$cart_product['options_price'] : $subtotal;
            // Check if no country required or if shipping method only available in specified countries
            if (empty($shipping_method['countries']) || in_array($selected_country, explode(',', $shipping_method['countries']))) {
                // Compare the price and weight to meet shipping method requirements
                if ($shipping_method['id'] == $selected_shipping_method && $product_price >= $shipping_method['price_from'] && $product_price <= $shipping_method['price_to'] && $product_weight >= $shipping_method['weight_from'] && $product_weight <= $shipping_method['weight_to']) {
                    if ($shipping_method['type'] == 'Single Product') {
                        // Calculate single product price
                        $cart_product['shipping_price'] += (float)$shipping_method['price'] * (int)$cart_product['quantity'];
                        $shippingtotal += $cart_product['shipping_price'];
                    } else {
                        // Calculate entire order price
                        $cart_product['shipping_price'] = (float)$shipping_method['price'] / count($products_in_cart);
                        $shippingtotal = (float)$shipping_method['price'];
                    }
                    $shipping_methods_available[] = $shipping_method['id'];
                } else if ($product_price >= $shipping_method['price_from'] && $product_price <= $shipping_method['price_to'] && $product_weight >= $shipping_method['weight_from'] && $product_weight <= $shipping_method['weight_to']) {
                    // No method selected, so store all methods available
                    $shipping_methods_available[] = $shipping_method['id'];
                }
            }
            // Update selected shipping method name
            if ($shipping_method['id'] == $selected_shipping_method) {
                $selected_shipping_method_name = $shipping_method['name'];
            }
        }
    }
    // Number of discounted products
    $num_discounted_products = count(array_column($products_in_cart, 'discounted'));
    // Iterate the products and update the price for the discounted products
    foreach ($products_in_cart as &$cart_product) {
        if (isset($cart_product['discounted']) && $cart_product['discounted']) {
            $price = &$cart_product['final_price'];
            if ($discount['discount_type'] == 'Percentage') {
                $d = (float)$price * ((float)$discount['discount_value'] / 100);
                $price -= $d;
                $discounttotal += $d * (int)$cart_product['quantity'];
            }
            if ($discount['discount_type'] == 'Fixed') {
                $d = (float)$discount['discount_value'] / $num_discounted_products;
                $price -= $d / (int)$cart_product['quantity'];
                $discounttotal += $d;
            }
        }
    }
}
// Make sure when the user submits the form all data was submitted and shopping cart is not empty
if (isset($_POST['method'], $_POST['first_name'], $_POST['last_name'], $_POST['address_street'], $_POST['address_city'], $_POST['address_state'], $_POST['address_zip'], $_POST['address_country'], $_SESSION['cart']) && !isset($_POST['update'])) {
    $account_id = null;
    // If the user is already logged in
    if (isset($_SESSION['account_loggedin'])) {
        // Account logged-in, update the user's details
        $stmt = $pdo->prepare('UPDATE accounts SET first_name = ?, last_name = ?, address_street = ?, address_city = ?, address_state = ?, address_zip = ?, address_country = ? WHERE id = ?');
        $stmt->execute([ $_POST['first_name'], $_POST['last_name'], $_POST['address_street'], $_POST['address_city'], $_POST['address_state'], $_POST['address_zip'], $_POST['address_country'], $_SESSION['account_id'] ]);
        $account_id = $_SESSION['account_id'];
    } else if (isset($_POST['email'], $_POST['password'], $_POST['cpassword']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) && !empty($_POST['password']) && !empty($_POST['cpassword'])) {
        // User is not logged in, check if the account already exists with the email they submitted
        $stmt = $pdo->prepare('SELECT id FROM accounts WHERE email = ?');
        $stmt->execute([ $_POST['email'] ]);
    	if ($stmt->fetch(PDO::FETCH_ASSOC)) {
            // Email exists, user should login instead...
    		$errors[] = 'Account already exists with that email!';
        }
        if (strlen($_POST['password']) > 20 || strlen($_POST['password']) < 5) {
            // Password must be between 5 and 20 characters long.
            $errors[] = 'Password must be between 5 and 20 characters long!';
    	}
        if ($_POST['password'] != $_POST['cpassword']) {
            // Password and confirm password fields do not match...
            $errors[] = 'Passwords do not match!';
        }
        if (!$errors) {
            // Hash the password
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            // Email doesnt exist, create new account
            $stmt = $pdo->prepare('INSERT INTO accounts (email, password, first_name, last_name, address_street, address_city, address_state, address_zip, address_country) VALUES (?,?,?,?,?,?,?,?,?)');
            $stmt->execute([ $_POST['email'], $password, $_POST['first_name'], $_POST['last_name'], $_POST['address_street'], $_POST['address_city'], $_POST['address_state'], $_POST['address_zip'], $_POST['address_country'] ]);
            $account_id = $pdo->lastInsertId();
            $stmt = $pdo->prepare('SELECT * FROM accounts WHERE id = ?');
            $stmt->execute([ $account_id ]);
            // Fetch the account from the database and return the result as an Array
            $account = $stmt->fetch(PDO::FETCH_ASSOC);
        }
    } else if (account_required) {
        $errors[] = 'Account creation required!';
    }
    if (!$errors && $products_in_cart) {
        // No errors, process the order
        // Process Stripe Payment
        if (stripe_enabled && $_POST['method'] == 'stripe') {
            // Include the stripe lib
            require_once 'lib/stripe/init.php';
            $stripe = new \Stripe\StripeClient(stripe_secret_key);
            $line_items = [];
            // Iterate the products in cart and add each product to the array above
            for ($i = 0; $i < count($products_in_cart); $i++) {
                $line_items[] = [
                    'quantity' => $products_in_cart[$i]['quantity'],
                    'price_data' => [
                        'currency' => stripe_currency,
                        'unit_amount' => round((float)$products_in_cart[$i]['final_price'] * 100),
                        'product_data' => [
                            'name' => $products_in_cart[$i]['meta']['name'],
                            'metadata' => [
                                'item_id' => $products_in_cart[$i]['id'],
                                'item_options' => $products_in_cart[$i]['options'],
                                'item_shipping' => $products_in_cart[$i]['shipping_price']
                            ]
                        ]
                    ]
                ];
            }
            // Add the shipping
            $line_items[] = [
                'quantity' => 1,
                'price_data' => [
                    'currency' => stripe_currency,
                    'unit_amount' => round($shippingtotal*100),
                    'product_data' => [
                        'name' => 'Shipping',
                        'description' => $selected_shipping_method_name,
                        'metadata' => [
                            'item_id' => 'shipping',
                            'shipping_method' => $selected_shipping_method_name
                        ]
                    ]
                ]
            ];
            // Check the webhook secret
            if (empty(stripe_webhook_secret)) {
                // No webhook secret, attempt to create one
                // Get the config.php file contents
                $contents = file_get_contents('config.php');
                if ($contents) {
                    // Attempt to create the webhook and get the secret
                    $webhook = $stripe->webhookEndpoints->create([
                        'url' => stripe_ipn_url,
                        'description' => 'pnblack', // Feel free to change this
                        'enabled_events' => ['checkout.session.completed']
                    ]);
                    $secret = $webhook['secret'];
                    // Update the "stripe_webhook_secret" constant in the config.php file with the new secret
                    $contents = preg_replace('/define\(\'stripe_webhook_secret\'\, ?(.*?)\)/s', 'define(\'stripe_webhook_secret\',\'' . $secret . '\')', $contents);
                    if (!file_put_contents('config.php', $contents)) {
                        // Could not write to config.php file
                        exit('Failed to automatically assign the Stripe webhook secret! Please set it manually in the config.php file.');
                    }
                } else {
                    // Could not open config.php file
                    exit('Failed to automatically assign the Stripe webhook secret! Please set it manually in the config.php file.');
                }
            }
            // Create the stripe checkout session and redirect the customer
            $session = $stripe->checkout->sessions->create([
                'success_url' => stripe_return_url,
                'cancel_url' => stripe_cancel_url,
                'payment_method_types' => ['card'],
                'line_items' => $line_items,
                'mode' => 'payment',
                'customer_email' => isset($account['email']) && !empty($account['email']) ? $account['email'] : $_POST['email'],
                'metadata' => [
                    'first_name' => $_POST['first_name'],
                    'last_name' => $_POST['last_name'],
                    'address_street' => $_POST['address_street'],
                    'address_city' => $_POST['address_city'],
                    'address_state' => $_POST['address_state'],
                    'address_zip' => $_POST['address_zip'],
                    'address_country' => $_POST['address_country'],
                    'account_id' => $account_id,
                    'discount_code' => isset($_SESSION['discount']) ? $_SESSION['discount'] : ''
                ]
            ]);
            // Redirect to Stripe checkout
            header('Location: stripe-redirect.php?stripe_session_id=' . $session['id']);
            exit;
        }
        // Process PayPal Payment
        if (paypal_enabled && $_POST['method'] == 'paypal') {
            // Process PayPal Checkout
            // Variable that will stored all details for all products in the shopping cart
            $data = [];
            // Add all the products that are in the shopping cart to the data array variable
            for ($i = 0; $i < count($products_in_cart); $i++) {
                $data['item_number_' . ($i+1)] = $products_in_cart[$i]['id'];
                $data['item_name_' . ($i+1)] = $products_in_cart[$i]['meta']['name'];
                $data['quantity_' . ($i+1)] = $products_in_cart[$i]['quantity'];
                $data['amount_' . ($i+1)] = $products_in_cart[$i]['final_price'];
                $data['on0_' . ($i+1)] = 'Options';
                $data['os0_' . ($i+1)] = $products_in_cart[$i]['options'];
            }
            // Metadata
            $metadata = [
                'account_id' => $account_id,
                'discount_code' => isset($_SESSION['discount']) ? $_SESSION['discount'] : '',
                'shipping_method' => $selected_shipping_method_name
            ];
            // Variables we need to pass to paypal
            $data = $data + [
                'cmd'			=> '_cart',
                'charset'		=> 'UTF-8',
                'upload'        => '1',
                'custom'        => json_encode($metadata),
                'business' 		=> paypal_email,
                'cancel_return'	=> paypal_cancel_url,
                'notify_url'	=> paypal_ipn_url,
                'currency_code'	=> paypal_currency,
                'return'        => paypal_return_url,
                'shipping_1'    => $shippingtotal,
                'address1'      => $_POST['address_street'],
                'city'          => $_POST['address_city'],
                'country'       => $_POST['address_country'],
                'state'         => $_POST['address_state'],
                'zip'           => $_POST['address_zip'],
                'first_name'    => $_POST['first_name'],
                'last_name'     => $_POST['last_name'],
                'email'         => isset($account['email']) && !empty($account['email']) ? $account['email'] : $_POST['email']
            ];
            if ($account_id != null) {
                // Log the user in with the details provided
                session_regenerate_id();
                $_SESSION['account_loggedin'] = TRUE;
                $_SESSION['account_id'] = $account_id;
                $_SESSION['account_role'] = $account ? $account['role'] : 'Member';
            }
            // Redirect the user to the PayPal checkout screen
            header('location:' . (paypal_testmode ? 'https://www.sandbox.paypal.com/cgi-bin/webscr' : 'https://www.paypal.com/cgi-bin/webscr') . '?' . http_build_query($data));
            // End the script, don't need to execute anything else
            exit;
        }
        // Process Coinbase Payment
        if (coinbase_enabled && $_POST['method'] == 'coinbase') {
            // Include the coinbase library
            require_once 'lib/vendor/autoload.php';
            $coinbase = CoinbaseCommerce\ApiClient::init(coinbase_key);
            // Variable that will stored all details for all products in the shopping cart
            $metadata = [];
            $description = '';
            // Add all the products that are in the shopping cart to the data array variable
            for ($i = 0; $i < count($products_in_cart); $i++) {
                // Add product data to array
                $metadata['item_' . ($i+1)] = $products_in_cart[$i]['id'];
                $metadata['item_name_' . ($i+1)] = $products_in_cart[$i]['meta']['name'];
                $metadata['qty_' . ($i+1)] = $products_in_cart[$i]['quantity'];
                $metadata['amount_' . ($i+1)] = $products_in_cart[$i]['final_price'];
                $metadata['option_' . ($i+1)] = $products_in_cart[$i]['options'];
                $description .= 'x' . $products_in_cart[$i]['quantity'] . ' ' . $products_in_cart[$i]['meta']['name'] . ', ';
            }
            // Add customer info
            $metadata['email'] = isset($account['email']) && !empty($account['email']) ? $account['email'] : $_POST['email'];
            $metadata['first_name'] = $_POST['first_name'];
            $metadata['last_name'] = $_POST['last_name'];
            $metadata['address_street'] = $_POST['address_street'];
            $metadata['address_city'] = $_POST['address_city'];
            $metadata['address_state'] = $_POST['address_state'];
            $metadata['address_zip'] = $_POST['address_zip'];
            $metadata['address_country'] = $_POST['address_country'];
            $metadata['account_id'] = $account_id;
            $metadata['discount_code'] = isset($_SESSION['discount']) ? $_SESSION['discount'] : '';
            $metadata['shipping_method'] = $selected_shipping_method_name;
            // Add shipping
            $metadata['shipping'] = $shippingtotal;
            // Add number of cart items
            $metadata['num_cart_items'] = count($products_in_cart);
            // Data
            $data = [
                'name' => count($products_in_cart) . ' Item' . (count($products_in_cart) > 1 ? 's' : ''),
                'description' => rtrim($description, ', '),
                'local_price' => [
                    'amount' => ($subtotal-$discounttotal)+$shippingtotal,
                    'currency' => coinbase_currency
                ],
                'metadata' => $metadata,
                'pricing_type' => 'fixed_price',
                'redirect_url' => coinbase_return_url,
                'cancel_url' => coinbase_cancel_url
            ];
            // Create charge
            $charge = CoinbaseCommerce\Resources\Charge::create($data);
            // Redirect to hosted checkout page
            header('Location: ' . $charge->hosted_url);
            exit;
        }
        if (pay_on_delivery_enabled && $_POST['method'] == 'payondelivery') {
            // Process Normal Checkout
            // Generate unique transaction ID
            $transaction_id = strtoupper(uniqid('SC') . substr(md5(mt_rand()), 0, 5));
            // Insert transaction into database
            $stmt = $pdo->prepare('INSERT INTO transactions (txn_id, payment_amount, payment_status, created, payer_email, first_name, last_name, address_street, address_city, address_state, address_zip, address_country, account_id, payment_method, shipping_method, shipping_amount, discount_code) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
            $stmt->execute([
                $transaction_id,
                ($subtotal-$discounttotal)+$shippingtotal+$taxtotal,
                default_payment_status,
                date('Y-m-d H:i:s'),
                isset($account['email']) && !empty($account['email']) ? $account['email'] : $_POST['email'],
                $_POST['first_name'],
                $_POST['last_name'],
                $_POST['address_street'],
                $_POST['address_city'],
                $_POST['address_state'],
                $_POST['address_zip'],
                $_POST['address_country'],
                $account_id,
                'website',
                $selected_shipping_method_name,
                $shippingtotal,
                isset($_SESSION['discount']) ? $_SESSION['discount'] : ''
            ]);
            // Get order ID
            $order_id = $pdo->lastInsertId();
            // Iterate products and deduct quantities
            foreach ($products_in_cart as $product) {
                // For every product in the shopping cart insert a new transaction into our database
                $stmt = $pdo->prepare('INSERT INTO transactions_items (txn_id, item_id, item_price, item_quantity, item_options) VALUES (?,?,?,?,?)');
                $stmt->execute([ $transaction_id, $product['id'], $product['final_price'], $product['quantity'], $product['options'] ]);
                // Update product quantity in the products table
                $stmt = $pdo->prepare('UPDATE products SET quantity = quantity - ? WHERE quantity > 0 AND id = ?');
                $stmt->execute([ $product['quantity'], $product['id'] ]);
                // Deduct option quantities
                if ($product['options']) {
                    $options = explode(',', $product['options']);
                    foreach ($options as $opt) {
                        $option_name = explode('-', $opt)[0];
                        $option_value = explode('-', $opt)[1];
                        $stmt = $pdo->prepare('UPDATE products_options SET quantity = quantity - ? WHERE quantity > 0 AND title = ? AND (name = ? OR name = "")');
                        $stmt->execute([ $product['quantity'], $option_name, $option_value ]);                
                    }
                }
            }
            // Authenticate the user
            if ($account_id != null) {
                // Log the user in with the details provided
                session_regenerate_id();
                $_SESSION['account_loggedin'] = TRUE;
                $_SESSION['account_id'] = $account_id;
                $_SESSION['account_role'] = $account ? $account['role'] : 'Member';
            }
            // Send order details to the specified email address
            send_order_details_email(
                isset($account['email']) && !empty($account['email']) ? $account['email'] : $_POST['email'],
                $products_in_cart,
                $_POST['first_name'],
                $_POST['last_name'],
                $_POST['address_street'],
                $_POST['address_city'],
                $_POST['address_state'],
                $_POST['address_zip'],
                $_POST['address_country'],
                ($subtotal-$discounttotal)+$shippingtotal,
                $order_id
            );
            header('Location: ' . url('index.php?page=placeorder'));
            exit;
        }
    }
    // Preserve form details if the user encounters an error
    $account = [
        'first_name' => $_POST['first_name'],
        'last_name' => $_POST['last_name'],
        'address_street' => $_POST['address_street'],
        'address_city' => $_POST['address_city'],
        'address_state' => $_POST['address_state'],
        'address_zip' => $_POST['address_zip'],
        'address_country' => $_POST['address_country']
    ];
}
?>
<?=template_header('Checkout')?>
<script>
  let productslink = document.querySelectorAll("[id='productslink']");

for(var i = 0; i < productslink.length; i++){
  productslink.item(i).classList.add('active');
} 
</script>


<div class="checkout">

    <p class="error"><?=implode('<br>', $errors)?></p>

    <?php if (!isset($_SESSION['account_loggedin'])): ?>
    <h4 style="padding:20px 40px;color:var(--clr-gray-300);">Have an account? <a style="color:var(--clr-purp);font-size:var(--fs-8);font-weight:var(--fw-5)" href="<?=url('index.php?page=myaccount')?>">Log In</a></h4>
    <?php endif; ?>

    <form action="" method="post">

        <div class="container">

            <div class="shipping-details">

                <h2>Payment Method</h2>

                <div class="payment-methods">
                    <?php if (pay_on_delivery_enabled): ?>
                    <input id="payondelivery" type="radio" name="method" value="payondelivery">
                    <label for="payondelivery">Pay on Delivery</label>
                    <?php endif; ?>

                    <?php if (paypal_enabled): ?>
                    <input id="paypal" type="radio" name="method" value="paypal">
                    <label for="paypal"><img src="https://www.paypalobjects.com/webstatic/mktg/Logo/pp-logo-100px.png" alt="PayPal Logo"></label>
                    <?php endif; ?>

                    <?php if (stripe_enabled): ?>
                    <input id="stripe" type="radio" name="method" value="stripe" checked>
                    <label for="stripe">CREDIT / DEBIT CARD</label>
                    <?php endif; ?>
                    
                    <?php if (coinbase_enabled): ?>
                    <input id="coinbase" type="radio" name="method" value="coinbase">
                    <label for="coinbase">CRYPTO</label>
                    <?php endif; ?>
                </div>

                <h2>Shipping Details</h2>
                <div class="credentials-box">
                <div class="row1">
                    <label for="first_name">First Name</label>
                    <input type="text" value="<?=htmlspecialchars($account['first_name'], ENT_QUOTES)?>" name="first_name" id="first_name" placeholder="John" class="form-field" required>
                </div>

                <div class="row2">
                    <label for="last_name">Last Name</label>
                    <input type="text" value="<?=htmlspecialchars($account['last_name'], ENT_QUOTES)?>" name="last_name" id="last_name" placeholder="Doe" class="form-field" required>
                </div>

                <label for="address_street">Address</label>
                <input type="text" value="<?=htmlspecialchars($account['address_street'], ENT_QUOTES)?>" name="address_street" id="address_street" placeholder="24 High Street" class="form-field" required>

                <label for="address_city">City</label>
                <input type="text" value="<?=htmlspecialchars($account['address_city'], ENT_QUOTES)?>" name="address_city" id="address_city" placeholder="New York" class="form-field" required>

                <div class="row1">
                    <label for="address_state">State</label>
                    <input type="text" value="<?=htmlspecialchars($account['address_state'], ENT_QUOTES)?>" name="address_state" id="address_state" placeholder="NY" class="form-field" required>
                </div>

                <div class="row2">
                    <label for="address_zip">Zip</label>
                    <input type="text" value="<?=htmlspecialchars($account['address_zip'], ENT_QUOTES)?>" name="address_zip" id="address_zip" placeholder="10001" class="form-field" required>
                </div>

                <label for="address_country">Country</label>
                <select name="address_country"  id="address_country" class="ajax-update form-field" required>
                    <?php foreach(get_countries() as $country): ?>
                    <option style="background: #000;" value="<?=$country?>"<?=$country==$account['address_country']?' selected':''?>><?=$country?></option>
                    <?php endforeach; ?>
                </select>

                    </div>

                    <?php if (!isset($_SESSION['account_loggedin'])): ?>
                <div class="credentials-box">
                <h2>Create Account<?php if (!account_required): ?> (optional)<?php endif; ?></h2>

                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="john@example.com" class="form-field" required>

                <label for="password">Password</label>
                <input type="password" name="password" id="password" placeholder="Password" class="form-field" autocomplete="new-password">

                <label for="cpassword">Confirm Password</label>
                <input type="password" name="cpassword" id="cpassword" placeholder="Confirm Password" class="form-field" autocomplete="new-password">
                </div>
                <?php endif; ?>

            </div>

            <div class="cart-details">
                    
                <h2>Shopping Cart</h2>

                <table>
                    <?php foreach($products_in_cart as $product): ?>
                    <tr>
                        <td><img src="<?=$product['meta']['img']?>" width="35" height="35"  style="vertical-align: middle;" alt="<?=$product['meta']['name']?>"></td>
                        <td><?=$product['quantity']?> x <?=$product['meta']['name']?></td>
                        <td class="price"><?=currency_code?><?=number_format($product['options_price'] * $product['quantity'],2)?></td>
                    </tr>
                    <?php endforeach; ?>
                </table>

                <div class="discount-code  credentials-box">
                <label for="discount_code">Discount Code</label>
                   <input type="text" class="ajax-update" name="discount_code" id="discount_code" placeholder="Discount Code" value="<?=isset($_SESSION['discount']) ? $_SESSION['discount'] : ''?>">
                    <span class="result">
                        <?php if (isset($_SESSION['discount'], $discount) && !$discount): ?>
                        Incorrect discount code!
                        <?php elseif (isset($_SESSION['discount'], $discount) && $current_date < strtotime($discount['start_date'])): ?>
                        Incorrect discount code!  
                        <?php elseif (isset($_SESSION['discount'], $discount) && $current_date > strtotime($discount['end_date'])): ?>
                        Discount code expired!
                        <?php elseif (isset($_SESSION['discount'], $discount)): ?>
                        Discount code applied!
                        <?php endif; ?>
                    </span>
                </div>

                
                <div class="shipping-methods-container credentials-box">
                <h2>Shipping</h2>
                    <?php if ($shipping_methods_available): ?>
                    <div class="shipping-methods">
                        <?php foreach($shipping_methods as $k => $method): ?>
                        <?php if (!in_array($method['id'], $shipping_methods_available)) continue; ?>
                        <div class="shipping-method radio-checkbox">                         
                          <label  for="sm<?=$k?>"><?=$method['name']?> (<?=currency_code?><?=number_format($method['price'], 2)?><?=$method['type']=='Single Product'?' per item':''?>)
                        <input type="radio"  class="ajax-update" id="sm<?=$k?>" name="shipping_method" value="<?=$method['id']?>" required<?=$selected_shipping_method==$method['id']?' checked':''?>>
                            <span class="rdo"></span>
                            <span><p></p></span>
                          </label>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
                
                <div class="summary">
                    <div class="subtotal">
                        <span>Subtotal</span>
                        <span><?=currency_code?><?=number_format($subtotal,2)?></span>
                    </div>

                    <?php if ($tax): ?>
                    <div class="vat">
                        <span>VAT <span class="alt">(<?=$tax['rate']?>%)</span></span>
                        <span><?=currency_code?><?=number_format($taxtotal,2)?></span>
                    </div>
                    <?php endif; ?>

                    <div class="shipping">
                        <span>Shipping</span>
                        <span><?=currency_code?><?=number_format($shippingtotal,2)?></span>
                    </div>

                    <?php if ($discounttotal > 0): ?>
                    <div class="discount">
                        <span>Discount</span>
                        <span>-<?=currency_code?><?=number_format(round($discounttotal, 1),2)?></span>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="total">
                    <span>Total <span class="alt">(VAT included)</span></span><span><?=currency_code?><?=number_format($subtotal-round($discounttotal,1)+$shippingtotal+$taxtotal,2)?></span>
                </div>

               
                            <div class="buttons">
                                <button type="submit" name="checkout" class="button-colordot">
                                    <span>Place Order</span>
                                    <svg width="13px" height="10px" viewBox="0 0 13 10">
                                        <path d="M1,5 L11,5"></path>
                                        <polyline points="8 1 12 5 8 9"></polyline>
                                    </svg>
                                </button>
                            </div>

            </div>

        </div>

    </form>

</div>

<?=template_footer()?>