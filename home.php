<?php
// Prevent direct access to file
defined('pnblack') or exit;
$site_name = site_name;
// Get the 4 most recent added products
$stmt = $pdo->prepare('SELECT p.*, (SELECT m.full_path FROM products_media pm JOIN media m ON m.id = pm.media_id WHERE pm.product_id = p.id ORDER BY pm.position ASC LIMIT 1) AS img FROM products p WHERE p.status = 1 ORDER BY p.date_added DESC LIMIT 6');
$stmt->execute();
$recently_added_products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?=template_header('Home')?>


<script>
  let homelink = document.querySelectorAll("[id='homelink']");

  for(var i = 0; i < homelink.length; i++){
    homelink.item(i).classList.add('active');
  } 

</script>

<section id="landing-section">
          <img loading="lazy" class="column_1 user-select-none pe-none" src="https://i.pinimg.com/736x/04/2f/f4/042ff44fb9c5733c61667685e2455904.jpg" alt="" />
          <div class="column_2">
            <div></div>
            <div class="column_2b">
              <h1 class="d3"><?="$site_name"?> </h1>
              <h1 class="d3">
                <span class='typewriter-text' data-text='[ "Loves you 🖤. ", "Spread the word 📢. ", "Cool stuff here 😎." ]'></span>
            </h1>
            </div>
          </div>
        </section>
      
        <section id="our-work">
          <div class="column_1 container py-5 flow">
            <h1>Why us ?</h1>
            <p>
            Welcome to our website! We're thrilled to have you join us. 
            At our core, we believe that customers are our most valuable asset, 
            and that's why we've crafted an experience designed entirely around catering to your needs.
            Discover convenience, personalized service, and an exceptional journey with us.   
            </p><br>

            <a href="pnblack/index.php?page=products"><button class="click-me"><h3> Get Started</h3></button></a>
          </div>
          <div class="column_2">
            <div class="work-cont">
              <div class="work-list flow">
                <i class="fa-sharp fa-solid fa-bolt work-icon"></i>     
                <h5 class="work-name">Fast Service</h5>
              </div>
              <div class="work-list">
                <i class="fa-sharp fa-solid fa-hand-holding-dollar work-icon"></i>
                <h5 class="work-name">Affordable</h5>
              </div>
              <div class="work-list">
                <i class="fa-sharp fa-solid fa-shield-halved work-icon"></i>
                <h5 class="work-name">Secure and Private</h5>
              </div>
              <div class="work-list">
                <i class="fa-sharp fa-solid fa-smile work-icon"></i>
                <h5 class="work-name">We value you</h5>
              </div>
            </div>
          </div>
        </section>
      
        <section id="section-3">
        <div class="img-cont ">
            <img class="user-select-none pe-none" src="https://pnblack.azurewebsites.net/shared/globe.gif" alt=""  />
          </div>
          <div class="container py-5">
            <div class="title flow">
              <h1 class="pt-3">Our Dream.</h1>
              <p>
              Our dream is to evolve into a worldwide hub, 
              offering a diverse range of products and services that cater to all.
               We aspire to transcend borders, becoming a go-to destination that
                connects people globally, fostering accessibility and innovation at
                 every step. Join us in shaping this exciting future where everyone
                  can easily access and enjoy our expanding platform.
              </p>
            </div>
          </div>
          
        </section>
      
        <section id="section-4" class="pb-5 container">
          <div class="section-4-header  py-3">
            <h4>Recently Added Products</h4>
            <div class="custom-swiper-pagination"></div>
          </div>
          <div class="swiper section-4">
            <div class="swiper-wrapper">
            <?php foreach ($recently_added_products as $product): ?>
              <div class="swiper-slide">     
                            <article class="card">
                                       <a href="<?=url('index.php?page=product&id=' . ($product['url_slug'] ? $product['url_slug']  : $product['id']))?>" class="product">
                                    <figure class="card-image">
                                        <?php if (!empty($product['img']) && file_exists($product['img'])): ?>
                                        <img src="<?=$product['img']?>" alt="<?=$product['name']?>">
                                        <?php endif; ?>
                                    </figure>
                                    <div class="card-header">
                                        <p><?=$product['name']?> <br> </p>
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
        </section>
      
        <section id="newsletter">
          <div class="column_1 flow">
            <div class="container py-3">
              <h3>Logo designed by Paige Frias.</h3>
            </div>
          </div>
        </section>


        
<?=template_footer()?>