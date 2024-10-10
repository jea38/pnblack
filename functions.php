<?php
// Function that will connect to the MySQL database
function pdo_connect_mysql() {
    try {
        // Connect to the MySQL database using the PDO interface
    	$pdo = new PDO('mysql:host=' . db_host . ';dbname=' . db_name . ';charset=utf8', db_user, db_pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $exception) {
    	// Could not connect to the MySQL database! If you encounter this error, ensure your db settings are correct in the config file!
    	exit('Failed to connect to database!');
    }
}
// Function to retrieve a product from cart by the ID and options string
function &get_cart_product($id, $options) {
    $p = null;
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as &$product) {
            if ($product['id'] == $id && $product['options'] == $options) {
                $p = &$product;
                return $p;
            }
        }
    }
    return $p;
}
// Populate categories function
function populate_categories($categories, $selected = 0, $parent_id = 0, $n = 0) {
    $html = '';
    foreach ($categories as $category) {
        if ($parent_id == $category['parent_id']) {
            $html .= '<option value="' . $category['id'] . '"' . ($selected == $category['id']  ? ' selected' : '') . '>' . str_repeat('--', $n) . ' ' . $category['name'] . '</option>';
            $html .= populate_categories($categories, $selected, $category['id'], $n+1);
        }
    }
    return $html;
}
// Get country list
function get_countries() {
    return ["United States"];
}
// Send order details email function
function send_order_details_email($email, $products, $first_name, $last_name, $address_street, $address_city, $address_state, $address_zip, $address_country, $subtotal, $order_id) {
    // Send payment notification to webmaster
    if (email_notifications) {
        $subject = 'You have received a new order!';
        $headers = 'From: ' . mail_from . "\r\n" . 'Reply-To: ' . $email . "\r\n" . 'Return-Path: ' . mail_from . "\r\n" . 'X-Mailer: PHP/' . phpversion() . "\r\n" . 'MIME-Version: 1.0' . "\r\n" . 'Content-Type: text/html; charset=UTF-8' . "\r\n";
        ob_start();
        include 'order-notification-template.php';
        $order_notification_template = ob_get_clean();
        mail(email, $subject, $order_notification_template, $headers);
    }
    if (!mail_enabled) {
        return;
    }
	$subject = 'Order Details';
	$headers = 'From: ' . mail_from . "\r\n" . 'Reply-To: ' . mail_from . "\r\n" . 'Return-Path: ' . mail_from . "\r\n" . 'X-Mailer: PHP/' . phpversion() . "\r\n" . 'MIME-Version: 1.0' . "\r\n" . 'Content-Type: text/html; charset=UTF-8' . "\r\n";
    ob_start();
    include 'order-details-template.php';
    $order_details_template = ob_get_clean();
	mail($email, $subject, $order_details_template, $headers);
}



// Template header, feel free to customize this
function template_header($title, $head = '') {
// Get the amount of items in the shopping cart, this will be displayed in the header.
$num_items_in_cart = isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'quantity')) : 0;
$home_link = url('index.php');
$products_link = url('index.php?page=products');
$myaccount_link = url('index.php?page=myaccount');
$cart_link = url('index.php?page=cart');
$admin_link = isset($_SESSION['account_loggedin'], $_SESSION['account_role']) && $_SESSION['account_role'] == 'Admin' ? '<a href="' . base_url . 'admin/index.php" target="_blank">Admin</a>' : '';
$logout_link = isset($_SESSION['account_loggedin']) ? '<li><a style="color:var(--clr-sec-400);" href="' . url('index.php?page=logout') . '"><div class="svg-space" ><svg  style="fill:var(--clr-sec-400);" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 700 700" style="enable-background:new 0 0 512 512;" xml:space="preserve"><g><g><path d="M336.709,74.843c-10.322-3.944-21.88,1.219-25.825,11.537c-3.945,10.316,1.22,21.879,11.536,25.824 C393.115,139.239,442,207.384,442,286c0,102.561-83.439,186-186,186S70,388.561,70,286c0-78.659,48.908-146.766,119.573-173.793 c10.317-3.946,15.481-15.509,11.536-25.825c-3.947-10.317-15.512-15.48-25.825-11.536C89.185,107.777,30,190.692,30,286  c0,124.922,101.09,226,226,226c124.922,0,226-101.09,226-226C482,190.65,422.778,107.759,336.709,74.843z" /></g></g><g><g><path d="M256,0c-11.046,0-20,8.954-20,20v195.851c0,11.046,8.954,20,20,20s20-8.955,20-20V20C276,8.954,267.046,0,256,0z" /></g></g></svg></div>Sign Out</a></li>' : '';
$site_name = site_name;
$base_url = base_url;




// DO NOT INDENT THE BELOW CODE
echo <<<EOT
<!DOCTYPE html>
<html lang="en-US">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta content="IE=edge" http-equiv="X-UA-Compatible">
    <meta name="x-apple-disable-message-reformatting">
    <meta name="viewport" content="width=device-width, initial-scale=0.86, maximum-scale=3.0, minimum-scale=0.86">
    
    <!-- Basic Information -->
    <title>$title</title>
   <link rel="icon" type="image/png" href=" favicon.png">
    <meta name="description" content="Discover a range of products and stay tuned for more.">

    <!-- Robots Meta -->
    <meta name="robots" content="max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    
    <!-- Verification -->
    <meta name="google-site-verification" content="Your-Google-Verification-Code">
    <meta name="msvalidate.01" content="Your-Bing-Verification-Code">
    
    <!-- Canonical Link -->
    <link rel="canonical" href=" ">

    <!-- Open Graph / Facebook -->
    <meta property="og:locale" content="en_US">
    <meta property="og:site_name" content="$site_name">
    <meta property="og:type" content="website">
    <meta property="og:title" content="$site_name">
    <meta property="og:description" content="Discover a range of products and stay tuned for more.">
    <meta property="og:url" content=" ">
    <meta property="fb:app_id" content="Your-Facebook-App-ID">
    <meta property="og:image" content="https://pnblack.azurewebsites.net/shared/featured-image.jpg">
    <meta property="og:image:secure_url" content="https://pnblack.azurewebsites.net/shared/featured-image.jpg">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="$site_name">
    <meta name="twitter:description" content="Discover a range of products and stay tuned for more.">
    <meta name="twitter:image" content="https://pnblack.azurewebsites.net/shared/featured-image.jpg">

    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,400i,600,600i,700,700i,900,900i" rel="stylesheet">
    <link rel='stylesheet' href=' css/main.css'>
    <link rel='stylesheet' href=' css/pnblack.css'>
    <link rel='stylesheet' href=' css/2.css'>
    <link rel='stylesheet' href=' css/p_view.css'>
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css'>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css'>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/Swiper/9.3.1/swiper-bundle.min.js'></script>

    <meta http-equiv="refresh" content="4000;url=https://pnblack.azurewebsites.net/logout" />

    $head
</head>

<body>
    <header>
        <nav class="container">
          <div id="nav-logo">
            <img class="nlogo" src=" shared/pnbf22.png">
          </div>
         
          <div class="hamburger">
            <div class="hamburger--container">
                <div class="hamburger--bars">
    
                </div>
            </div>
        </div>
        </nav>
      </header>
      
      <main>

        <div class="fsmenu">
            <div class="fsmenu--container">
                <div class="fsmenu--text-block">
                    <div class="fsmenu--text-container">
                        <ul class="fsmenu--list">
                            <li class="fsmenu--list-element">
                                <a href="$home_link">
                                    <span>Home</span>
                                </a>
                                <div class="fsmenu--scrolling-text">
                                    <span>Home</span><span>Home</span><span>Home</span><span>Home</span><span>Home</span>
                                </div>
                                <div class="fsmenu--link-img">
                                    <div class="fsmenu--img-container">
                                        <!--<img src="https://witwinkel.ch/themes/witwinkel/assets/projects/gourmet-festival-2019/content12.jpg">-->
                                    </div>
                                </div>
                            </li>
                            <li class="fsmenu--list-element">
                                <a href="$products_link">
                                    <span>Store</span>
                                </a>
                                <div class="fsmenu--scrolling-text">
                                    <span>Store</span><span>Store</span><span>Store</span><span>Store</span><span>Store</span>
                                </div>
                                <div class="fsmenu--link-img">
                                    <div class="fsmenu--img-container">
                                        <!--<img src="https://witwinkel.ch/themes/witwinkel/assets/shared/content/WITWINKEL-buero-albisrieden-2019.jpg">-->
                                    </div>
                                </div>
                            </li>
                            <li class="fsmenu--list-element">
                                <a href=" info/inu.php">
                                    <span>Info & Updates</span>
                                </a>
                                <div class="fsmenu--scrolling-text">
                                    <span>Info & Updates</span><span>Info & Updates</span><span>Info & Updates</span><span>Info & Updates</span><span>Info & Updates</span>
                                </div>
                                <div class="fsmenu--link-img">
                                    <div class="fsmenu--img-container">
                                        <!--<img src="https://witwinkel.ch/themes/witwinkel/assets/projects/gourmet-festival-2019/content12.jpg">-->
                                    </div>
                                </div>
                            </li>
                            <li class="fsmenu--list-element">
                                <a href=" lvs/contact.php">
                                    <span>Contact Us</span>
                                </a>
                                <div class="fsmenu--scrolling-text">
                                    <span>Contact Us</span><span>Contact Us</span><span>Contact Us</span><span>Contact Us</span><span>Contact Us</span>
                                </div>
                                <div class="fsmenu--link-img">
                                    <div class="fsmenu--img-container">
                                        <!--<img src="https://witwinkel.ch/themes/witwinkel/assets/shared/team/wirsind-witwinkel.jpg">-->
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
      
        <div class="enav">
        <ul>

         
      <li>
          <a href="$home_link" id="homelink">
              <div class="svg-space">
                  <svg id="Layer_1" enable-background="new 0 0 700 700" height="512" viewBox="0 0 700 700" width="512" xmlns="http://www.w3.org/2000/svg"><path d="m506.096 264.697c-3.901 3.875-8.999 5.812-14.096 5.812-5.141 0-10.279-1.969-14.188-5.904l-15.812-15.917v72.82c0 11.046-8.954 20-20 20s-20-8.954-20-20v-113.083l-152.107-153.11c-7.464-6.394-18.454-6.418-25.947-.055l-153.975 153.671c.006.193.029.382.029.577v212c0 22.056 17.944 40 40 40h252c22.056 0 40-17.944 40-40 0-11.046 8.954-20 20-20s20 8.954 20 20c0 44.112-35.888 80-80 80h-252c-44.112 0-80-35.888-80-80v-172.684l-15.872 15.841c-7.817 7.802-20.48 7.79-28.284-.028-7.803-7.818-7.79-20.481.028-28.285 0 0 210.834-210.404 211.118-210.657 22.842-20.336 57.227-20.262 79.982.169.282.254 209.216 210.549 209.216 210.549 7.786 7.836 7.744 20.499-.092 28.284zm-307.102 63.306c-10.146-12.411-16.244-28.252-16.244-45.495 0-39.701 32.299-72 72-72s72 32.299 72 72c0 17.337-6.161 33.261-16.406 45.701 14.518 6.944 27.864 16.58 39.189 28.559 7.588 8.026 7.233 20.685-.793 28.273-3.865 3.654-8.807 5.467-13.736 5.467-5.308 0-10.604-2.099-14.537-6.26-17.014-17.996-41.487-28.74-65.467-28.74-24.938 0-48.8 10.111-65.467 27.74-7.588 8.025-20.245 8.383-28.273.793-8.026-7.588-8.381-20.247-.793-28.273 10.996-11.63 24.117-20.997 38.527-27.765zm23.756-45.495c0 17.645 14.355 32 32 32s32-14.355 32-32-14.355-32-32-32-32 14.355-32 32z" /></svg>
              </div>
              Home
          </a>
      </li>
      <li>
          <a href="#">
              <div class="svg-space">
                  <svg id="regular" enable-background="new 0 0 24 24" height="512" viewBox="0 0 26 26" width="512" xmlns="http://www.w3.org/2000/svg"><g><path d="m14.25 21h-4.5c-.414 0-.75-.336-.75-.75s.336-.75.75-.75h4.5c.414 0 .75.336.75.75s-.336.75-.75.75z" /></g><g><path d="m12 3.457c-.414 0-.75-.336-.75-.75v-1.957c0-.414.336-.75.75-.75s.75.336.75.75v1.957c0 .414-.336.75-.75.75z" /></g><g><path d="m18.571 6.179c-.192 0-.384-.073-.53-.22-.293-.293-.293-.768 0-1.061l1.384-1.384c.293-.293.768-.293 1.061 0s.293.768 0 1.061l-1.384 1.384c-.147.146-.339.22-.531.22z" /></g><g><path d="m23.25 12.75h-1.957c-.414 0-.75-.336-.75-.75s.336-.75.75-.75h1.957c.414 0 .75.336.75.75s-.336.75-.75.75z" /></g><g><path d="m19.955 20.705c-.192 0-.384-.073-.53-.22l-1.384-1.384c-.293-.293-.293-.768 0-1.061s.768-.293 1.061 0l1.384 1.384c.293.293.293.768 0 1.061-.147.147-.339.22-.531.22z" /></g><g><path d="m4.045 20.705c-.192 0-.384-.073-.53-.22-.293-.293-.293-.768 0-1.061l1.384-1.384c.293-.293.768-.293 1.061 0s.293.768 0 1.061l-1.384 1.384c-.147.147-.339.22-.531.22z" /></g><g><path d="m2.707 12.75h-1.957c-.414 0-.75-.336-.75-.75s.336-.75.75-.75h1.957c.414 0 .75.336.75.75s-.336.75-.75.75z" /></g><g><path d="m5.429 6.179c-.192 0-.384-.073-.53-.22l-1.384-1.384c-.293-.293-.293-.768 0-1.061s.768-.293 1.061 0l1.384 1.384c.293.293.293.768 0 1.061-.148.146-.339.22-.531.22z" /></g><g><path d="m15.75 12.5c-.414 0-.75-.336-.75-.75 0-1.517-1.233-2.75-2.75-2.75-.414 0-.75-.336-.75-.75s.336-.75.75-.75c2.343 0 4.25 1.907 4.25 4.25 0 .414-.336.75-.75.75z" /></g><g><path d="m13.25 24h-2.5c-.843 0-1.75-.64-1.75-2.044v-1.764c0-1.061-.452-2.035-1.209-2.605-2.185-1.645-3.196-4.351-2.639-7.062.545-2.656 2.694-4.813 5.347-5.368 2.109-.443 4.268.07 5.914 1.408 1.644 1.336 2.587 3.317 2.587 5.435 0 2.158-.975 4.161-2.675 5.498-.842.662-1.325 1.584-1.325 2.529v2.223c0 .965-.785 1.75-1.75 1.75zm-1.264-17.498c-.392 0-.787.041-1.182.123-2.076.434-3.757 2.122-4.184 4.201-.439 2.137.355 4.269 2.072 5.562 1.131.852 1.807 2.274 1.807 3.804v1.764c0 .091.012.544.25.544h2.5c.138 0 .25-.112.25-.25v-2.223c0-1.409.692-2.76 1.898-3.709 1.337-1.05 2.103-2.624 2.103-4.318 0-1.664-.742-3.221-2.034-4.271-.988-.802-2.213-1.227-3.48-1.227z" /></g></svg>
              </div>
              Coming Soon
          </a>
      </li>
      <li>
          <a href="$products_link" id="productslink">
              <div class="svg-space">
                  <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 600 600" style="enable-background:new 0 0 500 500;" xml:space="preserve">
                  <g><g><path d="M470.223,0.561h-89.7c-9.4,0-16.7,6.3-19.8,14.6l-83.4,263.8h-178.3l-50-147h187.7c11.5,0,20.9-9.4,20.9-20.9 s-9.4-20.9-20.9-20.9h-215.9c-18.5,0.9-23.2,18-19.8,26.1l63.6,189.7c3.1,8.3,11.5,13.6,19.8,13.6h207.5c9.4,0,17.7-5.2,19.8-13.6
                         l83.4-263.8h75.1c11.5,0,20.9-9.4,20.9-20.9S481.623,0.561,470.223,0.561z" />
                  <path d="M103.223,357.161c-36.5,0-66.7,30.2-66.7,66.7s30.2,66.7,66.7,66.7s66.7-30.2,66.7-66.7S139.723,357.161,103.223,357.161z
                          M128.223,424.861c0,14.6-11.5,26.1-25,26.1c-13.6,0-25-11.5-25-26.1s11.5-26.1,25-26.1
                         C117.823,398.861,129.323,410.261,128.223,424.861z" />
                  <path d="M265.823,357.161c-36.5,0-66.7,30.2-66.7,66.7s30.2,66.7,66.7,66.7c37.5,0,66.7-30.2,66.7-66.7
                         C332.623,387.361,302.323,357.161,265.823,357.161z M290.923,424.861c0,14.6-11.5,26.1-25,26.1c-13.5,0-25-11.5-25-26.1
                         s11.5-26.1,25-26.1C280.423,398.861,291.923,410.261,290.923,424.861z" /></g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g></svg>
              </div>
              Store
          </a>
      </li>
      <li>
          <a href="$myaccount_link" id="myaccountlink">
              <div class="svg-space"><svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 700 700" style="enable-background:new 0 0 512 512;" xml:space="preserve"><g><g>	<g><path d="M496.659,312.128l-47.061-36.8c0.619-5.675,1.109-12.309,1.109-19.328s-0.512-13.653-1.109-19.328l47.104-36.821	c8.747-6.912,11.157-19.179,5.568-29.397l-48.939-84.672c-5.355-9.749-17.365-14.208-28.309-10.176l-55.531,22.293	c-10.624-7.68-21.781-14.165-33.323-19.349l-8.448-58.901C326.334,8.448,316.606,0,305.107,0h-98.133 c-11.499,0-21.227,8.448-22.592,19.435l-8.469,59.115c-11.179,5.056-22.165,11.435-33.28,19.371l-55.68-22.357	c-10.645-4.16-22.763,0.235-28.096,10.005l-49.003,84.8c-5.781,9.771-3.413,22.443,5.547,29.525l47.061,36.779	c-0.747,7.211-1.109,13.461-1.109,19.328s0.363,12.117,1.067,19.328l-47.104,36.843C6.59,319.083,4.2,331.349,9.768,341.568	l48.939,84.672c5.312,9.728,17.301,14.165,28.309,10.176l55.531-22.293c10.624,7.659,21.803,14.144,33.344,19.349l8.448,58.88 c1.387,11.2,11.115,19.648,22.613,19.648h98.133c11.499,0,21.227-8.448,22.592-19.435l8.469-59.093	c11.179-5.056,22.165-11.435,33.28-19.371l55.68,22.357c10.603,4.117,22.763-0.235,28.096-10.005l49.195-85.099	C507.838,331.371,505.448,319.104,496.659,312.128z M483.752,330.901l-50.816,85.717l-61.077-24.533 c-3.456-1.387-7.381-0.853-10.368,1.365c-13.227,9.899-26.005,17.344-39.104,22.699c-3.499,1.429-5.995,4.608-6.528,8.363 l-10.773,66.155l-99.563-1.131l-9.323-65.003c-0.555-3.755-3.029-6.933-6.528-8.363c-13.632-5.589-26.752-13.205-39.019-22.635 c-1.899-1.472-4.203-2.219-6.507-2.219c-1.344,0-2.688,0.235-3.989,0.768l-62.827,23.701l-48.939-84.672	c-0.448-0.832-0.363-1.792,0.149-2.197l51.776-40.469c2.944-2.304,4.48-6.016,4.011-9.728c-1.131-8.939-1.643-16.171-1.643-22.72 s0.533-13.76,1.643-22.72c0.469-3.733-1.067-7.424-4.011-9.728L28.286,181.12l50.816-85.717l61.077,24.533 c3.477,1.408,7.381,0.875,10.389-1.365c13.205-9.92,26.005-17.344,39.104-22.699c3.499-1.451,5.973-4.629,6.507-8.363 l10.795-66.176l99.584,1.152l9.301,65.024c0.555,3.755,3.029,6.933,6.528,8.363c13.611,5.568,26.731,13.184,39.019,22.635 c3.008,2.304,6.955,2.859,10.475,1.429l62.827-23.701l48.939,84.672c0.448,0.832,0.363,1.771-0.149,2.176l-51.776,40.469	c-2.944,2.304-4.48,5.995-4.011,9.728c0.811,6.485,1.643,14.272,1.643,22.72c0,8.469-0.832,16.235-1.643,22.72	c-0.469,3.712,1.067,7.424,4.011,9.728l51.712,40.448C483.987,329.344,484.094,330.304,483.752,330.901z" />	<path d="M256.019,149.333c-58.816,0-106.667,47.851-106.667,106.667s47.851,106.667,106.667,106.667 c58.816,0,106.667-47.851,106.667-106.667S314.835,149.333,256.019,149.333z M256.019,341.333 c-47.061,0-85.333-38.272-85.333-85.333s38.272-85.333,85.333-85.333c47.061,0,85.333,38.272,85.333,85.333	S303.08,341.333,256.019,341.333z" /></g></g></g></svg>                            </div>
              Account
          </a>
      </li>

      
     $logout_link


     <li class="cart_link">
     <a href="$cart_link">
     <span style="color: var(--clr-light);">$num_items_in_cart</span>
         <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 600 600" style="enable-background:new 0 0 500 500;" xml:space="preserve">
         <g><g><path d="M470.223,0.561h-89.7c-9.4,0-16.7,6.3-19.8,14.6l-83.4,263.8h-178.3l-50-147h187.7c11.5,0,20.9-9.4,20.9-20.9 s-9.4-20.9-20.9-20.9h-215.9c-18.5,0.9-23.2,18-19.8,26.1l63.6,189.7c3.1,8.3,11.5,13.6,19.8,13.6h207.5c9.4,0,17.7-5.2,19.8-13.6
                l83.4-263.8h75.1c11.5,0,20.9-9.4,20.9-20.9S481.623,0.561,470.223,0.561z" />
         <path d="M103.223,357.161c-36.5,0-66.7,30.2-66.7,66.7s30.2,66.7,66.7,66.7s66.7-30.2,66.7-66.7S139.723,357.161,103.223,357.161z
                 M128.223,424.861c0,14.6-11.5,26.1-25,26.1c-13.6,0-25-11.5-25-26.1s11.5-26.1,25-26.1
                C117.823,398.861,129.323,410.261,128.223,424.861z" />
         <path d="M265.823,357.161c-36.5,0-66.7,30.2-66.7,66.7s30.2,66.7,66.7,66.7c37.5,0,66.7-30.2,66.7-66.7
                C332.623,387.361,302.323,357.161,265.823,357.161z M290.923,424.861c0,14.6-11.5,26.1-25,26.1c-13.5,0-25-11.5-25-26.1
                s11.5-26.1,25-26.1C280.423,398.861,291.923,410.261,290.923,424.861z" /></g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g></svg>
                </a>
 </li>
 <a>
                   $admin_link
                </a>
                   </ul>
                   </div>
                   
EOT;
}

// Template footer
function template_footer() {
$base_url = base_url;
$rewrite_url = rewrite_url ? 'true' : 'false';
$year = date('Y');
$currency_code = currency_code;
// DO NOT INDENT THE BELOW CODE
echo <<<EOT

<a href="#" id="scroll" style="display: none;">
          <span></span>
          <script>
              $(document).ready(function () {
                  $(window).scroll(function () {
                      if ($(this).scrollTop() > 100) {
                          $('#scroll').fadeIn();
                      } else {
                          $('#scroll').fadeOut();
                      }
                  });
                  $('#scroll').click(function () {
                      $("html, body").animate({ scrollTop: 0 }, 600);
                      return false;
                  });
              });  
              </script>
      </a>
      </main>
      
      <footer>
        <section id="footer" class="line py-2">
          <div class="container">
            <div id="nav-logo">
                <img class="nlogo" src=" shared/pnbf22.png">
             <div class="social-icon">
                <a href="#"><i class="fa-brands fa-telegram"></i></a>
                <a href="#"><i class="fa-brands fa-instagram"></i></a>
                <a href="#"><i class="fa-brands fa-twitter"></i></a>
              </div>
            </div>
          </div>
      
        </section>
        <h5 class="copy-tag py-2">Copyright &#169 
        $year PnBlack. <a href=" legal">All Rights Reserved.</a>
        </h5>
      </footer>

      <script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.19.1/trumbowyg.min.js"></script>
<script>
    $('#description').trumbowyg();
</script>
<script>
const currency_code = "$currency_code", base_url = "$base_url", rewrite_url = $rewrite_url;
</script>
<script src=" script.js"></script>
<script src=" js/p_view.js"></script>
<script src=" js/main.js"></script>

</body>
</html>
EOT;
}
// Template admin header
function template_admin_header($title, $selected = 'orders', $selected_child = 'view') {
    $admin_links = '
        <a href="index.php?page=dashboard"' . ($selected == 'dashboard' ? ' class="selected"' : '') . '><i class="fas fa-tachometer-alt"></i>Dashboard</a>
        <a href="index.php?page=orders"' . ($selected == 'orders' ? ' class="selected"' : '') . '><i class="fas fa-shopping-cart"></i>Orders</a>
        <div class="sub">
            <a href="index.php?page=orders"' . ($selected == 'orders' && $selected_child == 'view' ? ' class="selected"' : '') . '><span>&#9724;</span>View Orders</a>
            <a href="index.php?page=order_manage"' . ($selected == 'orders' && $selected_child == 'manage' ? ' class="selected"' : '') . '><span>&#9724;</span>Create Order</a>
        </div>
        <a href="index.php?page=products"' . ($selected == 'products' ? ' class="selected"' : '') . '><i class="fas fa-box-open"></i>Products</a>
        <div class="sub">
            <a href="index.php?page=products"' . ($selected == 'products' && $selected_child == 'view' ? ' class="selected"' : '') . '><span>&#9724;</span>View Products</a>
            <a href="index.php?page=product"' . ($selected == 'products' && $selected_child == 'manage' ? ' class="selected"' : '') . '><span>&#9724;</span>Create Product</a>
        </div>
        <a href="index.php?page=categories"' . ($selected == 'categories' ? ' class="selected"' : '') . '><i class="fas fa-list"></i>Categories</a>
        <div class="sub">
            <a href="index.php?page=categories"' . ($selected == 'categories' && $selected_child == 'view' ? ' class="selected"' : '') . '><span>&#9724;</span>View Categories</a>
            <a href="index.php?page=category"' . ($selected == 'categories' && $selected_child == 'manage' ? ' class="selected"' : '') . '><span>&#9724;</span>Create Category</a>
        </div>
        <a href="index.php?page=accounts"' . ($selected == 'accounts' ? ' class="selected"' : '') . '><i class="fas fa-users"></i>Accounts</a>
        <div class="sub">
            <a href="index.php?page=accounts"' . ($selected == 'accounts' && $selected_child == 'view' ? ' class="selected"' : '') . '><span>&#9724;</span>View Accounts</a>
            <a href="index.php?page=account"' . ($selected == 'accounts' && $selected_child == 'manage' ? ' class="selected"' : '') . '><span>&#9724;</span>Create Account</a>
        </div>
        <a href="index.php?page=shipping"' . ($selected == 'shipping' ? ' class="selected"' : '') . '><i class="fas fa-shipping-fast"></i>Shipping</a>
        <div class="sub">
            <a href="index.php?page=shipping"' . ($selected == 'shipping' && $selected_child == 'view' ? ' class="selected"' : '') . '><span>&#9724;</span>View Shipping Methods</a>
            <a href="index.php?page=shipping_process"' . ($selected == 'shipping' && $selected_child == 'manage' ? ' class="selected"' : '') . '><span>&#9724;</span>Create Shipping Method</a>
        </div>
        <a href="index.php?page=discounts"' . ($selected == 'discounts' ? ' class="selected"' : '') . '><i class="fas fa-tag"></i>Discounts</a>
        <div class="sub">
            <a href="index.php?page=discounts"' . ($selected == 'discounts' && $selected_child == 'view' ? ' class="selected"' : '') . '><span>&#9724;</span>View Discounts</a>
            <a href="index.php?page=discount"' . ($selected == 'discounts' && $selected_child == 'manage' ? ' class="selected"' : '') . '><span>&#9724;</span>Create Discount</a>
        </div>
        <a href="index.php?page=taxes"' . ($selected == 'taxes' ? ' class="selected"' : '') . '><i class="fa-solid fa-percent"></i>Taxes</a>
        <div class="sub">
            <a href="index.php?page=taxes"' . ($selected == 'taxes' && $selected_child == 'view' ? ' class="selected"' : '') . '><span>&#9724;</span>View Taxes</a>
            <a href="index.php?page=tax"' . ($selected == 'taxes' && $selected_child == 'manage' ? ' class="selected"' : '') . '><span>&#9724;</span>Create Tax</a>
        </div>
        <a href="index.php?page=media"' . ($selected == 'media' ? ' class="selected"' : '') . '><i class="fas fa-images"></i>Media</a>
        <a href="index.php?page=emailtemplates"' . ($selected == 'emailtemplates' ? ' class="selected"' : '') . '><i class="fas fa-envelope"></i>Email Templates</a>
        <a href="index.php?page=settings"' . ($selected == 'settings' ? ' class="selected"' : '') . '><i class="fas fa-tools"></i>Settings</a>
    ';
// DO NOT INDENT THE BELOW CODE
echo <<<EOT
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width,minimum-scale=1">
   <meta http-equiv="refresh" content="4000;url=index.php?page=logout" />
		<title>$title</title>
       <link rel="icon" type="image/png" href=" favicon.png">
		<link href="admin.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.0.0/css/all.css">
	</head>
	<body class="admin">
        <aside class="responsive-width-100 responsive-hidden">
            <h1>PnBlack Admin</h1>
            $admin_links
            <div class="footer">
                <a href="https://codeshack.io/package/php/advanced-shopping-cart-system/" target="_blank">Advanced Shopping Cart</a>
                Version 2.0.1
            </div>
        </aside>
        <main class="responsive-width-100">
            <header>
                <a class="responsive-toggle" href="#">
                    <i class="fas fa-bars"></i>
                </a>
                <div class="space-between"></div>
                <div class="dropdown right">
                    <i class="fas fa-user-circle"></i>
                    <div class="list">
                        <a href="index.php?page=account&id={$_SESSION['account_id']}">Edit Profile</a>
                        <a href="index.php?page=logout">Logout</a>
                    </div>
                </div>
            </header>
EOT;
}
// Template admin footer
function template_admin_footer($js_script = '') {
        $js_script = $js_script ? '<script>' . $js_script . '</script>' : '';
// DO NOT INDENT THE BELOW CODE
echo <<<EOT
        </main>
        <script src="admin.js"></script>
        {$js_script}
    </body>
</html>
EOT;
}
// Determine URL function
function url($url) {
    if (rewrite_url) {
        $url = preg_replace('/\&(.*?)\=/', '/', str_replace(['index.php?page=', 'index.php'], '', $url));
    }
    return base_url . $url;
}
// Routeing function
function routes($urls) {
    foreach ($urls as $url => $file_path) {
        $url = '/' . ltrim($url, '/');
        $prefix = dirname($_SERVER['PHP_SELF']);
        $uri = $_SERVER['REQUEST_URI'];
        if (substr($uri, 0, strlen($prefix)) == $prefix) {
            $uri = substr($uri, strlen($prefix));
        }
        $uri = '/' . ltrim($uri, '/');
        $path = explode('/', parse_url($uri)['path']);
        $routes = explode('/', $url);
        $values = [];
        foreach ($path as $pk => $pv) {
            if (isset($routes[$pk]) && preg_match('/{(.*?)}/', $routes[$pk])) {
                $var = str_replace(['{','}'], '', $routes[$pk]);
                $routes[$pk] = preg_replace('/{(.*?)}/', $pv, $routes[$pk]);
                $values[$var] = $pv;
            }
        }
        if ($routes === $path && rewrite_url) {
            foreach ($values as $k => $v) {
                $_GET[$k] = $v;
            }
            return file_exists($file_path) ? $file_path : 'home.php';
        }
    }
    if (rewrite_url) {
        header('Location: ' . url('index.php'));
        exit;
    }
    return null;
}
// Format bytes to human-readable format
function format_bytes($bytes) {
    $i = floor(log($bytes, 1024));
    return round($bytes / pow(1024, $i), [0,0,2,2,3][$i]).['B','KB','MB','GB','TB'][$i];
}
?>