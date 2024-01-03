<?php
require('../core/database.php');
require('../core/flash.php');
require('./middleware.php');

// Kiểm tra xem đơn hàng có tồn tại biến $_GET['id'] hay không 
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    // Nếu có thì kiểm tra xem đơn hàng có tồn tại trong csdl hay không 
    if (!$db->getById('transaction', $id)) {
        Flash::set('message_fail', 'Đơn hàng không tồn tại');
        header('location: ./don_hang.php');
    } else {
        // Nếu tồn tại, lấy thông tin về đơn hàng và thông tin chi tiết về các sản phẩm trong đơn hàng 
        $transaction = $db->getById('transaction', $id);
        $info = $db->getAll("select * from `order` where transaction_id = $id");
        $list_product = [];
        foreach ($info as $key => $value) {
            $list_product[] = $db->getFirst("select `order`.`id` as `order_id`,`product`.`id` as `id`, `product`.`name` as `name`, `image_link`, `order`.`qty` as `qty`, `order`.`amount` as `price`, `sizes`.`name` as `size_name` from `order` inner join product on order.product_id = product.id inner join sizes on order.size_id = sizes.id where order.id = {$value['id']}");
        }
    }
} else { 
    header('location: ./don_hang.php');
}

?>
<?php include('./layout/head.php'); ?> 
<!-- Tạo phần tử breadcrumb (dòng chỉ đường) để hiển thị vị trí hiện tại của người dùng trên trang web --> 
<div class="row">
    <ol class="breadcrumb">
        <li><a href="#"><svg class="glyph stroked home">
                    <use xlink:href="#stroked-home"></use>
                </svg></a></li>
        <li class="active">Chi tiết đơn đặt hàng</li>
    </ol>
</div>
<!-- Tạo phần tử panel để chứa thông tin về người đặt hàng và chi tiết đơn hàng -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-body">
                <!-- Tạo bảng để hiển thị thông tin về người đặt hàng -->
                <h3>Thông tin khách hàng</h3>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <td style="width: 100px">Họ và tên</td>
                                <td><?php echo $transaction['user_name']; ?></td>
                            </tr>
                            <tr>
                                <td>Email</td>
                                <td><?php echo $transaction['user_email']; ?></td>
                            </tr>
                            <tr>
                                <td>Số điện thoại</td>
                                <td><?php echo $transaction['user_phone']; ?></td>
                            </tr>
                            <tr>
                                <td>Địa chỉ</td>
                                <td><?php echo $transaction['user_address']; ?></td>
                            </tr>
                            <tr>
                                <td>Tin nhắn</td>
                                <td><?php echo $transaction['message']; ?></td>
                            </tr>
                            <tr>
                                <td>Ngày đặt</td>
                                <td><?php echo date_format(date_create($transaction['created']), "H:i:s d/m/Y"); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div><br>
                <!-- Tạo bảng để hiển thị thông tin chi tiết về các sản phẩm trong đơn hàng -->
                <h3>Chi tiết đơn đặt hàng</h3>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr class="info">
                                <th class="text-center">STT</th>
                                <th>Tên sản phẩm</th>
                                <th>Số lượng</th>
                                <th>Size</th>
                                <th>Tổng Giá</th>
                                <?php if ($transaction['status'] == '0') { ?>
                                    <th>Hành động</th> <?php
                                                    } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $stt = 0;
                            // Lặp qua và hiển thị thông tin sản phẩm 
                            foreach ($list_product as $value) {
                                $stt = $stt + 1; ?>
                                <tr>
                                    <td style="vertical-align: middle;text-align: center;"><strong><?php echo $stt ?></strong></td>
                                    <td><img src="../public/images/product/<?php echo $value['image_link']; ?>" alt="" style="width: 50px;float:left;margin-right: 10px;"><strong><?php echo $value['name']; ?></strong>
                                    </td>
                                    <td style="vertical-align: middle"><strong><?php echo $value['qty']; ?></strong></td>
                                    <td style="vertical-align: middle"><strong><?php echo $value['size_name']; ?></strong></td>
                                    <td style="vertical-align: middle">
                                        <?php echo number_format($value['price']); ?> VNĐ
                                    </td>
                                    <?php if ($transaction['status'] == '0') { ?>
                                        <td class="list_td aligncenter">
                                            <a href="./don_hang_detail_delete.php?id=<?php echo $value['order_id'] ?>" title="Xóa"> <span class="glyphicon glyphicon-remove" onclick=" return confirm('Bạn chắc chắn muốn xóa')"></span> </a>
                                        </td>
                                    <?php
                                    } ?>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <!-- Nếu tình trạng đơn hàng là '0', hiển thị nút để xác nhận đơn hàng -->
                    <?php if ($transaction['status'] == '0') { ?>
                        <a href="./don_hang_detail_accept.php?id=<?php echo $transaction['id'] ?>" class="btn btn-success"> Xác nhận đơn hàng</a> <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include('./layout/footer.php'); ?>