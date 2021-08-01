@component('mail::message')
إستعادة كلمة المرور

كلمة المرور الجديدة الخاصة بحسابك:
<br>
{{ $data["newpassword"] }}
<br>
يمكنك تغييرها من ملف الشخصي

تحياتنا,<br>
{{ config('app.name') }}
@endcomponent
