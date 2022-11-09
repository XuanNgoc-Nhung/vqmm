<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Lucky wheel</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body
    style="min-height: 100vh;background: url({{ asset('storage/background.png') }});
background-repeat: no-repeat;background-size: 100% 100%;">
    <div id="header">
        <nav class="navbar">
            <div class="container">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                        data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="{{ route('luckywheel.index') }}">
                        <img src="{{ asset('storage/logo_lucky_wheel.png') }}" alt=""></a>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav navbar-right">
                        <li>
                            <div class="contact-form">
                                <img src="{{ asset('storage/input-phone.png') }}" alt="">
                                <input class="contact-form__input" type="text" name="phoneNumber" id="phone">
                            </div>
                        </li>
                        <li>
                            <div id="get-spin">
                                <button class="contact-submit__btn" id="get-spin">
                                    <img src="{{ asset('storage/record.png') }}" alt="">
                                </button>
                            </div>
                        </li>
                    </ul>
                </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
        </nav>

    </div>
    <div id="body">
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    <img src="{{ asset('storage/content.png') }}" alt="">
                </div>
                <div class="col-sm-6">
                    <div id="chart"></div>
                    <div id="question">
                        <h1></h1>
                    </div>
                    <script src="https://d3js.org/d3.v3.min.js" charset="utf-8"></script>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script> <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
    <script>
        $('#get-spin').on('click', function() {
            let phone = $('#phone').val();
            if (!phone) {
                swal(
                    "Thông báo",
                    "Vui lòng nhập số điện thoại",
                    "error"
                );
            } else {
                let phonePattern = new RegExp('(84|0[3|5|7|8|9])+([0-9]{8})');
                let checkPattern = phonePattern.test(phone);
                if (!checkPattern) {
                    swal(
                        "Thông báo",
                        "SĐT : " + phone + " không hợp lệ",
                        "error"
                    );
                } else {
                    clearPhone();
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: 'get',
                        url: '/get-spin/' + phone,
                        success: function(res) {
                            if (res.status) {
                                savePhone(phone);
                                swal(
                                    "Chúc mừng",
                                    "Bạn nhận được 1 lượt quay từ chương trình",
                                    "success"
                                );
                            } else {
                                swal(
                                    "Thông báo",
                                    "SĐT : " + phone + " đã nhận lượt quay trong hôm nay !",
                                    "error"
                                );
                            }
                        }
                    });
                }
            }

        });
        $('.spin').on('click', function() {
            spin(itemList);
        })

        function savePhone(phone) {
            localStorage.setItem('phone', phone);
        }

        function clearPhone() {
            localStorage.removeItem('phone', phone);
        }
    </script>
</body>

</html>
