<?php

namespace App\Controller;

use App\Entity\Excuse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class ExcuseController extends AbstractController
{
    public function search(string $query, EntityManagerInterface $entityManager)
    {
        $result = [];

        $repo = $entityManager->getRepository(Excuse::class);

        foreach ($repo->findByText($query) as $excuse) {
            $result[] = [
                'text' => $excuse->getText(),
            ];
        }

        return new JsonResponse($result);
    }
}