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
                <img class="logo" src="http://192.168.1.132/pnblack/shared/pnbf22.png">
              <!-- <div class="social-icon">
                <a href="#"><i class="fa-brands fa-telegram"></i></a>
                <a href="#"><i class="fa-brands fa-instagram"></i></a>
                <a href="#"><i class="fa-brands fa-twitter"></i></a>
              </div> -->
            </div>
          </div>
      
        </section>
        <h5 class="copy-tag py-2">Copyright &#169 
            <script>
                document.write(new Date().getFullYear());
            </script> PnBlack. <a href="../index.php?page=legal">All Rights Reserved.</a>
        </h5>
      </footer>

      <script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.19.1/trumbowyg.min.js"></script>
<script>
    $('#description').trumbowyg();
</script>
    <script src="http://192.168.1.132/pnblack/shared/main.js"></script>

</body>
</html>
