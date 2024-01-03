<?php
class Cart
{
    public function setQuantity($product_id, $size_id, $max_quantity_size, $quantity)
    {
        $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
        $cart[$product_id . "_" . $size_id] = $quantity;
        if ($cart[$product_id . "_" . $size_id] > $max_quantity_size) {
            $cart[$product_id . "_" . $size_id] = $max_quantity_size;
        }
        if ($cart[$product_id . "_" . $size_id] <= 0) {
            unset($cart[$product_id . "_" . $size_id]);
        }
        $_SESSION['cart'] = $cart;
    }

    public function plusQuantity($product_id, $size_id, $max_quantity_size)
    {
        $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
        if (isset($cart[$product_id . "_" . $size_id])) {
            $cart[$product_id . "_" . $size_id] += 1;
        } else {
            $cart[$product_id . "_" . $size_id] = 1;
        }
        if ($cart[$product_id . "_" . $size_id] > $max_quantity_size) {
            $cart[$product_id . "_" . $size_id] = $max_quantity_size;
        }
        $_SESSION['cart'] = $cart;
    }

    public function minusQuantity($product_id, $size_id)
    {
        $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
        if (isset($cart[$product_id . "_" . $size_id])) {
            $cart[$product_id . "_" . $size_id] -= 1;
        } else {
            $cart[$product_id . "_" . $size_id] = 1;
        }
        if ($cart[$product_id . "_" . $size_id] <= 0) {
            unset($cart[$product_id . "_" . $size_id]);
        }
        $_SESSION['cart'] = $cart;
    }

    public function all($db)
    {
        $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
        $products = [];
        foreach ($cart as $key => $quantity) {
            $temp = explode('_', $key);
            $product_id = $temp[0];
            $size_id = $temp[1];
            $size = $db->getById('sizes', $size_id);
            $product = $db->getById('product', $product_id);
            $price = $product['discount'] > 0 ? $product['price'] - $product['discount'] : $product['price'];
            $sub_total = $price * $quantity;
            $products[] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'image_link' => $product['image_link'],
                'quantity' => $quantity,
                'price' => $price,
                'sub_total' => $sub_total,
                'size_id' => $size_id,
                'size_name' => $size['name'],
            ];
        }
        return $products;
    }

    public function empty()
    {
        unset($_SESSION['cart']);
    }
}
