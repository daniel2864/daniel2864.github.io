<?php
    use Core\Core; 
    session_start(); 
    header("Access-Control-Allow-Origin: *");  
    header("Access-Control-Allow-Methods: *");
    
    require '../config.php';
    require '../routes.php';
    require '../vendor/autoload.php';

  
    $c = new Core();
    $c->run();
?>