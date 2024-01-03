<?php
require('../core/database.php');
require('../core/flash.php');
require('./middleware.php');

$total_order = $db->count("SELECT * FROM transaction");
// $total_user = $db->count("SELECT * FROM user");
// $total_comment = $db->count("SELECT * FROM comment");
$total_mod = $db->count("SELECT * FROM admin WHERE level = 1");

$year = $db->getAll("Select DISTINCT YEAR(transaction.created) as 'Nam' FROM transaction WHERE `transaction`.`status` = 1");

$slc = $db->getAll("SELECT SUM(sizedetail.quantity) AS 'soluongcon' FROM product, sizedetail WHERE product.id = sizedetail.product_id LIMIT 0, 10000");
$slb = $db->getAll("SELECT SUM(`order`.`qty`) AS 'soluongban' FROM product, `order` WHERE product.id = `order`.product_id");
$sum_sl = $slc[0]['soluongcon'] + $slb[0]['soluongban'];
if ($sum_sl != 0) {

    $data_pro = round(($slb[0]['soluongban'] / $sum_sl) * 100);
} else {
    $data_pro = 0;
}

$pro_dis = $db->getAll("SELECT * FROM `product` WHERE `discount` NOT IN(0) LIMIT 0,10000");
$pro_total = $db->getAll("SELECT * FROM `product` LIMIT 0,10000");
if (sizeof($pro_total) != 0) {
    $data_dis = round((sizeof($pro_dis) / sizeof($pro_total)) * 100);
} else {
    $data_dis = 0;
}

$tra_dis = $db->getAll("SELECT COUNT(*) as sl FROM transaction WHERE transaction.`status`= 1 LIMIT 0,10000");
$tra_to = $db->getAll("SELECT COUNT(*) as sl  FROM transaction  LIMIT 0,10000");
if ($tra_to[0]['sl'] != 0) {
    $data_tran = round(($tra_dis[0]['sl'] / $tra_to[0]['sl']) * 100);
} else {
    $data_tran = 0;
}

// $comment_am = $db->getAll("SELECT DISTINCT comment.product_id FROM `comment`");
// if (sizeof($pro_total) != 0) {
//     $data_comment_per =  round((sizeof($comment_am) / sizeof($pro_total)) * 100);
// } else {
//     $data_comment_per = 0;
// }

$year_post = 0;
$sale = 0;
$data_mounth = array();
$data_sale = array();
$data_mounth = array(0);
$data_sale = array(0, 1000);
if (isset($_POST['year']) && $_POST['year'] != 0) {
    $year_post = $_POST['year'];
    $data_big = $db->getAll("Select MONTH(transaction.created) as 'Thang',SUM(transaction.`amount`) as money FROM transaction,`order` WHERE transaction.id = `order`.`transaction_id` AND `transaction`.`status` = 1 AND YEAR(transaction.created) = " . $year_post . " GROUP BY Thang LIMIT 0, 10000");
    $data_mounth = array(0);
    $data_sale = array(0);
    foreach ($data_big as $d) {
        array_push($data_mounth, 'Tháng ' . $d['Thang']);
        array_push($data_sale, number_format($d['money'], 0, '.', ''));
    }
    $data_big2 = $db->getAll("Select YEAR(transaction.created) as 'Thang',SUM(transaction.`amount`) as money FROM transaction,`order` WHERE transaction.id = `order`.`transaction_id` AND `transaction`.`status` = 1 AND YEAR(transaction.created) = " . $year_post . " GROUP BY  Thang LIMIT 0, 10000");
    $sale = $data_big2[0]['money'];
}

?>
<?php include('./layout/head.php'); ?>
<div class="row">
    <ol class="breadcrumb">
        <li>
            <a href="#">
                <svg class="glyph stroked home">
                    <use xlink:href="#stroked-home"></use>
                </svg>
            </a>
        </li>
        <li class="active">Quản trị</li>
    </ol>
</div>

<div class="row">
    <div class="col-lg-12">
        <h2> Trang chủ</h2>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-md-6 col-lg-3">
        <div class="panel panel-blue panel-widget ">
            <div class="row no-padding">
                <div class="col-sm-3 col-lg-5 widget-left">
                    <svg class="glyph stroked bag">
                        <use xlink:href="#stroked-bag"></use>
                    </svg>
                </div>
                <div class="col-sm-9 col-lg-7 widget-right">
                    <div class="large"><?php echo $total_order; ?></div>
                    <div class="text-muted">Đơn hàng </div>
                </div>
            </div>
        </div>
    </div>
    <!-- <div class="col-xs-12 col-md-6 col-lg-3">
        <div class="panel panel-orange panel-widget">
            <div class="row no-padding">
                <div class="col-sm-3 col-lg-5 widget-left">
                    <svg class="glyph stroked empty-message">
                        <use xlink:href="#stroked-empty-message"></use>
                    </svg>
                </div>
                <div class="col-sm-9 col-lg-7 widget-right">
                    <div class="large"><?php echo $total_comment; ?></div>
                    <div class="text-muted">Bình luận</div>
                </div>
            </div>
        </div>
    </div> -->
    <!-- <div class="col-xs-12 col-md-6 col-lg-3">
        <div class="panel panel-teal panel-widget">
            <div class="row no-padding">
                <div class="col-sm-3 col-lg-5 widget-left">
                    <svg class="glyph stroked male-user">
                        <use xlink:href="#stroked-male-user"></use>
                    </svg>
                </div>
                <div class="col-sm-9 col-lg-7 widget-right">
                    <div class="large"><?php echo $total_user; ?></div>
                    <div class="text-muted">Khách hàng</div>
                </div>
            </div>
        </div>
    </div> -->
    <div class="col-xs-12 col-md-6 col-lg-3">
        <div class="panel panel-red panel-widget">
            <div class="row no-padding">
                <div class="col-sm-3 col-lg-5 widget-left">
                    <svg class="glyph stroked app-window-with-content">
                        <use xlink:href="#stroked-app-window-with-content"></use>
                    </svg>
                </div>
                <div class="col-sm-9 col-lg-7 widget-right">
                    <div class="large"><?php echo $total_mod; ?></div>
                    <div class="text-muted">Nhân Viên</div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-6 col-md-3">
        <div class="panel panel-default">
            <div class="panel-body easypiechart-panel">
                <h4>Đơn hàng đã xác nhận</h4>
                <div class="easypiechart" id="easypiechart-blue" data-percent="<?php echo $data_tran; ?>"><span class="percent"><?php echo $data_tran; ?>%</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-6 col-md-3">
        <div class="panel panel-default">
            <div class="panel-body easypiechart-panel">
                <h4>Số lượng sản phẩm đã bán</h4>
                <div class="easypiechart" id="easypiechart-orange" data-percent="<?php echo $data_pro; ?>"><span class="percent"><?php echo $data_pro; ?>%</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-6 col-md-3">
        <div class="panel panel-default">
            <div class="panel-body easypiechart-panel">
                <h4>Sản phẩm đang giảm giá</h4>
                <div class="easypiechart" id="easypiechart-teal" data-percent="<?php echo $data_dis; ?>"><span class="percent"><?php echo $data_dis; ?>%</span>
                </div>
            </div>
        </div>
    </div>
    <!-- <div class="col-xs-6 col-md-3">
        <div class="panel panel-default">
            <div class="panel-body easypiechart-panel">
                <h4>Sản phẩm được bình luận</h4>
                <div class="easypiechart" id="easypiechart-red" data-percent="<?php echo $data_comment_per; ?>"><span class="percent"><?php echo $data_comment_per; ?>%</span>
                </div>
            </div>
        </div>
    </div> -->
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">Thống kê doanh thu</div>
            <div class="panel-body">
                <div class="col-lg-12" style="margin-bottom: 40px">
                    <form method="post">
                        <label>Năm: </label>
                        <select onchange="this.form.submit()" class="form-control" name="year" id="year" style="display: inline-block;max-width: 200px;padding: 5px">
                            <option value='0'>--Chọn năm--</option>
                            <?php foreach ($year as $y) { ?>
                                <option value='<?php echo $y['Nam']; ?>'><?php echo $y['Nam']; ?></option>
                            <?php } ?>
                        </select>
                        <label style="margin-left: 20px">Tổng doanh thu: </label>
                        <span> <?php echo number_format($sale); ?> VNĐ</span>
                    </form>
                </div>
                <div class="canvas-wrapper">
                    <canvas class="main-chart" id="line-chart" height="200" width="600"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include('./layout/footer.php'); ?>

<script type="text/javascript">
    $(document).ready(function() {
        var y = <?php echo $year_post ?>;
        $("#year").val(y);
        var data = <?php echo json_encode($data_sale) ?>;
        var a = JSON.parse(JSON.stringify(data));

        var data2 = <?php echo json_encode($data_mounth) ?>;
        var m = JSON.parse(JSON.stringify(data2));

        var lineChartData = {
            labels: m,
            datasets: [{
                label: "dataset",
                fillColor: "rgba(48, 164, 255, 0.2)",
                strokeColor: "rgba(48, 164, 255, 1)",
                pointColor: "rgba(48, 164, 255, 1)",
                pointStrokeColor: "#fff",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(48, 164, 255, 1)",
                data: a
            }]

        }
        var chart1 = document.getElementById("line-chart").getContext("2d");
        window.myLine = new Chart(chart1).Line(lineChartData, {
            responsive: true
        });
    });
</script>
<script type="text/javascript">
    $('select').on('change', function() {
        $(this).closest('form').submit();
    });
</script>