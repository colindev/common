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

    public function testTake()
    {
        $tester = $this;
        $source = array(9, 8, 'a' => 7, 6, 5, 4, 3, 2, 1, 0);

        $arr = new Rde\Arr($source);

        $collection = array();
        $arr->take(5)->each(function($v, $k) use(&$collection, $tester) {
            if (7 === $v) {
                $tester->assertEquals('a', $k, '檢查索引');
            }

            $collection[$k] = $v;
        });

        $this->assertEquals(
            array(9,8, 'a' => 7,6,5),
            $collection,
            '檢查蒐集陣列take(5)');

        $collection = array();
        $arr->take(11)->each(function($v, $k) use(&$collection) {
            $collection[$k] = $v;
        });

        $this->assertEquals(
            $source,
            $collection,
            '檢查蒐集陣列take(11)');
    }

    public function testMixed()
    {
        $source = array(9, 8, 'a' => 7, 6, 5, 4, 3, 2, 1, 0);

        $arr = new Rde\Arr($source);

        $collection = array();
        $arr
            ->take(9)
            ->filter(function($v, $k){
                return is_int($k) && 6 != $v;
            })
            ->filter(function($v, $k){
                return is_int($k);
            })
            ->take(3)
            ->each(function($v, $k) use(&$collection){
                is_int($k) and
                $collection[] = $v or
                $collection[$k] = $v;
            });

        $this->assertEquals(
            array(9, 8, 5),
            $collection,
            '檢查結果陣列');
    }
}
