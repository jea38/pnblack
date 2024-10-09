<?php
require_once 'header.php';
?>


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
            <img class="user-select-none pe-none" src="images/globe.gif" alt=""  />
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
            <h1>Our Projects</h1>
            <div class="custom-swiper-pagination"></div>
          </div>
          <h4>We aren't working on any projects currently.</h4>
        
        </section>
      
        <section id="newsletter">
          <div class="column_1 flow">
            <div class="container py-3">
              <h3>Logo designed by Paige Frias.</h3>
            </div>
          </div>
        </section>

<?=include("footer.php");?>