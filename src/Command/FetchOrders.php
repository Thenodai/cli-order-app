<?php
declare(strict_types=1);

namespace App\Command;

use App\Db\Connection;
use App\Normalizer\OrderNormalizer;
use App\Writer\CsvWriter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class FetchOrders extends Command
{
    private $connection;
    private $normalizer;
    private $writer;

    public function __construct($name = null)
    {
        parent::__construct($name);
        $this->connection = new Connection();
        $this->normalizer = new OrderNormalizer();
        $this->writer = new CsvWriter();
    }

    public function configure()
    {
        $this->setName('fetch-orders')
            ->addOption('export', null, InputOption::VALUE_OPTIONAL, 'use if export wanted.', false);
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $orders = array_map(
            function ($order) {
                return $this->normalizer->mapToArray($order);
            },
            $this->connection->findAll()
        );

        if ($input->getOption('export') !== null) {
            for ($i = 0; $i <= count($orders) - 1; $i++) {
                $output->writeln($orders[$i]);
            }
            return;
        }

        $finished = $this->writer->write($orders);
        if ($finished) {
            $output->writeln('csv file exported');
            return;
        }
    }
}

