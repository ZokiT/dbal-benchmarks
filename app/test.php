<?php
require_once __DIR__ . '/vendor/autoload.php';

use app\Benchmark;

// Example usage:

// Function to test strtoupper
function strtoupperTest() {
    // Assuming you have some data to hash
    $data = 'Hello, World!';
    $strToUpper = strtoupper($data);

    return $strToUpper;
    // Optionally, you can use the hash result for something
}

// Function to test strtolower
function strtolowerTest() {
    // Assuming you have some data to hash
    $data = 'Hello, World!';
    $hash = strtolower($data);
    return $hash;
    // Optionally, you can use the hash result for something
}

// Create an instance of the Benchmark class
$benchmark = new Benchmark();

// Add methods to benchmark with their respective setup callbacks
$benchmark->addMethod('StrToUpper','strtoupperTest', function () {
    // Setup logic for MD2, if needed
});

$benchmark->addMethod('StrToLower', 'strtolowerTest', function () {
    // Setup logic for MD4, if needed
});

// Run the benchmark
$benchmark->run();