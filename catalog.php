<?php
require('./core/database.php');
require('./core/flash.php');
require('./core/pagination.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    if (!$db->getById('catalog', $id)) {
        Flash::set('message_fail', 'Danh mục không tồn tại');
        header('location: ./index.php');
    } else {
        $catalog_p = $db->getById('catalog', $id);
        $sql_product = "select * from product";
        if ($catalog_p['parent_id'] == '1') {
            $catalog_child = $db->getAll("select * from catalog where parent_id = {$catalog_p['id']} order by sort_order asc");
            if (!empty($catalog_child)) {
                $cat_list_id = array();
                foreach ($catalog_child as $value) {
                    $cat_list_id[] = $value['id'];
                }
                $str_list = implode(",", $cat_list_id);
                $sql_product .=  " where catalog_id in ($str_list)";
            } else {
                $sql_product .=  " where catalog_id = $id";
            }
        } else {
            $sql_product .=  " where catalog_id = $id";
        }

        // phân trang
        $per_page = 8;
        $current_page = 1;
        if (isset($_GET['page'])) {
            $current_page = $_GET['page'];
        }
        $offset = ($current_page - 1) * $per_page;
        $total = $db->count($sql_product);
        $total_page = ceil($total / $per_page);
        $sql_product .= " LIMIT $offset,$per_page";
        $pagination = new Pagination($total_page, $current_page);
        $product_list = $db->getAll($sql_product);
    }
} else {
    header('location: ./index.php');
}
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
                    <li class="active"><?php echo $catalog_p['name']; ?></li>
                </ol>

                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title"><?php echo $catalog_p['name']; ?></h3>
                    </div>
                    <div class="panel-body">
                        <?php if ($total > 0) { ?>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 clearpadding">
                                <?php
                                foreach ($product_list as $value) { ?>
                                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 re-padding">
                                        <div class="product_item">
                                            <div class="product-image">
                                                <a href="product.php?id=<?php echo $value['id']; ?>"><img src="./public/images/product/<?php echo $value['image_link']; ?>" alt="" class=""></a>
                                            </div>
                                            <p class="product_name"><a href="product.php?id=<?php echo $value['id']; ?>"><?php echo $value['name']; ?></a></p>
                                            <?php
                                            if ($value['discount'] > 0) {
                                                $new_price = $value['price'] - $value['discount'];
                                            ?>
                                                <p><span class='price text-right'><?php echo number_format($new_price); ?> VNĐ</span> <del class="product-discount"><?php echo number_format($value['price']); ?> VNĐ</del></p>
                                            <?php } else { ?>
                                                <p><span class='price text-right'><?php echo number_format($value['price']); ?> VNĐ</span></p>
                                            <?php } ?>
                                            <?php
                                            if ($value['discount'] != 0)
                                                echo '<span class="label_pro">Giảm giá</span>';
                                            ?>
                                            <p>
                                                <?php $raty_tb = round($value['rate_total'] / $value['rate_count']); ?>
                                                <?php
                                                $i2 = 1;

                                                for ($i2; $i2 <= $raty_tb; $i2++) {
                                                    echo '<i class="fa fa-star" style="color:gold"></i>';
                                                }
                                                $i3 = 1;
                                                for ($i3; $i3 <= (5 - $raty_tb); $i3++) {
                                                    echo '<i class="fa fa-star-o" style="color: gold"></i>';
                                                }
                                                ?>
                                            </p>
                                            <?php
                                            $query_count = 'SELECT SUM(quantity) As totalproduct FROM sizedetail WHERE product_id = ' . $value['id'];
                                            $result = $db->getFirst($query_count);
                                            $sl = $result['totalproduct'];
                                            if ($sl > 0) {
                                            ?>
                                                <a href="cart_edit.php?id=<?php echo $value['id']; ?>"><button class='btn btn-info'><span class="fa fa-shopping-cart" aria-hidden="true"></span> Thêm giỏ hàng</button></a>
                                            <?php } else { ?>
                                                <a style="pointer-events: none;"><button style="pointer-events: none;background-color: #333;border: none " class='btn btn-info'>Hết hàng</button></a>
                                            <?php } ?>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                            <?php echo $pagination->render(); ?>
                        <?php } else { ?>
                            <p>Không có sản phẩm nào</p>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include('./layout/footer.php') ?>