<?php

namespace RockEinstein\Lib\Api;

/**
 * @author Anderson Luciano <andersonluciano.dev@gmail.com>
 */
use RockEinstein\Lib\Util\MapCallAdapter;

class App {

    public $routeProvider;
    public $host;
    public $args;
    public $response;
    public $dbAdapter;
    public $sql;
    public $controllerPath;

    /**
     *
     * @param type $routesProvider
     */
    public function __construct(RoutesProvider $routesProvider = null) {
        if (empty($routesProvider)) {
            throw new \InvalidArgumentException;
        }
        $this->setRouteProvider($routesProvider);
    }

    public function run() {
        try {
            $response = $this->tryRun();
        } catch (\Exception $ex) {
            $response = new \Zend\Http\Response();
            $code = $ex->getCode();
            if (is_numeric($code) && $code >= 200 && $code <= 500) {
                $response->setStatusCode($code);
            } else {
                $response->setStatusCode(500);
            }

            $response->getHeaders()->addHeaderLine('Content-Type', 'application/json');
            $response->setContent(\json_encode(array(
                        'exception' => get_class($ex),
                        'message' => $ex->getMessage()
                    )
                )
            );
        }

        $response->getHeaders()->addHeaderLine('Access-Control-Allow-Origin', '*');

        /**
         * Imprime a resposta
         */
        $status = $response->renderStatusLine();
        header($status);
        foreach ($response->getHeaders() as $header) {
            if ($header instanceof MultipleHeaderInterface) {
                header($header->toString(), false);
                continue;
            }
            header($header->toString());
        }
        echo $response->getContent();
    }

    public function tryRun() {
        $response = new \Zend\Http\Response();
        $response->getHeaders()->addHeaderLine('Content-Type', 'application/json');

        $apiRequest = new \RockEinstein\Lib\Api\Request\ApiRequestImp();

        $resource = $apiRequest->getResource();
        $controller = $this->getRouteProvider()->getRoute($resource);
        $controller->setRequest($apiRequest);
        $controller->setResponse($response);
        $controller->setStatusCode();
        $parameters = array_merge(
            $apiRequest->getBodyParameters(),
            $apiRequest->getURLParameters(),
            $apiRequest->getHeaderParameters()
        );
        $mapCall = new MapCallAdapter($controller);
        $callReponse = $mapCall->callWithMapArgs($apiRequest->getMethod(), $parameters);

        if (is_array($callReponse) && !empty($callReponse['ContentType'])) {
            $response->getHeaders()->addHeaderLine('Content-Type', $callReponse['ContentType']);
            $response->setContent($callReponse['Body']);
        } else {
            $response->setContent(\json_encode($callReponse));
        }

        return $response;
    }

    public function getControllerPath() {
        return $this->controllerPath;
    }

    public function setControllerPath($controllerPath) {
        $this->controllerPath = $controllerPath;
    }

    /**
     *
     * @return RoutesProvider
     */
    public function getRouteProvider() {
        return $this->routeProvider;
    }

    public function setRouteProvider($routeProvider) {
        $this->routeProvider = $routeProvider;
    }
}
