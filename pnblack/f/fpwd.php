
<?php require_once "controllerUserData.php"; ?>

<?php include "header.php"; ?>



<center>
        <div class="credentials-box ">
                  <h3 class="d3" style="font-size:20px;color:var(--clr-purp)">password reset assistance.</h3>
            <p style="color:var(--clr-gray-300);">-Enter the email associated with your acccount.</p>
                               <?php
                        if(count($errors) > 0){
                            ?>
                                   <p style="color:red;">

            <?php 
                                    foreach($errors as $error){
                                        echo $error;
                                    }
                                ?>
            </p>
                  <?php
                        }
                    ?>


            <form action="fpwd" method="POST" autocomplete="">

                <input  type="email" name="email" id="email" placeholder="john@example.com" required value="<?php echo $email ?>">

                  <button  name="check-email" type="submit" class="button-colordot bcalt">
                            <span>PROCEED
                            <svg width="13px" height="10px" viewBox="0 0 13 10">
                                <path d="M1,5 L11,5"></path>
                                <polyline points="8 1 12 5 8 9"></polyline>
                            </svg></span>
                        </button>

            </form>


            <a href="../index.php?page=myaccount">
                  <button  name="check-email" type="submit" class="button-colordot">
                            <span>CANCEL</span>
                        </button>
            </a>

        </div>
        </center>

<?php include "footer.php"; ?>


