<?php

/**
 * @group array
 */
class ArrTest extends PHPUnit_Framework_TestCase
{
    public function testFilter()
    {
        $tester = $this;
        $source = array('a' => 1, 'b' => 2, 3, 4, 5);

        $cnt_odd = 0;
        $filter_odd = function($v, $k) use($tester, &$cnt_odd, $source) {
            $tester->assertTrue(isset($source[$k]), '檢查索引');
            $tester->assertEquals($source[$k], $v, '檢查值');
            ++$cnt_odd;

            return ($v & 1);
        };

        $filted = array();

        $arr = new Rde\Arr($source);

        $arr->filter($filter_odd)->each(function($v, $k) use(&$filted) {
                is_int($k) and
                $filted[] = $v or
                $filted[$k] = $v;
            });

        $this->assertEquals(
            5,
            $cnt_odd,
            '檢查過濾次數');
        
        $this->assertEquals(
            array('a' => 1, 3, 5),
            $filted,
            '檢查過濾結果');
    }
}
