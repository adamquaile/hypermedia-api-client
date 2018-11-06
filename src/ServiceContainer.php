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
        $instances = [];
        foreach ($this->services as $service) {
            if ($service instanceof $classOrInterface) {
                $instances[] = $service;
            }
        }
        return $instances;
    }
}