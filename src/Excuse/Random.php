<?php

namespace App\Excuse;

use App\Entity\Excuse;
use Doctrine\ORM\EntityManagerInterface;

class Random
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function get(): string
    {
        return $this->entityManager->getRepository(Excuse::class)->findRandomOne()->getText();
    }
}
