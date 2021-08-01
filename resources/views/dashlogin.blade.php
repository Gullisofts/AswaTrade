
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{config('app.name')}} | تسجيل دخول</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@200&display=swap" rel="stylesheet">
    <style>
        body
        {
            background: #eee;
        }
        #login
        {
            direction: rtl;
            text-align: right;
            font-family: 'Tajawal', sans-serif;
        }
        #login .container #login-row #login-column #login-box
        {
            margin-top: 120px;
            max-width: 600px;
            min-height: 320px;
            background-color: #fff;
            box-shadow: 1px 3px 5px 0px rgba(0,0,0,0.39);
            -webkit-box-shadow: 1px 3px 5px 0px rgba(0,0,0,0.39);
            -moz-box-shadow: 1px 3px 5px 0px rgba(0,0,0,0.39);
        }
        #login .container #login-row #login-column #login-box #login-form
        {
            padding: 20px;
        }
        #login .container #login-row #login-column #login-box #login-form #register-link
        {
            margin-top: -85px;
        }

    </style>
</head>
<body>
    <div id="login">
        <div class="container">
            <div id="login-row" class="row justify-content-center align-items-center">
                <div id="login-column" class="col-md-6">
                    <div id="login-box" class="col-md-12">
                        <form id="login-form" class="form" action="/dashboardloginproc" method="post">
                            @CSRF
                            <h3 class="text-center text-info mb-4">تسجيل الدخول</h3>

                            @foreach ($errors->all() as $error)
                            <p class="alert alert-danger">{{ $error }}</p>
                            @endforeach

                            @if(Session::has("loginerror"))
                            <p class="alert alert-danger">{{Session::get("loginerror")}}</p>
                            @endif

                            @if(Session::has("loginwait"))
                            <p class="alert alert-danger">{{Session::get("loginwait")}}</p>
                            @endif

                            @if(session()->has('newlogintimes') && !Session::has("loginwait"))
                            <p class="alert alert-danger">بقيت لديك {{5-session()->get('newlogintimes')}} محاولات</p>
                            @endif
                            <div class="form-group">
                                <label for="username" class="text-info">اسم المستخدم:</label><br>
                                <input type="text" name="username" id="username" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="password" class="text-info">كلمة المرور:</label><br>
                                <input type="password" name="password" id="password" class="form-control">
                            </div>
                            <div class="form-group text-center">
                                <input type="submit" name="submit" class="btn btn-info btn-md" value="دخول">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>