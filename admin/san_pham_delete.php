<?php
require('../core/database.php');
require('../core/upload.php');
require('../core/flash.php');
require('./middleware.php');

// Kiểm tra xem có tham số id truyền vào không
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    // Nếu có, xác định id sản phẩm và kiểm tra sự tồn tại của sản phẩm
    if (!$db->getById('product', $id)) {
        Flash::set('message_fail', 'Sản phẩm không tồn tại');
    } else {
        // Xác định thông tin sản phẩm
        $product = $db->getById('product', $id);
        $upload = new Upload;
        $image_link = $product['image_link'];
        $image_list = json_decode($product['image_list']);
        
        foreach ($image_list as $item) {
            $upload->delete($item);
        }
        $upload->delete($image_link);
        
        $kq = $db->delete('product', $id);
        $kq1 = $db->query("delete from sizedetail where product_id = {$product['id']}");
        // Kiểm tra kết quả xóa và đặt thông báo tương ứng
        if ($kq && $kq1) {
            Flash::set('message_success', 'Xóa sản phẩm thành công');
        } else {
            Flash::set('message_fail', 'Xóa sản phẩm thất bại');
        }
    }
    header('location: ./san_pham.php');
} else {
    header('location: ./san_pham.php');
}
// Đóng phiên làm việc với session
session_write_close();
