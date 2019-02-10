<?php
declare(strict_types=1);

namespace App\Command;

use App\Db\Connection;
use App\Normalizer\OrderNormalizer;
use App\Reader\CsvReader;
use App\Validator\OrderValidator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class PlaceOrder extends Command
{
    private $connection;
    private $validator;
    private $normalizer;
    private $reader;

    public function __construct($name = null)
    {
        parent::__construct($name);
        $this->connection = new Connection();
        $this->validator = new OrderValidator();
        $this->normalizer = new OrderNormalizer();
        $this->reader = new CsvReader();
    }

    public function configure()
    {
        $this->setName('place-order')
            ->addArgument('csv', InputArgument::OPTIONAL, '/path/to/csv/file');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        if ($file = $input->getArgument('csv')) {
            try {
                $data = $this->reader->read($file);
            } catch (\Exception $exception) {
                throw new \Exception(sprintf('%s', $exception->getMessage()));
            }
        } else {
            /** @var HelperSet $helper */
            $helper = $this->getHelper('question');
            $data = [];
            foreach ($this->getQuestions() as $question) {
                $data['order'][] = $helper->ask($input, $output, $question);
            }
        }
        foreach ($data as $datum) {
            $order = $this->normalizer->mapToEntity($datum);
            if ($violations = $this->validator->validate($order)) {
                throw new \Exception(sprintf('Violations found: %s', implode(', ', $violations)));
            }
            try {
                $outcome = $this->connection->save($order);
                if ($outcome) {
                    $output->writeln(sprintf('order placed successfully. id: %s', $outcome));
                }
            } catch (\Throwable $exception) {
                return $output->writeln(sprintf('something went wrong: %s', $exception->getMessage()));
            }
        }
    }

    /**
     * @return Question[]
     */
    private function getQuestions()
    {
        return [
            new Question('please enter your email? ', ''),
            new Question('What meal would you like? ', ''),
            new Question('any comments? ', ''),
            new Question('when should we expect you(year-month-day)? ', ''),
        ];
    }
}
