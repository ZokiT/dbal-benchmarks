<?php

namespace App\Benchmark\Commands;

use App\codeIgniter\CodeIgniterConnection;
use App\codeIgniter\CodeIgniterQueryBuilderInsert;
use App\laminas\LaminasQueryBuilderConnection;
use App\laminas\LaminasQueryBuilderInsert;
use App\laravel\EloquentQueryBuilderConnection;
use App\laravel\EloquentQueryBuilderInsert;
use App\pdo\PDOConnection;
use App\pdo\PDOInsert;
use App\symfony\DoctrineQueryBuilderConnection;
use App\symfony\DoctrineQueryBuilderInsert;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class QueryBuilderInsert extends AbstractCommand
{

    protected function configure(): void
    {
        parent::configure();
        $this
            ->setName('queryBuilderInserts')
            ->setDescription('Benchmark the inserts of query builders libraries.')
            ->setHelp('this command will provide a table output of the performance of query builder inserts in to the database')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {

        $this->getBenchmark()->addMethod(
            'doctrine query builder insert',
            [DoctrineQueryBuilderInsert::class, 'insert'],
            [DoctrineQueryBuilderConnection::class, 'connect']
        );

        $this->getBenchmark()->addMethod(
            'eloquent query builder insert',
            [EloquentQueryBuilderInsert::class, 'insert'],
            [EloquentQueryBuilderConnection::class, 'connect']
        );

        $this->getBenchmark()->addMethod(
            'pdo insert',
            [PDOInsert::class, 'insert'],
            [PDOConnection::class, 'connect']
        );

        $this->getBenchmark()->addMethod(
            'laminas insert',
            [LaminasQueryBuilderInsert::class, 'insert'],
            [LaminasQueryBuilderConnection::class, 'connect']
        );

        $this->getBenchmark()->addMethod(
            'codeIgniter insert',
            [CodeIgniterQueryBuilderInsert::class, 'insert'],
            [CodeIgniterConnection::class, 'connect']
        );

        return parent::execute($input, $output);
    }

}
