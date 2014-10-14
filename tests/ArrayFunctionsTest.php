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
class ArrayFunctionsTest extends PHPUnit_Framework_TestCase
{
    public function testIsArray()
    {
        $this->assertTrue(Rde\is_array(array()));
        $this->assertTrue(Rde\is_array(new FakeArray()));
        $this->assertFalse(Rde\is_array(null));
    }

    public function testGet()
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
    public function testGetException()
    {
        \Rde\array_get(null, 'a');
    }

    public function testEach()
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

    public function testMergeNumericKey()
    {
        $mixed = \Rde\array_merge_callback(
            function($n, $k, &$arr){
                $arr[] = $n;
            },
            array('a'),
            array('a', 'b'),
            array('c')
        );

        $this->assertEquals(
            array('a', 'a', 'b', 'c'),
            $mixed,
            '檢查純數字key merge'
        );
    }

    public function testMergeMixedKey()
    {
        $mixed = \Rde\array_merge_callback(
            function($n, $k, &$arr){
                if (is_numeric($k)) {
                    $arr[] = $n;
                    return;
                }

                $arr[$k] = $n;
            },
            array('a', 'x' => 1),
            array('a', 'b', 'y' => 3, 'x' => 2),
            array('z' => 5, 'c')
        );

        $this->assertEquals(
            array('a', 'x' => 2, 'a', 'b', 'y' => 3, 'z' => 5, 'c'),
            $mixed,
            '檢查混和key merge'
        );
    }

    public function testMergeMin()
    {
        $mixed = \Rde\array_merge_callback(
            function($n, $k, &$arr){
                $arr[$k] = isset($arr[$k]) && $n > $arr[$k] ? $arr[$k] : $n;
            },
            array('x' => 3, 'y' => 4),
            array('x' => 5, 'y' => 2),
            array('x' => 6, 'y' => 1)
        );

        $this->assertEquals(
            array('x' => 3, 'y' => 1),
            $mixed,
            '檢查merge最小值'
        );
    }

    public function testMergeRecursive()
    {
        $mixed = \Rde\array_merge_callback(
            function($n, $k, &$arr, $driver) {
                if (is_numeric($k)) {
                    $arr[] = $n;
                } elseif (isset($arr[$k]) && is_array($n) && is_array($arr[$k])) {
                    $arr[$k] = \Rde\array_merge_callback($driver, $arr[$k], $n);
                } else {
                    $arr[$k] = $n;
                }
            },
            array('x' => array(3,2,1), array('x','y')),
            array('x' => array('a' => 4), array('z'), 'y' => array(1)),
            array('x' => array('b' => 5), 'y' => 1)
        );

        $this->assertEquals(
            array(
                'x' => array(3, 2, 1, 'a' => 4, 'b' => 5),
                array('x','y'),
                array('z'),
                'y' => 1
            ),
            $mixed,
            '檢查遞迴混和merge'
        );
    }

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
            [9,8, 'a' => 7,6,5],
            $collection,
            '檢查蒐集陣列take(5)');

        $collection = [];

        foreach (Rde\array_take($source, 11) as $k => $v) {
            $collection[$k] = $v;
        }

        $this->assertEquals(
            $source,
            $collection,
            '檢查蒐集陣列take(11)');
    }
}
