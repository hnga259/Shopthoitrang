<?php
$login = [];
if (isset($_SESSION['admin'])) {
    $login =  $_SESSION['admin'];
}
if (isset($_COOKIE['admin'])) {
    $login = unserialize($_COOKIE['admin']);
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Trang Quản Trị</title>
    <link href="./public/css/bootstrap.min.css" rel="stylesheet">
    <link href="./public/css/datepicker3.css" rel="stylesheet">
    <link href="./public/css/styles.css" rel="stylesheet">
    <link href='https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css' rel='stylesheet' type='text/css'>
    <style id="glyphs-style" type="text/css">
        .glyph {
            fill: currentColor;
            display: inline-block;
            margin-left: auto;
            margin-right: auto;
            position: relative;
            text-align: center;
            vertical-align: middle;
            width: 70%;
            height: 70%;
        }

        .glyph.sm {
            width: 30%;
            height: 30%;
        }

        .glyph.md {
            width: 50%;
            height: 50%;
        }

        .glyph.lg {
            height: 100%;
            width: 100%;
        }

        .glyph-svg {
            width: 100%;
            height: 100%;
        }

        .glyph-svg .fill {
            fill: inherit;
        }

        .glyph-svg .line {
            stroke: currentColor;
            stroke-width: inherit;
        }

        .glyph.spin {
            animation: spin 1s linear infinite;
        }

        @-webkit-keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        @-moz-keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }
    </style>
    <script>
        function myFunction() {
            document.getElementById("myDropdown").classList.toggle("show");
        }
        window.onclick = function(event) {
            if (!event.target.matches('.dropbtn')) {
                var dropdowns = document.getElementsByClassName("dropdown-content");
                var i;
                for (i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
        }
    </script>
</head>
<body>
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#sidebar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#"><span>4FASHION </span>Admin</a>
                <ul class="user-menu">
                    <li class="dropdown">
                        <a href="#" class="dropbtn" onclick="myFunction()"><svg class="glyph stroked male-user">
                                <use xlink:href="#stroked-male-user"></use>
                            </svg> <?php echo $login['name']; ?> <span class="caret"></span></a>
                        <ul class="dropdown-menu dropdown-content" id="myDropdown" role="menu">
                            <!-- <li><a href="<?php echo ('admin/edit/' . $login['id']);    ?>"><svg class="glyph stroked male-user">
                                        <use xlink:href="#stroked-male-user"></use>
                                    </svg> Tài khoản của bạn</a></li> -->
                            <li><a href="./dang_xuat.php"><svg class="glyph stroked cancel">
                                        <use xlink:href="#stroked-cancel"></use>
                                    </svg> Đăng xuất</a></li>
                        </ul>
                    </li>
                </ul>
            </div>

        </div>
    </nav>
    <div id="sidebar-collapse" class="col-sm-3 col-lg-2 sidebar">
        <ul class="nav menu">
            <li>
                <a href="./">
                    <svg class="glyph stroked home">
                        <use xlink:href="#stroked-home" />
                    </svg> Trang chủ
                </a>
            </li>
            <li><a href="danh_muc.php"><svg class="glyph stroked clipboard with paper">
                        <use xlink:href="#stroked-clipboard-with-paper" />
                    </svg> Danh mục</a></li>
            <li><a href="san_pham.php"><svg class="glyph stroked bag">
                        <use xlink:href="#stroked-bag"></use>
                    </svg> Sản phẩm</a></li>
            <li><a href="don_hang.php"><svg class="glyph stroked notepad ">
                        <use xlink:href="#stroked-notepad" />
                    </svg> Đơn đặt hàng</a></li>
        </ul>
    </div>
    <div id="sidebar-collapse" class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
        <?php include('message.php') ?>