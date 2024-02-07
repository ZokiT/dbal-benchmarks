<?php

namespace App\Benchmark;

use Symfony\Component\Console\Output\OutputInterface;

class Benchmark
{
    const DEFAULT_ITERATIONS = 1000;

    private array $methods   = [];

    private int $iterations  = self::DEFAULT_ITERATIONS;

    const TABLE_HEADERS      = [
        'Method',
        'Avg. Execution Time (ms)',
        'Memory Usage (KB)',
        'Iterations',
        'Misses'
    ];

    private array $headers        = self::TABLE_HEADERS;
    private array $results        = [];
    private bool $outputToImage   = false;
    private bool $storeToFile     = false;
    private int $selectLimit      = 0;
    private bool $useSelectLimit  = false;

    public function addMethod($methodName, $callback, $setUpCallback = null, $handleException = null) {
        $this->methods[$methodName] = [
            'callback'        => $callback,
            'setUpCallback'   => $setUpCallback,
            'handleException' => $handleException
        ];
    }

    public function run(OutputInterface $output) {

        if ($this->getUseLimit()) {
            $this->addHeader('Select Limit');
        }

        foreach ($this->methods as $methodName => $method) {

            $output->writeln("Starting {$methodName}");
            gc_collect_cycles(); // garbage collector to have precise memory consumption

            $startingMemory = memory_get_usage();

            $params = new Params();
            $params->addParam('selectLimit', $this->getSelectLimit());
            $params->addParam('iterations', $this->getIterations());

            if ($method['setUpCallback']) {
                // Pass the shared objects explicitly to the setUpCallback function
                $params = call_user_func($method['setUpCallback'], $params);
            }

            $totalTime = 0;
            $miss = 0;
            for ($i = 0; $i < $this->iterations; $i++) {

                $timeStart = microtime(true);
                // Conditionally use the shared object based on its availability
                try {
                    $params = @call_user_func($method['callback'], $params);
                } catch (\Throwable $throwable) {
                    if ($method['handleException']) {
                        call_user_func($method['handleException'], $throwable);
                    }

                    $miss++;
                }

                $timeEnd = microtime(true);
                $totalTime += ($timeEnd - $timeStart) * 1000; // milliseconds;
            }

            $usedMemory = memory_get_usage();
            $averageExecutionTime = round($totalTime / $this->iterations, 6);
            $memoryUsage = round(($usedMemory - $startingMemory) / 1024, 6); // in kilobytes

            $this->addResult(
                $methodName,
                $averageExecutionTime,
                $memoryUsage,
                $this->getIterations(),
                $miss,
                $this->getSelectLimit()
            );

            $output->writeln("Done $methodName");
            unset($this->methods[$methodName]);
            unset($params);
        }
    }

    public function getIterations(): int
    {
        return $this->iterations;
    }

    public function setIterations(int $n)
    {
        $this->iterations = $n > 0 ? $n : self::DEFAULT_ITERATIONS;
    }

    private function addResult(string $methodName, float $averageExecutionTime, float $averageMemoryUsage, int $hits, int $miss, int $selectLimit)
    {
        $this->results[$methodName] = [
            $methodName, $averageExecutionTime, $averageMemoryUsage, $hits, $miss
        ];

        if ($this->getUseLimit()) {
            $this->results[$methodName][] = $selectLimit;
        }
    }

    public function getResults(): array
    {
        return $this->results;
    }

    public function setOutputToImage(bool $outputToImage)
    {
        $this->outputToImage = $outputToImage;
    }

    public function getOutputToImage(): bool
    {
        return $this->outputToImage;
    }

    public function setStoreToFile(bool $storeToFile)
    {
        $this->storeToFile = $storeToFile;
    }

    public function getStoreToFile(): bool
    {
        return $this->storeToFile;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function addHeader($header)
    {
        $this->headers[] = $header;
    }

    public function setSelectLimit(int $limit)
    {
        $this->selectLimit = $limit;
    }

    public function getUseLimit(): bool {
        return $this->useSelectLimit;
    }

    public function getSelectLimit(): int
    {
        return $this->selectLimit > 0 ? $this->selectLimit : 10;
    }

    public function setUseLimit(bool $val)
    {
        $this->useSelectLimit = $val;
    }
}