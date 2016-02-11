<?php

namespace RockEinstein\Lib\Api\Controller;

use RockEinstein\Lib\Api\Request\ApiResquest;
use \Zend\Http\Response;


abstract class AbstractController {

    protected $request;
    protected $response;

    public function setRequest(ApiResquest $request){
        $this->request = $request;
    }

    public function setResponse(Response $response){
        $this->response = $response;
    }


    public function setStatusCode($code = null) {
        $code = empty($code) ? $this->getDefaultStatusCode() : $code;
        $this->response->setStatusCode($code);
    }

    protected function getDefaultStatusCode() {
        switch ($this->request->getMethod()) {
            case 'OPTIONS': return 200;
            case 'GET': return 200; //OK
            case 'PUT': return 200;
            case 'POST': return 201; //created
            case 'DELETE': return 204;//no content
        }
    }
    
}
