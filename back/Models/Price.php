<?php
 
namespace Models;

use \Core\Model;

class Price  {
 
    static function get(){
        return  Price::externalData();
     }
 
     static function find($codigo){
         $data = Price::externalData();
         foreach ($data as $key => $value) { 
             if($codigo == $value['codigo']){
                 return $value;
 
             }
         }
         return  [];
      }
 
 
     static function externalData()
     {
         $json = file_get_contents(__DIR__.'/prices.json');  
 
         if($json === false){
             return [];
         };
         return json_decode($json, true);
 
     }

    
        
}
?>