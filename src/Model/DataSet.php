<?php

namespace AdamQuaile\HypermediaApiClient\Model;

class DataSet
{
    /**
     * @var array
     */
    private $data;

    public function __construct()
    {
        $a = 1;
    }

    public function set(string $key, $data)
    {
        $this->data[$key] = $data;
    }

    public function get(string $key)
    {
        return $this->data[$key];
    }

    public function has(string $key)
    {
        return array_key_exists($key, $this->data);
    }
}