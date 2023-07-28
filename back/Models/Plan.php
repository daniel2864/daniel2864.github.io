<?php
 
namespace Models; 

class Plan {

    

    static function get(){
       return  Plan::externalData();
    }

    static function find($codigo){
        $data = Plan::externalData();
        foreach ($data as $key => $value) { 
            if($codigo == $value['codigo']){
                return $value;

            }
        }
        return  [];
     }


    static function externalData()
    {
        $json = file_get_contents('../../plans.json');  

        if($json === false){
            return [];
        };
        
        return json_decode($json, true);

    }

    
        
}
?>