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
        $this->assertEquals('time', Rde\value('time'), '測試不可誤觸方法名稱');
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
}
