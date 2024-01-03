<?php 
session_start();
require('./core/cart.php');

$cart = new Cart;
$cart->empty();
header('location: ./cart.php');
?>