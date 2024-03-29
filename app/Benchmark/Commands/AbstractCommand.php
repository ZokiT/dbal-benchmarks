<?php

namespace App\Benchmark\Commands;

use App\Benchmark\Benchmark;
use Exception;
use Imagick;
use ImagickDraw;
use ImagickPixel;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\StreamOutput;
use Throwable;

abstract class AbstractCommand extends Command
{

    const ITERATIONS_OPTION = 'iterations';
    const IMAGE_OPTION      = 'image';
    const CHART_OPTION      = 'chart';
    const SELECT_LIMIT      = 'selectLimit';

    const FONTS_FOLDER      = './public/fonts/monospace.ttf';
    const CHARTS_FOLDER     = './public/charts';

    // TODO split the whole const and methods for generating images in separate Classes
    const IMAGES_FOLDER                = './public/images/';
    const IMAGES_EXTENSION             = '.png';
    const IMAGES_TABLE_CHARACTER_WIDTH = 10;
    const IMAGES_TABLE_ROW_HEIGHT      = 17;
    const IMAGES_TABLE_MARGINS         = 20;
    const IMAGES_TABLE_PADDINGS        = 10;
    const DEFAULT_ITERATIONS           = 10;

    private Benchmark $benchmark;

    public function __construct(Benchmark $benchmark, string $name = null)
    {
        $this->benchmark = $benchmark;
        parent::__construct($name);
    }

    protected function configure(): void {
        $this
            ->addOption(self::ITERATIONS_OPTION, 'i',  InputOption::VALUE_OPTIONAL, 'number of iterations to perform the benchmarks')
            ->addOption(self::IMAGE_OPTION, 'img', InputOption::VALUE_NEGATABLE, 'export the result to image')
            ->addOption(self::CHART_OPTION, 'c', InputOption::VALUE_NEGATABLE, 'store the results in local file for graph usage')
            ->addOption(self::SELECT_LIMIT, 'l', InputOption::VALUE_OPTIONAL, 'add limit for select queries');
    }

    protected function outputResults(OutputInterface $output): void {

        $this->prepareAndRenderTableResults($output);

        if ($this->getBenchmark()->getStoreToFile()) {

            if (!file_exists(self::CHARTS_FOLDER)){
                mkdir(self::CHARTS_FOLDER);
            }

            $filePath = self::CHARTS_FOLDER . "/{$this->getName()}.json";
            if (file_exists($filePath)) {
                $fileContent = file_get_contents($filePath);
                $fileContent = json_encode(array_merge(
                    [[
                        "result" => $this->getBenchmark()->getResults(),
                        "iterations" => $this->getBenchmark()->getIterations(),
                        "title" => $this->getName(),
                        "selectLimit" => $this->getBenchmark()->getSelectLimit()
                    ]],
                    json_decode($fileContent, true))
                );
                unlink($filePath);
                file_put_contents($filePath, $fileContent);
            } else {
                file_put_contents($filePath, json_encode(
                    [[
                        "result" => $this->getBenchmark()->getResults(),
                        "iterations" => $this->getBenchmark()->getIterations(),
                        "title" => $this->getName(),
                        "selectLimit" => $this->getBenchmark()->getSelectLimit()
                    ]]
                ));
            }
        }

        if ($this->getBenchmark()->getOutputToImage()) {

            if (!file_exists(self::IMAGES_FOLDER)){
                mkdir(self::IMAGES_FOLDER);
            }

            try {
                $this->generateImage($this->getOutputResultsAsString());
                $output->writeln("Result was generated in image");
            } catch (Throwable $throwable) {
                $output->writeln($throwable->getMessage());
            }
        }
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $iterations = (int)$input->getOption(self::ITERATIONS_OPTION);
        $this->getBenchmark()->setOutputToImage($input->getOption(self::IMAGE_OPTION) ?? false);
        $this->getBenchmark()->setStoreToFile($input->getOption(self::CHART_OPTION) ?? false);
        $limit = $input->getOption(self::SELECT_LIMIT);
        $this->getBenchmark()->setUseLimit(($limit !== null && (int)$limit > 0));
        if ($this->getBenchmark()->getUseLimit()) {
            $iterationsWithSelectLimit = $iterations > 0 ? $iterations : self::DEFAULT_ITERATIONS;
            $output->writeln('Option to use select limit activated, the iterations will be set to: ' . $iterationsWithSelectLimit);
            $this->getBenchmark()->setSelectLimit((int)$limit);
            $this->getBenchmark()->setIterations($iterationsWithSelectLimit);
        } else {
            $this->getBenchmark()->setIterations($iterations);
        }

        $this->getBenchmark()->run($output);
        $this->outputResults($output);

        return Command::SUCCESS;
    }

    public function getBenchmark(): Benchmark
    {
        return $this->benchmark;
    }

    private function prepareAndRenderTableResults(OutputInterface $output)
    {
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
            ->setHeaders($this->getBenchmark()->getHeaders())
            ->setRows($rows)
        ;

        // Render the table to the StreamOutput
        $table->render();
    }

    /**
     * @throws Exception
     */
    private function generateImage(bool|string $tableString)
    {
        if (is_bool($tableString)) {
            throw new Exception('Unable to export the result to image');
        }

        $splitTableStringRows = explode("\n", $tableString);

        // Filter the table results generated by Symphony from ANSI formats and codes
        $filteredSplitTableStringRows = array_map(function (string $inputString) {
            return preg_replace('/\033\[[0-9;]*[mGK]/', '', $inputString);
        }, $splitTableStringRows);

        // Create a new Imagick instance
        $image = new Imagick();

        // Create an ImagickDraw object
        $draw = new ImagickDraw();

        // Set font properties
        $draw->setFont(self::FONTS_FOLDER);
        $draw->setFontSize(12);
        $draw->setFillColor(new ImagickPixel('black'));

        // Calculate total width and height for the image
        $totalWidth = strlen($filteredSplitTableStringRows[0]) * self::IMAGES_TABLE_CHARACTER_WIDTH;
        $totalHeight = count($filteredSplitTableStringRows) * self::IMAGES_TABLE_ROW_HEIGHT;

        // Set image dimensions based on calculated values
        $image->newImage($totalWidth + self::IMAGES_TABLE_MARGINS, $totalHeight + self::IMAGES_TABLE_MARGINS, new ImagickPixel('white'));

        // Set initial position
        $y = self::IMAGES_TABLE_PADDINGS;
        $x = self::IMAGES_TABLE_PADDINGS;

        // Set cell height
        $cellHeight = self::IMAGES_TABLE_ROW_HEIGHT;

        foreach ($filteredSplitTableStringRows as $row) {
            $this->drawText($image, $draw, $x, $y, $totalWidth, $cellHeight, $row);
            $y += $cellHeight;
        }

        // Save the image to a file
        $image->writeImage(self::IMAGES_FOLDER . $this->getName() . date('Ymd_His') . self::IMAGES_EXTENSION);

        // Free up memory
        $image->clear();
        $image->destroy();
    }

    private function drawText($image, $draw, $x, $y, $width, $height, $text)
    {
        $fontMetrics = $image->queryFontMetrics($draw, $text);
        $textX = $x + ($width - $fontMetrics['textWidth']) / 2;
        $textY = $y + ($height + $fontMetrics['textHeight']) / 2;

        $image->annotateImage($draw, $textX, $textY, 0, $text);
    }

    private function getOutputResultsAsString(): bool|string
    {
        // Create a StreamOutput to capture the output
        $stream = fopen('php://temp', 'w+');
        $streamOutput = new StreamOutput($stream);

        $this->prepareAndRenderTableResults($streamOutput);

        // Get the content of the StreamOutput and close the stream
        rewind($stream);
        $tableString = stream_get_contents($stream);
        fclose($stream);

        return $tableString;
    }
}