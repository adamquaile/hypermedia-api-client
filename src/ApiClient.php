<?php

declare(strict_types=1);

namespace AdamQuaile\HypermediaApiClient;

use AdamQuaile\HypermediaApiClient\Extensions\Extension;
use AdamQuaile\HypermediaApiClient\Protocols\Http\HttpProtocol;
use AdamQuaile\HypermediaApiClient\Exceptions\MalformedUriException;
use AdamQuaile\HypermediaApiClient\Exceptions\UnsupportedProtocolException;
use AdamQuaile\HypermediaApiClient\Protocols\Protocol;
use Http\Client\HttpClient;

class ApiClient
{
    /**
     * @var EventDispatcher
     */
    private $eventDispatcher;

    /**
     * @var Extension[]
     */
    private $pluginsToInitialise = [];
    /**
     * @var Protocol[]
     */
    private $protocols;
    private $container;

    public function __construct(array $protocols, EventDispatcher $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->protocols = $protocols;
        $this->container = new ServiceContainer();
    }

    public function loadFromUri(string $uri)
    {
        while (!empty($this->pluginsToInitialise)) {
            $plugin = array_shift($this->pluginsToInitialise);
            $plugin->initialise($this, $this->eventDispatcher, $this->container);
        }
        if (false === $protocolStringLength = strpos($uri, '://')) {
            throw new MalformedUriException($uri);
        }

        $protocolString = substr($uri, 0, $protocolStringLength);

        foreach ($this->protocols as $protocol) {
            if ($protocol->supportsProtocolString($protocolString)) {
                return $protocol->loadFromUri($uri, $this);
            }
        }

        throw new UnsupportedProtocolException($protocolString);
    }

    public function getEventDispatcher(): EventDispatcher
    {
        return $this->eventDispatcher;
    }

    public function getServiceContainer(): ServiceContainer
    {
        return $this->container;
    }

    public function addExtension(Extension $plugin)
    {
        $this->container->track($plugin);
        $this->pluginsToInitialise[] = $plugin;
    }
}