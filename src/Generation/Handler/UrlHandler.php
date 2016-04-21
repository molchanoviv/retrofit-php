<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Generation\Handler;

use LogicException;
use Tebru\Retrofit\Generation\Handler;
use Tebru\Retrofit\Generation\HandlerContext;

/**
 * Class UrlHandler
 *
 * @author Nate Brunette <n@tebru.net>
 */
class UrlHandler implements Handler
{
    /**
     * Handle request url code generation
     *
     * @param HandlerContext $context
     * @return null
     * @throws LogicException
     */
    public function __invoke(HandlerContext $context)
    {
        $baseUrl = $context->annotations()->getBaseUrl() ?: '$this->baseUrl';
        $queryMap = $context->annotations()->getQueryMap();
        $queries = $context->annotations()->getQueries();
        $uri = $context->annotations()->getRequestUri();
        $paramTransformers = $context->annotations()->getParamTransformers();

        // if there aren't queries or a query map, just set request url
        if (null === $queryMap && null === $queries) {
            $context->body()->add('$requestUrl = %s . "%s";', $baseUrl, $uri);

            return null;
        }

        // if there's a query map, check to see if there are also queries
        if (null !== $queryMap) {
            // if we have regular queries, add them to the query builder
            if (null !== $queries) {
                $queryArray = $context->printer()->printArray($queries);
                $context->body()->add('$queryArray = %s + %s;', $queryArray, $queryMap);
            } else {
                $context->body()->add('$queryArray = %s;', $queryMap);
            }
        } else {
            // add queries to request url
            $queryArray = $context->printer()->printArray($queries);
            $context->body()->add('$queryArray = %s;', $queryArray);
        }

        foreach ($paramTransformers as $parameter => $transformer) {
            $context->body()->add('if (isset($queryArray[\'%s\'])) {', $parameter);
            $context->body()->add('$transformer = new %s();', $transformer);
            $context->body()->add(
                '$queryArray[\'%s\'] = $transformer->transform($queryArray[\'%s\']);',
                $parameter,
                $parameter
            );
            $context->body()->add('}');
        }

        $context->body()->add('$queryArray = \Tebru\Retrofit\Generation\Manipulator\QueryManipulator::boolToString($queryArray);');
        $context->body()->add('$queryString = http_build_query($queryArray);');
        $context->body()->add('$requestUrl = %s . "%s?" . $queryString;', $baseUrl, $uri);
    }
}
