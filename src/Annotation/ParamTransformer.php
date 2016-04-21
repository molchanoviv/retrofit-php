<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Annotation;

use Exception;
use Tebru;
use Tebru\Dynamo\Annotation\DynamoAnnotation;

/**
 * Tebru\Retrofit\Annotation\ParamTransformer
 *
 * @Annotation
 * @Target({"METHOD"})
 *
 * @author Ivan Molchanov <ivan.molchanov@yandex.ru>
 */
class ParamTransformer implements DynamoAnnotation
{
    const NAME = 'param_transformer';

    /**
     * @var string
     */
    private $value;

    /**
     * @var string
     */
    private $class;

    /**
     * Constructor
     *
     * @param array $params
     *
     * @throws Exception
     */
    public function __construct(array $params)
    {
        Tebru\assertArrayKeyExists(
            'value',
            $params,
            'An argument was not passed to a "%s" annotation.',
            get_class($this)
        );

        $this->value = $params['value'];

        if (array_key_exists('class', $params)) {
            $this->class = $params['class'];
        }
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * The name of the annotation or class of annotations
     *
     * @return string
     */
    public function getName()
    {
        return self::NAME;
    }

    /**
     * Whether or not multiple annotations of this type can
     * be added to a method
     *
     * @return bool
     */
    public function allowMultiple()
    {
        return true;
    }
}
