<?php
require('./core/database.php');
require('./core/cart.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    if (!$db->getById('product', $id)) {
        Flash::set('message_fail', 'Sản phẩm không tồn tại');
        header('location: ./index.php');
    } else {
        $product = $db->getById('product', $id);
        $size_array = $db->getAll("select * from sizedetail where product_id = $id order by size_id asc");
        $size_id = null;
        $max_quantity_size = 0;
        if (isset($_GET['size_id'])) {
            $size_id = $_GET['size_id'];
            $size_detail = $db->getFirst("select * from sizedetail where product_id = $id and size_id = $size_id");
            if (!$size_detail || $size_detail['quantity'] == 0) {
                header('location: ./cart.php');
            }
            $size_id = $size_detail['size_id'];
            $max_quantity_size = $size_detail['quantity'];
        } else {
            foreach ($size_array as $size) {
                if ($size['quantity'] > 0) {
                    $size_id = $size['size_id'];
                    $max_quantity_size = $size['quantity'];
                    break;
                }
            }
        }
        if ($size_id) {
            $cart = new Cart;
            if (isset($_GET['quantity'])) {
                $quantity = $_GET['quantity'];
                $cart->setQuantity($product['id'], $size_id, $max_quantity_size, $quantity);
            } else if (isset($_GET['minus'])) {
                $cart->minusQuantity($product['id'], $size_id);
            } else if (isset($_GET['plus'])) {
                $cart->plusQuantity($product['id'], $size_id, $max_quantity_size);
            } else {
                $cart->plusQuantity($product['id'], $size_id, $max_quantity_size);
            }
        }
        header('location: ./cart.php');
    }
} else {
    header('location: ./index.php');
}
