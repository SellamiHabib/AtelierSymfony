<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NumberTableController extends AbstractController
{
    #[Route(
        '/numberTable/{size}',
        name: 'app_number_table',
        requirements: ['size'=>"\d+"],
        defaults: ['size'=> 5]
    )]
    public function index($size): Response
    {
        $array = [];
        for ($i = 0; $i <$size;$i++) {
            $array[$i] = random_int(0,1000);
        }
        return $this->render('number_table/index.html.twig', [
            'controller_name' => 'NumberTableController',
            'array'=>$array
        ]);
    }
}
