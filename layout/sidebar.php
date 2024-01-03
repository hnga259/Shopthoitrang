<?php
require_once('./core/database.php');
$catalog_id = isset($_GET['catalog_id']) ? $_GET['catalog_id'] : NULL;
$price_from = isset($_GET['price_from']) ? $_GET['price_from'] : NULL;
$price_to = isset($_GET['price_to']) ? $_GET['price_to'] : NULL;
$catalog = $db->getAll("SELECT * from catalog where parent_id = 1 order by sort_order asc");
foreach ($catalog as $key => $value) {
    $subs = $db->getAll("SELECT * from catalog where parent_id = {$value['id']}");
    $catalog[$key]['sub'] = $subs;
}
?>
<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 clearpaddingl">
    <div class="panel panel-info" style="margin-bottom: 5px;">
        <div class="panel-heading" style="background-color: #f2f2f2;color: #111">
            <h3 class="panel-title">TÌM KIẾM</h3>
        </div>
        <div class="panel-body">
            <form class="form-horizontal text-center" method="get" action="search.php">
                <div class="form-group form-group-sm">
                    <label class="col-sm-5 clearpaddingl control-label" for="formGroupInputSmall">Danh mục</label>
                    <div class="col-sm-7" style="padding-left: 0px">
                        <select class="form-control" name="catalog_id">
                            <?php foreach ($catalog as $value) { ?>
                                <option value="<?php echo $value['id']; ?>" style='font-weight: bold' <?php if ($value['id'] == $catalog_id) echo 'selected'; ?>><?php echo $value['name']; ?></option>
                                <?php foreach ($value['sub'] as $val) { ?>
                                    <option value="<?php echo $val['id']; ?>" <?php if ($val['id'] == $catalog_id) echo 'selected'; ?>>&nbsp;&nbsp;&nbsp;<?php echo $val['name']; ?></option>
                                <?php } ?>

                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group form-group-sm">
                    <label class="col-sm-5 control-label" for="formGroupInputSmall">Giá từ:</label>
                    <div class="col-sm-7" style="padding-left: 0px">
                        <select class="form-control" name="price_from">
                            <?php for ($i = 0; $i < 1400000; $i = $i + 100000) { ?>
                                <option value="<?php echo $i ?>" <?php if ($i == $price_from) echo 'selected'; ?>><?php echo number_format($i); ?> VNĐ</option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group form-group-sm">
                    <label class="col-sm-5 control-label" for="formGroupInputSmall">đến:</label>
                    <div class="col-sm-7" style="padding-left: 0px">
                        <select class="form-control" name="price_to">
                            <?php for ($i = 100000; $i < 1500000; $i = $i + 100000) { ?>
                                <option value="<?php echo $i ?>" <?php if ($i == $price_to) echo 'selected'; ?>><?php echo number_format($i); ?> VNĐ</option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <button class="btn btn-info" type="submit"><i class="fa fa-search"></i> Tìm kiếm</button>
            </form>
        </div>
    </div>
    <div class="panel panel-info">
        <div class="panel-body" style="padding:0px">
            <div class="list-group">
                <?php
                foreach ($catalog as $value) { ?>
                    <div class="list-group">
                        <button class="list-group-item dropdown-btn">
                            <a href="catalog.php?id=<?php echo $value['id']; ?>" class=""><?php echo $value['name']; ?></a>
                            <i class="fa fa-caret-down"></i>
                        </button>
                        <div class="dropdown-container" style="display: none">
                            <?php
                            foreach ($value['sub'] as $val) { ?>
                                <a href="catalog.php?id=<?php echo $value['id']; ?>" class="list-group-item"><?php echo $val['name']; ?></a>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>

</div>

<script>
    var dropdown = document.getElementsByClassName("dropdown-btn");
    var i;

    for (i = 0; i < dropdown.length; i++) {
        dropdown[i].addEventListener("click", function() {
            this.classList.toggle("active");
            var dropdownContent = this.nextElementSibling;
            if (dropdownContent.style.display === "block") {
                dropdownContent.style.display = "none";
            } else {
                dropdownContent.style.display = "block";
            }
        });
    }
</script>