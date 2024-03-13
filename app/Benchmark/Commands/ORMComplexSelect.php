<?php

namespace App\Benchmark\Commands;

use App\codeIgniter\CodeIgniterConnection;
use App\codeIgniter\CodeIgniterModel;
use App\laminas\LaminasORM;
use App\laminas\LaminasORMConnection;
use App\laravel\EloquentModel;
use App\laravel\EloquentModelConnection;
use App\symfony\DoctrineEntityManager;
use App\symfony\DoctrineModel;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ORMComplexSelect extends AbstractCommand
{

    protected function configure(): void
    {
        parent::configure();
        $this
            ->setName('ORMComplexSelect')
            ->setDescription('Benchmark the select of ORM libraries with complex query.')
            ->setHelp('this command will provide a table output of the performance of ORM select from the database about more complex query')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {

        $this->getBenchmark()->addMethod(
            'doctrine dbal/orm',
            [DoctrineModel::class, 'complexQuerySelect'],
            [DoctrineEntityManager::class, 'connect'],
        );

        $this->getBenchmark()->addMethod(
            'eloquent',
            [EloquentModel::class, 'complexQuerySelect'],
            [EloquentModelConnection::class, 'connect'],
        );

        $this->getBenchmark()->addMethod(
            'laminas db/hydrator',
            [LaminasORM::class, 'complexQuerySelect'],
            [LaminasORMConnection::class, 'connect'],
        );

        $this->getBenchmark()->addMethod(
            'codeIgniter',
            [CodeIgniterModel::class, 'complexQuerySelect'],
            [CodeIgniterConnection::class, 'connect'],
        );

        return parent::execute($input, $output);
    }

}
