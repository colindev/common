<?php

/**
 * @group ns
 */
class NamespaceTest extends PHPUnit_Framework_TestCase
{
    public function testRdeArrayGetExists()
    {
        $this->assertTrue(
            function_exists('Rde\\array_get'),
            'Rde\\array_get 不存在'
        );
    }
}
