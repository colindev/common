## 工具函式庫

colin1124x/common 2.0.0 2014-10

PHP >= 5.5

[![Build Status](https://travis-ci.org/colin1124x/common.svg)](https://travis-ci.org/colin1124x/common)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/colin1124x/common/badges/quality-score.png)](https://scrutinizer-ci.com/g/colin1124x/common)
[![Code Coverage](https://scrutinizer-ci.com/g/colin1124x/common/badges/coverage.png)](https://scrutinizer-ci.com/g/colin1124x/common/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/rde/common/v/stable.svg)](https://packagist.org/packages/rde/common)
[![Total Downloads](https://poser.pugx.org/rde/common/downloads.svg)](https://packagist.org/packages/rde/common)
[![Latest Unstable Version](https://poser.pugx.org/rde/common/v/unstable.svg)](https://packagist.org/packages/rde/common)
[![License](https://poser.pugx.org/rde/common/license.svg)](https://packagist.org/packages/rde/common)

### 函式庫列表

從陣列中取值

```
array_get($array, $key[, $callable_or_default]);
```

陣列迭代器(支援多陣列迭代)

```
array_each($callback, $array_1[, $array_2[, ...]]);
```

自訂陣列合併

```
array_merge_callback(callable $merge_driver, $base_array[, $array_1[, $array_2[, ...]]])
```

 - `$merge_driver` 會收到4個參數,依序是
    - `$extend_value`
    - `$extend_key`
    - `&$base_array`
    - `$merge_driver`

詳情請參照
 - ArrayFunctionsTest::testMergeNumericKey
 - ArrayFunctionsTest::testMergeMixedKey
 - ArrayFunctionsTest::testMergeMin
 - ArrayFunctionsTest::testMergeRecursive
 
陣列過濾(Generator)
```
array_filter($source, callable $callback)

// 使用範例

$filter = function($val, $key){
    return is_int($key) && 10 < $val;
};

$source = ['a' => 12, 1, 3, 5, 9, 20];

foreach (Rde\array_filter($source, $filter) as $key => $val) {
    echo "{$key} => {$val}\n";
}

// echo
4 => 20
```

陣列擷取(Generator)
```
array_take($source, $cnt)

// 使用範例

$source = [9, 8, 7, 6, 5];
foreach (Rde\array_take($source, 2) as $key => $val) {
    echo "{$key} => {$val}\n"
}

// echo 
0 => 9
1 => 8
```
