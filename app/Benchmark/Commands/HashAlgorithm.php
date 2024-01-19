<?php

namespace App\Benchmark\Commands;

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
            'md5',
            function (string $string) {
                $hash = md5($string);
            },
            function () {
                return 'abcdefgh';
            }
        );

        $this->getBenchmark()->addMethod(
            'sha1',
            function () {
                $string = 'abcdefgh';
                $hash = sha1($string);
            }
        );

        return parent::execute($input, $output);
    }
}