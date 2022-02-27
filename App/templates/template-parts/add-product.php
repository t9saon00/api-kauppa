<div>Add product</div>

<form method="post" action="<?php

use Slimapp\Classes\JwtClass;
use Slimapp\Classes\Session;
use Slimapp\Classes\RemoteRequests;


echo APP_BASEPATH . "/add-product" ?>" id="Add-product-form"> 
    <input class="arg" name="title" type="text" id="Add-title" placeholder="Add title">
    <input class="arg" name="description" type="text" id="Add-description" placeholder="Add description">
    <input class="arg" name="category" type="text" id="Add-category" placeholder="Add category">
    <input class="arg" name="location" type="text" id="Add-location" placeholder="Add location">
    <input class="arg" name="price" type="text" id="Add-price" placeholder="Add price">
    <!-- <input class="arg" name="image" type="file" id="Add-image" placeholder="Add image"> -->
    <input type="submit" value="Add product">
    <?php Session::the_nonce_field("add_product_form")?>
</form>

