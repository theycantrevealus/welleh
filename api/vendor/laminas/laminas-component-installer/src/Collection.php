<?php

declare(strict_types=1);

namespace Laminas\ComponentInstaller;

use ArrayAccess;
use ArrayIterator;
use Countable;
use InvalidArgumentException;
use IteratorAggregate;
use OutOfRangeException;
use ReturnTypeWillChange;
use Traversable;

use function array_key_exists;
use function array_merge;
use function array_unique;
use function array_unshift;
use function count;
use function is_array;
use function iterator_to_array;
use function sprintf;

class Collection implements
    ArrayAccess,
    Countable,
    IteratorAggregate
{
    /** @var array */
    protected $items;

    /**
     * @param iterable $items
     * @throws InvalidArgumentException
     */
    public function __construct($items)
    {
        if ($items instanceof Traversable) {
            $items = iterator_to_array($items);
        }

        if (! is_array($items)) {
            throw new InvalidArgumentException('Collections require arrays or Traversable objects');
        }

        $this->items = $items;
    }

    /**
     * Factory method
     *
     * @param iterable $items
     * @return static
     */
    public static function create($items): self
    {
        return new static($items);
    }

    /**
     * Cast collection to an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->items;
    }

    /**
     * Apply a callback to each item in the collection.
     *
     * @return self
     */
    public function each(callable $callback)
    {
        foreach ($this->items as $key => $item) {
            $callback($item, $key);
        }
        return $this;
    }

    /**
     * Reduce the collection to a single value.
     *
     * @param mixed $initial Initial value.
     * @return mixed
     */
    public function reduce(callable $callback, $initial = null)
    {
        $accumulator = $initial;

        foreach ($this->items as $key => $item) {
            $accumulator = $callback($accumulator, $item, $key);
        }

        return $accumulator;
    }

    /**
     * Filter the collection using a callback.
     *
     * Filter callback should return true for values to keep.
     *
     * @return static
     */
    public function filter(callable $callback)
    {
        return $this->reduce(function ($filtered, $item, $key) use ($callback) {
            if ($callback($item, $key)) {
                $filtered[$key] = $item;
            }
            return $filtered;
        }, new static([]));
    }

    /**
     * Filter the collection using a callback; reject any items matching the callback.
     *
     * Filter callback should return true for values to reject.
     *
     * @return static
     */
    public function reject(callable $callback)
    {
        return $this->reduce(function ($filtered, $item, $key) use ($callback) {
            if (! $callback($item, $key)) {
                $filtered[$key] = $item;
            }
            return $filtered;
        }, new static([]));
    }

    /**
     * Transform each value in the collection.
     *
     * Callback should return the new value to use.
     *
     * @return static
     */
    public function map(callable $callback)
    {
        return $this->reduce(function ($results, $item, $key) use ($callback) {
            $results[$key] = $callback($item, $key);
            return $results;
        }, new static([]));
    }

    /**
     * Return a new collection containing only unique items.
     *
     * @return static
     */
    public function unique()
    {
        return new static(array_unique($this->items));
    }

    /**
     * Merge an array of values with the current collection.
     *
     * @param array $values
     * @return Collection
     */
    public function merge(array $values)
    {
        $this->items = array_merge($this->items, $values);
        return $this;
    }

    /**
     * Prepend a value to the collection.
     *
     * @param mixed $value
     * @return Collection
     */
    public function prepend($value)
    {
        array_unshift($this->items, $value);
        return $this;
    }

    /**
     * ArrayAccess: isset()
     *
     * @param string|int $offset
     * @return bool
     */
    #[ReturnTypeWillChange]
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->items);
    }

    /**
     * ArrayAccess: retrieve by key
     *
     * @param string|int $offset
     * @return mixed
     * @throws OutOfRangeException
     */
    #[ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        if (! $this->offsetExists($offset)) {
            throw new OutOfRangeException(sprintf(
                'Offset %s does not exist in the collection',
                $offset
            ));
        }

        return $this->items[$offset];
    }

    /**
     * ArrayAccess: set by key
     *
     * If $offset is null, pushes the item onto the stack.
     *
     * @param string|int|null $offset
     * @param mixed $value
     * @return void
     */
    #[ReturnTypeWillChange]
    public function offsetSet($offset, $value)
    {
        if (null === $offset) {
            $this->items[] = $value;
            return;
        }

        $this->items[$offset] = $value;
    }

    /**
     * ArrayAccess: unset()
     *
     * @param string|int $offset
     * @return void
     */
    #[ReturnTypeWillChange]
    public function offsetUnset($offset)
    {
        if ($this->offsetExists($offset)) {
            unset($this->items[$offset]);
        }
    }

    /**
     * Countable: number of items in the collection.
     *
     * @return int
     */
    #[ReturnTypeWillChange]
    public function count()
    {
        return count($this->items);
    }

    /**
     * Is the collection empty?
     *
     * @return bool
     */
    public function isEmpty()
    {
        return 0 === $this->count();
    }

    /**
     * Traversable: Iterate the collection.
     *
     * @return ArrayIterator
     */
    #[ReturnTypeWillChange]
    public function getIterator()
    {
        return new ArrayIterator($this->items);
    }
}
