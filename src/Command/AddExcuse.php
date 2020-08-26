<?php

namespace App\Command;

use App\Entity\Excuse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class AddExcuse extends Command
{
    protected static $defaultName = 'excuse:add';

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();
        $this->em = $em;
    }

    protected function configure()
    {
        $this
            ->setDescription('Add excuse to database')
            ->addArgument('text', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $excuse = new Excuse();
        $excuse->setText($input->getArgument('text'));

        $this->em->persist($excuse);
        $this->em->flush();

        $output->writeln('Excuse has been added');

        return Command::SUCCESS;
    }
}