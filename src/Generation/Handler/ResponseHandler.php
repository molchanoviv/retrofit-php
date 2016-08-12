<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Generation\Handler;

use LogicException;
use Tebru\Retrofit\Exception\RetrofitException;
use Tebru\Retrofit\Generation\Handler;
use Tebru\Retrofit\Generation\HandlerContext;

/**
 * Class ResponseHandler
 *
 * @author Nate Brunette <n@tebru.net>
 */
class ResponseHandler implements Handler
{
    /**
     * Create response
     *
     * @param HandlerContext $context
     * @return null
     * @throws LogicException
     * @throws RetrofitException
     */
    public function __invoke(HandlerContext $context)
    {
        $callback = $context->annotations()->getCallback();

        if (null === $callback && $context->annotations()->hasHandlers()) {
            $callback = $context->annotations()->getWrapperCallback();
            $context->body()->add('%s = new Tebru\Retrofit\Http\LambdaWrapperCallback();', $callback);
            $context->body()->add('%s->setSuccessCallback(%s);', $callback, $context->annotations()->getSuccessCallback());
            $context->body()->add('%s->setErrorCallback(%s);', $callback, $context->annotations()->getErrorCallback());
        }

        $context->body()->add('$request = new \GuzzleHttp\Psr7\Request("%s", $requestUrl, $headers, $body);', strtoupper($context->annotations()->getRequestMethod()));
        $context->body()->add('$beforeSendEvent = new \Tebru\Retrofit\Event\BeforeSendEvent($request);');
        $context->body()->add('$this->eventDispatcher->dispatch("retrofit.beforeSend", $beforeSendEvent);');
        $context->body()->add('$request = $beforeSendEvent->getRequest();');
        $context->body()->add('try {');

        if (null !== $callback) {
            if ($context->annotations()->isCallbackOptional()) {
                if (false === $context->annotations()->hasHandlers()) {
                    $context->body()->add('if (%s !== null) {', $callback);
                } else {
                    $context->body()->add('if (%s !== null && %s->hasHandlers()) {', $callback, $callback);
                }
                $context->body()->add('$response = $this->client->sendAsync($request, %s);', $callback);
                $context->body()->add('} else {');
                $context->body()->add('$response = $this->client->send($request);');
                $context->body()->add('}');
            } else {
                $context->body()->add('$response = $this->client->sendAsync($request, %s);', $callback);
            }
        } else {
            $context->body()->add('$response = $this->client->send($request);');
        }

        $context->body()->add('} catch (\Exception $exception) {');
        $context->body()->add('$apiExceptionEvent = new \Tebru\Retrofit\Event\ApiExceptionEvent($exception, $request);');
        $context->body()->add('$this->eventDispatcher->dispatch("retrofit.apiException", $apiExceptionEvent);');
        $context->body()->add('$exception = $apiExceptionEvent->getException();');
        $context->body()->add('throw new \Tebru\Retrofit\Exception\RetrofitApiException(get_class($this), $exception->getMessage(), $exception->getCode(), $exception);');
        $context->body()->add('}');
        $context->body()->add('$afterSendEvent = new \Tebru\Retrofit\Event\AfterSendEvent($request, $response);');
        $context->body()->add('$this->eventDispatcher->dispatch("retrofit.afterSend", $afterSendEvent);');
        $context->body()->add('$response = $afterSendEvent->getResponse();');
    }
}
