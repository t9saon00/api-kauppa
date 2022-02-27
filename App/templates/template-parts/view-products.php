<div>View products</div>

<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpForbiddenException;
use Slim\Exception\HttpNotFoundException;
use Slim\Routing\RouteCollectorProxy;
use Slim\Views\PhpRenderer;
use Slimapp\Middleware\BasicAuth;
use Slimapp\Middleware\CsrfProtection;
use slimapp\Sql\DbConnect as db;

?>

<div class="products">
    <?php
       $products = db::get_products(); 

       foreach($products as $product) :

            echo '
            <div class="product-wrapper">
                <div class="title">Tuote: ' . $product['Title'] . '</div>
                <div class="desc">Kuvaus: ' . $product['Description'] . '</div>
                <div class="cat">Kategoria: ' . $product['Category'] . '</div>
                <div class="location">Sijainti: ' . $product['Location'] . '</div>
                <div class="price">Hinta: ' . $product['Price'] . '</div>
            </div>';

       endforeach;
    ?> 
    <script>console.log(<?php echo json_encode($products); ?>);</script>

    
</div>