<?php
require('../core/database.php');
require('../core/flash.php');
require('./middleware.php');

//Kiểm tra xem tham số $_GET['id'] có được đặt hay không
if (isset($_GET['id'])) {
    // Nếu có, xác định id order và kiểm tra sự tồn tại của order
    $id = $_GET['id'];
    if (!$db->getById('transaction', $id)) {
        // Nếu không tồn tại, thông báo và chuyển hướng người dùng
        Flash::set('message_fail', 'Đơn hàng không tồn tại');
        header('location: ./don_hang.php');
    } else {
         // Nếu tồn tại, cập nhật trạng thái của đơn hàng thành '1'
        $data = [
            'status' => 1
        ];
        $kq = $db->update('transaction', $data, $id);
        // Thông báo thành công và chuyển hướng người dùng
        Flash::set('message_success', 'Xác nhận đơn đặt hàng thành công');
        header('location: ./don_hang.php');
    }
} else {
    // Nếu không, chuyển hướng người dùng về trang don_hang.php 
    header('location: ./don_hang.php');
}
session_write_close();
?>
