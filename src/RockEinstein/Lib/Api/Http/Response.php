<?php

namespace RockEinstein\Lib\Api\Http;

use Zend\Http\Response as ZfResponse;


/**
 * Class Response
 * @package RockEinstein\Lib\Api\Http
 * @author Francisco Ambrozio
 */
class Response
{

    /**
     * @var ZfResponse $_response
     */
    private $_response;

    /**
     * @param ZfResponse|null $response
     */
    public function __construct(ZfResponse $response = null)
    {
        if (!empty($response)) {
            $this->_response = $response;
        }
    }

    /**
     * @param int $status
     * @param array $body
     * @return array|void
     */
    public function response($status = 200, $body = array())
    {
        if (empty($this->_response)) {
            return $this->rawResponse($status, $body);
        } else {
            $this->_response->setStatusCode($status);

            return $body;
        }
    }

    /**
     * Devolve 400 Bad Request
     */
    public function badRequest($msg = null)
    {
        $body = array();

        if (!empty($msg)) {
            $body = array(
                'error' => $msg
            );
        }

        return $this->response(ZfResponse::STATUS_CODE_400, $body);
    }

    /**
     * Devolve 404 Not Found
     */
    public function notFound()
    {
        return $this->response(ZfResponse::STATUS_CODE_404, array('error' => 'Resource not found'));
    }

    /**
     * Devolve 405 Not Allowed
     */
    public function notAllowed()
    {
        return $this->response(ZfResponse::STATUS_CODE_405, array('error' => 'Method not allowed'));
    }

    /**
     * Devolve 412 Precondition Failed
     */
    public function preconditionFailed($msg)
    {
        return $this->response(ZfResponse::STATUS_CODE_412, $msg);
    }

    /**
     * Devolve 500 Internal Server Error
     */
    public function internalServerError()
    {
        return $this->response(ZfResponse::STATUS_CODE_500);
    }

    /**
     * @param int $status
     * @param array $body
     */
    private function rawResponse($status, $body)
    {
        $zfResponse = new ZfResponse();
        $zfResponse->setStatusCode($status);
        $reasonPhrase = $zfResponse->getReasonPhrase();

        \header('HTTP/1.0 ' . $status . ' ' . $reasonPhrase);
        \header('Content-Type: application/json');
        die(json_encode($body));
    }

}
