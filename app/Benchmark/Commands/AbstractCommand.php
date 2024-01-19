<?php

namespace App\Benchmark\Commands;

use App\Benchmark\Benchmark;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractCommand extends Command
{

    const ITERATIONS_OPTION = 'iterations';

    private Benchmark $benchmark;

    public function __construct(Benchmark $benchmark, string $name = null)
    {
        $this->benchmark = $benchmark;
        parent::__construct($name);
        // add command name as graph text
    }

    protected function configure(): void {
        $this
            ->addOption(self::ITERATIONS_OPTION, 'i',  InputArgument::OPTIONAL, 'number of iterations to perform the benchmarks');
    }

    protected function outputResults(OutputInterface $output): void {

        //TODO store to local file

        $table = new Table($output);
        $table->setHeaderTitle($this->getName());

        $rows = [];
        foreach ($array = $this->getBenchmark()->getResults() as $result) {
            $rows[] = $result;
            if ($result !== end($array)) {
                $rows[] = new TableSeparator();
            }
        }

        $table
            ->setHeaders($this->getBenchmark()::TABLE_HEADERS)
            ->setRows($rows)
        ;

        $table->render();
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->getBenchmark()->setIterations((int)$input->getOption(self::ITERATIONS_OPTION));

        $this->getBenchmark()->run();
        $this->outputResults($output);

        return Command::SUCCESS;
    }

    public function getBenchmark(): Benchmark
    {
        return $this->benchmark;
    }
}