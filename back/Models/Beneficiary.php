<?php
 
namespace Models; 

class Beneficiary {

  
    static function get(){
        return Beneficiary::externalData();
    }

    static function find($codigo){
        $data = Beneficiary::externalData();
        foreach ($data as $key => $value) { 
            if($codigo == $value['codigo']){
                return $value;
            }
        }
        return  [];
     }


    static function externalData()
    {
        
        $json = file_get_contents(__DIR__.'/proposta.json');  
 
        if($json === false){
            return [];
        };
        
        return json_decode($json, true);

    }

    static function create($data){ 
        $records = Beneficiary::externalData(); 
       
        $fhandle = fopen( __DIR__.'/proposta.json', 'w' );
        $first = true;

        fwrite( $fhandle, "[\n" );
        $code = 0; 
        
        if( !empty($records) ){
            foreach( $records as $key => $row ) {
                if( $first != true ) {
                    fwrite( $fhandle, ',' );
                } else {
                    $first = false;
                }
    
                fwrite( $fhandle, '{"codigo":"' . $row['codigo'] );
                fwrite( $fhandle, '","quantidade_beneficiarios":' .  $row['quantidade_beneficiarios'] );
                fwrite( $fhandle, ',"plano":' .  json_encode($row['plano']) );
                fwrite( $fhandle, ',"beneficiarios":' . json_encode($row['beneficiarios']) . '}' );
                $code = $row['codigo'];
            }

        }

        
        $code = $code + 1;
        $sep = '';
        if($code > 1){
            $sep = ",\n"; 
        }

         
        
        fwrite( $fhandle, $sep.'{"codigo":"' . $code );
        fwrite( $fhandle, '","quantidade_beneficiarios":' .   $data['number_beneficiaries'] );
        fwrite( $fhandle, ',"plano":' .  $data['plan'] );
        fwrite( $fhandle, ',"beneficiarios":' .  $data['beneficiaries'] . '}' );

        fwrite( $fhandle, "\n]" );
        fclose( $fhandle );
        unset( $fhandle );
        return true;


    }

    
        
}
?>