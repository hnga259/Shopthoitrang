<?php
require('./core/database.php');
require('./core/flash.php');
require('./core/cart.php');

$cart = new Cart;

$carts = $cart->all($db);
$total_items = count($carts);

?>
<?php include('./layout/head.php') ?>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 clearpadding" style="margin-top: 15px;">
        <?php include('./admin/layout/message.php') ?>
        <?php include('./layout/sidebar.php') ?>
        <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9 clearpaddingr">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 clearpadding">
                <ol class="breadcrumb">
                    <li><a href="./"><span class="glyphicon glyphicon-home" aria-hidden="true"></span> Trang chủ</a></li>
                    <li class="active">Chi tiết giỏ hàng</li>
                </ol>
                <?php
                if ($total_items > 0) {
                ?>
                    <div class="panel panel-info " style="margin-top: 20px;margin-bottom: 15px;background-color: #ffffff">
                        <div class="panel-heading">
                            <h3 class="panel-title">GIỎ HÀNG ( <?php echo $total_items; ?> sản phẩm )</h3>
                        </div>
                        <div class="panel-body" style="background-color: #ffffff">
                            <table class="table table-hover">
                                <thead style="background-color: rgb(240, 93, 64);color: #fff;font-size: 14px">
                                    <th>STT</th>
                                    <th>Tên sản phẩm</th>
                                    <th>Hình ảnh</th>
                                    <th style="text-align: center">Số lượng</th>
                                    <th style="text-align: center">Size</th>
                                    <th>Thành tiền</th>
                                    <th>Xóa</th>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 0;
                                    $total_price = 0;
                                    foreach ($carts as $items) {
                                        $total_price += $items['sub_total'];
                                    ?>
                                        <tr>
                                            <td><?php echo $i = $i + 1 ?></td>
                                            <td><a href="product.php?id=<?php echo $items['id']; ?>"><?php echo $items['name']; ?></a></td>
                                            <td><img src="./public/images/product/<?php echo $items['image_link']; ?>" class="img-thumbnail" alt="" style="width: 50px;"></td>
                                            <td style="min-width: 150px;text-align: center">
                                                <a class="cart-sumsub" href="cart_edit.php?id=<?= $items['id']; ?>&size_id=<?= $items['size_id']; ?>&minus">-</a>
                                                <form method="get" action="cart_edit.php" style="display: inline">
                                                    <input type="hidden" name="id" value="<?= $items['id']; ?>">
                                                    <input type="hidden" name="size_id" value="<?= $items['size_id']; ?>">
                                                    <input type="text" name="quantity" value="<?= $items['quantity']; ?>" style="width: 30px;text-align: center;">
                                                </form>
                                                <a class="cart-sumsub" href="cart_edit.php?id=<?= $items['id']; ?>&size_id=<?= $items['size_id']; ?>&plus">+</a>
                                            </td>
                                            <td><?php echo $items['size_name']; ?></td>
                                            <td><?php echo number_format($items['sub_total']); ?> VNĐ</td>
                                            <td><a style="color: red" href="cart_edit.php?id=<?= $items['id']; ?>&size_id=<?= $items['size_id']; ?>&quantity=0"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></td>
                                            <!--  -->
                                        </tr>
                                    <?php }
                                    ?>
                                    <tr>
                                        <td colspan="5">Tổng tiền</td>
                                        <td style="font-weight: bold;color:green"><?php echo number_format($total_price); ?> VNĐ</td>
                                        <td><a style="font-weight: bold;color: red" href="cart_remove.php">Xóa toàn bộ</a></td>
                                    </tr>
                                    <tr style="border: none">
                                        <td colspan="8"><a style="float: right;;border: none" href="order.php" class="btn btn-success">Đặt mua</a></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php } else { ?>
                    <div class="panel panel-info " style="margin-bottom: 15px">
                        <div class="panel-heading">
                            <h3 class="panel-title">GIỎ HÀNG ( 0 sản phẩm )</h3>
                        </div>
                        <div class="panel-body">
                            <div class="text-center">
                                <img src="./public/images/cart-empty.png" alt="">
                                <h4 style="color:red">Không có sản phẩm trong giỏ hàng</h4>
                                <a href="./" class="btn btn-info">Mua sắm</a>
                            </div>
                        </div>
                    </div>
                <?php }
                ?>
            </div>
        </div>
    </div>
</div>
<?php include('./layout/footer.php') ?>