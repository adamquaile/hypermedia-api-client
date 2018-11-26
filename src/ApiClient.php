<?php

declare(strict_types=1);

namespace AdamQuaile\HypermediaApiClient;

use AdamQuaile\HypermediaApiClient\Extensions\Auth\AuthExtension;
use AdamQuaile\HypermediaApiClient\Extensions\Deserialisation\DeserialisationExtension;
use AdamQuaile\HypermediaApiClient\Extensions\Extension;
use AdamQuaile\HypermediaApiClient\Extensions\Hypermedia\HypermediaExtension;
use AdamQuaile\HypermediaApiClient\Extensions\Iteration\IterationExtension;
use AdamQuaile\HypermediaApiClient\Extensions\Json\JsonExtension;
use AdamQuaile\HypermediaApiClient\Extensions\GraphBuilding\GraphBuildingExtension;
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

    public function __construct(array $protocols, EventDispatcher $eventDispatcher, array $plugins = [])
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->protocols = $protocols;
        $this->container = new ServiceContainer();

        if (empty($plugins)) {
            $plugins = [
                new AuthExtension(),
                new DeserialisationExtension(),
                new GraphBuildingExtension(),
                new HypermediaExtension(),
                new IterationExtension(),
                new JsonExtension(),
            ];
        }
        $this->pluginsToInitialise = $plugins;
    }

    public function loadFromUri(string $uri)
    {
        while (!empty($this->pluginsToInitialise)) {
            $plugin = array_shift($this->pluginsToInitialise);
            $plugin->initialise($this, $this->eventDispatcher, $this->container);
            $this->container->track($plugin);
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