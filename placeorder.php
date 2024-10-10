<?php
// Prevent direct access to file
defined('pnblack') or exit;
// Remove all the products in cart, the variable is no longer needed as the order has been processed
if (isset($_SESSION['cart'])) {
    unset($_SESSION['cart']);
}
// Remove discount code
if (isset($_SESSION['discount'])) {
    unset($_SESSION['discount']);
}
?>
<?=template_header('Place Order')?>

<?php if ($error): ?>
<h4 class="error"><?=$error?></h4>
<?php else: ?>

    <section id="landing-section">
    <img loading="lazy" class="column_1 user-select-none pe-none" src="https://pnblack.com/shared/featured-image.jpg" alt="" />

          <div class="column_2">
          <h1 class="d3">PnBlack </h1>            
          <h1 class="d3"><span class='typewriter-text' data-text='[ "Order placed ✅"]'></span>
            </h1>
            <div class="column_2b">


            <div class="placeorder">
    <h4>Order placed. Thank you for shopping! We'll email your order details.</h4>
    <a href='<?=url('index.php?page=products')?>'><button class='button-colordot'><span>CONTINUE</span></button></a>
</div>
            </div>
          </div>
        </section>



<?php endif; ?>

<?=template_footer()?>