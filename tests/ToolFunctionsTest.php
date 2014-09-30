<?php

/**
 * @group tool
 */
class ToolFunctionTest extends PHPUnit_Framework_TestCase
{
    public function testValue()
    {
        $this->assertEquals(1, Rde\value(1));
        $this->assertEquals(2, Rde\value(function(){return 2;}));
    }
}
