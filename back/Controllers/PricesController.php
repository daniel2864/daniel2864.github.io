<?php

namespace Controllers;

use \Core\Controller;
use \Models\Price;

class PricesController extends Controller{
    
    public function index() {

        $response = Price::get();  
        $status_code = 200;

        $this->returnJson($response, $status_code);
    }
     
}