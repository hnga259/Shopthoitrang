<?php
require('../core/database.php');
require('../core/flash.php');
require('./middleware.php');
$list = $db->getAll("SELECT * FROM catalog ORDER BY parent_id ASC");
?>
<?php include('./layout/head.php'); ?>
<div class="row">
    <ol class="breadcrumb">
        <li><a href="#"><svg class="glyph stroked home">
                    <use xlink:href="#stroked-home"></use>
                </svg></a></li>
        <li class="active">Danh mục</li>
    </ol>
</div><!--/.row-->

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="col-md-8">Quản lý danh mục</div>
                <div class="col-md-4 text-right"><a href="./danh_muc_add.php" class='btn btn-info'><svg class="glyph stroked plus sign">
                            <use xlink:href="#stroked-plus-sign" />
                        </svg> Thêm mới</a></div>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr class="info">
                                <th>ID</th>
                                <th>Tên danh mục</th>
                                <th>Danh Mục Cha</th>
                                <th>Thứ tự</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                            </tr>
                            <?php foreach ($list as $value) { ?>
                                <tr>
                                    <td><strong><?php echo $value['id']; ?></strong></td>
                                    <td><strong><?php echo $value['name']; ?></strong></td>
                                    <td><strong>
                                            <?php
                                            if ($value['parent_id'] == 0)
                                                echo "Menu gốc";
                                            else {
                                                $re = $db->getFirst("SELECT * FROM catalog WHERE id = {$value['parent_id']}");
                                                echo $re['name'];
                                            }
                                            ?>
                                        </strong>
                                    </td>
                                    <td><strong><?php echo $value['sort_order']; ?></strong></td>
                                    <td class="list_td aligncenter">
                                        <a href="./danh_muc_edit.php?id=<?php echo $value['id']; ?>" title="Sửa"><span class="glyphicon glyphicon-edit"></span></a>&nbsp;&nbsp;&nbsp;
                                        <a href="./danh_muc_delete.php?id=<?php echo $value['id']; ?>" title="Xóa"> <span class="glyphicon glyphicon-remove" onclick=" return confirm('Bạn chắc chắn muốn xóa')"></span> </a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div><!--/.row-->

<?php include('./layout/footer.php'); ?>