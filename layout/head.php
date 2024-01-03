<?php
require_once('./core/database.php');
require_once('./core/cart.php');

$cart = new Cart;
$carts = $cart->all($db);
$total_items = count($carts);

if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];;
}
if (isset($_COOKIE['user'])) {
    $user = unserialize($_COOKIE['user']);
}

$catalog = $db->getAll("select * from catalog");
$current_page = basename($_SERVER["SCRIPT_FILENAME"]);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>LPT Shop</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" type="text/css" href="./public/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="./public/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <style>
        .raty img {
            width: 16px !important;
            height: 16px !important;
        }
    </style>
</head>

<body>
    <div style="padding-left: 100px;padding-right: 100px">
        <div class="row">
            <nav class="navbar navbar-info re-navbar">
                <div class="container-fluid re-container-fluid">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="#">--- Menu ---</a>
                    </div>
                    <div class="collapse navbar-collapse re-navbar-collapse" id="bs-example-navbar-collapse-1">
                        <ul class="nav navbar-nav">
                            <li><a href="./" style="pointer-events: none"><img src="public/images/MANHDT.jpg" alt="" class="img-responsive" style="width: 196px; height: 40px;"></a></li>
                            <li class="active"><a href="./"><span class="glyphicon glyphicon-home" aria-hidden="true"></span> HOME<span class="sr-only">(current)</span></a></li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Thời trang<span class="caret"></span></a>
                                <ul class="dropdown-menu" id="re-dropdown-menu">
                                    <?php foreach ($catalog as $value) { ?>
                                        <li><a style="padding: 10px 20px;" href="catalog.php?id=<?php echo $value['id'] ?>"><?php echo $value['name']; ?></a></li>
                                    <?php } ?>
                                </ul>
                            </li>
                            <li style="padding-top: 7px;margin-left: 10px">
                                <form method="get" action="search.php">
                                    <button class="btn-search_info" type="submit"><i class="fa fa-search"></i></button>
                                    <input id="seach_info" type="text" name="name" placeholder="Tìm kiếm.." value="<?= isset($_GET['name']) ? $_GET['name'] : '' ?>">
                                </form>
                            </li>
                        </ul>
                        <ul class="nav navbar-nav navbar-right">
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> <span class="glyphicon glyphicon-shopping-cart"><span class="badge"><?php echo $total_items ?></span></span> Giỏ Hàng<span class="caret"></span></a>
                                <ul class="dropdown-menu" style="min-width: 300px;">
                                    <?php
                                    if ($total_items > 0) { ?>
                                        <div class="table-responsive" style="min-width: 400px;">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr style="background-color: #f2f2f2">
                                                        <th>Ảnh</th>
                                                        <th>Tên <span></span></th>
                                                        <th>SL</th>
                                                        <th>Size</th>
                                                        <th>Giá</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    foreach ($carts as $items) {  ?>
                                                        <tr>
                                                            <td> <img style="width: 40px;border-radius: 30%;" src="./public/images/product/<?php echo $items['image_link']; ?>" alt=""></td>
                                                            <td><?php echo $items['name']; ?></td>
                                                            <td><?php echo $items['quantity']; ?></td>
                                                            <td><?php echo $items['size_name']; ?></td>
                                                            <td><?php echo number_format($items['sub_total']); ?> VNĐ</td>
                                                        </tr>
                                                    <?php }
                                                    ?>
                                                </tbody>
                                            </table>
                                            <a href="cart.php" type="button" class="btn btn-success" style="margin-left: 10px"> Chi Tiết Giỏ Hàng </a>
                                            <a href="cart_remove.php" type="button" class="btn btn-danger pull-right" style="margin-right: 10px;background-color: red"> Xóa </a>
                                        </div>
                                    <?php } else { ?>
                                        <p style="color:red;font-weight: bold;float: right;padding-right: 30px">Không có sản phẩm trong giỏ hàng</p>
                                    <?php  } ?>
                                </ul>
                            </li>
                            <!-- <?php if (!isset($user)) { ?>
                                <li><a href="dang_nhap.php>">Đăng nhập</a></li>
                            <?php } else { ?>
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $user['name']; ?><span class="caret"></span></a>
                                    <ul class="dropdown-menu">
                                        <li><a href="user.php">Tài khoản</a></li>
                                        <li role="separator" class="divider"></li>
                                        <li><a href="logout.php">Đăng xuất</a></li>
                                    </ul>
                                </li>
                            <?php } ?> -->
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
        <?php if ($current_page == 'index.php') include('slider.php') ?>
    </div>
    <div class="container">