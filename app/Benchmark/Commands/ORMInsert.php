<?php

namespace App\Benchmark\Commands;

use App\codeIgniter\CodeIgniterModelInsert;
use App\codeIgniter\CodeIgniterConnection;
use App\laminas\LaminasModelInsert;
use App\laminas\LaminasSqlConnection;
use App\laravel\EloquentModelConnection;
use App\laravel\EloquentModelInsert;
use App\symfony\DoctrineEntityManager;
use App\symfony\DoctrineModelInsert;
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
            [DoctrineModelInsert::class, 'insert'],
            [DoctrineEntityManager::class, 'connect']
        );

        $this->getBenchmark()->addMethod(
            'eloquent model insert',
            [EloquentModelInsert::class, 'insert'],
            [EloquentModelConnection::class, 'connect']
        );

        $this->getBenchmark()->addMethod(
            'laminas model insert',
            [LaminasModelInsert::class, 'insert'],
            [LaminasSqlConnection::class, 'connect'],
            function (\Throwable $trowable) {
                var_dump($trowable->getMessage() . " " . $trowable->getFile() . " " . $trowable->getLine());
            }
        );

        $this->getBenchmark()->addMethod(
            'codeIgniter model insert',
            [CodeIgniterModelInsert::class, 'insert'],
            [CodeIgniterConnection::class, 'connect']
        );

        return parent::execute($input, $output);
    }

}
