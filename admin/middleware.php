<?php
$auth = false;
if (isset($_SESSION['admin'])) {
    $auth = true;
} 

if (isset($_COOKIE['admin'])) {
    $auth = true;
}
if (!$auth) {
    header('location: ./dang_nhap.php');
}
?>