<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{config('app.name')}}</title>

        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
        <link rel="stylesheet" href="{{asset('css/app.css')}}">
        <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    </head>
    <body>

        @if(isset($invalid))
        <div class="reset_done_msg">
            <div class="reset_msg img-thumbnail">
                <i class="fas fa-exclamation-circle reset_correct_ico" data-aos="flip-left" data-aos-delay="500"></i>
                <h4 class="mt-3 first_reset_heading" data-aos="fade-left" data-aos-delay="1000">لقد أتبعت رابط غير صحيح</h5>
                <a href="/" type="button" class="btn btn-primary mt-3 reset_done_btn" data-aos="fade-up" data-aos-delay="2000">الذهاب للرئيسية</a>
            </div>
        </div>
        @elseif(isset($expired))
        <div class="reset_done_msg">
            <div class="reset_msg img-thumbnail">
                <i class="fas fa-exclamation-circle reset_correct_ico" data-aos="flip-left" data-aos-delay="500"></i>
                <h4 class="mt-3 first_reset_heading" data-aos="fade-left" data-aos-delay="1000">إنتهت صلاحية هذا الرابط يرجى إعادة طلب استعادة كلمة المرور مرة أخرى</h5>
                <a href="/" type="button" class="btn btn-primary mt-3 reset_done_btn" data-aos="fade-up" data-aos-delay="2000">الذهاب للرئيسية</a>
            </div>
        </div>
        @else
        <div class="reset_done_msg">
            <div class="reset_msg img-thumbnail">
                <i class="fas fa-check-square reset_correct_ico" data-aos="flip-left" data-aos-delay="500"></i>
                <h4 class="mt-3 first_reset_heading" data-aos="fade-left" data-aos-delay="1000">تم ارسال كلمة السر الجديدة إلى البريد الإليكتروني</h5>
                <h5 class="mt-3 second_reset_heading" data-aos="fade-left" data-aos-delay="1500">يمكنك تغييرها من خلال الملف الشخصي الخاص بك</h5>
                <a href="/" type="button" class="btn btn-primary mt-3 reset_done_btn" data-aos="fade-up" data-aos-delay="2000">الذهاب للرئيسية</a>
            </div>
        </div>
        @endif

        <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
        <script src="{{asset('js/plugins.js')}}"></script>

        <script>
            AOS.init();
        </script>

    </body>
</html>