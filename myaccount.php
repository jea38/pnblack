<?php
// Prevent direct access to file
defined('pnblack') or exit;
// User clicked the "Login" button, proceed with the login process... check POST data and validate email
if (isset($_POST['login'], $_POST['email'], $_POST['password']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    // Check if the account exists
    $stmt = $pdo->prepare('SELECT * FROM accounts WHERE email = ?');
    $stmt->execute([ $_POST['email'] ]);
    $account = $stmt->fetch(PDO::FETCH_ASSOC);
    // If account exists verify password
    if ($account && password_verify($_POST['password'], $account['password'])) {
        // User has logged in, create session data
        session_regenerate_id();
        $_SESSION['account_loggedin'] = TRUE;
        $_SESSION['account_id'] = $account['id'];
        $_SESSION['account_role'] = $account['role'];
        $products_in_cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
        if ($products_in_cart) {
            // user has products in cart, redirect them to the checkout page
            header('Location: ' . url('index.php?page=checkout'));
        } else {
            // Redirect the user back to the same page, they can then see their order history
            header('Location: ' . url('index.php?page=myaccount'));
        }
        exit;
    } else {
        $error = 'Incorrect Email/Password!';
    }
}
// Variable that will output registration errors
$register_error = '';
// User clicked the "Register" button, proceed with the registration process... check POST data and validate email
if (isset($_POST['register'], $_POST['email'], $_POST['password'], $_POST['cpassword']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    // Check if the account exists
    $stmt = $pdo->prepare('SELECT * FROM accounts WHERE email = ?');
    $stmt->execute([ $_POST['email'] ]);
    $account = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($account) {
        // Account exists!
        $register_error = 'Account already exists with that email!';
    } else if ($_POST['cpassword'] != $_POST['password']) {
        $register_error = 'Passwords do not match!';
    } else if (strlen($_POST['password']) > 20 || strlen($_POST['password']) < 5) {
        // Password must be between 5 and 20 characters long.
        $register_error = 'Password must be between 5 and 20 characters long!';
    } else {
        // Account doesnt exist, create new account
        $stmt = $pdo->prepare('INSERT INTO accounts (email, password, first_name, last_name, address_street, address_city, address_state, address_zip, address_country) VALUES (?,?,"","","","","","","")');
        // Hash the password
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt->execute([ $_POST['email'], $password ]);
        $account_id = $pdo->lastInsertId();
        // Automatically login the user
        session_regenerate_id();
        $_SESSION['account_loggedin'] = TRUE;
        $_SESSION['account_id'] = $account_id;
        $_SESSION['account_role'] = 'Member';
        $products_in_cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
        if ($products_in_cart) {
            // User has products in cart, redirect them to the checkout page
            header('Location: ' . url('index.php?page=checkout'));
        } else {
            // Redirect the user back to the same page, they can then see their order history
            header('Location: ' . url('index.php?page=myaccount'));
        }
        exit;
    }
}
// Determine the current tab page
$tab = isset($_GET['tab']) ? $_GET['tab'] : 'orders';
// If user is logged in
if (isset($_SESSION['account_loggedin'])) {
    // Select all the users transations, which will appear under "My Orders"
    $stmt = $pdo->prepare('SELECT * FROM transactions  WHERE account_id = ? ORDER BY created DESC');
    $stmt->execute([ $_SESSION['account_id'] ]);
    $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // Select all the users transations, which will appear under "My Orders"
    $stmt = $pdo->prepare('SELECT
        p.name,
        p.id AS product_id,
        t.txn_id,
        t.payment_status,
        t.created AS transaction_date,
        ti.item_price AS price,
        ti.item_quantity AS quantity,
        ti.item_id,
        (SELECT m.full_path FROM products_media pm JOIN media m ON m.id = pm.media_id WHERE pm.product_id = p.id ORDER BY pm.position ASC LIMIT 1) AS img 
        FROM transactions t
        JOIN transactions_items ti ON ti.txn_id = t.txn_id
        JOIN accounts a ON a.id = t.account_id
        JOIN products p ON p.id = ti.item_id
        WHERE t.account_id = ?
        ORDER BY t.created DESC');
    $stmt->execute([ $_SESSION['account_id'] ]);
    $transactions_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // Retrieve the digital downloads
    $transactions_ids = array_column($transactions_items, 'product_id');
    if ($transactions_ids) {
        $stmt = $pdo->prepare('SELECT product_id, file_path, id FROM products_downloads WHERE product_id IN (' . trim(str_repeat('?,',count($transactions_ids)),',') . ') ORDER BY position ASC');
        $stmt->execute($transactions_ids);
        $downloads = $stmt->fetchAll(PDO::FETCH_GROUP);
    } else {
        $downloads = [];
    }
    // Retrieve account details
    $stmt = $pdo->prepare('SELECT * FROM accounts WHERE id = ?');
    $stmt->execute([ $_SESSION['account_id'] ]);
    $account = $stmt->fetch(PDO::FETCH_ASSOC);
    // Update settings
    if (isset($_POST['save_details'], $_POST['email'], $_POST['password'])) {
        // Assign and validate input data
        $first_name = isset($_POST['first_name']) ? $_POST['first_name'] : '';
        $last_name = isset($_POST['last_name']) ? $_POST['last_name'] : '';
        $address_street = isset($_POST['address_street']) ? $_POST['address_street'] : '';
        $address_city = isset($_POST['address_city']) ? $_POST['address_city'] : '';
        $address_state = isset($_POST['address_state']) ? $_POST['address_state'] : '';
        $address_zip = isset($_POST['address_zip']) ? $_POST['address_zip'] : '';
        $address_country = isset($_POST['address_country']) ? $_POST['address_country'] : '';
        // Check if account exists with captured email
        $stmt = $pdo->prepare('SELECT * FROM accounts WHERE email = ?');
        $stmt->execute([ $_POST['email'] ]);
        // Validation
        if ($_POST['email'] != $account['email'] && $stmt->fetch(PDO::FETCH_ASSOC)) {
            $error = 'Account already exists with that email!';
        } else if ($_POST['password'] && (strlen($_POST['password']) > 20 || strlen($_POST['password']) < 5)) {
            $error = 'Password must be between 5 and 20 characters long!';
        } else {
            // Update account details in database
            $password = $_POST['password'] ? password_hash($_POST['password'], PASSWORD_DEFAULT) : $account['password'];
            $stmt = $pdo->prepare('UPDATE accounts SET email = ?, password = ?, first_name = ?, last_name = ?, address_street = ?, address_city = ?, address_state = ?, address_zip = ?, address_country = ? WHERE id = ?');
            $stmt->execute([ $_POST['email'], $password, $first_name, $last_name, $address_street, $address_city, $address_state, $address_zip, $address_country, $_SESSION['account_id'] ]);
            // Redirect to settings page
            header('Location: ' . url('index.php?page=myaccount&tab=settings'));
            exit;           
        }
    }
}
?>

<?=template_header('Account')?>

<script>
  let myaccountlink = document.querySelectorAll("[id='myaccountlink']");

for(var i = 0; i < myaccountlink.length; i++){
  myaccountlink.item(i).classList.add('active');
} 

</script>


<section id="landing-section">
          <img loading="lazy" class="column_1 user-select-none pe-none" src="https://pnblack.com/shared/featured-image.jpg" alt="" />
          <div class="column_2">
            <div></div>
            <div class="column_2b">
              <h1 class="d3">PnBlack <span class='typewriter-text' data-text='[ "Account 👤"]'></span>
            </h1>
            </div>
          </div>
        </section>


<div class="myaccount">

    <?php if (!isset($_SESSION['account_loggedin'])): ?>



        <section id="our-work" class="login-register">
          <div class="column_1 container py-5 flow">
          <div class="credentials-box login">
                <h3>sign in to your account.</h3>
              <?php if ($error): ?>
            <p style="color:var(--clr-sec-400);"><?=$error?></p>
            <?php endif; ?>

            
            <form action="" method="post">

                <input style="background:#000;"  type="email" name="email" id="email" placeholder="Enter your email" required>

                <input style="background:#000;"  type="password" name="password" id="password" placeholder="Password" required>

                <button name="login" type="submit" class="btn-shiny shiny-effect01" target="_blank">              
                        <span>Login</span>
                </button>
                <a class="fpwd" href="f/fpwd.php">Forgot Password?</a>
                </form>
                  
            </div>
          </div>
          <div class="column_2 container py-5 flow">
          <div class="credentials-box register">
                <h3>create an account.</h3>
                       <?php if ($register_error): ?>
            <p class="error"><?=$register_error?></p>
            <?php endif; ?>


            <form action="" method="post">

                <input style="background:#000;"  type="email" name="email" id="email" placeholder="Enter your email" required>

                <input style="background:#000;"   type="password" name="password" id="password" placeholder="Password" required>

                <input style="background:#000;"  type="password" name="cpassword" id="cpassword" placeholder="Confirm Password" required>

                <button name="register" type="submit" class="btn-shiny shiny-effect01" target="_blank">              
                        <span>Register</span>
                </button>

                </form>
            </div>
          </div>
        </section>

          



    <?php else: ?>

    <div class="menu">
        <div class="menu-items">
            <a href="<?=url('index.php?page=myaccount')?>">Orders</a>
            <a href="<?=url('index.php?page=myaccount&tab=downloads')?>">Downloads</a>
            <a href="<?=url('index.php?page=myaccount&tab=settings')?>">Settings</a>
        </div>

    </div>


    <?php if ($tab == 'orders'): ?>
    <div class="myorders">

        <h3 style="color:var(--clr-purp);">My Orders</h3>

        <?php if (empty($transactions)): ?>
            <h4>You have no orders.</h4>
        <?php endif; ?>
        <?php foreach ($transactions as $transaction): ?>
        <div class="order">
            <div class="order-header">
                <div>           
                    <div class="rhide"><span>Date: </span><?=date('F j, Y', strtotime($transaction['created']))?></div>
                    <div style="color:var(--clr-pri-400);"><span>Status: </span><?=$transaction['payment_status']?></div>
                    <div class="rhide"><span>Shipping: </span><?=currency_code?><?=number_format($transaction['shipping_amount'],2)?></div>
                    <div><span>Total: </span><?=currency_code?><?=number_format($transaction['payment_amount'],2)?></div>
                </div>
            </div>
            <div class="order-items">
                <table>
                    <tbody>
                    <div style="color:var(--clr-pri-400);"><span>ID: # <?=$transaction['id']?></div>
                      <div><span>Tracking Number: </span><span style="color:var(--clr-pri-400);"><?=$transaction['tracking_number']?></span></div>
                    <div><span>Carrier: </span><span style="color:var(--clr-pri-400);"><?=$transaction['carrier']?></span></div>

                        <?php foreach ($transactions_items as $transaction_item): ?>
                        <?php if ($transaction_item['txn_id'] != $transaction['txn_id']) continue; ?>
                        <tr>
                            <td class="img">
                                <?php if (!empty($transaction_item['img']) && file_exists($transaction_item['img'])): ?>
                                <img src="<?=base_url?><?=$transaction_item['img']?>" width="50" height="50" style="vertical-align: middle;"  alt="<?=$transaction_item['name']?>">
                                <?php endif; ?>
                            </td>
                            <td class="name"><?=$transaction_item['quantity']?> x <?=$transaction_item['name']?></td>
                            <td class="price"><?=currency_code?><?=number_format($transaction_item['price'] * $transaction_item['quantity'],2)?></td>
                        </tr>                   
                        <?php endforeach; ?>
                    </tbody>
                </table>                
            </div>
        </div>
        <?php endforeach; ?>


    <?php elseif ($tab == 'downloads'): ?>
    <div class="mydownloads">

        <h3 style="color:var(--clr-purp);">My Downloads</h3>

        <?php if (empty($downloads)): ?>
        <h4>You have no digital downloads</h4>
        <?php endif; ?>
        <?php if ($downloads): ?>
        <table>
            <thead>
                <tr>
                    <td colspan="2">Product</td>
                    <td></td>
                </tr>
            </thead>
            <tbody>
                <?php $download_products_ids = []; ?>
                <?php foreach ($transactions_items as $item): ?>
                <?php if (isset($downloads[$item['product_id']]) && !in_array($item['product_id'], $download_products_ids)): ?>
                <tr>
                    <td class="img">
                        <?php if (!empty($item['img']) && file_exists($item['img'])): ?>
                        <img src="<?=base_url?><?=$item['img']?>" width="50" height="50" style="vertical-align: middle;"  alt="<?=$item['name']?>">
                        <?php endif; ?>
                    </td>
                    <td class="name"><?=$item['name']?></td>
                    <td>
                        <?php foreach ($downloads[$item['product_id']] as $download): ?>
                        <a href="<?=url('index.php?page=download&id=' . md5($item['txn_id'] . $download['id']))?>" download><i class="fa-solid fa-download fa-sm"></i><?=basename($download['file_path'])?></a>
                        <?php endforeach; ?>
                    </td>
                </tr>
                <?php $download_products_ids[] = $item['product_id']; ?>
                <?php endif; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>

    </div>
    <?php elseif ($tab == 'settings'): ?>
    <div class="settings">

        <h3 style="color:var(--clr-purp);">Settings</h3>

    
            <div class="credentials-box">
             <form action="" method="post">
 
             <label for="email">Email</label>
             <input id="email" type="email" name="email" placeholder="Email" value="<?=htmlspecialchars($account['email'], ENT_QUOTES)?>"     required>

             <label for="password">Password</label>
            <input  type="password" id="password" name="password" placeholder="New Password" value="" autocomplete="new-password"    >

            <label for="first_name">First Name</label>
            <input id="first_name" type="text" name="first_name" placeholder="Joe" value="<?=htmlspecialchars($account['first_name'], ENT_QUOTES)?>"    >

            <label for="last_name">Last Name</label>
            <input id="last_name" type="text" name="last_name" placeholder="Bloggs" value="<?=htmlspecialchars($account['last_name'], ENT_QUOTES)?>"    >

            <label for="address_street">Address</label>
            <input id="address_street" type="text" name="address_street" placeholder="24 High Street" value="<?=htmlspecialchars($account['address_street'], ENT_QUOTES)?>"    >

            <label for="address_city">City</label>
            <input id="address_city" type="text" name="address_city" placeholder="New York" value="<?=htmlspecialchars($account['address_city'], ENT_QUOTES)?>"    >

            <label for="address_state">State</label>
            <input id="address_state" type="text" name="address_state" placeholder="NY" value="<?=htmlspecialchars($account['address_state'], ENT_QUOTES)?>"    >

            <label for="address_zip">Zip</label>
            <input id="address_zip" type="text" name="address_zip" placeholder="10001" value="<?=htmlspecialchars($account['address_zip'], ENT_QUOTES)?>"    >

            <label for="address_country">Country</label>
            <select id="address_country" name="address_country" required    >
                <?php foreach(get_countries() as $country): ?>
                <option value="<?=$country?>"<?=$country==$account['address_country']?' selected':''?>><?=$country?></option>
                <?php endforeach; ?>
            </select>
 
                <button name="save_details" type="submit" class="button-colordot">
                            <span>SAVE</span>
                            <svg width="13px" height="10px" viewBox="0 0 13 10">
                                <path d="M1,5 L11,5"></path>
                                <polyline points="8 1 12 5 8 9"></polyline>
                            </svg>
                        </button>

        </form>

    </div>

    <?php endif; ?>

    <?php endif; ?>

</div>

<?=template_footer()?>