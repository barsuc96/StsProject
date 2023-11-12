<?php
namespace App\Helpers;


class FunctionHelper{


    function prepareResponse(bool $success,String $method,Array $data,Array $errors=null){
        $response = array();
        $response['data'] = array();
        $response['errors'] = array();
        
        $response['success'] = $success; 
        $response['method'] = $method;
        $response['data'] = $data;
        $response['errors'] = $errors;
        return $response;
    }
}