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
}
