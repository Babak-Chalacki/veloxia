<?php

/** @var Veloxia\Core\Router $router */

$router->get('/hello' , function (){
    return ['message' => 'this is hello'];
});
