<?php

namespace App\Benchmark\Commands;

use App\codeIgniter\CodeIgniterConnection;
use App\codeIgniter\CodeIgniterQueryBuilder;
use App\laminas\LaminasQueryBuilderConnection;
use App\laminas\LaminasQueryBuilder;
use App\laravel\EloquentQueryBuilderConnection;
use App\laravel\EloquentQueryBuilder;
use App\pdo\PDOConnection;
use App\pdo\PDOQueries;
use App\symfony\DoctrineQueryBuilderConnection;
use App\symfony\DoctrineQueryBuilder;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class QueryBuilderSelect extends AbstractCommand
{

    protected function configure(): void
    {
        parent::configure();
        $this
            ->setName('queryBuilderSelect')
            ->setDescription('Benchmark the select of query builders libraries.')
            ->setHelp('this command will provide a table output of the performance of query builder select from the database')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {

        $this->getBenchmark()->addMethod(
            'doctrine',
            [DoctrineQueryBuilder::class, 'select'],
            [DoctrineQueryBuilderConnection::class, 'connect']
        );

        $this->getBenchmark()->addMethod(
            'eloquent',
            [EloquentQueryBuilder::class, 'select'],
            [EloquentQueryBuilderConnection::class, 'connect']
        );

        $this->getBenchmark()->addMethod(
            'pdo',
            [PDOQueries::class, 'select'],
            [PDOConnection::class, 'connect']
        );

        $this->getBenchmark()->addMethod(
            'laminas',
            [LaminasQueryBuilder::class, 'select'],
            [LaminasQueryBuilderConnection::class, 'connect']
        );

        $this->getBenchmark()->addMethod(
            'codeIgniter',
            [CodeIgniterQueryBuilder::class, 'select'],
            [CodeIgniterConnection::class, 'connect']
        );

        return parent::execute($input, $output);
    }

}
