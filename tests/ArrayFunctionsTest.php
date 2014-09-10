<?php

class ArrayFunctionTest extends PHPUnit_Framework_TestCase
{
    public function testArrayGet()
    {
        $arr = array('a' => 1);

        $this->assertEquals(1, \Rde\array_get($arr, 'a'));
        $this->assertEquals(null, \Rde\array_get($arr, 'b'));
        $this->assertEquals(2, \Rde\array_get($arr, 'b', 2));
    }
}
