<?php
namespace Example\Foo\Application\Proxy;

use MultiRouting\Request\Proxy\ProxyInterface;

/**
 * Class JsonRpc
 * @package Example\Foo\Application\Proxy
 */
class JsonRpc implements ProxyInterface
{

    /**
     * @var object
     */
    protected $matchedInstance;

    /**
     * @var string
     */
    protected $matchedMethod;

    /**
     * @var array
     */
    protected $matchedParameters = [];

    /**
     * @param object $matchedInstance
     */
    public function setMatchedInstance($matchedInstance)
    {
        $this->matchedInstance = $matchedInstance;
    }

    /**
     * @param string $matchedMethod
     */
    public function setMatchedMethod($matchedMethod)
    {
        $this->matchedMethod = $matchedMethod;
    }

    /**
     * @param array $matchedParameters
     */
    public function setMatchedParameters(array $matchedParameters = [])
    {
        $this->matchedParameters = $matchedParameters;
    }

    /**
     * @param string $requestedMethod
     * @param array $requestedParams
     * @return mixed
     * @throws \Exception
     */
    public function __call($requestedMethod, array $requestedParams = [])
    {
        $response = call_user_func_array([$this->matchedInstance, $this->matchedMethod], $this->matchedParameters);

        return $response;
    }

}