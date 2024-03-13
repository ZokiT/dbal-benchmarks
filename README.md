# Benchmark tool for DBAL performance of most used PHP libraries

## Introduction
This tool is ready to benchmark and compare the database abstraction layers (DBAL) of prominent PHP frameworks in order to provide a comprehensive analysis of their average time usage,
memory consumption and performance. Through a systematic evaluation of widely used frameworks such as Laravel, Symfony, Laminas and CodeIgniter we will provide some results of the DBAL CURL and other operations. By seeing the differences between these frameworks, this research will help developers
to make informed decisions when choosing a PHP framework based on their specific database requirements.

The tool uses php 8.1, docker containers, pgsql database, included DBAL and ORM libraries with the mentioned frameworks above with composer, Symphony Console for CLI scripts and more.

## Table of Contents

- [Installation](#installation)
- [Configuration](#configuration)
- [Options](#options)
- [Custom benchmarks](#customization)

## Installation

`git clone https://github.com/ZokiT/dbal-benchmarks.git`

[docker](https://docs.docker.com/engine/install) is required to use the bash script which will prepare everything for you, as well for a better distribution of the project among other devices

without docker, you need to ensure that you have installed and enabled required extensions and tools:
php 8.1, composer, pgsql, pdo, pdo_pgsql and already created database. Change the database configuration, migrate, and you should be able to run the benchmarks commands:

```
cd /path/to/dbal-benchmarks
composer install
php cli.php list
```

### Windows
#### With bash
install [Git-bash](https://git-scm.com) if you want to start the project with the bash script

```bash
cd /path/to/dbal-benchmarks
./chiron.sh init 
php cli.php list
```
#### Without bash
```bash
cd /path/to/dbal-benchmarks
docker-compose up -d
docker exec -it app-dbal-benchmarks sh
composer install
docker exec -it db-dbal-benchmarks sh
psql -U user -d dbal_benchmarks -f /var/lib/pgsql/migrations.sql
php cli.php list
```

### Linux

```
cd /path/to/dbal-benchmarks
sh chiron.sh init
php cli.php list
```
## Configuration

If you need to change the database configuration for this project, you can do so by modifying the constants in the `app/DatabaseConfiguration.php` file. The file contains the following constants related to the database connection:

```php
    const HOST                = 'db';
    const USER                = 'user';
    const PASSWORD            = 'password';
    const DATABASE            = 'dbal_benchmarks';
    const DRIVER              = 'pgsql';
    const PORT                = 5432;

    /** CodeIgniter requires a different driver name for the database connection, refer to the documentation for more details */
    const CODE_IGNITER_DRIVER = 'Postgre';
```

## Options
There are three options you may provide along with the commands:

`-i [number]` - as number of iterations

`--graph`     - to save the results to a (for now) json file (public/charts) and show the results as Chart on localhost, to show the charts you need to include the file in the index.html files const

`--image`     - to store the results as image (public/images)

`-l [number]` - limit parameter for the select queries


## Customization

### Hash Algorithm Benchmarks example

The Hash Algorithm Benchmarks showcase a straightforward approach to integrate new benchmarks into your application. In this example, we'll focus on benchmarking various hash algorithms.

1. **Create a new class named `HashAlgorithm` within the `app/Benchmark/Commands` directory.**
    - Extend the `AbstractCommand` class.
    - Define the `configure` method to set the command name, description, and help message.
    - Implement the `execute` method to conduct iterations for benchmarking specified hash algorithms.

   ```php
   class HashAlgorithm extends AbstractCommand
   {
       protected function configure(): void
       {
           parent::configure();
           $this
               ->setName('hashAlgorithm')
               ->setDescription('Benchmark some hash algorithms performance.')
               ->setHelp('This command will provide...');
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
   ```

Within the `execute` method, use the `addMethod` function to add benchmarks for each hash algorithm you wish to evaluate. The callback functions provided to `addMethod` should contain the actual code to be benchmarked.

2. **Register the Command.**

    In the app/cli.php file, register the new HashAlgorithm command. This ensures that the benchmarking functionality is accessible via the command-line interface.

    ```php
        use App\Benchmark\Commands\HashAlgorithm;
        
        $application = new Application();
        $application->add(new HashAlgorithm());
        $application->run();
    ```

3. **Usage**

    Run the benchmark command in your terminal:
    ```bash
    php cli.php hashAlgorithm
    ```

    This will execute the benchmark for the specified hash algorithms and provide performance insights.
    
    ```plaintext
   +--------+---------------------- hashAlgorithm ---------+------------+--------+
   | Method | Avg. Execution Time (ms) | Memory Usage (KB) | Iterations | Misses |
   +--------+--------------------------+-------------------+------------+--------+
   | sha1   | 0.000926                 | 2.015625          | 1000       | 0      |
   +--------+--------------------------+-------------------+------------+--------+
   | md5    | 0.000949                 | 0.421875          | 1000       | 0      |
   +--------+--------------------------+-------------------+------------+--------+
    ```

Feel free to extend this pattern to add more benchmarks as needed, and leverage the flexibility to include setup callbacks for additional configurations.
