<?php
require_once 'connect.php';
require_once 'header.php';

// if (!isset($_SESSION['username'])) {
//     header("location: inu.php");
//     exit();
// }

if (isset($_POST['log'])) {
    $username = mysqli_real_escape_string($dbcon, $_POST['username']);
    $password = mysqli_real_escape_string($dbcon, $_POST['password']);

    $sql = "SELECT * FROM admin WHERE username = '$username'";

    $result = mysqli_query($dbcon, $sql);
    $row = mysqli_fetch_assoc($result);
    $row_count = mysqli_num_rows($result);


    if ($row_count == 1 && password_verify($password, $row['password'])) {
        $_SESSION['username'] = $username;
        header("location: admin.php");
    } else {
        echo "<h2 style='color:red;'>Incorrect username or password.</h2>";
    }
}
    ?>

    <div class="card">
    <div class="credentials-box">
    <h2>Editor Access</h2>
    <form action="" method="POST">
        <input type="text" name="username" placeholder="username" value="<?php if(isset($_POST['username'])){ echo strip_tags($_POST['username']);}?>">
        <input type="password" name="password" placeholder="password">
         <button type="submit" class="btn-shiny shiny-effect01" name="log" target="_blank">              
                        <span>Submit</span>
                </button>
    </form>
    </div>
    </div>

    <?php

Include("footer.php");
