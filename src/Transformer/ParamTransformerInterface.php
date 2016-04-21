<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Transformer;

/**
 * Tebru\Retrofit\Transformer\ParamTransformerInterface
 *
 * @author Ivan Molchanov <ivan.molchanov@yandex.ru>
 */
interface ParamTransformerInterface
{
    /**
     * @param mixed $variable
     *
     * @return mixed
     */
    public function transform($variable);
}
