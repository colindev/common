<?php

/**
 * @group object
 */
class ObjectFunctionTest extends PHPUnit_Framework_TestCase
{
    public function testGet()
    {
        $o = new stdClass();
        $o->{'a'} = 1;

        $this->assertEquals(1, \Rde\object_get($o, 'a'));
        $this->assertEquals(null, \Rde\object_get($o, 'b'));
        $this->assertEquals(2, \Rde\object_get($o, 'b', 2));
        $this->assertEquals(3, \Rde\object_get($o, 'b', function(){return 3;}));
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testGetException()
    {
        \Rde\object_get(null, 'a');
    }
}
