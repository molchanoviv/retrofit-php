<?php

namespace Tebru\Retrofit\Http;

use Psr\Http\Message\ResponseInterface;
use Tebru\Retrofit\Exception\RequestException;

/**
 * Tebru\Retrofit\Http\LambdaWrapperCallback
 *
 * @author Ivan Molchanov <ivan.molchanov@yandex.ru>
 */
class LambdaWrapperCallback implements Callback
{
    /**
     * @var Callable|null
     */
    private $successCallback;

    /**
     * @var Callable|null
     */
    private $errorCallback;

    /**
     * Called on successful responses
     *
     * @param ResponseInterface $response
     *
     * @return mixed
     */
    public function onResponse(ResponseInterface $response)
    {
        if (null === $this->successCallback) {
            return null;
        }

        return call_user_func($this->successCallback, $response);
    }

    /**
     * Called on errors
     *
     * @param RequestException $exception
     *
     * @return mixed
     */
    public function onFailure(RequestException $exception)
    {
        if (null === $this->errorCallback) {
            return null;
        }
        return call_user_func($this->errorCallback, $exception);
    }

    /**
     * @param Callable $successCallback
     *
     * @return LambdaWrapperCallback
     */
    public function setSuccessCallback(callable $successCallback = null)
    {
        $this->successCallback = $successCallback;

        return $this;
    }

    /**
     * @param Callable $errorCallback
     *
     * @return LambdaWrapperCallback
     */
    public function setErrorCallback(callable $errorCallback = null)
    {
        $this->errorCallback = $errorCallback;

        return $this;
    }
}
