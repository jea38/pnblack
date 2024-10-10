<?php
// Prevent direct access to file
defined('pnblack') or exit;
// Check to make sure the id parameter is specified in the URL
if (isset($_GET['id'])) {
    // Prepare statement and execute, prevents SQL injection
    $stmt = $pdo->prepare('SELECT * FROM products WHERE status = 1 AND (id = ? OR url_slug = ?)');
    $stmt->execute([ $_GET['id'], $_GET['id'] ]);
    // Fetch the product from the database and return the result as an Array
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    // Check if the product exists (array is not empty)
    if (!$product) {
        // Output simple error if the id for the product doesn't exists (array is empty)
        http_response_code(404);
        exit('Product does not exist!');
    }
    // Select the product images (if any) from the products_images table
    $stmt = $pdo->prepare('SELECT m.*, pm.position FROM products_media pm JOIN media m ON m.id = pm.media_id WHERE pm.product_id = ? ORDER BY pm.position ASC');
    $stmt->execute([ $product['id'] ]);
    // Fetch the product images from the database and return the result as an Array
    $product_media = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // Select the product options (if any) from the products_options table
    $stmt = $pdo->prepare('SELECT CONCAT(title, "::", type, "::", required) AS k, name, quantity, price, price_modifier, weight, weight_modifier, type, required FROM products_options WHERE product_id = ? ORDER BY position ASC');
    $stmt->execute([ $product['id'] ]);
    // Fetch the product options from the database and return the result as an Array
    $product_options = $stmt->fetchAll(PDO::FETCH_GROUP);
    // Add the HTML meta data (for SEO purposes)
    $meta = '
        <meta property="og:url" content="' . url('index.php?page=product&id=' . ($product['url_slug'] ? $product['url_slug']  : $product['id'])) . '">
        <meta property="og:title" content="' . $product['name'] . '">
    ';
    if (isset($product_media[0]) && file_exists($product_media[0]['full_path'])) {
        $meta .= '<meta property="og:image" content="' . base_url . $product_media[0]['full_path'] . '">';
    }
    // If the user clicked the add to cart button
    if (isset($_POST['quantity']) && is_numeric($_POST['quantity'])) {
        // abs() function will prevent minus quantity and (int) will ensure the value is an integer (number)
        $quantity = abs((int)$_POST['quantity']);
        // Get product options
        $options = '';
        $options_price = (float)$product['price'];
        $options_weight = (float)$product['weight'];
        // Iterate post data
        foreach ($_POST as $k => $v) {
            if (strpos($k, 'option-') !== false) {
                if (is_array($v)) {
                    // Option is checkbox or radio element
                    foreach ($v as $vv) {
                        if (empty($vv)) continue;
                        $options .= str_replace(['_', 'option-'], [' ', ''], $k) . '-' . $vv . ',';
                        $stmt = $pdo->prepare('SELECT * FROM products_options WHERE title = ? AND name = ? AND product_id = ?');
                        $stmt->execute([ str_replace(['_', 'option-'], [' ', ''], $k), $vv, $product['id'] ]);
                        $option = $stmt->fetch(PDO::FETCH_ASSOC);
                        $options_price = $option['price_modifier'] == 'add' ? $options_price + $option['price'] : $options_price - $option['price'];
                        $options_weight = $option['weight_modifier'] == 'add' ? $options_weight + $option['weight'] : $options_weight - $option['weight'];
                    }
                } else {
                    if (empty($v)) continue;
                    $options .= str_replace(['_', 'option-'], [' ', ''], $k) . '-' . $v . ',';
                    $stmt = $pdo->prepare('SELECT * FROM products_options WHERE title = ? AND name = ? AND product_id = ?');
                    $stmt->execute([ str_replace(['_', 'option-'], [' ', ''], $k), $v, $product['id'] ]);
                    $option = $stmt->fetch(PDO::FETCH_ASSOC);
                    if (!$option) {
                        // Option is text or datetime element
                        $stmt = $pdo->prepare('SELECT * FROM products_options WHERE title = ? AND product_id = ?');
                        $stmt->execute([ str_replace(['_', 'option-'], [' ', ''], $k), $product['id'] ]);
                        $option = $stmt->fetch(PDO::FETCH_ASSOC);                              
                    }
                    $options_price = $option['price_modifier'] == 'add' ? $options_price + $option['price'] : $options_price - $option['price'];
                    $options_weight = $option['weight_modifier'] == 'add' ? $options_weight + $option['weight'] : $options_weight - $option['weight'];
                }
            }
        }
        $options_price = $options_price < 0 ? 0 : $options_price;
        $options = rtrim($options, ',');
        // Check if the product exists (array is not empty)
        if ($quantity > 0) {
            // Product exists in database, now we can create/update the session variable for the cart
            if (!isset($_SESSION['cart'])) {
                // Shopping cart session variable doesnt exist, create it
                $_SESSION['cart'] = [];
            }
            $cart_product = &get_cart_product($product['id'], $options);
            if ($cart_product) {
                // Product exists in cart, update the quanity
                $cart_product['quantity'] += $quantity;
            } else {
                // Product is not in cart, add it
                $_SESSION['cart'][] = [
                    'id' => $product['id'],
                    'quantity' => $quantity,
                    'options' => $options,
                    'options_price' => $options_price,
                    'options_weight' => $options_weight,
                    'shipping_price' => 0.00
                ];
            }
        }
        // Prevent form resubmission...
        header('Location: ' . url('index.php?page=cart'));
        exit;
    }
} else {
    // Output simple error if the id wasn't specified
    http_response_code(404);
    exit('Product does not exist!');
}
?>
<?=template_header($product['name'], $meta)?>
<script>
  let productslink = document.querySelectorAll("[id='productslink']");

for(var i = 0; i < productslink.length; i++){
  productslink.item(i).classList.add('active');
} 
</script>

     <a href="<?=url('index.php?page=products')?>">
                  <button class="button-colordot bcalt">                 
                            <span><i class='fas fa-angle-double-left'></i>RETURN</span>
                        </button>
            </a>


<?php if ($error): ?>

<p class="content-wrapper error"><?=$error?></p>

<?php else: ?>
            <div class="pcontainer">
                <div class="single-product">
                    <div class="prow">

                        <div class="col-6">
                             <div class="product-image">
                                <?php if (isset($product_media[0]) && file_exists($product_media[0]['full_path'])): ?>
                                <div class="product-image-main">
                                 <img src="<?=base_url . $product_media[0]['full_path']?>" alt="<?=$product_media[0]['caption']?>" id="product-main-image">
                                </div>
                                 <?php endif; ?>

                                 
                                <div class="product-image-slider">
                                <?php foreach ($product_media as $media): ?>
                                <div class="<?=$media['position']==1?' selected':''?>">
                                 <img src="<?=base_url . $media['full_path'] ?>" class="image-list" alt="<?=$media['caption']?>">
                                 </div>
                                 <?php endforeach; ?>
                           </div>

                            </div>
                        </div>



                        <div class="col-6">

                             <div class="product">
                                <div class="product-title">
                                  <h3 class="name"><?=$product['name']?></h3>
                                </div>
                                <div class="product-price">
                                     <span class="price offer-price" data-price="<?=$product['price']?>"><?=currency_code?><?=number_format($product['price'],2)?></span>
                                        <?php if ($product['rrp'] > 0): ?>
                                        <span class="rrp sale-price"><?=currency_code?><?=number_format($product['rrp'],2)?></span>
                                        <?php endif; ?>
                                </div>
                                <div class="product-details">        
                                    <?=$product['description']?>
                                 </div>



              <form id="product-form" action="" method="post">
            <?php foreach ($product_options as $id => $option): ?>
            <?php $id = explode('::', $id); ?>
            <?php if ($id[1] == 'select'): ?>
            <div class="credentials-box">
            <label for="<?=$id[0]?>"><?=$id[0]?></label>
            <select style="background:#000;" id="<?=$id[0]?>" class="option select" name="option-<?=$id[0]?>"<?=$id[2] ? ' required' : ''?>>
                <option value="" selected disabled style="display:none"><?=$id[0]?></option>
                <?php foreach ($option as $option_value): ?>
                <option value="<?=$option_value['name']?>" data-price="<?=$option_value['price']?>" data-modifier="<?=$option_value['price_modifier']?>"<?=$option_value['quantity']==0?' disabled':''?>><?=$option_value['name']?></option>
                <?php endforeach; ?>
            </select>
            </div>

                       <?php elseif ($id[1] == 'radio'): ?>
                       <label class="cbxlabel"><?=$id[0]?></label>
                       <div class="radio-checkbox">
                         <?php foreach ($option as $n => $option_value): ?>
                        <label>
                    <input class="option radio" value="<?=$option_value['name']?>" name="option-<?=$id[0]?>" type="radio" data-price="<?=$option_value['price']?>" data-modifier="<?=$option_value['price_modifier']?>"<?=$id[2] && $n == 0 ? ' required' : ''?><?=$option_value['quantity']==0?' disabled':''?>>
                        <span class="rdo"></span>
                        <span><p><?=$option_value['name']?></p></span>
                      </label>
                       <?php endforeach; ?>
                       </div>



            <?php elseif ($id[1] == 'checkbox'): ?>
            <label class="cbxlabel"><?=$id[0]?></label>
                 <div class="radio-checkbox">
                   <?php foreach ($option as $n => $option_value): ?>
              <label>
                 <input class="option checkbox" value="<?=$option_value['name']?>" name="option-<?=$id[0]?>[]" type="checkbox" data-price="<?=$option_value['price']?>" data-modifier="<?=$option_value['price_modifier']?>"<?=$id[2] && $n == 0 ? ' required' : ''?><?=$option_value['quantity']==0?' disabled':''?>>
                <span class="cbx">
                  <svg width="12px" height="11px" viewBox="0 0 12 11">
                    <polyline points="1 6.29411765 4.5 10 11 1"></polyline>
                  </svg>
                </span>
                <span><p><?=$option_value['name']?></p></span>
              </label>
               <?php endforeach; ?>
              </div>

            <?php elseif ($id[1] == 'text'): ?>
            <?php foreach ($option as $option_value): ?>
            <div class="credentials-box">
            <label for="<?=$id[0]?>"><?=$id[0]?></label>
            <input id="<?=$id[0]?>" class="option text" name="option-<?=$id[0]?>" type="text" placeholder="<?=$option_value['name']?>" data-price="<?=$option_value['price']?>" data-modifier="<?=$option_value['price_modifier']?>"<?=$id[2] ? ' required' : ''?><?=$option_value['quantity']==0?' disabled':''?>>
           </div>
            <?php endforeach; ?>

            <?php elseif ($id[1] == 'datetime'): ?>
            <?php foreach ($option as $option_value): ?>
            <div class="credentials-box">
            <label for="<?=$id[0]?>"><?=$id[0]?></label>
            <input id="<?=$id[0]?>" class="option datetime" name="option-<?=$id[0]?>" type="datetime-local"<?=$option_value['name'] ? 'value="' . date('Y-m-d\TH:i', strtotime($product['date_added'])) . '" ' : ''?> data-price="<?=$option_value['price']?>" data-modifier="<?=$option_value['price_modifier']?>"<?=$id[2] ? ' required' : ''?><?=$option_value['quantity']==0?' disabled':''?>>
           </div>
            <?php endforeach; ?>          
            <?php endif; ?>
            <?php endforeach; ?>

            <div class="credentials-box">
            <label for="quantity">Quantity</label>
            <input id="quantity" type="number" name="quantity" value="1" min="1"<?php if ($product['quantity'] != -1): ?> max="<?=$product['quantity']?>"<?php endif; ?> placeholder="Quantity" required>
           </div>
           <p style="color:var(--clr-gray-400);">For more information on how shopping and shipping works, <a style="color:var(--clr-purp);" href="https://pnblack.com/info/p/6/a-simple-guide-to-shopping-on-our-website"> click here </a></p>
            <span class="divider"></span>
            <?php if ($product['quantity'] == 0): ?>
            <button type="submit" value="Out of Stock" class="button_e" align="center"  disabled>
            Out of Stock
           </button>
            <?php else: ?>
            <button type="submit"  value="Add To Cart" class="button_e" align="center">
            Add to Cart
           </button>
            <?php endif; ?>
        </form>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
<?php endif; ?>

<?=template_footer()?>