<?php

namespace Controllers;

use \Core\Controller;

class NotfoundController extends Controller{
    /**
     * By default it outputs an empty JSON
     *
     * @return void
     */
    public function index(){
        //Returning an empty JSON
        return $this->returnJson(array());
    }
}