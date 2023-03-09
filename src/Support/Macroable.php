<?php

namespace Imran\Collection\Support;

use BadMethodCallException;

trait Macroable
{
    protected static array $macros = [];
    /**
     * Dynamically handle calls to the class.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     *
     * @throws \BadMethodCallException
     */
    public static function __callStatic($method, $parameters)
    {
        if (! static::hasMacro($method)) {
            throw new BadMethodCallException(sprintf(
                 'Method %s::%s does not exist.', static::class, $method
             ));
        }

        $macro = static::$macros[$method];

        if ($macro instanceof \Closure) {
            $macro = $macro->bindTo(null, static::class);
        }

        return $macro(...$parameters);
    }
    /**
     * Dynamically handle calls to the class.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     *
     * @throws \BadMethodCallException
     */
    public function __call($method, $parameters)
    {
        if (! static::hasMacro($method)) {
            throw new BadMethodCallException(sprintf(
                 'Method %s::%s does not exist.', static::class, $method
             ));
        }

        $macro = static::$macros[$method];

        if ($macro instanceof \Closure) {
            $macro = $macro->bindTo($this, static::class);
        }

        return $macro(...$parameters);
    }
    /**
     * Register a custom macro.
     *
     * @param  string  $name
     * @param  callable  $macro
     * @return void
     */
    public static function macro($name, callable $macro)
    {
        static::$macros[$name] = $macro;
    }
    /**
     * Checks if macro is registered.
     *
     * @param  string  $name
     * @return bool
     */
    public static function hasMacro($name)
    {
        return isset(static::$macros[$name]);
    }

    /**
     * Flush the existing macros.
     *
     * @return void
     */
    public static function flushMacros()
    {
        static::$macros = [];
    }
}
