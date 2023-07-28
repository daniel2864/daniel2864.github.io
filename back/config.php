<?php
 
    require 'environment.php';
    
    if (ENVIRONMENT == 'development') {
        define('BASE_URL', 'http://127.0.0.1/');
        $config['dbname'] = 'php_puro';  
        $config['host'] = '127.0.0.1';  
        $config['dbuser'] = 'root';
        $config['dbpass'] = '123456';
    }
    else { // ENVIRONMENT = 'production'
        define('BASE_URL', 'www.meusite.com');
        $config['dbname'] = 'estrutura_mvc';
        $config['host'] = '127.0.0.1';
        $config['dbuser'] = 'root';
        $config['dbpass'] = '';
    }

    global $db;

    try {
        $db = new PDO('mysql:dbname='.$config['dbname'].';host='.$config['host'],
                        $config['dbuser'],
                        $config['dbpass']);
    } catch(PDOException $e) {
        die($e->getMessage());
        exit();
    }

?>