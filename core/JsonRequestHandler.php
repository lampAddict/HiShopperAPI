<?php

namespace core;

class JsonRequestHandler implements IRequestHandler {

    private $handlerCfg;

    function __construct($handlersCfg){
        $this->handlerCfg = $handlersCfg;
    }

    /**
     * @return bool
     */
    public function handle(){

        if(    isset($_POST['json'])
            && $_POST['json']
        ){
            $json = $_POST['json'];
        }
        else{
            $json = file_get_contents('php://input');
        }

        if(    !$json
            && empty($_GET)
        )return;

        $requestName = '';
        if(    isset($_GET['requestName'])
            && isset($_GET['m'])
        ){
            $requestName = $_GET['requestName'].'_'.$_GET['m'];
        }
        if( $requestName == '' )return;

        $request = new JsonRequest( $json );
        $request->requestName = $requestName;

        Dispatcher::obj()->request = $request;

        foreach( $this->handlerCfg as $action => $method ){
            
            if( $request->requestName == $action ){

                header('Content-type: application/json; charset=utf-8');

                $matches = array();
                preg_match('/(?<class>.*)::(?<method>.*)$/', $method, $matches);
                $_class  = $matches['class'];
                $_method = $matches['method'];

                $ctl = new $_class($request);
                $response = $ctl->$_method();
                
                echo $response;

                return true;
            }
        }

        $response = new JsonResponse($request);
        $response->errors = JsonResponse::UNKNOWN_ACTION;

        echo $response;

        return false;
    }

} 