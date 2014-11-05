<?php

/**
 * @group php
 */
class PhpTest extends PHPUnit_Framework_TestCase
{
    public function testIsArray()
    {
        $this->assertTrue(is_array(array()));
    }

    public function testObjectPropertyAccess()
    {
        $tmp = null;
        set_error_handler(function($errno) use(&$tmp) {
                $tmp = $errno;
            }, E_NOTICE);

        $o = new stdClass();

        $o->{'x'};

        restore_error_handler();

        $this->assertEquals(E_NOTICE, $tmp);
    }

    public function testArrayMap()
    {
        $arr = array('a', 'b', 'x' => 'c');

        $this->assertEquals(
            array("a'", "b'", 'x' => "c'"),
            array_map(function($item){return $item."'";}, $arr)
        );
    }

    public function testArrayWalk()
    {
        $arr = array('a', 'b', 'x' => 'c');

        $this->assertTrue(array_walk($arr, function(&$item, $key){
                    $item = $item."'";
                }));

        $this->assertEquals(
            array("a'", "b'", 'x' => "c'"),
            $arr
        );

        $this->assertTrue(array_walk($arr, function(&$item, $key){
                    $item = $item."'";
                    // 無法中斷
                    return false;
                }));

        $this->assertEquals(
            array("a''", "b''", 'x' => "c''"),
            $arr
        );

        $this->assertTrue(array_walk($arr, function(&$item, &$key){
                    // key 無法參照
                    $key = "k:{$key}";
                }));

        $this->assertEquals(
            array("a''", "b''", 'x' => "c''"),
            $arr
        );
    }

    public function testSearch()
    {
        $source = array(
            'a' => 1,
            'b' => 2,
            'c' => '3',
            'd' => true,
            'e' => null
        );

        $this->assertEquals('a', array_search(true, $source), '弱型別模式');
        $this->assertEquals('d', array_search(true, $source, true), '強型別模式');
    }
}
