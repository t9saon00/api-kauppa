<?php

function get_views()
{

    $views = array(
        array(
            "path" => "/", 
            "template" => "layout.php",
            "template_part" => "/home.php",
            "show_in_menu" => true
        ),
        array(
            "path" => "/kotisivu", 
            "template" => "layout.php",
            "template_part" => "/home.php",
            "show_in_menu" => true
        ),
        array(
            "path" => "/add-product",
            "template" => "layout.php",
            "template_part" => "/add-product.php",
            "show_in_menu" => true
        ),
        array(
            "path" => "/view-products",
            "template" => "layout.php",
            "template_part" => "/view-products.php",
            "show_in_menu" => true
        ),
       /* array(
            "path" => "/add-user",
            "template" => "layout.php",
            "template_part" => "/add-user.php",
            "show_in_menu" => true
        ), */

    );
    return $views;
}
