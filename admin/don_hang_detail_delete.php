<?php
require('../core/database.php');
require('../core/flash.php');
require('./middleware.php');

//Kiểm tra xem tham số $_GET['id'] có được đặt hay không
if (isset($_GET['id'])) {
    // Nếu có, xác định id order và kiểm tra sự tồn tại của order
    $id = $_GET['id'];
    if (!$db->getById('order', $id)) {
        Flash::set('message_fail', 'Order không tồn tại');
        header('location: ./don_hang.php');
    } else {
        // Lấy thông tin về đơn hàng và size chi tiết từ cơ sở dữ liệu
        $order = $db->getById('order', $id);
        $size_detail = $db->getFirst("select * from sizedetail where product_id = {$order['product_id']} and size_id = {$order['size_id']}");
        if ($size_detail) {
            // Nếu có size chi tiết, cập nhật số lượng
            $id_update_size = $size_detail['id'];
            $amount = $size_detail['quantity'] + $order['qty'];
            $data2 = [
                'product_id' => $order['product_id'],
                'size_id' => $order['size_id'],
                'quantity' => $amount,
            ];
            $db->update('sizedetail', $data2, $id_update_size);
        } else {
            // Nếu không có, tạo mới size chi tiết
            $data3 = [
                'product_id' => $order['product_id'],
                'size_id' => $order['size_id'],
                'quantity' => $order['qty'],
            ];
            $db->create('sizedetail', $data3);
        }
        // Xóa bản ghi đơn hàng và cập nhật tổng giá trị của đơn hàng trong bảng transaction
        $db->delete('order', $id);
        $transaction = $db->getById('transaction', $order['transaction_id']);
        $data = [
            'amount' => $transaction['amount'] - $order['amount']
        ];
        $db->update('transaction', $data,  $transaction['id']);
        Flash::set('message_success', 'Xóa thành công');
        header("location: ./don_hang_detail.php?id={$order['transaction_id']}");
    }
} else {
    header('location: ./don_hang.php');
}
session_write_close();
?>
