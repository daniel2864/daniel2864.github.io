<?php

namespace Controllers;

use \Core\Controller;
use \Models\Plan;

class PlansController extends Controller{

    public function index() {

        $response = Plan::get();    
        $status_code = 200;

        $this->returnJson($response, $status_code);
    }

    public function show(){

    }
     
}