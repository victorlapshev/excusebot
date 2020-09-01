<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class Welcome extends AbstractController
{
    public function index()
    {
        return new Response('Excuses bot welcome page');
    }
}
