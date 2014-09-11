<?php

class FakeArray implements ArrayAccess
{
    public function offsetExists($i){}
    public function offsetGet($i){}
    public function offsetSet($i, $v){}
    public function offsetUnset($i){}
}
/**
 * @group array
 */
class ArrayFunctionTest extends PHPUnit_Framework_TestCase
{
    public function testIsArray()
    {
        $this->assertTrue(Rde\is_array([]));
        $this->assertTrue(Rde\is_array(new FakeArray()));
        $this->assertFalse(Rde\is_array(null));
    }

    public function testArrayGet()
    {
        $arr = array('a' => 1);

        $this->assertEquals(1, \Rde\array_get($arr, 'a'));
        $this->assertEquals(null, \Rde\array_get($arr, 'b'));
        $this->assertEquals(2, \Rde\array_get($arr, 'b', 2));
        $this->assertEquals(3, \Rde\array_get($arr, 'b', function(){return 3;}));
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testArrayGetException()
    {
        \Rde\array_get(null, 'a');
    }

    public function testArrayEach()
    {
        $tester = $this;
        $tmp1 = array('a' => 9, 'b' => 5);
        $tmp2 = array('x' => 1, 'y' => 2);
        $tmp3 = array();

        Rde\array_each(function($k, $v) use($tester, &$tmp3) {
            $tmp3[] = array($k, $v);

            'a' === $k and $tester->assertEquals(9, $v);
            'b' === $k and $tester->assertEquals(5, $v);
            'x' === $k and $tester->assertEquals(1, $v);
            'y' === $k and $tester->assertEquals(2, $v);
        }, $tmp1, $tmp2);

        $tester->assertEquals(4, count($tmp3));
    }
}
