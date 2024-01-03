<?php

require('../core/database.php');
require('../core/flash.php');
require('./middleware.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    if (!$db->getById('catalog', $id)) {
        Flash::set('message_fail', 'Danh mục không tồn tại');
    } else {
        $kq = $db->delete('catalog', $id);
        if ($kq) {
            Flash::set('message_success', 'Xóa danh mục thành công');
        } else {
            Flash::set('message_fail', 'Xóa danh mục thất bại');
        }
    }
    header('location: ./danh_muc.php');
} else {
    header('location: ./danh_muc.php');
}
