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

        foreach ($repo->searchIndex($query) as $item) {
            $result[] = [
                'text' => $item->getHighlights()['text'][0]
            ];
        }

        $response = new JsonResponse($result);
        $response->setEncodingOptions(JSON_UNESCAPED_UNICODE);

        return $response;
    }
}
