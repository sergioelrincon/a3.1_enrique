<?php

function validar_nombre($nombre){
    $expresion = '/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/';
    if(preg_match($expresion, $nombre)){
        return true;
    }else{
        return false;
    }
}

function validar_precio($precio){
    if(gettype($precio) == "integer" || gettype($precio) == "double" || gettype($precio) == "float"){
        return true;
    }else{
        return false;
    }       
}


function validar_imagen($Nombre_Imagen){
    if(empty($Nombre_Imagen)){
        return false;
    }else{
        $expresion = "/\w+(\.)*\.(gif|jpe?g|png)$/i"; 
        if(preg_match($expresion, $Nombre_Imagen)){
            return true;
        }else{
            return false;
        }
    }
}

?>