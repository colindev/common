## 工具函式庫

[![Build Status](https://travis-ci.org/colindev/common.svg?branch=master)](https://travis-ci.org/colindev/common)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/colindev/common/badges/quality-score.png)](https://scrutinizer-ci.com/g/colindev/common)
[![Code Coverage](https://scrutinizer-ci.com/g/colindev/common/badges/coverage.png)](https://scrutinizer-ci.com/g/colindev/common/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/rde/common/v/stable.svg)](https://packagist.org/packages/rde/common)
[![Total Downloads](https://poser.pugx.org/rde/common/downloads.svg)](https://packagist.org/packages/rde/common)
[![Latest Unstable Version](https://poser.pugx.org/rde/common/v/unstable.svg)](https://packagist.org/packages/rde/common)
[![License](https://poser.pugx.org/rde/common/license.svg)](https://packagist.org/packages/rde/common)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/ba644cfb-3f1d-4a7b-b5eb-d9bd463c684e/mini.png)](https://insight.sensiolabs.com/projects/ba644cfb-3f1d-4a7b-b5eb-d9bd463c684e)

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
