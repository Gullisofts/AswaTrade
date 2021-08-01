@component('mail::message')
# استعادة كلمة المرور

لاستعادة كلمة المرور عليك الذهاب إلى الرابط بالأسفل:
<br>
ملحوظة هامة: هذا الرابط صالح فقط لمدة ساعة
<br>
{{ $data['resetlink'] }}

شكرا لتواصلك,<br>
{{ config('app.name') }}
@endcomponent
