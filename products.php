<?php
// Prevent direct access to file
defined('pnblack') or exit;
// Get all the categories from the database
$stmt = $pdo->query('SELECT * FROM categories');
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Get the current category from the GET request, if none exists set the default selected category to: all
$category = isset($_GET['category']) ? $_GET['category'] : 'all';
$category_sql = '';
if ($category != 'all') {
    $category_sql = 'JOIN products_categories pc ON pc.category_id = :category_id AND pc.product_id = p.id JOIN categories c ON c.id = pc.category_id';
}
// Get the sort from GET request, will occur if the user changes an item in the select box
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'sort3';
// The amounts of products to show on each page
$num_products_on_each_page = 12;
// The current page, in the URL this will appear as index.php?page=products&p=1, index.php?page=products&p=2, etc...
$current_page = isset($_GET['p']) && is_numeric($_GET['p']) ? (int)$_GET['p'] : 1;
// Select products ordered by the date added
if ($sort == 'sort1') {
    // sort1 = Alphabetical A-Z
    $stmt = $pdo->prepare('SELECT p.*, (SELECT m.full_path FROM products_media pm JOIN media m ON m.id = pm.media_id WHERE pm.product_id = p.id ORDER BY pm.position ASC LIMIT 1) AS img FROM products p ' . $category_sql . ' WHERE p.status = 1 ORDER BY p.name ASC LIMIT :page,:num_products');
} elseif ($sort == 'sort2') {
    // sort2 = Alphabetical Z-A
    $stmt = $pdo->prepare('SELECT p.*, (SELECT m.full_path FROM products_media pm JOIN media m ON m.id = pm.media_id WHERE pm.product_id = p.id ORDER BY pm.position ASC LIMIT 1) AS img FROM products p ' . $category_sql . ' WHERE p.status = 1 ORDER BY p.name DESC LIMIT :page,:num_products');
} elseif ($sort == 'sort3') {
    // sort3 = Newest
    $stmt = $pdo->prepare('SELECT p.*, (SELECT m.full_path FROM products_media pm JOIN media m ON m.id = pm.media_id WHERE pm.product_id = p.id ORDER BY pm.position ASC LIMIT 1) AS img FROM products p ' . $category_sql . ' WHERE p.status = 1 ORDER BY p.date_added DESC LIMIT :page,:num_products');
} elseif ($sort == 'sort4') {
    // sort4 = Oldest
    $stmt = $pdo->prepare('SELECT p.*, (SELECT m.full_path FROM products_media pm JOIN media m ON m.id = pm.media_id WHERE pm.product_id = p.id ORDER BY pm.position ASC LIMIT 1) AS img FROM products p ' . $category_sql . ' WHERE p.status = 1 ORDER BY p.date_added ASC LIMIT :page,:num_products');
} elseif ($sort == 'sort5') {
    // sort5 = Highest Price
    $stmt = $pdo->prepare('SELECT p.*, (SELECT m.full_path FROM products_media pm JOIN media m ON m.id = pm.media_id WHERE pm.product_id = p.id ORDER BY pm.position ASC LIMIT 1) AS img FROM products p ' . $category_sql . ' WHERE p.status = 1 ORDER BY p.price DESC LIMIT :page,:num_products');
} elseif ($sort == 'sort6') {
    // sort6 = Lowest Price
    $stmt = $pdo->prepare('SELECT p.*, (SELECT m.full_path FROM products_media pm JOIN media m ON m.id = pm.media_id WHERE pm.product_id = p.id ORDER BY pm.position ASC LIMIT 1) AS img FROM products p ' . $category_sql . ' WHERE p.status = 1 ORDER BY p.price ASC LIMIT :page,:num_products');
} else {
    // No sort was specified, get the products with no sorting
    $stmt = $pdo->prepare('SELECT p.*, (SELECT m.full_path FROM products_media pm JOIN media m ON m.id = pm.media_id WHERE pm.product_id = p.id ORDER BY pm.position ASC LIMIT 1) AS img FROM products p ' . $category_sql . ' WHERE p.status = 1 LIMIT :page,:num_products');
}
// bindValue will allow us to use integer in the SQL statement, we need to use for LIMIT
if ($category != 'all') {
    $stmt->bindValue(':category_id', $category, PDO::PARAM_INT);
}
$stmt->bindValue(':page', ($current_page - 1) * $num_products_on_each_page, PDO::PARAM_INT);
$stmt->bindValue(':num_products', $num_products_on_each_page, PDO::PARAM_INT);
$stmt->execute();
// Fetch the products from the database and return the result as an Array
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Get the total number of products
$stmt = $pdo->prepare('SELECT COUNT(*) FROM products p ' . $category_sql . ' WHERE p.status = 1');
if ($category != 'all') {
    $stmt->bindValue(':category_id', $category, PDO::PARAM_INT);
}
$stmt->execute();
$total_products = $stmt->fetchColumn()
?>
<?=template_header('Store')?>

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
              <h1 class="d3">
                PnBlack <span class='typewriter-text' data-text='[ "Store 🛒"]'></span>
            </h1>
<h5><?=$total_products?> Product<?=$total_products!=1?'s':''?></h5>

<div>
    <form class="credentials-box search" onsubmit="submitSearch(event);" role="search">
        <input type="text" onkeyup="searchFunction(event)" placeholder="Search..." autofocus />
        <button type="submit" class="button-colordot">
            <span>SEARCH</span>
        </button>
    </form>
</div>


            </div>
          </div>
        </section>

<div class="products">
        
   

<div class="container py-2">
<form action="" method="get" class="credentials-box filter products-form">
                    <input type="hidden" name="page" value="products">
                        <label class="category">
                            Category
                            <select style="background:#000;" name="category">
                                 <option value="all"<?=($category == 'all' ? ' selected' : '')?>>All</option>
                    <?=populate_categories($categories, $category)?>
                            </select>
                            </label>       
                        <label class="sortby">
                            Sort by
                            <select style="background:#000;" name="sort">
                    <option value="sort1"<?=($sort == 'sort1' ? ' selected' : '')?>>Alphabetical A-Z</option>
                    <option value="sort2"<?=($sort == 'sort2' ? ' selected' : '')?>>Alphabetical Z-A</option>
                    <option value="sort3"<?=($sort == 'sort3' ? ' selected' : '')?>>Newest</option>
                    <option value="sort4"<?=($sort == 'sort4' ? ' selected' : '')?>>Oldest</option>
                    <option value="sort5"<?=($sort == 'sort5' ? ' selected' : '')?>>Highest Price</option>
                    <option value="sort6"<?=($sort == 'sort6' ? ' selected' : '')?>>Lowest Price</option>
                            </select>
                            </label>
                </form>
</div>
         




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



    <div class="buttons">
        <?php if ($current_page > 1): ?>
        <a href="<?=url('index.php?page=products&p=' . ($current_page-1) . '&category=' . $category . '&sort=' . $sort)?>" class="button_e">Prev</a> 
        <?php endif; ?>
        <?php if ($total_products > ($current_page * $num_products_on_each_page) - $num_products_on_each_page + count($products)): ?>
        <a href="<?=url('index.php?page=products&p=' . ($current_page+1) . '&category=' . $category . '&sort=' . $sort)?>" class="button_e">Next</a>
        <?php endif; ?>
    </div>

</div>

<?=template_footer()?>