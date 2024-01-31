<?php

namespace App\Benchmark\Commands;

use App\Benchmark\Params;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;

/**
 * HashAlgorithm Command
 *
 * This command benchmarks the performance of MD5 and SHA-1 hash algorithms.
 *
 * Usage:
 * ```
 * php your_script.php hashAlgorithm
 * ```
 */
class HashAlgorithm extends AbstractCommand
{
    protected function configure(): void
    {
        parent::configure();
        $this
            ->setName('hashAlgorithm')
            ->setDescription('Benchmark some hash algorithms performance.')
            ->setHelp('this command will provide...')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {

        $this->getBenchmark()->addMethod(
            'sha1', // method name shown in the results
            function (Params $params) {
                // function that will be benchmarked
                $hash = sha1($params->getParam('stringToBeHashed'));
                return $params;
            },
            function (Params $params) {
                // preparing function that will pass the params to the benchmarked function
                $params->addParam('stringToBeHashed', 'abcdefgh');
                return $params;
            }
        );

        $this->getBenchmark()->addMethod(
            'md5',
            function (Params $params) {
                $hash = md5($params->getParam('stringToBeHashed'));
                return $params;
            },
            function (Params $params) {
                $params->addParam('stringToBeHashed', 'abcdefgh');
                return $params;
            }
        );

        return parent::execute($input, $output);
    }
}