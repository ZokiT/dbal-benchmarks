<?php

namespace App\Benchmark;

use JetBrains\PhpStorm\Pure;

class Benchmark
{
    const DEFAULT_ITERATIONS = 1000; // Adjust the number of iterations as needed
    private array $methods   = [];
    private int $iterations  = self::DEFAULT_ITERATIONS;
    /**
     * @var string[]
     */
    private array $headers;
    private mixed $maxLength;

    public function __construct()
    {
        $this->headers = ['Method', 'Avg. Execution Time (ms)', 'Avg. Memory Usage (KB)', 'Hits', 'Misses'];
        $this->maxLength = max(array_map('strlen', $this->headers));
    }

    public function addMethod($methodName, $callback, $setUpCallback = null, $handleException = null) {

        if (strlen($methodName) > $this->getMaxLength()) {
            $this->setMaxLength(strlen($methodName));
        }

        $this->methods[$methodName] = [
            'callback'        => $callback,
            'setUpCallback'   => $setUpCallback,
            'handleException' => $handleException
        ];
    }

    public function run() {

        $table = $this->prepareTableHeaders();

        foreach ($this->methods as $methodName => $method) {

            echo "Starting {$methodName} \n";
            sleep(1);
            gc_collect_cycles(); // garbage collector to have precise memory consumption

            $sharedObject = null;

            if ($method['setUpCallback']) {
                // Pass the shared object explicitly to the setUpCallback function
                $sharedObject = call_user_func($method['setUpCallback']);
            }

            $totalTime = 0;
            $miss = 0;
            $totalMemory = 0;

            for ($i = 0; $i < $this->iterations; $i++) {

                $startingMemory = memory_get_usage();
                $timeStart = microtime(true);
                // Conditionally use the shared object based on its availability
                try {
                    if ($sharedObject !== null) {
                        call_user_func($method['callback'], $sharedObject);
                    } else {
                        call_user_func($method['callback']);
                    }
                } catch (\Throwable $throwable) {
                    if ($method['handleException']) {
                        call_user_func($method['handleException'], $throwable);
                    }

                    $miss++;
                }

                $timeEnd = microtime(true);
                $usedMemory = memory_get_usage();
                $totalTime += ($timeEnd - $timeStart) * 1000; // milliseconds;
                $totalMemory += $usedMemory - $startingMemory;
            }

            $averageExecutionTime = round($totalTime / $this->iterations, 6);
            $averageMemoryUsage = round($totalMemory / $this->iterations / 1024, 6); // in kilobytes

            $table .= sprintf(
                $this->prepareTableFormat(),
                $methodName,
                $averageExecutionTime,
                $averageMemoryUsage,
                $this->getIterations() - $miss,
                $miss
            );

            echo "Done $methodName \n";
            unset($this->methods[$methodName]);
        }

        echo $table;
    }

    private function getMaxLength()
    {
        return $this->maxLength;
    }

    private function setMaxLength($length)
    {
        $this->maxLength = $length;
    }

    #[Pure] private function prepareTableHeaders(): string
    {
        $headers = sprintf($this->prepareTableFormat(), $this->headers[0], $this->headers[1], $this->headers[2], $this->headers[3], $this->headers[4]);
        $headers .= sprintf(
            $this->prepareTableFormat(),
            str_repeat('-', $this->getMaxLength()),
            str_repeat('-', $this->getMaxLength()),
            str_repeat('-', $this->getMaxLength()),
            str_repeat('-', $this->getMaxLength()),
            str_repeat('-', $this->getMaxLength())
        );

        return $headers;
    }

    #[Pure] private function prepareTableFormat(): string
    {
        $headersFormat = '';
        foreach ($this->headers as $ignored) {
            $headersFormat .= " %-{$this->getMaxLength()}s |";
        }

        return "| {$headersFormat}\n";
    }

    private function getIterations(): int
    {
        return $this->iterations;
    }

    public function setIterations(int $n)
    {
        $this->iterations = $n > 0 ? $n : self::DEFAULT_ITERATIONS;
    }
}