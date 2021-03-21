<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class ActivityController
{
    /**
     * @Route("/activities", name="activities_list")
     */
    public function list(Environment $twig)
    {
        return new Response($twig->render('article/list.html.twig', []));
    }
}
