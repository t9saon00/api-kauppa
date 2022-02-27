
<!doctype html>
<html>

<head>
  <link rel="stylesheet" href="<?php

use Slimapp\Classes\Session;

echo APP_BASEPATH . "/public/css/style.css"; ?>">

  <title>Slimapp</title>
</head>

<body> 

    <div>Login</div>
 
    <form method="post" id="Login-form" action="<?php echo APP_BASEPATH . '/login';?>">
        <input id="Username" class="login-form-field active" type="text" name="name" placeholder="name"/>
        <input id="Password" class="login-form-field active" type="password" name="password" placeholder="password"/>
        <input id="Submit-btn" class="login-btn active" type="submit" value="Login"/> 
        <?php Session::the_nonce_field("login_form");?>
    </form> 


    <div>Add user</div>

<form method="post" action="<?php

use Slimapp\Classes\JwtClass;
use Slimapp\Classes\RemoteRequests;


echo APP_BASEPATH . "file:///Macintosh HD/Users/topias_a10/Dropbox/add-user" ?>" id="Add-user-form">
    <input class="arg" name="name" type="text" id="Add-username" placeholder="Add username">
    <input class="arg" name="email" type="text" id="Add-email" placeholder="Add email">
    <input class="arg" name="phone" type="text" id="Add-phone" placeholder="Add phone">
    <input class="arg" name="password" type="password" id="Add-password" placeholder="Add password">
    <input type="submit" value="Add user">
    <?php Session::the_nonce_field("add_user_form")?>
</form>

<?php 
    $headers2 = array(
        'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpYXQiOjE2Mzg4NjYzMzUsImp0aSI6IjBsVWM0UkFrVDFlZ3BualNsRkN3WWc9PSIsImlzcyI6Imh0dHA6XC9cL3NsaW1hcHA6ODg4OCIsIm5iZiI6MTYzODg2NjMzNSwiZXhwIjoxNjM4ODY4MTM1LCJkYXRhIjp7ImNvbnN1bWVyX2tleSI6ImFwaW5hIn19.qgUUK4JEiN6WEOme_atG0q51tz3IM_vpxBoSZ_AQoLe1WX93r6ZId2NZkG3zY4wQf3Y3jiZ0y2_cMSgU842n1g', 
        'Accept: application/json'
    );

    $headers = array(
        'Authorization: Basic ' . base64_encode("apina:apina"), 
        'Accept: application/json'
    );
    
    //$request = RemoteRequests::remote_get("http://slimapp:8888/api/v1/get_token", '', $headers );

    $request2 = RemoteRequests::remote_get("http://slimapp:8888/api/v1/get_example", array("test"=>"123"), $headers2 );

   // print_r($request2); 

?>

</body>

</html>