Collections
===========
The Collection package is simple Laravel Like PHP class, which provides a convenient way of working with arrays of data. This class provides many useful methods to perform common tasks on arrays.

*   [Introduction](#introduction)
    *   [Creating Collections](#creating-collections)
    *   [Extending Collections](#extending-collections)
*   [Available Methods](#available-methods)

[Introduction](#introduction)
-----------------------------

The `Imran\Collection\Collection` class provides a fluent, convenient wrapper for working with arrays of data. For example, check out the following code. We'll use the `new Collection()` to create a new collection instance from the array, run the `strtoupper` function on each element, and then remove all empty elements:

```php
$collection = new Collection(['John', 'doe', null]);
$collection->map(function (string $name) { 
      return strtoupper($name);
  })->reject(function (string $name) {
      return empty($name);
  });
```

As you can see, the `Collection` class allows you to chain its methods to perform fluent mapping and reducing of the underlying array. In general, collections are immutable, meaning every `Collection` method returns an entirely new `Collection` instance.

### [Creating Collections](#creating-collections)

Creating a collection is as simple as:

```php
$collection = new Collection([1, 2, 3]);
```

### [Extending Collections](#extending-collections)

Collections are "macroable", which allows you to add additional methods to the `Collection` class at run time. The `Imran\Collection\Collection` class' `macro` method accepts a closure that will be executed when your macro is called. The macro closure may access the collection's other methods via `$this`, just as if it were a real method of the collection class. For example, the following code adds a `toUpper` method to the `Collection` class:

```php
use Imran\Collection\Collection;
Collection::macro('toUpper', function () {
    return $this->map(function (string $value) {
        return strtoupper($value);
    });
});
$collection = new Collection(['first', 'second']);
$upper = $collection->toUpper();
// ['FIRST', 'SECOND']
```

#### [Macro Arguments](#macro-arguments)

If necessary, you may define macros that accept additional arguments:

```php
use Imran\Collection\Collection;
Collection::macro('toLocale', function (string $locale) { 
    return $this->map(function (string $value) use ($locale) {
        return getLocale($value, [], $locale);    
    });
});
$collection = new Collection(['first', 'second']);
$translated = $collection->toLocale('es');
```
[Available Methods](#available-methods)
---------------------------------------

For the majority of the remaining collection documentation, we'll discuss each method available on the `Collection` class. Remember, all of these methods may be chained to fluently manipulate the underlying array. Furthermore, almost every method returns a new `Collection` instance, allowing you to preserve the original copy of the collection when necessary:

|                                                      |                                                     |                                                         |
|---------------------------------------------------------|---------------------------------------------------------|---------------------------------------------------------|
| <a href="#method-all">all</a>                           | <a href="#method-keys">keys</a>                         | <a href="#method-sort">sort</a>                         |
| <a href="#method-average">average</a>                   | <a href="#method-last">last</a>                         | <a href="#method-sortby">sortBy</a>                     |
| <a href="#method-avg">avg</a>                           | <a href="#method-lazy">lazy</a>                         | <a href="#method-sortbydesc">sortByDesc</a>             |
| <a href="#method-chunk">chunk</a>                       | <a href="#method-macro">macro</a>                       | <a href="#method-sortdesc">sortDesc</a>                 |
| <a href="#method-chunkwhile">chunkWhile</a>             | <a href="#method-make">make</a>                         | <a href="#method-sortkeys">sortKeys</a>                 |
| <a href="#method-collapse">collapse</a>                 | <a href="#method-map">map</a>                           | <a href="#method-sortkeysdesc">sortKeysDesc</a>         |
| <a href="#method-collect">collect</a>                   | <a href="#method-mapinto">mapInto</a>                   | <a href="#method-sortkeysusing">sortKeysUsing</a>       |
| <a href="#method-combine">combine</a>                   | <a href="#method-mapspread">mapSpread</a>               | <a href="#method-splice">splice</a>                     |
| <a href="#method-concat">concat</a>                     | <a href="#method-maptogroups">mapToGroups</a>           | <a href="#method-split">split</a>                       |
| <a href="#method-contains">contains</a>                 | <a href="#method-mapwithkeys">mapWithKeys</a>           | <a href="#method-splitin">splitIn</a>                   |
| <a href="#method-containsoneitem">containsOneItem</a>   | <a href="#method-max">max</a>                           | <a href="#method-sum">sum</a>                           |
| <a href="#method-containsstrict">containsStrict</a>     | <a href="#method-median">median</a>                     | <a href="#method-take">take</a>                         |
| <a href="#method-count">count</a>                       | <a href="#method-merge">merge</a>                       | <a href="#method-takeuntil">takeUntil</a>               |
| <a href="#method-countBy">countBy</a>                   | <a href="#method-mergerecursive">mergeRecursive</a>     | <a href="#method-takewhile">takeWhile</a>               |
| <a href="#method-crossjoin">crossJoin</a>               | <a href="#method-min">min</a>                           | <a href="#method-tap">tap</a>                           |
| <a href="#method-dd">dd</a>                             | <a href="#method-mode">mode</a>                         | <a href="#method-times">times</a>                       |
| <a href="#method-diff">diff</a>                         | <a href="#method-nth">nth</a>                           | <a href="#method-toarray">toArray</a>                   |
| <a href="#method-diffassoc">diffAssoc</a>               | <a href="#method-only">only</a>                         | <a href="#method-tojson">toJson</a>                     |
| <a href="#method-diffkeys">diffKeys</a>                 | <a href="#method-pad">pad</a>                           | <a href="#method-transform">transform</a>               |
| <a href="#method-doesntcontain">doesntContain</a>       | <a href="#method-partition">partition</a>               | <a href="#method-undot">undot</a>                       |
| <a href="#method-dump">dump</a>                         | <a href="#method-pipe">pipe</a>                         | <a href="#method-union">union</a>                       |
| <a href="#method-duplicates">duplicates</a>             | <a href="#method-pipeinto">pipeInto</a>                 | <a href="#method-unique">unique</a>                     |
| <a href="#method-duplicatesstrict">duplicatesStrict</a> | <a href="#method-pipethrough">pipeThrough</a>           | <a href="#method-uniquestrict">uniqueStrict</a>         |
| <a href="#method-each">each</a>                         | <a href="#method-pluck">pluck</a>                       | <a href="#method-unless">unless</a>                     |
| <a href="#method-eachspread">eachSpread</a>             | <a href="#method-pop">pop</a>                           | <a href="#method-unlessempty">unlessEmpty</a>           |
| <a href="#method-every">every</a>                       | <a href="#method-prepend">prepend</a>                   | <a href="#method-unlessnotempty">unlessNotEmpty</a>     |
| <a href="#method-except">except</a>                     | <a href="#method-pull">pull</a>                         | <a href="#method-unwrap">unwrap</a>                     |
| <a href="#method-filter">filter</a>                     | <a href="#method-push">push</a>                         | <a href="#method-value">value</a>                       |
| <a href="#method-first">first</a>                       | <a href="#method-put">put</a>                           | <a href="#method-values">values</a>                     |
| <a href="#method-first-or-fail">firstOrFail</a>         | <a href="#method-random">random</a>                     | <a href="#method-when">when</a>                         |
| <a href="#method-first-where">firstWhere</a>            | <a href="#method-range">range</a>                       | <a href="#method-whenempty">whenEmpty</a>               |
| <a href="#method-flatmap">flatMap</a>                   | <a href="#method-reduce">reduce</a>                     | <a href="#method-whennotempty">whenNotEmpty</a>         |
| <a href="#method-flatten">flatten</a>                   | <a href="#method-reduce-spread">reduceSpread</a>        | <a href="#method-where">where</a>                       |
| <a href="#method-flip">flip</a>                         | <a href="#method-reject">reject</a>                     | <a href="#method-wherestrict">whereStrict</a>           |
| <a href="#method-forget">forget</a>                     | <a href="#method-replace">replace</a>                   | <a href="#method-wherebetween">whereBetween</a>         |
| <a href="#method-forpage">forPage</a>                   | <a href="#method-replacerecursive">replaceRecursive</a> | <a href="#method-wherein">whereIn</a>                   |
| <a href="#method-get">get</a>                           | <a href="#method-reverse">reverse</a>                   | <a href="#method-whereinstrict">whereInStrict</a>       |
| <a href="#method-groupby">groupBy</a>                   | <a href="#method-search">search</a>                     | <a href="#method-whereinstanceof">whereInstanceOf</a>   |
| <a href="#method-has">has</a>                           | <a href="#method-shift">shift</a>                       | <a href="#method-wherenotbetween">whereNotBetween</a>   |
| <a href="#method-hasany">hasAny</a>                     | <a href="#method-shuffle">shuffle</a>                   | <a href="#method-wherenotin">whereNotIn</a>             |
| <a href="#method-implode">implode</a>                   | <a href="#method-skip">skip</a>                         | <a href="#method-wherenotinstrict">whereNotInStrict</a> |
| <a href="#method-intersect">intersect</a>               | <a href="#method-skipuntil">skipUntil</a>               | <a href="#method-wherenotnull">whereNotNull</a>         |
| <a href="#method-intersectbykeys">intersectByKeys</a>   | <a href="#method-skipwhile">skipWhile</a>               | <a href="#method-wherenull">whereNull</a>               |
| <a href="#method-isempty">isEmpty</a>                   | <a href="#method-slice">slice</a>                       | <a href="#method-wrap">wrap</a>                         |
| <a href="#method-isnotempty">isNotEmpty</a>             | <a href="#method-sliding">sliding</a>                   | <a href="#method-zip">zip</a>                           |
| <a href="#method-join">join</a>                         | <a href="#method-sole">sole</a>                         |                                                         |
| <a href="#method-keyby">keyBy</a>                       | <a href="#method-some">some</a>                         |


[Method Listing](#method-listing)
---------------------------------

.collection-method code { font-size: 14px; } .collection-method:not(.first-collection-method) { margin-top: 50px; }

#### [`all()`](#method-all)

The `all` method returns the underlying array represented by the collection:
```php
$collection = new Collection([1, 2, 3]);
$collection->all(); // [1, 2, 3]
```

#### [`average()`](#method-average)

Alias for the [`avg`](#method-avg) method.

#### [`avg()`](#method-avg)

The `avg` method returns the [average value](https://en.wikipedia.org/wiki/Average) of a given key:
```php
$collection = new Collection([
    ['foo' => 10],
    ['foo' => 10],
    ['foo' => 20],
    ['foo' => 40]
]);
$average = $collection->avg('foo');
// 20 
$collection = new Collection([1, 1, 2, 4]);
$average = $collection->avg();
// 2
```

#### [`chunk()`](#method-chunk)

The `chunk` method breaks the collection into multiple, smaller collections of a given size:
```php
$collection = new Collection([1, 2, 3, 4, 5, 6, 7]);
$chunks = $collection->chunk(4);
$chunks->all();
// [[1, 2, 3, 4], [5, 6, 7]]
```

#### [`chunkWhile()`](#method-chunkwhile)

The `chunkWhile` method breaks the collection into multiple, smaller collections based on the evaluation of the given callback. The `$chunk` variable passed to the closure may be used to inspect the previous element:
```php
$collection = new Collection(str_split('AABBCCCD'));
$chunks = $collection->chunkWhile(function (string $value, int $key, Collection $chunk) {
    return $value === $chunk->last();
});
$chunks->all();
// [['A', 'A'], ['B', 'B'], ['C', 'C', 'C'], ['D']]
```
#### [`collapse()`](#method-collapse)

The `collapse` method collapses a collection of arrays into a single, flat collection:
```php
$collection = new Collection([
    [1, 2, 3],
    [4, 5, 6],
    [7, 8, 9],
]);
$collapsed = $collection->collapse();
$collapsed->all();
// [1, 2, 3, 4, 5, 6, 7, 8, 9]
```
#### [`collect()`](#method-collect)

The `collect` method returns a new `Collection` instance with the items currently in the collection:
```php
$collectionA = new Collection([1, 2, 3]);
$collectionB = $collectionA->collect();
$collectionB->all();
// [1, 2, 3]
```

#### [`combine()`](#method-combine)

The `combine` method combines the values of the collection, as keys, with the values of another array or collection:
```php
$collection = new Collection(['name', 'age']);
$combined = $collection->combine(['George', 29]);
$combined->all();
// ['name' => 'George', 'age' => 29]
```
#### [`concat()`](#method-concat)

The `concat` method appends the given `array` or collection's values onto the end of another collection:
```php
$collection = new Collection(['John Doe']);
$concatenated = $collection->concat(['Jane Doe'])
                ->concat(['name' => 'Johnny Doe']);
$concatenated->all();
// ['John Doe', 'Jane Doe', 'Johnny Doe']
```
The `concat` method numerically reindexes keys for items concatenated onto the original collection. To maintain keys in associative collections, see the [merge](#method-merge) method.

#### [`contains()`](#method-contains)

The `contains` method determines whether the collection contains a given item. You may pass a closure to the `contains` method to determine if an element exists in the collection matching a given truth test:
```php
$collection = new Collection([1, 2, 3, 4, 5]);
$collection->contains(function (int $value, int $key) {
    return $value > 5;
});
// false
```
Alternatively, you may pass a string to the `contains` method to determine whether the collection contains a given item value:
```php
$collection = new Collection(['name' => 'Desk', 'price' => 100]);
$collection->contains('Desk');
// true
$collection->contains('New York');
// false
```
You may also pass a key / value pair to the `contains` method, which will determine if the given pair exists in the collection:
```php
$collection = new Collection([
    ['product' => 'Desk', 'price' => 200],
    ['product' => 'Chair', 'price' => 100],
]);
$collection->contains('product', 'Bookcase');
// false
```
The `contains` method uses "loose" comparisons when checking item values, meaning a string with an integer value will be considered equal to an integer of the same value. Use the [`containsStrict`](#method-containsstrict) method to filter using "strict" comparisons.

For the inverse of `contains`, see the [doesntContain](#method-doesntcontain) method.

#### [`containsOneItem()`](#method-containsoneitem)

The `containsOneItem` method determines whether the collection contains a single item:
```php
$collection = new Collection([])
$collection->containsOneItem();
// false
$collection = new Collection(['1'])
$collection->containsOneItem();
// true
$collection = new Collection(['1', '2'])
$collection->containsOneItem();
// false
```
#### [`containsStrict()`](#method-containsstrict)

This method has the same signature as the [`contains`](#method-contains) method; however, all values are compared using "strict" comparisons.

#### [`count()`](#method-count)

The `count` method returns the total number of items in the collection:
```php
$collection = new Collection([1, 2, 3, 4]);
$collection->count();
// 4
```
#### [`countBy()`](#method-countBy)

The `countBy` method counts the occurrences of values in the collection. By default, the method counts the occurrences of every element, allowing you to count certain "types" of elements in the collection:
```php
$collection = new Collection([1, 2, 2, 2, 3]);
$counted = $collection->countBy();
$counted->all();
// [1 => 1, 2 => 3, 3 => 1]
```
You pass a closure to the `countBy` method to count all items by a custom value:
```php
$collection = new Collection(['alice@gmail.com', 'bob@yahoo.com', 'carlos@gmail.com']);
$counted = $collection->countBy(function (string $email) {
    return substr(strrchr($email, "@"), 1);
});
$counted->all();
// ['gmail.com' => 2, 'yahoo.com' => 1]
```
#### [`crossJoin()`](#method-crossjoin)

The `crossJoin` method cross joins the collection's values among the given arrays or collections, returning a Cartesian product with all possible permutations:
```php
$collection = new Collection([1, 2]);
$matrix = $collection->crossJoin(['a', 'b']);
$matrix->all();
/*
    [
        [1, 'a'],
        [1, 'b'],
        [2, 'a'],
        [2, 'b'],
    ]
*/
```

#### [`diff()`](#method-diff)

The `diff` method compares the collection against another collection or a plain PHP `array` based on its values. This method will return the values in the original collection that are not present in the given collection:
```php
$collection = new Collection([1, 2, 3, 4, 5]);
$diff = $collection->diff([2, 4, 6, 8]);
$diff->all();
// [1, 3, 5]
```

#### [`diffAssoc()`](#method-diffassoc)

The `diffAssoc` method compares the collection against another collection or a plain PHP `array` based on its keys and values. This method will return the key / value pairs in the original collection that are not present in the given collection:
```php
$collection = new Collection([
    'color' => 'orange',
    'type' => 'fruit',
    'remain' => 6,
]);
$diff = $collection->diffAssoc([
    'color' => 'yellow',
    'type' => 'fruit',
    'remain' => 3,
    'used' => 6,
]);
$diff->all();
// ['color' => 'orange', 'remain' => 6]
```
#### [`diffKeys()`](#method-diffkeys)

The `diffKeys` method compares the collection against another collection or a plain PHP `array` based on its keys. This method will return the key / value pairs in the original collection that are not present in the given collection:
```php
$collection = new Collection([
    'one' => 10,
    'two' => 20,
    'three' => 30,
    'four' => 40,
    'five' => 50,
]);
$diff = $collection->diffKeys([
    'two' => 2,
    'four' => 4,
    'six' => 6,
    'eight' => 8,
]);
$diff->all();
// ['one' => 10, 'three' => 30, 'five' => 50]
```
#### [`doesntContain()`](#method-doesntcontain)

The `doesntContain` method determines whether the collection does not contain a given item. You may pass a closure to the `doesntContain` method to determine if an element does not exist in the collection matching a given truth test:
```php
$collection = new Collection([1, 2, 3, 4, 5]);
$collection->doesntContain(function (int $value, int $key) {    
    return $value < 5;
});
// false
```
Alternatively, you may pass a string to the `doesntContain` method to determine whether the collection does not contain a given item value:
```php
$collection = new Collection(['name' => 'Desk', 'price' => 100]);
$collection->doesntContain('Table');
// true
$collection->doesntContain('Desk');
// false
```
You may also pass a key / value pair to the `doesntContain` method, which will determine if the given pair does not exist in the collection:
```php
$collection = new Collection([
    ['product' => 'Desk', 'price' => 200],
    ['product' => 'Chair', 'price' => 100],
]);
$collection->doesntContain('product', 'Bookcase');
// true
```
The `doesntContain` method uses "loose" comparisons when checking item values, meaning a string with an integer value will be considered equal to an integer of the same value.

#### [`duplicates()`](#method-duplicates)

The `duplicates` method retrieves and returns duplicate values from the collection:
```php
$collection = new Collection(['a', 'b', 'a', 'c', 'b']);
$collection->duplicates();
// [2 => 'a', 4 => 'b']
```
If the collection contains arrays or objects, you can pass the key of the attributes that you wish to check for duplicate values:
```php
$employees = new Collection([
    ['email' => 'abigail@example.com', 'position' => 'Developer'],
    ['email' => 'james@example.com', 'position' => 'Designer'],
    ['email' => 'victoria@example.com', 'position' => 'Developer'],
]);
$employees->duplicates('position');
// [2 => 'Developer']
```
#### [`duplicatesStrict()`](#method-duplicatesstrict)

This method has the same signature as the [`duplicates`](#method-duplicates) method; however, all values are compared using "strict" comparisons.

#### [`each()`](#method-each)

The `each` method iterates over the items in the collection and passes each item to a closure:
```php
$collection = new Collection([1, 2, 3, 4]);
$collection->each(function (int $item, int $key) {
    // ...
});
```
If you would like to stop iterating through the items, you may return `false` from your closure:
```php
$collection->each(function (int $item, int $key) {
    if (/* condition */) {
         return false;
    }
});
```
#### [`eachSpread()`](#method-eachspread)

The `eachSpread` method iterates over the collection's items, passing each nested item value into the given callback:
```php
$collection = new Collection([['John Doe', 35], ['Jane Doe', 33]]);
$collection->eachSpread(function (string $name, int $age) {
    // ...
});
```
You may stop iterating through the items by returning `false` from the callback:
```php
$collection->eachSpread(function (string $name, int $age) {
    return false;
});
```
#### [`every()`](#method-every)

The `every` method may be used to verify that all elements of a collection pass a given truth test:
```php
$collection = new Collection([1, 2, 3, 4]);
$collection->every(function (int $value, int $key) {
    return $value > 2;
});
// false
```
If the collection is empty, the `every` method will return true:
```php
$collection = new Collection([]);
$collection->every(function (int $value, int $key) {
    return $value > 2;
});
// true
```
#### [`except()`](#method-except)

The `except` method returns all items in the collection except for those with the specified keys:
```php
$collection = new Collection(['product_id' => 1, 'price' => 100, 'discount' => false]);
$filtered = $collection->except(['price', 'discount']);
$filtered->all();
// ['product_id' => 1]
```
For the inverse of `except`, see the [only](#method-only) method.

#### [`filter()`](#method-filter)

The `filter` method filters the collection using the given callback, keeping only those items that pass a given truth test:
```php
$collection = new Collection([1, 2, 3, 4]);
$filtered = $collection->filter(function (int $value, int $key) { 
   return $value > 2;
});
$filtered->all();
// [3, 4]
```
If no callback is supplied, all entries of the collection that are equivalent to `false` will be removed:
```php
$collection = new Collection([1, 2, 3, null, false, '', 0, []]);
$collection->filter()->all();
// [1, 2, 3]
```
For the inverse of `filter`, see the [reject](#method-reject) method.

#### [`first()`](#method-first)

The `first` method returns the first element in the collection that passes a given truth test:
```php
$collection = new Collection([1, 2, 3, 4]);
$colleciton->first(function (int $value, int $key) {
    return $value > 2;
});
// 3
```
You may also call the `first` method with no arguments to get the first element in the collection. If the collection is empty, `null` is returned:
```php
$collection = new Collection([1, 2, 3, 4]);
$collection->first();
// 1
```
#### [`firstOrFail()`](#method-first-or-fail)

The `firstOrFail` method is identical to the `first` method; however, if no result is found, an `\Exception` exception will be thrown:
```php
$collection = new Collection([1, 2, 3, 4]);
$collection->firstOrFail(function (int $value, int $key) {
    return $value > 5;
});
// Throws Exception...
```
You may also call the `firstOrFail` method with no arguments to get the first element in the collection. If the collection is empty, an `\ItemNotFoundException` exception will be thrown:
```php
$collection = new Collection([]);
$collection->firstOrFail();
// Throws Exception...
```
#### [`firstWhere()`](#method-first-where)

The `firstWhere` method returns the first element in the collection with the given key / value pair:
```php
$collection = new Collection([
    ['name' => 'Regena', 'age' => null],
    ['name' => 'Linda', 'age' => 14],
    ['name' => 'Diego', 'age' => 23],
    ['name' => 'Linda', 'age' => 84],
]);
$collection->firstWhere('name', 'Linda');
// ['name' => 'Linda', 'age' => 14]
```
You may also call the `firstWhere` method with a comparison operator:
```php
$collection->firstWhere('age', '>=', 18);
// ['name' => 'Diego', 'age' => 23]
```
Like the [where](#method-where) method, you may pass one argument to the `firstWhere` method. In this scenario, the `firstWhere` method will return the first item where the given item key's value is "truthy":
```php
$collection->firstWhere('age');
// ['name' => 'Linda', 'age' => 14]
```
#### [`flatMap()`](#method-flatmap)

The `flatMap` method iterates through the collection and passes each value to the given closure. The closure is free to modify the item and return it, thus forming a new collection of modified items. Then, the array is flattened by one level:
```php
$collection = new Collection([
    ['name' => 'Sally'],
    ['school' => 'Arkansas'],
    ['age' => 28]
]);
$flattened = $collection->flatMap(function (array $values) {
    return array_map('strtoupper', $values);
});
$flattened->all();
// ['name' => 'SALLY', 'school' => 'ARKANSAS', 'age' => '28'];
```
#### [`flatten()`](#method-flatten)

The `flatten` method flattens a multi-dimensional collection into a single dimension:
```php
$collection = new Collection([
    'name' => 'john',
    'languages' => [
        'php', 'javascript'
    ]
]);
$flattened = $collection->flatten();
$flattened->all();
// ['john', 'php', 'javascript'];
```
If necessary, you may pass the `flatten` method a "depth" argument:
```php
$collection = new Collection([
    'Apple' => [
        [
            'name' => 'iPhone 6S',
            'brand' => 'Apple'
        ],
    ],
    'Samsung' => [
        [
            'name' => 'Galaxy S7',
            'brand' => 'Samsung'
        ],
    ],
]);
$products = $collection->flatten(1);
$products->values()->all();
/*
    [
        ['name' => 'iPhone 6S', 'brand' => 'Apple'],
        ['name' => 'Galaxy S7', 'brand' => 'Samsung'],
    ]
*/
```
In this example, calling `flatten` without providing the depth would have also flattened the nested arrays, resulting in `['iPhone 6S', 'Apple', 'Galaxy S7', 'Samsung']`. Providing a depth allows you to specify the number of levels nested arrays will be flattened.

#### [`flip()`](#method-flip)

The `flip` method swaps the collection's keys with their corresponding values:
```php
$collection = new Collection(['name' => 'john', 'framework' => 'laravel']);
$flipped = $collection->flip();
$flipped->all();
// ['john' => 'name', 'laravel' => 'framework']
```
#### [`forget()`](#method-forget)

The `forget` method removes an item from the collection by its key:
```php
$collection = new Collection(['name' => 'john', 'framework' => 'laravel']);
$collection->forget('name');
$collection->all();
// ['framework' => 'laravel']
```
> Unlike most other collection methods, `forget` does not return a new modified collection; it modifies the collection it is called on.

#### [`forPage()`](#method-forpage)

The `forPage` method returns a new collection containing the items that would be present on a given page number. The method accepts the page number as its first argument and the number of items to show per page as its second argument:
```php
$collection = new Collection([1, 2, 3, 4, 5, 6, 7, 8, 9]);
$chunk = $collection->forPage(2, 3);
$chunk->all();
// [4, 5, 6]
```
#### [`get()`](#method-get)

The `get` method returns the item at a given key. If the key does not exist, `null` is returned:
```php
$collection = new Collection(['name' => 'john', 'framework' => 'codeigniter']);
$value = $collection->get('name');
// john
```
You may optionally pass a default value as the second argument:
```php
$collection = new Collection(['name' => 'john', 'framework' => 'codeigniter']);
$value = $collection->get('age', 34);
// 34
```
You may even pass a callback as the method's default value. The result of the callback will be returned if the specified key does not exist:
```php
$collection->get('email', function () {
    return 'john@example.com';
});
// john@example.com
```
#### [`groupBy()`](#method-groupby)

The `groupBy` method groups the collection's items by a given key:
```php
$collection = new Collection([
    ['account_id' => 'account-x10', 'product' => 'Chair'],
    ['account_id' => 'account-x10', 'product' => 'Bookcase'],
    ['account_id' => 'account-x11', 'product' => 'Desk'],
]);
$grouped = $collection->groupBy('account_id');
$grouped->all();
/*
    [
        'account-x10' => [
            ['account_id' => 'account-x10', 'product' => 'Chair'],
            ['account_id' => 'account-x10', 'product' => 'Bookcase'],
        ],
        'account-x11' => [
            ['account_id' => 'account-x11', 'product' => 'Desk'],
        ],
    ]
*/
```
Instead of passing a string `key`, you may pass a callback. The callback should return the value you wish to key the group by:
```php
$grouped = $collection->groupBy(function (array $item, int $key) {
   return substr($item['account_id'], -3);
});
$grouped->all();
/*
    [
        'x10' => [
            ['account_id' => 'account-x10', 'product' => 'Chair'],
            ['account_id' => 'account-x10', 'product' => 'Bookcase'],
        ],
        'x11' => [
            ['account_id' => 'account-x11', 'product' => 'Desk'],
        ],
    ]
*/
```
Multiple grouping criteria may be passed as an array. Each array element will be applied to the corresponding level within a multi-dimensional array:
```php
$data = new Collection([
    10 => ['user' => 1, 'skill' => 1, 'roles' => ['Role_1', 'Role_3']],
    20 => ['user' => 2, 'skill' => 1, 'roles' => ['Role_1', 'Role_2']],
    30 => ['user' => 3, 'skill' => 2, 'roles' => ['Role_1']],
    40 => ['user' => 4, 'skill' => 2, 'roles' => ['Role_2']],
]);
$result = $data->groupBy(['skill', function (array $item) {
    return $item['roles'];
    }],
    preserveKeys: true
);
/*
[
    1 => [
        'Role_1' => [
            10 => ['user' => 1, 'skill' => 1, 'roles' => ['Role_1', 'Role_3']],
            20 => ['user' => 2, 'skill' => 1, 'roles' => ['Role_1', 'Role_2']],
        ],
        'Role_2' => [
            20 => ['user' => 2, 'skill' => 1, 'roles' => ['Role_1', 'Role_2']],
        ],
        'Role_3' => [
            10 => ['user' => 1, 'skill' => 1, 'roles' => ['Role_1', 'Role_3']],
        ],
    ],
    2 => [
        'Role_1' => [
            30 => ['user' => 3, 'skill' => 2, 'roles' => ['Role_1']],
        ],
        'Role_2' => [
            40 => ['user' => 4, 'skill' => 2, 'roles' => ['Role_2']],
        ],
    ],
];
*/
```
#### [`has()`](#method-has)

The `has` method determines if a given key exists in the collection:
```php
$collection = new Collection(['account_id' => 1, 'product' => 'Desk', 'amount' => 5]);
$collection->has('product');
// true
$collection->has(['product', 'amount']);
// true
$collection->has(['amount', 'price']);
// false
```
#### [`hasAny()`](#method-hasany)

The `hasAny` method determines whether any of the given keys exist in the collection:
```php
$collection = new Collection(['account_id' => 1, 'product' => 'Desk', 'amount' => 5]);
$collection->hasAny(['product', 'price']);
// true
$collection->hasAny(['name', 'price']);
// false
```
#### [`implode()`](#method-implode)

The `implode` method joins items in a collection. Its arguments depend on the type of items in the collection. If the collection contains arrays or objects, you should pass the key of the attributes you wish to join, and the "glue" string you wish to place between the values:
```php
$collection = new Collection([
    ['account_id' => 1, 'product' => 'Desk'],
    ['account_id' => 2, 'product' => 'Chair'],
]);
$collection->implode('product', ', ');
// Desk, Chair
```
If the collection contains simple strings or numeric values, you should pass the "glue" as the only argument to the method:
```php
$collection = new Collection([1, 2, 3, 4, 5]);
$collection->implode('-');
// '1-2-3-4-5'
```
You may pass a closure to the `implode` method if you would like to format the values being imploded:
```php
$collection->implode(function (array $item, int $key) {
        return strtoupper($item['product']);
    }, ', ');
// DESK, CHAIR
```
#### [`intersect()`](#method-intersect)

The `intersect` method removes any values from the original collection that are not present in the given `array` or collection. The resulting collection will preserve the original collection's keys:
```php
$collection = new Collection(['Desk', 'Sofa', 'Chair']);
$intersect = $collection->intersect(['Desk', 'Chair', 'Bookcase']);
$intersect->all();
// [0 => 'Desk', 2 => 'Chair']
```

#### [`intersectByKeys()`](#method-intersectbykeys)

The `intersectByKeys` method removes any keys and their corresponding values from the original collection that are not present in the given `array` or collection:
```php
$collection = new Collection([
    'serial' => 'UX301', 'type' => 'screen', 'year' => 2009,
]);
$intersect = $collection->intersectByKeys([
    'reference' => 'UX404', 'type' => 'tab', 'year' => 2011,
]);
$intersect->all();
// ['type' => 'screen', 'year' => 2009]
```
#### [`isEmpty()`](#method-isempty)

The `isEmpty` method returns `true` if the collection is empty; otherwise, `false` is returned:
```php
$collection = new Collection([]);
$collection->isEmpty();
// true
```
#### [`isNotEmpty()`](#method-isnotempty)

The `isNotEmpty` method returns `true` if the collection is not empty; otherwise, `false` is returned:
```php
$collection = new Collection([]);
$collection->isNotEmpty();
// false
```
#### [`join()`](#method-join)

The `join` method joins the collection's values with a string. Using this method's second argument, you may also specify how the final element should be appended to the string:
```php
$collection = new Collection(['a', 'b', 'c']);
$collection->join(', '); 
// 'a, b, c'
$collection = new Collection(['a', 'b', 'c']);
$collection->join(', ', ', and '); 
// 'a, b, and c'
$collection = new Collection(['a', 'b']);
$collection->join(', ', ' and '); 
// 'a and b'
$collection = new Collection(['a']);
$collection->join(', ', ' and '); 
// 'a'
$collection = new Collection([]);
$collection->join(', ', ' and '); 
// ''
```
#### [`keyBy()`](#method-keyby)

The `keyBy` method keys the collection by the given key. If multiple items have the same key, only the last one will appear in the new collection:
```php
$collection = new Collection([
    ['product_id' => 'prod-100', 'name' => 'Desk'],
    ['product_id' => 'prod-200', 'name' => 'Chair'],
]);
$keyed = $collection->keyBy('product_id');
$keyed->all();
/*
    [
        'prod-100' => ['product_id' => 'prod-100', 'name' => 'Desk'],
        'prod-200' => ['product_id' => 'prod-200', 'name' => 'Chair'],
    ]
*/
```
You may also pass a callback to the method. The callback should return the value to key the collection by:
```php
$keyed = $collection->keyBy(function (array $item, int $key) {
    return strtoupper($item['product_id']);
});
$keyed->all();
/*
    [
        'PROD-100' => ['product_id' => 'prod-100', 'name' => 'Desk'],
        'PROD-200' => ['product_id' => 'prod-200', 'name' => 'Chair'],
    ]
*/
```
#### [`keys()`](#method-keys)

The `keys` method returns all of the collection's keys:
```php
$collection = new Collection([
    'prod-100' => ['product_id' => 'prod-100', 'name' => 'Desk'],
    'prod-200' => ['product_id' => 'prod-200', 'name' => 'Chair'],
]);
$keys = $collection->keys();
$keys->all();
// ['prod-100', 'prod-200']
```
#### [`last()`](#method-last)

The `last` method returns the last element in the collection that passes a given truth test:
```php
$collection = new Collection([1, 2, 3, 4]);
$collection->last(function (int $value, int $key) {
    return $value < 3;
});
// 2
```
You may also call the `last` method with no arguments to get the last element in the collection. If the collection is empty, `null` is returned:
```php
$collection = new Collection([1, 2, 3, 4]);
$collection->last();
// 4
```

#### [`macro()`](#method-macro)

The static `macro` method allows you to add methods to the `Collection` class at run time. Refer to the documentation on [extending collections](#extending-collections) for more information.

#### [`make()`](#method-make)

The static `make` method creates a new collection instance. See the [Creating Collections](#creating-collections) section.

#### [`map()`](#method-map)

The `map` method iterates through the collection and passes each value to the given callback. The callback is free to modify the item and return it, thus forming a new collection of modified items:
```php
$collection = new Collection([1, 2, 3, 4, 5]);
$multiplied = $collection->map(function (int $item, int $key) {
    return $item * 2;
});
$multiplied->all();
// [2, 4, 6, 8, 10]
```
> Like most other collection methods, `map` returns a new collection instance; it does not modify the collection it is called on. If you want to transform the original collection, use the [`transform`](#method-transform) method.

#### [`mapInto()`](#method-mapinto)

The `mapInto()` method iterates over the collection, creating a new instance of the given class by passing the value into the constructor:
```php
class Currency
{
    /**
     * Create a new currency instance.
     */
    function __construct(
        public string $code
    ) {}
}
$collection = new Collection(['USD', 'EUR', 'GBP']);
$currencies = $collection->mapInto(Currency::class);
$currencies->all();
// [Currency('USD'), Currency('EUR'), Currency('GBP')]
```
#### [`mapSpread()`](#method-mapspread)

The `mapSpread` method iterates over the collection's items, passing each nested item value into the given closure. The closure is free to modify the item and return it, thus forming a new collection of modified items:
```php
$collection = new Collection([0, 1, 2, 3, 4, 5, 6, 7, 8, 9]);
$chunks = $collection->chunk(2);
$sequence = $chunks->mapSpread(function (int $even, int $odd) {
    return $even + $odd;
});
$sequence->all();
// [1, 5, 9, 13, 17]
```
#### [`mapToGroups()`](#method-maptogroups)

The `mapToGroups` method groups the collection's items by the given closure. The closure should return an associative array containing a single key / value pair, thus forming a new collection of grouped values:
```php
$collection = new Collection([
    [
        'name' => 'John Doe',
        'department' => 'Sales',
    ],
    [
        'name' => 'Jane Doe',
        'department' => 'Sales',
    ],
    [
        'name' => 'Johnny Doe',
        'department' => 'Marketing',
    ]
]);
$grouped = $collection->mapToGroups(function (array $item, int $key) {
    return [$item['department'] => $item['name']];
});
$grouped->all();
/*
    [
        'Sales' => ['John Doe', 'Jane Doe'],
        'Marketing' => ['Johnny Doe'],
    ]
*/
$grouped->get('Sales')->all();
// ['John Doe', 'Jane Doe']
```
#### [`mapWithKeys()`](#method-mapwithkeys)

The `mapWithKeys` method iterates through the collection and passes each value to the given callback. The callback should return an associative array containing a single key / value pair:
```php
$collection = new Collection([
    [
        'name' => 'John',
        'department' => 'Sales',
        'email' => 'john@example.com',
    ],
    [
        'name' => 'Jane',
        'department' => 'Marketing',
        'email' => 'jane@example.com',
    ]
]);
$keyed = $collection->mapWithKeys(function (array $item, int $key) {
    return [$item['email'] => $item['name']];
});
$keyed->all();
/*
    [
        'john@example.com' => 'John',
        'jane@example.com' => 'Jane',
    ]
*/
```
#### [`max()`](#method-max)

The `max` method returns the maximum value of a given key:
```php
$max = new Collection([
    ['foo' => 10],
    ['foo' => 20]
]);
$max->max('foo');
// 20
$max = new Collection([1, 2, 3, 4, 5]);
$max->max();
// 5
```
#### [`median()`](#method-median)

The `median` method returns the [median value](https://en.wikipedia.org/wiki/Median) of a given key:
```php
$median = new Collection([
    ['foo' => 10],
    ['foo' => 10],
    ['foo' => 20],
    ['foo' => 40]
])
$median->median('foo');
// 15
$median = new Collection([1, 1, 2, 4]);
$median->median();
// 1.5
```
#### [`merge()`](#method-merge)

The `merge` method merges the given array or collection with the original collection. If a string key in the given items matches a string key in the original collection, the given item's value will overwrite the value in the original collection:
```php
$collection = new Collection(['product_id' => 1, 'price' => 100]);
$merged = $collection->merge(['price' => 200, 'discount' => false]);
$merged->all();
// ['product_id' => 1, 'price' => 200, 'discount' => false]
```
If the given item's keys are numeric, the values will be appended to the end of the collection:
```php
$collection = new Collection(['Desk', 'Chair']);
$merged = $collection->merge(['Bookcase', 'Door']);
$merged->all();
// ['Desk', 'Chair', 'Bookcase', 'Door']
```
#### [`mergeRecursive()`](#method-mergerecursive)

The `mergeRecursive` method merges the given array or collection recursively with the original collection. If a string key in the given items matches a string key in the original collection, then the values for these keys are merged together into an array, and this is done recursively:
```php
$collection = new Collection(['product_id' => 1, 'price' => 100]);
$merged = $collection->mergeRecursive([
    'product_id' => 2,
    'price' => 200,
    'discount' => false
]);
$merged->all();
// ['product_id' => [1, 2], 'price' => [100, 200], 'discount' => false]
```
#### [`min()`](#method-min)

The `min` method returns the minimum value of a given key:
```php
$min = new Collection([['foo' => 10], ['foo' => 20]]);
$min->min('foo');
// 10
$min = new Collection([1, 2, 3, 4, 5]);
$min->min();
// 1
```
#### [`mode()`](#method-mode)

The `mode` method returns the [mode value](https://en.wikipedia.org/wiki/Mode_(statistics)) of a given key:
```php
$mode = new Collection([
    ['foo' => 10],
    ['foo' => 10],
    ['foo' => 20],
    ['foo' => 40]
]);
$mode->mode('foo');
// [10]
$mode = new Collection([1, 1, 2, 4]);
$mode->mode();
// [1]
$mode = new Collection([1, 1, 2, 2]);
$mode->mode();
// [1, 2]
```
#### [`nth()`](#method-nth)

The `nth` method creates a new collection consisting of every n-th element:
```php
$collection = new Collection(['a', 'b', 'c', 'd', 'e', 'f']);
$collection->nth(4);
// ['a', 'e']
```
You may optionally pass a starting offset as the second argument:
```php
$collection->nth(4, 1);
// ['b', 'f']
```
#### [`only()`](#method-only)

The `only` method returns the items in the collection with the specified keys:
```php
$collection = new Collection([
    'product_id' => 1,
    'name' => 'Desk',
    'price' => 100,
    'discount' => false
]);
$filtered = $collection->only(['product_id', 'name']);
$filtered->all();
// ['product_id' => 1, 'name' => 'Desk']
```
For the inverse of `only`, see the [except](#method-except) method.

#### [`pad()`](#method-pad)

The `pad` method will fill the array with the given value until the array reaches the specified size. This method behaves like the [array\_pad](https://secure.php.net/manual/en/function.array-pad.php) PHP function.

To pad to the left, you should specify a negative size. No padding will take place if the absolute value of the given size is less than or equal to the length of the array:
```php
$collection = new Collection(['A', 'B', 'C']);
$filtered = $collection->pad(5, 0);
$filtered->all();
// ['A', 'B', 'C', 0, 0]
$filtered = $collection->pad(-5, 0);
$filtered->all();
// [0, 0, 'A', 'B', 'C']
```
#### [`partition()`](#method-partition)

The `partition` method may be combined with PHP array destructuring to separate elements that pass a given truth test from those that do not:
```php
$collection = new Collection([1, 2, 3, 4, 5, 6]);
[$underThree, $equalOrAboveThree] = $collection->partition(function (int $i) {
    return $i < 3;
});
 
$underThree->all();
// [1, 2]
 
$equalOrAboveThree->all();
 
// [3, 4, 5, 6]
```
#### [`pipe()`](#method-pipe)

The `pipe` method passes the collection to the given closure and returns the result of the executed closure:
```php
$collection = new Collection([1, 2, 3]);
$piped = $collection->pipe(function (Collection $collection) {
    return $collection->sum();
});
// 6
```
#### [`pipeInto()`](#method-pipeinto)

The `pipeInto` method creates a new instance of the given class and passes the collection into the constructor:
```php
class ResourceCollection
{
    /**
     * Create a new ResourceCollection instance.
     */
    public function __construct(
      public Collection $collection,
    ) {}
}
$collection = new Collection([1, 2, 3]);
$resource = $collection->pipeInto(ResourceCollection::class);
$resource->collection->all();
// [1, 2, 3]
```
#### [`pipeThrough()`](#method-pipethrough)

The `pipeThrough` method passes the collection to the given array of closures and returns the result of the executed closures:
```php
use Imran\Collection\Collection;
$collection = new Collection([1, 2, 3]);
$result = $collection->pipeThrough([
    function (Collection $collection) {
        return $collection->merge([4, 5]);
    },
    function (Collection $collection) {
        return $collection->sum();
    },
]);
// 15
```
#### [`pluck()`](#method-pluck)

The `pluck` method retrieves all of the values for a given key:
```php
$collection = new Collection([
    ['product_id' => 'prod-100', 'name' => 'Desk'],
    ['product_id' => 'prod-200', 'name' => 'Chair'],
]);
$plucked = $collection->pluck('name');
$plucked->all();
// ['Desk', 'Chair']
```
You may also specify how you wish the resulting collection to be keyed:
```php
$plucked = $collection->pluck('name', 'product_id');
$plucked->all();
// ['prod-100' => 'Desk', 'prod-200' => 'Chair']
```
The `pluck` method also supports retrieving nested values using "dot" notation:
```php
$collection = new Collection([
    [
        'name' => 'Laracon',
        'speakers' => [
            'first_day' => ['Rosa', 'Judith'],
        ],
    ],
    [
        'name' => 'VueConf',
        'speakers' => [
            'first_day' => ['Abigail', 'Joey'],
        ],
    ],
]);
$plucked = $collection->pluck('speakers.first_day');
$plucked->all();
// [['Rosa', 'Judith'], ['Abigail', 'Joey']]
```
If duplicate keys exist, the last matching element will be inserted into the plucked collection:
```php
$collection = new Collection([
    ['brand' => 'Tesla',  'color' => 'red'],
    ['brand' => 'Pagani', 'color' => 'white'],
    ['brand' => 'Tesla',  'color' => 'black'],
    ['brand' => 'Pagani', 'color' => 'orange'],
]);
$plucked = $collection->pluck('color', 'brand');
$plucked->all();
// ['Tesla' => 'black', 'Pagani' => 'orange']
```
#### [`pop()`](#method-pop)

The `pop` method removes and returns the last item from the collection:
```php
$collection = new Collection([1, 2, 3, 4, 5]);
$collection->pop();
// 5
$collection->all();
// [1, 2, 3, 4]
```
You may pass an integer to the `pop` method to remove and return multiple items from the end of a collection:
```php
$collection = new Collection([1, 2, 3, 4, 5]);
$collection->pop(3);
// new Collection([5, 4, 3])
$collection->all();
// [1, 2]
```
#### [`prepend()`](#method-prepend)

The `prepend` method adds an item to the beginning of the collection:
```php
$collection = new Collection([1, 2, 3, 4, 5]);
$collection->prepend(0);
$collection->all();
// [0, 1, 2, 3, 4, 5]
```
You may also pass a second argument to specify the key of the prepended item:
```php
$collection = new Collection(['one' => 1, 'two' => 2]);
$collection->prepend(0, 'zero');
$collection->all();
// ['zero' => 0, 'one' => 1, 'two' => 2]
```
#### [`pull()`](#method-pull)

The `pull` method removes and returns an item from the collection by its key:
```php
$collection = new Collection(['product_id' => 'prod-100', 'name' => 'Desk']);
$collection->pull('name');
// 'Desk'
$collection->all();
// ['product_id' => 'prod-100']
```
#### [`push()`](#method-push)

The `push` method appends an item to the end of the collection:
```php
$collection = new Collection([1, 2, 3, 4]);
$collection->push(5);
$collection->all();
// [1, 2, 3, 4, 5]
```
#### [`put()`](#method-put)

The `put` method sets the given key and value in the collection:
```php
$collection = new Collection(['product_id' => 1, 'name' => 'Desk']);
$collection->put('price', 100);
$collection->all();
// ['product_id' => 1, 'name' => 'Desk', 'price' => 100]
```
#### [`random()`](#method-random)

The `random` method returns a random item from the collection:
```php
$collection = new Collection([1, 2, 3, 4, 5]);
$collection->random();
// 4 - (retrieved randomly)
```
You may pass an integer to `random` to specify how many items you would like to randomly retrieve. A collection of items is always returned when explicitly passing the number of items you wish to receive:
```php
$random = $collection->random(3);
$random->all();
// [2, 4, 5] - (retrieved randomly)
```
If the collection instance has fewer items than requested, the `random` method will throw an `InvalidArgumentException`.

The `random` method also accepts a closure, which will receive the current collection instance:
```php
use Imran\Collection\Collection;
$random = $collection->random(fn (Collection $items) => min(10, count($items)));
$random->all();
// [1, 2, 3, 4, 5] - (retrieved randomly)
```
#### [`range()`](#method-range)

The `range` method returns a collection containing integers between the specified range:
```php
$collection = new Collection();
$collection->range(3, 6);
$collection->all(); // [3, 4, 5, 6]
```
#### [`reduce()`](#method-reduce)

The `reduce` method reduces the collection to a single value, passing the result of each iteration into the subsequent iteration:
```php
$collection = new Collection([1, 2, 3]);
$total = $collection->reduce(function ($carry, $item) {
    return $carry + $item;
});
// 6
```
The value for `$carry` on the first iteration is `null`; however, you may specify its initial value by passing a second argument to `reduce`:
```php
$collection->reduce(function ($carry, $item) {
        return $carry + $item;
    }, 
4);
// 10
```
The `reduce` method also passes array keys in associative collections to the given callback:
```php
$collection = new Collection([
    'usd' => 1400,
    'gbp' => 1200,
    'eur' => 1000,
]);
$ratio = [
    'usd' => 1,
    'gbp' => 1.37,
    'eur' => 1.22,
];
$collection->reduce(function ($carry, $value, $key) use ($ratio) {
    return $carry + ($value * $ratio[$key]);
});
 
// 4264
```
#### [`reduceSpread()`](#method-reduce-spread)

The `reduceSpread` method reduces the collection to an array of values, passing the results of each iteration into the subsequent iteration. This method is similar to the `reduce` method; however, it can accept multiple initial values:
```php
$collection = new Collection([[1, 2], [3, 4], [5, 6]]);
$result = $collection->reduceSpread(function ($carry, $item, $key) {
          return [$carry + $item[0] + $item[1]];
}, 0);
// [21]
```
#### [`reject()`](#method-reject)

The `reject` method filters the collection using the given closure. The closure should return `true` if the item should be removed from the resulting collection:
```php
$collection = new Collection([1, 2, 3, 4]);
$filtered = $collection->reject(function (int $value, int $key) {
    return $value > 2;
});
$filtered->all();
// [1, 2]
```
For the inverse of the `reject` method, see the [`filter`](#method-filter) method.

#### [`replace()`](#method-replace)

The `replace` method behaves similarly to `merge`; however, in addition to overwriting matching items that have string keys, the `replace` method will also overwrite items in the collection that have matching numeric keys:
```php
$collection = new Collection(['John', 'Abigail', 'James']);
$replaced = $collection->replace([1 => 'Victoria', 3 => 'Finn']);
$replaced->all();
// ['John', 'Victoria', 'James', 'Finn']
```
#### [`replaceRecursive()`](#method-replacerecursive)

This method works like `replace`, but it will recur into arrays and apply the same replacement process to the inner values:
```php
$collection = new Collection([
    'John',
    'Abigail',
    [
        'James',
        'Victoria',
        'Finn'
    ]
]);
$replaced = $collection->replaceRecursive([
    'Charlie',
    2 => [1 => 'King']
]);
 
$replaced->all();
 
// ['Charlie', 'Abigail', ['James', 'King', 'Finn']]
```
#### [`reverse()`](#method-reverse)

The `reverse` method reverses the order of the collection's items, preserving the original keys:
```php
$collection = new Collection(['a', 'b', 'c', 'd', 'e']);
$reversed = $collection->reverse();
$reversed->all();
/*
    [
        4 => 'e',
        3 => 'd',
        2 => 'c',
        1 => 'b',
        0 => 'a',
    ]
*/
```
#### [`search()`](#method-search)

The `search` method searches the collection for the given value and returns its key if found. If the item is not found, `false` is returned:
```php
$collection = new Collection([2, 4, 6, 8]);
$collection->search(4);
// 1
```
The search is done using a "loose" comparison, meaning a string with an integer value will be considered equal to an integer of the same value. To use "strict" comparison, pass `true` as the second argument to the method:
```php
$collection = new Collection([2, 4, 6, 8]);
$collection->search('4', $strict = true);
// false
```
Alternatively, you may provide your own closure to search for the first item that passes a given truth test:
```php
$collection = new Collection([2, 4, 6, 8]);
$collection->search(function (int $item, int $key) {
    return $item > 5;
});
// 2
```
#### [`shift()`](#method-shift)

The `shift` method removes and returns the first item from the collection:
```php
$collection = new Collection([1, 2, 3, 4, 5]);
$collection->shift();
// 1
$collection->all();
// [2, 3, 4, 5]
```
You may pass an integer to the `shift` method to remove and return multiple items from the beginning of a collection:
```php
$collection = new Collection([1, 2, 3, 4, 5]);
$collection->shift(3);
// new Collection([1, 2, 3])
$collection->all();
// [4, 5]
```
#### [`shuffle()`](#method-shuffle)

The `shuffle` method randomly shuffles the items in the collection:
```php
$collection = new Collection([1, 2, 3, 4, 5]);
$shuffled = $collection->shuffle();
$shuffled->all();
// [3, 2, 5, 1, 4] - (generated randomly)
```
#### [`skip()`](#method-skip)

The `skip` method returns a new collection, with the given number of elements removed from the beginning of the collection:
```php
$collection = new Collection([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);
$collection = $collection->skip(4);
$collection->all();
// [5, 6, 7, 8, 9, 10]
```
#### [`skipUntil()`](#method-skipuntil)

The `skipUntil` method skips over items from the collection until the given callback returns `true` and then returns the remaining items in the collection as a new collection instance:
```php
$collection = new Collection([1, 2, 3, 4]);
$subset = $collection->skipUntil(function (int $item) {
    return $item >= 3;
});
$subset->all();
// [3, 4]
```
You may also pass a simple value to the `skipUntil` method to skip all items until the given value is found:
```php
$collection = new Collection([1, 2, 3, 4]);
$subset = $collection->skipUntil(3);
$subset->all();
// [3, 4]
```
> If the given value is not found or the callback never returns `true`, the `skipUntil` method will return an empty collection.

#### [`skipWhile()`](#method-skipwhile)

The `skipWhile` method skips over items from the collection while the given callback returns `true` and then returns the remaining items in the collection as a new collection:
```php
$collection = new Collection([1, 2, 3, 4]);
$subset = $collection->skipWhile(function (int $item) {
    return $item <= 3;
});
$subset->all();
// [4]
```
> If the callback never returns `false`, the `skipWhile` method will return an empty collection.

#### [`slice()`](#method-slice)

The `slice` method returns a slice of the collection starting at the given index:
```php
$collection = new Collection([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);
$slice = $collection->slice(4);
$slice->all();
// [5, 6, 7, 8, 9, 10]
```
If you would like to limit the size of the returned slice, pass the desired size as the second argument to the method:
```php
$slice = $collection->slice(4, 2);
$slice->all();
// [5, 6]
```
The returned slice will preserve keys by default. If you do not wish to preserve the original keys, you can use the [`values`](#method-values) method to reindex them.

#### [`sliding()`](#method-sliding)

The `sliding` method returns a new collection of chunks representing a "sliding window" view of the items in the collection:
```php
$collection = new Collection([1, 2, 3, 4, 5]);
$chunks = $collection->sliding(2);
$chunks->toArray();
// [[1, 2], [2, 3], [3, 4], [4, 5]]
```
This is especially useful in conjunction with the [`eachSpread`](#method-eachspread) method:
```php
$transactions->sliding(2)->eachSpread(function (Collection $previous, Collection $current) {
    $current->total = $previous->total + $current->amount;
});
```
You may optionally pass a second "step" value, which determines the distance between the first item of every chunk:
```php
$collection = new Collection([1, 2, 3, 4, 5]);
$chunks = $collection->sliding(3, step: 2);
$chunks->toArray();
// [[1, 2, 3], [3, 4, 5]]
```
#### [`sole()`](#method-sole)

The `sole` method returns the first element in the collection that passes a given truth test, but only if the truth test matches exactly one element:
```php
$collection = new Collection([1, 2, 3, 4]);
$collection->sole(function (int $value, int $key) {
    return $value === 2;
});
// 2
```
You may also pass a key / value pair to the `sole` method, which will return the first element in the collection that matches the given pair, but only if it exactly one element matches:
```php
$collection = new Collection([
    ['product' => 'Desk', 'price' => 200],
    ['product' => 'Chair', 'price' => 100],
]);
$collection->sole('product', 'Chair');
// ['product' => 'Chair', 'price' => 100]
```
Alternatively, you may also call the `sole` method with no argument to get the first element in the collection if there is only one element:
```php
$collection = new Collection([
    ['product' => 'Desk', 'price' => 200],
]);
$collection->sole();
// ['product' => 'Desk', 'price' => 200]
```

#### [`some()`](#method-some)

Alias for the [`contains`](#method-contains) method.

#### [`sort()`](#method-sort)

The `sort` method sorts the collection. The sorted collection keeps the original array keys, so in the following example we will use the [`values`](#method-values) method to reset the keys to consecutively numbered indexes:
```php
$collection = new Collection([5, 3, 1, 2, 4]);
$sorted = $collection->sort();
$sorted->values()->all();
// [1, 2, 3, 4, 5]
```
If your sorting needs are more advanced, you may pass a callback to `sort` with your own algorithm. Refer to the PHP documentation on [`uasort`](https://secure.php.net/manual/en/function.uasort.php#refsect1-function.uasort-parameters), which is what the collection's `sort` method calls utilizes internally.

> If you need to sort a collection of nested arrays or objects, see the [`sortBy`](#method-sortby) and [`sortByDesc`](#method-sortbydesc) methods.

#### [`sortBy()`](#method-sortby)

The `sortBy` method sorts the collection by the given key. The sorted collection keeps the original array keys, so in the following example we will use the [`values`](#method-values) method to reset the keys to consecutively numbered indexes:
```php
$collection = new Collection([
    ['name' => 'Desk', 'price' => 200],
    ['name' => 'Chair', 'price' => 100],
    ['name' => 'Bookcase', 'price' => 150],
]);
$sorted = $collection->sortBy('price');
$sorted->values()->all();
/*
    [
        ['name' => 'Chair', 'price' => 100],
        ['name' => 'Bookcase', 'price' => 150],
        ['name' => 'Desk', 'price' => 200],
    ]
*/
```
The `sortBy` method accepts [sort flags](https://www.php.net/manual/en/function.sort.php) as its second argument:
```php
$collection = new Collection([
    ['title' => 'Item 1'],
    ['title' => 'Item 12'],
    ['title' => 'Item 3'],
]);
$sorted = $collection->sortBy('title', SORT_NATURAL);
$sorted->values()->all();
/*
    [
        ['title' => 'Item 1'],
        ['title' => 'Item 3'],
        ['title' => 'Item 12'],
    ]
*/
```
Alternatively, you may pass your own closure to determine how to sort the collection's values:
```php
$collection = new Collection([
    ['name' => 'Desk', 'colors' => ['Black', 'Mahogany']],
    ['name' => 'Chair', 'colors' => ['Black']],
    ['name' => 'Bookcase', 'colors' => ['Red', 'Beige', 'Brown']],
]);
$sorted = $collection->sortBy(function (array $product, int $key) {
    return count($product['colors']);
});
 
$sorted->values()->all();
 
/*
    [
        ['name' => 'Chair', 'colors' => ['Black']],
        ['name' => 'Desk', 'colors' => ['Black', 'Mahogany']],
        ['name' => 'Bookcase', 'colors' => ['Red', 'Beige', 'Brown']],
    ]
*/
```
If you would like to sort your collection by multiple attributes, you may pass an array of sort operations to the `sortBy` method. Each sort operation should be an array consisting of the attribute that you wish to sort by and the direction of the desired sort:
```php
$collection = new Collection([
    ['name' => 'John Doe', 'age' => 34],
    ['name' => 'Abigail Doe', 'age' => 30],
    ['name' => 'John Doe', 'age' => 36],
    ['name' => 'Abigail Doe', 'age' => 32],
]);
$sorted = $collection->sortBy([
    ['name', 'asc'],
    ['age', 'desc'],
]);
 
$sorted->values()->all();
 
/*
    [
        ['name' => 'Abigail Doe', 'age' => 32],
        ['name' => 'Abigail Doe', 'age' => 30],
        ['name' => 'John Doe', 'age' => 36],
        ['name' => 'John Doe', 'age' => 34],
    ]
*/
```
When sorting a collection by multiple attributes, you may also provide closures that define each sort operation:
```php
$collection = new Collection([
    ['name' => 'John', 'age' => 34],
    ['name' => 'Abigail Otwell', 'age' => 30],
    ['name' => 'John', 'age' => 36],
    ['name' => 'Abigail Otwell', 'age' => 32],
]);
$sorted = $collection->sortBy([fn (array $a, array $b) => $a['name'] <=> $b['name'],fn (array $a, array $b) => $b['age'] <=> $a['age'],]);
$sorted->values()->all();
/*
    [
        ['name' => 'Abigail Doe', 'age' => 32],
        ['name' => 'Abigail Doe', 'age' => 30],
        ['name' => 'John', 'age' => 36],
        ['name' => 'John', 'age' => 34],
    ]
*/
```
#### [`sortByDesc()`](#method-sortbydesc)

This method has the same signature as the [`sortBy`](#method-sortby) method, but will sort the collection in the opposite order.

#### [`sortDesc()`](#method-sortdesc)

This method will sort the collection in the opposite order as the [`sort`](#method-sort) method:
```php
$collection = new Collection([5, 3, 1, 2, 4]);
$sorted = $collection->sortDesc();
$sorted->values()->all();
// [5, 4, 3, 2, 1]
```
Unlike `sort`, you may not pass a closure to `sortDesc`. Instead, you should use the [`sort`](#method-sort) method and invert your comparison.

#### [`sortKeys()`](#method-sortkeys)

The `sortKeys` method sorts the collection by the keys of the underlying associative array:
```php
$collection = new Collection([
    'id' => 22345,
    'first' => 'John',
    'last' => 'Doe',
]);
$sorted = $collection->sortKeys();
 
$sorted->all();
 
/*
    [
        'first' => 'John',
        'id' => 22345,
        'last' => 'Doe',
    ]
*/
```
#### [`sortKeysDesc()`](#method-sortkeysdesc)

This method has the same signature as the [`sortKeys`](#method-sortkeys) method, but will sort the collection in the opposite order.

#### [`sortKeysUsing()`](#method-sortkeysusing)

The `sortKeysUsing` method sorts the collection by the keys of the underlying associative array using a callback:
```php
$collection = new Collection([
    'ID' => 22345,
    'first' => 'John',
    'last' => 'Doe',
]);
$sorted = $collection->sortKeysUsing('strnatcasecmp');
 
$sorted->all();
 
/*
    [
        'first' => 'John',
        'ID' => 22345,
        'last' => 'Doe',
    ]
*/
```
The callback must be a comparison function that returns an integer less than, equal to, or greater than zero. For more information, refer to the PHP documentation on [`uksort`](https://www.php.net/manual/en/function.uksort.php#refsect1-function.uksort-parameters), which is the PHP function that `sortKeysUsing` method utilizes internally.

#### [`splice()`](#method-splice)

The `splice` method removes and returns a slice of items starting at the specified index:
```php
$collection = new Collection([1, 2, 3, 4, 5]);
$chunk = $collection->splice(2);
$chunk->all();
// [3, 4, 5]
$collection->all();
// [1, 2]
```
You may pass a second argument to limit the size of the resulting collection:
```php
$collection = new Collection([1, 2, 3, 4, 5]);
$chunk = $collection->splice(2, 1);
$chunk->all();
// [3]
$collection->all();
// [1, 2, 4, 5]
```
In addition, you may pass a third argument containing the new items to replace the items removed from the collection:
```php
$collection = new Collection([1, 2, 3, 4, 5]);
$chunk = $collection->splice(2, 1, [10, 11]);
$chunk->all();
// [3]
$collection->all();
// [1, 2, 10, 11, 4, 5]
```
#### [`split()`](#method-split)

The `split` method breaks a collection into the given number of groups:
```php
$collection = new Collection([1, 2, 3, 4, 5]);
$groups = $collection->split(3);
$groups->all();
// [[1, 2], [3, 4], [5]]
```
#### [`splitIn()`](#method-splitin)

The `splitIn` method breaks a collection into the given number of groups, filling non-terminal groups completely before allocating the remainder to the final group:
```php
$collection = new Collection([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);
$groups = $collection->splitIn(3);
$groups->all();
// [[1, 2, 3, 4], [5, 6, 7, 8], [9, 10]]
```
#### [`sum()`](#method-sum)

The `sum` method returns the sum of all items in the collection:
```php
$collection = new Collection([1, 2, 3, 4, 5]);
$collection->sum();
// 15
```
If the collection contains nested arrays or objects, you should pass a key that will be used to determine which values to sum:
```php
$collection = new Collection([
    ['name' => 'JavaScript: The Good Parts', 'pages' => 176],
    ['name' => 'JavaScript: The Definitive Guide', 'pages' => 1096],
]);
$collection->sum('pages');
// 1272
```
In addition, you may pass your own closure to determine which values of the collection to sum:
```php
    $collection = new Collection([
    ['name' => 'Chair', 'colors' => ['Black']],
    ['name' => 'Desk', 'colors' => ['Black', 'Mahogany']],
    ['name' => 'Bookcase', 'colors' => ['Red', 'Beige', 'Brown']],
]);
$collection->sum(function (array $product) {
    return count($product['colors']);
});
 
// 6
```
#### [`take()`](#method-take)

The `take` method returns a new collection with the specified number of items:
```php
$collection = new Collection([0, 1, 2, 3, 4, 5]);
$chunk = $collection->take(3);
$chunk->all();
// [0, 1, 2]
```
You may also pass a negative integer to take the specified number of items from the end of the collection:
```php
$collection = new Collection([0, 1, 2, 3, 4, 5]);
$chunk = $collection->take(-2);
$chunk->all();
// [4, 5]
```
#### [`takeUntil()`](#method-takeuntil)

The `takeUntil` method returns items in the collection until the given callback returns `true`:
```php
$collection = new Collection([1, 2, 3, 4]);
$subset = $collection->takeUntil(function (int $item) {
    return $item >= 3;
});
 
$subset->all();
 
// [1, 2]
```
You may also pass a simple value to the `takeUntil` method to get the items until the given value is found:
```php
$collection = new Collection([1, 2, 3, 4]);
$subset = $collection->takeUntil(3);
$subset->all();
// [1, 2]
```
> If the given value is not found or the callback never returns `true`, the `takeUntil` method will return all items in the collection.

#### [`takeWhile()`](#method-takewhile)

The `takeWhile` method returns items in the collection until the given callback returns `false`:
```php
$collection = new Collection([1, 2, 3, 4]);
$subset = $collection->takeWhile(function (int $item) {
    return $item < 3;
});
 
$subset->all();
 
// [1, 2]
```
> If the callback never returns `false`, the `takeWhile` method will return all items in the collection.

#### [`tap()`](#method-tap)

The `tap` method passes the collection to the given callback, allowing you to "tap" into the collection at a specific point and do something with the items while not affecting the collection itself. The collection is then returned by the `tap` method:
```php
$collection = new Collection([2, 4, 3, 1, 5]);
$collection->sort()
    ->tap(function (Collection $collection) {
        Log::debug('Values after sorting', $collection->values()->all());
    })
    ->shift();
// 1
```
#### [`times()`](#method-times)

The static `times` method creates a new collection by invoking the given closure a specified number of times:
```php
$collection = Collection::times(10, function (int $number) {
    return $number * 9;
});
 
$collection->all();
 
// [9, 18, 27, 36, 45, 54, 63, 72, 81, 90]
```
#### [`toArray()`](#method-toarray)

The `toArray` method converts the collection into a plain PHP `array`. If the collection's values are [Eloquent](/docs/10.x/eloquent) models, the models will also be converted to arrays:
```php
$collection = new Collection(['name' => 'Desk', 'price' => 200]);
$collection->toArray();
/*
    [
        ['name' => 'Desk', 'price' => 200],
    ]
*/
```
> ![](/img/callouts/exclamation.min.svg)
>
> `toArray` also converts all of the collection's nested objects that are an instance of `Arrayable` to an array. If you want to get the raw array underlying the collection, use the [`all`](#method-all) method instead.

#### [`toJson()`](#method-tojson)

The `toJson` method converts the collection into a JSON serialized string:
```php
$collection = new Collection(['name' => 'Desk', 'price' => 200]);
$collection->toJson();
// '{"name":"Desk", "price":200}'
```
#### [`transform()`](#method-transform)

The `transform` method iterates over the collection and calls the given callback with each item in the collection. The items in the collection will be replaced by the values returned by the callback:
```php
$collection = new Collection([1, 2, 3, 4, 5]);
$collection->transform(function (int $item, int $key) {
    return $item * 2;
});
 
$collection->all();
 
// [2, 4, 6, 8, 10]
```
> Unlike most other collection methods, `transform` modifies the collection itself. If you wish to create a new collection instead, use the [`map`](#method-map) method.

#### [`undot()`](#method-undot)

The `undot` method expands a single-dimensional collection that uses "dot" notation into a multi-dimensional collection:
```php
$person = new Collection([
    'name.first_name' => 'Marie',
    'name.last_name' => 'Valentine',
    'address.line_1' => '2992 Eagle Drive',
    'address.line_2' => '',
    'address.suburb' => 'Detroit',
    'address.state' => 'MI',
    'address.postcode' => '48219'
]);
$person = $person->undot();
 
$person->toArray();
 
/*
    [
        "name" => [
            "first_name" => "Marie",
            "last_name" => "Valentine",
        ],
        "address" => [
            "line_1" => "2992 Eagle Drive",
            "line_2" => "",
            "suburb" => "Detroit",
            "state" => "MI",
            "postcode" => "48219",
        ],
    ]
*/
```
#### [`union()`](#method-union)

The `union` method adds the given array to the collection. If the given array contains keys that are already in the original collection, the original collection's values will be preferred:
```php
$collection = new Collection([1 => ['a'], 2 => ['b']]);
$union = $collection->union([3 => ['c'], 1 => ['d']]);
$union->all();
// [1 => ['a'], 2 => ['b'], 3 => ['c']]
```
#### [`unique()`](#method-unique)

The `unique` method returns all of the unique items in the collection. The returned collection keeps the original array keys, so in the following example we will use the [`values`](#method-values) method to reset the keys to consecutively numbered indexes:
```php
$collection = new Collection([1, 1, 2, 2, 3, 4, 2]);
$unique = $collection->unique();
$unique->values()->all();
// [1, 2, 3, 4]
```
When dealing with nested arrays or objects, you may specify the key used to determine uniqueness:
```php
$collection = new Collection([
    ['name' => 'iPhone 6', 'brand' => 'Apple', 'type' => 'phone'],
    ['name' => 'iPhone 5', 'brand' => 'Apple', 'type' => 'phone'],
    ['name' => 'Apple Watch', 'brand' => 'Apple', 'type' => 'watch'],
    ['name' => 'Galaxy S6', 'brand' => 'Samsung', 'type' => 'phone'],
    ['name' => 'Galaxy Gear', 'brand' => 'Samsung', 'type' => 'watch'],
]);
$unique = $collection->unique('brand');
 
$unique->values()->all();
 
/*
    [
        ['name' => 'iPhone 6', 'brand' => 'Apple', 'type' => 'phone'],
        ['name' => 'Galaxy S6', 'brand' => 'Samsung', 'type' => 'phone'],
    ]
*/
```
Finally, you may also pass your own closure to the `unique` method to specify which value should determine an item's uniqueness:
```php
$unique = $collection->unique(function (array $item) {
    return $item['brand'].$item['type'];
});
 
$unique->values()->all();
 
/*
    [
        ['name' => 'iPhone 6', 'brand' => 'Apple', 'type' => 'phone'],
        ['name' => 'Apple Watch', 'brand' => 'Apple', 'type' => 'watch'],
        ['name' => 'Galaxy S6', 'brand' => 'Samsung', 'type' => 'phone'],
        ['name' => 'Galaxy Gear', 'brand' => 'Samsung', 'type' => 'watch'],
    ]
*/
```
The `unique` method uses "loose" comparisons when checking item values, meaning a string with an integer value will be considered equal to an integer of the same value. Use the [`uniqueStrict`](#method-uniquestrict) method to filter using "strict" comparisons.

#### [`uniqueStrict()`](#method-uniquestrict)

This method has the same signature as the [`unique`](#method-unique) method; however, all values are compared using "strict" comparisons.

#### [`unless()`](#method-unless)

The `unless` method will execute the given callback unless the first argument given to the method evaluates to `true`:
```php
$collection = new Collection([1, 2, 3]);
$collection->u$collection->unless(true, function (Collection $collection) {
    return $collection->push(4);
});
 
$collection->unless(false, function (Collection $collection) {
    return $collection->push(5);
});
 
$collection->all();
 
// [1, 2, 3, 5]
```
A second callback may be passed to the `unless` method. The second callback will be executed when the first argument given to the `unless` method evaluates to `true`:
```php
$collection = new Collection([1, 2, 3]);
$collection->unless(true, function (Collection $collection) {
    return $collection->push(4);
}, function (Collection $collection) {
    return $collection->push(5);
});
 
$collection->all();
 
// [1, 2, 3, 5]
```
For the inverse of `unless`, see the [`when`](#method-when) method.

#### [`unlessEmpty()`](#method-unlessempty)

Alias for the [`whenNotEmpty`](#method-whennotempty) method.

#### [`unlessNotEmpty()`](#method-unlessnotempty)

Alias for the [`whenEmpty`](#method-whenempty) method.

#### [`unwrap()`](#method-unwrap)

The static `unwrap` method returns the collection's underlying items from the given value when applicable:
```php
Collection::unwrap(new Collection('John Doe'));
// ['John Doe']
Collection::unwrap(['John Doe']);
// ['John Doe']
Collection::unwrap('John Doe');
// 'John Doe'
```
#### [`value()`](#method-value)

The `value` method retrieves a given value from the first element of the collection:
```php
$collection = new Collection([
    ['product' => 'Desk', 'price' => 200],
    ['product' => 'Speaker', 'price' => 400],
]);
$value = $collection->value('price');
// 200
```
#### [`values()`](#method-values)

The `values` method returns a new collection with the keys reset to consecutive integers:
```php
$collection = new Collection([
    10 => ['product' => 'Desk', 'price' => 200],
    11 => ['product' => 'Desk', 'price' => 200],
]);
$values = $collection->values();
$values->all();
/*
    [
        0 => ['product' => 'Desk', 'price' => 200],
        1 => ['product' => 'Desk', 'price' => 200],
    ]
*/
```
#### [`when()`](#method-when)

The `when` method will execute the given callback when the first argument given to the method evaluates to `true`. The collection instance and the first argument given to the `when` method will be provided to the closure:
```php
$collection = new Collection([1, 2, 3]);
$collection->when(true, function (Collection $collection, int $value) {
    return $collection->push(4);
});
 
$collection->when(false, function (Collection $collection, int $value) {
    return $collection->push(5);
});
 
$collection->all();
 
// [1, 2, 3, 4]
```
A second callback may be passed to the `when` method. The second callback will be executed when the first argument given to the `when` method evaluates to `false`:
```php
$collection = new Collection([1, 2, 3]);
$collection->when(false, function (Collection $collection, int $value) {
    return $collection->push(4);
}, function (Collection $collection) {
    return $collection->push(5);
});
 
$collection->all();
 
// [1, 2, 3, 5]
```
For the inverse of `when`, see the [`unless`](#method-unless) method.

#### [`whenEmpty()`](#method-whenempty)

The `whenEmpty` method will execute the given callback when the collection is empty:
```php
$collection = new Collection(['Michael', 'Tom']);
$collection->whenEmpty(function (Collection $collection) {
    return $collection->push('Adam');
});
 
$collection->all();
 
// ['Michael', 'Tom']
 
 
$collection = new Collection();
 
$collection->whenEmpty(function (Collection $collection) {
    return $collection->push('Adam');
});
 
$collection->all();
 
// ['Adam']
```
A second closure may be passed to the `whenEmpty` method that will be executed when the collection is not empty:
```php
$collection = new Collection(['Michael', 'Tom']);
$collection->whenEmpty(function (Collection $collection) {
    return $collection->push('Adam');
}, function (Collection $collection) {
    return $collection->push('Taylor');
});
 
$collection->all();
 
// ['Michael', 'Tom', 'Taylor']
```
For the inverse of `whenEmpty`, see the [`whenNotEmpty`](#method-whennotempty) method.

#### [`whenNotEmpty()`](#method-whennotempty)

The `whenNotEmpty` method will execute the given callback when the collection is not empty:
```php
$collection = new Collection(['michael', 'tom']);
$collection->whenNotEmpty(function (Collection $collection) {
    return $collection->push('adam');
});
 
$collection->all();
 
// ['michael', 'tom', 'adam']
 
 
$collection = collect();
 
$collection->whenNotEmpty(function (Collection $collection) {
    return $collection->push('adam');
});
 
$collection->all();
 
// []
```
A second closure may be passed to the `whenNotEmpty` method that will be executed when the collection is empty:
```php
$collection = new Collection();
$collection->whenNotEmpty(function (Collection $collection) {
    return $collection->push('adam');
}, function (Collection $collection) {
    return $collection->push('john');
});
 
$collection->all();
 
// ['john']
```
For the inverse of `whenNotEmpty`, see the [`whenEmpty`](#method-whenempty) method.

#### [`where()`](#method-where)

The `where` method filters the collection by a given key / value pair:
```php
$collection = new Collection([
    ['product' => 'Desk', 'price' => 200],
    ['product' => 'Chair', 'price' => 100],
    ['product' => 'Bookcase', 'price' => 150],
    ['product' => 'Door', 'price' => 100],
]);
$filtered = $collection->where('price', 100);
$filtered->all();
/*
    [
        ['product' => 'Chair', 'price' => 100],
        ['product' => 'Door', 'price' => 100],
    ]
*/
```
The `where` method uses "loose" comparisons when checking item values, meaning a string with an integer value will be considered equal to an integer of the same value. Use the [`whereStrict`](#method-wherestrict) method to filter using "strict" comparisons.

Optionally, you may pass a comparison operator as the second parameter. Supported operators are: '===', '!==', '!=', '==', '=', '<>', '>', '<', '>=', and '<=':
```php
$collection = new Collection([
    ['name' => 'Jim', 'deleted_at' => '2019-01-01 00:00:00'],
    ['name' => 'Sally', 'deleted_at' => '2019-01-02 00:00:00'],
    ['name' => 'Sue', 'deleted_at' => null],
]);
$filtered = $collection->where('deleted_at', '!=', null);
$filtered->all();
/*
    [
        ['name' => 'Jim', 'deleted_at' => '2019-01-01 00:00:00'],
        ['name' => 'Sally', 'deleted_at' => '2019-01-02 00:00:00'],
    ]
*/
```
#### [`whereStrict()`](#method-wherestrict)

This method has the same signature as the [`where`](#method-where) method; however, all values are compared using "strict" comparisons.

#### [`whereBetween()`](#method-wherebetween)

The `whereBetween` method filters the collection by determining if a specified item value is within a given range:
```php
$collection = new Collection([
    ['product' => 'Desk', 'price' => 200],
    ['product' => 'Chair', 'price' => 80],
    ['product' => 'Bookcase', 'price' => 150],
    ['product' => 'Pencil', 'price' => 30],
    ['product' => 'Door', 'price' => 100],
]);
$filtered = $collection->whereBetween('price', [100, 200]);
$filtered->all();
/*
    [
        ['product' => 'Desk', 'price' => 200],
        ['product' => 'Bookcase', 'price' => 150],
        ['product' => 'Door', 'price' => 100],
    ]
*/
```
#### [`whereIn()`](#method-wherein)

The `whereIn` method removes elements from the collection that do not have a specified item value that is contained within the given array:
```php
$collection = new Collection([
    ['product' => 'Desk', 'price' => 200],
    ['product' => 'Chair', 'price' => 100],
    ['product' => 'Bookcase', 'price' => 150],
    ['product' => 'Door', 'price' => 100],
]);
$filtered = $collection->whereIn('price', [150, 200]);
$filtered->all();
/*
    [
        ['product' => 'Desk', 'price' => 200],
        ['product' => 'Bookcase', 'price' => 150],
    ]
*/
```
The `whereIn` method uses "loose" comparisons when checking item values, meaning a string with an integer value will be considered equal to an integer of the same value. Use the [`whereInStrict`](#method-whereinstrict) method to filter using "strict" comparisons.

#### [`whereInStrict()`](#method-whereinstrict)

This method has the same signature as the [`whereIn`](#method-wherein) method; however, all values are compared using "strict" comparisons.

#### [`whereNotBetween()`](#method-wherenotbetween)

The `whereNotBetween` method filters the collection by determining if a specified item value is outside of a given range:
```php
$collection = new Collection([
    ['product' => 'Desk', 'price' => 200],
    ['product' => 'Chair', 'price' => 80],
    ['product' => 'Bookcase', 'price' => 150],
    ['product' => 'Pencil', 'price' => 30],
    ['product' => 'Door', 'price' => 100],
]);
$filtered = $collection->whereNotBetween('price', [100, 200]);
 
$filtered->all();
 
/*
    [
        ['product' => 'Chair', 'price' => 80],
        ['product' => 'Pencil', 'price' => 30],
    ]
*/
```
#### [`whereNotIn()`](#method-wherenotin)

The `whereNotIn` method removes elements from the collection that have a specified item value that is contained within the given array:
```php
$collection = new Collection([
    ['product' => 'Desk', 'price' => 200],
    ['product' => 'Chair', 'price' => 100],
    ['product' => 'Bookcase', 'price' => 150],
    ['product' => 'Door', 'price' => 100],
]);
$filtered = $collection->whereNotIn('price', [150, 200]);
$filtered->all();
/*
    [
        ['product' => 'Chair', 'price' => 100],
        ['product' => 'Door', 'price' => 100],
    ]
*/
```
The `whereNotIn` method uses "loose" comparisons when checking item values, meaning a string with an integer value will be considered equal to an integer of the same value. Use the [`whereNotInStrict`](#method-wherenotinstrict) method to filter using "strict" comparisons.

#### [`whereNotInStrict()`](#method-wherenotinstrict)

This method has the same signature as the [`whereNotIn`](#method-wherenotin) method; however, all values are compared using "strict" comparisons.

#### [`whereNotNull()`](#method-wherenotnull)

The `whereNotNull` method returns items from the collection where the given key is not `null`:
```php
$collection = new Collection([
    ['name' => 'Desk'],
    ['name' => null],
    ['name' => 'Bookcase'],
]);
 
$filtered = $collection->whereNotNull('name');
 
$filtered->all();
 
/*
    [
        ['name' => 'Desk'],
        ['name' => 'Bookcase'],
    ]
*/
```
#### [`whereNull()`](#method-wherenull)

The `whereNull` method returns items from the collection where the given key is `null`:
```php
$collection = new Collection([
    ['name' => 'Desk'],
    ['name' => null],
    ['name' => 'Bookcase'],
]);
 
$filtered = $collection->whereNull('name');
 
$filtered->all();
 
/*
    [
        ['name' => null],
    ]
*/
```
#### [`wrap()`](#method-wrap)

The static `wrap` method wraps the given value in a collection when applicable:
```php
use Imran\Collection\Collection;
 
$collection = Collection::wrap('John Doe');
 
$collection->all();
 
// ['John Doe']
 
$collection = Collection::wrap(['John Doe']);
 
$collection->all();
 
// ['John Doe']
 
$collection = Collection::wrap(collect('John Doe'));
 
$collection->all();
 
// ['John Doe']
```
#### [`zip()`](#method-zip)

The `zip` method merges together the values of the given array with the values of the original collection at their corresponding index:
```php
$collection = new Collection(['Chair', 'Desk']);
$zipped = $collection->zip([100, 200]);
$zipped->all();
// [['Chair', 100], ['Desk', 200]]
```
**Note:** This package is identical to Laravel collections, so the documentation used here is taken from Laravel website, just to save time.

### Hi, I'm Imran Ali! 👋

### 🚀 About Me

Senior **Full-Stack** Developer specializing in front end and back-end development. Experienced with all stages of
the development cycle for dynamic web projects. Innovative, creative and a proven team player, I possess
a Tech Degree in Front End Development and have 7 years building developing and managing websites,
applications and programs for various companies. I seek to secure the position of Senior Full
Stack Developer where i can share my skills, expertise and experience with valuable clients.

### 🛠 Skills

PHP OOP,
Laravel,
Codeigniter
Javascript,
Node,
React,
Vue,
Git,
HTML,
Rest Api,
Typescript,
Angular,
SCSS,
Docker,
CI/CD Jenkins,
Bootstrap,
Responsive Design,
ASP.NET Core

### 🔗 Follow on

[![linkedin](https://img.shields.io/badge/linkedin-0A66C2?style=for-the-badge&logo=linkedin&logoColor=white)](https://www.linkedin.com/in/imranali291/)
[![twitter](https://img.shields.io/badge/twitter-1DA1F2?style=for-the-badge&logo=twitter&logoColor=white)](https://twitter.com/imranali125)

### License

[![MIT License](https://img.shields.io/badge/License-MIT-green.svg)](https://choosealicense.com/licenses/mit/)
[![GPLv3 License](https://img.shields.io/badge/License-GPL%20v3-yellow.svg)](https://opensource.org/licenses/)
[![AGPL License](https://img.shields.io/badge/license-AGPL-blue.svg)](http://www.gnu.org/licenses/agpl-3.0)

### Contributing

Contributions are always welcome!

See `contributing.md` for ways to get started.

Please adhere to this project's `code of conduct`.
