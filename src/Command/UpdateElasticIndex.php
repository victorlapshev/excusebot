<?php

namespace App\Command;

use App\Entity\Excuse;
use Doctrine\ORM\EntityManagerInterface;
use Elastica\Client;
use Elastica\Document;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateElasticIndex extends Command
{
    protected static $defaultName = 'elastica:update';

    private $em;
    private $elasticaClient;

    public function __construct(EntityManagerInterface $em, Client $elasticaClient)
    {
        $this->em = $em;
        $this->elasticaClient = $elasticaClient;

        parent::__construct();
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $excuseRepo = $this->em->getRepository(Excuse::class);

        $this->elasticaClient->request('excuse', 'DELETE');
        $this->elasticaClient->request('excuse', 'PUT');

        /** @var Excuse $excuse */
        foreach ($excuseRepo->findAll() as $excuse) {
            $document = new Document($excuse->getId(), ['text' => $excuse->getText()]);
            $this->elasticaClient->getIndex('excuse')->addDocument($document);
        }

        $output->writeln('Successfully updated');

        return Command::SUCCESS;
    }
}