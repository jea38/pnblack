<?php
require_once 'connect.php';
require_once '../shared/header.php';
?>
	<link href="LiveSupportChat.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.1.1/css/all.css">

<section id="landing-section">
          <img loading="lazy" class="column_1 user-select-none pe-none" src="https://i.pinimg.com/736x/04/2f/f4/042ff44fb9c5733c61667685e2455904.jpg" alt="" />
          <div class="column_2">
            <div></div>
            <div class="column_2b">
              <h1 class="d3">PnBlack </h1>
              <h1 class="d3"><span class='typewriter-text' data-text='["Contact Us 🤙","Help is here 🤓"]'></span></h1>
            </div>
          </div>
        </section>
      

        <section id="our-work">
          <div class="column_1 container py-5">
            <h1 id="mylvs"></h1>
            <h1 id="mylvscb"></h1>
          </div>
          <div class="column_2 container py-5 flow">
          <h1 style="color:var(--clr-purp);">Email</h1>

          <div>
                    <form class="credentials-box" action="">
                    <div class="submitcontact">
        <spn></spn>
</div>
                    <label for="cname">Name</label>
                       <input type="text" name="cname" id="cname" placeholder="John Doe" required>

                    <label for="cemail">Your Email</label>
                       <input type="cemail" name="cemail" id="cemail" placeholder="you@mail.com" required>               

    <label for="message">Message</label>
    <textarea id="message" name="message" placeholder="Write something.." style="height:200px"></textarea>

<button class='blog-card-button' type="submit">
        <span>SUBMIT</span></button>
               </form>
              </div>
          </div>
        </section>

        <script>
            //Contact Form in PHP
const form = document.querySelector("form"),
statusTxt = form.querySelector(".submitcontact spn");
form.onsubmit = (e)=>{
  e.preventDefault();
  statusTxt.style.color = "#0D6EFD";
  statusTxt.style.display = "block";
  statusTxt.innerText = "Sending your message...";
  form.classList.add("disabled");
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "contactm", true);
  xhr.onload = ()=>{
    if(xhr.readyState == 4 && xhr.status == 200){
      let response = xhr.response;
      if(response.indexOf("required") != -1 || response.indexOf("valid") != -1 || response.indexOf("failed") != -1){
        statusTxt.style.color = "red";
      }else{
        form.reset();
        setTimeout(()=>{
          statusTxt.style.display = "none";
        }, 3000);
      }
      statusTxt.innerText = response;
      form.classList.remove("disabled");
    }
  }
  let formData = new FormData(form);
  xhr.send(formData);
}
            </script>

<script src="LiveSupportChat.js"></script>
        <script>
        new LiveSupportChat({
			auto_login: true,
			notifications: true,
            update_interval: 5000 // 5000ms = 5 seconds
        });
        </script>
		<!-- END -->

 
<?=include("../shared/footer.php");?>