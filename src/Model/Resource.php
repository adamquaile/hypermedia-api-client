<?php

declare(strict_types=1);

namespace AdamQuaile\HypermediaApiClient\Model;

use AdamQuaile\HypermediaApiClient\ApiClient;
use AdamQuaile\HypermediaApiClient\Exceptions\ResourceIterationNotDefined;
use AdamQuaile\HypermediaApiClient\Extensions\Hypermedia\Links\Link;

class Resource implements \IteratorAggregate
{
    /**
     * @var ApiClient
     */
    private $client;
    /**
     * @var string
     */
    private $uri;
    private $data;
    private $graph;

    /**
     * @var ?\Iterator
     */
    private $iterator;

    public function __construct(ApiClient $client, string $uri, AttributeBag $data, Graph $graph)
    {
        $this->client = $client;
        $this->uri = $uri;
        $this->data = $data;
        $this->graph = $graph;
    }

    public function getData(): AttributeBag
    {
        return $this->data;
    }

    public function setGraph(Graph $graph): void
    {
        $this->graph = $graph;
    }

    public function getGraph(): Graph
    {
        return $this->graph;
    }

    public function hasLink(string $name)
    {
        return $this->graph->hasEdge($name) && $this->graph->getEdge($name) instanceof Link;
    }

    public function followLink(string $name)
    {
        if (!$this->hasLink($name)) {
            throw new \LogicException("No such link $name");
        }

        return $this->client->loadFromUri($this->graph->getEdge($name)->getUri());
    }

    public function map(callable $mapFunction)
    {
        foreach ($this->getIterator() as $resource) {
            yield $mapFunction($resource);
        }
    }

    public function __call(string $key, array $arguments)
    {
        if ($this->graph->hasEdge($key)) {
            $node = $this->graph->getEdge($key);
            if ($node instanceof Link) {
                return $this->client->loadFromUri($node->getUri());
            }

            return $node;
        }

        throw new \InvalidArgumentException("No such key $key");
    }

    public function setIterator(\Iterator $iterator)
    {
        $this->iterator = $iterator;
    }

    public function getIterator()
    {
        if (is_null($this->iterator)) {
            throw new ResourceIterationNotDefined($this);
        }

        return $this->iterator;
    }

    public function __toString()
    {
        return $this->uri;
    }
}