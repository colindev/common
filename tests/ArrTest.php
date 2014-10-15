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

        $arr->init();

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

        $arr->init();

        $collection = array();
        $arr
            ->take(5)
            ->filter(function($v, $k){
                return is_int($k) && 6 < $v;
            })
            ->each(function($v, $k) use(&$collection) {
                is_int($k) and
                $collection[] = $v or
                $collection[$k] = $v;
            });

        $this->assertEquals(
            array(9, 8),
            $collection,
            '檢查結果陣列');
    }

    public function testArrayAccess()
    {
        $source = array(3, 2, 'a' => 1, '0', 9);

        $arr = new Rde\Arr($source);

        $this->assertEquals(
            1,
            $arr['a'],
            '檢查索引a');

        $this->assertEquals(
            '0',
            $arr[2],
            '檢查索引2');

        $arr->init();

        // 濾掉 索引或值型態是字串的元素
        $cnt = 0;
        $filter = function($v, $k) use(&$cnt){
            ++$cnt;

            return ! is_string($k) && ! is_string($v);
        };

        $arr->filter($filter);

        $this->assertFalse(isset($arr['a']), '檢查索引a是否正確被濾掉');
        $this->assertFalse(isset($arr[2]), '檢查索引2是否正確被濾掉');
        $this->assertEquals(2, $arr[1], '檢查索引1是否正確被濾出');
        $this->assertEquals(9, $arr[3], '檢查索引3是否正確被濾出且索引沒變');

        $this->assertEquals(5, $cnt, '檢查過濾函式呼叫次數');
    }

    public function testIterator()
    {
        $source = array(9, 8, 'x' => 7, 6, 5, 'y' => 4, 3, 2, 1);

        $arr = new Rde\Arr($source);

        foreach ($arr as $k => $v) {
            $this->assertTrue(isset($source[$k]), "檢查索引[{$k}]");
            $this->assertEquals($source[$k], $v, "檢查值[{$v}]");
        }

        $arr->init();

        $cnt_1 = 0;
        $cnt_2 = 0;
        $collection = array();

        $arr->filter(function($v, $k) use(&$cnt_1) {
                ++$cnt_1;
                return ! is_string($k);
            });

        $arr->filter(function($v, $k) use(&$cnt_2) {
                ++$cnt_2;
                return ! in_array($v, array(8, 6, 5));
            });

        foreach ($arr as $k => $v) {
            $collection[$k] = $v;
        }

        $this->assertEquals(
            array(
                0 => 9,
                4 => 3,
                5 => 2,
                6 => 1,
            ),
            $collection,
            '檢查蒐集陣列');

        $this->assertEquals(9, $cnt_1, '檢查過濾函式1執行次數');
        $this->assertEquals(7, $cnt_2, '檢查過濾函式2執行次數');
    }
}
