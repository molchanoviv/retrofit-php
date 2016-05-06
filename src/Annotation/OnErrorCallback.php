<?php

namespace Tebru\Retrofit\Annotation;

use Tebru\Dynamo\Annotation\DynamoAnnotation;

/**
 * Tebru\Retrofit\Annotation\OnErrorCallback
 *
 * @Annotation
 * @Target({"CLASS", "METHOD"})
 *
 * @author Ivan Molchanov <ivan.molchanov@yandex.ru>
 */
class OnErrorCallback extends VariableMapper implements DynamoAnnotation
{
    const NAME = 'on_error_callback';

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
        return false;
    }
}
