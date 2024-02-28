<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(ProductRepository $productRepository ): Response
    {
        $product= $productRepository->findBy([],["name" => "ASC"]);
 
        return $this->render('home/index.html.twig', [

            'products' => $product,
        ]);
    }
}
