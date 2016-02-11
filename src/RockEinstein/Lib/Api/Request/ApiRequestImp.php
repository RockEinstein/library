<?php


namespace RockEinstein\Lib\Api\Request;


class ApiRequestImp extends \Zend\Http\PhpEnvironment\Request implements ApiResquest {

    private $resourse;
    private $token;
    private $urlParameters = array();
    private $bodyParameters = array();
    private $headerParameters = array();

    public function __construct() {
        parent::__construct();
        $this->checkHeader();
        $this->checkBody();
        $this->checkURL();
        $this->checkToken();
    }

    private function checkToken() {
        return 'token?';
    }

    private function checkHeader() {
        foreach (parent::getHeaders() as $header) {
            $name = $header->getFieldName();
            $value = $header->getFieldValue();
            $name = $this->headerToParam($name);
            $this->headerParameters[$name] = $value;
        }
    }

    private function checkBody() {
        $content = parent::getContent();
        if (empty($content)) {
            return;
        }
        $json = json_decode($content, true);
        if (empty($json)) {
            throw new \Exception('Body Request is not a valid JSON' . "\n" . '(' . $content . ')');
        }
        foreach ($json as $name => $value) {
            if (!$this->checkParamName($name)) {
                throw new \Exception('Invalid param name (' . $name . ')');
            }
        }
        $this->bodyParameters = $json;
    }

    private function checkURL() {
        $url = parent::getUri()->getPath();
        $get = parent::getQuery()->toArray();
        $explodeUrl = explode('/', $url);
        array_shift($explodeUrl);
        array_shift($explodeUrl);
        if (empty($explodeUrl[0])) {
            throw new \Exception('Empty Resource');
        }
        $this->resourse = $explodeUrl[0];
        array_shift($explodeUrl);
        $max = count($explodeUrl);
        for ($i = 0; $i < $max; $i += 2) {
            $name = $explodeUrl[$i];
            if (empty($name)) {
                continue;
            }
            if (!$this->checkParamName($name)) {
                throw new \Exception('Invalid Parameter Name (' . $name . ')');
            }
            $value = isset($explodeUrl[$i + 1]) ? $explodeUrl[$i + 1] : null;
            $this->urlParameters[$name] = urldecode($value);
        }
        foreach ($get as $name => $value) {
            if (!$this->checkParamName($name)) {
                throw new \Exception('Invalid Parameter Name (' . $name . ')');
            }
        }
        $this->urlParameters = array_merge($this->urlParameters, $get);
    }

    protected function checkParamName($string) {
        $match = preg_match('@^[a-zA-Z_][a-zA-Z0-9_]*$@', $string);

        return $match == 1;
    }

    protected function headerToParam($headerName) {
        $explodeHeaderName = preg_split('@[-._]@', $headerName);
        $paramName = strtolower($explodeHeaderName[0]);
        array_shift($explodeHeaderName);
        foreach ($explodeHeaderName as $partName) {
            $paramName .= ucfirst(strtolower($partName));
        }

        return $paramName;
    }

    public function getBodyParameters() {
        return $this->bodyParameters;
    }

    public function getResource() {
        return $this->resourse;
    }

    public function getURLParameters() {
        return $this->urlParameters;
    }

    public function getToken() {
        return $this->token;
    }

    public function getHeaderParameters() {
        return $this->headerParameters;
    }

    public function getParameter($paramName) {
        switch ($paramName) {
            case 'resource':
                return $this->getResource();
            case 'method':
                return $this->getMethod();
        }
        if (isset($this->headerParameters[$paramName])) {
            return $this->headerParameters[$paramName];
        }
        if (isset($this->urlParameters[$paramName])) {
            return $this->urlParameters[$paramName];
        }
        if (isset($this->bodyParameters[$paramName])) {
            return $this->bodyParameters[$paramName];
        }
        throw new \Exception('Required Parameter not Found (' . $paramName . ')');
    }

}
