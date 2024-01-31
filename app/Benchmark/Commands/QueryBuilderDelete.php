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

class QueryBuilderDelete extends AbstractCommand
{

    protected function configure(): void
    {
        parent::configure();
        $this
            ->setName('queryBuilderDelete')
            ->setDescription('Benchmark the delete of query builders libraries.')
            ->setHelp('this command will provide a table output of the performance of query builder delete from the database')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {

        $this->getBenchmark()->addMethod(
            'doctrine',
            [DoctrineQueryBuilder::class, 'delete'],
            [DoctrineQueryBuilderConnection::class, 'prepareForDelete']
        );

        $this->getBenchmark()->addMethod(
            'eloquent',
            [EloquentQueryBuilder::class, 'delete'],
            [EloquentQueryBuilderConnection::class, 'prepareForDelete']
        );

        $this->getBenchmark()->addMethod(
            'pdo',
            [PDOQueries::class, 'delete'],
            [PDOConnection::class, 'prepareForDelete']
        );

        $this->getBenchmark()->addMethod(
            'laminas',
            [LaminasQueryBuilder::class, 'delete'],
            [LaminasQueryBuilderConnection::class, 'prepareForDelete']
        );

        $this->getBenchmark()->addMethod(
            'codeIgniter',
            [CodeIgniterQueryBuilder::class, 'delete'],
            [CodeIgniterConnection::class, 'prepareForDelete']
        );

        return parent::execute($input, $output);
    }

}
