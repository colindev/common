<?php

/**
 * @group tool
 */
class ToolFunctionsTest extends PHPUnit_Framework_TestCase
{
    public function testValue()
    {
        $this->assertEquals(1, Rde\value(1));
        $this->assertEquals(2, Rde\value(function(){return 2;}));
    }

    public function testCall()
    {
        $tester = $this;
        $checks = 0;

        Rde\call(function() use($tester, &$checks){
                ++$checks;
                $tester->assertEmpty(
                    func_get_args(),
                    '檢查無參數');
            });

        Rde\call(function() use($tester, &$checks){
                ++$checks;
                $tester->assertEquals(
                    array(9,7,8),
                    func_get_args(),
                    '檢查3參數');
            }, array(9,7,8));

        Rde\call(function() use($tester, &$checks){
                ++$checks;
                $tester->assertEquals(
                    array(9,7,8,'a','b','c'),
                    func_get_args(),
                    '檢查6參數');
            }, array(9,7,8,'a','b','c'));
    }

    public function testPipelineString()
    {
        // 函式字串
        $this->assertEquals(
            'a',
            \Rde\pipeline(
                'array_keys|min',
                array('b' => 1, 'c' => 2, 'a' => 3)));

        // closure 陣列
        $this->assertEquals(
            9,
            \Rde\pipeline(array(
                    function(){return 1;},
                    function($n){return $n + 2;},
                    function($n){return $n * 3;},
                ), null)
        );

        $s = '';
        $this->assertNull(\Rde\pipeline(array(
                    function() use(&$s) {$s .= 'a';},
                    function() use(&$s) {$s .= 'b';},
                    function() use(&$s) {$s .= 'c';},
                ), null, function($v){return null === $v;}));

        $this->assertEquals('abc', $s);
    }
}
