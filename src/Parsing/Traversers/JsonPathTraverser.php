<?php

namespace AdamQuaile\HypermediaApiClient\Parsing\Traversers;

use AdamQuaile\HypermediaApiClient\Parsing\PathTraverser;
use Flow\JSONPath\JSONPath;

class JsonPathTraverser implements PathTraverser
{
    /**
     * @var string
     */
    private $path;

    public function __construct(string $path)
    {
        if (!class_exists(JSONPath::class)) {
            throw new \LogicException("Package flow/jsonpath is required for json path traversal");
        }
        $this->path = $path;
    }

    public function iterate($data, bool $yieldWholePath = false)
    {
        foreach ($this->extract($data) as $key => $value) {
            if ($yieldWholePath) {
                $key = $this->path . ".$key";
            }
                yield $key => $value;
        }
        return (new JSONPath($data))->find($this->path)->data();
    }

    public function extract($data)
    {
        $data = (new JSONPath($data))->find($this->path)->data();
        if (!is_array($data) || !isset($data[0])) {
            throw new \LogicException("Could not extract {$this->path} from: " . \json_encode($data));
        }
        return $data[0];
    }
}