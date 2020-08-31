<?php

namespace App\Controller;

use Elastica\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class Welcome extends AbstractController
{
    public function index(Client $client)
    {
        return new Response('Yo');
    }
}
