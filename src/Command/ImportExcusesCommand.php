<?php

namespace App\Command;

use App\Entity\Excuse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportExcusesCommand extends Command
{
    protected static $defaultName = 'excuse:import';

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();
        $this->em = $em;
    }

    protected function configure()
    {
        $this
            ->setDescription('Bulk import excuses from txt file')
            ->addArgument('path', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filePath = $input->getArgument('path');

        if (!file_exists($filePath)) {
            $output->writeln('Cant find file');

            return Command::FAILURE;
        }

        $file = fopen($filePath, 'r');

        $count = 0;

        while ($line = fgets($file)) {
            $line = trim($line);

            $excuse = new Excuse();
            $excuse->setText($line);
            $this->em->persist($excuse);
            ++$count;
        }

        $this->em->flush();
        $output->writeln(sprintf('%d excuses has been added', $count));

        return Command::SUCCESS;
    }
}
