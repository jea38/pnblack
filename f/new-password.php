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
                <form action="new-password" method="POST" autocomplete="off">
      
                    <h3 class="d3" style="font-size:20px;color:var(--clr-purp)">new password.</h3>
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
                        <input  type="password" name="password" placeholder="Create new password" required>

                        <input  type="password" name="cpassword" placeholder="Confirm your password" required>


                            <button  name="change-password" type="submit" class="button-colordot bcalt">
                            <span>CHANGE</span>
                            <svg width="13px" height="10px" viewBox="0 0 13 10">
                                <path d="M1,5 L11,5"></path>
                                <polyline points="8 1 12 5 8 9"></polyline>
                            </svg>
                        </button>

                </form>
                </div>
                </center>

<?php include "footer.php"; ?>