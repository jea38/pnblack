<?php
// Prevent direct access to file
defined('pnblack') or exit;
$site_name = site_name;
?>
<?=template_header('Legal')?>

<section id="landing-section">
          <img loading="lazy" class="column_1 user-select-none pe-none" src="https://i.pinimg.com/736x/04/2f/f4/042ff44fb9c5733c61667685e2455904.jpg" alt="" />
          <div class="column_2">
            <div></div>
            <div class="column_2b">
              <h1 class="d3">PnBlack </h1>
              <h1 class="d3"><span class='typewriter-text' data-text='[ "Legal Docs📃."]'></span></h1>
            </div>
          </div>
        </section>
      
        <section id="our-work">
          <div class="column_1 container py-5 flow">
            <h1>Legal Docs</h1>
            <p>
             All legal documents in relation to our business and the services we provide. 
            </p>
          </div>
          <div class="column_2">
            <div class="work-cont">
              <div class="work-list flow">
                <a href="https://www.termsfeed.com/live/17aa26d3-5f39-460c-b3d4-fc5def01692a">
                <h5 style="text-decoration:underline 2px solid var(--clr-purp);" class="work-name">Privacy Policy</h5>
                </a>
              </div>
              <div class="work-list">
                <a href="https://www.termsfeed.com/live/a48e5040-3677-4d77-a5f9-3256e2c5448d">
                <h5 style="text-decoration:underline 2px solid var(--clr-purp);"  class="work-name">Terms & Conditions</h5>
              </a>
              </div>
              <div class="work-list">
                <a href="https://www.termsfeed.com/live/5a88d091-4dd5-499d-b6ab-705816ecaa3e">
                <h5 style="text-decoration:underline 2px solid var(--clr-purp);"  class="work-name">Disclaimer</h5>
                </a>
              </div>
              <div class="work-list">
                <a href="https://www.termsfeed.com/live/9e2b832b-dca1-42f8-94d8-39056529c022">
                <h5  style="text-decoration:underline 2px solid var(--clr-purp);"  class="work-name">Cookie Policy</h5>
                </a>
              </div>
            </div>
          </div>
        </section>
 
        <?=template_footer()?>