<?php

namespace Imran\Collection\Tests;

use Imran\Collection\Collection;
use PHPUnit\Framework\TestCase;

class CollectionTest extends TestCase
{
    /**
     * @group all
     * Test the `all` method.
     */
    public function testAll()
    {
        $collection = new Collection([1, 2, 3]);
        $this->assertEquals([1, 2, 3], $collection->all());
    }

    /**
     * @group average
     * Test the `average` method.
     */
    public function testAverage()
    {
        $collection = new Collection([1, 2, 3]);
        $this->assertEquals(2, $collection->average());
    }

    /**
     * @group avg
     * Test the `avg` method.
     */
    public function testAvg()
    {
        $collection = new Collection([1, 2, 3]);
        $this->assertEquals(2, $collection->avg());
    }

    /**
     * @group chunk
     * Test the `chunk` method.
     */
    public function testChunk()
    {
        $data = ['a', 'b', 'c', 'd', 'e'];
        $collection = new Collection($data);

        $chunks = $collection->chunk(2)->toArray();

        $this->assertCount(3, $chunks);
        $this->assertEquals(['a', 'b'], $chunks[0]);
        $this->assertEquals(['c', 'd'], $chunks[1]);
        $this->assertEquals(['e'], $chunks[2]);
    }



    /**
     * @group chunkWhile
     * Test the `chunkWhile` method.
     */
    public function testChunkWhile()
    {
        $collection = new Collection([1, 2, 3, 4, 5]);
        $chunks = $collection->chunkWhile(function ($value, $key) {
            return $value < 3;
        })->map(function($val) { return $val->values();})->toArray();

        $this->assertEquals(4, count($chunks));
        $this->assertEquals([1, 2], $chunks[0]);
        $this->assertEquals([3], $chunks[1]);
        $this->assertEquals([4], $chunks[2]);
        $this->assertEquals([5], $chunks[3]);
    }

    /**
     * @group collapse
     * Test the `collapse` method.
     */
    public function testCollapse()
    {
        $collection = new Collection([[1, 2], [3, 4], [5]]);
        $collapsed = $collection->collapse();
        $this->assertEquals([1, 2, 3, 4, 5], $collapsed->all());
    }

    /**
     * @group collect
     * Test the `collect` method.
     */
    public function testCollect()
    {
        $array = [1, 2, 3];
        $collection = new Collection($array);
        $collection = $collection->collect($collection);
        $this->assertEquals($array, $collection->all());
    }

    /**
     * @group combine
     * Test the `combine` method.
     */
    public function testCombine()
    {
        $keys = ['name', 'age', 'gender'];
        $values = ['John', 30, 'male'];
        $collection = new Collection($keys);
        $combined = $collection->combine($values);
        $this->assertEquals(['name' => 'John', 'age' => 30, 'gender' => 'male'], $combined->all());
    }

    /**
     * @group concat
     * Test the `concat` method.
     */
    public function testConcat()
    {
        $collection1 = new Collection([1, 2, 3]);
        $collection2 = new Collection([4, 5, 6]);
        $concatenated = $collection1->concat($collection2);

        $this->assertInstanceOf(Collection::class, $concatenated);
        $this->assertEquals([1, 2, 3, 4, 5, 6], $concatenated->toArray());

        // Test that the original collections remain unchanged
        $this->assertEquals([1, 2, 3], $collection1->toArray());
        $this->assertEquals([4, 5, 6], $collection2->toArray());
    }

    /**
     * @group contains
     * Test the `contains` method.
     */
    public function testContains()
    {
        $collection = new Collection(['apple', 'banana', 'cherry']);

        $this->assertTrue($collection->contains('banana'));
        $this->assertTrue($collection->contains(fn($value) => $value === 'banana'));
        $this->assertFalse($collection->contains('orange'));
        $this->assertFalse($collection->contains(fn($value) => $value === 'orange'));
        $collection = new Collection(['name' => 'Desk', 'price' => 100]);
        $this->assertTrue($collection->contains('Desk'));
        $collection = new Collection([1, 2, 3, 4, 5, 6]);
        $this->assertTrue($collection->contains(function (int $value, int $key) {
            return $value > 5;
        }));
        $collection = new Collection([
                                         ['product' => 'Desk', 'price' => 200],
                                         ['product' => 'Chair', 'price' => 100],
                                     ]);
        $this->assertTrue($collection->contains('product', 'Chair'));
        $this->assertFalse($collection->contains('product', 'Bookcase'));
    }
    /**
     * @group containsOneItem
     * Test the `containsOneItem` method.
     */
    public function testContainsOneItem()
    {
        $collection = new Collection([1]);

        $this->assertTrue($collection->containsOneItem());

        $collection = new Collection([]);
        $this->assertFalse($collection->containsOneItem());

        $collection = new Collection([3, 5, 9, 11]);
        $this->assertFalse($collection->containsOneItem());
    }

    /**
     * @group containsStrict
     * Test the `containsStrict` method.
     */
    public function testContainsStrict()
    {
        $collection = new Collection([1, 2, 3]);

        $this->assertTrue($collection->containsStrict(2));
        $this->assertFalse($collection->containsStrict('2'));
    }
    /**
     * @group count
     * Test the `count` method.
     */
    public function testCount()
    {
        $collection = new Collection(['apple', 'banana', 'cherry']);

        $this->assertEquals(3, $collection->count());
    }
    /**
     * @group reverse
     * Test the `reverse` method.
     */
    public function testReverse()
    {
        $collection = new Collection([1, 2, 3]);
        $reversed = $collection->reverse();

        $this->assertInstanceOf(Collection::class, $reversed);
        $this->assertEquals([3, 2, 1], $reversed->all());
    }
    /**
     * @group search
     * Test the `search` method.
     */
    public function testSearch()
    {
        $collection = new Collection([1, 2, 3, 4, 5]);
        $index = $collection->search(3);

        $this->assertEquals(2, $index);

        $collection = new Collection([1, 2, 3, 4, 5]);
        $index = $collection->search('3', true);

        $this->assertNotEquals(2, $index);
    }
    /**
     * @group shift
     * Test the `shift` method.
     */
    public function testShift()
    {
        $collection = new Collection([1, 2, 3]);
        $shifted = $collection->shift();

        $this->assertEquals(1, $shifted);
        $this->assertEquals([2, 3], $collection->all());
    }
    /**
     * @group shuffle
     * Test the `shuffle` method.
     */
    public function testShuffle()
    {
        $data = new Collection([1, 2, 3, 4, 5, 6]);
        $shuffled = $data->shuffle();
        $this->assertEquals(6, $shuffled->count());
        $this->assertEquals([1, 2, 3, 4, 5, 6], $shuffled->sort()->values()->all());
    }
    /**
     * @group skip
     * Test the `skip` method.
     */
    public function testSkipMethod()
    {
        $collection = new Collection([1, 2, 3, 4, 5]);
        $this->assertEquals([3, 4, 5], $collection->skip(2)->all());
    }
    /**
     * @group skipUntil
     * Test the `skipUntil` method.
     */
    public function testSkipUnti()
    {
        $data = new Collection([1, 2, 3, 4, 5, 6]);
        $this->assertEquals([3, 4, 5, 6], $data->skipUntil(function ($item) {
            return $item == 3;
        })->values()->all());
    }
    /**
     * @group skipWhile
     * Test the `skipWhile` method.
     */
    public function testSkipWhile()
    {
        $data = new Collection([1, 2, 3, 4, 5, 6]);
        $this->assertEquals([3, 4, 5, 6], $data->skipWhile(function ($item) {
            return $item < 3;
        })->values()->all());
    }
    /**
     * @group slice
     * Test the `slice` method.
     */
    public function testSliceMethod()
    {
        $collection = new Collection([1, 2, 3, 4, 5]);
        $this->assertEquals([2, 3], $collection->slice(1, 2)->values()->all());
    }
    /**
     * @group sliding
     * Test the `sliding` method.
     */
    public function testSliding()
    {
        $data = new Collection([1, 2, 3, 4, 5, 6]);
        $result = $data->sliding(2)->values()->toArray();
        $this->assertEquals([[1, 2], [2, 3], [3, 4], [4, 5], [5, 6]], $result);
    }
    /**
     * @group sole
     * Test the `sole` method.
     */
    public function testSole()
    {
        $data = new Collection([1]);
        $this->assertNotNull($data->sole());
        $data = new Collection([1]);
        $this->assertEquals(1, $data->sole());
        $data = new Collection([1, 2, 3, 4]);
        $this->assertEquals(2, $data->sole(function($value){
            return $value === 2;
        }));
        $collection = new Collection([
              ['product' => 'Desk', 'price' => 200],
              ['product' => 'Chair', 'price' => 100],
          ]);
        $result = $collection->sole('product', 'Chair');
       $this->assertEquals(['product' => 'Chair', 'price' => 100], $result);
    }
    /**
     * @group some
     * Test the `some` method.
     */
    public function testSome()
    {
        $data = new Collection([1, 2, 3, 4, 5, 6]);
        $this->assertTrue($data->some(function ($item) {
            return $item > 3;
        }));
    }
    /**
     * @group sort
     * Test the `sort` method.
     */
    public function testSortMethod()
    {
        $collection = new Collection([5, 3, 2, 4, 1]);
        $sorted = $collection->sort();
        $this->assertEquals([1, 2, 3, 4, 5], $sorted->values()->all());
    }
    /**
     * @group sortDesc
     * Test the `sortDesc` method.
     */
    public function testSortDesc()
    {
        $collection = new Collection([5, 3, 1, 2, 4]);
        $sorted = $collection->sortDesc();
        $this->assertEquals([5, 4, 3, 2, 1], $sorted->values()->all());
    }
    /**
     * @group sortBy
     * Test the `sortBy` method.
     */
    public function testSortBy()
    {
        $data = new Collection([['name' => 'Desk'], ['name' => 'Chair'], ['name' => 'Bookcase']]);
        $this->assertEquals([['name' => 'Bookcase'], ['name' => 'Chair'], ['name' => 'Desk']], $data->sortBy(function ($item) {
            return $item['name'];
        })->values()->all());
    }
    /**
     * @group sortByDesc
     * Test the `sortByDesc` method.
     */
    public function testSortByDesc()
    {
        $collection = new Collection([
                                         ['name' => 'Desk', 'price' => 200],
                                         ['name' => 'Chair', 'price' => 100],
                                         ['name' => 'Bookcase', 'price' => 150],
                                     ]);
        $sorted = $collection->sortByDesc('price');
        $this->assertEquals([
                                ['name' => 'Desk', 'price' => 200],
                                ['name' => 'Bookcase', 'price' => 150],
                                ['name' => 'Chair', 'price' => 100],
                            ], $sorted->values()->toArray());
    }
    /**
     * @group sortKeys
     * Test the `sortKeys` method.
     */
    public function testSortKeys()
    {
        $collection = new Collection([
                                         'z' => 'zebra',
                                         'a' => 'apple',
                                         'c' => 'carrot',
                                         'b' => 'banana',
                                     ]);

        $sorted = $collection->sortKeys();
        $this->assertEquals([
                                'a' => 'apple',
                                'b' => 'banana',
                                'c' => 'carrot',
                                'z' => 'zebra',
                            ], $sorted->all());
    }
    /**
     * @group sortKeysDesc
     * Test the `sortKeysDesc` method.
     */
    public function testSortKeysDesc()
    {
        $collection = new Collection([
                                         'z' => 'zebra',
                                         'a' => 'apple',
                                         'c' => 'carrot',
                                         'b' => 'banana',
                                     ]);

        $sorted = $collection->sortKeysDesc();
        $this->assertEquals([
                                'z' => 'zebra',
                                'c' => 'carrot',
                                'b' => 'banana',
                                'a' => 'apple',
                            ], $sorted->all());
    }
    /**
     * @group sortKeysUsing
     * Test the `sortKeysUsing` method.
     */
    public function testSortKeysUsing()
    {
        $collection = new Collection([
                                         'z' => 'zebra',
                                         'a' => 'apple',
                                         'c' => 'carrot',
                                         'b' => 'banana',
                                     ]);

        $sorted = $collection->sortKeysUsing(function ($a, $b) {
            return strcmp($a, $b);
        });
        $this->assertEquals([
                                'a' => 'apple',
                                'b' => 'banana',
                                'c' => 'carrot',
                                'z' => 'zebra',
                            ], $sorted->all());
    }
    /**
     * @group splice
     * Test the `splice` method.
     */
    public function testSplice()
    {
        $collection = new Collection([1, 2, 3, 4, 5]);

        $spliced = $collection->splice(2);
        $this->assertEquals([3, 4, 5], $spliced->all());
        $this->assertEquals([1, 2], $collection->all());

        $collection = new Collection([1, 2, 3, 4, 5]);

        $spliced = $collection->splice(2, 1);
        $this->assertEquals([3], $spliced->all());
        $this->assertEquals([1, 2, 4, 5], $collection->all());

        $collection = new Collection([1, 2, 3, 4, 5]);

        $spliced = $collection->splice(1, 2, [10, 11]);
        $this->assertEquals([2, 3], $spliced->all());
        $this->assertEquals([1, 10, 11, 4, 5], $collection->all());
    }
    /**
     * @group split
     * Test the `split` method.
     */
    public function testSplit()
    {
        $collection = new Collection([1, 2, 3, 4, 5]);
        $collections = $collection->split(3)->map(function($value) {
            return $value->toArray();
        });
        $this->assertEquals([[1, 2], [3, 4], [5]], $collections->toArray());
    }
    /**
     * @group splitIn
     * Test the `splitIn` method.
     */
    public function testSplitIn()
    {
        $collection = new Collection([1, 2, 3, 4, 5]);
        $collections = $collection->splitIn(3);
        $this->assertEquals([[1, 2], [3, 4], [5]], $collections->toArray());
    }
    /**
     * @group take
     * Test the `take` method.
     */
    public function testTake()
    {
        $data = new Collection([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);
        $this->assertEquals([1, 2, 3], $data->take(3)->all());
    }
    /**
     * @group takeUntil
     * Test the `takeUntil` method.
     */
    public function testTakeUntil()
    {
        $data = new Collection([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);
        $result = $data->takeUntil(function ($item) {
            return $item > 4;
        });
        $this->assertEquals([1, 2, 3, 4], $result->toArray());
    }
    /**
     * @group takeWhile
     * Test the `takeWhile` method.
     */
    public function testTakeWhile()
    {
        $collection = new Collection([1, 2, 3, 4, 5]);
        $while = $collection->takeWhile(function ($value) {
            return $value < 4;
        });
        $this->assertEquals([1, 2, 3], $while->all());
    }
    /**
     * @group tap
     * Test the `tap` method.
     */
    public function testTap()
    {
        $collection = new Collection([2, 4, 3, 1, 5]);
        $tapped = $collection->sort()->tap(function ($value){
            return $value->all();
        });
        $this->assertEquals([1, 2, 3, 4, 5], $tapped->values()->toArray());
        $this->assertEquals(1, $tapped->shift());
    }
    /**
     * @group times
     * Test the `times` method.
     */
    public function testTimes()
    {
        $times = Collection::times(5, function($value) {
            return $value * 9;
        });
        $this->assertEquals([9, 18, 27, 36, 45], $times->all());
    }
    /**
     * @group toArray
     * Test the `toArray` method.
     */
    public function testToArray()
    {
        $array = new Collection(['name' => 'Desk']);
        $this->assertEquals(['name' => 'Desk'], $array->toArray());
    }
    /**
     * @group toJson
     * Test the `toJson` method.
     */
    public function testToJson()
    {
        $array = new Collection(['name' => 'Desk']);
        $this->assertEquals('{"name":"Desk"}', $array->toJson());
    }
    /**
     * @group transform
     * Test the `transform` method.
     */
    public function testTransform()
    {
        $collection = new Collection([1, 2, 3, 4, 5]);
        $transformed = $collection->transform(function ($item) {
            return $item * 2;
        });
        $this->assertEquals([2, 4, 6, 8, 10], $transformed->all());
    }
    /**
     * @group undot
     * Test the `undot` method.
     */
    public function testUndot()
    {
        $data = new Collection(['foo' => ['bar' => ['baz' => 'hello']]]);
        $this->assertEquals(['foo' => ['bar' => ['baz' => 'hello']]], $data->undot()->all());
    }
    /**
     * @group union
     * Test the `union` method.
     */
    public function testUnion()
    {
        $data = new Collection([1, 2, 3, 4, 5, 6]);
        $this->assertEquals([1, 2, 3, 4, 5, 6], $data->union([7, 8, 9, 10])->all());
    }
    /**
     * @group unique
     * Test the `unique` method.
     */
    public function testUnique()
    {
        $data = new Collection([1, 2, 3, 4, 5, 6, 1, 2, 3]);
        $this->assertEquals([1, 2, 3, 4, 5, 6], $data->uniqueStrict()->all());
    }
    /**
     * @group uniqueStrict
     * Test the `uniqueStrict` method.
     */
    public function testUniqueStrict()
    {
        $collection = new Collection([1, '1', 2, '2', 3, '3']);
        $this->assertEquals([1, '1', 2, '2', 3, '3'], $collection->uniqueStrict()->all());
    }
    /**
     * @group unless
     * Test the `unless` method.
     */
    public function testUnless()
    {
        $data = new Collection([1, 2, 3, 4, 5, 6]);
        $this->assertEquals([1, 2, 3, 4, 5, 6, 7], $data->unless(false, function ($collection) {
            return $collection->push(7);
        })->all());
        $this->assertEquals([1, 2, 3, 4, 5, 6, 7], $data->unless(true, function ($collection) {
            return $collection->push(7);
        })->all());
    }
    /**
     * @group whenEmpty
     * Test the `whenEmpty` method.
     */
    public function testWhenEmpty()
    {
        $data = new Collection();
        $this->assertEquals([7], $data->whenEmpty(function ($collection) {
            return $collection->push(7);
        })->all());
        $data->push(1);
        $this->assertEquals([7, 1], $data->whenEmpty(function ($collection) {
            return $collection->push(7);
        })->all());
    }
    /**
     * @group whenNotEmpty
     * Test the `whenNotEmpty` method.
     */
    public function testWhenNotEmpty()
    {
        $collection = new Collection(['michael', 'tom']);

        $collection->whenNotEmpty(function (Collection $collection) {
            return $collection->push('adam');
        });
        $this->assertEquals(['michael', 'tom', 'adam'], $collection->all());
        $collection = new Collection();

        $collection->whenNotEmpty(function (Collection $collection) {
            return $collection->push('adam');
        });
        $this->assertEquals([], $collection->all());
    }

    /**
     * @group pop
     * Test the `pop` method.
     */
    public function testPop()
    {
        $collection = new Collection([1, 2, 3]);
        $popped = $collection->pop();

        $this->assertEquals(3, $popped);
        $this->assertEquals([1, 2], $collection->all());
    }
    /**
     * @group append
     * Test the `append` method.
     */
    public function testAppendMethod()
    {
        $collection = new Collection([1, 2, 3]);
        $this->assertEquals([1, 2, 3, 4], $collection->append(4)->all());
    }

    /**
     * @group prepend
     * Test the `prepend` method.
     */
    public function testPrepend()
    {
        $collection = new Collection([1, 2, 3]);
        $prepended = $collection->prepend(0);

        $this->assertInstanceOf(Collection::class, $prepended);
        $this->assertEquals([0, 1, 2, 3], $prepended->all());
    }
    /**
     * @group pull
     * Test the `pull` method.
     */
    public function testPullMethod()
    {
        $collection = new Collection(['name' => 'John', 'age' => 30]);
        $this->assertEquals('John', $collection->pull('name'));
    }

    /**
     * @group push
     * Test the `push` method.
     */
    public function testPush()
    {
        $collection = new Collection([1, 2, 3]);
        $pushed = $collection->push(4);

        $this->assertInstanceOf(Collection::class, $pushed);
        $this->assertEquals([1, 2, 3, 4], $pushed->all());
    }
    /**
     * @group put
     * Test the `put` method.
     */
    public function testPut()
    {
        $collection = new Collection(['name' => 'John', 'age' => 30]);
        $put = $collection->put('gender', 'male');

        $this->assertInstanceOf(Collection::class, $put);
        $this->assertEquals(['name' => 'John', 'age' => 30, 'gender' => 'male'], $put->all());
    }
    /**
     * @group random
     * Test the `random` method.
     */
    public function testRandom()
    {
        $collection = new Collection([1, 2, 3, 4, 5]);
        $random = $collection->random();
        $this->assertContains($random, [1, 2, 3, 4, 5]);
    }
    /**
     * @group range
     * Test the `range` method.
     */
    public function testRange()
    {
        $collection = Collection::range(0, 10);
        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertEquals([0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10], $collection->all());
    }
    /**
     * @group reduce
     * Test the `reduce` method.
     */
    public function testReduceMethod()
    {
        $collection = new Collection([1, 2, 3, 4, 5]);
        $sum = $collection->reduce(function ($carry, $item) {
            return $carry + $item;
        });
        $this->assertEquals(15, $sum);
    }
    /**
     * @group reduceSpread
     * Test the `reduceSpread` method.
     */
    public function testReduceSpread()
    {
        $data = new Collection([[1, 2], [3, 4], [5, 6]]);
        $result = $data->reduceSpread(function ($carry, $item, $key) {
            return [$carry + $item[0] + $item[1]];
        }, 0);
        $this->assertEquals([21], $result);
    }
    /**
     * @group reject
     * Test the `reject` method.
     */
    public function testReject()
    {
        $collection = new Collection([1, 2, 3, 4, 5]);
        $rejected = $collection->reject(fn($value) => $value % 2 === 0);

        $this->assertInstanceOf(Collection::class, $rejected);
        $this->assertEquals([1, 3, 5], $rejected->values()->toArray());
    }
    /**
     * @group replace
     * Test the `replace` method.
     */
    public function testReplace()
    {
        $data = new Collection([
                                   ['product_id' => 'prod-100', 'name' => 'Desk'],
                                   ['product_id' => 'prod-200', 'name' => 'Chair'],
                                   ['product_id' => 'prod-300', 'name' => 'Bookcase'],
                               ]);

        $replacementData = new Collection([
                                              ['product_id' => 'prod-100', 'name' => 'New Desk'],
                                              ['product_id' => 'prod-200', 'name' => 'New Chair'],
                                          ]);

        $expected = new Collection([
                                       ['product_id' => 'prod-100', 'name' => 'New Desk'],
                                       ['product_id' => 'prod-200', 'name' => 'New Chair'],
                                       ['product_id' => 'prod-300', 'name' => 'Bookcase'],
                                   ]);

        $result = $data->replace($replacementData);
        $this->assertEquals($expected, $result);
        $this->assertNotSame($data, $result);

        $collection = new Collection(['Imran', 'John', 'James']);
        $result = $collection->replace([1 => 'Victoria', 3 => 'Finn']);
        $expected = new Collection(['Imran', 'Victoria', 'James', 'Finn']);
        $this->assertEquals($expected, $result);
    }
    /**
     * @group replaceRecursive
     * Test the `replaceRecursive` method.
     */
    public function test_replace_recursive()
    {
        $data = [
            'name' => 'John',
            'age' => 25,
            'address' => [
                'street' => '123 Main St',
                'city' => 'Anytown',
                'state' => 'CA',
                'zip' => '12345',
                'country' => 'USA',
            ],
            'phone' => [
                'home' => '555-1234',
                'mobile' => '555-5678',
            ],
        ];

        $expected = [
            'name' => 'John',
            'age' => 25,
            'address' => [
                'street' => '123 Main St',
                'city' => 'Anytown',
                'state' => 'CA',
                'zip' => '12345',
                'country' => 'Canada', // Replace country value
            ],
            'phone' => [
                'home' => '555-9999', // Replace home value
                'mobile' => '555-5678',
            ],
        ];
        $collection = new Collection($data);
        $result = $collection->replaceRecursive([
            'address' => [
                'country' => 'Canada',
            ],
            'phone' => [
                'home' => '555-9999',
            ],
        ]);

        $this->assertEquals($expected, $result->toArray());
    }

    /**
     * @group countBy
     * Test the `countBy` method.
     */
    public function testCountBy()
    {
        $collection = new Collection([1, 2, 2, 3, 3, 3]);

        $counted = $collection->countBy();

        $this->assertEquals([1 => 1, 2 => 2, 3 => 3], $counted->toArray());

        $counted = $collection->countBy(function ($item) {
            return $item % 2 == 0 ? 'even' : 'odd';
        });

        $this->assertEquals(['odd' => 4, 'even' => 2], $counted->toArray());

        $collection = new Collection([
                                  ['name' => 'Alice', 'age' => 20],
                                  ['name' => 'Bob', 'age' => 30],
                                  ['name' => 'Charlie', 'age' => 30],
                                  ['name' => 'Dave', 'age' => 20],
                              ]);

        $counted = $collection->countBy('age');

        $this->assertEquals([20 => 2, 30 => 2], $counted->toArray());

        $counted = $collection->countBy(function ($item) {
            return $item['age'] >= 30 ? 'over 30' : 'under 30';
        });

        $this->assertEquals(['under 30' => 2, 'over 30' => 2], $counted->toArray());
    }

    /**
     * @group diff
     * Test the `diff` method
     */
    public function testDiff()
    {
        $data = new Collection([1, 2, 3, 4, 5]);
        $this->assertEquals([3, 4, 5], $data->diff([1, 2])->values()->toArray());
    }

    /**
     * @group diffUsing
     * Test the `diffUsing` method
     */
    public function testDiffUsing()
    {
        $data = new Collection([1, 2, 3, 4, 5]);
        $this->assertEquals([3, 4, 5], $data->diffUsing([1, 2], function ($a, $b) {
            return $a - $b;
        })->values()->toArray());

        $collection1 = new Collection([1, 2, 3, 4, 5]);
        $collection2 = new Collection([2, 4, 6, 8, 10]);
        $diff = $collection1->diffUsing($collection2, function ($value1, $value2) {
            return $value1 <=> $value2;
        })->values();
        $this->assertEquals([1, 3, 5], $diff->all());

        $collection3 = new Collection([
                                   ['name' => 'John', 'age' => 25],
                                   ['name' => 'Jane', 'age' => 30],
                                   ['name' => 'Bob', 'age' => 35],
                               ]);
        $collection4 = new Collection([
                                   ['name' => 'John', 'age' => 30],
                                   ['name' => 'Jane', 'age' => 30],
                                   ['name' => 'Alice', 'age' => 35],
                               ]);
        $diff2 = $collection3->diffUsing($collection4, function ($value1, $value2) {
            return $value1['name'] <=> $value2['name'];
        });
        $this->assertEquals([
                                ['name' => 'Bob', 'age' => 35]
                            ], $diff2->values()->all());
    }
    /**
     * @group diffAssoc
     * Test the `diffAssoc` method
     */
    public function testDiffAssoc()
    {
        $collection1 = new Collection(['id' => 1, 'name' => 'John', 'age' => 30]);
        $collection2 = new Collection(['id' => 2, 'name' => 'Jane', 'age' => 25]);

        $result = $collection1->diffAssoc($collection2);

        $this->assertEquals(['id' => 1, 'name' => 'John', 'age' => 30], $result->all());

        $collection3 = new Collection(['id' => 1, 'name' => 'John', 'age' => 25]);

        $result = $collection1->diffAssoc($collection3);

        $this->assertEquals(['age' => 30], $result->all());

        $result = $collection3->diffAssoc($collection1);

        $this->assertEquals(['age' => 25], $result->all());
    }
    /**
     * @group diffKeys
     * Test the `diffKeys` method
     */
    public function testDiffKeys()
    {
        $collection = new Collection([
                                         'name' => 'John',
                                         'age' => 25,
                                         'email' => 'john@example.com',
                                         'phone' => '555-1234'
                                     ]);

        $otherCollection = new Collection([
                                              'name' => 'Jane',
                                              'age' => 30,
                                              'email' => 'jane@example.com'
                                          ]);

        $result = $collection->diffKeys($otherCollection);

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertEquals(['phone' => '555-1234'], $result->all());
    }
    /**
     * @group diffAssocUsing
     * Test the `diffAssocUsing` method
     */
    public function testDiffAssocUsing()
    {
        $collection1 = new Collection(['id' => 1, 'name' => 'John']);

        $collection2 = new Collection(['id' => 2, 'name' => 'Jane']);

        $diff = $collection1->diffAssocUsing($collection2, function ($item1, $item2) {
            return $item1 <=> $item2;
        });

        $this->assertCount(2, $diff->toArray());
        $this->assertEquals(['id' => 1, 'name' => 'John'], $diff->toArray());
    }
    /**
     * @group diffKeysUsing
     * Test the `diffKeysUsing` method
     */
    public function testDiffKeysUsing()
    {
        $data = new Collection(['a' => 1, 'b' => 2, 'c' => 3, 'd' => 4, 'e' => 5]);
        $this->assertEquals(['c' => 3, 'd' => 4, 'e' => 5], $data->diffKeysUsing(['a' => 1, 'b' => 2], function ($a, $b) {
            return $a <=> $b;
        })->all());
    }
    /**
     * @group each
     * Test the `each` method
     */
    public function testEach()
    {
        $collection = new Collection([1, 2, 3, 4, 5]);
        $result = 0;
        $collection->each(function($item, $key) use (&$result) {
            $result += $item;
        });
        $this->assertEquals(15, $result);
    }
    /**
     * @group eachSpread
     * Test the `eachSpread` method
     */
    public function testEachSpreadMethod()
    {
        $collection = new Collection([[1, 2], [3, 4], [5, 6]]);
        $result = 0;
        $collection->eachSpread(function ($a, $b) use (&$result) {
            $result += $a + $b;
        });
        $this->assertEquals(21, $result);
    }
    /**
     * @group every
     * Test the `every` method
     */
    public function testEveryMethod()
    {
        $collection = new Collection([1, 2, 3, 4, 5]);
        $this->assertTrue($collection->every(function ($item) {
            return $item < 6;
        }));
        $this->assertFalse($collection->every(function ($item) {
            return $item < 5;
        }));
        $collection = new Collection();
        $this->assertTrue($collection->every(function ($item) {
            return $item > 5;
        }));
    }
    /**
     * @group except
     * Test the `except` method
     */
    public function testExceptMethod()
    {
        $collection = new Collection(['name' => 'John', 'age' => 30, 'gender' => 'male']);
        $this->assertEquals(['name' => 'John', 'gender' => 'male'], $collection->except(['age'])->all());
    }

    /**
     * @group filter
     * Test the `filter` method
     */
    public function testFilterMethod()
    {
        $collection = new Collection([1, 2, 3, 4]);
        $filtered = $collection->filter(function (int $value, int $key) {
            return $value > 2;
        });
        $this->assertEquals([3,4],$filtered->values()->toArray());

        $collection = new Collection([1, 2, 3, null, false, '', 0, []]);
        $filtered = $collection->filter();
        $this->assertEquals([1,2,3],$filtered->values()->toArray());
    }
    /**
     * @group first
     * Test the `first` method
     */
    public function testFirstMethod()
    {
        $collection = new Collection([1, 2, 3, 4, 5]);
        $this->assertEquals(1, $collection->first());

        $filtered = $collection->first(function($value) {
           return $value > 2;
        });
        $this->assertEquals(3, $filtered);
    }
    /**
     * @group firstOrFail
     * Test the `firstOrFail` method
     */
    public function testFirstOrFailMethod()
    {
        $collection = new Collection([1,2,3]);
        $this->assertEquals(1, $collection->firstOrFail());
        $this->expectException(\Exception::class);
        $collection->filter(function ($item) {
            return $item > 3;
        })->firstOrFail();
    }
    /**
     * @group where
     * Test the `where` method
     */
    public function testWhereMethod()
    {
        $collection = new Collection([
                                         ['name' => 'John', 'age' => 30],
                                         ['name' => 'Jane', 'age' => 25],
                                         ['name' => 'Bob', 'age' => 35],
                                     ]);
        $filtered = $collection->where('age', 30);
        $this->assertEquals([['name' => 'John', 'age' => 30]], $filtered->all());
    }
    /**
     * @group firstWhere
     * Test the `firstWhere` method
     */
    public function testFirstWhereMethod()
    {
        $collection = new Collection([
                                         ['name' => 'John', 'age' => 30],
                                         ['name' => 'Jane', 'age' => 25],
                                         ['name' => 'Bob', 'age' => 35],
                                     ]);
        $this->assertEquals(['name' => 'Jane', 'age' => 25], $collection->firstWhere('age', 25));
    }
    /**
     * @group whereStrict
     * Test the `whereStrict` method
     */
    public function testWhereStrict()
    {
        $collection = new Collection([
                                         ['name' => 'john', 'age' => 15],
                                         ['name' => 'jane', 'age' => 20],
                                         ['name' => 'doe', 'age' => 25],
                                     ]);

        $this->assertEquals([
                                ['name' => 'jane', 'age' => 20]
                            ], $collection->whereStrict('age', 20)->values()->all());
    }
    /**
     * @group whereBetween
     * Test the `whereBetween` method
     */
    public function testWhereBetween()
    {
        $collection = new Collection([
                                  ['product' => 'Desk', 'price' => 200],
                                  ['product' => 'Chair', 'price' => 80],
                                  ['product' => 'Bookcase', 'price' => 150],
                                  ['product' => 'Pencil', 'price' => 30],
                                  ['product' => 'Door', 'price' => 100],
                              ]);
        $filtered = $collection->whereBetween('price', [100, 200]);
        $this->assertEquals([
                                ['product' => 'Desk', 'price' => 200],
                                ['product' => 'Bookcase', 'price' => 150],
                                ['product' => 'Door', 'price' => 100],
                            ],$filtered->values()->all());
    }
    /**
     * @group whereNotBetween
     * Test the `whereNotBetween` method
     */
    public function testWhereNotBetween()
    {
        $collection = new Collection([
                                         ['product' => 'Desk', 'price' => 200],
                                         ['product' => 'Chair', 'price' => 80],
                                         ['product' => 'Bookcase', 'price' => 150],
                                         ['product' => 'Pencil', 'price' => 30],
                                         ['product' => 'Door', 'price' => 100],
                                     ]);
        $filtered = $collection->whereNotBetween('price', [100, 200]);
        $this->assertEquals([
                                ['product' => 'Chair', 'price' => 80],
                                ['product' => 'Pencil', 'price' => 30],
                            ],$filtered->values()->all());
    }
    /**
     * @group whereIn
     * Test the `whereIn` method
     */
    public function testWhereIn()
    {
        $collection = new Collection([
                                         ['product' => 'Desk', 'price' => 200],
                                         ['product' => 'Chair', 'price' => 100],
                                         ['product' => 'Bookcase', 'price' => 150],
                                         ['product' => 'Door', 'price' => 100],
                                     ]);
        $filtered = $collection->whereIn('price', [150, 200]);
        $this->assertEquals([
                                ['product' => 'Desk', 'price' => 200],
                                ['product' => 'Bookcase', 'price' => 150],
                            ],$filtered->values()->all());
    }
    /**
     * @group whereNotNull
     * Test the `whereNotNull` method
     */
    public function testWhereNotNull()
    {
        $collection = new Collection([
                                  ['name' => 'Desk'],
                                  ['name' => null],
                                  ['name' => 'Bookcase'],
                              ]);
        $this->assertEquals([
                                ['name' => 'Desk'],
                                ['name' => 'Bookcase'],
                            ], $collection->whereNotNull('name')->values()->all());
    }
    /**
     * @group whereNull
     * Test the `whereNull` method
     */
    public function testWhereNull()
    {
        $collection = new Collection([
                                         ['name' => 'Desk'],
                                         ['name' => null],
                                         ['name' => 'Bookcase'],
                                     ]);
        $this->assertEquals([
                                ['name' => null],
                            ], $collection->whereNull('name')->values()->all());
    }
    /**
     * @group wrap
     * Test the `wrap` method
     */
    public function testWrap()
    {
        $collection = Collection::wrap('John Doe');
        $this->assertEquals(['John Doe'], $collection->all());
    }
    /**
     * @group zip
     * Test the `zip` method
     */
    public function testZip()
    {
        $collection = new Collection([1, 2, 3]);
        $zipped = $collection->zip(['a', 'b', 'c'])->map(function($items) {
            return $items->toArray();
        });

        $this->assertEquals([[1, 'a'], [2, 'b'], [3, 'c']], $zipped->toArray());
    }
    /**
     * @group flatMap
     * Test the `flatMap` method
     */
    public function testFlatMapMethod()
    {
        $collection = new Collection([1, 2, 3]);
        $this->assertEquals([0, 1, 0, 2, 0, 3], $collection->flatMap(function ($item) {
            return [0, $item];
        })->all());

        $collection = new Collection([
                                  ['name' => 'Sally'],
                                  ['school' => 'Arkansas'],
                                  ['age' => 28]
                              ]);

        $flattened = $collection->flatMap(function (array $values) {
            return array_map('strtoupper', $values);
        });

        $this->assertEquals(['name' => 'SALLY', 'school' => 'ARKANSAS', 'age' => 28], $flattened->all());
    }
    /**
     * @group flatten
     * Test the `flatten` method
     */
    public function testFlattenMethod()
    {
        $collection = new Collection([1, [2, [3, [4, 5]]]]);
        $flattened = $collection->flatten();
        $this->assertEquals([1, 2, 3, 4, 5], $flattened->all());

        $collection = new Collection([
                                  'name' => 'imran',
                                  'languages' => [
                                      'php', 'javascript'
                                  ]
                              ]);

        $flattened = $collection->flatten();
        $this->assertEquals(['imran', 'php', 'javascript'], $flattened->all());

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
        $this->assertEquals([
                                ['name' => 'iPhone 6S', 'brand' => 'Apple'],
                                ['name' => 'Galaxy S7', 'brand' => 'Samsung'],
                            ], $products->values()->all());
    }
    /**
     * @group flip
     * Test the `flip` method
     */
    public function testFlipMethod()
    {
        $collection = new Collection(['name' => 'John', 'age' => 30]);
        $this->assertEquals(['John' => 'name', 30 => 'age'], $collection->flip()->all());
    }
    /**
     * @group forget
     * Test the `forget` method
     */
    public function testForgetMethod()
    {
        $collection = new Collection(['name' => 'John', 'age' => 30]);
        $collection->forget('name');
        $this->assertEquals(['age' => 30], $collection->all());
    }
    /**
     * @group forPage
     * Test the `forPage` method
     */
    public function testForPageMethod()
    {
        $collection = new Collection(range(1, 10));
        $this->assertEquals([4, 5, 6], $collection->forPage(2, 3)->values()->all());
    }
    /**
     * @group get
     * Test the `get` method
     */
    public function testGetMethod()
    {
        $collection = new Collection(['name' => 'John', 'age' => 30]);
        $this->assertEquals('John', $collection->get('name'));
    }
    /**
     * @group groupBy
     * Test the `groupBy` method
     */
    public function testGroupByMethod()
    {
        $collection = new Collection([
                                         ['name' => 'John', 'gender' => 'male', 'age' => 30],
                                         ['name' => 'Jane', 'gender' => 'female', 'age' => 25],
                                         ['name' => 'Bob', 'gender' => 'male', 'age' => 35],
                                     ]);
        $grouped = $collection->groupBy('gender');
        $this->assertEquals(['male', 'female'], $grouped->keys()->all());
        $this->assertEquals(2, $grouped->get('male')->count());
    }
    /**
     * @group has
     * Test the `has` method
     */
    public function testHasMethod()
    {
        $collection = new Collection(['name' => 'John', 'age' => 30]);
        $this->assertTrue($collection->has('name'));
        $this->assertFalse($collection->has('gender'));
    }
    /**
     * @group hasAny
     * Test the `hasAny` method
     */
    public function testHasAnyMethod()
    {
        $collection = new Collection(['name' => 'John', 'age' => 30]);
        $this->assertTrue($collection->hasAny(['name', 'gender']));
        $this->assertFalse($collection->hasAny(['gender', 'address']));
    }
    /**
     * @group implode
     * Test the `implode` method
     */
    public function testImplodeMethod()
    {
        $collection = new Collection(['John', 'Jane', 'Bob']);
        $this->assertEquals('John,Jane,Bob', $collection->implode(','));
        $collection = new Collection([
             ['account_id' => 1, 'product' => 'Desk'],
             ['account_id' => 2, 'product' => 'Chair'],
         ]);
        $result = $collection->implode(function ($item, $key) {
            return strtoupper($item['product']);
        }, ', ');
       $this->assertEquals('DESK, CHAIR', $result);
    }
    /**
     * @group intersect
     * Test the `intersect` method
     */
    public function testIntersectMethod()
    {
        $collection = new Collection([1,2, 3, 4, 5]);
        $this->assertEquals([2, 3, 4], $collection->intersect([2, 3, 4, 6, 7])->values()->all());
    }
    /**
     * @group intersectByKeys
     * Test the `intersectByKeys` method
     */
    public function testIntersectByKeysMethod()
    {
        $collection = new Collection([ 'serial' => 'UX301', 'type' => 'screen', 'year' => 2009]);
        $this->assertEquals(['type' => 'screen', 'year' => 2009], $collection->intersectByKeys(['reference' => 'UX404', 'type' => 'tab', 'year' => 2011])->all());
    }
    /**
     * @group isEmpty
     * Test the `isEmpty` method
     */
    public function testIsEmptyMethod()
    {
        $collection = new Collection([]);
        $this->assertTrue($collection->isEmpty());
        $collection = new Collection([1, 2, 3]);
        $this->assertFalse($collection->isEmpty());
    }
    /**
     * @group isNotEmpty
     * Test the `isNotEmpty` method
     */
    public function testIsNotEmptyMethod()
    {
        $collection = new Collection([]);
        $this->assertFalse($collection->isNotEmpty());
        $collection = new Collection([1, 2, 3]);
        $this->assertTrue($collection->isNotEmpty());
    }
    /**
     * @group keyBy
     * Test the `keyBy` method
     */
    public function testKeyByMethod()
    {
        $collection = new Collection([
                                         ['name' => 'John', 'age' => 30],
                                         ['name' => 'Jane', 'age' => 25],
                                         ['name' => 'Bob', 'age' => 35],
                                     ]);
        $this->assertEquals([30 => ['name' => 'John', 'age' => 30], 25 => ['name' => 'Jane', 'age' => 25], 35 => ['name' => 'Bob', 'age' => 35]], $collection->keyBy('age')->all());
    }
    /**
     * @group join
     * Test the `join` method
     */
    public function testJoinMethod()
    {
        $collection = new Collection(['John', 'Jane', 'Bob']);
        $this->assertEquals('John,Jane and Bob', $collection->join(',', ' and '));
    }
    /**
     * @group keys
     * Test the `keys` method
     */
    public function testKeysMethod()
    {
        $collection = new Collection(['name' => 'John', 'age' => 30]);
        $this->assertEquals(['name', 'age'], $collection->keys()->all());
    }
    /**
     * @group last
     * Test the `last` method
     */
    public function testLastMethod()
    {
        $collection = new Collection([1, 2, 3, 4, 5]);
        $this->assertEquals(5, $collection->last());
        $collection = new Collection([1, 2, 3, 4]);
        $output = $collection->last(function (int $value, int $key) {
            return $value < 3;
        });
        $this->assertEquals(2, $output);
    }
    /**
     * @group macro
     * Test the `macro` method
     */
    public function testMacroMethod()
    {
        Collection::macro('newMethod', function () {
            return 'Hello World!';
        });

        $data = new Collection;
        $this->assertEquals('Hello World!', $data->newMethod());
    }
    /**
     * @group make
     * Test the `make` method
     */
    public function testMakeMethod()
    {
        $data = Collection::make([1, 2, 3, 4, 5, 6]);
        $this->assertInstanceOf(Collection::class, $data);
        $this->assertEquals([1, 2, 3, 4, 5, 6], $data->all());
    }
    /**
     * @group map
     * Test the `map` method
     */
    public function testMapMethod()
    {
        $collection = new Collection([1, 2, 3, 4, 5]);
        $mapped = $collection->map(function ($value) {
            return $value * 2;
        });
        $this->assertEquals([2, 4, 6, 8, 10], $mapped->all());
    }
    /**
     * @group mapInto
     * Test the `mapInto` method
     */
    public function testMapIntoMethod()
    {
        $data = new Collection([1, 2, 3, 4, 5]);
        $this->assertInstanceOf(\stdClass::class, $data->mapInto(\stdClass::class)->first());
    }
    /**
     * @group mapSpread
     * Test the `mapSpread` method
     */
    public function testMapSpreadMethod()
    {
        $data = new Collection([[1, 2], [3, 4], [5, 6]]);
        $result = $data->mapSpread(function ($a, $b) {
            return $a * $b;
        })->all();
        $this->assertEquals([2, 12, 30], $result);
    }

    /**
     * @group mapToGroups
     * Test the `mapToGroups` method
     */
    public function testMapToGroupsMethod()
    {
        $data = new Collection([1, 2, 3, 4, 5, 6]);
        $result = $data->mapToGroups(function ($item, $key) {
            return [$item % 2 == 0 ? 'even' : 'odd' => $item];
        })->toArray();
        $this->assertEquals([
                                'odd' => [1, 3, 5],
                                'even' => [2, 4, 6]
                            ], $result);
    }

    /**
     * @group mapWithKeys
     * Test the `mapWithKeys` method
     */
    public function testMapWithKeysMethod()
    {
        $data = new Collection([1, 2, 3, 4, 5, 6]);
        $this->assertEquals([
                                1 => 1,
                                2 => 2,
                                3 => 3,
                                4 => 4,
                                5 => 5,
                                6 => 6
                            ], $data->mapWithKeys(function ($item) {
            return [$item => $item];
        })->all());
    }

    /**
     * @group median
     * Test the `median` method
     */
    public function testMedianMethod()
    {
        $data = new Collection([1, 2, 3, 4, 5, 6]);
        $this->assertEquals(3.5, $data->median());
    }
    /**
     * @group max
     * Test the `max` method
     */
    public function testMaxMethod()
    {
        $collection = new Collection([1, 2, 3, 4, 5]);
        $this->assertEquals(5, $collection->max());
        $max = new Collection([
                           ['foo' => 10],
                           ['foo' => 20]
                       ]);
        $result = $max->max('foo');
        $this->assertEquals(20, $result);
    }
    /**
     * @group merge
     * Test the `merge` method
     */
    public function testMergeMethod()
    {
        $collection = new Collection([1, 2, 3]);
        $this->assertEquals([1, 2, 3, 4, 5], $collection->merge([4, 5])->all());
    }
    /**
     * @group mergeRecursive
     * Test the `mergeRecursive` method
     */
    public function testMergeRecursive()
    {
        $data1 = [
            'a' => [
                'b' => 1,
                'c' => [
                    'd' => 2
                ]
            ]
        ];

        $data2 = [
            'a' => [
                'b' => 3,
                'c' => [
                    'e' => 4
                ]
            ]
        ];

        $expected = [
            'a' => [
                'b' => [1,3],
                'c' => [
                    'd' => 2,
                    'e' => 4
                ]
            ]
        ];

        $collection1 = new Collection($data1);
        $collection2 = new Collection($data2);

        $result = $collection1->mergeRecursive($collection2)->toArray();

        $this->assertEquals($expected, $result);
    }
    /**
     * @group min
     * Test the `min` method
     */
    public function testMinMethod()
    {
        $collection = new Collection([1, 2, 3, 4, 5]);
        $this->assertEquals(1, $collection->min());
    }
    /**
     * @group mode
     * Test the `mode` method
     */
    public function testMode()
    {
        $data = new Collection([1, 2, 3, 3, 4, 5, 5, 5]);

        $this->assertEquals([5], $data->mode());

        $data = new Collection(['apple', 'banana', 'banana', 'cherry', 'apple', 'cherry', 'cherry']);

        $this->assertEquals(['cherry'], $data->mode());

        $data = new Collection([]);

        $this->assertEquals([], $data->mode());
    }
    /**
     * @group nth
     * Test the `nth` method
     */
    public function testNth()
    {
        $data = new Collection([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);
        $this->assertEquals([1, 5, 9], $data->nth(4)->toArray());
    }
    /**
     * @group only
     * Test the `only` method
     */
    public function testOnly()
    {
        $data = new Collection(['a' => 1, 'b' => 2, 'c' => 3, 'd' => 4, 'e' => 5]);
        $this->assertEquals(['a' => 1, 'c' => 3], $data->only(['a', 'c'])->all());
    }
    /**
     * @group pad
     * Test the `only` method
     */
    public function testPad()
    {
        $data = new Collection([1, 2, 3]);
        $this->assertEquals([1, 2, 3, 0, 0], $data->pad(5, 0)->all());
    }

    /**
     * @group partition
     * Test the `partition` method
     */
    public function testPartition()
    {
        $data = new Collection([1, 2, 3, 4, 5, 6]);
        $result = $data->partition(function ($item) {
            return $item % 2 == 1;
        })->map(function($item){
            return $item->values();
        });
        $this->assertEquals([[1, 3, 5], [2, 4, 6]], $result->toArray());
    }

    /**
     * @group pipe
     * Test the `pipe` method
     */
    public function testPipe()
    {
        $data = new Collection([1, 2, 3, 4, 5, 6]);
        $this->assertEquals([2, 4, 6, 8, 10, 12], $data->pipe(function ($collection) {
            return $collection->map(function ($item) {
                return $item * 2;
            });
        })->all());
    }
    /**
     * @group pluck
     * Test the `pluck` method
     */
    public function testPluckMethod()
    {
        $collection = new Collection([
                                         ['name' => 'John', 'age' => 30],
                                         ['name' => 'Jane', 'age' => 25],
                                         ['name' => 'Bob', 'age' => 35],
                                     ]);
        $result = $collection->pluck('age', 'name');
        $this->assertEquals(['John' => 30, 'Jane' => 25, 'Bob' => 35], $result->toArray());
    }

    /**
     * @group duplicates
     * Test the `pluck` method
     */
    public function testDuplicates()
    {
        $collection = new Collection(['a', 'b', 'a', 'c', 'b']);
        $result = $collection->duplicates();
        $this->assertEquals([2 => 'a', 4 => 'b'], $result->toArray());
    }

    /**
     * @group pipeInto
     * Test the `pipeInto` method
     */
//    public function testPipeInto()
//    {
//        $data = new Collection([1, 2, 3, 4, 5, 6]);
//        $this->assertEquals([1, 2, 3, 4, 5, 6], $data->pipeInto(\stdClass::class)->all());
//    }

    /**
     * @group pipeThrough
     * Test the `pipeThrough` method
     */
//    public function testPipeThrough()
//    {
//        $data = new Collection([1, 2, 3, 4, 5, 6]);
//        $this->assertEquals([2, 4, 6, 8, 10, 12], $data->pipeThrough([MultiplyByTwo::class, 'multiply'])->all());
//    }

}

