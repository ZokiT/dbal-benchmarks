<?php
require __DIR__.'/vendor/autoload.php';

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
try {

$application = new Application();

// ... register commands
//$application->add(new GenerateAdminCommand());
$application->register('test')
    ->addArgument('n', InputArgument::OPTIONAL)
    ->setCode(function (InputInterface $input, OutputInterface $output): int {
        $table = new Table($output);
//        $table->setHeaderTitle('Books');
        $table
            ->setHeaders(['ISBN', 'Title', 'Author'])
            ->setRows([
                ['99921-58-10-7', 'Divine Comedy', 'Dante Alighieri'],
                ['9971-5-0210-0', 'A Tale of Two Cities', 'Charles Dickens'],
                ['960-425-059-0', 'The Lord of the Rings', 'J. R. R. Tolkien'],
                ['80-902734-1-6', 'And Then There Were None', 'Agatha Christie'],
            ])
        ;
        $table->render();

        return Command::SUCCESS;
    });

    $application->run();
} catch (Exception $e) {
    echo "{$e->getMessage()}";
}