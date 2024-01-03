    </div>
    <script src="./public/js/jquery-3.1.1.js" type="text/javascript"></script>
    <script src="./public/js/lumino.glyphs.js"></script>
    <script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"> </script>
    <script src="./public/js/bootstrap.min.js"></script>
    <script src="./public/js/chart.min.js"></script>
    <script src="./public/js/chart-data.js"></script>
    <script src="./public/js/easypiechart.js"></script>
    <script src="./public/js/easypiechart-data.js"></script>
    <script src="./public/js/bootstrap-datepicker.js"></script>
    <script>
        $(document).ready(function($) {
            $("#logout").click(function(e) {
                e.preventDefault();
                window.location.href = "./logout.php"
            });
            $('#calendar').datepicker({});
            ! function($) {
                $(document).on("click", "ul.nav li.parent > a > span.icon", function() {
                    $(this).find('em:first').toggleClass("glyphicon-minus");
                });
                $(".sidebar span.icon").find('em:first').addClass("glyphicon-plus");
            }(window.jQuery);
            $(window).on('resize', function() {
                if ($(window).width() > 768)
                    $('#sidebar-collapse').collapse('show')
            })
            $(window).on('resize', function() {
                if ($(window).width() <= 767)
                    $('#sidebar-collapse').collapse('hide')
            })
        });
    </script>
    <script type="text/javascript">
        $("#li-nav").click(function() {
            var isSomethingTrue = $("#li-nav").hasClass("open");

            if (isSomethingTrue) {
                $('#li-nav').removeClass('open');
            } else {
                $('#li-nav').addClass('open');
            }
            $('ul').removeAttr("style");
        });
        let page = window.location.pathname.split('/')[window.location.pathname.split('/').length - 1]
        document.querySelectorAll('.nav.menu li').forEach((e) => {
            if (page == 'index.php') {
                document.querySelector('.nav.menu li').classList.add('active')
            } else if (page) {
                if (page.includes(e.querySelector('a').getAttribute('href'))) {
                    e.classList.add('active')
                } else {
                    e.classList.remove('active')
                }
            } else {
                document.querySelector('.nav.menu li').classList.add('active')
            }
        })
    </script>
    </body>
    </html>