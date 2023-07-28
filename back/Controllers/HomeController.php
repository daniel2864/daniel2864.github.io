<?php
/**
 * =============================================================================================
 *                                      sample Model
 * =============================================================================================
 */

namespace Controllers;

use \Core\Controller;

class HomeController extends Controller{
    public function index() {
        
        $response = [
            'name' => 'Daniel Garcia1',
            'email' => 'daniel@gmail.com',
            'Whatsapp' => '+55 (92) 992 772797'
        ];

        $status_code = 200;

        $this->returnJson($response, $status_code);
    }
    public function me() {
        
        $response = [
            'name' => 'Daniel Garcia2',
            'email' => 'daniel@gmail.com',
            'Whatsapp' => '+55 (92) 992 772797'
        ];

        $status_code = 200;

        $this->returnJson($response, $status_code);
    }
}