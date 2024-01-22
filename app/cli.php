<?php
require __DIR__ . '/vendor/autoload.php';

use App\Benchmark\Benchmark;
use App\Benchmark\Commands\HashAlgorithm;
use App\Benchmark\Commands\ORMInsert;
use App\Benchmark\Commands\QueryBuilderInsert;
use App\Benchmark\Commands\QueryBuilderSelect;
use Symfony\Component\Console\Application;

try {

    $benchmark = new Benchmark();
    $application = new Application();

    $application->add(new QueryBuilderInsert($benchmark));
    $application->add(new ORMInsert($benchmark));
    $application->add(new QueryBuilderSelect($benchmark));

    // register other commands here
    $application->add(new HashAlgorithm($benchmark));

    $application->run();
} catch (Exception $e) {
    echo "{$e->getMessage()}";
}