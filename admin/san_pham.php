<?php
require('../core/database.php');
require('../core/flash.php');
require('../core/pagination.php');
require('./middleware.php'); 

// Lấy thông tin sản phẩm từ bảng product và catalog
$sql = "SELECT product.id as id,product.name as name,price,discount,image_link,view,buyed,catalog.name as namecatalog from product inner join catalog on catalog.id = product.catalog_id";
$sql_total = "SELECT * FROM product";

// Tìm kiếm sản phẩm có tên chứa giá trị của biến search
if (isset($_GET['search'])) {
    $sql .= " WHERE product.name like '%{$_GET['search']}%'";
    $sql_total .= " WHERE product.name like '%{$_GET['search']}%'";
}
// Phân trang
$per_page = 10; // Số lượng sản phẩm trên mỗi trang
$current_page = 1; // Trang hiện tại lấy giá trị từ tham số page truyền qua phương thức GET, hoặc mặc định là 1 nếu không có
if (isset($_GET['page'])) {
    $current_page = $_GET['page'];
}
$offset = ($current_page - 1) * $per_page; 
$total = $db->count($sql_total); 
$total_page = ceil($total / $per_page); 
$sql .= " LIMIT $offset,$per_page"; 
$pagination = new Pagination($total_page, $current_page); 

$product = $db->getAll($sql); // Lấy danh sách sản phẩm 
?>

<?php include('./layout/head.php'); ?>
<script type="text/javascript">
    $(document).ready(function() {
        // Ajax để xóa sản phẩm mà không cần load lại trang 
        $('.remove').click(function() {
            var container = $(this).parent().parent();
            var id = $(this).attr('id');
            var string = 'id=' + id;
            $.ajax({
                url: './san_pham_delete.php',
                type: 'post',
                data: string,
                success: function(success) {
                    container.slideUp('slow', function() {})
                }
            });
        });
    });
</script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#submit-del').click(function() {
            $.ajax({
                url: './san_pham_delete.php',
                type: 'post',
                data: string,
                success: function(success) {
                    container.slideUp('slow', function() {})
                }
            });
        });
    });
</script>
<div class="row">
    <ol class="breadcrumb">
        <li><a href="#"><svg class="glyph stroked home">
                    <use xlink:href="#stroked-home"></use>
                </svg></a></li>
        <li class="active">Sản phẩm</li>
    </ol>
</div>
<h3><span id="message"></span></h3>

<div class="panel panel-info">
    <div class="panel-heading">
        <div class="col-md-4">Quản lý sản phẩm
        </div>
        <div class="col-md-2" style="float:right">
        <!-- Nút thêm mới sản phẩm -->
        <a href="./san_pham_add.php" class='btn btn-info'><svg class="glyph stroked plus sign">
                    <use xlink:href="#stroked-plus-sign" />
                </svg> Thêm sản phẩm</a></div>
        <div class="col-md-1"></div>
        <div class="col-md-6" style="float:right;margin-top: 5px">
        <!--Giao diện HTML và Js cho ô tìm kiếm -->
            <form role="search" method="get">
                <div class="form-group">
                    <input name="search" type="text" class="form-control" placeholder="Nhập tên sản phẩm" value="<?= isset($_GET['search']) ? $_GET['search'] : '' ?>">
                </div>
                <button class="btn text-right" style="position: absolute;right: 16px;top: 2px;float:right; padding: 4px 8px 4px 8px;" type="submit"><img src="./public/images/ic_search.png" /></button>
            </form>
        </div>
    </div>
    <div class="panel-body">
        <form action="" method="post">
            <div class="table-responsive">
                <!-- Tạo bảng chứa thông tin sản phẩm -->
                <table class="table table-hover">
                    <thead>
                        <tr class="info">
                            <th class="text-center">ID</th>
                            <th>Tên sản phẩm</th>
                            <th>Danh mục</th>
                            <th>Giá</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Dữ liệu sản phẩm được lặp qua và hiển thị trong các ô tương ứng của bảng -->
                        <?php foreach ($product as $value) { ?>
                            <tr>
                                <!-- Thông tin chi tiết về mỗi sản phẩm -->
                                <td style="vertical-align: middle;text-align: center;"><strong><?php echo $value['id']; ?></strong></td>
                                <td><img src="../public/images/product/<?php echo $value['image_link']; ?>" alt="" style="width: 50px;float:left;margin-right: 10px;"><strong><?php echo $value['name']; ?></strong>
                                    <p style="font-size: 12px;margin-top: 4px;">View: <?php echo $value['view']; ?> <span> | Đã bán :<?php echo $value['buyed']; ?></span></p>
                                </td>
                                <td style="vertical-align: middle"><strong><?php echo $value['namecatalog']; ?></strong></td>
                                <td style="vertical-align: middle">
                                    <?php
                                    if ($value['discount'] > 0) {
                                        $price_new = $value['price'] - $value['discount'];
                                    ?>
                                        <strong><?php echo number_format($price_new); ?> VNĐ</strong><br><del><?php echo number_format($value['price']); ?> VNĐ</del>
                                    <?php } else { ?>
                                        <strong><?php echo number_format($value['price']); ?> VNĐ</strong>
                                    <?php } ?>
                                </td>
                                <td class="list_td aligncenter">
                                    <a href="./san_pham_edit.php?id=<?= $value['id'] ?>" title="Sửa"><span class="glyphicon glyphicon-edit"></span></a>&nbsp;&nbsp;&nbsp;
                                    <a href="./san_pham_delete.php?id=<?php echo $value['id']; ?>" title="Xóa" class="remove" onclick=" return confirm('Xóa sản phẩm sẽ đặt số lượng bằng 0')"> <span class="glyphicon glyphicon-remove"></span> </a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <?= $pagination->render() ?>
            </div>
    </div>
</div>
</form>
<?php include('./layout/footer.php'); ?>