<?php

function get_menu()
{

    $menu = array(
        array(
            "path" => "/kotisivu", 
            "name" => "Kotisivu",
            "show_in_menu" => true
        ),
        array(
            "path" => "/add-product",
            "name" => "Add product",
            "show_in_menu" => true
        ),
        array(
            "path" => "/view-products",
            "name" => "View products",
            "show_in_menu" => true
        ),
        /*array(
            "path" => "add-user",
            "name" => "Add user",
            "show_in_menu" => true
        ) */

    );

    return $menu;
};
