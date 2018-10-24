<?php

namespace AdamQuaile\HypermediaApiClient\Extensions\Hypermedia\Links\Parsers;

use AdamQuaile\HypermediaApiClient\Model\DataSet;
use AdamQuaile\HypermediaApiClient\Parsing\PathTraverser;
use AdamQuaile\HypermediaApiClient\Extensions\Hypermedia\Links\Link;
use AdamQuaile\HypermediaApiClient\Extensions\Hypermedia\Links\LinkParser;
use Psr\Http\Message\ResponseInterface;

class ResponseBodyLinkParser implements LinkParser
{
    const NAME_FROM_KEY     = 1;
    const NAME_FROM_PATH    = 2;
    /**
     * @var PathTraverser
     */
    private $linkIterator;
    /**
     * @var PathTraverser
     */
    private $uriLocator;
    private $namePathOrMode;


    public function __construct(PathTraverser $linkIterator, PathTraverser $uriLocator, $namePathOrMode)
    {
        $this->linkIterator = $linkIterator;
        $this->uriLocator = $uriLocator;
        $this->namePathOrMode = $namePathOrMode;
    }

    /**
     * @return Link[]
     */
    public function parseLinks(ResponseInterface $response, DataSet $dataSet): \Traversable
    {
        $yieldWholePath = ($this->namePathOrMode === 2);

        $linksToIterate = $this->linkIterator->iterate($dataSet->get('deserialised'), $yieldWholePath);
        foreach ($linksToIterate as $key => $value) {
            yield new Link(
                $this->uriLocator->extract($value),
                $this->getLinkName($key, $value)
            );
        }
    }

    private function getLinkName(string $key, $linkData)
    {
        if ($this->namePathOrMode instanceof PathTraverser) {
            return $this->namePathOrMode->extract($linkData);
        }

        return $key;
    }
}