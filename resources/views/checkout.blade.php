<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{config('app.name')}}</title>

    <script src="https://test.oppwa.com/v1/paymentWidgets.js?checkoutId={{$checkout_id}}"></script>

</head>
<body>
    <form action="/after_payment" class="paymentWidgets" data-brands="VISA MASTER"></form>
</body>
</html>