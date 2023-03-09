<?php
namespace Imran\Collection\Support;

trait Proxies {
    protected $escapeWhenCastingToString = false;
    /**
     * The methods that can be proxied.
     *
     * @var string[]
     */
    protected static $proxies = [
        'average',
        'avg',
        'contains',
        'doesntContain',
        'each',
        'every',
        'filter',
        'first',
        'flatMap',
        'groupBy',
        'keyBy',
        'map',
        'max',
        'min',
        'partition',
        'reject',
        'skipUntil',
        'skipWhile',
        'some',
        'sortBy',
        'sortByDesc',
        'sum',
        'takeUntil',
        'takeWhile',
        'unique',
        'until',
    ];

    /**
     * Dynamically access collection proxies.
     *
     * @param  string  $key
     * @return mixed
     *
     * @throws \Exception
     */
    public function __get($key)
    {
        if (! in_array($key, static::$proxies)) {
            throw new \Exception("Property [{$key}] does not exist on this collection instance.");
        }

        return new HigherOrderCollectionProxy($this, $key);
    }
}
