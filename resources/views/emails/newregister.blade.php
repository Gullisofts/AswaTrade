@component('mail::message')
تأكيد البريد الإليكتروني

يرجى تأكيد بريدك الإليكتروني بالذهاب للرابط بالأسفل:

<br>
<br>

{{ $data["verificationlink"] }}

<br>
<br>

تحياتنا,<br>
{{ config('app.name') }}
@endcomponent
