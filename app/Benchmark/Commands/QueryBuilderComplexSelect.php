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

class QueryBuilderComplexSelect extends AbstractCommand
{

    protected function configure(): void
    {
        parent::configure();
        $this
            ->setName('queryBuilderComplexSelect')
            ->setDescription('Benchmark the select of query builders libraries with complex query.')
            ->setHelp('this command will provide a table output of the performance of query builder select from the database about more complex query')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {

        $this->getBenchmark()->addMethod(
            'doctrine',
            [DoctrineQueryBuilder::class, 'complexQuerySelect'],
            [DoctrineQueryBuilderConnection::class, 'connect']
        );

        $this->getBenchmark()->addMethod(
            'eloquent',
            [EloquentQueryBuilder::class, 'complexQuerySelect'],
            [EloquentQueryBuilderConnection::class, 'connect']
        );

        $this->getBenchmark()->addMethod(
            'pdo',
            [PDOQueries::class, 'complexQuerySelect'],
            [PDOConnection::class, 'connect']
        );

        $this->getBenchmark()->addMethod(
            'laminas',
            [LaminasQueryBuilder::class, 'complexQuerySelect'],
            [LaminasQueryBuilderConnection::class, 'connect']
        );

        $this->getBenchmark()->addMethod(
            'codeIgniter',
            [CodeIgniterQueryBuilder::class, 'complexQuerySelect'],
            [CodeIgniterConnection::class, 'connect']
        );

        return parent::execute($input, $output);
    }

}
