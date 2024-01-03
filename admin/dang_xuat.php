<?php
session_start();
if (isset($_SESSION['admin'])) {
    unset($_SESSION['admin']);
}
if (isset($_COOKIE['admin'])) {
    setcookie('admin', '', time()-1, '/');
}
header('location: ./dang_nhap.php');