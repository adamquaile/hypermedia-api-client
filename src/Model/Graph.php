<?php

namespace AdamQuaile\HypermediaApiClient\Model;

use Traversable;

class Graph implements \IteratorAggregate
{
    private $edges = [];

    public function __get(string $key)
    {
        if ($this->hasEdge($key)) {
            return $this->getEdge($key);
        }
        throw new \LogicException("No such field $key");
    }

    public function addEdge($edgeName, $node)
    {
        $this->edges[$edgeName] = $node;
    }

    public function hasEdge($edgeName)
    {
        return array_key_exists($edgeName, $this->edges);
    }

    public function getEdge($edgeName)
    {
        return $this->edges[$edgeName];
    }

    /**
     * Retrieve an external iterator
     * @link https://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     * @since 5.0.0
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->edges);
    }
}