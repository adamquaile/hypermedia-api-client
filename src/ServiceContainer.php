<?php

namespace AdamQuaile\HypermediaApiClient;

class ServiceContainer
{
    private $services = [];

    public function track($service)
    {
        $this->services[] = $service;
    }

    public function findInstancesOf(string $classOrInterface)
    {
        foreach ($this->services as $service) {
            if ($service instanceof $classOrInterface) {
                yield $service;
            }
        }
    }
}