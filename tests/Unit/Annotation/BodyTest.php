<?php
/*
 * Copyright (c) 2015 Nate Brunette.
 * Distributed under the MIT License (http://opensource.org/licenses/MIT)
 */

namespace Tebru\Retrofit\Test\Unit\Annotation;

use PHPUnit_Framework_Error_Deprecated;
use Tebru\Retrofit\Annotation\Body;
use Tebru\Retrofit\Test\MockeryTestCase;

/**
 * Class BodyTest
 *
 * @author Nate Brunette <n@tebru.net>
 */
class BodyTest extends MockeryTestCase
{
    /**
     * @expectedException PHPUnit_Framework_Error_Deprecated
     */
    public function testJsonSerializableIsDeprecatedInConstructor()
    {
        new Body(['value' => '$body', 'jsonSerializable' => true]);
    }

    public function testJsonSerializableConstructor()
    {
        PHPUnit_Framework_Error_Deprecated::$enabled = false;
        $errorLevel = error_reporting();
        error_reporting(0);

        new Body(['value' => '$body', 'jsonSerializable' => true]);

        error_reporting($errorLevel);
        PHPUnit_Framework_Error_Deprecated::$enabled = true;
    }

    /**
     * @expectedException PHPUnit_Framework_Error_Deprecated
     */
    public function testJsonSerializableIsDeprecated()
    {
        $body = new Body(['value' => '$body']);
        $this->assertTrue($body->isJsonSerializable());
    }
}
