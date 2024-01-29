<?php

namespace App\Benchmark;

class Params
{

    private array $params = [];

    public function addParam(string $key, mixed $value)
    {
        $this->params[$key] = $value;
    }

    public function getParam(string $key)
    {
        return $this->params[$key];
    }

    public function flash()
    {
        $this->params = [];
    }
}