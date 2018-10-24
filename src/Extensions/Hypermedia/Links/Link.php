<?php

namespace AdamQuaile\HypermediaApiClient\Extensions\Hypermedia\Links;

class Link
{
    /**
     * @var string
     */
    private $uri;
    /**
     * @var string
     */
    private $name;

    public function __construct(string $uri, string $name)
    {
        $this->uri = $uri;
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }
}