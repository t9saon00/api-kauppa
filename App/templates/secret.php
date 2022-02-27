
<!doctype html>
<html>

<head>
  <link rel="stylesheet" href="<?php

use Slimapp\Classes\Session;

echo APP_BASEPATH . "/public/css/style.css"; ?>">

  <title>Slimapp</title>
</head>

<body> 

    <div>Secret</div>
 
    <form method="post" id="Secret-form" action="<?php echo APP_BASEPATH . '/secret';?>">
        <input id="Username" class="login-form-field" type="text" name="name" placeholder="name"/>
        <input id="Password" class="login-form-field" type="password" name="password" placeholder="password"/>

        <input id="Secret" class="secret-field active" type="text" name="secret" placeholder="secret"/>
        <input id="Submit-btn" class="login-btn active" type="submit" value="Login"/> 
        <?php Session::the_nonce_field("secret_form");?>
    </form> 

</body>

</html>