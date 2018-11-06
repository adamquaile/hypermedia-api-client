<?php

declare(strict_types=1);

namespace AdamQuaile\HypermediaApiClient\Model;

use AdamQuaile\HypermediaApiClient\ApiClient;
use AdamQuaile\HypermediaApiClient\Protocols\Protocol;
use AdamQuaile\HypermediaApiClient\Exceptions\ResourceIterationNotDefined;
use AdamQuaile\HypermediaApiClient\Model\DataSet;
use Traversable;

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
    private $links;

    /**
     * @var ?\Iterator
     */
    private $iterator;

    public function __construct(ApiClient $client, string $uri, DataSet $data, $links)
    {
        $this->client = $client;
        $this->uri = $uri;
        $this->data = $data;
        $this->links = $links;
    }

    public function __call(string $key, array $arguments)
    {
        if (isset($this->data->$key)) {
            return $this->data->$key;
        }
        if (isset($this->links->$key)) {
            return $this->followLink($key);
        }

        throw new \InvalidArgumentException("No such key $key");
    }

    public function getData(): DataSet
    {
        return $this->data;
    }

    public function hasLink(string $name)
    {
        return isset($this->links->$name);
    }

    public function followLink(string $name)
    {
        return $this->client->loadFromUri($this->links->$name->getUri());
    }

    public function map(callable $mapFunction)
    {
        foreach ($this->getIterator() as $resource) {
            yield $mapFunction($resource);
        }
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