<?php

namespace App\Benchmark\Commands;

use App\codeIgniter\CodeIgniterModel;
use App\codeIgniter\CodeIgniterConnection;
use App\laminas\LaminasORM;
use App\laminas\LaminasORMConnection;
use App\laravel\EloquentModelConnection;
use App\laravel\EloquentModel;
use App\symfony\DoctrineEntityManager;
use App\symfony\DoctrineModel;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ORMDelete extends AbstractCommand
{

    protected function configure(): void
    {
        parent::configure();
        $this
            ->setName('ORMDelete')
            ->setDescription('Benchmark the delete of the orm libraries.')
            ->setHelp('this command will provide a table output of the performance of orm layer delete from database')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {

        $this->getBenchmark()->addMethod(
            'doctrine dbal/orm',
            [DoctrineModel::class, 'delete'],
            [DoctrineEntityManager::class, 'prepareForDelete']
        );

        $this->getBenchmark()->addMethod(
            'eloquent',
            [EloquentModel::class, 'delete'],
            [EloquentModelConnection::class, 'prepareForDelete']
        );

        $this->getBenchmark()->addMethod(
            'laminas',
            [LaminasORM::class, 'delete'],
            [LaminasORMConnection::class, 'prepareForDelete']
        );

        $this->getBenchmark()->addMethod(
            'codeIgniter',
            [CodeIgniterModel::class, 'delete'],
            [CodeIgniterConnection::class, 'prepareForORMDelete']
        );

        return parent::execute($input, $output);
    }

}
