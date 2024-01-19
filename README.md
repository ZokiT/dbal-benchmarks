# Benchmark tool for DBAL performance of most used PHP libraries

## Introduction
This tool is ready to benchmark and compare the database abstraction layers (DBAL) of prominent PHP frameworks in order to provide a comprehensive analysis of their average time usage,
memory consumption and performance. Through a systematic evaluation of widely used frameworks such as Laravel, Symfony, Laminas and CodeIgniter we will provide some results of the DBAL CURL and other operations. By seeing the differences between these frameworks, this research will help developers
to make informed decisions when choosing a PHP framework based on their specific database requirements.

The tool uses php 8.1, docker containers, pgsql database, included DBAL and ORM libraries with the mentioned frameworks above with composer, Symphony Console for CLI scripts and more.

## Table of Contents

- [Installation](#installation)
- [Configuration](#configuration)
- [Custom benchmarks](#Create new Benchmark Class)

## Installation

`git clone https://github.com/example/example.git`

[docker](https://docs.docker.com/engine/install) is required to use the bash script which will prepare everything for you, as well for a better distribution of the project among other devices

without docker, you need to ensure that you have installed and enabled required extensions and tools:
php 8.1, composer, pgsql, pdo, pdo_pgsql and already created database. Change the database configuration, and you should be able to run the benchmarks commands:

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
php cli.php list
```

### Linux

```
cd /path/to/dbal-benchmarks
sh chiron.sh init
```
## Configuration

If you need to change the database configuration for this project, you can do so by modifying the constants in the `app/DatabaseConfiguration.php` file. The file contains the following constants related to the database connection:

```php
    const HOST                = '192.168.99.106';
    const USER                = 'user';
    const PASSWORD            = 'password';
    const DATABASE            = 'dbal_benchmarks';
    const DRIVER              = 'pgsql';
    const PORT                = 5432;

    /** CodeIgniter requires a different driver name for the database connection, refer to the documentation for more details */
    const CODE_IGNITER_DRIVER = 'Postgre';
```

## Create new Benchmark Class 

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
           // Example with setUp used, preparing data for the benchmark
           $this->getBenchmark()->addMethod(
               'md5',
               function (string $string) {
                   $hash = md5($string); // actual benchmark callback, uses what is returned in setUp callback
               },
               function () {
                   return 'abcdefgh'; // setUp callback, returned value is used in actual benchmark callback
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
   ```

#### Configure Benchmarks

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
    +--------+------------------- hashAlgorithm ------------+------+--------+
    | Method | Avg. Execution Time (ms) | Memory Usage (KB) | Hits | Misses |
    +--------+--------------------------+-------------------+------+--------+
    | md5    | 0.000609                 | 0                 | 1000 | 0      |
    +--------+--------------------------+-------------------+------+--------+
    | sha1   | 0.000711                 | 0                 | 1000 | 0      |
    +--------+--------------------------+-------------------+------+--------+
    ```

Feel free to extend this pattern to add more benchmarks as needed, and leverage the flexibility to include setup callbacks for additional configurations.
