<?php require_once "controllerUserData.php"; ?>
<?php
if($_SESSION['info'] == false){
    header('Location: ../index.php?page=myaccount');  
}
?>
<?php include "header.php"; ?>
<center>
                    <div class="credentials-box">

            <?php 
            if(isset($_SESSION['info'])){
                ?>
                 <h3  style="font-size:20px;color:var(--clr-purp)">
                <?php echo $_SESSION['info']; ?>
            </h3>
                <?php
            }
            ?>
                <form action="../index.php?page=myaccount" method="POST">
                             <button  name="login-now" type="submit" class="button-colordot bcalt">
                            <span>LOGIN</span>
                            <svg width="13px" height="10px" viewBox="0 0 13 10">
                                <path d="M1,5 L11,5"></path>
                                <polyline points="8 1 12 5 8 9"></polyline>
                            </svg>
                        </button>

                 
                </form>
                   </div>
                   </center>
<?php include "footer.php"; ?>