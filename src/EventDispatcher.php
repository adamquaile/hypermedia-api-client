<?php

namespace AdamQuaile\HypermediaApiClient;

class EventDispatcher
{
    private $listeners = [];

    public function register(string $name, callable $callback)
    {
        if (!array_key_exists($name, $this->listeners)) {
            $this->listeners[$name] = [];
        }
        $this->listeners[$name][] = $callback;
    }

    public function dispatch(string $name, $event)
    {
        if (!array_key_exists($name, $this->listeners)) {
            $this->listeners[$name] = [];
        }
        foreach ($this->listeners[$name] as $listener) {
            $listener($event);
        }
    }
}