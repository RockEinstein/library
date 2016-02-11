<?php

namespace RockEinstein\Lib\Api;

use RockEinstein\Lib\Api\Controller\Controller;

/**
* @see \RockEinstein\Lib\Api\ControllerProviderArray
*/
class RoutesProviderArray implements RoutesProvider {

    public $routes;
    public $controllerPath;

    /**
     * 
     * @param Array $routes
     */
    public function __construct($routes, $controllerPath = "Rest\\Controller\\") {
        $this->setControllerPath($controllerPath);
        if (is_string($routes) && file_exists($routes)) {
            $this->routes = require $routes;
        }
        else if(is_array($routes)) {
            $this->routes = $routes;
        }
        else {
            throw new \InvalidArgumentException();
        }
    }

    /**
     * 
     * @param String $route As rotas estar? no formato
     * Array
     * (
     *    [log] => Array
     *        (
     *            [controller] => Log
     *        )
     *
     *    [auth] => Array
     *        (
     *            [controller] => Auth
     *        )
     *
     * )
     * @throws \RockEinstein\Lib\Api\Request\Exception\ResourceNotFoundException
     */
    public function getRoute($route) {

        $keys = array_keys($this->routes);

        if (in_array(trim($route), $keys)) {
            $controllerName = $this->getControllerPath() . $this->routes[$route]['controller'];
            if(!class_exists($controllerName)){
                throw new \Exception('Controller especificada na rota não existe ('.$controllerName.')',404);
            }            
            $controller = new $controllerName();
            if(!$controller instanceof Controller){
                throw new RockEinstein\Lib\Api\Request\Exception\ResourceBrokenException($apiRequest->getResource(), $controller);
            }
            return $controller;
        }
        $controllerName = $this->controllerPath() . ucfirst($route);
        if (class_exists($controllerName)) {
            $controller = new $controllerName();
            return $controller;
        }
        throw new \RockEinstein\Lib\Api\Request\Exception\ResourceNotFoundException($route);
    }

    public function getControllerPath() {
        return $this->controllerPath;
    }

    public function setControllerPath($controllerPath) {
        $this->controllerPath = $controllerPath;
    }

}
