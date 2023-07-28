<?php
namespace Core;

use JsonSerializable;

class Controller {
    
    /**
     * Returns the type of the request method, Ex: PUT, PUSH, DELTE, GET, etc...
     *
     * @return void
     */
    protected function getMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * Returns the Authorization sent in the request header
     *
     * @return string
     */
    protected function getAuthorization()
    {
        return $_SERVER['HTTP_AUTHORIZATION'];
    }

    /**
     * Gets the data sent in the request according to the treatment required for each request method
     *
     * @return void
     */
    protected function getRequestData()
    {
     
        switch($this->getMethod()){
            case 'GET':
                return $_GET;
                break;
            
            //PUT and DELETE methods are received with the same treatment
            case 'PUT':
            case 'DELETE':
                $header = getallheaders();
                if (isset($header['Content-Type']) && $header['Content-Type'] == 'application/json') {
                    //Takes the JSON and transforms and decodes it to ARRAY
                    $data = json_decode(file_get_contents('php://input'));
                } else {
                    //It takes the data sent in php://input and converts it from a String to an ARRAY of objects
                    parse_str(file_get_contents('php://input'), $data);
                }

                //Cast by converting array of objects to ARRAY
                return (array) $data;
                break;
            
            case 'POST':
                 
                //In the POST method, the data comes as a JSON, so we decode it into an ARRAY of objects
                $data = json_decode(file_get_contents('php://input'));
                
                //If the data is sent from a <form> it will come in the global variable $_POST
                if (is_null($data))
                    $data = $_POST;
    
                //Cast by converting array of objects to ARRAY
                return (array) $data;
                break;

        }
    }

    /**
     * Converts an array into a JSON response and writes it to the request output
     *
     * @param [array] $array
     * @return void
     */
    protected function returnJson($array, $status_code = NULL)
    {
        //Setting the response header
        http_response_code(intval($status_code));
         
        // header("HTTP/1.1 $status_code $status_msg");
        header("Content-Type: application/json");
        echo json_encode($this->utf8ize( $array ) );
        exit;
    }

    /**
     * Function to force UTF-8 encode on characters
     *
     * @param [string || array] $d
     * @return [ string || array]
     */
    private function utf8ize($d)
    {
        if (is_array($d)) {
            foreach ($d as $k => $v) {
                $d[$k] = $this->utf8ize($v);
            }
        } else if (is_string($d)) {
            return utf8_encode($d);
        }
        return $d;
    }

    /**
     * Function to set a default error syntax in the response
     *
     * @param [int] $status_code
     * @param [string] $msg
     * @param [array] $response
     * @return void
     */
    protected function setError($status_code, $msg, $response = [])
    {
        $response['errors'] = [
            'status_code' => $status_code,
            'msg'         => $msg
        ];
        http_response_code(intval($status_code));
         
        // header("HTTP/1.1 $status_code $status_msg");
        header("Content-Type: application/json");
        echo json_encode($this->utf8ize( $response ) );
        exit;
    }
}