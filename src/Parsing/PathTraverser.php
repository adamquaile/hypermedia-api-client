<?php

namespace AdamQuaile\HypermediaApiClient\Parsing;

interface PathTraverser
{
    public function iterate($data, bool $yieldWholePath = false);
    public function extract($data);
}