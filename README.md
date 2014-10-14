## 工具函式庫
colin1124x/common 0.0.5 2014-10
PHP >= 5.3

[![Build Status](https://travis-ci.org/colin1124x/common.svg?branch=0.0.5)](https://travis-ci.org/colin1124x/common)

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