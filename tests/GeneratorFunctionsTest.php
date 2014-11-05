<?php

/**
 * @group generator
 */
class GeneratorFunctionsTest extends PHPUnit_Framework_TestCase
{
    public function testFilter()
    {
        $tester = $this;
        $source = ['a' => 1, 'b' => 2, 3, 4, 5];

        $cnt_odd = 0;
        $filter_odd = function($v, $k) use($tester, &$cnt_odd, $source) {
            $tester->assertTrue(isset($source[$k]), '檢查索引');
            $tester->assertEquals($source[$k], $v, '檢查值');
            ++$cnt_odd;

            return ($v & 1);
        };

        $filted = [];

        foreach (Rde\array_filter($source, $filter_odd) as $k => $v) {
            is_int($k) and
            $filted[] = $v or
            $filted[$k] = $v;
        }

        $this->assertEquals(5, $cnt_odd, '檢查過濾次數');
        $this->assertEquals(['a' => 1, 3, 5], $filted, '檢查過濾結果');
    }

    public function testTake()
    {
        $source = [9, 8, 'a' => 7, 6, 5, 4, 3, 2, 1, 0];

        $collection = [];

        foreach (Rde\array_take($source, 5) as $k => $v) {
            if (7 === $v) {
                $this->assertEquals('a', $k, '檢查索引');
            }

            $collection[$k] = $v;
        }

        $this->assertEquals(
            [9, 8, 'a' => 7, 6, 5],
            $collection,
            '檢查蒐集陣列take(5)'
        );

        $collection = [];

        foreach (Rde\array_take($source, 11) as $k => $v) {
            $collection[$k] = $v;
        }

        $this->assertEquals(
            $source,
            $collection,
            '檢查蒐集陣列take(11)'
        );
    }
}
