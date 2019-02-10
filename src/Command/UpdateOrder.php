<?php
declare(strict_types=1);

namespace App\Command;

use App\Db\Connection;
use App\Entity\Order;
use App\Validator\OrderValidator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;

class UpdateOrder extends Command
{
    private $connection;
    private $validator;

    public function __construct($name = null)
    {
        parent::__construct($name);
        $this->connection = new Connection();
        $this->validator = new OrderValidator();
    }

    public function configure()
    {
        $this->setName('update-order')
            ->addArgument('identifier', InputArgument::REQUIRED, 'your order id.');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $order = $this->connection->findById($input->getArgument('identifier'));
        $helper = $this->getHelper('question');
        $key = $helper->ask(
            $input,
            $output,
            new ChoiceQuestion('what would you like to change? ', ['Email', 'Meal', 'Comment', 'Date'])
        );
        $value = $helper->ask(
            $input,
            $output,
            $newQuestion = new Question(sprintf('Please enter new %s? ', $key))
        );
        /** @var Order $newOrder */
        $newOrder = clone $order;
        $newOrder->{'set' . $key}($value);
        if ($violations = $this->validator->validate($newOrder)) {
            return $output->writeln(sprintf('Violations found: %s', implode(', ', $violations)));
        }
        $outcome = $this->connection->update($newOrder);
        if ($outcome) {
            $output->writeln(sprintf('order updated successfully. id: %s', $newOrder->getId()));
        }
    }
}
