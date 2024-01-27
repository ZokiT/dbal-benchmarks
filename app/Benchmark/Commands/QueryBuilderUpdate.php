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

class QueryBuilderUpdate extends AbstractCommand
{

    protected function configure(): void
    {
        parent::configure();
        $this
            ->setName('queryBuilderUpdate')
            ->setDescription('Benchmark the update of query builders libraries.')
            ->setHelp('this command will provide a table output of the performance of query builder update in to the database')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {

        $this->getBenchmark()->addMethod(
            'doctrine',
            [DoctrineQueryBuilder::class, 'update'],
            [DoctrineQueryBuilderConnection::class, 'connectForUpdate']
        );

        $this->getBenchmark()->addMethod(
            'eloquent',
            [EloquentQueryBuilder::class, 'update'],
            [EloquentQueryBuilderConnection::class, 'connectForUpdate']
        );

        $this->getBenchmark()->addMethod(
            'pdo',
            [PDOQueries::class, 'update'],
            [PDOConnection::class, 'connectForUpdate']
        );

        $this->getBenchmark()->addMethod(
            'laminas',
            [LaminasQueryBuilder::class, 'update'],
            [LaminasQueryBuilderConnection::class, 'connectForUpdate']
        );

        $this->getBenchmark()->addMethod(
            'codeIgniter',
            [CodeIgniterQueryBuilder::class, 'update'],
            [CodeIgniterConnection::class, 'connectForUpdate']
        );

        return parent::execute($input, $output);
    }

}
