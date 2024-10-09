<?php
if (!isset($_SESSION['username'])) {
    header("location: inu.php");
    exit();
}