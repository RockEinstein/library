<?php


namespace RockEinstein\Lib\Api\Controller;

use RockEinstein\Lib\Model\ResultToArray;
use RockEinstein\Lib\Api\Http\Response;

/**
 * Class AbstractApiController
 * @package RockEinstein\AppAuth\Controllers
 * @author Francisco Ambrozio
 */
abstract class AbstractApiController extends AbstractController
{
    protected $_entity;
    protected $_em;
    protected $_required = array();
    protected $result;

    /**
     * @param array $notAllowed Array de métodos não permitidos para determinado recurso
     */
    public function __construct($notAllowed = array())
    {
        if (!empty($notAllowed)) {
            $this->grantAccess($notAllowed);
        }

        $this->_em = $this->_entity->getEntityManager();
    }

    public function get($id = null)
    {
        if (empty($id)) {
            $res = $this->_entity->getRepository()->findAll();
        } else {
            $res = $this->_entity->getRepository()->find($id);
        }

        if (empty($res)) {
            $response = new Response($this->response);
            return $response->notFound();
        }

        $this->result = new ResultToArray($res);

        return $this->getResult();
    }

    public function post()
    {
        $data = array_merge($this->request->getURLParameters(), $this->request->getBodyParameters());

        if (!empty($this->_required)) {
            foreach ($this->_required as $req) {
                if (!array_key_exists($req, $data) or empty($data[$req])) {
                    $response = new Response($this->response);
                    return $response->preconditionFailed('Required parameter: ' . $req);
                }
            }
        }

        try {
            $this->_entity->setProperties($data);
            $this->_em->persist($this->_entity);
            $this->_em->flush();
        } catch (\Exception $e) {
            $response = new Response($this->response);
            return $response->internalServerError();
        }

        $this->result = new ResultToArray($this->_entity);

        return $this->getResult();
    }

    public function put($id)
    {
        $id = (int) $id;

        if (empty($id)) {
            $response = new Response($this->response);
            return $response->badRequest('Invalid parameter.');
        }

        $entity = $this->_entity->getRepository()->find($id);

        if (empty($entity)) {
            $response = new Response($this->response);
            return $response->notFound();
        }

        $data = $this->request->getBodyParameters();

        try {
            $entity->setProperties($data);
            $this->_em->persist($entity);
            $this->_em->flush();
        } catch (\Exception $e) {
            $response = new Response($this->response);
            return $response->internalServerError();
        }

        $this->result = new ResultToArray($entity);

        return $this->getResult();
    }

    public function delete($id)
    {
        $id = (int) $id;

        if (empty($id)) {
            $response = new Response($this->response);

            return $response->badRequest('Invalid parameter.');
        }

        $entity = $this->_entity->getRepository()->find($id);

        if (empty($entity)) {
            $response = new Response($this->response);
            return $response->notFound();
        }

        try {
            $this->_em->remove($entity);
            $this->_em->flush();
        } catch (\Exception $e) {
            $response = new Response($this->response);
            return $response->badRequest();
        }
    }

    public function options()
    {
        return 'Allow: GET, PUT, POST, DELETE, OPTIONS';
    }

    /**
     * Adicionado este método como um suporte a poder manipular
     *  o resultado que se deseja exibir
     *
     * @return array
     */
    protected function getResult()
    {
        return $this->result->getArray();
    }

    /**
     * Verifica se o acesso ao método desejado é permitido
     *
     * @param array $notAllowed
     * @return array|void
     */
    private function grantAccess(array $notAllowed)
    {
        $method = $_SERVER['REQUEST_METHOD'];

        if (in_array($method, $notAllowed)) {
            $response = new Response();
            return $response->notAllowed();
        }
    }

}
