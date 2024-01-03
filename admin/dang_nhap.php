<?php
require('../core/database.php');
require('../core/flash.php');

$errors = [];
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = md5($_POST['password']);
    $remember = isset($_POST['remember']);
    $check = $db->getFirst("SELECT * FROM admin WHERE email = '$email' and password = '$password'");
    if ($check) {
        if ($remember) {
            setcookie('admin', serialize($check), time() + 3600 * 24 * 30, '/');
        } else {
            $_SESSION['admin'] = $check;
        }
        Flash::set('message_success', 'Đăng nhập thành công');
        header('location: ./index.php');
    } else {
        $errors['login'] = 'Tài khoản hoặc mật khẩu không đúng';
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Đăng Nhập Trang Quản Trị</title>
    <link href="./public/css/bootstrap.min.css" rel="stylesheet">
    <link href="./public/css/styles.css" rel="stylesheet">
</head>

<body>
    <div class="row">
        <div class="col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-4 col-md-offset-4">
            <div class="login-panel panel panel-default">
                <div class="panel-heading" style=" background-color: #30a5ff;margin-bottom: 10px;color: white;text-align: center;font-size: 25px;">ĐĂNG NHẬP</div>
                <div class="panel-body">
                    <form role="form" method="post">
                        <fieldset>
                            <div class="form-group">
                                <input class="form-control" placeholder="E-mail" name="email" type="email" autofocus="" required>
                            </div>
                            <div class="form-group">
                                <input class="form-control" placeholder="Mật khẩu" name="password" type="password" value="" required>
                            </div>
                            <?php if (isset($errors['login'])) : ?>
                                <div class="alert bg-danger" role="alert" id="AlertBox">
                                    <?= $errors['login'] ?>
                                </div>
                            <?php endif ?>
                            <div class="checkbox">
                                <label>
                                    <input name="remember" type="checkbox" value="Remember Me">Ghi nhớ đăng nhập
                                </label>
                            </div>
                            <button type="submit" name="login" class="btn btn-primary">Đăng nhập</button>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>