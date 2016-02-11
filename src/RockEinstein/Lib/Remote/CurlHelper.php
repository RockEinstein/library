<?php

namespace RockEinstein\Lib\Remote;

use RockEinstein\Lib\Util\CaseStyle\CaseParser;
use RockEinstein\Lib\Util\CaseStyle\CaseParserFactory;

class CurlHelper {

    /**
     *
     * @var string
     */
    public $url;

    /**
     *
     * @var Array
     */
    public $headerParameters = array();

    /**
     *
     * @var Array
     */
    public $queryParameters = array();

    /**
     *
     * @var Array
     */
    public $urlParameters = array();

    /**
     *
     * @var Array
     */
    public $bodyParamaters = array();

    /**
     *
     * @var callable
     */
    public $reponseFormatter;

    /**
     *
     * @var callable
     */
    public $requestFormatter;

    /**
     *
     * @var RockEinstein\Lib\Util\CaseStyle\CaseParser
     */
    public $headerCaseStyle;

    /**
     *
     * @var RockEinstein\Lib\Util\CaseStyle\CaseParser
     */
    public $queryCaseStyle;

    /**
     *
     * @var RockEinstein\Lib\Util\CaseStyle\CaseParser
     */
    public $urlCaseStyle;

    /**
     *
     * @var RockEinstein\Lib\Util\CaseStyle\CaseParser
     */
    public $bodyCaseStyle;

    public function prepareHeader($moreHeaders = array()) {
        $headersParameters = array_merge($this->headerParameters, $moreHeaders);

        $headers = array();
        foreach ($headersParameters as $headerName => $headerValue) {
            if (is_numeric($headerName)) {
                $headers[] = (string)$headerValue;
                continue;
            }

            $newName = ($this->headerCaseStyle instanceof CaseParser) ? $this->headerCaseStyle->parse($headerName) : $headerName;
            $headers[] = $newName . ': ' . (string)$headerValue;
        }

        return $headers;
    }

    public function prepareBody($moreBody = array()) {
        $bodyParameters = array_merge($this->bodyParamaters, $moreBody);
        if ($this->bodyCaseStyle instanceof CaseParser) {
            return $this->bodyCaseStyle->parse($bodyParameters);
        }

        return $bodyParameters;
    }

    public function formatBody($moreBody = array()) {
        $bodyParameters = $this->prepareBody($moreBody);
        $requestFormatter = $this->requestFormatter;

        return (is_callable($requestFormatter)) ? $requestFormatter($bodyParameters) : $bodyParameters;
    }

    public function prepareQuery($moreQuery = array()) {
        $queryParameters = array_merge($this->queryParameters, $moreQuery);
        if ($this->queryCaseStyle instanceof CaseParser) {
            $queryParameters = $this->queryCaseStyle->parse($queryParameters);
        }

        return http_build_query($queryParameters);
    }

    public function prepareUrl($moreUrl = array()) {
        $urlParamaters = array_merge($this->urlParameters, $moreUrl);
        if ($this->urlCaseStyle instanceof CaseParser) {
            $urlParamaters = $this->urlCaseStyle->parse($urlParamaters);
        }
        $queryFormat = http_build_query($urlParamaters);
        $urlFormat = str_replace(array('=', '&'), '/', $queryFormat);

        return $this->url . $urlFormat;
    }

    private function isSuccessCurl($curl) {
        $httpStatusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if ($httpStatusCode >= 200 && $httpStatusCode < 300) {
            return true;
        }

        return false;
    }

    public function get($urlParameters = array(), $queryParameters = array(), $headerParameters = array()) {
        $curl = curl_init();
        $url = $this->prepareUrl($urlParameters) . '?' . $this->prepareQuery($queryParameters);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->prepareHeader($headerParameters));
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($curl);
        if ($result === false) {
            throw new \Exception(curl_error($curl));
        }
        if (!$this->isSuccessCurl($curl)) {
            throw new \Exception($result);
        }
        $reponseFormatter = $this->reponseFormatter;

        return ($reponseFormatter) ? $reponseFormatter($result) : $result;
    }

    public function post($urlParameters = array(), $queryParameters = array(), $bodyParameters = array(), $headerParameters = array()) {
        $curl = curl_init();
        $url = $this->prepareUrl($urlParameters) . '?' . $this->prepareQuery($queryParameters);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->prepareHeader($headerParameters));
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $this->formatBody($bodyParameters));
        $result = curl_exec($curl);
        if ($result === false) {
            throw new \Exception(curl_error($curl));
        }
        if (!$this->isSuccessCurl($curl)) {
            throw new \Exception($result);
        }
        $reponseFormatter = $this->reponseFormatter;

        return ($reponseFormatter) ? $reponseFormatter($result) : $result;
    }

    public function put($urlParameters = array(), $queryParameters = array(), $bodyParameters = array(), $headerParameters = array()) {
        $curl = curl_init();
        $url = $this->prepareUrl($urlParameters) . '?' . $this->prepareQuery($queryParameters);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->prepareHeader($headerParameters));
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $this->formatBody($bodyParameters));
        $result = curl_exec($curl);
        if ($result === false) {
            throw new \Exception(curl_error($curl));
        }
        if (!$this->isSuccessCurl($curl)) {
            throw new \Exception($result);
        }
        $reponseFormatter = $this->reponseFormatter;

        return ($reponseFormatter) ? $reponseFormatter($result) : $result;
    }

    public function delete($urlParameters = array(), $queryParameters = array(), $headerParameters = array()) {
        $curl = curl_init();
        $url = $this->prepareUrl($urlParameters) . '?' . $this->prepareQuery($queryParameters);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->prepareHeader($headerParameters));
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($curl);
        if ($result === false) {
            throw new \Exception(curl_error($curl));
        }
        if (!$this->isSuccessCurl($curl)) {
            throw new \Exception($result);
        }
        $reponseFormatter = $this->reponseFormatter;

        return ($reponseFormatter) ? $reponseFormatter($result) : $result;
    }

    /**
     *
     * @param type $url
     * @return \RockEinstein\Lib\Remote\CurlHelper
     */
    public static function makeJsonCurl($url) {
        $curl = new CurlHelper();
        $curl->url = $url;
        $curl->headerCaseStyle = CaseParserFactory::getInstance()->makeHttpHeadCaseParser();
        $curl->headerParameters['Content-Type'] = 'application/json';
        $curl->requestFormatter = 'json_encode';
        $curl->reponseFormatter = 'json_decode';

        return $curl;
    }

    /**
     *
     * @param type $url
     * @return \RockEinstein\Lib\Remote\CurlHelper
     */
    public static function makeFormCurl($url) {
        $curl = new CurlHelper();
        $curl->url = $url;
        $curl->headerCaseStyle = CaseParserFactory::getInstance()->makeHttpHeadCaseParser();
        $curl->headerParameters['Content-Type'] = 'application/x-www-form-urlencoded';
        $curl->requestFormatter = 'http_build_query';

        return $curl;
    }

}
