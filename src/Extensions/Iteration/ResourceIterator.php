<?php

namespace AdamQuaile\HypermediaApiClient\Extensions\Iteration;

use AdamQuaile\HypermediaApiClient\Model\Resource;
use AdamQuaile\HypermediaApiClient\Parsing\PathTraverser;

class ResourceIterator implements \Iterator
{
    /**
     * @var Resource
     */
    private $resource;
    /**
     * @var Resource
     */
    private $firstResource;
    /**
     * @var callable
     */
    private $iteratorForItemsInResource;

    private $currentIndex = 0;
    private $currentResourceParsed = false;

    private $knownItems = [];

    public function __construct(Resource $resource, callable $iteratorForItemsInResource)
    {
        $this->resource = $resource;
        $this->firstResource = $resource;
        $this->iteratorForItemsInResource = $iteratorForItemsInResource;
    }

    /**
     * Return the current element
     * @link https://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     * @since 5.0.0
     */
    public function current()
    {
        if (!$this->currentResourceParsed) {
            $this->loadCurrentResource();
        }
        return $this->knownItems[$this->currentIndex];
    }

    /**
     * Move forward to next element
     * @link https://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function next()
    {
        $this->currentIndex++;
    }

    /**
     * Return the key of the current element
     * @link https://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function key()
    {
        return $this->currentIndex;
    }

    /**
     * Checks if current position is valid
     * @link https://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     * @since 5.0.0
     */
    public function valid()
    {
        if (!$this->currentResourceParsed) {
            $this->loadCurrentResource();
        }
        return array_key_exists($this->currentIndex, $this->knownItems);
    }

    /**
     * Rewind the Iterator to the first element
     * @link https://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function rewind()
    {
        $this->resource = $this->firstResource;
        $this->currentIndex = 0;
        $this->currentResourceParsed = false;
        $this->knownItems = [];
    }

    private function loadCurrentResource(): void
    {
        foreach (call_user_func($this->iteratorForItemsInResource, $this->resource) as $item) {
            $this->knownItems[] = $item;
        }
        $this->currentResourceParsed = true;
    }
}