<?php
declare(strict_types=1);

namespace App\Command;

use App\Db\Connection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DeleteOrder extends Command
{
    private $connection;

    public function __construct($name = null)
    {
        parent::__construct($name);
        $this->connection = new Connection();
    }

    public function configure()
    {
        $this->setName('delete-order')
             ->addArgument('order_id', InputArgument::REQUIRED, 'Order to delete')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $id = $input->getArgument('order_id');
        try {
            $this->connection->deleteOrder($id);
            $output->writeln(sprintf('order deleted: %s', $id));
        } catch (\Throwable $exception) {
            $output->writeln(sprintf('order could not be deleted: %s', $id));
        }
    }
}
