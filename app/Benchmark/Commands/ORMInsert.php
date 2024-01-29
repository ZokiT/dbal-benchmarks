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

class ORMInsert extends AbstractCommand
{

    protected function configure(): void
    {
        parent::configure();
        $this
            ->setName('ORMInserts')
            ->setDescription('Benchmark the inserts of the orm libraries.')
            ->setHelp('this command will provide a table output of the performance of orm layer inserts in to the database')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {

        $this->getBenchmark()->addMethod(
            'doctrine model insert',
            [DoctrineModel::class, 'insert'],
            [DoctrineEntityManager::class, 'connect']
        );

        $this->getBenchmark()->addMethod(
            'eloquent model insert',
            [EloquentModel::class, 'insert'],
            [EloquentModelConnection::class, 'connect']
        );

        $this->getBenchmark()->addMethod(
            'laminas model insert',
            [LaminasModel::class, 'insert'],
            [LaminasSqlConnection::class, 'connect'],
            function (\Throwable $e) {
                var_dump($e->getMessage());
            }
        );

        $this->getBenchmark()->addMethod(
            'codeIgniter model insert',
            [CodeIgniterModel::class, 'insert'],
            [CodeIgniterConnection::class, 'connect'],
            function (\Throwable $e) {
                var_dump($e->getMessage());
            }
        );

        return parent::execute($input, $output);
    }

}
