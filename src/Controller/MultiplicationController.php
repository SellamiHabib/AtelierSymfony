<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MultiplicationController extends AbstractController
{

    #[Route(
        'multi/{int1}/{int2}',
        name: 'multiplication',
        requirements: [
            'int1' => '\d+',
            'int2' => '\d+',]
    )]
    public function multiplication($int1, $int2): Response {
        $result = $int1 * $int2;
        return new Response("<h1>$result</h1>");
    }
}
