<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{config('app.name')}}</title>
</head>
<body>
    <?php
    if(isset($noval))
    {
        echo $noval;
    }
    else
    {
        echo"<pre>";
        print_r($finalresponse);
        echo"</pre>";
    }

    ?>
</body>
</html>