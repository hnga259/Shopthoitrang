<?php
require('./core/database.php');
require('./core/flash.php');


if (isset($_POST['comment'])) {
    $data = [
        'name' => $_POST['name'],
        'content' => $_POST['content'],
        'product_id' => $_POST['product_id'],
    ];
    $db->create('comment', $data);
}
if (isset($_GET['id']) && isset($_GET['comment_id']) && isset($_GET['reply'])) {
    $data = [
        'reply' => $_GET['reply'],
    ];
    $db->update('comment', $data, $_GET['comment_id']);
    header('location: ./product.php?id='.$_GET['id']);
}
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    if (!$db->getById('product', $id)) {
        Flash::set('message_fail', 'Sản phẩm không tồn tại');
        header('location: ./index.php');
    } else {
        $product = $db->getById('product', $id);
        $size_array = $db->getAll("select sizedetail.*, sizes.name as size_name from sizedetail inner join sizes on sizes.id = sizedetail.size_id where sizedetail.product_id = $id");
        $catalog_product = $db->getById('catalog', $product['catalog_id']);
        $image_list = json_decode($product['image_list']);
        $productsub = $db->getAll("select * from product where catalog_id = {$product['catalog_id']} limit 4");
        $productview = $db->getAll("select * from product order by buyed desc limit 4");
        $comments = $db->getAll("select * from comment where product_id = $id order by date desc");
        
        $auth = false;
        if (isset($_SESSION['admin'])) {
            $auth = $_SESSION['admin'];
        }

        if (isset($_COOKIE['admin'])) {
            $auth = unserialize($_COOKIE['admin']);
        }
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
                    <li><a href="catalog.php?id=<?php echo $catalog_product['id'] ?>"><?php echo $catalog_product['name']; ?></a></li>
                    <li class="active"><?php echo $product['name']; ?></li>
                </ol>
                <!-- zoom image -->
                <link rel="stylesheet" href="./public/js/jqzoom_ev/css/jquery.jqzoom.css" type="text/css">
                <div class="panel panel-info ">
                    <div class="panel-heading cus-color">
                        <h3 class="panel-title">Xem chi tiết sản phẩm</h3>
                    </div>
                    <div class="panel-body">
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                            <div class="text-center">
                                <a href="./public/images/product/<?php echo $product['image_link']; ?>" class="jqzoom" rel="gal1" title="triumph">
                                    <img src="./public/images/product/<?php echo $product['image_link']; ?>" alt="" style="max-width:380px;max-height: 500px">
                                </a>
                                <div class="clearfix"></div>
                                <ul id="thumblist" style="margin-top: 20px;">
                                    <li>
                                        <a class="zoomThumbActive" href='javascript:void(0);' rel="{gallery: 'gal1', smallimage: './public/images/product/<?php echo $product['image_link'] ?>',largeimage: './public/images/product/<?php echo $product['image_link'] ?>'}">
                                            <img src='./public/images/product/<?php echo $product['image_link'] ?>'>
                                        </a>
                                    </li>
                                    <?php if (is_array($image_list)) : ?>
                                        <?php foreach ($image_list as $value) { ?>
                                            <li>
                                                <a href='javascript:void(0);' rel="{gallery: 'gal1', smallimage: './public/images/product/<?php echo $value ?>',largeimage: './public/images/product/<?php echo $value ?>'}">
                                                    <img src='./public/images/product/<?php echo $value; ?>'>
                                                </a>
                                            </li>
                                        <?php } ?> <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="padding: 10px">
                            <h1 style="font-size: 22px;text-transform:uppercase;color: #111111;font-weight:bold;"><?php echo $product['name']; ?></h1>
                            <p><?php echo $product['content']; ?></p>

                            <?php
                            if ($product['discount'] > 0) {
                                $price_new = $product['price'] - $product['discount'];
                            ?><p>Giá cũ: <strong><del><?php echo number_format($product['price']) ?> VNĐ</del></strong></p>
                                <p>Giá khuyến mại: <span style="font-weight: bold;color: green"><?php echo number_format($price_new); ?> VNĐ</span></p>
                            <?php } else { ?>
                                <p>Giá: <span style="font-weight: bold;color: green"><?php echo number_format($product['price']); ?> VNĐ</span></p> <?php
                                                                                                                                                }
                                                                                                                                                    ?>
                            <p>Size hiện có:
                                <?php
                                foreach ($size_array as $size) {
                                ?>
                                    <span><?php echo $size['quantity'] ?> - <?php echo $size['size_name']; ?> ; </span>
                                <?php } ?>

                                <?php if (sizeof($size_array) == 0) { ?>
                                    <span>Sản phẩm đã hết hàng</span>
                                <?php } ?>
                            </p>
                            <p>Số lượt xem: <?php echo $product['view']; ?></p>
                            <p> Đánh giá &nbsp;
                                <?php $raty_tb = $product['rate_total'] / $product['rate_count']; ?>
                                <span> <?php echo round($raty_tb, 2); ?>/</span><b class='rate_count'><?php echo $product['rate_count']; ?></b>
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
                            <form action="cart_edit.php" method="get">
                                <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                                <label for="size_id">Chọn size</label>
                                <select name="size_id" id="size_id" class="form-control" style="margin-bottom: 10px;">
                                    <?php foreach ($size_array as $size) : ?>
                                        <option value="<?= $size['size_id'] ?>"><?= $size['size_name'] ?></option>
                                    <?php endforeach ?>
                                </select>
                                <button type="submit" class="btn btn-info"> Thêm vào giỏ hàng</a>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="panel panel-info">
                    <div class="panel-heading cus-color" style="text-align: left">
                        <h3 class="panel-title">Đánh giá sản phẩm</h3>
                    </div>
                    <div class="panel-body">
                        <?php if ($comments != null) { ?>
                            <?php foreach ($comments as $value) {
                            ?>
                                <div style="background-color: #fff; padding: 10px 20px; border-radius: 20px; margin-bottom: 10px; border: 0.5px solid #000000">
                                    <div>
                                        <span>
                                            <b>
                                                <u>
                                                    Tên: <?php echo $value['name']; ?>
                                                </u>
                                            </b>
                                        </span>
                                    </div>
                                    <p>Nội dung: <?php echo $value['content']; ?></p>
                                    <p style="color: grey; font-size: 12px">Đăng ngày: <?php echo date_format(date_create($value['date']), "d/m/Y H:i"); ?></p>
                                    <?php if ($value['reply']): ?>
                                        <p>Shop phản hồi: <?php echo $value['reply'] ?></p>
                                    <?php endif ?>
                                    <?php if (!$value['reply'] && $auth): ?>
                                        <input type="text" class="form-control" id="reply_comment_<?php echo $value['id'] ?>" style="display: none; margin-bottom: 5px" placeholder="Nhập nội dung phản hồi">
                                        <button class="btn btn-primary btn-sm" onclick="showComment(<?php echo $value['id'] ?>)">Nhập phản hồi</button>
                                        <button class="btn btn-info btn-sm" id="send_comment_<?php echo $value['id'] ?>" onclick="sendComment(<?php echo $value['id'] ?>)" style="display: none">Gửi phản hồi</button>
                                    <?php endif ?>
                                </div>
                            <?php } ?>
                        <?php } else { ?>
                            <p>Sản phẩm chưa có bình luận</p>
                        <?php } ?>
                        <hr>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 clearpadding" style="margin-top: 15px">
                            <div class="col-lg-1" style="padding: 0px">
                                <image style="max-width: 30px;float: right" src="./public/images/user-default.png">
                            </div>
                            <div class="col-lg-11">
                                <form method="post">
                                    <input name="product_id" value="<?php echo $product['id'] ?>" type="hidden" />
                                    <div style="display: flex">
                                        <input name="name" placeholder="Nhập tên" max="255" class="form-control" value="" class="col-lg-10" type="text" style="width: 200px; margin-right: 20px" required />
                                        <input name="content" max="255" placeholder="Nhập nội dung" class="form-control" value="" class="col-lg-10" type="text" required />
                                        <button class="btn btn-success" type="submit" name="comment" style="margin-left: 10px;">Gửi</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-info">
                    <div class="panel-heading cus-color">
                        <h3 class="panel-title">Sản phẩm liên quan</h3>
                    </div>
                    <div class="panel-body">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 clearpadding">
                            <?php
                            foreach ($productsub as $value) { ?>
                                <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 re-padding">
                                    <div class="product_item">
                                        <div class="product-image">
                                            <a href="product.php?id=<?php echo $value['id'] ?>"><img src="./public/images/product/<?php echo $value['image_link']; ?>" alt="" class=""></a>
                                        </div>
                                        <p class="product_name"><a href="product.php?id=<?php echo $value['id'] ?>"><?php echo $value['name']; ?></a></p>
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

                    </div>
                </div>
                <div class="panel panel-info">
                    <div class="panel-heading cus-color" style="text-align: left">
                        <h3 class="panel-title">Có thể bạn thích</h3>
                    </div>
                    <div class="panel-body">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 clearpadding">
                            <?php
                            foreach ($productview as $value) { ?>
                                <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 re-padding">
                                    <div class="product_item">
                                        <div class="product-image">
                                            <a href="product.php?id=<?php echo $value['id'] ?>"><img src="./public/images/product/<?php echo $value['image_link']; ?>" alt="" class=""></a>
                                        </div>
                                        <p class="product_name"><a href="product.php?id=<?php echo $value['id'] ?>"><?php echo $value['name']; ?></a></p>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('./layout/footer.php') ?>
<script src="./public/js/jqzoom_ev/js/jquery.jqzoom-core.js" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.jqzoom').jqzoom({
            zoomType: 'standard',
        });
    });
</script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.raty_detailt').raty({
            score: function() {
                return $(this).attr('data-score');
            },
            half: true,
            click: function(score, evt) {}
        });
    });
    function showComment(id) {
        $('#reply_comment_' + id).show()
        $('#send_comment_' + id).show()
    }
    function sendComment(id) {
        let reply = $('#reply_comment_' + id).val()
        if (!reply) {
            alert('Vui lòng nhập nội dung phản hồi')
            return false
        }
        let data = {
            id: <?= $product['id'] ?>,
            comment_id: id,
            reply: reply,
        }
        let query = new URLSearchParams(data).toString()
        window.location.href = './product.php?' + query
    }

</script>