<?php

namespace App\Benchmark;

use App\DatabaseConfig;
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
        'Hits',
        'Misses'
    ];

    private array $results = [];
    private bool $outputToImage = false;

    public function addMethod($methodName, $callback, $setUpCallback = null, $handleException = null) {
        $this->methods[$methodName] = [
            'callback'        => $callback,
            'setUpCallback'   => $setUpCallback,
            'handleException' => $handleException
        ];
    }

    public function run(OutputInterface $output) {

        foreach ($this->methods as $methodName => $method) {

            $output->writeln("Starting {$methodName}");
            sleep(1);
            gc_collect_cycles(); // garbage collector to have precise memory consumption

            $sharedObject = null;

            $config = new DatabaseConfig();
            if ($method['setUpCallback']) {
                // Pass the shared object explicitly to the setUpCallback function
                $sharedObject = call_user_func($method['setUpCallback'], $config);
            }

            $totalTime = 0;
            $miss = 0;
            $startingMemory = memory_get_usage();

            for ($i = 0; $i < $this->iterations; $i++) {

                $timeStart = microtime(true);
                // Conditionally use the shared object based on its availability
                try {
                    if ($sharedObject !== null) {
                        @call_user_func($method['callback'], $sharedObject);
                    } else {
                        @call_user_func($method['callback']);
                    }
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
                $this->getIterations() - $miss,
                $miss
            );

            $output->writeln("Done $methodName");
            unset($this->methods[$methodName]);
        }
    }

    private function getIterations(): int
    {
        return $this->iterations;
    }

    public function setIterations(int $n)
    {
        $this->iterations = $n > 0 ? $n : self::DEFAULT_ITERATIONS;
    }

    private function addResult(string $methodName, float $averageExecutionTime, float $averageMemoryUsage, int $param, int $miss)
    {
        $this->results[$methodName] = [
            $methodName, $averageExecutionTime, $averageMemoryUsage, $param, $miss
        ];
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
}