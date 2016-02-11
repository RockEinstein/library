<?php

namespace RockEinstein\Lib\Api;

/**
 * Description of RoutesProvider
 * @see \RockEinstein\Lib\Api\ControllerProvider
 * @author anderson
 */
interface RoutesProvider {
    
    /**
     * @param string $route
     * @return \RockEinstein\Lib\Api\Controller\Controller
     */
    public function getRoute($route);
}
