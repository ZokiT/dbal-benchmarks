<?php

namespace App\Benchmark\Commands;

use App\codeIgniter\CodeIgniterModel;
use App\codeIgniter\CodeIgniterConnection;
use App\laminas\LaminasModel;
use App\laminas\LaminasQueryBuilderConnection;
use App\laminas\LaminasSqlConnection;
use App\laravel\EloquentModelConnection;
use App\laravel\EloquentModel;
use App\symfony\DoctrineEntityManager;
use App\symfony\DoctrineModel;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ORMSelect extends AbstractCommand
{

    protected function configure(): void
    {
        parent::configure();
        $this
            ->setName('ORMSelect')
            ->setDescription('Benchmark the select of orm libraries.')
            ->setHelp('this command will provide a table output of the performance of orm select from the database')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {

        $this->getBenchmark()->addMethod(
            'doctrine dbal/orm',
            [DoctrineModel::class, 'select'],
            [DoctrineEntityManager::class, 'connect']
        );

        $this->getBenchmark()->addMethod(
            'eloquent',
            [EloquentModel::class, 'select'],
            [EloquentModelConnection::class, 'connect']
        );

        $this->getBenchmark()->addMethod(
            'laminas db/hydrator',
            [LaminasModel::class, 'select'],
            [LaminasSqlConnection::class, 'connect']
        );

        $this->getBenchmark()->addMethod(
            'codeIgniter',
            [CodeIgniterModel::class, 'select'],
            [CodeIgniterConnection::class, 'connect']
        );

        return parent::execute($input, $output);
    }

}
