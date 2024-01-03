<?php
require('../core/database.php');
require('../core/flash.php');
require('../core/pagination.php');
require('./middleware.php');

$sql = "SELECT * from transaction order by id desc";
$sql_total = "SELECT * from transaction";

// Xử lý phân trang
$per_page = 10;
$current_page = 1; 
if (isset($_GET['page'])) {
    $current_page = $_GET['page'];
}
$offset = ($current_page - 1) * $per_page; // Vị trí bắt đầu lấy dữ liệu từ csdl 
$total = $db->count($sql_total); 
$total_page = ceil($total / $per_page); 
$sql .= " LIMIT $offset,$per_page"; 
$pagination = new Pagination($total_page, $current_page); 

$transaction = $db->getAll($sql);
?>

<!-- Giao diện trang quản lý đơn đặt hàng -->
<?php include('./layout/head.php'); ?>
<div class="row">
    <ol class="breadcrumb">
        <li><a href="#"><svg class="glyph stroked home">
                    <use xlink:href="#stroked-home"></use>
                </svg></a></li>
        <li class="active">Đơn đặt hàng</li>
    </ol>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="col-md-8">Quản lý đơn đặt hàng</div>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr class="info">
                                <th class="text-center">STT</th>
                                <th>Tên khách hàng</th>
                                <th>Ngày đặt</th>
                                <th>Số ĐT</th>
                                <th>Giá tiền</th>
                                <th>Trạng thái</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php
                            $stt = 0;
                            // Vòng lặp foreach để duyệt qua từng đơn đặt hàng và hiển thị thông tin tương ứng trong bảng
                            foreach ($transaction as $value) {
                                $stt = $stt + 1;
                            ?>
                                <tr>
                                    <td style="vertical-align: middle;text-align: center;"><strong><?php echo $stt; ?></strong></td>
                                    <td><strong><?php echo $value['user_name']; ?></strong></td>
                                    <td><strong><?php echo date_format(date_create($value['created']), 'H:i:s d/m/Y') ?></strong></td>
                                    <td><strong><?php echo $value['user_phone']; ?></strong></td>
                                    <td><strong><?php echo number_format($value['amount']); ?></strong> VNĐ</td>
                                    <td>
                                        <?php
                                        switch ($value['status']) {
                                            case '0':
                                                echo "<p style='color:red'>Đang chờ </p>";
                                                break;
                                            case '1':
                                                echo "<p style='color:green'>Đã xác nhận</p>";
                                                break;
                                            default:
                                                echo 'Đang chờ';
                                                break;
                                        }
                                        ?>
                                    </td>
                                    <td class="list_td aligncenter">
                                        <a href="don_hang_detail.php?id=<?php echo $value['id'] ?>" title="Chi tiết"><span class="glyphicon glyphicon-list-alt"></span></a>&nbsp;&nbsp;&nbsp;
                                        <?php if ($value['status'] != 1) { ?>
                                            <a href="don_hang_delete.php?id=<?php echo $value['id'] ?>" title="Xóa"> <span class="glyphicon glyphicon-remove" onclick=" return confirm('Bạn chắc chắn muốn xóa')"></span> </a>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <?= $pagination->render() ?>
                </div>
            </div>
        </div>
    </div>
</div><!--/.row-->

<?php include('./layout/footer.php'); ?>