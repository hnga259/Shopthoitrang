    </div>
    <div class="row" style="display: block;margin-bottom: 100px"></div>
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="footer__about">
                        <div class="footer__logo">
                            <a href="./" style="pointer-events: none;padding: 10px;background-color: white"><img src="./public/images/MANHDT.jpg" alt="" class="img-responsive"></a>
                        </div>
                        <p>Khách hàng là trọng tâm của mô hình kinh doanh độc đáo của chúng tôi, bao gồm cả thiết kế.</p>
                        <a href="#"><img src='./public/images/payment.png'></a>
                    </div>
                </div>
                <div class="col-lg-2 offset-lg-1 col-md-3 col-sm-6">
                    <div class="footer__widget">
                        <h6>FOUR FASHION</h6>
                        <ul>
                            <li><a href="moi.php">Mới</a></li>
                            <li><a href="ban_chay.php>">Bán Chạy</a></li>
                            <li><a href="khuyen_mai.php">Khuyến Mại</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6">
                    <div class="footer__widget">
                        <h6>FOUR FASHION SHOP</h6>
                        <ul>
                            <li><a href="#">Liên hệ với chúng tôi</a></li>
                            <li><a href="#">Phương thức thanh toán</a></li>
                            <li><a href="#">Giao Hàng</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 offset-lg-1 col-md-6 col-sm-6">
                    <div class="footer__widget">
                        <h6>ĐỊA CHỈ EMAIL</h6>
                        <div class="footer__newslatter">
                            <p>Hãy là người đầu tiên biết về hàng mới xuất hiện, xem sách, bán hàng &amp; quảng cáo!</p>
                            <form action="#">
                                <input type="text" placeholder="Nhập Email" style="color: #ffffff">
                                <button type="submit"><i class="fa fa-envelope "></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="footer__copyright__text">
                        <p>Copyright ©
                            <script>
                                document.write(new Date().getFullYear());
                            </script>
                            All rights reserved | This template is made with <i class="fa fa-heart-o" aria-hidden="true"></i> by <a href="#">Nhóm 04 </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <script src="./public/js/jquery-3.1.1.js" type="text/javascript"></script>
    <script src="./public/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="./public/js/raty/jquery.raty.min.js"></script>

    <script type="text/javascript">
        $(function() {
            $.fn.raty.defaults.path = "./public/js/raty/img'); ?>";
            $('.raty').raty({
                score: function() {
                    return $(this).attr('data-score');
                },
                readOnly: true,
            });
        });
    </script>
    <!-- <script src="./public/js/jqzoom_ev/js/jquery.jcarousel.pack.js" type="text/javascript"></script> -->
</body>

</html>