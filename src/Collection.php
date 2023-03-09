<?php
namespace Imran\Collection;

use ArrayAccess;
use Closure;
use Imran\Collection\Support\Arr;
use Imran\Collection\Support\Arrayable;
use Imran\Collection\Support\Enumerable;
use Imran\Collection\Support\HigherOrderCollectionProxy;
use Imran\Collection\Support\HigherOrderWhenProxy;
use Imran\Collection\Support\Jsonable;
use Imran\Collection\Support\Macroable;
use Imran\Collection\Support\Proxies;
use InvalidArgumentException;

class Collection implements ArrayAccess, Enumerable
{
    use Macroable;
    use Proxies;
    protected array $items;


    /**
     * Create a new collection.
     *
     * @param  $items
     * @return void
     */
    public function __construct($items = [])
    {
        $this->items = $this->getArrayableItems($items);
    }
    /**
     * Get all of the items in the collection.
     *
     * @return array
     */
    public function all(): array
    {
        return $this->items;
    }

    /**
     * Alias for the "avg" method.
     *
     * @param  callable|string|null  $callback
     * @return mixed
     */
    public function average($callback = null)
    {
        return $this->avg($callback);
    }

    /**
     * Get the average value of a given key.
     *
     * @param  callable|string|null  $callback
     * @return mixed
     */
    public function avg($callback = null)
    {
        $callback = $this->valueRetriever($callback);

        $items = $this->map(function ($value) use ($callback) {
            return $callback($value);
        })->filter(function ($value) {
            return ! is_null($value);
        });

        if ($count = $items->count()) {
            return $items->sum() / $count;
        }
    }

    /**
     * Chunk the collection into chunks of the given size.
     *
     * @param  int  $size
     * @return static
     */
    public function chunk($size)
    {
        if ($size <= 0) {
            return new static;
        }

        $chunks = [];

        foreach (array_chunk($this->items, $size, true) as $chunk) {
            $chunks[] = array_values($chunk);
        }

        return new static($chunks);
    }

    /**
     * Chunk the collection into chunks using a callback.
     *
     * @param  callable  $callback
     * @return static
     */
    public function chunkWhile(callable $callback): Collection
    {
        $result = new static;

        if ($this->isEmpty()) {
            return $result;
        }

        $last = null;
        $chunk = new static;

        foreach ($this->items as $key => $item) {
            if ($last === null || $callback($item, $key, $chunk)) {
                $chunk->put($key, $item);
            } else {
                $result->push($chunk);
                $chunk = new static([$key => $item]);
            }

            $last = $item;
        }

        if (! $chunk->isEmpty()) {
            $result->push($chunk);
        }

        return $result;
    }




    /**
     * Collapse the collection of items into a single array.
     *
     * @return static
     */
    public function collapse(): static
    {
        $results = [];

        foreach ($this->items as $values) {
            if ($values instanceof Collection) {
                $values = $values->all();
            }

            $results = array_merge($results, $values);
        }

        return new static($results);
    }

    /**
     * Create a new collection instance if the value isn't one already.
     *
     * @param  mixed|null  $items
     * @return static
     */
    public function collect(mixed $items = null): static
    {
        if ($items instanceof self) {
            return $items;
        }

        if (is_array($items) || $items instanceof \Traversable) {
            return new static($items);
        }
        if(is_null($items)){
            return new static($this->items);
        }

        return new static([$items]);
    }

    /**
     * Create a collection by using this collection for keys and another for its values.
     *
     * @param  mixed  $values
     * @return static
     */
    public function combine($values)
    {
        return new static(array_combine($this->all(), $this->getArrayableItems($values)));
    }

    /**
     * Push all of the given items onto the collection.
     *
     * @param  iterable  $source
     * @return static
     */
    public function concat($source)
    {
        $result = new static($this);

        foreach ($source as $item) {
            $result->push($item);
        }

        return $result;
    }

    /**
     * Determine if an item exists in the collection.
     *
     * @param  mixed  $key
     * @param  mixed|null  $operator
     * @param  mixed|null  $value
     * @return bool
     */
    public function contains(mixed $key, mixed $operator = null, mixed $value = null): bool
    {
        if (func_num_args() === 1) {
            if ($this->useAsCallable($key)) {
                $placeholder = new \stdClass;

                return $this->first($key, $placeholder) !== $placeholder;
            }

            return in_array($key, $this->items);
        }
        return $this->contains($this->operatorForWhere(...func_get_args()));
    }
    /**
     * Determine if an item is not contained in the collection.
     *
     * @param  mixed  $key
     * @param  mixed  $operator
     * @param  mixed  $value
     * @return bool
     */
    public function doesntContain($key, $operator = null, $value = null)
    {
        return ! $this->contains(...func_get_args());
    }

    /**
     * Determine if the collection contains only one item.
     *
     * @return bool
     */
    public function containsOneItem(): bool
    {
        return $this->count() === 1;
    }

    /**
     * Determine if an item exists, using strict comparison.
     *
     * @param  mixed  $key
     * @param  mixed  $value
     * @return bool
     */
    public function containsStrict($key, $value = null)
    {
        if (func_num_args() === 2) {
            return $this->contains(function ($item) use ($key, $value) {
                return data_get($item, $key) === $value;
            });
        }

        if ($this->useAsCallable($key)) {
            return ! is_null($this->first($key));
        }

        if (in_array($key, $this->items, true)) {
            return true;
        }

        return false;
    }

    /**
     * Count the number of items in the collection.
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->items);
    }

    /**
     * Count the values in the collection grouped by a callback or key.
     *
     * @param  callable|string|null  $groupBy
     * @return Collection
     */
    public function countBy($groupBy = null)
    {
        $counted = [];

        $groupBy = $this->valueRetriever($groupBy);

        foreach ($this->items as $key => $value) {
            $groupKey = $groupBy($value, $key);

            if (!array_key_exists($groupKey, $counted)) {
                $counted[$groupKey] = 0;
            }

            $counted[$groupKey]++;
        }

        return new static($counted);
    }

    /**
     * Get the items in the collection that are not present in the given items.
     *
     * @param  mixed  $items
     * @return static
     */
    public function diff($items)
    {
        return new static(array_diff($this->items, $this->getArrayableItems($items)));
    }

    /**
     * Get a diff of key-value pairs of the collection compared to another collection.
     *
     * @param  mixed  $items
     * @return static
     */
    public function diffAssoc($items): static
    {
        if ($items instanceof self) {
            $items = $items->all();
        }
        return new static(array_diff_assoc($this->items, $this->getArrayableItems($items)));
    }

    /**
     * Get the items in the collection whose keys and values are not present in the given items, using the callback.
     *
     * @param  mixed  $items
     * @param  callable  $callback
     * @return static
     */
    public function diffAssocUsing($items, callable $callback)
    {
        return new static(array_diff_uassoc($this->items, $this->getArrayableItems($items), $callback));
    }

    /**
     * Get the items in the collection whose keys are not present in the given items.
     *
     * @param  mixed  $items
     * @return static
     */
    public function diffKeys($items)
    {
        return new static(array_diff_key($this->items, $this->getArrayableItems($items)));
    }

    /**
     * Get the items in the collection whose keys are not present in the given items, using the callback.
     *
     * @param  mixed  $items
     * @param  callable  $callback
     * @return static
     */
    public function diffKeysUsing($items, callable $callback)
    {
        return new static(array_diff_ukey($this->items, $this->getArrayableItems($items), $callback));
    }

    /**
     * Retrieve duplicate items from the collection.
     *
     * @param  callable|string|null  $callback
     * @param  bool  $strict
     * @return static
     */
    public function duplicates($callback = null, $strict = false)
    {
        $items = $this->map($this->valueRetriever($callback));

        $uniqueItems = $items->unique(null, $strict);

        $compare = $this->duplicateComparator($strict);

        $duplicates = new static;

        foreach ($items as $key => $value) {
            if ($uniqueItems->isNotEmpty() && $compare($value, $uniqueItems->first())) {
                $uniqueItems->shift();
            } else {
                $duplicates[$key] = $value;
            }
        }

        return $duplicates;
    }

    /**
     * Retrieve duplicate items from the collection using strict comparison.
     *
     * @param  callable|string|null  $callback
     * @return static
     */
    public function duplicatesStrict($callback = null)
    {
        return $this->duplicates($callback, true);
    }

    /**
     * Get the comparison function to detect duplicates.
     *
     * @param  bool  $strict
     * @return \Closure
     */
    protected function duplicateComparator($strict)
    {
        if ($strict) {
            return function ($a, $b) {
                return $a === $b;
            };
        }

        return function ($a, $b) {
            return $a == $b;
        };
    }

    /**
     * Execute a callback over each item.
     *
     * @param  callable  $callback
     * @return $this
     */
    public function each(callable $callback): static
    {
        foreach ($this->items as $key => $item) {
            if ($callback($item, $key) === false) {
                break;
            }
        }

        return $this;
    }

    /**
     * Execute a callback over each nested chunk of items.
     *
     * @param  callable  $callback
     * @return $this
     */
    public function eachSpread(callable $callback): static
    {
        return $this->each(function ($chunk, $key) use ($callback) {
            $chunk[] = $key;

            return $callback(...$chunk);
        });
    }

    /**
     * Determine if all items pass the given truth test.
     *
     * @param  string|callable  $key
     * @param  mixed  $operator
     * @param  mixed  $value
     * @return bool
     */
    public function every($key, $operator = null, $value = null)
    {
        if (func_num_args() === 1) {
            $callback = $this->valueRetriever($key);

            foreach ($this as $k => $v) {
                if (! $callback($v, $k)) {
                    return false;
                }
            }

            return true;
        }

        return $this->every($this->operatorForWhere(...func_get_args()));
    }
    /**
     * Get all of the items in the collection except for those with specified keys.
     *
     * @param  mixed  $keys
     * @return static
     */
    public function except($keys): static
    {
        $keys = is_array($keys) ? $keys : func_get_args();

        $items = array_filter($this->items, function ($value, $key) use ($keys) {
            return ! in_array($key, $keys);
        }, ARRAY_FILTER_USE_BOTH);

        return new static($items);
    }

    /**
     * Filter the items in the collection using the given callback.
     *
     * @param  callable|null  $callback
     * @return static
     */
    public function filter(callable $callback = null): static
    {
        if ($callback) {
            return new static(array_filter($this->items, $callback, ARRAY_FILTER_USE_BOTH));
        }

        return new static(array_filter($this->items));
    }

    /**
     * Get the items in the collection that are not present in the given items.
     *
     * @param  mixed  $items
     * @return static
     */
    public function diffUsing($items, callable $callback): static
    {
        return new static(array_udiff($this->items, $this->getArrayableItems($items), $callback));
    }

    /**
     * Get the first item from the collection passing the given truth test.
     *
     * @param  callable|null  $callback
     * @param  mixed  $default
     * @return mixed
     */
    public function first(callable $callback = null, $default = null)
    {
        return Arr::first($this->items, $callback, $default);
    }

    /**
     * Get the first item in the collection but throw an exception if no matching items exist.
     *
     * @param  mixed  $key
     * @param  mixed  $operator
     * @param  mixed  $value
     * @return mixed
     *
     * @throws \Exception
     */
    public function firstOrFail($key = null, $operator = null, $value = null)
    {
        $filter = func_num_args() > 1
            ? $this->operatorForWhere(...func_get_args())
            : $key;

        $placeholder = new \stdClass();

        $item = $this->first($filter, $placeholder);

        if ($item === $placeholder) {
            throw new \Exception('Item not found');
        }

        return $item;
    }
    /**
     * Get the first item by the given key value pair.
     *
     * @param  string  $key
     * @param  mixed  $operator
     * @param  mixed  $value
     * @return mixed
     */
    public function firstWhere($key, $operator = null, $value = null)
    {
        return $this->first($this->operatorForWhere(...func_get_args()));
    }
    /**
     * Map the values into a new collection after flattening.
     *
     * @param  callable|array|string  $callback
     * @return static
     */
    public function flatMap(callable|array|string $callback): static
    {
        return $this->map($callback)->collapse();
    }
    /**
     * Flatten a multi-dimensional collection into a single level.
     *
     * @param  int  $depth
     * @return static
     */
    public function flatten($depth = INF): static
    {
        $result = [];

        $flatten = function ($items, $depth) use (&$result, &$flatten) {
            foreach ($items as $item) {
                if (! is_array($item) && ! $item instanceof Collection) {
                    $result[] = $item;
                } elseif ($depth === 1) {
                    $result = array_merge($result, array_values($item));
                } else {
                    $flatten($item, $depth - 1);
                }
            }
        };

        $flatten($this->items, $depth);

        return new static($result);
    }


    /**
     * Get the value of the given key from the given item.
     *
     * @param  mixed  $item
     * @param  string  $key
     * @return mixed
     */
    protected function getValue($item, $key)
    {
        if (is_array($item)) {
            return $item[$key] ?? null;
        } elseif ($item instanceof \ArrayAccess) {
            return $item[$key] ?? null;
        } elseif (is_object($item)) {
            return $item->{$key} ?? null;
        }

        return null;
    }


    /**
     * Flip the keys and values of the collection.
     *
     * @return static
     */
    public function flip()
    {
        return new static(array_flip($this->items));
    }

    /**
     * Remove an item from the collection by key.
     *
     * @param  mixed  $keys
     * @return $this
     */
    public function forget($keys)
    {
        $keys = is_array($keys) ? $keys : func_get_args();

        foreach ($keys as $key) {
            unset($this->items[$key]);
        }

        return $this;
    }

    /**
     * Get a new collection containing the items from the collection that would be present on a given page number.
     *
     * @param  int  $page
     * @param  int  $perPage
     * @return static
     */
    public function forPage($page, $perPage)
    {
        $page = max($page, 1);
        $offset = ($page - 1) * $perPage;

        return $this->slice($offset, $perPage);
    }

    /**
     * Get an item from the collection by key.
     *
     * @param  mixed  $key
     * @param  mixed  $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        if (array_key_exists($key, $this->items)) {
            return $this->items[$key];
        }

        return Arr::value($default);
    }

    /**
     * Group an associative array by a field or using a callback.
     *
     * @param  array|callable|string  $groupBy
     * @param  bool  $preserveKeys
     * @return static
     */
    public function groupBy($groupBy, $preserveKeys = false)
    {
        if (! $this->useAsCallable($groupBy) && is_array($groupBy)) {
            $nextGroups = $groupBy;

            $groupBy = array_shift($nextGroups);
        }

        $groupBy = $this->valueRetriever($groupBy);

        $results = [];

        foreach ($this->items as $key => $value) {
            $groupKeys = $groupBy($value, $key);

            if (! is_array($groupKeys)) {
                $groupKeys = [$groupKeys];
            }

            foreach ($groupKeys as $groupKey) {
                $groupKey = is_bool($groupKey) ? (int) $groupKey : $groupKey;

                if (! array_key_exists($groupKey, $results)) {
                    $results[$groupKey] = new static;
                }

                $results[$groupKey]->offsetSet($preserveKeys ? $key : null, $value);
            }
        }

        $result = new static($results);

        if (! empty($nextGroups)) {
            return $result->map->groupBy($nextGroups, $preserveKeys);
        }

        return $result;
    }

    /**
     * Determine if an item exists in the collection by key.
     *
     * @param  mixed  $key
     * @return bool
     */
    public function has($key)
    {
        $keys = is_array($key) ? $key : func_get_args();

        foreach ($keys as $value) {
            if (! array_key_exists($value, $this->items)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Determine if any of the given keys exist in the collection.
     *
     * @param  mixed  $keys
     * @return bool
     */
    public function hasAny($keys)
    {
        $keys = is_array($keys) ? $keys : func_get_args();

        foreach ($keys as $key) {
            if ($this->has($key)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Concatenate values of a given key as a string.
     *
     * @param  $value
     * @param  string|null  $glue
     * @return string
     */
    public function implode($value, $glue = null)
    {
        if (!is_callable($value)) {
            $first = $this->first();
            if (is_array($first) || (is_object($first) && ! is_string($first))) {
                return implode($glue ?? '', $this->pluck($value)->all());
            }else {
                return implode($value ?? '', $this->items);
            }
        }

        return implode($glue ?? '', $this->map($value)->all());
    }


    /**
     * Intersect the collection with the given items.
     *
     * @param  Collection|array  $items
     * @return static
     */
    public function intersect($items)
    {
        return new static(array_intersect($this->items, $this->getArrayableItems($items)));
    }

    /**
     * Intersect the collection with the given items by key.
     *
     * @param  Collection|array  $items
     * @return static
     */
    public function intersectByKeys($items)
    {
        return new static(array_intersect_key(
              $this->items, $this->getArrayableItems($items))
        );
    }

    /**
     * Determine if the collection is empty or not.
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->items);
    }

    /**
     * Determine if the collection is not empty.
     *
     * @return bool
     */
    public function isNotEmpty()
    {
        return ! $this->isEmpty();
    }
    /**
     * Join all items from the collection using a string. The final items can use a separate glue string.
     *
     * @param  string  $glue
     * @param  string  $finalGlue
     * @return string
     */
    public function join($glue, $finalGlue = '')
    {
        if ($finalGlue === '') {
            return $this->implode($glue);
        }

        $count = $this->count();

        if ($count === 0) {
            return '';
        }

        if ($count === 1) {
            return $this->last();
        }

        $collection = new static($this->items);

        $finalItem = $collection->pop();

        return $collection->implode($glue).$finalGlue.$finalItem;
    }

    /**
     * Key an associative array by a field or using a callback.
     *
     * @param  callable|string  $keyBy
     * @return static
     */
    public function keyBy($keyBy)
    {
        $keyBy = $this->valueRetriever($keyBy);

        $results = [];

        foreach ($this->items as $key => $item) {
            $resolvedKey = $keyBy($item, $key);

            if (is_object($resolvedKey)) {
                $resolvedKey = (string) $resolvedKey;
            }

            $results[$resolvedKey] = $item;
        }

        return new static($results);
    }

    /**
     * Get the keys of the collection items.
     *
     * @return static
     */
    public function keys()
    {
        return new static(array_keys($this->items));
    }
    /**
     * Get the last item from the collection.
     *
     * @param  callable|null  $callback
     * @param  mixed  $default
     * @return mixed
     */
    public function last(callable $callback = null, $default = null)
    {
        if (is_null($callback)) {
            return empty($this->items) ? $default : end($this->items);
        }

        return $this->filter($callback)->last(null, $default);
    }

    /**
     * Create a new collection instance.
     *
     * @param  mixed  $items
     * @return static
     */
    public static function make($items = null)
    {
        return new static(is_null($items) ? [] : $items);
    }
    /**
     * Run a map over each of the items.
     *
     * @param  callable  $callback
     * @return static
     */
    public function map(callable $callback)
    {
        $keys = array_keys($this->items);

        $items = array_map($callback, $this->items, $keys);

        return new static(array_combine($keys, $items));
    }
    /**
     * Run a map over each nested chunk of items.
     *
     * @param  string  $class
     * @return static
     */
    public function mapInto($class)
    {
        return $this->map(function ($item) use ($class) {
            return new $class($item);
        });
    }
    /**
     * Run a map over each nested chunk of items, spreading the values.
     *
     * @param  callable  $callback
     * @return static
     */
    public function mapSpread(callable $callback)
    {
        return $this->map(function ($chunk, $key) use ($callback) {
            $chunk[] = $key;

            return $callback(...$chunk);
        });
    }
    /**
     * Map the values into groups based on a closure.
     *
     * @param  callable  $callback
     * @return static
     */
    public function mapToGroups(callable $callback)
    {
        $groups = $this->mapToDictionary($callback);

        return $groups->map([$this, 'make']);
    }
    /**
     * Run a dictionary map over the items.
     *
     * The callback should return an associative array with a single key/value pair.
     *
     * @param  callable  $callback
     * @return static
     */
    public function mapToDictionary(callable $callback)
    {
        $dictionary = [];

        foreach ($this->items as $key => $item) {
            $pair = $callback($item, $key);

            $key = key($pair);

            $value = reset($pair);

            if (! isset($dictionary[$key])) {
                $dictionary[$key] = [];
            }

            $dictionary[$key][] = $value;
        }

        return new static($dictionary);
    }
    /**
     * Run an associative map over each of the items.
     *
     * The callback should return an associative array with a single key/value pair.
     *
     * @param  callable  $callback
     * @return static
     */
    public function mapWithKeys(callable $callback)
    {
        $result = [];

        foreach ($this->items as $key => $value) {
            $assoc = $callback($value, $key);

            foreach ($assoc as $mapKey => $mapValue) {
                $result[$mapKey] = $mapValue;
            }
        }

        return new static($result);
    }
    /**
     * Get the max value of a given key.
     *
     * @param  callable|string|null  $callback
     * @return mixed
     */
    public function max($callback = null)
    {
        $callback = $this->valueRetriever($callback);

        return $this->filter(function ($value) {
            return ! is_null($value);
        })->reduce(function ($result, $item) use ($callback) {
            $value = $callback($item);

            return is_null($result) || $value > $result ? $value : $result;
        });
    }
    /**
     * Get the median value of a given key.
     *
     * @param  string|null  $key
     * @return mixed|null
     */
    public function median($key = null)
    {
        $values = (isset($key) ? $this->pluck($key) : $this)
            ->filter(function ($item) {
                return ! is_null($item);
            })->sort()->values();

        $count = $values->count();

        if ($count === 0) {
            return;
        }

        $middle = (int) ($count / 2);

        if ($count % 2) {
            return $values->get($middle);
        }

        return (new static([
                               $values->get($middle - 1), $values->get($middle),
                           ]))->average();
    }

    /**
     * Merge the collection with the given items.
     *
     * @param  iterable  $items
     * @return static
     */
    public function merge($items)
    {
        return new static(array_merge($this->items, $this->getArrayableItems($items)));
    }
    /**
     * Recursively merge the collection with the given items.
     *
     * @param  $items
     * @return static
     */
    public function mergeRecursive($items)
    {
        return new static(array_merge_recursive($this->items, $this->getArrayableItems($items)));
    }
    /**
     * Get the min value of a given key.
     *
     * @param  callable|string|null  $callback
     * @return mixed
     */
    public function min($callback = null)
    {
        $callback = $this->valueRetriever($callback);

        return $this->map(function ($value) use ($callback) {
            return $callback($value);
        })->filter(function ($value) {
            return ! is_null($value);
        })->reduce(function ($result, $value) {
            return is_null($result) || $value < $result ? $value : $result;
        });
    }
    /**
     * Get the mode of a given key.
     *
     * @param  string|array|null  $key
     * @return array|null
     */
    public function mode($key = null)
    {
        if ($this->count() === 0) {
            return [];
        }
        $collection = isset($key) ? $this->pluck($key) : $this;
        $counts = $collection->reduce(function ($carry, $item) {
            if (!isset($carry[$item])) {
                $carry[$item] = 1;
            } else {
                $carry[$item]++;
            }
            return $carry;
        }, []);

        $maxCount = max($counts);

        $modes = array_filter($counts, function ($count) use ($maxCount) {
            return $count === $maxCount;
        });

        return array_keys($modes);
    }

    /**
     * Create a new collection consisting of every n-th element.
     *
     * @param  int  $step
     * @param  int  $offset
     * @return static
     */
    public function nth($step, $offset = 0)
    {
        $new = [];

        $position = 0;

        foreach ($this->slice($offset)->items as $item) {
            if ($position % $step === 0) {
                $new[] = $item;
            }

            $position++;
        }

        return new static($new);
    }
    public function only($keys): Collection
    {
        $keys = is_array($keys) ? $keys : func_get_args();

        return $this->filter(function ($value, $key) use ($keys) {
            return in_array($key, $keys);
        });
    }
    /**
     * Pad collection to the specified length with a value.
     *
     * @param  int  $size
     * @param  mixed  $value
     * @return static
     */
    public function pad($size, $value)
    {
        return new static(array_pad($this->items, $size, $value));
    }

    /**
     * Partition the collection into two arrays using the given callback or key.
     *
     * @param  callable|string  $key
     * @param  mixed  $operator
     * @param  mixed  $value
     * @return static
     */
    public function partition($key, $operator = null, $value = null)
    {
        $passed = [];
        $failed = [];

        $callback = func_num_args() === 1
            ? $this->valueRetriever($key)
            : $this->operatorForWhere(...func_get_args());

        foreach ($this as $key => $item) {
            if ($callback($item, $key)) {
                $passed[$key] = $item;
            } else {
                $failed[$key] = $item;
            }
        }

        return new static([new static($passed), new static($failed)]);
    }

    /**
     * Pipe the collection to a callback and return the result.
     *
     * @param  callable  $callback
     * @return mixed
     */
    public function pipe(callable $callback)
    {
        return $callback($this);
    }

    /**
     * Pass the collection into a new class.
     *
     * @param  string  $class
     * @return mixed
     */
    public function pipeInto($class)
    {
        return new $class($this);
    }

    /**
     * Run the collection through a series of callbacks and return the result.
     *
     * @param  $pipes
     * @return mixed
     */
    public function pipeThrough($pipes)
    {
        return static::make($pipes)->reduce(
            function ($carry, $pipe) {
                return $pipe($carry);
            },
            $this,
        );
    }

    /**
     * Get an array with the values of a given key.
     *
     * @param  string|array|int|null  $value
     * @param  string|array|null  $key
     * @return static
     */
    public function pluck($value, $key = null)
    {
        $results = [];

        foreach ($this->items as $item) {
            // If the value is an integer and the item is an array or object, use array indexing or object properties
            if (is_int($value) && (is_array($item) || is_object($item))) {
                $itemValue = $item[$value] ?? null;
            }
            // If the value is a string and the item is an array or object, use dot notation to get the value
            elseif (is_string($value) && (is_array($item) || is_object($item))) {
                $itemValue = $this->getValueByDotNotation($item, $value);
            }
            // Otherwise, use the value as is
            else {
                $itemValue = $value;
            }

            // If the key is null, we will just append the value to the array and keep
            // looping. Otherwise we will key the array using the value of the key we
            // received from the developer. Then we'll return the final array form.
            if (is_null($key)) {
                $results[] = $itemValue;
            } else {
                // If the key is an integer and the item is an array or object, use array indexing or object properties
                if (is_int($key) && (is_array($item) || is_object($item))) {
                    $itemKey = $item[$key] ?? null;
                }
                // If the key is a string and the item is an array or object, use dot notation to get the value
                elseif (is_string($key) && (is_array($item) || is_object($item))) {
                    $itemKey = $this->getValueByDotNotation($item, $key);
                }
                // Otherwise, use the key as is
                else {
                    $itemKey = $key;
                }

                if (is_object($itemKey) && method_exists($itemKey, '__toString')) {
                    $itemKey = (string) $itemKey;
                }

                $results[$itemKey] = $itemValue;
            }
        }

        return new static($results);
    }

    /**
     * Get and remove the last N items from the collection.
     *
     * @param  int  $count
     * @return mixed
     */
    public function pop($count = 1)
    {
        if ($count === 1) {
            return array_pop($this->items);
        }

        if ($this->isEmpty()) {
            return new static;
        }

        $results = [];

        $collectionCount = $this->count();

        foreach (range(1, min($count, $collectionCount)) as $item) {
            array_push($results, array_pop($this->items));
        }

        return new static($results);
    }

    /**
     * Append an item to the end of the collection.
     *
     * @param $value
     * @return $this
     */
    public function append($value) {
        array_push($this->items, $value);
        return $this;
    }
    /**
     * Prepend an item to the beginning of the collection.
     *
     * @param  mixed  $value
     * @param  mixed  $key
     * @return $this
     */
    public function prepend($value, $key = null)
    {
        if (func_num_args() == 1) {
            array_unshift($this->items, $value);
        } else {
            $this->items = [$key => $value] + $this->items;
        }

        return $this;
    }

    /**
     * Pull a value from the collection by key.
     *
     * @param  mixed  $key
     * @param  mixed  $default
     * @return mixed
     */
    public function pull($key, $default = null)
    {
        $value = $this->get($key, $default);
        $this->forget($key);

        return $value;
    }
    /**
     * Push an item onto the end of the collection.
     *
     * @param  mixed  $value
     * @return $this
     */
    public function push($value)
    {
        $this->items[] = $value;

        return $this;
    }

    /**
     * Put an item in the collection by key.
     *
     * @param  mixed  $key
     * @param  mixed  $value
     * @return $this
     */
    public function put($key, $value)
    {
        $this->items[$key] = $value;

        return $this;
    }

    /**
     * Get one or a specified number of items randomly from the collection.
     *
     * @param  int|null  $number
     * @return static|mixed
     *
     * @throws \InvalidArgumentException
     */
    public function random($number = null)
    {
        if (is_null($number)) {
            return Arr::random($this->items);
        }

        return new static(Arr::random($this->items, $number));
    }

    /**
     * Create a new collection with a range of numbers.
     *
     * @param  int  $from
     * @param  int  $to
     * @return static
     */
    public static function range($from, $to)
    {
        return new static(range($from, $to));
    }

    /**
     * Reduce the collection to a single value.
     *
     * @param  callable  $callback
     * @param  mixed  $initial
     * @return mixed
     */
    public function reduce(callable $callback, $initial = null)
    {
        $result = $initial;

        foreach ($this->items as $key => $value) {
            $result = $callback($result, $value, $key);
        }

        return $result;
    }

    /**
     * Reduce the collection to multiple aggregate values.
     *
     * @param  callable  $callback
     * @param  mixed  ...$initial
     * @return array
     *
     * @throws \UnexpectedValueException
     */
    public function reduceSpread(callable $callback, ...$initial)
    {
        $result = $initial;

        foreach ($this->items as $key => $value) {
            $result = call_user_func_array($callback, array_merge($result, [$value, $key]));

            if (! is_array($result)) {
                throw new \UnexpectedValueException(sprintf(
                   "%s::reduceSpread expects reducer to return an array, but got a '%s' instead.",
                   class_basename(static::class), gettype($result)
               ));
            }
        }

        return $result;
    }

    /**
     * Create a collection of all elements that do not pass a given truth test.
     *
     * @param  callable|mixed  $callback
     * @return static
     */
    public function reject($callback = true)
    {
        $useAsCallable = $this->useAsCallable($callback);

        return $this->filter(function ($value, $key) use ($callback, $useAsCallable) {
            return $useAsCallable
                ? ! $callback($value, $key)
                : $value != $callback;
        });
    }

    /**
     * Replace the collection items with the given items.
     *
     * @param  mixed  $items
     * @return static
     */
    public function replace($items)
    {
        return new static(array_replace($this->items, $this->getArrayableItems($items)));
    }

    /**
     * Recursively replaces items with the given items.
     *
     * @param iterable $items
     * @return static
     */
    public function replaceRecursive($items)
    {
        return new static(array_replace_recursive($this->items, $this->getArrayableItems($items)));
    }

    /**
     * Reverse the order of the items in the collection.
     *
     * @return static
     */
    public function reverse()
    {
        return new static(array_reverse($this->items));
    }

    /**
     * Search the collection for a given value and return the corresponding key if successful.
     *
     * @param  mixed  $value
     * @param  bool  $strict
     * @return mixed
     */
    public function search($value, $strict = false)
    {
        if (! $this->useAsCallable($value)) {
            return array_search($value, $this->items, $strict);
        }

        foreach ($this->items as $key => $item) {
            if ($value($item, $key)) {
                return $key;
            }
        }

        return false;
    }

    /**
     * Get and remove the first N items from the collection.
     *
     * @param  int  $count
     * @return mixed
     */
    public function shift($count = 1)
    {
        if ($count === 1) {
            return array_shift($this->items);
        }

        if ($this->isEmpty()) {
            return new static;
        }

        $results = [];

        $collectionCount = $this->count();

        foreach (range(1, min($count, $collectionCount)) as $item) {
            array_push($results, array_shift($this->items));
        }

        return new static($results);
    }

    /**
     * Shuffle the items in the collection.
     *
     * @param  int  $seed
     * @return static
     */
    public function shuffle($seed = null)
    {
        $items = $this->items;

        if (is_null($seed)) {
            shuffle($items);
        } else {
            srand($seed);

            usort($items, function () {
                return rand(-1, 1);
            });
        }

        return new static($items);
    }

    /**
     * Skip the first {$count} items.
     *
     * @param  int  $count
     * @return static
     */
    public function skip($count)
    {
        return new static(array_slice($this->items, $count));
    }

    /**
     * Skip items while the given function returns true.
     *
     * @param $value
     * @return static
     */
    public function skipUntil($value)
    {
        $callback = $this->useAsCallable($value) ? $value : $this->equality($value);

        return $this->skipWhile($this->negate($callback));
    }
    /**
     * Make a function using another function, by negating its result.
     *
     * @param  \Closure  $callback
     * @return \Closure
     */
    protected function negate(Closure $callback)
    {
        return function (...$params) use ($callback) {
            return ! $callback(...$params);
        };
    }
    /**
     * Skip items while the given function returns true.
     *
     * @param  $callback
     * @return static
     */
    public function skipWhile($callback)
    {
        $skip = true;

        return $this->filter(function ($item, $key) use ($callback, &$skip) {
            if ($skip && $callback($item, $key)) {
                return false;
            }

            $skip = false;

            return true;
        });
    }

    /**
     * Slice the underlying collection array.
     *
     * @param  int  $offset
     * @param  int  $length
     * @return static
     */
    public function slice($offset, $length = null)
    {
        return new static(array_slice($this->items, $offset, $length, true));
    }

    /**
     * Create chunks representing a "sliding window" view of the items in the collection.
     *
     * @param  int  $size
     * @param  int  $step
     * @return static
     */
    public function sliding($size = 2, $step = 1)
    {
        $chunks = floor(($this->count() - $size) / $step) + 1;

        return static::times($chunks, function ($number) use ($size, $step) {
            return $this->slice(($number - 1) * $step, $size)->values()->toArray();
        });
    }
    /**
     * Get the first item in the collection, but only if exactly one item exists. Otherwise, throw an exception.
     *
     * @param  mixed  $key
     * @param  mixed  $operator
     * @param  mixed  $value
     * @return mixed
     *
     */
    public function sole($key = null, $operator = null, $value = null)
    {
        $filter = func_num_args() > 1
            ? $this->operatorForWhere(...func_get_args())
            : $key;

        $items = $this->when($filter)->filter($filter);

        if ($items->isEmpty()) {
            throw new \Exception('Item not found');
        }

        if ($items->count() > 1) {
            throw new \Exception('Multiple items found');
        }

        return $items->first();
    }

    /**
     * Alias for the "contains" method.
     *
     * @param  mixed  $key
     * @param  mixed  $operator
     * @param  mixed  $value
     * @return bool
     */
    public function some($key, $operator = null, $value = null)
    {
        return $this->contains(...func_get_args());
    }

    /**
     * Sort the collection.
     *
     * @param  null  $callback
     * @return static
     */
    public function sort($callback = null)
    {
        $items = $this->items;

        $callback
            ? uasort($items, $callback)
            : asort($items);

        return new static($items);
    }

    /**
     * Sort items in descending order.
     *
     * @param  int  $options
     * @return static
     */
    public function sortDesc($options = SORT_REGULAR)
    {
        $items = $this->items;

        arsort($items, $options);

        return new static($items);
    }

    /**
     * Sort the collection using the given callback.
     *
     * @param  callable|array|string  $callback
     * @param  int  $options
     * @param  bool  $descending
     * @return static
     */
    public function sortBy($callback, $options = SORT_REGULAR, $descending = false)
    {
        if (is_array($callback) && ! is_callable($callback)) {
            return $this->sortByMany($callback);
        }

        $results = [];

        $callback = $this->valueRetriever($callback);

        // First we will loop through the items and get the comparator from a callback
        // function which we were given. Then, we will sort the returned values and
        // grab all the corresponding values for the sorted keys from this array.
        foreach ($this->items as $key => $value) {
            $results[$key] = $callback($value, $key);
        }

        $descending ? arsort($results, $options)
            : asort($results, $options);

        // Once we have sorted all of the keys in the array, we will loop through them
        // and grab the corresponding model so we can set the underlying items list
        // to the sorted version. Then we'll just return the collection instance.
        foreach (array_keys($results) as $key) {
            $results[$key] = $this->items[$key];
        }

        return new static($results);
    }
    /**
     * Sort the collection using multiple comparisons.
     *
     * @param  array  $comparisons
     * @return static
     */
    protected function sortByMany(array $comparisons = [])
    {
        $items = $this->items;

        usort($items, function ($a, $b) use ($comparisons) {
            foreach ($comparisons as $comparison) {
                $comparison = Arr::wrap($comparison);

                $prop = $comparison[0];

                $ascending = Arr::get($comparison, 1, true) === true ||
                    Arr::get($comparison, 1, true) === 'asc';

                $result = 0;

                if (! is_string($prop) && is_callable($prop)) {
                    $result = $prop($a, $b);
                } else {
                    $values = [Arr::data_get($a, $prop), Arr::data_get($b, $prop)];

                    if (! $ascending) {
                        $values = array_reverse($values);
                    }

                    $result = $values[0] <=> $values[1];
                }

                if ($result === 0) {
                    continue;
                }

                return $result;
            }
        });

        return new static($items);
    }
    /**
     * Sort the collection in descending order using the given callback.
     *
     * @param  callable|string  $callback
     * @param  int  $options
     * @return static
     */
    public function sortByDesc($callback, $options = SORT_REGULAR)
    {
        return $this->sortBy($callback, $options, true);
    }

    /**
     * Sort the collection keys.
     *
     * @param  int  $options
     * @param  bool  $descending
     * @return static
     */
    public function sortKeys($options = SORT_REGULAR, $descending = false)
    {
        $items = $this->items;

        $descending ? krsort($items, $options) : ksort($items, $options);

        return new static($items);
    }

    /**
     * Sort the collection by keys in descending order.
     *
     * @param  int|callable|null  $callback
     * @return static
     */
    public function sortKeysDesc($callback = null)
    {
        return $this->sortKeys($callback)->reverse();
    }

    /**
     * Sort the collection keys using a callback.
     *
     * @param  callable  $callback
     * @return static
     */
    public function sortKeysUsing(callable $callback)
    {
        $items = $this->items;

        uksort($items, $callback);

        return new static($items);
    }

    /**
     * Splice a portion of the underlying collection array.
     *
     * @param  int  $offset
     * @param  int|null  $length
     * @param  mixed  $replacement
     * @return static
     */
    public function splice($offset, $length = null, $replacement = [])
    {
        if (func_num_args() === 1) {
            return new static(array_splice($this->items, $offset));
        }

        return new static(array_splice($this->items, $offset, $length, $this->getArrayableItems($replacement)));
    }
    /**
     * Split a collection into a certain number of groups.
     *
     * @param  int  $numberOfGroups
     * @return static
     */
    public function split($numberOfGroups)
    {
        if ($this->isEmpty()) {
            return new static;
        }

        $groups = new static;

        $groupSize = floor($this->count() / $numberOfGroups);

        $remain = $this->count() % $numberOfGroups;

        $start = 0;

        for ($i = 0; $i < $numberOfGroups; $i++) {
            $size = $groupSize;

            if ($i < $remain) {
                $size++;
            }

            if ($size) {
                $groups->push(new static(array_slice($this->items, $start, $size)));

                $start += $size;
            }
        }

        return $groups;
    }
    /**
     * Split a collection into a certain number of groups, and fill the first groups completely.
     *
     * @param  int  $numberOfGroups
     * @return static
     */
    public function splitIn($numberOfGroups)
    {
        return $this->chunk(ceil($this->count() / $numberOfGroups));
    }

    /**
     * Get the sum of the given values.
     *
     * @param  callable|string|null  $callback
     * @return mixed
     */
    public function sum($callback = null)
    {
        $callback = is_null($callback)
            ? $this->identity()
            : $this->valueRetriever($callback);

        return $this->reduce(function ($result, $item) use ($callback) {
            return $result + $callback($item);
        }, 0);
    }

    /**
     * Take the first {$limit} items.
     *
     * @param  int  $limit
     * @return static
     */
    public function take($limit)
    {
        if ($limit < 0) {
            return $this->slice($limit, abs($limit));
        }

        return $this->slice(0, $limit);
    }
    /**
     * Take items in the collection until the given condition is met.
     *
     * @param  mixed  $value
     * @return static
     */
    public function takeUntil($value)
    {
        $callback = $this->useAsCallable($value) ? $value : $this->equality($value);
        $result = [];

        foreach ($this->items as $item) {
            if ($callback($item)) {
                break;
            }

            $result[] = $item;
        }

        return new static($result);
    }

    /**
     * Take items in the collection while the given condition is met.
     *
     * @param $callback
     * @return static
     */
    public function takeWhile($callback)
    {
        $results = [];
        foreach ($this->items as $key => $item) {
            if (!$callback($item, $key)) {
                break;
            }
            $results[] = $item;
        }
        return new static($results);
    }

    /**
     * Pass the collection to the given callback and then return it.
     *
     * @param  callable  $callback
     * @return $this
     */
    public function tap(callable $callback)
    {
        $callback(clone $this);

        return $this;
    }

    public static function times($n, $callback = null)
    {
        if ($n < 1) {
            return new static;
        }
        return static::range(1, $n)
            ->when($callback)
            ->map($callback);
    }
    /**
     * Get the collection of items as a plain array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->map(function ($value) {
            return $value instanceof Collection ? $value->toArray() : $value;
        })->all();
    }
    /**
     * Convert the object into something JSON serializable.
     *
     * @return array
     */
    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return array_map(function ($value) {
            if ($value instanceof \JsonSerializable) {
                return $value->jsonSerialize();
            } elseif ($value instanceof Jsonable) {
                return json_decode($value->toJson(), true);
            } elseif ($value instanceof Arrayable) {
                return $value->toArray();
            }

            return $value;
        }, $this->all());
    }
    /**
     * Get the collection of items as JSON.
     *
     * @param  int  $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->jsonSerialize(), $options);
    }
    /**
     * Transform each item in the collection using a callback.
     *
     * @param  callable  $callback
     * @return $this
     */
    public function transform(callable $callback)
    {
        $this->items = $this->map($callback)->all();
        return $this;
    }
    /**
     * Convert a flatten "dot" notation array into an expanded array.
     *
     * @return static
     */
    public function undot()
    {
        $results = [];
        foreach ($this->items as $key => $value) {
            if (strpos($key, '.') === false) {
                $results[$key] = $value;
                continue;
            }
            $keys = explode('.', $key);
            $temp = &$results;
            while (count($keys) > 1) {
                $key = array_shift($keys);
                if (!isset($temp[$key]) || !is_array($temp[$key])) {
                    $temp[$key] = [];
                }
                $temp = &$temp[$key];
            }
            $temp[array_shift($keys)] = $value;
        }
        return new static($results);
    }

    /**
     * Union the collection with the given items.
     *
     * @param  mixed  $items
     * @return static
     */
    public function union($items)
    {
        return new static($this->items + $this->getArrayableItems($items));
    }

    /**
     * Return only unique items from the collection array.
     *
     * @param  string|callable|null  $key
     * @param  bool  $strict
     * @return static
     */
    public function unique($key = null, $strict = false)
    {
        if (is_null($key) && $strict === false) {
            return new static(array_unique($this->items, SORT_REGULAR));
        }

        $callback = $this->valueRetriever($key);

        $exists = [];

        return $this->reject(function ($item, $key) use ($callback, $strict, &$exists) {
            if (in_array($id = $callback($item, $key), $exists, $strict)) {
                return true;
            }

            $exists[] = $id;
        });
    }

    /**
     * Return only unique items from the collection array using strict comparison.
     *
     * @param  string|callable|null  $key
     * @return static
     */
    public function uniqueStrict($key = null)
    {
        return $this->unique($key, true);
    }

    /**
     * Apply the callback if the value is falsy.
     *
     * @param  bool  $value
     * @param  callable  $callback
     * @param  callable|null  $default
     * @return static|mixed
     */
    public function unless($value, callable $callback, callable $default = null)
    {
        return $this->when(! $value, $callback, $default);
    }
    /**
     * Apply the callback unless the collection is empty.
     *
     * @param  callable  $callback
     * @param  callable|null  $default
     * @return static|mixed
     */
    public function unlessEmpty(callable $callback, callable $default = null)
    {
        return $this->whenNotEmpty($callback, $default);
    }

    /**
     * Apply the callback unless the collection is not empty.
     *
     * @param  callable  $callback
     * @param  callable|null  $default
     * @return static|mixed
     */
    public function unlessNotEmpty(callable $callback, callable $default = null)
    {
        return $this->whenEmpty($callback, $default);
    }

    /**
     * Get the underlying items from the given collection if applicable.
     *
     * @param  array|static  $value
     * @return array
     */
    public static function unwrap($value)
    {
        return $value instanceof Enumerable ? $value->all() : $value;
    }

    public function value($default = null)
    {
        return $this->first() ?: $default;
    }

    /**
     * Apply the callback if the value is truthy.
     *
     * @param  bool|mixed  $value
     * @param  callable|null  $callback
     * @param  callable|null  $default
     * @return static|mixed
     */
    public function when($value, callable $callback = null, callable $default = null)
    {
        if (! $callback) {
            return new HigherOrderWhenProxy($this, $value);
        }

        if ($value) {
            return $callback($this, $value);
        } elseif ($default) {
            return $default($this, $value);
        }

        return $this;
    }

    /**
     * Apply the callback if the collection is empty.
     *
     * @param  callable  $callback
     * @param  callable|null  $default
     * @return static|mixed
     */
    public function whenEmpty(callable $callback, callable $default = null)
    {
        return $this->when($this->isEmpty(), $callback, $default);
    }

    /**
     * Apply the callback if the collection is not empty.
     *
     * @param  callable  $callback
     * @param  callable|null  $default
     * @return static|mixed
     */
    public function whenNotEmpty(callable $callback, callable $default = null)
    {
        return $this->when($this->isNotEmpty(), $callback, $default);
    }

    /**
     * Filter items by the given key value pair.
     *
     * @param  string  $key
     * @param  mixed  $operator
     * @param  mixed  $value
     * @return static
     */
    public function where($key, $operator = null, $value = null)
    {
        return $this->filter($this->operatorForWhere(...func_get_args()));
    }

    /**
     * Filter items by the given key value pair using strict comparison.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return static
     */
    public function whereStrict($key, $value)
    {
        return $this->where($key, '===', $value);
    }

    /**
     * Filter items such that the value of the given key is between the given values.
     *
     * @param  string  $key
     * @param  array  $values
     * @return static
     */
    public function whereBetween($key, $values)
    {
        return $this->where($key, '>=', reset($values))->where($key, '<=', end($values));
    }

    /**
     * Filter items by the given key value pair.
     *
     * @param  string  $key
     * @param  mixed  $values
     * @param  bool  $strict
     * @return static
     */
    public function whereIn($key, $values, $strict = false)
    {
        $values = $this->getArrayableItems($values);

        return $this->filter(function ($item) use ($key, $values, $strict) {
            return in_array(Arr::data_get($item, $key), $values, $strict);
        });
    }

    /**
     * Filter items by the given key value pair using strict comparison.
     *
     * @param  string  $key
     * @param  mixed  $values
     * @return static
     */
    public function whereInStrict($key, $values)
    {
        return $this->whereIn($key, $values, true);
    }

    /**
     * Filter the items, removing any items that don't match the given type(s).
     *
     * @param  string|string[]  $type
     * @return static
     */
    public function whereInstanceOf($type)
    {
        return $this->filter(function ($value) use ($type) {
            if (is_array($type)) {
                foreach ($type as $classType) {
                    if ($value instanceof $classType) {
                        return true;
                    }
                }

                return false;
            }

            return $value instanceof $type;
        });
    }

    /**
     * Filter items such that the value of the given key is not between the given values.
     *
     * @param  string  $key
     * @param  array  $values
     * @return static
     */
    public function whereNotBetween($key, $values)
    {
        return $this->filter(function ($item) use ($key, $values) {
            return Arr::data_get($item, $key) < reset($values) || Arr::data_get($item, $key) > end($values);
        });
    }

    /**
     * Filter items by the given key value pair.
     *
     * @param  string  $key
     * @param  mixed  $values
     * @param  bool  $strict
     * @return static
     */
    public function whereNotIn($key, $values, $strict = false)
    {
        $values = $this->getArrayableItems($values);

        return $this->reject(function ($item) use ($key, $values, $strict) {
            return in_array(Arr::data_get($item, $key), $values, $strict);
        });
    }

    /**
     * Filter items by the given key value pair using strict comparison.
     *
     * @param  string  $key
     * @param  mixed  $values
     * @return static
     */
    public function whereNotInStrict($key, $values)
    {
        return $this->whereNotIn($key, $values, true);
    }

    /**
     * Filter items where the value for the given key is not null.
     *
     * @param  string|null  $key
     * @return static
     */
    public function whereNotNull($key = null)
    {
        return $this->where($key, '!==', null);
    }

    /**
     * Filter items where the value for the given key is null.
     *
     * @param  string|null  $key
     * @return static
     */
    public function whereNull($key = null)
    {
        return $this->whereStrict($key, null);
    }
    /**
     * Wrap the given value in a collection if applicable.
     *
     * @param  mixed  $value
     * @return static
     */
    public static function wrap($value)
    {
        return $value instanceof Collection
            ? new static($value)
            : new static(self::wrapMe($value));
    }

    /**
     * If the given value is not an array and not null, wrap it in one.
     *
     * @param  mixed  $value
     * @return array
     */
    public static function wrapMe($value)
    {
        if (is_null($value)) {
            return [];
        }

        return is_array($value) ? $value : [$value];
    }

    /**
     * Zip the collection together with one or more arrays.
     *
     * e.g. new Collection([1, 2, 3])->zip([4, 5, 6]);
     *      => [[1, 4], [2, 5], [3, 6]]
     *
     * @param  mixed  ...$items
     * @return static
     */
    public function zip($items)
    {
        $arrayableItems = array_map(function ($items) {
            return $this->getArrayableItems($items);
        }, func_get_args());

        $params = array_merge([function () {
            return new static(func_get_args());
        }, $this->items], $arrayableItems);

        return new static(array_map(...$params));
    }

    /**
     * Get the arrayable items of the collection.
     *
     * @param  mixed  $items
     * @return array
     */
    protected function getArrayableItems($items)
    {
        if ($items instanceof Collection) {
            return $items->all();
        } elseif ($items instanceof Arrayable) {
            return $items->toArray();
        }elseif ($items instanceof Jsonable) {
            return json_decode($items->toJson(), true);
        }elseif ($items instanceof \Traversable) {
            return iterator_to_array($items);
        } elseif ($items instanceof \UnitEnum) {
            return [$items];
        } elseif (is_array($items)) {
            return $items;
        }

        return (array) $items;
    }
    /**
     * Get values and reset keys
     *
     * @return $this
     */
    public function values() {
        return new static(array_values($this->items));
    }

    /**
     * Create a new collection instance.
     *
     * @param  array  $items
     * @return static
     */
    public function newCollection(array $items = [])
    {
        return new static($items);
    }


    /**
     * Get the value of an item using dot notation.
     *
     * @param  mixed  $item
     * @param  string  $key
     * @return mixed
     */
    protected function getValueByDotNotation($item, $key)
    {
        foreach (explode('.', $key) as $segment) {
            if (is_array($item) && array_key_exists($segment, $item)) {
                $item = $item[$segment];
            } elseif (is_object($item) && isset($item->{$segment})) {
                $item = $item->{$segment};
            } else {
                return null;
            }
        }

        return $item;
    }

    /**
     * Get an operator checker callback.
     *
     * @param  string|callable  $key
     * @param  mixed  $operator
     * @param  mixed  $value
     * @return callable
     */
    protected function operatorForWhere($key, $operator = null, $value = null)
    {
        if (func_num_args() === 1) {
            $value = true;

            $operator = '=';
        }

        if (func_num_args() === 2) {
            $value = $operator;

            $operator = '=';
        }

        return function ($item) use ($key, $operator, $value) {
            $retrieved = Arr::data_get($item, $key);

            $strings = array_filter([$retrieved, $value], function ($value) {
                return is_string($value) || (is_object($value) && method_exists($value, '__toString'));
            });

            if (count($strings) < 2 && count(array_filter([$retrieved, $value], 'is_object')) == 1) {
                return in_array($operator, ['!=', '<>', '!==']);
            }
            
            switch ($operator) {
                default:
                case '=':
                case '==':  return $retrieved == $value;
                case '!=':
                case '<>':  return $retrieved != $value;
                case '<':   return $retrieved < $value;
                case '>':   return $retrieved > $value;
                case '<=':  return $retrieved <= $value;
                case '>=':  return $retrieved >= $value;
                case '===': return $retrieved === $value;
                case '!==': return $retrieved !== $value;
            }
        };
    }
    /**
     * Compare two values using the given operator.
     *
     * @param  mixed  $left
     * @param  string  $operator
     * @param  mixed  $right
     * @return bool
     */
    protected function compare($left, $operator, $right)
    {
        echo $left;
        switch ($operator) {
            case '>':
                return $left > $right;
            case '>=':
                return $left >= $right;
            case '<':
                return $left < $right;
            case '<=':
                return $left <= $right;
            case '<>':
            case '!=':
                return $left != $right;
            case '=':
            case '===':
                return $left === $right;
            case '!==':
                return $left !== $right;
            case 'like':
                return strpos($left, $right) !== false;
            default:
                return $left == $right;
        }
    }

    public function find($callback, $default = null) {
        foreach ($this->items as $item) {
            if ($callback($item)) {
                return $item;
            }
        }
        return $default;
    }

    /**
     * Determine if the given value is callable, but not a string.
     *
     * @param  mixed  $value
     * @return bool
     */
    protected function useAsCallable($value)
    {
        return ! is_string($value) && is_callable($value);
    }

    /**
     * Get a value retrieving callback.
     *
     * @param  callable|string|null  $value
     * @return callable
     */
    protected function valueRetriever($value)
    {
        if ($this->useAsCallable($value)) {
            return $value;
        }

        return function ($item) use ($value) {
            return Arr::data_get($item, $value);
        };
    }

    /**
     * Make a function to check an item's equality.
     *
     * @param  mixed  $value
     * @return \Closure
     */
    protected function equality($value)
    {
        return function ($item) use ($value) {
            return $item === $value;
        };
    }

    /**
     * Make a function that returns what's passed to it.
     *
     * @return \Closure
     */
    protected function identity()
    {
        return function ($value) {
            return $value;
        };
    }

    /**
     * Determine if an item exists at an offset.
     *
     * @param  mixed  $key
     * @return bool
     */
    #[\ReturnTypeWillChange]
    public function offsetExists($key)
    {
        return isset($this->items[$key]);
    }

    /**
     * Get an item at a given offset.
     *
     * @param  mixed  $key
     * @return mixed
     */
    #[\ReturnTypeWillChange]
    public function offsetGet($key)
    {
        return $this->items[$key];
    }

    /**
     * Set the item at a given offset.
     *
     * @param  mixed  $key
     * @param  mixed  $value
     * @return void
     */
    #[\ReturnTypeWillChange]
    public function offsetSet($key, $value)
    {
        if (is_null($key)) {
            $this->items[] = $value;
        } else {
            $this->items[$key] = $value;
        }
    }

    /**
     * Unset the item at a given offset.
     *
     * @param  mixed  $key
     * @return void
     */
    #[\ReturnTypeWillChange]
    public function offsetUnset($key)
    {
        unset($this->items[$key]);
    }

    /**
     * Convert the collection to its string representation.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->escapeWhenCastingToString
            ? e($this->toJson())
            : $this->toJson();
    }
    /**
     * Create a new instance with no items.
     *
     * @return static
     */
    public static function empty()
    {
        return new static([]);
    }

    /**
     * Get an iterator for the items.
     *
     * @return \ArrayIterator
     */
    #[\ReturnTypeWillChange]
    public function getIterator()
    {
        return new \ArrayIterator($this->items);
    }

}

