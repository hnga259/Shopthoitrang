<?php
require('../core/database.php');
require('../core/flash.php');
require('../core/upload.php');
require('./middleware.php');

// Mảng dữ liệu mặc định cho form
$data_form = [
    'name' => '',
    'image' => '',
    'price' => '',
    'discount' => '',
    'content' => '',
    'catalog_id' => '',
];

// Xử lý khi có dữ liệu được gửi đi (submit)
if (isset($_POST['submit'])) {
    $path = './upload/product/';
    // Sử dụng class Upload để xử lý hình ảnh  tải lên và lưu đường dẫn vào biến $image_link và $image_list
    $upload = new Upload;
    $image_link = '';
    $image_link = $upload->put($_FILES['image']);

    $image_list = array();
    $image_list = $upload->put_multiple($_FILES['list_image']);
    $image_list = json_encode($image_list);

    // Tạo mảng dữ liệu mới
    $data = [
        'name' => $_POST['name'],
        'image_link' => $image_link,
        'image_list' => $image_list,
        'content' => $_POST['content'],
        'catalog_id' => $_POST['catalog_id'],
        'price' => $_POST['price'],
        'discount' => $_POST['discount'],
    ];
    $kq = $db->create('product', $data);
    if ($kq) {
        // Thêm mới chi tiết size
        $data1 = array();
        $input = array();
        $sizes = $db->getAll("select * from sizes");
        // Lặp qua dsach các size để thêm mới chi tiết sl cho từng size
        foreach ($sizes as $size) {
            $id_size = $_POST['size_' . $size['id']];
            $quantity = $_POST['quantity_' . $size['id']];
            if ($id_size > 0 && $quantity > 0) {
                $input = array();
                $input['order'] = array('id', 'DESC');
                $input['limit'] = array('1', '0');
                $get_id = $db->getLastId('product');
                $id_pro = $get_id['id'];
                $data2 = [
                    'product_id' => $id_pro,
                    'size_id' => $id_size,
                    'quantity' => $quantity,
                ];
                $kq1 = $db->create('sizedetail', $data2);
            }
        }
        // Đặt thông báo thành công và chuyển hướng về trang quản lý sản phẩm
        Flash::set('message_success', 'Thêm sản phẩm thành công');
        header('location: ./san_pham.php');
    } else {
        // Đặt thông báo thất bại
        Flash::set('message_fail', 'Tạo sản phẩm thất bại');
    }
    // Cập nhật mảng dữ liệu form với dữ liệu vừa submit
    $data_form = $data;
}

// Lấy danh sách danh mục sản phẩm
$catalog = $db->getAll("SELECT * from catalog where parent_id = 1 order by sort_order asc");
foreach ($catalog as $key => $value) {
    $subs = $db->getAll("SELECT * from catalog where parent_id = {$value['id']}");
    $catalog[$key]['sub'] = $subs;
}
// Đóng phiên làm việc với session
session_write_close();
?>
<?php include('./layout/head.php'); ?>
<div class="row">
    <ol class="breadcrumb">
        <li><a href="#"><svg class="glyph stroked home">
                    <use xlink:href="#stroked-home"></use>
                </svg></a></li>
        <li class="active">Sản phẩm</li>
    </ol>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                Thêm sản phẩm
            </div>
            <div class="panel-body">
                <form class="form-horizontal" method="post" enctype="multipart/form-data">
                     <!-- Các trường dữ liệu của form -->
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Tên sản phẩm</label>
                        <div class="col-sm-5">
                            <input type="text" name='name' class="form-control" id="inputEmail3" placeholder="" value="<?php echo $data_form['name']; ?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Hình ảnh</label>
                        <div class="col-sm-5">
                            <input type="file" id="image" name="image" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Hình ảnh kèm theo</label>
                        <div class="col-sm-5">
                            <input type="file" id="list_image" name="list_image[]" multiple>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Danh mục</label>
                        <div class="col-sm-5">
                            <select class="form-control" name="catalog_id" required>
                                <option value="">--- Chọn danh mục sản phẩm ---</option>
                                <?php
                                foreach ($catalog as $value) {
                                    if (count($value['sub']) > 1) {
                                ?>
                                        <option value="<?php echo $value['id']; ?>" <?php if ($data_form['catalog_id'] == $value['id']) echo 'selected'; ?>><?php echo $value['name']; ?></option>
                                        <?php foreach ($value['sub'] as $val) { ?>
                                            <option value="<?php echo $val['id']; ?>" <?php if ($data_form['catalog_id'] == $val['id']) echo 'selected'; ?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $val['name']; ?></option>
                                        <?php }
                                        ?>
                                    <?php } else { ?>
                                        <option value="<?php echo $value['id']; ?>" <?php if ($data_form['catalog_id'] == $value['id']) echo 'selected'; ?>><?php echo $value['name']; ?></option>
                                <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Giá tiền</label>
                        <div class="col-sm-5">
                            <input type="text" name='price' class="form-control" id="inputEmail3" placeholder="" value="<?php echo $data_form['price']; ?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Giảm giá</label>
                        <div class="col-sm-5">
                            <input type="text" name='discount' class="form-control" id="inputEmail3" placeholder="" value="<?php echo $data_form['discount']; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Chi tiết</label>
                        <div class="col-sm-8">
                            <textarea class="form-control" rows="3" name="content" id='content'><?php echo $data_form['content']; ?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail4" class="col-sm-2 control-label">Chi tiết số lượng</label>
                        <?php
                        $sizes = $db->getAll("SELECT * FROM sizes");
                        foreach ($sizes as $size) {
                        ?>
                            <label for="inputEmail3" class="col-lg-1" style="margin-top: 10px;text-align: right"><?php echo $size['name'] ?></label>
                            <div class="col-lg-1">
                                <input type="hidden" name='size_<?php echo $size['id']; ?>' class="form-control" placeholder="" value='<?php echo $size['id'] ?>'>
                                <input type="text" name='quantity_<?php echo $size['id']; ?>' class="form-control" placeholder="" value="0">
                            </div>

                        <?php } ?>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-5">
                            <button type="submit" name="submit" class="btn btn-primary">Thêm mới</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include('./layout/footer.php'); ?>
<!-- Tích hợp trình soạn thảo văn bản CKEditor vào trang web -->
<script src="./public/js/ckeditor/ckeditor.js"></script> <!-- Mã nguồn -->
<script src="./public/js/ckeditor/config.js"></script> <!-- Cấu hình -->
<script src="./public/js/ckeditor/lang/vi.js"></script> <!-- Ngôn ngữ -->
<script src="./public/js/ckeditor/styles.js"></script> <!-- Hiển thị -->
<script>
    CKEDITOR.replace('content');
</script>