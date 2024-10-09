
<?php require_once "controllerUserData.php"; ?>
<?php 
$email = $_SESSION['email'];
if($email == false){
  header('Location: ../index.php');
}
?>
<?php include "header.php"; ?>
<center>
        <div class="credentials-box">
            <p style="font-size:20px;color:var(--clr-gray-300);">-Enter the code sent to your email.</p>
                                      <?php 
                    if(isset($_SESSION['info'])){
                        ?>
            <p style="color:lime;">
              <?php echo $_SESSION['info']; ?>
            </p>
                                   <?php
                    }
                    ?>


                    <?php
                    if(count($errors) > 0){
                        ?>
                        <p style="color:red;">
                            <?php
                            foreach($errors as $showerror){
                                echo $showerror;
                            }
                            ?>
                        </p>
                        <?php
                    }
                    ?>


            <form action="reset-code" method="POST" autocomplete="off">
                <input  type="number" name="otp" placeholder="Enter code" required>
                     <button  name="check-reset-otp" type="submit" class="button-colordot bcalt">
                            <span>CONTINUE</span>
                            <svg width="13px" height="10px" viewBox="0 0 13 10">
                                <path d="M1,5 L11,5"></path>
                                <polyline points="8 1 12 5 8 9"></polyline>
                            </svg>
                        </button>
            </form>

            <form action="fpwd" method="POST" autocomplete="off">
                <input style="background:var(--black1);display:none;"  type="email" name="email" id="email" placeholder="john@example.com" required value="<?php echo $email ?>">
                <button  name="check-email" type="submit" class="button-colordot bcalt">
                            <span>RESEND</span>
                            <svg width="13px" height="10px" viewBox="0 0 13 10">
                                <path d="M1,5 L11,5"></path>
                                <polyline points="8 1 12 5 8 9"></polyline>
                            </svg>
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

