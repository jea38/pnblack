<?php
// Prevent direct access to file
defined('pnblack') or exit;
// Check for search query
if (isset($_GET['query']) && $_GET['query'] != '') {
    // Escape the user query, prevent XSS attacks
    $search_query = htmlspecialchars($_GET['query'], ENT_QUOTES, 'UTF-8');
    // Select products ordered by the date added
    $stmt = $pdo->prepare('SELECT p.*, (SELECT m.full_path FROM products_media pm JOIN media m ON m.id = pm.media_id WHERE pm.product_id = p.id ORDER BY pm.position ASC LIMIT 1) AS img FROM products p WHERE p.status = 1 AND p.name LIKE ? ORDER BY p.date_added DESC');
    // bindValue will allow us to use integer in the SQL statement, we need to use for LIMIT
    $stmt->execute(['%' . $search_query . '%']);
    // Fetch the products from the database and return the result as an Array
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // Get the total number of products
    $total_products = count($products);
} else {
    // Simple error, if no search query was specified why is the user on this page?
    $error = 'No search query was specified!';
}
?>
<?=template_header('Search')?>

<script>
  let productslink = document.querySelectorAll("[id='productslink']");

for(var i = 0; i < productslink.length; i++){
  productslink.item(i).classList.add('active');
} 
</script>


<section id="landing-section">
          <img loading="lazy" class="column_1 user-select-none pe-none" src="https://pnblack.com/shared/featured-image.jpg" alt="" />
          <div class="column_2">
            <div></div>
            <div class="column_2b">
              <h1 class="d3">PnBlack</h1>
              <h1 class="d3">
                <span class='typewriter-text' data-text='[ "Cart 🛒"]'></span>
            </h1>
            </div>
          </div>
        </section>

<?php if ($error): ?>

<h4 class="content-wrapper error"><?=$error?></h4>

<?php else: ?>

<div class="container py-2 products content-wrapper">

    <h4>Search Results for "<?=$search_query?>" :</h4>
    <h4 style="color:var(--clr-purp);"><?=$total_products?> Product<?=$total_products!=1?'s':''?></h4>
    <a class='button_e' href='<?=url('index.php?page=products')?>'>RETURN</a>

                        <div class="card-grid">
        <?php foreach ($products as $product): ?>
                        <div class="card-list">
                            <article class="card">
        <a href="<?=url('index.php?page=product&id=' . ($product['url_slug'] ? $product['url_slug']  : $product['id']))?>" class="product">
                                    <figure class="card-image">
                                        <?php if (!empty($product['img']) && file_exists($product['img'])): ?>
            <img src="<?=base_url?><?=$product['img']?>" alt="<?=$product['name']?>">
            <?php endif; ?>
                                    </figure>
                                    <div class="card-header">
                                        <p><?=$product['name']?>. <br> </p>
                                        <button class="icon-button">
                                            <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 490 490" style="enable-background:new 0 0 500 500;" xml:space="preserve"><g><g><path d="M470.223,0.561h-89.7c-9.4,0-16.7,6.3-19.8,14.6l-83.4,263.8h-178.3l-50-147h187.7c11.5,0,20.9-9.4,20.9-20.9 s-9.4-20.9-20.9-20.9h-215.9c-18.5,0.9-23.2,18-19.8,26.1l63.6,189.7c3.1,8.3,11.5,13.6,19.8,13.6h207.5c9.4,0,17.7-5.2,19.8-13.6  l83.4-263.8h75.1c11.5,0,20.9-9.4,20.9-20.9S481.623,0.561,470.223,0.561z" /><path d="M103.223,357.161c-36.5,0-66.7,30.2-66.7,66.7s30.2,66.7,66.7,66.7s66.7-30.2,66.7-66.7S139.723,357.161,103.223,357.161z   M128.223,424.861c0,14.6-11.5,26.1-25,26.1c-13.6,0-25-11.5-25-26.1s11.5-26.1,25-26.1  C117.823,398.861,129.323,410.261,128.223,424.861z" /><path d="M265.823,357.161c-36.5,0-66.7,30.2-66.7,66.7s30.2,66.7,66.7,66.7c37.5,0,66.7-30.2,66.7-66.7  C332.623,387.361,302.323,357.161,265.823,357.161z M290.923,424.861c0,14.6-11.5,26.1-25,26.1c-13.5,0-25-11.5-25-26.1   s11.5-26.1,25-26.1C280.423,398.861,291.923,410.261,290.923,424.861z" /></g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g></svg>
                                        </button>
                                    </div>
                                    <div class="card-footer">
                                        <div class="card-meta card-meta--views">
                                            <button class="button_e" align="center">
                                              <?=currency_code?><?=number_format($product['price'],2)?>
                                                <?php if ($product['rrp'] > 0): ?>
                                                <span class="rrp"><?=currency_code?><?=number_format($product['rrp'],2)?></span>
                                                <?php endif; ?>
                                            </button>
                                        </div>
                                    </div>
                                </a>
                            </article>
                        </div>
        <?php endforeach; ?>

                    </div>

</div>

<?php endif; ?>

<?=template_footer()?>