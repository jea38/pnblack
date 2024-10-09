<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("location: inu.php");
} else {
    session_destroy();
    header("location: inu.php");
}
