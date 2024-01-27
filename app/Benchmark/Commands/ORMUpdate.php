<?php

namespace App\Benchmark\Commands;

use App\codeIgniter\CodeIgniterModel;
use App\codeIgniter\CodeIgniterConnection;
use App\laminas\LaminasModel;
use App\laminas\LaminasSqlConnection;
use App\laravel\EloquentModelConnection;
use App\laravel\EloquentModel;
use App\symfony\DoctrineEntityManager;
use App\symfony\DoctrineModel;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ORMUpdate extends AbstractCommand
{

    protected function configure(): void
    {
        parent::configure();
        $this
            ->setName('ORMUpdate')
            ->setDescription('Benchmark the update of the orm libraries.')
            ->setHelp('this command will provide a table output of the performance of orm layer update from database')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {

        $this->getBenchmark()->addMethod(
            'doctrine dbal/orm',
            [DoctrineModel::class, 'update'],
            [DoctrineEntityManager::class, 'connectForUpdate']
        );

        $this->getBenchmark()->addMethod(
            'eloquent',
            [EloquentModel::class, 'update'],
            [EloquentModelConnection::class, 'connectForUpdate']
        );

        $this->getBenchmark()->addMethod(
            'laminas',
            [LaminasModel::class, 'update'],
            [LaminasSqlConnection::class, 'connectForUpdate']
        );

        $this->getBenchmark()->addMethod(
            'codeIgniter',
            [CodeIgniterModel::class, 'update'],
            [CodeIgniterConnection::class, 'connectForORMUpdate']
        );

        return parent::execute($input, $output);
    }

}
