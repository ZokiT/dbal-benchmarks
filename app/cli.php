<?php
require __DIR__ . '/vendor/autoload.php';

use App\Benchmark\Benchmark;
use App\Benchmark\Commands\HashAlgorithm;
use App\Benchmark\Commands\ORMDelete;
use App\Benchmark\Commands\ORMInsert;
use App\Benchmark\Commands\ORMSelect;
use App\Benchmark\Commands\ORMUpdate;
use App\Benchmark\Commands\QueryBuilderDelete;
use App\Benchmark\Commands\QueryBuilderInsert;
use App\Benchmark\Commands\QueryBuilderSelect;
use App\Benchmark\Commands\QueryBuilderUpdate;
use Symfony\Component\Console\Application;

try {

    ini_set('memory_limit', '512M');

    $benchmark = new Benchmark();
    $application = new Application();

    $application->add(new QueryBuilderInsert($benchmark));
    $application->add(new ORMInsert($benchmark));
    $application->add(new QueryBuilderSelect($benchmark));
    $application->add(new ORMSelect($benchmark));
    $application->add(new QueryBuilderUpdate($benchmark));
    $application->add(new ORMUpdate($benchmark));
    $application->add(new QueryBuilderDelete($benchmark));
    $application->add(new ORMDelete($benchmark));

    // register other commands here
    $application->add(new HashAlgorithm($benchmark));

    $application->run();
} catch (Exception $e) {
    echo "{$e->getMessage()}";
}