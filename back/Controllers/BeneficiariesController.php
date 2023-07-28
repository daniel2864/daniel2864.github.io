<?php

namespace Controllers;

use \Core\Controller;
use \Models\Beneficiary; 
use \Models\Plan; 
use \Models\Price; 

class BeneficiariesController extends Controller{
    
    public function index() {

        $response = Beneficiary::get(); 
        $status_code = 200; 

        $this->returnJson($response, $status_code);
    }

 
   public function show(){
     
        $this->validation(array(
          'value'   => $_GET['codigo'],
          'requery' => true, 
          'message' => 'Dados necessários',
        )); 

        $response = Beneficiary::find($_GET['codigo']); 
        if(empty($response)){
          $this->setError(404, 'Registro não encontrado.');
        } 

        $this->returnJson($response, 200);

   }

   public function save(){

      $data = $this->getRequestData();
      //$data['beneficiaries'] = (array)$data['beneficiaries'];

      $validations[] = array(
        'key'     => 'beneficiaries',
        'value'   => (array)$data['beneficiaries'],
        'requery' => true, 
        'message' => 'Você deve escolher pelo menos 1 beneficiário',
      );

      $validations[] = array(
        'key'     => 'number_beneficiaries',
        'value'   => $data['number_beneficiaries'],
        'requery' => true, 
        'message' => 'Você deve indicar o número de beneficiários',
      );

      $validations[] = array(
        'key'     => 'plan_id',
        'value'   => $data['plan_id'],
        'requery' => true, 
        'message' => 'Você deve selecionar um plano de saúde',
      );

      $this->validation($validations); 

      

      $plan_benefiary = [];
      $plan  = Plan::find($data['plan_id']); 
      

      $data_new = json_decode($data['beneficiaries']); 
      
           
      foreach ((array)$data_new as $key => $value) {
       
        $name = '';
        $age = '';
        if(!empty($value->name)){
          $name = $value->name;
        }
        if(!empty($value->age)){
          $age = $value->age;
        }
        if(empty($name) && empty($age)){
          $data_new = json_decode($value);
          $name = $data_new[$key]->name;
          $age  = $data_new[$key]->age; 

        } 
        
      

          
        $plan_benefiary[] = array(
          "name"  => $name ,
          "age"   => $age,
          "price" => $this->getPrice($age, $data['plan_id'], $data['number_beneficiaries'])
        );
          
      }
 
        $data = array( 
            'number_beneficiaries' => $data['number_beneficiaries'],
            'plan'                 => json_encode($plan),
            'beneficiaries'        => json_encode($plan_benefiary)
          ); 
     
    
        $response = Beneficiary::create($data); 
  
   

        $status_code = 200;

        $this->returnJson($response, $status_code);

   }

   protected function validation($restrictions = []){
      $errors['messages'] = [];

       
      if(empty($restrictions[0])){

        if(!$this->isPresent($restrictions)){

          if($this->isRequired($restrictions)){
            if($this->isCustomMessage($restrictions)){
              $this->setError(406, $restrictions['message']);
            }else{
              $this->setError(406, 'Dados necessários'); 
            } 
          }   
        } 

      }else{
 
        for ($i=0; $i < count($restrictions); $i++) { 
           
          if(!empty($restrictions[$i])){
            if(!$this->isPresent($restrictions[$i])){

              if($this->isRequired($restrictions[$i])){
                $message = 'Dados necessários'; 
                if($this->isCustomMessage($restrictions[$i])){ 
                  $message = $restrictions[$i]['message'];
                } 
                $errors['messages'][$restrictions[$i]['key']] = $message;
              }   
            }

          }
        }
       
        

        if(count($errors['messages']) > 0){
          $this->setError(406, 'Erro nos dados enviados.',$errors);
        }

      }

  
   }

   protected function isNumber($value){
    return (ctype_digit(strval($value)));

   }

   protected function isPresent($restrictions){
    if(empty($restrictions['value'])){
      return false;
    }
    return true;

   }
   protected function isRequired($restrictions){
    if(empty($restrictions['requery'])){
      return false;
    }
    if(!empty($restrictions['requery']) && $restrictions['requery']){
      return true;
    }
    return false;

   }

   protected function isCustomMessage($restrictions){
    if(empty($restrictions['message'])){
      return false;
    }
    return true;

   }

   protected function getPrice($age,$plan_id, $number_beneficiaries){
      $price = Price::find($plan_id);    

      if($number_beneficiaries < $price['minimo_vidas']){
        $mensaje = 'Número de beneficiários para o plano selecionado é um mínimo de '.$price['minimo_vidas'].' beneficiário';
        if($price['minimo_vidas'] > 1){
          $mensaje = 'Número de beneficiários para o plano selecionado é um mínimo de '.$price['minimo_vidas'].' beneficiários';
        }

        $this->setError(406, $mensaje);

      }else{

        if($age >= 0 && $age <= 17){
          return $price['faixa1'];

        }

        if($age >= 18 && $age <= 40){
          return $price['faixa2'];
        }

        if($age > 40){
          return $price['faixa3'];
        }

      }

   }
     
}