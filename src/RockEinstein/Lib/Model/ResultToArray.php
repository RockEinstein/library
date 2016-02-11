<?php

namespace RockEinstein\Lib\Model;


/**
 * Class ResultToArray
 *
 * @package RockEinstein\Lib\Model
 * @author Francisco Ambrozio
 */
class ResultToArray {

    /**
     * @var mixed $result
     */
    private $_result;

    /**
     * @var array $array
     */
    private $_array;

    /**
     * @param mixed $result
     */
    public function __construct($result = null) {
        if (!empty($result)) {
            $this->_result = $result;
            $this->_array = $this->resultToArray();
        }
    }

    /**
     * Get result
     *
     * @return mixed
     */
    public function getResult() {
        return $this->_result;
    }

    /**
     * Set result
     *
     * @param mixed $result
     * @return $this
     */
    public function setResult($result) {
        $this->_result = $result;

        return $this;
    }

    /**
     * Get array
     *
     * @return array
     */
    public function getArray() {
        return $this->_array;
    }

    /**
     * Transforma um resultado de uma Entity em array
     *
     * @return array
     */
    public function resultToArray() {
        $array = array();

        if (is_array($this->_result)) {
            foreach ($this->_result as $res) {
                if (is_array($res)) {
                    $array[] = $res;
                } else {
                    $array[] = $this->fromObjectToArray($res);
                }
            }
        } else {
            $array[] = $this->fromObjectToArray($this->_result);
        }

        return $array;
    }

    /**
     * Transforma um objeto de um resultado de uma Entity em array
     *
     * @param object $object
     * @return array
     */
    protected function fromObjectToArray($object) {
        $reflection = new \ReflectionClass($object);
        $properties = $reflection->getProperties();
        $array = array();

        foreach ($properties as $prop) {
            if (in_array($prop->getName(),
                array(
                    'connectionName',
                    'entityManagerProvider',
                    '__iterable__',
                    '__initializer__',
                    '__cloner__',
                    '__isInitialized__'
                )
            )) {
                continue;
            }

            $method = 'get' . str_replace(' ', '', ucwords(str_replace('_', ' ', $prop->getName())));
            $res = $this->getRelationshipFields($object->$method());

            if (false === $res) {
                $array[$prop->getName()] = $object->$method();
            } else {
                if (count($res) == 1) {
                    $res = $res[0];
                }
                $array[$prop->getName()] = $this->formatResult($res);
            }
        }

        return $array;
    }

    /**
     * Tenta buscar os valores quando o campo se trata de um relacionamento
     *
     * @param mixed $class
     * @return array $results|boolean false
     */
    private function getRelationshipFields($class) {
        if (method_exists($class, 'getProperties')) {
            return $class->getProperties();
        } else {
            if ($class instanceof \Doctrine\ORM\PersistentCollection) {
                $values = $class->getValues();
                $results = array();

                foreach ($values as $val) {
                    $reflection = new \ReflectionClass($val);
                    $properties = $reflection->getProperties();

                    $array = array();

                    foreach ($properties as $prop) {
                        if (in_array($prop->getName(), array('connectionName', 'entityManagerProvider', '__iterable__'))) {
                            continue;
                        }

                        $methodName = 'get' . str_replace(' ', '', ucwords(str_replace('_', ' ', $prop->getName())));
                        $method = $val->$methodName();

                        if ($method instanceof \Doctrine\ORM\PersistentCollection) {
                            continue;
                        }

                        if (is_object($method)) {
                            $sub_array = array();

                            if (get_class($method) == "DateTime") {
                                $array[$prop->getName()] = $method->format(DATE_ISO8601);
                                continue;
                            }

                            foreach ($method->getProperties() as $idx => $v) {
                                if (is_object($v)) {
                                    continue;
                                }

                                $sub_array[$idx] = $v;
                            }
                        }

                        $array[$prop->getName()] = (empty($sub_array)) ? $method : $sub_array;
                        $sub_array = null;
                    }

                    $results[] = $array;

                }

                return $results;
            } else {
                return false;
            }
        }
    }

    /**
     * Formata um resultado
     *
     * @param mixed $result
     * @return array
     */
    private function formatResult($result) {
        if (!is_array($result)) {
            return $result;
        }

        $return = array();

        foreach ($result as $k => $res) {
            if (!empty($res) and !is_object($res)) {
                $return[$k] = $res;
            }
        }

        return $return;
    }

}
